$(function () {

    var input = $('input#txt_consulta');

    $("#img1").addClass('icon-chevron-down').removeClass('icon-chevron-right');


    $('#modalNotaVersao').modal({
        backdrop: false,
        show: true,
    });
    $("#fechar").on('click', function () {
        $('#modalNotaVersao').modal('hide');
    })

    $('input#txt_consulta').quicksearch('.accordion-group', {
        onAfter: function () {
            if ($(this).val() && $(this).val().length) {
                var accordion = $('#accordion2').find('.accordion-group:visible .accordion-body');
                var img = $('#accordion2').find('.accordion-group:visible .accordion-heading > a .icon-chevron-right');
                img.addClass('icon-chevron-down').removeClass('icon-chevron-right');
                accordion.collapse('show');
            } else {
                $('#collapse1.collapse:not(.in)').collapse('show');
                $('.accordion-body.in.collapse:not(#collapse1)').collapse('hide');
                //fecharImg();
            }
        }
    });
    var mark = function () {

        // Read the keyword
        var keyword = $('input#txt_consulta').val();

        // Remove previous marked elements and mark
        // the new keyword inside the context
        $("#accordion2").unmark({
            done: function () {
                $("#accordion2").mark(keyword);
            }
        });
    };
    $('input#txt_consulta').on("input", mark);


    function fecharImg() {
        $("a").each(function () {
            var href = $(this).attr('href');
            $($(this)).children("i").each(function () {
                var textIcon = $(this).attr("id");
                $("" + href + "").on('shown.collapse', function () {
                    console.log(input.val().length);
                    console.log(textIcon);
                    if (input.val().length > 1) {
                        if (textIcon != "img1") {
                            $("#" + textIcon + "").addClass('icon-chevron-right').removeClass('icon-chevron-down');
                        }
                    }
                });
            });
        });
    }


    $("a").click(function () {
        var href = $(this).attr('href')
        $($(this)).children("i").each(function () {
            var textIcon = $(this).attr("id");
            $("" + href + "").on('shown.collapse', function () {
                $("#" + textIcon + "").addClass('icon-chevron-down').removeClass('icon-chevron-right');
            });

            $("" + href + "").on('hidden.collapse', function () {
                $("#" + textIcon + "").addClass('icon-chevron-right').removeClass('icon-chevron-down');
            });
        });
    });


});