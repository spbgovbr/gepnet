$(function () {

    var height = window.innerHeight;
    if (height > 800)
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:580px;');
    else
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:380px;');

    var n = $('div.relatorioIndex').css("height");

    $("#idescritorio").select2({
        allowClear: true
    });

    $("#idprojeto").select2({
        allowClear: true
    });

    $("#idnatureza").select2({
        allowClear: true
    });

    $("form#form-risco-pesquisar").validate();

    $('#resetbutton').click(function (e) {
        e.preventDefault();
        //$("#idescritorio").select2("val", "").val('');
        //$("#idprojeto").select2("val", "").val('');
        //$("#idnatureza").select2("val", "").val('');
        $(':input', '#form-risco-pesquisar')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
    });

    $('#btnpesquisar').click(function (e) {
        if ($("form#form-risco-pesquisar").valid()) {
            e.preventDefault();
            $("form#form-risco-pesquisar").submit();
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
        if (input.id === "datdeteccaoinicio") {
            if ($("#datdeteccaofim").datepicker("getDate") != null) {
                if (verificaData($("#datdeteccaofim").val())) {
                    dateMax = $("#datdeteccaofim").datepicker("getDate");
                    dateMin = null;
                } else {
                    dateMax = null;
                    dateMin = null;
                }
            } else {
                dateMax = null;
                dateMin = null;
            }
            $('#datdeteccaoinicio').datepicker('option', 'minDate', dateMin);
            $('#datdeteccaoinicio').datepicker('option', 'maxDate', dateMax);
        } else if (input.id === "datdeteccaofim") {
            dateMax = new Date;
            dateMin = null;
            if ($("#datdeteccaoinicio").datepicker("getDate") != null) {
                if (verificaData($("#datdeteccaoinicio").val())) {
                    dateMin = $("#datdeteccaoinicio").datepicker("getDate");
                    dateMax = null;
                } else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            } else {
                dateMax = null; //Set this to your absolute maximum date
                dateMin = null;
            }
            $('#datdeteccaofim').datepicker('option', 'minDate', dateMin);
            $('#datdeteccaofim').datepicker('option', 'maxDate', dateMax);
        } else if (input.id === "datencerramentoinicio") {
            if ($("#datencerramentofim").datepicker("getDate") != null) {
                if (verificaData($("#datencerramentofim").val())) {
                    dateMax = $("#datencerramentofim").datepicker("getDate");
                    dateMin = null;
                } else {
                    dateMax = null;
                    dateMin = null;
                }
            } else {
                dateMax = null;
                dateMin = null;
            }
            $('#datencerramentoinicio').datepicker('option', 'minDate', dateMin);
            $('#datencerramentoinicio').datepicker('option', 'maxDate', dateMax);
        } else if (input.id === "datencerramentofim") {
            dateMax = new Date;
            dateMin = null;
            if ($("#datencerramentoinicio").datepicker("getDate") != null) {
                if (verificaData($("#datencerramentoinicio").val())) {
                    dateMin = $("#datencerramentoinicio").datepicker("getDate");
                    dateMax = null;
                } else {
                    dateMax = null; //Set this to your absolute maximum date
                    dateMin = null;
                }
            } else {
                dateMax = null; //Set this to your absolute maximum date
                dateMin = null;
            }
            $('#datencerramentofim').datepicker('option', 'minDate', dateMin);
            $('#datencerramentofim').datepicker('option', 'maxDate', dateMax);
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
});