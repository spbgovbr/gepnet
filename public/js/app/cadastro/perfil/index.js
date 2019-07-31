$(function () {

    perfilpessoa.init();
    $("#idperfil, #idescritorio, #flaativo").select2();

    $(document.body).on('click', "a.perfilpessoa-toggle", function (event) {
        event.preventDefault();
        var
            $this = $(this);
        perfilpessoa.toggle($this);
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                trocarFlag: base_url + '/cadastro/perfilpessoa/format/json'
            };
        var flagativo = r[6];
        if (flagativo === 'S') {
            return '<a class="btn btn-success perfilpessoa-toggle" title="Inativar" data-permission="deny" data-id="' + r[5] + '" href="' + url.trocarFlag + '"><i class="icon-ok icon-white "></i></a>';
        } else {
            return '<a class="btn btn-danger perfilpessoa-toggle" title="Ativar" data-permission="allow" data-id="' + r[5] + '" href="' + url.trocarFlag + '"><i class="icon-off icon-white "></i></a>';

        }
    }

    function formatadorSituacao(cellvalue, options, rowObject) {
        if (rowObject[6] === 'S') {
            return '<span class="label label-success">Ativo</span>';
        }
        return '<span class="label label-important">Inativo</span>';

    }

    colNames = ['Nome', 'Perfil', 'Escritório', 'Email', 'Situação', 'Operações'];
    colModel = [{
        name: 'nompessoa',
        index: 'nompessoa',
        width: 20,
        search: true
    }, {
        name: 'nomperfil',
        index: 'nomperfil',
        width: 20,
        hidden: false,
        search: true
    }, {
        name: 'nomescritorio',
        index: 'nomescritorio',
        width: 15,
        search: true
    }, {
        name: 'desemail',
        index: 'desemail',
        width: 15,
        search: true
    }, {
        name: 'situacao',
        index: 'situacao',
        width: 8,
        search: true,
        align: 'center',
        formatter: formatadorSituacao
    }, {
        name: 'idpessoa',
        index: 'idpessoa',
        width: 8,
        search: false,
        sortable: false,
        align: 'center',
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/cadastro/perfilpessoa/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '900',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nompessoa',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
        }
    });

    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');


    $("form#perfilpessoa-pesquisa").on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/cadastro/perfilpessoa/pesquisarjson?" + $("form#perfilpessoa-pesquisa").serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();


});

