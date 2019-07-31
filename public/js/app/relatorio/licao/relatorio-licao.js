$(function () {
    var
        licao = {};

    licao.selItemProjeto = '#idprojeto_pesquisa';
    licao.lstEntregaPreojeto = '#identrega_pesquisar';

    var height = window.innerHeight;
    if (height > 800)
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:580px;');
    else
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:380px;');

    $("#identrega").select2({
        allowClear: true
    });

    $("form#form-licao-pesquisar").validate();

    $('#resetbutton').click(function (e) {
        e.preventDefault();
        $(':input', '#form-licao-pesquisar')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
    });

    $('#btnpesquisar').click(function (e) {
        if ($("form#form-licao-pesquisar").valid()) {
            e.preventDefault();
            $("form#form-licao-pesquisar").submit();
        }
    });

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        beforeShow: function (date) {
            checkPeriodoData(this);
        }
    });

    $('.mask-date').mask('99/99/9999');

    checkPeriodoData = function (input) {
        var dateMin = null,
            dateMax = null;
        if (input.id === "datcadastroinicio") {
            if ($("#datcadastrofim").datepicker("getDate") != null) {
                if (verificaData($("#datcadastrofim").val())) {
                    dateMax = $("#datcadastrofim").datepicker("getDate");
                    dateMin = null;
                } else {
                    dateMax = null;
                    dateMin = null;
                }
            } else {
                dateMax = null;
                dateMin = null;
            }
            $('#datcadastroinicio').datepicker('option', 'minDate', dateMin);
            $('#datcadastroinicio').datepicker('option', 'maxDate', dateMax);
        } else if (input.id === "datcadastrofim") {
            dateMax = new Date;
            dateMin = null;
            if ($("#datcadastroinicio").datepicker("getDate") != null) {
                if (verificaData($("#datcadastroinicio").val())) {
                    dateMin = $("#datcadastroinicio").datepicker("getDate");
                    dateMax = null;
                } else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            } else {
                dateMax = null; //Set this to your absolute maximum date
                dateMin = null;
            }
            $('#datcadastrofim').datepicker('option', 'minDate', dateMin);
            $('#datcadastrofim').datepicker('option', 'maxDate', dateMax);
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

    $("body").on('change', licao.selItemProjeto, function (e) {
        e.preventDefault();
        putListaEntrega();
    });
    putListaEntrega = function () {
        var idprojeto = $(licao.selItemProjeto).val();
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
        changeYear: true
    });

    $('.mask-date').mask('99/99/9999');

});