$(function () {

    $.pnotify.defaults.history = false;

    var $form = $("form#form-mudanca");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/projeto/solicitacaomudanca/add/format/json", "form#form-mudanca", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
        }
    });

    $("body").delegate("#datdecisao, #datsolicitacao", "focusin", function () {
        var $this = $(this);
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            changeMonth: true,
            changeYear: true
        });
        $(this).mask('99/99/9999');
    });

    $('#voltar').click(function () {
        window.location.href = base_url + '/projeto/solicitacaomudanca/index/idprojeto/' + $("input[name='idprojeto']").val();
    });

});

