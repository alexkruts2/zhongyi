var authority_table;

$(function(){
    authority_table = $('#tbl_authority').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'doctor_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/admin/authority/getDoctors',
            "dataType":"json"
        },
        columns: [
            {data: 'department'},
            {data: 'name'},
            {data: 'authority'},
            {data:'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[0],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return data.name;
                }
            },

            {
                "aTargets":[3],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success m-l-5" data-name="'+full.name+'" data-authority=\''+full.authority+'\' onclick="editAuthority(\'' + data+ '\', this)"><i class="ti-pencil-alt"></i>修改权限</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]

    });

})
function editAuthority(id,obj) {
    var name = $(obj).data('name');
    var authority = $(obj).data('authority');
    $("#doctor_id").val(id);
    $("#doctor_name").val(name);
    // $("#authority").select2().select2('val',authority);
    $("#authority").val(authority);
    $("#authority").select2();
    // $("#authority").select2();
    $("#myModal").modal('show');
}
function updateAuthority() {
    var doctor_id = $("#doctor_id").val();
    var authority = JSON.stringify($("#authority").val());

    showOverlay();
    $.ajax({
        url: '/admin/authority/update',
        data: "id=" + doctor_id+'&authority='+authority,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            $("#myModal").modal('hide');
            if (resp.code == '0') {
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '修改成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#doctor_"+doctor_id+" td:nth-child(3)").html(authority);
                $("#doctor_"+doctor_id+" td:nth-child(4) button").attr("data-authority",JSON.parse(authority));
                $("#doctor_"+doctor_id+" td:nth-child(4) button").data("authority",JSON.parse(authority));

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
            $("#myModal").modal('hide');
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
