var MODULOEAP = (function ($, Handlebars) {
    var
        eap = {};

    vSalvar = true;
    vEditarEntrega = true;
    eap.projeto = {};
    eap.msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
    eap.tplProjeto = null;
    eap.tplGrupo = null;
    eap.tplEntrega = null;
    eap.tplQuadro = null;
    eap.tplQuadroEntrega = null;
    eap.idProjeto = "#idprojeto";
    eap.formGrupo = "form#ac-grupo";
    eap.formEntrega = "form#ac-entrega";
    eap.formAlterarEntrega = "form#ac-entrega";
    eap.formExcluirEntrega = "form#ac-entrega-excluir";
    eap.formExcluirGrupo = "form#ac-grupo-excluir";
    eap.formEditarGrupo = "form#ac-grupo";
    eap.$dialogGrupo = null;
    eap.$dialogEntrega = null;
    eap.desabilitarEntrega = null;
    eap.urls = {
        urlprincipal: base_url + '/projeto/eap/index/idprojeto/',
        cadastrarGrupo: '/projeto/eap/cadastrar-grupo/format/json',
        editarGrupo: '/projeto/cronograma/editar-grupo/format/json',
        cadastrarEntrega: '/projeto/eap/cadastrar-entrega/format/json',
        alterarEntrega: '/projeto/cronograma/editar-entrega/format/json',
        excluirEntrega: '/projeto/eap/excluir-entrega/format/json',
        excluirGrupo: '/projeto/eap/excluir-grupo/format/json'
    };

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });

    /** No módulo EAP, inabilitar ícone "Entrega"(criação de entrega) enquanto não houver pelo menos um
     * "Grupo" criado.
     */
    eap.desabilitarEntrega = function desabilitarEntrega() {
        var btnDisabled = 0;
        btnDisabled = $("#qtd-grupos").val() == 0 ? true : false;
        $("#btn-cad-cro").attr("disabled", btnDisabled);
        if (btnDisabled == true) {
            $("#btn-cad-cro").load(location.href + " #btn-cad-cro>*", "");
            return false;
        } else {
            return true;
            $("#btn-cad-cro").load(location.href + " #btn-cad-cro>*", "");
        }
    }

    eap.customEvents = function () {

        $("body").delegate(".draggable-entrega", "mouseover", function () {
            $(this).draggable({
                appendTo: "body",
                snap: true,
                cursorAt: {left: 5},
                zIndex: 200,
                revert: "invalid"
            });
        });

        $("body").delegate(".droppable-grupo", "mouseover", function () {
            $(this).droppable({
                accept: ".draggable-entrega",
                tolerance: 'intersect',
                hoverClass: 'ui-state-highlight',
                drop: function (event, ui) {
                    $.ajax({
                        url: base_url + '/projeto/eap/editar-entrega/format/json',
                        dataType: 'json',
                        type: 'POST',
                        async: false,
                        data: {
                            idprojeto: $(eap.idProjeto).val(),
                            idgrupo: $(this).attr('id'),
                            idatividadecronograma: ui.draggable.attr('id')
                        },
                        success: function (data) {
                            $.pnotify(data.msg);
                            window.location.href = eap.urls.urlprincipal + data.msg.idprojeto;
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
            });
        });

        $("body").delegate(".estrutura-analitica", "click", function () {
            $('.grupos-eap').toggle();
        });

        $("body").delegate(".dicionario-eap", "click", function () {
            $('.quadro-dicionario-eap').toggle();
        });

        $("body").delegate("form#ac-entrega, form#ac-grupo", "keypress", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

        $("body").on('openDialog', function (event, btn) {
            var dialog = btn.data('dialog'),
                formAtual = btn.data('form'),
                urlAjax = btn.data('urlajax'),
                urlForm = btn.data('urlform'),
                prefixo = btn.data('prefixo');

            $.ajax({
                url: urlAjax,
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                processData: false,
                success: function (data) {
                    dialog.html(data).dialog('open');
                    $(formAtual).validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function (form) {
                            enviar_ajax(urlForm, formAtual, function (data) {
                                dialog.dialog('close');
                                window.location.href = eap.urls.urlprincipal + $('#idprojeto').val();
                            });
                        }
                    });
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

        $("body").on('click', "a.btn-cadastrar-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.cadastrarGrupo
            ;

            eap.$dialogGrupo.dialog('option', 'title', 'EAP - Cadastrar Grupo');

            $this.data('form', eap.formGrupo),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogGrupo);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-cadastrar-entrega", function (event) {
            event.preventDefault();

            if ($("#qtd-grupos").val() == 0) {
                return false;
            } else {
                vEditarEntrega = false;
                var
                    $this = $(this),
                    urlAjax = $this.attr('href'),
                    urlForm = eap.urls.cadastrarEntrega;

                eap.$dialogEntrega.dialog('option', 'title', 'EAP - Cadastrar Entrega');

                $this.data('form', eap.formEntrega);
                $this.data('urlform', urlForm);
                $this.data('urlajax', urlAjax);
                $this.data('dialog', eap.$dialogEntrega);
                $this.data('prefixo', '#en');

                $("body").trigger('openDialog', [$this]);
            }
        });

        $("body").on('click', "a.excluir-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.excluirEntrega
            ;

            eap.$dialogEntrega.dialog('option', 'title', 'EAP - Excluir Entrega');

            $this.data('form', eap.formExcluirEntrega),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogExcluirEntrega);
            $this.data('prefixo', '#en');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.alterar-entrega", function (event) {
            event.preventDefault();
            vEditarEntrega = true;
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.alterarEntrega
            ;

            eap.$dialogEntrega.dialog('option', 'title', 'EAP - Editar Entrega');

            $this.data('form', eap.formAlterarEntrega);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogEntrega);
            $this.data('prefixo', '#en');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.excluir-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.excluirGrupo
            ;

            eap.$dialogGrupo.dialog('option', 'title', 'EAP - Excluir Grupo');

            $this.data('form', eap.formExcluirGrupo),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogExcluirGrupo);
            $this.data('prefixo', '#gr');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.editar-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.editarGrupo
            ;

            eap.$dialogGrupo.dialog('option', 'title', 'EAP - Editar Grupo');

            $this.data('form', eap.formEditarGrupo);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogEditarGrupo);
            $this.data('prefixo', '#gr');

            $("body").trigger('openDialog', [$this]);
        });

        $(eap.formGrupo).validate({
            errorClass: 'error',
            validClass: 'success',
            submitHandler: function (form) {

            }
        });

    };

    eap.initDialogs = function () {
        eap.$dialogGrupo = $('#dialog-grupo').dialog({
            autoOpen: false,
            title: 'EAP - Cadastrar Grupo',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                $('#dialog-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                $('#dialog-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function () {
                    var idprojeto = "";
                    var nomatividadecronograma = "";
                    var domtipoatividade = "";
                    if (vSalvar) {
                        $("#ac-grupo").validate();
                        if ($("#ac-grupo").valid()) {
                            vSalvar = false;
                            $('#dialog-grupo').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            nomatividadecronograma = $("#ac-grupo").find('input[id="nomatividadecronograma"]').val();
                            domtipoatividade = $("#ac-grupo").find('input[id="domtipoatividade"]').val();
                            idprojeto = $("#ac-grupo").find('input[id="idprojeto"]').val();
                            setTimeout(function () {
                                vSalvar = true;
                                $('#dialog-grupo').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }, 3000);
                            $.ajax({
                                url: base_url + '/projeto/cronograma/cadastrar-grupo/format/json/',
                                dataType: 'json',
                                type: 'POST',
                                data: {
                                    'idprojeto': idprojeto,
                                    'nomatividadecronograma': nomatividadecronograma,
                                    'domtipoatividade': domtipoatividade,
                                },
                                success: function (data) {
                                    $.pnotify({
                                        text: data.msg.text,
                                        type: 'success',
                                        hide: true
                                    });
                                    window.location.href = eap.urls.urlprincipal + idprojeto;
                                    return;
                                },
                                error: function () {
                                    $.pnotify({
                                        text: eap.msgerror,
                                        type: 'error',
                                        hide: false
                                    });
                                }
                            });
                            $(this).dialog('close');
                        }
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        eap.$dialogEntrega = $('#dialog-entrega').dialog({
            autoOpen: false,
            title: 'EAP - Cadastrar Entrega',
            width: '810px',
            modal: true,
            close: function (event, ui) {
                $('#dialog-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                $('#dialog-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function () {
                    if (vSalvar) {
                        var idprojeto = "";
                        var param = "";
                        if (vSalvar) {
                            $("#ac-entrega").validate();
                            if ($("#ac-entrega").valid()) {
                                vSalvar = false;
                                if (vEditarEntrega) {
                                    vUrlEntrega = base_url + '/projeto/cronograma/editar-entrega/format/json/';
                                } else {
                                    vUrlEntrega = base_url + '/projeto/cronograma/cadastrar-entrega/format/json/';
                                }
                                $('#dialog-entrega').parent().find("button").each(function () {
                                    $(this).attr('disabled', true);
                                });
                                idprojeto = $("#idprojeto").val();
                                param = $(eap.formEntrega).serialize();
                                setTimeout(function () {
                                    vSalvar = true;
                                    $('#dialog-entrega').parent().find("button").each(function () {
                                        $(this).attr('disabled', false);
                                    });
                                }, 3000);
                                $.ajax({
                                    url: vUrlEntrega,
                                    dataType: 'json',
                                    type: 'POST',
                                    data: param,
                                    success: function (data) {
                                        $.pnotify({
                                            text: data.msg.text,
                                            type: 'success',
                                            hide: true
                                        });
                                        window.location.href = eap.urls.urlprincipal + idprojeto;

                                        return;
                                    },
                                    error: function () {
                                        $.pnotify({
                                            text: eap.msgerror,
                                            type: 'error',
                                            hide: false
                                        });
                                    }
                                });
                                $(this).dialog('close');
                            }
                        }
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        eap.$dialogExcluirEntrega = $('#dialog-excluir-entrega').dialog({
            autoOpen: false,
            title: 'EAP - Excluir Entrega',
            width: '900px',
            modal: true,
            buttons: {
                'Excluir': function () {
                    $(eap.formExcluirEntrega).submit();

                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        eap.$dialogExcluirGrupo = $('#dialog-excluir-grupo').dialog({
            autoOpen: false,
            title: 'EAP - Excluir Grupo',
            width: '900px',
            modal: true,
            buttons: {
                'Excluir': function () {
                    $(eap.formExcluirGrupo).submit();
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        eap.$dialogEditarGrupo = $('#dialog-editar-grupo').dialog({
            autoOpen: false,
            title: 'EAP - Editar Grupo',
            width: '900px',
            modal: true,
            close: function (event, ui) {
                $('#dialog-editar-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                $('#dialog-editar-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function () {
                    var idprojeto = "";
                    var nomatividadecronograma = "";
                    var idatividadecronograma = "";
                    var domtipoatividade = "";
                    if (vSalvar) {
                        $("#ac-grupo").validate();
                        if ($("#ac-grupo").valid()) {
                            vSalvar = false;
                            $('#dialog-grupo').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            idprojeto = $("#ac-grupo").find('input[id="idprojeto"]').val();
                            idatividadecronograma = $("#ac-grupo").find('input[id="idatividadecronograma"]').val();
                            nomatividadecronograma = $("#ac-grupo").find('input[id="nomatividadecronograma"]').val();
                            domtipoatividade = $("#ac-grupo").find('input[id="domtipoatividade"]').val();
                            setTimeout(function () {
                                vSalvar = true;
                                $('#dialog-grupo').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }, 3000);
                            $.ajax({
                                url: base_url + '/projeto/cronograma/editar-grupo/format/json/',
                                dataType: 'json',
                                type: 'POST',
                                data: {
                                    'idprojeto': idprojeto,
                                    'idatividadecronograma': idatividadecronograma,
                                    'nomatividadecronograma': nomatividadecronograma,
                                    'domtipoatividade': domtipoatividade,
                                },
                                success: function (data) {
                                    $.pnotify({
                                        text: data.msg.text,
                                        type: 'success',
                                        hide: true
                                    });
                                    window.location.href = eap.urls.urlprincipal + idprojeto;
                                    return;
                                },
                                error: function () {
                                    $.pnotify({
                                        text: eap.msgerror,
                                        type: 'error',
                                        hide: false
                                    });
                                }
                            });
                            $(this).dialog('close');
                        }
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

    };
    eap.reSizeComponents = function reSizeComponents() {
        var widthRegionCenter = $('.region-center').width();
        $('.grupos-eap').width(widthRegionCenter - 5);
        $('.grupos-eap').height(350);
        $('.quadro-dicionario-eap').width(widthRegionCenter - 5);
        $('.quadro-dicionario-eap').height(355);
    }


    eap.init = function () {
        eap.reSizeComponents();
        eap.initDialogs();
        eap.customEvents();
    };

    return eap;


}(jQuery, Handlebars));

