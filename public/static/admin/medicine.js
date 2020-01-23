var medicineTable;
function uploadMedicineExcel() {
    $("input[name='file_1']").trigger('click');
}
$("input[name='file_1']").change(function() {
    $("#excel-form").submit();
});
$(function(){

    drawTable();
    $('#medicine-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        var forms = new FormData($(this)[0]);

        $.ajax({
            url: '/doctor/medicine/edit',
            data: forms,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    Swal.fire({
                        type: 'success',
                        text: '',
                        title: '修改成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#myModal").modal('hide');
                    medicineTable.destroy();
                    drawTable();
                    hideOverlay();

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
    });
    $("#excel-form").submit(function(e){
        e.preventDefault();
        var file = $("input[name='file_1']").val();
        if(file==''||file==null||file==undefined)
            return false;
        showOverlay();
        var forms = new FormData($(this)[0]);
        $.ajax({
            url: '/doctor/medicine/uploadMedicine',
            data: forms,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $("#myModal").modal('hide');
                    medicineTable.destroy();
                    drawTable();
                    hideOverlay();
                    $("input[name='file_1']").val('');
                    Swal.fire({
                        type: 'success',
                        html: '总数:'+resp.data.total_number+',  插入数:'+resp.data.insert_number+',<br>更新数:'+resp.data.update_number+',  失败数:'+resp.data.fail_number,
                        title: '成功导入',
                    });

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
    });
    scrollSidebarTop();
});
function drawTable(){
    medicineTable = $('#tbl_medicine').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'medicine_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/medicine/get',
            "dataType":"json"
        },
        columns: [
            {data: 'name'},
            {data: 'usage'},
            {data: 'weight'},
            {data: 'price'},
            {data:'min_weight'},
            {data:'max_weight'},
            {data:'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[1],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return data.length>10?"<span title='"+data+"'>"+data.substring(0,10)+'... ...'+"</span>":data;
                }
            },
            {
                "aTargets":[6],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success m-l-5" data-medicine=\''+JSON.stringify(full)+'\' onclick="editMedicine(\'' + data+ '\', this)"><i class="ti-pencil-alt"></i>修改</button>'+
                        '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteMedicine(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });
}

function editMedicine(id,obj) {
    $("#myModal").modal();
    $("#medicine_id").val(id);
    var medicine = ($(obj).data('medicine'));
    $("#medicine_name").val(medicine.name);
    $("#usage").val(medicine.usage);
    $("#weight").val(medicine.weight);
    $("#price").val(medicine.price);
    $("#min_weight").val(medicine.min_weight);
    $("#max_weight").val(medicine.max_weight);
}
function deleteMedicine(id,obj) {
    Swal.fire({
        title: "你确定要删除该药材吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        closeOnConfirm: true,
        closeOnCancel: true
    }).then(result => {
        if (result.value) {
            showOverlay();
            $.ajax({
                url: '/doctor/medicine/delete',
                data: "id=" + id,
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
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        medicineTable.row($(obj).parents('tr'))
                            .remove()
                            .draw();
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
    });
}
