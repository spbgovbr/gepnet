$(function () {
    vExcluir = true;
    vRestaurar = true;
    vClonar = true;
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            editar: {
                form: $("form#form-gerencia"),
                url: base_url + '/projeto/gerencia/editar/format/json',
                dialog: $('#dialog-editar')
            },
            configurar: {
                form: $("form#form-configurar"),
                url: base_url + '/projeto/gerencia/configurar/format/json',
                dialog: $('#dialog-configurar')
            },
            arquivo: {
                form: $("form#form-escritorio-arquivo"),
                url: base_url + '/cadastro/escritorio/editar-arquivo/format/json',
                dialog: $('#dialog-arquivo')
            },
            excluir: {
                form: $("form#form-escritorio-excluir"),
                url: base_url + '/cadastro/escritorio/excluir/format/json',
                dialog: $('#dialog-excluir')
            },
            desbloquear: {
                form: $("form#form-desbloqueio"),
                url: base_url + '/projeto/gerencia/desbloquear/format/json',
                dialog: $('#dialog-desbloquear')
            },
            clonarprojeto: {
                form: $("form#form-clonarprojeto"),
                url: base_url + '/projeto/gerencia/clonarprojeto/format/json',
                dialog: $('#dialog-clonarprojeto')
            },
            excluirprojeto: {
                form: $("form#form-excluirprojeto"),
                url: base_url + '/projeto/gerencia/excluirprojeto/format/json',
                dialog: $('#dialog-excluirprojeto')
            },
            restaurarprojeto: {
                form: $("form#form-restaurarprojeto"),
                url: base_url + '/projeto/gerencia/restaurarprojeto/format/json',
                dialog: $('#dialog-restaurarprojeto')
            },
        };

    $(".select2").select2();

    //Reset button
    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $(".select2").select2('data', null);
        $("#nomprograma").select2('data', null);
        $("#domstatusprojeto").select2('data', null);
        $("#idescritorio").select2('data', null);
        $("#codobjetivo").select2('data', null);
        $("#codacao").select2('data', null);
        $('#codacao').children('option').remove();
        //$('#codacao').append('<option value="0" selected>Todos</option>');
        $("#acompanhamento").select2('data', null);
        $("#nomprojeto").focus();
//        $("#nomalinhamento").select2('data', null);
//        $("#nomacao").select2('data', null);
//        $("#nomnatureza").select2('data', null);

    });

    //$('#codacao').append('<option value="0" selected>Todos</option>');
    //$('#codacao').html('<option value="0" selected>Todos</option>');
    if ($("#codobjetivo").val() == 0) {
        $('#codacao').append('<option value="0">Todos</option>');
        //console.log($('#codacao').options[0]);
    }
    $("#codobjetivo").change(function () {
        $('#codacao').children('option').remove();
        $.ajax({
            url: base_url + "/projeto/gerencia/pesquisaracaojson",
            dataType: 'json',
            type: 'POST',
            data: {
                'idobjetivo': $(this).val(),
            },
            success: function (data) {
                console.log(data);
                $.each(data, function (key, value) {
                    $('#codacao').append('<option value="' + key + '">' + value + '</option>');
                });
            },
            error: function () {
                $.pnotify({
                    text: 'Não foi encontrado ações para o objetivo selecionado.',
                    type: 'info',
                    hide: true
                });
            }
        });
    });


    /*xxxxxx CLONAR PROJETO xxxxxx*/
    var options = {
        url: actions.clonarprojeto.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                grid.setGridParam({
                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
            }
        }
    };

    actions.clonarprojeto.form.ajaxForm(options);

    actions.clonarprojeto.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Clonar Projeto',
        width: '900px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vClonar = true;
            $('#dialog-clonarprojeto').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.clonarprojeto.dialog.empty();
            grid.setGridParam({
                url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            'Clonar': function () {
                if (vClonar) {
                    vClonar = false;
                    $('#dialog-clonarprojeto').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    $("form#form-clonarprojeto").validate();
                    var form = $("form#form-clonarprojeto");
                    form.validate();
                    if (form.valid()) {

                        $.ajax({
                            url: actions.clonarprojeto.url,
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                'idprojeto': form.parent().find('input#idprojeto').val(),
                                'nomprojeto': form.parent().find('input#nomprojeto').val(),
                                'idescritorio': form.parent().find('select#idescritorio').val(),
                                'ano': form.parent().find('input#ano').val(),
                            },
                            success: function (data) {
                                //console.log(data.status);
                                if (data.status == 'error') {
                                    $.pnotify({
                                        text: 'Acesso Negado, este projeto não é público.',
                                        type: 'error',
                                        hide: false
                                    });
                                    $('#dialog-clonarprojeto').dialog('close');
                                } else {
                                    $.pnotify(data.msg);
                                }
                                if (data.msg.idprojeto) {
                                    window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.msg.idprojeto;
                                }
                                $('#dialog-clonarprojeto').dialog('close');
                            },
                            error: function () {
                                $('#dialog-clonarprojeto').dialog('close');

                                $.pnotify({
                                    text: 'Falha ao enviar a requisição',
                                    type: 'error',
                                    hide: false
                                });
                            }
                        });
                    } else {
                        $('#dialog-clonarprojeto').parent().find("button").each(function () {
                            $(this).attr('disabled', false);
                        });
                    }
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.clonarprojeto", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.clonarprojeto.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
        /**/
    });
    /*xxxxxx EXCLUIR PROJETO xxxxxx*/
    var options = {
        url: actions.excluirprojeto.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                grid.setGridParam({
                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
            }
        }
    };

    actions.excluirprojeto.form.ajaxForm(options);

    actions.excluirprojeto.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Excluir Projeto',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vRestaurar = true;
            $('#dialog-excluirprojeto').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.excluirprojeto.dialog.empty();
            grid.setGridParam({
                url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            'Excluir': function () {
                if (vRestaurar) {
                    vRestaurar = false;
                    $('#dialog-excluirprojeto').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });

                    $.ajax({
                        url: actions.excluirprojeto.url,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idprojeto': $('#idprojeto').val()
                        },
                        success: function (data) {
                            $.pnotify(data.msg);
                            if (data.success) {
                                grid.setGridParam({
                                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                                    page: 1
                                }).trigger("reloadGrid");
                                resizeGrid();
                            }
                            $('#dialog-excluirprojeto').dialog('close');
                        },
                        error: function () {
                            $('#dialog-excluirprojeto').dialog('close');
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                    /**/
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluirprojeto", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.excluirprojeto.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
        /**/
    });

    /*xxxxxx RECUPERAR PROJETO xxxxxx*/
    var options = {
        url: actions.restaurarprojeto.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                grid.setGridParam({
                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
            }
        }
    };

    actions.restaurarprojeto.form.ajaxForm(options);

    actions.restaurarprojeto.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Recuperar Projeto',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vRestaurar = true;
            $('#dialog-restaurarprojeto').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.restaurarprojeto.dialog.empty();
            grid.setGridParam({
                url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            'Recuperar': function () {
                if (vRestaurar) {
                    vRestaurar = false;
                    $('#dialog-restaurarprojeto').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });

                    $.ajax({
                        url: actions.restaurarprojeto.url,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idprojeto': $('#idprojeto').val()
                        },
                        success: function (data) {
                            $.pnotify(data.msg);
                            if (data.success) {
                                grid.setGridParam({
                                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                                    page: 1
                                }).trigger("reloadGrid");
                                resizeGrid();
                            }
                            $('#dialog-restaurarprojeto').dialog('close');
                        },
                        error: function () {
                            $('#dialog-restaurarprojeto').dialog('close');
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                    /**/
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.restaurarprojeto", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.restaurarprojeto.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
        /**/
    });

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    var options = {
        url: actions.editar.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };

    actions.editar.form.ajaxForm(options);

    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Editar',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                //console.log('submit');
                $('form#form-gerencia').submit();
                //$('form#form-documento').submit();
                //console.log(actions.editar.form);
                //$formEditar.on('submit');
                //$(actions.editar.form).trigger('submit');
                //enviar_ajax(actions.editar.url, actions.editar.form );
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar_", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {

                actions.editar.dialog.html(data).dialog('open');
                $("#idtipodocumento").select2();
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
                });
                $("#accordion").accordion();
                $('form#form-gerencia').validate();

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

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Escritorio - Detalhar',
        width: '810px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function (event) {
        //event.preventDefault();
        var
            $this = $(this);

//        $.ajax({
//            url: $this.attr('href'),
//            dataType: 'html',
//            type: 'GET',
//            async: true,
//            cache: false,
//            //data: $formEditar.serialize(),
//            processData: true,
//            success: function(data) {
//                //console.log(data);
//                actions.detalhar.dialog.html(data).dialog('open');
//            },
//            error: function() {
//                $.pnotify({
//                    text: 'Falha ao enviar a requisição',
//                    type: 'error',
//                    hide: false
//                });
//            }
//        });
    });

    /*xxxxxxxxxx ARQUIVO xxxxxxxxxx*/
    var optionsArquivo = {
        url: actions.arquivo.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };
    actions.arquivo.form.ajaxForm(optionsArquivo);

    //url_editar = base_url + '/cadastro/documento/edit';
    actions.arquivo.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Editar arquivo',
        width: '800px',
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.arquivo.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                console.log('submit');
                $('form#form-documento-arquivo').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.arquivo, a.btn_editar", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.arquivo.dialog.html(data).dialog('open');
                $('form#form-documento-arquivo').validate();

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
    var optionsExcluir = {
        url: actions.excluir.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };

    actions.excluir.form.ajaxForm(optionsExcluir);

    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Excluir',
        width: '810px',
        modal: true,
        buttons: {
            'Excluir': function () {
                $('form#form-documento-excluir').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluir", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            //data: $formEditar.serialize(),
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

    $(document.body).on('click', "a.desbloquear", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            //data: $formEditar.serialize(),
            //processData: false,
            success: function (data) {
                actions.desbloquear.dialog.html(data).dialog('open');
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

    actions.desbloquear.dialog.dialog({
        autoOpen: false,
        title: 'Desbloquear Projeto',
        width: '810px',
        modal: true,
        buttons: {
            'Salvar': function () {
                $.ajax({
                    url: base_url + '/projeto/gerencia/desbloquear/format/json',
                    dataType: 'html',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        'idprojeto': $('#idprojeto').val(),
                        'desjustificativa': $('#desjustificativa').val()
                    },
                    //processData: false,
                    success: function (data) {
//                        actions.desbloquear.dialog.html(data).dialog('open');
                        $.pnotify({
                            text: 'Projeto desbloqueado com sucesso.',
                            type: 'success',
                            hide: true
                        });
                        grid.trigger("reloadGrid");
                    },
                    error: function () {
                        $.pnotify({
                            text: 'Falha ao enviar a requisição',
                            type: 'error',
                            hide: false
                        });
                    }
                });
                $(this).dialog('close');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    function formatadorSituacao(cellvalue, options, rowObject) {
        var situacao = rowObject[17];
        return situacao;

    }

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/projeto/tap/informacoesiniciais',
                imprimir_plano: base_url + '/projeto/planoprojeto/imprimir',
                imprimir_tap: base_url + '/projeto/tap/imprimir',
                arquivo: base_url + '/projeto/gerencia/editar-arquivo',
                cronograma: base_url + '/projeto/cronograma/index',
                projeto: base_url + '/projeto/tap/index',
                desbloqueio: base_url + '/projeto/gerencia/desbloquear',
                configurar: base_url + '/projeto/gerencia/configurar',
                clonarprojeto: base_url + '/projeto/gerencia/clonarprojeto',
                excluirprojeto: base_url + '/projeto/gerencia/excluirprojeto',
                restaurarprojeto: base_url + '/projeto/gerencia/restaurarprojeto',
            };

        //alert(r);

        params = '/idprojeto/' + r[15];

        //console.log('14:' + r[14]);
        //console.log('15:' + r[15]);
        //console.log('16:' + r[16]);
        //console.log('17:' + r[17]);
        //console.log('18:' + r[18]);
        //console.log('21:' + r[21]);
        //console.log('21:' + r[21]);
        //console.log('21:' + r[21]);

        $return = '<a target="_blank" class="btn actionfrm detalhar" title="Imprimir PLANO DE PROJETO" data-id="' + cellvalue + '" href="' + url.imprimir_plano + params + '"><i class="icon-print"></i></a>' +
            '<a target="_blank" class="btn actionfrm detalhar" title="Imprimir TERMO DE ABERTURA DE PROJETO (TAP)" data-id="' + cellvalue + '" href="' + url.imprimir_tap + params + '"><i class="icon-print"></i></a>' +
            '<a class="btn actionfrm clonarprojeto" title="Clonar Projeto" data-id="' + cellvalue + '" href="' + url.clonarprojeto + params + '"><i class="icon-random"></i></a>' + '' +
            '<a class="btn actionfrm ' + (r[21] == 8 ? 'restaurarprojeto' : 'excluirprojeto') + '" title="' + (r[21] == 8 ? 'Recuperar' : 'Excluir') + ' Projeto" data-id="' + cellvalue + '" href="' + (r[21] == 8 ? url.restaurarprojeto : url.excluirprojeto) + params + '"><i class="' + (r[21] == 8 ? 'icon-repeat' : 'icon-trash') + '" ></i></a>' + '\n' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar TAP" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a target="_self" class="btn actionfrm editar" title="Gerenciar" data-id="' + cellvalue + '" href="' + url.projeto + params + '"><i class="icon-wrench"></i></a>' +
            '<a data-target="#dialog-configurar" class="btn actionfrm configurar" title="Configurar Permiss&otilde;es" data-id="' + cellvalue + '" href="' + url.configurar + params + '"><i class="icon-cog"></i></a>';

        /* if(r[17]){
         $return += '<a class="btn actionfrm editar disabled" title="Editar TAP" href="#"><i class="icon-edit"></i></a>' +
         '<a class="btn actionfrm editar disabled" title="Gerenciar" href="#"><i class="icon-wrench"></i></a>'+
         '<a data-target="#dialog-desbloquear" class="btn actionfrm desbloquear" title="Projeto Bloqueado" data-id="' + cellvalue + '" href="' + url.desbloqueio + params + '"><i class="icon-folder-close"></i></a>';
         }else{

         $return += '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar TAP" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
         '<a target="_self" class="btn actionfrm editar" title="Gerenciar" data-id="' + cellvalue + '" href="' + url.projeto + params + '"><i class="icon-wrench"></i></a>'+
         '<a class="btn actionfrm disabled" title="Projeto Ativo" data-id="" href=""><i class=" icon-folder-open"></i></a>';
         }*/
        //console.log(r[17]);

        /*if(r[8].length > 1){
         $return += '<a target="_self" class="btn actionfrm cronograma" title="Cronograma" data-id="' + cellvalue + '" href="' + url.cronograma + params + '"><i class="icon-list"></i></a>';
         }*/


        return $return;

    }

    function formatadorLinkExclui(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                exclui: base_url + '/projeto/tap/informacoesiniciais'
            };
        params = '/idprojeto/' + r[15];
        var exclui = r[17];
        if (exclui) {
            //console.log('true');
            //console.log(exclui);
            $return = '<a class="btn btn-success permissao-toggle" title="Revogar" data-id="' + cellvalue + '" href="' + url.exclui + params + '"><i class="icon-ok icon-white "></i></a>\n';
        } else {
            $return = '<a class="btn btn-danger permissao-toggle" title="Conceder" data-id="' + cellvalue + '" href="' + url.exclui + params + '"><i class="icon-off icon-white "></i></a>\n';
            //console.log('false');
            //console.log(exclui);
        }


        return $return;

    }

//    function formatadorImg(cellvalue, options, rowObject)
//    {
//    	var path = base_url + '/img/ico_verde.gif';
//    	return '<img src="'+ path +'" />';
//    }

    function formatadorImgPrazo(cellvalue, options, rowObject) {
//      var path = base_url + '/img/ico_verde.gif';
//      return '<img src="' + path + '" />';
        var retorno = '-';

        if (rowObject[12] >= rowObject[16]) {
            var retorno = '<span class="badge badge-important" title=' + rowObject[12] + '>P</span>';
        } else if (rowObject[12] > 0) {
            var retorno = '<span class="badge badge-warning" title=' + rowObject[12] + '>P</span>';
        } else {
            var retorno = '<span class="badge badge-success" title=' + rowObject[12] + '>P</span>';
        }

        if (rowObject[12] === "-")
            return rowObject[12];

        return retorno;
    }

    function formatadorImgRisco(cellvalue, options, rowObject) {
        var retorno = '-';

        if (rowObject[13] === '1') {
            var retorno = '<span class="badge badge-success" title="Baixo">R</span>';
        } else if (rowObject[13] === '2') {
            var retorno = '<span class="badge badge-warning" title="Médio">R</span>';
        } else if (rowObject[13] === '3') {
            var retorno = '<span class="badge badge-important" title="Alto">R</span>';
        }

        return retorno;
    }

    function formatadorImgAtrazo(cellvalue, options, rowObject) {
        //var retorno = '<span class="badge" title=' + rowObject[11] + '>'+rowObject[11]+'</span>'; 
        //console.log(rowObject[11] +' - ' + rowObject[19]);
//        if ((rowObject[11] >= rowObject[19]) && rowObject[19] != null) {
//            var retorno = '<span class="badge badge-important" title=' + rowObject[11] + '>'+rowObject[11]+'</span>'; 
//        }else if (rowObject[11] > 0) {
//            var retorno = '<span class="badge badge-warning" title=' + rowObject[11] + '>'+rowObject[11]+'</span>';
//        }else {

        var retorno = '<span class="badge badge-' + rowObject[19] + '" title=' + rowObject[11] + '>' + rowObject[11] + '</span>';

        return retorno;
    }

    function verificaTamanhoTextoPrograma(str, width, brk) {
        brk = brk || '\n';
        width = 15;

        if (!str) {
            return str;
        }
        var regex = '.{1,' + width + '}(\\s|$)' + '|\\S+?(\\s|$)';
        var array = str.match(RegExp(regex, 'g'));
        var frase = "";

        for (var i = 0; i < array.length; i++) {
            frase += array[i] + "\n";
        }
        ;
        var retorno = "<div style='white-space:initial'>" + frase + "</div>";
        return retorno;
    }

    function verificaTamanhoTextoProjeto(str, width, brk) {

        brk = brk || '\n';
        width = 36;

        if (!str) {
            return str;
        }
        var regex = '.{1,' + width + '}(\\s|$)' + '|\\S+?(\\s|$)';
        var array = str.match(RegExp(regex, 'g'));
        var frase = "";

        for (var i = 0; i < array.length; i++) {
            frase += array[i] + "\n";
        }
        ;
        var retorno = "<div style='white-space:initial'>" + frase + "</div>";
        return retorno;
    }

    function formatadorLinkClonar(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/projeto/gerencia/clonarprojeto'
            };
        params = '/idprojeto/' + r[15];
        var editar = r[17];
        $return = '<a title="Clonar Projeto" ' +
            'style="text-decoration: none; color: #0b83d1;" onMouseOver="this.style.textDecoration=\'underline\'; this.style.color = \'#1e395b\';" ' +
            'onMouseOut="this.style.textDecoration=\'none\'; this.style.color = \'#0b83d1\';" href="' + url.editar + params + '" ' +
            'class="actionfrm clonarprojeto" data-id="\' + cellvalue + \'">' + r[1] + '</a>\n';
        return verificaTamanhoTextoPrograma($return, null, null);
    }

    //'Sigla', 'Nome', 'Responsavel-1', 'Responsavel-2', 'Mapa', 'Situação', 'Logo', 'Operações'
    colNames = ['Programa', 'Projeto', 'Gerente', 'Escritório Responsável', 'Código', 'Publicado', 'Início', 'Término Meta', 'Término Tendência', 'Previsto', 'Concluído', 'Atraso', 'Prazo', 'Risco', 'Último Relatório', 'Situação'/*, ''*//*, 'Operações'*/];
    colModel = [
        {
            name: 'nomprograma',
            index: 'nomprograma',
            align: 'center',
            width: 25,
            hidden: true,
            search: false,
            formatter: verificaTamanhoTextoPrograma
        }, {
            name: 'nomprojeto',
            index: 'nomprojeto',
            align: 'center',
            width: 55,
            hidden: false,
            search: false,
            //formatter:verificaTamanhoTextoProjeto
            //formatter:'showlink',
            formatter: formatadorLinkClonar

            //formatoptions:{baseLinkUrl:base_url + '/projeto/tap/informacoesiniciais/idprojeto/' + $('#idprojeto').val()}
        }, {
            name: 'idgerenteprojeto',
            index: 'idgerenteprojeto',
            align: 'center',
            width: 50,
            hidden: true,
            search: false,
            formatter: verificaTamanhoTextoProjeto
        }, {
            name: 'nomescritorio',
            index: 'nomescritorio',
            align: 'center',
            width: 23,
            hidden: false,
            search: false
        }, {
            name: 'nomcodigo',
            index: 'nomcodigo',
            align: 'center',
            width: 50,
            hidden: true,
            search: false
        }, {
            name: 'flapublicado',
            index: 'flapublicado',
            align: 'center',
            width: 16,
            search: true,
            hidden: true,
            //formatter: formatadorSituacao
        }, {
            name: 'datinicio',
            index: 'datinicio',
            align: 'center',
            width: 18,
            hidden: true,
            search: true
        }, {
            name: 'datfim',
            index: 'datfim',
            align: 'center',
            hidden: true,
            width: 18,
            search: true
        }, {
            name: 'datfimplano',
            index: 'datfimplano',
            align: 'center',
            hidden: true,
            width: 18,
            search: true
        }, {
            name: 'previsto',
            index: 'previsto',
            align: 'center',
            width: 14,
            search: false,
            hidden: true,
            sortable: false
        }, {
            name: 'concluido',
            index: 'concluido',
            align: 'center',
            width: 16,
            hidden: true,
            search: false,
            sortable: false
        }, {
            name: 'atraso',
            index: 'atraso',
            width: 13,
            align: 'center',
            search: false,
            sortable: false,
            hidden: true,
            formatter: formatadorImgAtrazo
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 11,
            align: 'center',
            search: false,
            sortable: false,
            hidden: true,
            //formatter: formatadorImgPrazo
        }, {
            name: 'Risco',
            index: 'Risco',
            width: 10,
            align: 'center',
            search: false,
            sortable: false,
            hidden: true,
            formatter: formatadorImgRisco
        }, {
            name: 'ultimoacompanhamento',
            index: 'ultimoacompanhamento',
            width: 17,
            align: 'center',
            search: true,
            hidden: true,
            sortable: true
        }, {
            name: 'situacao',
            index: 'situacao',
            align: 'center',
            width: 22,
            search: true,
            hidden: true,
            formatter: formatadorSituacao
        }/*, {
         name: 'domstatusprojeto',
         index: 'domstatusprojeto',
         align: 'center',
         width: 16,
         search: true,
         sortable: false,
         formatter: formatadorLinkExclui
         }*/
        /*, {
            name: 'id',
            index: 'id',
            width: 60,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }*/
    ];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/gerencia/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '990',
        height: '200px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomprojeto',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
            //console.log('teste');
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

    // ## Formata o Título do Grid da Gerência ###########
    $(".ui-jqgrid-sortable").css('white-space', 'normal');
    $(".ui-jqgrid-sortable").css('height', 'auto');
    $("tr.ui-jqgrid-labels").css('vertical-align', 'top');
    // ###################################################

    actions.pesquisar.form.on('submit', function (e) {

        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    $("#accordion").accordion();

    resizeGrid();

    var
        $form = $("form#form-clonarprojeto")
    ;

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * Responsividade
     */
    function resizeGridHeight() {
        var /*regionCenter = $('.region-center'),*/
            parentHeight = $('#rodape').offset().top - 181, /*regionCenter.outerHeight(),*/
            headerHeight = $('.ui-jqgrid-hdiv').outerHeight(),
            footerHeight = $('.ui-jqgrid-pager.ui-corner-bottom').outerHeight(),
            buttonsHeight = $('.form-actions-mini').outerHeight(),
            bodyHeight = parentHeight
                - headerHeight
                - footerHeight
                - buttonsHeight
                // - (parseInt(regionCenter.css('padding').replace(/px/g, '')) * 2)
                - (parseInt($('.form-actions-mini').css('margin').replace(/[px ]/g, '')) * 2);
        $('.ui-jqgrid-bdiv').css({
            'height': bodyHeight
        });
    }

    resizeGridHeight();
    $(window).on('resize', function () {
        resizeGridHeight();
    });
});