
var package_table, file_table, selected_row, property_table, price_table, faq_table;
var propTypeArr;
var countries;

function saveProperty() {
    var form = new FormData($("#property-form")[0]);
    showOverlay();
    var url = '/admin/property/';
    if ($('#hash').val() == '') {
        url += 'create'
    } else {
        url += 'update';
    }

    $.ajax({
        url: url,
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {

                if ($('#hash').val() == '') {
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: '新建物业成功',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    window.location.href = '/admin/property/' + resp.data;
                }
                else {
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: '更新成功',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }

            } else {
                Swal.fire({
                    position: 'top-end',
                    type: 'error',
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
    })
}

function addPackage() {
    if( $("#package-link").val() == "" || $("#package-comment").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入全部信息'
        });
        return;
    }

    package_table.row.add([$("#package-type").val(),$("#package-link").val(),$("#package-comment").val(),'<button type="button" class="btn btn-sm btn-danger" onclick="deletePackage(this)"><i class="ti-trash"></i></button>']).draw();

    var data = package_table.rows().data();
    var packageData = Array();
    for(var i = 0; i < data.length; i++)
    {
        var myObject = new Object();
        myObject.package_type = data[i][0];
        myObject.package_link = data[i][1];
        myObject.package_comment = data[i][2];
        packageData.push(myObject);
    }
    var jsonString = JSON.stringify(packageData);
    $("#packages").val(jsonString);

    $('#package-modal').modal('hide');
}

function createPackage() {
    $('#package-type').val('');
    $('#package-link').val('');
    $('#package-comment').val('');
    $('#package-modal').modal();
}

function deletePackage(obj) {
    Swal.fire({
        title: "你确定要删除选中的文件打包吗?",
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
            package_table.row($(obj).parents('tr'))
                .remove()
                .draw();

            var data = package_table.rows().data();
            var packageData = Array();
            for(var i = 0; i < data.length; i++)
            {
                var myObject = new Object();
                myObject.package_type = data[i][0];
                myObject.package_link = data[i][1];
                myObject.package_comment = data[i][2];
                packageData.push(myObject);
            }
            var jsonString = JSON.stringify(packageData);
            $("#packages").val(jsonString);
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
    $.ajax({
        url:'/admin/base/proptype/getPropertyType',
        data:{
            country_id: $('#country_id').val()
        },
        type:"POST",
        success:function(response){
            var propertyTypes = response.data;
            $("#prop_type")
                .find('option')
                .remove()
                .end();
            for(var i = 0 ; i < propertyTypes.length; i++){
                $("#prop_type").append(new Option(propertyTypes[i].name,propertyTypes[i].id));
            }
        }
    });

    for(var i = 0; i < countries.length; i++) {
        if(countries[i].id == $('#country_id').val()) {
            $(".currency_mark").html(countries[i].symbol);
        }
    }
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

function changeCity(){
    $.ajax({
        url:"/admin/base/city/suburbs",
        data:{
            country_id: $('#country_id').val(),
            state_id: $('#state_id').val(),
            city_id:$("#city_id").val()
        },
        type:"GET",
        success:function(response){
            $('#suburb_id')
                .find('option')
                .remove()
                .end()
                .append('<option value="0">--请选择小区--</option>')
                .val('0');

            var suburbs = response.data;
            for(var i = 0 ; i < suburbs.length;i++){
                $("#suburb_id").append(new Option(suburbs[i].name_cn, suburbs[i].id));
            }
        }
    })
}
function showPackageModal(isEdit, obj) {
    $('#package-modal').modal();

    if (isEdit) {
        selected_row = $(obj).parents('tr');
        $('#package-type').val($(selected_row).children('td:nth-child(1)').html());
        $('#package-link').val($(selected_row).children('td:nth-child(2)').html());
        $('#package-comment').val($(selected_row).children('td:nth-child(3)').html());
    }else{
        $('#package-link').val('');
        $('#package-comment').val('');
    }
}

function showFileModal(id, fileName,isAdd, obj) {
    $('#file-modal').modal();
    $('.dropify-clear').click();

    if (isAdd) {
        $('#file_id').val(id);
        tbl_selected_row = file_table.row($(obj).parents('tr'));

        selected_row = $(obj).parents('tr');
        $('#file_type').val($(selected_row).children('td:nth-child(1)').html().trim());
        $('#display_name').val($(selected_row).children('td:nth-child(2)').children('a:nth-child(1)').html().trim());
    }
    else {
        $('#display_name').val('');
        $('#file_type').val(0);
    }
}

function deleteSmallFile(id, obj) {
    Swal.fire({
            title: "你确定要删除选中的小文件吗?",
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
                    url: '/admin/property/file/delete',
                    data: {
                        hash:$("#hash").val(),
                        id: id
                    },
                    type: 'POST',
                    success: function (resp) {
                        hideOverlay();
                        if (resp.code == 0) {
                            file_table.row($(obj).parents('tr'))
                                .remove()
                                .draw();
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                text: '',
                                title: '删除成功',
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
        });
}


function deleteAsset(obj, name, type) {
    Swal.fire({
        title: "你确定要删除选中的文件吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    }).then(result => {
         if (result.value) {
             showOverlay();
            $.ajax({
                url: '/admin/property/deleteAsset',
                data: {
                    name: name,
                    hash: $('#hash').val(),
                    type: type
                },
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == 0) {
                        if (type === 'image') {
                            $(obj).closest('div.owl-item').remove();
                        } else if (type === 'video') {
                            $("#video-player source").attr('src', '');
                            $("#video-player")[0].load();
                            $("#btn_del_video").hide();
                        } else if (type === 'training_video') {
                            $("#training-video-player source").attr('src', '');
                            $("#training-video-player")[0].load();
                            $("#btn_del_training_video").hide();
                        } else if (type === 'audio') {
                            $("#audio-player source").attr('src', '');
                            $("#audio-player")[0].load();
                            $("#btn_del_audio").hide();
                        }
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '删除成功',
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
        } else {
            // handle dismissals
            // result.dismiss can be 'cancel', 'overlay', 'esc' or 'timer'
        }
    })
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

function getState(){
    $.ajax({
        url: '/admin/base/stateList',
        type: 'get',
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                cities = resp.data.state;
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

function deleteProperty(hash, obj) {
    Swal.fire({
            title: "你确定要删除该物业吗?",
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
                    url: '/admin/property/delete',
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
                            property_table.row($(obj).parents('tr'))
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
                        })
                    }
                });
            }
        });
}

function showPriceModal(obj) {
    $("#price_hash").val("");
    $("#price-modal").modal();
}

function addPrice() {
    if($("#price_hash").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入价格单Hash'
        });
    }
    else {
        $.ajax({
            url: '/admin/property/bind_price',
            data: {
                property_hash : $("#hash").val(),
                price_hash : $("#price_hash").val()
            },
            type: 'POST',
            success: function (resp) {
                if (resp.code == '0') {
                    var unit_html = "";
                    for(var i = 0; i < resp.data.units.length; i++) {
                        unit_html += '<a href="/admin/property/unit/' + resp.data.units[i].hash + '">' + resp.data.units[i].unit_number + '</a><br>';
                    }

                    price_table.row.add([resp.data.price_hash, resp.data.price_name, unit_html, '<button type="button" class="btn btn-sm btn-danger" onclick="deletePrice(' + "'" + resp.data.price_hash + "'" + ',this)"><i class="ti-trash"></i></button>']).draw();
                    $("#price-modal").modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        text: '',
                        title: '添加成功',
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
}

function deletePrice(price_hash, obj) {
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
                    url: '/admin/property/delete_price',
                    data: {
                        price_hash : price_hash,
                        property_hash : $("#hash").val()
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
                            });

                            price_table.row($(obj).parents('tr'))
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
            url: '/admin/property/add_faq',
            data: {
                property_hash : $("#hash").val(),
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
                        timer: 1500
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
                    timer: 1500
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
                    url: '/admin/property/delete_faq',
                    data: {
                        faq_hash : faq_hash,
                        property_hash : $("#hash").val()
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
                                timer: 1500
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
                            timer: 1500
                        })
                    }
                });
            }
        });
}

function setImagePrimary(obj, url,is_primary) {
    $.ajax({
        url: '/admin/property/image/change_primary',
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
                })
                var image_html = "";
                $("#property-images").html(image_html);

                for (var i = 0; i < resp.data.images.length; i++) {
                    var btn_icon = " ti-thumb-up";
                    var item_class= "";
                    if(resp.data.images[i].is_primary == 1){
                        btn_icon = "ti-thumb-down";
                        item_class= "highlight_border";
                    }

                    image_html += '<div class="item ' + item_class + '"><img src="' + resp.data.base_url + '/' + resp.data.images[i].url + '" height="150"><div class="row" style="position:absolute; right:20px; bottom:10px"><button type="button" class="btn btn-sm btn-cyan" onclick="setImagePrimary(this,\'' + resp.data.images[i].url + '\',' + resp.data.images[i].is_primary + ')"><i class="' + btn_icon + '"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" style="" onclick="deleteAsset(this,\'' + resp.data.images[i].url + '\', \'image\')"><i class="ti-trash"></i></button></div></div>';
                }
                $('#images').val('');
                $("#property-images").html(image_html);
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
                })
            }
        }
    })
}

function uploadAsset(type) {
    var url = "";
    if(type == "image") {
        if($('#images').get(0).files.length === 0) {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请选择图片'
            });
            return;
        }
        url = '/admin/property/upload_image';
    } else if(type == "video") {
        if($('#video').get(0).files.length === 0) {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请选择视频'
            });
            return;
        }

        url = '/admin/property/upload_video';
    } else if(type == "audio") {
        if($('#audio').get(0).files.length === 0) {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请选择音频'
            });
            return;
        }

        url = '/admin/property/upload_audio';
    } else if(type == "training_video") {
        if ($('#training_video').get(0).files.length === 0) {
            Swal.fire({
                type: 'error',
                title: '错误',
                text: '请选择培训视频'
            });
            return;
        }

        url = '/admin/property/upload_training_video';
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
    }).then(result => {
        if (result.value) {
            showOverlay();
            $.ajax({
                url: url,
                data: new FormData(document.querySelector('form')),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == '0') {
                        if (type == "image") {
                            var image_html = "";
                            for (var i = 0; i < resp.data.images.length; i++) {
                                var btn_icon = "ti-thumb-up";
                                if(resp.data.images[i].is_primary == 1){
                                    btn_icon = "ti-thumb-down";
                                }
                                image_html += '<div class="item"><img src="' + resp.data.base_url + '/' + resp.data.images[i].url + '" height="150"><div class="row" style="position:absolute; right:20px; bottom:10px"><button type="button" class="btn btn-sm btn-cyan" onclick="setImagePrimary(this,\'' + resp.data.images[i].url + '\',' + resp.data.images[i].is_primary + ')"><i class="' + btn_icon + '"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" style="" onclick="deleteAsset(this,\'' + resp.data.images[i].url + '\', \'image\')"><i class="ti-trash"></i></button></div></div>';
                            }
                            $('#images').val('');
                            $("#property-images").html(image_html);
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
                        } else if (type == "video") {
                            $('#video').val('');
                            $("#video-player").html('<source src="' + resp.data.video + '" type="video/mp4"></source>');
                            $("#btn_del_video").toggle();
                        } else if (type == "training_video") {
                            $('#training_video').val('');
                            $("#training-video-player").html('<source src="' + resp.data.video + '" type="video/mp4"></source>');
                            $("#btn_del_training_video").show();
                        } else if (type == "audio") {
                            $('#audio').val('');
                            $("#audio-player").html('<source src="' + resp.data.audio + '" type="audio/mpeg"></source>');
                            $("#btn_del_audio").toggle();
                        }
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            text: '',
                            title: '上传成功',
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
                            timer: 1500
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
                        timer: 1500
                    })
                    hideOverlay();
                }
            });
        }
    })
}

function changeCommissionType() {
    if($("#commission_type").val() == "百分比") {
        $(".commssion_tag").html("%");
    }
    else {
        $(".commssion_tag").html("$");
    }
}

function gotoLocation() {
    if($("#address").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入地址'
        });
        return;
    }
    // $.ajax({
    //     url: "/admin/location/addFromProperty",
    //     data: {
    //         property_hash: $("#hash").val(),
    //         address: $("#address").val(),
    //         lat: $("#lat").val(),
    //         lng: $("#lng").val()
    //     },
    //     type: 'POST',
    //     success: function (resp) {
    //         var result = resp;
    //     }
    // });
     window.location = "/admin/location/add?property_hash=" + $("#hash").val() + "&address=" + $("#address").val() + "&lat=" + $("#lat").val() + "&lng=" + $("#lng").val();
}

function changePrice(hash, name, obj) {
    $("#price_name").val(name);
    selected_row = price_table.row($(obj).parents('tr'));
    $("#price-change-modal").modal();
}

function gotoAddUnit(price_hash) {
    window.location = "/admin/property/unit/add/" + price_hash;
}

function changePriceName() {
    var data = selected_row.data();

    if($("#price_name").val() == "") {
        Swal.fire({
            type: 'error',
            title: '错误',
            text: '请输入价格单名称'
        });
    }
    else {
        $.ajax({
            url: '/admin/property/change_price_name',
            data: {
                price_hash: data[0],
                price_name: $("#price_name").val()
            },
            type: 'POST',
            success: function (resp) {
                if(resp.code == 0) {
                    data[1] = $("#price_name").val();
                    data[3] = '<button type="button" class="btn btn-sm btn-cyan" onclick="gotoAddUnit(' + "'" + data[0] + "'" + ')"><i class="ti-link"></i></button><button type="button" class="btn btn-sm btn-success m-l-5" onclick="changePrice(' + "'" + data[0] + "','" + data[1] + "'" + ',this)"><i class="ti-pencil-alt"></i></button>';
                    selected_row.data(data).draw();

                    $("#price-change-modal").modal('hide');
                }
            }
        });
    }
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
        url: '/admin/property/add_article',
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
                    timer: 1500
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
                timer: 1500
            })
        }
    });
}


function setRecommended() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/property/recommended',
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
        url: '/admin/property/hot',
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

function publish() {
    var hash = $('#hash').val();
    showOverlay();
    $.ajax({
        url: '/admin/property/publish',
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
                    title: '发布成功',
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
    })
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
            closeOnConfirm: true,
            closeOnCancel: true
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: '/admin/property/delete_article',
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
                                timer: 1500
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
                            timer: 1500
                        })
                    }
                });
            }
        });
}

function getPropertyType() {
    $.ajax({
        url: '/admin/property/getPropertyType',
        data:{
        },
        type: 'post',
        success: function (resp) {
            if (resp.code == '0') {
                propTypeArr = resp.data.prop_type;
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

function changePropType() {
    for(var i = 0; i < propTypeArr.length; i++) {
        var type = propTypeArr[i];
        if(type.id == $("#prop_type").val()) {
            if(type.include_land_price == 0) {
                $("#div_commission_construct").hide();
            }
            else {
                $("#div_commission_construct").show();
            }
            break;
        }
    }
}

function getIsInclude(hash) {
    $.ajax({
        url: '/admin/property/getIsIncludeLand',
        data:{
            hash:hash
        },
        type: 'post',
        success: function (resp) {
            if (resp.code == '0') {
                if(resp.data.isIncludeLandFee == 1) {
                    $("#div_commission_construct").show();;
                }
                else {
                    $("#div_commission_construct").hide();
                }
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

$(function () {
    getPropertyType();

    if($("#hash").val() != "" && typeof $("#hash").val() != "undefined") {
        getIsInclude($("#hash").val());
    }

    getCountry();

    property_table = $('#tbl_property').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'immi_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[5, "desc"]],
        "pageLength":10,
        "ajax":{
            "type":"GET",
            "url": '/admin/property/get',
            "dataType":"json"
        },
        columns: [
            {data: 'hash'},
            {data: 'name_cn'},
            {data: 'country'},
            {data: 'developer_name_cn'},
            {data: 'is_published'},
            {data: 'published_at'},
            {data: 'hash'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets":[0],
                "mRender":function(data,type,full) {
                    return '<a href="/admin/property/' + full.hash + '">' + data + '</a>';
                }
            },
            {
                "aTargets":[1],
                "mRender":function(data,type,full) {
                    if (data === null)
                        return full.name_en;
                    else
                        return data;
                }
            },
            // {
            //     "aTargets":[2],
            //     "mRender":function(data,type,full){
            //         for(var i = 0; i < countries.length; i++) {
            //             if(countries[i].id == data) {
            //                 return countries[i].name_cn;
            //             }
            //         }
            //     }
            // },
            {
                "aTargets":[3],
                "mRender":function(data,type,full) {
                    if (data === null)
                        return full.developer_name_en;
                    else
                        return data;
                }
            },
            {
                "aTargets":[3],
                "mRender":function(data,type,full) {
                    if (data === null)
                        return full.developer_name_en;
                    else
                        return data;
                }
            },
            {
                "aTargets":[4],
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
                        html += '<span class="label label-danger m-l-10"> 热销</span>';
                    }

                    if(full.is_recommended == 1)
                    {
                        html += '<span class="label label-info m-l-10"> 推荐</span>';
                    }
                    return html;
                }
            },
            {
                "aTargets":[6],
                "mRender":function(data,type,full) {
                    return '<button class="btn btn-sm btn-danger" onclick="deleteProperty(\'' + data+ '\', this)"><i class="ti-trash"></i></button>';
                }
            },
            {"className": "text-center", "targets": "_all"}
        ]
    });

    file_table = $('#tblfile').DataTable({
        "columnDefs": [
            { "orderable": false, 'targets': [0] },
            { "className": 'text-center', 'targets': '_all'}
        ],
        "order": [[ 0, "asc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    $('#package-form').submit(function (e) {
        e.preventDefault();

        var form = new FormData($(this)[0]);
        showOverlay();
        $.ajax({
            url: 'admin/property/add_package',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    package_table.row.add([''])
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
        })
    });

    $('#file-form').submit(function (e) {
        e.preventDefault();

        var form = new FormData($(this)[0]);
        showOverlay();
        if($("#file_id").val() == "") {
            showOverlay();
            $.ajax({
                url: 'file/upload',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == 0) {
                        file_table.row.add([resp.data.type,'<a href="' +  resp.data.name + '">' + resp.data.display_name + '</a>',resp.data.updated_at,'<button type="button" class="btn btn-sm btn-success" onclick="showFileModal(' + "'" + resp.data.id + "','" + resp.data.file + "'" + ',true,this)"><i class="ti-pencil-alt"></i></button><button type="button" class="m-l-10 btn btn-sm btn-danger" onclick="deleteSmallFile(' + "'" + resp.data.id + "'," + 'this)"><i class="ti-trash"></i></button>']).draw();

                        $('#file-modal').modal('hide');
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
        else {
            $.ajax({
                url: 'file/update',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == 0) {
                        var data = tbl_selected_row.data();
                        data[0] = resp.data.type;
                        data[1] = '<a href="' +  resp.data.name + '">' + resp.data.display_name + '</a>';
                        data[2] = resp.data.updated_at;
                        data[3] = '<button type="button" class="btn btn-sm btn-success" onclick="showFileModal(' + "'" + resp.data.id + "','" + resp.data.file + "'" + ',true,this)"><i class="ti-pencil-alt"></i></button><button type="button" class="btn btn-sm btn-danger m-l-10" onclick="deleteSmallFile(' + "'" + resp.data.id + "'," + 'this)"><i class="ti-trash"></i></button>';
                        tbl_selected_row.data(data).draw();

                        //selected_row.data([resp.data.type,resp.data.display_name,resp.data.updated_at,'<button type="button" class="btn btn-sm btn-success" onclick="showFileModal(' + "'" + resp.data.id + "','" + resp.data.file + "'" + ',true,this)"><i class="ti-pencil-alt"></i></button><button type="button" class="btn btn-sm btn-danger" onclick="deleteSmallFile(' + "'" + resp.data.id + "'," + 'this)"><i class="ti-trash"></i></button>']).draw();
                        $('#file-modal').modal('hide');
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


    });

    package_table = $('#tblpackage').DataTable({
        "columnDefs": [
            { "orderable": false, 'targets': [0] },
            { "className": 'text-center', 'targets': '_all'}
        ],
        "order": [[ 0, "asc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

    if($("#packages").val() != "" && typeof $("#packages").val() !== "undefined")
    {
        var package_array = JSON.parse($("#packages").val());
        for(var i = 0; i < package_array.length; i++) {
            package_table.row.add([package_array[i].package_type,package_array[i].package_link,package_array[i].package_comment,'<button type="button" class="btn btn-sm btn-danger" onclick="deletePackage(this)"><i class="ti-trash"></i></button>']).draw();
        }
    }

    price_table = $('#tbl_price').DataTable({
        "columnDefs": [
            { "orderable": false, 'targets': [0] },
            { "className": 'text-center', 'targets': '_all'}
        ],
        "order": [[ 0, "asc" ]],
        "language" : {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        }
    });

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

    article_table = $('#tbl_training').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "className": "text-center", "targets": "_all"
            }]
    });
});
