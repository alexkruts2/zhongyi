var recipe_part_table;
function deleteRecipe_part(id, obj) {
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
            $.ajax({
                url: '/doctor/recipe/part/delete',
                data: "id=" + id,
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'POST',
                success: function (resp) {
                    if (resp.code == '0') {
                        Swal.fire({
                            type: 'success',
                            text: '',
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        recipe_part_table.row($(obj).parents('tr'))
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

function createRecipe_part(){
    var recipe_part_name = $("#part_name").val();
    if(recipe_part_name==''||recipe_part_name==undefined||recipe_part_name==null){
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入科室名'
        });
        return;
    }
    showOverlay();
    $.ajax({
        url: '/doctor/recipe/part/create',
        data: "name=" + recipe_part_name,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '保存成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                var new_id = resp.data.id;
                recipe_part_table.row.add({
                    id: new_id,
                    name: recipe_part_name
                }).draw();
                $('#myModal').modal('hide');

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
$(function () {
    recipe_part_table = $('#recipe_part').DataTable({
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
            "url": '/doctor/recipe/getPart',
            "dataType":"json"
        },
        columns: [
            {data: 'name'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[1],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteRecipe_part(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

});
