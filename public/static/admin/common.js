var selectedBiaozheng = [],selectedLizheng=[],selectedBiaoli=selectedMaizheng =[];

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

    var department_id = $("#department").val();
    if(department_id==''||department_id==null||department_id==undefined)
        $("#department").trigger("change");
});
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
var monthLabel = {
    'January':'一月',
    'February':'二月',
    'March':'三月',
    'April':'四月',
    'May':'五月',
    'June':'六月',
    'July':'七月',
    'August':'八月',
    'September':'九月',
    'October':'十月',
    'November':'十一月',
    'December':'十二月'
}
function scrollSidebarTop(){
    if (navigator.userAgent.indexOf("Chrome") !== -1){
        $(".sidebar-menu").scrollTop($(".sidebar-menu").prop("scrollHeight")-700);
        $(".sidebar-menu").perfectScrollbar('update');
    }else if (navigator.userAgent.indexOf("Mozilla") !== -1){
        $(".sidebar-menu").scrollTop($(".sidebar-menu").prop("scrollHeight"));
        $(".sidebar-menu").perfectScrollbar('update');
    }
}
function getContraryIds(medicine_id,callback){
    showOverlay();
    $.ajax({
        url: '/doctor/history/getContraryIds',
        data: 'id='+medicine_id,
        type: 'GET',
        cache: false,
        dataType: 'json',
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                callback(resp.data);
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
    });

}

function drawItems(type,itemList,selectedList, trigger = true) {
    var html = '<div class="form-group mt-3 row">';
    html+='<div class="col-sm-3">'
    var remain = itemList.length % 4;
    for(var i=0; i < itemList.length; i++){
        var checked = selectedList.includes(itemList[i])?"checked":'';
        html+='\t<div class="custom-control custom-checkbox">\n' +
            '\t\t<input type="checkbox" class="custom-control-input" id="'+type+'_'+i+'" name="'+type+'[]" value="'+itemList[i]+'" '+ checked +'>\n' +
            '\t\t<label class="custom-control-label" for="'+type+'_'+i+'">'+itemList[i]+'</label>\n' +
            '\t</div>\n';
        if((Math.floor(itemList.length/4) + remain>0?1:0) ==(i+1)||(2*Math.floor(itemList.length/4) + remain>1?1:0 ==(i+1))||(3*Math.floor(itemList.length/4)+ + remain>2?1:0)==(i+1))
            html+='</div>\n' +
                '<div class="col-sm-3">\n';
    }
    html+='</div>\n' +
        '</div>';
    $("#"+type+"Section").html(html);
    if (trigger == true)
        changeTrigger();
}
function arrayUnique(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
}

function changeTrigger() {
    $( "input[type='checkbox']" ).on('change',function(e){
        var elName = this.name;
        var itemList;
        switch (elName) {
            case 'biaozheng[]':
                itemList = selectedBiaozheng;
                break;
            case 'lizheng[]':
                itemList = selectedLizheng;
                break;
            case 'biaoli[]':
                itemList = selectedBiaoli;
                break;
            case 'maizheng[]':
                itemList = selectedMaizheng;
                break;
            default:
                itemList = selectedBiaozheng;
        }
        if(this.checked)
            itemList.push(this.value);
        else{
            var index = itemList.indexOf(this.value);
            if (index !== -1) itemList.splice(index, 1);
        }

        switch (elName) {
            case 'biaozheng[]':
                selectedBiaozheng = itemList;
                break;
            case 'lizheng[]':
                selectedLizheng = itemList;
                break;
            case 'biaoli[]':
                selectedBiaoli = itemList;
                break;
            case 'maizheng[]':
                selectedMaizheng = itemList;
                break;
            default:
                selectedBiaozheng = itemList;
        }

        if ($("#question_title").length > 0) {
            var data = $("#question_title").val();
            if(data==''||data==null||data==undefined) {

                drawRecipeSections([], [], [], [], []);
                drawRecipeSectionsOther([], [], [], [], []);

                var forms = new FormData($("#question-form")[0]);

                showOverlay();
                $.ajax({
                    url: '/doctor/inquiry/getRecipeOther',
                    data: forms,
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (resp) {
                        hideOverlay();
                        if (resp.code == 0) {
                            drawRecipeOther(resp.data);

                            // fuDaiNumbers = JSON.parse(resp.data.question.fuDaiNumber);
                        } else {
                            hideOverlay();
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
                });

            } else {
                return;
            }
        }

    })
}
function arr_diff (a1, a2) {

    var a = [], diff = [];

    for (var i = 0; i < a1.length; i++) {
        a[a1[i]] = true;
    }

    for (var i = 0; i < a2.length; i++) {
        if (a[a2[i]]) {
            delete a[a2[i]];
        } else {
            a[a2[i]] = true;
        }
    }

    for (var k in a) {
        diff.push(k);
    }

    return diff;
}

