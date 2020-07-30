$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        idProjeto = $("input[name='idprojeto']").val(),
        $dialogExcluir = $('#dialog-excluir'),
        $dialogAssinar = $('#dialog-assinar'),
        $dialogCadastrar = $('#dialog-cadastrar'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formEditar = $("form#form-aceite"),
        $formExcluir = $("form#form-aceite-excluir"),
        $formCadastrar = $("form#form-aceite");


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Termo de Aceite - Detalhar',
        width: '880px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogAssinar = $('#dialog-assinar').dialog({
        autoOpen: false,
        title: 'Assinar Termo de Aceite',
        width: '360px',
        modal: true,
        buttons: {
            'Salvar': function () {
                //console.log($("form#form-assinaDoc").valid());
                if ($("form#form-assinaDoc").valid()) {
                    var form = $('form#form-assinaDoc');
                    var $paramsForm = form.serialize();
                    $.ajax({
                        url: base_url + '/projeto/termoaceite/autenticarassinatura/format/json',
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: $paramsForm,
                        //processData:false,
                        success: function (data) {
                            if (data.success) {
                                $('#dialog-assinar').dialog('close');
                                $.pnotify(data.msg);
                            } else {
                                $("#message").text(data.msg.text);
                                $("#message").show();
                            }

                        },
                        error: function () {
                            $('#dialog-assinaDoc').dialog('close');
                        }
                    });
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    function validarCampos() {
        var cpf = $("#numcpf"),
            senha = $("#senha");

        if (cpf.val() != "") {
            if (senha.val() == "") {
                $("#message").text("Favor informar a senha para validação.");
                $("#message").show();
                senha.focus();
                return false;
            }
        } else if (cpf.val() == "") {
            $("#message").text("Favor informar um CPF de usuário válido.");
            $("#message").show();
            cpf.focus();
            return false;
        }

        $("#message").text('');
        $("#message").hide();
        return true;
    }

    $dialogEditar = $('#dialog-editar').dialog({
        autoOpen: false,
        title: 'Termo de Aceite - Editar',
        width: '880px',
        modal: true,
        buttons: {
            'Salvar': function () {
                if ($("form#form-aceite").valid()) {
                    var form = $('form#form-aceite');
                    var $paramsForm = form.serialize();
                    $.ajax({
                        url: base_url + "/projeto/termoaceite/editar/format/json",
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: $paramsForm,
                        //processData:false,
                        success: function (data) {
                            //if(typeof data.msg.text != 'string'){
                            //    $.formErrors(data.msg.text);
                            //    return;
                            //}
                            if (data.success) {
                                $('#dialog-editar').dialog('close');
                                grid.trigger("reloadGrid");
                            }
                            $.pnotify(data.msg);
                        },
                        error: function () {
                            $('#dialog-editar').dialog('close');
                        }
                    });
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogCadastrar = $('#dialog-cadastrar').dialog({
        autoOpen: false,
        title: 'Termo de Aceite - Cadastrar',
        width: '880px',
        modal: true,
        buttons: {
            'Salvar': function () {
                if ($("form#form-aceite").valid()) {
                    enviar_ajax("/projeto/termoaceite/add/format/json", "form#form-aceite", function (data) {
                        grid.trigger("reloadGrid");
                    });
                    $(this).dialog('close');
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogExcluir = $('#dialog-excluir').dialog({
        autoOpen: false,
        title: 'Termo de Aceite - Excluir',
        width: '880px',
        modal: true,
        buttons: {
            'Excluir': function () {
                enviar_ajax("/projeto/termoaceite/excluir/format/json", "form#form-aceite-excluir", function () {
                    grid.trigger("reloadGrid");
                });
                $(this).dialog('close');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function (event) {
        event.preventDefault();
        var
            $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                $dialogDetalhar.html(data).dialog('open');
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

    $(document.body).on('click', "a.excluir, a.editar, a.cadastrar, a.autenticarassinatura", function (event) {
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
                $dialog.html(data).dialog('open');
                $("#message").hide();
                $('.mask-cpf').mask("999.999.999-99");
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


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            paramsAssinar,
            url = {
                editar: base_url + '/projeto/termoaceite/editar',
                excluir: base_url + '/projeto/termoaceite/excluir',
                detalhar: base_url + '/projeto/termoaceite/detalhar',
                imprimir: base_url + '/projeto/termoaceite/imprimir',
                imprimirbook: base_url + '/projeto/termoaceite/imprimir-word',
            };
        params = '/idaceite/' + r[6] + '/identrega/' + r[7] + '/idprojeto/' + r[8];
        paramsAssinar = '/idaceite/' + r[6] + '/idprojeto/' + r[8];

        return '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            '<a data-target="#" class="btn actionfrm imprimir" title="Imprimir" target="_blank" data-id="' + cellvalue + '" href="' + url.imprimir + params + '"><i class="icon-print"></i></a>' +
            '<a data-target="#" class="btn actionfrm imprimir" title="Exportar" target="_blank" data-id="' + cellvalue + '" href="' + url.imprimirbook + params + '"><i class="icon-book"></i></a>'
            ;
    }

    colNames = ['Entrega Associada', 'Critério de Aceitação', 'Resposável pelo Aceite', 'Produto ou Serviço Entregue', 'Parecer Final', 'Aceite', 'Operações'];
    colModel = [{
        name: 'ac.nomatividadecronograma',
        index: 'ac.nomatividadecronograma',
        width: 25,
        search: true
    }, {
        name: 'ac.descriteiroaceitacao',
        index: 'ac.descriteiroaceitacao',
        width: 20,
        hidden: false,
        search: true
    }, {
        name: 'nomresponsavel',
        index: 'nomresponsavel',
        width: 20,
        align: 'center',
        search: true
    }, {
        name: 'a.desprodutoservico',
        index: 'a.desprodutoservico',
        width: 25,
        search: true
    }, {
        name: 'a.desparecerfinal',
        index: 'a.desparecerfinal',
        width: 20,
        search: true
    }, {
        name: 'a.flaaceite',
        index: 'a.flaaceite',
        width: 5,
        align: 'center',
        search: true
    }, {
        name: 'a.identrega',
        index: 'a.identrega',
        width: 35,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/projeto/termoaceite/retornaaceitesjson/idprojeto/" + idProjeto,
        datatype: "json",
        mtype: 'post',
        width: '800',
        height: '700',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomatividadecronograma',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
        }
    });

    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    $.ajax({
        url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            idprojeto: idProjeto
        },
        success: function (data) {
            data.projeto.ultimoStatusReport.datfimprojetotendencia = data.projeto.ultimoStatusReport.datfimprojetotendencia.substr(0, 10);
            TemplateManager.get('dados-projeto', function (tpl) {
                $("#dados-projeto").html(tpl(data.projeto));
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

    $('body').on('change', '#identrega', function () {
        $('.dados-entrega').hide();
        $.ajax({
            url: base_url + '/projeto/termoaceite/buscar-entrega/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idprojeto': $("input[name='idprojeto']").val(),
                'idatividadecronograma': $(this).val()
            },
            success: function (data) {
                $('.dados-entrega').show();

                $('.descricao-entrega > span').html(data.desobs);
                $('.criterio-aceitacao > span').html(data.descriterioaceitacao);
                $('.responsavel > span').html(data.nomparteinteressada);
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

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });
});

