//XXXXXXXXXX GRUPO XXXXXXXXXX
CRONOGRAMA.grupo = (function($, Handlebars){
    var o = {};

    o.formGrupo = "form#ac-grupo";
    o.$dialogGrupo = null;
    o.$dialogGrupoExcluir = null;
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.idProjeto = '#idprojeto';
    o.urls = {
        cadastrar: '/projeto/cronograma/cadastrar-grupo/format/json',
        editar: '/projeto/cronograma/editar-grupo/format/json',
        excluir: '/projeto/cronograma/excluir-grupo/format/json'
    };
    
    o.initDialogs = function()
    {
        o.$dialogGrupo = $('#dialog-grupo').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Grupo',
            width: '800px',
            modal: true,
            close: function(event, ui) {
            },
            buttons: {
                'Salvar': function() {
                    $(o.formGrupo).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
        o.$dialogGrupoExcluir = $('#dialog-excluir-grupo').dialog({
            autoOpen: false,
            title: 'Cronograma - Excluir Grupo',
            width: '800px',
            modal: true,
            close: function(event, ui) {
            },
            buttons: {
                'Excluir': function() {
                    $(o.formGrupo).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
    };
    
    o.events = function()
    {
        $("body").on('click', "a.btn-cadastrar-grupo", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = o.urls.cadastrar
                ;
              
            o.$dialogGrupo.dialog('option','title','Cronograma - Cadastrar Grupo');
            
            $this.data('form', o.formGrupo),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupo);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-editar-grupo", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val(),
                urlForm = o.urls.editar
                ;
            
            o.$dialogGrupo.dialog('option','title','Cronograma - Editar Grupo');
            
            $this.data('form', o.formGrupo),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupo);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-excluir-grupo", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val(),
                urlForm = o.urls.excluir
                ;
            
            o.$dialogGrupoExcluir.dialog('option','title','Cronograma - Excluir Grupo');
            
            $this.data('form', o.formGrupo),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogGrupoExcluir);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-clonar-grupo", function(event) {
            event.preventDefault();
            $.ajax({
                    url: base_url + '/projeto/cronograma/clonar-grupo/format/json',
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
        
        $("body").on('click', ".nome-grupo", function(event) {
           
            $(this).parent().parent().find('.container-entrega').toggle();
        });
    };

    o.init = function()
    {
        o.initDialogs();
        o.events();
    };

    return o;
}(jQuery, Handlebars));
