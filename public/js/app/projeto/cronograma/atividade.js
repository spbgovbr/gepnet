//XXXXXXXXXX ATIVIDADE XXXXXXXXXX
CRONOGRAMA.atividade = (function ($, Handlebars, Intervalo)
{
    var o = {};
            
    o.$dialogAtividade = null;
    o.$dialogAtividadeExcluir = null;
    o.formAtividade = "form#ac-atividade";
    o.formAtiviPredecxcluir = "form#ac-ativiPredec-excluir";
    o.formAtividadeExcluir = "form#ac-atividade-excluir";
    o.formPredecessoraExcluir = "form#ac-predecessora-excluir";
    o.formPercentual = "form#e_atividade";
    o.formAtualizarBaselineAtiv = "form#atualizar-baseline-ativ";
    o.alertaPredecessora = "#alert-predecessora";
    o.templatePredecessora = null;
    o.tablePredecessora = "table#table-predecessoras";
    o.selectPredecessora = "select#predecessora";
    o.selectPredecessoraEditar = ".container-predecessora-editar select#predecessora";
    o.linkPredecessora = 'a.remover-predecessora';
    o.linkPredecessoraEditar = 'a.remover-predecessora-editar';
    o.itemCronogramaSelecionado = 'input.input-item-cronograma:checked';
    o.editMode = false;
    o.urls = {
        cadastrar: '/projeto/cronograma/cadastrar-atividade/format/json',
        editar: '/projeto/cronograma/editar-atividade/format/json',
        excluir: '/projeto/cronograma/excluir-atividade/format/json',
        atualizarBaselineAtiv: '/projeto/cronograma/atualizar-baseline-atividade/format/json'
    };
    o.url_form = null;

    o.initDialogs = function()
    {
        o.$dialogAtividade = $('#dialog-atividade').dialog({
            autoOpen: false,
            title: 'Cronograma - Cadastrar Atividade',
            width: '1000px',
            modal: true,
            buttons: {
                'Salvar': function() {
                    $(o.formAtividade).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
//        o.$dialogAtividadeExcluir = $('#dialog-excluir').dialog({
//            autoOpen: false,
//            title: 'Cronograma - Excluir Atividade',
//            width: '1000px',
//            modal: true,
//            buttons: {
//                'Excluir': function() {
//                    $(o.formAtividadeExcluir).submit();
//                },
//                'Fechar': function() {
//                    $(this).dialog('close');
//                }
//            }
//        });
        o.$dialogAtualizarBaselineAtiv = $('#dialog-atualizar-baseline-ativ').dialog({
            autoOpen: false,
            title: 'Cronograma - Atualizar Base Line Atividade',
            width: '800px',
            modal: true,
            buttons: {
                'Confirmar': function() {
                    $(o.formAtualizarBaselineAtiv).submit();
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });
    };

    o.compilarTemplates = function()
    {
        o.templatePredecessora = Handlebars.compile($('#tpl-predecessora').html());
    };
   
    o.calendarios = function()
    {
        $("body").delegate("#datinicio, #datfim", "focusin", function() {
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

        $("body").delegate("#datiniciobaseline, #datfimbaseline", "focusin", function() {
            var $this = $(this);
            $this.datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                minDate: function(dateText){
                    var input = $this.data('input');
                    $(input).val(dateText);
                },
                onSelect: function( dateText, inst ){
                    var input = $this.data('input');
                    $(input).val(dateText);
                }
            });
        });
    };

    o.habilitarFolgas = function()
    {
        disabled = true;
            
        if($("table#table-predecessoras").find('tr').length > 0){
            disabled = false;
        $("#numfolga").attr('disabled',disabled);
        }else{
            
            $("#numfolga").val(0);
            
        }
    };

    o.setarInicioBaseLine = function()
    {
        var t = $(o.tablePredecessora),
            tam = t.find('tr').length,
            objInicio,
            objFim;

        if($("#datiniciobaseline").length){
            objInicio   = $("#datiniciobaseline");
            objFim      = $("#datfimbaseline");
        }else{
            objInicio   = $("#datinicio");
            objFim      = $("#datfim");
        }
        if(tam <= 0){
            $("#numfolga").attr('disabled',true);
            return;
        }
        //console.log("objeto: "+obj.attr('id'));
        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-inicio-base-line',
            dataType: 'json',
            type: 'POST',
            async: false,
            cache: true,
            data: $(o.formAtividade).serialize(),
            processData: false,
            success: function(data) {
                objInicio.data('inicio',data);
                objFim.data('fim',data);
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

    /*o.setarInicioBaseLineEdicao = function()
    {
        var t = $(o.tablePredecessora), tam = t.find('tr').length;

        if(tam <= 0){
            $("#datinicio").data('inicio',null);
            return;
        }

        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-inicio-base-line',
            dataType: 'json',
            type: 'POST',
            async: false,
            cache: true,
            data: $(o.formAtividade).serialize(),
            processData: false,
            success: function(data) {
                //console.log(data);
                $("#datinicio").data('inicio',data);
            },
            error: function() {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    };*/

    /*o.calcularBaseLineEdicao = function()
    {
        console.log('Calculando folgas Edicao');
        var
            $numfolga = $("#numfolga"),
            $dataInicioBaseLine = $("#datinicio"),
            dataPredecessora = $dataInicioBaseLine.data('inicio'),
            folgas = 0;

        if(false == $numfolga.is(':disabled')){
            folgas = $numfolga.val();
            console.log("numero folga: "+folgas);
        }
        console.log('$dataInicio: ' + $dataInicioBaseLine.val());
        $dataInicioBaseLine.val(function(){
            if(null == dataPredecessora){
                return '';
            }
            console.log('predecessora antes de somar mais 1: ' + dataPredecessora);
            var inicio = Intervalo.adicionarDias(dataPredecessora, 1);
            console.log('predecessora apos somar mais 1: ' + inicio);
            return Intervalo.adicionarDias(inicio, folgas);
        }).trigger('focusout');

    };*/

    o.calcularBaseLine = function()
    {
        //console.log('Calculando folgas');
        console.log("contador da data baseline: " + $("#datinicio").length);

        if($("#datiniciobaseline").length>0 && $("#datfimbaseline").length>0){
            o.calcularDias($("#datiniciobaseline"),'inicio');
            o.calcularDias($("#datfimbaseline"),'fim');
            /*$dataInicioBaseLine     = objInicio;
            $dataFimBaseLine        = objFim;
            dataInicioPredecessora  = $dataInicioBaseLine.data('inicio');
            dataFimPredecessora     = $dataFimaseLine.data('fim');

            $dataInicioBaseLine.val(function(){

                if(null == $dataInicioBaseLine){
                    //return '';
                }

                var inicio = Intervalo.adicionarDias($dataInicioBaseLine, 1);

                //return Intervalo.adicionarDias(inicio, folgas);
            }).trigger('focusout');

            $dataFimBaseLine.val(function(){

                if(null == dataFimPredecessora){
                    //return '';
                }

                var inicio = Intervalo.adicionarDias(dataFimPredecessora, 1);
                return Intervalo.adicionarDias(inicio, folgas);
            }).trigger('focusout');*/
        }
        if($("#datiniciobaseline").length<=0 &&  $("#datfimbaseline").length<=0){
            console.log("data Inicio: "+$("#datinicio"));
            o.calcularDias($("#datinicio"),'inicio');
            o.calcularDias($("#datfim"),'fim');
        }

    };

    o.calcularDias = function(obj,valor){
        var periodo = valor,
            $numfolga = $("#numfolga"),
            folgas = 0;
        console.log("obj: "+obj.val());
        console.log("ID: "+obj.attr('id'));

        if(obj.attr('id')=='datiniciobaseline' || obj.attr('id')=='datfimbaseline') {
            if (periodo == 'inicio') {
                var dataPredecessora = obj.data('inicio');
            }
            // para inseir automatico na data fim descomente esses campos
//            if (periodo == 'fim') {
//                var dataPredecessora = obj.data('fim');
//            }
        }

        if(obj.attr('id')=='datinicio' || obj.attr('id')=='datfim') {
            if (periodo == 'inicio') {
                var dataPredecessora = obj.val();
            }
            // para inseir automatico na data fim descomente esses campos
//        if (periodo == 'fim') {
//            var dataPredecessora = obj.val();
//        }
        }
        console.log("dataPredecessora: "+dataPredecessora);
        if(false == $numfolga.is(':disabled')){
            folgas = $numfolga.val();
        }
        console.log(folgas);
        obj.val(function(){

            if(null == dataPredecessora){
               dataPredecessora = $("#maior_valor").val();
               var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
               return Intervalo.adicionarDias(inicio, folgas);
               o.setarInicioBaseLine();
            }

            var inicio = Intervalo.adicionarDias(dataPredecessora, 0);
            return Intervalo.adicionarDias(inicio, folgas);
        }).trigger('focusout');
    }

    o.adicionarPredecessora = function(event, select)
    {
        var a = {}, p = null, t = null;

        //p = $(o.selectPredecessora);
        t = $(o.tablePredecessora);
        a.idatividadecronograma = select.find('option:selected').val();
        text = select.find('option:selected').text();
        aux = text.split('-');
        datas = aux[0].split('a');
        
        a.nomatividadecronograma = aux[1];
        a.datinicio = datas[0];
        a.datfim = datas[1];
        //  console.log(o.editMode);
        if(a.idatividadecronograma !== ''){

            $(o.templatePredecessora(a)).appendTo(t);
            o.atualizarCaptionPredecessoras();

            if(o.editMode === true){
                o.cadastrarPredecessora(a);
                CRONOGRAMA.retornaProjeto();
                o.setarInicioBaseLine();
                o.calcularBaseLine();
            }

            if(o.editMode === false){
                o.setarInicioBaseLine();
                o.calcularBaseLine();
            }
        }
    };

    o.atualizarCaptionPredecessoras = function()
    {
        //console.log('atualizando caption predecessora');
        var t = $(o.tablePredecessora), tam = t.find('tr').length;

        if(tam <= 0){
            $(o.alertaPredecessora).show();
            t.addClass('hidden');
            //$("#numfolga").val('').attr('disabled',true);
            //return;
        } else {
            t.find('caption').html('Predecessora(s) selecionada(s): ' + tam);
            t.removeClass('hidden');
            $(o.alertaPredecessora).hide();
            o.habilitarFolgas();
        }
        //$("#numfolga").attr('disabled',false);
    };

    o.removerPredecessora = function(btn)
    {
        if(o.editMode === true){
            o.excluirPredecessora(btn);
        
        } 
        
        btn.closest('tr').remove();
            o.setarInicioBaseLine();
            o.calcularBaseLine();
        o.atualizarCaptionPredecessoras();

        if(o.editMode === false){
            o.setarInicioBaseLine();
            o.calcularBaseLine();
        }
    };
    
    o.cadastrarPredecessora = function(a)
    {
        //console.log('cadastrando predecessora');
        var
            idprojeto = $("input#idprojeto").val(),
            idatividadepredecessora = a.idatividadecronograma,
            idatividade = $('#idatividadecronograma').val()
            ;
            
        $.ajax({
            url: base_url + '/projeto/cronograma/adicionar-predecessora',
            dataType: 'json',
            type: 'POST',
            data: {
                idprojeto: idprojeto,
                idatividadepredecessora: idatividadepredecessora,
                idatividade: idatividade
            },
            success: function(data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                
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
    };
    
    o.excluirPredecessora = function(btn)
    {
        //console.log('excluindo predecessora');
        var
            idprojeto = $("input#idprojeto").val(),
            idatividadepredecessora = btn.parent().find('input.idpredecessora').data('idatividadepredecessora'),
            idatividade = btn.parent().find('input.idpredecessora').val()
            ;
            
        $.ajax({
            url: base_url + '/projeto/cronograma/excluir-predecessora',
            dataType: 'json',
            type: 'POST',
            data: {
                idprojeto: idprojeto,
                idatividadepredecessora: idatividadepredecessora,
                idatividade: idatividade
            },
            success: function(data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
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
    };
    
    o.retornarInicioFimRealizado = function(){
        
        $("#datfimbaseline").attr('readonly', 'readonly');
    };

    o.customEvents = function()
    {
        $('body').on('adicionarPredecessora', function(event, select){
            o.adicionarPredecessora(event, select);
        });
        
        $('body').on('removerPredecessora', function(event, btn){
            o.removerPredecessora(btn);
        });

        $('body').on('calcularBaseLine', function(){
            o.calcularBaseLine();
        });
        $('body').on('retornarInicioFimRealizado', function(){
             o.retornarInicioFimRealizado();
        });
        
        /*
        $("body").on('atividadeAtualizarPercentual', function(event){
            
            $(o.formAtividadeExcluir).validate({
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
                            'idprojeto':  $("#idprojeto").val(),
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
            
        });
        */
        
        $("body").on('atividadeAtualizarTipo', function(event){
            var domtipoatividade = 4;
            if( $(o.itemCronogramaSelecionado).is(".item-marco") ){
                domtipoatividade = 3;
            }
            
            $.ajax({
                url: base_url + '/projeto/cronograma/atualizar-dom-tipo-atividade/format/json',
                dataType: 'json',
                type: 'POST',
                data: {
                    'domtipoatividade':  domtipoatividade,
                    'idatividadecronograma':  $(o.itemCronogramaSelecionado).val(),
                    'idprojeto':  $("#idprojeto").val()
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

    o.events = function()
    {
        /*
        $("body").on("submit",o.formPercentual, function(event) {
            console.log('submetendo');
            event.preventDefault();
            //$this.trigger('submit');
            $("body").trigger("atividadeAtualizarPercentual");
        });
        */
        
        $("body").on("click",".btn-tranformar-marco, .btn-tranformar-atividade", function(event) {
            $("body").trigger("atividadeAtualizarTipo");
            event.preventDefault();
        });
        
        $("#e_datinicio, #e_datfim, #inicial_dti, #inicial_dtf, #final_dti, #final_dtf").mask('99/99/9999');
        
        $("body").on('click', "a.btn-cadastrar-atividade", function(event) {
            event.preventDefault();
            var 
                $this = $(this),
                urlForm = o.urls.cadastrar,
                urlAjax = $this.attr('href') + '/idgrupo/' + $(o.itemCronogramaSelecionado).val()
                ;
            o.editMode = false;    
            o.$dialogAtividade.dialog('option','title','Cronograma - Cadastrar Atividade');
            
            $this.data('form', o.formAtividade),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogAtividade);
            $this.data('prefixo', '#at');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('click', "a.btn-editar-atividade", function(event) {
            event.preventDefault();
            var 
                $this = $(this),
                urlForm = o.urls.editar,
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val()
                ;
            o.editMode = true;
            o.$dialogAtividade.dialog('option','title','Cronograma - Editar Atividade');
            
            $this.data('form', o.formAtividade),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogAtividade);
            $this.data('prefixo', '#at');
            
            $("body").trigger('openDialog',[$this]);
        });
        
//        $("body").on('click', "a.btn-excluir-atividade", function(event) {
//            event.preventDefault();
//            var 
//                $this = $(this),
//                urlForm = o.urls.excluir,
//                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val()
//                ;
//            o.editMode = false;
//            o.$dialogAtividadeExcluir.dialog('option','title','Cronograma - Excluir Atividade');
//            
//            $this.data('form', o.formAtividadeExcluir),
//            $this.data('urlform', urlForm);
//            $this.data('urlajax', urlAjax);
//            $this.data('dialog', o.$dialogAtividadeExcluir);
//            $this.data('prefixo', '#at');
//            
//            $("body").trigger('openDialog',[$this]);
//        });
        
        $("body").on('click', "a.btn-atualizar-baseline-ativ", function(event) {
            event.preventDefault();
            var 
                $this = $(this),
                urlForm = o.urls.atualizarBaselineAtiv,
                urlAjax = $this.attr('href') + '/idatividadecronograma/' + $(o.itemCronogramaSelecionado).val()
                ;
            o.editMode = false;
            o.$dialogAtualizarBaselineAtiv.dialog('option','title','Cronograma - Atualizar Base Line Atividade');
            
            $this.data('form', o.formAtualizarBaselineAtiv),
            $this.data('urlform', urlForm);
            $this.data('urlajax', urlAjax);
            $this.data('dialog', o.$dialogAtualizarBaselineAtiv);
            $this.data('prefixo', '#at');
            
            $("body").trigger('openDialog',[$this]);
        });
        
        $("body").on('change', 'select#predecessora', function(event){
            var $this = $(this);
            $("body").trigger('adicionarPredecessora',[$this]);
        });

        $("body").on('click', o.linkPredecessora, function(e){
            e.preventDefault();
            $("body").trigger('removerPredecessora',[$(this)]);
        });

        $("body").delegate("#datinicio", "focusin", function() {
            var $this = $(this);
            $this.datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR'
            });
        });

        $("body").delegate("#datfim", "focusin", function() {
            var $this = $(this);
            $this.datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                beforeShow: function( selectedDate, inst ) {
                    var val = null;
                    if($('#datinicio').length > 0){
                        val = $('#datinicio').val();
                        $(this).datepicker( "option", "minDate", val );
                    }
                }
            });
        });

        $("body").delegate("#datiniciobaseline", "focusin", function() {
            var $this = $(this);
            $this.datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                onSelect: function( dateText, inst ){
                    var input = $this.data('input');
                    $(input).val(dateText);
                }
            });
        });
            // Para usar o datepicker na data fim baseline descomentar aqui
//        $("body").delegate("#datfimbaseline", "focusin", function() {
//            var $this = $(this);
//            $this.datepicker({
//                format: 'dd/mm/yyyy',
//                language: 'pt-BR',
//                beforeShow: function( selectedDate, inst ) {
//                    var val = null;
//                    if($('#datiniciobaseline').length > 0){
//                        val = $('#datiniciobaseline').val();
//                        $(this).datepicker( "option", "minDate", val );
//                    }
//                },
//                onSelect: function( dateText, inst ){
//                    var input = $this.data('input');
//                    $(input).val(dateText);
//                }
//            });
//        });

        $("body").delegate("#numfolga", "focusout", function() {
            $("body").trigger("calcularBaseLine");
        });

        $("body").on("focusout","#vlratividadebaseline", function(){
            $this = $(this);
            $("#vlratividade").val($this.val());
        });
   //==========================================================================     
        /// calculando o periodo de aula para inserir na data fim
        $("body").on("focusout","#numdiasbaseline", function(e){
          // Convertendo a data do brasil para americana 
           var datiniciobaseline = $("#datiniciobaseline").val();
           var splitdataini      = datiniciobaseline.split('/');
           var retonraDatAmer    = splitdataini[2]+ '-' + splitdataini[1]+ '-' + splitdataini[0];
           var QuantidadeDias    = $("#numdiasbaseline").val();
           var DataAtual         = new Date(retonraDatAmer);
           // Calculando a data com os dias do curso
             var  a = new Date(retonraDatAmer);
              // campo com apenas leitura
             // Calculando a data com os dias inseridos e preenchendo na data fim
                  //if (e.which == 13) {
                    $("#datfimbaseline").val((
                      new Date( 
                        a.getFullYear(), 
                        a.getMonth(), 
                        a.getDate()+ 1 + parseInt(QuantidadeDias) )
                      ).toString("dd/MM/yyyy"));
                    $("#datfim").val((
                      new Date( 
                        a.getFullYear(), 
                        a.getMonth(), 
                        a.getDate()+ 1 + parseInt(QuantidadeDias) )
                      ).toString("dd/MM/yyyy"));
                  //}
                    // se a quantidade tiver vazia data fim recebe vazio
                    if(QuantidadeDias == ''){
                         $("#datfimbaseline").val('');
                         $("#datfim").val('');
                       }

        });
        
   //=========================================================================     
        
           /// calculando o periodo de aula para inserir na data fim realizado
        $("body").on("focusout","#e_numdiasrealizados", function(e){
          // Convertendo a data do brasil para americana 
           var datiniciobaseline = $("#e_datinicio").val();
           var splitdataini      = datiniciobaseline.split('/');
           var retonraDatAmer    = splitdataini[2]+ '-' + splitdataini[1]+ '-' + splitdataini[0];
           var QuantidadeDias    = $("#e_numdiasrealizados").val();
           var DataAtual         = new Date(retonraDatAmer);
           // Calculando a data com os dias do curso
             var  a = new Date(retonraDatAmer);
              // campo com apenas leitura
             // Calculando a data com os dias inseridos e preenchendo na data fim
                  //if (e.which == 13) {
                     if(QuantidadeDias == ''){
                          $("#e_datfim").val('');
                          $("#e_numdiasrealizados").focus();
                       }else{
                              $("#e_datfim").val((
                                new Date( 
                                  a.getFullYear(), 
                                  a.getMonth(), 
                                  a.getDate()+ 1 + parseInt(QuantidadeDias) )
                                ).toString("dd/MM/yyyy"));
                       }

        });
        
   
        
   //========================================================================     
        
        // digitando apenas numeros no campo quantidade
        $("body").on("keypress","#numdiasbaseline", function(e){
            
        var tecla = (window.event) ? event.keyCode : event.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla != 8){
                $("#numdiasbaseline").attr('title', 'Digite apenas números');
                return false;
            }
            
            else return true;
        }
        });
        $("body").on("keypress","#numdiasrealizados, #e_numdiasrealizados", function(e){
            
        var tecla = (window.event) ? event.keyCode : event.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla != 8){
                $("#numdiasbaseline").attr('title', 'Digite apenas números');
                return false;
            }
            
            else return true;
        }
        });
        /////fim digitando apenas numeros no campo quantidade//////////////////
        
        // calculando o tempo BASELINE de curso com a data ao pressionar enter 
        $("body").on("keypress","#numdiasbaseline", function(e){
          // Convertendo a data do brasil para americana 
           var datiniciobaseline = $("#datiniciobaseline").val();
           var splitdataini      = datiniciobaseline.split('/');
           var retonraDatAmer    = splitdataini[2]+ '-' + splitdataini[1]+ '-' + splitdataini[0];
           var QuantidadeDias = $("#numdiasbaseline").val();
           var DataAtual = new Date(retonraDatAmer);
           // Calculando a data com os dias do curso
             var  a = new Date(retonraDatAmer);
             
             // Calculando a data com os dias inseridos e preenchendo na data fim
                  if (e.which == 13) {
                    // campo com apenas leitura
                    $("#datfimbaseline").val((
                      new Date( 
                        a.getFullYear(), 
                        a.getMonth(), 
                        a.getDate()+ 1 + parseInt(QuantidadeDias) )
                      ).toString("dd/MM/yyyy"));
                    $("#datfim").val((
                      new Date( 
                        a.getFullYear(), 
                        a.getMonth(), 
                        a.getDate()+ 1 + parseInt(QuantidadeDias) )
                      ).toString("dd/MM/yyyy"));
                  }
                  // se a quantidade tiver vazia data fim recebe vazio
                    if(QuantidadeDias == ''){
                         $("#datfimbaseline").val('');
                         $("#datfim").val('');
                       }
        });
        ///////////////////////////// fim calculo ////////////////////////
        // calculando o tempo REALIZADO de curso com a data ao pressionar enter 
        $("body").on("keypress","#numdiasrealizados", function(e){
          // Convertendo a data do brasil para americana 
           var datiniciobaseline = $("#datinicio").val();
           var splitdataini      = datiniciobaseline.split('/');
           var retonraDatAmer    = splitdataini[2]+ '-' + splitdataini[1]+ '-' + splitdataini[0];
           var QuantidadeDias = $("#numdiasrealizados").val();
           var DataAtual = new Date(retonraDatAmer);
           // Calculando a data com os dias do curso
             var  a = new Date(retonraDatAmer);
             
             // Calculando a data com os dias inseridos e preenchendo na data fim
                  if (e.which == 13) {
                    // campo com apenas leitura
                    $("#datfim").val((
                      new Date( 
                        a.getFullYear(), 
                        a.getMonth(), 
                        a.getDate()+ 1 + parseInt(QuantidadeDias) )
                      ).toString("dd/MM/yyyy"));
                  }
                  // se a quantidade tiver vazia data fim recebe vazio
                    if(QuantidadeDias == ''){
                         $("#datfim").val('');
                       }
        });
        ///////////////////////////// fim calculo ////////////////////////
        // calculando o tempo REALIZADO de curso com a data ao clicar fora 
        $("body").on("focusout","#numdiasrealizados", function(e){
          // Convertendo a data do brasil para americana 
           var datiniciobaseline = $("#datinicio").val();
           var splitdataini      = datiniciobaseline.split('/');
           var retonraDatAmer    = splitdataini[2]+ '-' + splitdataini[1]+ '-' + splitdataini[0];
           var QuantidadeDias = $("#numdiasrealizados").val();
           var DataAtual = new Date(retonraDatAmer);
           // Calculando a data com os dias do curso
             var  a = new Date(retonraDatAmer);
             
             // Calculando a data com os dias inseridos e preenchendo na data fim
                  
                    // campo com apenas leitura
                    $("#datfim").val((
                      new Date( 
                        a.getFullYear(), 
                        a.getMonth(), 
                        a.getDate()+ 1 + parseInt(QuantidadeDias) )
                      ).toString("dd/MM/yyyy"));
                  
                  // se a quantidade tiver vazia data fim recebe vazio
                    if(QuantidadeDias == ''){
                         $("#datfim").val('');
                       }
        });
        ///////////////////////////// fim calculo ////////////////////////
       
        $("body").on("focusin","#vlratividadebaseline, #vlratividade", function(){
            $this = $(this);
            if (!$this.data('formatCurrencyAttached'))
            {
                $this.data('formatCurrencyAttached', true);
                $this.formatCurrency({
                                    decimalSep   : ',',
                                    thousandsSep : '.',
                                    digits       : 2
                            }).trigger('keypress');
            }
        });
        
        
        // Calcular o qtd dia pela fim e data inicio realizado/////////////// 
        $("body").on("click", "#calcDias", function(){
           var datfim            = $("#datfim").val();
           var splitdataFim      = datfim.split('/');
           var retonraDatFimAmer = new Date(splitdataFim[2]+ '-' + splitdataFim[1]+ '-' + splitdataFim[0]);
           
           var datinicio         = $("#datinicio").val();
           var splitdataIni      = datinicio.split('/');
           var retonraDatIniAmer = new Date(splitdataIni[2]+ '-' + splitdataIni[1]+ '-' + splitdataIni[0]);
           var resultadoTotal = ((Date.UTC((retonraDatFimAmer.getYear()),retonraDatFimAmer.getMonth(),retonraDatFimAmer.getDate(),0,0,0)
               - Date.UTC((retonraDatIniAmer.getYear()),retonraDatIniAmer.getMonth(),retonraDatIniAmer.getDate(),0,0,0))/86400000);
            
            $("#numdiasrealizados").val(resultadoTotal);
        });
        // FIM Calcular o qtd dia pela fim e data inicio realizado//////////////
        
        ///////////////////////////// fim calculo ////////////////////////
        $("body").on("focusout","#datiniciobaseline, #datfimbaseline", function(){
            $this = $(this);
             var input = $this.data('input');
             $(input).val($this.val());
        });
   
 /////////////////////////// Quando houver custo habilita a despesa/////////////       
        $("body").on("keypress","#vlratividade", function(){
            
           var vlratividade = $(this).val();
           
           if(vlratividade > '0' || vlratividade > '0,00'){
               $("#idelementodespesa").attr('disabled', false);
           }   
        });
        $("body").on("focusout","#vlratividade", function(){
            
           var vlratividade = $(this).val();
           
           if( vlratividade == '' || vlratividade == '0,00'){
               $("#idelementodespesa").attr('disabled', true);
               vlratividade = $(this).val('0');
           }   
        });
 /////////////////////////// Fim Quando houver custo habilita a despesa/////////         
        
        
        $("#inicial_dti, #inicial_dtf, #final_dti, #final_dtf").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    };

    o.init = function ()
    {
        o.compilarTemplates();
        o.initDialogs();
        o.customEvents();
        o.events();
        o.retornarInicioFimRealizado();
    };

    return o;
}(jQuery, Handlebars, Intervalo));


