/**
 * Comment
 */

function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(document).ready(function () {

    $.pnotify.defaults.history = false;

    $('#pessoas-in').click(function (e) {
        listLeftMove();
    });

    $('#pessoas-out').click(function (e) {
        listRightMove();
    });

    $('#pessoas').dblclick(function (e) {
        listLeftMove();
    });

    $('#pessoasEquipe').dblclick(function (e) {
        listRightMove();
    });

    function listLeftMove() {
        var selectedOpts = $('#pessoas option:selected');
        if (selectedOpts.length == 0) {
            e.preventDefault();
        }

        $('#pessoasEquipe').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    function listRightMove() {
        var selectedOpts = $('#pessoasEquipe option:selected');
        if (selectedOpts.length == 0) {
            e.preventDefault();
        }

        $('#pessoas').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    }

    $("#grid-unidade-editar").load(base_url + "/diagnostico/diagnostico/unidades-filhas/id/" + $('#idunidadeprincipal').val());
    $('#pessparte').val($('#pessoasEquipe').val());


    $("body").on("click", "#submitbutton", function () {

        var $form = $('#form-diagnostico-editar'),
            equipe = $('#pessoasEquipe');

        var i = 0;
        var arrayEquipe = [];
        $("#pessoasEquipe option").each(function () {
            arrayEquipe[i] = $(this).val();
            i++;
        });
        $('#pessparte').val(arrayEquipe.join());

        var checkbox = $('input:checkbox[name^=unidades-vinculadas]:checked');
        var val = [];
        checkbox.each(function () {
            val.push($(this).val());
        });
        if (val.length > 0) {
            $('#msnchk').hide();
        } else {
            $('#msnchk').show();
            $('#msnchk').append("<span>Este campo é requerido.</span>");
            return false;
        }

        var valor = $("#idunidadeprincipal").val();
        if (valor == null || valor === "") {
            $('#idDisplay').show();
            return false;
        } else {
            $('#idDisplay').hide();
        }
        //$form.validate().form();
        var param = $form.serialize();

        if ($form.valid()) {
            $.ajax({
                url: base_url + '/diagnostico/diagnostico/clonar-add/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {
                    if (data.msg.type == 'success') {
                        $.pnotify(data.msg.text);
                        setTimeout(function () {
                            window.location.href =
                                window.location.href = base_url + "/diagnostico/diagnostico/detalhar/iddiagnostico/" + data.msg.iddiagnostico;
                        }, 2000);


                    } else {
                        $.pnotify(data.msg.text);
                    }
                },
                error: function () {
                    $.pnotify({
                        text: "Falha ao enviar a requisição",
                        type: 'error',
                        hide: false
                    });
                }

            });

        } else {
            return false;
        }

    });

    $(".pessoa-button").on('click', function (event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid",
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

    $("body").on("click", "#idunidadeprincipal", function () {
        if ($("#idunidadeprincipal").val() == null || $("#idunidadeprincipal").val() === "") {
            $('#grid-unidade-editar').hide();
        } else {
            $('#grid-unidade-editar').show();
        }
    });

    $("body").on("click", "#idunidadeprincipal", function () {
        $("#grid-unidade-editar").load(base_url + "/diagnostico/diagnostico/unidades-filhas/id/" + $('#idunidadeprincipal').val());
    });

    var valor = $("#idunidadeprincipal").val();
    $("#idunidadeprincipal option[value=" + valor + "]").attr("selected", "selected");
});

    
