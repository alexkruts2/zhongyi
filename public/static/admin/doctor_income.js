var doctor_profit_table;

function initHospital(){
    if($("#from").length)
        $('#from').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD   HH:mm' });
    if($("#to").length)
        $('#to').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD   HH:mm' });
}
$(function(){
    initHospital();
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
