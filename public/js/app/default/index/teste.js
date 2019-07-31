date = (function () {
    interval = function (dataInicio, dataFim) {
        var i = 0,
            inicio = Date.parseExact(dataInicio, "dd/MM/yyyy"),
            fim = Date.parseExact(dataFim, "dd/MM/yyyy")
        ;
        if (inicio == null) {
            return 0;
        }

        do {
            //console.log(inicio);
            if (false === inicio.is().sunday() && false === inicio.is().saturday()) {
                i++;
            }
            inicio.addDays(1);
        }
        while (false === inicio.equals(fim))
        return i;
    };

    weekdaysBetween = function (startDate, endDate) {
        var e = null, s = null, diffDays = 0, weeksBetween = 0, adjust = 0;

        if (startDate == null) {
            return 0;
        }

        startDate = Date.parseExact(startDate, 'dd/MM/yyyy');
        endDate = Date.parseExact(endDate, 'dd/MM/yyyy');

        if (startDate < endDate) {
            s = startDate;
            e = endDate;
        } else {
            s = endDate;
            e = startDate;
        }
        diffDays = Math.floor((e - s) / 86400000);
        weeksBetween = Math.floor(diffDays / 7);
        if (s.getDay() == e.getDay()) {
            adjust = 0;
        } else if (s.getDay() == 0 && e.getDay() == 6) {
            adjust = 5;
        } else if (s.getDay() == 6 && e.getDay() == 0) {
            adjust = 0;
        } else if (e.getDay() == 6 || e.getDay() == 0) {
            adjust = 5 - s.getDay() + 1;
        } else if (s.getDay() == 0 || s.getDay() == 6) {
            adjust = e.getDay() - 1;
        } else if (e.getDay() > s.getDay()) {
            adjust = e.getDay() - s.getDay();
        } else {
            adjust = 5 + e.getDay() - s.getDay();
        }
        return (weeksBetween * 5) + adjust;
    }

    adicionarDiasUteis = function (data, dias) {
        var tam = dias, i = 0, total = dias,
            aux = Date.parseExact(data, "dd/MM/yyyy"),
            dataTotal = Date.parseExact(data, "dd/MM/yyyy")
        ;

        do {
            if (aux.is().sunday() || aux.is().saturday()) {
                total = total + 1;
            }
            aux.addDays(1);
            i++;
        } while (i < tam);

        dataTotal.addDays(total);
        return dataTotal.toString('dd/MM/yyyy');
    };

    proximoDiaUtil = function (data) {
        data = Date.parseExact(data, "dd/MM/yyyy");
        if (false === data.is().sunday() && false === data.is().saturday()) {
            return data.addDays(1).toString('dd/MM/yyyy');
        }

        while (data.is().sunday() || data.is().saturday()) {
            data.addDays(1);
        }

        return data.toString('dd/MM/yyyy');
    };

    return {
        interval: interval,
        adicionarDiasUteis: adicionarDiasUteis,
        proximoDiaUtil: proximoDiaUtil,
        weekdaysBetween: weekdaysBetween
    };
})();

function _each(arr, fn /*function(element, index)*/, limit /*items per pass*/, callback) {
    var count = 0,
        len = arr.length;

    function run() {
        var d = limit;
        while (d-- && len >= count) {
            fn(arr[count], count++);
        }
        if (len > count) {
            setTimeout(run, 500);
        } else {
            if (typeof callback === "function") {
                callback();
            }
        }
    }

    run();
}

projeto = (function () {
    this.current = {
        i: 0,
        f: 0
    };

    calcularIntervalos = function () {
        $('.datai').each(function (i, val) {
            var
                $dataInicial = $(val),
                $dataFinal = $(val).parent().parent().find('.dataf')
            ;

            intervalo = date.interval($dataInicial.val(), $dataFinal.val());
            $dataFinal.data('intervalo', intervalo);
        });
    };

    atualizarDatas = function (btn) {
        var $datai = null, $dataf = null, $linhas = null, $linhaAtual = null;

        $linhaAtual = $(btn).closest('.linha');
        $datai = $linhaAtual.find('.datai');
        $dataf = $linhaAtual.find('.dataf');
        $linhas = $linhaAtual.nextAll();
        current.i = $datai.val();
        current.f = $dataf.val();
        console.log($linhas.length);
        _each($linhas, function (val, i) {
            //setTimeout(null, 10);
            var $linha = $(val), $dti = null, $dtf = null, intervalo = 0;

            $dti = $linha.find('.datai');
            $dtf = $linha.find('.dataf');

            if ($dti.val() != '') {
                // Intervalo entre as datas
                if (!$linha.data('intervalo')) {
                    intervalo = date.weekdaysBetween($dti.val(), $dtf.val());
                    $linha.data('intervalo', intervalo);
                } else {
                    intervalo = $linha.data('intervalo');
                }
                // Data inicial
                current.i = date.proximoDiaUtil(current.f);
                $dti.val(current.i);

                // Data Final
                current.f = date.adicionarDiasUteis(current.i, intervalo);
                $dtf.val(current.f);
            }
        }, 10);
        /*
            $linhas.each(function(val,i){
                setTimeout(null, 10);
                var
                    $linha = $(val),
                    $dti = null,
                    $dtf = null
                    ;

                $dti = $linha.find('.datai');
                $dtf = $linha.find('.dataf');

                if($dti.val() != '') {
                    // Intervalo entre as datas
                    intervalo = date.interval($dti.val(), $dtf.val());

                    // Data inicial
                    current.i = date.proximoDiaUtil(current.f);
                    $dti.val(current.i);

                    // Data Final
                    current.f = date.adicionarDiasUteis(current.i, intervalo);
                    $dtf.val(current.f);
                }
            });
            */
    };

    mostrarFerramentas = function (btn) {
        var pai = $(btn).closest('.linha'), isCurrent = pai.is('.well-mini');

        $('.linha').removeClass('well-mini');
        $('.linha .container-tools').empty();
        if (isCurrent) {
            return;
        }
        var tools = $('.ferramentas .tools').clone(true);
        pai.addClass('well-mini').find('.container-tools').html(tools);
        slider();
    };

    slider = function () {
        var s = null;
        s = $("#slider-range-min").slider({
            range: "min",
            value: 0,
            min: 0,
            max: 100,
            slide: function (event, ui) {
                $("#slider-value").text(ui.value);
                $("#amount").val(ui.value);
            }
        });
        $("#slider-value").text(s.slider("value"));
        $("#amount").val(s.slider("value"));
    };

    initDatas = function () {
        $(".dataf").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            showOn: "button",
            beforeShowDay: $.datepicker.noWeekends,
            beforeShow: function (selectedDate, inst) {
                var
                    dti = $(inst.input).parent().parent().find('.datai').val(),
                    dtf = $(inst.input).val(),
                    intervalo = null;
                intervalo = date.weekdaysBetween(dti, dtf);
                data = date.adicionarDiasUteis(dti, intervalo);
                $(this).datepicker("option", "minDate", data);
            }
        });

        $(".datai").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            showOn: "button",
            beforeShowDay: $.datepicker.noWeekends,
            beforeShow: function (selectedDate, inst) {
                var val = null;
                if ($(inst.input).parent().parent().prev().find('.dataf').length > 0) {
                    val = $(inst.input).parent().parent().prev().find('.dataf').val();
                    $(this).datepicker("option", "minDate", date.proximoDiaUtil(val));
                }
            }
        });

        $(document.body).on('blur', '.datai, .dataf', function () {
            console.log('mudou');
            var
                $linhaAtual = null,
                datai = '',
                dataf = '',
                intervalo = 0
            ;

            $linhaAtual = $(this).closest('.linha');//.css('background','red');
            datai = $linhaAtual.find('.datai').val();
            dataf = $linhaAtual.find('.dataf').val();
            intervalo = date.weekdaysBetween(datai, dataf);
            $linhaAtual.data('intervalo', intervalo);
        });
    };

    init = function () {
        //console.log('init');
        // console.log('weekdaysBetween: ' + weekdaysBetween(new Date(2013,4,6),new Date(2013,4,10)));
        // console.log('date.interval: ' + date.interval('06/05/2013', '10/05/2013'));
        initDatas();
        $("button.btn-tools").click(function () {
            return mostrarFerramentas($(this));
        });
        $(document.body).on('click', ".btn-aplicar", function () {
            return atualizarDatas($(this));
        });
    };

    return {
        init: init
    };
})();


$(function () {
    projeto.init();
});

/*
$("#notaccordion").addClass("ui-accordion ui-accordion-icons ui-widget ui-helper-reset")
  .find("h3")
    .addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom")
    .hover(function() { $(this).toggleClass("ui-state-hover"); })
    .prepend('<span class="ui-icon ui-icon-triangle-1-e"></span>')
    .click(function() {
      $(this)
        .toggleClass("ui-accordion-header-active ui-state-active ui-state-default ui-corner-bottom")
        .find("> .ui-icon").toggleClass("ui-icon-triangle-1-e ui-icon-triangle-1-s").end()
        .next().toggleClass("ui-accordion-content-active").slideToggle();
      return false;
    })
    .next()
      .addClass("ui-accordion-content  ui-helper-reset ui-widget-content ui-corner-bottom")
      .hide();
*/