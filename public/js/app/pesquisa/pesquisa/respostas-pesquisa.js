var altura_ocupada = 170;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Nº', 'Pessoa', 'Data resposta', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'idresultado',
        index: 'idresultado',
        width: 10,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'nompessoa',
        index: 'nompessoa',
        width: 70,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'datcadastro',
        index: 'datcadastro',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idpesquisa',
        index: 'idpesquisa',
        width: 10,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];


    grid = jQuery("#list-grid-resultado").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/pesquisa/listar-respostas-pesquisa/idpesquisa/" + $('#idpesquisa').val(),
        datatype: "json",
        mtype: 'post',
        width: '1370',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-resultado',
        sortname: 'idresultado',
        viewrecords: true,
        sortorder: "asc",
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

    grid.jqGrid('navGrid', '#pager-grid-resultado', {
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
                resposta: base_url + '/pesquisa/pesquisa/resposta-pesquisa',
            },

            params = '/cpf/' + r[4] + '/idpesquisa/' + r[3] + '/idresultado/' + r[0];
//        console.log(rowObject);

        return '<a class="btn actionfrm respostas" title="Detalhar Resposta" data-id="' + cellvalue + '" href="' + url.resposta + params + '"><i class="icon-tasks"></i></a>';

    }

    $(document.body).on('click', "a.respostas", function (event) {
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
                $('#dialog-respostas').html(data).dialog('open');
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

    $('#dialog-respostas').dialog({
        autoOpen: false,
        title: 'Resposta Pesquisa',
        width: 1030,
        height: 750,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#dialog-respostas').empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $("form#form-resultado-pesquisa").validate();
    $('.mask-date').mask('99/99/9999');
    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-resultado-pesquisa").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/pesquisa/listar-respostas-pesquisa?" + $("form#form-resultado-pesquisa").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });

});
