/**
 * Comment
 */
function selectRow(row) {
    //console.log(row);
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    var
        vSalvar = true;

    actions = {
        editar: {
            url: '/projeto/rh/editarinterno/format/json',
            dialog: $('#dialog-editar')
        }
    };

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
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                var url;
                idpessoai = $('#dialog-editar').parent().find('input#idparteinteressada').val();
                //console.log(idpessoai);
                if (idpessoai > 0) {
                    $formEditar = $('#dialog-editar').parent().find('form#form-parte');
                    url = base_url + "/projeto/rh/editarinterno/format/json";

                    //url = $formEditar.action;
                } else {
                    $formEditar = $('#dialog-editar').parent().find('form#form-parte-externo');
                    url = base_url + "/projeto/rh/editarexterno/format/json";
                }
                //var url = base_url + "/projeto/rh/editarinterno/format/json";
                //var url = base_url + actions.editar.url;

                if ($formEditar.valid()) {
                    $('#dialog-editar').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    //console.log(url);
                    //$("#listagemInteressados").hide();
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        type: 'POST',
                        data: $formEditar.serialize(),
                        success: function (data) {
                            $.pnotify(data.msg);
                            if (data.success) {
                                $("#listagemInteressados").load(location.href + " #listagemInteressados>*", "");
                                //parent.window.location.reload();
                                actions.editar.dialog.dialog('close');
                            } else {
                                $('#dialog-editar').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }
                        },
                        error: function () {
                            $('#dialog-editar').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
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
                $('#dialog-editar').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar", function (event) {
        event.preventDefault();
        //var $this = $(this);
        //var idpessoainterna = $this.data('idpessoainterna');

        var $id = $(this).attr('data-id');
        var $idprojeto = $(this).attr('data-idprojeto');
        var idpessoainterna = $(this).attr('data-idpessoainterna');
        //var idparteinteressada = $this.data('idparteinteressada');
        //console.log(idpessoainterna.length);
        if (idpessoainterna.length > 0) {
            var url = base_url + "/projeto/rh/editarinterno/idparteinteressada/" + $id + '/idprojeto/' + $idprojeto;
            //var url = base_url + "/projeto/rh/editarinterno/idparteinteressada/" + $this.data('id') + '/idprojeto/' + $this.data('idprojeto');
            $.ajax({
                url: url,
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                processData: false,
                success: function (data) {
                    actions.editar.dialog.html(data).dialog('open');
                    //console.log(data);

                    console.log('errado');
                    var $form = $("form#form-parte");
                    //$form.action.url = "/projeto/rh/editarinterno/format/json";
                    $form.validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function (form) {
                            enviar_ajax("/projeto/rh/editarinterno/format/json", "form#form-parte", function (data) {
                                if (data.success) {
                                    //grid.trigger('reloadGrid');
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
        }
        if (idpessoainterna.length == 0) {

            var url = base_url + "/projeto/rh/editarexterno/idparteinteressada/" + $id + '/idprojeto/' + $idprojeto;
            $.ajax({
                url: url,
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                processData: false,
                success: function (data) {
                    actions.editar.dialog.html(data).dialog('open');

                    var $formExterno = $("form#form-parte-externo");
                    //$formExterno.action = "/projeto/rh/editarexterno/format/json";
                    $formExterno.validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function (formExterno) {
                            enviar_ajax("/projeto/rh/editarexterno/format/json", "form#form-parte-externo", function (data) {
                                if (data.success) {
                                    //grid.trigger('reloadGrid');
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

        }
        /**/
    });

    $("#accordion").accordion();

    $.pnotify.defaults.history = false;

    $("#tabs").tabs();

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $('.mask-tel').mask("(99) 9999-9999");

    //$('.externo').hide();

    $('.origem').click(function () {
        context = $(this).data('cont');
        $('.cont').hide().find('input,select').hide().prop('disabled', true);
        $('.' + context).show().find('select,input:text,input:hidden').fadeIn('slow').prop('disabled', false);
        $('.origem').removeClass('active');
        $('.origem-' + context).addClass('active');
    });

    $('.origem:first').trigger('click');

    $("#btn-adicionar-externo").click(function () {
        $("form#form-parte-externo").submit();

        $("#nomparteinteressadaexterno").val("");
        $("#nomfuncaoexterno").val("");
        $("#domnivelinfluenciaexterno").val("");
        $("#destelefoneexterno").val("");
        $("#desemailexterno").val("");
    });

    $("#btn-adicionar").click(function () {

        $("#btn-adicionar").prop("disabled", true);
        $("form#form-parte").submit();

        $("#nomparteinteressada").val("");
        $("#nomfuncao").val("");
        $("#domnivelinfluencia").val("");

        setTimeout(function () {
            $("#btn-adicionar").prop("disabled", false);
        }, 2000);
//        $.ajax({
//            url: base_url + "/projeto/tap/partesinteressadas/format/json",
//            dataType: 'json',
//            type: 'POST',
//            data: $("form#form-parte").serialize(),
//            success: function(data) {
//                $.pnotify(data.msg);
//                if (data.success) {
//                    $.adicionar(data.parte);
//                }
//
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

    $(document.body).on('click', '.classeDoSeuBotao', function (event) {
        event.preventDefault();
//        console.log('excluir');

    });

    $.adicionar = function (parte) {
        var $row = "<tr class='success'>" +
            //            "<td><a class='btn actionfrm excluir excluirbutton' title='Excluir Interessado' data-id='" + parte.idparteinteressada + "' >" +
            //            "<i class='icon-trash'></i>" +
            "</a></td>" +
            "<td>" + parte.nomparteinteressada + "</td>" +
            "<td>" + parte.nomfuncao + "</td>" +
            "<td>" + parte.destelefone + "</td>" +
            "<td>" + parte.desemail + "</td>" +
            "<td>" + parte.domnivelinfluencia + "</td>" +
            "<td class='tabela' style='width: 8px;' nowrap='' align='center'>" +
            "<a style='margin-right: -13px;' class='btn actionfrm editar editarbutton' title='Editar Interessado' data-id='" + parte.idparteinteressada + "' data-idprojeto='" + parte.idprojeto + "' data-idpessoainterna='" + parte.idpessoainterna + "'>" +
            "<i class='icon-edit'></i></a></td>" +
            "<td class='tabela' style='width: 8px;' nowrap='' align='center'>" +
            "<a style='margin-right: -13px;' class='btn actionfrm excluir excluirbutton' data-target='#dialog-editar' title='Excluir Interessado' data-idprojeto='" + parte.idprojeto + "' data-id='" + parte.idparteinteressada + "'>" +
            "<i class='icon-trash'></i>" +
            "</a></td>" +
            "</tr>";
        $("#listagemInteressados")
            .removeClass('hide')
            .find("table tbody").prepend($row);
        $("#nenhumregistro").hide();
    };


    $(document.body).on("click", ".excluirbutton", function () {
        var $id = $(this).attr('data-id');
        var $idprojeto = $(this).attr('data-idprojeto');
        var $idpessoainterna = $(this).attr('data-idpessoainterna');

        var $this = $(this);
        $.ajax({
            //url: base_url + "/projeto/tap/excluirparte/format/json/id/" + $this.data('id'),
            //url: base_url + "/projeto/tap/excluirparte/format/json/$idpessoainterna/" + $idpessoainterna + '/idprojeto/' + $idprojeto,
            url: base_url + "/projeto/tap/excluirparte/format/json/",
            dataType: 'json',
            type: 'POST',
            data: {
                id: $this.data('id'),
                idprojeto: $this.data('idprojeto'),
                idpessoainterna: $this.data('idpessoainterna')
            },
            success: function (data) {
                $.pnotify(data.msg);
                if (data.success) {
                    setTimeout(function () {
                        $("#btn-adicionar").prop("disabled", false);
                    }, 2000);
                    if ($("#listagemInteressados").find("table tr:visible").length <= 2) {
                        $this.parent().parent().hide();
                        $("#nenhumregistro").show();
                    } else {
                        $this.parent().parent().fadeOut();
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
    });

    var $form = $("form#form-parte");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/projeto/tap/partesinteressadas/format/json", "form#form-parte", function (data) {
                if (data.success) {
                    $.adicionar(data.parte);
                }
                //window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.dados.idprojeto;
                //$("#resetbutton").trigger('click');
//                }
            });
            //console.log('enviando');
        }
    });

    var $formExterno = $("form#form-parte-externo");
    $formExterno.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (formExterno) {
            enviar_ajax("/projeto/tap/partesinteressadasexterno/format/json", "form#form-parte-externo", function (data) {
                if (data.success) {
                    $.adicionar(data.parte);
                }
            });
        }
    });

    $(".pessoa-button").on('click', function (event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid",
                type: "GET",
                dataType: "html",
                success: function (html) {
                    $(".grid-append").append(html).slideDown('fast');
                }
            });
            $('.pessoa-button')
                .off('click')
                .on('click', function () {
                    var $this = $(this);
                    $(".grid-append").slideDown('fast', function () {
                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                        $this.closest('.control-group').addClass('input-selecionado');
                    });
                });
        }
    });
});