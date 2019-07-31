/**
 * Comment
 */
function selectRow(row) {
    //console.log(row);
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {
//console.log('participantes.js');
//    $('#link_participantes').attr('style','display:block');
//    $('#link_participantes').attr('class','active');

    $("#accordion").accordion();

    $.pnotify.defaults.history = false;

    $("#tabs").tabs();


//    $('.origem').click(function() {
//        context = $(this).data('cont');
//        $('.cont').hide().find('input,select').hide().prop('disabled', true);
//        $('.' + context).show().find('select,input:text,input:hidden').fadeIn('slow').prop('disabled', false);
//        $('.origem').removeClass('active');
//        $('.origem-' + context).addClass('active');
//    });
//
//    $('.origem:first').trigger('click');

//    $("#btn-adicionar-externo").click(function() {
//        $("form#form-parte-externo").submit();
//    });

//    $("#btn-adicionar").click(function() {
    $(document.body).on("click", "#btn-adicionar", function () {
//        console.log($("form#form-parte").serialize());
//        $("form#form-participante").submit();
        $valid = true;
        $(document.body).find('.excluir').each(function (i, item) {
            if ($(item).attr('data-id') == $('#idpessoa').val()) {
                $.pnotify({
                    text: 'O participante já foi adicionado',
                    type: 'error',
                    hide: true
                });
                $valid = false;
            }
        });

        if ($valid) {
            $.ajax({
                url: base_url + "/agenda/index/participantes/format/json",
                dataType: 'json',
                type: 'POST',
                data: $("form#form-participante").serialize(),
                success: function (data) {
                    if (data.success) {
                        $.adicionar(data.parte);
                        $.pnotify({
                            text: 'Registro incluído com sucesso',
                            type: 'success',
                            hide: true
                        });
                    }

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
    });

    $(document.body).on('click', '.classeDoSeuBotao', function (event) {
        event.preventDefault();
//        console.log('excluir');

    });

    $.adicionar = function (parte) {
        var $row = "<tr class='success'>" +
            "<td><a class='btn actionfrm excluir excluirparticipantebutton' title='Excluir Participante' data-id='" + parte.idpessoa + "' data-agenda ='" + $('#agenda').val() + "' >" +
            "<i class='icon-trash'></i>" +
            "</a></td>" +
            "<td>" + parte.nompessoa + "</td>" +
            "<td>" + parte.desemail + "</td>" +
            "</tr>";
        $("#listagemInteressados")
            .removeClass('hide')
            .find("table tbody").prepend($row);
        $("#tabelaInteressados").show();
        $("#nenhumregistro").hide();
    };


    $(document.body).on("click", ".excluirparticipantebutton", function () {
        var $this = $(this);
        $.ajax({
            url: base_url + "/agenda/index/excluirparticipante/format/json/",
            dataType: 'json',
            type: 'POST',
            data: {
                idpessoa: $this.data('id'),
                idagenda: $this.data('agenda')
            },
            success: function (data) {
//                console.log('success!');
//                console.log(data);
                $.pnotify(data.msg);
                if (data.success) {

//                    window.location.reload(true);
                    if ($("#listagemInteressados").find("table tr:visible").length <= 2) {
                        $this.parent().parent().hide();
                        $("#tabelaInteressados").hide();
                        $("#nenhumregistro").show();
                    } else {
                        $this.parent().parent().fadeOut().remove();
                    }
                }

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

    var $form = $("form#form-participante");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
//            enviar_ajax("/projeto/tap/partesinteressadas/format/json", "form#form-parte", function(data) {
            enviar_ajax("/agenda/index/participantes/format/json", "form#form-participante", function (data) {
//                if (data.success) {
                if (data.success) {
                    $.adicionar(data.parte);
                    $(document.body).parent().grid.jqGrid("reloadGrid", true);
                }
                //window.location.href = base_url + "/projeto/tap/informacoesiniciais/idprojeto/" + data.dados.idprojeto;
                //$("#resetbutton").trigger('click');
//                }
            });
            //console.log('enviando');
        }
    });

    $(document.body).on('click', ".pessoa-button", function (event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid/agenda/1",
                type: "GET",
                dataType: "html",
                success: function (html) {
                    $(".grid-append").append(html).slideDown('fast');
                }
            });
            $('.pessoa-button')
                .off('click')
                .on('click', function () {
                    var $this = $(this);
                    $(".grid-append").slideDown('fast', function () {
                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                        $this.closest('.control-group').addClass('input-selecionado');
                    });
                });
        }
    });

//    $(".pessoa-button").on('click', function(event) {
//        event.preventDefault();
//        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
//        $(this).closest('.control-group').addClass('input-selecionado');
//        if ($("table#list-grid-pessoa").length <= 0) {
//            $.ajax({
//                url: base_url + "/cadastro/pessoa/grid",
//                type: "GET",
//                dataType: "html",
//                success: function(html) {
//                    $(".grid-append").append(html).slideDown('fast');
//                }
//            });
//            $('.pessoa-button')
//                .off('click')
//                .on('click',function() {
//                    var $this = $(this);
//                    $(".grid-append").slideDown('fast', function(){
//                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
//                        $this.closest('.control-group').addClass('input-selecionado');
//                    });
//                });
//        }
//    });

//    myLayout.children.center.layout1.center.options.onresize = null;
});