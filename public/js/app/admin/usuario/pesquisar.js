function enviar(url, form, callback) {
    $.ajax({
        url: base_url + url,
        dataType: 'json',
        type: 'POST',
        data: $(form).serialize(),
        //processData:false,
        success: function (data) {
            if (typeof data.msg.text != 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (callback && typeof (callback) === "function") {
                callback();
            }
        },
        error: function () {
            $.pnotify(error_notify);
        }
    });
}

$(function () {
    $.pnotify.defaults.history = false;

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $form = $("form#usuario"),
        $dialogSenha = $('#dialog-senha'),
        $dialogEditar = $('#dialog-editar'),
        $dialogExcluir = $('#dialog-excluir'),
        error_notify = {
            text: 'Falha ao enviar a requisição',
            type: 'error',
            hide: false,
            closer: true,
            closer_hover: true
        }
    ;

    $dialogSenha.dialog({
        autoOpen: false,
        title: 'Usuário - Alterar senha',
        width: 'auto',
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Enviar': function () {
                enviar("/admin/usuario/alterar-senha/format/json", 'form#usuario-senha');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Usuário - Editar',
        width: 'auto',
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Enviar': function () {
                enviar("/admin/usuario/editar/format/json", 'form#usuario-editar');
            }
        }
    });

    $dialogExcluir.dialog({
        autoOpen: false,
        title: 'Usuário - Excluir',
        width: '850px',
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Enviar': function () {
                enviar("/admin/usuario/excluir/format/json", 'form#usuario-excluir', function () {
                    grid.trigger("reloadGrid");
                });
            }
        }
    });

    $(document.body).on('click', 'a.senha-default', function (event) {
        event.preventDefault();
        var
            $this = $(this),
            val = $this.text();
        $("#ds_senha, #ds_senha_repeat").val(val);
    });

    $(document.body).on('click', "a.senha, a.editar, a.excluir", function (event) {
        event.preventDefault();
        var
            $this = $(this);

        selRowId = grid.jqGrid('getGridParam', 'selrow');
        no_pessoa = grid.jqGrid('getCell', selRowId, 'no_pessoa');
        cd_matricula = grid.jqGrid('getCell', selRowId, 'cd_matricula');
        $dialog = $($this.data('target'));

        $dialog.empty().dialog('close');

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'POST',
            async: true,
            cache: true,
            data: {'cd_matricula': cd_matricula, 'no_pessoa': no_pessoa},
            //processData:false,
            success: function (data) {
                $dialog.html(data).dialog('open');
                if ($("select#cd_lotacao").length > 0) {
                    $("select#cd_lotacao").select2({
                        placeholder: "Selecione",
                        allowClear: true
                    });
                }

                if ($('#ds_senha').length > 0) {
                    $('#ds_senha').siblings('p').html(function () {
                        return $(this).html() + ' <a class="senha-default btn btn-link" href="#">CTIDPF</a>';
                    });
                }
            },
            error: function () {
                $.pnotify(error_notify);
            }
        });
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                senha: base_url + '/admin/usuario/form-senha',
                editar: base_url + '/admin/usuario/form-editar',
                excluir: base_url + '/admin/usuario/form-excluir'
            };
        params = '/cd_pessoa/' + r[0] + '/cd_lotacao/' + r[4];
        //console.log(r);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class=" icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class=" icon-trash"></i></a>' +
            '<a data-target="#dialog-senha" class="btn actionfrm senha" title="Alterar senha" data-id="' + cellvalue + '" href="' + url.senha + params + '"><i class=" icon-lock"></i></a>';
    }

    colNames = ['Ações', 'Matrícula', 'Nome', 'Usuário', 'Lotação'];
    colModel = [{
        name: 'cd_pessoa',
        index: 'cd_pessoa',
        width: 90,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }, {
        name: 'cd_matricula',
        index: 'cd_matricula',
        width: 100,
        search: false,
        sortable: true/*,
        formatter: formatadorLink*/
    }, {
        name: 'no_pessoa',
        index: 'no_pessoa',
        width: 400,
        search: true
    }, {
        name: 'ds_usuario',
        index: 'ds_usuario',
        width: 200,
        hidden: false,
        search: false
    }, {
        name: 'cd_lotacao',
        index: 'cd_lotacao',
        hidden: true,
        search: false
    }];

    grid = jQuery("#list2").jqGrid({
        caption: "Lista de Usuários",
        url: base_url + "/admin/usuario/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '400px',
        colNames: colNames,
        colModel: colModel,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'no_pessoa',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            // console.log('teste');
            // $("a.actionfrm").tooltip();
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

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/admin/usuario/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        // $("a.actionfrm").tooltip();
        return false;
    });

//    $.formErrors = function(data) {
//        $.each(data, function(element, errors) {
//            var ul = $("<ul>").attr("class", "errors help-inline");
//            $.each(errors, function(name, message) {
//                ul.append($("<li>").text(message));
//            });
//            $("#" + element).after(ul);
//        });
//    }
});