$(function () {

    $.pnotify.defaults.history = false;

    var
        $form = $("form#form-status-report");
    url_cadastrar = base_url + "/projeto/relatorio/add/format/json";

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
//        submitHandler: function(form) {
//            enviar_ajax("/projeto/relatorio/add/format/json", "form#form-status-report", function(data) {
////                if (data.success) {
////                    window.location.href = base_url + "/projeto/relatorio/index/idprojeto/" + data.idprojeto;
////                }
//            });
//        }
        submitHandler: function (form) {
            var options = {
                url: url_cadastrar,
                dataType: 'json',
                type: 'POST',
                success: function (data) {
                    if (typeof data.msg.text != 'string') {
                        $.formErrors(data.msg.text);
                        return;
                    }
                    $.pnotify(data.msg);
                    if (data.success) {
                        $("#resetbutton").trigger('click');
                    }
                }

            };
            $form.ajaxSubmit(options);
        }
    });

//    $(".pessoa-button").on('click', function(event) {
//        event.preventDefault();
//        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
//        $(this).closest('.control-group').addClass('input-selecionado');
//        if ($("table#list-grid-pessoa").length <= 0) {
//            $.ajax({
//                url: base_url + "/cadastro/pessoa/grid",
//                type: "GET",
//                dataType: "html",
//                success: function(html) {
//                    $(".grid-append").append(html).slideDown('fast');
//                }
//            });
//            $('.pessoa-button')
//                .off('click')
//                .on('click',function() {
//                    var $this = $(this);
//                    $(".grid-append").slideDown('fast', function(){
//                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
//                        $this.closest('.control-group').addClass('input-selecionado');
//                    });
//                });
//        } 
//    });
});


