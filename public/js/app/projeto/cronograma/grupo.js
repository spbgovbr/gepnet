//XXXXXXXXXX GRUPO XXXXXXXXXX
CRONOGRAMA.grupo = (function ($) {
    var o = {};
    vEditarGrupo = true;
    vExcluir = true;
    vSalvar = true;
    o.msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
    o.msgerroacesso = 'Acesso negado para essa ação';
    o.formGrupo = "form#ac-grupo";
    o.formGrupoExcluir = "form#ac-grupo-excluir";
    o.$dialogGrupo = null;
    o.$dialogGrupoExcluir = null;
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.idProjeto = '#idprojeto';
    o.urls = {
        cadastrar: '/projeto/cronograma/cadastrar-grupo/format/json',
        editar: '/projeto/cronograma/editar-grupo/format/json',
        excluir: '/projeto/cronograma/excluir-grupo/format/json'
    };

    o.initDialogs = function () {
        o.$dialogGrupo = $('#dialog-grupo').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Grupo',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                vSalvar = true;
                $('#dialog-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                vSalvar = true;
                $('#dialog-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function (event) {
                    event.preventDefault();
                    if (vSalvar) {
                        vSalvar = false;
                        $('#dialog-grupo').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        var idprojeto = $("#idprojeto").val();
                        var param = $(o.formGrupo).serialize();
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-grupo').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 8000);
                        if (vEditarGrupo) {
                            var vUrlGrupo = base_url + o.urls.editar;
                        } else {
                            var vUrlGrupo = base_url + o.urls.cadastrar;
                        }
                        $.ajax({
                            url: vUrlGrupo,
                            dataType: 'json',
                            type: 'POST',
                            data: param,
                            success: function (data) {
                                $.pnotify({
                                    text: data.msg.text,
                                    type: 'success',
                                    hide: false
                                });
                                window.location.href = window.location.href;
                                return;
                            },
                            error: function () {
                                $.pnotify({
                                    text: o.msgerroacesso,
                                    type: 'error',
                                    hide: false
                                });
                            }
                        });
                    }
                    $(this).dialog('close');
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        o.$dialogGrupoExcluir = $('#dialog-excluir-grupo').dialog({
            autoOpen: false,
            title: 'Cronograma - Excluir Grupo',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Excluir': function (event) {
                    event.preventDefault();
                    if (vExcluir) {
                        vExcluir = false;
                        $('#dialog-excluir-grupo').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        var idprojeto = $("#idprojeto").val();
                        var idatividadecronograma = $("input:checked").val();
                        $.ajax({
                            url: base_url + '/projeto/cronograma/excluir-grupo/format/json/',
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                idprojeto: idprojeto,
                                idatividadecronograma: idatividadecronograma
                            },
                            success: function (data) {
                                $.pnotify({
                                    text: data.msg.text,
                                    type: 'success',
                                    hide: false
                                });
                                $(CRONOGRAMA.allToolBar).hide();
                                window.location.href = window.location.href;
                                return;
                            },
                            error: function () {
                                $.pnotify({
                                    text: o.msgerroacesso,
                                    type: 'error',
                                    hide: false
                                });
                            }
                        });
                    }
                    $(this).dialog('close');
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });
        //#############################################################
        o.alterarVisibilidadeGrupo = function (event) {
            event.preventDefault();
            var a = {}, dados, flashowhide;
            dados = $(o.itemCronogramaSelecionado).data('dados');
            a.flashowhide = dados.flashowhide;
            a.idprojeto = $("#idprojeto").val();
            a.idatividaselecionada = $(o.itemCronogramaSelecionado).val();
            if (a.idatividadecronograma !== '') {
                $.ajax({
                    url: base_url + '/projeto/cronograma/alterar-visibilidade/format/json',
                    dataType: 'json',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        idprojeto: a.idprojeto,
                        idatividadecronograma: a.idatividaselecionada,
                        flashowhide: a.flashowhide
                    },
                    success: function (data) {
                        //CRONOGRAMA.retornaProjeto();
                        $.pnotify(data.msg);
                    },
                    error: function () {
                        $.pnotify({
                            text: msgerror,
                            type: 'error',
                            hide: false
                        });
                    }

                });
            }
        };
        //#############################################################
    };

    o.events = function () {
        $("body").on('click', "a.btn-cadastrar-grupo", function (event) {
            event.preventDefault();
            vEditarGrupo = false;
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = o.urls.cadastrar
            ;

            o.$dialogGrupo.dialog('option', 'title', 'Cronograma - Cadastrar Grupo');

            $this.data('form', o.formGrupo);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupo);
            $this.data('prefixo', '#gr');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-editar-grupo", function (event) {
            event.preventDefault();
            vEditarGrupo = true;
            var
                $this = $(this),
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val(),
                urlForm = o.urls.editar
            ;

            o.$dialogGrupo.dialog('option', 'title', 'Cronograma - Editar Grupo');

            $this.data('form', o.formGrupo);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupo);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-excluir-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.excluir,
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val()
            ;

            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade;

            o.$dialogGrupoExcluir.dialog('option', 'title', 'Cronograma - Excluir Grupo');

            $this.data('form', o.formGrupo);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupoExcluir);
            $this.data('prefixo', '#gr');

            $("body").trigger('openDialog', [$this]);

        });

        $('body').on('alterarVisibilidadeGrupo', function (event) {
            o.alterarVisibilidadeGrupo(event);
        });

        $("body").on('click', "a.btn-clonar-grupo", function (event) {
            event.preventDefault();
            $.ajax({
                url: base_url + '/projeto/cronograma/clonar-grupo/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'idprojeto': $(o.idProjeto).val(),
                    'idatividadecronograma': $(o.itemCronogramaSelecionado).val()
                },
                success: function (data) {
                    $.pnotify(data.msg);
                    window.location.href = base_url + "/projeto/cronograma/index/idprojeto/" + $("#idprojeto").val();
                },
                error: function () {
                    $.pnotify({
                        text: o.msgerroacesso,
                        type: 'error',
                        hide: false
                    });
                }
            });
        });

        $("body").on('click', ".nome-grupo", function (event) {
            $(this).parent().parent().find('.container-entrega').toggle();
        });

        $("body").on('click', ".btn-ocultar-entregas", function (event) {
            event.preventDefault();
            $this = $(this);
            $("body").trigger('alterarVisibilidadeGrupo', [$this]);
        });

        $("body").delegate("form#ac-grupo", "keypress", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

    };


    o.init = function () {
        o.initDialogs();
        o.events();
        //CRONOGRAMA.retornaProjeto();
    };

    return o;
}(jQuery));
