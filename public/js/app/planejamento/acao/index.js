$(function () {


    var
        grid = null,
        colModel = null,
        colNames = null,
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar');


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Ação - Detalhar',
        width: '810px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Ação - Editar',
        width: '1185px',
        modal: false,
        open: function (event, ui) {
//            $('#')
//                    .attr('readonly', true)
//                    .focus(function() {
//                $(this).blur();
//            });
            $("form#form-acao").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/planejamento/acao/edit/format/json", "form#form-acao", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
            //$("form#form-pessoa input").trigger('focusout');
        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Salvar': function () {
                //console.log('submit');
                //$formEditar.on('submit');
                $("form#form-acao").trigger('submit');
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
            //data: $formEditar.serialize(),
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

    $(document.body).on('click', "a.excluir, a.editar", function (event) {
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
            //data: $formEditar.serialize(),
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

    var $form = $("form#acao-pesquisar");


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/planejamento/acao/edit',
                detalhar: base_url + '/planejamento/acao/detalhar'
            };
        params = '/idacao/' + r[6];
//        console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    colNames = ['Ação', 'Cadastrador', 'Data Cadastro', 'Ativo', 'Escritório', 'Operações'];
    colModel = [{
        name: 'nomacao',
        index: 'nomacao',
        width: 35,
        search: true
    }, {
        name: 'idcadastrador',
        index: 'idcadastrador',
        width: 45,
        hidden: false,
        search: false
    }, {
        name: 'datcadastro',
        index: 'datcadastro',
        width: 12,
        search: true
    }, {
        name: 'flaativo',
        index: 'flaativo',
        width: 5,
        search: true
    }, {
        name: 'codescritorio',
        index: 'codescritorio',
        width: 10,
        search: true
    }, {
        name: 'idacao',
        index: 'idacao',
        width: 16,
        search: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/planejamento/acao/pesquisarjson/idobjetivo/" + $('#idobjetivo').val(),
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
        sortname: 'nomacao',
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

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planejamento/acao/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });

    resizeGrid();
});