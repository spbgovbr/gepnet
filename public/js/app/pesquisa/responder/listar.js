var altura_ocupada = 130;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Nome Questionário', 'Observa&ccedil;&otilde;es', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 40,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'desobservacao',
        index: 'desobservacao',
        width: 55,
        hidden: false,
        search: false,
        sortable: true
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
        url: base_url + "/pesquisa/responder/pesquisar",
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
        caption: 'Selecione um formulário abaixo para preenchimento',
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

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                responder: base_url + '/pesquisa/responder/responder-pesquisa',
            };
        params = '/idpesquisa/' + r[2];
//        console.log(rowObject);
        return '<a data-target="dialog-responder" class="btn actionfrm responder" title="Responder" data-id="' + cellvalue + '" href="' + url.responder + params + '"><i class="icon-check"></i></a>';
    }


    $("form#form-questionario-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-questionario-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/responder/pesquisar?" + $("form#form-questionario-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });


});