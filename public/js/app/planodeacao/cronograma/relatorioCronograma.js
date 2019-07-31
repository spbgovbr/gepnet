function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    $("#idprograma").on('change', function (event) {
        var idprograma = $(this).val(),
            nomprograma = $('#idprograma option:selected').text(),
            selectProjetos = $("#idplanodeacaos");
        selectProjetos.html('');
        $("input[name='nomprograma']").val(nomprograma);
        $.ajax({
            url: base_url + "/planodeacao/cronograma/buscarplanodeacao",
            type: "POST",
            dataType: "json",
            data: {idprograma: idprograma},
            success: function (data) {
                selectProjetos.append(new Option('Todos', 0));
                $.each(data, function (i, item) {
                    selectProjetos.append(new Option(item, i));
                });
            }
        });
    });

    $("#idescritorio").on('change', function (event) {
        var idescritorio = $(this).val(),
            nomescritorio = $('#idescritorio option:selected').text(),
            selectProjetos = $("#idplanodeacaos");
        selectProjetos.html('');
        $("input[name='nomescritorio']").val(nomescritorio);
        $.ajax({
            url: base_url + "/planodeacao/cronograma/buscarplanodeacao",
            type: "POST",
            dataType: "json",
            data: {idescritorio: idescritorio},
            success: function (data) {
                selectProjetos.append(new Option('Todos', 0));
                $.each(data, function (i, item) {
                    selectProjetos.append(new Option(item, i));
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

    $('#idplanodeacaos').focusout(function () {
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
        changeYear: true
    });

    $('.date-maskBR').focusin(function () {
        var $this = $(this);
        $(this).mask('99/99/9999');
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    });

    $('#resetbutton').click(function () {
        $('#idplanodeacaos').html('');
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

});

