$(function () {


    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogExcluir = $('#dialog-excluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formEditar = $("form#form-processo-editar");


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Processo - Detalhar',
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
        title: 'Processo - Editar',
        width: '1213px',
        modal: false,
        open: function (event, ui) {
            $("form#form-processo-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/processo/index/edit/format/json", "form#form-processo-editar", function () {
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
                $("form#form-processo-editar").trigger('submit');
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

    var $form = $("form#processo-pesquisar");


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/processo/index/edit',
                excluir: base_url + '/processo/index/excluir',
                detalhar: base_url + '/processo/index/detalhar',
                //arquivo: base_url + '/processo/index/editar-arquivo'
            };
        params = '/idprocesso/' + r[8];
//        console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    //idpessoa, nompessoa, numcpf, desunidade, nummatricula, desfuncao
    colNames = ['Processo', 'Diretoria', 'Dono', 'Gestor', 'Executor', 'Consultor', 'Atualização', 'Validade', 'Operações'];
    colModel = [{
        name: 'nomprocesso',
        index: 'nomprocesso',
        width: 40,
        search: true
    }, {
        name: 'nomsetor',
        index: 'nomsetor',
        width: 30,
        hidden: false,
        search: false
    }, {
        name: 'iddono',
        index: 'dono',
        width: 40,
        search: true
    }, {
        name: 'idgestor',
        index: 'gestor',
        width: 40,
        search: true
    }, {
        name: 'idexecutor',
        index: 'executor',
        width: 40,
        search: true
    }, {
        name: 'idconsultor',
        index: 'consultor',
        width: 40,
        search: true
    }, {
        name: 'nomatualizacao',
        index: 'proc.datatualizacao',
        width: 30,
        search: true
    }, {
        name: 'numvalidade',
        index: 'numvalidade',
        width: 30,
        search: true,
    }, {
        name: 'idprocesso',
        index: 'idprocesso',
        width: 30,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/processo/index/pesquisarjson",
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
        sortname: 'nomprocesso',
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
            url: base_url + "/processo/index/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });

    resizeGrid();
});