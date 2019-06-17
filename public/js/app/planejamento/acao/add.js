$(function () {
    $.pnotify.defaults.history = false;

    $(".select2").select2();

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $('#idobjetivo')
        .attr('readonly', true)
        .focus(function () {
            $(this).blur();
        });

    var $form = $("form#form-acao");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/planejamento/acao/add/format/json", "form#form-acao", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
            //console.log('enviando');
        }
    });


});