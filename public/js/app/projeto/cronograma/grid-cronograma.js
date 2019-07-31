$(function () {
    //CRONOGRAMA.retornaCronograma();
    jQuery('#tree').jqGrid({
        url: base_url + "/projeto/cronograma/retornacronogramajson/format/json/idprojeto/" + $('#idprojeto').val(),
        "colModel": [
            {
                "name": "idatividadecronograma",
                "index": "idatividadecronograma",
                "sorttype": "int",
                "key": true,
                "hidden": true,
                "width": 50
            }, {
                "name": "nomatividadecronograma",
                "index": "nomatividadecronograma",
                "sorttype": "string",
                "label": "Name",
                "width": 170,
                "uiicon": "icon-folder-open"
            }, {
                "name": "price",
                "index": "price",
                "sorttype": "numeric",
                "label": "Price",
                "width": 90,
                "align": "right"
            }, {
                "name": "qty_onhand",
                "index": "qty_onhand",
                "sorttype": "int",
                "label": "Qty",
                "width": 90,
                "align": "right"
            }, {
                "name": "color",
                "index": "color",
                "sorttype": "string",
                "label": "Color",
                "width": 100
            }, {
                "name": "lft",
                "hidden": true
            }, {
                "name": "rgt",
                "hidden": true
            }, {
                "name": "level",
                "hidden": true
            }, {
                "name": "uiicon",
                "hidden": true
            }
        ],
        "width": "780",
        "hoverrows": false,
        "viewrecords": false,
        "gridview": true,
        "height": "auto",
        "sortname": "lft",
        "loadonce": true,
        "rowNum": 100,
        "scrollrows": true,
        // enable tree grid
        "treeGrid": true,
        // which column is expandable
        "ExpandColumn": "name",
        // datatype
        "treedatatype": "json",
        // the model used
        "treeGridModel": "nested",
        // configuration of the data comming from server
        "treeReader": {
            "left_field": "lft",
            "right_field": "rgt",
            "level_field": "level",
            "leaf_field": "isLeaf",
            "expanded_field": "expanded",
            "loaded": "loaded",
            "icon_field": "icon"
        },
        "sortorder": "asc",
        "datatype": "json",
        "pager": "#pager"
    });


    grid = jQuery("#list2").jqGrid({
        caption: "Cronograma",
        url: base_url + "/projeto/cronograma/retornacronogramajson/format/json/idprojeto/" + $('#idprojeto').val(),
        datatype: "json",
        mtype: 'post',
        width: '990',
        height: '100%',
        colNames: ['Grupo/Entrega/Atividade', 'Inicio', 'Fim', 'D/P'], //'Custo', 'Início', 'Fim', 'D/R', '%', 'Responsavel', 'Atraso', 'Comentarios'],
        colModel: [
            {
                name: 'nomatividadecronograma',
                index: 'nomatividadecronograma',
                align: 'center',
                width: 20,
                hidden: false,
                search: false
            },
            {
                name: 'datiniciobaseline',
                index: 'datiniciobaseline',
                align: 'center',
                width: 18,
                hidden: false,
                search: false,
                editable: true
            },
            {
                name: 'datfimbaseline',
                index: 'datfimbaseline',
                align: 'center',
                width: 18,
                hidden: false,
                search: false,
                editable: true
            },
            {name: 'diasbaseline', index: 'diasbaseline', align: 'center', width: 10, hidden: false, search: false},
        ],
        rownumbers: true,
        //rowNum: 20,
        //rowList: [20, 50, 100],
        pager: '#pager2',
        //sortname: 'nomprojeto',
        viewrecords: true,
        //sortorder: "asc",
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

    jQuery("#list2").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [
            {startColumnName: 'datiniciobaseline', numberOfColumns: 3, titleText: 'Planejado'},
            //{startColumnName: 'datinicio', numberOfColumns: 3, titleText: 'Real'}
        ]
    });

    resizeGrid();
});