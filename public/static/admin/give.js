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
                var html = generateMedicineHtml(recipe);
                var priceHtml = '<div class="row mt-3">\n' +
                    '\t<label class="col-md-2 col-form-label text-right">\n' +
                    '\t\t&nbsp;总价: </label>\n' +
                    '\t<div class="col-md-3 col-form-label"><b id="totalPrice">'+resp.data.price+'</b>元 </div>\n' +
                    '</div>\n';
                $("#medicineSection").html(html);

                fuHtml = "<div class='row mt-3'>" +
                    "<div class='col-sm-2'></div>" +
                    "<div class='col-sm-1' style='margin-left: 3.5%'><input type='number' disabled value='"+resp.data.fuNumber+"' class='text-center form-control' /></div>" +
                    "<div class='text-left col-form-label'>副</div>" +
                    "<div class='col-sm-1'><input type='number' class='text-center form-control' value='"+resp.data.daiNumber*resp.data.fuNumber+"'  disabled/></div>" +
                    "<div class='col-sm-1 col-form-label'>代</div>" +
                    "</div>";
                $("#medicineSection").append(fuHtml);
                $("#medicineSection").append(priceHtml);


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
