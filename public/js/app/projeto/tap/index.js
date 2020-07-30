$(document).ready(function () {
    $('ul').find('a').on("click", function (e) {
        e.preventDefault();
    });
    $("ul").find('li').removeClass('disabled').addClass('enabled');
    $('ul').find('a').unbind('click');

    $("#message").hide();


    $.pnotify.defaults.history = false;

    $('.datepicker').mask('99/99/9999');

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("#resetbutton").click(function () {
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-gerencia"),        
        url_cadastrar = base_url + "/projeto/tap/add/format/json";

    $form.on('submit', function (e) {
        var $return = false;
        var options = {
            url: url_cadastrar,
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                if (typeof data.msg.text != 'string') {
                    $.formErrors(data.msg.text);
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                    return;
                }
                if (data.success) {
                    $return = true;
                    window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.dados.idprojeto;
                }
                $.pnotify(data.msg);
            },
            error: function (data) {
                $return = false;
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }

        };
        $form.ajaxSubmit(options);
        return $return;
    });

    vExcluir = true;
    vRestaurar = true;
    vClonar = true;
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            editar: {
                form: $("form#form-gerencia"),
                url: base_url + '/projeto/gerencia/editar/format/json',
                dialog: $('#dialog-editar')
            },
            configurar: {
                form: $("form#form-configurar"),
                url: base_url + '/projeto/gerencia/configurar/format/json',
                dialog: $('#dialog-configurar')
            },
            arquivo: {
                form: $("form#form-escritorio-arquivo"),
                url: base_url + '/cadastro/escritorio/editar-arquivo/format/json',
                dialog: $('#dialog-arquivo')
            },
            excluir: {
                form: $("form#form-escritorio-excluir"),
                url: base_url + '/cadastro/escritorio/excluir/format/json',
                dialog: $('#dialog-excluir')
            },
            desbloquear: {
                form: $("form#form-desbloqueio"),
                url: base_url + '/projeto/gerencia/desbloquear/format/json',
                dialog: $('#dialog-desbloquear')
            },
            clonarprojeto: {
                form: $("form#form-clonarprojeto"),
                url: base_url + '/projeto/gerencia/clonarprojeto/format/json',
                dialog: $('#dialog-clonarprojeto')
            },
            excluirprojeto: {
                form: $("form#form-excluirprojeto"),
                url: base_url + '/projeto/gerencia/excluirprojeto/format/json',
                dialog: $('#dialog-excluirprojeto')
            },
            restaurarprojeto: {
                form: $("form#form-restaurarprojeto"),
                url: base_url + '/projeto/gerencia/restaurarprojeto/format/json',
                dialog: $('#dialog-restaurarprojeto'),
            },                    
        };

    /*xxxxxx EXCLUIR PROJETO xxxxxx*/
    var options = {
        url: actions.excluirprojeto.url,
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
                recarregarBotoes();
            }
        }
    };

    actions.excluirprojeto.form.ajaxForm(options);

    actions.excluirprojeto.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Excluir Projeto',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vRestaurar = true;
            $('#dialog-excluirprojeto').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.excluirprojeto.dialog.empty();
            recarregarBotoes();
        },
        buttons: {
            'Excluir': function () {
                if (vRestaurar) {
                    vRestaurar = false;
                    $('#dialog-excluirprojeto').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });

                    $.ajax({
                        url: actions.excluirprojeto.url,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idprojeto': $('#idprojeto').val()
                        },
                        success: function (data) {
                            $.pnotify(data.msg);
                            if (data.success) {
                                recarregarBotoes();
                            }
                            $('#dialog-excluirprojeto').dialog('close');
                        },
                        error: function () {
                            $('#dialog-excluirprojeto').dialog('close');
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "#excluir", function (event) {
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
            processData: false,
            success: function (data) {
                actions.excluirprojeto.dialog.html(data).dialog('open');
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

    /*xxxxxx RECUPERAR PROJETO xxxxxx*/
    var options = {
        url: actions.restaurarprojeto.url,
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
                recarregarBotoes();
            }
        }
    };

    actions.restaurarprojeto.form.ajaxForm(options);

    actions.restaurarprojeto.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Recuperar Projeto',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vRestaurar = true;
            $('#dialog-restaurarprojeto').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.restaurarprojeto.dialog.empty();
            recarregarBotoes();
        },
        buttons: {
            'Recuperar': function () {
                if (vRestaurar) {
                    vRestaurar = false;
                    $('#dialog-restaurarprojeto').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });

                    $.ajax({
                        url: actions.restaurarprojeto.url,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idprojeto': $('#idprojeto').val()
                        },
                        success: function (data) {
                            $.pnotify(data.msg);
                            if (data.success) {
                                recarregarBotoes();
                            }
                            $('#dialog-restaurarprojeto').dialog('close');
                        },
                        error: function () {
                            $('#dialog-restaurarprojeto').dialog('close');
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "#restaurar", function (event) {
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
            processData: false,
            success: function (data) {
                actions.restaurarprojeto.dialog.html(data).dialog('open');
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

    
});

function visualizar($div) {
    $('.informacoes').hide();
    $('#' + $div).show();
}

function recarregarBotoes() {
    $("#barrafer").load(location.href + " #barrafer>*", "");
}

$(document).on("click", ".accordion-heading", function () {
    if ($('.accordion-toggle').hasClass("collapsed")) {
        $("#img").attr("class", "icon-plus");
    } else {
        $("#img").attr("class", "icon-minus");
    }
});
