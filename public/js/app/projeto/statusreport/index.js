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
                url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            }
        };
    $(".select2").select2();

    //Reset button
    $("#resetbutton").click(function () {
        //    $("#idprojeto").val('');
        //    $("#idescritorio").val('');
        //    $("#idprograma").val('');
        //    $("#domstatusprojeto").val('');
        //    $("#codobjetivo").val('');
        //    $("#codacao").val('');
        //    $("#codnatureza").val('');
        //    $("#codsetor").val('');

        $(".select2").select2('data', null);
        $("#idescritorio").select2('data', null);
        $("#idprograma").select2('data', null);
        $("#domstatusprojeto").select2('data', null);
        $("#codobjetivo").select2('data', null);
        $("#codacao").select2('data', null);

    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                detalhar: base_url + '/projeto/statusreport/detalhar'
            };
        params = '/idprojeto/' + r[15];
        //console.log(rowObject);

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>';

    }

    function formatadorImgPrazo(cellvalue, options, rowObject) {
//      var path = base_url + '/img/ico_verde.gif';
//      return '<img src="' + path + '" />';
        var retorno = '-';

        if (rowObject[12] >= rowObject[16]) {
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
        var retorno = '-';
        //console.log(rowObject[13]);
        if (rowObject[13] === '1') {
            var retorno = '<span class="badge badge-success" title="Baixo">R</span>';
        } else if (rowObject[13] === '2') {
            var retorno = '<span class="badge badge-warning" title="Médio">R</span>';
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
        return rowObject[17];
    }

    function formatadorImgAtrazo(cellvalue, options, rowObject) {
        var retorno = '<span class="badge" title=' + rowObject[11] + '>' + rowObject[11] + '</span>';

//        if ((rowObject[11] >= rowObject[19]) && rowObject[19] != null) {
//            var retorno = '<span class="badge badge-important" title=' + rowObject[11] + '>'+rowObject[11]+'</span>';
//        }else if (rowObject[11] > 0) {
//            var retorno = '<span class="badge badge-warning" title=' + rowObject[11] + '>'+rowObject[11]+'</span>';
//        }else {
//            var retorno = '<span class="badge badge-success" title=' + rowObject[11] + '>'+rowObject[11]+'</span>'; 
//        }
        //console.log(rowObject[18]);
        if (rowObject[18] != 'undefined') {
            var retorno = '<span class="badge badge-' + rowObject[18] + '" title=' + rowObject[11] + '>' + rowObject[11] + '</span>';
        }

        return retorno;
    }

    function verificaTamanhoTextoPrograma(str, width, brk) {
        brk = brk || '\n';
        width = 15;

        if (!str) {
            return str;
        }
        var regex = '.{1,' + width + '}(\\s|$)' + '|\\S+?(\\s|$)';
        var array = str.match(RegExp(regex, 'g'));
        var frase = "";

        for (var i = 0; i < array.length; i++) {
            frase += array[i] + "\n";
        }
        ;
        var retorno = "<div style='white-space:initial'>" + frase + "</div>";
        return retorno;
    }

    function verificaTamanhoTextoProjeto(str, width, brk) {

        brk = brk || '\n';
        width = 36;

        if (!str) {
            return str;
        }
        var regex = '.{1,' + width + '}(\\s|$)' + '|\\S+?(\\s|$)';
        var array = str.match(RegExp(regex, 'g'));
        var frase = "";

        for (var i = 0; i < array.length; i++) {
            frase += array[i] + "\n";
        }
        ;
        var retorno = "<div style='white-space:initial'>" + frase + "</div>";
        return retorno;
    }

    colNames = ['Programa', 'Projeto', 'Gerente', 'Escritório Resp.', 'Código', 'Publicado', 'Início', 'Término Meta', 'T&eacute;rmino Tend&ecirc;ncia', 'Previsto', 'Concluído', 'Atraso', 'Prazo', 'Risco', 'Último Relat&oacute;rio', 'Situação', 'Operações'];
    colModel = [
        {
            name: 'nomprograma',
            index: 'nomprograma',
            align: 'center',
            width: 25,
            hidden: false,
            search: false,
            formatter: verificaTamanhoTextoPrograma
        }, {
            name: 'nomprojeto',
            index: 'nomprojeto',
            align: 'center',
            width: 60,
            hidden: false,
            search: false,
            formatter: verificaTamanhoTextoProjeto
        }, {
            name: 'idgerenteprojeto',
            index: 'idgerenteprojeto',
            align: 'center',
            width: 60,
            hidden: false,
            search: false,
            formatter: verificaTamanhoTextoPrograma
        }, {
            name: 'nomescritorio',
            index: 'nomescritorio',
            align: 'center',
            width: 25,
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
            name: 'datfim',
            index: 'datfim',
            align: 'center',
            width: 20,
            search: true,
        }, {
            name: 'datfimplano',
            index: 'datfimplano',
            align: 'center',
            width: 20,
            search: true
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
            formatter: formatadorImgAtrazo
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            hidden: true,
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
            name: 'ultimoacompanhamento',
            index: 'ultimoacompanhamento',
            width: 28,
            align: 'center',
            search: true,
            sortable: true
        }, {
            name: 'situacao',
            index: 'situacao',
            width: 15,
            search: true,
            //sortable: false,
            formatter: formatadorSituacao
        }, {
            name: 'id',
            index: 'id',
            width: 20,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];

    var urlPesq = (($('#codacao').val() != '') && ($('#codobjetivo').val() != '')) ?
        (base_url + "/projeto/statusreport/pesquisarjson?codobjetivo=" + $('#codobjetivo').val() + "&codacao=" + $('#codacao').val()) :
        (($('#codobjetivo').val() != '') ?
            (base_url + "/projeto/statusreport/pesquisarjson?codobjetivo=" + $('#codobjetivo').val()) :
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
        gridComplete: function () {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
        },
        loadComplete: function (e) {
            if (e.records == 0) {
                $.pnotify.defaults.history = false;
                $.pnotify({
                    title: 'Aten\u00E7\u00E3o:',
                    text: 'Este Escrit\u00F3rio de Projeto nao possui nenhum projeto p\u00fablico cadastrado.',
                })
            }
            //console.log(e.records);                
        },
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
            url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    resizeGrid();
});