var selectedBiaozheng = [],selectedLizheng=[],selectedBiaoli=selectedMaizheng =[];

function showOverlay(text='') {
    $.LoadingOverlay("show", {
        image: "",
        fontawesome: "fa fa-spinner fa-spin",
        fontawesomeColor: "#e46a76",
        size: 3,
        text: text,
        textColor: "#e46a76"
    });
}

function hideOverlay() {
    $.LoadingOverlay("hide");
}

function showErrorToast(msg) {
    $.toast({
        heading: '错误',
        text: msg,
        position: 'top-right',
        loaderBg:'#ff6849',
        icon: 'error',
        hideAfter: 5000
    });
}

const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'AUD',
    minimumFractionDigits: 2
});

function formatPrice(val) {
    if (val > 0)
        return '$' + val;

    if (val == 0)
        return '-';

    if (val < 0)
        return '($' + Math.abs(val) + ')';
}
$(function(){
    $("#department").on("change",function(){
        var department_id = $(this).val();
        showOverlay();
        $.ajax({
            url:'/doctor/getDoctors',
            type:'GET',
            data: "department_id=" + department_id,
            cache: false,
            dataType: 'json',
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == '0') {
                    var doctors = resp.data;
                    var html = '<option value="">请选择医生</option>';
                    for(var i = 0 ; i < doctors.length; i++){
                        html +='<option value="' + doctors[i].id+'" data-from="'+doctors[i].from+'" data-to="'+doctors[i].to+'">' + doctors[i].name + '</option>';
                    }
                    $('#doctor_id').html(html);
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

        })
    });

    var department_id = $("#department").val();
    if(department_id==''||department_id==null||department_id==undefined)
        $("#department").trigger("change");
});
function removeMedicine(obj) {
    var id = $(obj).data("index");
    $("#medicine option[value='" + id + "']").attr("disabled",false);
    var row = $(obj).parent().parent();
    row.remove();
    calcPrice();
}

function getContraryMedicines(medicine_id,callback){
    showOverlay();
    $.ajax({
        url:'/doctor/getContraryMedicines',
        type:'GET',
        data: "medicine_id=" + medicine_id,
        cache: false,
        dataType: 'json',
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                callback(resp);
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

    })
}
function changeMaxMinValue(){
    return;

    $('input[type="number"]').on('change',function(){
        v = parseInt($(this).val());
        min = parseInt($(this).attr('min'));
        max = parseInt($(this).attr('max'));
        if (v < min){
            $(this).val(min);
        } else if (v > max){
            $(this).val(max);
        }
    })
}

function getStateWord(data) {
    switch (data) {
        case 'ACCEPT':
            return '接受';
            break;
        case 'WAITING_TREATMENT':
            return '等待治疗';
            break;
        case 'TREATING':
            return '在治疗中';
            break;
        case 'BEFORE_TREATING_PAY':
            return '结束治疗';
            break;
        case 'AFTER_TREATING_PAY':
            return '结束治疗付款';
            break;
        case 'CLOSE':
            return '结束';
            break;
        default:
            return '';
    }
}
var monthLabel = {
    'January':'一月',
    'February':'二月',
    'March':'三月',
    'April':'四月',
    'May':'五月',
    'June':'六月',
    'July':'七月',
    'August':'八月',
    'September':'九月',
    'October':'十月',
    'November':'十一月',
    'December':'十二月'
}
function scrollSidebarTop(){
    if (navigator.userAgent.indexOf("Chrome") !== -1){
        $(".sidebar-menu").scrollTop($(".sidebar-menu").prop("scrollHeight")-700);
        $(".sidebar-menu").perfectScrollbar('update');
    }else if (navigator.userAgent.indexOf("Mozilla") !== -1){
        $(".sidebar-menu").scrollTop($(".sidebar-menu").prop("scrollHeight"));
        $(".sidebar-menu").perfectScrollbar('update');
    }
}
function getContraryIds(medicine_id,callback){
    showOverlay();
    $.ajax({
        url: '/doctor/history/getContraryIds',
        data: 'id='+medicine_id,
        type: 'GET',
        cache: false,
        dataType: 'json',
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                callback(resp.data);
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

function drawItems(type,itemList,selectedList, trigger = true) {
    var html = '<div class="form-group mt-3 row">';
    html+='<div class="col-sm-3">'
    var remain = itemList.length % 4;
    for(var i=0; i < itemList.length; i++){
        var checked = selectedList.includes(itemList[i])?"checked":'';
        html+='\t<div class="custom-control custom-checkbox">\n' +
            '\t\t<input type="checkbox" class="custom-control-input '+type+'" id="'+type+'_'+i+'" name="'+type+'[]" onclick="drawTags()" value="'+itemList[i]+'" '+ checked +'>\n' +
            '\t\t<label class="custom-control-label" for="'+type+'_'+i+'">'+itemList[i]+'</label>\n' +
            '\t</div>\n';
        if((Math.floor(itemList.length/4) + remain>0?1:0) ==(i+1)||(2*Math.floor(itemList.length/4) + remain>1?1:0 ==(i+1))||(3*Math.floor(itemList.length/4)+ + remain>2?1:0)==(i+1))
            html+='</div>\n' +
                '<div class="col-sm-3">\n';
    }
    html+='</div>\n' +
        '</div>';
    $("#"+type+"Section").html(html);
    if (trigger == true)
        changeTrigger();
}
function drawZhengSection(type,itemList,selectedList) {
    var selectHtml = '<label for="example-text-input" class="col-2 col-form-label text-right"></label>\n' +
        '<div class="col-6">\n' +
        '\t<select class="m-b-10 form-control select2 select2-multiple" style="width: 100%" data-placeholder="Choose" multiple="multiple" name="'+type+'[]" id="'+type+'">\n' +
        '\t</select>\n' +
        '</div>\n';
    $("#"+type+"Section").html(selectHtml);
    for(var i=0; i < itemList.length; i++){
        var checked = selectedList.includes(itemList[i])?"checked":'';
        $("#"+type).append(new Option(itemList[i],itemList[i],false,checked));
    }

    $("#"+type).select2();
}
function arrayUnique(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
}

function changeTrigger() {
    $( "input[type='checkbox']" ).on('change',function(e){
        var elName = this.name;
        var itemList;
        switch (elName) {
            case 'biaozheng[]':
                itemList = selectedBiaozheng;
                break;
            case 'lizheng[]':
                itemList = selectedLizheng;
                break;
            case 'biaoli[]':
                itemList = selectedBiaoli;
                break;
            case 'maizheng[]':
                itemList = selectedMaizheng;
                break;
            default:
                itemList = selectedBiaozheng;
        }
        if(this.checked)
            itemList.push(this.value);
        else{
            var index = itemList.indexOf(this.value);
            if (index !== -1) itemList.splice(index, 1);
        }

        switch (elName) {
            case 'biaozheng[]':
                selectedBiaozheng = itemList;
                break;
            case 'lizheng[]':
                selectedLizheng = itemList;
                break;
            case 'biaoli[]':
                selectedBiaoli = itemList;
                break;
            case 'maizheng[]':
                selectedMaizheng = itemList;
                break;
            default:
                selectedBiaozheng = itemList;
        }
    })
}
function arr_diff (a1, a2) {

    var a = [], diff = [];

    for (var i = 0; i < a1.length; i++) {
        a[a1[i]] = true;
    }

    for (var i = 0; i < a2.length; i++) {
        if (a[a2[i]]) {
            delete a[a2[i]];
        } else {
            a[a2[i]] = true;
        }
    }

    for (var k in a) {
        diff.push(k);
    }

    return diff;
}

function drawMedicine(data,inquiry=false,appendable,inquiry_detail=false) {
    var html = '';
    for(var i=0; i < data.length; i++){
        var shifouhefangChecked = data[i].shifouhefang?'checked':'';

        if (appendable)
            plusButton ='<button type="button" class="btn btn-circle btn-warning p-0-0"  title="添加药材" onclick="setRecipeId(\''+ data[i].id + '\');"><i class="fas fa-plus"></i></button>';
        else
            plusButton = '';
        html += ' <h4 class="text-bold">'+data[i].prescription_name+plusButton+' </h4><hr>'
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
            var disabled = appendable==false?"disabled='disabled'":"";
            var unitLable = unit==null||unit==''||unit==undefined||unit=='公克'?' 元/1g':unit=='两'?' 元/两':('元/'+unit);
            price = unit=='公克'?price/10:price;


            var maxMin = data[i].shifouhefang!=true?"max='"+max_weight+"' min='"+min_weight+"'":'';
            html+="<div class=\"row\">\n" +
                "   <div class='col-sm-1'></div> <label class=\"col-2 col-form-label text-right\">\n" +
                "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicineQA('" + data[i].id + "', this,"+inquiry+","+appendable+");\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+
                "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
                "    <div class=\"col-2\">\n" +
                "        <input class=\"form-control\" type=\"number\" value=\""+weight+"\" "+maxMin+" name=\"weight[]\" onchange='setWeight(\"" + data[i].id + "\",this,"+dbmedicines[j].medicine_id+","+inquiry+","+appendable+")'  id=\"weight"+data[i].id+'_'+selectedMedicine_id+"\" " + disabled + " >\n" +
                "    </div>\n" +
                "<div class=\"col-6 text-left\">\n" +
                "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" "+unitLable+" (最小："+min_weight+", 最大："+ max_weight+") </label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
                "<input type='hidden' name='unit[]' value='"+dbmedicines[j].unit+"' />"+
                "</div>\n"+
                "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
                "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
                "</div></div>\n"
            html +='</div>';

        }

        if(data[i].id=='hefang')
            hefangdisable = 'disabled';
        else hefangdisable = '';
        var shifouhefangHtml = '<div class="row">\n' +
            '   <div class="col-sm-3"></div>\n' +
            '   <div class="col-8">\n' +
            '        <div class="custom-control custom-checkbox">\n' +
            '\t\t<input type="hidden" class="custom-control-input" name="houfang[]" value="'+data[i].shifouhefang+'">\n' +
            '\t\t<input type="checkbox" class="custom-control-input" id="houfang_'+data[i].id+'"'+shifouhefangChecked+' onclick="sethoufang(this)" '+hefangdisable+'>\n' +
            '\t\t<label class="custom-control-label" for="houfang_'+data[i].id+'">是否合方</label>\n' +
            '\t\t</div>\n' +
            '    </div>\n' +
            '</div>';

        if(inquiry)
            html += shifouhefangHtml;

        if(inquiry==true)
            fuhtml = '<div class="col-sm-1"></div> <div class="col-sm-2 text-right"><input type="number" class="form-control text-right" onchange="changeFuNumber(this)" name="fuNumber_' +data[i].id+'" id="fuNumber_'+data[i].id+'" min="1" value="'+(data[i].fuNumber==undefined||data[i].fuNumber==null?1:data[i].fuNumber)+'"/></div><div class="text-left col-form-label">副</div>' ;
        else
            fuhtml = '<div class="col-sm-3 p-r-0 text-right col-form-label">1副</div>';

        // if(inquiry==true){
            otherHtml = '<div class="row mt-3">\n' +
                '   <div class="col-sm-1"></div> \n' +
                '   <label class="col-2 col-form-label text-right mt-3">其他病症</label>\n' +
                '    <div class="col-7">\n' +
                '        <textarea class="form-control" type="text" disabled rows="3">'+data[i].other_condition+'</textarea>\n' +
                '    </div>\n' +
                '</div>' ;
            html+=otherHtml;
            eatingHtml = '<div class="row mt-3">\n' +
                '   <div class="col-sm-1"></div> \n' +
                '   <label class="col-2 col-form-label text-right mt-3">煎服法</label>\n' +
                '    <div class="col-7">\n' +
                '        <textarea class="form-control" type="text" disabled rows="3">'+data[i].eating_method+'</textarea>\n' +
                '    </div>\n' +
                '</div>' ;
            html+=eatingHtml;
            var ban = data[i].ban=='null'||data[i].ban=='undefined'?'':data[i].ban;
            ban = ban==null?'':ban;
            banHtml = '<div class="row mt-3">\n' +
                '   <div class="col-sm-1"></div> \n' +
                '   <label class="col-2 col-form-label text-right mt-3">禁忌</label>\n' +
                '    <div class="col-7">\n' +
                '        <textarea class="form-control" type="text" disabled rows="3">'+ban+'</textarea>\n' +
                '    </div>\n' +
                '</div>' ;
            html+=banHtml;
        // }



        html+='<div class="row mt-3">' + fuhtml +
            '<div class="col-sm-2"><input type="number" onchange="changeDaiNumber(this)" name="daiNumber_'+data[i].id+'" id="daiNumber_'+data[i].id+'" class="form-control" '+disabled+' min="1" value="' + (data[i].daiNumber==undefined||data[i].daiNumber==null||data[i].daiNumber<1?1:data[i].daiNumber) + '" /></div>' +
            '<div class="col-sm-2 col-form-label p-l-0"><label style="line-height: 25px;">代</label></div></div>';
        html += '<div class="row"><div class="col-sm-3 p-r-0 text-right col-form-label">总价</div> ' +
            '<div class="col-sm-2"><input type="text" disabled name="totalPrice_'+data[i].id+'" id="totalPrice_'+data[i].id+'" class="form-control" value="' + data[i].price + '" /><label></label></div>' +
            '</div>';
    }
    $("#medicineSection").html(html);
}
function calcPrice(strMedicines) {
    if(strMedicines==undefined||strMedicines==''||strMedicines==null)
        return 0;

    if(strMedicines=='hefang')
        return 0;
    var totalPrice = 0;
    var tempMedicines = JSON.parse(strMedicines);
    for(var i = 0 ; i < tempMedicines.length; i++){
        unit = tempMedicines[i].unit;
        amount_per_unit = unit=="null"||unit==null||unit==''||unit==undefined||unit=='公克' ? 10 : 1;
        totalPrice += tempMedicines[i].weight*tempMedicines[i].price/amount_per_unit;
    }
    return totalPrice;
}
function drawRecipeSections(recipes,inquiry) {
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
        url: '/doctor/history/getRecipes',
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
                drawMedicine(drawRecipes,inquiry,true);
                // nowRecipes = getMedicineList();
                $("#medicines").val(JSON.stringify(drawRecipes));
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
function getDrawData(orginalRecipes,newRecipes){
    if(newRecipes.length>=orginalRecipes.length){//add a recipe
        var result = orginalRecipes;
        for(var i=0; i < newRecipes.length; i++){
            if(!checkMedicineContain(newRecipes[i].id,orginalRecipes)){
                newRecipes[i].daiNumber = 0;
                var tempMedicine = JSON.parse(newRecipes[i].medicine);
                for(var j=0; j<tempMedicine.length; j++){
                    var weight = tempMedicine[j].weight==undefined?0:tempMedicine[j].weight;
                    if(tempMedicine[j].weight==undefined&&tempMedicine[j].min_weight==tempMedicine[j].max_weight)
                        weight = tempMedicine[j].max_weight;
                    tempMedicine[j].weight = weight;
                }
                newRecipes[i].medicine = JSON.stringify(tempMedicine);
                newRecipes[i].price = calcPrice(newRecipes[i].medicine);
                if(orginalRecipes.length>0){
                    if(orginalRecipes[orginalRecipes.length-1].id=='hefang'){
                        temp = orginalRecipes[orginalRecipes.length-1];
                        result.pop();
                        result.push(newRecipes[i]);
                        result.push(temp);
                    }else
                        result.push(newRecipes[i]);
                }else
                    result.push(newRecipes[i]);
                break;
            }
        }
    }else {//delete a recipe
        var result = [];
        for(var i=0; i<orginalRecipes.length; i++){
            if(checkMedicineContain(orginalRecipes[i].id,newRecipes)||orginalRecipes[i].id=='hefang'){
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
function setWeight(recipe_id,obj,medicine_id,inquiry,appendable) {
    var weight = parseInt($(obj).val());
    // $("medicines")
    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);
    for(var i = 0 ; i < recipeDatas.length; i++){
        if(recipeDatas[i].id==recipe_id){
            tempMedicines = JSON.parse(recipeDatas[i].medicine);
            for(var j=0; j<tempMedicines.length;j++){
                if(tempMedicines[j].medicine_id==medicine_id){
                    tempMedicines[j].weight = weight;
                    break;
                }
            }
            recipeDatas[i].medicine = JSON.stringify(tempMedicines);
            recipeDatas[i].price = calcPrice(recipeDatas[i].medicine);
            break;
        }
    }
    $("#medicines").val(JSON.stringify(recipeDatas));
    drawMedicine(recipeDatas,inquiry,appendable);
    calcPriceTotal();
}
function changeDaiNumber(obj) {
    var id = obj.id.replace('daiNumber_','');
    var daiNumber = $(obj).val();
    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);
    for(var i = 0 ; i < recipeDatas.length; i++){
        if(recipeDatas[i].id==id){
            recipeDatas[i].daiNumber = daiNumber;
            break;
        }
    }
    $("#medicines").val(JSON.stringify(recipeDatas));
}
function setRecipeId(id){
    selectedRecipeId = id;
    $("#myModal").modal('show');
    $("#medicine option").attr("disabled",false);

    var sel_medicineIds = [];
    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);
    for(var i=0; i < recipeDatas.length; i++){
        if(recipeDatas[i].id==id){
            var tempMedicine = JSON.parse(recipeDatas[i].medicine);
            for(var j = 0 ; j < tempMedicine.length;j++){
                $("#medicine option[value='"+tempMedicine[j].medicine_id+"']").attr("disabled",true);
                getContraryIds(tempMedicine[j].medicine_id,function(contraryIds){
                    for(var i=0; i < contraryIds.length; i++){
                        $("#medicine option[value='"+contraryIds[i]+"']").attr("disabled","disabled");
                    }
                    $(".select2").select2({
                        placeholder:"请选择"
                    });

                });

            }
        }
    }

}
function addMedicineInModal(inquiry) {
    selectedMedicine_id = $("#medicine").val();
    selectedMedicine_name = $("#medicine option:selected").text();
    if(selectedMedicine_id<1) return;
    var min_weight =  $("#medicine option:selected").data("min");
    var max_weight =  $("#medicine option:selected").data("max");
    var price = $("#medicine option:selected").data("price");
    var unit  = $("#medicine option:selected").data("unit");
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
    drawMedicine(recipeDatas,inquiry,true);
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
        $("ul li").hover(function (e)
        {
            var $target = $(e.target);
            if($target.is('option')){
                console.log($target.attr("id"));//Will alert id if it has id attribute
                console.log($target.text());//Will alert the text of the option
                console.log($target.val());//Will alert the value of the option
            }
        });

    });

}

function calcPriceTotal(){
    var totalPrice = 0;
    var recipeDatas = JSON.parse($("#medicines").val());
    for(var i=0; i < recipeDatas.length; i++){
        var fuNumber = recipeDatas[i].fuNumber!=undefined&&recipeDatas[i].fuNumber!=null&&recipeDatas[i].fuNumber!=''?recipeDatas[i].fuNumber:1;
        if(recipeDatas[i].shifouhefang!=true)
            totalPrice += calcPrice(recipeDatas[i].medicine)*fuNumber;
    }
    $("#total_price").val(totalPrice);
    $("#total_price_span").html(totalPrice);
}
function removeMedicineQA(recipeId, obj,inquiry,appendable) {
    var id = $(obj).data("index");

    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);
    for(var i = 0 ; i < recipeDatas.length;i++){
        if(recipeDatas[i].id==recipeId){
            medicine_temp = JSON.parse(recipeDatas[i].medicine);
            for(var j=0; j<medicine_temp.length;j++){
                if(medicine_temp[j].medicine_id==id){
                    medicine_temp.splice(j,1);
                    break;
                }
            }
            recipeDatas[i].medicine = JSON.stringify(medicine_temp);
            recipeDatas[i].price = calcPrice(recipeDatas[i].medicine);
            break;
        }
    }
    drawMedicine(recipeDatas,inquiry,appendable);
    $("#medicines").val(JSON.stringify(recipeDatas));
    calcPrice(recipeId);
    calcPriceTotal();
}
function searchRecipes(callback=null){

    $("#recipe").prop('disabled',false);
    var data = $("#question_title").val();
    if(data==''||data==null||data==undefined) {

        drawRecipeSections([], [], [], [], []);
        drawRecipeSectionsOther([], [], [], [], []);

        var forms = new FormData($("#question-form")[0]);

        showOverlay();
        $.ajax({
            url: '/doctor/inquiry/getRecipeOther',
            data: forms,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    drawRecipeOther(resp.data);

                    if(callback!=null)
                        callback();
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

    } else {
        return;
    }

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

    // for(var i=0; i < recipes.length; i++){
    //     calcPriceOther(recipes[i].id);
    // }
}
function drawRecipeOther(recipes){
    var html = '',
        recipe_list = [];
    if (recipes.length > 0)
        recipe_list = recipes;
    for (var i = 0; i < recipe_list.length; i ++) {
        html += '<option onmouseover="showToolTip()" value="' + recipe_list[i].id+'" data-medicine=\'' + recipe_list[i].medicine + '\' data-otherCondition=\'' +
            recipe_list[i].other_condition +
            '\' data-eating_method=\'' + recipe_list[i].eating_method +
            '\' data-price=\'' + recipe_list[i].price +
            '\' data-maizheng=\'' + recipe_list[i].maizheng +
            '\' data-ban=\'' + recipe_list[i].ban + '\'>'+recipe_list[i].prescription_name+'</option>';
    }
    $("#recipe").html(html);
}

function showToolTip(spanId){
    var flag = '';
    if(spanId.startsWith('select2-recipe-result-'))
        flag='recipe';
    else if(spanId.startsWith('select2-search_history-result-'))
        flag='search_history';
    spanId = spanId.replace("select2-recipe-result-", "");
    spanId = spanId.replace("select2-search_history-result-","");
    arrSpanId = spanId.split("-");
    var id = arrSpanId[1];
    if(Number.isInteger(parseInt(id))!=true)
        return;
    if(flag=='recipe'){
        var medicine = $("#recipe").children('option[value="'+id+'"]').data('medicine');
        var other_condition = $("#recipe").children('option[value="'+id+'"]').data('othercondition');
        var eating_method = $("#recipe").children('option[value="'+id+'"]').data('eating_method');
        var ban = $("#recipe").children('option[value="'+id+'"]').data('ban');
        var recipe_price = $("#recipe").children('option[value="'+id+'"]').data('price');
        var maizheng = $("#recipe").children('option[value="'+id+'"]').data('maizheng');
        var recipe_name = $("#recipe").children('option[value="'+id+'"]').html();


        html = '';
        var dbmedicines = medicine;
        for(var j=0; j<dbmedicines.length; j++){
            html+='<div class="recipe_medicine_">';
            var min_weight =  dbmedicines[j].min_weight;
            var max_weight =  dbmedicines[j].max_weight;
            var weight = dbmedicines[j].weight==undefined?0:dbmedicines[j].weight;
            if(dbmedicines[j].weight==undefined&&dbmedicines[j].min_weight==dbmedicines[j].max_weight)
                weight = dbmedicines[j].max_weight;
            var price = dbmedicines[j].price;
            var unit = dbmedicines[j].unit;
            selectedMedicine_id = dbmedicines[j].medicine_id;
            selectedMedicine_name = dbmedicines[j].medicine;
            var unitLable = unit==null||unit==''||unit==undefined||unit=='公克'?' 元/1g':unit=='两'?' 元/两':('元/'+unit);
            price = unit=='公克'?price/10:price;


            html+="<div class=\"row\">\n" +
                " <label class=\"col-2 col-form-label text-right\">\n" +
                "         &nbsp;"+selectedMedicine_name+
                "<input type='hidden' name='medicine_id[]' value='"+selectedMedicine_id+"' /><input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
                "<div class=\"col-10 text-left\">\n" +
                "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" "+unitLable+" (最小："+min_weight+", 最大："+ max_weight+") </label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
                "<input type='hidden' name='unit[]' value='"+dbmedicines[j].unit+"' />"+
                "</div>\n"+
                "<input class=\"form-control\" type=\"hidden\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
                "<input class=\"form-control\" type=\"hidden\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
                "</div></div>\n"
            html +='</div>';

        }

        $("#modal_recipe_name").html(recipe_name);
        $("#modal_medicines").html(html);
        $("#modal_other_condition").html(other_condition);
        $("#modal_eatting_method").html(eating_method);
        $("#modal_price").html(recipe_price);
        $("#modal_maizheng").html(maizheng);
        $("#modal_ban").html(ban);
        $("#tipModal").modal('show');
    }else if(flag=='search_history'){
        $("#historyModal").modal('show');
        var doctor_name = $("#search_history").children('option[value="'+id+'"]').data('doctor');
        var treat_start = $("#search_history").children('option[value="'+id+'"]').data('treat_start');
        var price = $("#search_history").children('option[value="'+id+'"]').data('price');
        $("#doctor_name").html(doctor_name);
        $("#treat_start").html(treat_start);
        $("#total_price_modal").html(price);

    }

}
function hideTooltip(){
    $("#tipModal").modal('hide');
    $("#historyModal").modal('hide');
}
function getHefang() {
    var otherCondition=eatting_method=ban='';
    var hefangMedicines =temp = [];
    var strMedicins = $("#medicines").val();
    var recipeDatas = JSON.parse(strMedicins);
    var recipeIds = '';
    for(var i = 0 ; i < recipeDatas.length; i++){
        recipeIds +=recipeDatas[i].id+',';
        if(recipeDatas[i].shifouhefang==true){
            var tempMedicines = JSON.parse(recipeDatas[i].medicine);
            temp = temp.concat(tempMedicines);
            otherCondition += recipeDatas[i].other_condition==null?'':recipeDatas[i].other_condition+"\n";
            eatting_method += recipeDatas[i].eating_method==null?'':recipeDatas[i].eating_method + "\n";
            ban += recipeDatas[i].ban==null?'':recipeDatas[i].ban + "\n";
        }
    }
    if(temp.length<1){
        Swal.fire({
            type: 'info',
            title: '请选择至少一个是否合方。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    recipeIds = recipeIds.replace(/,\s*$/, "");

    for(var i=0; i < temp.length; i++) {
        for (var j = 0; j < hefangMedicines.length; j++) {
            if (hefangMedicines[j].medicine_id == temp[i].medicine_id) {
                hefangMedicines[j].max_weight = hefangMedicines[j].max_weight > temp[i].max_weight ? hefangMedicines[j].max_weight : temp[i].max_weight;
                hefangMedicines[j].min_weight = hefangMedicines[j].min_weight < temp[i].min_weight ? hefangMedicines[j].min_weight : temp[i].min_weight;
                hefangMedicines[j].weight = hefangMedicines[j].weight > temp[i].weight ? hefangMedicines[j].weight : temp[i].weight;
                break;
            }
        }
        if (j == hefangMedicines.length)
            hefangMedicines = hefangMedicines.concat(temp[i]);
    }
    strHefangMedicines = JSON.stringify(hefangMedicines);
    hefangRecipe = {
        id:"hefang",
        ids:recipeIds,
        prescription_name:"合方",
        ban:ban,
        eating_method:eatting_method,
        medicine:strHefangMedicines,
        other_condition:otherCondition,
        price:calcPrice(strHefangMedicines),
        daiNumber:1
    }
    for(var i = 0 ; i < recipeDatas.length; i++){
        if(recipeDatas[i].id=='hefang'){
            recipeDatas[i] = hefangRecipe;
            break;
        }
    }
    if(i==recipeDatas.length)
        recipeDatas.push(hefangRecipe);
    drawMedicine(recipeDatas,true,true);
    $("#medicines").val(JSON.stringify(recipeDatas));
    calcPriceTotal();

    // drawHeFang(hefangRecipe);
}
function sethoufang(obj) {
    var houfang = $(obj).prop('checked');
    var recipeId = $(obj)[0].id.replace('houfang_','');
    var strMedicins = $("#medicines").val();
    var recipeDatas = JSON.parse(strMedicins);
    for(var i = 0 ; i < recipeDatas.length; i++){
        if(recipeDatas[i].id==recipeId){
            recipeDatas[i].shifouhefang = houfang;
            break;
        }
    }
    $("#medicines").val(JSON.stringify(recipeDatas));
    drawMedicine(recipeDatas,true,true);
    calcPriceTotal();
}

function getMaizhengCheckValue(type){
    var str = '';
    var elements = document.getElementsByClassName(type);
    for(var i = 0 ; elements[i];i++){
        if(elements[i].checked){
            str += elements[i].value + ',';
        }
    }
    if(str.length>1)
        str = str.substring(0,str.length-1);
    return str;
}

function drawTags(){
    var strBiaos = getMaizhengCheckValue('biaozheng');
    var strLis = getMaizhengCheckValue('lizheng');
    var strBiaoLis = getMaizhengCheckValue('biaoli');
    var strMais = getMaizhengCheckValue('maizheng');
    $("#total_biaozheng").tagsinput('removeAll');
    $("#total_lizheng").tagsinput('removeAll');
    $("#total_biaoli").tagsinput('removeAll');
    $("#total_maizheng").tagsinput('removeAll');
    $("#total_biaozheng").tagsinput('add',strBiaos);
    $("#total_lizheng").tagsinput('add',strLis);
    $("#total_biaoli").tagsinput('add',strBiaoLis);
    $("#total_maizheng").tagsinput('add',strMais);
}

$('input').on('itemRemoved',function(event){
    var vals = $(this).val();
    var id = $(this)[0].id;
    switch (id) {
        case 'total_biaozheng':
            $(".biaozheng").prop('checked',false);
            break;
        case 'total_lizheng':
            $(".lizheng").prop('checked',false);
            break;
        case 'total_biaoli':
            $(".biaoli").prop('checked',false);
            break;
        case 'total_maizheng':
            $(".maizheng").prop('checked',false);
            break;
        default:
            break;
    }

    var elementValues = vals.split(',');
    for(var i = 0 ; elementValues[i] ; i ++){
        $('input[value="'+elementValues[i]+'"]').prop('checked',true);
    }

})
