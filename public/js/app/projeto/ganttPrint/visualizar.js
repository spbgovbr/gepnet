$(function () {

    $('.progress').popover();
    var
        $form = $("form#form-gantt"),
        $dialogMostrargantt = $('#dialog-print')
    ;
    actions = {
        detalhar: {
            urlatividade: base_url + '/projeto/gantt/detalharatividade/',
            urlentrega: base_url + '/projeto/gantt/detalharentrega/',
            dialog: $('#dialog-detalhar')
        },
        mostrargantt: {
            $form: $("form#form-gantt"),
            url: base_url + '/projeto/gantt/mostrargantt/',
            dialog: $('#dialog-print')
        }
    };

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'GANTT - Detalhar Atividade',
        width: 1100,
        height: 800,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.detalhar.dialog.empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);


    /*xxxxxxxxxx PRINT GANTT xxxxxxxxxx*/
    actions.mostrargantt.dialog.dialog({
        autoOpen: false,
        title: 'GANTT - Impressão',
        width: 1400,
        height: 900,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.mostrargantt.dialog.empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);


    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("body").delegate(".accordion-menu", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var accordion = $(this);
        var accordionContent = accordion.next('.accordion-content');
        var nome = accordion.attr('name') + "p";
        $('#' + nome).collapse('toggle');
    });
    $("body").delegate(".accordion-menup", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var accordionp = $(this);
        var accordionContent = accordionp.next('.accordion-content');
        var nome = accordionp.attr('name');
        var nome = nome.substring(0, nome.length - 1);
        $('#' + nome).collapse('toggle');
    });
    $("body").delegate(".detail-atividade", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var form = $('form#form-gantt');
        var idprojeto = form.find('input[id="idprojeto"]').val();
        var idatividade = $this.attr('id');
        var domatividade = $this.attr('dom');
        if (domatividade == "2") {
            actions.detalhar.dialog.dialog("option", "width", 820);
            actions.detalhar.dialog.dialog("option", "height", 510);
            actions.detalhar.dialog.dialog("option", "title", "GANTT - Detalhar Entrega");
            var urlAction = actions.detalhar.urlentrega + 'idprojeto/' + idprojeto + '/idatividadecronograma/' + idatividade + '/domatividade/' + domatividade;
        } else {
            actions.detalhar.dialog.dialog("option", "width", 1100);
            actions.detalhar.dialog.dialog("option", "height", 800);
            actions.detalhar.dialog.dialog("option", "title", "GANTT - Detalhar Atividade");
            var urlAction = actions.detalhar.urlatividade + 'idprojeto/' + idprojeto + '/idatividadecronograma/' + idatividade + '/domatividade/' + domatividade;
        }

        $.ajax({
            url: urlAction,
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.mostrargantt.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });


        $.ajax({
            url: urlAction,
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                actions.detalhar.dialog.html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });


    });


});