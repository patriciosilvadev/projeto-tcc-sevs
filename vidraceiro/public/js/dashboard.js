$(document).ready(function() {
    $(".navbar-toggler").click(function(event) {
        event.stopPropagation();
        $('#menu-dashboard').toggle();
    });
    $(window).resize(function() {
        $("#menu-dashboard").hide();
    });
    $('body,html').click(function(e) {
        var container = $("#menu-dashboard");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });
});