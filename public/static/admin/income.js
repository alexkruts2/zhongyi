new Chart(document.getElementById("chart2"),
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
    });
