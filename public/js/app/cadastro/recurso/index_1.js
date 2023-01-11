$(function () {
    //var idperfil = $("select#idperfil").val();
    permissao.init();
    /*
    $('#container-layout').layout({
        closable: true,
        resizable: true,
        slidable: true
    });
    */
    $("#tabs").tabs();

    //XXXXXXXXXX NOVOS RECURSOS XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    tableToGrid("table#novos-recursos",
        {
            width: '1170',
            height: '300px',
            rownumbers: true
        });
    $("#novos-recursos").jqGrid('setLabel', 'rn', 'Ord');

    $("#novos-recursos").on("click", "a.cadastrar_recurso", function (event) {
        event.preventDefault();
        var $this = $(this);
        permissao.cadastrar($this);
    });

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
        //caption: "Documentos",
        url: base_url + "/cadastro/recurso/pesquisar/format/json",
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
    /*
     actions.pesquisar.form.on('submit', function(e) {
     e.preventDefault();
     grid.setGridParam({
     url: base_url + "/cadastro/recurso/pesquisar/format/json?" + $("form#form-pesquisar").serialize(),
     page: 1
     }).trigger("reloadGrid");
     //$("a.actionfrm").tooltip();
     return false;
     });
     */
    //XXXXXXXXXX PERMISSAO XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


    $(document.body).on('click', "a.permissao-toggle", function (event) {
        event.preventDefault();
        var
            $this = $(this);
        permissao.toggle($this);
    });

    function permissaoFormatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                permissao: base_url + '/cadastro/recurso/toggle/format/json'
            };
        // params = '/idpermissao/' + r[4];
        //console.log(rowObject);
        var perm = r[4];
        //console.log(permissoes.length);
        if (permissao.isAllow(perm)) {
            //console.log('true');
            //console.log(perm);
            return '<a class="btn btn-success permissao-toggle" title="Revogar" data-permission="deny" data-id="' + r[4] + '" href="' + permissao.urlAllow + '"><i class="icon-ok icon-white "></i></a>';
        } else {
            return '<a class="btn btn-danger permissao-toggle" title="Conceder" data-permission="allow" data-id="' + r[4] + '" href="' + permissao.urlDeny + '"><i class="icon-off icon-white "></i></a>';
            //console.log('false');
            //console.log(perm);
        }


    }

    permissaoColNames = ['Recurso', 'Permissao', 'Descrição', 'Allow', 'Operações'];
    permissaoColModel = [{
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
        name: 'allow',
        index: 'allow',
        width: 30,
        search: false,
        sortable: false,
        hidden: true
    }, {
        name: 'idpermissao',
        index: 'idpermissao',
        width: 30,
        search: false,
        sortable: false,
        formatter: permissaoFormatadorLink
    }];

    gridPermissao = jQuery("#list3").jqGrid({
        //caption: "Documentos",
        url: base_url + "/cadastro/recurso/permissao/format/json",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '300px',
        colNames: permissaoColNames,
        colModel: permissaoColModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager3',
        sortname: 'idpermissao',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
        }
    });

    //grid.jqGrid('filterToolbar');
    gridPermissao.jqGrid('navGrid', '#pager3', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    gridPermissao.jqGrid('setLabel', 'rn', 'Ord');

    $("select#idperfil").select2().change(function () {
        permissao.loadPermissions();
        gridPermissao.setGridParam({
            url: base_url + "/cadastro/recurso/permissao/format/json?" + $("form#form-perfil-permissao").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });
    // $("select#idperfil").change();


    /*
     actions.pesquisar.form.on('submit', function(e) {
     e.preventDefault();
     gridPermissao.setGridParam({
     url: base_url + "/cadastro/recurso/pesquisar/format/json?" + $("form#form-pesquisar").serialize(),
     page: 1
     }).trigger("reloadGrid");
     //$("a.actionfrm").tooltip();
     return false;
     });
     */

});