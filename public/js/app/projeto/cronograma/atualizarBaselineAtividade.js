$(function () {
    var
        msgerror = null,
        grid = null,
        colModel = null,
        colNames = null,
        //idprojeto = $("#idprojetoorigem").val(),
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/projeto/cronograma/pesquisarprojetojson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            copiar: {
                dialog: $('#dialog-copiar')
            }
        };
    msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
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
                enviar_ajax("/projeto/cronograma/copiar-cronograma/format/json", "form#copiar-cronograma", function () {
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
                    text: msgerror,
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
            $idprojetocopiar = $(this).attr('data-id');

        //console.log($(this));
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            data: {
                idprojetocopiar: $idprojetocopiar
            },
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.copiar.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: msgerror,
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
                detalhar: base_url + '/projeto/cronograma/detalhar',
                copiar: base_url + '/projeto/cronograma/copiar-cronograma'
            };
        params = '/idprojeto/' + r[5] + '/idprojetoorigem/' + $("#idprojetoorigem").val();

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Ver o Cronograma" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-copiar" class="btn actionfrm copiar" title="Copiar o Cronograma" data-id="' + cellvalue + '" href="' + url.copiar + params + '"><i class="icon-calendar"></i></a>';
    }


    colNames = ['Programa', 'Projeto', 'Gerente', 'Escritório', 'Código', 'Operações'];
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
            name: 'idprojeto',
            index: 'idprojeto',
            width: 10,
            search: false,
            sortable: false,
            align: 'center',
            formatter: formatadorLink
        }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/projeto/cronograma/pesquisarprojetojson",
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
            url: base_url + "/projeto/cronograma/pesquisarprojetojson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");

        return false;

    });

    $("#accordion").accordion();
    $.ajax({
        url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            idprojeto: idprojetoorigem
        },
        success: function (data) {
            var tplProjeto = Handlebars.compile($('#tpl-projeto').html());
            $('#dados-projeto').html(tplProjeto(data.projeto));
        },
        error: function () {
            $.pnotify({
                text: msgerror,
                type: 'error',
                hide: false
            });
        }
    });

    resizeGrid();
});
