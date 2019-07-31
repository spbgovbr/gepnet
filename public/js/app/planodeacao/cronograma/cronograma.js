var CRONOGRAMA = (function ($, Handlebars, Intervalo) {
    var
        cron = {};

    cron.planodeacao = {};
    cron.tplPlanodeacao = null;
    cron.tplGrupo = null;
    cron.tplEntrega = null;
    cron.tplAtividade = null;
    cron.tplMarco = null;
    cron.imprimirCronogramaPdf = '.btn-imprimir-cronograma-pdf';
    cron.checkItemCronograma = 'input.input-item-cronograma';
    cron.allToolBar = ' .btn-group-grupo, .btn-group-atividade, .btn-group-entrega';
    cron.allModo = '.btn-modo';
    cron.ItemDetalhado = '.btn-simples';
    cron.allItemSimples = '.btn-detalhado, .cron-simples';
    cron.allItemDetalhado = '.btn-simples, .cron-detalhado';
    cron.allButtonsToolBar = '.btn-group-modo a, .btn-group-modo button, .btn-group-atividade a, .btn-group-grupo a, .btn-group-entrega a, .btn-cadastrar-grupo, .btn-cadastrar-grupo a, .btn-group-cronograma a, .btn-group-ferramentas button';
    cron.itemativo = null;
    cron.itemChecado = null;
    cron.itemChkPrecedessora = null;
    cron.existeChecado = false;
    cron.clickcheck = true;
    cron.idplanodeacao = $('#idplanodeacao').val();
    cron.$dialogAtualizarBaseline = null;
    cron.formAtualizarBaseline = "form#atualizar-baseline",
        cron.nav = [];
    cron.altura = {
        doc: 0,
        norte: 0,
        sul: 0,
        acordion: 0,
        acordionPadding: 20
    };

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
        $('.container-grupo').height(cron.altura.doc - retirar);
    };

    cron.customEvents = function () {
        $('body').on('mostrarFerramentas', function (event, chk) {
            cron.clickcheck = false;
            cron.itemChecado = 'input.input-item-cronograma[value="' + chk.val() + '"]';

            var
                grupo = chk.data('group');

            $('.item-cronograma').removeClass('success');
            $(cron.itemChecado).closest('.item-cronograma').addClass('success');

            $(cron.allToolBar).hide();
            if (chk.is(":checked")) {
                $('' + grupo).show();
            }
            var dados = chk.data('dados');
            var flgordenacao = dados.flaordenacao;
            if (flgordenacao == 'S') {
                var txtTitle = 'Bloquear Ordenação';
                $(".btn-ordena").attr('disabled', false);
            } else {
                var txtTitle = 'Desbloquear Ordenação';
                $(".btn-ordena").attr('disabled', true);
            }
            $("a.btn-bloquear-ordenacao").each(function (index) {
                var $this = $(this);
                $this.find('i').removeClass((flgordenacao == 'S' ? 'iconp-lock' : 'iconp-unlock'));
                $this.find('i').addClass((flgordenacao == 'S' ? 'iconp-unlock' : 'iconp-lock'));
                $this.find('i').attr('title', txtTitle);
                $this.find('i').prop('title', txtTitle);
            });

            if (grupo == '.btn-group-grupo') {
                $('.btn-ordena-grupo').prop('title', txtTitle);
                $('.btn-ordena-grupo').tooltip('hide')
                    .attr('data-original-title', txtTitle)
                    .tooltip('fixTitle');
            }

            if (grupo == '.btn-group-entrega') {
                $('.btn-ordena-entrega').prop('title', txtTitle);
                $('.btn-ordena-entrega').tooltip('hide')
                    .attr('data-original-title', txtTitle)
                    .tooltip('fixTitle');
            }
            if (grupo == '.btn-group-atividade') {
                $('.btn-ordena-atividade').prop('title', txtTitle);
                $('.btn-ordena-atividade').tooltip('hide')
                    .attr('data-original-title', txtTitle)
                    .tooltip('fixTitle');

                var aux = dados.numpercentualconcluido.split('.');
                dados.numpercentualconcluido = aux[0];

                var folganum = dados.numfolga;
                //var i = dados.datfim.substring(10).split('F(');
                //var folga = i[1].split(')');
                var folga = folganum;

                $("#e_datinicio").val(dados.datinicio);
                if (cron.itemChkPrecedessora != chk.val()) {
                    cron.verificaExistePredecessora(chk.val(), $("#e_datinicio"), folga, dados.numdiasrealizados);
                    //cron.verificaExistePredecessora(chk.val(), $("#e_datinicio"), folga[0], dados.numdiasrealizados);
                }
                $("#e_numpercentualconcluido").val(dados.numpercentualconcluido);
                $("#e_numdiasrealizados").val(dados.numdiasrealizados);
                $("#e_domtipoatividade").val(dados.domtipoatividade);
                $("#e_idatividadecronograma").val(chk.val());
                $("#e_idgrupo").val(dados.idgrupo);
                //$("#e_numfolga").val(folga[0]);
                $("#e_numfolga").val(folga);

                $("a.btn-tranformar-marco, a.btn-tranformar-atividade").hide();
                if (chk.is('.item-marco')) {
                    $("a.btn-tranformar-atividade").show();
                } else {
                    $("a.btn-tranformar-marco").show();
                }

                $(CRONOGRAMA.atividade.formPercentual).validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        $.ajax({
                            url: base_url + '/planodeacao/cronograma/atividade-atualizar-percentual/format/json',
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
                                'idplanodeacao': $("#idplanodeacao").val(),
                                'idgrupo': $("#e_idgrupo").val()
                            },
                            success: function (data) {
                                //CRONOGRAMA.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';
                                CRONOGRAMA.retornaPlanodeacao();
                                cron.mostraCampos();
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
                    //CRONOGRAMA.atividade.habilitarElementoDespesa();
                    $(formAtual).validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function (form) {
                            enviar_ajax(urlForm, formAtual, function (data) {
                                if (data.item) {
                                    cron.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';
                                }
                                cron.retornaPlanodeacao();
                                cron.mostraCampos();
                                dialog.dialog('close');
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
        $("body").on('click', "a.btn-atualizar-cronograma", function (event) {
            event.preventDefault();
            cron.atualizarCronograma();
        });

        $("body").on('click', "a.btn-atualizar-baseline", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlForm = '/planodeacao/cronograma/atualizar-baseline/format/json',
                urlAjax = $this.attr('href');

            //console.log($this.attr('href') );
            cron.$dialogAtualizarBaseline.dialog('option', 'title', 'Cronograma - Atualizar Base Line');
            $this.data('form', cron.formAtualizarBaseline),
                $this.data('urlajax', urlAjax);
            $this.data('urlform', urlForm);
            $this.data('dialog', cron.$dialogAtualizarBaseline);
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on("fitrarCronograma", function () {
            $.ajax({
                url: base_url + '/planodeacao/cronograma/pesquisar',
                dataType: 'json',
                type: 'POST',
                data: $('form#ac_atividade_pesquisar').serialize(),
                //processData:false,
                success: function (data) {

                    $('.grupo, .entrega').hide();
                    $("input.input-item-cronograma", ".container-atividade").closest('.item-cronograma').hide();
                    //console.log(data);
                    $.each(data, function (i, val) {
                        //console.log(val);
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
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                }
            });
        });


    };

    cron.verificaExistePredecessora = function (atividade, objAtividade, folga, numdiareal) {
        cron.itemChkPrecedessora = atividade;
        var idplanodeacao = $("input#idplanodeacao").val(),
            idatividadecronograma = atividade;

        $.ajax({
            url: base_url + '/planodeacao/cronograma/retorna-predecessora',
            dataType: 'json',
            type: 'POST',
            async: false,
            cache: true,
            data: {
                idplanodeacao: idplanodeacao,
                idatividadecronograma: idatividadecronograma
            },
            //processData: false,
            success: function (data) {
                if (data != null) {
                    //console.log('com predecessora');
                    $("#e_datinicio").val(data);
                    var inicio = Intervalo.adicionarDias($("#e_datinicio").val(), 0);
                    $("#e_datinicio").val(Intervalo.adicionarDias(inicio, folga));
                    $("#e_datfim").val(Intervalo.adicionarDias($("#e_datinicio").val(), Math.abs(numdiareal)));

                    $("#e_datinicio").attr({disabled: "disabled", readonly: "true"});
                    $("#e_datinicio").attr('readonly', true);
                    $("#e_datfim").attr({disabled: "disabled", readonly: "true"});
                    $("#e_datfim").attr('readonly', true);

                    $("#e_numdiasrealizados").focus();

                } else if (data == null) {
                    //console.log('sem predecessora');
                    var inicio = Intervalo.adicionarDias($("#e_datinicio").val(), 0);
                    $("#e_datfim").val(Intervalo.adicionarDias(inicio, Math.abs(numdiareal)));

                    $("#e_datinicio").removeAttr('disabled');
                    $("#e_datinicio").attr('readonly', false);
                    $("#e_datfim").removeAttr('disabled');
                    $("#e_datfim").attr('readonly', false);

                    $("#e_numdiasrealizados").focus();
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


    cron.atualizarCronograma = function () {

        var idplanodeacao = $("input#idplanodeacao").val();

        $.ajax({
            url: base_url + '/planodeacao/cronograma/atualizar-cronograma/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            cache: true,
            data: {
                idplanodeacao: idplanodeacao
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
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
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
                CRONOGRAMA.retornaPlanodeacao();
                cron.mostraCampos();
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
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        });
    };

    cron.retornaPlanodeacao = function () {
        var idplanodeacao = $("input#idplanodeacao").val();
        $.ajax({
            url: base_url + '/planodeacao/cronograma/retorna-planodeacao/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                idplanodeacao: idplanodeacao
            },
            success: function (data) {
                cron.planodeacao = data.planodeacao;
                cron.renderPlanodeacao();
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

    cron.renderPlanodeacao = function () {
        //cron.tplPlanodeacao   = Handlebars.compile($('#tpl-planodeacao').html());
        cron.tplGrupo = Handlebars.compile($('#tpl-grupo').html());

        Handlebars.registerPartial("helperEntrega", $("#tpl-entrega").html());
        Handlebars.registerPartial("helperAtividade", $("#tpl-atividade").html());

        //$('#dados-planodeacao').html(cron.tplPlanodeacao(cron.planodeacao));
        TemplateManager.get('dados-planodeacao', function (tpl) {
            $("#dados-planodeacao").html(tpl(cron.planodeacao));
        });

        $('.container-grupo').html(cron.tplGrupo(cron.planodeacao));
        cron.events();

        if (cron.itemChecado !== null) {
            $(cron.itemChecado).attr("checked", true).trigger('click');
        }

        if (cron.itemativo !== null) {
            $(cron.itemativo).addClass("success");
        }
        cron.nav = $(cron.checkItemCronograma);
    };

    cron.events = function () {
        var intervalo = window.setInterval(cron.calcularAlturaCronograma, 500);

        $(cron.allButtonsToolBar).tooltip();

        $("body").on('click', cron.checkItemCronograma, function () {
            var $this = $(this);
            if (cron.clickcheck) {
                $('body').trigger('mostrarFerramentas', [$this]);
            } else cron.clickcheck = true;
        });

        $("body").on('click', cron.allModo, function (event) {
            var $this = $(this);
            if ($this.is(".btn-simples")) {
                $(cron.allItemSimples).show();
                $(cron.allItemDetalhado).hide();
            }
            if ($this.is(".btn-detalhado")) {
                $(cron.allItemSimples).hide();
                $(cron.allItemDetalhado).show();
            }
        });

        $("body").on('click', cron.imprimirCronogramaPdf, function () {
            var $this = $(this);
            if ($this.is(".btn-simples")) {
                $(cron.allItemSimples).show();
                $(cron.allItemDetalhado).hide();
            }
            if ($this.is(".btn-detalhado")) {
                $(cron.allItemSimples).hide();
                $(cron.allItemDetalhado).show();
            }
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

    cron.mostraCampos = function (a) {
        if ($(cron.ItemDetalhado).css("display") == "none") {
            $(cron.allItemSimples).show();
            $(cron.allItemDetalhado).hide();
        } else {
            $(cron.allItemSimples).hide();
            $(cron.allItemDetalhado).show();
        }
    };

    cron.init = function () {
        cron.initDialogs();
        cron.retornaPlanodeacao();
        cron.customEvents();
        cron.events();
    };

    return cron;

}(jQuery, Handlebars, Intervalo));



