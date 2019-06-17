$(function () {
    var
        grid = null,
        colModel = null,
        colNames = null,
        idplanodeacaoorigem = $("#idplanodeacaoorigem").val(),
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/planodeacao/cronograma/pesquisarplanodeacaojson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            importar: {
                dialog: $('#dialog-importar')
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

    actions.importar.dialog.dialog({
        autoOpen: false,
        title: 'Cronograma - Importar',
        width: 850,
        modal: false,
        buttons: {
            'Importar': function () {
                enviar_ajax("/planodeacao/cronograma/importar-cronograma/format/json", "form#importar-cronograma", function () {
                });
                $(this).dialog('close');
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

    $(document.body).on('click', ".importar", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $idplanodeacaoimportar = $(this).attr('data-id');
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            data: {
                idplanodeacaoimportar: $idplanodeacaoimportar
            },
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.importar.dialog.html(data).dialog('open');
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
                importar: base_url + '/planodeacao/cronograma/importar-cronograma'
            };
        params = '/idplanodeacao/' + r[5] + '/idplanodeacaoorigem/' + $("#idplanodeacaoorigem").val();

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Ver o Cronograma" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-importar" class="btn actionfrm importar" title="Importar o Cronograma" data-id="' + cellvalue + '" href="' + url.importar + params + '"><i class="icon-calendar"></i></a>';
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
            TemplateManager.get('dados-planodeacao', function (tpl) {
                $("#dados-planodeacao").html(tpl(data.planodeacao));
            });
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
