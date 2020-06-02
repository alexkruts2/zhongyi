var video;
var webcamStream;
var snapshotel;
var imgFile = '';
var guhaoTable;
var treatment;
var printFlag = false;
$(function () {
    // $("#myModal").modal('show');
    $('#myModal').modal({backdrop: 'static', keyboard: false});

    $("#main-wrapper").focus(function(){
       window.location.href = "/doctor/accept/guahao/view";
    });

    $("#doctor_id").on("change",function(){
        var doctor_id = $(this).val();
        var selected = $(this).find('option:selected');
        var from = selected.data('from');
        var to = selected.data('to');
        $("#from").val(from);
        $("#to").val(to);
    });




    startWebcam();

    guhaoTable = $('#tbl_guahao').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'guahao_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/accept/guahao/list',
            "dataType":"json"
        },
        columns: [
            {data: 'treat_start'},
            {data: 'guahao'},
            {data: 'patient_name'},
            {data: 'patient_phone'},
            {data: 'doctor_department'},
            {data:'doctor_name'},
            {data:'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[0],
                'orderable':true,
                "mRender":function(data,type,full) {
                    if(data=='0000-00-00 00:00:00')
                        return '';
                    else
                        return data;
                }
            },
            {
                "aTargets":[2],
                'orderable':false
            },
            {
                "aTargets":[3],
                'orderable':false
            },
            {
                "aTargets":[4],
                'orderable':false
            },
            {
                "aTargets":[5],
                'orderable':false
            },
            {
                "aTargets":[6],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-primary m-l-5" onclick="viewTreatment(\'' + data+ '\', this)"><i class="ti-eye"></i>查看</button>'+
                        '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/accept/guahao/edit/'+data+'\'"><i class="ti-pencil"></i>修改</button>'+
                        '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteTreatment(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    if($("#patient-edit-form").length)
        $("#do")
});
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
    if(!(IDNumber.length==18||IDNumber.length==21)){
        Swal.fire({
            type: 'warning',
            title: '身份证号码错误。长度必须为18或21。'
        });

        return;
    }
    $("#ID_number").val(IDNumber);
    $('#myModal').modal('hide');

    $("#patient-form").show();
}

function startWebcam(){
    canvas = document.getElementById("myCanvas");
    if(canvas){
        ctx = canvas.getContext('2d');

        if(navigator.getUserMedia){
            navigator.getUserMedia({
                video:true,
                audio:false
            },function(localMediaStream){
                video = document.querySelector('video');
                snapshotel = document.getElementById( "snapshot" );

                //video.src = window.URL.createObjectURL(localMediaStream);
                video.srcObject=localMediaStream;
                webcamStream = localMediaStream;
                video.play();
            },function(err){
                Swal.fire({
                    type: 'error',
                    text: err,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }
    }
}

function snapshot() {
    // Draws current image from the video element into the canvas
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    var img = new Image();
    img.src = canvas.toDataURL("image/jpeg",0.7);
    img.width = 600;
    img.name = 'photo';
    snapshotel.innerHTML = '';
    snapshotel.appendChild(img);
    showOverlay();
    $.ajax({
        url: '/doctor/accept/patient/uploadImage',
        type: "POST",
        data: {"image": img.src},
        success: function (response) {
            hideOverlay();
            if(response.code==0){
                imgFile = response.data;
                $("#photo").val(imgFile);
            }else {
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
                position: 'top-end',
                type: 'error',
                text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                title: '错误',
                showConfirmButton: false,
                timer: 1500
            })
        }
    });
}
function dataURItoBlob( dataURI ) {

    var byteString = atob( dataURI.split( ',' )[ 1 ] );
    var mimeString = dataURI.split( ',' )[ 0 ].split( ':' )[ 1 ].split( ';' )[ 0 ];

    var buffer	= new ArrayBuffer( byteString.length );
    var data	= new DataView( buffer );

    for( var i = 0; i < byteString.length; i++ ) {

        data.setUint8( i, byteString.charCodeAt( i ) );
    }

    return new Blob( [ buffer ], { type: mimeString } );
}

function print(data,callbackFunc) {
    someJSONdata = [
        {
            属性: '患者姓名',
            值: data.patient_name,
        },
        {
            属性: '患者电话号码',
            值: data.patient_phone,
        },
        {
            属性: '医生姓名',
            值: data.doctor.name,
        },
        {
            属性: '科室',
            值: data.doctor.department.name,
        },
        {
            属性: '挂号费',
            值: data.accept_fee,
        },{
            属性: '挂号时间',
            值: data.updated_at,
        }
    ]

    // JsBarcode("#barcode", guahao);
    printJS({
        printable: someJSONdata,
        properties: ['属性', '值'],
        header:null,
        type: 'json',
        onPrintDialogClose:callbackFunc
    });
}
function deleteTreatment(id, obj) {
    Swal.fire({
        title: "你确定要删除该挂号吗?",
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
                url: '/doctor/accept/guahao/delete',
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
                        guhaoTable.row($(obj).parents('tr'))
                            .remove()
                            .draw();
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
    });
}
function viewTreatment (id,obj) {
    showOverlay();
    $.ajax({
        url: '/doctor/accept/guahao/detail',
        data: "id=" + id,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success:function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                $("#guahaoModal").modal('show');
                $("#department").html(resp.data.department);
                $("#doctor_name").html(resp.data.doctor_name);
                $("#working_time").html(resp.data.work_from + " ~ " + resp.data.work_to);
                $("#patient_name").html(resp.data.name);
                $("#ID_Number").html(resp.data.ID_Number);
                $("#sex").html(resp.data.sex);
                $("#patient_phone").html(resp.data.patient_phone);
                $("#department").html(resp.data.department);
                $("#guahao").html(resp.data.guahao);
            } else {
                Swal.fire({
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }
    });
}
function payAccept() {
    var treatment_id = treatment.id;
    var state = "WAITING_TREATMENT";
    showOverlay();
    $.ajax({
        url: '/doctor/accept/treatment/update',
        data: "id="+treatment_id+"&state="+ state,
        type: 'get',
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                hideOverlay();
                print(resp.data,function(){
                    window.location.href='/doctor/accept/guahao/view';
                });                $("#payModal").modal('hide');
                printFlag = true;
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
    })
}
function cancelAccept() {
    $("#payModal").modal('hide');
    var treatment_id = treatment.id;
    showOverlay();
    $.ajax({
        url: '/doctor/accept/guahao/delete',
        data: "id=" + treatment_id,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {
                window.location.href = '/doctor/accept/guahao/view';
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


$('#patient-form').submit(function (e) {
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

    if(imgFile==''){
        Swal.fire({
            type: 'error',
            title: '请快照'
        });
        return;
    }
    if($("#from").val()==''||$("#from").val()==undefined||$("#from").val()==null){
        Swal.fire({
            type: 'error',
            title: '请选择医生'
        });
        return;
    }
    if($("#name").val()==''||$("#name").val()==undefined||$("#name").val()==null){
        Swal.fire({
            type: 'error',
            title: '请输入姓名。'
        });
        return;
    }
    if($("#phone_number").val()==''||$("#phone_number").val()==undefined||$("#phone_number").val()==null){
        Swal.fire({
            type: 'error',
            title: '请输入手机号码。'
        });
        return;
    }
    if($("#phone_number").val().length!=11){
        Swal.fire({
            type: 'error',
            title: '手机号码错误。长度必须为11。'
        });
        return;
    }

    showOverlay();
    var forms = new FormData($(this)[0]);

    $.ajax({
        url: '/doctor/accept/patient/create',
        data: forms,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                hideOverlay();
                treatment = resp.data;
                $("#payModal").modal({backdrop: 'static', keyboard: false});

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
$('#patient-edit-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (typeof e.originalEvent === 'undefined' || e.isTrigger) {
        console.log('Prevent duplicate events');
        return false;
    }

    if($("#from").val()==''||$("#from").val()==undefined||$("#from").val()==null){
        Swal.fire({
            type: 'error',
            title: '请选择医生'
        });
        return;
    }
    if($("#name").val()==''||$("#name").val()==undefined||$("#name").val()==null){
        Swal.fire({
            type: 'error',
            title: '请输入姓名。'
        });
        return;
    }
    if($("#phone_number").val()==''||$("#phone_number").val()==undefined||$("#phone_number").val()==null){
        Swal.fire({
            type: 'error',
            title: '请输入手机号码。'
        });
        return;
    }
    if($("#phone_number").val().length!=11){
        Swal.fire({
            type: 'error',
            title: '手机号码错误。长度必须为11。'
        });
        return;
    }

    showOverlay();
    var forms = new FormData($(this)[0]);

    $.ajax({
        url: '/doctor/accept/guahao/update',
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
                    type: 'info',
                    title: '成功'
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
