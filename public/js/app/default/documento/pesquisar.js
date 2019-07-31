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

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogExcluir = $('#dialog-excluir'),
        $dialogEditar = $('#dialog-editar'),
        $formEditar = $("form#documento-editar");

    $("#tipodoc_cd_tipodoc,#an_documento").select2({
        placeholder: "Selecione",
        allowClear: true
    });

    //$("#tipodoc_cd_tipodoc,#an_documento").select2('data', null)

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $dialogExcluir.dialog({
        autoOpen: false,
        title: 'Documento - Excluir',
        width: '1130px',
        modal: false,
        close: function (event, ui) {
            $dialogExcluir.empty();
        },
        buttons: {
            'Fechar': function (event, ui) {
                $(this).dialog('close');
            },
            'Excluir': function () {
                enviar_ajax("/documento/excluir/format/json", 'form#excluir', function () {
                    $dialogExcluir.dialog('close');
                    grid.trigger("reloadGrid");
                });
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Documento - Editar',
        width: '800px',
        modal: false,
        open: function (event, ui) {
            //console.log('open');
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR'
            });
            $("form#documento-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/documento/editar/format/json", "form#documento-editar", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
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
                $("form#documento-editar").trigger('submit');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function (event) {
        event.preventDefault();
        var
            $this = $(this);

        if ($this.data('dialog')) {
            var id = 'div#' + $this.data('dialog');
            $(id).dialog('open');
            return;
        }

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            //data: $formEditar.serialize(),
            processData: false,
            success: function (data) {
                var
                    id = '',
                    time = '';

                time = (new Date()).getTime();
                id = 'dlg-detalhar-' + time;
                $this.data('dialog', id);
                $('<div id="' + id + '" class"dlg">').dialog({
                    autoOpen: false,
                    title: 'Documento - Detalhar',
                    width: '1130px',
                    modal: true,
                    buttons: {
                        'Fechar': function () {
                            $(this).dialog('close');
                        }
                    }
                });

                $('div#' + id).html(data).dialog('open');
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
        /*
        if($this.data('dialog')){
            var id = 'div#' + $this.data('dialog');
           $(id).dialog('open'); 
           return;
        } 
        */
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

    $('#resetbutton').on('click', function () {
        $("#tipodoc_cd_tipodoc,#an_documento").select2('data', null);
        $("#tipodoc_cd_tipodoc,#an_documento").select2('val', null);
        //$('.collapse').collapse('hide');
    });

    var $form = $("form#documento");

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/documento/form-editar',
                excluir: base_url + '/documento/form-excluir',
                detalhar: base_url + '/documento/detalhar'
            };
        params = '/cd_documento/' + r[0] + '/tipodoc_cd_tipodoc/' + r[6] + '/nr_copia/' + r[8] + '/an_documento/' + r[7];
        //console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>';
    }

    colNames = ['#', 'Número', 'Data', 'Tipo', 'Referência', 'Interessado'];
    colModel = [{
        name: 'cd_documento',
        index: 'cd_documento',
        width: 102,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }, {
        name: 'nr_documento',
        index: 'nr_documento',
        width: 40,
        hidden: false,
        search: false
    }, {
        name: 'dt_documento',
        index: 'dt_documento',
        width: 60,
        search: true
    }, {
        name: 'ds_tp_doc',
        index: 'ds_tp_doc',
        width: 200,
        hidden: false,
        search: false
    }, {
        name: 'ds_referen_doc',
        index: 'ds_referen_doc',
        width: 200,
        search: true
    }, {
        name: 'ds_interess_doc',
        index: 'ds_interess_doc',
        width: 200,
        search: true
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/documento/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nr_documento',
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

    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/documento/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });
});