var guahaoTable,inquiryTable,select_treat_id;
var recorder;
var video='';
var annotationNumber=0;
var jsonQuestion = '';
var record_state = '';
var fuDaiNumbers;
var selectedRecipeId = 0;

var biaozheng_default = "发热，汗出，恶风，鼻鸣干呕，头项强痛，恶寒，项背强几几，汗出恶风，汗遂漏不止，小便难，四肢微急难以屈伸 ，微寒，如虐状，热多寒少，身痒，面色有热色，大汗出，形似疟，热多寒少，无汗，自汗出，微恶寒，脚挛急，厥，足温，喘而汗出，身疼腰痛，骨节疼痛，无汗而喘，身疼痛，不汗出而烦躁，厥逆，筋惕肉瞤，身不疼但重，发汗，身无大热，发汗后，汗出而喘，发汗过多，欲得按，身为振振摇，大汗出，身热不去，身瞤动，振振欲擗地，身热恶风，颈项强，日晡所发潮热，潮热，身黄，项强，头痛发热，微盗汗出，日晡所小有潮热，心下痛不可近，支节烦疼，但头汗出，汗出不恶寒，汗出而喘，无大热，伤寒发热汗出不解，时时恶风，背微恶寒，发热无汗，风湿相抟，身体疼烦，不能自转侧，骨节疼烦掣痛（不得屈伸，近之则痛剧），恶风不欲去衣，身微肿，汗出而闷，身重，身难以转侧，口不仁，手足逆冷，自汗出，头痛，日久热不退，其背恶寒，厥逆，四肢沉重疼痛，身体痛，手足寒，骨节痛，手足厥逆（四逆），汗出而厥，手足厥寒，身体重，腰中冷如坐水中，形如水状，腹重如带五千钱，腰以下冷痛，手足厥寒，大汗出，热不去，汗出而厥，身有微热，头痛，大病瘥后腰以下有水气，身体强几几，无汗小便反少，卧不着席，脚挛急，齘齿，全身抽搐，湿家身烦疼，一身尽疼，汗出恶风，身体疼烦，不能自转侧，风湿相搏，骨节疼烦掣痛，手足不得屈伸、近之则痛剧，汗出短气，恶风不欲去衣，身微肿，骨节疼烦，半身不遂，但臂不遂，身痒而瘾疹；外证疮疡，诸肢节疼痛，身体尪赢，脚肿如脱，病历节不可屈伸、疼痛，脚气疼痛、不可屈伸，关节痛，外证身体不仁（身体麻痹不仁）、如风痹状（风痹要疼的，血痹不疼），面色薄，面色白，手足烦，手足烦热，酸削不能行，四肢酸疼，虚劳腰痛，肌肤甲错，两目黯黑。身体重，腰中冷，腰以下冷湿，腹重，振寒（寒战），发汗后脐下悸，其身甲错，腹皮急（肚皮拘急痉挛）、按之濡（软）、如肿状，手足厥冷，白汗出，逆冷，手足不仁，面色黧黑，手足痹，面翕热如醉状，一身面目黄肿，汗出恶风，恶风，一身悉肿，续自汗出，四肢肿，无大热，四肢聂聂动，黄汗病、身肿发热汗出而渴、状如风水、汗色黄如柏汁，黄汗病两胫自冷，历节病两胫发热，身甲错，恶疮，身瞤，腰髋弛痛，腰以上汗出、下无汗，有物在皮中状，身疼重，骨疼，身冷，恶寒，身痹不仁".split('，');
var lizheng_default = "下利而渴），大汗出，热不去，下利厥逆而恶寒，吐逆，大下利而厥冷，里寒外热，下利谵语，下利后烦按之心下濡，呕，吐涎沫，脉微下利，热多欲饮水，气逆欲吐，大病瘥后劳复，不渴，大便坚硬不通，身热而渴，汗出恶寒，能食，温温欲吐，里急，后重，少腹满，腹痛，腹中痛，腹无积聚，腹满、饮食如故，脉浮而数，腹中寒气、雷鸣切痛，呕吐，痛而闭者（腹胀满而痛，大便不通），心胸中大寒痛（痛而不可触），呕不能饮食，腹中寒，胁下偏痛，寒疝绕脐痛，寒疝腹中痛，下利不饮食（有宿食），但欲饮热，自利、利反快，虽利、心下续坚满，病溢饮、水饮流到四肢当汗出而不汗出，心下痞坚，支饮胸满（大便不通），腹满（肚子胀）口舌干燥，呕家不渴，先渴后呕，呕吐、心下痞、眩悸，心胸中有停痰宿水，吐水，心胸间虚、气满、不能食，多唾口燥，从小腹上冲胸咽，面热如醉、为胃热上冲熏面，微热消渴，消渴、小便反多、以饮一斗、小便一斗，渴欲饮水、水入则吐，渴欲饮水不止，其人苦渴，渴欲饮水，口干舌燥，不渴，食已汗出，暮卧盗汗出，汗出已反发热，腹满胁鸣相逐".split('，');
var    biaoli_default = "胸满，小便不利，小便难，小便数，心烦，咽中干，烦躁，喘而胸满者，嗜卧，胸满胁痛，不汗出而烦躁，咳，少腹满，喘，咳而微喘，微喘，昼日烦躁不得眠，夜而安静，气上冲胸，起则头眩，烦，虚烦不得眠，心中懊侬，烦热，胸中窒，虚烦，卧起不安者，头眩，心烦腹满，往来寒热，胸胁苦满，嘿嘿不欲饮食，心烦喜呕，胁下痞硬，胁下满，呕而发热，腹中急痛，心中悸而烦，郁郁微烦，胸胁满而呕，热结膀胱，其人如狂，少腹急结，血自下，惊狂，卧起不安，气从少腹上冲心，发狂，少腹硬满，小便自利，瘀热在里，少腹硬，其人如狂，其人喜忘，有久瘀血，短气躁烦，心中懊侬，心下支结，胸胁满微结，引胁下痛，气上冲喉咽，不得息，小便自利，汗出短气，表有热，里亦有热，虚劳不足，汗出而闷，心动悸，独语如见鬼状，不识人，循衣摸床，惕而不安，微喘直视，发热谵语，腹满身重，难以转侧，但欲寐，心中烦，不得卧，咽痛、胸满、心烦，咽中伤生疮、不能语言、声不出者，小便自利，胸中气塞，短气，咽中干，虚赢少气，气上冲胸，口噤不得语，胸满，口噤，不呕，小便自利（小便频数），百合病（静默不言不语，昏昏然，卧起不安，饮食无常，冷热不定，口苦，小便赤，精神失常，癫，），狐惑病（默默欲眠，目不得闭，卧起不安，蚀于喉为惑，蚀于阴为狐，不欲饮食，恶闻食臭，其面目乍赤、乍黑、乍白。蚀于上部则声喝，蚀于下部则咽干），微烦，默默但欲卧，目赤如鸠眼，目四眦黑，左胁下脾肿大，病癥瘕，邪火内炽、吐血、衄血、三焦积热、眼目赤肿、口舌生疮、外证疮疡，头眩短气，目瞑，精自出，阴头寒，目眩（目眶痛），发落，男子失精，女子梦交，阴寒精自出，梦失精，咽干口燥，虚劳虚烦不得眠，小便自利，腹重，咳而上气、喉中水鸡声，咳逆上气，时时吐浊，但坐不得眠，咳而脉浮，咳而脉沉，火逆上气、咽喉不利，肺痈（喘不得卧，咳而胸满）、浓未成，咳而胸满，咽干不渴，时出浊唾腥臭，久久吐脓如米粥，咳而上气，喘，目如脱状，烦躁而喘，咳有微热、烦燥胸满，气上冲胸，温疟者其脉如平、身无寒但热、骨节疼烦、时呕，疟多寒者名日牡疟，疟病发渴，治疟寒多微有热、或但寒不热，胸满而短气，少腹弦急（比里急还厉害，），虚劳里急（里急就是少腹里急），少腹拘急（小腹痉挛、抽搐），往来寒热，气从少腹上至心，喘息咳唾，胸背痛，胸痹心痛，心痛彻背，胸痹不得卧，胸痹心中痞、留气结在胸，胁下逆抢心，胸痹、胸中气塞，喉中涩、噎塞习习如痒，胸痹缓急（时轻时重），心中痞，诸逆心悬痛，心痛彻背、背痛彻心，胸胁逆满，胸胁支满，目眩，心下有痰饮（微则短气、甚则悸），常欲蹈其胸上，肝着(瘀滞），悬饮内痛，咳家，咳家、咳烦、胸中痛，喘满，病支饮（咳逆倚息、短气不得卧、其形如肿),苦冒眩，支饮不得息（痰饮充斥、压迫肺，呼吸困难），脐下有悸，吐涎沫而癫眩，咳逆、倚息不得卧，小便难，时复冒，冲气即低、而反更咳、胸满、咳满，咳满即止、而更复渴、冲气复发，消渴小便反多、以饮一斗、小便一斗，小便如粟状、小腹弦急、痛引脐中，小便自利，烦躁，遗尿，心下坚大如盘、边如旋盘".split('，');
var maizheng_default = "脉缓，脉浮缓，脉促，脉微缓，脉微，脉洪大，脉微弱，脉浮，脉沉，脉浮细，脉沉微，脉沉紧，脉浮数，脉微而沉，脉沉结，脉浮而动数，脉沉而紧，脉关上浮，寸脉微浮，脉浮，脉浮虚而涩，脉浮滑，脉结悸，脉结代，脉迟，脉弦，脉涩，脉滑，脉浮数，脉数，无脉，脉微欲绝，脉微而厥，脉细欲绝，脉弱，脉沉迟，脉浮虚而涩脉微而数，脉阴阳俱微，脉浮弱而涩，脉极虚芤迟，脉浮大，脉浮而数，脉紧弦，脉沉弦，脉伏，脉沉弦，脉沉紧，脉沉小，脉迟而涩".split('，');

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
                "aTargets": [2],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return data==null||data==''||data==undefined?data:data.substring(0, 10);;
                }
            },{
                "aTargets": [4],
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
    if($("#recipe").length){
        $("#recipe").prepend("<option></option>").select2({
            placeholder:"请选择",
        });


    }
    if($("#medicine").length)
        $("#medicine").select2();

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
                        var medicines = JSON.parse(resp.data.question.medicines);
                        for(var i =  0; i < medicines.length; i++){
                            $("#recipe").append(new Option(medicines[i].prescription_name,medicines[i].id,false,true));
                        }
                        $("#recipe").prop('disabled',true);
                        $("#medicines").val(resp.data.question.medicines);

                        drawMedicine(medicines,true,false);

                        $("#question_string").val(resp.data.question.questions);
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
        hideTooltip();
        var recipes = $(this).val();
        drawRecipeSections(recipes,true);
    });

    // default slide
    drawSlide([]);

    // default list
    // drawZhengSection('biaozheng',biaozheng_default,[]);

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
        width: 80,
        height: 45,
        fluid: false,
        autoplay:false,
        plugins: {
            record: {
                audio: false,
                video: true,
                maxLength: videoMaxLengthInSeconds,
                debug: true,
                videoMimeType: "video/mp4"
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
    if(!form.parsley({'excluded': ':disabled'}).validate()){
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
                if(url=='/doctor/inquiry/completeTreatment')
                    window.location.href = "/doctor/inquiry/view"
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

function init_recipeSection() {
    drawSlide([]);
    drawRecipe([], []);
    drawRecipeSections([], [], [], [], []);
    drawRecipeSectionsOther([], [], [], [], []);
}


function removeMedicineInq(recipeId, obj) {
    var id = $(obj).data("index");
    $("#medicine option[value='" + id + "']").attr("disabled",false);
    var row = $(obj).parent().parent().parent();
    row.remove();

    calcPriceOther(recipeId);
}
function changeFuNumber(obj) {
    var id = obj.id.replace('fuNumber_','');
    var fuNumber = $(obj).val();
    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);

    for(var i = 0 ; i < recipeDatas.length; i++){
        if(recipeDatas[i].id==id){
            recipeDatas[i].fuNumber = fuNumber;
            recipeDatas[i].price = calcPrice(recipeDatas[i].medicine)*fuNumber;
            break;
        }
    }
    $("#medicines").val(JSON.stringify(recipeDatas));
    if($("#question_title").val()==''||$("#question_title").val()==undefined||$("#question_title").val()==null)
        drawMedicine(recipeDatas,true,true);
    else
        drawMedicine(recipeDatas,true,false);
    calcPriceTotal();
}

// function searchRecipes(){
//     $("#recipe").prop('disabled',false);
//     if ($("#question_title").length > 0) {
//         var data = $("#question_title").val();
//         if(data==''||data==null||data==undefined) {
//
//             drawRecipeSections([], [], [], [], []);
//             drawRecipeSectionsOther([], [], [], [], []);
//
//             var forms = new FormData($("#question-form")[0]);
//
//             showOverlay();
//             $.ajax({
//                 url: '/doctor/inquiry/getRecipeOther',
//                 data: forms,
//                 type: 'POST',
//                 cache: false,
//                 contentType: false,
//                 processData: false,
//                 success: function (resp) {
//                     hideOverlay();
//                     if (resp.code == 0) {
//                         drawRecipeOther(resp.data);
//                     } else {
//                         hideOverlay();
//                         Swal.fire({
//                             type: 'error',
//                             text: resp.message,
//                             title: '错误',
//                             showConfirmButton: false,
//                             timer: 3000
//                         });
//                     }
//                 },
//                 error: function (e) {
//                     hideOverlay();
//                     Swal.fire({
//                         type: 'error',
//                         text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
//                         title: '错误',
//                         showConfirmButton: false,
//                         timer: 3000
//                     });
//                 }
//             });
//
//         } else {
//             return;
//         }
//     }
//
// }
