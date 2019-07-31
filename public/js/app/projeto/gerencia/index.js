$(function() {

    var
            grid = null,
            lastsel = null,
            gridEnd = null,
            colModel = null,
            colNames = null,
            actions = {
        pesquisar: {
            form: $("form#form-pesquisar"),
            url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize()
        },
        detalhar: {
            dialog: $('#dialog-detalhar')
        },
        editar: {
            form: $("form#form-gerencia"),
            url: base_url + '/projeto/gerencia/editar/format/json',
            dialog: $('#dialog-editar')
        },
        arquivo: {
            form: $("form#form-escritorio-arquivo"),
            url: base_url + '/cadastro/escritorio/editar-arquivo/format/json',
            dialog: $('#dialog-arquivo')
        },
        excluir: {
            form: $("form#form-escritorio-excluir"),
            url: base_url + '/cadastro/escritorio/excluir/format/json',
            dialog: $('#dialog-excluir')
        },
        desbloquear: {
            form: $("form#form-desbloqueio"),
            url: base_url + '/projeto/gerencia/desbloquear/format/json',
            dialog: $('#dialog-desbloquear')
        }
    };

    $(".select2").select2();





    //Reset button
    $("#resetbutton").click(function() {
        //$('.container-importar').slideToggle();
        $(".select2").select2('data', null);
        $("#nomprograma").select2('data', null);
        $("#domstatusprojeto").select2('data', null);
        $("#idescritorio").select2('data', null);
//        $("#nomalinhamento").select2('data', null);
//        $("#nomacao").select2('data', null);
//        $("#nomnatureza").select2('data', null);

    });
    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    var options = {
        url: actions.editar.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function(data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };

    actions.editar.form.ajaxForm(options);

    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Gerencia - Editar',
        width: '800px',
        modal: false,
        open: function(event, ui) {

        },
        close: function(event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function() {
                //console.log('submit');
                $('form#form-gerencia').submit();
                //$('form#form-documento').submit();
                //console.log(actions.editar.form);
                //$formEditar.on('submit');
                //$(actions.editar.form).trigger('submit');
                //enviar_ajax(actions.editar.url, actions.editar.form );
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar_", function(event) {
        event.preventDefault();
        var
                $this = $(this),
                $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function(data) {


                actions.editar.dialog.html(data).dialog('open');
                $("#idtipodocumento").select2();
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
                });
                $("#accordion").accordion();
                $('form#form-gerencia').validate();

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

    /*xxxxxxxxxx DETALHAR xxxxxxxxxx*/

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Escritorio - Detalhar',
        width: '810px',
        modal: false,
        buttons: {
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function(event) {
        //event.preventDefault();
        var
                $this = $(this);

//        $.ajax({
//            url: $this.attr('href'),
//            dataType: 'html',
//            type: 'GET',
//            async: true,
//            cache: false,
//            //data: $formEditar.serialize(),
//            processData: true,
//            success: function(data) {
//                //console.log(data);
//                actions.detalhar.dialog.html(data).dialog('open');
//            },
//            error: function() {
//                $.pnotify({
//                    text: 'Falha ao enviar a requisição',
//                    type: 'error',
//                    hide: false
//                });
//            }
//        });
    });

    /*xxxxxxxxxx ARQUIVO xxxxxxxxxx*/
    var optionsArquivo = {
        url: actions.arquivo.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function(data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };
    actions.arquivo.form.ajaxForm(optionsArquivo);

    //url_editar = base_url + '/cadastro/documento/edit';
    actions.arquivo.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Editar arquivo',
        width: '800px',
        modal: false,
        open: function(event, ui) {

        },
        close: function(event, ui) {
            actions.arquivo.dialog.empty();
        },
        buttons: {
            'Salvar': function() {
                console.log('submit');
                $('form#form-documento-arquivo').submit();
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.arquivo, a.btn_editar", function(event) {
        event.preventDefault();
        var
                $this = $(this),
                $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function(data) {
                actions.arquivo.dialog.html(data).dialog('open');
                $('form#form-documento-arquivo').validate();

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

    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    var optionsExcluir = {
        url: actions.excluir.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function(data) {
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                $("#resetbutton").trigger('click');
                grid.trigger("reloadGrid");
            }
        }
    };

    actions.excluir.form.ajaxForm(optionsExcluir);

    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Excluir',
        width: '810px',
        modal: false,
        buttons: {
            'Excluir': function() {
                $('form#form-documento-excluir').submit();
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluir", function(event) {
        event.preventDefault();
        var
                $this = $(this),
                $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            //data: $formEditar.serialize(),
            processData: false,
            success: function(data) {
                actions.excluir.dialog.html(data).dialog('open');
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

    $(document.body).on('click', "a.desbloquear", function(event) {
        event.preventDefault();
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            //data: $formEditar.serialize(),
            //processData: false,
            success: function(data) {
                actions.desbloquear.dialog.html(data).dialog('open');
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

    actions.desbloquear.dialog.dialog({
        autoOpen: false,
        title: 'Desbloquear Projeto',
        width: '810px',
        modal: false,
        buttons: {
            'Salvar': function() {
                $.ajax({
                    url: base_url + '/projeto/gerencia/desbloquear/format/json',
                    dataType: 'html',
                    type: 'POST',
                    async: true,
                    cache: true,
                    data: { 'idprojeto':$('#idprojeto').val(),
                            'desjustificativa':$('#desjustificativa').val()
                    },
                    //processData: false,
                    success: function(data) {
//                        actions.desbloquear.dialog.html(data).dialog('open');
                        $.pnotify({
                            text: 'Projeto desbloqueado com sucesso.',
                            type: 'success',
                            hide: true
                        });
                        grid.trigger("reloadGrid");
                    },
                    error: function() {
                        $.pnotify({
                            text: 'Falha ao enviar a requisição',
                            type: 'error',
                            hide: false
                        });
                    }
                });
                $(this).dialog('close');
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });
      function formatadorSituacao(cellvalue, options, rowObject)
    {
        var situacao = rowObject[16];
         return situacao;

    }

    function formatadorLink(cellvalue, options, rowObject)
    {
        var r = rowObject,
                params = '',
                url = {
//            editar: base_url + '/projeto/tap/informacoesiniciais',
            editar: base_url + '/projeto/tap/informacoesiniciais',
            imprimir_plano: base_url + '/projeto/planoprojeto/imprimir',
            imprimir_tap: base_url + '/projeto/tap/imprimir',
            arquivo: base_url + '/projeto/gerencia/editar-arquivo',
            cronograma: base_url + '/projeto/cronograma/index',
            projeto: base_url + '/projeto/tap/index',
            desbloqueio: base_url + '/projeto/gerencia/desbloquear'
        };
        params = '/idprojeto/' + r[14];

        //console.log(r[16]);

        $return =  '<a target="_blank" class="btn actionfrm detalhar" title="Imprimir PLANO DE PROJETO" data-id="' + cellvalue + '" href="' + url.imprimir_plano + params + '"><i class="icon-print"></i></a>' +
                   '<a target="_blank" class="btn actionfrm detalhar" title="Imprimir TERMO DE ABERTURA DE PROJETO (TAP)" data-id="' + cellvalue + '" href="' + url.imprimir_tap + params + '"><i class="icon-print"></i></a>'+ '\n' + 
                   '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar TAP" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
                   '<a target="_self" class="btn actionfrm editar" title="Gerenciar" data-id="' + cellvalue + '" href="' + url.projeto + params + '"><i class="icon-wrench"></i></a>';
        
       /* if(r[16]){
            $return += '<a class="btn actionfrm editar disabled" title="Editar TAP" href="#"><i class="icon-edit"></i></a>' +
                       '<a class="btn actionfrm editar disabled" title="Gerenciar" href="#"><i class="icon-wrench"></i></a>'+            
                       '<a data-target="#dialog-desbloquear" class="btn actionfrm desbloquear" title="Projeto Bloqueado" data-id="' + cellvalue + '" href="' + url.desbloqueio + params + '"><i class="icon-folder-close"></i></a>';
        }else{      
        
            $return += '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar TAP" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
                       '<a target="_self" class="btn actionfrm editar" title="Gerenciar" data-id="' + cellvalue + '" href="' + url.projeto + params + '"><i class="icon-wrench"></i></a>'+
                       '<a class="btn actionfrm disabled" title="Projeto Ativo" data-id="" href=""><i class=" icon-folder-open"></i></a>';
        }*/
        //console.log(r[16]);
        
        /*if(r[8].length > 1){
            $return += '<a target="_self" class="btn actionfrm cronograma" title="Cronograma" data-id="' + cellvalue + '" href="' + url.cronograma + params + '"><i class="icon-list"></i></a>';
        }*/

        
            
        return $return;
                
    }

//    function formatadorImg(cellvalue, options, rowObject)
//    {
//    	var path = base_url + '/img/ico_verde.gif';
//    	return '<img src="'+ path +'" />';
//    }

    function formatadorImgPrazo(cellvalue, options, rowObject)
    {
//      var path = base_url + '/img/ico_verde.gif';
//      return '<img src="' + path + '" />';
        var retorno = '-';

        if (rowObject[11] >= rowObject[15]) {
            var retorno = '<span class="badge badge-important" title=' + rowObject[11] + '>P</span>';
        } else if (rowObject[11] > 0) {
            var retorno = '<span class="badge badge-warning" title=' + rowObject[11] + '>P</span>';
        } else {
            var retorno = '<span class="badge badge-success" title=' + rowObject[11] + '>P</span>';
        }

        if (rowObject[11] === "-")
            return rowObject[11];

        return retorno;
    }

    function formatadorImgRisco(cellvalue, options, rowObject)
    {
        var retorno = '-';

        if (rowObject[12] === '1') {
            var retorno = '<span class="badge badge-success">R</span>';
        } else if (rowObject[12] === '2') {
            var retorno = '<span class="badge badge-warning">R</span>';
        } else if (rowObject[12] === '3') {
            var retorno = '<span class="badge badge-important">R</span>';
        }

        return retorno;
    }


  


    //'Sigla', 'Nome', 'Responsavel-1', 'Responsavel-2', 'Mapa', 'Situação', 'Logo', 'Operações'
    colNames = ['Programa', 'Projeto', 'Gerente', 'Codigo', 'Publicado', 'Início', 'Término Meta', 'Termino Tendencia', 'Previsto', 'Concluído', 'Atraso', 'Prazo', 'Risco', 'Últim. Acompanhamento', 'Situação','Operações'];
    colModel = [
        {
            name: 'nomprograma',
            index: 'nomprograma',
            align: 'center',
            width: 25,
            hidden: false,
            search: false
        }, {
            name: 'nomprojeto',
            index: 'nomprojeto',
            align: 'center',
            width: 60,
            hidden: false,
            search: false
        }, {
            name: 'idgerenteprojeto',
            index: 'idgerenteprojeto',
            align: 'center',
            width: 60,
            hidden: false,
            search: false
        }, {
            name: 'nomcodigo',
            index: 'nomcodigo',
            align: 'center',
            width: 60,
            hidden: true,
            search: false
        }, {
            name: 'flapublicado',
            index: 'flapublicado',
            align: 'center',
            width: 20,
            search: true,
            //formatter: formatadorSituacao
        }, {
            name: 'datinicio',
            index: 'datinicio',
            align: 'center',
            width: 20,
            search: true
        }, {
            name: 'datfimplano',
            index: 'datfimplano',
            align: 'center',
            width: 20,
            search: true
        }, {
            name: 'datfim',
            index: 'datfim',
            align: 'center',
            width: 20,
            search: true
        }, {
            name: 'previsto',
            index: 'previsto',
            width: 15,
            search: false,
            sortable: false
        }, {
            name: 'concluido',
            index: 'concluido',
            width: 15,
            search: false,
            sortable: false
        }, {
            name: 'atraso',
            index: 'atraso',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            hidden: true
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorImgPrazo
        }, {
            name: 'Risco',
            index: 'Risco',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            formatter: formatadorImgRisco
        }, {
            name: 'ultimoacompanhamento',
            index: 'ultimoacompanhamento',
            width: 28,
            search: false,
            sortable: false
        }, { 
            name: 'situacao',
            index: 'situacao',
            align: 'center',
            width: 30,
            search: true,
            formatter: formatadorSituacao
        }
        ,{
            name: 'id',
            index: 'id',
            width: 33,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];
//1210
    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/gerencia/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1000',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomprojeto',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function() {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
        }
    });

    //grid.jqGrid('filterToolbar');
    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');

    actions.pesquisar.form.on('submit', function(e) {

        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/gerencia/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    $("#accordion").accordion();
    
    resizeGrid();
});