var questionNumber = 1 , answerNumber = 0;
var answerItemNumber = 0;
var queries = [];
var qa_table;
var recipe_medicines;
function openQAModal() {
    // Swal.fire({
    //         title: "1个输入框还是2个输入框？",
    //         type: "info",
    //         showCancelButton: true,
    //         confirmButtonClass: "btn-danger",
    //         confirmButtonText: "1个",
    //         cancelButtonText: "2个",
    //         closeOnConfirm: false,
    //         closeOnCancel: false
    //     }).then(result => {
    //         $("#question").val('');
    //         if (result.value) {
    //             answerItemNumber = 1;
    //             answerNumber = 0;
    //             $("#answerItemSection").html('');
    //             $("#QAModal").modal('show');
    //             $("#btnAnswer2").hide();
    //             $("#btnAnswer1").show();
    //         }else if(result.dismiss=='cancel'){
    //             $("#answerItemSection").html('');
    //             $("#QAModal").modal('show');
    //             $("#btnAnswer1").hide();
    //             $("#btnAnswer2").show();
    //             answerItemNumber = 2;
    //             answerNumber = 0;
    //         }
    //     });
    $("#question").val('');
    answerItemNumber = 1;
    answerNumber = 0;
    $("#answerItemSection").html('');
    $("#QAModal").modal('show');
    $("#btnAnswer2").hide();
    $("#btnAnswer1").show();

}
function appendAnswerItem1() {
    answerNumber++;
    var html = '<div class="form-group mt-3 row" >\n' +
        '\t<label for="example-text-input" class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="'+answerNumber+'" onclick="removeAnswer1(this);"><i class="fas fa-times"></i> </button></label>\n' +
        '\t<div class="col-10">\n' +
        '\t\t<input class="form-control" type="text" value=""  id="answer_'+ questionNumber+'_'+answerNumber+'">\n' +
        '\t</div>\n' +
        '</div>\n';
    $("#answerItemSection").append(html);
}
function removeAnswer1(obj) {
    answerNumber--;
    var selectNumber = $(obj).data("index");
    var row = $(obj).parent().parent();
    row.remove();
    $(".tooltip").fadeOut();
    for(var i=selectNumber+1; i< answerNumber+2; i++){
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","answer_"+questionNumber+'_'+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }
}

function appendAnswerItem2() {
    answerNumber++;
    html = '<div class="form-group mt-3 row">\n' +
        '\t<label for="example-text-input" class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="'+answerNumber+'" onclick="removeAnswer2(this);"><i class="fas fa-times"></i> </button></label>\n' +
        '\t<div class="col-7">\n' +
        '\t\t<input class="form-control" type="text" value="" id="answer_'+ questionNumber+'_'+answerNumber+'">\n' +
        '\t</div>\n' +
        '\t<div class="col-3">\n' +
        '\t\t<input class="form-control" type="number" value="" id="weight_'+ questionNumber+'_'+answerNumber+'" min="0" max="1" step="0.01">\n' +
        '\t</div>\n' +
        '</div>\n';
    $("#answerItemSection").append(html);
}

function removeAnswer2(obj) {
    answerNumber--;
    var selectNumber = $(obj).data("index");

    var row = $(obj).parent().parent();
    row.remove();
    for(var i=selectNumber+1; i< answerNumber+2; i++){
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","answer_"+questionNumber+'_'+(i-1));
        $('*[data-index="'+i+'"]').parent().next().next().first().children().attr("id","weight_"+questionNumber+'_'+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }
}
function addQueryItem() {
    if(answerNumber<1){
        Swal.fire({
            type: 'info',
            title: '请输入至少一个答案项目。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    var html = '';
    var question = $("#question").val();
    if(question==''||question==null||question==undefined){
        Swal.fire({
            type: 'info',
            title: '请输入质问。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    if(answerItemNumber == 1){
        var answers = [];
        for(var i = 1; i < answerNumber+1; i++){
            var answerItem = $("#answer_"+questionNumber+"_"+i).val();
            if(answerItem==''||answerItem==null||answerItem==undefined){
                Swal.fire({
                    type: 'info',
                    title: '答案不能为空。',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }
            answers.push(answerItem);
        }
        html +='</div>\n' +
            '</div>';
        // $("#answerSection").append(html);
    }else if(answerItemNumber==2){
        var answers = [];
        for(var i = 1; i < answerNumber+1; i++){
            var answerItem = $("#answer_"+questionNumber+"_"+i).val();
            var weight = $("#weight_"+questionNumber+"_"+i).val();
            answers.push({answerItem:answerItem,weight:weight});
            html +='<p class="m-0-0">' + answerItem+'('+weight+')</p>';
        }
        html +='</div>\n' +
            '</div>';
        $("#answerSection").append(html);
    }
    var queryItem = {
        answerItemNumber:answerItemNumber,
        question:question,
        answers:answers
    };
    queries.push(queryItem);
    questionNumber++;
    $("#QAModal").modal('hide');
    drawAnswerSection(queries);
}

function removeQuestion(obj) {
    questionNumber--;
    var selectNumber = $(obj).data("index");

    queries.splice(selectNumber-1, 1);
    drawAnswerSection(queries);
}
function drawAnswerSection(queries) {
    html = '';
    for(var i = 0 ; i < queries.length; i++){
        if(queries[i].answerItemNumber==1)
            html +='<div class="form-group mt-3 row">\n' +
                '\t<label class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="'+(i*1+1)+'" onclick="removeQuestion(this);"><i class="fas fa-times"></i> </button></label>\n' +
                '\t<div class="col-8 border-light bg-light border">\n' +
                '\t\t<h5 class="font-weight-bold">';
        html+=(i*1+1)+'.'+queries[i].question+'</h5>\n';
        for(var j=0; j<queries[i].answers.length;j++){
            html +='<p class="m-0-0">' + queries[i].answers[j]+'</p>';
        }
        html +='</div>\n' +
            '</div>';
    }
    $("#answerSection").html(html);
}

$(function(){

    if($(".select2").length)
        $(".select2").select2({
            placeholder:"请选择药房"
        });


    qa_table = $('#tbl_qa').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'qa_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/qa/get',
            "dataType":"json"
        },
        columns: [
            {data: 'number'},
            {data: 'title'},
            {data: 'department',orderable:false},
            {data: 'created_at'},
            {data:'id'}
            ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[4],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/qa/edit/'+data+'\'"><i class="ti-pencil-alt"></i>修改</button>'+
                        '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteQA(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });


    $(document).keypress(
        function(event){
            if (event.which == '13') {
                event.preventDefault();
            }
        });

    if(typeof biaozhengList !== 'undefined'){
        drawItems('biaozheng',biaozhengList,selectedBiaozheng);
        drawItems('lizheng',lizhengList,selectedLizheng);
        drawItems('biaoli',biaoliList,selectedBiaoli);
        drawItems('maizheng',maizhengList,selectedMaizheng);
        changeTrigger();
    }
    $("#recipes").on("change",function(e){

        var recipes = $(this).val();
        drawRecipeSections(recipes,false);
    });
    if (typeof recipes !== 'undefined') {
        $("#medicines").val(recipes);
        $("#recipes").trigger('change');
    }

});

$('#question-form').submit(function (e) {
    $("#biaozheng").val(JSON.stringify(biaozhengList))
    $("#lizheng").val(JSON.stringify(lizhengList))
    $("#biaoli").val(JSON.stringify(biaoliList))
    $("#maizheng").val(JSON.stringify(maizhengList))

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

    showOverlay();
    $("#questions").val(JSON.stringify(queries));
    var forms = new FormData($(this)[0]);
    var questionId = $("#question_id").val();
    if(questionId==''||questionId==null||questionId==undefined){
        url = '/doctor/qa/create';
    }
    else{
        url = '/doctor/qa/editQA'
    }

    // var receips = $("#recipes").select2("data");
    // //     receips_txt = $("#recipes").text(),
    // //     medicine_item = [],
    // //     medicine_details = [],
    // //     medicine_detail_item = [],
    // //     medicine_list = [];
    // // for (var i = 0; i < receips.length; i ++) {
    // //     medicine_details = [];
    // //     $(".recipe_medicine_" + receips[i].id).each(function(index, obj){
    // //         medicine_detail_item = {
    // //             id : $(obj).find("input[name='medicine_id[]']").val(),
    // //             name :  $(obj).find("input[name='medicine_name[]']").val(),
    // //             mass : $(obj).find("input[name='mass[]']").val(),
    // //             price : $(obj).find("input[name='price[]']").val(),
    // //             unit : $(obj).find("input[name='unit[]']").val(),
    // //             max_weight : $(obj).find("input[name='max_weight[]']").val(),
    // //             min_weight : $(obj).find("input[name='min_weight[]']").val()
    // //         }
    // //         if (typeof medicine_detail_item.mass != "undefined") {
    // //             medicine_details.push(medicine_detail_item);
    // //         }
    // //     })
    // //     medicine_item = {
    // //         receip_id : receips[i].id,
    // //         receip_txt : receips[i].text,
    // //         medicines: medicine_details,
    // //         fu_number: $("input[name='fuNumber_" + receips[i].id + "']").val(),
    // //         total: $("input[name='totalPrice_" + receips[i].id + "']").val()
    // //     }
    // //     medicine_list.push(medicine_item);
    // // }
    // var medicine_list = getMedicineList();
    // // forms.append("medicines", JSON.stringify(medicine_list));
    //
    // var is_empty_medicine = false;
    // for (var i = 0; i < medicine_list.length; i ++) {
    //     if (medicine_list[i].medicines.length == 0)
    //         is_empty_medicine = true;
    // }
    // if (is_empty_medicine == true) {
    //     Swal.fire({
    //         type: 'error',
    //         title: '药材不能为空。'
    //     });
    //     hideOverlay();
    //     return ;
    // }

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
                window.location.href = '/doctor/qa/view';
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

function deleteQA(id, obj) {
    Swal.fire({
        title: "你确定要删除该问答吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        closeOnConfirm: true,
        closeOnCancel: true
    }).then(result => {
        if (result.value) {
            showOverlay();
            $.ajax({
                url: '/doctor/qa/delete',
                data: "id=" + id,
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'GET',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        Swal.fire({
                            type: 'success',
                            text: '',
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        qa_table.row($(obj).parents('tr'))
                            .remove()
                            .draw();
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
        }
    });
}

function appenItem(type) {
    var item = $("#"+type+"Input").val();
    if(item==''||item==undefined||item==null){
        Swal.fire({
            type: 'warning',
            title: '请输入项目名。',
            showConfirmButton: false,
            timer: 3000
        });
        $("#"+type+"Input").focus();
        return;
    }
    // var itemList;
    switch (type) {
        case 'biaozheng':
            itemList = biaozhengList;
            selectedList = selectedBiaozheng;
            break;
        case 'lizheng':
            itemList = lizhengList;
            selectedList = selectedLizheng;
            break;
        case 'biaoli':
            itemList = biaoliList;
            selectedList = selectedBiaoli;
            break;
        case 'maizheng':
            itemList = maizhengList;
            selectedList = selectedMaizheng;
            break;
        default:
            itemList = biaozhengList;
            selectedList = selectedBiaozheng;
    }

    if(itemList.includes(item)){
        Swal.fire({
            type: 'warning',
            title: '存在的项目。',
            showConfirmButton: false,
            timer: 3000
        });
        $("#"+type+"Input").focus();
        return;
    }
    items= item.split(',');
    itemList = itemList.concat(items);
    itemList = arrayUnique(itemList);
    var itemList = itemList.filter(function (el) {
        return el != null && el !='' && el != undefined;
    });

    drawItems(type,itemList,selectedList);
    $("#"+type+"Input").val('');
    switch (type) {
        case 'biaozheng':
            biaozhengList = itemList;
            break;
        case 'lizheng':
            lizhengList = itemList;
            break;
        case 'biaoli':
            biaoliList = itemList;
            break;
        case 'maizheng':
            maizhengList = itemList;
            break;
        default:
            biaozhengList = itemList;
    }

}
function removeItem(type) {
    switch (type) {
        case 'biaozheng':
            itemList = biaozhengList;
            selectedList = selectedBiaozheng;
            break;
        case 'lizheng':
            itemList = lizhengList;
            selectedList = selectedLizheng;
            break;
        case 'biaoli':
            itemList = biaoliList;
            selectedList = selectedBiaoli;
            break;
        case 'maizheng':
            itemList = maizhengList;
            selectedList = selectedMaizheng;
            break;
        default:
            itemList = biaozhengList;
            selectedList = selectedBiaozheng;
    }

    itemList  = arr_diff(itemList,selectedList);
    selectedList = [];
    switch (type) {
        case 'biaozheng':
            biaozhengList = itemList;
            selectedBiaozheng = [];
            break;
        case 'lizheng':
            lizhengList = itemList;
            selectedLizheng = [];
            break;
        case 'biaoli':
            biaoliList = itemList;
            selectedBiaoli = [];
            break;
        case 'maizheng':
            maizhengList = itemList;
            selectedMaizheng = [];
            break;
        default:
            biaozhengList = itemList;
            selectedBiaozheng = [];
    }
    drawItems(type,itemList,selectedList);
}


function addMedicineInModal() {
    selectedMedicine_id = $("#medicine").val();
    selectedMedicine_name = $("#medicine option:selected").text();
    if(selectedMedicine_id<1) return;
    var min_weight =  $("#medicine option:selected").data("min");
    var max_weight =  $("#medicine option:selected").data("max");
    var price = $("#medicine option:selected").data("price");
    var unit = $("#medicine option:selected").data("unit");
    var weight = max_weight==min_weight?max_weight:0;

    medicine_detail_item = {
        medicine_id : selectedMedicine_id,
        medicine :  selectedMedicine_name,
        weight : weight,
        price : price,
        unit : unit,
        max_weight : max_weight,
        min_weight : min_weight
    };
    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);
    for(var i = 0 ; i < recipeDatas.length;i++){
        if(recipeDatas[i].id==selectedRecipeId){
            medicine_temp = JSON.parse(recipeDatas[i].medicine);
            medicine_temp.push(medicine_detail_item);
            recipeDatas[i].medicine = JSON.stringify(medicine_temp);
            break;
        }
    }
    drawMedicine(recipeDatas,false,true);
    $("#medicines").val(JSON.stringify(recipeDatas));

    $("#medicine option:selected").attr("disabled","disabled");
    $("#medicine").prop("selectedIndex",-1);
    // changeMaxMinValue();
    getContraryIds(selectedMedicine_id,function(contraryIds){
        for(var i=0; i < contraryIds.length; i++){
            $("#medicine option[value='"+contraryIds[i]+"']").attr("disabled","disabled");
        }
        $(".select2").select2({
            placeholder:"请选择"
        });

    });

}



function getMedicineList(){
    var receips = $("#recipes").select2("data"),
        receips_txt = $("#recipes").text(),
        medicine_item = [],
        medicine_details = [],
        medicine_detail_item = [],
        medicine_list = [];
    for (var i = 0; i < receips.length; i ++) {
        medicine_details = [];
        $(".recipe_medicine_" + receips[i].id).each(function(index, obj){
            medicine_detail_item = {
                medicine_id : $(obj).find("input[name='medicine_id[]']").val(),
                medicine :  $(obj).find("input[name='medicine_name[]']").val(),
                weight : $(obj).find("input[name='weight[]']").val(),
                price : $(obj).find("input[name='price[]']").val(),
                unit : $(obj).find("input[name='unit[]']").val(),
                max_weight : $(obj).find("input[name='max_weight[]']").val(),
                min_weight : $(obj).find("input[name='min_weight[]']").val()
            }
            if (typeof medicine_detail_item.weight != "undefined") {
                medicine_details.push(medicine_detail_item);
            }
        });
        medicine_item = {
            id : receips[i].id,
            prescription_name : receips[i].text,
            medicine: JSON.stringify(medicine_details),
            fu_number: $("input[name='fuNumber_" + receips[i].id + "']").val(),
            total: $("input[name='totalPrice_" + receips[i].id + "']").val()
        }
        medicine_list.push(medicine_item);
    }
    return medicine_list;
}



