var department_table;
var selected_row;
function editDepartment(id, obj) {
    var name = $(obj).data('name');
    $("#department_name_edit").val(name);
    $("#editModal").modal('show');
    $("#saveBtn").attr("data-id",id);
    $("#saveBtn").data("id",id);
}
function saveDepartment(obj) {
    var id = $(obj).data('id');
    var name = $("#department_name_edit").val();
    showOverlay();
    $.ajax({
        url: '/admin/doctor/department/edit',
        data: "id=" + id+'&name='+name,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '修改成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#immi_"+id+' td').first().html(name);
                $("#immi_"+id+' td:nth-child(2) button').data('name',name);
                $('#editModal').modal('hide');

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

function createDepartment(){
    var department_name = $("#department_name").val();
    if(department_name==''||department_name==undefined||department_name==null){
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入科室名'
        });
        return;
    }
    showOverlay();
    $.ajax({
        url: '/admin/doctor/department/create',
        data: "name=" + department_name,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '保存成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                var new_id = resp.data.id;
                department_table.row.add({
                    id: new_id,
                    name: department_name
                }).draw();
                $('#myModal').modal('hide');

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
$(function () {
    department_table = $('#tbl_department').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"GET",
            "url": '/admin/doctor/department/get',
            "dataType":"json"
        },
        columns: [
            {data: 'name'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[1],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success m-l-5" data-id="'+full.id+'" data-name="'+full.name+'" onclick="editDepartment(\'' + data+ '\', this)"><i class="ti-pencil"></i>修改</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

});
