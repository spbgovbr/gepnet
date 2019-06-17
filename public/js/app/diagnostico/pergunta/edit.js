/**
 * Comment
 */

$(function () {
    $("#id_secao").focus();
    var urls = {
            addPergunta: '/diagnostico/questionario/pergunta-add/format/json',
            retornalistagem: base_url + "/diagnostico/questionario/retornaperguntajson/idquestionario/" + $('#idquestionario').val(),
            alterarPergunta: "/diagnostico/questionario/perguntaeditar/format/json",
            excluirPergunta: "/diagnostico/questionario/excluir-pergunta-questinario/format/json",
            addResposta: '/diagnostico/questionario/opcaorespostaadd/format/json',
        },
        $idquestionario = $('#idquestionario').val(),
        $dialogIncluir = $('#dialog-incluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogExcluir = $('#dialog-excluir'),
        $dialogCadastrar = $('#dialog-opcao-resposta'),
        formExcluirPergunta = $('#form-exclui-pergunta'),
        $formPergunta = $('#form-pergunta');

    var grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;

    $("#menuquest")
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menuquest')
        .find('a').unbind('click');

    $('#menuquest')
        .find('a').on("click", function (e) {
        e.find('li.active')
        e.removeClass('disabled')
        e.addClass('enabled');
    });


    $.pnotify.defaults.history = false;


    $("#gbox_list-grid").width($('.region-center').width() - 50);

    $('#menuquest').width($('.region-center').width() - 50);

    $("#tipopergunta").attr("disabled");

    $(document.body).on('focusout', "#posicao", function (event) {
        event.preventDefault()
        if (($('#posicaocad').val().split('|').indexOf($(this).val()) != -1)
            && ($('#posicaocad').val() == $(this).val())) {
            if ($('#posicao').parent().find("> #error").length == 0) {
                $('#posicao').parent().append('<label id="error" for="posicao" class="error poser">Já existe posição n° ' + $(this).val() + '.</label>');
            } else {
                $('#posicao').parent().find("> #error").empty();
            }
            $("#submitbutton").attr('disabled', 'true');
        } else {
            $("#submitbutton").removeAttr("disabled", 'false');
            $('.poser').remove();
        }
    });

    function removeValidacao(obj) {

        if (obj.hasClass('error')) {
            obj.removeClass('error');
        }
        obj.removeAttr('data-rule-required');
        obj.parent().find("> label:eq(0)").each(function () {
            if ($(this).hasClass('required')) {
                $(this).removeClass('required');
            }
        });
        if (obj.parent().find("> label:eq(1)").length > 0) {
            obj.parent().find("> label:eq(1)").each(function () {
                if ($(this).text.length == 0) {
                    $(this).text('Este campo é requerido.');
                }
                $(this).removeClass('error');
                $(this).removeClass('required');
                $(this).empty();
            });
        }
        obj.parent().removeClass('error');
    }

    function setObrigatoriedade(obj) {
        obj.removeAttr('disabled');
        obj.addClass('error');
        obj.attr('data-rule-required', '1');
        obj.parent().find("> label:eq(0)").each(function () {
            if ($(this).hasClass(('optional')).toString()) {
                $(this).removeClass('optional');
            }
            if ($(this).hasClass(('error')).toString()) {
                $(this).removeClass('error');
            }
            $(this).addClass('required');
        });
        if (obj.parent().find("> label:eq(1)").length == 0) {
            obj.parent().append('<label for="' + obj.attr('id') + '" class="error" ></label>');
        } else {
            obj.parent().find("> label:eq(1)").each(function () {
                if ($(this).text.length == 0) {
                    $(this).text('Este campo é requerido.');
                }
                $(this).addClass('required');
            });
        }

        if (obj.parent().hasClass('error')) {
            obj.parent().removeClass('error');
        }

    }

    $(document.body).on('keypress', "#dspergunta", function (event) {
        event.preventDefault
        $(this).parent().find("> label:eq(1)").each(function () {
            if ($(this).parent().hasClass('error')) {
                $(this).hide();
            }
        });

    });

    validacao = function () {
        var $form = $('form#form-pergunta');
        var itemSelecionado = $("#id_secao option:selected");

        if (itemSelecionado.val().length == 0) {
            setObrigatoriedade($("#dstitulo"));
            removeValidacao($("#posicao"));
        } else {
            if (itemSelecionado.val() == 1 || itemSelecionado.val() == 2) {
                removeValidacao($("#dstitulo"));
                removeValidacao($("#posicao"));
                $("#dstitulo").attr('disabled', 'disabled');
                $("#posicao").focus();
            } else {
                if (itemSelecionado.val() == 8) {
                    removeValidacao($("#dstitulo"));
                    $("#dstitulo").attr('disabled', 'disabled');
                    $("#posicao").focus();
                } else {
                    setObrigatoriedade($("#dstitulo"));
                    setObrigatoriedade($("#posicao"));
                    $("#posicao").focus();

                }
            }
        }
    }

    $(document.body).on('focusin', "#id_secao", function (event) {
        event.preventDefault
        validacao();
    });

    $(document.body).on('change', "#id_secao", function (event) {
        event.preventDefault
        validacao();
    });

    function setObrigatoriedade(obj) {
        obj.removeAttr('disabled');
        obj.addClass('error');
        obj.attr('data-rule-required', '1');
        obj.parent().find("> label:eq(0)").each(function () {
            if ($(this).hasClass(('optional')).toString()) {
                $(this).removeClass('optional');
            }
            if ($(this).hasClass(('error')).toString()) {
                $(this).removeClass('error');
            }
            $(this).addClass('required');
        });
        if (obj.parent().find("> label:eq(1)").length == 0) {
            obj.parent().append('<label for="' + obj.attr('id') + '" class="error" ></label>');
        } else {
            obj.parent().find("> label:eq(1)").each(function () {
                if ($(this).text.length == 0) {
                    $(this).text('Este campo é requerido.');
                }
                $(this).addClass('required');
            });
        }

        if (obj.parent().hasClass('error')) {
            obj.parent().removeClass('error');
        }

    }


    tipoPergunta = function () {
        $("#tipopergunta").removeAttr("disabled");
        $("#tipopergunta option[value=1]").removeAttr("disabled");
        $("#tipopergunta option[value=2]").removeAttr("disabled");
        $("#tipopergunta option[value=3]").removeAttr("disabled");

        if ($("#tiporegistro option:selected").val() == 1) {
            $("#tipopergunta option[value=1]").removeAttr("disabled");
            $("#tipopergunta option[value=2]").removeAttr("disabled");
            $("#tipopergunta option[value=3]").removeAttr("disabled");
            $("#tipopergunta option[value='']").remove('selected', 'selected');
            $("#tipopergunta option[value=1]").attr('disabled', 'disabled');
        } else {
            if ($("#tiporegistro option:selected").val() == 2) {
                $("#tipopergunta option[value=1]").removeAttr("disabled");
                $("#tipopergunta option[value=2]").removeAttr("disabled");
                $("#tipopergunta option[value=3]").removeAttr("disabled");
                //$("#tipopergunta option[value='']").remove('selected','selected');
            }
        }
        //        $("#tipopergunta option[value=1]").removeAttr("disabled");
        //        $("#tipopergunta option[value=2]").removeAttr("disabled");
        //        $("#tipopergunta option[value=3]").removeAttr("disabled");
        //        $("#tipopergunta option[value='']").remove('selected','selected');
        //        $("#tipopergunta option[value=2]").attr('disabled','disabled');
        //        $("#tipopergunta option[value=3]").attr('disabled','disabled');
        //    } else {
        //        $("#tipopergunta option[value=1]").attr('disabled','disabled');
        //        $("#tipopergunta option[value=2]").attr('disabled','disabled');
        //        $("#tipopergunta option[value=3]").attr('disabled','disabled');
        //        $("#tipopergunta option[value='']").attr('selected','selected');
        //        $("#tipopergunta").attr("disabled");
        //    }
        //}
    }

    $(document.body).on('change', "#tiporegistro", function (event) {
        event.preventDefault
        tipoPergunta();
    });

    $(document.body).on('focusin', "#tipopergunta", function (event) {
        event.preventDefault
        tipoPergunta();
    });

    $(document.body).on('change', "#tipopergunta", function (event) {
        event.preventDefault
        tipoPergunta();
    });

    $('#posicao').keyup(function () {
        $(this).val(this.value.replace(/\D/g, ''));
    });

    $dialogIncluir.dialog({
        autoOpen: false,
        title: 'Adicionar Pergunta',
        width: '730px',
        modal: true,
        buttons: {
            'Salvar': function () {
                if ($("form#form-pergunta").valid()) {
                    var form = $('form#form-pergunta');
                    var $paramsForm = $('form#form-pergunta').serialize();
                    $.ajax({
                        url: base_url + urls.addPergunta,
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: $paramsForm,
                        success: function (data) {
                            $.pnotify(data.msg);
                            $('#dialog-incluir').html(data).dialog('close');
                            $("#list-grid").setGridParam({datatype: 'json', page: 1}).trigger('reloadGrid');
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
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Editar Pergunta',
        width: '730px',
        modal: true,
        buttons: {
            'Salvar': function () {
                if ($("form#form-editar-pergunta").valid()) {
                    var form = $('form#form-editar-pergunta');
                    var $paramsForm = $('form#form-editar-pergunta').serialize();
                    $.ajax({
                        url: base_url + urls.alterarPergunta,
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: $paramsForm,
                        success: function (data) {
                            $.pnotify(data.msg);
                            $('#dialog-editar').html(data).dialog('close');
                            $("#list-grid").setGridParam({datatype: 'json', page: 1}).trigger('reloadGrid');

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
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $dialogExcluir.dialog({
        autoOpen: false,
        title: 'Excluir Pergunta',
        width: '730px',
        modal: true,
        close: function (event, ui) {
            vExcluir = true;
            $('#dialog-excluir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        open: function (event, ui) {
            vExcluir = true;
            $('#dialog-excluir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        buttons: {
            'Salvar': function () {
                if ($("form#form-excluir-pergunta").valid()) {
                    var form = $('form#form-excluir-pergunta');
                    var $paramsForm = $('form#form-excluir-pergunta').serialize();
                    $.ajax({
                        url: base_url + urls.excluirPergunta,
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: $paramsForm,
                        success: function (data) {
                            $.pnotify(data.msg);
                            $('#dialog-excluir').html(data).dialog('close');
                            $("#list-grid").setGridParam({datatype: 'json', page: 1}).trigger('reloadGrid');

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
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogCadastrar.dialog({
        autoOpen: false,
        title: 'Gerenciar Opções de Respostas',
        width: 880,
        heigth: 180,
        modal: true,
        close: function (event, ui) {
            vExcluir = true;
            $('#dialog-opcao-resposta').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        open: function (event, ui) {
            vExcluir = true;
            $('#dialog-opcao-resposta').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $(document.body).on('click', "a.incluir, a.editar, a.excluir, a.cadastrar", function (event) {
        $("#list").setGridParam({datatype: 'json', page: 1}).trigger('reloadGrid');
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target')),
            $url = $this.attr('href');

        $.ajax({
            url: $url,
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                $dialog.html(data).dialog('open');
                //verificaSecao();
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
            url = {
                editar: base_url + '/diagnostico/questionario/perguntaeditar',
                excluir: base_url + '/diagnostico/questionario/excluir-pergunta-questinario',
                cadastrar: base_url + '/diagnostico/questionario/opcaorespostaadd',
            };

        params = '/idquestionario/' + $idquestionario + '/idpergunta/' + r[8] + '/tpregistro/' + r[7];

        $return = '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar Pergunta" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir Pergunta" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
        if (r[6] != 1) {
            $return = $return + '<a data-target="#dialog-opcao-resposta" class="btn actionfrm cadastrar" title="Gerenciar Opções de Resposta" data-id="' + cellvalue + '" href="' + url.cadastrar + params + '"><i class="icon-tasks"></i></a>';
        } else {
            $return = $return + '<a disabled class="btn actionfrm cadastrar" title="Não permitido para perguntas descritivas" ><i class="icon-tasks"></i></a>';
        }
        return $return;

    }

    colNames = ['Ordem', 'Seção', 'Descrição da Pergunta', 'Enunciado da Pergunta ', 'Obrigatório', 'Tipo', 'tp_pergutna', 'tp_registro', 'idpergunta', 'Operações'];

    colModel = [
        {
            name: 'posicao',
            index: 'posicao',
            align: 'center',
            width: 60,
            hidden: false,
            search: false,
            editable: false
        },
        {
            name: 'ds_secao',
            index: 'ds_secao',
            align: 'left',
            width: 100,
            hidden: false,
            search: false,
            editable: false
        }, {
            name: 'dspergunta',
            index: 'dspergunta',
            align: 'left',
            width: 440,
            hidden: false,
            search: false,
            editable: false
        }, {
            name: 'dstitulo',
            index: 'dstitulo',
            align: 'center',
            width: 440,
            hidden: false,
            search: false,
            editable: false
        }, {
            name: 'obrigatoriedade',
            index: 'obrigatoriedade',
            align: 'center',
            width: 60,
            hidden: false,
            search: false,
            editable: false
        }, {
            name: 'ds_pergunta',
            index: 'ds_pergunta',
            align: 'center',
            width: 80,
            hidden: false,
            search: false,
            editable: false
        }, {
            name: 'tp_pergunta',
            index: 'tp_pergunta',
            align: 'center',
            width: 80,
            hidden: true,
            search: false,
            editable: false
        }, {
            name: 'tp_registro',
            index: 'tp_registro',
            align: 'center',
            width: 80,
            hidden: true,
            search: false,
            editable: false
        }, {
            name: 'idpergunta',
            index: 'idpergunta',
            width: 10,
            hidden: true,
            search: false,
            sortable: false,
            sorttype: "int",
            //formatter: formatadorLink
        }, {
            name: 'operacao',
            index: 'operacao',
            width: 140,
            //hidden: true,
            search: false,
            sortable: false,
            //sorttype:"int",
            formatter: formatadorLink
        }
    ];

    grid = jQuery("#list-grid").jqGrid({
        caption: "Listagem de Perguntas",
        url: urls.retornalistagem,
        datatype: "json",
        mtype: 'post',
        height: 'auto',
        colNames: colNames,
        colModel: colModel,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid',
        viewrecords: true,
        sortname: 'posicao',
        sortorder: "asc",
        //hiddengrid: true,
        grouping: true,
        groupingView: {
            groupField: ['ds_secao'],
            groupColumnShow: [false],
            groupText: ['<b>{0} - {1} Item(s)</b>'],
            groupCollapse: false,
            groupOrder: ['asc']
        }
    });

    grid.jqGrid('navGrid', '#pager-grid', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    //resizeGrid();
});
