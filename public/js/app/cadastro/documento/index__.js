Pessoa = (function (){
    
    var
    grid     = null,
    lastsel  = null,
    gridEnd  = null,
    colModel = null,
    colNames = null,
    $dialogExcluir = $('#dialog-excluir'),
    $dialogEditar  = $('#dialog-editar'),
    $dialogDetalhar = $('#dialog-detalhar'),
    $formEditar    = $("form#form-pessoa"),
    $form  = $("form#pessoa-pesquisar");
    
    validatorMethods = function()
    {
        $.validator.addMethod("cpf", function(value) {
            String.prototype.isCPF = function(){
                var c = this;
                if((c = c.replace(/[^\d]/g,"").split("")).length != 11) return false;
                if(new RegExp("^" + c[0] + "{11}$").test(c.join(""))) return false;
                for(var s = 10, n = 0, i = 0; s >= 2; n += c[i++] * s--);
                if(c[9] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
                for(var s = 11, n = 0, i = 0; s >= 2; n += c[i++] * s--);
                if(c[10] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
                return true;
            };
            
            var retorno = value.isCPF();
            return retorno;
        }, 'Informe um CPF válido.');  
    };
    
    mascaras = function()
    {
        $('.mask-cel').mask("(99) 9999-9999?9").focusout(function(){
            var phone, element;
            element = $(this);
            element.unmask();
            phone = element.val().replace(/\D/g, '');
            if(phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        }).trigger('focusout');
        
        
        $('.mask-tel').mask("(99) 9999-9999");
        $('.mask-cpf').mask("999.999.999-99");  
    };
    
    formatadorLink = function (cellvalue, options, rowObject) 
    {
        var r = rowObject,
        params = '',
        url = {
            editar:   base_url + '/cadastro/pessoa/edit',
            excluir:  base_url + '/cadastro/pessoa/form-excluir',
            detalhar: base_url + '/cadastro/pessoa/detalhar'
        };
        params =   '/idpessoa/'+ r[5];  
        //console.log(rowObject);
        
        return '<a data-target="editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params  +'"><i class="icon-edit"></i></a>' +
            '<a data-target="excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
            '<a data-target="detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>';
    };
    
    enviarFormAjax = function (url, form, callback)
    {
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: $(form).serialize(),
            //processData:false,
            success: function(data) {
                if(typeof data.msg.text != 'string'){
                    $.formErrors(data.msg.text);
                    return;
                } 
                if (callback && typeof(callback) === "function") {  
                    callback();  
                }  
                $.pnotify(data.msg);
            },
            error: function () {
                $.pnotify({
                    text:    'Falha ao enviar a requisição',
                    type:    'error',
                    hide:    false
                });
            }
        });
    };
    
    initGrid = function()
    {
        colNames = ['Cargo','Nome','Matrícula','CPF','Lotação','Operações'];
        colModel = [{
                name:'domcargo',
                index:'domcargo', 
                width:50,
                hidden:false,
                search:false
            },{
                name:'nompessoa',
                index:'nompessoa', 
                width:200,
                hidden:false,
                search:false
            },{
                name:'nummatricula',
                index:'nummatricula', 
                width:60,
                search:true
            },{
                name:'numcpf',
                index:'numcpf', 
                width:60,
                search:true
            },{
                name:'unidade',
                index:'unidade', 
                width:200,
                search:true
            },{
                name:'idpessoa',
                index:'idpessoa', 
                width:102,
                search:false,
                sortable:false,
                formatter: Pessoa.formatadorLink
            }];
        
        grid = jQuery("#list2").jqGrid({
            //caption: "Documentos",
            url: base_url + "/cadastro/pessoa/pesquisarjson", 
            datatype: "json", 
            mtype:'post',
            width: '1170',
            height: '300px',
            colNames:colNames, 
            colModel:colModel, 
            rowNum:20, 
            rowList:[20,50,100], 
            pager: '#pager2', 
            sortname: 'nompessoa', 
            viewrecords: true, 
            sortorder: "desc",
            gridComplete: function(){
                // console.log('teste');
                //$("a.actionfrm").tooltip();
            }
        });
        
        //grid.jqGrid('filterToolbar');
        grid.jqGrid('navGrid','#pager2',{
            search:false,
            edit:false,
            add:false,
            del:false,
            view:false
        });
    };
    
    initDialogs = function()
    {
        $dialogEditar.dialog({
            autoOpen: false,
            title: 'Documento - Editar',
            width: '800px',
            modal: false,
            open: function( event, ui ){
                $formEditar.validate({
                    errorClass:'error',
                    validClass:'success',
                    submitHandler: function(form) {
                        enviar_ajax("/documento/editar/format/json", "form#form-pessoa", function(){
                            grid.trigger("reloadGrid");
                        });
                    }
                });
            },
            close: function ( event, ui ){
                $dialogEditar.empty();
            },
            buttons: {
                'Fechar': function(){
                    $(this).dialog('close');
                },
                'Limpar': function(){
                    $("#importar").select2('data', null);
                    $("#alert-import").html('').hide();
                    $("#btn-importar").attr('disabled', true); 
                    $("select#domcargo option").attr('disabled',false);
                },
                'Salvar': function(){
                    $formEditar.trigger('submit');
                }
            }
        });
    };
    
    initPesquisa = function()
    {
        $form.on('submit', function(e){
            e.preventDefault();
            grid.setGridParam({
                url:base_url + "/cadastro/pessoa/pesquisarjson?" + $form.serialize(),
                page:1
            }).trigger("reloadGrid");
            return false;
        });
    };
    
    initEventos = function()
    {
        console.log('init eventos');
        //$(document.body).on('click',"a.excluir, a.editar, a.detalhar",function(event){
        $(document.body).on('click',"a.detalhar",function(event){
            event.preventDefault();
            var 
            $this = $(this),
            $dialog = $('#dialog-detalhar')
            ;
            
            $dialog.dialog({
                autoOpen: false,
                title: 'Pessoa - Detalhar',
                width: '810px',
                modal: true,
                buttons: {
                    'Fechar': function(){
                        $(this).dialog('close');
                    }
                }
            });
            
            $.ajax({
                url: $this.attr('href'),
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                processData:false,
                success: function(data) {
                    $dialog.html(data).dialog('open');
                },
                error: function () {
                    $.pnotify({
                        text:    'Falha ao enviar a requisição',
                        type:    'error',
                        hide:    false
                    });
                }
            });
        
        });
        
        $(document.body).on('click',"a.editar",function(event){
            event.preventDefault();
            var 
            $this = $(this),
            $dialog = $('#dialog-editar')
            ;
            
            $dialog.dialog({
                autoOpen: false,
                title: 'Pessoa - Editar',
                width: '810px',
                modal: true,
                buttons: {
                    'Fechar': function(){
                        $(this).dialog('close');
                    }
                }
            });
            
            $.ajax({
                url: $this.attr('href'),
                dataType: 'html',
                type: 'GET',
                async: true,
                cache: true,
                processData:false,
                success: function(data) {
                    $dialog.html(data).dialog('open');
                },
                error: function () {
                    $.pnotify({
                        text:    'Falha ao enviar a requisição',
                        type:    'error',
                        hide:    false
                    });
                }
            });
        
        });
    };
    
    init = function()
    {
        validatorMethods();
        initDialogs();
        initGrid();
        initPesquisa();
        initEventos();
    
    };
    
    return {
        init: init,
        formatadorLink:formatadorLink
    };
})();

$(function(){
    Pessoa.init();
});