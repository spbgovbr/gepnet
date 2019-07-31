$(function () {
    var actions = {
        pesquisar: {
            url: base_url + "/diagnostico/estatistica/resumo/format/json"
        }
    }

    //function enviar_ajax(url) {
    //    $.ajax({
    //        url: base_url + url,
    //        dataType: 'json',
    //        type: 'POST',
    //        complete: function (json) {
    //            var newJ = $.parseJSON(json.responseText);
    //            $('#unidadeDiagnosticadas').text(newJ.dados.unidadeDiagnosticadas);
    //            $('#pessoasEntrevistadas').text(newJ.dados.pessoasEntrevistadas);
    //            $('#entrevistadasCargo').text(newJ.dados.entrevistadasCargo);
    //            $('#questionariosRespondidos').text(newJ.dados.questionariosRespondidos);
    //            $('#stisfacaoServidor').text(newJ.dados.stisfacaoServidor);
    //            $('#satisfacaoCidadao').text(newJ.dados.satisfacaoCidadao);
    //            $('#satisfacaoServidorSecao').text(newJ.dados.satisfacaoServidorSecao);
    //            $('#satisfacaoServidorCargo').text(newJ.dados.satisfacaoServidorCargo);
    //            $('#satisfacaoServidorMacroprocesso').text(newJ.dados.satisfacaoServidorMacroprocesso);
    //        },
    //        error: function () {
    //            $.pnotify({
    //                text: 'Falha ao enviar a requisição',
    //                type: 'error',
    //                hide: false
    //            });
    //        }
    //    });
    //}

    $(document.body).on('change', "#diagnostico", function (event) {
        event.preventDefault();

        $.ajax({
            url: actions.pesquisar.url,
            dataType: 'json',
            type: 'POST',
            async: true,
            cache: true,
            data: {iddiagnostico: $(this).val()},
            success: function (data) {
                $('#unidadeDiagnosticadas').text(data.dados.unidadeDiagnosticadas);
                $('#pessoasEntrevistadas').text(data.dados.pessoasEntrevistadas);
                $('#questionariosRespondidos').text(data.dados.questionariosRespondidos);
                $('#stisfacaoServidor').text(data.dados.stisfacaoServidor);
                $('#satisfacaoCidadao').text(data.dados.satisfacaoCidadao);

                $('#entrevistadasCargo > tbody').empty();

                if (data.dados.entrevistadasCargo.length > 0) {
                    var cargos = data.dados.entrevistadasCargo;
                    for (var i = 0, c = cargos.length; i < c; i++) {
                        $('#entrevistadasCargo > tbody').append('<tr><td style="width: 80%;">' + cargos[i].cargo + '</td><td style="width: 20%;">' + cargos[i].total + '</td><tr>');
                    }
                } else {
                    $('#entrevistadasCargo > tbody').append('<tr><td colspan="2">Não existe(m) pessoa(s) entrevistada(s).</td></tr>');
                }

                $('#satisfacaoServidorSecao > tbody').empty();

                if (data.dados.satisfacaoServidorSecao.length > 0) {
                    var secoes = data.dados.satisfacaoServidorSecao;
                    for (var i = 0, s = secoes.length; i < s; i++) {

                        $('#satisfacaoServidorSecao > tbody').append('<tr><td style="width: 80%;">' + secoes[i].secao + '</td><td style="width: 20%;">' + secoes[i].valor + '</td><tr>');
                    }
                } else {
                    $('#satisfacaoServidorSecao > tbody').append('<tr><td colspan="2">Não existe(m) seção(ões) cadastrada(s).</td></tr>');
                }

                $('#satisfacaoServidorMacroprocesso > tbody').empty();

                if (data.dados.satisfacaoServidorMacroprocesso.length > 0) {
                    var macroprocessos = data.dados.satisfacaoServidorMacroprocesso;
                    for (var i = 0, s = macroprocessos.length; i < s; i++) {

                        $('#satisfacaoServidorMacroprocesso > tbody').append('<tr><td style="width: 80%;">' + macroprocessos[i].macroprocesso + '</td><td style="width: 20%;">' + macroprocessos[i].valor + '</td><tr>');
                    }
                } else {
                    $('#satisfacaoServidorMacroprocesso > tbody').append('<tr><td colspan="2">Não existe(m) macroprocesso(s) cadastrado(s).</td></tr>');
                }

                $('#satisfacaoServidorCargo > tbody').empty();

                if (data.dados.satisfacaoServidorCargo.length > 0) {
                    var cargos = data.dados.satisfacaoServidorCargo;
                    for (var i = 0, c = cargos.length; i < c; i++) {

                        $('#satisfacaoServidorCargo > tbody').append('<tr><td style="width: 80%;">' + cargos[i].cargo + '</td><td style="width: 20%;">' + cargos[i].valor + '</td><tr>');
                    }
                } else {
                    $('#satisfacaoServidorCargo > tbody').append('<tr><td colspan="2">Não existe(m) cargos(s) cadastrado(s).</td></tr>');
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
});