/**
 * Comment
 */

$(function () {
    $("#menuquest")
        .find('li').addClass('disabled')
        .end()
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menuquest')
        .find('a').unbind('click');

    $('#menuquest')
        .find('a').on("click", function (e) {
        e.preventDefault();
    });

    $.pnotify.defaults.history = false;


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
        $form = $("form#form-questionario"),
        url_cadastrar = base_url + "/diagnostico/questionario/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/diagnostico/questionario/add/format/json", $form, function (data) {
                if (data.success) {
                    window.location.href = base_url + "/diagnostico/questionario/dadosbasicos/idquestionariodiagnostico/" + data.msg.idquestionariodiagnostico + "/tpquestionario/" + data.msg.tpquestionario;
                }
            });
        }
    });

});