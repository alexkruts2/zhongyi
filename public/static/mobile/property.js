$('.nav-link').on('shown.bs.tab', function (e) {
    $(this).removeClass('active');
    $("#tab-container li").removeClass('active');
    $(this).parent().addClass('active');
});
$('.questin-title').on('hidden.bs.collapse', function () {
    // do something…
    $(this).find(".fa-angle-down").show();
    $(this).find(".fa-angle-up").hide();
})
$('.questin-title').on('shown.bs.collapse', function () {
    // do something…
    console.log("show");
    $(this).find(".fa-angle-up").show();
    $(this).find(".fa-angle-down").hide();
})
$(function () {
    // initializeMap();
    $('#map-tab').on('shown.bs.tab', function (e) {
        initializeMap();
    });

});


