jQuery.validator.addMethod("dataAtividade", function (value, element) {
    var diasValor = 0;
    var dtIni = $("#e_datinicio").val();
    dtIni = dtIni.trim();
    var dtFim = $("#e_datfim").val();
    dtFim = dtFim.trim();
    /***************************************************/
    if ((verificaData(dtIni)) && (verificaData(dtFim))) {
        var diasValor = calculaDiasEntreDatas(dtIni, dtFim);
        if (diasValor < 0) {
            $("#e_datfim").val($("#e_datinicio").val());
            return true;
        } else {
            $(element).attr('title', $(element).attr('alt'));
            return true;
        }
    } else {
        $(element).attr('title', 'Data inválida');
        return false;
    }
}, "Data inválida");
jQuery.validator.addMethod("dataAtividadeFeriado", function (value, element) {
    var dia = 1;
    var selectedDate = $(element).datepicker('getDate');
    if (verificaData(value)) {
        var dtTemp = montaData(value);
        dia = dtTemp.getDay();
    }
    if ((dia < 1 || dia > 5)) {
        return false;
    } else {
        if (CRONOGRAMA.nonUtilDates(selectedDate)) {
            return false;
        } else {
            if (CRONOGRAMA.dataFeriado(value)) {
                return false;
            }
            return true;
        }
    }
}, "Data inválida.");
//XXXXXXXXXX ATIVIDADE XXXXXXXXXX
CRONOGRAMA.atividade = (function ($, Handlebars, Intervalo) {
        var o = {};
        vSalvar = true;
        vAtualizar = false;
        msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
        msgerroacesso = 'Acesso negado para essa ação';
        o.$dialogAtividade = null;
        o.$dialogAtividadeExcluir = null;
        o.formAtividade = "form#ac-atividade";
        o.formAtiviPredecxcluir = "form#ac-ativiPredec-excluir";
        o.formAtividadeExcluir = "form#ac-atividade-excluir";
        o.formPredecessoraExcluir = "form#ac-predecessora-excluir";
        o.formPercentual = "form#e_atividade";
        o.formAtualizarBaselineAtiv = "form#atualizar-baseline-ativ";
        o.alertaPredecessora = "#alert-predecessora";
        o.templatePredecessora = null;
        o.tablePredecessora = "table#table-predecessoras";
        o.selectPredecessora = "select#predecessora";
        o.selectPredecAtividade = "select#predecessorasAtividade";
        o.selectPercentualconcluido = "select#numpercentualconcluido";
        o.selectPredecessoraEditar = ".container-predecessora-editar select#predecessora";
        o.linkPredecessora = 'a.remover-predecessora';
        o.linkPredecessoraEditar = 'a.remover-predecessora-editar';
        o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
        o.predecAtividadeNova = {};
        o.dataMaior = null;
        o.arrDatMaiorPredecessora = {};
        o.editMode = false;
        o.urls = {
            cadastrar: '/projeto/cronograma/cadastrar-atividade/format/json',
            editar: '/projeto/cronograma/editar-atividade/format/json',
            excluir: '/projeto/cronograma/excluir-atividade/format/json',
            atualizarBaselineAtiv: '/projeto/cronograma/atualizar-baseline-atividade/format/json'
        };
        o.url_form = null;

        o.initDialogs = function () {
            o.$dialogAtividade = $('#dialog-atividade').dialog({
                autoOpen: false,
                title: 'Cronograma - Cadastrar Atividade',
                width: '1100px',
                modal: true,
                close: function (event, ui) {
                    $('#dialog-atividade').parent().find("button").each(function () {
                        $(this).attr('disabled', false);
                    });
                    if (vAtualizar) {
                        //CRONOGRAMA.retornaProjeto();
                        vAtualizar = false;
                    }
                    o.predecAtividadeNova = {};
                },
                open: function (event, ui) {
                    o.predecAtividadeNova = {};
                    vSalvar = true;
                    $('#dialog-atividade').parent().find("button").each(function () {
                        $(this).attr('disabled', false);
                    });
                },
                buttons: {
                    'Salvar': function (event) {
                        event.preventDefault();
                        if (vSalvar) {
                            if ($(o.formAtividade).valid()) {
                                if (o.editMode) {
                                    $("#numdiasrealizados").removeAttr('disabled');
                                    $("#numdiasrealizados").attr('readonly', false);
                                    $("#datinicio").removeAttr('disabled');
                                    $("#datinicio").attr('readonly', false);
                                    $("#datfim").removeAttr('disabled');
                                    $("#datfim").attr('readonly', false);
                                } else {
                                    $("#datiniciobaseline").removeAttr('disabled');
                                    $("#datiniciobaseline").attr('readonly', false);
                                    $("#datfimbaseline").removeAttr('disabled');
                                    $("#datfimbaseline").attr('readonly', false);
                                    $("#numdiasbaseline").removeAttr('disabled');
                                    $("#numdiasbaseline").attr('readonly', false);
                                    $("#datinicio").removeAttr('disabled');
                                    $("#datinicio").attr('readonly', false);
                                    $("#datfim").removeAttr('disabled');
                                    $("#datfim").attr('readonly', false);
                                }
                                vSalvar = false;
                                $('#dialog-atividade').parent().find("button").each(function () {
                                    $(this).attr('disabled', true);
                                });
                                var predecSelecao = "";
                                var predecSelecao = "";
                                $(o.selectPredecAtividade).find('option').each(function () {
                                    if ($.isNumeric($(this).val())) {
                                        if (predecSelecao == "") {
                                            predecSelecao = $(this).val();
                                        } else {
                                            predecSelecao = predecSelecao + ';' + $(this).val();
                                        }
                                    }
                                });
                                $('#listaPredecessoras').val(predecSelecao);
                                $(o.formAtividade).submit();
                                vAtualizar = false;
                                setTimeout(function () {
                                    vSalvar = true;
                                    $('#dialog-atividade').parent().find("button").each(function () {
                                        $(this).attr('disabled', false);
                                        //
                                    });
                                }, 300);

                                $(document).ajaxComplete(function (event, xhr) {
                                    if (JSON.parse(xhr.responseText).success) {
                                        window.location.href = window.location.href;
                                    }
                                });
                            }

                        }
                    },
                    'Fechar': function () {
                        vSalvar = true;
                        $('#dialog-atividade').parent().find("button").each(function () {
                            $(this).attr('disabled', false);
                        });
                        $(this).dialog('close');
                    }
                }
            }).css("maxHeight", window.innerHeight - 150);

            o.$dialogAtualizarBaselineAtiv = $('#dialog-atualizar-baseline-ativ').dialog({
                autoOpen: false,
                title: 'Cronograma - Atualizar Base Line Atividade',
                width: '800px',
                modal: true,
                close: function (event, ui) {
                    $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                        $(this).attr('disabled', false);
                    });
                },
                open: function (event, ui) {
                    vSalvar = true;
                    $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                        $(this).attr('disabled', false);
                    });
                },
                buttons: {
                    'Confirmar': function (event) {
                        event.preventDefault();
                        if (vSalvar) {
                            vSalvar = false;
                            $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            var param = $(o.formAtualizarBaselineAtiv).serialize();
                            setTimeout(function () {
                                vSalvar = true;
                                $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }, 300);

                            var vUrlAtualizarBaseline = base_url + o.urls.atualizarBaselineAtiv;

                            $.ajax({
                                url: vUrlAtualizarBaseline,
                                dataType: 'json',
                                type: 'POST',
                                data: param,
                                success: function (data) {
                                    $.pnotify({
                                        text: data.msg.text,
                                        type: data.msg.type,
                                        hide: true
                                    });
                                    window.location.href = window.location.href;
                                    //return;
                                },
                                error: function () {
                                    $.pnotify({
                                        text: msgerroacesso,
                                        type: 'error',
                                        hide: false
                                    });
                                }
                            });
                        }
                        $(this).dialog('close');
                    },
                    'Fechar': function () {
                        $(this).dialog('close');
                    }
                }
            }).css("maxHeight", window.innerHeight - 150);
        };

        o.listapartesinteressadas = function (a) {
            idprojeto = $('#idprojeto').val();
            $.ajax({
                url: base_url + '/projeto/tpa/grid-tpa/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'idprojeto': idprojeto,
                    'sidx': 1,
                    'sord': 'asc',
                    'nopaginator': '1',
                },
                success: function (data) {
                    $("#idparteinteressada").empty();
                    $("#idparteinteressada").append($("<option />").val("").text("Selecione"));
                    $.each(data, function (key, value) {
                        $("#idparteinteressada").append($("<option />").val(value['idparteinteressada']).text(value['nomparteinteressada']));
                    });
                },
                error: function () {
                    $.pnotify({
                        text: msgerroacesso,
                        type: 'error',
                        hide: false
                    });
                }
            });
        };

        o.carregaArrayPredecessoras = function () {
            var at = {},
                iniPos = 0,
                fimPos = 0;
            at.idatividadecronograma = "";
            at.textatividadecronograma = "";
            at.dtfimatividadecronograma = "";
            at.dtamericaatividade = "";
            var tamOptions = $('select#predecessorasAtividade > option').length;

            if (tamOptions > 0) {
                $('select#predecessorasAtividade').find('option').each(function () {
                    if ($.isNumeric($(this).val())) {
                        at.idatividadecronograma = $(this).val();
                        at.textatividadecronograma = $(this).text();
                        fimPos = at.textatividadecronograma.indexOf(" -- ", 0);
                        if (fimPos != -1) {
                            iniPos = fimPos - 10;
                        }
                        if ((iniPos != -1) && (fimPos != -1)) {
                            at.dtfimatividadecronograma = at.textatividadecronograma.substring(iniPos, fimPos);
                            var splitdatapre = at.dtfimatividadecronograma.split('/');
                            at.dtamericaatividade = splitdatapre[2] + '-' + splitdatapre[1] + '-' + splitdatapre[0];
                            at.dtamericaatividade = new Date(at.dtamericaatividade);
                            at.dtamericaatividade.setDate(at.dtamericaatividade.getDate() + 1);
                        }
                        itemAtualAtv = {
                            'idatividadecronograma': at.idatividadecronograma,
                            'textatividadecronograma': at.textatividadecronograma,
                            'dtfimatividadecronograma': at.dtfimatividadecronograma,
                            'dtamericaatividade': at.dtamericaatividade
                        };
                        if (typeof at.idatividadecronograma !== 'undefined') {
                            if (at.idatividadecronograma > 0) {
                                if (typeof o.predecAtividadeNova[at.idatividadecronograma] === 'undefined') {
                                    o.predecAtividadeNova[at.idatividadecronograma] = itemAtualAtv;
                                }
                            }
                        }
                        iniPos = 0;
                        fimPos = 0;
                        at.idatividadecronograma = "";
                        at.textatividadecronograma = "";
                        at.dtfimatividadecronograma = "";
                        at.dtamericaatividade = "";
                    }
                });
            } else {
                o.predecAtividadeNova = {};
            }
        };

        o.calendarios = function () {
        };

        o.habilitarFolgas = function () {
            disabled = true;
            var length = $('select#predecessorasAtividade > option').length;

            if (length > 0) {
                disabled = false;
                $("#numfolga").attr('disabled', disabled);
                //$("#numfolga").attr({readonly: "false"});
            } else {
                $("#numfolga").val(0);
                disabled = true;
                $("#numfolga").attr('disabled', disabled);
                //$("#numfolga").attr({readonly: "true"});
            }
        };

        o.trataDominioTipoAtividade = function () {
            var folgas = $("#numfolga").val();
            var valorDominio = $('select#domtipoatividade option:selected').val();
            var valorDominio2 = $('#domtipoatividade').val();

            if ((valorDominio == "4") || (valorDominio2 == "4")) {
                valorDominio = "4";
            } else {
                valorDominio = "3";
            }

            if (valorDominio == "4") {
                $("#infoMarco").css("display", "");
                var maiorValor = $("#maior_valor").val();
                maiorValor = maiorValor.trim();
                var novaDtata = $("#maior_valor").val();

                if (verificaData($("#maior_valor").val())) {
                    var dataFinalPredec = "";
                    dataFinalPredec = montaData(novaDtata);
                    for (x = 0; x <= 15; x++) {
                        chkData = CRONOGRAMA.nonUtilDates(dataFinalPredec);
                        if (!(chkData)) {
                            break;
                        } else {
                            novaDtata = (new Date(
                                    dataFinalPredec.getFullYear(),
                                    dataFinalPredec.getMonth(),
                                    dataFinalPredec.getDate() + 1)
                            ).toString("dd/MM/yyyy");
                            dataFinalPredec = montaData(novaDtata);
                        }
                    }
                    if (folgas >= 0) {
                        var parFolga = 0;
                        parFolga = (folgas * 1) + 1;

                        if (o.editMode) {
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datinicio,#datfim", "", "");
                            $("#numdiasrealizados").val(0);
                        } else {
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datinicio,#datfim,#datiniciobaseline,#datfimbaseline", "", "");
                            $("#numdiasbaseline").val(0);
                        }
                    } else {
                        var parFolga = (folgas * (-1));
                        if (o.editMode) {
                            var diasRealizados = $("#numdiasrealizados").val();
                            if (valorDominio == "3") {
                                o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datinicio");
                                $("#numdiasrealizados").val(1);
                            } else {
                                o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datinicio,#datfim");
                                $("#numdiasrealizados").val(0);
                            }
                        } else {
                            o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datiniciobaseline,#datfimbaseline,#datinicio,#datfim");
                            if (valorDominio == "3") {
                                $("#numdiasbaseline").val(1);
                            } else {
                                $("#numdiasbaseline").val(0);
                            }
                        }
                    }
                    if (o.editMode) {
                        $("#datinicio").attr({disabled: "disabled", readonly: "true"});
                        $("#datfim").attr({disabled: "disabled", readonly: "true"});
                        $("#numdiasrealizados").attr({disabled: "disabled", readonly: "true"});
                    } else {
                        $("#datiniciobaseline").attr({disabled: "disabled", readonly: "true"});
                        $("#datfimbaseline").attr({disabled: "disabled", readonly: "true"});
                        $("#numdiasbaseline").attr({disabled: "disabled", readonly: "true"});
                        $("#datinicio").attr({disabled: "disabled", readonly: "true"});
                        $("#datfim").attr({disabled: "disabled", readonly: "true"});
                    }
                } else {
                    if (o.editMode) {
                        if (verificaData($("#datinicio").val())) {
                            $("#datfim").val($("#datinicio").val());
                        }
                        $("#datinicio").datepicker("option", "maxDate", null);
                        $("#datfim").datepicker("option", "minDate", null);
                        $("#numdiasrealizados").val(0);
                        $("#datinicio").removeAttr('disabled');
                        $("#datinicio").attr('readonly', false);
                        $("#numdiasrealizados").attr({disabled: "disabled", readonly: "true"});
                        $("#datfim").attr({disabled: "disabled", readonly: "true"});
                    } else {
                        if (verificaData($("#datiniciobaseline").val())) {
                            $("#datfimbaseline").val($("#datiniciobaseline").val());
                            $("#datfim").val($("#datiniciobaseline").val());
                            $("#datinicio").val($("#datiniciobaseline").val());
                        }
                        $("#datiniciobaseline").datepicker("option", "maxDate", null);
                        $("#datfimbaseline").datepicker("option", "minDate", null);
                        $("#datinicio").datepicker("option", "maxDate", null);
                        $("#datfim").datepicker("option", "minDate", null);
                        $("#numdiasbaseline").val(0);
                        $("#datiniciobaseline").removeAttr('disabled');
                        $("#datiniciobaseline").attr('readonly', false);
                        $("#numdiasbaseline").attr({disabled: "disabled", readonly: "true"});
                        $("#datfimbaseline").attr({disabled: "disabled", readonly: "true"});
                        $("#datinicio").attr({disabled: "disabled", readonly: "true"});
                        $("#datfim").attr({disabled: "disabled", readonly: "true"});
                    }
                }
            } else {
                $("#infoMarco").css("display", "none");
                var maiorValor = $("#maior_valor").val();
                // var maiorValor = maiorValor.trim();
                var novaDtata = $("#maior_valor").val();
                if (verificaData($("#maior_valor").val())) {
                    var dataFinalPredec = "";
                    dataFinalPredec = montaData(novaDtata);
                    for (x = 0; x <= 15; x++) {
                        chkData = CRONOGRAMA.nonUtilDates(dataFinalPredec);
                        if (!(chkData)) {
                            break;
                        } else {
                            novaDtata = (new Date(
                                    dataFinalPredec.getFullYear(),
                                    dataFinalPredec.getMonth(),
                                    dataFinalPredec.getDate() + 1)
                            ).toString("dd/MM/yyyy");
                            dataFinalPredec = montaData(novaDtata);
                        }
                    }

                    if (folgas >= 0) {
                        var parFolga = (folgas * 1) + 2;

                        if (o.editMode) {
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datinicio,#datfim", "", "");
                        } else {
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datinicio,#datfim,#datiniciobaseline,#datfimbaseline", "", "");
                        }
                    } else {
                        var parFolga = (folgas * (-1));

                        if (o.editMode) {
                            var diasRealizados = $("#numdiasrealizados").val();
                            if (valorDominio == "3") {
                                o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datinicio");
                                $("#numdiasrealizados").val(1);
                            } else {
                                o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datinicio,#datfim");
                                $("#numdiasrealizados").val(0);
                            }
                        } else {
                            o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datiniciobaseline,#datfimbaseline,#datinicio,#datfim");
                            if (valorDominio == "3") {
                                $("#numdiasbaseline").val(1);
                            } else {
                                $("#numdiasbaseline").val(0);
                            }
                        }
                    }
                    if (o.editMode) {
                        $("#datinicio").datepicker("option", "maxDate", null);
                        $("#datfim").datepicker("option", "minDate", $("#datinicio").val());
                        $("#datinicio").attr({disabled: "disabled", readonly: "true"});
                        $("#numdiasrealizados").removeAttr('disabled');
                        $("#numdiasrealizados").attr('readonly', false);
                        $("#datfim").removeAttr('disabled');
                        $("#datfim").attr('readonly', false);
                        $("#numdiasrealizados").val(1);
                    } else {

                        $("#datinicio").datepicker("option", "maxDate", null);
                        $("#datfim").datepicker("option", "minDate", $("#datinicio").val());
                        $("#datiniciobaseline").datepicker("option", "maxDate", null);
                        $("#datfimbaseline").datepicker("option", "minDate", $("#datiniciobaseline").val());
                        $("#datiniciobaseline").attr({disabled: "disabled", readonly: "true"});
                        $("#datinicio").attr({disabled: "disabled", readonly: "true"});
                        $("#datfimbaseline").removeAttr('disabled');
                        $("#datfimbaseline").attr('readonly', false);
                        $("#datfim").removeAttr('disabled');
                        $("#datfim").attr('readonly', false);
                        $("#numdiasbaseline").removeAttr('disabled');
                        $("#numdiasbaseline").attr('readonly', false);
                        $("#numdiasbaseline").val(1);
                    }
                } else {
                    if (o.editMode) {
                        if (verificaData($("#datinicio").val())) {
                            $("#datfim").val($("#datinicio").val());
                        }
                        $("#datinicio").removeAttr('disabled');
                        $("#datinicio").attr('readonly', false);
                        $("#datfim").removeAttr('disabled');
                        $("#datfim").attr('readonly', false);
                        $("#numdiasrealizados").removeAttr('disabled');
                        $("#numdiasrealizados").attr('readonly', false);
                        $("#numdiasrealizados").val(1);
                        if (verificaData($("#datfim").val())) {
                            if (o.editMode) {
                                var FimI = $('#datfim').val();
                                $("#datinicio").datepicker("option", "maxDate", FimI);
                            }
                        } else {
                            $("#datinicio").datepicker("option", "maxDate", null);
                        }
                    } else {
                        if (verificaData($("#datiniciobaseline").val())) {
                            $("#datfimbaseline").val($("#datiniciobaseline").val());
                            $("#datinicio").val($("#datiniciobaseline").val());
                            $("#datfim").val($("#datiniciobaseline").val());
                        }
                        $("#datfimbaseline").removeAttr('disabled');
                        $("#datfimbaseline").attr('readonly', false);
                        $("#numdiasbaseline").removeAttr('disabled');
                        $("#numdiasbaseline").attr('readonly', false);
                        $("#datinicio").removeAttr('disabled');
                        $("#datinicio").attr('readonly', false);
                        $("#datfim").removeAttr('disabled');
                        $("#datfim").attr('readonly', false);
                        $("#numdiasbaseline").val(1);
                        $("#datiniciobaseline").datepicker("option", "maxDate", null);
                    }
                }
            }
            CRONOGRAMA.carregaSelectPercentualconcluido(valorDominio, $(o.selectPercentualconcluido));
        };

        o.trataDatasAtividadeEdicao = function (input) {
            var valorDominio = parseInt($('#domtipoatividade option:selected').val());
            var valorDominio1 = parseInt($("#ac-atividade").find('select[id="domtipoatividade"]').val());
            var valorDominio2 = parseInt($('#domtipoatividade').val());

            if ((valorDominio == 4) || (valorDominio1 == 4) || (valorDominio2 == 4)) {
                valorDominio = 4;
            } else {
                valorDominio = 3;
            }
            var nameObj = input.attr("name");

            if ((nameObj == 'datinicio') || (nameObj == 'datfim')) {

                if (valorDominio == 4) {

                    if (verificaData($("#datinicio").val())) {
                        var inicio = $('#datinicio').val();
                        $("#datfim").val(inicio);
                        $("#numdiasrealizados").val(0);
                    } else {
                        $("#datfim").val('');
                        $("#numdiasrealizados").val('');
                    }
                    $("#datinicio").datepicker("option", "maxDate", null);

                } else {
                    if ((verificaData($("#datinicio").val())) && (verificaData($("#datfim").val()))) {
                        var iniciob = montaData($("#datinicio").val());
                        var fimb = montaData($("#datfim").val());

                        if (fimb < iniciob) {
                            $("#datfim").val($("#datinicio").val());
                        }
                        var days = calculaDiasEntreDatas($("#datinicio").val(), $("#datfim").val());
                        days++;
                        if (days < 0) {
                            if (nameObj == 'datfim') {
                                $("#numdiasrealizados").val(1);
                            }
                        } else {
                            o.buscaNumDiasEntreDatas("#datinicio", "#datfim", "#numdiasrealizados");
                        }
                    }
                    if (nameObj == 'datinicio') {
                        if (verificaData($("#datinicio").val())) {
                            var inicio = $('#datinicio').val();
                            $("#datfim").datepicker("option", "minDate", inicio);
                        } else {
                            $("#datfim").datepicker("option", "minDate", null);
                        }
                    } else {
                        if (verificaData($("#datfim").val())) {
                            if (o.editMode) {
                                var fim = $('#datfim').val();
                                $("#datinicio").datepicker("option", "maxDate", fim);
                            }
                        } else {
                            $("#datinicio").datepicker("option", "maxDate", null);
                        }
                    }
                }
            }
            if ((nameObj == 'datiniciobaseline') || (nameObj == 'datfimbaseline')) {
                var obj = $('#' + nameObj);
                var input = obj.data('input');
                $(input).val(obj.val());
                if (valorDominio == 4) {
                    if (verificaData($("#datiniciobaseline").val())) {
                        $("#datfimbaseline").val($("#datiniciobaseline").val());
                        $("#datinicio").val($("#datiniciobaseline").val());
                        $("#datfim").val($("#datiniciobaseline").val());
                        $("#numdiasbaseline").val(0);
                    } else {
                        $("#datfimbaseline").val('');
                        $("#datinicio").val('');
                        $("#datfim").val('');
                        $("#numdiasbaseline").val('');
                    }
                    $("#datiniciobaseline").datepicker("option", "maxDate", null);
                } else {
                    if ((verificaData($("#datiniciobaseline").val())) && (verificaData($("#datfimbaseline").val()))) {
                        var iniciobaseline = $("#datiniciobaseline").datepicker("getDate");
                        var fimbaseline = $("#datfimbaseline").datepicker("getDate");
                        if (fimbaseline < iniciobaseline) {
                            $("#datfimbaseline").val($("#datiniciobaseline").val());
                            input = $("#datfimbaseline").data('input');
                            $(input).val($("#datfimbaseline").val());
                            input = $("datiniciobaseline").data('input');
                            $(input).val($("datiniciobaseline").val());
                        }
                        var days = calculaDiasEntreDatas($("#datiniciobaseline").val(), $("#datfimbaseline").val());
                        days++;
                        if (days < 0) {
                            if (nameObj == 'datfimbaseline') {
                                $("#numdiasbaseline").val(1);
                            }
                        } else {
                            o.buscaNumDiasEntreDatas("#datiniciobaseline", "#datfimbaseline", "#numdiasbaseline");
                        }
                    }
                }
            }

        };

        o.setarDataInicio = function () {
            var t = $(o.selectPredecAtividade),
                tam = $(o.selectPredecAtividade + ' > option').length;
            var
                idprojeto = $('#idprojeto').val(),
                domtipoatividade = $('#domtipoatividade').val(),
                idatividadecronograma = $('#idatividadecronograma').val();
            if (tam <= 0) {
                disabled = true;
                $("#numfolga").attr('disabled', disabled);
                o.habilitarDataInicio();
                return;
            } else {
                disabled = false;
                $("#numfolga").attr('disabled', disabled);
            }
            if ($.isNumeric(idatividadecronograma)) {
                if (idatividadecronograma > 0) {
                    $.ajax({
                        url: base_url + '/projeto/cronograma/retorna-inicio-base-line',
                        dataType: 'json',
                        type: 'POST',
                        async: true,
                        cache: true,
                        data: {
                            idprojeto: idprojeto,
                            idatividadecronograma: idatividadecronograma,
                            domtipoatividade: domtipoatividade
                        },
                        //processData: false,
                        success: function (data) {
                            var maior_data = data.maiordatapredecessora;
                            var dtinicio_baseline = data.datainiciobaseline;
                            maior_data = maior_data.trim();
                            dtinicio_baseline = dtinicio_baseline.trim();
                            var dataInicio = null;
                            if ((dtinicio_baseline != null) && (dtinicio_baseline != "")) {
                                o.habilitarDataInicio();
                                $("#maior_valor").attr('value', maior_data);
                                $("#datinicio").attr('value', maior_data);
                                o.calcularReal($("#maior_valor"), 'inicio');
                                if ($("#datinicio").val() != (dtinicio_baseline)) {
                                    $("#datinicio").val(dtinicio_baseline);
                                    o.atualizadaDataFim();
                                } else {
                                    o.calcularReal($("#datinicio"), 'inicio');
                                    o.atualizadaDataFim();
                                }
                                var dados = $(o.itemCronogramaSelecionado).data('dados');
                                var folga = dados.numfolga;
                                $("#numfolga").val(folga);
                                dataInicio = $("#datinicio").val();
                                $('#datInicioHidden').removeAttr('value');
                                $('#datInicioHidden').attr('value', dataInicio);
                                $("#datInicioHidden").attr('value', $("#datinicio").val());
                                o.habilitarFolgas();
                                o.desabilitarDataInicio();
                            } else {
                                o.habilitarDataInicio();
                                o.habilitarFolgas();
                                $("#maior_valor").removeAttr('value');
                            }
                        },
                        error: function () {
                            $.pnotify({
                                text: msgerror,
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                }
            }
        };

        o.buscaDataFimPorDias = function (dtainicio, numdias, dtdestino, numdiasrealizados, dtfim) {
            //var datinicio = $(dtainicio).val();
            var datinicio = dtainicio;
            var qtdedias = numdias;
            var datafim = "";

            if (verificaData(datinicio)) {
                if ($.isNumeric(qtdedias)) {
                    if (qtdedias > 0) {
                        $.ajax({
                            url: base_url + '/projeto/cronograma/retorna-data-fim-por-dias',
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                datainicio: datinicio,
                                numdias: qtdedias
                            },
                            success: function (data) {
                                datafim = data.datafim;
                                var campos = dtdestino.split(',');
                                $.each(campos, function (i, nomeCampo) {
                                    //if (false == $(dtdestino).is('[readonly]')) {
                                    if (false == $(nomeCampo).is('[readonly]')) {
                                        $(nomeCampo).val(datafim);
                                        $(nomeCampo).datepicker('setDate', datafim);
                                    } else {
                                        o.habilitarItem($(nomeCampo));
                                        //o.habilitarItem($(dtdestino));
                                        $(nomeCampo).val(datafim);
                                        $(nomeCampo).datepicker('setDate', datafim);
                                        //$(dtdestino).val(data.datafim);
                                        //$("#datinicio").val(datafim);
                                        o.desabilitarItem($(nomeCampo));
                                        //o.desabilitarItem($(dtdestino));
                                    }
                                });

                                if (numdiasrealizados > 0) {
                                    var cdestinos = dtfim.split(',');
                                    $.each(cdestinos, function (j, finalCampo) {
                                        o.buscaDataFimPorDias(datafim, numdiasrealizados, finalCampo, "", "");
                                    });
                                }
                            },
                            error: function () {
                            }
                        });
                    } else {
                        $(dtdestino).val(dtainicio);
                        //$(dtdestino).val($(dtainicio).val());
                    }
                } else {
                    $(dtdestino).val('');
                    $(numdias).focus();
                }
            } else {
                $(dtainicio).val('')
            }
            return datafim;
        };

        o.buscaDataAnteriorPorDias = function (dtainicio, numdias, dtdestino) {
            //var datinicio = $(dtainicio).val();
            var datinicio = dtainicio;
            var qtdedias = numdias;
            var datafim = "";

            if (verificaData(datinicio)) {
                if ($.isNumeric(qtdedias)) {
                    if (qtdedias > 0) {
                        $.ajax({
                            url: base_url + '/projeto/cronograma/retorna-data-anterior-por-dias',
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                datainicio: datinicio,
                                numdias: qtdedias
                            },
                            success: function (data) {
                                datafim = data.datafim;
                                var campos = dtdestino.split(',');
                                $.each(campos, function (i, nomeCampo) {
                                    //if (false == $(dtdestino).is('[readonly]')) {
                                    if (false == $(nomeCampo).is('[readonly]')) {
                                        $(nomeCampo).val(datafim);
                                    } else {
                                        o.habilitarItem($(nomeCampo));
                                        $(nomeCampo).val(datafim);
                                        o.desabilitarItem($(nomeCampo));
                                    }
                                });
                            },
                            error: function () {
                            }
                        });
                    } else {
                        $(dtdestino).val(dtainicio);
                        //$(dtdestino).val($(dtainicio).val());
                    }
                } else {
                    $(dtdestino).val('');
                    $(numdias).focus();
                }
            } else {
                $(dtainicio).val('')
            }
        };

        o.buscaNumDiasEntreDatas = function (dtainicio, dtafim, numdestino) {
            var datinicio = $(dtainicio).val();
            var datfim = $(dtafim).val();
            vNumdias = "";
            if ((verificaData(datinicio)) && (verificaData(datfim))) {
                $.ajax({
                    url: base_url + '/projeto/cronograma/retorna-qtde-dias-uteis-entre-datas',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        datainicio: datinicio,
                        datafim: datfim
                    },
                    success: function (data) {
                        var numdias = data.numdias;
                        if (numdestino != "") {
                            if ($(numdestino).is('[readonly]')) {
                                $(numdestino).removeAttr('disabled');
                                //$(numdestino).attr('readonly', false);
                                $(numdestino).val(numdias);
                                //$(numdestino).attr({disabled: "disabled", readonly: "true"});
                            } else {
                                $(numdestino).val(numdias);
                            }
                        }
                    },
                    error: function () {
                    }
                });
            } else {
                $(numdestino).val("");
            }
        };

        o.setarDataInicioBaseline = function () {
            var t = $(o.selectPredecAtividade),
                tam = $(o.selectPredecAtividade + ' > option').length,
                valorDominio = $('#domtipoatividade option:selected').val(),
                valorDominio1 = $("#ac-atividade").find('select[id="domtipoatividade"]').val(),
                valorDominio2 = $('#domtipoatividade').val();
            if ((valorDominio == "4") || (valorDominio1 == "4") || (valorDominio2 == "4")) {
                valorDominio = "4";
            }
            if (tam <= 0) {
                o.habilitarItem($("#datinicio"));
                if (o.editMode === false) {
                    o.habilitarItem($("#datiniciobaseline"));
                    //o.habilitarItem($("#datinicio"));
                    if (valorDominio == "4") {
                        o.desabilitarItem($("#datfimbaseline"));
                        o.desabilitarItem($("#datfim"));
                        $("#numdiasbaseline").val(0);
                        o.desabilitarItem($("#numdiasbaseline"));
                    } else {
                        o.habilitarItem($("#datinicio"));
                        o.habilitarItem($("#datiniciobaseline"));
                        o.habilitarItem($("#datfimbaseline"));
                        o.habilitarItem($("#numdiasbaseline"));
                    }
                } else {
                    if (valorDominio == "4") {
                        o.desabilitarItem($("#datfim"));
                        $("#numdiasrealizados").val(0);
                        o.desabilitarItem($("#numdiasrealizados"));
                    } else {
                        o.habilitarItem($("#datinicio"));
                        o.habilitarItem($("#datfim"));
                        o.habilitarItem($("#numdiasrealizados"));
                    }
                }
                o.desabilitarItem($("#numfolga"));
                $("#maior_valor").attr('value', '');
                return;
            } else {
                o.desabilitarItem($("#datinicio"));
                if (o.editMode === false) {
                    o.desabilitarItem($("#datiniciobaseline"));
                }
                o.habilitarItem($("#numfolga"));
            }
        };

        o.existePredecessora = function () {
            var
                idprojeto = $('#idprojeto').val(),
                idatividadecronograma = $('#idatividadecronograma').val(),
                domtipoatividade = $('#domtipoatividade').val();


            if (idatividadecronograma.length > 0) {
                $.ajax({
                    url: base_url + '/projeto/cronograma/retorna-inicio-base-line',
                    dataType: 'json',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        idprojeto: idprojeto,
                        idatividadecronograma: idatividadecronograma,
                        domtipoatividade: domtipoatividade
                    },
                    //processData: false,
                    success: function (data) {
                        if (data.maiordatapredecessora != null) {
                            var maior_data = data.maiordatapredecessora;
                            var dtinicio_baseline = data.datainiciobaseline;
                            maior_data = maior_data.trim();
                            dtinicio_baseline = dtinicio_baseline.trim();
                            var dataInicio = null;
                            if ((dtinicio_baseline != null) && (dtinicio_baseline != "")) {
                                o.habilitarFolgas();
                                o.desabilitarDataInicio();
                            }
                            if ((maior_data == null) || (maior_data == "")) {
                                o.habilitarDataInicio();
                                $("#maior_valor").attr('value', '');
                            }
                        }
                    },
                    error: function () {
                        $.pnotify({
                            text: msgerror,
                            type: 'error',
                            hide: false
                        });
                    }
                });
            }
        };

        o.calcularReal = function (obj, valor) {
            o.calcularDias(obj, valor);
        }

        o.calcularBaseLine = function () {
            o.calculaDataInicio();
            o.atualizadaDataFim();
            //o.atualizadaDataFimBaseline();
        };

        o.calculaDataInicio = function () {

            var folgas = $("#numfolga").val();
            valorDominio = parseInt($('select#domtipoatividade option:selected').val()),
                valorDominio2 = parseInt($('#domtipoatividade').val()),
                MARCO = parseInt(4);

            console.log(valorDominio);
            console.log(valorDominio2);

            if ((valorDominio == MARCO) || (valorDominio2 == MARCO)) {
                valorDominio = 4;
            } else {
                valorDominio = 3;
            }

            var novaDtata = $("#maior_valor").val();
            var DtInicio = $("#datinicio").val();
            if (verificaData($("#maior_valor").val())) {
                var dataFinalPredec = "";
                var tamOptions = $('select#predecessorasAtividade > option').length;
                dataFinalPredec = montaData(novaDtata);
                for (x = 0; x <= 15; x++) {
                    chkData = CRONOGRAMA.nonUtilDates(dataFinalPredec);
                    if (!(chkData)) {
                        break;
                    } else {
                        novaDtata = (new Date(
                                dataFinalPredec.getFullYear(),
                                dataFinalPredec.getMonth(),
                                dataFinalPredec.getDate() + 1)
                        ).toString("dd/MM/yyyy");
                        dataFinalPredec = montaData(novaDtata);
                    }
                }
                if (folgas >= 0) {
                    var parFolga = 0;

                    if (o.editMode == true) {
                        var diasRealizados = $("#numdiasrealizados").val();
                        if (valorDominio == MARCO) {
                            parFolga = (folgas * 1) + 1;
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datinicio,#datfim", "", "");
                        } else {
                            parFolga = (folgas * 1) + 2;
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datinicio", diasRealizados, "#datfim");
                            o.desabilitarItem($("#datinicio"));
                        }

                    } else {
                        var numdiasbaseline = $("#numdiasbaseline").val();
                        if (valorDominio == MARCO) {
                            parFolga = (folgas * 1) + 1;
                            o.buscaDataFimPorDias(novaDtata, parFolga, "#datiniciobaseline,#datfimbaseline,#datinicio,#datfim", "", "");
                        } else {
                            parFolga = (folgas * 1) + 2;
                            if (numdiasbaseline > 0) {
                                o.buscaDataFimPorDias(novaDtata, parFolga, "#datiniciobaseline,#datinicio", numdiasbaseline, "#datfim,#datfimbaseline");
                            } else {
                                o.buscaDataFimPorDias(novaDtata, parFolga, "#datiniciobaseline,#datinicio", "", "");
                            }
                        }

                        $("#numdiasbaseline").focus();

                    }
                } else {
                    var parFolga = (folgas * (-1));

                    if (o.editMode == true) {
                        var diasRealizados = $("#numdiasrealizados").val();
                        if (valorDominio == 3)
                            o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datinicio");
                        else {
                            o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datinicio,#datfim");
                            $("#numdiasrealizados").val(0);
                        }
                        if (diasRealizados > 0) {
                            $("#numdiasrealizados").focus();
                        }
                    } else {
                        o.buscaDataAnteriorPorDias(novaDtata, parFolga, "#datiniciobaseline,#datinicio");
                        $("#numdiasbaseline").focus();
                    }
                }
            } else {
                o.habilitarFolgas();
                o.setaNumdiasMarco();
            }
        }

        o.calcularDias = function (obj, valor) {
            var periodo = valor,
                $numfolga = $("#numfolga"),
                $numReal = $("#numdiasrealizados"),
                numdiareal = 0,
                folgas = 0,
                parFolga = 0,
                dataNova = null,
                desabilitaItem = false,
                dataPredecessora = null;
            var valorDominio = $('select#domtipoatividade option:selected').val();
            var valorDominio1 = $("#ac-atividade").find('select[id="domtipoatividade"]').val();
            var valorDominio2 = $('#domtipoatividade').val();

            if (!($numfolga.is('[readonly]'))) {
                folgas = $numfolga.val();
            }

            if ((valorDominio == "4") || (valorDominio1 == "4") || (valorDominio2 == "4")) {
                valorDominio = "4";
                if (folgas >= 0) {
                    parFolga = (folgas * 1) + 1;
                } else {
                    parFolga = (folgas * (-1));
                }
            } else {
                valorDominio = "3";
                if (folgas >= 0) {
                    parFolga = (folgas * 1) + 2;
                } else {
                    parFolga = (folgas * (-1));
                }
            }
            numdiareal = $numReal.val();

            if (true == $(obj).is('[readonly]')) {
                o.habilitarItem($(obj));
                desabilitaItem = true;
            }

            var maiorValor = $("#maior_valor").val();

            if (obj.attr('id') == 'datiniciobaseline' || obj.attr('id') == 'datfimbaseline') {
                if (periodo == 'inicio') {
                    if (o.editMode === false) {
                        dataPredecessora = $("#maior_valor").val();
                    } else {
                        dataPredecessora = $("#maior_valor").val();
                        if ((!(null == dataPredecessora)) && (dataPredecessora != "")) {
                            dataPredecessora = $("#maior_valor").val();
                        }
                    }
                    if ((!(null == dataPredecessora)) && (dataPredecessora != "")) {
                        var maiorValor = $("#maior_valor").val();
                        var maiorValor = maiorValor.trim();
                        if (maiorValor != "") {
                            dataPredecessora = $("#maior_valor").val();
                        }
                    }
                    if ((!(null == dataPredecessora)) && (dataPredecessora != "")) {
                        if (verificaData(dataPredecessora)) {
                            var dataFinalRealizado = montaData(dataPredecessora);
                            for (x = 0; x <= 15; x++) {
                                chkData = CRONOGRAMA.nonUtilDates(dataFinalRealizado);
                                if (!(chkData)) {
                                    break;
                                } else {
                                    dataPredecessora = (new Date(
                                            dataFinalRealizado.getFullYear(),
                                            dataFinalRealizado.getMonth(),
                                            dataFinalRealizado.getDate() + 1)
                                    ).toString("dd/MM/yyyy");
                                    dataFinalRealizado = montaData(dataPredecessora);
                                }
                            }
                            if (parseInt(parFolga) >= 0) {
                                //o.buscaDataFimPorDias(dataPredecessora, parFolga, "#" + obj.attr('id') );
                            }
                        }
                    }
                }
            }

            if (obj.attr('id') == 'datinicio') {
                if (periodo == 'inicio') {
                    if (o.editMode === false) {
                        dataPredecessora = $("#maior_valor").val();
                    } else {
                        dataPredecessora = $("#maior_valor").val();
                        //dataPredecessora = obj.val();
                        if ((!(null == dataPredecessora)) && (dataPredecessora != "")) {
                            dataPredecessora = $("#maior_valor").val();
                        }
                    }
                    if ((!(null == dataPredecessora)) && (dataPredecessora != "")) {
                        var maiorValor = $("#maior_valor").val();
                        var maiorValor = maiorValor.trim();
                        if (maiorValor != "") {
                            dataPredecessora = $("#maior_valor").val();
                        }
                    }
                    if ((!(null == dataPredecessora)) && (dataPredecessora != "")) {
                        if (verificaData(dataPredecessora)) {
                            var dataFinalRealizado = montaData(dataPredecessora);
                            for (x = 0; x <= 15; x++) {
                                chkData = CRONOGRAMA.nonUtilDates(dataFinalRealizado);
                                if (!(chkData)) {
                                    break;
                                } else {

                                    dataPredecessora = (new Date(
                                            dataFinalRealizado.getFullYear(),
                                            dataFinalRealizado.getMonth(),
                                            dataFinalRealizado.getDate() + 1)
                                    ).toString("dd/MM/yyyy");

                                    dataFinalRealizado = montaData(dataPredecessora);
                                }
                            }
                            if (parseInt(parFolga) >= 0) {
                                //if (o.editMode === false) {
                                //    o.buscaDataFimPorDias(dataPredecessora, parFolga, "#datiniciobaseline,#datinicio", "", "");
                                //}else{
                                //    o.buscaDataFimPorDias(dataPredecessora, parFolga, "#datinicio", "", "");
                                //}
                            }
                        }
                    }
                }
            }
            if (false == $numfolga.is('[readonly]')) {
                folgas = $numfolga.val();
            }
            o.setaNumdiasMarco();
            if (desabilitaItem) {
                o.desabilitarItem($(obj));
                desabilitaItem = false;
            }
        }

        o.adicionarPredecessora = function (event, select) {
            event.preventDefault();
            var a = {},
                at = {},
                iniPos = 0,
                fimPos = 0;
            seletedIt = o.selectPredecessora,
                t = $(o.tablePredecessora);
            at.idatividadecronograma = "";
            at.textatividadecronograma = "";
            at.dtfimatividadecronograma = "";
            at.dtamericaatividade = "";
            vAtualizar = true;
            a.idatividadecronograma = $(seletedIt).find('option:selected').val();
            a.idprojeto = $("#idprojeto").val();
            a.idatividaselecionada = $(o.itemCronogramaSelecionado).val();
            if (a.idatividadecronograma !== '') {
                $.ajax({
                    url: base_url + '/projeto/cronograma/valida-predecessora',
                    dataType: 'json',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        idprojeto: a.idprojeto,
                        idatividadepredecessora: a.idatividadecronograma,
                        idatividadecronograma: a.idatividaselecionada
                    },
                    success: function (data) {
                        /*******************************************************/
                        if (!(data.valida)) {
                            $.pnotify({
                                text: data.msg.text,
                                type: 'error',
                                hide: true
                            });
                        } else {
                            //if (o.editMode === true) {
                            o.carregaArrayPredecessoras();
                            //}
                            at.idatividadecronograma = select.find('option:selected').val();
                            at.textatividadecronograma = select.find('option:selected').text();
                            fimPos = at.textatividadecronograma.indexOf(" -- ", 0);
                            if (fimPos != -1) {
                                iniPos = fimPos - 10;
                            }
                            if ((iniPos != -1) && (fimPos != -1)) {
                                at.dtfimatividadecronograma = at.textatividadecronograma.substring(iniPos, fimPos);
                                var splitdatapre = at.dtfimatividadecronograma.split('/');
                                at.dtamericaatividade = splitdatapre[2] + '-' + splitdatapre[1] + '-' + splitdatapre[0];
                                at.dtamericaatividade = new Date(at.dtamericaatividade);
                                at.dtamericaatividade.setDate(at.dtamericaatividade.getDate() + 1);
                            }
                            itemAtualAtv = {
                                'idatividadecronograma': at.idatividadecronograma,
                                'textatividadecronograma': at.textatividadecronograma,
                                'dtfimatividadecronograma': at.dtfimatividadecronograma,
                                'dtamericaatividade': at.dtamericaatividade
                            };
                            if (typeof at.idatividadecronograma !== 'undefined') {
                                if (at.idatividadecronograma > 0) {
                                    if (typeof o.predecAtividadeNova[at.idatividadecronograma] === 'undefined') {
                                        o.predecAtividadeNova[at.idatividadecronograma] = itemAtualAtv;
                                        o.listaPredecessorasAtividade();
                                    }
                                }
                            }
                            o.calcularBaseLine();
                            o.setarDataInicioBaseline();
                            o.atualizarCaptionPredecessoras();
                            /*******************************************************/
                        }
                    },
                    error: function () {
                        $.pnotify({
                            text: msgerroacesso,
                            type: 'error',
                            hide: false
                        });
                    }

                });
            }
        };

        o.desabilitarDataInicio = function () {
            $("#datinicio").attr('readonly', true);
            $("#datinicio").attr('disabled', 'disabled');
        }

        o.habilitarDataInicio = function () {
            $("#datinicio").removeAttr('disabled');
            $("#datinicio").removeAttr('readonly');
        }

        o.desabilitarItem = function (objItem) {
            objItem.attr('readonly', true);
            objItem.attr('disabled', 'disabled');
        }

        o.habilitarItem = function (objItem) {
            objItem.removeAttr('disabled');
            objItem.removeAttr('readonly');
        }

        o.atualizarCaptionPredecessoras = function () {
            var tam = $(o.selectPredecAtividade + ' > option').length;
            if (tam > 0) {
                o.habilitarFolgas();
            }
        };

        o.carregaPredecessoras = function (listaPredecessoras) {
            var count = 0;
            $(o.selectPredecAtividade).empty();
            $.each(listaPredecessoras, function () {
                if (this.data != "") {
                    $(o.selectPredecAtividade).append($('<option>').text(this.data).attr('value', this.idatividadepredecessora));
                    count++;
                }
            });
            o.habilitarFolgas();
        };

        o.listaPredecessorasAtividade = function () {
            var count = 0;
            var newAtiviArray = {};
            var dataMaiorLista = "";
            $(o.selectPredecAtividade).empty();
            $.each(o.predecAtividadeNova, function () {
                if ((this.textatividadecronograma != "") && (this.idatividadecronograma != "") && (this.dtfimatividadecronograma != "")) {
                    if (dataMaiorLista == "") {
                        dataMaiorLista = this.dtamericaatividade;
                    } else {
                        if (this.dtamericaatividade > dataMaiorLista) {
                            dataMaiorLista = this.dtamericaatividade;
                        }
                    }
                    $(o.selectPredecAtividade).append($('<option>').text(this.textatividadecronograma).attr('value', this.idatividadecronograma));
                    var itemAtualAtv = {
                        'idatividadecronograma': this.idatividadecronograma,
                        'textatividadecronograma': this.textatividadecronograma,
                        'dtfimatividadecronograma': this.dtfimatividadecronograma,
                        'dtamericaatividade': this.dtamericaatividade
                    };
                    newAtiviArray[this.idatividadecronograma] = itemAtualAtv;
                    count++;
                }
            });

            tam = $(o.selectPredecAtividade + ' > option').length;

            if (tam > 0) {
                $("#maior_valor").val(
                    (
                        new Date(
                            dataMaiorLista.getFullYear(),
                            dataMaiorLista.getMonth(),
                            dataMaiorLista.getDate()
                        )
                    ).toString("dd/MM/yyyy")
                );
                o.habilitarItem($("#numfolga"));
                //o.habilitarFolgas();
            } else {
                $("#maior_valor").val("");
                o.habilitarItem($("#datinicio"));
                o.habilitarItem($("#datiniciobaseline"));
                o.desabilitarItem($("#numfolga"));
            }

            o.predecAtividadeNova = {};
            if (verificaData($("#maior_valor").val())) {
                o.predecAtividadeNova = newAtiviArray;

                if (!(o.editMode === true)) {
                    o.calcularBaseLine();
                } else {
                    o.calculaDataInicio();
                }
            }
        };

        o.carregaPercentualconcluido = function (valorDominio) {
            var soma = (valorDominio == 4 ? 100 : 10), count = 0;
            $(o.selectPercentualconcluido).empty();
            while (count <= 100) {
                $(o.selectPercentualconcluido).append($('<option>').text(count + '%').attr('value', count));
                count = count + soma;
            }
        };

        o.retornarInicioFimRealizado = function () {
            //$("#datfimbaseline").attr('disabled', 'disabled');
            $("#datfimbaseline").attr('readonly', 'readonly');
        };

        o.setaNumdiasMarco = function () {
            var valorDominio = $('select#domtipoatividade option:selected').val();
            var valorDominio1 = $("#ac-atividade").find('select[id="domtipoatividade"]').val();
            var valorDominio2 = $('#domtipoatividade').val();
            if ((valorDominio == "4") || (valorDominio1 == "4") || (valorDominio2 == "4")) {
                valorDominio = "4";
            } else {
                valorDominio = "3";
            }
            if (valorDominio == "4") {
                if (o.editMode === true) {
                    if (false == $("#numdiasrealizados").is('[readonly]')) {
                        $("#datfim").val($("#datinicio").val());
                        $("#numdiasrealizados").val(0);
                    } else {
                        $("#datfim").val($("#datinicio").val());
                        o.habilitarItem($("#numdiasrealizados"));
                        $("#numdiasrealizados").val(0);
                        o.desabilitarItem($("#numdiasrealizados"));
                    }
                } else {
                    if (false == $("#numdiasbaseline").is('[readonly]')) {
                        $("#datfimbaseline").val($("#datiniciobaseline").val());
                        $("#numdiasbaseline").val(0);
                    } else {
                        $("#datfimbaseline").val($("#datiniciobaseline").val());
                        o.habilitarItem($("#numdiasbaseline"));
                        $("#numdiasbaseline").val(0);
                        o.desabilitarItem($("#numdiasbaseline"));
                    }
                }
            }
        };

        o.setaEnumDiasMarco = function () {
            if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                valorDominio = parseInt(4);
            } else {
                valorDominio = parseInt(3);
            }
            if (valorDominio == 4) {
                if (false == $("#e_numdiasrealizados").is('[readonly]')) {
                    $("#e_numdiasrealizados").val(0);
                    o.desabilitarItem($("#e_numdiasrealizados"));
                } else {
                    o.habilitarItem($("#e_numdiasrealizados"));
                    $("#e_numdiasrealizados").val(0);
                    o.desabilitarItem($("#e_numdiasrealizados"));
                }
                if (false == $("#e_datfim").is('[readonly]')) {
                    o.habilitarItem($("#e_datfim"));
                    $("#e_datfim").val($("#e_datinicio").val());
                    o.desabilitarItem($("#e_datfim"));
                } else {
                    o.habilitarItem($("#e_datfim"));
                    $("#e_datfim").val($("#e_datinicio").val());
                    o.desabilitarItem($("#e_datfim"));
                }
            }
        };

        o.atualizadaDataFim = function (e) {
            var valorDominio = parseInt($('select#domtipoatividade option:selected').val());
            var valorDominio1 = parseInt($("#ac-atividade").find('select[id="domtipoatividade"]').val());
            var valorDominio2 = parseInt($('#domtipoatividade').val());
            var folgas = parseInt($('#numfolga').val());
            var qtDias;
            var datinicio;

            if ((valorDominio == 4) || (valorDominio1 == 4) || (valorDominio2 == 4)) {
                valorDominio = 4;
            } else {
                valorDominio = 3;
            }
            // Convertendo a data do brasil para americana
            if (o.editMode) {
                datinicio = $("#datinicio").val();
            } else {
                datinicio = $("#datiniciobaseline").val();
            }
            if (o.editMode) {
                qtDias = parseInt($("#numdiasrealizados").val());
            } else {
                qtDias = parseInt($("#numdiasbaseline").val());
            }

            if ($.isNumeric(qtDias)) {

                if (qtDias >= 0) {
                    /*****************************************************/
                    if (verificaData(datinicio)) {
                        var dataFinalRealizado = montaData(datinicio);

                        for (x = 0; x <= 15; x++) {
                            chkData = CRONOGRAMA.nonUtilDates(dataFinalRealizado);
                            if (!(chkData)) {
                                break;
                            } else {
                                datinicio = (new Date(
                                        dataFinalRealizado.getFullYear(),
                                        dataFinalRealizado.getMonth(),
                                        dataFinalRealizado.getDate() + 1)
                                ).toString("dd/MM/yyyy");
                                dataFinalRealizado = montaData(datinicio);
                            }
                        }

                        if (qtDias > 0) {

                            if (valorDominio == 4) {

                                o.buscaDataFimPorDias(datinicio, 1, "#datinicio,#datfim", "", "");

                                if (o.editMode) {
                                    $("#numdiasrealizados").val(0);
                                } else {
                                    $("#numdiasbaseline").val(0);
                                }
                            } else {
                                if (o.editMode) {
                                    o.buscaDataFimPorDias(datinicio, qtDias, "#datfim", "", "");
                                } else {
                                    o.buscaDataFimPorDias(datinicio, qtDias, "#datfim,#datfimbaseline", "", "");
                                }
                            }
                        } else {
                            $("#datfim").val($("#datinicio").val());
                            if (o.editMode) {
                                if (valorDominio == 4) {
                                    $("#numdiasrealizados").val(0);
                                } else {
                                    $("#numdiasrealizados").val(1);
                                }
                            } else {
                                if (valorDominio == 4) {
                                    $("#numdiasbaseline").val(0);
                                } else {
                                    $("#numdiasbaseline").val(1);
                                }
                                qtDias = 1;
                                $("#datfimbaseline").val($("#datiniciobaseline").val());
                            }
                        }
                    }
                }
            }
        }

        o.atualizadaDataFimBaseline = function (e) {
            var valorDominio = $('select#domtipoatividade option:selected').val();
            var valorDominio1 = $("#ac-atividade").find('select[id="domtipoatividade"]').val();
            var valorDominio2 = $('#domtipoatividade').val();
            if ((valorDominio == "4") || (valorDominio1 == "4") || (valorDominio2 == "4")) {
                valorDominio = "4";
            } else {
                valorDominio = "3";
            }
            // Convertendo a data do brasil para americana
            if (o.editMode === true) {
                var datinicio = $("#datinicio").val();
            } else {
                var datinicio = $("#datiniciobaseline").val();
            }
            //var splitdataini = datinicio.split('/');
            //var retornaDataAmerica = splitdataini[2] + '-' + splitdataini[1] + '-' + splitdataini[0];
            if (o.editMode === true) {
                var QuantidadeDias = $("#numdiasrealizados").val();
            } else {
                var QuantidadeDias = $("#numdiasbaseline").val();
            }
            // se a quantidade tiver vazia data fim recebe vazio
            if (QuantidadeDias != '') {
                //if ((parseInt(QuantidadeDias) >= 0) && (parseInt(splitdataini[0]) > 0) && (parseInt(splitdataini[1]) > 0) && (parseInt(splitdataini[2]) > 0)) {
                if (parseInt(QuantidadeDias) >= 0) {
                    /***************************************************************/
                    if (verificaData(datinicio)) {
                        var dataFinalRealizado = montaData(datinicio);
                        for (x = 0; x <= 15; x++) {
                            chkData = CRONOGRAMA.nonUtilDates(dataFinalRealizado);
                            if (!(chkData)) {
                                break;
                            } else {
                                datinicio = (new Date(
                                        dataFinalRealizado.getFullYear(),
                                        dataFinalRealizado.getMonth(),
                                        dataFinalRealizado.getDate() + 1)
                                ).toString("dd/MM/yyyy");
                                dataFinalRealizado = montaData(datinicio);
                            }
                        }
                        if (parseInt(QuantidadeDias) > 0) {
                            if (valorDominio == "4") {
                                o.buscaDataFimPorDias(datinicio, 1, "#datiniciobaseline,#datfimbaseline", "", "");
                                if (o.editMode === false) {
                                    $("#numdiasbaseline").val(0);
                                } else {
                                    $("#numdiasrealizados").val(0);
                                }
                            } else {
                                if (o.editMode === false) {
                                    o.buscaDataFimPorDias(datinicio, QuantidadeDias, "#datfimbaseline", "", "");
                                } else {
                                    o.buscaDataFimPorDias(datinicio, QuantidadeDias, "#datfim", "", "");
                                }
                            }
                        } else {
                            $("#datfim").val($("#datinicio").val());
                            if (o.editMode === true) {
                                if (valorDominio == "4") {
                                    $("#numdiasrealizados").val(0);
                                } else {
                                    $("#numdiasrealizados").val(1);
                                }
                                QuantidadeDias = $("#numdiasrealizados").val();
                            } else {
                                if (valorDominio == "4") {
                                    $("#numdiasbaseline").val(0);
                                } else {
                                    $("#numdiasbaseline").val(1);
                                }
                                QuantidadeDias = 1;
                                $("#datfimbaseline").val($("#datiniciobaseline").val());
                            }
                        }
                    }
                    /*************************************************************** /
                     if (Date.parse(retornaDataAmerica)) {
                        var DataAtual = new Date(retornaDataAmerica);
                        // Calculando a data com os dias do curso
                        var a = new Date(retornaDataAmerica);
                        // Calculando a data com os dias inseridos e preenchendo na data fim
                        // campo com apenas leitura
                        if (parseInt(QuantidadeDias) > 0) {
                            $("#datfimbaseline").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + parseInt(QuantidadeDias))
                            ).toString("dd/MM/yyyy"));
                        }
                        else {
                            if (o.editMode === true) {
                                $("#numdiasrealizados").val((valorDominio=="4" ? 0 : 1));
                                QuantidadeDias = $("#numdiasrealizados").val();
                            }else{
                                $("#numdiasbaseline").val((valorDominio=="4" ? 0 : 1));
                                QuantidadeDias = $("#numdiasbaseline").val();
                            }
                            $("#datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + parseInt(QuantidadeDias))
                            ).toString("dd/MM/yyyy"));
                            if (o.editMode === false) {
                                $("#datfimbaseline").val($("#datiniciobaseline").val());
                            }
                        }
                    }
                     /****************************************************************/
                }
            }
        }

        o.customEvents = function () {
            $('body').on('adicionarPredecessora', function (event, select) {
                o.adicionarPredecessora(event, select);
            });

            /*$('body').on('calcularBaseLine', function () {
             o.calcularBaseLine();
             });/**/
            $('body').on('retornarInicioFimRealizado', function () {
                o.retornarInicioFimRealizado();
            });

            $("body").on('click', ".btn-predecessora-move", function (event) {
                event.preventDefault();

                var a = {}, validaPredecessora = true,
                    at = {};

                a.idprojeto = $("#idprojeto").val();
                a.idatividaselecionada = $(o.itemCronogramaSelecionado).val();
                $this = $(this);
                var idselect = $this.attr('id');
                if (idselect == "predecessora-in") {
                    var indselect = $(o.selectPredecessora).find('option:selected').index();
                    if ((indselect >= 0) && (indselect !== '')) {
                        $(o.selectPredecessora + ' :selected').each(function (i, selected) {
                            if ($.isNumeric($(selected).val())) {
                                a.idatividadecronograma = $(selected).val();
                                //if (o.editMode === true) {
                                o.carregaArrayPredecessoras();
                                //}
                                /**********************************/
                                $.ajax({
                                    url: base_url + '/projeto/cronograma/valida-predecessora',
                                    dataType: 'json',
                                    type: 'POST',
                                    async: true,
                                    cache: true,
                                    data: {
                                        idprojeto: a.idprojeto,
                                        idatividadepredecessora: a.idatividadecronograma,
                                        idatividadecronograma: a.idatividaselecionada
                                    },
                                    success: function (data) {
                                        /*******************************************************/
                                        if (!(data.valida)) {
                                            $.pnotify({
                                                text: data.msg.text,
                                                type: 'error',
                                                hide: true
                                            });
                                            validaPredecessora = false;
                                            event.preventDefault();
                                        } else {
                                            //if (o.editMode === true) {
                                            o.carregaArrayPredecessoras();
                                            //}
                                            at.idatividadecronograma = $(selected).val();
                                            at.textatividadecronograma = $(selected).text();
                                            fimPos = at.textatividadecronograma.indexOf(" -- ", 0);
                                            if (fimPos != -1) {
                                                iniPos = fimPos - 10;
                                            }
                                            if ((iniPos != -1) && (fimPos != -1)) {
                                                at.dtfimatividadecronograma = at.textatividadecronograma.substring(iniPos, fimPos);
                                                var splitdatapre = at.dtfimatividadecronograma.split('/');
                                                at.dtamericaatividade = splitdatapre[2] + '-' + splitdatapre[1] + '-' + splitdatapre[0];
                                                at.dtamericaatividade = new Date(at.dtamericaatividade);
                                                at.dtamericaatividade.setDate(at.dtamericaatividade.getDate() + 1);
                                            }
                                            itemAtualAtv = {
                                                'idatividadecronograma': at.idatividadecronograma,
                                                'textatividadecronograma': at.textatividadecronograma,
                                                'dtfimatividadecronograma': at.dtfimatividadecronograma,
                                                'dtamericaatividade': at.dtamericaatividade
                                            };
                                            if (typeof at.idatividadecronograma !== 'undefined') {
                                                if ((at.idatividadecronograma > 0) && (at.textatividadecronograma != "")) {
                                                    if (typeof o.predecAtividadeNova[at.idatividadecronograma] === 'undefined') {
                                                        o.predecAtividadeNova[at.idatividadecronograma] = itemAtualAtv;
                                                        o.listaPredecessorasAtividade();
                                                        o.setarDataInicioBaseline();
                                                    }
                                                }
                                            }

                                        }
                                        /*******************************************************/
                                    },
                                    error: function () {
                                        $.pnotify({
                                            text: msgerroacesso,
                                            type: 'error',
                                            hide: false
                                        });
                                    }
                                });
                                /**********************************/
                                o.listaPredecessorasAtividade();
                                if (!(validaPredecessora)) {
                                    return false;
                                }
                            }
                        });
                        //o.listaPredecessorasAtividade();
                    }
                    //else{
                    //    console.log('9999 - 3');
                    //}
                }
                if (idselect == "predecessora-out") {
                    var indselect = $(o.selectPredecAtividade).find('option:selected').index();
                    if (indselect >= 0) {
                        //console.log(o.editMode);
                        //if (o.editMode === true) {
                        o.carregaArrayPredecessoras();
                        //}

                        $(o.selectPredecAtividade + ' :selected').each(function (i, selected) {
                            if ($.isNumeric($(selected).val())) {
                                at.idatividadecronograma = $(selected).val();
                                at.textatividadecronograma = $(selected).text();
                                itemAtualAtv = {
                                    'idatividadecronograma': "",
                                    'textatividadecronograma': "",
                                    'dtfimatividadecronograma': "",
                                    'dtamericaatividade': ""
                                };
                                o.predecAtividadeNova[at.idatividadecronograma] = itemAtualAtv;
                                o.listaPredecessorasAtividade();
                            }
                        });
                    }
                }
                o.calcularBaseLine();
                o.setarDataInicioBaseline();
                o.atualizarCaptionPredecessoras();
                o.setaNumdiasMarco();
            });

            $("body").on('atividadeAtualizarTipo', function (event) {
                var dados = $(o.itemCronogramaSelecionado).data('dados');
                var folga = dados.numfolga;
                var domtipoatividade = 4;
                var e_numdiasflg = false;
                var e_datfimflg = false;
                var e_datinicioflg = false;
                var numdiasrealizados = 0;
                //var dados = $(o.itemCronogramaSelecionado).data('dados');
                numdiasrealizados = dados.numdiasrealizados;
                if ($("#e_numdiasrealizados").is('[readonly]')) {
                    $("#e_numdiasrealizados").removeAttr('disabled');
                    $("#e_numdiasrealizados").attr('readonly', false);
                    e_numdiasflg = true;
                }
                if ($("#e_datfim").is('[readonly]')) {
                    $("#e_datfim").removeAttr('disabled');
                    //$("#e_datfim").attr('readonly', false);
                    e_datfimflg = true;
                }
                if ($("#e_datinicio").is('[readonly]')) {
                    $("#e_datinicio").removeAttr('disabled');
                    //$("#e_datinicio").attr('readonly', false);
                    e_datinicioflg = true;
                }
                if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                    domtipoatividade = 3;
                } else {
                    var edatinicio = $("#e_datinicio").val();
                    $("#e_datfim").val(edatinicio);
                    $("#e_numdiasrealizados").val(0);
                }
                var inicioDt = $("#e_datinicio").val();
                var fimDt = $("#e_datfim").val();
                if (domtipoatividade == 4) {
                    if (e_datinicioflg) {
                        $("#e_datinicio").attr({disabled: "disabled", readonly: "true"});
                        e_datinicioflg = false;
                    }
                    $("#e_numdiasrealizados").attr({disabled: "disabled", readonly: "true"});
                    e_numdiasflg = false;
                    $("#e_datfim").attr({disabled: "disabled", readonly: "true"});
                    e_datfimflg = false;
                }
                $.ajax({
                    url: base_url + '/projeto/cronograma/atualizar-dom-tipo-atividade/format/json',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        'domtipoatividade': domtipoatividade,
                        'datinicio': inicioDt,
                        'datfim': fimDt,
                        'numfolga': folga,
                        'numdiasrealizados': numdiasrealizados,
                        'idatividadecronograma': $(o.itemCronogramaSelecionado).val(),
                        'idprojeto': $("#idprojeto").val()
                    },
                    success: function (data) {
                        //CRONOGRAMA.retornaProjeto();
                        $.pnotify(data.msg);
                    },
                    error: function () {
                        //CRONOGRAMA.retornaProjeto();
                        $.pnotify({
                            text: msgerroacesso,
                            type: 'error',
                            hide: false
                        });
                    }
                });
            });
        };

        o.events = function () {
            $("body").on("click", ".btn-tranformar-marco, .btn-tranformar-atividade", function (event) {
                $("body").trigger("atividadeAtualizarTipo");
                event.preventDefault();
            });

            $("#inicial_dti, #inicial_dtf, #final_dti, #final_dtf").mask('99/99/9999');
            //$("#e_datinicio, #e_datfim, #inicial_dti, #inicial_dtf, #final_dti, #final_dtf").mask('99/99/9999');

            $("body").on('click', "a.btn-cadastrar-atividade", function (event) {
                event.preventDefault();
                var
                    $this = $(this),
                    urlForm = o.urls.cadastrar,
                    urlAjax = $this.attr('href') + '/idgrupo/' + $(o.itemCronogramaSelecionado).val()
                ;
                o.editMode = false;
                o.$dialogAtividade.dialog('option', 'title', 'Cronograma - Cadastrar Atividade');


                $this.data('form', o.formAtividade);
                $this.data('urlform', urlForm);
                $this.data('urlajax', urlAjax);
                $this.data('dialog', o.$dialogAtividade);
                $this.data('prefixo', '#at');

                $("body").trigger('openDialog', [$this]);

            });

            $("body").on('click', "a.btn-editar-atividade", function (event) {
                event.preventDefault();
                var
                    $this = $(this),
                    urlForm = o.urls.editar,
                    urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val()
                ;
                o.editMode = true;
                o.$dialogAtividade.dialog('option', 'title', 'Cronograma - Editar Atividade');
                $this.data('form', o.formAtividade);
                $this.data('urlform', urlForm);
                $this.data('urlajax', urlAjax);
                $this.data('dialog', o.$dialogAtividade);
                $this.data('prefixo', '#at');
                $("body").trigger('openDialog', [$this]);
            });

            $("body").on('click', "a.btn-atualizar-baseline-ativ", function (event) {
                event.preventDefault();

                var id = $(this).attr('id');
                if (id === 'disabled') {
                    alert("A base line desse projeto não pode ser atualizado porque o projeto já foi assinado.");
                    return false;
                }

                var
                    $this = $(this),
                    urlForm = o.urls.atualizarBaselineAtiv,
                    urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val()
                ;
                o.editMode = false;
                o.$dialogAtualizarBaselineAtiv.dialog('option', 'title', 'Cronograma - Atualizar Base Line Atividade');

                $this.data('form', o.formAtualizarBaselineAtiv);
                $this.data('urlform', urlForm);
                $this.data('urlajax', urlAjax);
                $this.data('dialog', o.$dialogAtualizarBaselineAtiv);
                $this.data('prefixo', '#at');

                $("body").trigger('openDialog', [$this]);
            });

            //$("body").on('change', 'select#predecessora', function (event) {
            $("body").on('dblclick', 'select#predecessora', function (event) {
                event.preventDefault();
                var $this = $(this);
                var valorDominio = $('select#domtipoatividade option:selected').val();
                var valorDominio2 = $('#domtipoatividade').val();
                if ((valorDominio == "4") || (valorDominio2 == "4")) {
                    valorDominio = "4";
                } else {
                    valorDominio = "3";
                }
                $("body").trigger('adicionarPredecessora', [$this]);
                if (o.editMode === true) {
                    o.habilitarFolgas();
                }
                //o.setaNumdiasMarco();
            });

            $("body").on('dblclick', 'select#predecessorasAtividade', function (event) {
                event.preventDefault();
                var at = {};
                var $this = $(this);
                var valorDominio = $('select#domtipoatividade option:selected').val();
                var valorDominio2 = $('#domtipoatividade').val();
                if ((valorDominio == "4") || (valorDominio2 == "4")) {
                    valorDominio = "4";
                } else {
                    valorDominio = "3";
                }
                //if (o.editMode === true) {
                o.carregaArrayPredecessoras();
                //}
                at.idatividadecronograma = $this.find('option:selected').val();
                at.textatividadecronograma = $this.find('option:selected').text();
                var fimPos = at.textatividadecronograma.indexOf(" -- ", 0);
                if (fimPos != -1) {
                    var iniPos = fimPos - 10;
                }
                itemAtualAtv = {
                    'idatividadecronograma': "",
                    'textatividadecronograma': "",
                    'dtfimatividadecronograma': "",
                    'dtamericaatividade': ""
                };
                o.predecAtividadeNova[at.idatividadecronograma] = itemAtualAtv;
                o.listaPredecessorasAtividade();
                if (o.editMode === true) {
                    o.habilitarFolgas();
                }
                o.setaNumdiasMarco();
                o.calcularBaseLine();
                o.setarDataInicioBaseline();
                o.atualizarCaptionPredecessoras();
            });

            $("body").delegate("#datinicio", "focusin", function () {
                var $this = $(this);
                $(this).mask('99/99/9999');
                if ($(this).is(":visible")) {
                    $this.datepicker({
                        format: 'dd/mm/yyyy',
                        language: 'pt-BR',
                        beforeShowDay: CRONOGRAMA.nonWorkingDates,
                        beforeShow: function (selectedDate, inst) {
                            var valorDominio = $('select#domtipoatividade option:selected').val();
                            var valorDominio2 = $('#domtipoatividade').val();
                            if ((valorDominio == "4") || (valorDominio2 == "4")) {
                                valorDominio = "4";
                            } else {
                                valorDominio = "3";
                            }
                            var val = null;
                            if (valorDominio == "4") {
                                $("#datinicio").datepicker("option", "maxDate", null);
                            } else {
                                if (verificaData($("#datfim").val())) {
                                    if (o.editMode === true) {
                                        val = $('#datfim').val();
                                        $(this).datepicker("option", "maxDate", val);
                                    }
                                }
                            }
                        }
                    });
                }
            });
            $("body").delegate("#datfim", "focusin", function () {
                var $this = $(this);
                $(this).mask('99/99/9999');
                $this.datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR',
                    beforeShowDay: CRONOGRAMA.nonWorkingDates,
                    beforeShow: function (selectedDate, inst) {
                        var val = null;
                        if (verificaData($("#datinicio").val())) {
                            val = $('#datinicio').val();
                            $(this).datepicker("option", "minDate", val);
                        }
                    }
                });
            });

            $("body").delegate("#e_datinicio", "focusin", function () {
                var $this = $(this);
                $(this).mask('99/99/9999');
                if ($(this).is(":visible")) {
                    $this.datepicker({
                        format: 'dd/mm/yyyy',
                        language: 'pt-BR',
                        beforeShowDay: CRONOGRAMA.nonWorkingDates,
                        onSelect: function (selectedDate, inst) {
                            if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                                o.setaEnumDiasMarco();
                            } else {
                                if ((verificaData($this.val())) && (verificaData($("#e_datfim").val()))) {
                                    var numdiasreais = parseInt($("#e_numdiasrealizados").val());
                                    if (numdiasreais > 0) {
                                        $("#e_datfim").val(o.buscaDataFimPorDias($this.val(), numdiasreais, "#e_datfim", null));
                                    } else {
                                        $("#e_datfim").val($this.val());
                                    }
                                }
                            }
                        },
                        beforeShow: function (selectedDate, inst) {
                            var val = null;
                            if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                                o.setaEnumDiasMarco();
                                $("#e_datinicio").datepicker("option", "maxDate", null);
                                $("#e_datfim").datepicker("option", "minDate", null);
                                o.desabilitarItem($("#e_datfim"));
                                o.desabilitarItem($("#e_numdiasrealizados"));
                            } else {
                                $("#e_numdiasrealizados").removeAttr('disabled');
                                $("#e_numdiasrealizados").attr('readonly', false);
                                $("#e_datfim").removeAttr('disabled');
                                //$("#e_datfim").attr('readonly', false);
                                if (verificaData($("#e_datfim").val())) {
                                    if (o.editMode === true) {
                                        var numdiasreais = parseInt($("#e_numdiasrealizados").val());
                                        val = $('#e_datfim').val();
                                        $("#e_datinicio").datepicker("option", "maxDate", val);
                                        if (numdiasreais > 0) {
                                            $("#e_datfim").val(o.buscaDataFimPorDias($this.val(), numdiasreais, "#e_datfim", ''));
                                        } else {
                                            $("#e_datfim").val($this.val());
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });

            $("body").delegate("#e_datfim", "focusin", function () {
                var $this = $(this);
                $(this).mask('99/99/9999');
                if ($(this).is(":visible")) {
                    $this.datepicker({
                        format: 'dd/mm/yyyy',
                        language: 'pt-BR',
                        beforeShowDay: CRONOGRAMA.nonWorkingDates,
                        onSelect: function (selectedDate, inst) {
                            if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                                o.setaEnumDiasMarco();
                            } else {
                                if ((verificaData($("#e_datinicio").val())) && (verificaData($("#e_datfim").val()))) {
                                    o.buscaNumDiasEntreDatas("#e_datinicio", "#e_datfim", "#e_numdiasrealizados");
                                }
                            }
                        },
                        beforeShow: function (selectedDate, inst) {
                            var val = null;
                            if (verificaData($("#e_datinicio").val())) {
                                val = $('#e_datinicio').val();
                                $(this).datepicker("option", "minDate", val);
                                o.buscaNumDiasEntreDatas("#e_datinicio", "#e_datfim", "#e_numdiasrealizados");
                            }
                        }
                    });
                }
            });

            $("body").delegate("#datiniciobaseline", "focusin", function () {
                var $this = $(this);
                $(this).mask('99/99/9999');
                $this.datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR',
                    beforeShowDay: CRONOGRAMA.nonWorkingDates,
                    beforeShow: function (selectedDate, inst) {
                        var valorDominio = $('select#domtipoatividade option:selected').val();
                        var valorDominio2 = $('#domtipoatividade').val();
                        if ((valorDominio == "4") || (valorDominio2 == "4")) {
                            valorDominio = "4";
                        } else {
                            valorDominio = "3";
                        }
                        var val = null;
                        if (valorDominio == "4") {
                            $(this).datepicker("option", "maxDate", null);
                        } else {
                            if (verificaData($("#datfimbaseline").val())) {
                                if (o.editMode === true) {
                                    val = $('#datfimbaseline').val();
                                    $(this).datepicker("option", "maxDate", val);
                                }
                            }
                        }
                    }
                });
            });

            $("body").delegate("#datfimbaseline", "focusin", function () {
                var $this = $(this);
                $(this).mask('99/99/9999');
                $this.datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR',
                    beforeShowDay: CRONOGRAMA.nonWorkingDates,
                    beforeShow: function (selectedDate, inst) {
                        //var val = null;
                        //if (verificaData($("#datiniciobaseline").val())) {
                        //    val = $('#datiniciobaseline').val();
                        //    $(this).datepicker("option", "minDate", val);
                        //}
                    }
                });
            });

            $("body").delegate("#numfolga", "focusout", function () {
                if (!(o.editMode === true)) {
                    o.listaPredecessorasAtividade();
                    o.calcularBaseLine();
                } else {
                    o.calculaDataInicio();
                }
                o.setarDataInicioBaseline();
                o.atualizarCaptionPredecessoras();
                $("#datInicioHidden").removeAttr('value');
                $("#datInicioHidden").attr('value', $("#datinicio").val());
            });

            $("body").on("focusout", "#vlratividadebaseline", function () {
                $this = $(this);
                $("#vlratividade").val($this.val());
            });
            //==========================================================================
            /// calculando o periodo de aula para inserir na data fim
            $("body").on("focusout", "#numdiasbaseline", function (e) {
                var valorDominio = $('select#domtipoatividade option:selected').val();
                var valorDominio2 = $('#domtipoatividade').val();
                if ((valorDominio == "4") || (valorDominio2 == "4")) {
                    valorDominio = "4";
                    $("#numdiasbaseline").val(1);
                } else {
                    valorDominio = "3";
                }
                // Convertendo a data do brasil para americana
                var datiniciobaseline = $("#datiniciobaseline").val();
                var QuantidadeDias = $("#numdiasbaseline").val();
                // se a quantidade tiver vazia data fim recebe vazio
                if (QuantidadeDias == '') {
                    $("#datfimbaseline").val('');
                    $("#datfim").val('');
                } else {
                    if (verificaData(datiniciobaseline)) {
                        var dataFinalbaseline = montaData(datiniciobaseline);
                        for (x = 0; x <= 15; x++) {
                            chkData = CRONOGRAMA.nonUtilDates(dataFinalbaseline);
                            if (!(chkData)) {
                                break;
                            } else {
                                datiniciobaseline = (new Date(
                                        dataFinalbaseline.getFullYear(),
                                        dataFinalbaseline.getMonth(),
                                        dataFinalbaseline.getDate() + 1)
                                ).toString("dd/MM/yyyy");
                                dataFinalbaseline = montaData(datiniciobaseline);
                            }
                        }
                        if (parseInt(QuantidadeDias) >= 0) {
                            if (valorDominio == "4")
                                o.buscaDataFimPorDias(datiniciobaseline, 1, "#datiniciobaseline,#datinicio,#datfimbaseline,#datfim", "", "");
                            else
                                o.buscaDataFimPorDias(datiniciobaseline, QuantidadeDias, "#datfimbaseline,#datfim", "", "");
                        }
                    }
                }
            });

            //=========================================================================
            /// calculando o periodo de aula para inserir na data fim realizado
            $("body").delegate("#e_numdiasrealizados", "focusout", function (e) {
                e.preventDefault();
                var domtipoatividade = parseInt($("#domtipoatividade").val());
                var datinicioreal = $("#e_datinicio").val();
                var QuantidadeDias = $("#e_numdiasrealizados").val();

                // Calculando a data com os dias inseridos e preenchendo na data fim
                if (QuantidadeDias == '') {
                    $("#e_datfim").val('');
                    $("#e_numdiasrealizados").focus();
                } else {
                    // if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                    if (domtipoatividade == 3) {
                        o.setaEnumDiasMarco();
                    } else {
                        if (verificaData(datinicioreal)) {
                            if (parseInt(QuantidadeDias) > 0) {
                                $('#e_datfim').val(o.buscaDataFimPorDias(datinicioreal, QuantidadeDias, '#e_datfim', ''));
                            } else {
                                $('#e_datfim').val(datinicioreal);
                            }
                        }
                        // if (verificaData(datinicioreal)) {
                        //     var dataFinalRealizado = montaData(datinicioreal);
                        //     for (x = 0; x <= 15; x++) {
                        //         chkData = CRONOGRAMA.nonUtilDates(dataFinalRealizado);
                        //         if (!(chkData)) {
                        //             break;
                        //         }
                        //         else {
                        //             datinicioreal = (new Date(
                        //                     dataFinalRealizado.getFullYear(),
                        //                     dataFinalRealizado.getMonth(),
                        //                     dataFinalRealizado.getDate() + 1)
                        //             ).toString("dd/MM/yyyy");
                        //             dataFinalRealizado = montaData(datinicioreal);
                        //         }
                        //     }
                        //     if (parseInt(QuantidadeDias) >= 0) {
                        //         if ($(o.itemCronogramaSelecionado).is(".item-marco"))
                        //             o.buscaDataFimPorDias(datinicioreal, 1, "#e_datinicio,#e_datfim", "", "");
                        //         else
                        //             o.buscaDataFimPorDias(datinicioreal, QuantidadeDias, "#e_datfim", "", "");
                        //     }
                        // }
                    }
                }
            });

            //=========================================================================
            ///////////////////////////// fim calculo ////////////////////////
            // calculando o tempo REALIZADO de curso com a data ao clicar fora
            $("body").on("focusout", "#numdiasrealizados", function (e) {
                // Convertendo a data do brasil para americana
                var datinicioreal = $("#datinicio").val();
                var QuantidadeDias = $("#numdiasrealizados").val();
                // Calculando a data com os dias inseridos e preenchendo na data fim
                if (QuantidadeDias == '') {
                    $("#datfim").val('');
                    $("#numdiasrealizados").focus();
                } else {
                    if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                        o.setaEnumDiasMarco();
                    } else {
                        if (verificaData(datinicioreal)) {
                            var dataFinalRealizado = montaData(datinicioreal);
                            for (x = 0; x <= 15; x++) {
                                chkData = CRONOGRAMA.nonUtilDates(dataFinalRealizado);
                                if (!(chkData)) {
                                    break;
                                } else {
                                    datinicioreal = (new Date(
                                            dataFinalRealizado.getFullYear(),
                                            dataFinalRealizado.getMonth(),
                                            dataFinalRealizado.getDate() + 1)
                                    ).toString("dd/MM/yyyy");
                                    dataFinalRealizado = montaData(datinicioreal);
                                }
                            }
                            if (parseInt(QuantidadeDias) >= 0) {
                                if ($(o.itemCronogramaSelecionado).is(".item-marco"))
                                    o.buscaDataFimPorDias(datinicioreal, 1, "#datinicio,#datfim", "", "");
                                else
                                    o.buscaDataFimPorDias(datinicioreal, QuantidadeDias, "#datfim", "", "");
                            }
                        }
                    }
                }
            });
            ///////////////////////////// fim calculo ////////////////////////
            //========================================================================
            // digitando apenas numeros no campo quantidade
            $("body").on("keypress", "#numdiasbaseline", function (e) {
                var tecla = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
                var kshift = (e.shiftKey ? true : false);
                if (((tecla > 47 && tecla < 58) || (tecla == 46) || (tecla == 8) || (tecla == 9))) return true;
                else {
                    if (tecla != 8) {
                        $("#numdiasbaseline").attr('title', 'Digite apenas números');
                        return false;
                    } else return true;
                }
            });
            $("body").on("keypress", "#numdiasrealizados, #e_numdiasrealizados", function (e) {
                var tecla = null;
                var tecla = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
                var kshift = (e.shiftKey ? true : false);
                if (((tecla > 47 && tecla < 58) || (tecla == 46) || (tecla == 8) || (tecla == 9))) return true;
                else {
                    if (tecla != 8) {
                        $(this).attr('title', 'Digite apenas números');
                        return false;
                    } else return true;
                }
            });
            // calculando o tempo REALIZADO de curso com a data ao pressionar enter
            $("body").on("keypress", "#numdiasrealizados", function (e) {
                if (e.which == 13) {
                    o.atualizadaDataFim();
                }
            });

            $("body").on("focusin", "#vlratividadebaseline, #vlratividade", function () {
                $this = $(this);
                if (!$this.data('formatCurrencyAttached')) {
                    $this.data('formatCurrencyAttached', true);
                    $this.formatCurrency({
                        decimalSep: ',',
                        thousandsSep: '.',
                        digits: 2
                    }).trigger('keypress');
                }
            });
            $("body").on("click", "#ac-atividade", function () {
                // o.existePredecessora();
                var dataInicio = $('#datinicio').val();
                $('#datInicioHidden').removeAttr('value');
                $('#datInicioHidden').attr('value', dataInicio);
            });

            $("body").on("focusout", "#datinicio, #datfim", function () {
                var valorDominio = $('select#domtipoatividade option:selected').val();
                var valorDominio2 = $('#domtipoatividade').val();
                if ((valorDominio == "4") || (valorDominio2 == "4")) {
                    valorDominio = "4";
                } else {
                    valorDominio = "3";
                }
                var iniciob = montaData($("#datinicio").val());
                var fimb = montaData($("#datfim").val());
                if (fimb < iniciob) {
                    $("#datfim").val($("#datinicio").val());
                }
                o.existePredecessora();
                var dataInicio = $('#datinicio').val();
                $('#datInicioHidden').removeAttr('value');
                $('#datInicioHidden').attr('value', dataInicio);
                if (o.editMode === false) {
                    if ((verificaData($("#datinicio").val())) && (verificaData($("#datfim").val()))) {
                        if (valorDominio == "4") {
                            $("#datfimbaseline").val($("#datiniciobaseline").val());
                            $("#datfim").val($("#datiniciobaseline").val());
                            $("#datinicio").val($("#datiniciobaseline").val());
                            if (false == $("#numdiasbaseline").is('[readonly]')) {
                                $("#numdiasbaseline").val(0);
                            } else {
                                o.habilitarItem($("#numdiasbaseline"));
                                $("#numdiasbaseline").val(0);
                                o.desabilitarItem($("#numdiasbaseline"));
                            }
                        } else {
                            var daysIni = calculaDiasEntreDatas($("#datinicio").val(), $("#datfim").val());
                            if (daysIni <= 0) {
                                $("#datfim").val($("#datinicio").val());
                                $("#numdiasbaseline").val((valorDominio == "3" ? "1" : "0"));
                            } else {
                                o.buscaNumDiasEntreDatas("#datinicio", "#datfim", "#numdiasbaseline");
                            }
                        }
                    }
                } else {
                    if (valorDominio == "4") {
                        if (false == $("#numdiasrealizados").is('[readonly]')) {
                            $("#datfim").val($("#datinicio").val());
                            $("#numdiasrealizados").val(0);
                        } else {
                            $("#datfim").val($("#datinicio").val());
                            o.habilitarItem($("#numdiasrealizados"));
                            $("#numdiasrealizados").val(0);
                            o.desabilitarItem($("#numdiasrealizados"));
                        }
                    }
                }
            });

            // Calcular o qtd dia pela fim e data inicio realizado///////////////
            /* $("body").on("focusout", "#e_datfim", function () {
             if ((verificaData($("#e_datinicio").val())) && (verificaData($("#e_datfim").val()))) {
             var iniciob = montaData($("#e_datinicio").val());
             var fimb = montaData($("#e_datfim").val());
             if(fimb<iniciob){
             $("#e_datfim").val($("#e_datinicio").val());
             }
             CRONOGRAMA.atualizaNumDias = true;
             o.buscaNumDiasEntreDatas("#e_datinicio", "#e_datfim", "#e_numdiasrealizados");
             }
             });*/
            ///////////////////////////// fim calculo ////////////////////////
            $("#inicial_dti, #inicial_dtf").datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                beforeShow: checkPeriodoInicial,
                beforeShowDay: CRONOGRAMA.nonWorkingDates
            });

            $("#final_dti, #final_dtf").datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                beforeShow: checkPeriodoFinal,
                beforeShowDay: CRONOGRAMA.nonWorkingDates
            });

        };

        checkPeriodoInicial = function (input) {
            var dateMin = null,
                dateMax = null;

            if (input.id === "inicial_dti") {
                if ($("#inicial_dtf").datepicker("getDate") != null) {
                    if (verificaData($("#inicial_dtf").val())) {
                        dateMax = $("#inicial_dtf").datepicker("getDate");
                        dateMin = null;
                    } else {
                        dateMax = null;
                        dateMin = null;
                    }
                } else {
                    dateMax = null;
                    dateMin = null;
                }
            } else if (input.id === "inicial_dtf") {
                dateMax = new Date;
                dateMin = null;
                if ($("#inicial_dti").datepicker("getDate") != null) {
                    if (verificaData($("#inicial_dti").val())) {
                        dateMin = $("#inicial_dti").datepicker("getDate");
                        dateMax = null;
                    } else {
                        dateMax = null; //Set this to your absolute maximum date
                        dateMin = null;
                    }
                } else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            }
            return {
                minDate: dateMin,
                maxDate: dateMax
            };
            /**/

        }

        checkPeriodoFinal = function (input) {
            var dateMin = null,
                dateMax = null;

            if (input.id === "final_dti") {
                if ($("#final_dtf").datepicker("getDate") != null) {
                    if (verificaData($("#final_dtf").val())) {
                        dateMax = $("#final_dtf").datepicker("getDate");
                        dateMin = null;
                    } else {
                        dateMax = null;
                        dateMin = null;
                    }
                } else {
                    dateMax = null;
                    dateMin = null;
                }
            } else if (input.id === "final_dtf") {
                dateMax = new Date;
                dateMin = null;
                if ($("#final_dti").datepicker("getDate") != null) {
                    if (verificaData($("#final_dti").val())) {
                        dateMin = $("#final_dti").datepicker("getDate");
                        dateMax = null;
                    } else {
                        dateMax = null; //Set this to your absolute maximum date
                        dateMin = null;
                    }
                } else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            }
            return {
                minDate: dateMin,
                maxDate: dateMax
            };
        }

        verificaData = function (vrData) {
            if (typeof (vrData) != 'undefined') {
                if (vrData.length > 0) {
                    var splitdataTmp = vrData.split('/');

                    if (splitdataTmp[0] != "__") {
                        var retornaDataAmericaTmp = splitdataTmp[2] + '-' + splitdataTmp[1] + '-' + splitdataTmp[0];
                        if (Date.parse(retornaDataAmericaTmp)) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        calculaDiasEntreDatas = function (dataini, datafim) {
            var datinicio = dataini;
            var datfim = datafim;
            var positivo = true;
            if ((verificaData(datinicio)) && (verificaData(datfim))) {
                var splitdataIni = datinicio.split('/');
                var splitdataFim = datfim.split('/');
                if ((parseInt(splitdataFim[0]) > 0) && (parseInt(splitdataFim[1]) > 0) && (parseInt(splitdataFim[2]) > 0) &&
                    (parseInt(splitdataIni[0]) > 0) && (parseInt(splitdataIni[1]) > 0) && (parseInt(splitdataIni[2]) > 0)) {
                    if ((Date.parse(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0])) &&
                        (Date.parse(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]))) {
                        var retonraDatFimAmer = new Date(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0]);
                        var retonraDatIniAmer = new Date(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]);
                        /*********************/
                        var timeDiff = Math.abs(retonraDatFimAmer.getTime() - retonraDatIniAmer.getTime());
                        if (timeDiff < 0) positivo = false;
                        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        /*********************/
                        var resultadoTotal = ((Date.UTC((retonraDatFimAmer.getYear()), retonraDatFimAmer.getMonth(), retonraDatFimAmer.getDate(), 0, 0, 0)
                            - Date.UTC((retonraDatIniAmer.getYear()), retonraDatIniAmer.getMonth(), retonraDatIniAmer.getDate(), 0, 0, 0)) / 86400000);
                        return resultadoTotal;
                        //return diffDays;
                    }
                }
            } else return 0;
        }

        montaData = function (datatxt) {
            var splitdatapre = datatxt.split('/');
            var dtamericaatividade = splitdatapre[2] + '-' + splitdatapre[1] + '-' + splitdatapre[0];
            dtamericaatividade = new Date(dtamericaatividade);
            dtamericaatividade.setDate(dtamericaatividade.getDate() + 1);
            return dtamericaatividade;
        }

        o.init = function () {
            //o.compilarTemplates();
            o.initDialogs();
            o.customEvents();
            o.events();
            o.retornarInicioFimRealizado();
        };

        return o;
    }(jQuery, Intervalo)
)
;
