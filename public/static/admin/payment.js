var paymentTable,jiaoFeiTable;
$(function(){
    if($("#tbl_payment").length)
        drawPaymentTable();
    if($("#tbl_jiaofei").length){
        drawJiaoFeiTable();
        $("#myModal").modal('hide');
    }


    $('#tbl_payment tbody').on( 'click', 'tr', function () {
        paymentTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        select_treat_id = $(this).attr('id').replace('payment_','');
    });

})
function inputGuahao() {
    var guahao = $('#guahao').val();
    if(guahao==''||guahao==null||guahao==undefined){
        Swal.fire({
            type: 'info',
            text: '请输入挂码。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    showOverlay();
    $.ajax({
        url: '/doctor/accept/payment/getData',
        data: "guahao=" + guahao,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                $("#guahaoID").val(resp.data.guahao);
                $("#patient_name").val(resp.data.patient_name);
                $("#recipe").val(resp.data.recipe);
                $("#price").val(resp.data.price);
                $("#treat_id").val(resp.data.id);
                // $("#fuNumber").val(resp.data.fuNumber);
                // $("#daiNumber").val(resp.data.fuNumber * resp.data.daiNumber);
                $('#myModal').modal('hide');
                $("#payment-form").show();

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
function payTreatment() {

    var pay_type = $("#pay_type").val();
    if(pay_type==''||pay_type==undefined||pay_type==null){
        Swal.fire({
            type: 'error',
            title: '请选择付款方式。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    var treat_id = $("#treat_id").val();
    showOverlay();
    $.ajax({
        url: '/doctor/accept/payment/done',
        data: "id=" + treat_id+'&pay_type='+pay_type,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                Swal.fire({
                    type: 'info',
                    title: '成功',
                    showConfirmButton: false,
                    timer: 3000
                });
                drawJiaoFeiTable();
                $("#myModal").modal('hide');
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

function drawPaymentTable() {
    paymentTable = $('#tbl_payment').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'payment_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "bFilter": false,
        info: false,
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/accept/payment/list',
            "dataType":"json"
        },
        columns: [
            {data: 'treat_start'},
            {data: 'patient_name'},
            {data: 'recipe_name'},
            {data:'price'},
            {data:'doctor_name'},
            {data:'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [1],
                'orderable': false,
            },
            {
                "aTargets": [2],
                'orderable': false,
            },
            {
                "aTargets": [4],
                'orderable': false,
            },
            {
                "aTargets": [5],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/history/detail/' + data+ '\'"><i class="ti-pencil-alt"></i>详情</button>';
                }

            },

            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback":function(settings){
            if(settings.aoData.length)
                $( "#tbl_guahao tbody tr:first-child" ).trigger('click');
        }
    });

}
function drawJiaoFeiTable() {
    if(jiaoFeiTable)
        jiaoFeiTable.destroy();
    jiaoFeiTable = $('#tbl_jiaofei').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'payment_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        info: false,
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/accept/payment/create',
            "dataType":"json"
        },
        columns: [
            {data: 'treat_start'},
            {data: 'guahao'},
            {data: 'patient_name'},
            {data:'phone_number'},
            {data:'price'},
            {data:'department_name'},
            {data:'doctor_name'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [7],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-sm btn-info m-l-5" onclick="showPayModal('+full.id+')"><i class="fab fa-amazon-pay"></i>付款</button>'+
                        '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/history/detail/' + full.id+ '\'"><i class="ti-pencil-alt"></i>详情</button>';
                }

            },

            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback":function(settings){
            if(settings.aoData.length)
                $( "#tbl_guahao tbody tr:first-child" ).trigger('click');
        }
    });

}

function showPayModal (id) {
    $("#treat_id").val(id);
    $("#myModal").modal('show');
}
