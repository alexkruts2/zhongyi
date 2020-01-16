var category_table, article_table;
var selected_row;

function showCategoryModal(bAdd, category_id, obj) {
    if (bAdd) {
        $('#category_name').val('');
        $('#category_id').val('');
    } else {
        selected_row = category_table.row($(obj).parents('tr'));
        $('#category_name').val(selected_row.data()[0]);
        $('#category_id').val(category_id);
    }

    $('#category-modal').modal();
}

function deleteCategory(id, obj) {
    Swal.fire({
        title: "你确定要删除该分类吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    })
        .then(result => {
            if (result.value) {
    
                $.ajax({
                    url: '/admin/article/category/delete',
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
                                title: '成功',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            category_table.row($(obj).parents('tr'))
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

function changeCountry() {
    $.ajax({
        url:"/admin/base/country/states",
        data:{
            country_id: $('#country_id').val()
        },
        type:"GET",
        success:function(response){
            var states = response.data;
            $('#state_id')
                .find('option')
                .remove()
                .end()
                .append('<option value="0">--请选择州/省/地区--</option>')
                .val('0');

            for( var i = 0 ; i < states.length ; i++ ){
                $("#state_id").append(new Option(states[i].name_cn, states[i].id));
            }
        }
    });
}

function changeState() {
    $.ajax({
        url:"/admin/base/state/cities",
        data:{
            country_id: $('#country_id').val(),
            state_id: $('#state_id').val()
        },
        type:"GET",
        success:function(response){
            $('#city_id')
                .find('option')
                .remove()
                .end()
                .append('<option value="0">--请选择城市--</option>')
                .val('0');

            var cities = response.data;
            for(var i = 0 ; i < cities.length;i++){
                $("#city_id").append(new Option(cities[i].name_cn, cities[i].id));
            }
        }
    })
}

function deleteArticle(hash, obj) {
    Swal.fire({
        title: "你确定要删除该文章吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    })
        .then(result => {
            if (result.value) {
                $.ajax({
                    url: '/admin/article/delete',
                    data: "hash=" + hash,
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
                            article_table.row($(obj).parents('tr'))
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

function showPreviewModal() {

    var content = $('#content').summernote('code');//CKEDITOR.instances.content.getData();
    var header = '<div style="padding-bottom: 20px;"><h2>' + $('#name').val() + '</h2>\n' +
        '<span style="color: #6c757d">' + $('#author').val() + ' </span><span style="color: #1083b9">' + $("#copyright_type option:selected").text() + '</span></div>';

    $('.article-preview').html(header + content);

    $('#preview-modal').modal();
}

function showImportModal() {
    $('#import_url').val('');
    $('#import-modal').modal();
}

function publish() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/article/publish',
        data: 'hash=' + hash,
        cache: false,
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    text: '',
                    title: '设置成功',
                    showConfirmButton: false,
                    timer: 3000
                });
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

function setTop() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/article/top',
        data: 'hash=' + hash,
        cache: false,
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    text: '',
                    title: '设置成功',
                    showConfirmButton: false,
                    timer: 3000
                });
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
                    timer: 1500
                });
        }
    })
}

function setWhiteLabel() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/article/whitelabel',
        data: 'hash=' + hash,
        cache: false,
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    text: '',
                    title: '设置成功',
                    showConfirmButton: false,
                    timer: 1500
                });
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

function saveArticle() {
    var form = new FormData($('#article-form')[0]);
    // form.append('content_real', $('#content').summernote('code'));

    showOverlay();
    if ($('#hash').val() == '') {
        $.ajax({
            url: '/admin/article/create',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == '0') {
                    window.location.href = '/admin/article/' + resp.data.hash;
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
            url: '/admin/article/update',
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

function changeCopyrightType() {
    if ($('#copyright_type').val() == '2') {
        $('#official').collapse('show');
    } else {
        $('#official').collapse('hide');
    }
}

function initArticleTable() {
    article_table = $('#tblarticle').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'article_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "ajax":{
            "type":"GET",
            "url": '/admin/article/get?category=' + $('#category').val() + '&country=' + $('#country').val() + '&copyright=' + $('#copyright_type').val() + '&from=' + $('#from').val() + '&to=' + $('#to').val(),
            "dataType":"json"
        },
        columns: [
            {data: 'created_at'},
            {data: 'published_at'},
            {data: 'name'},
            {data: 'category'},
            {data: 'country'},
            {data: 'author'},
            {data: 'copyright_type'},
            {data: 'is_published'},
            {data: 'view_counts'},
            {data: 'share_counts'},
            {data: 'hash'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[2],
                "mRender":function(data,type,full){
                    return '<a href="/admin/article/' + full.hash + '"><h6>' + data + '</h6><small>' + full.hash + '</small></a>';
                }
            },
            {
                "aTargets":[6],
                "mRender":function(data,type,full) {
                    if (data == 1)
                        return '网络采集';
                    else if (data == 2)
                        return '微信采集';
                    else if (data == 3)
                        return '原创';
                    else if (data == 4)
                        return '授权发布';
                    else
                        return '未知';
                }
            },
            {
                "aTargets":[7],
                "mRender":function(data,type,full) {
                    var html = '';
                    if (data === 1)
                        html = '<span class="label label-success m-r-5">已发布</span>';
                    if (full.is_top === 1)
                        html += '<span class="label label-danger m-r-5">置顶</span>';
                    if (full.is_whitelabelled === 1)
                        html += '<span class="label label-warning">品牌信息声明</span>';
                    return html;
                }
            },
            {
                "aTargets":[10],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-danger" onclick="deleteArticle(\'' + data+ '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
    });
}

function searchArticle() {
    article_table.clear();
    article_table.destroy();
    initArticleTable();
}

$(function () {
    if ($('#copyright_type').val() == '2') {
        $('#official').collapse('show');
    }

    category_table = $('#tblcategory').DataTable({
        "columnDefs": [
            { "orderable": false, 'targets': [2] },
            { "className": 'text-center', 'targets': '_all'}
        ],
        "order": [[ 0, "desc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    $('#category-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        var form = new FormData($(this)[0]);

        $.ajax({
            url: '/admin/article/category/addOrUpdate',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    $('#category-modal').modal('hide');

                    if ($('#category_id').val() !== ''){
                        var data = selected_row.data();
                        data[0] = resp.data.name;
                        selected_row.data(data).draw();
                    }
                    else {
                        category_table.row.add([resp.data.name, '', '<button class="btn btn-sm btn-info" onclick="showCategoryModal(false, ' + resp.data.id + ', this)"><i class="ti-pencil-alt"></i></button>\n' +
                        '<button class="btn btn-sm btn-danger" onclick="deleteCategory(' + resp.data.id + ', this)"><i class="ti-trash"></i></button>']).draw();
                    }

                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '保存成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    hideOverlay();
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
    })

    initArticleTable();

    $('#import-form').submit(function (e) {
        e.preventDefault();
        showOverlay();
        $.ajax({
            url: '/admin/article/import?url=' + $('#import_url').val(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'GET',
            success: function (resp) {
                hideOverlay();
                if (resp.code == '0') {
                    $('#import-modal').modal('hide');

                    $('#head_pic').attr('data-default-file', resp.data.image);
                    $('#name').val(resp.data.name);
                    $('#lite_des').val(resp.data.lite_des);
                    $('#author').val(resp.data.author);
                    $('#source_url').val(resp.data.source);
                    $('#content').summernote('code', resp.data.html);
                    $('#copyright_type').val('2');
                    // window.location.href = '/admin/article/' + resp.data.id;
                } else {
                    hideOverlay();
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
    });
});
