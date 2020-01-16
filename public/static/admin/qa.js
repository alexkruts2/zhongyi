var questionNumber = 1 , answerNumber = 0;
var answerItemNumber = 0;
var queries = [];
var qa_table;

function openQAModal() {
    // Swal.fire({
    //         title: "1个输入框还是2个输入框？",
    //         type: "info",
    //         showCancelButton: true,
    //         confirmButtonClass: "btn-danger",
    //         confirmButtonText: "1个",
    //         cancelButtonText: "2个",
    //         closeOnConfirm: false,
    //         closeOnCancel: false
    //     }).then(result => {
    //         $("#question").val('');
    //         if (result.value) {
    //             answerItemNumber = 1;
    //             answerNumber = 0;
    //             $("#answerItemSection").html('');
    //             $("#QAModal").modal('show');
    //             $("#btnAnswer2").hide();
    //             $("#btnAnswer1").show();
    //         }else if(result.dismiss=='cancel'){
    //             $("#answerItemSection").html('');
    //             $("#QAModal").modal('show');
    //             $("#btnAnswer1").hide();
    //             $("#btnAnswer2").show();
    //             answerItemNumber = 2;
    //             answerNumber = 0;
    //         }
    //     });
    $("#question").val('');
    answerItemNumber = 1;
    answerNumber = 0;
    $("#answerItemSection").html('');
    $("#QAModal").modal('show');
    $("#btnAnswer2").hide();
    $("#btnAnswer1").show();

}
function appendAnswerItem1() {
    answerNumber++;
    var html = '<div class="form-group mt-3 row" >\n' +
        '\t<label for="example-text-input" class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="'+answerNumber+'" onclick="removeAnswer1(this);"><i class="fas fa-times"></i> </button></label>\n' +
        '\t<div class="col-10">\n' +
        '\t\t<input class="form-control" type="text" value=""  id="answer_'+ questionNumber+'_'+answerNumber+'">\n' +
        '\t</div>\n' +
        '</div>\n';
    $("#answerItemSection").append(html);
}
function removeAnswer1(obj) {
    answerNumber--;
    var selectNumber = $(obj).data("index");
    var row = $(obj).parent().parent();
    row.remove();
    $(".tooltip").fadeOut();
    for(var i=selectNumber+1; i< answerNumber+2; i++){
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","answer_"+questionNumber+'_'+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }
}

function appendAnswerItem2() {
    answerNumber++;
    html = '<div class="form-group mt-3 row">\n' +
        '\t<label for="example-text-input" class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="'+answerNumber+'" onclick="removeAnswer2(this);"><i class="fas fa-times"></i> </button></label>\n' +
        '\t<div class="col-7">\n' +
        '\t\t<input class="form-control" type="text" value="" id="answer_'+ questionNumber+'_'+answerNumber+'">\n' +
        '\t</div>\n' +
        '\t<div class="col-3">\n' +
        '\t\t<input class="form-control" type="number" value="" id="weight_'+ questionNumber+'_'+answerNumber+'" min="0" max="1" step="0.01">\n' +
        '\t</div>\n' +
        '</div>\n';
    $("#answerItemSection").append(html);
}

function removeAnswer2(obj) {
    answerNumber--;
    var selectNumber = $(obj).data("index");

    var row = $(obj).parent().parent();
    row.remove();
    for(var i=selectNumber+1; i< answerNumber+2; i++){
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","answer_"+questionNumber+'_'+(i-1));
        $('*[data-index="'+i+'"]').parent().next().next().first().children().attr("id","weight_"+questionNumber+'_'+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }
}
function addQueryItem() {
    if(answerNumber<1){
        Swal.fire({
            type: 'info',
            title: '请输入至少一个答案项目。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    var html = '';
    var question = $("#question").val();
    if(question==''||question==null||question==undefined){
        Swal.fire({
            type: 'info',
            title: '请输入质问。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    if(answerItemNumber == 1){
        var answers = [];
        for(var i = 1; i < answerNumber+1; i++){
            var answerItem = $("#answer_"+questionNumber+"_"+i).val();
            if(answerItem==''||answerItem==null||answerItem==undefined){
                Swal.fire({
                    type: 'info',
                    title: '答案不能为空。',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }
            answers.push(answerItem);
        }
        html +='</div>\n' +
            '</div>';
        // $("#answerSection").append(html);
    }else if(answerItemNumber==2){
        var answers = [];
        for(var i = 1; i < answerNumber+1; i++){
            var answerItem = $("#answer_"+questionNumber+"_"+i).val();
            var weight = $("#weight_"+questionNumber+"_"+i).val();
            answers.push({answerItem:answerItem,weight:weight});
            html +='<p class="m-0-0">' + answerItem+'('+weight+')</p>';
        }
        html +='</div>\n' +
            '</div>';
        $("#answerSection").append(html);
    }
    var queryItem = {
        answerItemNumber:answerItemNumber,
        question:question,
        answers:answers
    };
    queries.push(queryItem);
    questionNumber++;
    $("#QAModal").modal('hide');
    drawAnswerSection(queries);
}

function removeQuestion(obj) {
    questionNumber--;
    var selectNumber = $(obj).data("index");

    queries.splice(selectNumber-1, 1);
    drawAnswerSection(queries);
}
function drawAnswerSection(queries) {
    html = '';
    for(var i = 0 ; i < queries.length; i++){
        if(queries[i].answerItemNumber==1)
            html +='<div class="form-group mt-3 row">\n' +
                '\t<label class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="'+(i*1+1)+'" onclick="removeQuestion(this);"><i class="fas fa-times"></i> </button></label>\n' +
                '\t<div class="col-8 border-light bg-light border">\n' +
                '\t\t<h5 class="font-weight-bold">';
        html+=(i*1+1)+'.'+queries[i].question+'</h5>\n';
        for(var j=0; j<queries[i].answers.length;j++){
            html +='<p class="m-0-0">' + queries[i].answers[j]+'</p>';
        }
        html +='</div>\n' +
            '</div>';
    }
    $("#answerSection").html(html);
}

$(function(){

    $(".select2").select2({
        placeholder:"请选择药房"
    });

    $('#question-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        $("#questions").val(JSON.stringify(queries));
        var forms = new FormData($(this)[0]);
        var questionId = $("#question_id").val();
        if(questionId==''||questionId==null||questionId==undefined){
            url = '/doctor/qa/create';
        }
        else{
            url = '/doctor/qa/editQA'
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
                    // window.location.href = '/admin/qa/view';
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

    qa_table = $('#tbl_qa').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'qa_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/qa/get',
            "dataType":"json"
        },
        columns: [
            {data: 'number'},
            {data: 'title'},
            {data: 'department',orderable:false},
            {data: 'created_at'},
            {data:'doctor_name'},
            {data:'id'}
            ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[5],
                'orderable':false,
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/qa/edit/'+data+'\'"><i class="ti-pencil-alt"></i>修改</button>'+
                        '<button class="btn btn-sm btn-danger m-l-5" onclick="deleteQA(\'' + data+ '\', this)"><i class="ti-trash"></i>删除</button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });


    $(document).keypress(
        function(event){
            if (event.which == '13') {
                event.preventDefault();
            }
        });

});


function deleteQA(id, obj) {
    Swal.fire({
        title: "你确定要删除该问答吗?",
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
            $.ajax({
                url: '/doctor/qa/delete',
                data: "id=" + id,
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'GET',
                success: function (resp) {
                    if (resp.code == '0') {
                        Swal.fire({
                            type: 'success',
                            text: '',
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        qa_table.row($(obj).parents('tr'))
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
