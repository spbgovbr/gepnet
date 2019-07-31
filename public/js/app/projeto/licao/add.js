$(function () {

    $.pnotify.defaults.history = false;

    var $form = $("form#form-licao"),
        idProjeto = $("input[name='idprojeto']").val();

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function ($form) {
            enviar_ajax("/projeto/licao/cadastrar/format/json", "form#form-licao", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
        }
    });

});

