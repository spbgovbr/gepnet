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

    $(document.body).on('click', "#ds_item-in", function (event) {
        listLeftMove();
    });
    $(document.body).on('click', "#ds_item-out", function (event) {
        listRightMove();
    });

    $(document.body).on('dblclick', "#ds_item", function (event) {
        listLeftMove();
    });

    $(document.body).on('dblclick', "#id_secao", function (event) {
        listRightMove();
    });

    function listLeftMove() {
        var selectedOpts = $('#ds_item option:selected');
        if (selectedOpts.length === 0) {
            event.preventDefault();
        }

        $('#id_secao').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    function listRightMove() {
        var selectedOpts = $('#id_secao option:selected');
        if (selectedOpts.length === 0) {
            event.preventDefault();
        } else {
            var pergunta = $('#id_secao option:selected').val().toString().replace("{", "").replace("}", "");
            var seletorPergunta = pergunta.split(",");

            if (seletorPergunta[1] > 0) {
                $.pnotify({
                    text: 'Atenção: Esta seção não pode ser removida, porque existe pergunta vinculada.',
                    type: 'warning',
                    hide: true
                });
                return false;
            }
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

    $("body").on("click", "#submitbutton", function () {

        var $form = $('#form-itemsecao-edit');
        var i = 0;
        var arrayQuestionario = [],
            $formInserir = $("form#form-itemsecao-edit");
        var arrayQuestTexto = [],
            $formInserirText = $("form#form-itemsecao-edit");
        $("#id_secao option").each(function () {
            arrayQuestionario[i] = $(this).val();
            arrayQuestTexto[i] = $(this).text();
            i++;
        });
        $('#secao').val(arrayQuestionario.join());
        $('#secaoTexto').val(arrayQuestTexto.join());

        if (arrayQuestionario.join() == null || arrayQuestionario.join() === "") {
            $('#quest-editar').show();
            return false;
        } else {
            $('#quest-editar').hide();
        }

        $form.validate().form();
        if ($form.valid()) {
            var param = $form.serialize();
            $.ajax({
                url: base_url + '/diagnostico/questionario/secoes/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {
                    $.pnotify({
                        text: data.msg.text,
                        type: data.msg.type,
                        hide: data.msg.hide
                    });

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