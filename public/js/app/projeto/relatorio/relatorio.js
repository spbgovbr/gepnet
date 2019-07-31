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
                    url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
                },
                incluir: {
                    form: $("form#form-status-report-incluir"),
                    url: base_url + '/projeto/relatorio/add/format/json',
                    dialog: $('#dialog-incluir')
                },
                detalhar: {
                    url: base_url + '/projeto/relatorio/detalhar/format/json',
                    dialog: $('#dialog-detalhar')
                },
                editar: {
                    form: $("form#form-status-report-editar"),
                    url: base_url + '/projeto/relatorio/editar/format/json',
                    dialog: $('#dialog-editar')
                },
                excluir: {
                    form: $("form#form-status-report-excluir"),
                    url: base_url + '/projeto/relatorio/excluir/format/json',
                    dialog: $('#dialog-excluir')
                }

            };
    //Reset button
    $("#resetbutton").click(function() {
        $("#idprojeto").val('');
        $("#idescritorio").val('');
        $("#idprograma").val('');
        $("#domstatusprojeto").val('');
        $("#codobjetivo").val('');
        $("#codacao").val('');
        $("#codnatureza").val('');
        $("#codsetor").val('');
    });

        /*xxxxxxxxxx INCLUIR xxxxxxxxxx*/
    var options = {
        url: actions.incluir.url,
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
                console.log("sucesso para fechar agora.");
                grid.trigger("reloadGrid");
            }

        }
    };

    actions.incluir.form.ajaxForm(options);

    actions.incluir.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Incluir',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function(event, ui) {

        },
        close: function(event, ui) {
            actions.incluir.dialog.empty();
            gerachartacompanhamento();
            gerachartatraso();
        },
        buttons: {
            'Salvar': function() {
//                console.log('submit');
                $('form#form-status-report-incluir').submit();
                $(this).dialog('close');

                //$('form#form-documento').submit();
//                console.log(actions.editar.form);s
                //$formEditar.on('submit');
                //$(actions.editar.form).trigger('submit');
                //enviar_ajax(actions.editar.url, actions.editar.form );
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
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
        title: 'Relatório - Editar',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function(event, ui) {

        },
        close: function(event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function() {
//                console.log('submit');
                $('form#form-status-report-editar').submit();
                //$('form#form-documento').submit();
//                console.log(actions.editar.form);s
                //$formEditar.on('submit');
                //$(actions.editar.form).trigger('submit');
                //enviar_ajax(actions.editar.url, actions.editar.form );
            },
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.incluir, a.editar, a.detalhar", function(event) {
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
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
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
    
    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    var options = {
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

    actions.excluir.form.ajaxForm(options);

    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Excluir',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function(event, ui) {

        },
        close: function(event, ui) {
            actions.excluir.dialog.empty();
        },
        buttons: {
            'Excluir': function() {
                
     $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "/projeto/relatorio/relatoriojson/idprojeto/" + $("#ip").val(), 
                
                success: function(data) {
                    if(data.records > 1){
                         $('form#form-status-report-excluir').submit();
                         actions.excluir.dialog.html(data).dialog('close');
                    }else{
                        actions.excluir.dialog.html(data).dialog('close');
                        alert('Acompanhamento só poderá ser deletado se houver mais de um');
                        return false;
                    }
                }
            });
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

    //##### DETALHAR #######
    //actions.detalhar.form.ajaxForm(options);

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Detalhar',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function(event, ui) {

        },
        close: function(event, ui) {
            actions.detalhar.dialog.empty();
        },
        buttons: {
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });


    function formatadorLink(cellvalue, options, rowObject)
    {
        var r = rowObject,
                params = '',
                url = {
                    detalhar: base_url + '/projeto/relatorio/detalhar',
                    editar: base_url + '/projeto/relatorio/editar',
                    excluir: base_url + '/projeto/relatorio/excluir'
                };
        params = '/idstatusreport/' + r[8] + '/idprojeto/' + $("#ip").val();
//        console.log(r);

        //return  '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
               '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
               '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
    }

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

    colNames = ['Data Acompanhamento', 'Previsto', 'Concluído', 'Tendência Encerramento', 'Cronograma PDF', 'Usuário', 'Prazo', 'Risco', 'Operações'];
    colModel = [
        {
            name: 'datacompanhamento',
            index: 'datacompanhamento',
            align: 'center',
            width: 21,
            hidden: false,
            search: false
        }, {
            name: 'numpercentualprevisto',
            index: 'numpercentualprevisto',
            align: 'center',
            width: 9,
            hidden: false,
            search: false
        }, {
            name: 'numpercentualconcluido',
            index: 'numpercentualconcluido',
            align: 'center',
            width: 11,
            hidden: false,
            search: false
        }, {
            name: 'datfimprojetotendencia',
            index: 'datfimprojetotendencia',
            align: 'center',
            width: 21,
            search: true,
        }, {
            name: 'cronogramapdf',
            index: 'cronogramapdf',
            align: 'center',
            width: 15,
            search: false,
            sortable: false,
        }, {
            name: 'idcadastrador',
            index: 'idcadastrador',
            align: 'center',
            width: 55,
            search: true
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
            //formatter: formatadorImgPrazo
        }, {
            name: 'Risco',
            index: 'risco',
            width: 15,
            align: 'center',
            sortable: false,
            search: false,
            //formatter: formatadorImgRisco
        }, {
            name: 'idstatusreport',
            index: 'idstatusreport',
            width: 25,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/relatorio/relatoriojson/idprojeto/" + $("#ip").val(),
        datatype: "json",
        mtype: 'post',
        width: '1210',
        height: '150px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'datacompanhamento',
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
            url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    resizeGrid();
});