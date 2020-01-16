var city_table, selected_row;

function searchCity() {
    var countryId = $('#country').val();
    if (countryId < 1) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择国家'
        });
        return;
    }
    var stateId = $('#state').val();

    showOverlay();
    $.ajax({
        url: '/admin/base/state/cities',
        data: "country_id=" + countryId + "&state_id=" + stateId,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                city_table.clear().draw();
                city_table.rows.add(resp.data).draw();
                // for (var i = 0 ; i < resp.data.length ; i++) {
                //     var item = resp.data[i];
                //     city_table.row.add([item.name_cn, item.name_en, item.name_en_abbr, item.id]).draw();
                // }
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

function changeCountry() {
    $('#state_id')
        .find('option')
        .remove()
        .end();

    $.ajax({
        url:"/admin/base/country/states",
        data:{
            country_id: $('#country_id').val()
        },
        type:"GET",
        success:function(response){
            var states = response.data;
            for( var i = 0 ; i < states.length ; i++ ){
                $("#state_id").append(new Option(states[i].name_cn, states[i].id));
            }

            $('#state_id').selectpicker('refresh');
        }
    })
}

$(function () {
    $("#country").on("change",function(){
        if( this.value > 0 ) {
            $("#state").prop("disabled", false);

            $('#state')
                .find('option')
                .remove()
                .end()
                .append('<option value="0">全部</option>')
                .val('0');

            showOverlay();
            $.ajax({
                url:"/admin/base/country/states",
                data:{
                    country_id: this.value
                },
                type:"GET",
                success:function(response){
                    var states = response.data;
                    for( var i = 0 ; i < states.length ; i++ ){
                        $("#state").append(new Option(states[i].name_cn, states[i].id));
                    }

                    $('#state').selectpicker('refresh');

                    hideOverlay();
                    searchCity();
                }
            })
        } else {
            $("#state").prop("disabled", true);
        }
    });

    $("#state").on("change",function(){
        searchCity();
    });

    city_table = $('#table').DataTable({
        "columnDefs": [
            {"orderable": false, "targets": [1]}
        ],
        "order": [[1, "asc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "columns": [
            {"data": "country"},
            {"data": "state"},
            {"data": "name_cn"},
            {"data": "name_en"},
            {"data": "name_en_abbr"},
            {"data": "id"}
        ],
        "aoColumnDefs": [
            {
                "aTargets": [5],
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-info m-r-5" type="button" onclick="showCityModal(false,' + data + ', this)"><i class="ti-pencil-alt"></i></button>' +
                        '<button class="btn btn-danger" type="button" onclick="deleteCity(this,' + data + ')"><i class="ti-trash"></i></button>';
                },
                "orderable":false
            },
            {"className": "text-center", "targets": "_all"}
        ],
    });

    $('#city-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var form = new FormData($(this)[0]);

        $.ajax({
            url: '/admin/base/city/addorUpdate',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $('#city-modal').modal('hide');
                    var cityId = $('#city_id').val();
                    if ( cityId < 1 ){
                        city_table.row.add(resp.data).draw();
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


});


var showCityModal = function (isAdd, city_id, obj) {
    var countryId = $("#country").val();
    var stateId = $("#state").val();

    $('#city-modal').modal();

    $('#country_id').val(countryId);
    $('#country_id').selectpicker('refresh');

    if (stateId > 0) {
        $('#state_id')
            .find('option')
            .remove()
            .end();

        $.ajax({
            url:"/admin/base/country/states",
            data:{
                country_id: $('#country_id').val()
            },
            type:"GET",
            success:function(response){
                var states = response.data;
                for( var i = 0 ; i < states.length ; i++ ){
                    $("#state_id").append(new Option(states[i].name_cn, states[i].id));
                }
                $('#state_id').val(stateId);
                $('#state_id').selectpicker('refresh');
            }
        });
    }

    $('#city_id').val(city_id);

    if (!isAdd) {
        selected_row = city_table.row( $(obj).parents('tr') );
        var data = selected_row.data();
        $('#country_id').val(data['country_id']);
        $('#state_id')
            .find('option')
            .remove()
            .end();

        $.ajax({
            url:"/admin/base/country/states",
            data:{
                country_id: $('#country_id').val()
            },
            type:"GET",
            success:function(response){
                var states = response.data;
                for( var i = 0 ; i < states.length ; i++ ){
                    $("#state_id").append(new Option(states[i].name_cn, states[i].id));
                }
                $('#state_id').val(data['state_id']);
                $('#state_id').selectpicker('refresh');
            }
        });
        $('#name_cn').val(data['name_cn']);
        $('#name_en').val(data['name_en']);
        $('#name_en_abbr').val(data['name_en_abbr']);
        $('#city_id').val(data['id']);
    } else {
        $('#name_cn').val('');
        $('#name_en').val('');
        $('#name_en_abbr').val('');
        $('#city_id').val('');
    }
};

var deleteCity = function(obj, id) {
    Swal.fire({
        title: "你确定要删除该市吗?",
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
                    url: '/admin/base/city/delete',
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
                            city_table.row( $(obj).parents('tr') )
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
