function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;
    $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formPortfolio = $("form#form-portfolio");

    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Portfólio - Detalhar',
        width: '880px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Portfólio - Editar',
        width: '1100px',
        modal: false,
        open: function (event, ui) {
            $("form#form-portfolio-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/planejamento/portfolio/editar/format/json", "form#form-portfolio-editar", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                $("form#form-portfolio-editar").trigger('submit');
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
                $dialogDetalhar.html(data).dialog('open');
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

    $(document.body).on('click', "a.editar", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                $dialog.html(data).dialog('open');
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
                editar: base_url + '/planejamento/portfolio/editar',
                detalhar: base_url + '/planejamento/portfolio/detalhar'
            };
        params = '/idportfolio/' + r[7];

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    function formatadorSituacao(cellvalue, options, rowObject) {
        if (rowObject[6] == 'S') {
            return '<span class="label label-success">Ativo</span>';
        }
        return '<span class="label label-important">Inativo</span>';

    }


    colNames = ['Nome Portfólio', 'Responsável', 'Tipo', 'Escritório', 'Email Escritório', 'Telefone Escritório', 'Situação', 'Operações'];
    colModel = [{
        name: 'noportfolio',
        index: 'noportfolio',
        width: 15,
        search: true
    }, {
        name: 'idresponsavel',
        index: 'idresponsavel',
        width: 15,
        search: true
    }, {
        name: 'tipo',
        index: 'tipo',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'nomescritorio',
        index: 'nomescritorio',
        width: 15,
        align: 'center',
        search: true
    }, {
        name: 'email',
        index: 'email',
        width: 15,
        align: 'center',
        search: true
    }, {
        name: 'telefone',
        index: 'telefone',
        width: 8,
        align: 'center',
        search: false
    }, {
        name: 'ativo',
        index: 'ativo',
        width: 6,
        align: 'center',
        search: true,
        formatter: formatadorSituacao
    }, {
        name: 'idportfolio',
        index: 'idportfolio',
        width: 10,
        align: 'center',
        search: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({

        url: base_url + "/planejamento/portfolio/pesquisarportfoliojson",
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
        sortname: 'noportfolio',
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

    $formPortfolio.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planejamento/portfolio/pesquisarportfoliojson?" + $formPortfolio.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();

});