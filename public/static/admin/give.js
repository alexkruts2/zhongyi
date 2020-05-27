var medicineTable,global_guahao;
$(function(){
    drawMedicineTable();
})

function giveMedicine () {
    guahao = global_guahao;
    if(guahao==''||guahao==undefined||guahao==null){
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入挂号'
        });
        return;
    }
    showOverlay();
    $.ajax({
        url: '/doctor/recipe/giveMedicine',
        data: "guahao=" + guahao,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                $("#giveMedicine").prop("disabled",true);
                Swal.fire({
                    type: 'info',
                    title: '发药成功',
                    showConfirmButton: false,
                    timer: 3000
                });
                drawMedicineTable();
                $("#myModal").modal('hide');
            } else {
                $("#giveMedicine").prop("disabled",true);
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

function drawMedicineTable(){
    if(medicineTable)
        medicineTable.destroy();
    medicineTable = $('#tbl_give').DataTable({
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
            "url": '/doctor/recipe/give',
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
                    return '<button class="btn btn-sm btn-info m-l-5" onclick="getGuahaoData(\''+full.guahao+'\')"><i class="ti-wand"></i>发药</button>'+
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

function getGuahaoData(guahao){
    global_guahao = guahao;
    if(guahao==''||guahao==undefined||guahao==null){
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入挂号'
        });
        return;
    }
    showOverlay();
    $("#myModal").modal('show');

    $.ajax({
        url: '/doctor/recipe/checkGuahao',
        data: "guahao=" + guahao,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                var recipe = JSON.parse(resp.data.recipe);
                // var houfang = JSON.parse(resp.data.houfang);
                $("#medicines").val(resp.data.recipe);

                drawMedicine(JSON.parse(resp.data.recipe),true,false,true);

            } else {
                $("#giveMedicine").prop("disabled",true);
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
