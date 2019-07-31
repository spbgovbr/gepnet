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
    //console.log('faltam implementar o alterar e o excluir');
    var
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        grid = null,
        $dialogEditar = $('#dialog-editar'),
        $dialogExcluir = $('#dialog-excluir');

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Tipo Documento - Editar',
        width: 'auto',
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Enviar': function () {
                enviar_ajax("/admin/tipodoc/editar/format/json", 'form#tipodoc-editar', function () {
                    grid.trigger("reloadGrid");
                });
            }
        }
    });
    $dialogExcluir.dialog({
        autoOpen: false,
        title: 'Tipo Documento - Excluir',
        width: 'auto',
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            },
            'Enviar': function () {
                enviar_ajax("/admin/tipodoc/excluir/format/json", 'form#tipodoc-excluir', function () {
                    grid.trigger("reloadGrid");
                });
            }
        }
    });

    $("#dti").datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        onClose: function (selectedDate) {
            $("#dtf").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#dtf").datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        onClose: function (selectedDate) {
            $("#dti").datepicker("option", "maxDate", selectedDate);
        }
    });

    $(document.body).on('click', "a.editar, a.excluir", function (event) {
        event.preventDefault();
        var
            $this = $(this);

        selRowId = grid.jqGrid('getGridParam', 'selrow');
        cd_tp_doc = grid.jqGrid('getCell', selRowId, 'cd_tp_doc');
        ds_tp_doc = grid.jqGrid('getCell', selRowId, 'ds_tp_doc');
        $dialog = $($this.data('target'));

        $dialog.dialog('close');
        $dialog.empty();

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'POST',
            async: true,
            cache: true,
            data: {'cd_tp_doc': cd_tp_doc, 'ds_tp_doc': ds_tp_doc},
            success: function (data) {
                $dialog.html(data).dialog('open');
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

    var $form = $("form#tipodoc-pesquisar");

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/admin/tipodoc/form-editar',
                excluir: base_url + '/admin/tipodoc/form-excluir'
            };
        params = '/cd_tp_doc/' + r[0];
        //console.log(rowObject);
        /*
        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params  +'"><i class="icon-edit"></i></a>' +
               '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
        */
        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>';
    }

    function formatadorAtivo(cellvalue, options, rowObject) {
        return (cellvalue == 'S') ? 'Sim' : 'Não';
    }

    colNames = ['Ações', 'Descrição', 'Ativo', 'Atualização'];
    colModel = [{
        name: 'cd_tp_doc',
        index: 'cd_tp_doc',
        width: 38,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }, {
        name: 'ds_tp_doc',
        index: 'ds_tp_doc',
        width: 400,
        search: false,
        sortable: true/*,
        formatter: formatadorLink*/
    }, {
        name: 'st_ativo',
        index: 'st_ativo',
        width: 100,
        search: true,
        formatter: formatadorAtivo
    }, {
        name: 'dt_atualizacao',
        index: 'dt_atualizacao',
        width: 100,
        hidden: false,
        search: false
    }];

    grid = jQuery("#list2").jqGrid({
        caption: "Lista de Tipos de Documentos",
        url: base_url + "/admin/tipodoc/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '400px',
        colNames: colNames,
        colModel: colModel,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'ds_tp_doc',
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
            url: base_url + "/admin/tipodoc/pesquisarjson?" + $form.serialize(),
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
//    }
});