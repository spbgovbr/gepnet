$(function() {
	
	
var
            grid = null,
            lastsel = null,
            gridEnd = null,
            colModel = null,
            colNames = null,
            idProjeto = $("input[name='idprojeto']").val(),
            $dialogExcluir = $('#dialog-excluir'),
            $dialogEditar = $('#dialog-editar'),
            $dialogDetalhar = $('#dialog-detalhar'),
            $formEditar = $("form#form-aceite-editar");
            $formExcluir = $("form#form-aceite-excluir");


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Termo de Aceite - Detalhar',
        width: '880px',
        modal: true,
        buttons: {
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });
    
    $dialogExcluir = $('#dialog-excluir').dialog({
            autoOpen: false,
            title: 'Termo de Aceite - Excluir',
            width: '880px',
            modal: true,
            open: function(event, ui) {
                $("form#form-aceite-excluir").validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function(form) {
                            enviar_ajax("/projeto/termoaceite/excluir/format/json", "form#form-aceite-excluir", function() {
                            grid.trigger("reloadGrid");
                        });
                    }
                });
            },
            buttons: {
                'Excluir': function() {
                    $("form#form-aceite-excluir").submit();
                    $(this).dialog('close');
                },
                'Fechar': function() {
                    $(this).dialog('close');
                }
            }
        });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Termo de Aceite - Editar',
        width: '1020px',
        modal: false,
        open: function(event, ui) {
            $("form#form-aceite-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function(form) {
                        enviar_ajax("/projeto/termoaceite/editar/format/json", "form#form-aceite-editar", function() {
                        grid.trigger("reloadGrid");
                    });
                }
            });
        },
        close: function(event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function() {
                $("form#form-aceite-editar").trigger('submit');
                $(this).dialog('close');
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function(event) {
        event.preventDefault();
        var
                $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function(data) {
                $dialogDetalhar.html(data).dialog('open');
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

    $(document.body).on('click', "a.excluir, a.editar", function(event) {
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
                $dialog.html(data).dialog('open');
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


    function formatadorLink(cellvalue, options, rowObject)
    {
        var r = rowObject,
                params = '',
                url = {
            editar: base_url + '/projeto/termoaceite/editar',
            excluir: base_url + '/projeto/termoaceite/excluir',
            detalhar: base_url + '/projeto/termoaceite/detalhar',
            imprimir: base_url + '/projeto/termoaceite/imprimir'
        };
        params = '/idaceite/' + r[6] + '/identrega/' + r[7] + '/idprojeto/' + r[8] ;
        

        return	'<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
         	'<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
                '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>' +
                '<a data-target="#" class="btn actionfrm imprimir" title="Imprimir" data-id="' + cellvalue + '" href="' + url.imprimir + params + '" target="_blank"><i class="icon-print"></i></a>' 
                ;
    }
   
    colNames = ['Entrega Associada', 'Critério de Aceitação', 'Resposável', 'Produto ou Serviço Entregue', 'Parecer Final', 'Aceite','Operações'];
    colModel = [{
	        name: 'ac.nomatividadecronograma',
	        index: 'ac.nomatividadecronograma',
	        width: 25,
	        search: true
	    },{
            name: 'ac.descriteiroaceitacao',
            index: 'ac.descriteiroaceitacao',
            width: 20,
            hidden: false,
            search: true
        }, {
            name: 'nomresponsavel',
            index: 'nomresponsavel',
            width: 20,
            align: 'center',
            search: true
        }, {
            name: 'a.desprodutoservico',
            index: 'a.desprodutoservico',
            width: 25,
            search: true
        }, {
            name: 'a.desparecerfinal',
            index: 'a.desparecerfinal',
            width: 20,
            search: true
        },{
            name: 'a.flaaceite',
            index: 'a.flaaceite',
            width: 5,
            align: 'center',
            search: true
        },{
            name: 'a.identrega',
            index: 'a.identrega',
            width: 18,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/projeto/termoaceite/retornaaceitesjson/idprojeto/" + idProjeto,
        datatype: "json",
        mtype: 'post',
        width: '800',
        height: '700',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nomatividadecronograma',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function() {
        }
    });

    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();
    
    $.ajax({
        url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            idprojeto: idProjeto
        },
        success: function(data) {
             TemplateManager.get('dados-projeto', function(tpl){
                $("#dados-projeto").html(tpl(data.projeto));
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
    
    $('body').on('change', '#identrega', function() {
        $('.dados-entrega').hide();
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
    });
    
    
});

