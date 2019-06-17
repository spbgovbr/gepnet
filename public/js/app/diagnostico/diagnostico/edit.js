/**
 * Comment
 */

function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(document).ready(function () {

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: nonUtilDates
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
        if (selectedOpts.length === 0) {
            e.preventDefault();
        }

        $('#pessoasEquipe').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    function listRightMove() {
        var selectedOpts = $('#pessoasEquipe option:selected');
        if (selectedOpts.length === 0) {
            e.preventDefault();
        }

        $('#pessoas').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    $("#grid-unidade-editar").load(base_url + "/diagnostico/diagnostico/unidades-filhas/id/" + $('#idunidadeprincipal').val() + "/iddiagnostico/" + $("#iddiagnostico").val());
    $('#pessparte').val($('#pessoasEquipe').val());

    $.pnotify.defaults.history = false;

    $("body").on("click", "#submitbutton", function () {
        $('#idunidadeprincipal').removeAttr('disabled');
        var $form = $('#form-diagnostico-editar'),
            equipe = $('#pessoasEquipe');

        var i = 0;
        var arrayEquipe = [];
        $("#pessoasEquipe option").each(function () {
            arrayEquipe[i] = $(this).val();
            i++;
        });
        $('#pessparte').val(arrayEquipe.join());

        if (arrayEquipe.join() == null || arrayEquipe.join() === "") {
            $('#equipe-editar').show();
            return false;
        } else {
            $('#equipe-editar').hide();
        }

        var valor = $("#idunidadeprincipal").val();
        if (valor == null || valor === "") {
            $('#idDisplay').show();
            return false;
        } else {
            $('#idDisplay').hide();
        }

        var checkbox = $('input:checkbox[name^=unidades-vinculadas]:checked');
        var val = [];
        checkbox.each(function () {
            val.push($(this).val());
        });
        if (val.length > 0) {
            $('#msnchk').hide();
        } else {

            var ids = $('[id^=msg]').length;
            if (ids < 1) {
                $('#msnchk').show();
                $('#msnchk').append("<span id='msg'>Este campo é requerido.</span>");
                return false;
            } else if (val.length > 0) {
                $('#msnchk').hide();
            }
            return false;
        }

        $form.validate().form();

        if ($("#dtencerramento").val() !== "") {

            var data_inicio = $("#dtinicio").val();
            var data_fim = $("#dtencerramento").val();

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

            var param = $form.serialize();
            $.ajax({
                url: base_url + '/diagnostico/diagnostico/editar/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {

                    $.pnotify({
                        text: data.msg.text,
                        type: data.msg.type,
                        hide: false
                    });
                    setTimeout(function () {
                        window.location.href = base_url + "/diagnostico/diagnostico/detalhar/iddiagnostico/" + $("#iddiagnostico").val();
                    }, 2000);
                },
                error: function () {
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
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
            $('#grid-unidade-editar').hide();
        } else {
            $('#grid-unidade-editar').show();
        }
    });

    $("body").on("click", "#idunidadeprincipal", function () {
        $("#grid-unidade-editar").load(base_url + "/diagnostico/diagnostico/unidades-filhas/id/" + $('#idunidadeprincipal').val() + "/iddiagnostico/" + $('#iddiagnostico').val());
    });

    $("body").on("change", "#dtencerramento", function () {
        $('#msndatas').hide();
    });
    var valor = $("#idunidadeprincipal").val();
    $("#unidadeprincipal option[value=" + valor + "]").attr("selected", "selected");
});
