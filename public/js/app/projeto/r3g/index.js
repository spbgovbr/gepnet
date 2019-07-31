$(function () {
    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogCadastrar = $('#dialog-cadastrar'),
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            editar: {
                form: $("form#form-r3g-edit"),
                url: base_url + '/projeto/r3g/editar/format/json',
                dialog: $('#dialog-editar')
            },
            excluir: {
                form: $("form#form-r3g-excluir"),
                url: base_url + '/projeto/r3g/excluir/format/json',
                dialog: $('#dialog-excluir')
            },
            cadastrar: {
                form: $("form#form-r3g"),
                url: base_url + '/projeto/r3g/add/format/json',
                dialog: $('#dialog-cadastrar'),
            }
        };

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    //limitadores de campo textarea
    $("body").delegate("#desplanejado", "focusin", function () {
        var max = $(this).attr('data-rule-maxlength');
        $(this).limit(max, '#contDesPlanejado');
    });
    $("body").delegate("#desrealizado", "focusin", function () {
        var max = $(this).attr('data-rule-maxlength');
        $(this).limit(max, '#contDesRealizado');
    });
    $("body").delegate("#desconsequencia", "focusin", function () {
        var max = $(this).attr('data-rule-maxlength');
        $(this).limit(max, '#contDesConsequencia');
    });
    $("body").delegate("#descausa", "focusin", function () {
        var max = $(this).attr('data-rule-maxlength');
        $(this).limit(max, '#contDesCausa');
    });
    $("body").delegate("#descontramedida", "focusin", function () {
        var max = $(this).attr('data-rule-maxlength');
        $(this).limit(max, '#contDesContramedida');
    });


    $("body").delegate(".datemask-BR", "focusin", function () {
        var $this = $(this);
        $(this).mask('99/99/9999');
        $this.attr('readonly', true);
        $this.datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    });

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'R3g - Editar',
        width: 1145,
        height: 580,
        modal: true,
        open: function (event, ui) {
            $("form#form-r3g-editar").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/projeto/r3g/editar/format/json", "form#form-r3g-editar", function () {
                        grid.trigger("reloadGrid");
                    });
                }
            });
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR'
            });
        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $('form#form-r3g-editar').trigger('submit');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.editar", function (event) {
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
                actions.editar.dialog.html(data).dialog('open');
                $('form#form-r3g-edit').validate({
                    errorClass: 'error',
                    validClass: 'success',
                    submitHandler: function (form) {
                        enviar_ajax("/projeto/r3g/edit/format/json", "form#form-r3g-edit", function (data) {
                            if (data.success) {
                                grid.trigger('reloadGrid');
                            }
                        });
                    }
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
    });

    /*xxxxxxxxxx EXCLUIR xxxxxxxxxx*/
    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'R3g - Excluir',
        width: 810,
        height: 650,
        modal: false,
        buttons: {
            'Excluir': function () {
                var arrParams = {idr3g: $("#dialog-excluir").find('input[name="idr3g"]').val()};
                ajax_arrparams("/projeto/r3g/excluir/format/json", arrParams, function (data) {
                    if (data.success) {
                        grid.trigger('reloadGrid');
                        actions.excluir.dialog.dialog('close');
                    }
                });
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    //    /**
//     * Envia ajax por array de parametros
//     */
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
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: true
                });
            }
        });
    }

    $(document.body).on('click', "a.excluir", function (event) {
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
                actions.excluir.dialog.html(data).dialog('open');
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

    function formatadorImgPrazo(cellvalue, options, rowObject) {
//      var path = base_url + '/img/ico_verde.gif';
        return '<span class="badge badge-important" title="P">P</span>';
        var retorno = '-';

        if (rowObject[11] >= rowObject[15]) {
            var retorno = '<span class="badge badge-important" title=' + rowObject[11] + '>P</span>';
        } else if (rowObject[11] > 0) {
            var retorno = '<span class="badge badge-warning" title=' + rowObject[11] + '>P</span>';
        } else {
            var retorno = '<span class="badge badge-success" title=' + rowObject[11] + '>P</span>';
        }

        if (rowObject[11] === "-") return rowObject[11];

        return retorno;
    }

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/projeto/r3g/editar',
                excluir: base_url + '/projeto/r3g/excluir'
            };
        params = '/idprojeto/' + r[16] + '/idr3g/' + r[17];

        $return = '<a target="_blank" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a target="_blank" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';

        return $return;

    }

    colNames = ['Data Detecção', 'Tipo', 'Planejado', 'Realizado', 'Prazo Projeto', 'Contramedida', 'Data', 'Responsável', 'Efetiva?', 'Status', 'Operações'];
    colModel = [
        {
            name: 'datdeteccao',
            index: 'datdeteccao',
            align: 'center',
            width: 25,
            search: false
        }, {
            name: 'domtipo',
            index: 'domtipo',
            align: 'center',
            width: 25,
            search: false
        }, {
            name: 'desplanejado',
            index: 'desplanejado',
            align: 'center',
            width: 70,
            search: false
        }, {
            name: 'desrealizado',
            index: 'desrealizado',
            align: 'center',
            width: 70,
            search: true
        }, {
            name: 'domcorprazoprojeto',
            index: 'domcorprazoprojeto',
            align: 'center',
            width: 30,
            search: true
        }, {
            name: 'descontramedida',
            index: 'descontramedida',
            align: 'center',
            width: 70,
            search: true
        }, {
            name: 'datcadastro',
            index: 'datcadastro',
            align: 'center',
            width: 25,
            search: true
        }, {
            name: 'desreponsavel',
            index: 'desreponsavel',
            align: 'center',
            width: 70,
            search: true
        }, {
            name: 'flacontramedidaefetiva',
            index: 'flacontramedidaefetiva',
            width: 15,
            search: false,
            sortable: false
        }, {
            name: 'domstatuscontramedida',
            index: 'domstatuscontramedida',
            width: 35,
            search: false,
            sortable: false
        }, {
            name: 'id',
            index: 'id',
            width: 50,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/projeto/r3g/pesquisarjson/idprojeto/" + $("#id").val(),
        datatype: "json",
        mtype: 'post',
        width: '1145',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'idprojeto',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function () {
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

    actions.pesquisar.form.on('submit', function (e) {

        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/projeto/r3g/pesquisarjson?" + $("form#form-r3g").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    resizeGrid();

    /*xxxxxxxxxx CADASTRAR xxxxxxxxxx*/
    var options = {
        url: actions.cadastrar.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
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

    actions.cadastrar.form.ajaxForm(options);

    $dialogCadastrar.dialog({
        autoOpen: false,
        title: 'R3g - Imprevistos e Contramedidas',
        width: 1145,
        height: 620,
        autoScroll: true,
        modal: true,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.cadastrar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $("form#form-r3g").trigger('submit');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.cadastrar", function (event) {
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
            success: function (data) {
                $dialogCadastrar.html(data).dialog('open');
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

    $("#accordion2").click(function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-minus");
        } else {
            $("#img").attr("class", "icon-plus");
        }
    });

});