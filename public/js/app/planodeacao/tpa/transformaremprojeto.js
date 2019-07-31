/**
 * Comment
 */
function selectRow(row) {
    //console.log(row);
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

function fillSelect($num) {
    $idobjetivo = $("#idobjetivo").val() ? $("#idobjetivo").val() : -1;
    $.ajax({
        url: base_url + "/planodeacao/tpa/acao/idobjetivo/" + $idobjetivo,
        dataType: 'json',
        type: 'GET',
        async: true,
        cache: true,
        processData: false,
        success: function (data) {
//            console.log($num);
            var options = $("#idacao");
            if (data) {
                options.empty();
                options.append($("<option />").val("").text("Selecione"));
                $.each(data, function () {
                    options.append($("<option />").val(this.idacao).text(this.nomacao));
                });
                options.find('option[value=' + $num + ']').attr('selected', 'selected');
            }
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

$(function () {

    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("#resetbutton").click(function () {
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-gerencia"),
        url_cadastrar = base_url + "/planodeacao/tpa/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            setTimeout(function () {
                $("#enviarbutton").prop("disabled", false);
            }, 8000);
            $("#enviarbutton").prop("disabled", true);
            enviar_ajax("/planodeacao/tpa/transformaremprojeto/format/json", "form#form-gerencia", function (data) {
                if (data.success) {
                    window.location.href = base_url + "/planodeacao/gerencia";
                } else {
                    $("#enviarbutton").prop("disabled", false);
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                }
            });
        }
    });

    var
        vTransformar = true,
        actions = {
            transformarprojeto: {
                form: $("form#form-gerencia"),
                url: base_url + '/planodeacao/tpa/transformaremprojeto/format/json',
                dialog: $('#dialog-transformarprojeto')
            }
        };

    /*xxxxxx TRANSFORMAR EM PROJETO xxxxxx*/
    var options = {
        url: actions.transformarprojeto.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
            }
        }
    };

    actions.transformarprojeto.form.ajaxForm(options);

    actions.transformarprojeto.dialog.dialog({
        autoOpen: false,
        title: 'Gerenciar - Transformar em Projeto',
        width: '800px',
        modal: true,
        open: function (event, ui) {
            $("#enviarbutton").prop("disabled", true);
        },
        close: function (event, ui) {
            setTimeout(function () {
                $("#enviarbutton").prop("disabled", false);
            }, 2500);

        },
        buttons: {
            'Transformar em Projeto': function () {
                actions.transformarprojeto.form.submit();
                actions.transformarprojeto.dialog.dialog("close");
            },
            'Fechar': function () {
                $("#enviarbutton").prop("disabled", false);
                actions.transformarprojeto.dialog.dialog("close");
            }
        }
    });


    $(document.body).on('click', "#enviarbutton", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: actions.transformarprojeto.url,
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                if ($form.valid()) {
                    actions.transformarprojeto.dialog.dialog("open");
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisiÃ§Ã£o',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(".pessoa-button").on('click', function (event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid",
                type: "GET",
                dataType: "html",
                success: function (html) {
                    $(".grid-append").append(html).slideDown('fast');
                }
            });
            $('.pessoa-button')
                .off('click')
                .on('click', function () {
                    var $this = $(this);
                    $(".grid-append").slideDown('fast', function () {
                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                        $this.closest('.control-group').addClass('input-selecionado');
                    });
                });
        }
    });

    $("body").on("focusin", "#vlrorcamentodisponivel", function () {
        $this = $(this);
        if (!$this.data('formatCurrencyAttached')) {
            $this.data('formatCurrencyAttached', true);
            $this.formatCurrency({
                decimalSep: ',',
                thousandsSep: '.',
                digits: 2
            }).trigger('keypress');
        }
    });

    $('#idobjetivo').change(function () {
        fillSelect();
    });

    fillSelect($("#idacao").val());

});


