var unit_table;
var selected_row;
var priceList;
var isIncludeLandFee = false;
var countries;

function createPropertyUnit() {
    $("#pricelist_hash").val("");
    $("#price-modal").modal();
}

function saveUnitInfo() {
    var form = new FormData($('#unit-form')[0]);
    if($("#unit_number").val() == "")
    {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入户型号'
        });
    }
    else if($("#total_price").val() == "")
    {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入价格'
        });
    }

    else {
        showOverlay();
        if($("#is_from_property").val() == 0) {
            $.ajax({
                url: '/admin/property/unit/update',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '更新成功',
                            showConfirmButton: false,
                            timer: 1500
                        })
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
        }
        else {
            $.ajax({
                url: '/admin/property/unit/create',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
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
        }

    }
}

function getIsInclude(hash) {
    $.ajax({
        url: '/admin/property/unit/getIsIncludeLand',
        data:{
            hash:hash
        },
        type: 'post',
        success: function (resp) {
            if (resp.code == '0') {
                if(resp.data.isIncludeLandFee == 1) {
                    $("#div_structure_price").show();
                    $("#div_structure_size").show();
                    $("#div_land_price").show();
                    $("#div_land_size").show();
                }
                else {
                    $("#div_structure_price").hide();
                    $("#div_structure_size").hide();
                    $("#div_land_price").hide();
                    $("#div_land_size").hide();
                }
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

function getPriceList() {
    $.ajax({
        url: '/admin/property/unit/getPriceList',
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'get',
        success: function (resp) {
            if (resp.code == '0') {
                priceList = resp.data.priceList;
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

function deleteUnit(hash, obj) {
    Swal.fire({
        title: "你确定要删除该户型吗?",
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
                    url: '/admin/property/unit/delete',
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
                            unit_table.row($(obj).parents('tr'))
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

function sizeUnitChang(){
    if($("#size_unit").val() == "sqm")
    {
        $(".size_unit").html("m²");
    }
    else if($("#size_unit").val() == "sqft") {
        $(".size_unit").html("ft²");
    }
}

function getCountry() {
    $.ajax({
        url: '/admin/base/countryList',
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                countries = resp.data.country;
                if($("#hash").val() != "") {
                    var mark = "$";
                    for(var i = 0; i < countries.length; i++) {
                        if(countries[i].id == $("#country_id").val()) {
                            mark = countries[i].symbol;
                            break;
                        }
                    }
                    $(".price_mark").html(mark);
                }
            } else {
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    });
}

$(function () {
    if($("#hash").val() != "" && typeof $("#hash").val() != "undefined") {
        getIsInclude($("#hash").val());
    }
    getCountry();
    getPriceList();

    unit_table = $('#tbl_unit').DataTable({
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
            "url": '/admin/property/unit/get',
            "dataType":"json"
        },
        columns: [
            {data: 'created_at'},
            {data: 'pricelist_hash'},
            {data: 'unit_number'},
            {data: 'total_price'},
            {data: 'room'},
            {data: 'bath'},
            {data: 'garage'},
            {data: 'aspect'},
            {data: 'status'},
            {data: 'hash'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[1],
                "mRender":function(data,type,full){
                    return '<h6>' + full.pricelist_name + '</h6><small class="clr-lightgray">' + data + '</small>';
                }
            },
            {
                "aTargets":[2],
                "mRender":function(data,type,full){
                    return  '<a href="/admin/property/unit/' + full.hash + '"><h6>' + data + '</h6><small>' + full.hash + '</small></a>';
                }
            },
            {
                "aTargets":[3],
                "mRender":function(data,type,full){
                    var mark = "$";
                    if(full.country != -1)
                    {
                        for(var i = 0; i < countries.length; i++) {
                            if(countries[i].id == full.country) {
                                mark = countries[i].symbol;
                            }
                        }
                    }

                    return  mark + data.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                }
            },
            {
                "aTargets":[8],
                "mRender":function(data,type,full){
                    switch(data)
                    {
                        case '1':
                            return '未挂牌';
                            break;
                        case '2':
                            return '可售';
                            break;
                        case '3':
                            return '已预订';
                            break;
                        case '4':
                            return '已支付定金';
                            break;
                        case '5':
                            return '合同发出';
                            break;
                        case '6':
                            return '合同交换';
                            break;
                        case '7':
                            return '完成交割';
                            break;
                    }

                }
            },
            {
                "aTargets":[9],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-danger" onclick="deleteUnit(\'' + data+ '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });




    $('#price-form').submit(function (e) {
        e.preventDefault();
        var form = new FormData($(this)[0]);
        if($("#pricelist_hash").val() == "")
        {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请输入价格单Hash'
            });
        }
        else {
            showOverlay();
            $.ajax({
                url: '/admin/property/unit/add_unit',
                data:{
                    pricelist_hash : $("#pricelist_hash").val()
                },
                type: 'post',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '添加成功',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        unit_table.draw();
                        $("#price-modal").modal('hide');
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

    $('#unit-form').submit(function (e) {
        e.preventDefault();

    })

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});
