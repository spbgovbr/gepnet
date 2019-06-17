function enviar_ajax(url, form, callback) {
    $.ajax({
        url: base_url + url,
        dataType: 'json',
        type: 'POST',
        data: $(form).serialize(),
        //processData:false,
        success: function (data) {
            if (typeof data.msg.text != 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            if (callback && typeof (callback) === "function") {
                callback();
            }
            $.pnotify(data.msg);
        },
        error: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    });
}

$(function () {
    $.pnotify.defaults.history = false;
    $(".select2").select2();
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("body").delegate(".datemask-BR", "focusin", function () {
        $(this).mask('99/99/9999');
    });

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-documento"),
        url_cadastrar = base_url + "/cadastro/documento/cadastrar/format/json"
    ;

    $form.validate({
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
                },
                error: function (data) {
                    $.pnotify(data.msg);
                }

            };
            $form.ajaxSubmit(options);
            /*
            $.ajax({
                url: url_cadastrar,
                dataType: 'json',
                type: 'POST',
                async: true,
                cache:true,
                data: $form.serialize(),
                processData:false,
                success: function(data) {
                    if(typeof data.msg.text != 'string'){
                        $.formErrors(data.msg.text);
                        return;
                    } 
                    $.pnotify(data.msg);
                    if(data.success){
                        $("#resetbutton").trigger('click');
                    }
                },
                error: function () {
                //$('div#noticia').html('Em manuten&ccedil;&atilde;o');
                }
            });
            */
        }
    });

//    $.formErrors = function(data) {
//        $.each(data, function(element, errors) {
//            var ul = $("<ul>").attr("class", "errors help-inline");
//            $.each(errors, function(name, message) {
//                ul.append($("<li>").text(message));
//            });
//            $("#" + element).after(ul);
//        });
//    }
});



