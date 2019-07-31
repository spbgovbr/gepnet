$(function () {

    $(document.body).on('click', ".comments", function (event) {
        event.preventDefault();
        var comentario = $(this).data("text");
        console.log($(this).data("text"));
        $.ajax({
            url: base_url + '/projeto/cronograma/excluircomentariojson/format/json',
            dataType: 'json',
            type: 'POST',
            async: true,
            cache: true,
            data: {idcomentario: comentario},
            success: function (data) {
                if (data.success) {
                    form.load('' + urlComentario + '');
                    $.pnotify(data.msg);
                    CRONOGRAMA.retornaProjeto();
                } else {
                    $.pnotify(data.msg);
                }
            },
            error: function () {
                $('#dialog-comentario').dialog('close');
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

});