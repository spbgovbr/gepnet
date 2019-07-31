/**
 * Comment
 */

$(function () {
    $("#menuquest")
        .find('li').addClass('disabled')
        .end()
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menuquest')
        .find('a').unbind('click');

    $('#menuquest')
        .find('a').on("click", function (e) {
        e.preventDefault();
    });

    $.pnotify.defaults.history = false;

    $("body").on("click", "#submitbutton", function () {
        $form.validate().form();
        if ($form.valid()) {
            var param = $formInserir.serialize();
            $.ajax({
                url: base_url + '/diagnostico/questionario/add/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {
                    if (data.msg.type == 'success') {
                        $.pnotify(data.msg.text);
                        window.location.href = base_url + "/diagnostico/questionario/dadosbasicos/idquestionariodiagnostico/" + data.msg.idquestionariodiagnostico;
                    } else {
                        $.pnotify(data.msg.text);
                    }
                },
                error: function () {
                    $.pnotify({
                        text: msgerroacesso,
                        type: 'error',
                        hide: false
                    });
                }
            });
        } else {
            return false;
        }
    });

});