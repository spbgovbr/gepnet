var altura_ocupada = 115;
$(function () {
    var
        msgerror = null,
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
    colNames = ['Data Cadastro', 'Responsável', 'Origem', 'Informado', 'Informa&ccedil;&atilde;o', 'Frequência', 'Trasmissão',
        'Armazenamento', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'datcadastro',
        index: 'datcadastro',
        width: 10,
        search: false,
        hidden: true,
        sortable: true
    }, {
        name: 'nomresponsavel',
        index: 'nomresponsavel',
        width: 15,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desorigem',
        index: 'desorigem',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desinformado',
        index: 'desinformado',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desinformacao',
        index: 'desinformacao',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desfrequencia',
        index: 'desfrequencia',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'destransmissao',
        index: 'destransmissao',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desarmazenamento',
        index: 'desarmazenamento',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idcomunicacao',
        index: 'idcomunicacao',
        width: 15,
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
            form: $("form#form-comunicacao-add"),
            url: base_url + '/projeto/comunicacao/add/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            form: $("form#form-comunicacao-edit"),
            url: base_url + '/projeto/comunicacao/edit/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            form: $("form#form-comunicacao-excluir"),
            url: base_url + '/projeto/comunicacao/excluir/format/json',
            dialog: $('#dialog-excluir')
        }
    };


    grid = jQuery("#list-grid-comunicacao").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/comunicacao/grid-comunicacao/idprojetopesquisar/" + $('#idprojetopesquisar').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-comunicacao',
        sortname: 'nomresponsavel',
        viewrecords: true,
        sortorder: "asc",
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
                text: msgerror,
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-comunicacao', {
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
        title: 'Comunica&ccedil;&atilde;o - Cadastrar',
        width: 1000,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.inserir.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-comunicacao-add').submit();
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
                $('form#form-comunicacao-add').validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/comunicacao/add/format/json", 'form#form-comunicacao-add', function (data) {
                            if (data.success) {
                                $("#resetbutton").trigger('click');
                                grid.trigger('reloadGrid');
                                $('#btn-close-grid-pessoa').trigger('click');
                                reset();
                            }
                        });
                    }
                });
            },
            error: function () {
                $.pnotify({
                    text: msgerror,
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Comunica&ccedil;&atilde;o - Editar',
        width: 1000,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-comunicacao-edit').submit();
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
                $('form#form-comunicacao-edit').validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/comunicacao/edit/format/json", "form#form-comunicacao-edit", function (data) {
                            if (data.success) {
                                grid.trigger('reloadGrid');
                            }
                        });
                    }
                });
            },
            error: function () {
                $.pnotify({
                    text: msgerror,
                    type: 'error',
                    hide: false
                });
            }
        });
    });


    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Comunica&ccedil;&atilde;o - Excluir',
        width: 930,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {idcomunicacao: $("#dialog-excluir").find('input[name="idcomunicacao"]').val()};
                ajax_arrparams("/projeto/comunicacao/excluir/format/json", arrParams, function (data) {
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
                    text: msgerror,
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
                    text: msgerror,
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Comunica&ccedil;&atilde;o - Detalhar',
        width: 930,
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
                editar: base_url + '/projeto/comunicacao/edit',
                excluir: base_url + '/projeto/comunicacao/excluir',
                detalhar: base_url + '/projeto/comunicacao/detalhar',
            };
        params = '/idprojeto/' + r[9] + '/idcomunicacao/' + r[8];
        //console.log(rowObject);

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
    }

//    /**
//     * Envia ajax por array de parametros
//     */
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
                    text: msgerror,
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    $('#btnfiltrar').click(function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/comunicacao/grid-comunicacao?" + $("form#form-comunicacao-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });

    $("#accordion2").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });
});