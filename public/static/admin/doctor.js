var doctor_table;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function checkTime(from,to){
    var froms = from.split(':');
    var tos = to.split(':');
    var currentD = new Date();
    var startHappyHourD = new Date();
    startHappyHourD.setHours(froms[0],froms[1],0); // 5.30 pm
    var endHappyHourD = new Date();
    endHappyHourD.setHours(tos[0],tos[1],0); // 6.30 pm
    if(currentD >= startHappyHourD && currentD < endHappyHourD ){
        return 1;
    }else{
        return 0;
    }
}
function deleteDoctor(id, obj) {
    Swal.fire({
        title: "你确定要删除该医生吗?",
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
                url: '/admin/doctor/delete',
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
                        doctor_table.row($(obj).parents('tr'))
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
function viewDoctor(id,obj) {
    window.location.href = '/admin/doctor/detail/'+id;
}

function editDoctor(id,obj){
    window.location.href = '/admin/doctor/edit/'+id;
}
$(function () {

    initSlider();
    if($('#from').length>0){
        $('#from').clockpicker({
            donetext: '完成',
            autoclose: true
        });
        $('#to').clockpicker({
            donetext: '完成',
            autoclose: true
        });
        $('.clockpicker').click(function(e){
            e.stopPropagation();
        })
        console.log("doctor form called");
    }

    doctor_table = $('#tbl_doctor').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/admin/doctor/get',
            "dataType":"json"
        },
        columns: [
            {data: 'name'},
            {data: 'visiting_place'},
            '',
            '',
            {data: 'department_name',orderable:false},
            {data: 'hospital_name'},
            {data:'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[2],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return full.from+" ~ "+full.to;
                }
            },
            {
                "aTargets":[3],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return checkTime(full.from,full.to)?'<span class="text-success">有号</span>':'<span class="text-danger">无号</span>';
                }
            },
            {
                "aTargets":[6],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-primary m-l-5" onclick="viewDoctor(\'' + data+ '\', this)"><i class="ti-eye"></i>查看</button>'+
                     '<button class="btn btn-sm btn-success m-l-5" onclick="editDoctor(\'' + data+ '\', this)"><i class="ti-pencil-alt"></i>修改</button>'+
                        '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteDoctor(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback":function(settings){
            var department = $("#department_select");
            if(department.length<1){
                showOverlay();
                $.ajax({
                    url:'/admin/doctor/getAllDepartment',
                    type:'GET',
                    success:function(data){
                        hideOverlay();
                        var departments = data.data;
                        var html = "<label class=\"text-right dataTables_filter\">\n" +
                            "    科室&nbsp;\n" +
                            "    <select id='department_select' style='min-height: 30px;border-radius: 0.25rem'><option value=''>全部</option> \n";
                        for(var i=0; i < departments.length;i++){
                            html+="<option value='"+departments[i].id+"'>"+departments[i].name+"</option>";
                        }
                        html+= "    </select>\n" +
                            "    &nbsp;\n" +
                            "    </label>";
                        $("#tbl_doctor_filter").after(html);
                        $("#department_select").on("change",function(){
                            doctor_table.column(4).search(this.value).draw();
                        });
                    }
                })
            }
        }
    });

})
$('#doctor-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (typeof e.originalEvent === 'undefined' || e.isTrigger) {
        console.log('Prevent duplicate events');
        return false;
    }

    var form = $(this);
    if(!form.parsley().validate()){
        return ;
    }
    showOverlay();
    var forms = new FormData($(this)[0]);
    // $(this).find(':submit').attr('disabled','disabled');
    $.ajax({
        url: '/admin/doctor/create',
        data: forms,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                hideOverlay();
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '添加成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                window.location.href = '/admin/doctor/view';
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

function initSlider(){
    var slider = document.getElementById("myRange");
    if(slider!=null && slider!=undefined && slider!=''){
        var doctor_value = document.getElementById("doctor_value");
        var hospital_value = document.getElementById("hospital_value");
        doctor_value.innerHTML = slider.value;
        hospital_value.innerHTML = 100 - slider.value;
        $("#doctor_ratio").val(slider.value);

        slider.oninput = function() {
            doctor_value.innerHTML = this.value;
            hospital_value.innerHTML = 100 - this.value;
            $("#doctor_ratio").val(this.value);
        }
    }

}
