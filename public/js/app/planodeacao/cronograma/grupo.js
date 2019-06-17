//XXXXXXXXXX GRUPO XXXXXXXXXX
CRONOGRAMA.grupo = (function ($, Handlebars) {
    var o = {};
    vExcluir = true;
    vSalvar = true;
    o.formGrupo = "form#ac-grupo";
    $form = $("ac-grupo");
    o.formGrupoExcluir = "form#ac-grupo-excluir";
    o.formInserirParte = "form#form-parte";
    o.ItemDetalhado = '.btn-simples';
    o.$dialogGrupo = null;
    o.$dialogInserirParte = null;
    o.$dialogGrupoExcluir = null;
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.idplanodeacao = '#idplanodeacao'

    o.urls = {
        cadastrar: '/planodeacao/cronograma/cadastrar-grupo/format/json',
        editar: '/planodeacao/cronograma/editar-grupo/format/json',
        excluir: '/planodeacao/cronograma/excluir-grupo/format/json',
        inserirparte: '/planodeacao/tpa/addparteinterno/format/json'
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
        o.$dialogGrupo = $('#dialog-grupo').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Grupo',
            width: '1000',
            modal: false,
            position: {
                my: "top top",
                at: "top top",
                of: window
            },
            close: function (event, ui) {
                vSalvar = true;
                $('#dialog-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                $('#dialog-grupo').empty();
            },
            open: function (event, ui) {
                vSalvar = true;
                $('#dialog-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Salvar': function (event) {
                    event.preventDefault();
                    if (vSalvar) {
                        if ($(o.formGrupo).valid()) {
                            vSalvar = false;
                            $('#dialog-grupo').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            setTimeout(function () {
                                vSalvar = true;
                                $('#dialog-grupo').parent().find("button").each(function () {
                                    $(this).attr('disabled', false);
                                });
                            }, 8000);
                            $(o.formGrupo).submit();
                        }
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        }).css("maxHeight", window.innerHeight - 150);

        o.$dialogGrupoExcluir = $('#dialog-excluir-grupo').dialog({
            autoOpen: false,
            title: 'Cronograma - Excluir Grupo',
            width: '800px',
            modal: true,
            close: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                $('#dialog-excluir-grupo').empty();
            },
            open: function (event, ui) {
                vExcluir = true;
                $('#dialog-excluir-grupo').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            buttons: {
                'Excluir': function (event) {
                    event.preventDefault();
                    if (vExcluir) {
                        vExcluir = false;
                        $('#dialog-excluir-grupo').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        setTimeout(function () {
                            vExcluir = true;
                            $('#dialog-excluir-grupo').parent().find("button").each(function () {
                                $(this).attr('disabled', false);
                            });
                        }, 4000);
                        $(o.formGrupoExcluir).submit();
                    }
                },
                'Fechar': function () {
                    $(this).dialog('close');
                }
            }
        }).css("maxHeight", window.innerHeight - 150);
        o.$dialogInserirParte = $('#dialog-inserir-parte').dialog({
            autoOpen: false,
            title: 'Parte Interessada - Cadastrar',
            width: 1000,
            height: 600,
            modal: false,
            open: function (event, ui) {
                $('#dialog-inserir-parte').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
            },
            close: function (event, ui) {
                $('#dialog-inserir-parte').parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
                $('#dialog-inserir-parte').empty();
            },
            buttons: {
                'Salvar': function () {
                    //var tipoParte = $('li.active').data('cont');
                    var tipoParte = $('#dialog-inserir-parte').parent().find("li.active").data('cont');
                    if (tipoParte == 'externo') {
                        $('form#form-parte-externo').submit();
                        if ($('form#form-parte-externo').valid()) {
                            $('#dialog-inserir-parte').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            $(this).dialog('close');
                        }
                    } else {
                        $('form#form-parte').submit();
                        if ($('form#form-parte').valid()) {
                            $('#dialog-inserir-parte').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            $(this).dialog('close');
                        }
                    }
                },
                'Fechar': function () {
                    $('#dialog-inserir-parte').parent().find("button").each(function () {
                        $(this).attr('disabled', false);
                    });
                    $(this).dialog('close');
                }
            }
        }).css("maxHeight", window.innerHeight - 150);
    };

    o.events = function () {

        $("body").on('click', "a.btn-cadastrar-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = o.urls.cadastrar
            ;

            o.$dialogGrupo.dialog('option', 'title', 'Cronograma - Cadastrar Grupo');

            $this.data('form', o.formGrupo);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupo);
            $this.data('prefixo', '#gr');

            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-editar-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val(),
                urlForm = o.urls.editar
            ;

            o.$dialogGrupo.dialog('option', 'title', 'Cronograma - Editar Grupo');

            $this.data('form', o.formGrupo);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupo);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog', [$this]);
        });

        $("body").on('click', "a.btn-excluir-grupo", function (event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.excluir,
                idcronogramaatividade = +$(o.itemCronogramaSelecionado).val()
            ;

            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade;

            o.$dialogGrupoExcluir.dialog('option', 'title', 'Cronograma - Excluir Grupo');

            $this.data('form', o.formGrupoExcluir);
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupoExcluir);
            $this.data('prefixo', '#gr');

            $("body").trigger('openDialog', [$this]);

        });

        /*jQuery.validator.addMethod("campoemail", function (value, element) {
         return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test(value);
         }, 'Informe um endereço de email valido.');*/

        jQuery.validator.addMethod("campoinformado", function (value, element) {
            var textocampo = $(element).val().trim();
            if (textocampo == '') {
                return false;  // FAIL validation when REGEX matches
            } else {
                return true;   // PASS validation otherwise
            }
            ;
        }, "Este campo deve ser informado.");

        $("body").on('click', "form#frminternogr #adicionarinterno", function (event) {
            var nomeitem = $('#nomparte').val();
            var iditem = $('#idparte').val();
            var $forminterno = $("form#frminternogr");
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

        $("body").on('click', "form#frmexternogr #adicionarexterno", function (event) {
            var nomeitem = $('#nomparteexterno').val();
            var emailparte = $('#emailparte').val();
            var telefoneparte = $('#telefoneparte').val();
            var $formexterno = $("form#frmexternogr");
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

        $("body").on('click', "a.btn-imprimir-grupo", function (event) {
            event.preventDefault();
            var idplanodeacao = $(o.idplanodeacao).val();
            var idatividadecronograma = $(o.itemCronogramaSelecionado).val();
            var urlJanela = base_url + '/planodeacao/cronograma/imprimir-pdf';
            window.open(urlJanela + '/idplanodeacao/' + idplanodeacao + '/idatividadecronograma/' + idatividadecronograma);
        });

        $("body").on('click', "a.btn-clonar-grupo", function (event) {
            event.preventDefault();
            $.ajax({
                url: base_url + '/planodeacao/cronograma/clonar-grupo/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'idplanodeacao': $(o.idplanodeacao).val(),
                    'idatividadecronograma': $(o.itemCronogramaSelecionado).val()
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

        $("body").on('click', ".nome-grupo", function (event) {

            $(this).parent().parent().find('.container-entrega').toggle();
        });
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

    $('.mask-tel').mask("(99) 9999-9999");

    o.init = function () {
        o.initDialogs();
        o.events();
        //CRONOGRAMA.retornaPlanodeacao();
    };

    return o;
}(jQuery, Handlebars));
