/**
 * Comment
 */
function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

function fillSelect($num){
    $idobjetivo = $("#idobjetivo").val() ? $("#idobjetivo").val() : -1;
    $.ajax({
        url: base_url + "/projeto/tap/acao/idobjetivo/" + $idobjetivo,
        dataType: 'json',
        type: 'GET',
        async: true,
        cache: true,
        processData: false,
        success: function(data) {
//            console.log($num);
            var options = $("#idacao");
            if(data){
                options.empty();
                options.append($("<option />").val("").text("Selecione"));
                $.each(data, function() {
                    options.append($("<option />").val(this.idacao).text(this.nomacao));
                });
                options.find('option[value='+ $num +']').attr('selected', 'selected');
            }
        },
        error: function() {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    });
}

$(function() {
    $("#menutap")
           .find('li').addClass('disabled')
           .end()
           .find('li.active')
           .removeClass('disabled')
           .addClass('enabled');
    $('#menutap')
            .find('a').unbind('click');
    
    $('#menutap')
            .find('a').on("click", function(e) {
        e.preventDefault();
    });

    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("#resetbutton").click(function() {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
            $form = $("form#form-gerencia"),
            url_cadastrar = base_url + "/projeto/tap/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function(form) {
            enviar_ajax("/projeto/tap/add/format/json", "form#form-gerencia", function(data) {
                if (data.success) {
                    window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.dados.idprojeto;
                }
            });
        }
    });
    
    $(".pessoa-button").on('click', function(event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid",
                type: "GET",
                dataType: "html",
                success: function(html) {
                    $(".grid-append").append(html).slideDown('fast');
                }
            });
            $('.pessoa-button')
                .off('click')
                .on('click',function() {
                    var $this = $(this);
                    $(".grid-append").slideDown('fast', function(){
                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                        $this.closest('.control-group').addClass('input-selecionado');
                    });
                });
        } 
    });
    
    $("body").on("focusin","#vlrorcamentodisponivel", function(){
        $this = $(this);
        if (!$this.data('formatCurrencyAttached'))
        {
            $this.data('formatCurrencyAttached', true);
            $this.formatCurrency({
                                decimalSep   : ',',
                                thousandsSep : '.',
                                digits       : 2
                        }).trigger('keypress');
        }
    });

    $('#idobjetivo').change( function() {
        fillSelect();
    });

    fillSelect($("#idacao").val());
});


