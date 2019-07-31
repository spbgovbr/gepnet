$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar');

    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Setor - Detalhar',
        width: '1000px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Setor - Editar',
        width: '1050px',
        modal: false,
        open: function (event, ui) {
            $("form#form-setor-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/cadastro/setor/editar/format/json", "form#form-setor-editar", function () {
                        grid.trigger("reloadGrid");
                        $(this).dialog('close');
                    });
                }
            });

        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                $("form#form-setor-editar").trigger('submit');
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
                editar: base_url + '/cadastro/setor/editar',
                detalhar: base_url + '/cadastro/setor/detalhar',

            };
        params = '/idsetor/' + r[3];

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>';
    }


    function formatadorSituacao(cellvalue, options, rowObject) {
        if (rowObject[1] == 'S') {
            return '<span class="label label-success">Ativo</span>';
        }
        return '<span class="label label-important">Inativo</span>';

    }


    colNames = ['Nome do Setor', 'Situação', 'Operações'];
    colModel = [{
        name: 'nomsetor',
        index: 'nomsetor',
        hidden: false,
        search: false
    }, {
        name: 'flaativo',
        index: 'flaativo',
        align: 'center',
        width: 20,
        search: true,
        formatter: formatadorSituacao
    }, {
        name: 'idsetor',
        index: 'idsetor',
        width: 18,
        align: 'center',
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/cadastro/setor/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomsetor',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
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

    var $form = $("form#form-setor");

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/cadastro/setor/pesquisarjson?" + $("form#form-setor").serialize(),
            page: 1
        }).trigger("reloadGrid");

    });
    resizeGrid();
});