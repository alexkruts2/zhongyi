var pricelist_table;
var selected_row;

function deletePrice(hash, obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
                    url: '/admin/property/pricelist/delete',
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
                            })
                            pricelist_table.row($(obj).parents('tr'))
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

function createPrice() {
    $('#name').val('');
    $('#hash').val('');
    $('#price-modal').modal();
}

function updatePrice(hash, obj)
{
    selected_row = $(obj).parents('tr');
    var data = pricelist_table.row(selected_row).data();

    $('#hash').val(data.hash);
    $('#name').val(data.name);
    $('#property').val(data.property !== null ? data.property.hash : '');
    $('#price-modal').modal();
}

$(function () {
    pricelist_table = $('#tbl_pricelist').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[1, "desc"]],
        "ajax":{
            "type":"GET",
            "url": '/admin/property/pricelist/get',
            "dataType":"json"
        },
        columns: [
            {data: 'hash'},
            {data: 'name'},
            {data: 'property'},
            {data: 'unitList'},
            {data: 'created_at'},
            {data: 'hash'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [2],
                "mRender": function (data, type, full) {
                    if (data !== null)
                        return '<a href="/admin/property/' + data.hash + '">' + data.name + '</a>';
                    else
                        return '';
                },
                "orderable": false
            },
            {
                "aTargets": [3],
                "mRender": function (data, type, full) {
                    var html = '';
                    for (var i = 0 ; i < data.length ; i++) {
                        html += '<a href="/admin/property/unit/' + data[i].hash + '">' + data[i].unit_number + '</a>, '
                    }
                    return html;
                },
                "orderable": false
            },
            {
                "aTargets":[5],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-info m-r-5" onclick="updatePrice(\'' + full.hash + '\', this)"><i class="ti-pencil-alt"></i></button>' +
                        '<button class="btn btn-sm btn-danger" onclick="deletePrice(\'' + full.hash + '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    $('#price-form').submit(function (e) {
        e.preventDefault();
        var form = new FormData($(this)[0]);
        // form.append('content_real', $('#content').summernote('code'));

        showOverlay();
        if ($('#hash').val() == '') {
            $.ajax({
                url: '/admin/property/pricelist/create',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        pricelist_table.draw();
                        $('#price-modal').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '保存成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
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
                url: '/admin/property/pricelist/update',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        pricelist_table.draw();
                        $('#price-modal').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '更新成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
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
