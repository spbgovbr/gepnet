$(function () {
    $.pnotify.defaults.history = false;
    var $form = $("form#form-setor");
    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/cadastro/setor/add/format/json", "form#form-setor", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
        }
    });

    $('#voltar').click(function () {
        window.location.href = base_url + '/cadastro/setor/index';
    });

});

