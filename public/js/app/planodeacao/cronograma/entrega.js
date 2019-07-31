//XXXXXXXXXX ENTREGA XXXXXXXXXX
CRONOGRAMA.entrega = (function ($, Handlebars) {
    var o = {};

    vSalvar = true;
    vExcluir = true;
    o.formEntrega = "form#ac-entrega";
    o.formEntregaExcluir = "form#ac-entrega-excluir";
    o.$dialogEntrega = null;
    o.$dialogEntregaExcluir = null;
    o.ItemDetalhado = '.btn-simples';
    o.allItemSimples = '.btn-detalhado, .cron-simples';
    o.allItemDetalhado = '.btn-simples, .cron-detalhado';
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.idplanodeacao = '#idplanodeacao';
    o.urls = {
        cadastrar: '/planodeacao/cronograma/cadastrar-entrega/format/json',
        editar: '/planodeacao/cronograma/editar-entrega/format/json',
        excluir: '/planodeacao/cronograma/excluir-entrega/format/json'
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

    o.initDialogs = function () {
        o.$dialogEntrega = $('#dialog-entrega').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Entrega',
            width: '1000',
            modal: false,
            position: {
                my: "top top",
                at: "top top",
                of: window
            },
            close: function (event, ui) {
                vSalvar = true;
                $('#dialog-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                $('#dialog-entrega').empty();
            },
            open: function (event, ui) {
                vSalvar = true;
                $('#dialog-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function (event) {
                    event.preventDefault();
                    if (vSalvar) {
                        if ($(o.formEntrega).valid()) {
                            vSalvar = false;
                            $('#dialog-entrega').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            setTimeout(function () {
                                vSalvar = true;
                                $('#dialog-entrega').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }, 8000);
                            $(o.formEntrega).submit();
                        }
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        }).css("maxHeight", window.innerHeight - 150);

        o.$dialogEntregaExcluir = $('#dialog-excluir-entrega').dialog({
            autoOpen: false,
            title: 'Cronograma - Excluir Entrega',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                $('#dialog-excluir-entrega').empty();
            },
            open: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-entrega').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Excluir': function (event) {
                    event.preventDefault();
                    if (vExcluir) {
                        vExcluir = false;
                        $('#dialog-excluir-entrega').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vExcluir = true;
                            $('#dialog-excluir-entrega').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 4000);
                        $(o.formEntregaExcluir).submit();
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        }).css("maxHeight", window.innerHeight - 150);
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

    o.events = function () {
        $("body").on('click', "a.btn-cadastrar-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val(),
                urlAjax = $this.attr('href') + '/idgrupo/' + idcronogramaatividade,
                urlForm = o.urls.cadastrar
            ;

            o.$dialogEntrega.dialog('option', 'title', 'Cronograma - Cadastrar Entrega');

            $this.data('form', o.formEntrega),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntrega);
            $this.data('prefixo', '#en');
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-editar-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.editar,
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val()
            ;

            idgrupo = $(o.itemCronogramaSelecionado).closest('.grupo').find('.item-cronograma input.input-item-cronograma').val();
            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade + '/idgrupo/' + idgrupo;

            o.$dialogEntrega.dialog('option', 'title', 'Cronograma - Editar Entrega');

            $this.data('form', o.formEntrega),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntrega);
            $this.data('prefixo', '#en');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-excluir-entrega", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.excluir,
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val()
            ;

            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade;

            o.$dialogEntregaExcluir.dialog('option', 'title', 'Cronograma - Excluir Entrega');

            $this.data('form', o.formEntregaExcluir),
                $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntregaExcluir);
            $this.data('prefixo', '#en');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-imprimir-entrega", function (event) {
            event.preventDefault();
            var idplanodeacao = $(o.idplanodeacao).val();
            var idatividadecronograma = $(o.itemCronogramaSelecionado).val();
            var urlJanela = base_url + '/planodeacao/cronograma/imprimir-pdf';
            window.open(urlJanela + '/idplanodeacao/' + idplanodeacao + '/idatividadecronograma/' + idatividadecronograma);
            //var dataDados               = $(o.itemCronogramaSelecionado).data('dados');
        });

        $("body").on('click', "a.btn-clonar-entrega", function (event) {
            event.preventDefault();
            $.ajax({
                url: base_url + '/planodeacao/cronograma/clonar-entrega/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'idplanodeacao': $(o.idplanodeacao).val(),
                    'idatividadecronograma': $(o.itemCronogramaSelecionado).val(),
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

        jQuery.validator.addMethod("campoinformado", function (value, element) {
            var textocampo = $(element).val().trim();
            if (textocampo == '') {
                return false;  // FAIL validation when REGEX matches
            } else {
                return true;   // PASS validation otherwise
            }
            ;
        }, "Este campo deve ser informado.");

        $("body").on('click', "form#frminternoen #adicionarinterno", function (event) {
            var nomeitem = $('#nomparte').val();
            var iditem = $('#idparte').val();
            var $forminterno = $("form#frminternoen");
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
                        'idplanodeacao': $(o.idplanodeacao).val(),
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

        $("body").on('click', "form#frmexternoen #adicionarexterno", function (event) {
            var nomeitem = $('#nomparteexterno').val();
            var emailparte = $('#emailparte').val();
            var telefoneparte = $('#telefoneparte').val();
            var $formexterno = $("form#frmexternoen");
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
                        'idplanodeacao': $(o.idplanodeacao).val(),
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

    o.listapartesinteressadas = function (a) {
        $.ajax({
            url: base_url + '/planodeacao/tpa/grid-tpa/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idplanodeacao': $(o.idplanodeacao).val(),
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

    o.init = function () {
        o.initDialogs();
        o.events();
    };

    return o;
}(jQuery, Handlebars));


