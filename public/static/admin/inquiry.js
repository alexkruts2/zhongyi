var guahaoTable,inquiryTable,select_treat_id;
var recorder;
var video='';
var annotationNumber=0;
var jsonQuestion = '';
var record_state = '';

function drawGuahaoTable() {
    if(guahaoTable)
        guahaoTable.destroy();
    guahaoTable = $('#tbl_guahao').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'guahao_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "bFilter": false,
        info: false,
        select: true,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/inquiry/getGuahao',
            "dataType":"json"
        },
        columns: [
            {data: 'guahao'},
            {data: 'patient_name'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [0],
                'orderable': false,
                "mRender": function (data, type, full) {
                    switch (data) {
                        case 'ACCEPT':
                            return '挂号'
                            break;
                        case "ACCEPT_PAY":
                            return '挂号付费'
                            break;
                        case "WAITING_TREATMENT":
                            return '等待问诊'
                            break;
                        case "TREATING":
                            return '在问诊中'
                            break;
                        case "BEFORE_TREATING_PAY":
                            return '完成问诊'
                            break;
                        case "AFTER_TREATING_PAY":
                            return '支付药费'
                            break;
                        case "BEFORE_MEDICINE":
                            return '等待抓药'
                            break;
                        case "CLOSE":
                            return '回家'
                            break;
                        default:
                            return data;
                    }
                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback":function(settings){
                $( "#tbl_guahao tbody tr:first-child" ).trigger('click');
        }

        });
}
function drawInquiryTable(treat_id){
    if(inquiryTable)
        inquiryTable.destroy();
    inquiryTable = $('#tbl_inquiry').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'treat_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "bFilter": false,
        info: false,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/inquiry/getTreatementData',
            'data':{id:treat_id},
            "dataType":"json"
        },
        columns: [
            {data: 'patient_name'},
            {data: 'disease_name'},
            {data: 'number'},
            {data: 'date'},
            {data: 'recipe'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
        {
            "aTargets": [3],
            'orderable': false,
            "mRender": function (data, type, full) {
                return data==null||data==''||data==undefined?data:data.substring(0, 10);;
            }
        },{
            "aTargets": [5],
            'orderable': false,
            "mRender": function (data, type, full) {
                return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/history/detail/' + data+ '\'"><i class="ti-pencil-alt"></i>详情</button>';

            }
        },
        {"className": "text-center", "targets": "_all"}
    ],
    "drawCallback":function(settings){
        $('#tbl_guahao tbody').on( 'click', 'tr', function () {
            guahaoTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        });
    }

});

}
$(function () {
    if($( "#tbl_guahao" ).length)
        drawGuahaoTable();
    if(record_state==''&&$("#myVideo").length){
        Swal.fire({
            type: 'warning',
            title: '请开始录制视频。',
            showConfirmButton: false,
            timer: 3000
        });
    }

    if($(".select2").length)
        $(".select2").select2({
            placeholder:"请选择标题"
        });
    if($("#myVideo").length)
        startVideoCamera();
    $("#question_title").on("change",function(e){
        var data = $(this).val();
        if(data==''||data==null||data==undefined)
            return;
        showOverlay();
        $.ajax({
            url: '/doctor/inquiry/getRecipe',
            data: 'question_id='+data,
            type: 'POST',
            cache: false,
            dataType: 'json',
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    drawSlide(JSON.parse(resp.data.question.questions));
                    drawRecipe(resp.data.recipe);
                    jsonQuestion = JSON.parse(resp.data.question.questions);
                    $("#question_string").val(resp.data.question.questions);
                    $("#recipe").trigger("change");
                } else {
                    hideOverlay();
                    Swal.fire({
                        type: 'error',
                        text: resp.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            error: function (e) {
                hideOverlay();
                Swal.fire({
                    type: 'error',
                    text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });


    $("#recipe").on("change",function (e) {
        $("#medicineSection").html("");
        var medicines =  $("#recipe option:selected").data("medicine");
        for(var i=0; i<medicines.length;i++){
            addMedicine(medicines[i]);
        }
        calcPrice();
        changeMaxMinValue();
    });
    $("#question_title").trigger("change");

    appendAnnotation();
    if($( "#tbl_guahao" ).length){
        setInterval( function () {
            guahaoTable.ajax.reload();
        }, 120000 );
    }


    $('#tbl_guahao tbody').on( 'click', 'tr', function () {
        guahaoTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        select_treat_id = $(this).attr('id')==undefined?'':$(this).attr('id').replace('guahao_','');
        drawInquiryTable(select_treat_id);

        var rowIndex = guahaoTable.row(this).index();
        var rowData = guahaoTable.rows( rowIndex ).data()[0];
        if(rowData!=undefined&&rowData!=null&&rowData!=''){
            $("#patient_name").val(rowData.patient_name);
            $("#ID_Number").val(rowData.ID_Number);
        }
    });

    scrollSidebarTop();
});

function createInquiry() {
    if(select_treat_id==''||select_treat_id==undefined||select_treat_id==null)
        return;
    window.location.href = '/doctor/inquiry/create/'+select_treat_id;
}

function startVideoCamera(){
    var videoMaxLengthInSeconds = 3600;

    // Inialize the video player
    var player = videojs("myVideo", {
        controls: true,
        width: 1440,
        height: 960,
        fluid: false,
        autoplay:true,
        plugins: {
            record: {
                audio: false,
                video: true,
                maxLength: videoMaxLengthInSeconds,
                debug: true,
                videoMimeType: "video/webm;codecs=H264"
            }
        }
    }, function(){
        videojs.log(
            'Using video.js', videojs.VERSION,
            'with videojs-record', videojs.getPluginVersion('record'),
            'and recordrtc', RecordRTC.version
        );
    });
    player.on('deviceError', function() {
        console.log('device error:', player.deviceErrorCode);
    });
    player.on('error', function(error) {
        console.log('error:', error);
    });
    player.on('startRecord', function() {
        var guahao = $("#guahao").val();
        showOverlay();
        $.ajax({
            url: '/doctor/inquiry/startTreatment',
            data: 'guahao='+guahao,
            type: 'GET',
            cache: false,
            dataType: 'json',
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    console.log(resp.data);
                    record_state = 'onRecording';
                } else {
                    Swal.fire({
                        type: 'error',
                        text: resp.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            error: function (e) {
                hideOverlay();
                Swal.fire({
                    type: 'error',
                    text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });
    // user completed recording and stream is available
    // Upload the Blob to your server or download it locally !
    player.on('finishRecord', function() {
        record_state = 'endRecording';
        showOverlay();
        var videoBlob = player.recordedData;
        var formData = new FormData();
        formData.append('video', videoBlob);
        xhr('/doctor/inquiry/uploadVideo', formData, function (fName) {
            hideOverlay();
            video = fName.data;
            $("#video_url").val(fName.data);
        });
    });
    player.on('ready',function () {
        console.log('player is ready');
        $(".vjs-record.vjs-control").trigger('click');
    });
    player.play();
}

function xhr(url, data, callback) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            callback(JSON.parse(request.responseText));
        }
    };
    request.open('POST', url);
    request.send(data);
}
function appendAnnotation() {
    annotationNumber++;
    var html ="<div class=\"form-group mt-3 row\">\n" +
        "\t<div class=\"col-2 text-right\">\n" +
        "\t\t<button type=\"button\" class=\"btn btn-default\" title=\"删除\" data-index=\""+annotationNumber+"\" onclick=\"removeAnnotation(this);\"><i class=\"fas fa-times\"></i> </button>\n" +
        "\t</div>\n" +
        "\t<div class=\"col-3\">\n" +
        "\t\t<input class=\"form-control\" type=\"text\" name='annotation_key[]' id=\"annotation_key"+annotationNumber+"\">\n" +
        "\t</div>\n" +
        "\t<div class=\"col-3\">\n" +
        "\t\t<input class=\"form-control\" type=\"text\"  name='annotation_value[]' id=\"annotation_value"+annotationNumber+"\">\n" +
        "\t</div>\n" +
        "</div>\n"
    $("#annotationSection").append(html);
}

function removeAnnotation(obj) {
    annotationNumber--;
    var selectNumber = $(obj).data("index");
    var row = $(obj).parent().parent();
    row.remove();
    for(var i=selectNumber+1; i< annotationNumber+2; i++){
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","annotation_key"+(i-1));
        $('*[data-index="'+i+'"]').parent().next().next().first().children().attr("id","annotation_value"+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }

}

function drawRecipe(recipes){
    var html = '';
    for(var i=0; i < recipes.length; i++){
        html +='<option value="'+recipes[i].id+'" data-medicine=\''+recipes[i].medicine+'\'>'+recipes[i].prescription_name+'</option>';
    }
    $("#recipe").html(html);
}

function addMedicine(medicine) {
    selectedMedicine_id = medicine.medicine_id;
    selectedMedicine_name = medicine.medicine;
    if(selectedMedicine_id<1) return;
    var min_weight =  medicine.min_weight;
    var max_weight =  medicine.max_weight;
    var weight = medicine.weight==undefined?0:medicine.weight;
    var price = medicine.price;

    var html="<div class=\"row\">\n" +
        "    <label class=\"col-2 col-form-label text-right\">\n" +
        "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicine(this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+"<input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
        "    <div class=\"col-3\">\n" +
        "        <input class=\"form-control\" type=\"number\" value=\""+weight+"\" name=\"mass[]\" onchange='calcPrice()' max='"+max_weight+"' min='"+min_weight+"' id=\"weight"+selectedMedicine_id+"\">\n" +
        "    </div>\n" +
        "<div class=\"col-3 text-left\">\n" +
        "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" 元/10g (最小："+min_weight+", 最大："+ max_weight+") </label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
        "</div>\n"+
        "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
        "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
        "</div>\n"
    $("#medicineSection").append(html);
    $("#medicine option[value='"+selectedMedicine_id+"']").attr("disabled","disabled");
    $("#medicine").prop("selectedIndex",-1);
}
function drawSlide(questions) {
    var html = '';
    var indicateHtml = '';
    for (var i = 0; i < questions.length; i++) {
        activeClass = i == 0 ? 'active' : '';
        html += '<div class="carousel-item ' + activeClass + '">\n' +
            '\t<div class="overlaybg"><img src="/img/photos/white.png" class="img-fluid"></div>\n' +
            '\t<div class="news-content carousel-caption text-dark">\n' +
            '\t\t<h4 class="text-dark">' + questions[i].question + '</h4>\n' +
            '\t\t<p>&nbsp;</p>\n';
        for (var j = 0; j < questions[i].answers.length; j++) {
            html += '\t\t<div class="custom-control custom-radio">\n' +
                '\t\t\t<input type="radio" id="customRadio' + i + '_' + j + '" name="customRadio' + i + '" class="custom-control-input slideRadio">\n' +
                '\t\t\t<label class="custom-control-label" for="customRadio' + i + '_' + j + '">' + questions[i].answers[j] + '</label>\n' +
                '\t\t</div>\n';
        }
        html += '\t</div>\n' +
            '</div>';
        indicateHtml += '<li data-target="#carouselExampleIndicators" data-slide-to="' + i + '" class="' + activeClass + '"></li>';
    }
    $(".carousel-inner").html(html);
    $(".carousel-indicators").html(indicateHtml);

    $('.slideRadio').click(function () {
        var index = $(this)[0].id.replace('customRadio','');
        var index_i = index.split('_')[0];
        var index_j = index.split('_')[1];
        jsonQuestion[index_i].selectIndex = index_j;
        $("#question_string").val(JSON.stringify(jsonQuestion));
    })
}
function addMedicineInModal() {
    selectedMedicine_id = $("#medicine").val();
    selectedMedicine_name = $("#medicine option:selected").text();
    if(selectedMedicine_id<1) return;
    var min_weight =  $("#medicine option:selected").data("min");
    var max_weight =  $("#medicine option:selected").data("max");
    var price = $("#medicine option:selected").data("price");

    var html="<div class=\"row\">\n" +
        "    <label class=\"col-2 col-form-label text-right\">\n" +
        "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicine(this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+"<input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
        "    <div class=\"col-3\">\n" +
        "        <input class=\"form-control\" type=\"number\" value=\"0\" onchange='calcPrice()' name=\"mass[]\" max='"+max_weight+"' min='"+min_weight+"' id=\"weight_"+selectedMedicine_id+"\">\n" +
        "    </div>\n" +
        "<div class=\"col-3 text-left\">\n" +
        "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" 元/10g (最小："+min_weight+", 最大："+ max_weight+")</label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
        "</div>\n"+
        "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
        "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
        "</div>\n"
    $("#medicineSection").append(html);
    $("#medicine option:selected").attr("disabled","disabled");
    $("#medicine").prop("selectedIndex",-1);
    changeMaxMinValue();
}
function calcPrice(){
    var weights = $("input[name*='mass']");
    var prices = $("input[name*='price']");
    var totalPrice = 0;
    for(var i =0; i < weights.length; i++){
        if(weights[i].value!=null&&weights[i].value!=undefined&&weights[i].value!=''){
            if(prices[i]!=undefined&&prices[i]!=null&&prices[i]!=''){
                totalPrice +=weights[i].value*prices[i].value;
                console.log(weights[i].value,prices[i].value);
            }
        }
    }
    totalPrice /= 10.0;
    $("#total_price").val(totalPrice);
    $("#total_price_span").html(totalPrice);
    console.log(totalPrice);
}


$('#question-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (typeof e.originalEvent === 'undefined' || e.isTrigger) {
        console.log('Prevent duplicate events');
        return false;
    }

    var form = $(this);
    if(!form.parsley().validate()){
        return ;
    }

    if(record_state=='onRecording'){
        Swal.fire({
            type: 'warning',
            title: '请停止录制视频。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    if($("#treatment_id").length)
        url = '/doctor/inquiry/update';
    else
        url = '/doctor/inquiry/completeTreatment';
    if(video==''){
        Swal.fire({
            type: 'error',
            title: '您尚未录制视频。'
        });
    }
    var disease_name = $("#disease_name").val();
    if(disease_name==''||disease_name==undefined||disease_name==null){
        Swal.fire({
            type: 'error',
            title: '请输入病名。'
        });
        return;
    }
    showOverlay();
    var forms = new FormData($(this)[0]);

    $.ajax({
        url: url,
        data: forms,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                hideOverlay();
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '成功',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                hideOverlay();
                Swal.fire({
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function (e) {
            hideOverlay();
            Swal.fire({
                type: 'error',
                text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                title: '错误',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
});

