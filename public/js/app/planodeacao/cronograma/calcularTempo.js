//
$(document).ready(function () {
    $("#datfimbaseline").attr('readonly', 'readonly');

    $("#order_data_fim").change(function () {

        var order = $(this).val();
        //alert(order);return false;
    });

    $("a.btn-excluir-atividade").click(function () {
        var idplanodeacao = $("#idplanodeacao").val();
        var idatividadecronograma = $("input:checked").val();
        var domtipoatividade = $("input#domtipoatividade").val();
        $.ajax({
            url: base_url + '/planodeacao/cronograma/excluir-atividade/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                idplanodeacao: idplanodeacao,
                idatividadecronograma: idatividadecronograma,

            },
            success: function (data) {
                //alert(data.msg.text); return false;
                if (data.msg.text == 'ativiPredec') {
                    $('#dialog-AtivPredecexcluir').dialog({
                        autoOpen: true,
                        title: 'Cronograma - Excluir Atividade',
                        width: '1000px',
                        modal: true,
                        buttons: {
                            'Fechar': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }
                if (data.msg.text == 'predecessora') {
                    $('#dialog-Predecessoraexcluir').dialog({
                        autoOpen: true,
                        title: 'Cronograma - Excluir Atividade',
                        width: '1000px',
                        modal: true,
                        buttons: {
                            'Excluir': function () {
                                var param = "predecessora";
                                $.ajax({
                                    url: base_url + '/planodeacao/cronograma/excluir-predecessora/format/json/',
                                    dataType: 'json',
                                    type: 'get',
                                    data: {
                                        idplanodeacao: idplanodeacao,
                                        idatividadepredecessora: idatividadecronograma,
                                        idatividadecronograma: idatividadecronograma,
                                        params: param
                                    },
                                    success: function (data) {
                                        //$("form #acatividadeexcluir").submit();
                                        $.pnotify(data);
                                        location.reload();
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
                                    url: base_url + '/planodeacao/cronograma/excluir-predecessora/format/json/',
                                    dataType: 'json',
                                    type: 'get',
                                    data: {
                                        idplanodeacao: idplanodeacao,
                                        idatividadecronograma: idatividadecronograma,
                                        params: param
                                    },
                                    success: function (data) {
                                        $.pnotify(data);
                                        CRONOGRAMA.retornaPlanodeacao();
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

//     $("input:radio[name='item_cronograma']").click(function(){
//          
//         var tese = $(this).val();
//         alert(tese); return false;
//     });
// $("#numdiasrealizados").click(function(obj){
//        var periodo = 'inicio',
//            folgas = 0;
//        console.log("obj: "+obj.val());
//        console.log("ID: "+obj.attr('id'));
//
//        if(obj.attr('id')=='datiniciobaseline') {
//           
//                var dataPredecessora = obj.html('inicio');
//                alert(dataPredecessora);
//            
//           
//        };
//
////        if(obj.attr('id')=='datinicio' || obj.attr('id')=='datfim') {
////            if (periodo == 'inicio') {
////                var dataPredecessora = obj.val();
////            }
////            if (periodo == 'fim') {
////                var dataPredecessora = obj.val();
////            }
////        }
////        console.log("dataPredecessora: "+dataPredecessora);
////        if(false == $numfolga.is(':disabled')){
////            folgas = $numfolga.val();
////        }
////        console.log(folgas);
////        obj.val(function(){
////
////            if(null == dataPredecessora){
////               dataPredecessora = $("#maior_valor").val();
////               var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
////               return Intervalo.adicionarDias(inicio, folgas);
////               o.setarInicioBaseLine();
////            }
////
////            var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
////            return Intervalo.adicionarDias(inicio, folgas);
////        });
//    });
});

