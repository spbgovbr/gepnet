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
        title: {
            text: 'Relatório',
            horizontalAlignment: 'center',
            verticalAlignment: 'bottom',
            font: {
                //color: 'black',
                color: '#3A3636',
                family: 'Verdana, Arial',
                //opacity: 4.75,
                size: 11,
                weight: "bold",
            }
        },
        valueAxis: {
            position: "left",
            title: {
                text: "Atraso (dias)",
                font: {
                    color: '#3A3636',
                    family: 'Verdana, Arial',
                    size: 11,
                    weight: "bold",
                }
            }
        },
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
    var gauge = $("#chartcontainer-criteriofarol-atraso").dxCircularGauge({
        scale: {
            startValue: inicio,
            endValue: parseInt(data) + parseInt(numcriteriofarol),
            majorTick: {
                tickInterval: 45
            },
            label: {
                visible: false,
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
    return gauge;
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
        title: {
            text: 'Relatório',
            horizontalAlignment: 'center',
            verticalAlignment: 'bottom',
            font: {
                //color: 'black',
                color: '#3A3636',
                family: 'Verdana, Arial',
                //opacity: 4.75,
                size: 11,
                weight: "bold",
            }
        },
        valueAxis: {
            position: "left",
            title: {
                text: "% Concluído",
                font: {
                    color: '#3A3636',
                    family: 'Verdana, Arial',
                    size: 11,
                    weight: "bold",
                }
            }
        },
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
        url: base_url + "/projeto/statusreport/chartplanejadorealizadojson",
        dataType: 'json',
        type: 'POST',
        data: {idprojeto: $('#ip').val(), idstatusreport: $('#idst').val()},
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
        url: base_url + "/projeto/statusreport/chartatrasojson",
        dataType: 'json',
        type: 'POST',
        data: {idprojeto: $('#ip').val(), idstatusreport: $('#idst').val()},
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
        url: base_url + "/projeto/statusreport/chartprazojson",
        dataType: 'json',
        type: 'POST',
        data: {idprojeto: $('#ip').val(), idstatusreport: $('#idst').val()},
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

function gerachartPercentualConcluidoMarco() {
    $.ajax({
        url: base_url + "/projeto/statusreport/chartmarcoreljson",
        dataType: 'json',
        type: 'POST',
        data: {idprojeto: $('#ip').val(), idstatusreport: $('#idst').val()},
        success: function (data) {
            charMarco(data.prazo);
        },
        error: function () {
//            $.pnotify({
//                text: 'Falha ao renderizar gráfico <b>Farol Atraso</b>',
//                type: 'error',
//                hide: false
//            });
            charMarco(0);
        }
    });
}

function charRisco(riscoValor) {
    var riscoNome = "";
    var riscoCor = "";
    var riscoPos = "";
    var risco = 0;
    if (riscoValor == 1) {
        //Semaforo verde
        risco = 17;
        riscoNome = "Baixo";
        riscoCor = "green";
        riscoPos = "bottom-left";
    } else if (riscoValor == 2) {
        //Semaforo amarelo
        risco = 50;
        riscoNome = "Médio";
        riscoCor = "#FF8C00";// Laranja escuro
        riscoPos = "top-center";
    } else {
        //Semaforo vermelho
        risco = 80;
        riscoNome = "Alto";
        riscoCor = "red";
        riscoPos = "bottom-right";
    }
    $("#chartcontainer-criteriofarol-risco").dxCircularGauge({
        scale: {
            startValue: 0,
            endValue: 100,
            majorTick: {
                tickInterval: 5
            },
            label: {
                visible: false,
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
        needles: [{value: risco}],
        //markers: [{value: risco}],
        title: {
            text: riscoNome,
            font: {
                color: riscoCor,
                family: "Verdana, Arial",
                size: 14,
                weight: "bold"
            },
            position: riscoPos
        }
    });/**/
}

function charMarco(diasm, critf) {
    var marco = 0;
    marco = diasm;
    // if (diasm >= critf) {
    //     marco = 80; //Semaforo vermelho
    // } else if (diasm > 0) {
    //     marco = 50; //Semaforo amarelo
    // } else {
    //     marco = 17; //Semaforo verde
    // }
    $("#chartcontainer-criteriofarol-marco").dxCircularGauge({
        scale: {
            startValue: 0,
            endValue: 100,
            majorTick: {
                tickInterval: 10
            }
        },
        rangeContainer: {
            backgroundColor: "none",
            ranges: [
                {
                    startValue: 0,
                    endValue: 35,
                    color: "#000000"
                },
                {
                    startValue: 35,
                    endValue: 65,
                    color: "#000000"
                },
                {
                    startValue: 65,
                    endValue: 100,
                    color: "#000000"
                }
            ]
        },
        needles: [{value: marco}],
        markers: [{value: marco}]
        /* title: " Marco "*/
    });
}

$(function () {

    gerachartacompanhamento();
    gerachartatraso();
    gerachartprazo();
    gerachartPercentualConcluidoMarco();
    charRisco($('#risco').val());
    charMarco($('#dm').val(), $('#cf').val());

});
