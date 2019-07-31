function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {
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
                url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            editar: {
                form: $("form#form-gerencia"),
                url: base_url + '/planodeacao/gerencia/editar/format/json',
                dialog: $('#dialog-editar')
            },
            configurar: {
                form: $("form#form-configurar"),
                url: base_url + '/planodeacao/gerencia/configurar/format/json',
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
                url: base_url + '/planodeacao/gerencia/desbloquear/format/json',
                dialog: $('#dialog-desbloquear')
            },
            clonarplanodeacao: {
                form: $("form#form-clonarplanodeacao"),
                url: base_url + '/planodeacao/gerencia/clonarplanodeacao/format/json',
                dialog: $('#dialog-clonarplanodeacao')
            },
            excluirplanodeacao: {
                form: $("form#form-excluirplanodeacao"),
                url: base_url + '/planodeacao/gerencia/excluirplanodeacao/format/json',
                dialog: $('#dialog-excluirplanodeacao')
            },
            restaurarplanodeacao: {
                form: $("form#form-restaurarplanodeacao"),
                url: base_url + '/planodeacao/gerencia/restaurarplanodeacao/format/json',
                dialog: $('#dialog-restaurarplanodeacao')
            }
        };

    $(".select2").select2();

    //Reset button
    $("#resetbutton").click(function () {
        $(".select2").select2('data', null);
        $("#nomprograma").select2('data', null);
        $("#domstatusprojeto").select2('data', null);
        $("#idescritorio").select2('data', null);
    });
    /*xxxxxx CLONAR PLANO DE ACAO xxxxxx*/
    var options = {
        url: actions.clonarplanodeacao.url,
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
                grid.setGridParam({
                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
            }
        }
    };

    actions.clonarplanodeacao.form.ajaxForm(options);

    actions.clonarplanodeacao.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Clonar Plano de Ação',
        width: '800px',
        modal: false,
        open: function (event, ui) {
            var form = $("form#form-clonarplanodeacao");
            $(".pessoa-button").on('click', function (event) {
                event.preventDefault();
                $('#gridpessoa').attr('disabled', false);
                $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                $(this).closest('.control-group').addClass('input-selecionado');
                if ($("table#list-grid-pessoa").length <= 0) {
                    $.ajax({
                        url: base_url + "/cadastro/pessoa/grid",
                        type: "GET",
                        dataType: "html",
                        success: function (html) {
                            $(".grid-append").append(html).slideDown('fast');
                            $('#gridpessoa').attr('disabled', false);
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
            /**/
        },
        close: function (event, ui) {
            vClonar = true;
            $('#dialog-clonarplanodeacao').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.clonarplanodeacao.dialog.empty();
            grid.setGridParam({
                url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            'Clonar': function () {
                if (vClonar) {
                    $("form#form-clonarplanodeacao").validate();
                    var form = $("form#form-clonarplanodeacao");
                    form.validate();
                    if (form.valid()) {
                        vClonar = false;
                        $('#dialog-clonarplanodeacao').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        $.ajax({
                            url: actions.clonarplanodeacao.url,
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                'idplanodeacao': $('#idplanodeacao').val(),
                                'nomprojeto': form.parent().find('input#nomprojeto').val(),
                                'nomproponente': form.parent().find('input#nomproponente').val(),
                                'idescritorio': form.parent().find('select#idescritorio').val(),
                                'ano': form.parent().find('input#ano').val(),
                            },
                            success: function (data) {
                                $.pnotify(data.msg);
                                if (data.success) {
                                    grid.setGridParam({
                                        url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                                        page: 1
                                    }).trigger("reloadGrid");
                                    resizeGrid();
                                }
                                $('#dialog-clonarplanodeacao').dialog('close');
                            },
                            error: function () {
                                $('#dialog-clonarplanodeacao').dialog('close');
                                $.pnotify({
                                    text: 'Falha ao enviar a requisição',
                                    type: 'error',
                                    hide: false
                                });
                            }
                        });
                    } else {
                        vClonar = true;
                        $('#dialog-clonarplanodeacao').parent().find("button").each(function () {
                            $(this).attr('disabled', false);
                        });
                    }
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.clonarplanodeacao", function (event) {
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
                actions.clonarplanodeacao.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
        /**/
    });
    /*xxxxxx EXCLUIR PLANO DE ACAO xxxxxx*/
    var options = {
        url: actions.excluirplanodeacao.url,
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
                grid.setGridParam({
                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
            }
        }
    };

    actions.excluirplanodeacao.form.ajaxForm(options);

    actions.excluirplanodeacao.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Excluir Plano de Ação',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vRestaurar = true;
            $('#dialog-excluirplanodeacao').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });

            actions.excluirplanodeacao.dialog.empty();
            grid.setGridParam({
                url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            'Excluir': function () {
                if (vRestaurar) {
                    vRestaurar = false;
                    $('#dialog-excluirplanodeacao').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    $.ajax({
                        url: actions.excluirplanodeacao.url,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idplanodeacao': $('#idplanodeacao').val()
                        },
                        success: function (data) {
                            if (data.success) {
                                grid.setGridParam({
                                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                                    page: 1
                                }).trigger("reloadGrid");
                                resizeGrid();
                                $.pnotify(data.msg);
                            }
                            $('#dialog-excluirplanodeacao').dialog('close');
                        },
                        error: function () {
                            $('#dialog-excluirplanodeacao').dialog('close');
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                    /**/
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluirplanodeacao", function (event) {
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
                actions.excluirplanodeacao.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
        /**/
    });

    /*xxxxxx RESTAURAR PLANO DE ACAO xxxxxx*/
    var options = {
        url: actions.restaurarplanodeacao.url,
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
                grid.setGridParam({
                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
            }
        }
    };

    actions.restaurarplanodeacao.form.ajaxForm(options);

    actions.restaurarplanodeacao.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Restaurar Plano de Ação',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vRestaurar = true;
            $('#dialog-restaurarplanodeacao').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            grid.trigger('reloadGrid');
            actions.restaurarplanodeacao.dialog.empty();
            grid.setGridParam({
                url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            'Restaurar': function () {
                if (vRestaurar) {
                    vRestaurar = false;
                    $('#dialog-restaurarplanodeacao').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });

                    $.ajax({
                        url: actions.restaurarplanodeacao.url,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idplanodeacao': $('#idplanodeacao').val()
                        },
                        success: function (data) {
                            if (data.success) {
                                grid.setGridParam({
                                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                                    page: 1
                                }).trigger("reloadGrid");
                                resizeGrid();
                                $.pnotify(data.msg);
                            }
                            $('#dialog-restaurarplanodeacao').dialog('close');
                        },
                        error: function () {
                            $('#dialog-restaurarplanodeacao').dialog('close');
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                    /**/
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.restaurarplanodeacao", function (event) {
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
                actions.restaurarplanodeacao.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
        /**/
    });

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    var options = {
        url: actions.editar.url,
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
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };

    actions.editar.form.ajaxForm(options);

    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Editar',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-gerencia').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar_", function (event) {
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

                actions.editar.dialog.html(data).dialog('open');
                $("#idtipodocumento").select2();
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
                });
                $("#accordion").accordion();
                $('form#form-gerencia').validate();

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

    /*xxxxxxxxxx DETALHAR xxxxxxxxxx*/

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Escritorio - Detalhar',
        width: '810px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function (event) {
        //event.preventDefault();
        var
            $this = $(this);
    });

    /*xxxxxxxxxx ARQUIVO xxxxxxxxxx*/
    var optionsArquivo = {
        url: actions.arquivo.url,
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
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };
    actions.arquivo.form.ajaxForm(optionsArquivo);

    actions.arquivo.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Editar arquivo',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.arquivo.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                console.log('submit');
                $('form#form-documento-arquivo').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.arquivo, a.btn_editar", function (event) {
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
                actions.arquivo.dialog.html(data).dialog('open');
                $('form#form-documento-arquivo').validate();

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

    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    var optionsExcluir = {
        url: actions.excluir.url,
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
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };

    actions.excluir.form.ajaxForm(optionsExcluir);

    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Excluir',
        width: '810px',
        modal: true,
        buttons: {
            'Excluir': function () {
                $('form#form-documento-excluir').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluir", function (event) {
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
                actions.excluir.dialog.html(data).dialog('open');
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

    $(document.body).on('click', "a.desbloquear", function (event) {
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
            //processData: false,
            success: function (data) {
                actions.desbloquear.dialog.html(data).dialog('open');
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

    actions.desbloquear.dialog.dialog({
        autoOpen: false,
        title: 'Desbloquear Plano de Ação',
        width: '810px',
        modal: true,
        buttons: {
            'Salvar': function () {
                $.ajax({
                    url: base_url + '/planodeacao/gerencia/desbloquear/format/json',
                    dataType: 'html',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        'idplanodeacao': $('#idplanodeacao').val(),
                        'desjustificativa': $('#desjustificativa').val()
                    },
                    //processData: false,
                    success: function (data) {
//                        actions.desbloquear.dialog.html(data).dialog('open');
                        $.pnotify({
                            text: 'Plano de Ação desbloqueado com sucesso.',
                            type: 'success',
                            hide: true
                        });
                        grid.trigger("reloadGrid");
                    },
                    error: function () {
                        $.pnotify({
                            text: 'Falha ao enviar a requisição',
                            type: 'error',
                            hide: false
                        });
                    }
                });
                $(this).dialog('close');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    function formatadorSituacao(cellvalue, options, rowObject) {
        var situacao = rowObject[15];
        return situacao;

    }

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/planodeacao/tpa/informacoesiniciais',
                imprimir_plano: base_url + '/planodeacao/planoplanodeacao/imprimir',
                imprimir_tpa: base_url + '/planodeacao/tpa/imprimir',
                arquivo: base_url + '/planodeacao/gerencia/editar-arquivo',
                cronograma: base_url + '/planodeacao/cronograma/index',
                planodeacao: base_url + '/planodeacao/tpa/index',
                desbloqueio: base_url + '/planodeacao/gerencia/desbloquear',
                configurar: base_url + '/planodeacao/gerencia/configurar',
                clonarplanodeacao: base_url + '/planodeacao/gerencia/clonarplanodeacao',
                excluirplanodeacao: base_url + '/planodeacao/gerencia/excluirplanodeacao',
                restaurarplanodeacao: base_url + '/planodeacao/gerencia/restaurarplanodeacao'
            };

        params = '/idplanodeacao/' + r[16];

        $return = '<a class="btn actionfrm editar" title="Gerenciar Plano de Ação" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-wrench"></i></a>' +
            '<a target="_blank" class="btn actionfrm detalhar" title="Imprimir Plano de Ação" data-id="' + cellvalue + '" href="' + url.imprimir_tpa + params + '"><i class="icon-print"></i></a>' +
            '<a class="btn actionfrm clonarplanodeacao" title="Clonar Plano de Ação" data-id="' + cellvalue + '" href="' + url.clonarplanodeacao + params + '"><i class="icon-random"></i></a>' +
            '<a class="btn actionfrm ' + (r[16] == 8 ? 'restaurarplanodeacao' : 'excluirplanodeacao') + '" title="'
            + (r[16] == 8 ? 'Recuperar' : 'Excluir') + ' Plano de Ação" data-id="' + cellvalue + '" href="' + (r[16] == 8 ? url.restaurarplanodeacao : url.excluirplanodeacao) + params + '"><i class="' + (r[16] == 8 ? 'icon-repeat' : 'icon-trash') + '" ></i></a>';

        return $return;

    }

    function formatadorLinkExclui(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                exclui: base_url + '/planodeacao/tpa/informacoesiniciais'
            };
        params = '/idplanodeacao/' + r[16];
        var exclui = r[16];
        if (exclui) {
            console.log('true');
            console.log(exclui);
            $return = '<a class="btn btn-success permissao-toggle" title="Revogar" data-id="' + cellvalue + '" href="' + url.exclui + params + '"><i class="icon-ok icon-white "></i></a>\n';
        } else {
            $return = '<a class="btn btn-danger permissao-toggle" title="Conceder" data-id="' + cellvalue + '" href="' + url.exclui + params + '"><i class="icon-off icon-white "></i></a>\n';
            console.log('false');
            console.log(exclui);
        }


        return $return;

    }

    function formatadorImgPrazo(cellvalue, options, rowObject) {

        var retorno = '-';
        /*
         * rowObject[12] => prazo
         * rowObject[18] => criterio farol
         * */
        if (rowObject[12] >= rowObject[18]) {
            var retorno = '<span class="badge badge-important" title=' + rowObject[12] + '>P</span>';
        } else if (rowObject[12] > 0) {
            var retorno = '<span class="badge badge-warning" title=' + rowObject[12] + '>P</span>';
        } else {
            var retorno = '<span class="badge badge-success" title=' + rowObject[12] + '>P</span>';
        }

        if (rowObject[12] === "-")
            return rowObject[12];

        return retorno;
    }

    function formatadorImgRisco(cellvalue, options, rowObject) {
        var retorno = '-';

        if (rowObject[13] === '1') {
            var retorno = '<span class="badge badge-success" title="Baixo">R</span>';
        } else if (rowObject[13] === '2') {
            var retorno = '<span class="badge badge-warning" title="Médio">R</span>';
        } else if (rowObject[13] === '3') {
            var retorno = '<span class="badge badge-important" title="Alto">R</span>';
        }

        return retorno;
    }

    colNames = ['Programa', 'Plano de Ação', 'Unidade Executora', 'Responsável', 'Codigo', 'Publicado', 'Início Previsto', 'Término Previsto', 'Termino Tendencia', 'Previsto', 'Concluído', 'Atraso', 'Prazo', 'Risco', 'Últim. Acompanhamento', 'Situação', 'Operações'];
    colModel = [
        {
            name: 'nomprograma',
            index: 'nomprograma',
            align: 'center',
            width: 50,
            hidden: true,
            search: false
        }, {
            name: 'nomprojeto',
            index: 'nomprojeto',
            align: 'center',
            width: 50,
            hidden: false,
            search: false
        }, {
            name: 'nomsetor',
            index: 'nomsetor',
            align: 'center',
            width: 35,
            hidden: false,
            search: false,
            //formatter: formatadorSituacao
        }, {
            name: 'noproponente',
            index: 'noproponente',
            align: 'center',
            width: 40,
            hidden: false,
            search: false
        }, {
            name: 'nomcodigo',
            index: 'nomcodigo',
            align: 'center',
            width: 20,
            search: true,
            hidden: false
        }, {
            name: 'flapublicado',
            index: 'flapublicado',
            align: 'center',
            width: 13,
            search: true,
            hidden: true,
            //formatter: formatadorSituacao
        }, {
            name: 'datinicio',
            index: 'datinicio',
            align: 'center',
            width: 15,
            hidden: false,
            search: true
        }, {
            name: 'datfim',
            index: 'datfim',
            align: 'center',
            hidden: false,
            width: 15,
            search: true
        }, {
            name: 'datfimplano',
            index: 'datfimplano',
            align: 'center',
            width: 15,
            hidden: true,
            search: false
        }, {
            name: 'previsto',
            index: 'previsto',
            align: 'center',
            hidden: true,
            width: 15,
            search: true
        }, {
            name: 'concluido',
            index: 'concluido',
            width: 15,
            hidden: true,
            search: false,
            sortable: false
        }, {
            name: 'atraso',
            index: 'atraso',
            width: 15,
            hidden: true,
            search: false,
            sortable: false
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 11,
            align: 'center',
            hidden: true,
            search: false,
            sortable: false,
            formatter: formatadorImgPrazo
        }, {
            name: 'Risco',
            index: 'Risco',
            width: 11,
            align: 'center',
            hidden: true,
            search: false,
            sortable: false,
            formatter: formatadorImgRisco
        }, {
            name: 'ultimoacompanhamento',
            index: 'ultimoacompanhamento',
            width: 19,
            align: 'center',
            hidden: false,
            search: false,
            sortable: false
        }, {
            name: 'situacao',
            index: 'situacao',
            align: 'center',
            width: 12,
            hidden: false,
            search: true,
            formatter: formatadorSituacao
        }, {
            name: 'id',
            index: 'id',
            width: 25,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }/*, {
            name: 'domstatusprojeto',
            index: 'domstatusprojeto',
            align: 'center',
            width: 13,
            hidden: true,
            search: true,
            //formatter: formatadorSituacao
        }/**/];
//1210
    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/planodeacao/gerencia/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1000',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomprojeto',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
        }
    });
    //grid.jqGrid('filterToolbar');
    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');

    actions.pesquisar.form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planodeacao/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
    });

    $("#accordion").accordion();

    resizeGrid();
});