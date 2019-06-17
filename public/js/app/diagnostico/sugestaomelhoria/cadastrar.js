$(function () {
    $.pnotify.defaults.history = false;

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $('.mask-date').mask('99/99/9999');
    $('#domcorimpacto, #domcorprobabilidade').change(function () {
        alteraRisco();
    });
});

function alteraRisco() {
    var probabilidade = $('#domcorprobabilidade').val();
    var impacto = $('#domcorimpacto').val();
    var opcao = null;

    if (impacto == '1' || probabilidade == '1') {
        opcao = '1';
    } else if (impacto == '2' || probabilidade == '2') {
        opcao = '2';
    } else if (impacto == '3' || probabilidade == '3') {
        opcao = '3';
    } else {
        opcao = '';
    }
    switch (opcao) {
        case '1':
            $('div#risco-badge.badge').attr('class', 'badge badge-important');
            $("div#risco-badge.badge").text("Alto");
            break;
        case '2':
            $('div#risco-badge.badge').attr('class', 'badge badge-warning');
            $("div#risco-badge.badge").text("Médio");
            break;
        case '3':
            $('div#risco-badge.badge').attr('class', 'badge badge-success');
            $("div#risco-badge.badge").text("Baixo");
            break;
        default:
            $('div#risco-badge.badge').attr('class', 'badge');
            $("div#risco-badge.badge").text("Risco");
            break;
    }
    $('#domcorrisco').val(opcao);
    return;
}

function resetFormRisco() {
    $("input, select, textarea").not('input#idprojeto, #form-risco-pesquisar :input').val('');
}

$('#flariscoativo').change(function () {
    if ($('#flariscoativo').val() == '2') {
        $('#divdatinatividade').show();
    } else {
        $('#divdatinatividade').hide();
    }
});

$('#divcontramedidaEfetiva').hide();
$('#domstatuscontramedida').change(function () {
    if ($('#domstatuscontramedida').val() == '2' || $('#domstatuscontramedida').val() == '3') {
        $('#divcontramedidaEfetiva').show();
    } else {
        $('#divcontramedidaEfetiva').hide();
    }
});

$('#idtiporisco').change(function (event) {
    event.preventDefault();
    var valor = $("#idtiporisco option:selected").val();

    if ($("#idtiporisco option:selected").val() > 0) {
        $.ajax({
            url: base_url + "/projeto/risco/combo-tratamento/id/" + valor,
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#domtratamento').html(data);
            }
        });
    } else {
        $('select[name=domtratamento]').text("Selecione");
        $('#domtratamento').html("");
    }
});