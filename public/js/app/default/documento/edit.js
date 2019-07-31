$(function () {
    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });
    var $formEditar = $("form#documento-editar");

    $formEditar.validate({
        errorClass: 'error',
        validClass: 'success',

        highlight: function (element, errorClass, validClass) {
            //console.log('wilton');
            var $element;
            if (element.type === 'radio') {
                $element = this.findByName(element.name);
            } else {
                $element = $(element);
            }
            $element.addClass(errorClass).removeClass(validClass);
            // add the bootstrap error class
            $element.parents("div.control-group").addClass("error").removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            //console.log('unwilton');
            var $element;
            if (element.type === 'radio') {
                $element = this.findByName(element.name);
            } else {
                $element = $(element);
            }
            $element.removeClass(errorClass).addClass(validClass);
            // remove the bootstrap error class
            $element.parents("div.control-group").removeClass(errorClass);

            if ($element.parents("div.control-group").find("." + errorClass).length == 0) {
                // Only remove the class if there are no other errors
                $element.parents("div.control-group").removeClass("error");
            }
        },
        submitHandler: function (form) {
            $.ajax({
                url: base_url + "/documento/editar/format/json",
                dataType: 'json',
                type: 'POST',
                async: true,
                cache: true,
                data: $formEditar.serialize(),
                processData: false,
                success: function (data) {
                    if (typeof data.msg.text != 'string') {
                        $.formErrors(data.msg.text);
                        return;
                    }
                    $.pnotify(data.msg);
                    if (data.success) {
                        //location.href = base_url + data.redirect;
                    }
                },
                error: function () {
                    //$('div#noticia').html('Em manuten&ccedil;&atilde;o');
                }
            });
            return false;
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



