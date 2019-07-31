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
        title: 'Grandes Eventos - Detalhar',
        width: '910px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Grandes Eventos - Editar',
        width: '1213px',
        modal: false,
        open: function (event, ui) {
            $("form#form-evento-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/evento/grandeseventos/editar/format/json", "form#form-evento-editar", function () {
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
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Salvar': function () {
                $("form#form-evento-editar").trigger('submit');
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
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
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
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/evento/grandeseventos/editar',
                detalhar: base_url + '/evento/grandeseventos/detalhar',
            };
        params = '/idevento/' + r[6];


        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    colNames = ['Nome', 'UF', 'Início', 'Fim', 'Dias', 'Responsável', 'Operações'];
    colModel = [{
        name: 'nomevento',
        index: 'nomevento',
        width: 30,
        search: true
    }, {
        name: 'uf',
        index: 'uf',
        width: 5,
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
        name: 'dias',
        index: 'dias',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'nomresponsavel',
        index: 'idresponsavel',
        width: 20,
        align: 'center',
        search: true
    }, {
        name: 'idevento',
        index: 'idevento',
        width: 8,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/evento/grandeseventos/pesquisarjson",
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
        sortname: 'nomevento',
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

    var $form = $("form#form-evento-pesquisar");
    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/evento/grandeseventos/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();
});

