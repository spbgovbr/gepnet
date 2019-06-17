$(function () {
    $("form#form-responder-pesquisa").validate();
    $('.mask-date').mask('99/99/9999');
    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $('#salvar').click(function (e) {
        e.preventDefault();
        enviar_ajax("/pesquisa/responder/responder-externa/idpesquisa/" + $('#idpesquisa').val() + "/format/json", "form#form-responder-pesquisa", function (data) {
            if (data.success) {
                window.setTimeout(function () {
                    window.location = base_url + "/pesquisa/responder/listar"
                }, 2000);
            }
        });

    });

});