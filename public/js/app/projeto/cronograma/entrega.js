//XXXXXXXXXX ENTREGA XXXXXXXXXX
CRONOGRAMA.entrega = (function($, Handlebars){
    var o = {};

    o.formEntrega = "form#ac-entrega";
    o.formEntregaExcluir = "form#ac-entrega-excluir";
    o.$dialogEntrega = null;
    o.$dialogEntregaExcluir = null;
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.idProjeto = '#idprojeto';
    o.urls = {
        cadastrar: '/projeto/cronograma/cadastrar-entrega/format/json',
        editar: '/projeto/cronograma/editar-entrega/format/json',
        excluir: '/projeto/cronograma/excluir-entrega/format/json'
    };
    
    o.initDialogs = function()
    {
        o.$dialogEntrega = $('#dialog-entrega').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Entrega',
            width: '810px',
            modal: true,
            buttons: {
                'Salvar': function() {
                    $(o.formEntrega).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
        
        o.$dialogEntregaExcluir = $('#dialog-excluir-entrega').dialog({
            autoOpen: false,
            title: 'Cronograma - Excluir Entrega',
            width: '800px',
            modal: true,
            buttons: {
                'Excluir': function() {
                    $(o.formEntregaExcluir).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
    };

    o.events = function()
    {
        $("body").on('click', "a.btn-cadastrar-entrega", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                idcronogramaatividade = + $(o.itemCronogramaSelecionado).val(),
                urlAjax = $this.attr('href') + '/idgrupo/' + idcronogramaatividade,
                urlForm = o.urls.cadastrar
                ;
                
            o.$dialogEntrega.dialog('option','title','Cronograma - Cadastrar Entrega');
            
            $this.data('form', o.formEntrega),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntrega);
            $this.data('prefixo', '#en');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-editar-entrega", function(event) {
            event.preventDefault();
             var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.editar,
                idcronogramaatividade = + $(o.itemCronogramaSelecionado).val()
                ;
                
            idgrupo = $(o.itemCronogramaSelecionado).closest('.grupo').find('.item-cronograma input.input-item-cronograma').val();
            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade + '/idgrupo/' + idgrupo;
            
            o.$dialogEntrega.dialog('option','title','Cronograma - Editar Entrega');
            
            $this.data('form', o.formEntrega),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntrega);
            $this.data('prefixo', '#en');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-excluir-entrega", function(event) {
            event.preventDefault();
             var
                $this = $(this),
                urlAjax = null,
                idgrupo = null,
                urlForm = o.urls.excluir,
                idcronogramaatividade = + $(o.itemCronogramaSelecionado).val()
                ;
                
            urlAjax = $this.attr('href') + '/idatividadecronograma/' + idcronogramaatividade;
            
            o.$dialogEntregaExcluir.dialog('option','title','Cronograma - Excluir Entrega');
            
            $this.data('form', o.formEntregaExcluir),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogEntregaExcluir);
            $this.data('prefixo', '#en');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-clonar-entrega", function(event) {
            event.preventDefault();
            $.ajax({
                        url: base_url + '/projeto/cronograma/clonar-entrega/format/json',
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'idprojeto':  $(o.idProjeto).val(),
                            'idatividadecronograma':  $(o.itemCronogramaSelecionado).val(),
                        },
                        success: function(data) {
                            CRONOGRAMA.retornaProjeto();
                            $.pnotify(data.msg);
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
    };

    o.init = function()
    {
        o.initDialogs();
        o.events();
    };
    
    return o;
}(jQuery, Handlebars));


