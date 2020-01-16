var table;
$(function () {
    $("#setting").on("click", function () {
        $('#setting-modal').modal();
        showOverlay();
        $.ajax({
            url: '/admin/crawler/weixin/getCrawler',
            type: "POST",
            success: function (response) {
                hideOverlay();
                var app_id = response.data.app_id;
                var webhook_url = response.data.webhook_url;
                var users = response.data.source_code;
                $("#app_id").val(app_id);
                $("#weixin_webhook_url").val(webhook_url);
                $('#users').tagsinput('removeAll');

                $("#users").tagsinput("add",users);
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
    });
    $('#setting-form').submit(function (e) {
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
    $("#shenjian_ctrl_button").on("click",function () {
        showOverlay();
        $.ajax({
            url:"/admin/crawler/weixin/start",
            type:"GET",
            success:function(response){
                hideOverlay();

                if(response.data=="starting"){
                    Swal.fire({
                        position: 'top-end',
                        type: '',
                        text: '',
                        title: '启动中',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }else if(response.data=="running"){
                    Swal.fire({
                        position: 'top-end',
                        type: '',
                        text: '',
                        title: '启动中',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }else if(response.data=="stoped"){
                    Swal.fire({
                        position: 'top-end',
                        type: '',
                        text: '',
                        title: '停止中',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }else if(response.data=='stopping'){
                    Swal.fire({
                        position: 'top-end',
                        type: '',
                        text: '',
                        title: '停止中',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
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
        });
    });
    setInterval(function(){
        $.ajax({
            url:"/admin/crawler/weixin/status",
            type:"GET",
            success:function(response){
                var json = response;
                var status = json.data.state;
                setCrawlerStatus(status);
            }
        });
    }, 10000);
    drawTable();
    showOverlay();
    $.ajax({
        url:"/admin/crawler/weixin/status",
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
    if(table!=undefined&&table!=''&&table!=null)
        table.destroy();

    table = $('#table').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
        $(nRow).attr('id', 'state_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "asc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"GET",
            "url": '/admin/crawler/weixin/data',
            "dataType":"json"
        },
        columns: [
            {data: 'article_title'},
            {data: 'article_author'},
            {data: 'weixin_nickname'},
            {data: 'is_original'},
            {data: 'weixin_avatar'},
            {data: 'article_publish_time'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "mRender":function(data,type,full){
                    return data.length > 10 ?
                        data.substr( 0, 10 ) +'…' :
                        data;
                }
            },{
                "aTargets":[4],
                "mRender":function(data,type,full){
                    return '<img class="avatar-img" width="30" height="30" src="' + data + '">';
                }
            },{
                "aTargets":[5],
                "mRender":function (data,type,full) {
                    return timeConverter(data);
                }
            },{
                "className": "text-center", "targets": "_all"
        }
        ],
    });
}
function timeConverter(UNIX_timestamp){
    var a = new Date(UNIX_timestamp * 1000);
    // var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    date = ("0" + date).slice(-2);
    var hour = a.getHours();
    hour = ("0" + hour).slice(-2);
    var min = a.getMinutes();
    min = ("0" + min).slice(-2);
    var sec = a.getSeconds();
    sec = ("0" + sec).slice(-2);
    var time = year + '-' + month + '-'  + date + ' ' +  hour + ':' + min + ':' + sec ;
    return time;
}
