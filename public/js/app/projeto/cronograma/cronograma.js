var CRONOGRAMA = (function ($, Intervalo) {
    var
        cron = {};

    cron.projeto = {};
    cron.tplProjeto = null;
    cron.tplGrupo = null;
    cron.tplEntrega = null;
    cron.tplAtividade = null;
    cron.tplMarco = null;
    cron.msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
    cron.msgeacessonegado = 'Acesso Negado.';
    cron.checkIconeCronograma = 'i.icon-pencil';
    cron.checkItemCronograma = 'input.input-item-cronograma';
    cron.allToolBar = '.btn-group-atividade, .btn-group-grupo, .btn-group-entrega';
    cron.allButtonsToolBar = '.btn-group-atividade a, .btn-group-grupo a, .btn-group-entrega a, .btn-cadastrar-grupo, .btn-group-cronograma a, .btn-group-ferramentas button';
    cron.itemativo = null;
    cron.itemChecado = null;
    cron.itemChkPrecedessora = null;
    cron.existeChecado = false;
    cron.atualizaNumDias = false;
    cron.idprojeto = $('#idprojeto').val();
    cron.$dialogAtualizarBaseline = null;
    cron.formAtualizarBaseline = "form#atualizar-baseline",
        cron.nav = [], cron.DatasFeriados = [];
    cron.altura = {
        doc: 0,
        norte: 0,
        sul: 0,
        acordion: 0,
        acordionPadding: 20
    };
    cron.DatasFeriados = ["01/01", "21/04", "01/05", "07/09", "12/10", "02/11", "15/11", "25/12"];
    cron.calcularAlturaCronograma = function () {
        var retirar = 150;
        if ($('.ui-layout-north').is(':visible')) {
            retirar += $('.ui-layout-north').height();
        }
        if ($('.ui-layout-south').is(':visible')) {
            retirar += $('.ui-layout-south').height();
        }
        if ($('#collapseOne').height() !== 0) {
            retirar += $('.accordion-inner').height() + cron.altura.acordionPadding;
        }
        //$('.container-grupo').height($('.region-west').height() - 150);

        $('.bodycontainer').css('max-height', $('.region-west').height() - 190);
    };
    var largura_ant = 0;
    cron.calcularLarguraCronograma = function () {
        var largura = $('.region-center').width(),
            retirar = 58;
        //console.log(largura);
        //$('.container-grupo').width(largura);
        //$('#cronograma-titulos').width(largura);
        //$('.container-grupo-cabecalho').width(largura);
        //$('.item-cronograma').width(largura);
        //$('.container-entrega').width(largura);

        //console.log(largura_ant);
        if (largura != largura_ant && largura_ant > 0) {
            $('.ui-layout-center').load(location.href + ".region-center>*", "");
            largura_ant = largura;
        }
    }

    cron.customEvents = function () {

        $('body').on('mostrarFerramentas', function (event, chk) {
            cron.itemChecado = 'input.input-item-cronograma[value="' + chk.val() + '"]';
            var grupo = chk.data('group');
            $('.item-cronograma').removeClass('success');
            $(cron.itemChecado).closest('.item-cronograma').addClass('success');
            $(cron.allToolBar).hide();
            if (chk.is(":checked")) {
                $('' + grupo).show();
            }

            if (grupo == '.btn-group-atividade') {
                var dados = chk.data('text'),
                    arrayDados = dados.replace("{", "").replace("}", "").split(',');

                var numpercentualconcluido = arrayDados[0],
                    datinicio = arrayDados[1],
                    datfim = arrayDados[2],
                    numfolga = arrayDados[3],
                    domtipoatividade = arrayDados[4],
                    idgrupo = arrayDados[5],
                    numdiasrealizados = arrayDados[6];

                //var aux = dados.numpercentualconcluido.split('.');
                //dados.numpercentualconcluido = aux[0];
                //var i = dados.datfim.substring(10).split('E(');
                var folga = numfolga;
                /*if (cron.itemChkPrecedessora != chk.val()) {
                 cron.verificaExistePredecessora(chk.val(), $("#e_datinicio"), folga[0], dados.numdiasrealizados);
                 }/**/
                //if ((dados.predecessora == "1")||(dados.predecessora == 1)) {
                //    $("#e_datinicio").attr({disabled: "disabled"});
                //    $("#e_datinicio").attr('readonly', true);
                //} else if ((dados.predecessora == null)||(dados.predecessora == "0")||(dados.predecessora == 0)) {
                //    $("#e_datinicio").removeAttr('disabled');
                //    $("#e_datinicio").attr('readonly', false);
                //}
                $("#e_datinicio").val(datinicio);
                $("#e_datfim").val(datfim);
                $("#e_numdiasrealizados").val(numdiasrealizados);
                $("#e_domtipoatividade").val(domtipoatividade);
                $("#e_idatividadecronograma").val(chk.val());
                $("#e_idgrupo").val(idgrupo);
                $("#e_numfolga").val(folga);
                $("a.btn-tranformar-marco, a.btn-tranformar-atividade").hide();
                if (chk.is('.item-marco')) {
                    $("a.btn-tranformar-atividade").show();
                    $("#e_datfim").val($("#e_datinicio").val());
                    $("#e_numdiasrealizados").val(0);
                    $("#e_datfim").attr({readonly: "true"});
                    $("#e_datfim").attr('disabled', 'disabled');
                    $("#e_numdiasrealizados").attr({readonly: "true"});
                    $("#e_numdiasrealizados").attr('disabled', 'disabled');
                    CRONOGRAMA.carregaSelectPercentualconcluido(4, $("#e_numpercentualconcluido"));
                } else {
                    $("a.btn-tranformar-marco").show();
                    $("#e_numdiasrealizados").removeAttr('disabled');
                    $("#e_numdiasrealizados").attr('readonly', false);
                    $("#e_datfim").removeAttr('disabled');
                    $("#e_datfim").attr('readonly', false);
                    CRONOGRAMA.carregaSelectPercentualconcluido(3, $("#e_numpercentualconcluido"));
                }
                $("#e_numpercentualconcluido").val(numpercentualconcluido);
                $(CRONOGRAMA.atividade.formPercentual).validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        // Aguarda a atualização do dias para submeter o form
                        if (CRONOGRAMA.atualizaNumDias) {
                            setTimeout(function () {
                                CRONOGRAMA.atualizaNumDias = false;
                                $.ajax({
                                    url: base_url + '/projeto/cronograma/atividade-atualizar-percentual/format/json',
                                    dataType: 'json',
                                    type: 'POST',
                                    data: {
                                        'datinicio': $("#e_datinicio").val(),
                                        'datfim': $("#e_datfim").val(),
                                        'numpercentualconcluido': $("#e_numpercentualconcluido option:checked").val(),
                                        'idatividadecronograma': $("#e_idatividadecronograma").val(),
                                        'domtipoatividade': $("#e_domtipoatividade").val(),
                                        'numdiasrealizados': $("#e_numdiasrealizados").val(),
                                        'numfolga': $("#e_numfolga").val(),
                                        'idprojeto': $("#idprojeto").val(),
                                        'idgrupo': $("#e_idgrupo").val()
                                    },
                                    success: function (data) {
                                        //CRONOGRAMA.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';                                        
                                        //CRONOGRAMA.retornaProjeto();
                                        //cron.renderProjeto();
                                        $.pnotify(data.msg);
                                    },
                                    error: function () {
                                        $.pnotify({
                                            text: cron.msgeacessonegado,
                                            type: 'error',
                                            hide: false
                                        });
                                    }
                                });

                            }, 2000);
                        } else {
                            $.ajax({
                                url: base_url + '/projeto/cronograma/atividade-atualizar-percentual/format/json',
                                dataType: 'json',
                                type: 'POST',
                                data: {
                                    'datinicio': $("#e_datinicio").val(),
                                    'datfim': $("#e_datfim").val(),
                                    'numpercentualconcluido': $("#e_numpercentualconcluido option:checked").val(),
                                    'idatividadecronograma': $("#e_idatividadecronograma").val(),
                                    'domtipoatividade': $("#e_domtipoatividade").val(),
                                    'numdiasrealizados': $("#e_numdiasrealizados").val(),
                                    'numfolga': $("#e_numfolga").val(),
                                    'idprojeto': $("#idprojeto").val(),
                                    'idgrupo': $("#e_idgrupo").val()
                                },
                                success: function (data) {
                                    //CRONOGRAMA.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';
                                    //CRONOGRAMA.retornaProjeto();
                                    //cron.renderProjeto();
                                    $.pnotify(data.msg);
                                },
                                error: function () {
                                    $.pnotify({
                                        text: cron.msgeacessonegado,
                                        type: 'error',
                                        hide: false
                                    });
                                }
                            });
                        }
                    }
                });

            }
        });

        $("body").on('openDialog', function (event, btn) {
            var dialog = btn.data('dialog'),
                formAtual = btn.data('form'),
                urlAjax = btn.data('urlajax'),
                urlForm = btn.data('urlform'),
                prefixo = btn.data('prefixo');

            $.ajax({
                url: urlAjax,
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                //data: $formEditar.serialize(),
                processData: false,
                success: function (data) {
                    dialog.html(data).dialog('open');
                    $("#flainformatica").trigger('change');
                    CRONOGRAMA.atividade.habilitarFolgas();
                    $(formAtual).validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function (form) {
                            enviar_ajax(urlForm, formAtual, function (data) {
                                if (data != null) {
                                    if (data.item) {
                                        cron.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';
                                    }
                                    if (data.success == true) {
                                        cron.renderAtividadeCronograma(data);
                                    }
                                }
                                dialog.dialog('close');
                            });
                        }
                    });
                },
                error: function () {
                    $.pnotify({
                        text: cron.msgeacessonegado,
                        type: 'error',
                        hide: false
                    });
                }
            });
        });
        $("body").on('click', "a.btn-atualizar-cronograma", function (event) {
            event.preventDefault();
            cron.atualizarCronograma();
        });


        $("body").on('click', "a.btn-atualizar-baseline", function (event) {
            event.preventDefault();

            var id = $(this).attr('id');
            if (id === 'disabled') {
                alert("A base line desse projeto não pode ser atualizado porque o projeto já foi assinado.");
                return false;
            }

            var
                $this = $(this),
                urlForm = '/projeto/cronograma/atualizar-baseline/format/json',
                urlAjax = $this.attr('href');

            cron.$dialogAtualizarBaseline.dialog('option', 'title', 'Cronograma - Atualizar Base Line');
            $this.data('form', cron.formAtualizarBaseline),
                $this.data('urlajax', urlAjax);
            $this.data('urlform', urlForm);
            $this.data('dialog', cron.$dialogAtualizarBaseline);
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on("fitrarCronograma", function () {
            $.ajax({
                url: base_url + '/projeto/cronograma/pesquisar',
                dataType: 'json',
                type: 'POST',
                data: $('form#ac_atividade_pesquisar').serialize(),
                //processData:false,
                success: function (data) {
                    $('.grupo, .entrega').hide();
                    $("input.input-item-cronograma", ".container-atividade").closest('.item-cronograma').hide();
                    $.each(data, function (i, val) {
                        if (val.domtipoatividade == '2') {
                            $(".container-entrega  > #en" + val.idatividadecronograma).closest('.grupo').show();
                            $(".container-entrega  > #en" + val.idatividadecronograma).show();
                        } else {
                            $("#at" + val.idatividadecronograma).closest('.grupo').show();
                            $("#at" + val.idatividadecronograma).closest('.entrega').show();
                            $("input.input-item-cronograma[value=" + val.idatividadecronograma + "]").closest('.item-cronograma').show();
                        }
                    });
                },
                error: function () {
                    $.pnotify({
                        text: cron.msgeacessonegado,
                        type: 'error',
                        hide: false
                    });
                }
            });
        });
    };

    cron.verificaExistePredecessora = function (atividade, objAtividade, folga, numdiareal) {
        cron.itemChkPrecedessora = atividade;
        var idprojeto = $("input#idprojeto").val(),
            idatividadecronograma = atividade;

        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-predecessora',
            dataType: 'json',
            type: 'POST',
            async: false,
            cache: true,
            data: {
                idprojeto: idprojeto,
                idatividadecronograma: idatividadecronograma
            },
            success: function (data) {
                if (data != null) {
                    $("#e_datinicio").attr({disabled: "disabled"});
                    $("#e_datinicio").attr('readonly', true);
                } else if (data == null) {
                    $("#e_datinicio").removeAttr('disabled');
                    $("#e_datinicio").attr('readonly', false);
                }
            },
            error: function () {
                $.pnotify({
                    text: cron.msgeacessonegado,
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    cron.atualizarCronograma = function () {
        var idprojeto = $("input#idprojeto").val();
        $.ajax({
            url: base_url + '/projeto/cronograma/atualizar-cronograma/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            cache: true,
            data: {
                idprojeto: idprojeto
            },
            //processData: false,
            success: function (data) {

                if (data.success == true) {
                    $.pnotify({
                        text: data.msg.text,
                        type: 'success',
                        hide: true
                    });
                }
                if (data.error == true) {
                    $.pnotify({
                        text: data.msg.text,
                        type: 'error',
                        hide: false
                    });
                }
            },
            error: function () {
                $.pnotify({
                    text: cron.msgeacessonegado,
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    cron.calcDiasUteis = function (start, end) {
        // This makes no effort to account for holidays
        // Counts end day, does not count start day
        var startDatTmp = start.split('/');
        var startDat = startDatTmp[2] + '-' + startDatTmp[1] + '-' + startDatTmp[0];
        var endDatTmp = end.split('/');
        var endDat = endDatTmp[2] + '-' + endDatTmp[1] + '-' + endDatTmp[0];
        // make copies we can normalize without changing passed in objects
        var start = new Date(startDat);
        var end = new Date(endDat);
        // initial total
        var totalBusinessDays = 0;
        var totalFeriadosDays = 0;
        var totalWeekendDays = 0;
        // normalize both start and end to beginning of the day
        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);
        var current = new Date(start);
        current.setDate(current.getDate() + 1);
        // loop through each day, checking
        while (current <= end) {
            if (cron.dataFeriado(current)) {
                ++totalFeriadosDays;
            } else {
                if (cron.dataDiaUtil(current)) {
                    ++totalBusinessDays;
                } else {
                    ++totalWeekendDays;
                }
            }
            current.setDate(current.getDate() + 1);
        }
        var resultado = {
            diasFeriados: totalFeriadosDays,
            diasUteis: (totalBusinessDays == 0 ? 1 : totalBusinessDays + 1),
            diasFinadeSemana: totalWeekendDays
        };
        return (totalBusinessDays == 0 ? 1 : totalBusinessDays + 1);
    }
    cron.dataDiaUtil = function (currentdate) {
        day = currentdate.getDay();
        //console.log(day);
        if (day >= 1 && day <= 5) {
            return true;
        }
        return false;
    }
    cron.dataFeriado = function (currentdate) {
        var splitdatapre = currentdate.split('/');
        var retornaDataAmerica = splitdatapre[2] + '/' + splitdatapre[1] + '/' + splitdatapre[0];
        var dtamericaatividade = new Date(retornaDataAmerica);
        dtamericaatividade.setHours(0, 0, 0, 0);
        var currentdate = new Date(dtamericaatividade);

        var dd = (currentdate.getDate() < 10 ? '0' : '') + currentdate.getDate();
        var mm = ((currentdate.getMonth() + 1) < 10 ? '0' : '') + (currentdate.getMonth() + 1);
        var diaMes = dd + '/' + mm;
        if ($.inArray(diaMes, cron.DatasFeriados) != -1) {
            return true;
        }
        return false;
    }
    cron.initDialogs = function () {
        cron.$dialogAtualizarBaseline = $('#dialog-atualizar-baseline').dialog({
            autoOpen: false,
            title: 'Cronograma - Atualizar Base Line',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                $('#dialog-atualizar-baseline').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                //CRONOGRAMA.retornaProjeto();
            },
            open: function (event, ui) {
                vSalvar = true;
                $('#dialog-atualizar-baseline').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Confirmar': function () {
                    $(cron.formAtualizarBaseline).submit();
                    $('#dialog-atualizar-baseline').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });

                    $(document).ajaxComplete(function (event, xhr) {
                        if (JSON.parse(xhr.responseText).success) {
                            window.location.href = window.location.href;
                        }
                    });
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });
    };
    cron.nonWorkingDates = function (date) {
        var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5,
            Saturday = 6;
        var feriadosfixos = $("input#feriadosfixos").val();
        feriadosfixos = feriadosfixos.trim();
        var closedDates = [];
        var arrayItemFeriado = "";
        if (feriadosfixos != "") {
            var arrayFeriados = feriadosfixos.split(",");
            for (var j = 0; j < arrayFeriados.length; j++) {
                var arrayItemFeriado = arrayFeriados[j].split(";");
                var diaIt = parseInt(arrayItemFeriado[0]);
                var mesIt = parseInt(arrayItemFeriado[1]);
                var anoIt = parseInt(arrayItemFeriado[2]);
                if (anoIt > 0)
                    closedDates[j] = [mesIt, diaIt, anoIt];
                else
                    closedDates[j] = [mesIt, diaIt, 0];
            }
        }
        var closedDays = [[Sunday], [Saturday]];
        for (var i = 0; i < closedDays.length; i++) {
            if (day === closedDays[i][0]) {
                return [false];
            }
        }
        for (i = 0; i < closedDates.length; i++) {
            if (closedDates[i][2] > 0) {
                if (
                    (date.getDate() === closedDates[i][1] &&
                        date.getMonth() === closedDates[i][0] - 1 &&
                        date.getFullYear() === closedDates[i][2])) {
                    return [false];
                }
            } else {
                if (
                    (date.getDate() === closedDates[i][1] &&
                        date.getMonth() === closedDates[i][0] - 1)) {
                    return [false];
                }
            }
        }
        return [true];
    };

    cron.renderCronograma = function () {
        var idprojeto = $("input#idprojeto").val();
        $.ajax({
            url: base_url + '/projeto/cronograma/retornacronogramajson/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                idprojeto: idprojeto
            },
            success: function (data) {
                //$('#tabCron').load(location.href+".region-center>*","");
            },
            error: function () {
                $.pnotify({
                    text: cron.msgeacessonegado,
                    type: 'error',
                    hide: false
                });
            }
        });
    };

    cron.retornaProjeto = function () {
        var idprojeto = $("input#idprojeto").val();
        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                idprojeto: idprojeto
            },
            success: function (data) {
                if (data != null) {
                    if (data.projeto.ultimoStatusReport.datfimprojetotendencia !== null) {
                        data.projeto.ultimoStatusReport.datfimprojetotendencia = data.projeto.ultimoStatusReport.datfimprojetotendencia.substr(0, 10);
                    }
                    cron.projeto = data.projeto;
                    $("input#numcriteriofarol").val(data.projeto.numcriteriofarol);
                }
                //cron.renderProjeto();
            },
            error: function () {
                $.pnotify({
                    text: cron.msgeacessonegado,
                    type: 'error',
                    hide: false
                });
            }
        });
    };

    cron.carregaSelectPercentualconcluido = function (valorDominio, selectPercentualconcluido) {
        var soma = (valorDominio == 4 ? 100 : 10), count = 0;
        selectPercentualconcluido.empty();
        while (count <= 100) {
            selectPercentualconcluido.append($('<option>').text(count + '%').attr('value', count));
            count = count + soma;
        }
    };

    cron.nonUtilDates = function (date) {
        if (date !== null) {

            if (!(cron.dataDiaUtil(date))) {
                return true;
            } else {

                var feriadosfixos = $("input#feriadosfixos").val();
                feriadosfixos = feriadosfixos.trim();
                var closedDates = [];
                var arrayItemFeriado = "";
                if (feriadosfixos != "") {
                    var arrayFeriados = feriadosfixos.split(",");
                    for (var j = 0; j < arrayFeriados.length; j++) {
                        var arrayItemFeriado = arrayFeriados[j].split(";");
                        var diaIt = parseInt(arrayItemFeriado[0]);
                        var mesIt = parseInt(arrayItemFeriado[1]);
                        var anoIt = parseInt(arrayItemFeriado[2]);
                        if (anoIt > 0)
                            closedDates[j] = [mesIt, diaIt, anoIt];
                        else
                            closedDates[j] = [mesIt, diaIt, 0];
                    }
                }
                var cou = 0;
                for (i = 0; i < closedDates.length; i++) {
                    if (closedDates[i][2] > 0) {
                        if (
                            (date.getDate() === closedDates[i][1] &&
                                date.getMonth() === closedDates[i][0] - 1 &&
                                date.getFullYear() === closedDates[i][2])) {
                            cou++;
                            break;
                        }
                    } else {
                        if (
                            (date.getDate() === closedDates[i][1] &&
                                date.getMonth() === closedDates[i][0] - 1)) {
                            cou++;
                            break;
                        }
                    }
                }

                return (cou > 0 ? true : false);
            }
        } else {
            return null;
        }
    };

    cron.dataFeriado = function (vrdate) {
        if (vrdate !== null) {
            var feriadosfixos = $("input#feriadosfixos").val();
            feriadosfixos = feriadosfixos.trim();
            var arrayData = vrdate.split('/');
            var dia = parseInt(arrayData[0]);
            var mes = parseInt(arrayData[1]);
            var ano = parseInt(arrayData[2]);
            var closedDates = [];
            var arrayItemFeriado = "";
            if (feriadosfixos != "") {
                var arrayFeriados = feriadosfixos.split(",");
                for (var j = 0; j < arrayFeriados.length; j++) {
                    var arrayItemFeriado = arrayFeriados[j].split(";");
                    var diaIt = parseInt(arrayItemFeriado[0]);
                    var mesIt = parseInt(arrayItemFeriado[1]);
                    var anoIt = parseInt(arrayItemFeriado[2]);
                    if (anoIt > 0)
                        closedDates[j] = anoIt;
                    else
                        closedDates[j] = [mesIt, diaIt, ano];
                }
            }
            var cou = 0;
            for (i = 0; i < closedDates.length; i++) {
                var clMes = parseInt(closedDates[i][0]);
                var clDia = parseInt(closedDates[i][1]);
                var clAno = parseInt(closedDates[i][2]);
                if (mes === clMes &&
                    dia === clDia &&
                    ano === clAno
                ) {
                    cou++;
                    break;
                }
            }
            return (cou > 0 ? true : false);

        } else {
            return true;
        }
    };

    //cron.renderProjeto = function () {
    //    //cron.tplProjeto   = Handlebars.compile($('#tpl-projeto').html());
    //    cron.tplGrupo = Handlebars.compile($('#tpl-grupo').html());
    //
    //    Handlebars.registerPartial("helperEntrega", $("#tpl-entrega").html());
    //    Handlebars.registerPartial("helperAtividade", $("#tpl-atividade").html());
    //
    //    //$('#dados-projeto').html(cron.tplProjeto(cron.projeto));
    //    TemplateManager.get('dados-projeto', function (tpl) {
    //        $("#dados-projeto").html(tpl(cron.projeto));
    //    });
    //
    //    TemplateManager.get('dados-cronograma-projeto', function (tpl) {
    //        $("#dados-cronograma-projeto").html(tpl(cron.projeto));
    //    });
    //
    //    $('.container-grupo').html(cron.tplGrupo(cron.projeto));
    //    cron.events();
    //
    //    if (cron.itemChecado !== null) {
    //        $(cron.itemChecado).attr("checked", true).trigger('click');
    //    }
    //
    //    if (cron.itemativo !== null) {
    //        $(cron.itemativo).addClass("success");
    //    }
    //    cron.nav = $(cron.checkItemCronograma);
    //};

    cron.renderAtividadeCronograma = function (data) {
        acao = data.acao;
        objAtividade = data.item;
        prazo = data.prazo;
        //cron.retornaProjeto();
    };

    cron.events = function () {
        var intervalo = window.setInterval(cron.calcularAlturaCronograma, 500);
        var largura = window.setInterval(cron.calcularLarguraCronograma, 500);

        $(cron.allButtonsToolBar).tooltip();

        $("body").on('click', cron.checkItemCronograma, function () {
            var $this = $(this);
            $('body').trigger('mostrarFerramentas', [$this]);
        });


        $("body").on('dblclick', cron.checkItemCronograma, function () {
            var $this = $(this),
                grupo = $this.data('group');

            if (grupo == '.btn-group-atividade') {
                $("#e_numpercentualconcluido").focus();
            }
        });

        $('.btn-group-ferramentas button:eq(1)').addClass('active');

        $("#btn-fullscreen").on('click', function () {
            myLayout.close('north');
            myLayout.close('south');
        });

        $("#btn-restaurar").on('click', function () {
            myLayout.open('north');
            myLayout.open('south');
        });
    };

    cron.init = function () {
        cron.initDialogs();
        //cron.retornaProjeto();
        //cron.retornaCronograma();
        cron.customEvents();
        cron.events();
    };

    return cron;

}(jQuery, Intervalo));



