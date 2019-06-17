$(function () {
    //var idperfil = $("select#idperfil").val();
    permissao.init();
    permissao.loadPermissions();

    //$('.k-last').remove();
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
            return '<a class="btn btn-success permissao-toggle" title="Revogar" data-permission="deny" data-id="' + r[4] + '" href="' + permissao.urlDeny + '"><i class="icon-ok icon-white "></i></a>';
        } else {
            return '<a class="btn btn-danger permissao-toggle" title="Conceder" data-permission="allow" data-id="' + r[4] + '" href="' + permissao.urlAllow + '"><i class="icon-off icon-white "></i></a>';
            //console.log('false');
            //console.log(perm);
        }
    }

    function no_permissaoFormatador(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                permissao: base_url + '/cadastro/recurso/toggle/format/json'
            };
        // params = '/idpermissao/' + r[4];
        //console.log(rowObject);

        //console.log(permissoes.length);
        return cellvalue;
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
        search: false,
        formatter: no_permissaoFormatador
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
        caption: "Permissões",
        url: base_url + "/cadastro/permissao/pesquisar/format/json",
        datatype: "json",
        mtype: 'post',
        width: '900',
        height: '300px',
        colNames: permissaoColNames,
        colModel: permissaoColModel,
        rownumbers: true,
        rowNum: 50,
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
    $("select#idperfil").select2().change(function () {
        permissao.loadPermissions();
        gridPermissao.setGridParam({
            url: base_url + "/cadastro/permissao/pesquisar/format/json?" + $("form#form-perfil-permissao").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });
    // $("select#idperfil").change();


    $("form#form-perfil-permissao").on('submit', function (e) {
        e.preventDefault();
        gridPermissao.setGridParam({
            url: base_url + "/cadastro/permissao/pesquisar/format/json?" + $("form#form-perfil-permissao").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });

    resizeGrid();


});