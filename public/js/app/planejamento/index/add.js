$(function () {
    $.pnotify.defaults.history = false;

    $(".select2").select2();

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    var $form = $("form#form-objetivo");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/planejamento/index/add/format/json", "form#form-objetivo", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
            //console.log('enviando');
        }
    });


});