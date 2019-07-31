var altura_ocupada = 120;

$(function () {

    function formatadorLinkEditar(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                clonar: base_url + '/diagnostico/questionario/form-clonar'
            };
        params = '/idquestionariodiagnostico/' + r[4];
        $return = '<a title="Editar" class="link" id="' + r[4] + '"' +
            'style="text-decoration: none; color: #0b83d1;" onMouseOver="this.style.textDecoration=\'underline\'; this.style.color = \'#1e395b\';" ' +
            'onMouseOut="this.style.textDecoration=\'none\'; this.style.color = \'#0b83d1\';" href="' + url.clonar + params + '" >' + r[0] + '</a>\n';
        return $return;
    }

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        actions = {
            clonardiagnostico: {
                form: $("form#form-clonar"),
                url: base_url + '/diagnostico/questionario/clonar-add',
                dialog: $('#modal')
            }
        };
    msgerroacesso = 'Acesso negado.',
        colNames = ['Nome do Questionário', 'Tipo de Questionário', 'Diagnóstico', 'Data de Cadastro', 'id'];
    colModel = [{
        name: 'nomquestionario',
        index: 'nomquestionario',
        width: 19,
        search: false,
        hidden: false,
        sortable: true,
        formatter: formatadorLinkEditar
    }, {
        name: 'tipo',
        index: 'tipo',
        width: 15,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'observacao',
        index: 'observacao',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'dtcadastro',
        index: 'dtcadastro',
        width: 8,
        hidden: false,
        search: false,
        sortable: true
    }, {
        name: 'idquestionariodiagnostico',
        index: 'idquestionariodiagnostico',
        width: 8,
        hidden: true,
        search: false,
        sortable: true
    }];

    grid = jQuery("#list-grid-diagnostico").jqGrid({
        url: base_url + "/diagnostico/questionario/pesquisar?" + $("form#form-pesquisar").serialize(),
        datatype: "json",
        mtype: 'post',
        width: '645',
        height: '200',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-diagnostico',
        sortname: 'dtinicio',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
        },
        loadComplete: function (data) {
//            console.log('sucesso');
//            console.log(data);
        },
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid-diagnostico', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');
    resizeGrid();

    $("form#form-pesquisar").validate();

    $(document.body).on('click', "a.link", function (event) {
        event.preventDefault();

        id = $(this).attr('id');
        var
            $this = $(this),
            $dialog = $($this.data('target'));

        $.ajax({
            url: $this.attr('href'),
            data: id,
            dataType: 'html',
            type: 'POST',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                //$("#modal").dialog("open");
                actions.clonardiagnostico.dialog.html(data).dialog('open');
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


    actions.clonardiagnostico.dialog.dialog({
        autoOpen: false,
        title: 'Questionário - Clonar',
        width: '900px',
        modal: true,
        open: function (event, ui) {
        },
        close: function (event, ui) {
            grid.setGridParam({
                url: base_url + "/diagnostico/questionario/pesquisar?" + $("form#form-pesquisar").serialize(),
                page: 1
            }).trigger("reloadGrid");
            resizeGrid();
        },
        buttons: {
            "Clonar": {
                text: "Clonar",
                id: "submitbuttonClonar",
                click: function () {
                    //alert("here");
                }
            },
            'Fechar': function () {
                grid.setGridParam({
                    url: base_url + "/diagnostico/questionario/pesquisar?" + $("form#form-pesquisar").serialize(),
                    page: 1
                }).trigger("reloadGrid");
                resizeGrid();
                $(this).dialog('close');
            }

        }

    });


    $("body").on("click", "#submitbuttonClonar", function (e) {
        e.preventDefault();
        $form = $("form#form-clonar");

        $form.validate().form();
        if ($form.valid()) {
            var param = $form.serialize();
            $.ajax({
                url: base_url + '/diagnostico/questionario/clonar-add/format/json',
                dataType: 'json',
                type: 'POST',
                data: param,
                success: function (data) {
                    $.pnotify(data.msg.text);
                    setTimeout(function () {
                        window.location.href =
                            base_url + "/diagnostico/questionario/dadosbasicos/idquestionariodiagnostico/" + data.msg.idquestionariodiagnostico +
                            '/tpquestionario/' + data.msg.tpquestionario;
                    }, 1000);
                },
                error: function () {
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                }
            });
        } else {
            return false;
        }

    });

});