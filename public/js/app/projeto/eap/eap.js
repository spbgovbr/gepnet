
var MODULOEAP = (function ($, Handlebars) {
    var 
        eap = {};
        
    eap.projeto = {};   
    eap.tplProjeto = null;
    eap.tplGrupo     = null;
    eap.tplEntrega   = null;
    eap.tplQuadro   = null;
    eap.tplQuadroEntrega   = null;
    eap.idProjeto = "#idprojeto";
    eap.formGrupo = "form#ac-grupo";
    eap.formEntrega = "form#ac-entrega";
    eap.formExcluirEntrega = "form#ac-entrega-excluir";
    eap.formExcluirGrupo = "form#ac-grupo-excluir";
    eap.$dialogGrupo = null;
    eap.$dialogEntrega = null;
    eap.urls = {
        cadastrarGrupo: '/projeto/eap/cadastrar-grupo/format/json',
        cadastrarEntrega: '/projeto/eap/cadastrar-entrega/format/json',
        excluirEntrega: '/projeto/eap/excluir-entrega/format/json',
        excluirGrupo: '/projeto/eap/excluir-grupo/format/json'
    };
    
    
    eap.retornaProjeto = function() 
    {
        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                idprojeto: $(eap.idProjeto).val()
            },
            success: function(data) {
                  eap.projeto = data.projeto;
                  eap.renderProjeto();
            },
            error: function() {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    };
    
    eap.renderProjeto = function ()
    {
        TemplateManager.get('dados-projeto', function(tpl){
            $("#dados-projeto").html(tpl(eap.projeto));
          });
        eap.tplGrupo     = Handlebars.compile($('#tpl-grupo').html());
        Handlebars.registerPartial("helperEntrega", $("#tpl-entrega").html());
        Handlebars.registerPartial("helperQuadroEntrega", $("#tpl-quadro-entrega").html());
        $('.container-grupo').html(eap.tplGrupo(eap.projeto));
    };
    
    eap.customEvents = function()
    {
       
        $("body").delegate(".draggable-entrega", "mouseover" ,function() {
            $(this).draggable({
                appendTo: "body",
                snap: true,
                cursorAt: {left: 5},
                zIndex: 200,
                revert: "invalid"
            });
        });
        
        $("body").delegate(".droppable-grupo", "mouseover", function() {
            $(this).droppable({
                accept: ".draggable-entrega", 
                tolerance: 'intersect',
                hoverClass: 'ui-state-highlight',
                drop: function(event, ui){
                    $.ajax({
                        url: base_url + '/projeto/eap/editar-entrega/format/json',
                        dataType: 'json',
                        type: 'POST',
                        async: false,
                        data: {
                            idprojeto: $(eap.idProjeto).val(),
                            idgrupo: $(this).attr('id') ,
                            idatividadecronograma: ui.draggable.attr('id') 
                        },
                        success: function(data) {
                            eap.retornaProjeto();
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
                }
            });
        });
        
        $("body").delegate(".estrutura-analitica", "click", function() {
            $('.grupos-eap').toggle();
        });
        
        $("body").delegate(".dicionario-eap", "click", function() {
            $('.quadro-dicionario-eap').toggle();
        });
        
        $("body").on('openDialog', function(event, btn){
            var dialog    = btn.data('dialog'),
                formAtual = btn.data('form'),
                urlAjax   = btn.data('urlajax'),
                urlForm   = btn.data('urlform'),
                prefixo   = btn.data('prefixo');
                
            $.ajax({
                url: urlAjax,
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                processData: false,
                success: function(data) {
                    dialog.html(data).dialog('open');
                    $(formAtual).validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function(form) {
                            enviar_ajax(urlForm, formAtual, function(data) {
                                eap.retornaProjeto();
                                dialog.dialog('close');
                            });
                        }
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
        
        $("body").on('click', "a.btn-cadastrar-grupo", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.cadastrarGrupo
                ;
              
            eap.$dialogGrupo.dialog('option','title','EAP - Cadastrar Grupo');
            
            $this.data('form', eap.formGrupo),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogGrupo);
            $this.data('prefixo', '#gr');
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-cadastrar-entrega", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.cadastrarEntrega
                ;
                
            eap.$dialogEntrega.dialog('option','title','EAP - Cadastrar Entrega');
            
            $this.data('form', eap.formEntrega),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogEntrega);
            $this.data('prefixo', '#en');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.excluir-entrega", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.excluirEntrega
                ;
                
            eap.$dialogEntrega.dialog('option','title','EAP - Excluir Entrega');
            
            $this.data('form', eap.formExcluirEntrega),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogExcluirEntrega);
            $this.data('prefixo', '#en');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.excluir-grupo", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlAjax = $this.attr('href'),
                urlForm = eap.urls.excluirGrupo
                ;
                
            eap.$dialogGrupo.dialog('option','title','EAP - Excluir Grupo');
            
            $this.data('form', eap.formExcluirGrupo),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', eap.$dialogExcluirGrupo);
            $this.data('prefixo', '#gr');
            
            $("body").trigger('openDialog',[$this]);
        });
    };
    
   
    eap.initDialogs = function()
    {
        eap.$dialogGrupo = $('#dialog-grupo').dialog({
            autoOpen: false,
            title: 'EAP - Cadastrar Grupo',
            width: '800px',
            modal: true,
            close: function(event, ui) {
            },
            buttons: {
                'Salvar': function() {
                    $(eap.formGrupo).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
        
        eap.$dialogEntrega = $('#dialog-entrega').dialog({
            autoOpen: false,
            title: 'EAP - Cadastrar Entrega',
            width: '810px',
            modal: true,
            buttons: {
                'Salvar': function() {
                    $(eap.formEntrega).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });  
        
        eap.$dialogExcluirEntrega = $('#dialog-excluir-entrega').dialog({
            autoOpen: false,
            title: 'EAP - Excluir Entrega',
            width: '900px',
            modal: true,
            buttons: {
                'Excluir': function() {
                    $(eap.formExcluirEntrega).submit();
                    
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
        
        eap.$dialogExcluirGrupo = $('#dialog-excluir-grupo').dialog({
            autoOpen: false,
            title: 'EAP - Excluir Grupo',
            width: '900px',
            modal: true,
            buttons: {
                'Excluir': function() {
                    $(eap.formExcluirGrupo).submit();
                    
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
        
    };
    
    eap.init = function()
    {
        eap.initDialogs();
        eap.retornaProjeto();
        eap.customEvents();
    };

    return eap;
        

}(jQuery, Handlebars));

