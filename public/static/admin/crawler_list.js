var crawler = '';
$(function () {
    $(".setting").on("click", function () {
        crawler = $(this).data("crawler");
        var url = '';
        if(crawler=='suburbprofile')
            url = '/admin/location/suburbprofile/getCrawler';
        else
            url = '/admin/crawler/' + crawler + '/getCrawler';

        if(crawler=='weixin')
            $("#weixin-setting-modal").modal();
        else
            $('#setting-modal').modal();
        showOverlay();
        $.ajax({
            url: url,
            type: "POST",
            success: function (response) {
                hideOverlay();
                if(crawler=='weixin'){
                    var app_id = response.data.app_id;
                    var webhook_url = response.data.webhook_url;
                    var users = response.data.source_code;
                    $("#app_id").val(app_id);
                    $("#weixin_webhook_url").val(webhook_url);
                    $('#users').tagsinput('removeAll');

                    $("#users").tagsinput("add",users);

                }else{
                    if(response.data == null){
                        $("#source_code").html('');
                        $("#source_code").val('');
                        $("#webhook_url").val('');
                    }else{
                        var source_code = response.data.source_code;
                        var webhook_url = response.data.webhook_url;

                        $("#source_code").val(source_code);
                        $("#source_code").html(source_code);
                        $("#webhook_url").val(webhook_url);
                    }
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
                    timer: 1500
                });
            }
        });
    });

    $('#setting-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var form = new FormData($(this)[0]);
        $.ajax({
            url: '/admin/crawler/'+crawler+'/setCrawler',
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

    $('#weixin-setting-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var form = new FormData($(this)[0]);
        $.ajax({
            url: '/admin/crawler/weixin/setCrawler',
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

    $(".shenjian_ctrl_button").on("click",function () {
        crawler = $(this).data("crawler");
        var url = '';
        if(crawler=='suburbprofile')
            url = '/admin/location/suburbprofile/start';
        else
            url = '/admin/crawler/' + crawler + '/start';
        showOverlay();
        $.ajax({
            url:url,
            type:"GET",
            success:function(response){
                setCrawlerStatus(response.data,crawler);
                hideOverlay();
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

    showOverlay();
    checkStatus();

    setInterval(function(){
        checkStatus();
    }, 15000);

});


function setCrawlerStatus(status,crawler) {

    // var button = $("button").find("[data-crawler='"+ crawler+"']");
    var button = $("#btn_"+ crawler);
    button.attr('class', '');
    button.addClass('btn');
    if (status === 'starting') {
        button.html("<i class='fas fa-sync-alt fa-spin'></i>&nbsp;启动中");
        button.addClass('btn-warning');
        button.prop("disabled",true);
    } else if (status === 'running') {
        button.html("<i class='fas fa-power-off'></i>&nbsp;停止");
        button.addClass('btn-danger');
        button.prop("disabled",false);
    } else if(status === 'stopping') {
        button.html("<i class='fas fa-sync-alt fa-spin'></i>&nbsp;停止中");
        button.addClass('btn-warning');
        button.prop("disabled",true);
    }else{
        button.html("<i class='ti-control-play'></i>启动");
        button.addClass('btn-cyan');
        button.prop("disabled",false);
    }
}

function checkStatus(){
    var url = '/admin/crawler/checkAllState';
        $.ajax({
            url:url,
            type:"GET",
            success:function(response){
                hideOverlay();
                var json = response;
                var datas = json.data;
                setCrawlerStatus(datas.te,'te');
                setCrawlerStatus(datas.suburbprofile,'suburbprofile');
                setCrawlerStatus(datas.airbnb,'airbnb');
                setCrawlerStatus(datas.realestate,'realestate');
                setCrawlerStatus(datas.weixin,'weixin');
            }
        });

}