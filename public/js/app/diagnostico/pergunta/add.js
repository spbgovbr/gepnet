/**
 * Comment
 */

$(function () {
    var urls = {
            addPergunta: '/diagnostico/questionario/pergunta-add/format/json'
        },
        $dialogPergunta = $('#dialog-incluir');

    $("#menuquest")
        .find('li.active')
        .removeClass('disabled')
        .addClass('enabled');
    $('#menuquest')
        .find('a').unbind('click');

    $('#menuquest')
        .find('a').on("click", function (e) {
        e.find('li.active')
        e.removeClass('disabled')
        e.addClass('enabled');
    });


});