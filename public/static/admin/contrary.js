var contraryTable;
function uploadContraryExcel() {
    $("input[name='file_1']").trigger('click');
}
$("input[name='file_1']").change(function() {
    $("#excel-form").submit();
});
$(function(){
    drawTable();
    $('#contrary-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var forms = new FormData($(this)[0]);

        $.ajax({
            url: '/doctor/medicine/contrary/edit',
            data: forms,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    Swal.fire({
                        type: 'success',
                        text: '',
                        title: '修改成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#myModal").modal('hide');
                    contraryTable.destroy();
                    drawTable();
                    hideOverlay();

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

    $("#excel-form").submit(function(e){
        e.preventDefault();
        var file = $("input[name='file_1']").val();
        if(file==''||file==null||file==undefined)
            return false;
        showOverlay();
        var forms = new FormData($(this)[0]);
        $.ajax({
            url: '/doctor/medicine/contrary/upload',
            data: forms,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $("#myModal").modal('hide');
                    contraryTable.destroy();
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
});
function drawTable(){
    contraryTable = $('#tbl_contrary').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'contrary_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/medicine/contrary/get',
            "dataType":"json"
        },
        columns: [
            {data: 'name'},
            {data: 'contrary'},
            {data:'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[2],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success m-l-5" data-medicine=\''+JSON.stringify(full)+'\' onclick="editMedicine(\'' + data+ '\', this)"><i class="ti-pencil-alt"></i>修改</button>'+
                        '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteMedicine(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });
}

function editMedicine(id,obj) {
    $("#myModal").modal();
    $("#contrary_id").val(id);
    var medicine = ($(obj).data('medicine'));
    $("#medicine_name").val(medicine.name);
    $("#contrary").val(medicine.contrary);
}
function deleteMedicine(id,obj) {
    Swal.fire({
        title: "你确定要删除该药材吗?",
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
                url: '/doctor/medicine/contrary/delete',
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
                        contraryTable.row($(obj).parents('tr'))
                            .remove()
                            .draw();
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
