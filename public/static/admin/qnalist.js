var qna_table,tag_table;
var selected_row;
var selected_obj;

function deleteQnaList(id, obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
                url: '/admin/qnalist/delete',
                data: "id=" + id,
                cache: false,
                dataType: 'json',
                processData: false,
                type: 'POST',
                success: function (resp) {
                    if (resp.code == '0') {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        qna_table.row($(obj).parents('tr'))
                            .remove()
                            .draw();
                    } else {
                        Swal.fire({
                            position: 'top-end',
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
                        position: 'top-end',
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

/*...........标签管理.............*/
function addTag() {
    if($("#meta_name").val() == "" || $("#meta_value").val() == "" || $("#source").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });
        return;
    }

    if($("#isUpdate").val() == 1) {
        tag_table.row($(selected_obj).parents('tr'))
            .remove()
            .draw();
    }

    tag_table.row.add([$("#meta_name").val(),$("#meta_value").val(),$("#source").val(),'<button type="button" class="btn btn-sm btn-cyan" onclick="updateTag(this, ' + "'" + $("#meta_name").val() + "','" + $("#meta_value").val()+  "','" + $("#source").val() + "'" + ')"><i class="ti-pencil"></i> </button>  <button type="button" class="btn btn-sm btn-danger" onclick="deleteTag(this, \'' + $("#meta_name").val() + '\')"><i class="ti-trash"></i> </button>']).draw();

    var data = tag_table.rows().data();
    var tagData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.meta_name = data[i][0];
        myObject.meta_value = data[i][1];
        myObject.source = data[i][2];
        tagData.push(myObject);
    }
    var jsonString = JSON.stringify(tagData);
    $("#MetadataDTO").val(jsonString);

    $('#tag-modal').modal('hide');

}

function createTag() {
    $('#meta_name').val('');
    $('#meta_value').val('');
    $('#source').val('Custom Editorial');
    $('#isUpdate').val(0);
    selected_obj = null;
    $('#tag-modal').modal();
}

function updateTag(obj,name, value,source) {
    $('#meta_name').val(name);
    $('#meta_value').val(value);
    $('#source').val(source);
    $('#isUpdate').val(1);
    $('#tag-modal').modal();
    selected_obj = obj;
}

function deleteTag(obj,name) {
    tag_table.row($(obj).parents('tr'))
        .remove()
        .draw();

    var data = tag_table.rows().data();
    var tagData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.meta_name = data[i][0];
        myObject.meta_value = data[i][1];
        myObject.source = data[i][2];
        tagData.push(myObject);
    }
    var jsonString = JSON.stringify(tagData);
    $("#MetadataDTO").val(jsonString);
}

/*........问题列表管理.............*/
function addQuery() {
    if($("#query_content").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入问题内容'
        });
        return;
    }

    var is_primary = 0;
    if($("#query_is_primary").parent().find('input').is(':checked')) {
        is_primary = 1;
    }

    if($("#query_is_update").val() == 0) {
        var primaryVal =  '';
        if(is_primary == 1) {
            primaryVal = '首要';
        }
        query_table.row.add([primaryVal,$('#query_content').val(),'<button type="button" class="btn btn-sm btn-success" onclick="changeQuery(' + "'" + is_primary + "','" + $('#query_content').val() + "'," + ' this)"><i class="ti-pencil-alt"></i><button type="button" class="btn btn-sm btn-danger m-l-5" onclick="deleteQuery(this)"><i class="ti-trash"></i> </button>']).draw();
    }
    else {
        var data = selected_row.data();
        if(is_primary == 0) {
            data[0] = '';
        }
        else {
            data[0] = '首要';
        }

        data[1] = $("#query_content").val();
        selected_row.data(data).draw();
    }

    var data = query_table.rows().data();
    var queryData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        if(data[i][0] == "")
            myObject.is_primary = 0;
        else
            myObject.is_primary = 1;
        myObject.content = data[i][1];
        queryData.push(myObject);
    }
    var jsonString = JSON.stringify(queryData);
    $("#questions").val(jsonString);

    $('#query-modal').modal('hide');

}

function changeQuery(is_primary, content, obj){
    $('#query_is_update').val(1);
    $('#query_is_primary').attr('checked', true);
    $('#query_content').val(content);
    selected_row = query_table.row($(obj).parents('tr'));
    $('#query-modal').modal();
}

function createQuery() {
    if ($('#id').val() == '' && query_table.data().count() == 0) {
        $('.switchery').trigger('click');
    }

    $('#query_is_update').val(0);
    $('#query_content').val('');
    $('#query-modal').modal();
}

function deleteQuery(obj,name) {
    query_table.row($(obj).parents('tr'))
        .remove()
        .draw();

    var data = query_table.rows().data();
    var queryData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        if(data[i][0] == "")
            myObject.is_primary = 0;
        else
            myObject.is_primary = 1;
        myObject.content = data[i][1];
        queryData.push(myObject);
    }
    var jsonString = JSON.stringify(queryData);
    $("#questions").val(jsonString);
}


$(function () {
    qna_table = $('#tbl_qnalist').DataTable({
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
            "url": '/admin/qnalist/get',
            "dataType":"json"
        },
        columns: [
            {data: 'created_at'},
            // {data: 'name'},
            {data: 'faq_hash'},
            // {data: 'isContextOnly'},
            // {data: 'PromptDTO'},
            {data: 'MetadataDTO'},
            {data: 'questions'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[1],
                "mRender":function(data,type,full){
                    return  '<h6>' + full.faq_name + '</h6><small class="clr-lightgray">' + data + '</small>';
                }
            },
            // {
            //     "aTargets":[2],
            //     "mRender":function(data,type,full){
            //         if(data == 0)
            //             return '否';
            //         else
            //             return '是';
            //     }
            // },
            {
                "aTargets":[2],
                "mRender":function(data,type,full) {
                    var data_array = JSON.parse(data);
                    var html = "";
                    if(data_array != null)
                    {
                        for(var i = 0; i < data_array.length; i++)
                        {
                            if(i > 5)
                            {
                                html += "...<br>";
                                break;
                            }
                            html += data_array[i].meta_name + " : " + data_array[i].meta_value + "<br>";
                        }
                    }

                    return html;
                }
            },
            {
                "aTargets":[3],
                "mRender":function(data,type,full) {
                    var question_array = JSON.parse(data);
                    var html = "";
                    for(var i = 0; i < question_array.length; i++)
                    {
                        if(i > 5)
                        {
                            html += "...<br>";
                            break;
                        }
                        html += question_array[i].content + "<br>";
                    }
                    return html;
                }
            },
            {
                "aTargets":[4],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-success" onclick="window.location=' + "'/admin/qnalist/" + data + "'" + '"><i class="ti-pencil-alt"></i></button><button class="btn btn-sm btn-danger m-l-5" onclick="deleteQnaList(\'' + data+ '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    /*.........标签.........*/
    tag_table = $('#tbl_tags').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "searching":   false,
        "paging":   false,
        "ordering": false,
        "info":     false
    });

    if($("#MetadataDTO").val() != "" && typeof $("#MetadataDTO").val() !== 'undefined' )
    {
        var array = JSON.parse($("#MetadataDTO").val());
        for(var i = 0; i < array.length; i++) {
            tag_table.row.add([array[i].meta_name,array[i].meta_value,array[i].source,'<button type="button" class="btn btn-sm btn-cyan" onclick="updateTag(this, ' + "'" + array[i].meta_name + "','" + array[i].meta_value +  "','" + array[i].source + "'" + ')"><i class="ti-pencil"></i> </button> <button type="button" class="btn btn-sm btn-danger" onclick="deleteTag(this, \'' + array[i].meta_name + '\')"><i class="ti-trash"></i> </button>']).draw();
        }
    }

    /*..........问题列表..........*/
    query_table = $('#tbl_questions').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        "searching":   false,
        "paging":   false,
        "ordering": false,
        "info":     false
    });

    if($("#questions").val() != "" && typeof $("#questions").val() !== "undefined")
    {
        var array = JSON.parse($("#questions").val());
        for(var i = 0; i < array.length; i++) {
            var isPrimary = ""
            if(array[i].is_primary == 1) {
                isPrimary = "首要"
            }
            query_table.row.add([isPrimary,array[i].content,'<button type="button" class="btn btn-sm btn-success" onclick="changeQuery(' + "'" + array[i].is_primary + "','" + array[i].content + "'," + ' this)"><i class="ti-pencil-alt"></i> </button><button type="button" class="btn btn-sm btn-danger m-l-5" onclick="deleteQuery(this)"><i class="ti-trash"></i> </button>']).draw();
        }
    }

    $('#qnaList-form').submit(function (e) {
        e.preventDefault();
        var form = new FormData($(this)[0]);
        // form.append('content_real', $('#content').summernote('code'));

        if(query_table.rows().data().length == 0) {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请添加问题'
            });
        }
        else if($("#answer").val() == "") {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请填写回答'
            });
        }
        else {
            showOverlay();
            if ($('#id').val() == '') {
                $.ajax({
                    url: '/admin/qnalist/create',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (resp) {
                        hideOverlay();
                        if (resp.code == '0') {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                text: '',
                                title: '保存成功',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            window.location.href = '/admin/qnalist';
                        } else {
                            Swal.fire({
                                position: 'top-end',
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
                            timer: 3000
                        });
                    }
                })
            } else {
                $.ajax({
                    url: '/admin/qnalist/update',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (resp) {
                        hideOverlay();
                        if (resp.code == '0') {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                text: '',
                                title: '更新成功',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            Swal.fire({
                                position: 'top-end',
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
                            timer: 3000
                        });
                    }
                })
            }
        }
    })

    // $(window).keydown(function(event){
    //     if(event.keyCode == 13) {
    //         event.preventDefault();
    //         return false;
    //     }
    // });
});
