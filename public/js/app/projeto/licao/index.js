$(function () {
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        idProjeto = $("input[name='idprojeto']").val(),
        $dialogExcluir = $('#dialog-excluir'),
        $dialogCadastrar = $('#dialog-cadastrar'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formEditar = $("form#form-licao-editar"),
        $formCadastrar = $("form#form-licao"),
        $formExcluir = $("form#form-licao-excluir");


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Lição Aprendida - Detalhar',
        width: '880px',
        heigth: '740px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogExcluir = $('#dialog-excluir').dialog({
        autoOpen: false,
        title: 'Lição Aprendida - Excluir',
        width: '1020px',
        modal: true,
        open: function (event, ui) {
            $("form#form-licao-excluir").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/projeto/licao/excluir/format/json", "form#form-licao-excluir", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        buttons: {
            'Excluir': function () {
                $("form#form-licao-excluir").submit();
                $(this).dialog('close');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogCadastrar.dialog({
        autoOpen: false,
        title: 'Lição Aprendida - Cadastrar',
        width: '1020px',
        modal: false,
        open: function (event, ui) {
            $("form#form-licao").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/projeto/licao/cadastrar/format/json", "form#form-licao", function (data) {
                        if (data.success) {
                            $('#dialog-cadastrar').html(data).dialog('close');
                            grid.trigger("reloadGrid");
                        }
                    });
                }
            });
        },
        close: function (event, ui) {
            $dialogCadastrar.empty();
        },
        buttons: {
            'Salvar': function () {
                /*****************************************/
                $("form#form-licao").trigger('submit');
                if ($("form#form-licao").valid()) {
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
                /****************************************/
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Lição Aprendida - Editar',
        width: '1020px',
        modal: false,
        open: function (event, ui) {
            $("form#form-licao-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/projeto/licao/editar/format/json", "form#form-licao-editar", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                $("form#form-licao-editar").trigger('submit');
                if ($("form#form-licao-editar").valid()) {
                    $(this).dialog('close');
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
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

    $(document.body).on('click', "a.excluir, a.editar, a.cadastrar ", function (event) {
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

    var $form = $('form#form-licao-pesquisar');
    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/licao/retornalicoesjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    $.ajax({
        url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            idprojeto: $("#idprojeto").val()
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

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            linkEditar = '',
            url = {
                editar: base_url + '/projeto/licao/editar',
                excluir: base_url + '/projeto/licao/excluir',
                detalhar: base_url + '/projeto/licao/detalhar',
                imprimir: base_url + '/projeto/licao/imprimir'
            };
        params = '/idlicao/' + r[6] + '/idprojeto/' + r[7];


        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            '<a data-target="#" class="btn actionfrm imprimir" title="Imprimir" data-id="' + cellvalue + '" href="' + url.imprimir + params + '" target="_blank"><i class="icon-print"></i></a>';
        ;
    }


    colNames = ['Associada a', 'Entrega', 'Resultados Obtidos', 'Pontos Fortes', 'Pontos Fracos', 'Sugestões', 'Operações'];
    colModel = [{
        name: 'nomassociada',
        index: 'nomassociada',
        width: 8,
        search: true
    }, {
        name: 'ac.nomatividadecronograma',
        index: 'ac.nomatividadecronograma',
        width: 22,
        search: true
    }, {
        name: 'desresultadosobtidos',
        index: 'desresultadosobtidos',
        width: 20,
        hidden: false,
        search: false
    }, {
        name: 'despontosfortes',
        index: 'despontosfortes',
        width: 20,
        search: false
    }, {
        name: 'despontosfracos',
        index: 'despontosfracos',
        width: 20,
        search: true
    }, {
        name: 'dessugestoes',
        index: 'dessugestoes',
        width: 20,
        search: false
    }, {
        name: 'a.idlicao',
        index: 'a.idlicao',
        width: 15,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/projeto/licao/retornalicoesjson/idprojeto/" + $("#idprojeto").val(),
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
        sortname: 'nomatividadecronograma',
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

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });

});
