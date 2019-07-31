/**
 * Comment
 */
$(function () {
    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("body").delegate(".datemask-BR", "focusin", function () {
        $(this).mask('99/99/9999');
    });

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-r3g"),
        url_cadastrar = base_url + "/projeto/r3g/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/projeto/r3g/add/format/json", "form#form-r3g", function (data) {
                if (data.success) {
                    //window.location.href = base_url + "/projeto/r3g/add/idprojeto/" + data.idprojeto;
                }
            });
        }
    });
});


