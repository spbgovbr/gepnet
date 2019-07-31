$(function () {

    var height = window.innerHeight;
    if (height > 800)
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:580px');
    else
        $('div.relatorioIndex').attr('style', 'overflow-x: auto; overflow-y: auto; height:380px;');

    $("#idprojeto").select2({
        allowClear: true
    });

    $("#domreferencia").select2({
        allowClear: true
    });

    $("#domsemafaro").select2({
        allowClear: true
    });

    $("form#form-diario-pesquisar").validate();

    $('#resetbutton').click(function (e) {
        e.preventDefault();
        $(':input', '#form-diario-pesquisar')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
    });

    $('#btnpesquisar').click(function (e) {
        if ($("form#form-diario-pesquisar").valid()) {
            e.preventDefault();
            $("form#form-diario-pesquisar").submit();
        }
    });

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        readonly: true,
        onSelect: function (date) {
            checkDataDiario(this.id);
        },
        onClose: function (date) {
            checkDataDiario(this.id);
        },
    });

    $('.mask-date').mask('99/99/9999');
    checkDataDiario = function (idData) {
        var dateInicio = $('#datdiariobordoinicio').datepicker('getDate');
        var dateFim = $('#datdiariobordofim').datepicker('getDate');
        if (idData == 'datdiariobordoinicio') {
            $('#datdiariobordofim').datepicker('option', 'minDate', dateInicio);
            if ((dateFim <= dateInicio) && ($("#datdiariobordofim").val() != "")) {
                var date2 = $('#datdiariobordoinicio').datepicker('getDate');
                date2.setDate(date2.getDate());
                $('#datdiariobordofim').datepicker('setDate', date2);
            }
        }
        if (idData == 'datdiariobordofim') {
            $('#datdiariobordoinicio').datepicker('option', 'maxDate', dateFim);
            if ((dateFim < dateInicio) && ($("#datdiariobordoinicio").val() != "")) {
                var date3 = $('#datdiariobordofim').datepicker('getDate');
                date3.setDate(date3.getDate());
                $('#datdiariobordoinicio').datepicker('setDate', date3);
            }
        }
    }

});