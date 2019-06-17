/**
 * Comment
 */
function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {
    $.pnotify.defaults.history = false;

    //$(".select2").select2();

    var $form = $("form#form-processo-editar");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/processo/index/add/format/json", "form#form-processo-editar", function (data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
            //console.log('enviando');
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
});
/*
 *
 *
 *,
 function(event){
 event.preventDefault();
 $(this).closest('.control-group').nextAll().removeClass('input-selecionado');
 $(".grid-append").slideUp('slow');
 }
 **/