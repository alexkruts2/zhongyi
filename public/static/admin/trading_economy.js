/**
 * Created by GCM on 2019/7/15.
 */
var table;

function search() {
    var countryId = $('#country').val();
    if (countryId < 1) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择国家'
        });
        return;
    }
    table.clear().draw();
    $.ajax({
        url: '/admin/crawler/te/data',
        data: "country_id=" + countryId,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                for (var i = 0 ; i < resp.data.length ; i++) {
                    var item = resp.data[i];
                    table.row.add([item.country_name, item.inflation_cpi, item.hpi, item.hpi_yoy,
                        item.hh_debt_gdp,item.interest_rate,item.mortgage_rate,item.consumer_confi,item.unemployment,item.gdp_growth,item.created_at]).draw();
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

$(function () {
    table = $('#table').DataTable({
        "columnDefs": [
            {"orderable": false, "targets": [2]}
        ],
        "order": [[0, "asc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {"className": "text-center", "targets": "_all"}
        ],
    });
    $("#shenjian_ctrl_button").on("click",function () {
        showOverlay();
        $.ajax({
            url:"/admin/crawler/te/start",
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
    $("#setting").on("click",function(){
        $('#setting-modal').modal();
        showOverlay();
        $.ajax({
            url:'/admin/crawler/te/getCrawler',
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
    setInterval(function(){
        $.ajax({
            url:"/admin/crawler/te/status",
            type:"GET",
            success:function(response){
                var json = response;
                var status = json.data.state;
                setCrawlerStatus(status);
            }
        });
    }, 10000);

    showOverlay();
    $.ajax({
        url:"/admin/crawler/te/status",
        type:"GET",
        success:function(response){
            hideOverlay();
            var json = response;
            var status = json.data.state;
            setCrawlerStatus(status);
        }
    });

});


$('#setting-form').submit(function (e) {
    e.preventDefault();
    showOverlay();
    var form = new FormData($(this)[0]);
    $.ajax({
        url: '/admin/crawler/te/setCrawler',
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
                    timer: 3000
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
