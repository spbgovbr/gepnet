$(document).ready(function () {
    $("#dtacaoinicial_pesquisar").datepicker({
        onClose: function (selectedDate) {
            $("#dtacaofinal_pesquisar").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#dtacaofinal_pesquisar").datepicker({
        onClose: function (selectedDate) {
            $("#dtacaoinicial_pesquisar").datepicker("option", "maxDate", selectedDate);
        }
    });
});
var altura_ocupada = 120;

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        msgerroacesso = 'Acesso negado para essa ação.',
        colNames = ['Usuário', 'Função no Projeto', 'Funcionalidade', 'Tipo', 'Data', 'Hora'];
    colModel = [{
        name: 'nompessoa',
        index: 'nompessoa',
        width: 19,
        search: false,
        hidden: false,
        sortable: false
    }, {
        name: 'dsfuncaoprojeto',
        index: 'dsfuncaoprojeto',
        width: 15,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'descricao',
        index: 'descricao',
        width: 8,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'tipo',
        index: 'tipo',
        width: 8,
        hidden: false,
        search: false,
        sortable: false
    }, {
        name: 'dtacao',
        index: 'dtacao',
        width: 10,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'hracao',
        index: 'hracao',
        width: 5,
        hidden: false,
        search: false,
        sortable: false
    }];

    grid = jQuery("#list-grid-linhatempo").jqGrid({
        url: base_url + "/projeto/linhatempo/pesquisar/idprojeto/" + $('#idprojeto').val(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-linhatempo',
        sortname: 'dtacao',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
        },
        onSelectRow: function (id) {
        },
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-linhatempo', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    /**
     * Envia ajax por array de parametros
     */
    function ajax_arrparams(url, data, callback) {
        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'POST',
            data: data,
            success: function (data) {
                if (typeof data.msg.text !== 'string') {
                    $.formErrors(data.msg.text);
                    return;
                }
                $.pnotify(data.msg);
                if (callback && typeof (callback) === "function") {
                    callback(data);
                }
            },
            error: function () {
                $.pnotify({
                    text: msgerroacesso,
                    type: 'error',
                    hide: false
                });
            }
        });
    }

    $("form#form-linhatempo").validate();

    $('#btnpesquisar').click(function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/linhatempo/pesquisar?" + $("form#form-linhatempo").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });

    $('.mask-date').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        changeMonth: true,
        changeYear: true
    });

    $('.mask-date').mask('99/99/9999');

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });

});