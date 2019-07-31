function selectRow(row) {
    //console.log(row);
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    $.pnotify.defaults.history = false;

    var $form = $("form#associar-perfil");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/cadastro/perfilpessoa/associarperfil/format/json", "form#associar-perfil", function (data) {
                if (data.success) {

                    /* Após os dados serem cadastrados do presfil a combo select
                      *trás a opção selecione por padrão
                     */
                    $('#idperfil').each(function () {
                        $(this).val('').attr('selected', 'selected');
                    });

                    /* Após os dados serem cadastrados Escritório de Projetos
                     *  a combo select trás a opção selecione por padrão
                     */
                    $('#idescritorio').each(function () {
                        $(this).val('').attr('selected', 'selected');
                    });
                    $("#resetbutton").trigger('click');
                }
            });
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

    $('#perfil').removeClass('region-west');

});

