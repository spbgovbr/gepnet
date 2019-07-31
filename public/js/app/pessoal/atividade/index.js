$(function () {


    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        //$dialogExcluir = $('#dialog-excluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formEditar = $("form#form-atividade-editar");


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Atividade - Detalhar',
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
        title: 'Atividade - Editar',
        width: '1213px',
        modal: false,
        open: function (event, ui) {
            $("form#form-atividade-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/pessoal/atividade/edit/format/json", "form#form-atividade-editar", function () {
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
                $("form#form-atividade-editar").trigger('submit');
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

    var $form = $("form#atividade-pesquisar");


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/pessoal/atividade/edit',
                //excluir: base_url + '/pessoal/atividade/excluir',
                detalhar: base_url + '/pessoal/atividade/detalhar',
            };
        params = '/idatividade/' + r[10];
//        console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    colNames = ['Atividade', 'Início', 'Fim Meta', 'Fim Real', 'Demandante', 'Responsável', '%', 'Contínua?', 'Atualização', 'Operações'];
    colModel = [{
        name: 'nomatividade',
        index: 'ativ.nomatividade',
        width: 30,
        search: true
    }, {
        name: 'datinicio',
        index: 'ativ.datinicio',
        width: 10,
        align: 'center',
        hidden: false,
        search: true
    }, {
        name: 'datfimmeta',
        index: 'ativ.datfimmeta',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'datfimreal',
        index: 'ativ.datfimreal',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'nomcadastrador',
        index: 'nomcadastrador',
        width: 20,
        search: true
    }, {
        name: 'nomresponsavel',
        index: 'nomresponsavel',
        width: 20,
        search: true
    }, {
        name: 'numpercentualconcluido',
        index: 'numpercentualconcluido',
        width: 5,
        align: 'center',
        search: true
    }, {
        name: 'flacontinua',
        index: 'flacontinua',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'datatualizacao',
        index: 'ativ.datatualizacao',
        width: 10,
        align: 'center',
        search: true
    }, {
        name: 'idatividade',
        index: 'idatividade',
        width: 10,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pessoal/atividade/pesquisarjson",
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
        sortname: 'nomatividade',
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
            url: base_url + "/pessoal/atividade/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();
});

