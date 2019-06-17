function selectRow(row) {
    //console.log(row);
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
        colNames = null,
        $dialogExcluir = $('#dialog-excluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formEditar = $("form#form-projetoprocesso");


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Projeto de Processo - Detalhar',
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
        title: 'Projeto de Pocesso - Editar',
        width: '1185px',
        modal: false,
        open: function (event, ui) {
            $("form#form-projetoprocesso").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/processo/projeto/edit/format/json", "form#form-projetoprocesso", function () {
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
                //$formEditar.on('submit');
                $("form#form-projetoprocesso").trigger('submit');
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

    var $form = $("form#projeto-pesquisar");


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/processo/projeto/edit',
                detalhar: base_url + '/processo/projeto/detalhar',
                acao: base_url + '/processo/pacao/index'
            };
        params = '/idprojetoprocesso/' + r[9];
//        console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#" class="btn actionfrm arquivo" title="Incluir Ação" data-id="' + cellvalue + '" href="' + url.acao + params + '"><i class="icon-upload"></i></a>'
            ;
    }

    //idpessoa, nompessoa, numcpf, desunidade, nummatricula, desfuncao
    colNames = ['Ano', 'Processo', 'Diretoria', 'Responsável', 'Início Previsto', 'Término Previsto', 'Orçamento', 'Situação', 'Data Situação', 'Operações'];
    colModel = [{
        name: 'numano',
        index: 'numano',
        width: 40,
        search: true
    }, {
        name: 'nomprocesso',
        index: 'nomprocesso',
        width: 40,
        search: true
    }, {
        name: 'diretoria',
        index: 'nomsetor',
        width: 40,
        search: true
    }, {
        name: 'idresponsavel',
        index: 'idresponsavel',
        width: 40,
        search: true
    }, {
        name: 'datinicioprevisto',
        index: 'proj.datinicioprevisto',
        width: 40,
        search: true
    }, {
        name: 'datterminoprevisto',
        index: 'proj.datterminoprevisto',
        width: 40,
        search: true
    }, {
        name: 'vlrorcamento',
        index: 'vlrorcamento',
        width: 30,
        search: true
    }, {
        name: 'domsituacao',
        index: 'domsituacao',
        width: 30,
        search: true,
    }, {
        name: 'datsituacao',
        index: 'proj.datsituacao',
        width: 30,
        search: false,
    }, {
        name: 'idprojetoprocesso',
        index: 'idprojetoprocesso',
        width: 40,
        search: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/processo/projeto/pesquisarjson",
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
            url: base_url + "/processo/projeto/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });

    $(document.body).on('click', ".pessoa-button", function (event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid",
                type: "GET",
                dataType: "html",
                success: function (html) {
                    $(".grid-append").append(html).slideDown('fast');
                }
            });
            $('.pessoa-button')
                .off('click')
                .on('click', function () {
                    var $this = $(this);
                    $(".grid-append").slideDown('fast', function () {
                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                        $this.closest('.control-group').addClass('input-selecionado');
                    });
                });
        }
    });

    resizeGrid();
});