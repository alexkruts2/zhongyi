$(document).ready(function() {
    $('#setting-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var forms = new FormData($(this)[0]);
        $.ajax({
            url: '/admin/setting',
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
                        title: '添加成功',
                        showConfirmButton: false,
                        timer: 1500
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

    $('#password-form').submit(function (e) {
        e.preventDefault();

        var old_pw = $("#original_password").val();
        var new_pw = $("#new_password").val();
        var repeat_pw = $("#repeat_password").val();
        if(old_pw==''||old_pw==null||old_pw==undefined){
            Swal.fire({
                type: 'error',
                title: '请输入原密码。',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        if(new_pw==''||new_pw==null||new_pw==undefined){
            Swal.fire({
                type: 'error',
                title: '请输入新密码。',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        if(new_pw!=repeat_pw){
            Swal.fire({
                type: 'error',
                title: '密码和重复密码不同。',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        showOverlay();
        var forms = new FormData($(this)[0]);
        $.ajax({
            url: '/doctor/setting/change_password',
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
                        title: '更改成功',
                        showConfirmButton: false,
                        timer: 1500
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
    if($(".dropify").length){
        $('.dropify').dropify({
            messages: {
                'default': '将文件拖放到此处或单击',
                'replace': '拖放或单击以替换',
                'remove':  '删除',
                'error':   '糟糕，出现了错误。'
            }
        });
    }

});
