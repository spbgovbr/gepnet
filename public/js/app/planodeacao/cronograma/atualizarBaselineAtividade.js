$(function () {
    var
        grid = null,
        colModel = null,
        colNames = null,
        //idplanodeacao = $("#idplanodeacaoorigem").val(),
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/planodeacao/cronograma/pesquisarplanodeacaojson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            copiar: {
                dialog: $('#dialog-copiar')
            }
        };

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Cronograma - Detalhar',
        width: 1100,
        height: 500,
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    actions.copiar.dialog.dialog({
        autoOpen: false,
        title: 'Cronograma - Copiar',
        width: 850,
        modal: false,
        buttons: {
            'Copiar': function () {
                enviar_ajax("/planodeacao/cronograma/copiar-cronograma/format/json", "form#copiar-cronograma", function () {
                });
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function (event) {
        event.preventDefault();
        var
            $this = $(this);
        //console.log($this.attr('href'));
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.detalhar.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(document.body).on('click', "a.copiar", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $idplanodeacaocopiar = $(this).attr('data-id');

        //console.log($(this));
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            data: {
                idplanodeacaocopiar: $idplanodeacaocopiar
            },
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.copiar.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                detalhar: base_url + '/planodeacao/cronograma/detalhar',
                copiar: base_url + '/planodeacao/cronograma/copiar-cronograma'
            };
        params = '/idplanodeacao/' + r[5] + '/idplanodeacaoorigem/' + $("#idplanodeacaoorigem").val();

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Ver o Cronograma" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-copiar" class="btn actionfrm copiar" title="Copiar o Cronograma" data-id="' + cellvalue + '" href="' + url.copiar + params + '"><i class="icon-calendar"></i></a>';
    }


    colNames = ['Programa', 'Plano de Ação', 'Gerente', 'Escritório', 'Código', 'Operações'];
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
            width: 40,
            hidden: false,
            search: false
        }, {
            name: 'idgerenteprojeto',
            index: 'idgerenteprojeto',
            align: 'center',
            width: 40,
            hidden: false,
            search: false
        }, {
            name: 'nomescritorio',
            index: 'nomescritorio',
            align: 'center',
            width: 40,
            hidden: false,
            search: false
        }, {
            name: 'nomcodigo',
            index: 'nomcodigo',
            align: 'center',
            width: 30,
            hidden: false,
            search: false
        }, {
            name: 'idplanodeacao',
            index: 'idplanodeacao',
            width: 10,
            search: false,
            sortable: false,
            align: 'center',
            formatter: formatadorLink
        }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/planodeacao/cronograma/pesquisarplanodeacaojson",
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

    actions.pesquisar.form.on('submit', function (e) {

        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planodeacao/cronograma/pesquisarplanodeacaojson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");

        return false;

    });

    $("#accordion").accordion();
    $.ajax({
        url: base_url + '/planodeacao/cronograma/retorna-planodeacao/format/json',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            idplanodeacao: idplanodeacaoorigem
        },
        success: function (data) {
            var tplPlanodeacao = Handlebars.compile($('#tpl-planodeacao').html());
            $('#dados-planodeacao').html(tplPlanodeacao(data.planodeacao));
        },
        error: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    });

    resizeGrid();
});
