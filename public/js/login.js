function enviar_ajax(url, form, callback)
{
    $.ajax({
        url: base_url + url,
        dataType: 'json',
        type: 'POST',
        data: $(form).serialize(),
        //processData:false,
        success: function(data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (callback && typeof(callback) === "function") {
                callback(data);
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
};

$(function() {

    $.pnotify.defaults.history = false;
    
    $(document).ajaxStart(function() {
        $("div#ajax-indicator").show();
    }).ajaxStop(function() {
        $("div#ajax-indicator").hide();
    });
    
    $.formErrors = function(data) {
        $.each(data, function(element, errors) {
            //var ul = $("<ul>").attr("class", "errors help-inline");
            var ul = $("<ul>").attr("class", "errors");
            $.each(errors, function(name, message) {
                ul.append($("<li>").text(message));
            });
            $("#" + element).parent().find('ul').remove();
            $("#" + element).after(ul);
        });
    }

    $("a.link_sair").click(function() {
        var url = "/index/logout";
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'GET',
            //data: $(form).serialize(),
            //processData:false,
            success: function(data) {
                location.href= base_url + "/index/index"
            },
            error: function() {
                window.close();
            }
        });
        return false;
    });
    $('#dialog-perfil').dialog({
        autoOpen: false,
        title: 'Mudar Perfil',
        width: '550px',
        modal: false,
        open: function(event, ui) {
            $("#idperfil").select2();
        },
        close: function(event, ui) {
//$dialogEditar.empty();
        },
        buttons: {
            'Fechar': function() {
                $(this).dialog('close');
            },
            'Enviar': function() {
//console.log('submit');
//$formEditar.on('submit');
                $("form#form-perfil").trigger('submit');
            }
        }
    });
    $('#form-perfil').submit(function(event) {
        event.preventDefault();
        var url = '/index/mudar-perfil/format/json',
                $this = $(this);
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: $this.serialize(),
            //processData:false,
            success: function(data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                $.pnotify(data.msg);
                location.href = base_url + '/index/boas-vindas';
            },
            error: function() {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });
    $("a.link_perfil").click(function() {
        $('#dialog-perfil').dialog('open');
    });
});

