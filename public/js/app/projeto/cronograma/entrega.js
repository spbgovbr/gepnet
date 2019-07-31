//XXXXXXXXXX ENTREGA XXXXXXXXXX
CRONOGRAMA.entrega = (function ($) {
    var o = {};

    vSalvar = true;
    vExcluir = true;
    o.msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
    o.msgerroacesso = 'Acesso negado para essa ação';
    o.formEntrega = "form#ac-entrega";
    o.formEntregaExcluir = "form#ac-entrega-excluir";
    o.$dialogEntrega = null;
    o.$dialogEntregaExcluir = null;
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.idProjeto = '#idprojeto';
    o.urls = {
        cadastrar: '/projeto/cronograma/cadastrar-entrega/format/json',
        editar: '/projeto/cronograma/editar-entrega/format/json',
        excluir: '/projeto/cronograma/excluir-entrega/format/json'
    };

    o.initDialogs = function () {
        o.$dialogEntrega = $('#dialog-entrega').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Entrega',
            width: '810px',
            modal: true,
            close: function (event, ui) {
                vSalvar = true;
                $('#dialog-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                vSalvar = true;
                $('#dialog-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function (event) {
                    event.preventDefault();
                    if (vSalvar) {
                        vSalvar = false;
                        $('#dialog-entrega').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        /*setTimeout(function () {
                         vSalvar = true;
                         $('#dialog-entrega').parent().find("button").each(function () {
                         $(this).attr('disabled', false);
                         });
                         }, 15000);*/
                        $(o.formEntrega).submit();

                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-entrega').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 300);
                        $(document).ajaxComplete(function (event, xhr) {
                            if (JSON.parse(xhr.responseText).success) {
                                window.location.href = window.location.href;
                            }
                        });
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        o.$dialogEntregaExcluir = $('#dialog-excluir-entrega').dialog({
            autoOpen: false,
            title: 'Cronograma - Excluir Entrega',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            open: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Excluir': function (event) {
                    event.preventDefault();
                    if (vExcluir) {
                        vExcluir = false;
                        $('#dialog-excluir-entrega').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        /*setTimeout(function () {
                         vExcluir = true;
                         $('#dialog-excluir-entrega').parent().find("button").each(function () {
                         $(this).attr('disabled', false);
                         });
                         }, 15000);/**/
                        $(CRONOGRAMA.allToolBar).hide();
                        $(o.formEntregaExcluir).submit();

                        $(document).ajaxComplete(function (event, xhr) {
                            if (JSON.parse(xhr.responseText).success) {
                                window.location.href = window.location.href;
                            }
                        });
                        //window.location.href = base_url + "/projeto/cronograma/index/idprojeto/" + $("#idprojeto").val();
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });

        //#############################################################
        o.alterarVisibilidadeEntrega = function (event) {
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
                        //$.pnotify(data.msg.text);
                        //$.pnotify(data.msg);
                        //$.pnotify({
                        //    text: data.msg.text,
                        //    type: 'error',
                        //    hide: true
                        //});
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
        $("body").on('click', "a.btn-cadastrar-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val(),
                urlAjax = $this.attr('href') + '/idgrupo/' + idcronogramaatividade,
                urlForm = o.urls.cadastrar
            ;

            o.$dialogEntrega.dialog('option', 'title', 'Cronograma - Cadastrar Entrega');

            $this.data('form', o.formEntrega),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntrega);
            $this.data('prefixo', '#en');
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-editar-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.editar,
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val()
            ;

            idgrupo = $(o.itemCronogramaSelecionado).attr('data-pai');
            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade + '/idgrupo/' + idgrupo;

            o.$dialogEntrega.dialog('option', 'title', 'Cronograma - Editar Entrega');

            $this.data('form', o.formEntrega),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntrega);
            $this.data('prefixo', '#en');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-excluir-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.excluir,
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val()
            ;

            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade;

            o.$dialogEntregaExcluir.dialog('option', 'title', 'Cronograma - Excluir Entrega');

            $this.data('form', o.formEntregaExcluir),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntregaExcluir);
            $this.data('prefixo', '#en');

            $("body").trigger('openDialog', [$this]);
        });

        $('body').on('alterarVisibilidadeEntrega', function (event) {
            o.alterarVisibilidadeEntrega(event);
        });

        $("body").on('click', "a.btn-clonar-entrega", function (event) {
            event.preventDefault();
            $.ajax({
                url: base_url + '/projeto/cronograma/clonar-entrega/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'idprojeto': $(o.idProjeto).val(),
                    'idatividadecronograma': $(o.itemCronogramaSelecionado).val(),
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

        $("body").on('click', ".nome-entrega", function (event) {
            $(this).parent().parent().find('.container-atividade').toggle();
        });

        $("body").on('click', ".btn-ocultar-atividades", function (event) {
            event.preventDefault();
            $this = $(this);
            $("body").trigger('alterarVisibilidadeEntrega', [$this]);
        });

        $("body").delegate("form#ac-entrega", "keypress", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

    };

    o.init = function () {
        o.initDialogs();
        o.events();
    };

    return o;
}(jQuery));


