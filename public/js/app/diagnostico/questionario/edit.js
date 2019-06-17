/**
 * Comment
 */

$(function () {
    $("#menuquest")
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');

    $.pnotify.defaults.history = true;


    function enviar_ajax(url, form, callback) {
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: $(form).serialize(),
            //processData:false,
            success: function (data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                if (callback && typeof (callback) === "function") {
                    callback(data);
                }
                $.pnotify(data.msg);
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    var
        $form = $("form#form-questionario-editar"),
        url_cadastrar = base_url + "/diagnostico/questionario/dadosbasicos/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/diagnostico/questionario/dadosbasicos/format/json", $form, function (data) {
                if (data.success) {
                    window.location.href = base_url + "/diagnostico/questionario/dadosbasicos/idquestionariodiagnostico/" + data.msg.idquestionariodiagnostico + "/tpquestionario/" + data.msg.tpquestionario;
                }
            });
        }
    });

    $('#tpquestionario option').not(':selected').prop('disabled', true);

});