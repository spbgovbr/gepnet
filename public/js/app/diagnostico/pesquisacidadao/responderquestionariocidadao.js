$(function () {

    var
        iddiagnostico = $("input[name='iddiagnostico']").val(),
        idquestionariodiagnostico = $("input[name='idquestionariodiagnostico']").val(),
        actions = {
            responder: {
                form: $('form#form-responder'),
                url: base_url + "/diagnostico/pesquisacidadaos/responderquestionariocidadao/format/json"
            },
            listagem: {
                url: base_url + "/diagnostico/pesquisacidadaos/listarquestionariocidadao/tpquestionario/2/iddiagnostico/" + iddiagnostico
            }
        },
        msgerror = "Não foi possivel realizar esta operação.",
        msgFormulario = "Por favor verifique suas respostas.";

    $(document).on('dblclick', '.radioButton', function () {
        if (this.checked) {
            $(this).prop('checked', false);
        }
    });

    $(document).on("input", ".textareaResposta", function () {
        var limite = $(this).attr("maxlength");
        var caracteresDigitados = $(this).val().length;
        var caracteresRestantes = limite - caracteresDigitados;

        if (limite < caracteresDigitados) {
            $(this).val(caracteresDigitados.substring(0, limite));
        }
        $(".caracteres").text(caracteresRestantes);
    });

    $(document.body).on('click', "#submitResponder", function (event) {
        event.preventDefault();
        if ($("form#form-responder").valid()) {
            var $paramsForm = $('form#form-responder').serialize();
            $.ajax({
                url: actions.responder.url,
                dataType: 'json',
                type: 'POST',
                async: true,
                cache: true,
                data: $paramsForm,
                success: function (data) {
                    console.log(data);
                    if (data.msg.type == 'success') {
                        $.pnotify(data.msg);
                        window.location.href = actions.listagem.url;
                    }
                    if (data.msg.type == 'info') {
                        $.pnotify(data.msg);
                        return false;
                    }
                },
                error: function () {
                    $.pnotify({
                        text: msgerror,
                        type: 'error',
                        hide: false
                    });
                }
            });
        } else {
            $.pnotify({
                text: msgFormulario,
                type: 'info',
                hide: false
            });
        }
    });

});