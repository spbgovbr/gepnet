function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    $("#idprograma").on('change', function (event) {
        var idprograma = $(this).val(),
            idescritorio = $("#idescritorio").val(),
            nomprograma = $('#idprograma option:selected').text(),
            selectProjetos = $("#idprojetos"),
            selectNaturezas = $("#idnaturezas");
        selectProjetos.html('');
        selectNaturezas.html('');
        $("input[name='nomprograma']").val(nomprograma);
        $.ajax({
            url: base_url + "/projeto/cronograma/buscarprojetos",
            type: "POST",
            dataType: "json",
            data: {
                idprograma: idprograma,
                idescritorio: idescritorio
            },
            success: function (data) {
                selectProjetos.append(new Option('Todos', 0));
                $.each(data, function (i, item) {
                    selectProjetos.append(new Option(item, i));
                });
            }
        });
        $.ajax({
            url: base_url + "/projeto/cronograma/buscarnaturezas",
            type: "POST",
            dataType: "json",
            data: {
                idprograma: idprograma,
                idescritorio: idescritorio
            },
            success: function (data) {
                selectNaturezas.append(new Option('Todos', 0));
                $.each(data, function (i, item) {
                    selectNaturezas.append(new Option(item, i));
                });
            }
        });
    });

    $("#idescritorio").on('change', function (event) {
        var idescritorio = $(this).val(),
            idprograma = $("#idprograma").val(),
            nomescritorio = $('#idescritorio option:selected').text(),
            selectProjetos = $("#idprojetos"),
            selectNaturezas = $("#idnaturezas");
        selectProjetos.html('');
        selectNaturezas.html('');
        $("input[name='nomescritorio']").val(nomescritorio);
        $.ajax({
            url: base_url + "/projeto/cronograma/buscarprojetos",
            type: "POST",
            dataType: "json",
            data: {
                idescritorio: idescritorio,
                idprograma: idprograma
            },
            success: function (data) {
                selectProjetos.append(new Option('Todos', 0));
                $.each(data, function (i, item) {
                    selectProjetos.append(new Option(item, i));
                });
            }
        });
        $.ajax({
            url: base_url + "/projeto/cronograma/buscarnaturezas",
            type: "POST",
            dataType: "json",
            data: {
                idescritorio: idescritorio,
                idprograma: idprograma
            },
            success: function (data) {
                selectNaturezas.append(new Option('Todos', 0));
                $.each(data, function (i, item) {
                    selectNaturezas.append(new Option(item, i));
                });
            }
        });
    });

    $("#domstatusprojeto").on('change', function (event) {
        var statusprojeto = $('#domstatusprojeto option:selected').text();
        $("input[name='statusprojeto']").val(statusprojeto);
    });

    $("#statusatividade").on('change', function (event) {
        var statusatividade = $('#statusatividade option:selected').text();
        $("input[name='nomstatusatividade']").val(statusatividade);
    });

    $("#idelementodespesa").on('change', function (event) {
        var nomelementodespesa = $('#idelementodespesa option:selected').text();
        $("input[name='nomelementodespesa']").val(nomelementodespesa);
    });

    $('#nomresponsavel').focusout(function () {
        $("input[name='nomresponsavel']").val($(this).val());
    });

    $('#idprojetos').focusout(function () {
        var ids = $(this).val();
        if (ids == '0') {
            $("input[name='projetos']").val('Projeto: Todos');
        } else {
            $("input[name='projetos']").val('Código interno dos Projetos: ' + ids.join());
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

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true,
        //beforeShowDay: nonWorkingDates
    });

    function nonWorkingDates(date) {
        var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5,
            Saturday = 6;

//        FERIADOS:
//        anoNovo              = "01/01/"+ano;
//        tiradentes           = "21/04/"+ano;
//        diaTrabalhador       = "01/05/"+ano;      
//        independencia        = "07/09/"+ano;
//        aparecida            = "12/10/"+ano;  
//        finados              = "02/11/"+ano;
//        proclamacaoRepublica = "15/11/"+ano;
//        natal                = "25/12/"+ano;

        //mes,dia,ano
        var closedDates = [[1, 1, date.getFullYear()], [4, 21, date.getFullYear()], [5, 1, date.getFullYear()], [9, 7, date.getFullYear()],
            [10, 12, date.getFullYear()], [11, 2, date.getFullYear()], [11, 15, date.getFullYear()], [12, 25, date.getFullYear()]];

        var closedDays = [[Sunday], [Saturday]];
        for (var i = 0; i < closedDays.length; i++) {
            if (day === closedDays[i][0]) {
                return [false];
            }
        }
        for (i = 0; i < closedDates.length; i++) {
            if (date.getMonth() === closedDates[i][0] - 1 &&
                date.getDate() === closedDates[i][1] &&
                date.getFullYear() === closedDates[i][2]) {
                return [false];
            }
        }
        return [true];
    };

    $('.date-maskBR').focusin(function () {
        var $this = $(this);
        $(this).mask('99/99/9999');
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            //beforeShowDay: nonWorkingDates
        });

        function nonWorkingDates(date) {
            var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5,
                Saturday = 6;

            //        FERIADOS:
            //        anoNovo              = "01/01/"+ano;
            //        tiradentes           = "21/04/"+ano;
            //        diaTrabalhador       = "01/05/"+ano;
            //        independencia        = "07/09/"+ano;
            //        aparecida            = "12/10/"+ano;
            //        finados              = "02/11/"+ano;
            //        proclamacaoRepublica = "15/11/"+ano;
            //        natal                = "25/12/"+ano;

            //mes,dia,ano
            var closedDates = [[1, 1, date.getFullYear()], [4, 21, date.getFullYear()], [5, 1, date.getFullYear()], [9, 7, date.getFullYear()],
                [10, 12, date.getFullYear()], [11, 2, date.getFullYear()], [11, 15, date.getFullYear()], [12, 25, date.getFullYear()]];

            var closedDays = [[Sunday], [Saturday]];
            for (var i = 0; i < closedDays.length; i++) {
                if (day === closedDays[i][0]) {
                    return [false];
                }
            }
            for (i = 0; i < closedDates.length; i++) {
                if (date.getMonth() === closedDates[i][0] - 1 &&
                    date.getDate() === closedDates[i][1] &&
                    date.getFullYear() === closedDates[i][2]) {
                    return [false];
                }
            }
            return [true];
        };
    });

    $('#resetbutton').click(function () {
        $('#idprojetos').html('');
    });

    var
        $form = $("form#rel-cronograma")
    ;

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#voltar').click(function () {
        window.location.href = base_url + "/projeto/cronograma/relatorio-cronograma";
    });

    $('#link-pdf').click(function (event) {
        event.preventDefault();
        $('#form-rel').submit();
        return false;
    });

    $('#link-csv').click(function (event) {
        event.preventDefault();
        $('#form-csv').submit();
        return false;
    });

});
