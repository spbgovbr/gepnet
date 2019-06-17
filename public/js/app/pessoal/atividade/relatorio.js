$(function () {

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;


    var $form = $("form#form-atividade-relatorio");

    colNames = ['Atividade', 'Início', 'Fim Meta', 'Fim Real', 'Demandante', 'Responsável', '%', 'Contínua?', 'Atualização', 'Escritório'];
    colModel = [{
        name: 'nomatividade',
        index: 'nomatividade',
        width: 25,
        search: false
    }, {
        name: 'datinicio',
        index: 'datinicio',
        width: 10,
        align: 'center',
        hidden: false,
        search: false
    }, {
        name: 'datfimmeta',
        index: 'datfimmeta',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'datfimreal',
        index: 'datfimreal',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'nomcadastrador',
        index: 'nomcadastrador',
        width: 20,
        search: true
    }, {
        name: 'nomresponsavel',
        index: 'nomresponsavel',
        width: 20,
        search: true
    }, {
        name: 'numpercentualconcluido',
        index: 'numpercentualconcluido',
        width: 5,
        align: 'center',
        search: true
    }, {
        name: 'flacontinua',
        index: 'flacontinua',
        width: 5,
        align: 'center',
        search: true
    }, {
        name: 'datatualizacao',
        index: 'datatualizacao',
        width: 10,
        align: 'center',
        search: false,
        sortable: false,
    }, {
        name: 'nomescritorio',
        index: 'nomescritorio',
        width: 10,
        search: true
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/pessoal/atividade/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomatividade',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
        }
    });

    //grid.jqGrid('filterToolbar');
    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/pessoal/atividade/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });
    resizeGrid();
});

