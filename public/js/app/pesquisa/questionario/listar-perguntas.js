var altura_ocupada = 180;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Escrit&oacute;rio', 'Pergunta', 'Ordem', 'Obrigatório', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomescritorio',
        index: 'nomescritorio',
        width: 10,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'desfrase',
        index: 'desfrase',
        width: 65,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'numordempergunta',
        index: 'numordempergunta',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'obrigatoriedade',
        index: 'obrigatoriedade',
        width: 5,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idquestionario',
        index: 'idquestionario',
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
            dialog: $('#dialog-inserir')
        },
        editar: {
            dialog: $('#dialog-editar')
        },
        excluir: {
            dialog: $('#dialog-excluir')
        },
    };


    grid = jQuery("#list-grid-questionario-frase").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/questionario/pesquisar-perguntas/idquestionario/" + $('#id-questionario').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager-grid-questionario-frase',
        sortname: 'numordempergunta',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            //console.log('teste');
            $("a.actionfrm").tooltip({placement: 'left'});
        },
        onSelectRow: function (id) {
//            if(window.selectRow){
//                var row = grid.getRowData(id);
//                selectRow(row);
//            } else {
//                alert('Função [selectRow] não está definida');
//            }
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-questionario-frase', {
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
        title: 'Questionário - Adicionar Pergunta',
        width: 1030,
        height: 700,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.inserir.dialog.empty();
        },
        buttons: {
//            'Salvar': function() {                   
//                    $('form#form-questionario-frase').submit();
//            },
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
                var $form = $("form#form-questionario-frase");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/pesquisa/questionario/vincular-pergunta/format/json", "form#form-questionario-frase", function (data) {
                            if (data.success) {
                                resetFormQuestionarioFrase();
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
        title: 'Questionário - Editar Vínculo',
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
                $('form#form-questionario-frase').submit();
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
                var $form = $("form#form-questionario-frase");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/pesquisa/questionario/editar-vinculo-pergunta/format/json", "form#form-questionario-frase", function (data) {
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
        title: 'Questionário - Excluir Vínculo',
        width: 945,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var questionario = $("#dialog-excluir").find('input[name="idquestionario"]').val();
                var frase = $("#dialog-excluir").find('input[name="idfrase"]').val();
                var arrParams = {idquestionario: questionario, idfrase: frase};
                ajax_arrparams("/pesquisa/questionario/desvincular-pergunta/format/json", arrParams, function (data) {
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
        title: 'Questionário - Detalhar',
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
                editar: base_url + '/pesquisa/questionario/editar-vinculo-pergunta',
                detalhar: base_url + '/pesquisa/questionario/detalhar-vinculo-pergunta',
                excluir: base_url + '/pesquisa/questionario/desvincular-pergunta',
            };
        params = '/idquestionario/' + r[5] + '/idfrase/' + r[4];
//        console.log(rowObject);
        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Remover" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
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


    $("form#form-questionario-frase-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-questionario-frase-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/questionario/pesquisar-perguntas?" + $("form#form-questionario-frase-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });


});