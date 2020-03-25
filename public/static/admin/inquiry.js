var guahaoTable,inquiryTable,select_treat_id;
var recorder;
var video='';
var annotationNumber=0;
var jsonQuestion = '';
var record_state = '';
var fuDaiNumbers;
var selectedRecipeId = 0;

var biaozheng_default = ["发热", "汗出", "恶风", "鼻鸣干呕", "头项强痛"],
    lizheng_default = ["不呕", "下之后", "大烦渴不解", "心下满微痛", "吐逆"],
    biaoli_default = ["胸满", "小便不利", "小便难", "小便数", "心烦"],
    maizheng_default = ["脉缓", "脉浮缓", "脉促", "脉微缓", "脉微"];

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

    if($("#question_title").length)
        $("#question_title").prepend("<option></option>").select2({
            allowClear: true,
            placeholder:"请选择问诊单模板"
        });
    if($("#recipe").length)
        $("#recipe").prepend("<option></option>").select2({
            placeholder:"请选择"
        });

    if($("#myVideo").length)
        startVideoCamera();
    $("#question_title").on("change",function(e){
        var data = $(this).val();
        if(data==''||data==null||data==undefined) {
            // default list
            drawItems('biaozheng',biaozheng_default,[], false);
            drawItems('lizheng',lizheng_default,[], false);
            drawItems('biaoli',biaoli_default,[], false);
            drawItems('maizheng',maizheng_default,[], true);
            init_recipeSection();
            return;
        } else {
            init_recipeSection();
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
                        jsonQuestion = JSON.parse(resp.data.question.questions);
                        drawRecipe(resp.data.question, resp.data.recipe);
                        $("#question_string").val(resp.data.question.questions);
                        // $("#recipe").trigger("change");
                        var biaozhengList = JSON.parse(resp.data.question.biaozheng)
                        drawItems('biaozheng',biaozhengList,[], false);
                        drawItems('lizheng',JSON.parse(resp.data.question.lizheng),[], false);
                        drawItems('biaoli',JSON.parse(resp.data.question.biaoli),[], false);
                        drawItems('maizheng',JSON.parse(resp.data.question.maizheng),[]);

                        // fuDaiNumbers = JSON.parse(resp.data.question.fuDaiNumber);
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
        }

    });

    $("#recipe").on("change",function (e) {
        var recipes = $(this).val();
        var medicines = [];
        var eating_method = [];
        var ban = [];
        var othercondition = [];
        var selrecipes = [];
        var selbuf = $("#recipe").select2("data");
        var data = $("#question_title").val();

        // if (recipes.length > 3) {
        //     Swal.fire({
        //         type: 'warning',
        //         title: '不得超过3个。',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     $("#recipe").val('').change();
        //     return;
        // }

        if(data==''||data==null||data==undefined) { // no selected question
            for (var i = 0; i < recipes.length; i ++) {
                medicines.push($("#recipe option[value='" + recipes[i] + "']").data("medicine"))
                eating_method.push($("#recipe option[value='" + recipes[i] + "']").data("eating_method"))
                ban.push($("#recipe option[value='" + recipes[i] + "']").data("ban"))
                othercondition.push($("#recipe option[value='" + recipes[i] + "']").data("othercondition"))
            }
            for (var i = 0; i < selbuf.length; i ++) {
                selrecipes.push({prescription_name: selbuf[i].text, id: selbuf[i].id});
            }
            drawRecipeSectionsOther(selrecipes, medicines, eating_method, ban, othercondition);
        } else {
            medicines = $("#recipe option:selected").data("medicine");
            for (var i = 0; i < recipes.length; i ++) {
                eating_method.push($("#recipe option[value='" + recipes[i] + "']").data("eating_method"))
                ban.push($("#recipe option[value='" + recipes[i] + "']").data("ban"))
                othercondition.push($("#recipe option[value='" + recipes[i] + "']").data("othercondition"))
            }
            drawRecipeSections(recipes, medicines, eating_method, ban, othercondition);
        }

    });

    // default slide
    drawSlide([]);

    // default list
    drawItems('biaozheng',biaozheng_default,[]);
    drawItems('lizheng',lizheng_default,[]);
    drawItems('biaoli',biaoli_default,[]);
    drawItems('maizheng',maizheng_default,[]);

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
        //  $(".vjs-record-button").trigger('click');
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

function drawRecipe(questions, recipes){
    var html = '',
        recipe_list = [];
    if (recipes.length > 0)
        recipe_list = JSON.parse(questions.medicines);
    for (var i = 0; i < recipe_list.length; i ++) {
        html += '<option value="' + recipe_list[i].receip_id+'" data-medicine=\'' + questions.medicines + '\' data-otherCondition=\'' +
            recipes[i].other_condition + '\' data-eating_method=\'' + recipes[i].eating_method + '\' data-ban=\'' + recipes[i].ban + '\'>'+recipe_list[i].receip_txt+'</option>';
    }
    $("#recipe").html(html);
}

function drawRecipeOther(recipes){
    var html = '',
        recipe_list = [];
    if (recipes.length > 0)
        recipe_list = recipes;
    for (var i = 0; i < recipe_list.length; i ++) {
        html += '<option value="' + recipe_list[i].id+'" data-medicine=\'' + recipe_list[i].medicine + '\' data-otherCondition=\'' +
            recipe_list[i].other_condition + '\' data-eating_method=\'' + recipe_list[i].eating_method + '\' data-ban=\'' + recipe_list[i].ban + '\'>'+recipe_list[i].prescription_name+'</option>';
    }
    $("#recipe").html(html);
}

function drawRecipeSections(selrecipes, medicines, eating_method, ban, othercondition) {
    var temp = medicines;
    var html = "";
    if(medicines==''||medicines==null||medicines==undefined){
        temp = medicines= [];
        $("#medicineSection").html("");
        calcPrice();
        return;
    }

    var recipes = (medicines);
    for(var i=0; i < recipes.length; i++){
        var dbmedicines = (recipes[i].medicines);
        if (selrecipes.indexOf(recipes[i].receip_id) < 0)
            continue;
        html += ' <h4 class="text-bold">'+recipes[i].receip_txt+'</h4><hr>';
        for(var j=0; j<dbmedicines.length; j++){
            html+='<div id="recipe_medicine_' + recipes[i].receip_id + '" class="recipe_medicine_' + recipes[i].receip_id + '">';
            // html +='<div class="row">\n' +
            //     '\t<label class="col-2 col-form-label text-right">\n' +
            //     '\t\t'+dbmedicines[j].medicine+'\n' +
            //     '\t</label>\n' +
            //     '\t<div class="col-2">\n' +
            //     '\t\t<input class="form-control" type="number" value="'+dbmedicines[j].min_weight+'" disabled>\n' +
            //     '\t</div>\n' +
            //     '\t<span style="paddint-top:8px">~</span>\n' +
            //     '\t<div class="col-2">\n' +
            //     '\t\t<input class="form-control" type="number" value="'+dbmedicines[j].max_weight+'" disabled>\n' +
            //     '\t</div>\n' +
            //     '\t<div class="col-2 text-left">\n' +
            //     '\t\t<label id="price_6" style="line-height:38px">' + dbmedicines[j].price + '  元/10g</label>\n' +
            //     '\t</div>\n' +
            //     '</div>';
            var min_weight =  dbmedicines[j].min_weight;
            var max_weight =  dbmedicines[j].max_weight;
            var weight = dbmedicines[j].mass;
            if(dbmedicines[j].weight==undefined&&dbmedicines[j].min_weight==dbmedicines[j].max_weight)
                weight = dbmedicines[j].max_weight;
            var price = dbmedicines[j].price;
            var unit = dbmedicines[j].unit;
            selectedMedicine_id = dbmedicines[j].id;
            selectedMedicine_name = dbmedicines[j].name;

            var unitLable = unit==null || unit=="null" ||unit==''||unit==undefined||unit=='公克'?' 元/10g':unit=='两'?' 元/两':('元/'+unit);

            html+="<div class=\"row\">\n" +
                "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n" +
                selectedMedicine_name +
                "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
                "    <div class=\"col-2\">\n" +
                "        <input class=\"form-control\" type=\"number\" value=\""+weight+"\" name=\"mass[]\" min=\"0\"  id=\"weight"+selectedMedicine_id+"\" disabled>\n" +
                "    </div>\n" +
                "<div class=\"col-4 text-left\">\n" +
                "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" "+unitLable+" (最小："+min_weight+", 最大："+ max_weight+") </label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
                "<input type='hidden' name='unit[]' value='"+dbmedicines[j].unit+"' />"+
                "</div>\n"+
                "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
                "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
                "</div>" +
            "</div>\n"
            html +='</div>';

        }

        html += '<div id="recipe_medicine_' + recipes[i].receip_id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n其他症状</label>\n" +
            "    <div class=\"col-8\">\n" +
            "        <textarea class=\"form-control\" type=\"text\" disabled>" + othercondition[i] + "</textarea>\n" +
            "    </div>\n" +
            "</div>\n" +
        "</div>\n";

        html += '<div id="recipe_medicine_' + recipes[i].receip_id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n禁忌</label>\n" +
            "    <div class=\"col-8\">\n" +
            "        <textarea class=\"form-control\" type=\"text\" disabled>" + ban[i] + "</textarea>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += '<div id="recipe_medicine_' + recipes[i].receip_id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n煎服方法</label>\n" +
            "    <div class=\"col-8\">\n" +
            "        <textarea class=\"form-control\" type=\"text\" disabled>" + eating_method[i] + "</textarea>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += '<div id="recipe_medicine_' + recipes[i].receip_id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <div class=\"col-1\">\n</div>\n" +
            "    <div class=\"col-8\">\n" +
            "        <div class=\"custom-control custom-checkbox\">" +
            "           <input type=\"hidden\" class=\"custom-control-input\" name=\"houfang[]\" value=\"0\">" +
            "           <input type=\"checkbox\" class=\"custom-control-input\" id=\"houfang_" + recipes[i].receip_id + "\" onclick=\"sethoufang(this)\">" +
            "           <label class=\"custom-control-label\" for=\"houfang_" + recipes[i].receip_id + "\">\n是否合方</label>" +
            "        </div>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += "<div class='row mt-3 fuPrice fuPrice_" + recipes[i].receip_id + "\'>" +
            "<div class='col-sm-2'></div>" +
            "<div class='col-sm-1' style='margin-left: 3.5%'>" +
            "<input class=\"form-control\" type=\"hidden\" value=\""+recipes[i].total+"\" name=\"totalPrice[]\" id=\"totalPrice_"+recipes[i].receip_id+"\">\n" +
            "<input class=\"form-control\" type=\"hidden\" value=\""+recipes[i].fu_number+"\" name=\"daiNumber[]\" id=\"daiNumber_"+recipes[i].receip_id+"\">\n" +
            "<input type='number' id='fuNumber_" + recipes[i].receip_id + "' name='fuNumber[]' min='0' class='text-center form-control' onchange='changeFuNumber(" + recipes[i].receip_id + ", this);' value='1'/></div>" +
            "<div class='text-left col-form-label'>副</div>" +
            "<div class='col-sm-2'><input type='number' class='text-center form-control' id='fudaiNumber_" + recipes[i].receip_id + "' name='fudaiNumber[]' value=\"" + recipes[i].fu_number + "\"/></div>" +
            "<div class='col-form-label'>代</div>" +
            "<div class='col-sm-2'><input type='number' class='text-center form-control fuPriceVal' id='fuPrice_" + recipes[i].receip_id + "' name='fuPrice[]' value='" + recipes[i].total + "'disabled/></div>" +
            "<div class='col-sm-1 col-form-label'>元</div>" +
            "</div>";
    }
    $("#medicineSection").html(html);
    calcPrice();

}

function drawRecipeSectionsOther(selrecipes, medicines, eating_method, ban, othercondition) {
    var temp = medicines;
    var html = "";
    if(medicines==''||medicines==null||medicines==undefined){
        temp = medicines= [];
        $("#medicineSection").html("");
        return;
    }

    var recipes = (selrecipes);
    for(var i=0; i < recipes.length; i++){
        var dbmedicines = (medicines[i]);
        html += ' <h4 class="text-bold">'+recipes[i].prescription_name+' <button type="button" class="btn btn-circle btn-warning p-0-0"  title="添加药材" onclick="setRecipeId('+ recipes[i].id + ');"><i class="fas fa-plus"></i></button></h4><hr>';
        for(var j=0; j<dbmedicines.length; j++){
            html+='<div id="recipe_medicine_' + recipes[i].id + '" class="recipe_medicine_' + recipes[i].id + '">';
            // html +='<div class="row">\n' +
            //     '\t<label class="col-2 col-form-label text-right">\n' +
            //     '\t\t'+dbmedicines[j].medicine+'\n' +
            //     '\t</label>\n' +
            //     '\t<div class="col-2">\n' +
            //     '\t\t<input class="form-control" type="number" value="'+dbmedicines[j].min_weight+'" disabled>\n' +
            //     '\t</div>\n' +
            //     '\t<span style="paddint-top:8px">~</span>\n' +
            //     '\t<div class="col-2">\n' +
            //     '\t\t<input class="form-control" type="number" value="'+dbmedicines[j].max_weight+'" disabled>\n' +
            //     '\t</div>\n' +
            //     '\t<div class="col-2 text-left">\n' +
            //     '\t\t<label id="price_6" style="line-height:38px">' + dbmedicines[j].price + '  元/10g</label>\n' +
            //     '\t</div>\n' +
            //     '</div>';
            var min_weight =  dbmedicines[j].min_weight;
            var max_weight =  dbmedicines[j].max_weight;
            var weight = dbmedicines[j].weight==undefined?0:dbmedicines[j].weight;
            if(dbmedicines[j].weight==undefined&&dbmedicines[j].min_weight==dbmedicines[j].max_weight)
                weight = dbmedicines[j].max_weight;
            var price = dbmedicines[j].price;
            var unit = dbmedicines[j].unit;
            selectedMedicine_id = dbmedicines[j].medicine_id;
            selectedMedicine_name = dbmedicines[j].medicine;

            var unitLable = unit==null || unit=="null" ||unit==''||unit==undefined||unit=='公克'?' 元/10g':unit=='两'?' 元/两':('元/'+unit);

            html+="<div class=\"row\">\n" +
                "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n" +
                "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicineInq(" + recipes[i].id + ", this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;" + selectedMedicine_name +
                "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
                "    <div class=\"col-2\">\n" +
                "        <input class=\"form-control\" type=\"number\" value=\""+weight+"\" name=\"mass[]\" min=\"0\" onchange='calcPriceOther(" + recipes[i].id + ")'  id=\"weight"+selectedMedicine_id+"\">\n" +
                "    </div>\n" +
                "<div class=\"col-4 text-left\">\n" +
                "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" "+unitLable+" (最小："+min_weight+", 最大："+ max_weight+") </label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
                "<input type='hidden' name='unit[]' value='"+dbmedicines[j].unit+"' />"+
                "</div>\n"+
                "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
                "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
                "</div>" +
                "</div>\n"
            html +='</div>';

        }

        html += '<div id="recipe_medicine_' + recipes[i].id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n其他症状</label>\n" +
            "    <div class=\"col-8\">\n" +
            "        <textarea class=\"form-control\" type=\"text\" disabled>" + othercondition[i] + "</textarea>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += '<div id="recipe_medicine_' + recipes[i].id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n禁忌</label>\n" +
            "    <div class=\"col-8\">\n" +
            "        <textarea class=\"form-control\" type=\"text\" disabled>" + ban[i] + "</textarea>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += '<div id="recipe_medicine_' + recipes[i].id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n煎服方法</label>\n" +
            "    <div class=\"col-8\">\n" +
            "        <textarea class=\"form-control\" type=\"text\" disabled>" + eating_method[i] + "</textarea>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += '<div id="recipe_medicine_' + recipes[i].id + '">' +
            "<div class=\"row\">\n" +
            "   <div class='col-sm-2'></div><div class=\"col-sm-1\"></div>" +
            "    <div class=\"col-8\">\n" +
            "        <div class=\"custom-control custom-checkbox\">" +
            "           <input type=\"hidden\" class=\"custom-control-input\" name=\"houfang[]\" value=\"0\">" +
            "           <input type=\"checkbox\" class=\"custom-control-input\" id=\"houfang_" + recipes[i].id + "\" onclick=\"sethoufang(this)\">" +
            "           <label class=\"custom-control-label\" for=\"houfang_" + recipes[i].id + "\">\n是否合方</label>" +
            "        </div>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += "<div class='row mt-3 fuPrice fuPrice_" + recipes[i].id + "\'>" +
            "<div class='col-sm-2'></div>" +
            "<div class='col-sm-1' style='margin-left: 3.5%'>" +
            "<input class=\"form-control\" type=\"hidden\" value=\"0"+"\" name=\"totalPrice[]\" id=\"totalPrice_"+recipes[i].id+"\">\n" +
            "<input class=\"form-control\" type=\"hidden\" value=\"0"+"\" name=\"daiNumber[]\" id=\"daiNumber_"+recipes[i].id+"\">\n" +
            "<input type='number' id='fuNumber_" + recipes[i].id + "' name='fuNumber[]' min='0' class='text-center form-control' onchange='changeFuNumber(" + recipes[i].id + ", this);' value='1'/></div>" +
            "<div class='text-left col-form-label'>副</div>" +
            "<div class='col-sm-2'><input type='number' class='text-center form-control' id='fudaiNumber_" + recipes[i].id + "' name='fudaiNumber[]' value='0'/></div>" +
            "<div class='col-form-label'>代</div>" +
            "<div class='col-sm-2'><input type='number' class='text-center form-control fuPriceVal' id='fuPrice_" + recipes[i].id + "' name='fuPrice[]' value='" + recipes[i].total + "'disabled/></div>" +
            "<div class='col-sm-1 col-form-label'>元</div>" +
            "</div>";
    }
    $("#medicineSection").html(html);

    for(var i=0; i < recipes.length; i++){
        calcPriceOther(recipes[i].id);
    }

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
    if(questions.length<1){
        html +='<div class="carousel-inner"><div class="carousel-item active">\n' +
            '\t<div class="overlaybg"><img src="/img/photos/white.png" class="img-fluid"></div>'
            +'</div></div>';
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
        return ;
    }
    showOverlay();
    var forms = new FormData($(this)[0]);

    var receips = $("#recipe").select2("data"),
        medicine_item = [],
        medicine_details = [],
        medicine_detail_item = [],
        medicine_list = [];
    for (var i = 0; i < receips.length; i ++) {
        medicine_details = [];
        $(".recipe_medicine_" + receips[i].id).each(function(index, obj){
            medicine_detail_item = {
                id : $(obj).find("input[name='medicine_id[]']").val(),
                name :  $(obj).find("input[name='medicine_name[]']").val(),
                mass : $(obj).find("input[name='mass[]']").val(),
                price : $(obj).find("input[name='price[]']").val(),
                unit : $(obj).find("input[name='unit[]']").val(),
                max_weight : $(obj).find("input[name='max_weight[]']").val(),
                min_weight : $(obj).find("input[name='min_weight[]']").val()
            }
            if (typeof medicine_detail_item.mass != "undefined") {
                medicine_details.push(medicine_detail_item);
            }
        })
        medicine_item = {
            receip_id : receips[i].id,
            receip_txt : receips[i].text,
            medicines: medicine_details,
            fu_number: $("#fuNumber_" + receips[i].id).val(),
            fudai_number: $("#fudaiNumber_" + receips[i].id).val(),
            total: $("#fuPrice_" + receips[i].id).val()
        }
        medicine_list.push(medicine_item);
    }
    forms.append("recipe_detail", JSON.stringify(medicine_list));

    var is_empty_medicine = false;
    for (var i = 0; i < medicine_list.length; i ++) {
        if (medicine_list[i].medicines.length == 0)
            is_empty_medicine = true;
    }
    if (is_empty_medicine == true) {
        Swal.fire({
            type: 'error',
            title: '药材不能为空。'
        });
        hideOverlay();
        return ;
    }

    var sel_houfang_length = 0;
    $("input[name='houfang[]']").each(function(index, obj){
        if ($(obj).val() == "1")
            sel_houfang_length ++;
    });
    if (sel_houfang_length > 3) {
        Swal.fire({
            type: 'warning',
            title: '是否合方不得超过3个。',
            showConfirmButton: false,
            timer: 3000
        });
        $("#recipe").val('').change();
        hideOverlay();
        return;
    }

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

function addMedicineInModal() {
    selectedMedicine_id = $("#medicine").val();
    selectedMedicine_name = $("#medicine option:selected").text();
    if(selectedMedicine_id<1) return;
    var min_weight =  $("#medicine option:selected").data("min");
    var max_weight =  $("#medicine option:selected").data("max");
    var price = $("#medicine option:selected").data("price");

    var html="<div id='recipe_medicine_" + selectedRecipeId + "' class='recipe_medicine_" + selectedRecipeId + "'><div class=\"row\">\n" +
        "  <div class='col-sm-2'></div>  <label class=\"col-1 col-form-label text-right\">\n" +
        "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicineInq(" + selectedRecipeId + ", this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+
        "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
        "    <div class=\"col-2\">\n";

    var data = $("#question_title").val();
    if (data == "") {
        html+="        <input class=\"form-control\" type=\"number\" value=\"0\" onchange='calcPriceOther(" + selectedRecipeId + ")' name=\"mass[]\" id=\"weight"+selectedMedicine_id+"\">\n";
    } else {
        html+="        <input class=\"form-control\" type=\"number\" value=\"0\" onchange='calcPrice()' name=\"mass[]\" id=\"weight_"+selectedMedicine_id+"\">\n";
    }

    html+="    </div>\n" +
        "<div class=\"col-4 text-left\">\n" +
        "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" 元/10g (最小："+min_weight+", 最大："+ max_weight+")</label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
        "</div>\n"+
        "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
        "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
        "</div>\n" +
        "</div>";

    if ($(".recipe_medicine_" + selectedRecipeId).length > 1)
        $("#recipe_medicine_"+selectedRecipeId).after(html);
    else
        $("#recipe_medicine_"+selectedRecipeId).before(html);
    // $("#medicine option:selected").attr("disabled","disabled");
    // $("#medicine").prop("selectedIndex",-1);
    changeMaxMinValue();
    getContraryIds(selectedMedicine_id,function(contraryIds){
        for(var i=0; i < contraryIds.length; i++){
            $("#medicine option[value='"+contraryIds[i]+"']").attr("disabled","disabled");
        }
    });
}

function setRecipeId(id){
    selectedRecipeId = id;
    $("#myModal").modal('show');

    var sel_medicineIds = [];
    $(".recipe_medicine_" + id).each(function(index, obj){
        sel_medicineIds.push($(obj).find("input[name='medicine_id[]']").val());
    });
    for (var i = 0; i < sel_medicineIds.length; i ++) {
        $("#medicine option[value='"+sel_medicineIds[i]+"']").attr("disabled","disabled");
    }

}

function calcPrice(){

    var fuPrice_obj = "";
    var totalPrice = 0;

    $(".fuPrice").each(function(index, obj){
        fuPrice_obj = $(obj).find("input[name='fuPrice[]']");
        totalPrice += parseInt($(fuPrice_obj).val());
    });

    $("#total_price").val(totalPrice);
    $("#total_price_span").html(totalPrice);
    console.log(totalPrice);
}

function calcPriceOther(recipe_id){

    var fuPrice_obj = "";
    var totalPrice = 0;

    var totalPrice = 0,
        amount = 0,
        price_per_unit = 0,
        amount_per_unit = 0,
        unit = "",
        mass_obj = "",
        price_obj = "",
        unit_obj = "";
    $(".recipe_medicine_" + recipe_id).each(function(index, obj){
        mass_obj = $(obj).find("input[name='mass[]']");
        price_obj = $(obj).find("input[name='price[]']");
        unit_obj = $(obj).find("input[name='unit[]']");
        amount = parseInt($(mass_obj).val());
        price_per_unit = parseInt($(price_obj).val());
        unit = $(unit_obj).val();
        amount_per_unit = unit=="null"||unit==null||unit==''||unit==undefined||unit=='公克' ? 10 : 1;
        totalPrice += amount * (price_per_unit / amount_per_unit);
    });

    var fuNumber = $("#fuNumber_" + recipe_id).val();
    $("#totalPrice_" + recipe_id).val(totalPrice);
    $("#fuPrice_" + recipe_id).val(totalPrice * fuNumber);

    totalPrice = 0;
    $(".fuPrice").each(function(index, obj){
        fuPrice_obj = $(obj).find("input[name='fuPrice[]']");
        totalPrice += parseInt($(fuPrice_obj).val());
    });

    $("#total_price").val(totalPrice);
    $("#total_price_span").html(totalPrice);
    console.log(totalPrice);
}

function changeFuNumber(receip_id, obj) {
    var fuNumber = $(obj).val();
    var daiNumber = $("#daiNumber_" + receip_id).val();
    var fuPrice = fuNumber * $("#totalPrice_" + receip_id).val();
    var fudaiNumber = fuNumber * daiNumber;
    $("#fuPrice_" + receip_id).val(fuPrice);
    var data = $("#question_title").val();
    if (data == "") {
        calcPriceOther(receip_id);
    } else {
        calcPrice();

        $("#fudaiNumber_" + receip_id).val(fudaiNumber);

    }
}

function init_recipeSection() {
    drawSlide([]);
    drawRecipe([], []);
    drawRecipeSections([], [], [], [], []);
    drawRecipeSectionsOther([], [], [], [], []);
}

function sethoufang(obj) {
    var houfang = $(obj).prev().val();
    $(obj).prev().val(1 - houfang);
}

function removeMedicineInq(recipeId, obj) {
    var id = $(obj).data("index");
    $("#medicine option[value='" + id + "']").attr("disabled",false);
    var row = $(obj).parent().parent().parent();
    row.remove();

    calcPriceOther(recipeId);
}
