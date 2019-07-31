jQuery.validator.addMethod("notequal", function (value, element, param) {
    return this.optional(element) || value != $(param).val();
}, "Por Favor entre com um valor diferente");

var hora = new RegExp(/^(([0-1{0}][0-9{1}])|([2][0-3]{1})):([0-5{0}][0-9{1}]):([0-5{0}][0-9{1}])/);
jQuery.validator.addMethod("hora", function (value, element) {
    if (!this.optional(element) && (value == "__:__:__" || value == "")) {
        return true;
    }
    if (value.length == 8 && !value.match(hora)) {
        return false;
    }

    return true;
}, "Hora inválida.");

jQuery.validator.addMethod("dateITA", function (value, element) {
    var check = false;
    var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
    if (re.test(value)) {
        var adata = value.split('/');
        var gg = parseInt(adata[0], 10);
        var mm = parseInt(adata[1], 10);
        var aaaa = parseInt(adata[2], 10);
        var xdata = new Date(aaaa, mm - 1, gg);
        if (((xdata.getFullYear() === aaaa) && (xdata.getMonth() === mm - 1) && (xdata.getDate() === gg)) || (value === '__/__/____')) {
            check = true;
        } else {
            check = false;
        }
    } else if (value == '__/__/____') {
        check = true;
    } else {
        check = false;
    }
    return this.optional(element) || check;
}, "Data inválida.");


$.validator.setDefaults({
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
        $element.parents("div.control-group").removeClass(errorClass)/*.addClass(validClass)*/;

        if ($element.parents("div.control-group").find("." + errorClass).length == 0) {
            // Only remove the class if there are no other errors
            $element.parents("div.control-group").removeClass("error");
        }
    }
});

$.formErrors = function (data) {
    $.each(data, function (element, errors) {
        //var ul = $("<ul>").attr("class", "errors help-inline");
        var ul = $("<ul>").attr("class", "errors");
        $.each(errors, function (name, message) {
            ul.append($("<li>").text(message));
        });
        $("#" + element).parent().find('ul').remove();
        $("#" + element).after(ul);
    });
}

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
                callback(data);
            }
            $.pnotify(data.msg);
        },
        error: function () {
            $.pnotify({
                //text: 'Falha ao enviar a requisição 4444',
                text: 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).',
                type: 'error',
                hide: false
            });
        }
    });
}