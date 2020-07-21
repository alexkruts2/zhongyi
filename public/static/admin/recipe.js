var currentDiseaseNumber;
var recipe_table;

function addDisease() {

    if(currentDiseaseNumber==undefined||currentDiseaseNumber=='')
        currentDiseaseNumber = 1;
    currentDiseaseNumber++;
    var html = "<div class=\"row\">\n" +
        "<label class=\"col-2 col-form-label text-right\">\n" +
        "<button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index='"+currentDiseaseNumber+"' onclick=\"removeDisease(this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;" +
        "病症<span id='label_"+currentDiseaseNumber+"'>"+currentDiseaseNumber+"</span></label>\n" +
        "<div class=\"col-10\">\n" +
        "<input class=\"form-control\" type=\"text\" value=\"\" name='disease[]' id=\"disease_"+currentDiseaseNumber+"\" />\n" +
        " </div>\n" +
        "</label>\n";
    $("#diseaseSection").append(html);
}

function removeDisease(obj){
    currentDiseaseNumber--;
    var row = $(obj).parent().parent();
    row.remove();
    $(".tooltip").fadeOut();
    var selectNumber = $(obj).data("index");
    for(var i=selectNumber+1; i< currentDiseaseNumber+2; i++){
        $('*[data-index="'+i+'"]').next().html(i-1);
        $('*[data-index="'+i+'"]').next().attr("id","label_"+(i-1));
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","disease_"+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }
}

function addMedicine() {
    selectedMedicine_id = $("#medicine").val();
    selectedMedicine_name = $("#medicine option:selected").text();
    if(selectedMedicine_id<1) return;
    var min_weight =  $("#medicine option:selected").data("min");
    var max_weight =  $("#medicine option:selected").data("max");
    var price = $("#medicine option:selected").data("price");
    var unit = $("#medicine option:selected").data("unit");

    var unitLable = unit==null||unit==''||unit==undefined||unit=='公克'?' 元/10g':unit=='两'?' 元/两':('元/'+unit);

    var html="<div class=\"row\">\n" +
        "    <label class=\"col-2 col-form-label text-right\">\n" +
        "        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"删除\" data-index=\""+selectedMedicine_id+"\" onclick=\"removeMedicine(this);\"><i class=\"fas fa-times\"></i> </button> &nbsp;"+selectedMedicine_name+"<input type='hidden' name='medicine_name[]' value='"+selectedMedicine_name+"' /></label>\n" +
        "    <div class=\"col-3\">\n" +
        "        <input class=\"form-control\" type=\"number\" value=\""+min_weight+"\" name=\"min_weight[]\" id=\"min_weight_"+selectedMedicine_id+"\">\n" +
        "    </div>\n" +
        "<span style=\"\n" +
        "    padding-top: 8px;\n" +
        "\">~</span>"+
        "    <div class=\"col-3\">\n" +
        "        <input class=\"form-control\" type=\"number\" value=\""+max_weight+"\" name=\"max_weight[]\" id=\"max_weight_"+selectedMedicine_id+"\">\n" +
        "    </div>\n" +
        "<div class=\"col-3 text-center\">\n" +
        "    <label id=\"price_"+selectedMedicine_id+"\" style=\"line-height: 38px;\">"+price+" " + unitLable +"</label><input type='hidden' name='price[]' value='"+price+"' /> \n" +
        "</div>\n"+
        "</div>\n"
    $("#medicineSection").append(html);
    $("#medicine option:selected").attr("disabled","disabled");
    $("#medicine").prop("selectedIndex",-1);


    getContraryIds(selectedMedicine_id,function(contraryIds){
        for(var i=0; i < contraryIds.length; i++){
            $("#medicine option[value='"+contraryIds[i]+"']").attr("disabled","disabled");
        }
        $(".select2").select2({
            placeholder:"请选择"
        });

    });

    changeMaxMinValue();
}


function deleteRecipe(id, obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
                url: '/doctor/recipe/delete',
                data: "id=" + id,
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'POST',
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

                        recipe_table.row($(obj).parents('tr'))
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
$(function () {

    if($(".select2").length)
        $(".select2").select2({
            placeholder:"请选择"
        });

    drawTable();
});
$('#recipe-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (typeof e.originalEvent === 'undefined' || e.isTrigger) {
        console.log('Prevent duplicate events');
        return false;
    }

    if($("#recipe_id").length)
        url = '/doctor/recipe/update';
    else
        url = '/doctor/recipe/create';
    var form = $(this);
    if(!form.parsley().validate()){
        return ;
    }
    var minValue= $("input[name='min_weight[]'").val();
    console.log(minValue);
    if(minValue==undefined||minValue==null||minValue==''){
        Swal.fire({
            type: 'success',
            title: '请添加至少一种药物。',
            showConfirmButton: false,
            timer: 1500
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
                window.location.href = "/doctor/recipe/view"
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
function uploadRecipes() {
    $("input[name='file_1']").trigger('click');
}
$("input[name='file_1']").change(function() {
    $("#excel-form").submit();
});
$("#excel-form").submit(function(e){
    e.preventDefault();
    var file = $("input[name='file_1']").val();
    if(file==''||file==null||file==undefined)
        return false;
    showOverlay();
    var forms = new FormData($(this)[0]);
    $.ajax({
        url: '/doctor/recipe/uploadRecipes',
        data: forms,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                $("#myModal").modal('hide');
                recipe_table.destroy();
                drawTable();
                hideOverlay();
                $("input[name='file_1']").val('');
                Swal.fire({
                    type: 'success',
                    html: '总数:'+resp.data.total_number+',  插入数:'+resp.data.insert_number+',<br>更新数:'+resp.data.update_number+',  失败数:'+resp.data.fail_number,
                    title: '成功导入',
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
function drawTable() {
    if($("#recipe").length)
        recipe_table = $('#recipe').DataTable({
            "processing":true,
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
            },
            "serverSide":true,
            "order": [[0, "desc"]],
            "pageLength":10,
            "aLengthMenu":[10,20,50],
            "ajax":{
                "type":"GET",
                "url": '/doctor/recipe/getDatas',
                "dataType":"json"
            },
            columns: [
                {data: 'department'},
                {data: 'prescription_name'},
                {data: 'price'},
                {data: 'id'}
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
            },
            "aoColumnDefs": [
                {
                    "aTargets":[0],
                    "mRender":function(data,type,full) {
                        return data==''||data==undefined||data==null?'未知':data;
                    }
                },{
                    "aTargets":[2],
                    "mRender":function(data,type,full) {
                        return data==''||data==undefined||data==null?'0':data;
                    }
                },{
                    "aTargets":[3],
                    "mRender":function(data,type,full) {
                        return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/recipe/edit/'+data+'\'"><i class="ti-pencil"></i>修改</button>'+
                            '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteRecipe(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                    }
                },
                {"className": "text-center", "targets": "_all"}
            ]
        });
}
function deleteAllRecipes() {
    Swal.fire({
        title: "你确定要删除全部药房吗?",
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
                url: '/doctor/recipe/deleteAll',
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'POST',
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
                        window.location.reload();
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
