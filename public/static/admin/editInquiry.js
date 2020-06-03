var inquiryTable;
$(function () {

    if($('#tbl_inquiry').length>0) {
        if (inquiryTable)
            inquiryTable.destroy();
        inquiryTable = $('#tbl_inquiry').DataTable({
            "processing": true,
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('id', 'inquiry_' + aData.id); // or whatever you choose to set as the id
            },
            "serverSide": true,
            "order": [[0, "desc"]],
            info: false,
            "pageLength": 10,
            "aLengthMenu": [10, 20, 50],
            "ajax": {
                "type": "POST",
                "url": '/doctor/inquiry/getList',
                "dataType": "json"
            },
            columns: [
                {data: 'treat_start'},
                {data: 'guahao'},
                {data: 'patient_name'},
                {data: 'phone_number'},
                {data: 'price'},
                {data: 'department_name'},
                {data: 'doctor_name'}
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
            },
            "aoColumnDefs": [
                {
                    "aTargets": [7],
                    'orderable': false,
                    "mRender": function (data, type, full) {
                        return '<button class="btn btn-sm btn-info m-l-5" onclick="location.href=\'/doctor/inquiry/edit/' + full.id + '\'"><i class="ti ti-pencil"></i>修改药房</button>' +
                            '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/history/detail/' + full.id + '\'"><i class="ti-pencil-alt"></i>详情</button>';
                    }

                },

                {"className": "text-center", "targets": "_all"}
            ],
            "drawCallback": function (settings) {
                if (settings.aoData.length)
                    $("#tbl_guahao tbody tr:first-child").trigger('click');
            }
        });
    }

    $(".question-area").height($(".question-area").width()*0.751);
    if(typeof biaozhengList !== 'undefined') {
        drawItems('biaozheng', biaozhengList, selBiaozheng);
        drawItems('lizheng', lizhengList, selLizheng);
        drawItems('biaoli', biaoliList, selBiaoli);
        drawItems('maizheng', maizhengList, selmaizheng);
    }
    if (typeof recipes !== 'undefined') {
        $("#medicines").val(recipes);
        drawMedicine(JSON.parse(recipes),true,true,false);
        calcPriceTotal();
    }
    searchRecipes(function(){
        var drecipes = JSON.parse(recipes);
        var selVals = new Array();
        for(var i=0; i < drecipes.length;i++){
            if(drecipes[i].id!='hefang'){
                selVals.push(drecipes[i].id);
            }
        }
        $('#recipe').val(selVals); // Select the option with a value of '1'
        $("#recipe").trigger('change');
        $("#medicines").val(recipes);

    });

    if($("#recipe").length)
        $("#recipe").prepend("<option></option>").select2({
            placeholder:"请选择"
        });

    $("#recipe").on("change",function (e) {
        var recipes = $(this).val();
        drawRecipeSections(recipes,true);
    });

    $('#question-form').submit(function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (typeof e.originalEvent === 'undefined' || e.isTrigger) {
            console.log('Prevent duplicate events');
            return false;
        }

        var form = $(this);
        if(!form.parsley({'excluded': ':disabled'}).validate()){
            return ;
        }

        url = '/doctor/inquiry/updateRecipe';
        showOverlay();
        var forms = new FormData($(this)[0]);

        var sel_houfang_length = 0;
        $("input[name='houfang[]']").each(function(index, obj){
            if ($(obj).val() == "1")
                sel_houfang_length ++;
        });
        if (sel_houfang_length > 3) {
            Swal.fire({
                type: 'warning',
                title: '是否合方不得超过3个。',
                showConfirmButton: false,
                timer: 3000
            });
            $("#recipe").val('').change();
            hideOverlay();
            return;
        }

        $.ajax({
            url: url,
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
                        title: '成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
                        window.location.href = "/doctor/inquiry/editView"
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

});
