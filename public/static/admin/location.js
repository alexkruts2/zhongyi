var location_table;
var selected_row;

function deleteLocation(hash, obj) {
    Swal.fire({
        title: "你确定要删除该地址吗?",
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
                url: '/admin/location/delete',
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
                        location_table.row($(obj).parents('tr'))
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
                },
                error: function (e) {
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            });
        }
    });
}
function deleteItem(obj,title) {
    Swal.fire({
        title: "你确定要删除该"+title+"吗?",
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
           $(obj).parents('tr').fadeOut('slow');
        }
    });
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

$(function () {

    location_table = $('#tbl_location').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[1, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"GET",
            "url": '/admin/location/get',
            "dataType":"json"
        },
        columns: [
            {data: 'hash'},
            {data: 'property_name_cn'},
            {data: 'address'},
            {data: 'lat'},
            {data: 'hash'},
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                'aTargets':[0],
                'className':'text-center',
                'mRender':function(data,type,full){
                    return '<a href="/admin/location/detail/' + full.hash + '">' + data + '</a>';
                }
            },
            {
                'aTargets':[1],
                'className':'text-center',
                'mRender':function(data,type,full){
                    return '<a href="/admin/property/' + full.property_hash + '">' + data + '</a>';
                }
            },
            {
                "aTargets":[4],
                "className":'text-center',
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-danger m-l-20" onclick="deleteLocation(\'' + full.hash + '\', this)"><i class="ti-trash"></i></button>';
                }
            },{
                "aTargets":[3],
                "className":'text-center',
                "mRender":function(data,type,full) {
                    return '<a target="_blank" href="https://www.google.com/maps/search/' + full.lat+ ', '+full.lng + '">' + '('+full.lat+', '+full.lng+')' + '</a>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    if(typeof initializeMap != 'undefined'){
        initializeMap();
        var property_hash = getUrlParameter('property_hash');
        if(typeof property_hash !== "undefined") {
            $("#property_hash").val(property_hash);
            $("#address").val(getUrlParameter('address'));
            $("#lat").val(getUrlParameter('lat'));
            $("#lng").val(getUrlParameter('lng'));
            geocodeAndSearch();
        }
        else {
            $("#property_hash").val("");
        }
    }

    if(typeof nearby != 'undefined') {
        if (nearby != undefined && nearby != '' && nearby != null) {
            nearby = nearby.replace(/&quot;/g, '"');
            nearby = nearby.replace(/\\/g, '\\\\');
            var nearbies = JSON.parse(nearby);
            for (const [key, value] of Object.entries(nearbies)) {
                var markerData = {};
                switch (key) {
                    case 'primarySchool':
                        markerData.iconUrl = '/static/images/location/icmark-primary.png';
                        break;
                    case 'highSchool':
                        markerData.iconUrl = '/static/images/location/icmark-high.png';
                        break;
                    case 'university':
                        markerData.iconUrl = '/static/images/location/icmark-university.png';
                        break;
                    case 'restaurant':
                        markerData.iconUrl = '/static/images/location/icmark-eatdrink.png';
                        break;
                    case 'hospital':
                        markerData.iconUrl = '/static/images/location/icmark-hospital.png';
                        break;
                    case 'transport':
                        markerData.iconUrl = '/static/images/location/icmark-transport.png';
                        break;
                    case 'leisure':
                        markerData.iconUrl = '/static/images/location/icmark-goingout.png';
                        break;
                    case 'shopping':
                        markerData.iconUrl = '/static/images/location/icmark-shopping.png';
                        break;
                    default:
                        break;
                }
                markerData.datas = value;
                drawMarkersInUpdate(markerData);
            }
        }
    }

});
