function showOverlay(text='') {
    $.LoadingOverlay("show", {
        image: "",
        fontawesome: "fa fa-spinner fa-spin",
        fontawesomeColor: "#e46a76",
        size: 3,
        text: text,
        textColor: "#e46a76"
    });
}

function hideOverlay() {
    $.LoadingOverlay("hide");
}

function showErrorToast(msg) {
    $.toast({
        heading: '错误',
        text: msg,
        position: 'top-right',
        loaderBg:'#ff6849',
        icon: 'error',
        hideAfter: 5000
    });
}

const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'AUD',
    minimumFractionDigits: 2
});

function formatPrice(val) {
    if (val > 0)
        return '$' + val;

    if (val == 0)
        return '-';

    if (val < 0)
        return '($' + Math.abs(val) + ')';
}
$(function(){
    $("#department").on("change",function(){
        var department_id = $(this).val();
        showOverlay();
        $.ajax({
            url:'/doctor/getDoctors',
            type:'GET',
            data: "department_id=" + department_id,
            cache: false,
            dataType: 'json',
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == '0') {
                    var doctors = resp.data;
                    var html = '<option value="">请选择医生</option>';
                    for(var i = 0 ; i < doctors.length; i++){
                        html +='<option value="' + doctors[i].id+'" data-from="'+doctors[i].from+'" data-to="'+doctors[i].to+'">' + doctors[i].name + '</option>';
                    }
                    $('#doctor_id').html(html);
                } else {
                    Swal.fire({
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
                    type: 'error',
                    text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }

        })
    });
})
function removeMedicine(obj) {
    var id = $(obj).data("index");
    $("#medicine option[value='" + id + "']").attr("disabled",false);
    var row = $(obj).parent().parent();
    row.remove();
    calcPrice();
}
function getContraryMedicines(medicine_id,callback){
    showOverlay();
    $.ajax({
        url:'/doctor/getContraryMedicines',
        type:'GET',
        data: "medicine_id=" + medicine_id,
        cache: false,
        dataType: 'json',
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                callback(resp);
            } else {
                Swal.fire({
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
                type: 'error',
                text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                title: '错误',
                showConfirmButton: false,
                timer: 3000
            });
        }

    })
}
function changeMaxMinValue(){
    return;

    $('input[type="number"]').on('change',function(){
        v = parseInt($(this).val());
        min = parseInt($(this).attr('min'));
        max = parseInt($(this).attr('max'));
        if (v < min){
            $(this).val(min);
        } else if (v > max){
            $(this).val(max);
        }
    })
}

function getStateWord(data) {
    switch (data) {
        case 'ACCEPT':
            return '接受';
            break;
        case 'WAITING_TREATMENT':
            return '等待治疗';
            break;
        case 'TREATING':
            return '在治疗中';
            break;
        case 'BEFORE_TREATING_PAY':
            return '结束治疗';
            break;
        case 'AFTER_TREATING_PAY':
            return '结束治疗付款';
            break;
        case 'CLOSE':
            return '结束';
            break;
        default:
            return '';
    }

}
