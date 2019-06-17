$(function () {
    //var idperfil = $("select#idperfil").val();
    permissao.init();
    //XXXXXXXXXX NOVOS RECURSOS XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    tableToGrid("table#novos-recursos",
        {
            width: '800',
            height: '300px',
            rownumbers: true

        });
    $("#novos-recursos").jqGrid('setLabel', 'rn', 'Ord');
    $("#novos-recursos").jqGrid('setCaption', 'Novos Recursos');

    $("#novos-recursos").on("click", "a.cadastrar_recurso", function (event) {
        event.preventDefault();
        var $this = $(this);
        permissao.cadastrar($this);
    });

    resizeGrid();
    $('#recursos').removeClass('region-west');
    //$('.k-last').remove();
});