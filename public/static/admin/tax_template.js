
function showTaxModal(isAdd, output, tax_id) {
    $('#tax-modal').modal();
    $('#tax_output').val(output);
    // $('#can_edit').attr('checked', 'checked');

    if (!isAdd) {
        $('#tax_name').val($('#name-' + tax_id).html());

        if ($('#type-' + tax_id).html().includes('按比例'))
            $('#tax_type').val('按比例');
        else if ($('#type-' + tax_id).html().includes('固定值'))
            $('#tax_type').val('固定值');
        else
            $('#tax_type').val('特殊');

        $('#tax_value').val($('#value-' + tax_id).html());
        $('#tax_comment').val($('#comment-' + tax_id).html());
        $('#tax_id').val(tax_id);
        $('#tax_output').val(output);
        
        if ($('#canedit-' + tax_id).html().trim() == '可') {
            $('#editable').attr('checked', 'checked');
        } else {
            $('#editable').removeAttr('checked');
        }
    }
}

function getBindInfo(id) {
    $.ajax({
        url: '/admin/base/tax/bind/get?id=' + id,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            if (resp.code == '0') {
                $('#country_id').val(resp.data.country_id);
                setBindInfo($('#country_id').val(), resp.data.state_id, resp.data.proptype_id);
            } else {
                setBindInfo($('#country_id').val(), 0, 0);
            }
        }
    });
}

function setBindInfo(country_id, state_id, proptype_id) {
    $.ajax({
        url: '/admin/base/country/get?id=' + country_id,
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'GET',
        success: function (resp) {
            if (resp.code == 0) {
                $('#state_id')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="0">全部</option>');

                var len = resp.data.state.length;
                for (var i = 0 ; i < len ; i++) {
                    var item = resp.data.state[i];
                    $('#state_id').append('<option value="' + item.id + '">' + item.name + '</option>');
                }

                $('#state_id').val(state_id);

                $('#proptype_id')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="0">全部</option>');

                len = resp.data.proptype.length;
                for (i = 0 ; i < len ; i++) {
                    var item = resp.data.proptype[i];
                    $('#proptype_id').append('<option value="' + item.id + '">' + item.name + '</option>');
                }

                $('#proptype_id').val(proptype_id);

                $('.selectpicker').selectpicker('refresh');
            } else {
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '失败',
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
                title: '失败',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
};

function deleteTax(id) {
    Swal.fire({
        title: "你确定要删除选中的税费吗?",
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
                    url: '/admin/base/tax/delete',
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
                            $('#tax-' + id).remove();
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
                    }
                });
            }
        });
};

function updateSpecial(id) {
    showOverlay();
    $.ajax({
        url: '/admin/base/tax/special/update',
        data: 'special_id=' + id + '&name=' + $('#special_name_' + id).val() + '&rule=' + $('#special_rule_' + id).val(),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {

            } else {
                $.toast({
                    heading: '错误',
                    text: resp.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000
                });
            }
        }
    });
};

function deleteSpecial(id) {
    Swal.fire({
        title: "你确定要删除选中的特殊税费吗?",
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
                url: '/admin/base/tax/special/delete',
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
                        $('#special-' + id).remove();
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
                }
            });
        }
    });
};

$(function () {
    $('.selectpicker').selectpicker();

    getBindInfo($('#template_id').val());

    $('#country_id').change(function(){
        setBindInfo($('#country_id').val(), 0, 0);
    });


    $('#template-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        var form = new FormData($(this)[0]);

        $.ajax({
            url: '/admin/base/tax/template/update',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '保存成功',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // $.toast({
                    //     heading: '错误',
                    //     text: resp.message,
                    //     position: 'top-right',
                    //     loaderBg: '#ff6849',
                    //     icon: 'error',
                    //     hideAfter: 5000
                    // });
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        text: resp.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        });
    });

    $('#tax-form').submit(function (e) {
        e.preventDefault();

        showOverlay();
        var form = new FormData($(this)[0]);
        form.append('template_id', $('#template_id').val());

        if ($('#tax_id').val() == '') {
            $.ajax({
                url: '/admin/base/tax/add',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        var html = '<tr id="tax-' + resp.data.id + '">\n' +
                            '<td id="name-' + resp.data.id + '">' + resp.data.name + '</td>\n' +
                            '<td id="type-' + resp.data.id + '" class="text-center">\n';

                        if (resp.data.type == '按比例') {
                            html += '<span class="label label-success">';
                        } else if (resp.data.type == '固定值') {
                            html += '<span class="label label-info">';
                        } else {
                            html += '<span class="label label-warning">';
                        }

                        html += resp.data.type + '</span></td>';
                        html += '<td id="value-' + resp.data.id + '" class="text-center">' + resp.data.value + '</td>\n' +
                            '<td id="comment-' + resp.data.id + '" class="text-center">' + resp.data.comment + '</td>\n' +
                            '<td id="canedit-' + resp.data.id + '" class="text-center">';

                        if (resp.data.can_edit == true) {
                            html += '可</td>';
                        } else {
                            html += '否</td>'
                        }
                        html += '<td>\n' +
                            '<div class="text-center">\n' +
                            '<button class="btn btn-info" type="button" onclick="showTaxModal(false, ' + resp.data.output + ', ' + resp.data.id + ')"><i class="fa fa-edit"></i></button>\n' +
                            '<button class="btn btn-danger" type="button" onclick="deleteTax(' + resp.data.id + ', ' + resp.data.id + ')"><i class="fa fa-trash-o"></i></button>\n' +
                            '</div>\n' +
                            '</td>\n' +
                            '</tr>';

                        if (resp.data.output == '成交') {
                            $('#taxes1').append(html);
                        } else if (resp.data.output == '持有') {
                            $('#taxes2').append(html);
                        }
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '保存成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#tax-modal').modal('hide');
                    } else {
                        // $.toast({
                        //     heading: '错误',
                        //     text: resp.message,
                        //     position: 'top-right',
                        //     loaderBg: '#ff6849',
                        //     icon: 'error',
                        //     hideAfter: 5000
                        // });
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            text: resp.message,
                            title: '错误',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            });
        } else {

            $.ajax({
                url: '/admin/base/tax/update',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code === 0) {
                        $('#name-' + resp.data.id).html(resp.data.name);

                        if (resp.data.type === '按比例') {
                            $('#type-' + resp.data.id).html('<span class="label label-success">按比例</span>');
                        } else if (resp.data.type === '固定值') {
                            $('#type-' + resp.data.id).html('<span class="label label-info">固定值</span>');
                        } else {
                            $('#type-' + resp.data.id).html('<span class="label label-warning">特殊</span>');
                        }
                        $('#value-' + resp.data.id).html(resp.data.value);
                        $('#comment-' + resp.data.id).html(resp.data.comment);
                        if (resp.data.can_edit == true) {
                            $('#canedit-' + resp.data.id).html('可');
                        } else {
                            $('#canedit-' + resp.data.id).html('否');
                        }
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '保存成功',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#tax-modal').modal('hide');
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
                }
            });
        }
    });

    $('#tax-modal').on('hidden.bs.modal', function () {
        $('#tax_name').val('');
        $('#tax_value').val('');
        $('#tax_comment').val('');
        $('#tax_id').val('');
        $('#tax_output').val('');
    });

    $('#special-form').submit(function (e) {
        e.preventDefault();


        if($("#special_name_new").val() == "") {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请输入名称'
            });
            return;
        }

        if($('#special_rule_new').val() == ""){
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请输入规则'
            });
            return;
        }
        showOverlay();

        var form = new FormData($(this)[0]);
        form.append('template_id', $('#template_id').val());

        $.ajax({
            url: '/admin/base/tax/special/add',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code === 0) {
                    var html = '<div id="special-' + resp.data.id + '" class="col-4">\n' +
                        '<div class="form-group">\n' +
                        '<label> 名称：<span class="text-danger">*</span> </>\n' +
                        '<div class="controls">\n' +
                        '<input type="text" class="form-control" id="special_name_' + resp.data.id + '" name="name" required data-validation-required-message="必填" value="' + resp.data.name + '" placeholder="请输入名称。。。">\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class="form-group">\n' +
                        '<label> 规则：</label>\n' +
                        '<div class="controls">\n' +
                        '<textarea class="form-control" name="rule" id="special_rule_' + resp.data.id + '" rows="8" cols="60">' + resp.data.rule + '</textarea>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<button type="button" class="btn btn-info waves-effect waves-light" onclick="updateSpecial(' + resp.data.id + ')"><i class="fa fa-save"></i> 保存</button>\n' +
                        '<button type="button" class="btn btn-danger waves-effect waves-light" onclick="deleteSpecial(' + resp.data.id + ')"><i class="fa fa-trash"></i> 删除</button>\n' +
                        '</div>';

                    $('#special-new').before(html);

                    $('#special_name_new').val('');
                    $('#special_rule_new').val('');
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '保存成功',
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
            }
        });
    });
});