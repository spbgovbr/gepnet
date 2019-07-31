$(function () {
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null
    ;

    var $form = $("form#parecer");

    colNames = ['Número', 'Ano', 'Origem', 'Referência', 'Expediente', 'Autor', 'Assunto'];
    colModel = [{
        name: 'nr_parecer',
        index: 'nr_parecer',
        width: 102,
        search: false,
        sortable: true
    }, {
        name: 'an_parecer',
        index: 'an_parecer',
        width: 40,
        search: false
    }, {
        name: 'ds_origem_par',
        index: 'ds_origem_par',
        width: 60,
        search: false
    }, {
        name: 'ds_referen_par',
        index: 'ds_referen_par',
        width: 200,
        search: true
    }, {
        name: 'ds_expedient_par',
        index: 'ds_expedient_par',
        width: 200,
        search: true
    }, {
        name: 'ds_autor_par',
        index: 'ds_autor_par',
        width: 50,
        search: true
    }, {
        name: 'ds_assunto_par',
        index: 'ds_assunto_par',
        width: 100,
        search: true
    }];

    grid = jQuery("#list2").jqGrid({
        caption: "Parecer Anterior a 2002",
        url: base_url + "/parecer/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: 'auto',
        colNames: colNames,
        colModel: colModel,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nr_parecer',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
            // console.log('teste');
            // $("a.actionfrm").tooltip();
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

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/parecer/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        // $("a.actionfrm").tooltip();
        return false;
    });
});