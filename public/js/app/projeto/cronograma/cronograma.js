var CRONOGRAMA = (function ($, Handlebars, Intervalo) {
    var 
        cron = {};
    
    cron.projeto = {};
    cron.tplProjeto   = null;
    cron.tplGrupo     = null;
    cron.tplEntrega   = null;
    cron.tplAtividade = null;
    cron.tplMarco     = null;
    cron.checkItemCronograma = 'input.input-item-cronograma';
    cron.allToolBar = '.btn-group-atividade, .btn-group-grupo, .btn-group-entrega';
    cron.allButtonsToolBar = '.btn-group-atividade a, .btn-group-grupo a, .btn-group-entrega a, .btn-cadastrar-grupo, .btn-group-cronograma a, .btn-group-ferramentas button';
    cron.itemativo = null;
    cron.itemChecado = null;
    cron.existeChecado = false;
    cron.idprojeto = $('#idprojeto').val();
    cron.$dialogAtualizarBaseline = null;
    cron.formAtualizarBaseline = "form#atualizar-baseline",
    cron.nav = [];
    cron.altura = {
        doc      : 0,
        norte    : 0,
        sul      : 0,
        acordion : 0,
        acordionPadding : 20
    };
    
    cron.calcularAlturaCronograma = function()
    {
       // console.log('wilton');
        var retirar = 150;
        
        if($('.ui-layout-north').is(':visible')){
            retirar += $('.ui-layout-north').height();
        }
        if($('.ui-layout-south').is(':visible')){
            retirar += $('.ui-layout-south').height();
        }
        if($('#collapseOne').height() !== 0){
            retirar += $('.accordion-inner').height() + cron.altura.acordionPadding;
            //console.log('ACORDION VISIVEL');
            //console.log(retirar);
        }
        //console.log(retirar);
        //console.log(cron.altura.doc);
        //console.log(retirar);
        
        $('.container-grupo').height(cron.altura.doc - retirar);
    };
    
    cron.customEvents = function()
    {
       
        $('body').on('mostrarFerramentas', function(event, chk) {
            cron.itemChecado = 'input.input-item-cronograma[value="' + chk.val() + '"]';
            
            
            var
                grupo = chk.data('group');
                
            
            $('.item-cronograma').removeClass('success');
            $(cron.itemChecado).closest('.item-cronograma').addClass('success');
                
            $(cron.allToolBar).hide();
            if (chk.is(":checked")) {
                $('' + grupo).show();
            }
            
            if(grupo == '.btn-group-atividade'){
                var dados = chk.data('dados');
                //console.log(dados.numdiasrealizados);
                //console.log(dados.idgrupo);
                //console.log(dados.datinicio);
                var aux = dados.numpercentualconcluido.split('.');
                dados.numpercentualconcluido = aux[0];
                
                $("#e_datinicio").val(dados.datinicio);
                $("#e_datfim").val(dados.datfim.substring(0,10));
                $("#e_numpercentualconcluido").val(dados.numpercentualconcluido);
                $("#e_numdiasrealizados").val(dados.numdiasrealizados);
                $("#e_idatividadecronograma").val(chk.val());
                $("#e_idgrupo").val(dados.idgrupo);
                
                $("body").delegate("#e_datinicio, #e_datfim", "focusin", function() {
                    var $this = $(this);
                    $this.datepicker({
                        format: 'dd/mm/yyyy',
                        language: 'pt-BR',
                        minDate: function(dateText){
                            var input = $this.data('input');
                            $(input).val(dateText);
                        }
                    });
                });
                
                $("a.btn-tranformar-marco, a.btn-tranformar-atividade").hide();
                if(chk.is('.item-marco')){
                    $("a.btn-tranformar-atividade").show();
                } else {
                    $("a.btn-tranformar-marco").show();
                }
                //console.log('atachando o evento submit');
                $(CRONOGRAMA.atividade.formPercentual).validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function(form) {

                        $.ajax({
                            url: base_url + '/projeto/cronograma/atividade-atualizar-percentual/format/json',
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                'datinicio':  $("#e_datinicio").val(),
                                'datfim':  $("#e_datfim").val(),
                                'numpercentualconcluido':  $("#e_numpercentualconcluido option:checked").val(),
                                'idatividadecronograma':  $("#e_idatividadecronograma").val(),
                                'numdiasrealizados':  $("#e_numdiasrealizados").val(),
                                'idprojeto':  $("#idprojeto").val(),
                                'idgrupo': $("#e_idgrupo").val()
                            },
                            success: function(data) {
                                //CRONOGRAMA.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';
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
                    }
                });

            }
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
                //data: $formEditar.serialize(),
                processData: false,
                success: function(data) {
                    dialog.html(data).dialog('open');
                    $("#flainformatica").trigger('change');
                    CRONOGRAMA.atividade.habilitarFolgas();
                    $(formAtual).validate({
                        errorClass: 'error',
                        validClass: 'success',
                        submitHandler: function(form) {
                            enviar_ajax(urlForm, formAtual, function(data) {
                                if(data.item){
                                    cron.itemativo = prefixo + data.item.idatividadecronograma + ' > .item-cronograma';
                                }
                                
                                cron.retornaProjeto();
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
        
        $("body").on('click', "a.btn-atualizar-baseline", function(event) {
            event.preventDefault();
            var
                $this = $(this),
                urlForm = '/projeto/cronograma/atualizar-baseline/format/json',
                urlAjax = $this.attr('href');
                
            //console.log($this.attr('href') );
            cron.$dialogAtualizarBaseline.dialog('option','title','Cronograma - Atualizar Base Line');
            $this.data('form', cron.formAtualizarBaseline),
            $this.data('urlajax', urlAjax);
            $this.data('urlform', urlForm);
            $this.data('dialog', cron.$dialogAtualizarBaseline);
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on("fitrarCronograma",function(){
            $.ajax({
                url: base_url + '/projeto/cronograma/pesquisar',
                dataType: 'json',
                type: 'POST',
                data: $('form#ac_atividade_pesquisar').serialize(),
                //processData:false,
                success: function(data) {
                    
                    $('.grupo, .entrega').hide();
                    $("input.input-item-cronograma", ".container-atividade").closest('.item-cronograma').hide();
                    //console.log(data);
                    $.each(data,function(i, val){
                        //console.log(val);
                       if(val.domtipoatividade == '2'){
                           $(".container-entrega  > #en" + val.idatividadecronograma).closest('.grupo').show();
                           $(".container-entrega  > #en" + val.idatividadecronograma).show(); 
                       }else{
                            $("#at" + val.idatividadecronograma).closest('.grupo').show();
                            $("#at" + val.idatividadecronograma).closest('.entrega').show();
                            $("input.input-item-cronograma[value=" + val.idatividadecronograma + "]").closest('.item-cronograma').show();
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
        
       
    };
    
    cron.initDialogs = function()
    {
        cron.$dialogAtualizarBaseline = $('#dialog-atualizar-baseline').dialog({
                autoOpen: false,
                title: 'Cronograma - Atualizar Base Line',
                width: '800px',
                modal: true,
                close: function(event, ui) {
                },
                buttons: {
                    'Confirmar': function() {
                        $(cron.formAtualizarBaseline).submit();
                    },
                    'Fechar': function() {
                        $(this).dialog('close');
                    }
                }
            });
    };
    
    cron.retornaProjeto = function()
    {
        var idprojeto = $("input#idprojeto").val();
        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                idprojeto: idprojeto
            },
            success: function(data) {
                cron.projeto = data.projeto;
                cron.renderProjeto();
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
    
    cron.renderProjeto = function ()
    {
        //cron.tplProjeto   = Handlebars.compile($('#tpl-projeto').html());
        cron.tplGrupo     = Handlebars.compile($('#tpl-grupo').html());
        
        Handlebars.registerPartial("helperEntrega", $("#tpl-entrega").html());
        Handlebars.registerPartial("helperAtividade", $("#tpl-atividade").html());
        
        //$('#dados-projeto').html(cron.tplProjeto(cron.projeto));
        TemplateManager.get('dados-projeto', function(tpl){
             $("#dados-projeto").html(tpl(cron.projeto));
         });
        
        $('.container-grupo').html(cron.tplGrupo(cron.projeto));
        cron.events();
        
        if(cron.itemChecado !== null){
            $(cron.itemChecado).attr("checked",true).trigger('click');
        }
        
        if(cron.itemativo !== null){
            $(cron.itemativo).addClass("success");
        }
        cron.nav = $(cron.checkItemCronograma);
    };
    
    cron.events = function()
    {
        var intervalo = window.setInterval(cron.calcularAlturaCronograma, 500);

        $(cron.allButtonsToolBar).tooltip();
        
        $("body").on('click', cron.checkItemCronograma, function() {
            //console.log('mostar ferramentas');
            var $this = $(this);
            $('body').trigger('mostrarFerramentas',[$this]);
        });
        
        $("body").on('dblclick', cron.checkItemCronograma, function() {
            var $this = $(this),
                grupo = $this.data('group');
            
            if(grupo == '.btn-group-atividade'){
                $("#e_numpercentualconcluido").focus();
            }
        });

        $('.btn-group-ferramentas button:eq(1)').addClass('active');

        $("#btn-fullscreen").on('click', function(){
            myLayout.close('north');
            myLayout.close('south');
        });

        $("#btn-restaurar").on('click', function(){
            myLayout.open('north');
            myLayout.open('south');
        });
    };
    
    cron.init = function()
    {
        cron.initDialogs();
        cron.retornaProjeto();
       //alert(cron.projeto.datinicioplano);return false;
        cron.customEvents();
        cron.events();
    };

    return cron;

}(jQuery, Handlebars, Intervalo));



