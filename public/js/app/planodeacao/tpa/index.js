$(function () {

    $('ul').find('a').on("click", function (e) {
        e.preventDefault();
    });

    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $("#importar").select2('data', null);
    });

    var
        $form = $("form#form-gerencia"),
        url_cadastrar = base_url + "/planodeacao/tpa/add/format/json";

    $form.on('submit', function (e) {
        var $return = false;
        var options = {
            url: url_cadastrar,
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                if (typeof data.msg.text != 'string') {
                    $.formErrors(data.msg.text);
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                    return;
                }
                if (data.success) {
                    $return = true;
                    //$("#resetbutton").trigger('click');
                    window.location.href = base_url + "/planodeacao/tpa/informacoesiniciais/idplanodeacao/" + data.dados.idplanodeacao;
                }
                $.pnotify(data.msg);


            },
            error: function (data) {
                $return = false;
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }


        };
        $form.ajaxSubmit(options);
        return $return;
    });

//    $.formErrors = function(data) {
//        $.each(data, function(element, errors) {
//            var ul = $("<ul>").attr("class", "errors help-inline");
//            $.each(errors, function(name, message) {
//                ul.append($("<li>").text(message));
//            });
//            $("#" + element).parent().find('ul').remove();
//            $("#" + element).after(ul);
//        });
//    }
});


$(document).ready(function () {
    $("ul").find('li').removeClass('disabled').addClass('enabled');
    $('ul').find('a').unbind('click');
});

function visualizar($div) {
    $('.informacoes').hide();
    $('#' + $div).show();
}