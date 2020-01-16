var country_table;

var showCountryModal = function (isAdd, country_id) {
    $('#country-modal').modal();

    if (!isAdd) {
        $('#country_name').val($('#name-' + country_id).html());
        $('#currency').val($('#currency-' + country_id).html());
        $('#symbol').val($('#symbol-' + country_id).html());
        if ($('#loanable-' + country_id).html() == '可') {
            $('#loanable').val(1);
        } else {
            $('#loanable').val(0);
        }
        $('#loan_ratio').val($('#ratio-' + country_id).html());
        $('#inflation').val($('#inflation-' + country_id).html());
        $('#rent_growth_ratio').val($('#rent-' + country_id).html());
        $('#country_id').val(country_id);
    }else{
        $('#country_name_cn').val('');
        $('#country_name_en').val('');
        $('#calling_code').val('');
        $('#language').val('');
        $('#currency').val('');
        $('#symbol').val('');
        $('#country_id').val('');
    }
};

var showCityModal = function(country_id) {
    $('#country_id_city').val(country_id);
    $('#city-modal').modal();
};

var showPropertyTypeModal = function(country_id) {
    $('#country_id_type').val(country_id);

    showOverlay();

    $.ajax({
        url: '/admin/base/proptype/' + $('#country_id_type').val(),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            hideOverlay();

            if (resp.code == '0') {

                var len = resp.data.length;
                for (var i = 0 ; i < len ; i++) {
                    var item = resp.data[i];
                    var html = '<div id="type-' + item.id + '" class="row addtype" style="padding-bottom: 10px;">\n' +
                        '<div class="col-md-5">\n' +
                        '<input type="text" id="type-' + item.id + '-name" class="form-control" value="' + item.name + '" placeholder="请输入物业类型。。。">\n' +
                        '</div>\n' +
                        '<div class="col-md-4 m-t-5">\n' +
                        '<label class="custom-control custom-checkbox m-b-0">\n' +
                        '<input type="checkbox" id="landprice-' + item.id + '" class="custom-control-input" ' + (item.include_land_price ? 'checked' : '') + '>\n' +
                        '<span class="custom-control-label">包括土地价</span>\n' +
                        '</label>'+
                        '</div>' +
                        '<div class="col-md-3">\n' +
                        '<button type="button" class="btn btn-primary" onclick="updatePropertyType(' + item.id + ')"><i class="ti-check"></i></button>\n' +
                        '<button type="button" class="btn btn-danger" onclick="deletePropertyType(' + item.id + ')"><i class="ti-close"></i></button>\n' +
                        '</div>\n' +
                        '</div>';
                    $('#type-new-name').val('');
                    $('#type-new').before(html);
                }
            } else {

            }
        }
    });

    $('.type-title').html('物业类型（' + $('#name-' + country_id).html() + '）');
    $('#property-type-modal').modal();
};

var addPropertyType = function() {
    // $('#country_id_city').val(country_id);
    if ($('#type-new-name').val().trim() == '')
        return;

    showOverlay();

    $.ajax({
        url: '/admin/base/proptype/add',
        data: "country_id=" + $('#country_id_type').val() + "&name=" + $('#type-new-name').val() + "&include_land_price=" + $('#include_land_price').prop('checked'),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();

            if (resp.code == '0') {
                var html = '<div id="type-' + resp.data.id + '" class="row addtype" style="padding-bottom: 10px;">\n' +
                    '<div class="col-md-5">\n' +
                    '<input type="text" id="type-' + resp.data.id + '-name" class="form-control" value="' + resp.data.name + '" placeholder="请输入物业类型。。。">\n' +
                    '</div>\n' +
                    '<div class="col-md-4 m-t-5">\n' +
                    '<label class="custom-control custom-checkbox m-b-0">\n' +
                    '<input type="checkbox" id="landprice-' + resp.data.id + '" class="custom-control-input" ' + (resp.data.include_land_price ? 'checked' : '') + '>\n' +
                    '<span class="custom-control-label">包括土地价</span>\n' +
                    '</label>'+
                    '</div>' +
                    '<div class="col-md-3">\n' +
                    '<button type="button" class="btn btn-primary" onclick="updatePropertyType(' + resp.data.id + ')"><i class="ti-check"></i></button>\n' +
                    '<button type="button" class="btn btn-danger" onclick="deletePropertyType(' + resp.data.id + ')"><i class="ti-close"></i></button>\n' +
                    '</div>\n' +
                    '</div>';
                $('#type-new-name').val('');
                $('#type-new').before(html);

                html = '<a id="proptype-' + resp.data.id + '">' + resp.data.name + ', </a>';
                $('#types-' + $('#country_id_type').val()).append(html);
            } else {

            }
        }
    });
};

var updatePropertyType = function(id) {
    showOverlay();

    $.ajax({
        url: '/admin/base/proptype/update',
        data: 'id=' + id + '&name=' + $('#name-' + id).val() + '&include_land_price=' + $('#land-' + id).prop('checked'),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {

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
        },
        error: function (e) {
            hideOverlay();
            Swal.fire({
                position: 'top-end',
                type: 'error',
                text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                title: '错误',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
};

var deletePropertyType = function(id) {
    Swal.fire({
        title: "你确定要删除该物业类型吗?",
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
                url: '/admin/base/proptype/delete',
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
                        $('#proptype-' + id).remove();
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
                        timer: 1500
                    });
                }
            });
        }
    });
};

var deleteCountry = function(obj, id) {
    Swal.fire({
        title: "你确定要删除该国家吗?",
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
                    url: '/admin/base/country/delete',
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
                            country_table.row( $(obj).parents('tr') )
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
};

function updateCountry() {
    var form = new FormData($('#main-form')[0]);
    var ecData = new FormData($('#economy-form')[0]);

    ecData.forEach(function (value, key, parent) {
        form.append(key, value);
    });

    showOverlay();
    $.ajax({
        url: '/admin/base/country/update',
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    text: '',
                    title: '更新成功',
                    showConfirmButton: false,
                    timer: 3000
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

$(function () {
    country_table = $('#tblcountry').DataTable({
        "columnDefs": [
            { "orderable": false, 'targets': [9] },
            { "className": 'text-center', 'targets': '_all'}
        ],
        "order": [[ 0, "desc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    $('#country-add-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        var form = new FormData($(this)[0]);

        $.ajax({
            url: '/admin/base/country/add',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $('#country-modal').modal('hide');
                    country_table.row.add(['<a href="/admin/base/country/' + resp.data.id + '">' + resp.data.name_cn + '</a>', resp.data.symbol, '', '', '', '', '', '', '', '', '', '',
                    '<button class="btn btn-sm btn-danger" onclick="deleteCountry(this, ' + resp.data.id + ')"><i class="ti-trash"></i></button>']).draw();
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '添加成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    hideOverlay();
                    console.log(e);
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
        })
    });

    $('#proptype-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        var form = new FormData($(this)[0]);

        $.ajax({
            url: '/admin/base/proptype/add',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    var html = '<div class="form-group row" id="proptype-' + resp.data.id + '">\n' +
                        '<div class="col-md-4">\n' +
                        '<input type="text" class="form-control" id="name-' + resp.data.id + '" value="' + resp.data.name + '">\n' +
                        '</div>\n' +
                        '<div class="col-md-6" style="display: flex">\n' +
                        '<div class="controls m-t-10">\n' +
                        '<label class="custom-control custom-checkbox m-b-0">\n' +
                        '<input type="checkbox" class="custom-control-input" id="land-' + resp.data.id + '"' + (resp.data.include_land_price == true ? 'checked' : '')+ '>\n' +
                        '<span class="custom-control-label">包括土地价</span>\n' +
                        '</label>\n' +
                        '</div>\n' +
                        '<button type="button" class="btn btn-success m-l-15" onclick="updatePropertyType(' + resp.data.id + ')"><i class="ti-save"></i></button>\n' +
                        '<button type="button" class="btn btn-danger m-l-10" onclick="deletePropertyType(' + resp.data.id + ')"><i class="ti-trash"></i></button>\n' +
                        '</div>\n' +
                        '</div>';
                    $('#proptypes').append(html);
                } else {
                    hideOverlay();
                    console.log(e);
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
        })
    });

    $('#city-modal').on('hidden.bs.modal', function () {
        $('#city_name').val('');
        $('#country_id_city').val('');
    });

    $('#country-modal').on('hidden.bs.modal', function () {
        $('#name_cn').val('');
        $('#name_en').val('');
        $('#calling_code').val('');
        $('#language').val('');
        $('#currency').val('');
        $('#symbol').val('');
    });
});