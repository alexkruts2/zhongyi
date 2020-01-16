
var template_table;

function showTemplateModal() {
    $('#template-modal').modal();
}

function deleteTemplate(obj, id) {
    Swal.fire({
        title: "你确定要删除选中的税费模板吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    })
        .then(result => {
            if (result.value) {
                $.ajax({
                    url: '/admin/base/tax/template/delete',
                    data: "id=" + id,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    type: 'POST',
                    success: function (resp) {
                        if (resp.code == '0') {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                text: '',
                                title: '删除成功',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            template_table
                                .row( $(obj).parents('tr') )
                                .remove()
                                .draw();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                text: resp.message,
                                title: '失败',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function (e) {
                        hideOverlay();
                        Swal.fire({
                            position: 'top-end',
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
    template_table = $('#tbltemplate').DataTable({
        columnDefs: [
            { "orderable": false, "targets": [5] },
            { "className": 'text-center', "targets": [4, 5]}
        ],
        "order": [[ 0, "desc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    $('#template-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        $.ajax({
            url: '/admin/base/tax/template/add',
            data: 'name=' + $('#template_name').val(),
            cache: false,
            dataType: 'json',
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $('#template-modal').modal('hide');

                    template_table.row.add([
                        '<a href="/admin/base/tax/template/' + resp.data.id + '">' + resp.data.name + '</a>', '', '', '', '',
                        '<button class="btn btn-danger" type="button" onclick="deleteTemplate(this, ' + resp.data.id + ')"><i class="ti-close"></i></button>'
                    ]).draw(false);

                } else {
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        text: resp.message,
                        title: '失败',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            error: function (e) {
                hideOverlay();
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });

    $('#template-modal').on('hidden.bs.modal', function () {
        $('#template_name').val('');
    });
})