var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        colNames = ['Nome Questionário', 'Tipo', 'Opera&ccedil;&otilde;es'];
    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 78,
        search: false,
        hidden: false,
        sortable: true
    }, {
        name: 'tipoquestionario',
        index: 'tipoquestionario',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idquestionario',
        index: 'idquestionario',
        width: 12,
        hidden: false,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];


    grid = jQuery("#list-grid-questionario").jqGrid({
        //caption: "Documentos",
        url: base_url + "/pesquisa/pesquisa/pesquisar",
        datatype: "json",
        mtype: 'post',
        width: '1370',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-questionario',
        sortname: 'nomquestionario',
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

    grid.jqGrid('navGrid', '#pager-grid-questionario', {
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
                publicar: base_url + '/pesquisa/pesquisa/publicar',
            };
        params = '/idquestionario/' + r[2];
//        console.log(rowObject);
        return '<a class="btn actionfrm publicar" title="Publicar" data-id="' + cellvalue + '" href="' + url.publicar + '"><i class="icon-ok"></i></a>'

    }

    $(document.body).on('click', "a.publicar", function (event) {
        event.preventDefault();
        var $this = $(this);
        isDuplicada($this.data().id);
        $('#dialog-modal').dialog('open');
        $('#publicar').val($this.data().id);
    });

    $('#dialog-modal').dialog({
        autoOpen: false,
        title: 'Confirmar Publicação',
        width: 500,
        height: 200,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $('#publicar').val('');
            $('.msg-duplicada').html('');
        },
        buttons: {
            'Continuar': function () {
                $.ajax({
                    url: base_url + '/pesquisa/pesquisa/publicar',
                    dataType: 'json',
                    type: 'POST',
                    data: {id: $('#publicar').val()},
                    success: function (data) {
                        $.pnotify({
                            text: data.msg,
                            type: 'success',
                            hide: true
                        });
                    },
                    error: function () {
                        $.pnotify({
                            text: 'Falha ao enviar a requisição',
                            type: 'error',
                            hide: false
                        });
                    }
                });

                $('#publicar').val('');
                $(this).dialog('close');
                $('.msg-duplicada').html('');
            },
            'Cancelar': function () {
                $('#publicar').val('');
                $(this).dialog('close');
                $('.msg-duplicada').html('');
            }
        }
    });

    function isDuplicada(id) {
        $.ajax({
            url: base_url + '/pesquisa/pesquisa/pesquisa-duplicada',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {id: id},
            success: function (data) {
                if (data.duplicada) {
                    $('#dialog-modal').append('<span class="msg-duplicada" style="color:red;">Atenção: Já existe uma pesquisa publicada com o mesmo nome.</span>');
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao verificar duplicidade',
                    type: 'error',
                    hide: false
                });
            }
        });

    }

    $("form#form-questionario-pesquisar").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        if ($("form#form-questionario-pesquisar").valid()) {
            grid.setGridParam({
                url: base_url + "/pesquisa/pesquisa/pesquisar?" + $("form#form-questionario-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
        }
    });

});
