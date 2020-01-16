var faq_table;
var selected_row;

function deleteFaq(hash, obj) {
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
                    url: '/admin/faq/delete',
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
                                text: '',
                                title: '删除成功',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            faq_table.row($(obj).parents('tr'))
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

function createFaq() {
    $('#name').val('');
    $('#qnaList').val('');
    $('#faq_hash').val('');
    $('#faq-modal').modal();
}

function updateFaq(hash, name, qnaList, obj)
{
    $('#faq_hash').val(hash);
    $('#name').val(name);
    $('#qnaList').val(qnaList);

    $('#faq-modal').modal();
}

function gotoCreateQna(hash) {
    window.location = "/admin/qnalist/add?hash=" + hash;
}

$(function () {
    faq_table = $('#tbl_faq').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[3, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"GET",
            "url": '/admin/faq/get',
            "dataType":"json"
        },
        columns: [
            {data: 'hash'},
            {data: 'name'},
            // {data: 'qnaList'},
            {data: 'created_at'},
            {data: 'hash'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[3],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-cyan" onclick="gotoCreateQna(' + "'" + full.hash + "'" + ')"><i class="ti-link"></i><button class="btn btn-sm btn-success m-l-5" onclick="updateFaq(\'' + full.hash + '\',\'' + full.name + '\',\'' + full.qnaList + '\', this)"><i class="ti-pencil-alt"></i></button><button class="btn btn-sm btn-danger m-l-5" onclick="deleteFaq(\'' + full.hash + '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    $('#faq-form').submit(function (e) {
        e.preventDefault();
        var form = new FormData($(this)[0]);
        // form.append('content_real', $('#content').summernote('code'));

        showOverlay();
        if ($('#faq_hash').val() == '') {
            $.ajax({
                url: '/admin/faq/create',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        faq_table.draw();
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '新建成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#faq-modal').modal('hide');
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
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
        } else {
            $.ajax({
                url: '/admin/faq/update',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        faq_table.draw();
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '修改成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#faq-modal').modal('hide');

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
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
        }
    })

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});
