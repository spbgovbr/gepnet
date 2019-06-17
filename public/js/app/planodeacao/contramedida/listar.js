var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Título Risco', 'Título Contramedida', 'Prazo', 'Tendência/Real', 'Status Contramedida', 'Contramedida Efetiva?', 'Tipo Contramedida', 'Responsável', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'norisco',
        index: 'norisco',
        width: 15,
        search: false,
        hidden: false,
        sortable: false
    }, {
        name: 'tc.nocontramedida',
        index: 'tc.nocontramedida',
        width: 15,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'tc.datprazocontramedida',
        index: 'tc.datprazocontramedida',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'tc.datprazocontramedidaatraso',
        index: 'tc.datprazocontramedidaatraso',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'tc.domstatuscontramedida',
        index: 'tc.domstatuscontramedida',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'tc.flacontramedidaefetiva',
        index: 'tc.flacontramedidaefetiva',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'tc.idtipocontramedida',
        index: 'tc.idtipocontramedida',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'tc.desresponsavel',
        index: 'tc.desresponsavel',
        width: 15,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'tc.idcontramedida',
        index: 'tc.idcontramedida',
        width: 15,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];
    actions = {
        detalhar: {
            dialog: $('#dialog-detalhar')
        },
        detalharrisco: {
            dialog: $('#dialog-detalhar-risco')
        },
        inserir: {
            url: base_url + '/planodeacao/contramedida/cadastrar/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/planodeacao/contramedida/editar/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            url: base_url + '/planodeacao/contramedida/excluir/format/json',
            dialog: $('#dialog-excluir')
        }
    };

    grid = jQuery("#list-grid-contramedida").jqGrid({
        //caption: "Documentos",
        url: base_url + "/planodeacao/contramedida/pesquisar/idrisco/" + $('#idrisco').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-contramedida',
        sortname: 'idcontramedida',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
            //console.log('teste');
            //$("a.actionfrm").tooltip();
        },
        onSelectRow: function (id) {
//            if(window.selectRow){
//                var row = grid.getRowData(id);
//                selectRow(row);
//            } else {
//                alert('Função [selectRow] não está definida');
//            }
        },
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-contramedida', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    $.validator.addMethod("riscoativo", function (value) {
        var flariscoativo = $('#flariscoativo').val();
        var flacontramedidaefetiva = $('#flacontramedidaefetiva').val();
        if (flacontramedidaefetiva == '1' && flariscoativo == '1') {
            $('#flacontramedidaefetiva').focus();
            return false;
        }
        return true;
    }, 'A contramedida não pode ser efetiva se o risco ainda estiver ativo!');

    $("body").delegate(".datemask-BR", "focusin", function () {
        var $this = $(this);
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            changeMonth: true,
            changeYear: true,
            readonly: false
        });
    });

    $('.datemask-BR').mask('99/99/9999');

    /*xxxxxxxxxx INSERIR xxxxxxxxxx*/
    actions.inserir.dialog.dialog({
        autoOpen: false,
        title: 'Contramedida - Cadastrar',
        width: 1030,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#dialog-inserir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.inserir.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-contramedida').submit();
                if ($('form#form-contramedida').valid()) {
                    $('#dialog-inserir').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    $(this).dialog('close');
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.inserir", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.inserir.dialog.html(data).dialog('open');
                var $form = $("form#form-contramedida");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    rules: {
                        flacontramedidaefetiva: {
                            riscoativo: true
                        }
                    },
                    submitHandler: function (form) {
                        enviar_ajax("/planodeacao/contramedida/cadastrar/format/json", "form#form-contramedida", function (data) {
                            if (data.success) {
                                resetFormContramedida();
                                grid.trigger('reloadGrid');
                            }
                        });
                    }
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

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Contramedida - Editar',
        width: 1030,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-contramedida').submit();
                if ($('form#form-contramedida').valid()) {
                    $('#dialog-editar').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    $(this).dialog('close');
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.editar.dialog.html(data).dialog('open');
                var $form = $("form#form-contramedida");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    rules: {
                        flacontramedidaefetiva: {
                            riscoativo: true
                        }
                    },
                    submitHandler: function (form) {
                        enviar_ajax("/planodeacao/contramedida/editar/format/json", "form#form-contramedida", function (data) {
                            if (data.success) {
                                grid.trigger('reloadGrid');
                            }
                        });
                    }
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


    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Contramedida - Excluir',
        width: 945,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {idcontramedida: $("#dialog-excluir").find('input[name="idcontramedida"]').val()};
                ajax_arrparams("/planodeacao/contramedida/excluir/format/json", arrParams, function (data) {
                    if (data.success) {
                        grid.trigger('reloadGrid');
                        actions.excluir.dialog.dialog('close');
                    }
                });
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluir", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.excluir.dialog.html(data).dialog('open');
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

    /*xxxxxxxxxx DETALHAR xxxxxxxxxx*/
    $(document.body).on('click', "a.detalhar", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.detalhar.dialog.html(data).dialog('open');
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

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Contramedida - Detalhar',
        width: 945,
        height: 600,
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    /*xxxxxxxxxx DETALHAR RISCO xxxxxxxxxx*/
    $(document.body).on('click', "a.risco", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.detalharrisco.dialog.html(data).dialog('open');
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

    actions.detalharrisco.dialog.dialog({
        autoOpen: false,
        title: 'Risco - Detalhar',
        width: 945,
        height: 600,
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/planodeacao/contramedida/editar',
                excluir: base_url + '/planodeacao/contramedida/excluir',
                detalhar: base_url + '/planodeacao/contramedida/detalhar',
                detalharrisco: base_url + '/planodeacao/risco/detalhar',
            };
        params = '/idcontramedida/' + r[8] + '/idrisco/' + r[9];
//        console.log(rowObject);
        teste = rowObject;

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            '<a data-target="#dialog-contramedida" class="btn actionfrm risco" title="Visualizar Risco" data-id="' + cellvalue + '" href="' + url.detalharrisco + params + '"><i class="icon-eye-open"></i></a>';
    }

    /**
     * Envia ajax por array de parametros
     */
    function ajax_arrparams(url, data, callback) {
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: data,
            success: function (data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                $.pnotify(data.msg);
                if (callback && typeof (callback) === "function") {
                    callback(data);
                }
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

    $.pnotify.defaults.history = false;

    $("form#form-contramedida-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-contramedida-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/planodeacao/contramedida/pesquisar?" + $("form#form-contramedida-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });

});