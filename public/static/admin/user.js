"use strict";
var user_table, admin_table, selected_row, bUpdateAdmin = false;

function updateUser(obj, hash) {
    $('#user-modal').modal();
    selected_row = user_table.row( $(obj).parents('tr') );

    fillUserForm(selected_row.data(), hash);
    bUpdateAdmin = false;
}

function updateAdmin(obj, hash) {
    $('#user-modal').modal();
    selected_row = admin_table.row( $(obj).parents('tr') );

    fillUserForm(selected_row.data(), hash);
    bUpdateAdmin = true;
}

function fillUserForm(data, hash) {
    $('#avatar').val('');
    $('.dropify-clear').click();
    $('#name').val(data[1]);
    $('#phone').val(data[4]);
    $('#email').val(data[3]);
    $('#position').val(data[7]);
    $('#type').val(data[5]);
    $('#role').val(data[6]);
    $('#password').val('');
    $('.selectpicker').selectpicker('refresh');
    $('#user_hash').val(hash);
}

function deleteUser(obj, hash) {
    Swal.fire({
        title: "你确定要删除选中的用户吗?",
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
                url: '/admin/user/delete',
                data: "hash=" + hash,
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'POST',
                success: function (resp) {
                    if (resp.code == '0') {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '用户已被删除',
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        user_table.row( $(obj).parents('tr') )
                            .remove()
                            .draw();
                    } else {
                        Swal.fire({
                            position: 'top-end',
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
                    console.log(e);
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
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

    $('.dropify').dropify({
        messages: {
            default: ''
        }
    });
    $('.selectpicker').selectpicker();

    // getPage(1, 'all', true);
    user_table= $('#tblusers').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [0, 9] }
        ],
        "order": [[ 1, "asc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    admin_table= $('#tbladmins').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [0, 9] }
        ],
        "order": [[ 1, "asc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    $('#user-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        var form = new FormData($(this)[0]);
        $.ajax({
            url: '/admin/user/update',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    var data;
                    if (bUpdateAdmin)
                        data = admin_table.row(selected_row).data();
                    else
                        data = user_table.row(selected_row).data();

                    data[0] = '<img class="avatar-img" src="' + resp.data.avatar + '" height="50" width="50"/>';
                    data[1] = resp.data.name;
                    data[3] = resp.data.email;
                    data[4] = resp.data.phone;
                    data[5] = resp.data.type;
                    data[6] = resp.data.role;
                    data[7] = resp.data.position;
                    if (bUpdateAdmin)
                        admin_table.row(selected_row).data(data).draw();
                    else
                        user_table.row(selected_row).data(data).draw();

                    $('#user-modal').modal('hide');
                }
            },
            error: function (e) {
                hideOverlay();
                console.log(e);
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
});