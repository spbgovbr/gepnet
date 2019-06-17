var altura_ocupada = 120;

$(function () {
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Nome', 'Posi&ccedil;&atilde;o na Organiza&ccedil;&atilde;o', 'E-mail', 'Telefone', 'N&iacute;vel de Infu&ecirc;ncia', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomparteinteressada',
        index: 'nomparteinteressada',
        width: 8,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'nomfuncao',
        index: 'nomfuncao',
        width: 9,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'desemail',
        index: 'desemail',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'destelefone',
        index: 'destelefone',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'domnivelinfluencia',
        index: 'domnivelinfluencia',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idparteinteressada',
        index: 'idparteinteressada',
        width: 8,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];
    actions = {
        detalharparte: {
            dialog: $('#dialog-detalhar')
        },
        inserirparte: {
            url: base_url + '/planodeacao/tpa/addparteinterno/format/json',
            dialog: $('#dialog-inserir')
        },
        editarparte: {
            url: base_url + '/planodeacao/tpa/editarparteinterno/format/json',
            dialog: $('#dialog-editar')
        },
        excluirparte: {
            url: base_url + '/planodeacao/tpa/excluirparte/format/json',
            dialog: $('#dialog-excluir')
        }
    };


    grid = jQuery("#list-grid-tpa").jqGrid({
        //caption: "Documentos",
        url: base_url + "/planodeacao/tpa/grid-tpa/idplanodeacao/" + $('#idplanodeacao').val(),
        datatype: "json",
        mtype: 'post',
        width: '505',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-tpa',
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

    grid.jqGrid('navGrid', '#pager-grid-tpa', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();


    /*xxxxxxxxxx INSERIR xxxxxxxxxx*/
    actions.inserirparte.dialog.dialog({
        autoOpen: false,
        title: 'Parte Interessada - Cadastrar',
        width: 1000,
        height: 580,
        modal: false,
        open: function (event, ui) {
            $('#dialog-inserir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        close: function (event, ui) {
            $('#dialog-inserir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.inserirparte.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                //var tipoParte = $('li.active').data('cont');
                var tipoParte = $('#dialog-inserir').parent().find("li.active").data('cont');
                if (tipoParte == 'externo') {
                    $('form#form-parte-externo').submit();
                    if ($('form#form-parte-externo').valid()) {
                        $('#dialog-inserir').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        $(this).dialog('close');
                    }
                } else {
                    $('form#form-parte').submit();
                    if ($('form#form-parte').valid()) {
                        $('#dialog-inserir').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        $(this).dialog('close');
                    }
                }
            },
            'Fechar': function () {
                $('#dialog-inserir').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
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
                actions.inserirparte.dialog.html(data).dialog('open');

                var $form = $("form#form-parte");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/planodeacao/tpa/addparteinterno/format/json", "form#form-parte", function (data) {
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
                        enviar_ajax("/planodeacao/tpa/addparteexterno/format/json", "form#form-parte-externo", function (data) {
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
    actions.editarparte.dialog.dialog({
        autoOpen: false,
        title: 'Parte Interessada - Editar',
        width: 1000,
        height: 580,
        modal: false,
        open: function (event, ui) {
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        close: function (event, ui) {
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.editarparte.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                var IdExterno = $("#dialog-editar").find('input[name="idparteinteressadaexterno"]').val();
                if (IdExterno === undefined || IdExterno === null) {
                    $('form#form-parte').submit();
                    if ($('form#form-parte').valid()) {
                        $('#dialog-editar').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        $(this).dialog('close');
                    }
                } else {
                    $('form#form-parte-externo').submit();
                    if ($('form#form-parte-externo').valid()) {
                        $('#dialog-editar').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        $(this).dialog('close');
                    }
                }
            },
            'Fechar': function () {
                $('#dialog-editar').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
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
                actions.editarparte.dialog.html(data).dialog('open');
                var $form = $("form#form-parte");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/planodeacao/tpa/editarparteinterno/format/json", "form#form-parte", function (data) {
                            if (data.success) {
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
                        enviar_ajax("/planodeacao/tpa/editarparteexterno/format/json", "form#form-parte-externo", function (data) {
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
    actions.excluirparte.dialog.dialog({
        autoOpen: false,
        title: 'Parte Interessada - Excluir',
        width: 930,
        height: 500,
        modal: false,
        open: function (event, ui) {
            $('#dialog-excluir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        close: function (event, ui) {
            $('#dialog-excluir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.excluirparte.dialog.empty();
        },
        buttons: {
            'Excluir': function () {
                var arrParams = {id: $("#dialog-excluir").find('input[name="idparteinteressada"]').val()};
                ajax_arrparams("/planodeacao/tpa/excluirparte/format/json", arrParams, function (data) {
                    if (data.success) {
                        grid.trigger('reloadGrid');
                        actions.excluirparte.dialog.dialog('close');
                    }
                });
                $('#dialog-excluir').parent().find("button").each(function () {
                    $(this).attr('disabled', true);
                });
                $(this).dialog('close');
            },
            'Fechar': function () {
                $('#dialog-excluir').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
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
                actions.excluirparte.dialog.html(data).dialog('open');
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
                actions.detalharparte.dialog.html(data).dialog('open');
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

    actions.detalharparte.dialog.dialog({
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
                editarparte: base_url + '/planodeacao/tpa/editarparteinterno',
                excluirparte: base_url + '/planodeacao/tpa/excluirparte',
                detalharparte: base_url + '/planodeacao/tpa/detalharparte',
            };
        params = '/idparteinteressada/' + r[5] + '/idplanodeacao/' + r[6];
//        console.log(rowObject);
//        teste = rowObject;

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar Parte" data-id="' + cellvalue + '" href="' + url.detalharparte + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar Parte" data-id="' + cellvalue + '" href="' + url.editarparte + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir Parte" data-id="' + cellvalue + '" href="' + url.excluirparte + params + '"><i class="icon-trash"></i></a>';
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
            url: base_url + "/planodeacao/tpa/grid-tpa?" + $("form#form-parte-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });

});