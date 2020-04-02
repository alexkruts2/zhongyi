var doctor_profit_table;

function initHospital(){
    if($("#from").length)
        $('#from').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD   HH:mm' ,
            okText:"确认",
            cancelText:"取消",
            lang : 'zh_CN'
        });
    if($("#to").length)
        $('#to').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD   HH:mm' ,
            okText:"确认",
            cancelText:"取消",
            lang : 'zh_CN'
        });
}
$(function(){
    initHospital();
    getDoctors('','');
    $("#department_income").on("change",function(){
        var department_id = $(this).val();
        var hospital_id = $("#hospital").val();
        getDoctors(department_id,hospital_id);
    });
    $("#hospital").on("change",function(){
        var department_id = $(this).val();
        var hospital_id = $("#hospital").val();
        getDoctors(department_id,hospital_id);
    })
    $("#doctor_id").on("change",function(){
        // var phone = $(this).data('phone');
        // var doctor_code = $(this).data('doctorid');

        var doctor_id = $(this).val();
        var selected = $(this).find('option:selected');
        var phone = selected.data('phone');
        var doctorid = selected.data('doctorid');

        $("#phone_number").val(phone);
        $("#doctor_code").val(doctorid);
    })
});
function drawDoctorProfitTable() {
    if(doctor_profit_table)
        doctor_profit_table.destroy();
    doctor_profit_table = $('#tbl_doctor_profit').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "paging":   false,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/admin/income/getDoctorProfit',
            "data":{
                hospital:$("#hospital").val(),
                department:$("#department").val(),
                from:$("#from").val(),
                doctor_id:$("#doctor_id").val(),
                to:$("#to").val()
            },
            "dataType":"json"
        },
        columns: [
            {data: null},
            {data: 'patient_name'},
            {data: 'ID_Number'},
            {data: 'department_name'},
            {data: 'doctor_name'},
            {data: 'price'},
            {data:'treat_start'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
            return nRow;
        },
        fnDrawCallback:function(settings){
            console.log(settings.aoData);
            var sum = 0.0;
            for(var i = 0 ; i<settings.aoData.length;i++){
                sum += 1.0*settings.aoData[i]._aData.price;
            }
            $("#totalSum").html(sum);
            console.log(sum);
        }
    });
}

function getDoctors(department_id,hospital_id){
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
                var html = '<option value="">全部医生</option>';
                for(var i = 0 ; i < doctors.length; i++){
                    if(hospital_id==doctors[i].hospital_id||hospital_id=='')
                        html +='<option value="' + doctors[i].id+'" data-phone="'+doctors[i].phone+'" data-doctorid="'+doctors[i].id+'">' + doctors[i].name + '</option>';
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
}
