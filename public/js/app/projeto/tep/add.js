
$(function() {

    $.pnotify.defaults.history = false;

    $("#resetbutton").click(function() {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
            $form = $("form#form-tep");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function(form) {
            enviar_ajax("/projeto/tep/editar/format/json", "form#form-tep", function(data) {
                if (data.success) {
                    //window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.dados.idprojeto;
                }
            });
        }
    });
});


