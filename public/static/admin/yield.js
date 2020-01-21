var yieldTable;
$(function(){
    drawYieldTable();
});
function drawYieldTable() {
    yieldTable = $('#tbl_yield').DataTable({
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
            "url": '/doctor/medicine/yield/getData',
            'data': {id: ''},
            "dataType": "json"
        },
        columns: [
            {data: 'guahao'},
            {data: 'patient_name'},
            {data: 'disease_name'},
            {data: 'date'},
            {data: 'recipe'},
            {data: 'state'},
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
                    return data==null||data==''||data==undefined?data:data.substring(0, 10);;
                }
            }, {
                "aTargets": [6],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/medicine/yield/detail/' + data + '\'"><i class="ti-pencil-alt"></i>详情</button>';

                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback": function (settings) {
        }
    })
}
function payTreatment(guahao) {
    showOverlay();
    $.ajax({
        url: '/doctor/medicine/yield/pay',
        data: 'guahao='+guahao,
        type: 'POST',
        cache: false,
        dataType: 'json',
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                window.location.href = '/doctor/medicine/yield/view';
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

}
function cancelTreatment() {
    window.location.href = '/doctor/medicine/yield/view';
}
