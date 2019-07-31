$(function () {


    var
        grid = null,
        colModel = null,
        colNames = null,
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar');


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Objetivo - Detalhar',
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
        title: 'Objetivo - Editar',
        width: '1185px',
        modal: false,
        open: function (event, ui) {
            $("form#form-objetivo").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/planejamento/index/edit/format/json", "form#form-objetivo", function () {
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
                $("form#form-objetivo").trigger('submit');
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

    var $form = $("form#objetivo-pesquisar");


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/planejamento/index/edit',
                detalhar: base_url + '/planejamento/index/detalhar',
                acao: base_url + '/planejamento/acao/index',
                //arquivo: base_url + '/processo/index/editar-arquivo'
            };
        params = '/idobjetivo/' + r[5];
//        console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#" class="btn actionfrm arquivo" title="Gerenciar Ação" data-id="' + cellvalue + '" href="' + url.acao + params + '"><i class="icon-upload"></i></a>'
            ;
    }

    colNames = ['Nome Objetivo', 'Cadastrador', 'Data Cadastro', 'Ativo', 'Escritório', 'Operações'];
    colModel = [{
        name: 'nomobjetivo',
        index: 'nomobjetivo',
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
        index: 'obj.datcadastro',
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
        name: 'idobjetivo',
        index: 'idobjetivo',
        width: 16,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/planejamento/index/pesquisarjson",
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
        sortname: 'nomobjetivo',
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
            url: base_url + "/planejamento/index/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });

    resizeGrid();
});