$(function () {

    var
        $form = $("#form-pesquisar-questionario_resp"),
        iddiagnostico = $("input[name='iddiagnostico']").val(),
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar-questionario_resp"),
                url: base_url + "/diagnostico/pesquisacidadaos/buscaquestionariorespondidocidadao?"
            },
            listagem: {
                url: base_url + "/diagnostico/pesquisacidadaos/buscaquestionariorespondidocidadao/tpquestionario/2/iddiagnostico/" + iddiagnostico
            }
        },
        msgerror = "Não foi possivel realizar esta operação.",
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;

    var width = $("#list-grid-respondido").width($('.region-center').width() - 50);
    $("#toolbar-grid-respondido").width($('.region-center').width() - 50)
    $("#pager-grid-respondido").width($('.region-center').width() - 50)

    function formatadorLink(cellvalue, options, rowObject) {

        var r = rowObject,
            params = '',
            linkEditar = '',
            url = {
                visualizar: base_url + '/diagnostico/pesquisacidadaos/visualizarquestionariorespondidocidadao'
            };
        params = '/idquestionariodiagnostico/' + r[3] + '/iddiagnostico/' + iddiagnostico + '/numero/' + r[1] + '/tpquestionario/2';

        $return = '<a class="btn actionfrm visualizar" title="Visualizar Questionário Respondido" data-id="' + cellvalue + '" href="' + url.visualizar + params + '"><i class="icon-eye-open"></i></a>';

        return $return;

        //$return = '<a title="Questionário" ' +
        //    'style="text-decoration: none; color: #0b83d1;" onMouseOver="this.style.textDecoration=\'underline\'; this.style.color = \'#1e395b\';" ' +
        //    'onMouseOut="this.style.textDecoration=\'none\'; this.style.color = \'#0b83d1\';" href="' + url.responder + params + '" >' + r[0] + '</a>\n';
        //
        //return $return;
    }

    colNames = ['Nome do Questionário', 'Número do Questionário', 'Data do Questionário', 'idquestionario', 'Operação'];
    colModel = [
        {
            name: 'nomquestionario',
            index: 'nomquestionario',
            width: 140,
            search: false,
            hidden: false,
            sortable: false,
        }, {
            name: 'numero',
            index: 'numero',
            width: 70,
            align: 'center',
            search: false,
            hidden: false,
            sortable: true,
        }, {
            name: 'dtrespondido',
            index: 'dtrespondido',
            width: 70,
            align: 'center',
            search: false,
            hidden: false,
            sortable: false,
        }, {
            name: 'idquestionario',
            index: 'idquestionario',
            width: 5,
            search: false,
            hidden: true,
            sortable: true,
        }, {
            name: 'operacao',
            index: 'operacao',
            width: 30,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorLink
        }
    ];

    grid = jQuery("#list-grid-respondido").jqGrid({
        caption: "Listagem de Questionarios Respodidos dos Cidadãos",
        url: actions.listagem.url,
        datatype: "json",
        mtype: 'post',
        width: 1200,
        height: 'auto',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-respondido',
        sortname: 'numero',
        viewrecords: true,
        sortorder: "asc",
        grouping: true,
        groupingView: {
            groupField: ['nomquestionario'],
            groupColumnShow: [false],
            groupText: ['<b>{0} - {1} Item(s)</b>'],
            groupCollapse: false,
            groupOrder: ['asc']
        }
    });

    grid.jqGrid('navGrid', '#pager-grid-respondido', {
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
            url: actions.pesquisar.url + $("form#form-pesquisar-questionario_resp").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });

    //resizeGrid();
});