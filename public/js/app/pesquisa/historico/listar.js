var altura_ocupada = 110;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Pesquisa', 'Situa&ccedil;&atilde;o', 'Publica&ccedil;&atilde;o', 'Encerramento', 'Publicado por', 'Unidade', 'Encerrado por', 'Unidade'];

    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 30,
        search: false,
        hidden: false,
        sortable: false
    }, {
        name: 'situacao',
        index: 'situacao',
        width: 4,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'datpublicacao',
        index: 'datpublicacao',
        width: 8,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'datencerramento',
        index: 'datencerramento',
        width: 8,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'nome_publicou',
        index: 'nome_publicou',
        width: 10,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'unidade_pub',
        index: 'unidade_pub',
        width: 7,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'nome_encerrou',
        index: 'nome_encerrou',
        width: 10,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'unidade_enc',
        index: 'unidade_enc',
        width: 7,
        hidden: false,
        search: false,
        sortable: false
    }];

    grid = jQuery("#list-grid-pesquisa").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/historico/pesquisar",
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

    $('.mask-date').mask('99/99/9999');
    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $("form#form-historico-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-historico-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/historico/pesquisar?" + $("form#form-historico-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });


});