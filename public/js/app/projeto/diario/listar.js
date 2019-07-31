var altura_ocupada = 120;

$(function () {

    $("form#form-diario-pesquisar").validate();

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Data Diario', 'Referência', 'Status', 'Usuário', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'datdiariobordo',
        index: 'datdiariobordo',
        width: 10,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'domreferencia',
        index: 'domreferencia',
        width: 20,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'domsemafaro',
        index: 'domsemafaro',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'nompessoa',
        index: 'nompessoa',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'iddiariobordo',
        index: 'iddiariobordo',
        width: 10,
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
            url: base_url + '/projeto/diario/cadastrar/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/projeto/diario/editar/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            url: base_url + '/projeto/diario/excluir/format/json',
            dialog: $('#dialog-excluir')
        }
    };

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $("body").delegate(".datemask-BR", "focusin", function () {
        var $this = $(this);
        $(this).mask('99/99/9999');
        $this.attr('readonly', true);
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    });

    grid = jQuery("#list-grid-diario").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/diario/pesquisar/idprojeto/" + $('#idprojeto').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-diario',
        sortname: 'datdiariobordo',
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

    grid.jqGrid('navGrid', '#pager-grid-diario', {
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
        title: 'Diário de Bordo - Cadastrar',
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
                $('form#form-diario').submit();
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
                var $form = $("form#form-diario");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/diario/cadastrar/format/json", "form#form-diario", function (data) {
                            if (data.success) {
                                resetFormAta();
                                grid.trigger('reloadGrid');
                            }
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

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Diário de Bordo - Editar',
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
                $('form#form-diario').submit();
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
                var $form = $("form#form-diario");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/diario/editar/format/json", "form#form-diario", function (data) {
                            if (data.success) {
                                grid.trigger('reloadGrid');
                            }
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
//
//
    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Diário de Bordo - Excluir',
        width: 935,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {iddiariobordo: $("#dialog-excluir").find('input[name="iddiariobordo"]').val()};
                ajax_arrparams("/projeto/diario/excluir/format/json", arrParams, function (data) {
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
        title: 'Diário de Bordo - Detalhar',
        width: 935,
        height: 500,
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
                editar: base_url + '/projeto/diario/editar',
                excluir: base_url + '/projeto/diario/excluir',
                detalhar: base_url + '/projeto/diario/detalhar',
            };
        params = '/iddiariobordo/' + r[4] + '/idprojeto/' + r[5];
//        console.log(rowObject);
//        teste = rowObject;

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
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    }


    $("form#form-diario-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-diario-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/projeto/diario/pesquisar?" + $("form#form-diario-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });

    $("#accordion2").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });

});