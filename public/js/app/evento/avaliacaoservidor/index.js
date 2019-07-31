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
        title: 'Avaliação Servidor - Detalhar',
        width: '1213px',
        height: '680',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Avaliação Servidor - Editar',
        width: '1213px',
        height: '680',
        modal: false,
        open: function (event, ui) {
            $("form#form-avaliacao-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/evento/avaliacaoservidor/editar/format/json", "form#form-avaliacao-editar", function () {
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
                $("form#form-avaliacao-editar").trigger('submit');
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
                editar: base_url + '/evento/avaliacaoservidor/editar',
                detalhar: base_url + '/evento/avaliacaoservidor/detalhar',
            };
        params = '/ideventoavaliacao/' + r[8];


        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    function formatadorMedia(cellvalue, options, rowObject) {
        return number_format(cellvalue, 2);
    }

    colNames = ['Evento', 'Tipo', 'Avaliado', 'Avaliador', 'Nota Avaliador', 'Média', 'Média final', 'Data', 'Operações'];
    colModel = [{
        name: 'nomevento',
        index: 'nomevento',
        width: 20,
        search: true
    }, {
        name: 'noavaliacao',
        index: 'noavaliacao',
        width: 15,
        align: 'center',
        hidden: false,
        search: true
    }, {
        name: 'nomavaliado',
        index: 'nomavaliado',
        width: 20,
        align: 'center',
        search: true
    }, {
        name: 'nomavaliador',
        index: 'nomavaliador',
        width: 20,
        align: 'center',
        search: true
    }, {
        name: 'numnotaavaliador',
        index: 'numnotaavaliador',
        width: 6,
        align: 'center',
        search: true
    }, {
        name: 'nummedia',
        index: 'nummedia',
        width: 6,
        align: 'center',
        search: true,
        formatter: formatadorMedia
    }, {
        name: 'nummediafinal',
        index: 'nummediafinal',
        width: 6,
        align: 'center',
        search: true,
        formatter: formatadorMedia
    }, {
        name: 'datcadastro',
        index: 'datcadastro',
        width: 7,
        align: 'center',
        search: true
    }, {
        name: 'ideventoavaliacao',
        index: 'ideventoavaliacao',
        width: 8,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/evento/avaliacaoservidor/pesquisarjson",
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

    var $form = $("form#form-avaliacao-pesquisar");
    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/evento/avaliacaoservidor/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();
});

