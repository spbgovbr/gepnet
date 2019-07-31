/**
 * Comment
 */

$(function () {

    var urls = {
            listaOpcoes: base_url + "/diagnostico/questionario/retornaopcoesrespostajson/format/json?idpergunta=" + $("#idpergunta").val() + "&idquestionario=" + $("#idquestionario").val(),
            editarResposta: base_url + '/diagnostico/questionario/manipulaopcoesrespostajson/format/json?idpergunta=' + $("#idpergunta").val() + "&idquestionario=" + $("#idquestionario").val()
        },
        $idpergunta = $('#idpergunta').val();

    var gridOp = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;

    $.pnotify.defaults.history = false;

    $('#acordion2').width($('.region-center').width());

    $(document.body).on('focusout', "#ordenacao", function (event) {
        if (($('#posicaocad').val().split('|').indexOf($(this).val()) != -1)
            && ($('#posicaoat').val() != $(this).val())) {
            $('#ordenacao').parent().addClass('error');
            $('#ordenacao').parent().append('<label id="error" for="ordenacao" class="error poser">Já existe posição n° ' + $(this).val() + '.</label>');
            $("#submitbutton").attr('disabled', 'true');
        } else {
            $("#submitbutton").removeAttr("disabled", 'false');
            $('#ordenacao').parent().removeClass('error');
            $('.poser').remove();
        }
    });

    $("#dialog-opcao-resposta").height(480);

    $("#dialog-opcao-resposta").width(700);

    $("#accordion2").width($("#dialog-opcao-resposta").width() - 90);

    $("#list").width($(".span8").width() - 10);

    $(document.body).on('click', '#accordion2', function (event) {
        event.preventDefault();
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
            apresentaListagem();
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });

    $(document.body).on('click', "#remov", function (event) {
        event.preventDefault();
        var gr = jQuery("#list").jqGrid('getGridParam', 'selrow');

        if (gr != null)
            jQuery("#list").jqGrid('editGridRow', gr, {
                editCaption: "Remover Opção de Resposta?",
                top: 50,
                left: 350,
                modal: true,
                width: 600,
                height: 'auto',
                url: base_url + '/diagnostico/questionario/manipulaopcoesrespostajson/format/json?operacao=del&idpergunta=' + $("#idpergunta").val() + "&idquestionario=" + $("#idquestionario").val(),
                recreateForm: true,
                msg: "Deseja remover opção selecionada?",
                bSubmit: "Sim",
                bCancel: "Não",
                beforeShowForm: function ($form) {
                    $("#ordenacao").attr('disabled', 'disabled');
                    $("#desresposta").attr('disabled', 'disabled');
                    $("#escala").attr('disabled', 'disabled');
                },
                //afterSubmit: function ($form) {
                //    if($form.status==200) {
                //        $.pnotify({
                //            text: 'Registro excluído com sucesso',
                //            type: 'success',
                //            hide: false
                //        });
                //    }else{
                //        $.pnotify({
                //            text: 'Não foi possível realizar essa operação.',
                //            type: 'error',
                //            hide: false
                //        });
                //    }
                //},
                reloadAfterSubmit: true,
                closeAfterEdit: true,
            });
        else
            $.pnotify({
                text: 'Selecione um registro para exclusão.',
                type: 'info',
                hide: true
            });
    });

    $(document.body).on('click', "#edit", function (event) {
        event.preventDefault();
        var gr = jQuery("#list").jqGrid('getGridParam', 'selrow');
        //console.log(gr);
        if (gr != null)
            jQuery("#list").jqGrid('editGridRow', gr, {
                editCaption: "Alterar Opção de Resposta",
                top: 50,
                left: 350,
                width: 600,
                recreateForm: true,
                url: base_url + '/diagnostico/questionario/manipulaopcoesrespostajson/format/json?operacao=edit&idpergunta=' + $("#idpergunta").val() + "&idquestionario=" + $("#idquestionario").val(),
                bSubmit: "Salvar",
                bCancel: "Cancelar",
                modal: true,
                height: 'auto',
                beforeShowForm: function ($form) {
                    $('#ordenacao').keyup(function () {
                        $(this).val(this.value.replace(/\D/g, ''));
                    });
                    if ($('#tpregistro').val() == 2) {
                        $('#tr_escala').remove();
                    } else {
                        $('#escala').keyup(function () {
                            $(this).val(this.value.replace(/\D/g, ''));
                        });
                    }
                },
                //afterSubmit: function ($form) {
                //    //if($form.status==200) {
                //        $.pnotify({
                //            text: 'Registro alterado com sucesso',
                //            type: 'success',
                //            hide: false
                //        });
                //    //}else{
                //    //    $.pnotify({
                //    //        text: 'Não foi possível realizar essa operação.',
                //    //        type: 'error',
                //    //        hide: false
                //    //    });
                //    //}
                //},
                reloadAfterSubmit: true,
                closeAfterEdit: true,
            });
        else
            $.pnotify({
                text: 'Selecione um registro para alteração.',
                type: 'info',
                hide: true
            });
    });

    $(document.body).on('click', "#add", function (event) {
        event.preventDefault();

        jQuery("#list").jqGrid('editGridRow', 'new', {
            addCaption: "Adicionar Opção de Resposta",
            top: 50,
            left: 350,
            width: 600,
            recreateForm: true,
            url: base_url + '/diagnostico/questionario/opcaorespostaadd/format/json?operacao=add&idpergunta=' + $("#idpergunta").val() + "&idquestionario=" + $("#idquestionario").val(),
            bSubmit: "Salvar",
            bCancel: "Cancelar",
            modal: true,
            height: 'auto',
            reloadAfterSubmit: true,
            closeAfterReset: true,
            clearAfterAdd: true,
            beforeShowForm: function ($form) {
                $('#ordenacao').keyup(function () {
                    $(this).val(this.value.replace(/\D/g, ''));
                });
                if ($('#tpregistro').val() == 2) {
                    $('#tr_escala').remove();
                } else {
                    $('#escala').keyup(function () {
                        $(this).val(this.value.replace(/\D/g, ''));
                    });
                }
            },
        });

    });

    $(document.body).on('click', "a.editar-opcao, a.excluir-opcao", function (event) {

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
                $("#list").setGridParam({datatype: 'json', page: 1}).trigger('reloadGrid');
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

    colNames = ['Indentificador', 'Ordem', 'Descrição de Resposta', 'Escala'];

    colModel = [
        {
            name: 'idresposta',
            index: 'idresposta',
            width: 20,
            hidden: false,
            align: 'center',
            search: false,
            sortable: false,
            resize: false,
            editable: true,
            editoptions: {readonly: true, size: 10},
        }, {
            name: 'ordenacao',
            index: 'ordenacao',
            align: 'center',
            width: 20,
            hidden: false,
            search: false,
            editable: true,
        }, {
            name: 'desresposta',
            index: 'desresposta',
            align: 'left',
            width: 140,
            hidden: false,
            search: false,
            editable: true,
            edittype: "textarea",
            editoptions: {
                rows: "2",
                cols: "35",
                required: true
            }
        }, {
            name: 'escala',
            index: 'escala',
            align: 'center',
            width: 20,
            hidden: false,
            search: false,
            editable: true,
            editoptions: {size: 10, maxlegent: 2}
        }
    ];

    function apresentaListagem() {

        gridOp = jQuery("#list").jqGrid({
            caption: "Listagem de Opções de Resposta",
            url: base_url + "/diagnostico/questionario/retornaopcoesrespostajson/format/json?idpergunta=" + $("#idpergunta").val() + "&idquestionario=" + $("#idquestionario").val(),
            datatype: "json",
            width: 750,
            height: 170,
            mtype: 'post',
            colNames: colNames,
            colModel: colModel,
            rowNum: 20,
            rowList: [20, 50, 100],
            pager: '#pager',
            viewrecords: true,
            sortname: 'ordenacao',
            sortorder: "asc"
        });

        gridOp.jqGrid('navGrid', '#pager', {
            search: false,
            edit: false,
            add: false,
            del: false,
            view: false
        });
    }

});
