var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Nome Questionário', 'Tipo', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 74,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'tipoquestionario',
        index: 'tipoquestionario',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idquestionario',
        index: 'idquestionario',
        width: 16,
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
            url: base_url + '/pesquisa/questionario/cadastrar/format/json',
            dialog: $('#dialog-inserir')
        },
        editar: {
            url: base_url + '/pesquisa/questionario/editar/format/json',
            dialog: $('#dialog-editar')
        },
    };


    grid = jQuery("#list-grid-questionario").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/questionario/pesquisar",
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-questionario',
        sortname: 'nomquestionario',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            //console.log('teste');
            $("a.actionfrm").tooltip({placement: 'left'});
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

    grid.jqGrid('navGrid', '#pager-grid-questionario', {
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
        title: 'Questionário - Cadastrar',
        width: 1030,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.inserir.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-questionario').submit();
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
                var $form = $("form#form-questionario");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/pesquisa/questionario/cadastrar/format/json", "form#form-questionario", function (data) {
                            if (data.success) {
                                resetFormQuestionario();
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
        title: 'Questionário - Editar',
        width: 1030,
        height: 580,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-questionario').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });


    function statusQuestionario($this, callback) {
        $.ajax({
            url: base_url + '/pesquisa/questionario/status-questionario',
            dataType: 'json',
            type: 'POST',
            data: {idquestionario: $this.data().id},
            success: function (data) {
                if (data.disponivel == true) {
                    $('#dialog-error-editar').dialog('open');
                } else {
                    if (callback && typeof (callback) === "function") {
                        console.log($this.attr.url);
                        callback($this);
                    }
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

    $('#dialog-error-editar').dialog({
        autoOpen: false,
        title: 'Erro',
        width: 500,
        height: 250,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $(this).dialog('close');
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.vincular", function (event) {
        event.preventDefault();
        var $this = $(this);
        statusQuestionario($this, vincularPesguntas);
    });

    function vincularPesguntas($this) {
        window.location = $this.attr('href');
    }

    $(document.body).on('click', "a.editar", function (event) {
        event.preventDefault();
        var $this = $(this);
        statusQuestionario($this, formEditar);
    });

    function formEditar($this) {
        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.editar.dialog.html(data).dialog('open');
                var $form = $("form#form-questionario");
                $form.validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/pesquisa/questionario/editar/format/json", "form#form-questionario", function (data) {
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
    }

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
        title: 'Questionário - Detalhar',
        width: 945,
        height: 600,
        modal: false,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    //disponibilizar para publicação
    $(document.body).on('click', "a.disponivel", function (event) {
        event.preventDefault();
        var $this = $(this);
        $('#dialog-disponivel').dialog('open');
        $('#questionario').val($this.data().id);
    });

    $('#dialog-disponivel').dialog({
        autoOpen: false,
        title: 'Disponibilizar para publicação',
        width: 750,
        height: 250,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#questionario').val('');
        },
        buttons: {
            'Continuar': function () {
                alterarDisponibilidade($('#questionario').val());

                $('#questionario').val('');
                $(this).dialog('close');
            },
            'Cancelar': function () {
                $('#questionario').val('');
                $(this).dialog('close');
            }
        }
    });

    //Tornar indisponivel para publicação
    $(document.body).on('click', "a.indisponivel", function (event) {
        event.preventDefault();
        var $this = $(this);
        alterarDisponibilidade($this.data().id);
    });

    function alterarDisponibilidade(idquestionario) {
        $.ajax({
            url: base_url + '/pesquisa/questionario/alterar-disponibilidade',
            dataType: 'json',
            type: 'POST',
            data: {idquestionario: idquestionario},
            success: function (data) {
                $.pnotify(data);
                if (data.isValid) {
                    grid.trigger("reloadGrid");
                } else {
                    $('#dialog-error').dialog('open');
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

    $('#dialog-error').dialog({
        autoOpen: false,
        title: 'Erro',
        width: 500,
        height: 250,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $(this).dialog('close');
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            styleClass = 'disponivel',
            title = 'Liberar para publicação',
            url = {
                editar: base_url + '/pesquisa/questionario/editar',
                detalhar: base_url + '/pesquisa/questionario/detalhar',
                vincular: base_url + '/pesquisa/questionario/listar-perguntas',
                disponibilidade: base_url + '/pesquisa/questionario/alterar-disponibilidade',
            };
        params = '/idquestionario/' + r[2];
//        console.log(rowObject);

        if (r[3] === 'Disponível') {
            styleClass = 'btn-success indisponivel';
            title = 'Indisponibilizar';
        }
        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a class="btn actionfrm vincular" title="Adicionar Perguntas" data-id="' + cellvalue + '" href="' + url.vincular + params + '"><i class="icon-random"></i></a>' +
            '<a class="btn actionfrm ' + styleClass + '" title="' + title + '" data-id="' + cellvalue + '" href="' + url.disponibilidade + '"><i class="icon-play"></i></a>';
    }


    $("form#form-questionario-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-questionario-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/questionario/pesquisar?" + $("form#form-questionario-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });


});