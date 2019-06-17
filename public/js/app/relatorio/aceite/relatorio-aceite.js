$(function () {
    var
        aceite = {};

    aceite.selItemProjeto = '#idprojeto_pesquisa';
    aceite.lstEntregaPreojeto = '#identrega_pesquisar';

    var height = window.innerHeight;
    if (height > 800)
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:580px;');
    else
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:380px;');

    $("#idprojeto").select2({
        allowClear: true
    });

    $("#flagaceito").select2({
        allowClear: true
    });

    $("form#form-aceite-pesquisar").validate();

    $('#resetbutton').click(function (e) {
        e.preventDefault();
        $(':input', '#form-aceite-pesquisar')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
    });

    $('#btnpesquisar').click(function (e) {
        if ($("form#form-aceite-pesquisar").valid()) {
            e.preventDefault();
            $("form#form-aceite-pesquisar").submit();
        }
    });

    //$( "#idprojeto_pesquisa" ).change(function() {
    //$("body").on('change', "#idprojeto_pesquisa", function() {
    $("body").on('change', aceite.selItemProjeto, function (e) {
        e.preventDefault();
        putListaEntrega();
    });
    putListaEntrega = function () {
        var idprojeto = $(aceite.selItemProjeto).val();
        if (idprojeto > 0) {
            $.ajax({
                url: base_url + '/projeto/termoaceite/listaentregas/format/json',
                dataType: 'json',
                type: 'POST',
                async: false,
                cache: true,
                data: {
                    idprojeto: idprojeto
                },
                success: function (data) {
                    var count = 0;
                    $('#identrega_pesquisar').empty();
                    $('#identrega_pesquisar').append($('<option>').text("Todas").attr('value', ""));
                    $.each(data, function (i, val) {
                        if (i != "") {
                            $('#identrega_pesquisar').append($('<option>').text(val).attr('value', i));
                            count++;
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
        } else {
            $('#identrega_pesquisar').empty();
            $('#identrega_pesquisar').append($('<option>').text("Todas").attr('value', ""));
        }
    }

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        beforeShow: function (date) {
            checkPeriodoData(this);
        }
        //readonly: true,
    });

    $('.mask-date').mask('99/99/9999');

    checkPeriodoData = function (input) {
        var dateMin = null,
            dateMax = null;
        if (input.id === "dataceitacaoinicio") {
            if ($("#dataceitacaofim").datepicker("getDate") != null) {
                if (verificaData($("#dataceitacaofim").val())) {
                    dateMax = $("#dataceitacaofim").datepicker("getDate");
                    dateMin = null;
                } else {
                    dateMax = null;
                    dateMin = null;
                }
            } else {
                dateMax = null;
                dateMin = null;
            }
            $('#dataceitacaoinicio').datepicker('option', 'minDate', dateMin);
            $('#dataceitacaoinicio').datepicker('option', 'maxDate', dateMax);
        } else if (input.id === "dataceitacaofim") {
            dateMax = new Date;
            dateMin = null;
            if ($("#dataceitacaoinicio").datepicker("getDate") != null) {
                if (verificaData($("#dataceitacaoinicio").val())) {
                    dateMin = $("#dataceitacaoinicio").datepicker("getDate");
                    dateMax = null;
                } else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            } else {
                dateMax = null; //Set this to your absolute maximum date
                dateMin = null;
            }
            $('#dataceitacaofim').datepicker('option', 'minDate', dateMin);
            $('#dataceitacaofim').datepicker('option', 'maxDate', dateMax);
        }
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
    putListaEntrega();
})
;