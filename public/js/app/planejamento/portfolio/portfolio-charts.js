$(function () {

    var chart = {};
    chart.tamFonteTitulo = 18;
    chart.tamFonteTooltip = 14;

    function chartOrcamentarioProjetosPrograma(data) {
        $("#chartContainer-orcamentario").dxChart({
            dataSource: data,
            equalBarWidth: {
                width: 50
            },
            commonSeriesSettings: {
                argumentField: 'programa',
                type: "stackedBar",
                hoverMode: "allArgumentPoints",
                selectionMode: "allArgumentPoints",
                label: {
                    visible: true,
                    format: "fixedPoint",
                    precision: 3,
                    customizeText: function () {
                        return 'R$ ' + number_format(this.value, 2, ',', '.');
                    }

                }
            },
            valueAxis: {
                title: {
                    text: "Valores em Milhões"
                }
            },
            series: [
                {valueField: "milhoes", name: "Programa"}
            ],
            title: {
                text: "Total Orçamentário de Projetos por Programa",
                placeholderSize: 50,
                font: {size: chart.tamFonteTitulo}

            },
            legend: {
                position: "inside",
                horizontalAlignment: "right",
                border: {visible: true}
            },
            pointClick: function (point) {
                this.select();
            }
        });
    }

    function chartProjetosPrograma(data) {
        $("#chartContainer-projetos-programa").dxPieChart({
            size: {
                width: 600
            },
            dataSource: data,
            series: [
                {
                    argumentField: "programa",
                    valueField: "totalProjetos",
                    label: {
                        visible: true,
                        percentPrecision: 0,
                        customizeText: function () {
                            return this.percentText;
                        },
                        connector: {visible: true}
                    },
                    legend: {
                        hoverMode: "markPoint",
                        horizontalAlignment: "right"
                    }
                }
            ],
            title: {
                text: "Total de Projetos por Programa",
                placeholderSize: 50,
                font: {size: chart.tamFonteTitulo}
            },
            tooltip: {
                enabled: true,
                percentPrecision: 0,
                font: {size: chart.tamFonteTooltip},
                customizeText: function () {
                    return this.argument + " - " + this.percentText;
                }
            },
            animation: {
                duration: 3000,
                easing: 'linear'
            }
        });
    }


    function chartProjetosNatureza(data) {
        $("#chartContainer-projetos-natureza").dxPieChart({
            size: {
                width: 600
            },
            dataSource: data,
            series: [
                {
                    argumentField: "natureza",
                    valueField: "totalProjetos",
                    label: {
                        visible: true,
                        percentPrecision: 0,
                        customizeText: function () {
                            return this.percentText;
                        },
                        connector: {visible: true}
                    },
                    legend: {
                        hoverMode: "markPoint",
                        horizontalAlignment: "right"
                    }
                }
            ],
            tooltip: {
                enabled: true,
                percentPrecision: 0,
                font: {size: chart.tamFonteTooltip},
                customizeText: function () {
                    return this.argument + " - " + this.percentText;
                }
            },
            animation: {
                duration: 3000,
                easing: 'linear'
            },
            title: {
                text: "Total de Projetos por Natureza",
                font: {size: chart.tamFonteTitulo},
                placeholderSize: 50
            }
        });

    }


    $.ajax({
        url: base_url + "/planejamento/portfolio/chartorcamentarioprojetosprogramajson",
        dataType: 'json',
        data: {idprograma: $('#idescritorio').val()},
        type: 'POST',
        success: function (data) {
//            for(datas in data){
//                
//            console.log(data[datas]);
//            }return false;
            chartOrcamentarioProjetosPrograma(data);
        },
        error: function (x, y, z) {
            chartOrcamentarioProjetosPrograma(0);
        }
    });

    $.ajax({
        url: base_url + "/planejamento/portfolio/chartorcamentarioprojetosprogramajson",
        dataType: 'json',
        data: {idprograma: $('#idescritorio').val()},
        type: 'POST',
        success: function (data) {
            chartProjetosPrograma(data);
        },
        error: function (x, y, z) {
            chartProjetosPrograma(0);
        }
    });

    $.ajax({
        url: base_url + "/planejamento/portfolio/chartprojetosnaturezajson",
        dataType: 'json',
        data: {idprograma: $('#idescritorio').val()},
        type: 'POST',
        success: function (data) {
            chartProjetosNatureza(data);
        },
        error: function (x, y, z) {
            chartProjetosNatureza(0);
        }
    });
});