$(function () {

    var
        grid = null,
        vSalvar = true,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        idProjeto = "input[name='idprojeto']",
        $dialogExcluir = $('#dialog-excluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        formEditar = "form#form-mudanca-editar",
        formCadastrar = "form#form-mudanca",
        formExcluir = "form#form-mudanca-excluir",
        $dialogCadastrar = $('#dialog-cadastrar');

    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Solicitação de Mudança - Detalhar',
        width: '1100px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogExcluir = $('#dialog-excluir').dialog({
        autoOpen: false,
        title: 'Solicitação de Mudança - Excluir',
        width: '880px',
        modal: true,
        open: function (event, ui) {
            $(formExcluir).validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/projeto/solicitacaomudanca/excluir/format/json", formExcluir, function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        buttons: {
            'Excluir': function () {
                $(formExcluir).submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Solicitação de Mudança - Editar',
        width: '1020px',
        modal: false,
        open: function (event, ui) {
            $(formEditar).validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/projeto/solicitacaomudanca/editar/format/json", formEditar, function (data) {
                        if (data.success) {
                            $dialogEditar.html(data).dialog('close');
                            grid.trigger("reloadGrid");
                        }
                    });
                }
            });
            vSalvar = true;
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        close: function (event, ui) {
            $dialogEditar.empty();
            vSalvar = true;
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        buttons: {
            'Salvar': function (event) {
                event.preventDefault();
                if (vSalvar) {
                    $(formEditar).trigger('submit');
                    if ($(formEditar).valid()) {
                        vSalvar = false;
                        $('#dialog-editar').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-editar').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 2500);
                    }
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
                vSalvar = true;
                $('#dialog-editar').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $(document.body).on('click', "a.detalhar", function (event) {
        event.preventDefault();
        var
            $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                $dialogDetalhar.html(data).dialog('open');
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

    $(document.body).on('click', "a.excluir, a.editar", function (event) {
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
                $dialog.html(data).dialog('open');
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

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/projeto/solicitacaomudanca/editar',
                excluir: base_url + '/projeto/solicitacaomudanca/excluir',
                detalhar: base_url + '/projeto/solicitacaomudanca/detalhar',
                imprimir: base_url + '/projeto/solicitacaomudanca/imprimir'
            };
        params = '/idmudanca/' + r[6] + '/idprojeto/' + r[7];


        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            '<a data-target="#" class="btn actionfrm imprimir" title="Imprimir" data-id="' + cellvalue + '" href="' + url.imprimir + params + '" target="_blank"><i class="icon-print"></i></a>'
            ;
    }

    colNames = ['Solicitante', 'Data Solicitação', 'Tipo', 'Aprovada?', 'Data Decisão', 'Usuário', 'Operações'];
    colModel = [{
        name: 'm.nomsolicitante',
        index: 'm.nomsolicitante',
        width: 20,
        search: true
    }, {
        name: 'datsolicitacao',
        index: 'datsolicitacao',
        align: 'center',
        width: 15,
        hidden: false,
        search: true
    }, {
        name: 'tm.dsmudanca',
        index: 'tm.dsmudanca',
        width: 15,
        align: 'center',
        search: true
    }, {
        name: 'm.flaaprovada',
        index: 'm.flaaprovada',
        align: 'center',
        width: 8,
        search: true
    }, {
        name: 'datdecisao',
        index: 'datdecisao',
        align: 'center',
        width: 15,
        search: true
    }, {
        name: 'p.nompessoa',
        index: 'p.nompessoa',
        width: 20,
        align: 'center',
        search: true
    }, {
        name: 'm.idmudanca',
        index: 'm.idmudanca',
        width: 18,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/projeto/solicitacaomudanca/pesquisarjson/idprojeto/" + $(idProjeto).val(),
        datatype: "json",
        mtype: 'post',
        width: '800',
        height: '700px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomsolicitante',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
        }
    });

    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    $.ajax({
        url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            idprojeto: $(idProjeto).val()
        },
        success: function (data) {
            data.projeto.ultimoStatusReport.datfimprojetotendencia = data.projeto.ultimoStatusReport.datfimprojetotendencia.substr(0, 10);
            TemplateManager.get('dados-projeto', function (tpl) {
                $("#dados-projeto").html(tpl(data.projeto));
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

    /*xxxxxxxxxx CADASTRAR xxxxxxxxxx*/
    $dialogCadastrar.dialog({
        autoOpen: false,
        title: 'Solicitação de Mudança - Cadastrar',
        width: '1020px',
        modal: false,
        open: function (event, ui) {
            $(formCadastrar).validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/projeto/solicitacaomudanca/add/format/json", formCadastrar, function (data) {
                        if (data.success) {
                            $dialogCadastrar.html(data).dialog('close');
                            grid.trigger("reloadGrid");
                        }
                    });
                }
            });
            vSalvar = true;
            $('#dialog-cadastrar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        close: function (event, ui) {
            $dialogCadastrar.empty();
            vSalvar = true;
            $('#dialog-cadastrar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        buttons: {
            'Salvar': function (event) {
                event.preventDefault();
                if (vSalvar) {
                    $("form#form-mudanca").trigger('submit');
                    if ($("form#form-mudanca").valid()) {
                        vSalvar = false;
                        $('#dialog-cadastrar').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-cadastrar').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 2500);
                    }
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
                vSalvar = true;
                $('#dialog-cadastrar').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            }
        }
    }).css("maxHeight", window.innerHeight - 150);
    $(document.body).on('click', "a.cadastrar", function (event) {
        event.preventDefault();
        var $this = $(this);
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                $dialogCadastrar.html(data).dialog('open');
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

    $("#dados-projeto").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });

});
