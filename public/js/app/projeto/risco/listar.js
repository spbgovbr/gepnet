var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        msgerroacesso = 'Acesso negado para essa ação.',
        colNames = ['Data Detecção', 'Título Risco', 'Origem', 'Etapa', 'Tipo',
            'Risco', 'Contramedida',
            'Risco Ativo?', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'datdeteccao',
        index: 'datdeteccao',
        width: 9,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'norisco',
        index: 'norisco',
        width: 25,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desorigemrisco',
        index: 'desorigemrisco',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'dsetapa',
        index: 'dsetapa',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'dstiporisco',
        index: 'dstiporisco',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'domcorrisco',
        index: 'domcorrisco',
        width: 5,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'domtratamento',
        index: 'domtratamento',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'flariscoativo',
        index: 'flariscoativo',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idrisco',
        index: 'idrisco',
        width: 18,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];
    actions = {
        detalhar: {
            dialog: $('#dialog-detalhar')
        },
        inserir: {
            url: base_url + '/projeto/risco/cadastrar/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/projeto/risco/editar/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            url: base_url + '/projeto/risco/excluir/format/json',
            dialog: $('#dialog-excluir')
        }
    };


    grid = jQuery("#list-grid-risco").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/risco/pesquisar/idprojeto/" + $('#idprojeto').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-risco',
        sortname: 'datdeteccao',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
            //console.log('teste');
            //$("a.actionfrm").tooltip();
        },
        onSelectRow: function (id) {
//            if(window.selectRow){
//                var row = grid.getRowData(id);
//                selectRow(row);
//            } else {
//                alert('Função [selectRow] não está definida');
//            }
        },
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-risco', {
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
        title: 'Risco - Cadastrar',
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
                $('form#form-risco').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.inserir", function (event) {
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
                actions.inserir.dialog.html(data).dialog('open');
                var $form = $("form#form-risco");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/risco/cadastrar/format/json", "form#form-risco", function (data) {
                            if (data.success) {
                                resetFormRisco();
                                grid.trigger('reloadGrid');
                                actions.inserir.dialog.dialog('close');
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
        title: 'Risco - Editar',
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
                $('form#form-risco').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar", function (event) {
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
                actions.editar.dialog.html(data).dialog('open');
                var $form = $("form#form-risco");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/risco/editar/format/json", "form#form-risco", function (data) {
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


    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Risco - Excluir',
        width: 945,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {idrisco: $("#dialog-excluir").find('input[name="idrisco"]').val()};
                ajax_arrparams("/projeto/risco/excluir/format/json", arrParams, function (data) {
                    if (data.success) {
                        grid.trigger('reloadGrid');
                        actions.excluir.dialog.dialog('close');
                    }
                });
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluir", function (event) {
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
        title: 'Risco - Detalhar',
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
                editar: base_url + '/projeto/risco/editar',
                excluir: base_url + '/projeto/risco/excluir',
                detalhar: base_url + '/projeto/risco/detalhar',
                //contramedida:   base_url + '/projeto/contramedida/listar',
                imprimir: base_url + '/projeto/risco/imprimir/print/one',
            };
        params = '/idrisco/' + r[8] + '/idprojeto/' + r[9];
//        console.log(rowObject);
        teste = rowObject;

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            /*'<a data-target="#" class="btn actionfrm contramedida" title="Contramedidas" data-id="' + cellvalue + '" href="' + url.contramedida + params + '"><i class="icon-ok-circle"></i></a>'+*/
            '<a data-target="#" class="btn actionfrm imprimir" title="Imprimir" data-id="' + cellvalue + '" href="' + url.imprimir + params + '"><i class="icon-print"></i></a>';
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


    $("form#form-risco-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-risco-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/projeto/risco/pesquisar?" + $("form#form-risco-pesquisar").serialize(),
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

});