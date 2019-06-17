$(function () {
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/planodeacao/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            }
        };
    //Reset button
    $("#resetbutton").click(function () {
        $("#idplanodeacao").val('');
        $("#idescritorio").val('');
        $("#idprograma").val('');
        $("#domstatusprojeto").val('');
        $("#codobjetivo").val('');
        $("#codacao").val('');
        $("#codnatureza").val('');
        $("#codsetor").val('');
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                detalhar: base_url + '/planodeacao/statusreport/detalhar'
            };
        params = '/idplanodeacao/' + r[16];
        //console.log(rowObject);

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>';

    }

    function formatadorImgPrazo(cellvalue, options, rowObject) {
//      var path = base_url + '/img/ico_verde.gif';
//      return '<img src="' + path + '" />';
        var retorno = '-';
        /*
        * rowObject[12] => prazo
        * rowObject[18] => criterio farol
        * */
        if (rowObject[12] >= rowObject[18]) {
            var retorno = '<span class="badge badge-important" title=' + rowObject[12] + '>P</span>';
        } else if (rowObject[12] > 0) {
            var retorno = '<span class="badge badge-warning" title=' + rowObject[12] + '>P</span>';
        } else {
            var retorno = '<span class="badge badge-success" title=' + rowObject[12] + '>P</span>';
        }

        if (rowObject[12] === "-") return rowObject[12];

        return retorno;
    }

    function formatadorImgRisco(cellvalue, options, rowObject) {
        var retorno = rowObject[13] + '-';

        if (rowObject[13] === '1') {
            var retorno = '<span class="badge badge-success" title="Baixo">R</span>';
        } else if (rowObject[13] === '2') {
            var retorno = '<span class="badge badge-warning" title="Medio">R</span>';
        } else if (rowObject[13] === '3') {
            var retorno = '<span class="badge badge-important" title="Alto">R</span>';
        }

        return retorno;
    }

    function formatadorAtraso(cellvalue, options, rowObject) {
        var retorno = '-';

        if (rowObject[11] || rowObject[11] === 0) {
            retorno = rowObject[11];
        }

        return retorno;
    }


    function formatadorSituacao(cellvalue, options, rowObject) {
        return rowObject[15];
    }

    colNames = ['Programa', 'Plano de Ação', 'Unidade Executora', 'Responsável', 'Código', 'Publicado', 'Início', 'Fim', 'Termino Tendencia', 'Previsto', 'Concluído', 'Atraso', 'Prazo', 'Risco', 'Últim. Acompanhamento', 'Situação', 'Operações'];
    colModel = [
        {
            name: 'nomprograma',
            index: 'nomprograma',
            align: 'center',
            width: 25,
            hidden: false,
            search: false
        }, {
            name: 'nomprojeto',
            index: 'nomprojeto',
            align: 'center',
            width: 60,
            hidden: false,
            search: false
        }, {
            name: 'nomsetor',
            index: 'nomsetor',
            align: 'center',
            width: 50,
            hidden: true,
            search: false,
        }, {
            name: 'noproponente',
            index: 'noproponente',
            align: 'center',
            width: 60,
            hidden: false,
            search: false
        }, {
            name: 'nomcodigo',
            index: 'nomcodigo',
            align: 'center',
            width: 20,
            search: true,
            hidden: true
        }, {
            name: 'flapublicado',
            index: 'flapublicado',
            align: 'center',
            width: 12,
            search: true,
            hidden: true
        }, {
            name: 'datinicio',
            index: 'datinicio',
            align: 'center',
            width: 12,
            search: true
        }, {
            name: 'datfim',
            index: 'datfim',
            align: 'center',
            width: 12,
            search: true,
        }, {
            name: 'datfimplano',
            index: 'datfimplano',
            align: 'center',
            width: 12,
            search: true,
            hidden: true
        }, {
            name: 'previsto',
            index: 'previsto',
            width: 10,
            search: false,
            sortable: false
        }, {
            name: 'concluido',
            index: 'concluido',
            width: 10,
            search: false,
            sortable: false
        }, {
            name: 'atraso',
            index: 'atraso',
            width: 10,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorAtraso
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 10,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorImgPrazo
        }, {
            name: 'Risco',
            index: 'risco',
            width: 10,
            align: 'center',
            sortable: false,
            search: false,
            formatter: formatadorImgRisco
        }, {
            name: 'ultimoacompanhamento',
            index: 'ultimoacompanhamento',
            sortable: false,
            width: 15,
            search: false,
            sortable: false
        }, {
            name: 'situacao',
            index: 'situacao',
            width: 15,
            search: false,
            sortable: false
        }, {
            name: 'id',
            index: 'id',
            width: 15,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];

    var urlPesq = (($('#codacao').val() != '') && ($('#codobjetivo').val() != '')) ?
        (base_url + "/planodeacao/statusreport/pesquisarjson?codobjetivo=" + $('#codobjetivo').val() + "&codacao=" + $('#codacao').val()) :
        (($('#codobjetivo').val() != '') ?
            (base_url + "/planodeacao/statusreport/pesquisarjson?codobjetivo=" + $('#codobjetivo').val()) :
            (base_url + "/planodeacao/statusreport/pesquisarjson/statusreport/1"));

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: urlPesq,
        datatype: "json",
        mtype: 'post',
        width: '1210',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomprojeto',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
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

    actions.pesquisar.form.on('submit', function (e) {

        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planodeacao/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    resizeGrid();
});