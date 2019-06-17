$(function () {
    $.pnotify.defaults.history = false;
    var $form = $("form#form-entidadeexterna");
    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/acordocooperacao/entidadeexterna/add/format/json", "form#form-entidadeexterna", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
        }
    });

    $('#voltar').click(function () {
        window.location.href = base_url + '/acordocooperacao/entidadeexterna/index';
    });


});

