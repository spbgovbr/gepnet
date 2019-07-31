$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;
    $form = $("form#form-pesquisar-projeto");
    $formPortfolio = $("form#form-portfolio");

//            //Função para enviar o postiti para o gerenciar.
//                $(".postit").click(function(){
//                 var postiti = $(this).attr("id");
//                    //Enviando os id via ajax do jquery
//                 $.ajax({
//                    url: base_url + '/projeto/statusreport/pesquisarjson/idobjetivo/' + postiti,
//                    dataType: "json",
//                    type: "POST",
//                    data: "idobjetivo=" + postiti,
//                    success: function() {
//
//                        $.getJSON(base_url+"projeto/gerencia","#list2");
//                         },
//    });
//    });
    function formatadorImgPrazo(cellvalue, options, rowObject) {
        var retorno = '-';
        if (rowObject[8] >= rowObject[14]) {
            var retorno = '<span class="badge badge-important" title=' + rowObject[8] + '>P</span>';
        } else if (rowObject[8] > 0) {
            var retorno = '<span class="badge badge-warning" title=' + rowObject[8] + '>P</span>';
        } else {
            var retorno = '<span class="badge badge-success" title=' + rowObject[8] + '>P</span>';
        }
        if (rowObject[8] === "-") return rowObject[8];
        return retorno;
    }

    function formatadorImgRisco(cellvalue, options, rowObject) {
        var retorno = '-';
        if (rowObject[9] === '1') {
            var retorno = '<span class="badge badge-success">R</span>';
        } else if (rowObject[9] === '2') {
            var retorno = '<span class="badge badge-warning">R</span>';
        } else if (rowObject[9] === '3') {
            var retorno = '<span class="badge badge-important">R</span>';
        }
        return retorno;
    }

    function formatadorAtraso(cellvalue, options, rowObject) {
        var retorno = '-';
        if (rowObject[8] || rowObject[8] === 0) {
            retorno = rowObject[8];
        }
        return retorno;
    }

    function formatadorLinkProjeto(cellvalue, options, rowObject) {
        return '<a href="' + base_url + '/projeto/statusreport/detalhar/idprojeto/' + rowObject[14] + '" style="color: #0088CC">' + rowObject[1] + '</a>';
    }

    colNames = ['Programa', 'Projeto', 'Status', 'Gerente', 'Início', 'Término Meta', 'Término Tendência', 'Concluído', 'Previsto', 'Prazo', 'Risco', 'Atraso', 'Último Acomp.'];
    colModel = [{
        name: 'nomprograma',
        index: 'nomprograma',
        width: 15,
        search: true
    }, {
        name: 'nomprojeto',
        index: 'nomprojeto',
        width: 15,
        search: true,
        formatter: formatadorLinkProjeto
    }, {
        name: 'domstatusprojeto',
        index: 'domstatusprojeto',
        width: 10,
        search: true/*,
                formatter: formatadorLinkProjeto*/
    }, {
        name: 'idgerenteprojeto',
        index: 'idgerenteprojeto',
        width: 15,
        align: 'center',
        hidden: false,
        search: true
    }, {
        name: 'datinicio',
        index: 'datinicio',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'datfim',
        index: 'datfim',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'datfimplano',
        index: 'datfimplano',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'numpercentualconcluido',
        index: 'numpercentualconcluido',
        width: 5,
        align: 'center',
        search: true
    }, {
        name: 'numpercentualprevisto',
        index: 'numpercentualprevisto',
        width: 5,
        align: 'center',
        search: true
    }, {
        name: 'Prazo',
        index: 'prazo',
        width: 5,
        align: 'center',
        search: false,
        formatter: formatadorImgPrazo
    }, {
        name: 'Risco',
        index: 'risco',
        width: 5,
        align: 'center',
        search: false,
        formatter: formatadorImgRisco
    }, {
        name: 'Atraso',
        index: 'atraso',
        width: 5,
        align: 'center',
        search: false,
        formatter: formatadorAtraso
    }, {
        name: 'datacompanhamento',
        index: 'datacompanhamento',
        width: 10,
        align: 'center',
        search: true
    }];

    grid = jQuery("#list2").jqGrid({

        url: base_url + "/planejamento/portfolio/pesquisarprojetojson?" + $form.serialize(),
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
        sortname: 'nomprograma',
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

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planejamento/portfolio/pesquisarprojetojson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();

    function calculaAltura() {
        var alturaMax = 0;
        $('.box-objetivo').each(function () {
            if ($(this).height() > alturaMax) {
                alturaMax = $(this).height();
            }
        });
        $('.box-objetivo').css('height', alturaMax);
    }

//    $("#enviar").click(function(e) {
//            var codescritorio = $('#idescritorio').serialize();
//            $.ajax({
//                type: 'GET',
//                dataType: 'json',
//                url: base_url + "/projeto/statusreport/pesquisarjson?",
//                async: true,
//                data: codescritorio,
//                 success: function() {
//                    //location.reload(); //= base_url + "/projeto/statusreport/" ;
//                }
//            });
//    });

    calculaAltura();


});