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
        title: 'Entidade Externa - Detalhar',
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
        title: 'Entidade Externa - Editar',
        width: '1000px',
        modal: false,
        open: function (event, ui) {
            $("form#form-entidadeexterna-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/acordocooperacao/entidadeexterna/editar/format/json", "form#form-entidadeexterna-editar", function () {
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
                $("form#form-entidadeexterna-editar").trigger('submit');
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
                editar: base_url + '/acordocooperacao/entidadeexterna/editar',
                detalhar: base_url + '/acordocooperacao/entidadeexterna/detalhar',
            };
        params = '/identidadeexterna/' + r[3];


        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    colNames = ['Nome da Entidade Externa', 'Cadastrador', 'Data Cadastro', 'Operações'];
    colModel = [{
        name: 'nomentidadeexterna',
        index: 'nomentidadeexterna',
        width: 40,
        search: true
    }, {
        name: 'nompessoa',
        index: 'nompessoa',
        width: 30,
        align: 'center',
        search: true
    }, {
        name: 'datcadastro',
        index: 'datcadastro',
        width: 20,
        align: 'center',
        search: true
    }, {
        name: 'identidadeexterna',
        index: 'identidadeexterna',
        width: 10,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/acordocooperacao/entidadeexterna/pesquisarjson",
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
        sortname: 'nomentidadeexterna',
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

    var $form = $("form#form-entidadeexterna-pesquisar");
    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/acordocooperacao/entidadeexterna/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();
});

