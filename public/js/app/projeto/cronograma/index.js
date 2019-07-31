jQuery.validator.addMethod("dateBR", function (value, element) {
    //contando chars
    if (value.length != 10) return (this.optional(element) || false);
    // verificando data
    var data = value;
    var dia = data.substr(0, 2);
    var barra1 = data.substr(2, 1);
    var mes = data.substr(3, 2);
    var barra2 = data.substr(5, 1);
    var ano = data.substr(6, 4);
    if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) return (this.optional(element) || false);
    if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) return (this.optional(element) || false);
    if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) return (this.optional(element) || false);
    if (ano < 1900) return (this.optional(element) || false);
    return (this.optional(element) || true);
}, "Data inválida"); // Mensagem padrão

$(function () {

    $('#status').click(function () {
        if ($(this).val() == '50') {
            $('.intervalo-percentual').show();
        } else {
            $('.intervalo-percentual').hide();
        }
    });


    CRONOGRAMA.altura.doc = $(document).height();
    CRONOGRAMA.init();
    CRONOGRAMA.grupo.init();
    CRONOGRAMA.entrega.init();
    CRONOGRAMA.atividade.init();

    var urls = {
            comentario: '/projeto/cronograma/addcomentario/format/json'
        },
        itens = {
            'itemAtual': {},
            'itemAnterior': {},
        },
        itemSelecionadoAterior,
        cron = {},
        urlComentario,
        $select = document.createElement("select"),
        idIMGSelecionada,
        $dialogComentario = $('#dialog-comentario');

    $("form#ac_atividade_pesquisar").validate();

    $('.colapseGrupo').click(function () {
        if ($(this).hasClass('mostrar')) {
            $(this).removeClass('mostrar');
            $('.colapseEntrega').removeClass('mostrar');
            $('.nivelPai-' + $(this).attr('data-value')).show(250);
            return;
        }

        $(this).addClass('mostrar');
        $('.nivelPai-' + $(this).attr('data-value')).hide(250);

    });

    $('.colapseEntrega').click(function () {
        if ($(this).hasClass('mostrar')) {
            $(this).removeClass('mostrar');
            $('.nivelEntrega-' + $(this).attr('data-value')).show(250);
            return;
        }

        $(this).addClass('mostrar');
        $('.nivelEntrega-' + $(this).attr('data-value')).hide(250);
    });

    $dialogComentario = $('#dialog-comentario').dialog({
        autoOpen: false,
        title: 'Adicionar Comentários',
        width: '730px',
        modal: true,
        buttons: {
            'Salvar': function () {
                if (($("#parte").val().length > 0) && ($("#tppermissao").val() == 1)) {
                    if ($("form#form-comentario").valid()) {
                        var form = $('form#form-comentario');
                        var $paramsForm = form.serialize();
                        $.ajax({
                            url: base_url + urls.comentario,
                            dataType: 'json',
                            type: 'POST',
                            async: true,
                            cache: true,
                            data: $paramsForm,
                            //processData:false,
                            success: function (data) {
                                if (data.success) {
                                    form.load('' + urlComentario + '');
                                    $.pnotify(data.msg);
                                    var textoIMG = "#img" + idIMGSelecionada[0];
                                    if (data.msg.qtdComentario == 0) {
                                        $(textoIMG).attr('src', base_url + '/img/comments.png');
                                    } else {
                                        $(textoIMG).attr('src', base_url + '/img/comments_blak.png');
                                    }
                                } else {
                                    $.pnotify(data.msg);
                                }
                            },
                            error: function () {
                                $('#dialog-comentario').dialog('close');
                                $.pnotify({
                                    text: 'Falha ao enviar a requisição',
                                    type: 'error',
                                    hide: false
                                });
                            }
                        });
                    }
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    $(document.body).on('click', ".comments", function (event) {
        event.preventDefault();
        var comentario = $(this).data("text"),
            idprojeto = $("#idprojeto").val(),
            idcronograma = $("#idatividadecronograma").val();
        var form = $('form#form-comentario');
        $.ajax({
            url: base_url + '/projeto/cronograma/excluircomentariojson/format/json',
            dataType: 'json',
            type: 'POST',
            async: true,
            cache: true,
            data: {idcomentario: comentario, idprojeto: idprojeto, idatividadecronograma: idcronograma},
            success: function (data) {
                if (data.success) {
                    form.load('' + urlComentario + '');
                    $.pnotify(data.msg);
                    //CRONOGRAMA.retornaProjeto();
                    var textoIMG = "#img" + idIMGSelecionada[0];
                    if (data.msg.qtdComentario == 0) {
                        $(textoIMG).attr('src', base_url + '/img/comments.png');
                    } else {
                        $(textoIMG).attr('src', base_url + '/img/comments_blak.png');
                    }
                } else {
                    $.pnotify(data.msg);
                }
            },
            error: function () {
                $('#dialog-comentario').dialog('close');
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(document.body).on('click', "a.comentarios", function (event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target')),
            $idatividadecronograma = $($this.data('text'));
        idIMGSelecionada = $($this.data('text'));
        $url = $this.attr('href') + "/idatividadecronograma/" + $idatividadecronograma[0],
            urlComentario = $url;
        $.ajax({
            url: $url,
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

    $("#closebutton_pesq").click(function (event) {
        $("#btn-buscar").click();
    });

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });

    cron.popularComboPorcentagem = function (obj, tipo) {

        var selected0, selected10, selected20, selected30, selected40,
            selected50, selected60, selected70, selected80, selected90, selected100;
        var selected = 'selected';
        var domtipoatividade = parseInt(tipo);

        selected0 = (obj.html() == 0) ? selected : '';
        selected10 = (obj.html() == 10) ? selected : '';
        selected20 = (obj.html() == 20) ? selected : '';
        selected30 = (obj.html() == 30) ? selected : '';
        selected40 = (obj.html() == 40) ? selected : '';
        selected50 = (obj.html() == 50) ? selected : '';
        selected60 = (obj.html() == 60) ? selected : '';
        selected70 = (obj.html() == 70) ? selected : '';
        selected80 = (obj.html() == 80) ? selected : '';
        selected90 = (obj.html() == 90) ? selected : '';
        selected100 = (obj.html() == 100) ? selected : '';

        if (domtipoatividade == 4) {
            obj.html("<select id='porcentagem' class='span1 input-toolbar' style='width: 58px !important; height: 22px !important;'>" +
                "<option value='0'  " + selected0 + ">0</option>" +
                "<option value='100' " + selected100 + ">100</option>" +
                "</select>");
        } else {

            obj.html("<select id='porcentagem' class='span1 input-toolbar' style='width: 58px !important; height: 22px !important;'>" +
                "<option value='0'  " + selected0 + ">0</option>" +
                "<option value='10'  " + selected10 + ">10</option>" +
                "<option value='20'  " + selected20 + ">20</option>" +
                "<option value='30'  " + selected30 + ">30</option>" +
                "<option value='40'  " + selected40 + ">40</option>" +
                "<option value='50'  " + selected50 + ">50</option>" +
                "<option value='60'  " + selected60 + ">60</option>" +
                "<option value='70'  " + selected70 + ">70</option>" +
                "<option value='80'  " + selected80 + ">80</option>" +
                "<option value='90'  " + selected90 + ">90</option>" +
                "<option value='100' " + selected100 + ">100</option>" +
                "</select>");
        }
    }

    cron.popularComboParteInteressada = function (idparteinteressada, obj) {

        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-parte-interessada/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idprojeto': $("#idprojeto").val(),
            },
            success: function (data) {
                var selected = '',
                    $select = "<select id='idparteinteressada' class='span1 input-toolbar' style='width: 120px !important; height: 22px !important;'>",
                    option = "<option value=''>Selecione</option>";

                $.each(data, function (id, valor) {
                    selected = (idparteinteressada == valor.idparteinteressada) ? ' selected' : '';
                    option += "<option value='" + valor.idparteinteressada + "'" + selected + ">" + valor.nomparteinteressada + "</option>";
                });

                $select += option;
                $select += "</select>";
                obj.html($select);
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição ',
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    $(document.body).on('click', ".grupo", function (event) {
        event.preventDefault();
        var par = $(this).parent().parent(); //tr
        var tdRadio = par.children("td:nth-child(1)");
        var tdBotoes = par.children("td:nth-child(2)");
        var tdNome = par.children("td:nth-child(5)");
        var tdResponsavel = par.children("td:nth-child(16)");
        var dados = tdRadio.children("input[type=radio]").data('text');
        var arrayDados = dados.replace("{", "").replace("}", "").split(',');
        var idparteinteressada = parseInt(arrayDados[8]);
        ;

        itens.itemAtual = {
            'nome': tdNome.html(),
            'responsavel': tdResponsavel.html(),
            'dados': arrayDados
        }

        if (typeof itemSelecionadoAterior == 'undefined') {
            itemSelecionadoAterior = par;
            itens.itemAnterior = itens.itemAtual;
        } else {
            verificaItemAnterior(par);
        }

        $('.item-cronograma').removeClass('success');

        tdRadio.children("input[type=radio]").attr('checked', 'checked');
        // $(this).parent().parent().addClass('success');

        tdNome.html("<input type='text' id='nomatividadecronograma' class='input-toolbar' style='width: 230px !important;height: 10px;' value='" + tdNome.html() + "' autocomplete='off'/>");
        cron.popularComboParteInteressada(idparteinteressada, tdResponsavel);
        tdBotoes.html("<i class='icon-ok' id='atualizarGrupo' title='Salvar'/>");
    });

    $(document.body).on('click', "#atualizarGrupo", function (event) {
        event.preventDefault();

        var par = $(this).parent().parent(); //tr
        var tdRadio = par.children("td:nth-child(1)");
        var tdNome = par.children("td:nth-child(5)");
        var tdResponsavel = par.children("td:nth-child(16)");
        var idprojeto = $("#idprojeto").val();
        var idatividadecronograma = tdRadio.children("input[type=radio]").val();

        $.ajax({
            url: base_url + '/projeto/cronograma/atualiza-grupo-in-line/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idatividadecronograma': idatividadecronograma,
                'idparteinteressada': tdResponsavel.children("select").val(),
                'nomatividadecronograma': tdNome.children("input[type=text]").val(),
                'idprojeto': idprojeto,
            },
            success: function (data) {

                if (data.msg.type == 'success') {
                    $.pnotify(data.msg);
                    itemSelecionadoAterior = '';
                    window.location.href = window.location.href;
                } else {
                    $.pnotify(data.msg);
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição ',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(document.body).on('click', ".entrega", function (event) {
        event.preventDefault();
        var par = $(this).parent().parent(); //tr
        var tdRadio = par.children("td:nth-child(1)");
        var tdBotoes = par.children("td:nth-child(2)");
        var tdNome = par.children("td:nth-child(6)");
        var tdResponsavel = par.children("td:nth-child(17)");
        var dados = tdRadio.children("input[type=radio]").data('text');
        var arrayDados = dados.replace("{", "").replace("}", "").split(',');
        var idparteinteressada = parseInt(arrayDados[8]);
        ;

        itens.itemAtual = {
            'nome': tdNome.html(),
            'responsavel': tdResponsavel.html(),
            'dados': arrayDados
        }

        if (typeof itemSelecionadoAterior == 'undefined') {
            itemSelecionadoAterior = par;
            itens.itemAnterior = itens.itemAtual;
        } else {
            verificaItemAnterior(par);
        }

        $('.item-cronograma').removeClass('success');

        tdRadio.children("input[type=radio]").attr('checked', 'checked');
        tdRadio.children("input[type=radio]").closest('.item-cronograma').addClass('success');

        tdNome.html("<input type='text' id='nomatividadecronograma' class='input-toolbar' style='width: 230px !important;height: 10px;' value='" + tdNome.html() + "' autocomplete='off'/>");
        cron.popularComboParteInteressada(idparteinteressada, tdResponsavel);
        tdBotoes.html("<i class='icon-ok' id='atualizarEntrega' title='Salvar'/>");
    });

    $(document.body).on('click', "#atualizarEntrega", function (event) {
        event.preventDefault();

        var par = $(this).parent().parent(); //tr
        var tdRadio = par.children("td:nth-child(1)");
        var tdNome = par.children("td:nth-child(6)");
        var tdResponsavel = par.children("td:nth-child(17)");
        var dados = tdRadio.children("input[type=radio]").data('text');
        var arrayDados = dados.replace("{", "").replace("}", "").split(',');
        var idprojeto = $("#idprojeto").val();

        var idatividadecronograma = tdRadio.children("input[type=radio]").val();

        $.ajax({
            url: base_url + '/projeto/cronograma/atualiza-entrega-in-line/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idatividadecronograma': idatividadecronograma,
                'idparteinteressada': tdResponsavel.children("select").val(),
                'nomatividadecronograma': tdNome.children("input[type=text]").val(),
                'idprojeto': idprojeto,
            },
            success: function (data) {
                if (data.msg.type == 'success') {
                    $.pnotify(data.msg);
                    itemSelecionadoAterior = '';
                    window.location.href = window.location.href;
                } else {
                    $.pnotify(data.msg);
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição ',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $(document.body).on('click', ".atividadeCron", function (event) {
        event.preventDefault();
        var par = $(this).parent().parent(); //tr
        var dados = $(this).data('text').replace(/{/g, "").replace(/}/g, "");
        var arrayDados = dados.split(",");
        var domTipoAtividade = parseInt(arrayDados[4]);
        var idparteinteressada = parseInt(arrayDados[8]);
        var contPredecessoras = parseInt(arrayDados[9]);
        var MARCO = parseInt(4);
        var ATIVIDADE = parseInt(3);
        var disabledInicio = (contPredecessoras > 0) ? 'disabled' : '';
        var disabledFim = (domTipoAtividade == MARCO) ? 'disabled' : '';

        if (domTipoAtividade > 2) {

            var tdRadio = par.children("td:nth-child(1)");
            var tdBotoes = par.children("td:nth-child(2)");
            var tdNome = par.children("td:nth-child(7)");
            var tdCusto = par.children("td:nth-child(12)");
            var tdDtInicio = par.children("td:nth-child(13)");
            var tdDtFim = par.children("td:nth-child(14)");
            var tdDiasReais = par.children("td:nth-child(16)");
            var tdPorcentagem = par.children("td:nth-child(17)");
            var tdResponsavel = par.children("td:nth-child(18)");


            itens.itemAtual = {
                'nome': tdNome.html(),
                'custo': tdCusto.html(),
                'inicio': tdDtInicio.html(),
                'fim': tdDtFim.html(),
                'diasReais': tdDiasReais.html(),
                'responsavel': tdResponsavel.html(),
                'porcentagem': tdPorcentagem.html(),
                'dados': arrayDados
            }

            if (typeof itemSelecionadoAterior == 'undefined') {
                itemSelecionadoAterior = par;
                itens.itemAnterior = itens.itemAtual;
            } else {
                verificaItemAnterior(par);
            }
            // console.log(itemSelecionadoAterior.children("td:nth-child(1)").parent().is('success'));
            // itemSelecionadoAterior.removeClass('success');
            $('.item-cronograma').removeClass('success');

            tdRadio.children("input[type=radio]").attr('checked', 'checked');
            par.closest('.item-cronograma').addClass('success');

            if (domTipoAtividade === MARCO) {
                tdDtFim.html(tdDtInicio.html());
                tdDiasReais.html(0);
            }

            tdNome.html("<input type='text' id='nomatividadecronograma' maxlength='200' class='input-toolbar' style='width: 180px !important;height: 10px;' value='" + tdNome.html() + "' autocomplete='off'/>");
            tdCusto.html("<input type='text' name='vlratividade' id='vlratividade' class='input-toolbar' autocomplete='off' style='width: 45px;height: 10px;' value='" + tdCusto.html() + "'/>");
            tdDtInicio.html("<input type='text' class='input-toolbar' autocomplete='off' name='e_datinicio' " + disabledInicio + " alt='Data inicio realizado' title='Data inicio realizado' id='e_datinicio' data-rule-required='true' data-rule-minlength='10' data-rule-dateBR='true' data-rule-dataAtividade='1' data-rule-dataAtividadeFeriado='1' style='width: 60px;height: 10px;'  value='" + tdDtInicio.html() + "'/>");
            tdDtFim.html("<input type='text' class='input-toolbar' autocomplete='off' name='e_datfim' id='e_datfim' " + disabledFim + " alt='Data fim realizado' title='Data fim realizado' data-rule-required='true' data-rule-minlength='10' data-rule-dateBR='true' data-rule-dataAtividade='1' data-rule-dataAtividadeFeriado='1' style='width: 60px;height: 10px;' value='" + tdDtFim.html() + "'/>");
            tdDiasReais.html("<input type='text' style='width: 25px !important;text-align: center;height: 10px;' " + disabledFim + "   autocomplete='off' title='Qtd de Dias Realizados' class='input-toolbar' name='e_numdiasrealizados' id='e_numdiasrealizados' data-rule-required='true' value='" + tdDiasReais.html() + "'/>");
            cron.popularComboPorcentagem(tdPorcentagem, domTipoAtividade);

            cron.popularComboParteInteressada(idparteinteressada, tdResponsavel);

            tdBotoes.html("<i class='icon-ok' id='atualizar' data-text='" + dados + "' title='Salvar'/>");
        }
    });

    function verificaItemAnterior(trAtual) {
        var tdRadioAtual = trAtual.children("td:nth-child(1)");
        var idatividadeAtual = parseInt(tdRadioAtual.children("input[type=radio]").val());
        var tdRadioAnterior = itemSelecionadoAterior.children("td:nth-child(1)");
        var idatividadeAnterior = parseInt(tdRadioAnterior.children("input[type=radio]").val());

        if (idatividadeAtual != idatividadeAnterior) {
            cancelarItem(itemSelecionadoAterior);
            itemSelecionadoAterior = trAtual;
            return true;
        }
        return;
    }

    function cancelarItem(par) {
        var tdRadio = par.children("td:nth-child(1)");
        var tdBotoes = par.children("td:nth-child(2)");
        var tdPredecessora = par.children("td:nth-child(8)");
        var tdDtInicioBase = par.children("td:nth-child(9)");
        var tdDtFimBase = par.children("td:nth-child(10)");
        var tdCusto = par.children("td:nth-child(12)");
        var tdDtInicio = par.children("td:nth-child(13)");
        var tdDtFim = par.children("td:nth-child(14)");
        var tdDiasReais = par.children("td:nth-child(16)");
        var tdPorcentagem = par.children("td:nth-child(17)");

        var botao;

        if (itens.itemAnterior.dados[4] == 1) {
            var tdNome = par.children("td:nth-child(5)");
            var tdResponsavel = par.children("td:nth-child(16)");
            botao = "<i class='icon-edit grupo' data-text='" + itens.itemAnterior.dados + "'' title='Editar Grupo'/>";
        } else if (itens.itemAnterior.dados[4] == 2) {
            var tdNome = par.children("td:nth-child(6)");
            var tdResponsavel = par.children("td:nth-child(17)");
            botao = "<i class='icon-edit entrega' data-text='" + itens.itemAnterior.dados + "'' title='Editar Entrega'/>";
        } else if (itens.itemAnterior.dados[4] == 3) {
            var tdNome = par.children("td:nth-child(7)");
            var tdResponsavel = par.children("td:nth-child(18)");
            botao = "<i class='icon-edit atividadeCron' data-text='" + itens.itemAnterior.dados + "' title='Editar Atividade'/>";
        } else if (itens.itemAnterior.dados[4] == 4) {
            var tdNome = par.children("td:nth-child(7)");
            var tdResponsavel = par.children("td:nth-child(18)");
            botao = "<i class='icon-edit atividadeCron' data-text='" + itens.itemAnterior.dados + "'' title='Editar Marco'/>";
        }

        tdNome.text(itens.itemAnterior.nome);
        tdResponsavel.text(itens.itemAnterior.responsavel);

        if (parseInt(itens.itemAnterior.dados[4]) > 2) {
            tdCusto.text(itens.itemAnterior.custo);
            tdDtInicio.text(itens.itemAnterior.inicio);
            tdDtFim.text(itens.itemAnterior.fim);
            tdDiasReais.text(itens.itemAnterior.diasReais);
            tdPorcentagem.text(itens.itemAnterior.porcentagem);
            tdBotoes.html(botao);
        } else {
            tdBotoes.html(botao);
        }
        itens.itemAnterior = {};
        itens.itemAnterior = itens.itemAtual;
        itens.itemAtual = {};

    }

    $(document.body).on('click', "#atualizar", function (event) {
        event.preventDefault();
        var numfolga = 0;
        var par = $(this).parent().parent(); //tr
        var tdRadio = par.children("td:nth-child(1)");
        var tdBotoes = par.children("td:nth-child(2)");
        var tdNome = par.children("td:nth-child(7)");
        var tdPredecessora = par.children("td:nth-child(8)");
        var tdDtInicioBase = par.children("td:nth-child(9)");
        var tdDtFimBase = par.children("td:nth-child(10)");
        var tdCusto = par.children("td:nth-child(12)");
        var tdDtInicio = par.children("td:nth-child(13)");
        var tdDtFim = par.children("td:nth-child(14)");
        var tdDiasReais = par.children("td:nth-child(16)");
        var tdPorcentagem = par.children("td:nth-child(17)");
        var tdResponsavel = par.children("td:nth-child(18)");
        var dados = $(this).data('text').replace(/{/g, "").replace(/}/g, "");
        var arrayDados = dados.split(",");
        var idprojeto = $("#idprojeto").val();
        var domtipoatividade = parseInt(arrayDados[4]);
        var ATIVIDADE = parseInt(3);
        var MARCO = parseInt(4);
        var listaPredecessora = {};
        var totalDias = 0;
        var dataFimMarco;

        if (tdPredecessora.children('input#listaPredec').val() != 'undefined') {
            listaPredecessora = tdPredecessora.children('input#listaPredec').val();
        }

        if (tdDiasReais.children("input[type=text]").val() != 'undefined') {
            totalDias = tdDiasReais.children("input[type=text]").val();
        }

        if (domtipoatividade === 3) {
            numfolga = parseInt(arrayDados[3]);
        }

        if (domtipoatividade === MARCO) {
            dataFimMarco = tdDtInicio.children("input[type=text]").val();
            tdDtFim.children("input[type=text]").val(dataFimMarco);
            tdDiasReais.html(0);
        }

        var idgrupo = parseInt(arrayDados[5]);
        var idatividadecronograma = tdRadio.children("input[type=radio]").val();

        if (tdNome.children("input[type=text]").val().length == 0) {
            $.pnotify({text: 'Defina um nome para atividade.', type: 'info', hide: true});
            return false;
        }
        if (tdDtInicio.children("input[type=text]").val().length == 0) {
            $.pnotify({text: 'Defina uma data de inicio para atividade.', type: 'info', hide: true});
            return false;
        }
        if (tdDtFim.children("input[type=text]").val().length == 0) {
            $.pnotify({text: 'Defina uma data fim para atividade.', type: 'info', hide: true});
            return false;
        }
        if (tdResponsavel.children("select").val().length == 0) {
            $.pnotify({text: 'Defina o responsável pela atividade.', type: 'info', hide: true});
            return false;
        } else {

            $.ajax({
                url: base_url + '/projeto/cronograma/atividade-atualizar-percentual/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'datinicio': tdDtInicio.children("input[type=text]").val(),
                    'datfim': tdDtFim.children("input[type=text]").val(),
                    'datiniciobaseline': tdDtInicioBase.html(),
                    'datfimbaseline': tdDtFimBase.html(),
                    'vlratividade': tdCusto.children("input[type=text]").val(),
                    'numpercentualconcluido': tdPorcentagem.children("select").val(),
                    'idatividadecronograma': idatividadecronograma,
                    'idparteinteressada': tdResponsavel.children("select").val(),
                    'domtipoatividade': domtipoatividade,
                    'nomatividadecronograma': tdNome.children("input[type=text]").val(),
                    'numdiasrealizados': totalDias,
                    'listaPredecessoras': listaPredecessora,
                    'numfolga': parseInt(numfolga),
                    'idprojeto': parseInt(idprojeto),
                    'idgrupo': parseInt(idgrupo)
                },
                success: function (data) {

                    if (data.msg.type == 'success') {
                        $.pnotify(data.msg);
                        window.location.href = window.location.href;
                    } else {
                        $.pnotify(data.msg);
                    }
                },
                error: function () {
                    $.pnotify({
                        text: 'Falha ao enviar a requisição ',
                        type: 'error',
                        hide: false
                    });
                }
            });
        }
    });

});
