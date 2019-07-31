$(document).ready(function () {
    $("#desemail, #token").on('click', function () {
        $(this).parent('div')
            .removeClass('error')
            .find(".error, .help-inline").fadeOut(500);
    });
    $("#resetbutton").on('click', function () {
        $('.error').removeClass('error');
        $(".error, .help-inline").fadeOut(500);
    });
});