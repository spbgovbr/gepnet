var caps = null;
$(function () {
    $.pnotify.defaults.history = false;
    var $form = $("form#perfil");
    //$senha = $("input#senha");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            $.ajax({
                url: base_url + "/log/in/format/json",
                dataType: 'json',
                type: 'POST',
                async: true,
                cache: true,
                data: $form.serialize(),
                processData: false,
                success: function (data) {
                    if (typeof data.msg.text != 'string') {
                        $.formErrors(data.msg.text);
                        return;
                    }
                    $.pnotify(data.msg);
                    if (data.success) {
                        window.location = data.redirect;
                    }
                },
                error: function () {
                    //$('div#noticia').html('Em manuten&ccedil;&atilde;o');
                }
            });
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
    /*
    $senha.keypress(function(e){
        var _popover = $form.popover({
            trigger: 'manual',
            content: 'A tecla CAPS LOCK foi acionada',
            template: '<div class="popover" id="caps"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p></p></div></div></div>'
        });

        var s = String.fromCharCode( e.keyCode || e.which );
        if (s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey) {
            if(!caps){
                $form.popover('show'); 
            }
            caps = true;
        } else {
            if(caps){
                $form.popover('hide'); 
            }
            caps = false;
        }
    });
    */
});



