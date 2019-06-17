var altura_ocupada = 120;

$(function () {
    var
        vSalvar = true,
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Nome', 'Posi&ccedil;&atilde;o na Organiza&ccedil;&atilde;o', 'E-mail', 'Telefone', 'N&iacute;vel de Infu&ecirc;ncia', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomparteinteressada',
        index: 'nomparteinteressada',
        width: 10,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'nomfuncao',
        index: 'nomfuncao',
        width: 15,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desemail',
        index: 'desemail',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'destelefone',
        index: 'destelefone',
        width: 11,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'domnivelinfluencia',
        index: 'domnivelinfluencia',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idparteinteressada',
        index: 'idparteinteressada',
        width: 10,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];
    actions = {
        detalhar: {
            dialog: $('#dialog-detalhar')
        },
        inserir: {
            url: base_url + '/planodeacao/rh/addinterno/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/planodeacao/rh/editarinterno/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            url: base_url + '/planodeacao/rh/excluirparte/format/json',
            dialog: $('#dialog-excluir')
        }
    };


    grid = jQuery("#list-grid-rh").jqGrid({
        //caption: "Documentos",
        url: base_url + "/planodeacao/rh/grid-rh/idplanodeacao/" + $('#idplanodeacao').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-rh',
        sortname: 'nomparteinteressada',
        viewrecords: true,
        sortorder: "asc",
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

    grid.jqGrid('navGrid', '#pager-grid-rh', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();


    /*xxxxxxxxxx INSERIR xxxxxxxxxx*/
    actions.inserir.dialog.dialog({
        autoOpen: false,
        title: 'Parte Interessada - Cadastrar',
        width: 1000,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.inserir.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                var tipoParte = $('li.active').data('cont');
                var tipoAba = $('.nav-pills .active').text();
                if ((tipoParte == 'externo') || (tipoAba == 'Externo')) {
                    $('form#form-parte-externo').submit();
                    if ($('form#form-parte-externo').valid()) {
                        $('#dialog-inserir').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-inserir').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 3000);
                        $(this).dialog('close');
                    }
                } else {
                    $('form#form-parte').submit();
                    if ($('form#form-parte').valid()) {
                        $('#dialog-inserir').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-inserir').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 3000);
                        $(this).dialog('close');
                    }
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
                var $form = $("form#form-parte");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/planodeacao/rh/addinterno/format/json", "form#form-parte", function (data) {
                            if (data.success) {
                                resetFormPart();
                                grid.trigger('reloadGrid');
                            }
                        });
                    }
                });

                var $formExterno = $("form#form-parte-externo");
                $formExterno.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (formExterno) {
                        enviar_ajax("/planodeacao/rh/addexterno/format/json", "form#form-parte-externo", function (data) {
                            if (data.success) {
                                resetFormPart();
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
        title: 'Parte Interessada - Editar',
        width: 1000,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            vSalvar = true;
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                var tipoParte = $('li.active').data('cont');
                var tipoAba = $('.nav-pills .active').text();
                if ((tipoParte == 'externo') || (tipoAba == 'Externo')) {
                    $('form#form-parte-externo').submit();
                    if ($('form#form-parte-externo').valid()) {
                        $('#dialog-editar').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-editar').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 2500);
                    }
                } else {
                    $('form#form-parte').submit();
                    if ($('form#form-parte').valid()) {
                        $('#dialog-editar').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vSalvar = true;
                            $('#dialog-editar').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 2500);
                    }
                }
            },
            'Fechar': function () {
                $('#dialog-editar').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                vSalvar = true;
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
                var $form = $("form#form-parte");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/planodeacao/rh/editarinterno/format/json", "form#form-parte", function (data) {
                            if (data.success) {
                                parent.window.location.reload();
                                //grid.trigger('reloadGrid');
                                actions.editar.dialog.html(data).dialog('close');
                            }
                        });
                    }
                });

                var $formExterno = $("form#form-parte-externo");
                $formExterno.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (formExterno) {
                        enviar_ajax("/planodeacao/rh/editarexterno/format/json", "form#form-parte-externo", function (data) {
                            if (data.success) {
                                //parent.window.location.reload();
                                grid.trigger('reloadGrid');
                                actions.editar.dialog.html(data).dialog('close');
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
        title: 'Parte Interessada - Excluir',
        width: 930,
        height: 500,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {id: $("#dialog-excluir").find('input[name="idparteinteressada"]').val()};
                ajax_arrparams("/planodeacao/rh/excluirparte/format/json", arrParams, function (data) {
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
        title: 'Parte Interessada - Detalhar',
        width: 930,
        height: 500,
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
                editar: base_url + '/planodeacao/rh/editarinterno',
                excluir: base_url + '/planodeacao/rh/excluirparte',
                detalhar: base_url + '/planodeacao/rh/detalhar',
            };
        params = '/idparteinteressada/' + r[5] + '/idplanodeacao/' + r[6];
//        console.log(rowObject);
//        teste = rowObject;

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
    }

//    /**
//     * Envia ajax por array de parametros
//     */
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

    $('.mask-tel').mask('(99) 9999-9999');

    $("form#form-parte-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/planodeacao/rh/grid-rh?" + $("form#form-parte-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });

});