$(function () {
    permissao.init();

    //$('.k-last').remove();

    //XXXXXXXXXX GERENCIAR RECURSOS XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    var
        dialogEditar = $('#dialog-editar'),
        dialogDetalhar = $('#dialog-detalhar'),
        formEditar = $("#form-permissao");

    dialogEditar.dialog({
        autoOpen: false,
        title: 'Permissão - Editar',
        width: '830px',
        modal: false,
        open: function (event, ui) {
            $("#form-permissao").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/cadastro/permissao/editar/format/json", "form#form-permissao", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        close: function (event, ui) {
            dialogEditar.empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Salvar': function () {
                //console.log('submit');
                //$formEditar.on('submit');
                $("#form-permissao").trigger('submit');
            }
        }
    });

    dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Permissão - Detalhar',
        width: '830px',
        modal: false,
        close: function (event, ui) {
            dialogEditar.empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar", function (event) {
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
                dialogEditar.html(data).dialog('open');
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
                dialogDetalhar.html(data).dialog('open');
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

    //$("#novos-recursos").jqGrid('showCol', 'rn');
    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/cadastro/permissao/editar',
                detalhar: base_url + '/cadastro/permissao/detalhar'
            };
        params = '/idpermissao/' + r[3];
        //console.log(rowObject);

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>';
    }

    colNames = ['Recurso', 'Permissao', 'Descrição', 'Operações'];
    colModel = [{
        name: 'ds_recurso',
        index: 'ds_recurso',
        width: 50,
        hidden: false,
        search: false
    }, {
        name: 'no_permissao',
        index: 'no_permissao',
        width: 60,
        hidden: false,
        search: false
    }, {
        name: 'ds_permissao',
        index: 'ds_permissao',
        width: 200,
        search: true
    }, {
        name: 'idpermissao',
        index: 'idpermissao',
        width: 30,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        caption: "Gerênciar Recursos",
        url: base_url + "/cadastro/recurso/pesquisar/format/json",
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
        sortname: 'idpermissao',
        viewrecords: true,
        sortorder: "desc",
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

    $("#resetbutton").click(function () {
        $("select#idpermissao").select2('data', null);
        $("select#idrecurso").select2('data', null);
        //$("select#idperfil").select2('data', null);
    });

    $("select#idpermissao").select2();
    $("select#idrecurso").select2().change(function () {
        $("select#idpermissao").select2('destroy');
        $("select#idpermissao").empty().html('<option value="">Carregando...</option>');
        $.ajax({
            url: base_url + '/cadastro/permissao/retorna-por-recurso/idrecurso/' + $("select#idrecurso").select2("val"),
            dataType: 'json',
            type: 'GET',
            //processData:false,
            success: function (data) {
                //console.log(data);
                var linhas = $("select#idpermissao").get(0);
                linhas.options.length = 0; //reset para zero
                $.each(data, function (i, item) {
                    linhas.options[linhas.length] = new Option(item.nome, item.id);
                });
                $("select#idpermissao").select2();
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

    $("form#form-recurso-pesquisar").on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/cadastro/recurso/pesquisar/format/json?" + $("form#form-recurso-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });

    resizeGrid();
});