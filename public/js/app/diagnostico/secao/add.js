/**
 * Comment
 */

$(function () {
    $("#menuquest")
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menuquest')
        .find('a').unbind('click');

    $('#menuquest')
        .find('a').on("click", function (e) {
        //e.preventDefault()
        //e.end()
        e.find('li.active')
        e.removeClass('disabled')
        e.addClass('enabled');
    });

    $('#ds_item-in').click(function (e) {
        listLeftMove();
    });

    $('#ds_item-out').click(function (e) {
        listRightMove();
    });

    $('#ds_item').dblclick(function (e) {
        listLeftMove();
    });

    $('#id_secao').dblclick(function (e) {
        listRightMove();
    });

    function listLeftMove() {
        var selectedOpts = $('#ds_item option:selected');
        if (selectedOpts.length === 0) {
            e.preventDefault();
        }

        $('#id_secao').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    function listRightMove() {
        var selectedOpts = $('#id_secao option:selected');
        if (selectedOpts.length === 0) {
            e.preventDefault();
        }

        $('#ds_item').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    $.pnotify.defaults.history = true;


    function enviar_ajax(url, form, callback) {
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: $(form).serialize(),
            //processData:false,
            success: function (data) {
                if (typeof data.msg.text !== 'string') {
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
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    // var
    //     $form = $("form#form-itemsecao"),
    //     url_cadastrar = base_url + "/diagnostico/questionario/secoes-add/format/json";
    //
    // $form.validate({
    //     errorClass: 'error',
    //     validClass: 'success',
    //     submitHandler: function (form) {
    //         enviar_ajax("/diagnostico/questionario/secoes-add/format/json", $form, function (data) {
    //             if (data.success) {
    //                 window.location.href = base_url + "/diagnostico/questionario/secao/id_item/" + data.msg.id_item;
    //             }
    //         });
    //     }
    // });

    $("body").on("click", "#submitbutton", function () {
        var $form = $('#form-itemsecao');
        var i = 0;
        var arrayQuestionario = [],
            $formInserir = $("form#form-itemsecao");
        var arrayQuestTexto = [],
            $formInserirText = $("form#form-itemsecao");
        $("#id_secao option").each(function () {
            arrayQuestionario[i] = $(this).val();
            arrayQuestTexto[i] = $(this).text();
            i++;
        });
        $('#secao').val(arrayQuestionario.join());
        $('#secaoTexto').val(arrayQuestTexto.join());

        $form.validate().form();
        if ($form.valid()) {
            var param = $form.serialize();
            $.ajax({
                url: base_url + '/diagnostico/questionario/secoes-add/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {
                    $.pnotify(data.msg.text);
                    window.location.href = window.location.href;
                },
                error: function () {
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
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