var guahao;
function getGuahaoData(){
    guahao = $("#guahao").val();
    if(guahao==''||guahao==undefined||guahao==null){
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入挂号'
        });
        return;
    }
    showOverlay();
    $.ajax({
        url: '/doctor/recipe/checkGuahao',
        data: "guahao=" + guahao,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                var recipe = JSON.parse(resp.data.recipe);
                // var houfang = JSON.parse(resp.data.houfang);
                $("#medicines").val(resp.data.recipe);

                drawMedicine(JSON.parse(resp.data.recipe),true,false,true);
                // (recipe, houfang);
                calcPriceTotal();

                $("input").prop("disabled",true);
                $("#totalPrice").html(resp.data.price);
                $("#giveMedicine").prop("disabled",false);
            } else {
                $("#giveMedicine").prop("disabled",true);
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
function  generateMedicineHtml(medicines) {
    var html = '';
    for(var i = 0 ; i < medicines.length; i++){
        html+='<div class="row">\n' +
            '\t<label class="col-2 col-form-label text-right">\n' +
            '\t\t&nbsp;'+medicines[i].medicine+'</label>\n' +
            '\t<div class="col-3">\n' +
            '\t\t<input class="form-control" type="text" value="'+medicines[i].weight+'" readonly>\n' +
            '\t</div>\n' +
            '\t<div class="col-3">\n' +
            '\t\t<label id="price_'+medicines[i].medicine_id+'" style="line-height: 38px;">'+medicines[i].price+' 元/10g</label>\n' +
            '\t</div>\n' +
            '</div>'
    }
    return html;
}
function giveMedicine () {
    if(guahao==''||guahao==undefined||guahao==null){
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入挂号'
        });
        return;
    }
    showOverlay();
    $.ajax({
        url: '/doctor/recipe/giveMedicine',
        data: "guahao=" + guahao,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                $("#giveMedicine").prop("disabled",true);
                Swal.fire({
                    type: 'info',
                    title: '发药成功',
                    showConfirmButton: false,
                    timer: 3000
                });

            } else {
                $("#giveMedicine").prop("disabled",true);
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

function drawRecipeSections (medicines, houfang) {
    var temp = medicines;
    var html = "";
    var totalPrice = 0;
    if(medicines==''||medicines==null||medicines==undefined){
        temp = medicines= [];
        $("#medicineSection").html("");
        return;
    }

    var recipes = (medicines);
    for(var i=0; i < recipes.length; i++){
        var dbmedicines = (recipes[i].medicines);
        html += ' <h4 class="text-bold">'+recipes[i].receip_txt+'</h4><hr>';
        for(var j=0; j<dbmedicines.length; j++){
            html+='<div id="recipe_medicine_' + recipes[i].receip_id + '" class="recipe_medicine_' + recipes[i].receip_id + '">';
            var min_weight =  dbmedicines[j].min_weight;
            var max_weight =  dbmedicines[j].max_weight;
            var weight = dbmedicines[j].mass;
            if(dbmedicines[j].mass==undefined&&dbmedicines[j].min_weight==dbmedicines[j].max_weight)
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
            "   <div class='col-sm-2'></div> <div class=\"col-1\">\n</div>\n" +
            "    <div class=\"col-8\">\n" +
            "        <div class=\"custom-control custom-checkbox\">";
            // "           <input type=\"hidden\" name=\"houfang[]\" value=\"0\">" +
        if (houfang[i] == "1")
            html += "           <input type=\"checkbox\" class=\"custom-control-input\" value=\"1\" id=\"houfang_" + recipes[i].receip_id + "\" checked disabled>";
        else
            html += "           <input type=\"checkbox\" class=\"custom-control-input\" value=\"1\" id=\"houfang_" + recipes[i].receip_id + "\" disabled>";
        html +=    "           <label class=\"custom-control-label\" for=\"houfang_" + recipes[i].receip_id + "\">是否合方</label>"
            "        </div>\n" +
            "    </div>\n" +
            "</div>\n" +
            "</div>\n";

        html += "</div></div></div></div>";

        html += "<div class='row mt-3 fuPrice fuPrice_" + recipes[i].receip_id + "\'>" +
            "<div class='col-sm-2'></div>" +
            "<div class='col-sm-1' style='margin-left: 3.5%'>" +
            "<input class=\"form-control\" type=\"hidden\" value=\""+recipes[i].total+"\" name=\"totalPrice[]\" id=\"totalPrice_"+recipes[i].receip_id+"\">\n" +
            "<input type='number' id='fuNumber_" + recipes[i].receip_id + "' name='fuNumber[]' min='0' class='text-center form-control' value=\"" + recipes[i].fu_number + "\" readonly/></div>" +
            "<div class='text-left col-form-label'>副</div>" +
            "<div class='col-sm-2'><input type='number' class='text-center form-control' id='fudaiNumber_" + recipes[i].receip_id + "' name='fudaiNumber[]' value='" + recipes[i].fudai_number + "' readonly/></div>" +
            "<div class='col-form-label'>代</div>" +
            "<div class='col-sm-2'><input type='text' class='text-center form-control fuPriceVal' id='fuPrice_" + recipes[i].receip_id + "' name='fuPrice[]' value='" + Number(recipes[i].total).toLocaleString("en") + "' readonly/></div>" +
            "<div class='col-sm-1 col-form-label'>元</div>" +
            "</div>";

        totalPrice += parseInt(recipes[i].total);

    }

    html += ' <hr><h4 class="text-bold">总价：'+totalPrice.toLocaleString("en")+'元</h4>';

    $("#medicineSection").html(html);

}

