$(document).ready(function () {
    $.pnotify.defaults.history = false;
    $("#message").hide();
    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-tep"),
        $dialogAssinar = $('#dialog-assinar');

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/projeto/tep/editar/format/json", "form#form-tep", function (data) {
                if (data.success) {
                    //window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.dados.idprojeto;
                }
            });
        }
    });

    $("#accordion2").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });


    $dialogAssinar = $('#dialog-assinar').dialog({
        autoOpen: false,
        title: 'Assinar Termo de Encerramento',
        width: '360px',
        modal: true,
        buttons: {
            'Salvar': function () {
                //console.log($("form#form-assinaDoc").valid());
                if ($("form#form-assinaDoc").valid()) {
                    var form = $('form#form-assinaDoc');
                    var $paramsForm = form.serialize();
                    $.ajax({
                        url: base_url + '/projeto/termoencerramento/autenticarassinatura/format/json',
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: $paramsForm,
                        //processData:false,
                        success: function (data) {
                            if (data.success) {
                                $('#dialog-assinar').dialog('close');
                                $.pnotify(data.msg);
                            } else {
                                $("#message").text(data.msg.text);
                                $("#message").show();
                            }

                        },
                        error: function () {
                            $('#dialog-assinaDoc').dialog('close');
                        }
                    });
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $(document.body).on('click', "a.autenticarassinatura", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            //data: $formEditar.serialize(),
            processData: false,
            success: function (data) {
                $dialog.html(data).dialog('open');
                $("#message").hide();
                $('.mask-cpf').mask("999.999.999-99");
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });

});
