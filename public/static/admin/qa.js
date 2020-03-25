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
            {data:'doctor_name'},
            {data:'id'}
            ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[5],
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
        drawRecipeSections(recipes);
    });
    if(medicines.length){
        var recipes = $("#recipes").val();

        drawRecipeSections(recipes);

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

    var receips = $("#recipes").select2("data");
    //     receips_txt = $("#recipes").text(),
    //     medicine_item = [],
    //     medicine_details = [],
    //     medicine_detail_item = [],
    //     medicine_list = [];
    // for (var i = 0; i < receips.length; i ++) {
    //     medicine_details = [];
    //     $(".recipe_medicine_" + receips[i].id).each(function(index, obj){
    //         medicine_detail_item = {
    //             id : $(obj).find("input[name='medicine_id[]']").val(),
    //             name :  $(obj).find("input[name='medicine_name[]']").val(),
    //             mass : $(obj).find("input[name='mass[]']").val(),
    //             price : $(obj).find("input[name='price[]']").val(),
    //             unit : $(obj).find("input[name='unit[]']").val(),
    //             max_weight : $(obj).find("input[name='max_weight[]']").val(),
    //             min_weight : $(obj).find("input[name='min_weight[]']").val()
    //         }
    //         if (typeof medicine_detail_item.mass != "undefined") {
    //             medicine_details.push(medicine_detail_item);
    //         }
    //     })
    //     medicine_item = {
    //         receip_id : receips[i].id,
    //         receip_txt : receips[i].text,
    //         medicines: medicine_details,
    //         fu_number: $("input[name='fuNumber_" + receips[i].id + "']").val(),
    //         total: $("input[name='totalPrice_" + receips[i].id + "']").val()
    //     }
    //     medicine_list.push(medicine_item);
    // }
    var medicine_list = getMedicineList();
    // forms.append("medicines", JSON.stringify(medicine_list));

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
                // window.location.href = '/admin/qa/view';
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

function drawRecipeSections(recipes) {
    var temp = medicines;
    if(recipes==''||recipes==null||recipes==undefined){
        temp = medicines= [];
        $("#medicineSection").html("");
        $("#medicines").val('');
        return;
    }

    medicines = [];
    for(var i=0; i < recipes.length ; i++){
        if(temp[recipes[i]]==undefined){
            medicines[recipes[i]] = 0;
            temp[recipes[i]] = 0;
        }
        else
            medicines[recipes[i]] = temp[recipes[i]];
    }
    showOverlay();
    $.ajax({
        url: '/doctor/qa/getRecipes',
        data: "recipes=" + recipes,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                var strMedicines = $("#medicines").val();
                if(strMedicines=='')
                    originalMedicines = [];
                else
                    originalMedicines = JSON.parse(strMedicines);

                var drawRecipes = getDrawData(originalMedicines,resp.data);
                var html=drawMedicine(drawRecipes);
                $("#medicineSection").html(html);
                nowRecipes = getMedicineList();
                $("#medicines").val(JSON.stringify(nowRecipes));
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

function changeFuNumber(obj) {
    var id = obj.id.replace('fuNumber_','');
    medicines[id] = obj.value;
    $("#fuDaiNumber").val(JSON.stringify(medicines));
}
function addMedicineInModal() {
    selectedMedicine_id = $("#medicine").val();
    selectedMedicine_name = $("#medicine option:selected").text();
    if(selectedMedicine_id<1) return;
    var min_weight =  $("#medicine option:selected").data("min");
    var max_weight =  $("#medicine option:selected").data("max");
    var price = $("#medicine option:selected").data("price");
    var unit = $("#medicine option:selected").data("unit");

    var html="<div id='recipe_medicine_" + selectedRecipeId + "' class='recipe_medicine_" + selectedRecipeId + "'><div class=\"row\">\n" +
        "  <div class='col-sm-2'></div>  <label class=\"col-1 col-form-label text-right\">\n" +
        "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicineQA(" + selectedRecipeId + ", this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+
        "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
        "    <div class=\"col-2\">\n" +
        "        <input class=\"form-control\" type=\"number\" value=\"0\" onchange='calcPrice(" + selectedRecipeId + ")' name=\"mass[]\" id=\"weight_"+selectedMedicine_id+"\">\n" +
        "    </div>\n" +
        "<div class=\"col-4 text-left\">\n" +
        "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" 元/10g (最小："+min_weight+", 最大："+ max_weight+")</label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
        "<input type='hidden' name='unit[]' value='"+unit+"' />"+
        "</div>\n"+
        "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
        "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
        "</div>\n" +
        "</div>";
    if ($("#recipe_medicine_"+selectedRecipeId).length > 0)
        $("#recipe_medicine_"+selectedRecipeId).after(html);
    else
        $("#medicineSection hr").after(html);
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

function calcPrice(recipe_id) {
    var totalPrice = 0,
        amount = 0,
        price_per_unit = 0,
        amount_per_unit = 0,
        unit = "",
        mass_obj = "",
        price_obj = "",
        unit_obj = "";
    $(".recipe_medicine_" + recipe_id).each(function(index, obj){
        mass_obj = $(obj).find("input[name='weight[]']");
        price_obj = $(obj).find("input[name='price[]']");
        unit_obj = $(obj).find("input[name='unit[]']");
        amount = parseInt($(mass_obj).val());
        price_per_unit = parseInt($(price_obj).val());
        unit = $(unit_obj).val();
        amount_per_unit = unit=="null"||unit==null||unit==''||unit==undefined||unit=='公克' ? 10 : 1;
        totalPrice += amount * (price_per_unit / amount_per_unit);
    });

    $("#totalPrice_" + recipe_id).val(totalPrice);

}

function removeMedicineQA(recipeId, obj) {
    var id = $(obj).data("index");
    $("#medicine option[value='" + id + "']").attr("disabled",false);
    var row = $(obj).parent().parent().parent();
    row.remove();

    calcPrice(recipeId);
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

function drawMedicine(data) {
    var html = '';
    for(var i=0; i < data.length; i++){
        html += ' <h4 class="text-bold">'+data[i].prescription_name+' <button type="button" class="btn btn-circle btn-warning p-0-0"  title="添加药材" onclick="setRecipeId('+ data[i].receip_id + ');"><i class="fas fa-plus"></i></button></h4><hr>'
        var dbmedicines = JSON.parse(data[i].medicine);
        for(var j=0; j<dbmedicines.length; j++){
            html+='<div id="recipe_medicine_' + dbmedicines[j].medicine_id + '_'+data[i].id+'" class="recipe_medicine_' + data[i].id + '">';
            var min_weight =  dbmedicines[j].min_weight;
            var max_weight =  dbmedicines[j].max_weight;
            var weight = dbmedicines[j].weight==undefined?0:dbmedicines[j].weight;
            if(dbmedicines[j].weight==undefined&&dbmedicines[j].min_weight==dbmedicines[j].max_weight)
                weight = dbmedicines[j].max_weight;
            var price = dbmedicines[j].price;
            var unit = dbmedicines[j].unit;
            selectedMedicine_id = dbmedicines[j].medicine_id;
            selectedMedicine_name = dbmedicines[j].medicine;

            var unitLable = unit==null||unit==''||unit==undefined||unit=='公克'?' 元/10g':unit=='两'?' 元/两':('元/'+unit);

            html+="<div class=\"row\">\n" +
                "   <div class='col-sm-2'></div> <label class=\"col-1 col-form-label text-right\">\n" +
                "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicineQA(" + data[i].id + ", this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+
                "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
                "    <div class=\"col-2\">\n" +
                "        <input class=\"form-control\" type=\"number\" value=\""+weight+"\" name=\"weight[]\" min=\"0\" onchange='calcPrice(" + data[i].id + ")'  id=\"weight"+selectedMedicine_id+"\">\n" +
                "    </div>\n" +
                "<div class=\"col-4 text-left\">\n" +
                "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" "+unitLable+" (最小："+min_weight+", 最大："+ max_weight+") </label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
                "<input type='hidden' name='unit[]' value='"+dbmedicines[j].unit+"' />"+
                "</div>\n"+
                "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
                "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
                "</div></div>\n"
            html +='</div>';

        }
        html+='<div class="row"> <div class="col-sm-3 p-r-0 text-right col-form-label">1副</div> ' +
            '<div class="col-sm-2"><input type="number" onchange="changeFuNumber(this)" name="fuNumber_'+data[i].id+'" id="fuNumber_'+data[i].id+'" class="form-control" value="' + medicines[data[i].id] + '" /></div>' +
            '<div class="col-sm-2 col-form-label p-l-0"><label style="line-height: 25px;">代</label></div></div>';
        html += '<div class="row"><div class="col-sm-3 p-r-0 text-right col-form-label">总价</div> ' +
            '<div class="col-sm-2"><input type="number" disabled name="totalPrice_'+data[i].id+'" id="totalPrice_'+data[i].id+'" class="form-control" value="' + medicines[data[i].id] + '" /><label></label></div>' +
            '</div>';
    }
    return html;
}

function getDrawData(orginalRecipes,newRecipes){
    if(newRecipes.length>orginalRecipes.length){
        for(var i=0; i < orginalRecipes.length; i++){
            if(checkMedicineContain(orginalRecipes[i].id,newRecipes)){
                newRecipes.splice(i,1);
            }
        }
        var result = orginalRecipes.concat(newRecipes);
    }else{
        var result = [];
        for(var i=0; i<orginalRecipes.length; i++){
            if(checkMedicineContain(orginalRecipes[i].id,newRecipes)){
                result.push(orginalRecipes[i]);
            }
        }
    }
    return result;

}
function checkMedicineContain(id,Recipes){
    for(var i=0; i < Recipes.length; i++){
        if(id==Recipes[i].id)
            return true;
    }
    return false;
}
