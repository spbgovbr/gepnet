function chartatraso(data) {
    $("#chartcontainer-atraso").dxChart({
        dataSource: data,
        commonSeriesSettings: {
            argumentField: 'data'
        },
        series: [
            {name: 'Atraso', valueField: 'Atraso'}
        ],
        argumentAxis: {
            grid: {
                visible: true
            }
        },
        tooltip: {
            enabled: true
        },
        //title: " Evolução Atraso ",
        legend: {
            verticalAlignment: "right",
            horizontalAlignment: "top"
        },
        commonPaneSettings: {
            border: {
                visible: false,
                right: true
            }
        },
        export: {
            enabled: true,
            printingEnabled: true
        }
    });
}

function chartprazo(data) {
    var numcriteriofarol = $('#cf').val() ? $('#cf').val() : 30;
    var inicio = data < 0 ? data : -15;
    $("#chartcontainer-criteriofarol-atraso").dxCircularGauge({
        scale: {
            startValue: inicio,
            endValue: parseInt(data) + parseInt(numcriteriofarol),
            majorTick: {
                tickInterval: 45
            }
        },
        rangeContainer: {
            backgroundColor: "none",
            ranges: [
                {
                    startValue: inicio,
                    endValue: 0,
                    color: "#A6C567"
                },
                {
                    startValue: 0,
                    endValue: numcriteriofarol,
                    color: "#FCBB69"
                },
                {
                    startValue: numcriteriofarol,
                    endValue: parseInt(data) + parseInt(numcriteriofarol),
                    color: "#E19094"
                }
            ]
        },
        needles: [{value: data}],
        markers: [{value: data}]/*,
        title: " Prazo "*/
    });
}

function chartplanejadorealizado(data) {
    $("#chartcontainer-planejado-realizado").dxChart({
        dataSource: data,
        commonSeriesSettings: {
            argumentField: 'data'
        },
        series: [
//           {name: 'Data', valueField: 'data'},
            {name: 'Planejado', valueField: 'Planejado'},
            {name: 'Realizado', valueField: 'Realizado'}

        ],
        argumentAxis: {
            grid: {
                visible: true
            }
        },
        tooltip: {
            enabled: true
        },
        // title: " %Concluído ( Planejado x Realizado) ",
        legend: {
            verticalAlignment: "right",
            horizontalAlignment: "top"
        },
        commonPaneSettings: {
            border: {
                visible: false,
                right: true
            }
        }
    });
}

function gerachartacompanhamento() {
    $.ajax({
        url: base_url + "/planodeacao/statusreport/chartplanejadorealizadojson",
        dataType: 'json',
        type: 'POST',
        data: {idplanodeacao: $('#idplanodeacao').val()},
        success: function (data) {
            chartplanejadorealizado(data);
        },
        error: function () {
//            $.pnotify({
//                text: 'Falha ao renderizar gráfico <b>Planejado x Realizado</b>',
//                type: 'error',
//                hide: false
//            });
            chartplanejadorealizado(0);
        }
    });
}

function gerachartatraso() {
    $.ajax({
        url: base_url + "/planodeacao/statusreport/chartatrasojson",
        dataType: 'json',
        type: 'POST',
        data: {idplanodeacao: $('#idplanodeacao').val()},
        success: function (data) {
            chartatraso(data);
        },
        error: function () {
//            $.pnotify({
//                text: 'Falha ao renderizar gráfico <b>Evolução Atraso</b>',
//                type: 'error',
//                hide: false
//            });
            chartatraso(0);
        }
    });
}

function gerachartprazo() {
    $.ajax({
        url: base_url + "/planodeacao/statusreport/chartprazojson",
        dataType: 'json',
        type: 'POST',
        data: {idplanodeacao: $('#idplanodeacao').val(), idstatusreport: $('#idst').val()},
        success: function (data) {
            chartprazo(data.prazo);
        },
        error: function () {
//            $.pnotify({
//                text: 'Falha ao renderizar gráfico <b>Farol Atraso</b>',
//                type: 'error',
//                hide: false
//            });
            chartprazo(0);
        }
    });
}

$(function () {

    gerachartacompanhamento();

    gerachartatraso();

    gerachartprazo();


    var risco = 0;
    if ($('#risco').val() == 1) {
        risco = 17; //Semaforo verde
    } else if ($('#risco').val() == 2) {
        risco = 50; //Semaforo amarelo
    } else {
        risco = 80; //Semaforo vermelho
    }
    $("#chartcontainer-criteriofarol-risco").dxCircularGauge({
        scale: {
            startValue: 0,
            endValue: 100,
            majorTick: {
                tickInterval: 5
            }
        },
        rangeContainer: {
            backgroundColor: "none",
            ranges: [
                {
                    startValue: 0,
                    endValue: 35,
                    color: "#A6C567"
                },
                {
                    startValue: 35,
                    endValue: 65,
                    color: "#FCBB69"
                },
                {
                    startValue: 65,
                    endValue: 100,
                    color: "#E19094"
                }
            ]
        },
        needles: [{value: risco}]/*,
        title: " Risco "*/
    });

    var marco = 10;
    if ($('#dm').val() >= $('#cf').val()) {
        marco = 80; //Semaforo vermelho
    } else if ($('#dm').val() > 0) {
        marco = 50; //Semaforo amarelo
    } else {
        marco = 17; //Semaforo verde
    }
    $("#chartcontainer-criteriofarol-marco").dxCircularGauge({
        scale: {
            startValue: 0,
            endValue: 100,
            majorTick: {
                tickInterval: 5
            }
        },
        rangeContainer: {
            backgroundColor: "none",
            ranges: [
                {
                    startValue: 0,
                    endValue: 35,
                    color: "#A6C567"
                },
                {
                    startValue: 35,
                    endValue: 65,
                    color: "#FCBB69"
                },
                {
                    startValue: 65,
                    endValue: 100,
                    color: "#E19094"
                }
            ]
        },
        needles: [{value: marco}]/*,
        title: " Marco "*/
    });

});

