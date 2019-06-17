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
//            console.log($num);
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

//jQuery.validator.addMethod("dataFimPlanoMaior", function (value, element) {
//    var fimPlanoDate = $('#datfimplano').datepicker('getDate');
//    var fimDate = $('#datfim').datepicker('getDate');
//    if(fimPlanoDate>fimDate) {
//        $("#msgError").removeAttr("display:none");
//        $("#msgError").show();
//        return false;
//    }else{ 
//        $("#msgError").hide();
//        $("#msgError").removeAttr("display:none");
//        return true;
//    }
//}, "<div style='display: none;'>Data fim do plano de projeto maior que a data de fim do projeto.</div>");
jQuery.validator.addMethod("dataFimPlanoMaior", function (value, element) {
    var fimPlanoDate = $('#datfimplano').datepicker('getDate');
    var fimDate = $('#datfim').datepicker('getDate');
    if (fimPlanoDate > fimDate) {
        // console.log('hummm');
//        $("#msgError").show();
//        $("#msgError").removeAttr("display:none");

        return false;
    } else {
        $("#msgError").hide();
        $("#msgError").removeAttr("display:none");
        return true;
    }
}, "<div style='width: 100%; padding-left: 90%; margin-left:-0px; position:relative; margin-top: -40px; '>Data fim do plano de projeto maior que a data de fim do projeto.</div>");


$(function () {
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

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    $("#resetDemandate").click(function () {
        $("#idDemanAnterior").val($('#iddemandante').val());
        $('#iddemandante').val("");
        $('#nomdemandante').val("Não detalhado");
        $('#nomdemandante').attr("");

    });

    $("#resetAdjunto").click(function () {
        $("#idadjuntoAnterior").val($('#idgerenteadjunto').val());
        $('#idgerenteadjunto').val("");
        $('#nomgerenteadjunto').val("Não detalhado");
        $('#nomgerenteadjunto').attr("");
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

    var
        $form = $("form#form-gerencia"),
        url_cadastrar = base_url + "/projeto/tap/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            $("#submitbutton").prop("disabled", true);
            enviar_ajax("/projeto/tap/informacoesiniciais/format/json", "form#form-gerencia", function (data) {
                if (data.success) {
                    setTimeout(function () {
                        $("#submitbutton").prop("disabled", false);
                    }, 2000);
                }
            });
        }
    });

    checkDataProjeto = function (nameData) {
        //console.log('Campo: ' + nameData);
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
//            $('#datinicioplano').datepicker('option', 'minDate', dateInicio);
//            $('#datinicioplano').datepicker('setDate', date2);
//            var dFimPlano = $('#datinicio').datepicker('getDate');
//            dFimPlano.setDate(dFimPlano.getDate()+60)
//            $("#datfimplano" ).datepicker("setDate", dFimPlano);

        }
        if (nameData == 'datfim') {
            $('#datinicio').datepicker('option', 'maxDate', dateFim);
            var date3 = $('#datfim').datepicker('getDate');
            var dateIni = $('#datinicio').datepicker('getDate');
            date3.setDate(date3.getDate());
            if ($("#datinicio").val() != "") {
                if (dateFim < dateInicio) {
                    $('#datinicio').datepicker('setDate', date3);
                }
            }
            //$('#datfimplano').datepicker('option', 'maxDate', date3);
            //$("#datfimplano" ).datepicker("setDate", dateIni.getDate()+60);
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

            if (dateIniPlano > dateFimPlano) {
                var datip = $('#datinicio').datepicker('getDate');
                datip.setDate(datip.getDate());
                $('#datinicioplano').datepicker('setDate', datip);
                $('#datinicioplano').datepicker('option', 'maxDate', dateFimPlano);

            }
            if (!($("form#form-gerencia").valid())) {
                $('#nomprojeto').focus();
                //$('#datfimplano').datepicker('hide');
                //console.log('erro');
                //$('#datinicioplano').datepicker('option', 'maxDate', dateFimPlano);
                //var date5 = $('#datfimplano').datepicker('getDate');
                //date5.setDate(date5.getDate());
                //if($("#datinicioplano").val() != "") {
                //    if (dateFimPlano < dateIniPlano) {
                //        $('#datinicioplano').datepicker('setDate', date5);
                //    }
                //}
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

    $("#accordion2").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });

    $('#idobjetivo').change(function () {
        fillSelect();
    });
    $("#numprocessosei2").mask("99999.999999/9999-99", {reverse: true});
    //fillSelect($("#idacao").val());

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

});
