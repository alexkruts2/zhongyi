var platform, map, ui, bubble, marker, search, explore,icon,tooltip;
var primarySchools = [], highSchools = [], universities = [], restaurants = [], hospitals = [], transports = [], leisures = [], shoppings = []
var subIconSize = 32;

var initializeMap = function() {
    if (map != null) {
        return;
    }
    platform = new H.service.Platform({
        'app_id': 'rLH3gcQKZ8FDUWfBfckJ',
        'app_code': 'CWWgJNAXJhCrQcLm4rsUWg',
        useHTTPS: true
    });
    icon = new H.map.Icon('/static/images/location/ic_mapmarkhome.png');

    var pixelRatio = window.devicePixelRatio || 1;
    var defaultLayers = platform.createDefaultLayers({
        tileSize: pixelRatio === 1 ? 256 : 512,
        ppi: pixelRatio === 1 ? undefined : 320,
        lg: 'CHI'
    });

    //Step 2: initialize a map  - not specificing a location will give a whole world view.
    map = new H.Map(document.getElementById('map-box'),
        defaultLayers.normal.map, {pixelRatio: pixelRatio});

    //Step 3: make the map interactive
    // MapEvents enables the event system
    // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
    var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

    // Create the default UI components
    ui = H.ui.UI.createDefault(map, defaultLayers, 'zh-CN');
    ui.removeControl('mapsettings');

    map.setCenter({lat:$('#lat').val(), lng:$('#lng').val()});

    addDraggableMarker(map, behavior);

    map.setZoom(16);
    tooltip = new Tooltip(map);

    search = new H.places.Search(platform.getPlacesService());
    explore = new H.places.Explore(platform.getPlacesService())
};

var geocode = function(address) {
    var geocoder = platform.getGeocodingService(),
        geocodingParameters = {
            searchText: $('#address').val(),
            jsonattributes : 1
        };

    geocoder.geocode(
        geocodingParameters,
        onSuccess,
        onError
    );
};

function searchPlaces() {

    $('#primary-school').html('');
    $('#high-school').html('');
    $('#university').html('');
    $('#restaurant').html('');
    $('#hospital').html('');
    $('#public-transport').html('');
    $('#leisure').html('');
    $('#shopping').html('');

    showOverlay();
    var params = {
        'q': 'primary school',
        'at': marker.getPosition().lat + ',' + marker.getPosition().lng
    };
    search.request(params, {}, onPrimarySchool, onError);
    showOverlay();
    params = {
        'q': 'high school',
        'at': marker.getPosition().lat + ',' + marker.getPosition().lng
    };
    search.request(params, {}, onHighSchool, onError);
    showOverlay();
    params = {
        'q': 'university',
        'at': marker.getPosition().lat + ',' + marker.getPosition().lng
    };
    search.request(params, {}, onUniversity, onError);
    showOverlay();
    params = {
        'cat': 'eat-drink',
        'in': marker.getPosition().lat + ',' + marker.getPosition().lng + ';r=5000'
    };
    explore.request(params, {}, onRestaurant, onError);
    showOverlay();
    params = {
        'cat': 'hospital',
        'in': marker.getPosition().lat + ',' + marker.getPosition().lng + ';r=5000'
    };
    explore.request(params, {}, onHospital, onError);
    showOverlay();
    params = {
        'cat': 'transport',
        'in': marker.getPosition().lat + ',' + marker.getPosition().lng + ';r=5000'
    };
    explore.request(params, {}, onTransport, onError);
    showOverlay();
    params = {
        'cat': 'going-out',
        'in': marker.getPosition().lat + ',' + marker.getPosition().lng + ';r=5000'
    };
    explore.request(params, {}, onLeisure, onError);
    showOverlay();
    params = {
        'cat': 'shopping',
        'in': marker.getPosition().lat + ',' + marker.getPosition().lng + ';r=5000'
    };
    explore.request(params, {}, onShopping, onError);
}

function onPrimarySchool(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });

    for (var i = 0 ; i < data.results.items.length ; i++) {
        if (data.results.items[i].distance > 2000)
            continue;

        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td>'+
            '<td contenteditable="true">' + data.results.items[i].title + '</td>'+
            '<td contenteditable="true"></td>'+
            '<td contenteditable="true">' + data.results.items[i].distance + '</td>'+
            '<td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] + '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'小学\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#primary-school').append(tr);

        // var obj = {name: data.results.items[i].title.replace('&', ''), distance: data.results.items[i].distance, icon: data.results.items[i].icon};
        // primarySchools.push(obj);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-primary.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);
}

function onHighSchool(data) {
    hideOverlay();
    var tr;

    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        if (data.results.items[i].distance > 3000)
            continue;

        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title +
            '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] +
            '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'中学\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#high-school').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-high.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);

}

function onUniversity(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title +
            '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] +
            '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'大学\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#university').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-university.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);
}

function onRestaurant(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        if (data.results.items[i].distance > 1000)
            continue;

        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title + '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] +
            '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'餐饮\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#restaurant').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-eatdrink.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);

}

function onHospital(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title + '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] + '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'医院\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#hospital').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-hospital.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);
}

function onTransport(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        if (data.results.items[i].distance > 1000)
            continue;

        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title + '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] + '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'公共交通\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#public-transport').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-transport.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);

}

function onLeisure(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        if (data.results.items[i].distance > 2000)
            continue;

        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title + '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] + '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'娱乐\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#leisure').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-goingout.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);
}

function onShopping(data) {
    hideOverlay();
    var tr;
    data.results.items.sort(function (a, b) {
        if(a.distance < b.distance) return -1;
        if(a.distance > b.distance) return 1;
        return 0;
    });
    for (var i = 0 ; i < data.results.items.length ; i++) {
        if (data.results.items[i].distance > 5000)
            continue;

        tr = '<tr><td><img height="35px" src="' + data.results.items[i].icon + '"></td><td contenteditable="true">' + data.results.items[i].title + '</td><td contenteditable="true"></td><td contenteditable="true">' + data.results.items[i].distance + '</td><td>' + data.results.items[i].position[0] + ',' + data.results.items[i].position[1] + '</td>'+
            '<td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,\'购物\')"><i class="ti-trash"></i></button></td>'+
            '</tr>';
        $('#shopping').append(tr);
    }
    var markerData = [{
        iconUrl:'/static/images/location/icmark-shopping.png',
        datas:data.results.items
    }];
    drawMarkers(markerData);
}

function removePlace(array, name) {
    return array.filter(function (item) {
        return item.name !== name;
    });
}

function savePlaces(project_id) {
    showOverlay();

    $('#primary-school > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        primarySchools.push(obj);
    });
    $('#high-school > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        highSchools.push(obj);
    });
    $('#university > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        universities.push(obj);
    });
    $('#restaurant > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        restaurants.push(obj);
    });
    $('#hospital > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        hospitals.push(obj);
    });
    $('#public-transport > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        transports.push(obj);
    });
    $('#leisure > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        leisures.push(obj);
    });
    $('#shopping > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        shoppings.push(obj);
    });

    $.ajax({
        url: '/admin/project/savePlaces',
        data: "project_id=" + project_id + "&primarySchool=" + JSON.stringify(primarySchools) + "&highSchool=" + JSON.stringify(highSchools) + "&university=" + JSON.stringify(universities) + "&restaurant=" + JSON.stringify(restaurants) + "&hospital=" + JSON.stringify(hospitals) + "&transport=" + JSON.stringify(transports) + "&leisure=" + JSON.stringify(leisures) + "&shopping=" + JSON.stringify(shoppings),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            if (resp.code == '0') {

            } else {

            }
        }
    });
}

function deleteNearby(obj, type, id = 0) {

    if (id != 0) {
        $.ajax({
            url: '/admin/project/deletePlace',
            data: "id=" + id,
            cache: false,
            dataType: 'json',
            processData: false,
            type: 'POST',
            success: function (resp) {
                if (resp.code == '0') {

                } else {

                }
            }
        });
    }

    if (type == 'primary-school') {
        primarySchools = removePlace(primarySchools, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'high-school') {
        highSchools = removePlace(highSchools, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'university') {
        universities = removePlace(universities, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'restaurant') {
        restaurants = removePlace(restaurants, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'hospital') {
        hospitals = removePlace(hospitals, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'transport') {
        transports = removePlace(transports, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'leisure') {
        leisures = removePlace(leisures, $(obj).closest('tr').children('td').eq(1).html());
    } else if (type == 'shopping') {
        shoppings = removePlace(shoppings, $(obj).closest('tr').children('td').eq(1).html());
    }

    $(obj).closest('tr').remove();
}

function openBubble(position, text){
    if(!bubble){
        bubble =  new H.ui.InfoBubble(
            position,
            {content: text});
        ui.addBubble(bubble);
    } else {
        bubble.setPosition(position);
        bubble.setContent(text);
        bubble.open();
    }
}

function addLocationsToMap(locations){
    var group = new  H.map.Group(),
        position;

    // Add a marker for each location found
    if (locations.length > 0) {
        position = {
            lat: locations[0].location.displayPosition.latitude,
            lng: locations[0].location.displayPosition.longitude
        };
        if (!marker)
            marker = new H.map.Marker(position);

        marker.setPosition(position);
        marker.label = locations[0].location.address.label;

        $('#address').val(locations[0].location.address.label);
        $('#lat').val(position.lat);
        $('#lng').val(position.lng);
        // group.addObject(marker);
    }
    // for (i = 0;  i < locations.length; i += 1) {
    //     position = {
    //         lat: locations[i].location.displayPosition.latitude,
    //         lng: locations[i].location.displayPosition.longitude
    //     };
    //     marker = new H.map.Marker(position);
    //     marker.label = locations[i].location.address.label;
    //     group.addObject(marker);
    // }

    marker.addEventListener('tap', function (evt) {
        map.setCenter(evt.target.getPosition());
        openBubble(
            evt.target.getPosition(), evt.target.label);
    }, false);

    // Add the locations group to the map
    map.addObject(marker);
    map.setCenter(marker.getPosition());
}

function onSuccess(result) {
    var locations = result.response.view[0].result;
    /*
     * The styling of the geocoding response on the map is entirely under the developer's control.
     * A representitive styling can be found the full JS + HTML code of this example
     * in the functions below:
     */
    addLocationsToMap(locations);
}

function onError(error) {
    console.log(error);
}

function saveAddress(hash) {
    var pos = marker.getPosition();
    showOverlay();

    $('#primary-school > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        primarySchools.push(obj);
    });
    $('#high-school > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        highSchools.push(obj);
    });
    $('#university > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        universities.push(obj);
    });
    $('#restaurant > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        restaurants.push(obj);
    });
    $('#hospital > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        hospitals.push(obj);
    });
    $('#public-transport > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        transports.push(obj);
    });
    $('#leisure > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        leisures.push(obj);
    });
    $('#shopping > tr').each(function() {
        var obj = {name: $(this).find('td:nth-child(2)').html().replace('&', ''), chinese: $(this).find('td:nth-child(3)').html().replace('&', ''), distance: $(this).find('td:nth-child(4)').html(), location: $(this).find('td:nth-child(5)').html(), icon: $(this).find('td:first-child img').attr('src')};
        shoppings.push(obj);
    });
    var nearby = {
        'primarySchool':primarySchools,
        'highSchool':highSchools,
        'university':universities,
        'restaurant':restaurants,
        'hospital':hospitals,
        'transport':transports,
        'leisure':leisures,
        'shopping':shoppings
    };
    var address = $("#address").val();
    if(hash==undefined)
        hash='';
    $.ajax({
        url: '/admin/location/saveAddress',
        data:"hash=" + hash + '&address=' + $('#address').val() + '&lat=' + pos.lat + '&lng=' + pos.lng+'&nearby='+JSON.stringify(nearby),
        cache: false,
        dataType: 'json',
        processData: false,
        type: 'POST',
        success: function (resp) {
            hideOverlay();
            var loc_id = resp.data.id;
            if($("#property_hash").val() != "") {
                $.ajax({
                    url:"/admin/property/update_location",
                    data:{
                        hash: $("#property_hash").val(),
                        loc_id : loc_id
                    },
                    type:"post",
                    success:function(response){
                    }
                });
            }
            Swal.fire({
                position: 'top-end',
                type: 'success',
                text: '',
                title: '保存成功',
                showConfirmButton: false,
                timer: 1500
            });
        },
        error: function (err) {
            hideOverlay();
            Swal.fire({
                position: 'top-end',
                type: 'error',
                text: '',
                title: '保存失败',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}

function addDraggableMarker(map, behavior){

    var position = {
        lat: $('#lat').val(),
        lng: $('#lng').val()
    };
    marker = new H.map.Marker(position,{icon:icon});
    // Ensure that the marker can receive drag events
    marker.draggable = true;
    map.addObject(marker);

    // disable the default draggability of the underlying map
    // when starting to drag a marker object:
    map.addEventListener('dragstart', function(ev) {
        var target = ev.target;
        if (target instanceof H.map.Marker) {
            behavior.disable();
        }
    }, false);


    // re-enable the default draggability of the underlying map
    // when dragging has completed
    map.addEventListener('dragend', function(ev) {
        var target = ev.target;
        if (target instanceof mapsjs.map.Marker) {
            behavior.enable();
            $('#lat').val(target.b.lat);
            $('#lng').val(target.b.lng);
        }
    }, false);

    // Listen to the drag event and move the position of the marker
    // as necessary
    map.addEventListener('drag', function(ev) {
        var target = ev.target,
            pointer = ev.currentPointer;
        if (target instanceof mapsjs.map.Marker) {
            var position = map.screenToGeo(pointer.viewportX, pointer.viewportY);
            target.setPosition(position);
            // $.ajax({
            //     url: 'https://reverse.geocoder.api.here.com/6.2/reversegeocode.json',
            //     type: 'GET',
            //     dataType: 'jsonp',
            //     jsonp: 'jsoncallback',
            //     data: {
            //         prox: position.lat+','+position.lng+','+250,
            //         mode: 'retrieveAddresses',
            //         maxresults: '1',
            //         gen: '9',
            //         app_id: 'rLH3gcQKZ8FDUWfBfckJ',
            //         app_code: 'CWWgJNAXJhCrQcLm4rsUWg'
            //     },
            //     success: function (data) {
            //         console.log(JSON.stringify(data));
            //     }
            // });

        }
    }, false);
}

var geocodeAndSearch = function(){
    // map.removeObjects() ();
    var objects = map.getObjects();
    for(var i = 0 ; i < objects.length; i++){
        map.removeObject(objects[i]);
    }
    var geocoder = platform.getGeocodingService(),
        geocodingParameters = {
            searchText: $('#address').val(),
            jsonattributes : 1
        };

    geocoder.geocode(
        geocodingParameters,
        function(result){
            var locations = result.response.view[0].result;
            addLocationsToMap(locations);
            searchPlaces();
        },
        onError
    );
}

var drawMarkers = function(markers){
    for(var i = 0 ; i < markers.length; i++){
        var iconUrl = markers[i].iconUrl;
        var subIcon = new H.map.Icon(iconUrl,{size:{w: subIconSize, h: subIconSize}});
        for(var j=0; j<markers[i].datas.length;j++){
            var data = markers[i].datas[j];
            var lat =data.position[0];
            var lng = data.position[1];

            var subMarker = new H.map.Marker({lat:lat,lng:lng},{icon:subIcon});
            subMarker.title = data.title;
            map.addObject(subMarker);
        }
    }
}
var drawMarkersInUpdate = function(markers){
    var iconUrl = markers.iconUrl;
    var subIcon = new H.map.Icon(iconUrl,{size:{w: subIconSize, h: subIconSize}});
    for(var j=0; j<markers.datas.length;j++){
        var data = markers.datas[j];
        var lat =data.location.split(',')[0];
        var lng = data.location.split(',')[1];
        var subMarker = new H.map.Marker({lat:lat,lng:lng},{icon:subIcon});
        subMarker.title = data.name;

        map.addObject(subMarker);
    }
}
$(function () {
    // initializeMap();
    window.addEventListener('resize', () => map.getViewPort().resize());

    // $('#map-tab').on('shown.bs.tab', function (e) {
    //     initializeMap();
    // });
});


(function (ctx) {
    // ensure CSS is injected
    var tooltipStyleNode = ctx.createElement('style'),
        css = '#nm_tooltip{' +
            ' color:white;' +
            ' background:black;' +
            ' border: 1px solid grey;' +
            ' padding-left: 1em; ' +
            ' padding-right: 1em; ' +
            ' display: none;  ' +
            ' min-width: 120px;  ' +
            '}';

    tooltipStyleNode.type = 'text/css';
    if (tooltipStyleNode.styleSheet) { // IE
        tooltipStyleNode.styleSheet.cssText = css;
    } else {
        tooltipStyleNode.appendChild(ctx.createTextNode(css));
    }
    if (ctx.body) {
        ctx.body.appendChild(tooltipStyleNode);
    } else if (ctx.addEventListener) {
        ctx.addEventListener('DOMContentLoaded',  function () {
            ctx.body.appendChild(tooltipStyleNode);
        }, false);
    } else {
        ctx.attachEvent('DOMContentLoaded',  function () {
            ctx.body.appendChild(tooltipStyleNode);
        });
    }
})(document);

Object.defineProperty(Tooltip.prototype, 'visible', {
    get: function() {
        return this._visible;
    },
    set: function(visible) {
        this._visible = visible;
        this.tooltip.style.display = visible ? 'block' : 'none';
    }
});


function Tooltip(map) {
    var that = this;
    that.map = map;
    that.tooltip  = document.createElement('div');
    that.tooltip.id = 'nm_tooltip';
    that.tooltip.style.position = 'absolute';
    obj = null,
        showTooltip = function () {
            var point = that.map.geoToScreen(obj.getPosition()),
                left = point.x - (that.tooltip.offsetWidth / 2),
                top = point.y + 1; // Slight offset to avoid flicker.
            that.tooltip.style.left = left + 'px';
            that.tooltip.style.top = top + 'px';
            that.visible = true;
            that.tooltip.innerHTML =  obj.title;
        };

    map.getElement().appendChild(that.tooltip);
    map.addEventListener('pointermove', function (evt) {
        obj = that.map.getObjectAt(evt.currentPointer.viewportX,
            evt.currentPointer.viewportY);
        if(obj && obj.title){
            showTooltip();
        } else {
            that.visible = false;
        }
    });

    map.addEventListener('tap', function (evt){
        that.tooltip.visible  = false;
    });
    map.addEventListener('drag', function (evt){
        if (that.visible) {
            showTooltip();
        }
    });
};
