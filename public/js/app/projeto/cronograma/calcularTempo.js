//
$(document).ready(function () {
    msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';
    msgerroacesso = 'Acesso negado para essa ação.';

    $("#order_data_fim").change(function () {
        var order = $(this).val();
    });

    $("a.btn-excluir-atividade").click(function () {
        var idprojeto = $("#idprojeto").val();
        var idatividadecronograma = $("input:checked").val();
        var domtipoatividade = $("input#domtipoatividade").val();
        $.ajax({
            url: base_url + '/projeto/cronograma/verifica-atividades-predecessoras/format/json',
            dataType: 'json',
            type: 'GET',
            data: {
                idprojeto: idprojeto,
                idatividadecronograma: idatividadecronograma,
            },
            success: function (data) {
                if (data.msg.text == 'atividadeSucessora') {
                    $('#dialog-AtivPredecexcluir').dialog({
                        autoOpen: true,
                        title: 'Cronograma - Excluir Atividade',
                        width: '1000px',
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    if (data.msg.text == 'predecessora') {
                        $('#dialog-Predecessoraexcluir').dialog({
                            autoOpen: true,
                            title: 'Cronograma - Excluir Atividade',
                            width: '1000px',
                            modal: true,
                            buttons: {
                                'Sim': function () {
                                    var param = "predecessora";
                                    $.ajax({
                                        url: base_url + '/projeto/cronograma/excluir-atividade/format/json/',
                                        dataType: 'json',
                                        type: 'POST',
                                        data: {
                                            idprojeto: idprojeto,
                                            idatividadecronograma: idatividadecronograma,
                                            params: param
                                        },
                                        success: function (data) {
                                            $.pnotify(data.msg.text);
                                            window.location.href = base_url + "/projeto/cronograma/index/idprojeto/" + $("#idprojeto").val();
                                            return;
                                        },
                                    });
                                    $(this).dialog('close');
                                },
                                'Não': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    } else {
                        if (data.msg.text == 'atividade') {
                            $('#dialog-excluir').dialog({
                                autoOpen: true,
                                title: 'Cronograma - Excluir Atividade',
                                width: '1000px',
                                modal: true,
                                buttons: {
                                    'Excluir': function () {
                                        var param = "atividade";
                                        $.ajax({
                                            url: base_url + '/projeto/cronograma/excluir-atividade/format/json/',
                                            dataType: 'json',
                                            type: 'POST',
                                            data: {
                                                idprojeto: idprojeto,
                                                idatividadecronograma: idatividadecronograma,
                                                params: param
                                            },
                                            success: function (data) {
                                                $.pnotify(data.msg.text);
                                                window.location.href = base_url + "/projeto/cronograma/index/idprojeto/" + $("#idprojeto").val();
                                                return;
                                            },
                                        });
                                        $(this).dialog('close');
                                    },
                                    'Fechar': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    }
                }
            },
            error: function () {
                $.pnotify({
                    text: msgerroacesso,
                    type: 'error',
                    hide: false
                });
            }
        });
    });
});

