var altura_ocupada = 120;
$(function () {
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        msgerroacesso = 'Acesso negado para essa ação.',
        colNames = ['Código', 'Data', 'Macroprocesso de Trabalho', 'Macroprocesso Melhorar', 'dfgsdfg', 'Risco Ativo?', 'Opera&ccedil;&otilde;es'];
    colModel = [
        {
            name: 'idmelhoria',
            index: 'idmelhoria',
            width: 6,
            hidden: false,
            search: false,
            sortable: true
        }, {
            name: 'datmelhoria',
            index: 'datmelhoria',
            width: 9,
            search: false,
            hidden: false,
            sortable: true
        }, {
            name: 'idmacroprocessotrabalho',
            index: 'idmacroprocessotrabalho',
            width: 25,
            hidden: false,
            search: false,
            sortable: true
        }, {
            name: 'idmacroprocessomelhorar',
            index: 'idmacroprocessomelhorar',
            width: 25,
            hidden: false,
            search: false,
            sortable: true
        }, {
            name: 'idunidaderesponsavelproposta',
            index: 'idunidaderesponsavelproposta',
            width: 8,
            hidden: true,
            search: false,
            sortable: true
        }, {
            name: 'flaabrangencia',
            index: 'flaabrangencia',
            width: 8,
            hidden: true,
            search: false,
            sortable: true
        }, {
            name: 'idmelhoria',
            index: 'idmelhoria',
            width: 10,
            hidden: false,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }
    ];
    actions = {
        detalhar: {
            dialog: $('#dialog-detalhar')
        },
        inserir: {
            url: base_url + '/diagnostico/sugestaomelhoria/cadastrar/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/diagnostico/sugestaomelhoria/editar/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            url: base_url + '/diagnostico/sugestaomelhoria/excluir/format/json',
            dialog: $('#dialog')
        }
    };

    grid = jQuery("#list-grid-sugestaomelhoria").jqGrid({
        url: base_url + "/diagnostico/sugestaomelhoria/pesquisar/iddiagnostico/" + $('#iddiagnostico').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-sugestaomelhoria',
        sortname: 'datmelhoria',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
        },
        onSelectRow: function (id) {
        },
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-sugestaomelhoria', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    /*xxxxxxxxxx INSERIR xxxxxxxxxx*/
    actions.inserir.dialog.dialog({
        autoOpen: false,
        title: 'Sugestão de Melhorias - Cadastrar',
        width: 1030,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.inserir.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                if ($('.form-sug').attr('id') == 'form-melhoria') {
                    $('#form-melhoria').submit();
                } else {
                    $('#form-padronizacao').submit();
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    /*xxxxxxx Cadastrar xxxxxxxxx*/
    $(document.body).on('click', "a.inserir, #melhoria-menu, #padronizacao-menu", function (event) {
        event.preventDefault();
        var $this = $(this);
        var str = $(this).attr('id');
        var formulario = '';

        if (str != undefined) {
            formulario = 'form#form-' + str.split('-')[0];
        } else {
            formulario = 'form#form-melhoria';
        }

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.inserir.dialog.html(data).dialog('open');
                var $form = $(formulario);
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/diagnostico/sugestaomelhoria/cadastrar/format/json", formulario, function (data) {
                            if (data.success) {
                                grid.trigger('reloadGrid');
                                $('#form-melhoria').append('<input type="hidden" name="aba2" id="aba2" value="aba2" />');
                                $('#aba2').replaceWith('<a data-target="#dialog-inserir" class="link padronizacao-link" id="padronizacao-menu" href="/diagnostico/sugestaomelhoria/cadastrar/iddiagnostico/' + $('#iddiagnostico').val() + '/aba/padronizacao/id/' + data.ata.idmelhoria + '/desmelhoria/' + $('#desmelhoria').val() + '/idsituacao/' + data.msg.idsituacao + '">Padronização de Melhoria</a>');
                            }
                        });
                    }
                });
            },
            error: function () {
                $.pnotify({
                    text: msgerroacesso,
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Sugestão de Melhorias - Editar',
        width: 1030,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                if ($('.form-sug-editar').attr('id') == 'form-melhoria') {
                    $('#form-melhoria').submit();
                } else {
                    $('#form-padronizacao').submit();
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar, #melhoria-menu-editar, #padronizacao-menu-editar", function (event) {
        event.preventDefault();
        var $this = $(this);


        var str = $(this).attr('id');
        var formulario = '';

        if (str != undefined) {
            formulario = 'form#form-' + str.split('-')[0];
        } else {
            formulario = 'form#form-melhoria';
        }

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.editar.dialog.html(data).dialog('open');
                var $form = $(formulario);
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/diagnostico/sugestaomelhoria/editar/format/json", formulario, function (data) {
                            if (data.success) {
                                grid.trigger('reloadGrid');
                            }
                        });
                    }
                });
            },
            error: function () {
                $.pnotify({
                    text: msgerroacesso,
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(document.body).on('click', "a.excluir", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'POST',
            data: $('form#form-melhoria-excluir').serialize(),
            async: true,
            cache: true,
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

    /*xxxxxxxxxx DETALHAR xxxxxxxxxx*/
    $(document.body).on('click', "a.detalhar", function (event) {
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
                actions.detalhar.dialog.html(data).dialog('open');
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

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Sugestão de Melhorias - Detalhar',
        width: 945,
        height: 600,
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/diagnostico/sugestaomelhoria/editar',
                excluir: base_url + '/diagnostico/sugestaomelhoria/excluir',
                detalhar: base_url + '/diagnostico/sugestaomelhoria/detalhar',
            };
        params = '/iddiagnostico/' + r[14] + '/idmelhoria/' + r[0];
        teste = rowObject;

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
    }

    /**
     * Envia ajax por array de parametros
     */
    function ajax_arrparams(url, data, callback) {
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: data,
            success: function (data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                $.pnotify(data.msg);
                if (callback && typeof (callback) === "function") {
                    callback(data);
                }
            },
            error: function () {
                $.pnotify({
                    text: msgerroacesso,
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    $("form#form-sugestaomelhoria-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-sugestaomelhoria-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/diagnostico/sugestaomelhoria/pesquisar?" + $("form#form-sugestaomelhoria-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $('.mask-date').mask('99/99/9999');

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });


    $("#dialog").dialog({
        autoOpen: false,
        title: 'Sugestão de Melhoria - Excluir',
        width: 360,
        height: 150,
        modal: true,
        dataType: 'json',
        type: 'POST',
        data: $('form#form-melhoria-excluir').serialize(),
        buttons: {
            'Sim': function () {
                var arrParams = {idmelhoria: $("#dialog").find('input[name="idmelhoria"]').val()};
                ajax_arrparams("/diagnostico/sugestaomelhoria/excluir/format/json", arrParams, function (data) {
                    if (!data.success) {
                        grid.trigger('reloadGrid');
                        setTimeout(function () {
                            window.location.href = base_url + "/diagnostico/sugestaomelhoria/listar/iddiagnostico/" +
                                $('#iddiagnostico').val();
                        }, 1000);
                    }
                });
            },
            'Não': function () {
                $(this).dialog('close');
            }
        }


    });

});
