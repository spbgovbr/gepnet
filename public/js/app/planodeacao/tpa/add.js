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
        url: base_url + "/planodeacao/tpa/acao/idobjetivo/" + $idobjetivo,
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

$(function () {
    $("#menutpa")
        .find('li').addClass('disabled')
        .end()
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menutpa')
        .find('a').unbind('click');

    $('#menutpa')
        .find('a').on("click", function (e) {
        e.preventDefault();
    });

    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        onSelect: function (date) {
            checkDataPlanodeacao(this.id);
        },
        onClose: function (date) {
            checkDataPlanodeacao(this.id);
        },
    });
    checkDataPlanodeacao = function (nameData) {
        var dateInicio = $('#datinicio').datepicker('getDate');
        var dateFim = $('#datfim').datepicker('getDate');
        var dateIniPlano = $('#datinicioplano').datepicker('getDate');
        var dateFimPlano = $('#datfimplano').datepicker('getDate');
        if (nameData == 'datinicio') {
            $('#datfim').datepicker('option', 'minDate', dateInicio);
            if ((dateFim <= dateInicio) && ($("#datfim").val() != "")) {
                var date2 = $('#datinicio').datepicker('getDate');
                date2.setDate(date2.getDate());
                //date2.setDate(date2.getDate() + 1);
                $('#datfim').datepicker('setDate', date2);
            }
        }
        if (nameData == 'datfim') {
            $('#datinicio').datepicker('option', 'maxDate', dateFim);
            if ((dateFim < dateInicio) && ($("#datinicio").val() != "")) {
                var date3 = $('#datfim').datepicker('getDate');
                date3.setDate(date3.getDate());
                //date3.setDate(date3.getDate() - 1);
                $('#datinicio').datepicker('setDate', date3);
            }
        }
        if (nameData == 'datinicioplano') {
            $('#datfimplano').datepicker('option', 'minDate', dateIniPlano);
            if ((dateFimPlano <= dateIniPlano) && ($("#datfimplano").val() != "")) {
                var date4 = $('#datinicioplano').datepicker('getDate');
                date4.setDate(date4.getDate());
                //date4.setDate(date4.getDate() + 1);
                $('#datfimplano').datepicker('setDate', date4);
            }
        }
        if (nameData == 'datfimplano') {
            $('#datinicioplano').datepicker('option', 'maxDate', dateFimPlano);
            if ((dateFimPlano < dateIniPlano) && ($("#datinicioplano").val() != "")) {
                var date4 = $('#datfimplano').datepicker('getDate');
                date4.setDate(date4.getDate());
                //date4.setDate(date4.getDate() - 1);
                $('#datinicioplano').datepicker('setDate', date4);
            }
        }
    }

    $("body").delegate(".datemask-BR", "focusin", function () {
        var $this = $(this);
        $(this).mask('99/99/9999');
        $this.attr('readonly', true);
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    });

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-gerencia"),
        url_cadastrar = base_url + "/planodeacao/tpa/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/planodeacao/tpa/add/format/json", "form#form-gerencia", function (data) {
                if (data.success) {
                    window.location.href = base_url + "/planodeacao/tpa/informacoesiniciais/idplanodeacao/" + data.dados.idplanodeacao;
                }
            });
        }
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

    $('#idobjetivo').change(function () {
        fillSelect();
    });

    fillSelect($("#idacao").val());
});


