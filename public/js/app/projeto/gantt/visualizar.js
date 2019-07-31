$(function () {

    $('.progress').popover();
    vGerar = true;
    var
        $form = $("form#form-gantt"),
        valorGrupo = $form.find('select[id="idgrupo"] option:selected').val(),
        valorEntrega = $form.find('select[id="identrega"] option:selected').val(),
        grupoCons = $form.find('input[id="idgrupo_cons"]').val(),
        entregaCons = $form.find('input[id="identrega_cons"]').val(),
        idativida_cons = $form.find('input[id="idatividadecronograma_cons"]').val(),
        idamarco_cons = $form.find('input[id="idatividademarco_cons"]').val()
    ;
    actions = {
        detalhar: {
            urlatividade: base_url + '/projeto/gantt/detalharatividade/',
            urlentrega: base_url + '/projeto/gantt/detalharentrega/',
            dialog: $('#dialog-detalhar')
        },
        gerargantt: {
            form: $("form#form-gerargantt"),
            url: base_url + '/projeto/gantt/gerargantt/',
            dialog: $('#dialog-gerargantt')
        },
        //importargantt: {
        //    url: base_url + '/projeto/gantt/importar-gantt/',
        //},
    };
    /**** configuraçoes pagina PDF **/
    var
        form = $('.form'),
        cache_width = form.width(),
        a4 = [595.28, 841.89]; // for a4 size paper width and height

    /********************************/
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

    /*xxxxxx GERAR GANTT xxxxxx*/
    var options = {
        url: actions.gerargantt.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
            }
        }
    };
    actions.gerargantt.form.ajaxForm(options);
    actions.gerargantt.dialog.dialog({
        autoOpen: false,
        title: 'Gerência - Gerar GANTT',
        //width:  '600px',
        //height: '1600px',
        width: 460,
        height: 400,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            vClonar = true;
            $('#dialog-gerargantt').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            actions.gerargantt.dialog.empty();
        },
        buttons: {
            'Gerar': function (event) {
                $(this).dialog().find('form#form-gerar-gantt').submit();
            },
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
    $("body").delegate(".accordion-menuen", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var accordion = $(this);
        var accordionContent = accordion.next('.accordion-content');
        var href = accordion.attr('href');
        atual = href.substring(1);
        var parente = atual + "p";
        $('#' + atual).collapse('toggle');
        $('#' + parente).collapse('toggle');
    });
    $("body").delegate(".accordion-menuenp", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var accordion = $(this);
        var accordionContent = accordion.next('.accordion-content');
        var href = accordion.attr('href');
        var atual = href.substring(1);
        var parente = href.substring(1, href.length - 1);
        $('#' + atual).collapse('toggle');
        $('#' + parente).collapse('toggle');
    });

    $("body").delegate("#btn-gerar-gantt", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var form = $('form#form-gantt');
        var idprojeto = form.find('input[id="idprojeto"]').val();
        var idgrupo = form.find('select[id="idgrupo"] option:selected').val();
        var identrega = form.find('select[id="identrega"] option:selected').val();
        var idatividadecronograma = form.find('select[id="idatividadecronograma"] option:selected').val();
        var idatividademarco = form.find('select[id="idatividademarco"] option:selected').val();

        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: actions.gerargantt.url,
            dataType: 'html',
            type: 'POST',
            data: {
                'idprojeto': idprojeto,
                'idgrupo': idgrupo,
                'identrega': identrega,
                'idatividadecronograma': idatividadecronograma,
                'idatividademarco': idatividademarco
            },
            success: function (data) {
                actions.gerargantt.dialog.html(data).dialog('open');
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

    $("body").delegate("#resetbutton", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var form = $('form#form-gantt');
        form.find('select[id="tipoexibicao"]').val("3");
        form.find('input[id="idgrupo_cons"]').val("");
        form.find('input[id="identrega_cons"]').val("");
        form.find('input[id="idatividadecronograma_cons"]').val("");
        form.find('input[id="idatividademarco_cons"]').val("");
        entregaCons = form.find('input[id="identrega_cons"]').val();
        idativida_cons = form.find('input[id="idatividadecronograma_cons"]').val();
        idamarco_cons = form.find('input[id="idatividademarco_cons"]').val();
        $('#idgrupo').val('').trigger('change');
        form.find('select[id="identrega"]').val("");
        form.find('select[id="idatividadecronograma"]').val("");
        form.find('select[id="idatividademarco"]').val("");
    });

    $("body").delegate("#btnpesquisar11", "click", function (event) {
        event.preventDefault();
        $this = $(this);
        var form = $('form#form-gantt');

        var idprojeto = form.find('input[id="idprojeto"]').val();
        var idprojeto = form.find('input[id="idprojeto"]').val();
        var idprojeto = form.find('input[id="idprojeto"]').val();
        var idprojeto = form.find('input[id="idprojeto"]').val();
        var idatividade = $this.attr('id');
        var domatividade = $this.attr('dom');
        var urlAction = actions.detalhar.urlatividade + 'idprojeto/' + idprojeto + '/idatividadecronograma/' + idatividade + '/domatividade/' + domatividade;
    });
    $('#idgrupo').change(function (e) {
        e.preventDefault();
        var valor = $(e.target).val();
        var options = $("#identrega");
        lista_atividade(valor, "", 2, options);
    });
    $('#identrega').change(function (e) {
        e.preventDefault();
        var valor = $(e.target).val();
        var options = $("#idatividadecronograma");
        lista_atividade(valor, "", 3, options);
    });
    /******************************************************************************/

    //create pdf
    function createPDF() {
        getCanvas().then(function (canvas) {
            var
                img = canvas.toDataURL("image/png"),
                doc = new jsPDF({
                    unit: 'px',
                    format: 'a4'
                });
            doc.addImage(img, 'JPEG', 20, 20);
            doc.save('techumber-html-to-pdf.pdf');
            form.width(cache_width);
        });
    }

    // create canvas object
    function getCanvas() {
        form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
        return html2canvas(form, {
            imageTimeout: 2000,
            removeContainer: true
        });
    }

    var specialElementHandlers = {
        '#gantt': function (element, renderer) {
            return true;
        }
    };

    function lista_atividade(idgrupo, idgrupopai, domatividade, options) {
        var form = $('form#form-gantt');
        var idprojeto = form.find('input[id="idprojeto"]').val();
        $.ajax({
            url: base_url + "/projeto/gantt/atividade/idprojeto/" + idprojeto + "/idgrupo/" + idgrupo + "/idgrupopai/" + idgrupopai + "/domtipoatividade/" + domatividade,
            dataType: 'json',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                if (data) {
                    options.empty();
                    options.append($("<option />").val("").text("Selecione"));
                    $.each(data, function () {
                        if ((this.domtipoatividade == 3) || (this.domtipoatividade == 4)) {
                            options.append($("<option />").val(this.idatividadecronograma).text(this.datinicio + ' a ' + this.datfim + ' - ' + this.nomatividadecronograma));
                        } else {
                            options.append($("<option />").val(this.idatividadecronograma).text(this.nomatividadecronograma));
                        }
                    });
                    if (options.attr('id') == "identrega") {
                        options.find('option[value="' + entregaCons + '"]').attr('selected', 'selected');
                    }
                    if (options.attr('id') == "idatividadecronograma") {
                        options.find('option[value="' + idativida_cons + '"]').attr('selected', 'selected');
                    }
                    if (options.attr('id') == "idatividademarco") {
                        options.find('option[value="' + idamarco_cons + '"]').attr('selected', 'selected');
                    }
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisiÃ§Ã£o',
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    function target_popup(form) {
        window.open('', 'formpopup', 'width=400,height=400,resizeable,scrollbars');
        form.target = 'formpopup';
    }

    if (valorEntrega > 0) {
        var valor = valorEntrega;
        var options = $("#idatividadecronograma");
        lista_atividade(valor, "", 3, options);
    } else {
        if (valorGrupo > 0) {
            var valor = valorGrupo;
            var options = $("#identrega");
            lista_atividade(valor, "", 2, options);
            var optionsAt = $("#idatividadecronograma");
            lista_atividade("", valor, 3, optionsAt);
        }
    }
    /******************************************************************************/
    $('aside').attr('class', 'movel');
    $('figcaption').attr('class', 'cap');
    $(".movel").appendTo('#cont-aside');
    $(".gantt").appendTo('#cont-data-gantt');
    $("figcaption").remove(".cap");
    $('.pgp.gantt').appendTo('li.pgp.cronograma');
    $('.liabc').appendTo('.region-west.ui-layout-pane.ui-layout-pane-west');

    function reSizeTable() {
        var widthGeral = $(window).width() - 100;
        $('#cont-comp').width(widthGeral + 15);
    }

    reSizeTable();
    $(window).resize(reSizeTable);

    /******************************************************************************/
    //#portal-header
    var headerHeight = $('#portal-header').parent().outerHeight();
    var resizeHeaderHeight = $('.ui-layout-resizer.ui-layout-resizer-north.ui-layout-resizer-open.ui-layout-resizer-north-open').outerHeight();
    var elementHeight = headerHeight + resizeHeaderHeight;
    var elementGanttData = $('.gantt-data');
    var elementGanttHeader = $('.gantt-header');
    var elementGanttItems = $('.gantt-items.totalstyle');
    $('.ui-layout-pane.region-center').scroll(function () {
        var topGanttDays = elementGanttData.offset().top + elementGanttHeader.outerHeight();
        if (topGanttDays <= elementHeight) {
            elementGanttHeader.css({
                'position': 'fixed',
                'top': (($(this).scrollTop() - elementHeight) + 8) + 'px',
            });
            elementGanttItems.css({
                'marginTop': (elementGanttHeader.outerHeight()) + 'px'
            })
        } else {
            elementGanttHeader.css({
                'position': 'relative',
                'top': 'auto',
                'left': 'auto',
            });
            elementGanttItems.css({
                'marginTop': 'auto'
            })
        }
    });

    $('#aside-label').css('height', elementGanttHeader.outerHeight());

    $('#cont-aside').resizable({
        maxWidth: 500,
        minWidth: 160,
        ghost: true,
        resizeHeight: false,
        stop: function (event, ui) {
            $(this).css("height", 'auto !important');
        }
    });

    $("#accordion2").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });
});