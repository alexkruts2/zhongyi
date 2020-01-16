var allTable,individualTable;
var treat_id;
$(function(){
    drawAllTable();
    drawIndividualTable();
    showIDModal();
});
function drawAllTable() {
    allTable = $('#tbl_history_all').DataTable({
        "processing": true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'treat_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide": true,
        "order": [[0, "desc"]],
        "pageLength": 10,
        info: false,
        "aLengthMenu": [10, 20, 50],
        "ajax": {
            "type": "POST",
            "url": '/doctor/history/getAllData',
            'data': {id: ''},
            "dataType": "json"
        },
        columns: [
            {data: 'patient_name'},
            {data: 'disease_name'},
            {data: 'number'},
            {data: 'date'},
            {data: 'recipe'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [3],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return data.substring(0, 10);
                }
            }, {
                "aTargets": [5],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/history/individual/' + data + '\'"><i class="ti-pencil-alt"></i>详情</button>';

                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback": function (settings) {
        }
    })
}
function drawIndividualTable() {
    individualTable = $('#tbl_history_individual').DataTable({
        "processing": true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'treat_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide": true,
        "order": [[0, "desc"]],
        "pageLength": 10,
        info: false,
        "aLengthMenu": [10, 20, 50],
        "ajax": {
            "type": "POST",
            "url": '/doctor/inquiry/getTreatementData',
            'data': {id: treat_id},
            "dataType": "json"
        },
        columns: [
            {data: 'date'},
            {data: 'disease_name'},
            {data: 'recipe'},
            {data: 'doctor_name'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [0],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return data.substring(0, 10);
                }
            }, {
                "aTargets": [4],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/inquiry/detail/' + data+ '\'"><i class="ti-pencil-alt"></i>详情</button>';

                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback": function (settings) {
        }
    })
}
function inputIDNumber() {
    var IDNumber = $('#id_number').val();
    if(IDNumber==''||IDNumber==null||IDNumber==undefined){
        Swal.fire({
            type: 'info',
            text: '请输入身份证号码。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    $.ajax({
        url: '/doctor/history/getTreat_id',
        data: 'IDNumber='+IDNumber,
        type: 'GET',
        cache: false,
        dataType: 'json',
        processData: false,
        success: function (resp) {
            if (resp.code == 0) {
                window.location.href = '/doctor/history/individual/'+resp.data;
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
            Swal.fire({
                type: 'error',
                text: 'Internal Error ' + e.status + ' - ' + e.resp.message,
                title: '错误',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });

}
function showIDModal(){
    $("#myModal").modal({backdrop: 'static', keyboard: false});
}
