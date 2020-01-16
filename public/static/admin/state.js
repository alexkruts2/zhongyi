
var state_table, selected_row;

function searchState(obj) {

    if ($(obj).val() < 1) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择国家'
        });
        return;
    }

    showOverlay();
    $.ajax({
        url: '/admin/base/country/states',
        data: "country_id=" + $(obj).val(),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                state_table.clear().draw();
                state_table.rows.add(resp.data).draw();
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
    });
};

var showStateModal = function (isAdd, state_id, obj) {
    var countryId = $("#country").val();
    if (countryId > 0) {
        $('#country_id').val(countryId);
        $('#country_id').selectpicker('refresh');
    }

    $('#state-modal').modal();

    if (!isAdd) {
        selected_row = state_table.row( $(obj).parents('tr') );
        var data = selected_row.data();

        $('#country_id').val(data['country_id']);
        $('#name_cn').val(data['name_cn']);
        $('#name_en').val(data['name_en']);
        $('#name_en_abbr').val(data['name_en_abbr']);
        $('#state_id').val(data['id']);
    } else {
        $('#name_cn').val('');
        $('#name_en').val('');
        $('#name_en_abbr').val('');
        $('#state_id').val('');
    }
};

$('#state-form').submit(function (e) {
    e.preventDefault();
    var form = new FormData($(this)[0]);
    var countryId = $("#country_id").val();
    if (countryId < 1) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择国家'
        });
        return;
    }
    showOverlay();

    $.ajax({
        url: '/admin/base/state/addorUpdate',
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                $('#state-modal').modal('hide');
                var stateId = $('#state_id').val();
                if ( stateId < 1 ){
                    state_table.row.add(resp.data).draw();
                }
                else {
                    selected_row.data(resp.data).draw();
                }
            } else {
                hideOverlay();
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
});

var deleteState = function(obj, id) {
    Swal.fire({
        title: "你确定要删除该州吗?",
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
                    url: '/admin/base/state/delete',
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
                            })
                            state_table.row( $(obj).parents('tr') )
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
                            })
                        }
                    }
                });
            }
        });
};

$(function () {
    state_table = $('#tblState').DataTable({
        "columnDefs": [
            {"orderable": false, "targets": [2]}
        ],
        "order": [[0, "asc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "columns": [
            {"data": "country"},
            {"data": "name_cn"},
            {"data": "name_en"},
            {"data": "name_en_abbr"},
            {"data": "id"}
        ],
        "aoColumnDefs": [
            {
                "aTargets": [4],
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-info m-r-5" type="button" onclick="showStateModal(false,' + data + ', this)"><i class="ti-pencil-alt"></i></button>' +
                        '<button class="btn btn-danger" type="button" onclick="deleteState(this,' + data + ')"><i class="ti-trash"></i></button>';
                },
                "orderable":false
            },
            {"className": "text-center", "targets": "_all"}
        ],
    });
});
