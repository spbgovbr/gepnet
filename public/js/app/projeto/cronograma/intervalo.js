Intervalo = (function () {
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

    adicionarDias = function (data, dias) {
        var tam = dias, i = 0, total = dias,
            aux = Date.parseExact(data, "dd/MM/yyyy"),
            dataTotal = Date.parseExact(data, "dd/MM/yyyy");

        if (aux !== null) {
            if (tam > 0) {
                do {
                    aux.addDays(1);
                    i++;
                } while (i < tam);
            }

            dataTotal.addDays(total);
            return dataTotal.toString('dd/MM/yyyy');
        }
        return false;
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
        adicionarDias: adicionarDias,
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