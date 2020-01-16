/**
 * Created by GCM on 2019/7/16.
 */
var table;

$(function () {
    $("#setting").on("click",function(){
        $('#setting-modal').modal();
        showOverlay();
        $.ajax({
            url:'/admin/location/suburbprofile/getCrawler',
            type:"POST",
            success:function(response){
                hideOverlay();
                var source_code = response.data.source_code;
                var webhook_url = response.data.webhook_url;
                $("#source_code").html(source_code);
                $("#webhook_url").val(webhook_url);
            },
            error:function(e){
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

    $('#setting-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var form = new FormData($(this)[0]);
        $.ajax({
            url: '/admin/location/suburbprofile/setCrawler',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    hideOverlay();
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '保存成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
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

    $("#shenjian_ctrl_button").on("click",function () {
        showOverlay();
        $.ajax({
            url:"/admin/location/suburbprofile/start",
            type:"GET",
            success:function(response){
                hideOverlay();
                if(response.data=='starting')
                    Swal.fire({
                        position: 'top-end',
                        type: '',
                        text: '',
                        title: '启动中',
                        showConfirmButton: false,
                        timer: 3000
                    });
                else if(response.data=='stopping')
                    Swal.fire({
                        position: 'top-end',
                        type: '',
                        text: '',
                        title: '停止中',
                        showConfirmButton: false,
                        timer: 3000
                    });
                setCrawlerStatus(response.data);
            },
            error:function(e){
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

    setInterval(function(){
        $.ajax({
            url:"/admin/location/suburbprofile/status",
            type:"GET",
            success:function(response){
                var json = response;
                var status = json.data.state;
                setCrawlerStatus(status);
            }
        });
    }, 10000);
    table = $('#table').DataTable({
        "order": [[0, "asc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {"className": "text-center", "targets": "_all"}
        ],
    });
    $("#state").on("change",function(){
        drawTable();
    })
    drawTable();
    showOverlay();
    $.ajax({
        url:"/admin/crawler/suburbprofile/status",
        type:"GET",
        success:function(response){
            hideOverlay();
            var json = response;
            var status = json.data.state;
            setCrawlerStatus(status);
        }
    });

});

function drawTable(){
    showOverlay();
    var stateId = $("#state").val();
    table.clear().draw();
    $.ajax({
        url: '/admin/location/suburbprofile/data',
        cache: false,
        data: "state_id="+stateId,
        dataType: 'json',
        processData: false,
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                for (var i = 0 ; i < resp.data.length ; i++) {
                    var item = resp.data[i];
                    table.row.add([item.suburb.name_en, item.house_median_price, item.house_median_rent, item.apt_median_price,
                        item.apt_median_rent,item.on_sale,item.on_rental,item.updated_at]).draw();
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

