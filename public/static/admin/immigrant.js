var immi_table, process_table, doc_table, fee_table, adv_table, requirements_table,faq_table;
var selected_row;
var fileNameArray = Array();
var file_base_url;
var countries;
function deleteImmigrant(hash, obj) {
    Swal.fire({
        title: "你确定要删除该移民信息吗?",
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
                    url: '/admin/immi/delete',
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
                            })
                            immi_table.row($(obj).parents('tr'))
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
                            })
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
                        })
                    }
                });
            }
        });
}

function changeCountry() {
    if(typeof $("#country_id").val() !== "undefined") {
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

    if(typeof countries != 'undefined' && countries != null){
        for(var i = 0; i < countries.length; i++) {
            if(countries[i].id == $('#country_id').val()) {
                $(".symbol").html(countries[i].symbol);
            }
        }
    }
}

function getCountry(){
    $.ajax({
        url: '/admin/base/countryList',
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                countries = resp.data.country;
            } else {
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 1500
                })
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

function publish() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/immi/publish',
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
                    timer: 1500
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

function setRecommended() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/immi/recommended',
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
                    timer: 1500
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

function setHot() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/immi/hot',
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
                    timer: 1500
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

function createArticle() {
    $('#article_hash').val('');
    $('#article-modal').modal();
}

function addArticle() {
    if($("#article_hash").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入文章Hash'
        });
        return;
    }

    $.ajax({
        url: '/admin/immi/add_article',
        data: {
            article_hash : $("#article_hash").val(),
            hash : $("#hash").val()
        },
        type: 'POST',
        success: function (resp) {
            if (resp.code == '0') {
                article_table.row.add([resp.data.article_hash, '<a href="/admin/article/' + resp.data.article_hash + '">' + resp.data.article_name + '</a>', resp.data.article_category, '<button type="button" class="btn btn-sm btn-danger" onclick="deleteArticle(' + "'" + resp.data.article_hash + "'" + ',this)"><i class="ti-trash"></i></button>']).draw();
                $("#article-modal").modal('hide');
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    text: '',
                    title: '添加成功',
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
                })
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
            })
        }
    });
}

function deleteArticle(article_hash,obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
                    url: '/admin/immi/delete_article',
                    data: {
                        article_hash : article_hash,
                        hash : $("#hash").val()
                    },
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
                            })
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
                            })
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
                        })
                    }
                });
            }
        });
}

function addFee() {
    if($("#fee_title").val() == "" || $("#fee_amount").val() == "" || $("#fee_body").val() == "" || $("#fee_des").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });
        return;
    }

    fee_table.row.add([$("#fee_title").val(), $(".symbol").html() + formatNumber($("#fee_amount").val()),$("#fee_body").val(),$("#fee_des").val(),'<button type="button" class="btn btn-sm btn-danger" onclick="deleteFee(this)"><i class="ti-trash"></i> </button>']).draw();

    var data = fee_table.rows().data();
    var feeData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.fee_title = data[i][0];
        myObject.fee_amount = data[i][1];
        myObject.fee_body = data[i][2];
        myObject.fee_des = data[i][3];
        feeData.push(myObject);
    }
    var jsonString = JSON.stringify(feeData);
    $("#fee").val(jsonString);

    $('#fee-modal').modal('hide');
}

function createFee() {
    $('#fee_title').val('');
    $('#fee_amount').val('');
    $('#fee_body').val('');
    $('#fee_des').val('');
    $('#fee-modal').modal();
}

function deleteFee(obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
            fee_table.row($(obj).parents('tr'))
                .remove()
                .draw();

            var data = fee_table.rows().data();
            var feeData = Array();
            for (var i = 0; i < data.length; i++) {
                var myObject = new Object();
                myObject.fee_title = data[i][0];
                myObject.fee_amount = data[i][1];
                myObject.fee_body = data[i][2];
                myObject.fee_des = data[i][3];
                feeData.push(myObject);
            }
            var jsonString = JSON.stringify(feeData);
            $("#fee").val(jsonString);
        }
    });
}

function addProcess() {
    if($("#process_rank").val() == "" || $("#process_title").val() == "" || $("#process_des").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });
        return;
    }

    process_table.row.add([$("#process_rank").val(),$("#process_title").val(),$("#process_des").val(),'<button type="button" class="btn btn-sm btn-danger" onclick="deleteProcess(this)"><i class="ti-trash"></i></button>']).draw();

    var data = process_table.rows().data();
    var processData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.process_rank = data[i][0];
        myObject.process_title = data[i][1];
        myObject.process_des = data[i][2];
        processData.push(myObject);
    }
    var jsonString = JSON.stringify(processData);
    $("#process").val(jsonString);

    $('#process-modal').modal('hide');

}

function deleteProcess(obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
            process_table.row($(obj).parents('tr'))
                .remove()
                .draw();

            var data = process_table.rows().data();
            var processData = Array();
            for (var i = 0; i < data.length; i++) {
                var myObject = new Object();
                myObject.process_rank = data[i][0];
                myObject.process_title = data[i][1];
                myObject.process_des = data[i][2];
                processData.push(myObject);
            }
            var jsonString = JSON.stringify(processData);
            $("#process").val(jsonString);
        }
    });
}

function createProcess() {
    $('#process_title').val('');
    $('#process_rank').val('');
    $('#process_des').val('');
    $('#process-modal').modal();
}

function addDoc() {
    if($("#doc_name").val() == "" || $("#doc_des").val() == "" || $("#doc_download").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });
        return;
    }

    doc_table.row.add([$("#doc_name").val(),$("#doc_des").val(),
        '<a href="' + file_base_url + $("#doc_download").val() + '">' + $("#doc_download").val() + '</a>','<button type="button" class="btn btn-sm btn-danger" onclick="deleteDocument(this,' + "'" + $("#doc_name").val() + "'" + ')"><i class="ti-trash"></i></button>']).draw();

    var jsonString = Array();
    if($("#document").val() != "")
    {
        jsonString = JSON.parse($("#document").val());
    }

    var myObject = new Object();
    myObject.doc_name = $("#doc_name").val();
    myObject.doc_des = $("#doc_des").val();
    myObject.doc_download = $("#doc_download").val();
    jsonString.push(myObject);

    $("#document").val(JSON.stringify(jsonString));

    $('#doc-modal').modal('hide');
}

function showAdvModal() {
    $('#adv_is_update').val(0);
    $('#adv_rank').val('');
    $('#adv_title').val('');
    $('#adv_des').val('');
    $('#adv-modal').modal();
}

function changeAdvModal(title, rank, des, obj) {
    $('#adv_is_update').val(1);
    $('#adv_rank').val(rank);
    $('#adv_title').val(title);
    $('#adv_des').val(des);
    selected_row = adv_table.row($(obj).parents('tr'));
    $('#adv-modal').modal();
}

function addAdv() {
    if($("#adv_rank").val() == "" || $("#adv_title").val() == "" || $("#adv_des").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });
        return;
    }

    if( $('#adv_is_update').val() == 0)
        adv_table.row.add([$("#adv_rank").val(),$("#adv_title").val(), $("#adv_des").val(), '<button type="button" class="btn btn-sm btn-success" onclick="changeAdvModal(' + "'" + $("#adv_title").val() + "','" + $("#adv_rank").val() + "','" +  $("#adv_des").val() + "'" + ',this)"><i class="ti-pencil-alt"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" onclick="deleteAdv(this)"><i class="ti-trash"></i></button>']).draw();
    else {
        var data = selected_row.data();
        data[0] = $("#adv_rank").val();
        data[1] = $("#adv_title").val();
        data[2] = $("#adv_des").val();
        selected_row.data(data).draw();
    }

    var data = adv_table.rows().data();
    var advData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.rank = data[i][0];
        myObject.title = data[i][1];
        myObject.des = data[i][2];
        advData.push(myObject);
    }
    var jsonString = JSON.stringify(advData);
    $("#adv").val(jsonString);

    $('#adv-modal').modal('hide');
}

function deleteAdv(obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
            adv_table.row($(obj).parents('tr'))
                .remove()
                .draw();

            var data = adv_table.rows().data();
            var advData = Array();
            for (var i = 0; i < data.length; i++) {
                var myObject = new Object();
                myObject.rank = data[i][0];
                myObject.title = data[i][1];
                myObject.des = data[i][2];
                advData.push(myObject);
            }
            var jsonString = JSON.stringify(advData);
            $("#adv").val(jsonString);
        }
    });

}

function showRequirementsModal() {
    $('#requirement_is_update').val(0);
    $('#requirements_rank').val('');
    $('#requirements_title').val('');
    $('#requirements_des').val('');
    $('#requirements-modal').modal();
}

function changeRequireModal(title, rank, des, obj) {
    $('#requirement_is_update').val(1);
    $('#requirements_rank').val(rank);
    $('#requirements_title').val(title);
    $('#requirements_des').val(des);
    selected_row = requirements_table.row($(obj).parents('tr'));
    $('#requirements-modal').modal();
}

function addRequirements() {
    if($("#requirements_rank").val() == "" || $("#requirements_title").val() == "" || $("#requirements_des").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });        return;
    }

    if( $('#requirement_is_update').val() == 1) {
        var data = selected_row.data();
        data[0] = $("#requirements_rank").val();
        data[1] = $("#requirements_title").val();
        data[2] = $("#requirements_des").val();
        selected_row.data(data).draw();
    }
    else
    {
        requirements_table.row.add([$("#requirements_rank").val(),$("#requirements_title").val(), $("#requirements_des").val(), '<button type="button" class="btn btn-sm btn-success" onclick="changeRequireModal(' + "'" + $("#requirements_title").val() + "','" + $("#requirements_rank").val() + "','" +  $("#requirements_des").val() + "'" + ',this)"><i class="ti-pencil-alt"></i><button type="button" class="btn btn-sm btn-danger m-l-10"<button type="button" class="btn btn-sm btn-danger" onclick="deleteRequirement(this)"><i class="ti-trash"></i></button>']).draw();
    }

    var data = requirements_table.rows().data();
    var requirementsData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.rank = data[i][0];
        myObject.title = data[i][1];
        myObject.des = data[i][2];
        requirementsData.push(myObject);
    }
    var jsonString = JSON.stringify(requirementsData);
    $("#requirements").val(jsonString);

    $('#requirements-modal').modal('hide');
}

function deleteRequirement(obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
            requirements_table.row($(obj).parents('tr'))
                .remove()
                .draw();

            var data = requirements_table.rows().data();
            var requirementData = Array();
            for (var i = 0; i < data.length; i++) {
                var myObject = new Object();
                myObject.rank = data[i][0];
                myObject.title = data[i][1];
                myObject.des = data[i][2];
                requirementData.push(myObject);
            }
            var jsonString = JSON.stringify(requirementData);
            $("#requirements").val(jsonString);
        }
    });

}

function createDoc() {
    $('#doc_name').val('');
    $('#doc_des').val('');

    $('#doc-modal').modal();
}

function deleteDocument(obj,name) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
            doc_table.row($(obj).parents('tr'))
                .remove()
                .draw();

            var jsonString = JSON.parse($("#document").val());
            var newjsonString = Array();
            for (var i = 0; i < jsonString.length; i++) {
                if (jsonString[i]["doc_name"] != name) {
                    var myObject = new Object();
                    myObject.doc_name = jsonString[i]["doc_name"];
                    myObject.doc_des = jsonString[i]["doc_des"];
                    myObject.doc_download = jsonString[i]["doc_download"];
                    newjsonString.push(myObject);
                }
            }

            $("#document").val(JSON.stringify(newjsonString));
        }
    });
}

function showFaqModal(obj) {
    $("#faq_hash").val("");
    $("#faq-modal").modal();
}

function addFaq() {
    if($("#faq_hash").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入FAQ Hash'
        });
    }
    else {
        $.ajax({
            url: '/admin/immi/add_faq',
            data: {
                immi_hash : $("#hash").val(),
                faq_hash : $("#faq_hash").val()
            },
            type: 'POST',
            success: function (resp) {
                if (resp.code == '0') {
                    faq_table.row.add([resp.data.faq_name,resp.data.faq_hash,'<button type="button" class="btn btn-sm btn-danger" onclick="deleteFaq(' + "'" + resp.data.faq_hash + "'" + ',this)"><i class="ti-trash"></i></button>']).draw();
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '添加成功',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $("#faq-modal").modal('hide');
                } else {
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        text: resp.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    })
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
                })
            }
        });
    }
}

function deleteFaq(faq_hash, obj) {
    Swal.fire({
        title: "你确定要删除该信息吗?",
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
                    url: '/admin/immi/delete_faq',
                    data: {
                        faq_hash : faq_hash,
                        immi_hash : $("#hash").val()
                    },
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
                            })
                            faq_table.row($(obj).parents('tr'))
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
                            })
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
                        })
                    }
                });
            }
        });
}


function saveImmigrant() {
    var form = new FormData($('#immi-form')[0]);
    // form.append('content_real', $('#content').summernote('code'));

    showOverlay();
    if ($('#hash').val() == '') {
        $.ajax({
            url: '/admin/immi/create',
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
                    });
                    window.location.href = '/admin/immi/' + resp.data.hash;
                } else {
                    Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                })
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
                })
            }
        })
    } else {
        $.ajax({
            url: '/admin/immi/update',
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
                    });
                } else {
                    Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                })
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
                })
            }
        })
    }
}

$(function () {
    getCountry();
    changeCountry();

    immi_table = $('#tbl_immigrant').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[3, "desc"]],
        "pageLength":10,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"GET",
            "url": '/admin/immi/get',
            "dataType":"json"
        },
        columns: [
            {data: 'name_cn'},
            {data: 'country'},
            {data: 'is_published'},
            {data: 'published_at'},
            {data: 'duration'},
            {data: 'type'},
            {data: 'hash'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[0],
                "mRender":function(data,type,full){
                    return '<a href="/admin/immi/' + full.hash + '"><h6>' + data + '</h6><small>' + full.hash + '</small></a>';
                }
            },
            {
                "aTargets":[1],
                "mRender":function(data,type,full){
                    if(full.state == null)
                        return  data + "  全部区域";
                    else
                        return  data + "  " + full.state;
                }
            },
            {
                "aTargets":[2],
                "mRender":function(data,type,full) {
                    var html = "";
                    if (data === 1)
                    {
                        html = '<span class="label label-custom">已发布</span>';
                    }
                    else
                    {
                        html = '<span class="label label-inverse">未发布</span>';
                    }

                    if(full.is_hot == 1)
                    {
                        html += '<span class="label label-danger m-l-10"> 热们</span>';
                    }

                    if(full.is_recommended == 1)
                    {
                        html += '<span class="label label-info m-l-10"> 推荐</span>';
                    }

                    return html;
                }
            },
            {
                "aTargets":[4],
                "mRender":function(data,type,full) {
                    if (data > 12)
                        return '12个月以上';
                    else
                        return  data + '个月以内';
                }
            },
            {
                "aTargets":[6],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-danger" onclick="deleteImmigrant(\'' + data+ '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    adv_table = $('#tbl_adv').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
           {
                "className": "text-center", "targets": "_all"
           }]
    });

    if($("#adv").val() != "" && typeof $("#adv").val() !== "undefined")
    {
        var adv_array = JSON.parse($("#adv").val());
        for(var i = 0; i < adv_array.length; i++) {
            adv_table.row.add([adv_array[i].rank,adv_array[i].title,adv_array[i].des,'<button type="button" class="btn btn-sm btn-success" onclick="changeAdvModal(' + "'" + adv_array[i].title + "','" + adv_array[i].rank + "','" +  adv_array[i].des + "'" + ',this)"><i class="ti-pencil-alt"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" onclick="deleteAdv(this)"><i class="ti-trash"></i></button>']).draw();
        }
    }


    requirements_table = $('#tbl_requirements').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "className": "text-center", "targets": "_all"
            }]
    });

    if($("#requirements").val() != "" && typeof $("#requirements").val() !== "undefined")
    {
        var requirement_array = JSON.parse($("#requirements").val());
        for(var i = 0; i < requirement_array.length; i++) {
            requirements_table.row.add([requirement_array[i].rank,requirement_array[i].title,requirement_array[i].des,'<button type="button" class="btn btn-sm btn-success" onclick="changeRequireModal(' + "'" + requirement_array[i].title + "','" + requirement_array[i].rank + "','" +  requirement_array[i].des + "'" + ',this)"><i class="ti-pencil-alt"></i><button type="button" class="btn btn-sm btn-danger m-l-10" onclick="deleteRequirement(this)"><i class="ti-trash"></i> </button>']).draw();
        }
    }

    article_table = $('#tbl_training').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "className": "text-center", "targets": "_all"
            }]
    });

    process_table = $('#tbl_process').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "className": "text-center", "targets": "_all"
            }]
    });
    if($("#process").val() != "" && typeof $("#process").val() !== "undefined")
    {
        var process_array = JSON.parse($("#process").val());
        for(var i = 0; i < process_array.length; i++) {
            process_table.row.add([process_array[i].process_rank,process_array[i].process_title,process_array[i].process_des,'<button type="button" class="btn btn-sm btn-danger" onclick="deleteProcess(this)"><i class="ti-trash"></i></button>']).draw();
        }
    }

    doc_table = $('#tbl_doc').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "className": "text-center", "targets": "_all"
            }]
    });

    if($("#document").val() != "" && typeof $("#document").val() !== "undefined")
    {
        var doc_array = JSON.parse($("#document").val());
        for(var i = 0; i < doc_array.length; i++) {
            doc_table.row.add([doc_array[i].doc_name,doc_array[i].doc_des,'<a href="' + file_base_url + doc_array[i].doc_download + '">' + doc_array[i].doc_download + '</a>','<button type="button"  class="btn btn-sm btn-danger" onclick="deleteDocument(this,' + "'" +  doc_array[i].doc_name  + "'" + ')"><i class="ti-trash"></i> </button>']).draw();
        }
    }

    fee_table = $('#tbl_fee').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "className": "text-center", "targets": "_all"
            }]
    });

    if($("#fee").val() != ""  && typeof $("#fee").val() !== "undefined")
    {
        var fee_array = JSON.parse($("#fee").val());
        for(var i = 0; i < fee_array.length; i++) {
            fee_table.row.add([fee_array[i].fee_title, formatNumber(fee_array[i].fee_amount),fee_array[i].fee_body,fee_array[i].fee_des,'<button type="button" class="btn btn-sm btn-danger" onclick="deleteFee(this)"><i class="ti-trash"></i> </button>']).draw();
        }
    }

    $('#doc_file').change(function (){
        showOverlay();
        // var myForm = document.getElementById('docForm');
        // var formData = new FormData(myForm);
        $.ajax({
            url: '/admin/immi/add_doc_file',
            data: new FormData(document.querySelector('#docForm')),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == '0') {
                    file_base_url = resp.data.base_url;
                    $("#doc_download").val(resp.data.file);
                } else {
                    Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                })
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
                })
            }
        })
    });

    $('.owl-carousel').owlCarousel({
        loop:false,
        margin:10,
        nav:false,
        responsive:{
            0:{
                items:1
            },
            1000:{
                items:3
            },
            1600:{
                items:5
            }
        }
    })

    faq_table = $('#tbl_faq').DataTable({
        "columnDefs": [
            { "orderable": false, 'targets': [0] },
            { "className": 'text-center', 'targets': '_all'}
        ],
        "order": [[ 0, "asc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });
});

function setImagePrimary(obj, url,is_primary) {
    $.ajax({
        url: '/admin/immi/image/change_primary',
        data: {
            image_url:url,
            hash:$("#hash").val()
        },
        type: 'POST',
        success: function (resp) {
            if(resp.code == 0) {
                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    text: '',
                    title: '设置成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                var image_html = "";
                $("#immi_images").html(image_html);

                for (var i = 0; i < resp.data.images.length; i++) {
                    var btn_icon = " ti-thumb-up";
                    if(resp.data.images[i].is_primary == 1){
                        btn_icon = "ti-thumb-down";
                    }
                    image_html += '<div class="item"><img src="' + resp.data.base_url + '/' + resp.data.images[i].url + '" height="150"><div class="row" style="position:absolute; right:20px; bottom:10px"><button type="button" class="btn btn-sm btn-cyan" onclick="setImagePrimary(this,\'' + resp.data.images[i].url + '\',' + resp.data.images[i].is_primary + ')"><i class="' + btn_icon + '"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" style="" onclick="deleteAsset(this,\'' + resp.data.images[i].url + '\', \'image\')"><i class="ti-trash"></i></button></div></div>';
                }
                $('#images').val('');
                $("#immi_images").html(image_html);
                $('.owl-carousel').trigger('destroy.owl.carousel');

                $('.owl-carousel').owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 3
                        },
                        1000: {
                            items: 5
                        }
                    }
                })
            }
            else {
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
                    text: resp.message,
                    title: '设置失败',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    })
}

function addImages() {
    if($('#images').get(0).files.length === 0) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择图片'
        });
        return;
    }
    Swal.fire({
        title: "你确定要上传该文件吗?",
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
            showOverlay();
            $.ajax({
                url: '/admin/immi/add_image',
                data: new FormData(document.querySelector('form')),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        var image_html = "";
                        for (var i = 0; i < resp.data.images.length; i++) {
                            var btn_icon = " ti-thumb-up";
                            if(resp.data.images[i].is_primary == 1){
                                btn_icon = "ti-thumb-down";
                            }
                            image_html += '<div class="item"><img src="' + resp.data.base_url + '/' + resp.data.images[i].url + '" height="150"><div class="row" style="position:absolute; right:20px; bottom:10px"><button type="button" class="btn btn-sm btn-cyan" onclick="setImagePrimary(this,\'' + resp.data.images[i].url + '\',' + resp.data.images[i].is_primary + ')"><i class="' + btn_icon + '"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" style="" onclick="deleteImage(\'' + resp.data.images[i].url + '\')"><i class="ti-trash"></i></button></div></div>';
                        }
                        $('#images').val('');
                        $("#immi_images").html(image_html);
                        $("#imageJson").val(JSON.stringify(resp.data.images));
                        $('.owl-carousel').trigger('destroy.owl.carousel');

                        $('.owl-carousel').owlCarousel({
                            loop: false,
                            margin: 10,
                            nav: false,
                            responsive: {
                                0: {
                                    items: 1
                                },
                                1000: {
                                    items: 3
                                },
                                1600: {
                                    items: 5
                                }
                            }
                        })

                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '保存图片成功',
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
                        })
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
                    })
                }
            })
        }
    });
}

function addVideo() {
    if ($('#video').get(0).files.length === 0) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择视频'
        });
        return;
    }

    Swal.fire({
        title: "你确定要上传该文件吗?",
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
                showOverlay();
            $.ajax({
                url: '/admin/immi/add_video',
                data: new FormData(document.querySelector('form')),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        $('#video').val('');
                        $("#video-player").html('<source src="' + resp.data.video + '" type="video/mp4"></source>');
                        $("#deleteVideoBtn").show();
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '保存视频成功',
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
                        })
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
                    })
                }
            })
        }
    })

}

function addTrainingVideo() {
    if ($('#training_video').get(0).files.length === 0) {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请选择视频'
        });
        return;
    }

    Swal.fire({
        title: "你确定要上传该文件吗?",
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
                showOverlay();
                $.ajax({
                    url: '/admin/immi/add_training_video',
                    data: new FormData(document.querySelector('form')),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (resp) {
                        hideOverlay();
                        if (resp.code == '0') {
                            $('#training_video').val('');
                            $("#training-video-player").html('<source src="' + resp.data.training_video + '" type="video/mp4"></source>');
                            $("#btn_del_training_video").show();
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                text: '',
                                title: '保存视频成功',
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
                            })
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
                        })
                    }
                })
            }
        })

}

function deleteVideo()
{
    Swal.fire({
        title: "你确定要删除该视频吗?",
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
            showOverlay();
            $.ajax({
                url: '/admin/immi/delete_video',
                data: {
                    hash: $('#hash').val()
                },
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        $("#video").val('');
                        $('#video-player source').attr('src', "");
                        $("#video-player")[0].load();
                        $("#deleteVideoBtn").hide();
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '删除视频成功',
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
                        })
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
                    })
                }
            })
        }
    })
}

function deleteTrainingVideo()
{
    Swal.fire({
        title: "你确定要删除该视频吗?",
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
                showOverlay();
                $.ajax({
                    url: '/admin/immi/delete_training_video',
                    data: {
                        hash: $('#hash').val()
                    },
                    type: 'POST',
                    success: function (resp) {
                        hideOverlay();
                        if (resp.code == '0') {
                            $("#training_video").val('');
                            $('#training-video-player source').attr('src', "");
                            $("#training-video-player")[0].load();
                            $("#btn_del_training_video").hide();
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                text: '',
                                title: '删除视频成功',
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
                            })
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
                        })
                    }
                })
            }
        })
}

function deleteImage(name) {
    Swal.fire({
        title: "你确定要删除选中的图片吗?",
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
                showOverlay();
            $.ajax({
                url: '/admin/immi/delete_file',
                data: {
                    name: name,
                    hash: $('#hash').val()
                },
                type: 'POST',
                success: function (resp) {
                    hideOverlay();

                    if (resp.code == '0') {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '删除成功',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        fileNameArray = JSON.parse($("#imageJson").val());
                        for (var i = 0; i < fileNameArray.length; i++) {
                            if (fileNameArray[i].url == name) {
                                fileNameArray.splice(i, 1);
                            }
                        }

                        $("#imageJson").val(JSON.stringify(fileNameArray));
                        $("#immi_images").html("");
                        var image_html = "";
                        for (var i = 0; i < fileNameArray.length; i++) {
                            var btn_icon = " ti-thumb-up";
                            if(fileNameArray[i].is_primary == 1){
                                btn_icon = "ti-thumb-down";
                            }
                            image_html += '<div class="item"><img src="' + resp.data.base_url + '/' + fileNameArray[i].url + '" height="150"><div class="row" style="position:absolute; right:20px; bottom:10px"><button type="button" class="btn btn-sm btn-cyan" onclick="setImagePrimary(this,\'' + fileNameArray[i].url + '\',' + fileNameArray[i].is_primary + ')"><i class="' + btn_icon + '"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" style="" onclick="deleteImage(\'' + fileNameArray[i].url + '\')"><i class="ti-trash"></i></button></div></div>';
                            //image_html += '<div class="item"><img src="' + resp.data.base_url + "/" + fileNameArray[i].url + '" height="150"><button class="btn btn-sm btn-danger" style="position:absolute; right:10px; bottom:10px" onclick="deleteImage(\'' + fileNameArray[i].url + '\')"><i class="ti-trash"></i></button></div>';
                        }

                        $("#immi_images").html(image_html);
                        $('.owl-carousel').trigger('destroy.owl.carousel');

                        $('.owl-carousel').owlCarousel({
                            loop: false,
                            margin: 10,
                            nav: false,
                            responsive: {
                                0: {
                                    items: 1
                                },
                                1000: {
                                    items: 3
                                },
                                1600: {
                                    items: 5
                                }
                            }
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            text: resp.message,
                            title: '错误',
                            showConfirmButton: false,
                            timer: 3000
                        })
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
                    })
                }
            })
        }
    })
}

function getObjectURL(file) {
    var url = null ;
    // 下面函数执行的效果是一样的，只是需要针对不同的浏览器执行不同的 js 函数而已
    if (window.createObjectURL!=undefined) { // basic
        url = window.createObjectURL(file) ;
    } else if (window.URL!=undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file) ;
    } else if (window.webkitURL!=undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file) ;
    }
    return url ;
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}