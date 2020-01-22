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
            "scales":{"yAxes":[{"ticks":{"beginAtZero":true}}]},
            onClick: handleClick
        }
    };
var chart;

$(function(){
    drawDoctor();
});
function drawDoctor(){
    if(chart)
        chart.destroy();
        url = '/admin/income/getDoctorAll';
    showOverlay();
    $.ajax({
        url: url,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                var label = [],datas = [];
                for(var i=0; i < resp.data.length;i++){
                    label.push(resp.data[i].name);
                    datas.push(resp.data[i].sum);
                }
                chartData.data.datasets[0].label = '医生收入';
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
function handleClick(c,i) {
    e = i[0];
    console.log(e._index)
    var x_value = this.data.labels[e._index];
    var y_value = this.data.datasets[0].data[e._index];
    console.log(x_value);
    console.log(y_value);


    var form = document.createElement("form");
    var element1 = document.createElement("input");

    form.method = "GET";
    form.action = "/admin/income/all";

    element1.value=x_value;
    element1.name="doctor_name";
    form.appendChild(element1);


    document.body.appendChild(form);

    form.submit();


}
