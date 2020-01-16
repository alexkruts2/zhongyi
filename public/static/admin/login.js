
var timer = 60;

var getAuthCode = function () {
    var mobile = jQuery('#mobile').val();
    jQuery.post('/admin/login1', {mobile: mobile}, function (resp) {
        if (resp.code == 0) {
            setTimeout(countDown, 1000);
        }
    });
};

var countDown = function () {
    if (timer > 0) {
        timer--;
        setTimeout(countDown, 1000);
        jQuery('#btnauthcode').attr("disabled", true);
        jQuery('#btnauthcode').html(timer);
    } else {
        jQuery('#btnauthcode').removeAttr("disabled");
        jQuery('#btnauthcode').html("获取验证码");
        timer = 60;
    }
};