var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Pergunta', 'Tipo Resposta', 'Situa&ccedil;&atilde;o', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'desfrase',
        index: 'desfrase',
        width: 65,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'domtipofrase',
        index: 'domtipofrase',
        width: 15,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'flaativo',
        index: 'flaativo',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idfrase',
        index: 'idfrase',
        width: 12,
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
            url: base_url + '/pesquisa/pergunta/cadastrar/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/pesquisa/pergunta/editar/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            url: base_url + '/pesquisa/pergunta/excluir/format/json',
            dialog: $('#dialog-excluir')
        }
    };


    grid = jQuery("#list-grid-pergunta").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/pergunta/pesquisar",
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-pergunta',
        sortname: 'desfrase',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            //console.log('teste');
            $("a.actionfrm").tooltip();
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

    grid.jqGrid('navGrid', '#pager-grid-pergunta', {
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
        title: 'Pergunta - Cadastrar',
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
                $('form#form-pergunta').submit();
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
                var $form = $("form#form-pergunta");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/pesquisa/pergunta/cadastrar/format/json", "form#form-pergunta", function (data) {
                            if (data.success) {
                                resetFormPergunta();
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
        title: 'Pergunta - Editar',
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
                $('form#form-pergunta').submit();
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
                var $form = $("form#form-pergunta");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/pesquisa/pergunta/editar/format/json", "form#form-pergunta", function (data) {
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


    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Pergunta - Excluir',
        width: 945,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {idfrase: $("#dialog-excluir").find('input[name="idfrase"]').val()};
                ajax_arrparams("/pesquisa/pergunta/excluir/format/json", arrParams, function (data) {
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
        title: 'Pergunta - Detalhar',
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
                editar: base_url + '/pesquisa/pergunta/editar',
                //excluir:        base_url + '/pesquisa/pergunta/excluir',
                detalhar: base_url + '/pesquisa/pergunta/detalhar',
                resposta: base_url + '/pesquisa/resposta/listar',
            };
        params = '/idfrase/' + r[3];
//        console.log(rowObject);
        var opcoes = '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>';
        if (r[4] == 1 || r[4] == 2 || r[4] == 7) {
            opcoes += '<a data-target="#" class="btn actionfrm contramedida" title="Resposta(s)" data-id="' + cellvalue + '" href="' + url.resposta + params + '"><i class="icon-ok-circle"></i></a>';
        }

        return opcoes;
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


    $("form#form-pergunta-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-pergunta-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/pergunta/pesquisar?" + $("form#form-pergunta-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });


});