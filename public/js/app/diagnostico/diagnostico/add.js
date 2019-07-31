/**
 * Comment
 */
function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

function fillSelect($num) {
    $idobjetivo = $("#idobjetivo").val() ? $("#idobjetivo").val() : -1;
    $.ajax({
        url: base_url + "/projeto/tap/acao/idobjetivo/" + $idobjetivo,
        dataType: 'json',
        type: 'GET',
        async: true,
        cache: true,
        processData: false,
        success: function (data) {
            var options = $("#idacao");
            if (data) {
                options.empty();
                options.append($("<option />").val("").text("Selecione"));
                $.each(data, function () {
                    options.append($("<option />").val(this.idacao).text(this.nomacao));
                });
                options.find('option[value=' + $num + ']').attr('selected', 'selected');
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

jQuery.validator.addMethod("dataFimPlanoMaior", function (value, element) {
    var fimPlanoDate = $('#datfimplano').datepicker('getDate');
    var fimDate = $('#datfim').datepicker('getDate');
    if (fimPlanoDate > fimDate) {

        return false;
    } else {
        $("#msgError").hide();
        $("#msgError").removeAttr("display:none");
        return true;
    }
}, "<div style='width: 100%; padding-left: 90%; margin-left:-0px; position:relative; margin-top: -40px; '>Data fim do plano de projeto maior que a data de fim do projeto.</div>");

$(function () {
    $("#menutap")
        .find('li').addClass('disabled')
        .end()
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menutap')
        .find('a').unbind('click');

    $('#menutap')
        .find('a').on("click", function (e) {
        e.preventDefault();
    });

    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: nonUtilDates,
        onSelect: function (date) {
            checkDataProjeto(this.id);
        },
        onClose: function (date) {
            checkDataProjeto(this.id);
        },
    });

    function nonUtilDates(date) {
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

    checkDataProjeto = function (nameData) {
        var dateInicio = $('#datinicio').datepicker('getDate');
        var dateFim = $('#datfim').datepicker('getDate');
        var dateIniPlano = $('#datinicioplano').datepicker('getDate');
        var dateFimPlano = $('#datfimplano').datepicker('getDate');
        if (nameData == 'datinicio') {
            $('#datfim').datepicker('option', 'minDate', dateInicio);
            var date2 = $('#datinicio').datepicker('getDate');
            date2.setDate(date2.getDate());
            if ($("#datfim").val() != "") {
                if (dateFim <= dateInicio) {
                    $('#datfim').datepicker('setDate', date2);
                }
            }
            $('#datinicioplano').datepicker('option', 'minDate', dateInicio);
            $('#datinicioplano').datepicker('setDate', date2);

        }
        if (nameData == 'datfim') {
            $('#datinicio').datepicker('option', 'maxDate', dateFim);
            var date3 = $('#datfim').datepicker('getDate');
            date3.setDate(date3.getDate());
            if ($("#datinicio").val() != "") {
                if (dateFim < dateInicio) {
                    $('#datinicio').datepicker('setDate', date3);
                }
            }
            $("#datfimplano").datepicker("setDate", date3.getDate() + 60);
        }
        if (nameData == 'datinicioplano') {
            var date4 = $('#datinicioplano').datepicker('getDate');
            date4.setDate(date4.getDate());
            if ($("#datinicio").val() != "") {
                var datei = $('#datinicio').datepicker('getDate');
                datei.setDate(datei.getDate());
                if (dateIniPlano < dateInicio) {
                    $('#datfimplano').datepicker('setDate', datei);
                    var date4 = $('#datinicioplano').datepicker('getDate');
                    date4.setDate(date4.getDate());
                    $('#datfimplano').datepicker('option', 'minDate', date4);
                }
            }
            if ($("#datfimplano").val() != "") {
                if (dateFimPlano <= dateIniPlano) {
                    $('#datfimplano').datepicker('setDate', date4);
                }
            }
        }
        if (nameData == 'datfimplano') {
            if (!($("form#form-gerencia").valid())) {
                $('#nomprojeto').focus();
            }
        }
    }

    $("body").delegate(".datemask-BR", "focusin", function () {
        var $this = $(this);
        $(this).mask('99/99/9999');
        //$this.attr('readonly',true);
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    });

    $("#resetbutton").click(function () {
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-gerencia"),
        url_cadastrar = base_url + "/diagnostico/diagnostico/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/diagnostico/diagnostico/add/format/json", "form#form-gerencia", function (data) {
                if (data.success) {
                    window.location.href = base_url + "/diagnostico/diagnostico/editar/iddiagnostico/" + data.dados.iddiagnostico;
                }
            });
        }
    });

    function enviar_ajax(url, form, callback) {

        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: $(form).serialize(),
            //processData:false,
            success: function (data) {
                if (typeof data.msg.text != 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                if (callback && typeof (callback) === "function") {
                    callback();
                }
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

    $("body").on("focusin", "#vlrorcamentodisponivel", function () {
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

    $('#idobjetivo').change(function () {
        fillSelect();
    });

    fillSelect($("#idacao").val());

    $("#portifolio").on('click', function (event) {
        event.preventDefault();
        $("#modal-portifolio").dialog({
            resizable: false,
            height: "auto",
            width: 980,
            modal: true,
            overflow: "auto",
            buttons: {
                Fechar: function () {
                    $(this).dialog("close");
                }
            }
        });
    });

    $('#pessoas-in').click(function (e) {
        listLeftMove();
    });

    $('#pessoas-out').click(function (e) {
        listRightMove();
    });

    $('#pessoas').dblclick(function (e) {
        listLeftMove();
    });

    $('#pessoasEquipe').dblclick(function (e) {
        listRightMove();
    });

    function listLeftMove() {
        var selectedOpts = $('#pessoas option:selected');
        if (selectedOpts.length == 0) {
            e.preventDefault();
        }

        $('#pessoasEquipe').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    function listRightMove() {
        var selectedOpts = $('#pessoasEquipe option:selected');
        if (selectedOpts.length == 0) {
            e.preventDefault();
        }

        $('#pessoas').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    }

    $('#idunidadeprincipal').change(function () {
        var numeroGerado = 0;

        if ($(this).val().length > 0) {
            $('#msgUnidade').empty();
            numeroGerado = $("#numeroGerado").val();
            $('#msnchk').empty();
            $("#grid-unidade").load(base_url + "/diagnostico/diagnostico/unidades-filhas/id/" + $(this).val());
            if (numeroGerado == 0) {
                $.ajax({
                    url: base_url + '/diagnostico/diagnostico/gera-sequence/unidade/' + $(this).val() + '/format/json',
                    dataType: 'json',
                    type: 'POST',
                    success: function (data) {
                        $('#dsdiagnostico').val(data);
                        var arr = data.split(".");
                        var dado = arr[1].split("-");
                        var numero = parseInt(dado[0].trimRight());
                        $('#numeroGerado').val(numero);
                    },
                    error: function (data) {
                        $.pnotify({
                            text: "Falha ao realizar requisição de unidade filhas.",
                            type: 'error',
                            hide: false
                        });
                    }
                });
            } else {
                var dado = $('#dsdiagnostico').val().split("-");
                var novoNome = dado[0] + "- " + $('select#idunidadeprincipal > option:selected').text();
                $('#dsdiagnostico').removeAttr('readonly');
                $('#dsdiagnostico').val("");
                $('#dsdiagnostico').val(novoNome);
                $('#dsdiagnostico').attr('readonly', true);
            }
        } else {
            $('#dsdiagnostico').removeAttr('readonly');
            $('#dsdiagnostico').val("");
            $('#dsdiagnostico').attr('readonly', true);
            $('#msgUnidade').empty();
            $('#numeroGerado').val(0);
        }
    });


    $("body").on("click", "#submitbutton", function () {

        $form.validate().form();
        var i = 0;
        var arrayEquipe = [],
            $formInserir = $("form#form-gerencia");

        $("#pessoasEquipe option").each(function () {
            arrayEquipe[i] = $(this).val();
            i++;
        });
        $('#pessparte').val(arrayEquipe.join());

        var valor = $("#idunidadeprincipal").val();
        if (valor == null || valor === "") {
            $('#idDisplay').show();
        } else {
            $('#idDisplay').hide();
        }

        if (arrayEquipe.join() == null || arrayEquipe.join() === "") {
            $('#equipe-editar').show();
        } else {
            $('#equipe-editar').hide();
        }
        var countSelectUndPrincial = $.trim($("#idunidadeprincipal").val()).length;

        if (countSelectUndPrincial > 0) {
            $('#msnchk').empty();
            var checkbox = $('input:checkbox[name^=unidades-vinculadas]:checked');

            var val = [];
            checkbox.each(function () {
                val.push($(this).val());
            });

            if (val.length > 0) {
                $('#msnchk').hide();
            } else {
                $('#msnchk').show();
                $('#msnchk').append("<span>Este campo é requerido.</span>");
                return false;
            }
        } else {
            $('#msgUnidade').show();
            $('#msgUnidade').append("<span>Este campo é requerido.</span>");
        }
        var data_inicio = $("#dtinicio").val();
        var data_fim = $("#dtencerramento").val();
        if ((data_inicio.length > 0) && (data_fim.length > 0)) {
            var compara1 = parseInt(data_inicio.split("/")[2].toString() + data_inicio.split("/")[1].toString() + data_inicio.split("/")[0].toString());
            var compara2 = parseInt(data_fim.split("/")[2].toString() + data_fim.split("/")[1].toString() + data_fim.split("/")[0].toString());

            if (compara1 < compara2) {
                $("#dtencerramento").load(location.href + " #dtencerramento>*", "");
                $('#msndatas').hide();
            } else {
                $('#msndatas').show();
                $('#msndatas').append("<span>A data Fim não pode ser maior que a data de Início.</span>");
                return false;
            }
        }

        if ($form.valid()) {
            var param = $formInserir.serialize();
            $.ajax({
                url: base_url + '/diagnostico/diagnostico/add/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {
                    $.pnotify({
                        text: data.msg.text,
                        type: data.msg.type,
                        hide: false
                    });
                    window.location.href = base_url + "/diagnostico/diagnostico/detalhar/iddiagnostico/" + data.msg.iddiagnostico;

                },
                error: function () {
                    $.pnotify({
                        text: msgerroacesso,
                        type: 'error',
                        hide: false
                    });
                }
            });
        } else {
            return false;
        }
    });

    $("body").on("click", "#idunidadeprincipal", function () {
        if ($("#idunidadeprincipal").val() == null || $("#idunidadeprincipal").val() === "") {
            $('#grid-unidade').hide();
        } else {
            $('#grid-unidade').show();
        }
    });

    $("body").on("click", "#menu-geraSequence", function (ev) {
        ev.preventDefault();

        $.ajax({
            url: base_url + '/diagnostico/diagnostico/gera-sequence/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                unidade: $('#idunidadeprincipal option:selected').val(),
                gerar: true,
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

});