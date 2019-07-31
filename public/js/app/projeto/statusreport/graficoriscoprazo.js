$(document).ready(function () {
    montaChartPrazo();
    montaChartRisco();
    montaChartMarco();
});

function montaChartPrazo() {
    var data = $('#uPrazo').val() ? $('#uPrazo').val() : 0;
    data = parseInt(data);
    var numcriteriofarol = $('#cf').val() ? $('#cf').val() : 30;
    var inicio = data < 0 ? data : -15;
    var numMaxInterval = parseInt(data) + parseInt(numcriteriofarol);
    var numMax = parseInt(numMaxInterval) + 15;
    var intervalIni = 0;
    s1 = [data];
    plotatrasojp = $.jqplot('chartcontainer-criteriofarol-atrasojp', [s1], {
        seriesDefaults: {
            renderer: $.jqplot.MeterGaugeRenderer,
            rendererOptions: {
                min: inicio,
                max: numMax,
                intervals: [intervalIni, numcriteriofarol, numMax],
                intervalColors: ['#A6C567', '#FCBB69', '#E19094'],
                label: data,
                labelPosition: 'inside',
                pad: 0,
                sliceMargin: 1,
                intervalOuterRadius: 139,
                intervalInnerRadius: 144,
                hubRadius: 10,
                background: "#FFFFFF",
                showTickLabels: true,
                needlePad: 6,
                ringColor: '#616161', //'#AEAEAE',
                padding: 3,
                tickColor: '#616161', //'#747474',
                dataLabelPositionFactor: 0.5,
                ringWidth: 0.1,
                showDataLabels: true,
                dataLabelThreshold: 1//,
                //dataLabelPositionFactor: 1.1
            },
        }
    });
}

function montaChartRisco() {
    var risco = 0;
    if ($('#risco').val() == 1) {
        risco = 17; //Semaforo verde
    } else if ($('#risco').val() == 2) {
        risco = 50; //Semaforo amarelo
    } else {
        risco = 80; //Semaforo vermelho
    }
    pointRisco = [risco];
    plot3 = $.jqplot('chartcontainer-criteriofarol-riscojp', [pointRisco], {
        seriesDefaults: {
            renderer: $.jqplot.MeterGaugeRenderer,
            rendererOptions: {
                min: 0,
                max: 100,
                ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                intervals: [35, 65, 100],
                intervalColors: ['#A6C567', '#FCBB69', '#E19094'],
                label: risco,
                labelPosition: 'inside',
                sliceMargin: 1,
                intervalOuterRadius: 139,
                intervalInnerRadius: 144,
                hubRadius: 10,
                background: "#FFFFFF",
                showTickLabels: true,
                needlePad: 6,
                ringColor: '#616161', //'#AEAEAE',
                padding: 3,
                tickColor: '#616161', //'#747474',
                dataLabelPositionFactor: 0.5,
                ringWidth: 0.1
            },
        }
    });
}

function montaChartMarco() {
    var marco = 10;
    if ($('#dm').val() >= $('#cf').val()) {
        marco = 80; //Semaforo vermelho
    } else if ($('#dm').val() > 0) {
        marco = 50; //Semaforo amarelo
    } else {
        marco = 17; //Semaforo verde
    }
    pointMarco = [marco];
    plot3 = $.jqplot('chartcontainer-criteriofarol-marcojp', [pointMarco], {
        seriesDefaults: {
            renderer: $.jqplot.MeterGaugeRenderer,
            rendererOptions: {
                min: 0,
                max: 100,
                ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                intervals: [35, 65, 100],
                intervalColors: ['#A6C567', '#FCBB69', '#E19094'],
                label: marco,
                labelPosition: 'inside',
                sliceMargin: 1,
                intervalOuterRadius: 139,
                intervalInnerRadius: 144,
                hubRadius: 10,
                background: "#FFFFFF",
                showTickLabels: true,
                needlePad: 6,
                ringColor: '#616161', //'#AEAEAE',
                padding: 3,
                tickColor: '#616161', //'#747474',
                dataLabelPositionFactor: 0.5,
                ringWidth: 0.1
            },
        }
    });
}
