
var suburb_table, selected_row;

function searchSuburb() {
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
    var cityId = $('#city').val();

    suburb_table.clear().draw();
    showOverlay();
    $.ajax({
        url: '/admin/base/city/suburbs',
        data: "country_id=" + countryId + "&state_id=" + stateId + "&city_id=" + cityId,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                console.log(resp.data);
                suburb_table.rows.add(resp.data).draw();
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

$(function () {
    $("#country").on("change",function(){
        if(this.value < 1){
            $("#state").prop("disabled", true);
            $("#state").selectpicker('refresh');
            $("#city").prop("disabled", true);
            $("#city").selectpicker('refresh');
        }
        else{
            $("#state").prop("disabled", false);
            $("#city").prop("disabled", false);

            $('#state')
                .find('option')
                .remove()
                .end()
                .append('<option value="0">全部</option>')
                .val('0');
            $('#city')
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
                    for(var i = 0 ; i < states.length;i++){
                        $("#state").append(new Option(states[i].name_cn, states[i].id));
                    }
                    $('#state').selectpicker('refresh');
                    $("#city").selectpicker('refresh');

                    hideOverlay();
                    searchSuburb();
                }
            });
        }
    });

    $("#state").on("change",function(){
        if( this.value > 0 ){
            $('#city')
                .find('option')
                .remove()
                .end()
                .append('<option value="0">全部</option>')
                .val('0');

            showOverlay();
            $.ajax({
                url:"/admin/base/state/cities",
                data:{
                    country_id: $('#country').val(),
                    state_id: this.value
                },
                type:"GET",
                success:function(response){
                    var cities = response.data;
                    for(var i = 0 ; i < cities.length;i++){
                        $("#city").append(new Option(cities[i].name_cn, cities[i].id));
                    }
                    $('#city').selectpicker('refresh');

                    hideOverlay();
                    searchSuburb();
                }
            })
        }
    });

    $("#city").on("change",function(){
        searchSuburb();
    });

    suburb_table = $('#table').DataTable({
        "columnDefs": [
            {"orderable": false, "targets": [2]}
        ],
        "order": [[0, "asc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "columns": [
            {"data": "country"},
            {"data": "state"},
            {"data": "city"},
            {"data": "name_en"},
            {"data": "name_cn"},
            {"data": "post_code"},
            {"data": "id"}
        ],
        "aoColumnDefs": [
            {
                "aTargets": [6],
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-info m-r-5" type="button" onclick="showModal(false,' + data + ', this)"><i class="ti-pencil-alt"></i></button>' +
                        '<button class="btn btn-danger" type="button" onclick="deleteSuburb(this,' + data + ')"><i class="ti-trash"></i></button>';
                },
                "orderable":false
            },
            {"className": "text-center", "targets": "_all"}
        ],
    });

    $('#suburb-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var form = new FormData($(this)[0]);

        $.ajax({
            url: '/admin/base/suburb/addorUpdate',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $('#suburb-modal').modal('hide');
                    var suburbId = $('#suburb_id').val();
                    if ( suburbId < 1 ){
                        suburb_table.row.add(resp.data).draw();
                    } else {
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

function changeCountry() {
    $('#state_id')
        .find('option')
        .remove()
        .end();
    $('#state_id').selectpicker('refresh');

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
            if (states.length > 0) {
                $('#state_id').val(states[0].id);
                $('#state_id').selectpicker('refresh');
            }
        }
    });
}

function changeState() {
    $('#city_id')
        .find('option')
        .remove()
        .end();
    $('#city_id').selectpicker('refresh');

    $.ajax({
        url:"/admin/base/state/cities",
        data:{
            country_id: $('#country_id').val(),
            state_id: $('#state_id').val()
        },
        type:"GET",
        success:function(response){
            var cities = response.data;
            for(var i = 0 ; i < cities.length;i++){
                $("#city_id").append(new Option(cities[i].name_cn, cities[i].id));
            }
            $('#city_id').selectpicker('refresh');
        }
    })
}

var showModal = function (isAdd, suburb_id, obj) {
    var countryId = $("#country").val();
    var stateId = $("#state").val();
    var cityId = $("#city").val();

    $('#suburb-modal').modal();

    $('#suburb_id').val(suburb_id);

    if (!isAdd) {
        selected_row = suburb_table.row( $(obj).parents('tr') );
        var data = selected_row.data();
        console.log(data);
        $('#country_id').val(data['country_id']);
        $('#country_id').selectpicker('refresh');

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

                $.ajax({
                    url:"/admin/base/state/cities",
                    data:{
                        country_id: $('#country_id').val(),
                        state_id: $('#state_id').val()
                    },
                    type:"GET",
                    success:function(response){
                        var cities = response.data;
                        for(var i = 0 ; i < cities.length;i++){
                            $("#city_id").append(new Option(cities[i].name_cn, cities[i].id));
                        }
                        $('#city_id').val(data['city_id']);
                        $('#city_id').selectpicker('refresh');
                    }
                })

            }
        });
        $('#name_cn').val(data['name_cn']);
        $('#name_en').val(data['name_en']);
        $('#post_code').val(data['post_code']);
        $('#suburb_id').val(data['id']);

    } else {
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

                    if (cityId > 0) {
                        $.ajax({
                            url:"/admin/base/state/cities",
                            data:{
                                country_id: $('#country_id').val(),
                                state_id: stateId
                            },
                            type:"GET",
                            success:function(response){
                                var cities = response.data;
                                for(var i = 0 ; i < cities.length;i++){
                                    $("#city_id").append(new Option(cities[i].name_cn, cities[i].id));
                                }
                                $('#city_id').val(cityId);
                                $('#city_id').selectpicker('refresh');
                            }
                        })
                    }
                }
            });
        }

        $('#name_cn').val('');
        $('#name_en').val('');
        $('#name_en_abbr').val('');
        $('#suburb_id').val('');
    }
};

var deleteSuburb = function(obj, id) {
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
                url: '/admin/base/suburb/delete',
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
                            title: '成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        suburb_table.row( $(obj).parents('tr') )
                            .remove()
                            .draw();
                    } else {

                    }
                }
            });
        }
    });
};
