
$(function () {
    $('.dados-entrega').show();
    $.pnotify.defaults.history = false;
    
    var $form = $("form#form-aceite");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function($form) {
            enviar_ajax("/projeto/termoaceite/add/format/json", "form#form-aceite", function(data) {
                if (data.success) {
                    location.href = '/public/projeto/termoaceite/index/idprojeto/'+$("input[name='idprojeto']").val();
                    $("#resetbutton").trigger('click');
                    $('.dados-entrega').hide();
                }
            });
        }
    });
    
    $('body').on('change', '#identrega', function() {
        $('.dados-entrega').hide();
        //$('#idmarco').html('');
        $.ajax({
            url: base_url + '/projeto/termoaceite/buscar-entrega/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idprojeto':  $("input[name='idprojeto']").val(),
                'idatividadecronograma':  $(this).val()
            },
            success: function(data) {
                $('.dados-entrega').show();
                
                $('.descricao-entrega > span').html(data.desobs);
                $('.criterio-aceitacao > span').html(data.descriterioaceitacao);
                $('.responsavel > span').html(data.nomparteinteressada);
            },
            error: function() {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });

        $.ajax({
            url: base_url + '/projeto/termoaceite/buscar-marcos/format/json',
            dataType: 'json',
            type: 'POST',
            data: {
                'idprojeto':  $("input[name='idprojeto']").val(),'identrega':  $(this).val()
            },
            success: function(data) {
                var selectMarco = $('#idmarco');
                selectMarco.append(new Option('Todos', ''));
                $.each(data, function(key, value){
                   //console.log(value['idatividadecronograma'] +"-"+key);
                        selectMarco.append(new Option(value, key));
                });
            },
            error: function() {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    });

    $('#aceito').change( function(){
       if($(this).val() == 'S'){
           $.pnotify({
               text: 'ATENÇÃO: Prezado usuário, ao efetivar este aceite, o registro não poderá mais ser alterado, apenas excluído!',
               type: 'info',
               hide: false
           });
       }
    });
    
});

