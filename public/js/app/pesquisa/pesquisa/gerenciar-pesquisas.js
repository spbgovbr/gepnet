var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Pesquisa', 'Tipo', 'Situa&ccedil;&atilde;o', 'Respondidas', 'Cadastrada', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 64,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'tipoquestionario',
        index: 'tipoquestionario',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'situacao',
        index: 'situacao',
        width: 5,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'respondidas',
        index: 'respondidas',
        width: 5,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'datcadastro',
        index: 'datcadastro',
        width: 8,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'idpesquisa',
        index: 'idpesquisa',
        width: 10,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];


    grid = jQuery("#list-grid-pesquisa").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/pesquisa/listar-publicadas",
        datatype: "json",
        mtype: 'post',
        width: '1370',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-pesquisa',
        sortname: 'datcadastro',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
            //console.log('teste');
            $("a.actionfrm").tooltip({placement: 'left'});
        },
        onSelectRow: function (id) {
//            if(window.selectRow){
//                var row = grid.getRowData(id);
//                selectRow(row);
//            } else {
//                alert('Função [selectRow] não está definida');
//            }
        },
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-pesquisa', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                publicar: base_url + '/pesquisa/pesquisa/publicar-encerrar',
                respondidas: base_url + '/pesquisa/pesquisa/pesquisas-respondidas',
                detalhar: base_url + '/pesquisa/pesquisa/detalhar-pesquisa',
            },
            styleClass = 'publicar',
            title = 'Republicar';

        params = '/idpesquisa/' + r[5];
//        console.log(rowObject);

        if (r[2] === 'Publicada') {
            styleClass = 'btn-success encerrar';
            title = 'Encerrar Pesquisa';
        }

        return '<a class="btn actionfrm ' + styleClass + '" title="' + title + '" data-id="' + cellvalue + '" href="' + url.publicar + params + '"><i class="icon-ok"></i></a>' +
            '<a class="btn actionfrm detalhar" title="Detalhar Pesquisa" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a class="btn actionfrm respondidas" title="Pesquisas Respondidas" data-id="' + cellvalue + '" href="' + url.respondidas + params + '"><i class=" icon-check"></i></a>';
    }

    //detalhar pesquisa
    $(document.body).on('click', "a.detalhar", function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: $this.attr('href'),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                $('#dialog-detalhar').html(data).dialog('open');
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });

    });

    $('#dialog-detalhar').dialog({
        autoOpen: false,
        title: 'Detalhar Pesquisa',
        width: 1000,
        height: 750,
        modal: false,
        open: function (event, ui) {
        },
        close: function (event, ui) {
            $('#dialog-detalhar').empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    //republicar pesquisa
    $(document.body).on('click', "a.publicar", function (event) {
        event.preventDefault();
        var $this = $(this);
        $('#dialog-republicar').dialog('open');
        $('#pesquisa').val($this.data().id);
    });

    $('#dialog-republicar').dialog({
        autoOpen: false,
        title: 'Confirmar Publicação',
        width: 500,
        height: 200,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#pesquisa').val('');
        },
        buttons: {
            'Continuar': function () {
                publicarEncerrar();

                $('#pesquisa').val('');
                $(this).dialog('close');
            },
            'Cancelar': function () {
                $('#pesquisa').val('');
                $(this).dialog('close');
            }
        }
    });

    //encerrar pesquisa
    $(document.body).on('click', "a.encerrar", function (event) {
        event.preventDefault();
        var $this = $(this);
        $('#dialog-encerrar').dialog('open');
        $('#pesquisa').val($this.data().id);
    });

    $('#dialog-encerrar').dialog({
        autoOpen: false,
        title: 'Confirmar Encerramento',
        width: 500,
        height: 200,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#pesquisa').val('');
        },
        buttons: {
            'Continuar': function () {
                publicarEncerrar();

                $('#pesquisa').val('');
                $(this).dialog('close');
            },
            'Cancelar': function () {
                $('#pesquisa').val('');
                $(this).dialog('close');
            }
        }
    });

    function publicarEncerrar() {
        $.ajax({
            url: base_url + '/pesquisa/pesquisa/publicar-encerrar',
            dataType: 'json',
            type: 'POST',
            data: {idpesquisa: $('#pesquisa').val()},
            success: function (data) {
                $.pnotify({
                    text: data.msg,
                    type: 'success',
                    hide: true
                });
                grid.trigger("reloadGrid");
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    $('.mask-date').mask('99/99/9999');
    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $("form#form-pesquisa-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-pesquisa-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/pesquisa/listar-publicadas?" + $("form#form-pesquisa-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });

});
