function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    $.pnotify.defaults.history = false;

    var $form = $("form#form-portfolio-editar");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/planejamento/portfolio/editar/format/json", "form#form-portfolio-editar", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
        }
    });


    $(document.body).on('click', ".pessoa-button", function (event) {
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

    $(document.body).on('change', "#idescritorio", function (event) {
        event.preventDefault();
        var $id = $(this).val();
        $('.dados-escritorio').hide();

        $.ajax({
            url: base_url + '/cadastro/escritorio/buscar-escritorio/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idescritorio': $id
            },
            success: function (data) {
                $('.dados-escritorio').show();
                //console.log(data);
                $('.email').html("Email Escritório: <br/>" + data.desemail);
                $('.telefone').html("Telefone Escritório: <br/>" + data.numfone);
            },
            error: function () {
                /*$.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });*/
            }
        });
    });


});