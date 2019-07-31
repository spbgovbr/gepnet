$(function () {
    var
        idProjeto = $("input[name='idprojeto']").val(),
        actions = {
            assinadocumento: {
                form: $("form#from-assinaDoc"),
                url: base_url + '/projeto/termoaceite/autenticarassinatura/format/json',
            }
        }
    $("#message").hide();
    $('#modalAssinatura').modal({
        backdrop: false,
        show: false,
    });


    $("#resetbutton").on("click", function () {
        $("#message").hide();
    });

    $(".icon-pencil").on("click", function () {
        $('#dialog-assinar').dialog('show');
        var $modalAssinatura = $('#modalAssinatura')
        $modalAssinatura.modal({show: true});
        $("#idaceite").val($(this).attr('data-id'));
    });

    //function validarCampos(){
    //    var cpf             = $("#numcpf"),
    //        senha           = $("#senha");
    //
    //    if (cpf.val() != "") {
    //        if(senha.val() == ""){
    //            $("#message").text("Favor informar a senha para validação.");
    //            $("#message").show();
    //            senha.focus();
    //            return false;
    //        }
    //    }else if (cpf.val() == "") {
    //        $("#message").text("Favor informar um CPF de usuário válido.");
    //        $("#message").show();
    //        cpf.focus();
    //        return false;
    //    }
    //
    //    $("#message").text('');
    //    $("#message").hide();
    //    return true;
    //}
    $('.mask-cpf').mask("999.999.999-99");

    //$("#submitbutton").on("click",function(){
    //    $("#message").hide();
    //    if(validarCampos()){
    //        $('#form#form-assinaDoc').parent().find("button").each(function () {
    //            $(this).attr('disabled', true);
    //        });
    //        var param = $("form#form-assinaDoc").serialize();
    //
    //        $.ajax({
    //            url: actions.assinadocumento.url,
    //            dataType: 'json',
    //            type: 'POST',
    //            data: param,
    //            success: function (data) {
    //                if (data.success) {
    //                    $("#message").hide();
    //                    $('#modalAssinatura').modal('hide');
    //                    $.pnotify({
    //                        text: data.msg.text,
    //                        type: data.msg.type,
    //                        hide: true
    //                    });
    //                }else{
    //                    $("#message").text(data.msg.text);
    //                    $("#message").show();
    //                    $("#numcpf").focus();
    //                    return false;
    //                }
    //            },
    //            error: function () {
    //                $.pnotify({
    //                    text: 'Falha ao enviar a requisição',
    //                    type: 'error',
    //                    hide: false
    //                });
    //            }
    //        });
    //    }
    //});
    $("#fechar").on('click', function () {
        $('#modalAssinatura').modal('hide');
    })

});

