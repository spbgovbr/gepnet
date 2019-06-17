$(function () {

    var
        $form = $("#form-vincular"),
        iddiagnostico = $("input[name='iddiagnostico']").val(),
        actions = {
            pesquisar: {
                form: $("form#form-quest-vinculado-pesquisar"),
                url: base_url + "/diagnostico/pesquisacidadaos/buscaquestionariovinculadocidadao?tpquestionario=2&"
            },
            vincular: {
                form: $("form#form-vincular"),
                url: base_url + "/diagnostico/pesquisacidadaos/vincularquestionariocidadao/format/json"
            },
            responder: {
                form: $("form#form-responder"),
                url: base_url + "/diagnostico/pesquisacidadaos/responderquestionariocidadao/format/json"
            },
            listagem: {
                //form : $("form#form-responder"),
                url: base_url + "/diagnostico/pesquisacidadaos/buscaquestionariovinculadocidadao/tpquestionario/2/iddiagnostico/" + iddiagnostico
            }
        },
        msgerror = "Não foi possivel realizar esta operação.",
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null;

    //$('#gbox_list-grid').height(30);
    //$('#gbox_list-grid').width($('.region-center').width()-10);
    //$('#menuquest').width($('.region-center').width());

    $('#ds_item-in').click(function (e) {
        listLeftMoveQuest();
    });

    $('#ds_item-out').click(function (e) {
        listRightMoveQuest();
    });

    $('#ds_questionario').dblclick(function (e) {
        listLeftMoveQuest();
    });

    $('#idquestionario').dblclick(function (e) {
        listRightMoveQuest();
    });

    function listLeftMoveQuest() {
        var selectedOpts = $('#ds_questionario option:selected');
        if (selectedOpts.length === 0) {
            e.preventDefault();
        }

        $('#idquestionario').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    function listRightMoveQuest() {
        var selectedOpts = $('#idquestionario option:selected');
        if (selectedOpts.length === 0) {
            e.preventDefault();
        }

        $('#ds_questionario').append($(selectedOpts).clone());
        $(selectedOpts).remove();
    }

    $("body").on("click", "#submitbutton", function () {
        if ($form != 'undefinde') {
            $form.validate().form();
            var i = 0;
            var arrayQuestionario = [];
            $("#idquestionario option").each(function () {
                arrayQuestionario[i] = $(this).val();
                i++;
            });
            $('#questionario').val(arrayQuestionario.join());
            if ($form.valid()) {
                var param = $form.serialize();
                $.ajax({
                    url: actions.vincular.url,
                    dataType: 'json',
                    type: 'POST',
                    data: param,
                    success: function (data) {
                        if (data.msg.type == 'success') {
                            $.pnotify(data.msg);
                            window.location.href = base_url + "/diagnostico/pesquisacidadaos/listarquestionariocidadao/tpquestionario/2/iddiagnostico/" + iddiagnostico;
                        } else {
                            $.pnotify(data.msg.text);
                        }
                    },
                    error: function () {
                        $.pnotify({
                            text: msgerror,
                            type: 'error',
                            hide: false
                        });
                    }
                });
            }
        }
    });


    function formatadorLink(cellvalue, options, rowObject) {

        var r = rowObject,
            params = '',
            linkEditar = '',
            url = {
                responder: base_url + '/diagnostico/pesquisacidadaos/gerarnumeroquestionariocidadao'
            };
        params = '/idquestionariodiagnostico/' + r[1] + '/iddiagnostico/' + r[2] + '/tpquestionario/2';

        if (r[3] == 'Sim') {
            $return = '<a title="Responder" ' +
                'style="text-decoration: none; color: #0b83d1;" onMouseOver="this.style.textDecoration=\'underline\'; this.style.color = \'#1e395b\';" ' +
                'onMouseOut="this.style.textDecoration=\'none\'; this.style.color = \'#0b83d1\';" href="' + url.responder + params + '" >' + r[0] + '</a>\n';
        } else {
            $return = '<a title="Respondido" ' +
                'style="text-decoration: none; color: #000;" ' +
                ' href="#" >' + r[0] + '</a>\n';
        }
        return $return;
    }

    colNames = ['Nome do Questionário', 'idquestionario', 'iddiagnostico', 'Disponível'];
    colModel = [
        {
            name: 'nomquestionario',
            index: 'nomquestionario',
            width: 60,
            search: false,
            hidden: false,
            sortable: true,
            formatter: formatadorLink
        }, {
            name: 'idquestionario',
            index: 'idquestionario',
            width: 1,
            search: false,
            hidden: true,
            sortable: true,
        }, {
            name: 'iddiagnostico',
            index: 'iddiagnostico',
            width: 1,
            search: false,
            hidden: true,
            sortable: true,
        }, {
            name: 'disponivel',
            index: 'disponivel',
            width: 20,
            align: 'center',
            search: false,
            hidden: false,
            sortable: true,
        }
    ];

    grid = jQuery("#list-grid").jqGrid({
        caption: "Listagem de Questionarios Vinculados dos Cidadãos",
        url: actions.listagem.url,
        datatype: "json",
        mtype: 'POST',
        width: 1048,
        height: 'auto',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid',
        sortname: 'nomquestionario',
        viewrecords: true,
        sortorder: "asc",
        loadError: function () {
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        },
    });

    grid.jqGrid('navGrid', '#pager-grid', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');

    actions.pesquisar.form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: actions.pesquisar.url + $("form#form-quest-vinculado-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
    });
    //resizeGrid();
});