$(function() {
    var
            grid = null,
            lastsel = null,
            gridEnd = null,
            colModel = null,
            colNames = null,
            actions = {
                pesquisar: {
                    form: $("form#form-pesquisar"),
                    url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
                },
                detalhar: {
                    dialog: $('#dialog-detalhar')
                }
            };
    //Reset button
    $("#resetbutton").click(function() {
        $("#idprojeto").val('');
        $("#idescritorio").val('');
        $("#idprograma").val('');
        $("#domstatusprojeto").val('');
        $("#codobjetivo").val('');
        $("#codacao").val('');
        $("#codnatureza").val('');
        $("#codsetor").val('');
    });

    function formatadorLink(cellvalue, options, rowObject)
    {
        var r = rowObject,
                params = '',
                url = {
            detalhar: base_url + '/projeto/statusreport/detalhar'
        };
        params = '/idprojeto/' + r[14];
        //console.log(rowObject);

        return  '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>';
        
    }

    function formatadorImgPrazo(cellvalue, options, rowObject)
    {
//      var path = base_url + '/img/ico_verde.gif';
//      return '<img src="' + path + '" />';
        var retorno = '-';

        if (rowObject[11] >= rowObject[15]) {
          var retorno = '<span class="badge badge-important" title=' + rowObject[11] + '>P</span>';
        } else if(rowObject[11] > 0){
          var retorno = '<span class="badge badge-warning" title=' + rowObject[11] + '>P</span>';  
        } else {
          var retorno = '<span class="badge badge-success" title=' + rowObject[11] + '>P</span>';  
        }
        
        if(rowObject[11] === "-") return rowObject[11];
        
        return retorno;
    }
    
    function formatadorImgRisco(cellvalue, options, rowObject)
    {
        var retorno = '-';
        
        if (rowObject[12] === '1') {
            var retorno = '<span class="badge badge-success">R</span>';
        } else if(rowObject[12] === '2'){
            var retorno = '<span class="badge badge-warning">R</span>';
        }  else if(rowObject[12] === '3'){
            var retorno = '<span class="badge badge-important">R</span>';
        }
        
        return retorno;
    }
    
    function formatadorAtraso(cellvalue, options, rowObject)
    {
        var retorno = '-';
        
        if (rowObject[10] || rowObject[10] === 0) {
            retorno = rowObject[10];
        }
        
        return retorno;
    }


    function formatadorSituacao(cellvalue, options, rowObject)
    {
        return rowObject[16];
    }

    colNames = ['Programa', 'Projeto', 'Gerente', 'Código', 'Publicado','Início', 'Término Meta', 'Termino Tendencia', 'Previsto', 'Concluído', 'Atraso', 'Prazo', 'Risco', 'Últim. Acompanhamento', 'Situação', 'Operações'];
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
            name: 'idgerenteprojeto',
            index: 'idgerenteprojeto',
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
            hidden: true,
        }, {
            name: 'flapublicado',
            index: 'flapublicado',
            align: 'center',
            width: 15,
            search: true,
            hidden: true,
        }, {
            name: 'datinicio',
            index: 'datinicio',
            align: 'center',
            width: 20,
            search: true
        }, {
            name: 'datfimplano',
            index: 'datfimplano',
            align: 'center',
            width: 20,
            search: true
        }, {
            name: 'datfim',
            index: 'datfim',
            align: 'center',
            width: 20,
            search: true,
        }, {
            name: 'previsto',
            index: 'previsto',
            width: 15,
            search: false,
            sortable: false,
        }, {
            name: 'concluido',
            index: 'concluido',
            width: 15,
            search: false,
            sortable: false,
        }, {
            name: 'atraso',
            index: 'atraso',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorAtraso
        },{
            name: 'prazo',
            index: 'prazo',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorImgPrazo
        }, {
            name: 'Risco',
            index: 'risco',
            width: 15,
            align: 'center',
            sortable: false,
            search: false,
            formatter: formatadorImgRisco
        }, {
            name: 'acompanhamento',
            index: 'datacompanhamento',
            sortable: false,
            width: 15,
            search: false,
            sortable: false,
        }, {
            name: 'situacao',
            index: 'situacao',
            width: 15,
            search: false,
            sortable: false,
            formatter: formatadorSituacao
        }, {
            name: 'id',
            index: 'id',
            width: 20,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];
        
        var urlPesq = (($('#codacao').val() != '') && ($('#codobjetivo').val() != ''))?
                (base_url + "/projeto/statusreport/pesquisarjson?codobjetivo=" + $('#codobjetivo').val() + "&codacao=" + $('#codacao').val()):
            (($('#codobjetivo').val() != '')?
                (base_url + "/projeto/statusreport/pesquisarjson?codobjetivo=" + $('#codobjetivo').val()):
                (base_url + "/projeto/statusreport/pesquisarjson/statusreport/1"));
        
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
            gridComplete: function() {
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

    actions.pesquisar.form.on('submit', function(e) {

        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });
    
    resizeGrid();
});