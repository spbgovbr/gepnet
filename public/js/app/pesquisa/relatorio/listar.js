var altura_ocupada = 110;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Pesquisa', 'Tipo', 'Situa&ccedil;&atilde;o', 'Respondidas', 'Opera&ccedil;&otilde;es'];

    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 40,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'tipoquestionario',
        index: 'tipoquestionario',
        width: 5,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'situacao',
        index: 'situacao',
        width: 5,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'respondidas',
        index: 'respondidas',
        width: 5,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'idpesquisa',
        index: 'idpesquisa',
        width: 5,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list-grid-pesquisa").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/relatorio/pesquisar",
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-pesquisa',
        sortname: 'nomquestionario',
        viewrecords: true,
        sortorder: "asc",
        //caption: 'Selecione um formulário abaixo para gerar o relatorio',
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
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-pesquisa', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    actions = {
        percentual: {
            dialog: $('#dialog-percentual')
        },
        tabelado: {
            dialog: $('#dialog-tabelado')
        }
    };

    /**Percentual**/
    $(document.body).on('click', "a.percentual", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'POST',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.percentual.dialog.html(data).dialog('open');
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

    actions.percentual.dialog.dialog({
        autoOpen: false,
        title: 'Relat&oacute;rio Percentual',
        width: 1200,
        height: 768,
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    /**Tabelado**/
    $(document.body).on('click', "a.tabelado", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'POST',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.tabelado.dialog.html(data).dialog('open');
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

    actions.tabelado.dialog.dialog({
        autoOpen: false,
        title: 'Relat&oacute;rio Tabelado',
        width: 1200,
        height: 768,
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
                percentual: base_url + '/pesquisa/relatorio/relatorio-percentual',
                tabelado: base_url + '/pesquisa/relatorio/relatorio-tabelado',
            };
        params = '/idpesquisa/' + r[4];
//        console.log(rowObject);
        return '<a data-target="dialog-relatorio" class="btn actionfrm percentual" title="Relat&oacute;rio Percentual" data-id="' + cellvalue + '" href="' + url.percentual + params + '" style="font-style: italic; font-weight: bold; font-size: initial;">&percnt;</a>' +
            '<a data-target="dialog-relatorio" class="btn actionfrm tabelado" title="Relat&oacute;rio Tabelado" data-id="' + cellvalue + '" href="' + url.tabelado + params + '"><i class="icon-th"></i></a>';
    }


    $("form#form-pesquisa-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-pesquisa-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/relatorio/pesquisar?" + $("form#form-pesquisa-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });


});