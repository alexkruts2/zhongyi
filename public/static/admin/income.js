var chartData =
    {
        "type":"bar",
        "data":{"labels":["2019-1","2019-2","2019-2","2019-2","2019-2","2019-2","2019-2","2019-2","2019-2","2019-2"],
            "datasets":[{
                "label":"My First Dataset",
                "data":[65,59,80,81,56,55,40,40,40,40],
                "fill":true,
                "borderWidth":1}
            ]},
        "options":{
            "scales":{"yAxes":[{"ticks":{"beginAtZero":true}}]}
        }
    };
var chart,doctor_name,hospital_table;

$(function(){
    // var url = new URL(location.href);
    // doctor_name = url.searchParams.get("doctor_name");
    // doctor_name = doctor_name==null?'':doctor_name;
    // if(doctor_name!='')
    //     $(".card-title").html(doctor_name);
    // drawAll('day');
    initHospital();
});
function initHospital(){
    if($("#from").length)
        $('#from').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD   HH:mm' });
    if($("#to").length)
        $('#to').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD   HH:mm' });
}
function drawAll(period){
    if(chart)
        chart.destroy();
    var url = '';
    if(period=='year')
        url = '/admin/income/getAllMonth';
    else if(period=='month')
        url = '/admin/income/getAllDay';
    else if(period=='week')
        url = '/admin/income/getAllWeek';
    else if(period=='day')
        url = '/admin/income/getHourlyData';
    showOverlay();
    $.ajax({
        url: url,
        data:'doctor_name='+doctor_name,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                var label = [],datas = [];
                if(period=='year'){
                    for(var i=0; i < resp.data.length;i++){
                        if(resp.data[i].year==null||resp.data[i].year==undefined||resp.data[i].year=='')
                            continue;
                        label.push(resp.data[i].year+'年 '+ monthLabel[resp.data[i].month]);
                        datas.push(resp.data[i].sum);
                    }
                    chartData.data.datasets[0].label = '月收入';
                }else if(period=='month'){
                    for(var j=1; j<resp.data[0].day;j++){
                        label.push(j);
                        datas.push(0);
                    }
                    for(var i=0; i < resp.data.length;i++){
                        label.push(resp.data[i].day);
                        datas.push(resp.data[i].sum);
                        for(var j=resp.data[i].day+1; resp.data[i+1]!=undefined&&j<resp.data[i+1].day; j++){
                            label.push(j);
                            datas.push(0);
                        }
                    }
                    for(var j=resp.data[i-1].day; j<32;j++){
                        label.push(j);
                        datas.push(0);
                    }
                    chartData.data.datasets[0].label = '本月收入';
                }else if(period=='week'){
                    var sunday = getSunday();
                    var saturday = getSaturday();
                    if(resp.data[0]==undefined){
                        for(var j=sunday;  j<saturday+1;j++){
                            label.push(j);
                            datas.push(0);
                        }
                    }else{
                        for(var j=sunday; resp.data[0]!=undefined && j<resp.data[0].day;j++){
                            label.push(j);
                            datas.push(0);
                        }
                        for(var i=0; i < resp.data.length;i++){
                            label.push(resp.data[i].day);
                            datas.push(resp.data[i].sum);
                            for(var j=resp.data[i].day+1; resp.data[i+1]!=undefined&&j<resp.data[i+1].day; j++){
                                label.push(j);
                                datas.push(0);
                            }
                        }
                        if(resp.data[i-1]!=undefined){
                            for(var j=resp.data[i-1].day; j<saturday+1;j++){
                                label.push(j);
                                datas.push(0);
                            }
                        }
                    }
                    chartData.data.datasets[0].label = '本周收入';
                }else if(period=='day'){
                    if(resp.data[0]==undefined){
                        for(var j=0;  j<24;j++){
                            label.push(j);
                            datas.push(0);
                        }
                    }else{
                        for(var j=0; j<resp.data[0].hour;j++){
                            label.push(j);
                            datas.push(0);
                        }
                        for(var i=0; i < resp.data.length;i++){
                            label.push(resp.data[i].hour);
                            datas.push(resp.data[i].sum);
                            for(var j=resp.data[i].hour+1; resp.data[i+1]!=undefined&&j<resp.data[i+1].hour; j++){
                                label.push(j);
                                datas.push(0);
                            }
                        }
                        for(var j=resp.data[i-1].hour; j<24;j++){
                            label.push(j);
                            datas.push(0);
                        }
                    }

                    chartData.data.datasets[0].label = '今日收入';
                }
                chartData.data.labels = label;
                chartData.data.datasets[0].data = datas;

                chart = new Chart(document.getElementById("chart2"),chartData);

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
function drawHospitalTable() {
    if(hospital_table)
        hospital_table.destroy();

    hospital_table = $('#tbl_hospital').DataTable({
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
            "url": '/admin/income/getHospitalProfit',
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

function getSunday() {
    var curr = new Date; // get current date
    var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
    return first;
}
function getSaturday() {
    var curr = new Date; // get current date
    var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
    var last = first + 6; // last day is the first day + 6

    return last;
}

