//XXXXXXXXXX ATIVIDADE XXXXXXXXXX
CRONOGRAMA.atividade = (function ($, Handlebars, Intervalo) {
    var o = {};
    vSalvar = true;
    vOrdena = true;
    vDetalhado = true;
    o.$dialogAtividade = null;
    o.$dialogAtividadeExcluir = null;
    o.formAtividade = "form#ac-atividade";
    o.formBloquear = "form#ac-atividade-bloquear";
    o.formAtiviPredecxcluir = "form#ac-ativiPredec-excluir";
    o.formAtividadeExcluir = "form#ac-atividade-excluir";
    o.formPredecessoraExcluir = "form#ac-predecessora-excluir";
    o.formPercentual = "form#e_atividade";
    o.formAtualizarBaselineAtiv = "form#atualizar-baseline-ativ";
    o.alertaPredecessora = "#alert-predecessora";
    o.templatePredecessora = null;
    o.tablePredecessora = "table#table-predecessoras";
    o.ItemDetalhado = '.btn-simples';
    o.allItemSimples = '.btn-detalhado, .cron-simples';
    o.allItemDetalhado = '.btn-simples, .cron-detalhado';
    o.selectPredecessora = "select#predecessora";
    o.selectPredecAtividade = "select#predecessorasAtividade";
    o.selectPredecessoraEditar = ".container-predecessora-editar select#predecessora";
    o.linkPredecessora = 'a.remover-predecessora';
    o.linkPredecessoraEditar = 'a.remover-predecessora-editar';
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.editMode = false;
    o.urls = {
        cadastrar: '/planodeacao/cronograma/cadastrar-atividade/format/json',
        editar: '/planodeacao/cronograma/editar-atividade/format/json',
        excluir: '/planodeacao/cronograma/excluir-atividade/format/json',
        atualizarBaselineAtiv: '/planodeacao/cronograma/atualizar-baseline-atividade/format/json',
        bloquearAtiv: '/planodeacao/cronograma/bloquear-ordenacao/format/json',
    };

    jQuery.validator.addMethod("valorSequencia", function (value, element) {
        var sequencial = parseInt($("#numseq").val());
        var maximoValor = parseInt($("#numseq").attr("max-value"));
        if (!($.isNumeric(sequencial))) {
            sequencial = 1;
        }
        if (!($.isNumeric(maximoValor))) {
            maximoValor = 999;
        }
        if ((sequencial > maximoValor) || (sequencial < 1)) {
            return false;
        }
        return true;
    }, "Valor da sequência inválido");

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
                o.mostraCampos();
                $('#dialog-atividade').empty();
                $(this).dialog('option', 'width', '1100px');
                o.formAtividade = "form#ac-atividade";
            },
            open: function (event, ui) {
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
                            vSalvar = false;
                            $('#dialog-atividade').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            $(o.formAtividade).submit();
                            setTimeout(function () {
                                vSalvar = true;
                                $('#dialog-atividade').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }, 8000);
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
        }).css("maxHeight", window.innerHeight - 100);
        o.$dialogAtualizarBaselineAtiv = $('#dialog-atualizar-baseline-ativ').dialog({
            autoOpen: false,
            title: 'Cronograma - Atualizar Base Line Atividade',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                o.mostraCampos();
                $('#dialog-atualizar-baseline-ativ').empty();
            },
            open: function (event, ui) {
                vSalvar = true;
                $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Confirmar': function () {
                    $(o.formAtualizarBaselineAtiv).submit();
                    $('#dialog-atualizar-baseline-ativ').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    CRONOGRAMA.retornaPlanodeacao();
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        }).css("maxHeight", window.innerHeight - 150);
    };

    o.listapartesinteressadas = function (a) {
        idplanodeacao = $('#idplanodeacao').val();
        $.ajax({
            url: base_url + '/planodeacao/tpa/grid-tpa/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idplanodeacao': idplanodeacao,
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
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    };

    o.compilarTemplates = function () {
        o.templatePredecessora = Handlebars.compile($('#tpl-predecessora').html());
    };

    o.calendarios = function () {
    };

    o.habilitarFolgas = function () {
        disabled = true;
        var length = $('select#predecessorasAtividade > option').length;
        if (length > 0) {
            disabled = false;
            $("#numfolga").attr('disabled', disabled);
        } else {
            $("#numfolga").val(0);
            disabled = true;
            $("#numfolga").attr('disabled', disabled);
        }
    };

    o.trataDatasAtividadeEdicao = function (input) {
        if ((input.attr("name") === "datinicio") || (input.attr("name") === "datfim")) {
            if (($("#datinicio").datepicker("getDate") != null) && ($("#datfim").datepicker("getDate") != null)) {
                var days = calculaDiasEntreDatas($("#datinicio").val(), $("#datfim").val());
                //console.log('trataDatasAtividadeEdicao: ' + input.attr("name") );
                if (days < 0) {
                    if (input.attr("name") === "datfim") {
                        //$("#numdiasrealizados").val(0);
                    }
                } else {
                    //$("#numdiasrealizados").val(days);
                }
            } else {
                //$("#numdiasrealizados").val(0);
            }
            o.setDataLimites();
        }
    };

    o.trataDatasAtividade = function (input) {
        var dateMin = null,
            dateMax = null;
        if (input.attr("name") === "datiniciobaseline") {
            if (input.datepicker("getDate") != null) {
                if (verificaData(input.val())) {
                    var dependente = input.data('input');
                    $(dependente).val(input.val());
                    dateMax = null;
                    dateMin = input.datepicker("getDate");
                }
            }
            //$("#datfimbaseline").datepicker("option", "minDate", dateMin);
            //$("#datfimbaseline").datepicker("option", "maxDate", dateMax);
        }
        if (input.attr("name") === "datfimbaseline") {
            if (input.datepicker("getDate") != null) {
                if (verificaData(input.val())) {
                    var dependente = input.data('input');
                    $(dependente).val(input.val());
                    dateMax = input.datepicker("getDate");
                    dateMin = null;
                }
            }
            //$("#datiniciobaseline").datepicker("option", "minDate", dateMin);
            //$("#datiniciobaseline").datepicker("option", "maxDate", dateMax);
        }
        if (input.attr("name") === "datinicio") {
            if (input.datepicker("getDate") != null) {
                if (verificaData(input.val())) {
                    dateMax = null;
                    dateMin = input.datepicker("getDate");
                }
            }
            //$("#datfim").datepicker("option", "minDate", dateMin);
            //$("#datfim").datepicker("option", "maxDate", dateMax);
        }
        if (input.attr("name") === "datfim") {
            if (input.datepicker("getDate") != null) {
                if (verificaData(input.val())) {
                    dateMax = input.datepicker("getDate");
                    dateMin = null;
                }
            }
            //$("#datinicio").datepicker("option", "minDate", dateMin);
            //$("#datinicio").datepicker("option", "maxDate", dateMax);
        }
        if (($("#datiniciobaseline").datepicker("getDate") != null) && ($("#datfimbaseline").datepicker("getDate") != null)) {
            var days = calculaDiasEntreDatas($("#datiniciobaseline").val(), $("#datfimbaseline").val());
            if (days < 0) {
                if (input.attr("name") === "datiniciobaseline") {
                    $("#datfimbaseline").val($("#datiniciobaseline").val());
                    $("#numdiasbaseline").val(0);
                }
                if (input.attr("name") === "datfimbaseline") {
                    $("#datiniciobaseline").val($("#datfimbaseline").val());
                    $("#numdiasbaseline").val(0);
                }
            } else {
                $("#numdiasbaseline").val(days);
            }
        } else {
            $("#numdiasbaseline").val(0);
        }
        if (($("#datinicio").datepicker("getDate") != null) && ($("#datfim").datepicker("getDate") != null)) {
            var days = calculaDiasEntreDatas($("#datinicio").val(), $("#datfim").val());
            if (days < 0) {
                if (input.attr("name") === "datinicio") {
                    $("#datfim").val($("#datinicio").val());
                }
                if (input.attr("name") === "datfim") {
                    $("#datinicio").val($("#datfim").val());
                }
            }
        }
    };

    o.setarDataInicio = function () {
        //console.log("# Iniciando o setarDataInicio #");
        var t = $(o.selectPredecAtividade),
            tam = $(o.selectPredecAtividade + ' > option').length;
        var
            idplanodeacao = $('#idplanodeacao').val(),
            idatividadecronograma = $('#idatividadecronograma').val();

        if (tam <= 0) {
            disabled = true;
            $("#numfolga").attr('disabled', disabled);
            return;
        } else {
            disabled = false;
            $("#numfolga").attr('disabled', disabled);
        }
        //console.log("# fora isnumeric:" + idatividadecronograma);
        if ($.isNumeric(idatividadecronograma)) {
            //console.log("# Iniciando - isnumeric #");
            if (idatividadecronograma > 0) {
                //console.log("# Iniciando - idatividadecronograma > 0 #");
                $.ajax({
                    url: base_url + '/planodeacao/cronograma/retorna-inicio-base-line',
                    dataType: 'json',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        idplanodeacao: idplanodeacao,
                        idatividadecronograma: idatividadecronograma
                    },
                    //processData: false,
                    success: function (data) {

                        var resultado = data;
                        var dataInicio = null;

                        if (resultado != null) {
                            o.habilitarDataInicio();
                            $("#maior_valor").attr('value', data);
                            o.calcularReal($("#maior_valor"), 'inicio');
                            $("#datinicio").val(data);
                            o.calcularReal($("#datinicio"), 'inicio');

                            dataInicio = $("#datinicio").val();
                            $('#datInicioHidden').removeAttr('value');
                            $('#datInicioHidden').attr('value', dataInicio);

                            $("#datInicioHidden").attr('value', $("#datinicio").val());
                            o.habilitarFolgas();
                            o.desabilitarDataInicio();
                        }
                        //console.log('332 - maior_valor: ' + $("#maior_valor").val());
                        if (resultado == null) {
                            o.habilitarDataInicio();
                            o.habilitarFolgas();
                            $("#maior_valor").removeAttr('value');
                        }
                        //console.log('338 - maior_valor: ' + $("#maior_valor").val());

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
        }
    };

    o.existePredecessora = function () {
        // console.log("##############Existe Predecessora##################");
        var
            idplanodeacao = $('#idplanodeacao').val(),
            idatividadecronograma = $('#idatividadecronograma').val();
        if ($.isNumeric(idatividadecronograma)) {
            if (idatividadecronograma > 0) {
                $.ajax({
                    url: base_url + '/planodeacao/cronograma/retorna-inicio-base-line',
                    dataType: 'json',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: {
                        idplanodeacao: idplanodeacao,
                        idatividadecronograma: idatividadecronograma
                    },
                    //processData: false,
                    success: function (data) {
                        var resultado = data;
                        if (resultado != null) {
                            o.habilitarFolgas();
                            o.desabilitarDataInicio();
                        }
                        if (resultado == null) {
                            o.habilitarDataInicio();
                            $("#maior_valor").removeAttr('value');
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
        }
    };

    o.calcularReal = function (obj, valor) {
        o.calcularDias(obj, valor);
    }

    o.calcularBaseLine = function () {
        if ($("#datiniciobaseline").length > 0 && $("#datfimbaseline").length > 0) {
            o.calcularDias($("#datiniciobaseline"), 'inicio');
            o.calcularDias($("#datfimbaseline"), 'fim');
        }
        if ($("#datiniciobaseline").length <= 0 && $("#datfimbaseline").length <= 0) {
            o.calcularDias($("#datinicio"), 'inicio');
            $("#datInicioHidden").removeAttr('value');
            $("#datInicioHidden").attr('value', $("#datinicio").val());
        }
    };

    o.calcularDias = function (obj, valor) {
        var periodo = valor,
            $numfolga = $("#numfolga"),
            $numReal = $("#numdiasrealizados"),
            numdiareal = 0,
            folgas = 0,
            dataPredecessora = null;
        numdiareal = $numReal.val();
        if (false == $numfolga.is(':disabled')) {
            folgas = $numfolga.val();
        }
        if (obj.attr('id') == 'datiniciobaseline' || obj.attr('id') == 'datfimbaseline') {
            if (periodo == 'inicio') {
                dataPredecessora = obj.data('inicio');
                obj.val(function () {
                    if (null == dataPredecessora) {
                        //console.log('1.1) calcularDias => dataPredecessora:' + dataPredecessora);
                        o.setarDataInicio();
                        dataPredecessora = $("#maior_valor").val();
                        //console.log('1.2) calcularDias => dataPredecessora:' + dataPredecessora);
                        var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
                        return Intervalo.adicionarDias(inicio, folgas);
                    } else {
                        //console.log('2) calcularDias => dataPredecessora:' + dataPredecessora);
                        var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
                        return Intervalo.adicionarDias(inicio, folgas);
                    }
                }).trigger('focusout');
            }
            // para inseir automatico na data fim descomente esses campos
            if (periodo == 'fim') {
                dataPredecessora = obj.data('fim');
                obj.val(function () {
                    if (null == dataPredecessora) {
                        dataPredecessora = $("#maior_valor").val();
                    }
                    //console.log('3) calcularDias => dataPredecessora:' + dataPredecessora);
                    var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
                    return Intervalo.adicionarDias(inicio, numdiareal);
                }).trigger('focusout');
            }
        }

        if (obj.attr('id') == 'datinicio' || obj.attr('id') == 'datfim') {
            if (periodo == 'inicio') {
                dataPredecessora = obj.val();
                obj.val(function () {
                    if (null == dataPredecessora) {
                        dataPredecessora = $("#maior_valor").val();
                    }
                    //console.log('4) calcularDias => dataPredecessora:' + dataPredecessora);
                    var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
                    return Intervalo.adicionarDias(inicio, folgas);
                }).trigger('focusout');
            }
            // para inseir automatico na data fim descomente esses campos
            if (periodo == 'fim') {
                dataPredecessora = obj.val();
                obj.val(function () {
                    if (null == dataPredecessora) {
                        dataPredecessora = $("#maior_valor").val();
                    }
                    //console.log('5) calcularDias => dataPredecessora:' + dataPredecessora);
                    var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
                    return Intervalo.adicionarDias(inicio, numdiareal);
                }).trigger('focusout');
            }
        }
        if (false == $numfolga.is(':disabled')) {
            folgas = $numfolga.val();
        }
    }

    o.adicionarPredecessora = function (event, select) {
        var a = {},
            seletedIt = o.selectPredecessora,
            t = $(o.tablePredecessora);
        a.idatividadecronograma = $(seletedIt).find('option:selected').val();
        if (a.idatividadecronograma !== '') {
            if (o.editMode === true) {
                o.cadastrarPredecessora(a);
                o.calcularBaseLine();
                CRONOGRAMA.retornaPlanodeacao();
                o.mostraCampos();
            }
            o.atualizarCaptionPredecessoras();
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

    o.atualizarCaptionPredecessoras = function () {
        var tam = $(o.selectPredecAtividade + ' > option').length;
        if (tam > 0) {
            o.habilitarFolgas();
        }
    };

    o.removerPredecessora = function (valorPredecessora) {
        if (o.editMode === true) {
            //o.excluirPredecessora(valorPredecessora);
            o.excluirPredecessora(valorPredecessora);
            CRONOGRAMA.retornaPlanodeacao();
            o.mostraCampos();
            o.setarDataInicio();
            //btn.closest('tr').remove();
            o.atualizarCaptionPredecessoras();
        } else {
            //btn.closest('tr').remove();
            o.atualizarCaptionPredecessoras();
            CRONOGRAMA.retornaPlanodeacao();
            o.mostraCampos();
            o.setarDataInicio();
            o.calcularBaseLine();
        }
    };
    o.mostraCampos = function (a) {
        if ($(o.ItemDetalhado).css("display") == "none") {
            $(o.allItemSimples).show();
            $(o.allItemDetalhado).hide();
        } else {
            $(o.allItemSimples).hide();
            $(o.allItemDetalhado).show();
        }
    };

    o.cadastrarPredecessora = function (a) {
        var t = null,
            idplanodeacao = $("input#idplanodeacao").val(),
            idatividadepredecessora = a.idatividadecronograma,
            seletedIt = o.selectPredecessora,
            idatividade = $(o.formAtividade).find('input[id="idatividadecronograma"]').val();
        ;
        $.ajax({
            url: base_url + '/planodeacao/cronograma/adicionar-predecessora',
            dataType: 'json',
            type: 'POST',
            async: true,
            cache: true,
            data: {
                idplanodeacao: idplanodeacao,
                idatividadepredecessora: idatividadepredecessora,
                idatividade: idatividade
            },
            success: function (data) {
                if (data.success == true) {
                    if (data != null) {
                        /****** Adiciona a Lista ********/
                        // t = $(o.tablePredecessora);
                        // a.idatividadecronograma = $(seletedIt).find('option:selected').val();
                        // text = $(seletedIt).find('option:selected').text();
                        // aux = text.split('-');
                        // datas = aux[0].split('a');
                        // a.nomatividadecronograma = aux[1];
                        // a.datinicio = datas[0];
                        // a.datfim = datas[1];
                        // $(o.templatePredecessora(a)).appendTo(t);
                        o.carregaPredecessoras(data.listaPredecessoras);
                        /*******************************/
                    }
                    $.pnotify({
                        text: data.msg.text,
                        type: 'success',
                        hide: true
                    });
                    o.setarDataInicio();
                } else {
                    if (typeof data.msg.text !== 'string') {
                        $.pnotify({
                            text: 'Falha ao enviar a requisição',
                            type: 'error',
                            hide: true
                        });
                    } else {
                        $.pnotify({
                            text: data.msg.text,
                            type: 'error',
                            hide: true
                        });
                    }
                }
                CRONOGRAMA.retornaPlanodeacao();
                o.mostraCampos();
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
                o.mostraCampos();
            }
        });
        /**/
    };

    o.excluirPredecessora = function (predecessora) {
        var idatividadepredecessora = null;
        idatividadepredecessora = predecessora;
        var
            idplanodeacao = $("input#idplanodeacao").val(),
            idatividade = $('#idatividadecronograma').val();
        $.ajax({
            url: base_url + '/planodeacao/cronograma/excluir-predecessora',
            dataType: 'json',
            type: 'POST',
            data: {
                idplanodeacao: idplanodeacao,
                idatividadepredecessora: idatividadepredecessora,
                idatividade: idatividade
            },
            success: function (data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                o.carregaPredecessoras(data.listaPredecessoras);
                /*var count = 0;
                $(o.selectPredecAtividade).empty();
                $.each(data.listaPredecessoras, function (i, val) {
                    if (i != "") {
                        $(o.selectPredecAtividade).append($('<option>').text(val).attr('value', i));
                        count++;
                    }
                });/**/
                $.pnotify(data.msg);
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
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

    o.retornarInicioFimRealizado = function () {
        $("#datfimbaseline").attr('readonly', 'readonly');
    };

    o.setDataLimites = function () {
        var datainicio = $("#datinicio"),
            datafim = $("#datfim"),
            dateMax = null,
            dateMin = null;
        if (datainicio.datepicker("getDate") != null) {
            if (verificaData(datainicio.val())) {
                dateMin = datainicio.datepicker("getDate");
                dateMax = null;
            }
        }
        //datafim.datepicker("option", "minDate", dateMin);
        //datafim.datepicker("option", "maxDate", dateMax);
        dateMax = null;
        dateMin = null;
        if (datafim.datepicker("getDate") != null) {
            if (verificaData(datafim.val())) {
                dateMin = null;
                dateMax = datafim.datepicker("getDate");
            }
        }
        //datainicio.datepicker("option", "minDate", dateMin);
        //datainicio.datepicker("option", "maxDate", dateMax);
    };

    o.customEvents = function () {
        $('body').on('adicionarPredecessora', function (event, select) {
            o.adicionarPredecessora(event, select);
        });

        $('body').on('calcularBaseLine', function () {
            o.calcularBaseLine();
        });
        $('body').on('retornarInicioFimRealizado', function () {
            o.retornarInicioFimRealizado();
        });

        $("body").on('atividadeAtualizarTipo', function (event) {
            var domtipoatividade = 4;
            if ($(o.itemCronogramaSelecionado).is(".item-marco")) {
                domtipoatividade = 3;
            }
            $.ajax({
                url: base_url + '/planodeacao/cronograma/atualizar-dom-tipo-atividade/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'domtipoatividade': domtipoatividade,
                    'idatividadecronograma': $(o.itemCronogramaSelecionado).val(),
                    'idplanodeacao': $("#idplanodeacao").val()
                },
                success: function (data) {
                    CRONOGRAMA.retornaPlanodeacao();
                    o.mostraCampos();
                    $.pnotify(data.msg);
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
        $("body").on('click', "a.btn-numseq-primeiro, a.btn-numseq-anterior, a.btn-numseq-proximo, a.btn-numseq-ultimo", function (event) {
            event.preventDefault();
            $this = $(this);
            acaoatividade = 0;
            var dados = $(o.itemCronogramaSelecionado).data('dados');
            var flgordenacao = dados.flaordenacao;
            if (flgordenacao == 'S') {
                $('a.btn-ordena').attr('disabled', true);
                if ($this.is(".btn-numseq-primeiro")) {
                    acaoatividade = 1;
                } else {
                    if ($this.is(".btn-numseq-anterior")) {
                        acaoatividade = 2;
                    } else {
                        if ($this.is(".btn-numseq-proximo")) {
                            acaoatividade = 3;
                        } else {
                            if ($this.is(".btn-numseq-ultimo")) {
                                acaoatividade = 4;
                            }
                        }
                    }
                }
                if (vOrdena) {
                    vOrdena = false;
                    $.ajax({
                        url: base_url + '/planodeacao/cronograma/ordenar-atividade/format/json',
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idplanodeacao': $("input#idplanodeacao").val(),
                            'idatividadecronograma': $(o.itemCronogramaSelecionado).val(),
                            'acaoatividade': acaoatividade,
                        },
                        success: function (data) {
                            CRONOGRAMA.retornaPlanodeacao();
                            o.mostraCampos();
                            $.pnotify(data.msg);
                            $('a.btn-ordena').attr('disabled', false);
                            vOrdena = true;
                        },
                        error: function () {
                            $('a.btn-ordena').attr('disabled', false);
                            vOrdena = true;
                            $.pnotify({
                                text: 'Falha ao enviar a requisição',
                                type: 'error',
                                hide: false
                            });
                        }
                    });
                }
            }
        });
        $("body").on('click', "a.btn-imprimir-atividade", function (event) {
            event.preventDefault();
            var idplanodeacao = $("#idplanodeacao").val();
            var idatividadecronograma = $(o.itemCronogramaSelecionado).val();
            var urlJanela = base_url + '/planodeacao/cronograma/imprimir-pdf';
            window.open(urlJanela + '/idplanodeacao/' + idplanodeacao + '/idatividadecronograma/' + idatividadecronograma);
        });
        $("body").on('click', "a.btn-clonar-atividade", function (event) {
            event.preventDefault();
            var dados = $(o.itemCronogramaSelecionado).data('dados');
            var idgrupo = dados.idgrupo;
            idplanodeacao = $('#idplanodeacao').val();
            $.ajax({
                url: base_url + '/planodeacao/cronograma/clonar-atividade/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'idplanodeacao': idplanodeacao,
                    'idatividadecronograma': $(o.itemCronogramaSelecionado).val(),
                    'idgrupo': idgrupo
                },
                success: function (data) {
                    $.pnotify(data.msg);
                    CRONOGRAMA.retornaPlanodeacao();
                    o.mostraCampos();
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
        $("body").on('click', "a.btn-cancelar-atividade", function (event) {
            var idplanodeacao = $("#idplanodeacao").val();
            var idatividadecronograma = $("input:checked").val();
            var domtipoatividade = $("input#domtipoatividade").val();
            $.ajax({
                url: base_url + '/planodeacao/cronograma/cancelar-atividade/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    idplanodeacao: idplanodeacao,
                    idatividadecronograma: idatividadecronograma,
                },
                success: function (data) {
                    $('#dialog-cancelar').dialog({
                        autoOpen: true,
                        title: 'Cronograma - Cancelar Atividade',
                        width: '800px',
                        modal: true,
                        buttons: {
                            'Cancelar Atividade': function () {
                                var param = "predecessora";
                                $.ajax({
                                    url: base_url + '/planodeacao/cronograma/cancelar-atividade/format/json/',
                                    dataType: 'json',
                                    type: 'post',
                                    data: {
                                        idplanodeacao: idplanodeacao,
                                        idatividadepredecessora: idatividadecronograma,
                                        idatividadecronograma: idatividadecronograma,
                                        params: param
                                    },
                                    success: function (data) {
                                        if (typeof data.msg.text !== 'string') {
                                            $.formErrors(data.msg.text);
                                            return;
                                        }
                                        $.pnotify(data.msg);
                                        CRONOGRAMA.retornaPlanodeacao();
                                        o.mostraCampos();
                                    },
                                    error: function () {
                                        $.pnotify({
                                            text: 'Falha ao enviar a requisição',
                                            type: 'error',
                                            hide: false
                                        });
                                        o.mostraCampos();
                                    },
                                });
                                $(this).dialog('close');
                            },
                            'Fechar': function () {
                                $(this).dialog('close');
                            }
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
        $("body").on('click', "a.btn-bloquear-ordenacao", function (event) {
            event.preventDefault();
            idplanodeacao = $('#idplanodeacao').val();
            idatividadecronograma = $(o.itemCronogramaSelecionado).val();
            var dados = $(o.itemCronogramaSelecionado).data('dados');
            var flagordenacao = dados.flaordenacao;
            if ((flagordenacao != 'S') && ((flagordenacao != 'N'))) {
                flagordenacao = 'S';
            } else {
                /*  INVERTE A SITUAÇÃO ATUAL  */
                flagordenacao = (flagordenacao == 'S' ? 'N' : 'S');
            }
            o.formAtividade = "form#ac-atividade-bloquear";
            var
                $this = $(this),
                urlForm = o.urls.bloquearAtiv,
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + idatividadecronograma + '/flagordenacao/' + flagordenacao;
            o.editMode = false;
            o.$dialogAtividade.dialog('option', 'title', 'Cronograma - Bloquear Ordenação');
            o.$dialogAtividade.dialog('option', 'width', '900px');

            $this.data('form', o.formBloquear), $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogAtividade);
            $this.data('idplanodeacao', idplanodeacao);
            $this.data('idatividadecronograma', idatividadecronograma);
            $this.data('flagordenacao', flagordenacao);

            $("body").trigger('openDialog', [$this]);
        });
        $("body").on('click', ".btn-predecessora-move", function (event) {
            event.preventDefault();
            var at = {};
            $this = $(this);
            var idselect = $this.attr('id');
            if (idselect == "predecessora-in") {
                var indselect = $(o.selectPredecessora).find('option:selected').index();
                if (indselect >= 0) {
                    $(o.selectPredecessora + ' :selected').each(function (i, selected) {
                        at.idatividadecronograma = $(selected).val();
                        o.cadastrarPredecessora(at);
                    });
                }
            }
            if (idselect == "predecessora-out") {
                var indselect = $(o.selectPredecAtividade).find('option:selected').index();
                if (indselect >= 0) {
                    $(o.selectPredecAtividade + ' :selected').each(function (i, selected) {
                        idatividadePredecessora = $(selected).val();
                        o.removerPredecessora(idatividadePredecessora);
                    });
                }
            }
        });
    };

    o.events = function () {
        $("body").on("click", ".btn-tranformar-marco, .btn-tranformar-atividade", function (event) {
            $("body").trigger("atividadeAtualizarTipo");
            event.preventDefault();
        });

        $("#e_datinicio, #e_datfim, #inicial_dti, #inicial_dtf, #final_dti, #final_dtf").mask('99/99/9999');

        $("body").on('click', "a.btn-cadastrar-atividade", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlForm = o.urls.cadastrar,
                urlAjax = $this.attr('href')
                    + '/idgrupo/' + $(o.itemCronogramaSelecionado).val()
            ;
            o.editMode = false;
            o.$dialogAtividade.dialog('option', 'title', 'Cronograma - Cadastrar Atividade');

            $this.data('form', o.formAtividade),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogAtividade);
            $this.data('prefixo', '#at');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-editar-atividade", function (event) {
            event.preventDefault();
            var dados = $(o.itemCronogramaSelecionado).data('dados');
            var
                $this = $(this),
                urlForm = o.urls.editar,
                urlAjax = $this.attr('href')
                    + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val()
                    + '/idgrupo/' + dados.idgrupo
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
            $("body").trigger('adicionarPredecessora', [$this]);
            o.habilitarFolgas();
        });

        $("body").on('dblclick', 'select#predecessorasAtividade', function (event) {
            event.preventDefault();
            var $this = $(this);
            idatividadePredecessora = $this.find('option:selected').val();
            o.removerPredecessora(idatividadePredecessora);
            o.habilitarFolgas();
        });

        $("body").delegate("#idgrupo", "focusin", function () {
            o.setDataLimites();
        });

        $("body").delegate("#datiniciobaseline", "focusin", function () {
            var $this = $(this);
            $(this).mask('99/99/9999');
            $this.datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                onSelect: function (dateText, inst) {
                    var input = $this.data('input');
                    $(input).val(dateText);
                },
                beforeShow: function (selectedDate, inst) {
                    var val = null;
                    if ($('#datinicio').length > 0) {
                        val = $('#datinicio').val();
                        //$(this).datepicker("option", "minDate", val);
                    }
                }
            });
        });

        $("body").delegate("#numfolga", "focusout", function () {
            //console.log('numfolga --- focusout');
            //console.log('maior_valor: ' +  $("#maior_valor").val());
            //console.log('maior_valor: ' +  $("#maior_valor").val());
            $("#datinicio").val($("#maior_valor").val());
            o.calcularDias($("#datinicio"), 'inicio');
            $("#datfim").val($("#datinicio").val());
            o.calcularDias($("#datfim"), 'fim');
            $("#datInicioHidden").removeAttr('value');
            $("#datInicioHidden").attr('value', $("#datinicio").val());
        });

        $("body").on("focusout", "#vlratividadebaseline", function () {
            //$this = $(this);
            //$("#vlratividade").val($this.val());
        });
        //==========================================================================
        /// calculando o periodo de aula para inserir na data fim
        $("body").on("focusout", "#numdiasbaseline", function (e) {
            // Convertendo a data do brasil para americana
            var datiniciobaseline = $("#datiniciobaseline").val();
            var splitdataini = datiniciobaseline.split('/');
            var retonraDatAmer = splitdataini[2] + '-' + splitdataini[1] + '-' + splitdataini[0];
            var QuantidadeDias = $("#numdiasbaseline").val();
            // se a quantidade tiver vazia data fim recebe vazio
            if (QuantidadeDias == '') {
                $("#datfimbaseline").val('');
                $("#datfim").val('');
            } else {
                if ((parseInt(QuantidadeDias) >= 0) && (parseInt(splitdataini[0]) > 0) && (parseInt(splitdataini[1]) > 0) && (parseInt(splitdataini[2]) > 0)) {
                    if (Date.parse(retonraDatAmer)) {
                        var DataAtual = new Date(retonraDatAmer);
                        // Calculando a data com os dias do curso
                        var a = new Date(retonraDatAmer);
                        // campo com apenas leitura
                        // Calculando a data com os dias inseridos e preenchendo na data fim
                        //if (e.which == 13) {
                        if (parseInt(QuantidadeDias) > 0) {
                            $("#datfimbaseline").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1 + parseInt(QuantidadeDias))
                            ).toString("dd/MM/yyyy"));
                            $("#datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1 + parseInt(QuantidadeDias))
                            ).toString("dd/MM/yyyy"));
                        } else {
                            $("#datfimbaseline").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1)
                            ).toString("dd/MM/yyyy"));
                            $("#datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1)
                            ).toString("dd/MM/yyyy"));
                        }
                        //}

                    }
                }
            }
        });

        //=========================================================================

        /// calculando o periodo de aula para inserir na data fim realizado
        $("body").on("focusout", "#e_numdiasrealizados", function (e) {
            // Convertendo a data do brasil para americana
            var datiniciobaseline = $("#e_datinicio").val();
            var splitdataini = datiniciobaseline.split('/');
            var retonraDatAmer = splitdataini[2] + '-' + splitdataini[1] + '-' + splitdataini[0];
            var QuantidadeDias = $("#e_numdiasrealizados").val();
            // Calculando a data com os dias do curso
            // campo com apenas leitura
            // Calculando a data com os dias inseridos e preenchendo na data fim
            //if (e.which == 13) {
            if (QuantidadeDias == '') {
                $("#e_datfim").val('');
                $("#e_numdiasrealizados").focus();
            } else {
                if ((parseInt(QuantidadeDias) >= 0) && (parseInt(splitdataini[0]) > 0) && (parseInt(splitdataini[1]) > 0) && (parseInt(splitdataini[2]) > 0)) {
                    if (Date.parse(retonraDatAmer)) {
                        var DataAtual = new Date(retonraDatAmer);
                        var a = new Date(retonraDatAmer);
                        if (parseInt(QuantidadeDias) > 0) {
                            $("#e_datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1 + parseInt(QuantidadeDias))
                            ).toString("dd/MM/yyyy"));
                        } else {
                            $("#e_datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1)
                            ).toString("dd/MM/yyyy"));
                        }
                    }
                }
            }
        });

        //========================================================================

        // digitando apenas numeros no campo quantidade
        $("body").on("keypress", "#numdiasbaseline", function (e) {
            var tecla = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
            if ((tecla > 47 && tecla < 58)) return true;
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
            if (((tecla > 47 && tecla < 58) || (tecla == 46) || (tecla == 8))) return true;
            else {
                if ((tecla != 8) && (tecla != 46)) {
                    $("#numdiasrealizados").attr('title', 'Digite apenas números');
                    return false;
                } else return true;
            }
        });
        /////fim digitando apenas numeros no campo quantidade//////////////////

        // calculando o tempo BASELINE de curso com a data ao pressionar enter
        $("body").on("keypress", "#numdiasbaseline", function (e) {
            // Convertendo a data do brasil para americana
            var datiniciobaseline = $("#datiniciobaseline").val();
            var splitdataini = datiniciobaseline.split('/');
            var retonraDatAmer = splitdataini[2] + '-' + splitdataini[1] + '-' + splitdataini[0];
            var QuantidadeDias = $("#numdiasbaseline").val();
            // se a quantidade tiver vazia data fim recebe vazio
            if (QuantidadeDias == '') {
                $("#datfimbaseline").val('');
                $("#datfim").val('');
            } else {
                if ((parseInt(QuantidadeDias) >= 0) && (parseInt(splitdataini[0]) > 0) && (parseInt(splitdataini[1]) > 0) && (parseInt(splitdataini[2]) > 0)) {
                    if (Date.parse(retonraDatAmer)) {
                        var DataAtual = new Date(retonraDatAmer);
                        // Calculando a data com os dias do curso
                        var a = new Date(retonraDatAmer);
                        // Calculando a data com os dias inseridos e preenchendo na data fim
                        if (e.which == 13) {
                            // campo com apenas leitura
                            if (parseInt(QuantidadeDias) > 0) {
                                $("#datfimbaseline").val((
                                    new Date(
                                        a.getFullYear(),
                                        a.getMonth(),
                                        a.getDate() + 1 + parseInt(QuantidadeDias))
                                ).toString("dd/MM/yyyy"));
                                $("#datfim").val((
                                    new Date(
                                        a.getFullYear(),
                                        a.getMonth(),
                                        a.getDate() + 1 + parseInt(QuantidadeDias))
                                ).toString("dd/MM/yyyy"));
                            } else {
                                $("#datfimbaseline").val((
                                    new Date(
                                        a.getFullYear(),
                                        a.getMonth(),
                                        a.getDate() + 1)
                                ).toString("dd/MM/yyyy"));
                                $("#datfim").val((
                                    new Date(
                                        a.getFullYear(),
                                        a.getMonth(),
                                        a.getDate() + 1)
                                ).toString("dd/MM/yyyy"));
                            }
                        }
                    }
                }
            }
        });
        ///////////////////////////// fim calculo ////////////////////////
        // calculando o tempo REALIZADO de curso com a data ao pressionar enter
        $("body").on("keypress", "#numdiasrealizados", function (e) {
            // Convertendo a data do brasil para americana
            var datinicio = $("#datinicio").val();
            var splitdataini = datinicio.split('/');
            var retonraDatAmer = splitdataini[2] + '-' + splitdataini[1] + '-' + splitdataini[0];
            var QuantidadeDias = $("#numdiasrealizados").val();
            // se a quantidade tiver vazia data fim recebe vazio
            if (QuantidadeDias == '') {
                $("#datfim").val('');
            } else {
                if ((parseInt(QuantidadeDias) >= 0) && (parseInt(splitdataini[0]) > 0) && (parseInt(splitdataini[1]) > 0) && (parseInt(splitdataini[2]) > 0)) {
                    if (Date.parse(retonraDatAmer)) {
                        var DataAtual = new Date(retonraDatAmer);
                        // Calculando a data com os dias do curso
                        var a = new Date(retonraDatAmer);
                        // Calculando a data com os dias inseridos e preenchendo na data fim
                        if (e.which == 13) {
                            // campo com apenas leitura
                            if (parseInt(QuantidadeDias) > 0) {
                                $("#datfim").val((
                                    new Date(
                                        a.getFullYear(),
                                        a.getMonth(),
                                        a.getDate() + 1 + parseInt(QuantidadeDias))
                                ).toString("dd/MM/yyyy"));
                            } else {
                                $("#datfim").val((
                                    new Date(
                                        a.getFullYear(),
                                        a.getMonth(),
                                        a.getDate() + 1)
                                ).toString("dd/MM/yyyy"));
                            }
                        }
                    }
                }
            }
        });
        ///////////////////////////// fim calculo ////////////////////////
        // calculando o tempo REALIZADO de curso com a data ao clicar fora
        $("body").on("focusout", "#numdiasrealizados", function (e) {
            // Convertendo a data do brasil para americana
            var datinicio = $("#datinicio").val();
            var splitdataini = datinicio.split('/');
            var retonraDatAmer = splitdataini[2] + '-' + splitdataini[1] + '-' + splitdataini[0];
            var QuantidadeDias = $("#numdiasrealizados").val();
            // se a quantidade tiver vazia data fim recebe vazio
            if (QuantidadeDias == '') {
                $("#datfim").val('');
            } else {
                if ((parseInt(QuantidadeDias) >= 0) && (parseInt(splitdataini[0]) > 0) && (parseInt(splitdataini[1]) > 0) && (parseInt(splitdataini[2]) > 0)) {
                    if (Date.parse(retonraDatAmer)) {
                        var DataAtual = new Date(retonraDatAmer);
                        // Calculando a data com os dias do curso
                        var a = new Date(retonraDatAmer);
                        // Calculando a data com os dias inseridos e preenchendo na data fim
                        // campo com apenas leitura
                        if (parseInt(QuantidadeDias) > 0) {
                            $("#datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1 + parseInt(QuantidadeDias))
                            ).toString("dd/MM/yyyy"));
                        } else {
                            $("#datfim").val((
                                new Date(
                                    a.getFullYear(),
                                    a.getMonth(),
                                    a.getDate() + 1)
                            ).toString("dd/MM/yyyy"));
                        }
                    }
                }
            }
        });
        ///////////////////////////// fim calculo ////////////////////////

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
            //99999 o.existePredecessora();
            var dataInicio = $('#datinicio').val();
            $('#datInicioHidden').removeAttr('value');
            $('#datInicioHidden').attr('value', dataInicio);
        });

        $("body").on("focusout", "#datinicio", function () {
            o.existePredecessora();
            var dataInicio = $('#datinicio').val();
            $('#datInicioHidden').removeAttr('value');
            $('#datInicioHidden').attr('value', dataInicio);
        });

        // Calcular o qtd dia pela fim e data inicio realizado///////////////
        $("body").on("focusout", "#e_datfim", function () {
            var datfim = $("#e_datfim").val();
            var splitdataFim = datfim.split('/');
            var datinicio = $("#e_datinicio").val();
            var splitdataIni = datinicio.split('/');
            if ((parseInt(splitdataFim[0]) > 0) && (parseInt(splitdataFim[1]) > 0) && (parseInt(splitdataFim[2]) > 0) &&
                (parseInt(splitdataIni[0]) > 0) && (parseInt(splitdataIni[1]) > 0) && (parseInt(splitdataIni[2]) > 0)) {
                if ((Date.parse(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0])) &&
                    (Date.parse(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]))) {
                    var retonraDatFimAmer = new Date(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0]);
                    var retonraDatIniAmer = new Date(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]);
                    var resultadoTotal = ((Date.UTC((retonraDatFimAmer.getYear()), retonraDatFimAmer.getMonth(), retonraDatFimAmer.getDate(), 0, 0, 0)
                        - Date.UTC((retonraDatIniAmer.getYear()), retonraDatIniAmer.getMonth(), retonraDatIniAmer.getDate(), 0, 0, 0)) / 86400000);
                    //alert(resultadoTotal);
                    //$("#e_numdiasrealizados").val(resultadoTotal);
                }
            }
        });

        // Calcular o qtd dia pela fim e data inicio realizado///////////////
        $("body").on("click", "#calcDias", function () {
            var datfim = $("#datfim").val();
            var splitdataFim = datfim.split('/');
            var datinicio = $("#datinicio").val();
            var splitdataIni = datinicio.split('/');
            if ((parseInt(splitdataFim[0]) > 0) && (parseInt(splitdataFim[1]) > 0) && (parseInt(splitdataFim[2]) > 0) &&
                (parseInt(splitdataIni[0]) > 0) && (parseInt(splitdataIni[1]) > 0) && (parseInt(splitdataIni[2]) > 0)) {
                if ((Date.parse(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0])) &&
                    (Date.parse(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]))) {
                    var retonraDatFimAmer = new Date(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0]);
                    var retonraDatIniAmer = new Date(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]);
                    var resultadoTotal = ((Date.UTC((retonraDatFimAmer.getYear()), retonraDatFimAmer.getMonth(), retonraDatFimAmer.getDate(), 0, 0, 0)
                        - Date.UTC((retonraDatIniAmer.getYear()), retonraDatIniAmer.getMonth(), retonraDatIniAmer.getDate(), 0, 0, 0)) / 86400000);
                    $("#numdiasrealizados").val(resultadoTotal);
                }
            }
        });

        $("#e_datfim, #e_datinicio").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            onSelect: function (selectedDate, inst) {
                if (($("#e_datinicio").datepicker("getDate") != null) && ($("#e_datfim").datepicker("getDate") != null)) {
                    var days = calculaDiasEntreDatas($("#e_datinicio").val(), $("#e_datfim").val());
                    if (days >= 0) {
                        $("#e_numdiasrealizados").val(days);
                    } else {
                        id = $(this).attr('id');
                        if (id == "e_datinicio") {
                            $("#e_datinicio").val($("#e_datfim").val());
                        } else {
                            $("#e_datfim").val($("#e_datinicio").val());
                        }
                        $("#e_numdiasrealizados").val(0);
                    }
                }
            }
        });

        // FIM Calcular o qtd dia pela fim e data inicio realizado//////////////

        $("#inicial_dti, #inicial_dtf").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            beforeShow: checkPeriodoInicial
        });

        $("#final_dti, #final_dtf").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            beforeShow: checkPeriodoFinal
        });

        jQuery.validator.addMethod("campoinformado", function (value, element) {
            var textocampo = $(element).val().trim();
            if (textocampo == '') {
                return false;  // FAIL validation when REGEX matches
            } else {
                return true;   // PASS validation otherwise
            }
            ;
        }, "Este campo deve ser informado.");

        $("body").on('click', "form#frminternoat #adicionarinterno", function (event) {
            var nomeitem = $('#nomparte').val();
            var iditem = $('#idparte').val();
            var idplanodeacao = $('#idplanodeacao').val();
            var $forminterno = $("form#frminternoat");
            $forminterno.validate({
                errorClass: 'error',
                validClass: 'success',
                rules: {
                    nomparte: {
                        campoinformado: true
                    }
                }
            });
            if ($forminterno.valid()) {
                $.ajax({
                    url: base_url + '/planodeacao/tpa/addparteinterno/format/json',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        'idplanodeacao': idplanodeacao,
                        'nomparteinteressada': nomeitem,
                        'idparteinteressada': iditem,
                        'domnivelinfluenciaexterno': 'Baixo',
                    },
                    success: function (data) {
                        $.pnotify(data.msg);
                        o.listapartesinteressadas();
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
        });

        $("body").on('click', "form#frmexternoat #adicionarexterno", function (event) {
            var nomeitem = $('#nomparteexterno').val();
            var emailparte = $('#emailparte').val();
            var telefoneparte = $('#telefoneparte').val();
            var idplanodeacao = $('#idplanodeacao').val();
            var $formexterno = $("form#frmexternoat");
            $formexterno.validate({
                errorClass: 'error',
                validClass: 'success',
                rules: {
                    nomparteexterno: {
                        campoinformado: true
                    },
                    emailparte: {
                        campoinformado: true
                    },
                    telefoneparte: {
                        campoinformado: true
                    }
                }
            });
            if ($formexterno.valid()) {
                $.ajax({
                    url: base_url + '/planodeacao/tpa/addparteexterno/format/json',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        'idplanodeacao': idplanodeacao,
                        'nomparteinteressadaexterno': nomeitem,
                        'desemailexterno': emailparte,
                        'destelefoneexterno': telefoneparte,
                        'domnivelinfluenciaexterno': 'Baixo',
                    },
                    success: function (data) {
                        $.pnotify(data.msg);
                        o.listapartesinteressadas();
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
        /**/

    }
    setDataInicial = function (input) {
        var dateMin = null,
            dateMax = null;
        /*
        if (input.id === "inicial_dti") {
            if ($("#inicial_dtf").datepicker("getDate") != null) {
                if (verificaData($("#inicial_dtf").val())) {
                    dateMax = $("#inicial_dtf").datepicker("getDate");
                    dateMin = null;
                }
                else {
                    dateMax = null;
                    dateMin = null;
                }
            }
            else {
                dateMax = null;
                dateMin = null;
            }
        }
        else if (input.id === "inicial_dtf") {
            dateMax = new Date;
            dateMin = null;
            if ($("#inicial_dti").datepicker("getDate") != null) {
                if (verificaData($("#inicial_dti").val())) {
                    dateMin = $("#inicial_dti").datepicker("getDate");
                    dateMax = null;
                }
                else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            }
            else {
                dateMax = null; //Set this to your absolute maximum date
                dateMin = null;
            }
        }
        return {
            minDate: dateMin,
            maxDate: dateMax
        };
        / **/

    }

    verificaData = function (vrData) {
        if (vrData == "") {
            return false;
        } else {
            var splitdataTmp = vrData.split('/');
            var retonraDatAmerTmp = splitdataTmp[2] + '-' + splitdataTmp[1] + '-' + splitdataTmp[0];
            if (Date.parse(retonraDatAmerTmp)) {
                return true;
            }
            return false
        }
    }

    calculaDiasEntreDatas = function (dataini, datafim) {
        var datinicio = dataini;
        var datfim = datafim;
        if ((verificaData(datinicio)) && (verificaData(datfim))) {
            var splitdataIni = datinicio.split('/');
            var splitdataFim = datfim.split('/');
            if ((parseInt(splitdataFim[0]) > 0) && (parseInt(splitdataFim[1]) > 0) && (parseInt(splitdataFim[2]) > 0) &&
                (parseInt(splitdataIni[0]) > 0) && (parseInt(splitdataIni[1]) > 0) && (parseInt(splitdataIni[2]) > 0)) {
                if ((Date.parse(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0])) &&
                    (Date.parse(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]))) {
                    var retonraDatFimAmer = new Date(splitdataFim[2] + '-' + splitdataFim[1] + '-' + splitdataFim[0]);
                    var retonraDatIniAmer = new Date(splitdataIni[2] + '-' + splitdataIni[1] + '-' + splitdataIni[0]);
                    var resultadoTotal = ((Date.UTC((retonraDatFimAmer.getYear()), retonraDatFimAmer.getMonth(), retonraDatFimAmer.getDate(), 0, 0, 0)
                        - Date.UTC((retonraDatIniAmer.getYear()), retonraDatIniAmer.getMonth(), retonraDatIniAmer.getDate(), 0, 0, 0)) / 86400000);
                    return resultadoTotal;
                }
            }
        } else return 0;
    }

    o.init = function () {
        o.compilarTemplates();
        o.initDialogs();
        o.customEvents();
        o.events();
        o.retornarInicioFimRealizado();
    };

    return o;
}(jQuery, Handlebars, Intervalo));
