$(function () {
    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-gerencia"),
        url_cadastrar = base_url + "/planodeacao/tpa/informacoestecnicas/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            $("#submitbutton").prop("disabled", true);
            setTimeout(function () {
                $("#submitbutton").prop("disabled", false);
            }, 4000);
            enviar_ajax("/planodeacao/tpa/informacoestecnicas/format/json", "form#form-gerencia", function (data) {
                if (data.success) {
                    $("#submitbutton").prop("disabled", false);
                } else {
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                }
            });
        }
    });
});
