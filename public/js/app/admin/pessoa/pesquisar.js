function enviar_ajax(url, form, callback) {
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
            if (callback && typeof (callback) === "function") {
                callback();
            }
            $.pnotify(data.msg);
        },
        error: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    });
}

$(function () {
    $.pnotify.defaults.history = false;
    var
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogCadastrar = $('#dialog-cadastrar'),
        $dialogExcluir = $('#dialog-excluir'),
        $formUsuario = $('form#usuario');

    $dialogCadastrar.dialog({
        autoOpen: false,
        title: 'Usuário - Incluir',
        width: 'auto',
        modal: false,
        close: function (event, ui) {
            $dialogCadastrar.empty();
        },
        open: function (event, ui) {
            //console.log('open');
            $('form#usuario').validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    enviar_ajax("/admin/usuario/cadastrar/format/json", 'form#usuario', function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Enviar': function () {
                //console.log('submit');
                $('form#usuario').trigger('submit');
            }
        }
    });

    $dialogExcluir.dialog({
        autoOpen: false,
        title: 'Usuário - Excluir',
        width: '850px',
        modal: false,
        close: function (event, ui) {
            $dialogExcluir.empty();
        },
        open: function (event, ui) {

        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Excluir': function () {
                enviar_ajax("/admin/usuario/excluir/format/json", 'form#usuario-excluir', function () {
                    grid.trigger("reloadGrid");
                });
            }
        }
    });

    $(document.body).on('click', 'a.senha-default', function (event) {
        //console.log('teste');
        event.preventDefault();
        var
            $this = $(this),
            val = $this.text();
        $this.parents('.control-group').find('input#ds_senha').val(val);
    });

    $(document.body).on('click', "a.incluir, a.excluir", function (event) {
        event.preventDefault();
        var
            $this = $(this);

        selRowId = grid.jqGrid('getGridParam', 'selrow');
        cd_matricula = grid.jqGrid('getCell', selRowId, 'cd_matricula');
        no_pessoa = grid.jqGrid('getCell', selRowId, 'no_pessoa');
        $dialog = $($this.data('target'));

        if ($dialogCadastrar.dialog('isOpen')) {
            $dialogCadastrar.dialog('close');
        }

        if ($dialogExcluir.dialog('isOpen')) {
            $dialogExcluir.dialog('close');
        }

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
                if ($this.is('.incluir')) {
                    $("#cd_lotacao").select2({
                        placeholder: "Selecione",
                        allowClear: true
                    });
                    $('#ds_senha').siblings('p').html(function () {
                        return $(this).html() + ' <a class="senha-default btn btn-link" href="#">CTIDPF</a>';
                    });
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false,
                    closer: true,
                    closer_hover: true
                });
            }
        });
    });

    var $form = $("form#pessoa");

    function formatadorLink(cellvalue, options, rowObject) {
        var
            r = rowObject,
            cadastrado = 1;
        params = '',
            link = '',
            url = {
                incluir: base_url + '/admin/usuario/form-incluir',
                excluir: base_url + '/admin/usuario/form-excluir'
            };
        params = '/cd_pessoa/' + r[0];
        cadastrado = parseInt(r[4]);
        link = '<a data-target="#dialog-excluir" class="btn btn-danger actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash icon-white"></i></a>';
        if (cadastrado == 0) {
            link = '<a data-target="#dialog-cadastrar" class="btn actionfrm incluir" title="Incluir" data-id="' + cellvalue + '" href="' + url.incluir + params + '"><i class="icon-plus-sign"></i></a>';
        }
        return link;
    }

    colNames = ['Ações', 'Matrícula', 'Nome', 'Nome de Guerra'];
    colModel = [{
        name: 'cd_pessoa',
        index: 'cd_pessoa',
        width: 50,
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
        name: 'no_guerra',
        index: 'no_guerra',
        width: 200,
        hidden: false,
        search: false
    }];

    grid = jQuery("#list2").jqGrid({
        caption: "Lista de Pessoas",
        url: base_url + "/admin/pessoa/pesquisarjson",
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

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/admin/pessoa/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
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
//    };
});