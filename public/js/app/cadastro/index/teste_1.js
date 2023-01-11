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


current = {
    i: 0,
    f: 0
};

date = (function () {
    interval = function (dataInicio, dataFim) {
        var
            inicio = dataInicio.clone(),
            fim = dataFim.clone()
        i = 0
        ;

        do {
            if (false === inicio.is().sunday() && false === inicio.is().saturday()) {
                i++;
            }
            inicio.addDays(1);
        }
        while (false === inicio.equals(fim))
        return i;
    };

    adicionarDiasUteis = function (data, dias) {
        var
            aux = data.clone(),
            tam = dias,
            total = dias,
            i = 0
        ;

        do {
            //console.log('contador: ' + i);
            //console.log('data: ' + aux.toString('dd/MM/yyyy'));
            if (aux.is().sunday() || aux.is().saturday()) {
                //  console.log('final de semana');
                total = total + 1;
            }
            aux.addDays(1);
            i++;
        } while (i < tam);

        data.addDays(total);
        return data;
    };

    proximoDiaUtil = function (data) {
        if (false === data.is().sunday() && false === data.is().saturday()) {
            return data.addDays(1);
        }

        while (data.is().sunday() || data.is().saturday()) {
            data.addDays(1);
        }

        return data;
    };

    return {
        interval: interval,
        adicionarDiasUteis: adicionarDiasUteis,
        proximoDiaUtil: proximoDiaUtil
    };
})();
/*
dateInterval = function (DataInicio, DataFim) {
    var 
    inicio = DataInicio.clone(),
    fim = DataFim.clone()
    i = 0
    ;
    
    do {
        //console.log(inicio.toString('dd/MM/yyyy'));
        if( false === inicio.is().sunday() && false === inicio.is().saturday()){
            i++;
        }
        inicio.addDays(1);
    }
    while( false === inicio.equals(fim) )
    //while( 1 != inicio.compareTo(fim) )
   
    return i;
};

dataAdicionarDiasUteis = function(data, dias)
{
    console.log('XXXXXXXXXXXXXXXXXXXXXXXXX');
    var 
    aux = data.clone(),
    tam = dias,
    total = dias,
    i = 0
    ;
        
    do {
        console.log('contador: ' + i);
        console.log('data: ' + aux.toString('dd/MM/yyyy'));
        if(aux.is().sunday() || aux.is().saturday()){
            console.log('final de semana');
            total = total + 1;
        }
        aux.addDays(1);
        i++;
    } while (i < tam);
    
    data.addDays(total);
    return data;
};

dataProximoDiaUtil = function(data){
    if(false === data.is().sunday() && false === data.is().saturday()){
        return data.addDays(1);
    }
    
    while( data.is().sunday() || data.is().saturday() )
    {
        data.addDays(1);
    }
    
    return data;
}
*/
$(function () {
    $("button.btn-tools").click(
        function () {
            var
                pai = $(this).parent().parent(),
                isCurrent = pai.is('.well-mini');
            /*
            if (pai.is('.well-mini')){
                $('.row').removeClass('well-mini');
                $('.row .container-tools').empty();
                return;
            }
            */
            $('.row').removeClass('well-mini');
            $('.row .container-tools').empty();

            if (isCurrent) {
                return;
            }

            var ferramentas = $('.ferramentas .tools').clone(true);
            pai.addClass('well-mini').find('.container-tools').html(ferramentas);

            $("#slider-range-min").slider({
                range: "min",
                value: 0,
                min: 0,
                max: 100,
                slide: function (event, ui) {
                    $("#slider-value").text(ui.value);
                    $("#amount").val(ui.value);
                }
            });
            $("#slider-value").text($("#slider-range-min").slider("value"));
            $("#amount").val($("#slider-range-min").slider("value"));
        }/*,
            function(){
                $(this).parent().parent().removeClass('well-mini').find('.container-tools').empty();
            }*/
    );

    $(document.body).on('click', ".btn-aplicar", function () {
        var
            $datai = null,
            $dataf = null,
            $linhas = null,
            $linhaAtual = null
        ;
        //console.log('começou');
        //$(this).closest('.linha').css('background','red');
        $linhaAtual = $(this).closest('.linha');//.css('background','red');
        //console.log($linhaAtual.length);
        $datai = $linhaAtual.find('.datai');
        $dataf = $linhaAtual.find('.dataf');
        $linhas = $linhaAtual.nextAll();
        current.i = Date.parseExact($datai.val(), "dd/MM/yyyy");
        current.f = Date.parseExact($dataf.val(), "dd/MM/yyyy");
        //console.log(current.i);
        //console.log(current.f);

        $linhas.each(function (i, val) {
            var
                $row = $(val),
                $dti = null,
                $dtf = null,
                dataInicial = null,
                dataFinal = null
            ;

            if ($row.is("#linha1") || $row.is(".content-body")) {
                return;
            }

            $dti = $row.find('.datai');
            $dtf = $row.find('.dataf');
            //console.log($dti.val());
            if ($dti.val() != '') {

                dataInicial = Date.parseExact($dti.val(), "dd/MM/yyyy");
                dataFinal = Date.parseExact($dtf.val(), "dd/MM/yyyy");
                // Intervalo entre as datas
                intervalo = date.interval(dataInicial, dataFinal);
                //console.log(intervalo);

                // Data inicial
                current.i = date.proximoDiaUtil(current.f);
                $dti.val(current.i.toString('dd/MM/yyyy'));

                // Data Final
                current.f = date.adicionarDiasUteis(current.i, intervalo);
                $dtf.val(current.f.toString('dd/MM/yyyy'));
                //console.log('dias: ' + intervalo);
            }
        });
    });

    $('.datai').each(function (i, val) {
        var
            $dataInicial = $(val),
            $dataFinal = $(val).parent().parent().find('.dataf'),
            dataInicial = null,
            dataFinal = null
        ;

        //console.log(dataInicial + ' - ' + dataFinal);
        dataInicial = Date.parseExact($dataInicial.val(), "dd/MM/yyyy");
        dataFinal = Date.parseExact($dataFinal.val(), "dd/MM/yyyy");
        intervalo = date.interval(dataInicial, dataFinal);
        $dataFinal.data('intervalo', intervalo);
        //console.log(intervalo);
    });

    $(".dataf").datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        showOn: "button",
        beforeShowDay: $.datepicker.noWeekends,
        beforeShow: function (selectedDate, inst) {
            var val = $(inst.input).parent().parent().find('.datai').val();
            d = Date.parseExact(val, "dd/MM/yyyy");
            //proximo = dataProximoDiaUtil(d);
            data = date.adicionarDiasUteis(d, $(inst.input).data('intervalo'));

            //var val = $(inst.input).parent().parent().find('.datai').datepicker('getDate');
            //console.log(val);
            $(this).datepicker("option", "minDate", data.toString('dd/MM/yyyy'));
            //$(this).datepicker( "option", "minDate", '+1D' );
        }
    });

    $(".datai").datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        showOn: "button",
        //minDate: -20, maxDate: '+1M +10D',
        beforeShowDay: $.datepicker.noWeekends,
        beforeShow: function (selectedDate, inst) {
            var val = null;
            if ($(inst.input).parent().parent().prev().find('.dataf').length > 0) {
                val = $(inst.input).parent().parent().prev().find('.dataf').val()//.datepicker( "getDate" );     
                d = Date.parseExact(val, "dd/MM/yyyy");
                //console.log(d);
                proximo = date.proximoDiaUtil(d);
                //console.log(proximo);
                //var val = $(inst.input).parent().parent().prev().find('.dataf').val();
                //console.log(val);
                $(this).datepicker("option", "minDate", proximo.toString("dd/MM/yyyy"));
                //$(this).datepicker( "option", "minDate", '+1D' );
                //var val = $(inst.input).parent().parent().next().find('.dataf').datepicker( "option", "minDate", selectedDate );
                //$(inst.input).parent().parent().find('.dataf').datepicker( "option", "minDate", selectedDate );
            }
        }
    });
});