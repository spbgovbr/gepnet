function selectRow(row) {
    $('.input-selecionado')
        .find('input:hidden').val(row.idpessoa).trigger('blur')
        .end()
        .find('input:text').val(row.nompessoa).trigger('blur');
}

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogCadastrar = $('#dialog-cadastrar');
    $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar');
    actions = {
        pesquisar: {
            form: $("form#form-pesquisar"),
            url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
        },
        cadastrar: {
            form: $("form#form-acordo"),
            url: base_url + '/acordocooperacao/instrumentocooperacao/add/format/json',
            dialog: $('#dialog-cadastrar')
        },
        detalhar: {
            url: base_url + '/projeto/relatorio/detalhar/format/json',
            dialog: $('#dialog-detalhar')
        },
        editar: {
            form: $("form#form-acordo-editar"),
            url: base_url + '/acordocooperacao/instrumentocooperacao/editar/format/json',
            dialog: $('#dialog-editar')
        },
        excluir: {
            form: $("form#form-status-report-excluir"),
            url: base_url + '/projeto/relatorio/excluir/format/json',
            dialog: $('#dialog-excluir')
        }

    };


    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Instrumento Cooperação - Detalhar',
        width: '1121px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

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
        title: 'Instrumento Cooperação - Cadastrar',
        width: 1104,
        height: 620,
        autoScroll: true,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.cadastrar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                $("form#form-acordo").trigger('submit');
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

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    var options = {
        url: actions.editar.url,
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

    actions.editar.form.ajaxForm(options);

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Instrumento Cooperação - Editar',
        width: 1104,
        height: 620,
        autoScroll: true,
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                $("form#form-acordo-editar").trigger('submit');
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.detalhar", function (event) {
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
                $dialogDetalhar.html(data).dialog('open');
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

    $(document.body).on('click', "a.editar", function (event) {
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
            success: function (data) {
                $dialog.html(data).dialog('open');
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


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/acordocooperacao/instrumentocooperacao/editar',
                detalhar: base_url + '/acordocooperacao/instrumentocooperacao/detalhar'
            };
        params = '/idacordo/' + r[10];


        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>'
            ;
    }

    colNames = ['Cod.', 'Setor', 'Num. SIAPRO', 'Nome', 'Responsável Interno', 'Fiscal', 'Início', 'Fim', 'Situação', 'PDF', 'Operações'];
    colModel = [
        {
            name: 'idacordo',
            index: 'idacordo',
            width: 5,
            search: true
        }, {
            name: 'nomsetor',
            index: 'nomsetor',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'numsiapro',
            index: 'numsiapro',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'nomacordo',
            index: 'nomacordo',
            width: 5,
            search: true
        }, {
            name: 'idresponsavelinterno',
            index: 'idresponsavelinterno',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'idfiscal',
            index: 'idfiscal',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'datiniciovigencia',
            index: 'datiniciovigencia',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'datfimvigencia',
            index: 'datfimvigencia',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'flasituacaoatual',
            index: 'flasituacaoatual',
            width: 10,
            align: 'center',
            search: true
        }, {
            name: 'pdf',
            index: 'pdf',
            width: 5,
            align: 'center',
            search: false
        }, {
            name: 'idacordo',
            index: 'idacordo',
            width: 10,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }];

    grid = jQuery("#list2").jqGrid({
        url: base_url + "/acordocooperacao/instrumentocooperacao/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1370',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'idacordo',
        viewrecords: true,
        sortorder: "desc",
        gridComplete: function () {
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

    var $form = $("form#form-entidadeexterna-pesquisar");
    $form.on('submit', function (e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/acordocooperacao/instrumentocooperacao/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        return false;
    });


    $(document.body).on('click', ".pessoa-button", function (event) {
//    $(".pessoa-button").on('click', function(event) {
        event.preventDefault();
        $(this).closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
        $(this).closest('.control-group').addClass('input-selecionado');
        if ($("table#list-grid-pessoa").length <= 0) {
            $.ajax({
                url: base_url + "/cadastro/pessoa/grid",
                type: "GET",
                dataType: "html",
                success: function (html) {
                    $(".grid-append").append(html).slideDown('fast');
                }
            });
            $('.pessoa-button')
                .off('click')
                .on('click', function () {
                    var $this = $(this);
                    $(".grid-append").slideDown('fast', function () {
                        $this.closest('.container-pessoa').find('.control-group').removeClass('input-selecionado');
                        $this.closest('.control-group').addClass('input-selecionado');
                    });
                });
        }
    });

    resizeGrid();

    var $i = 0;
    $.adicionar = function (dados) {
        var $row = "<tr class='success' data-row='" + dados.val() + "'>" +
            "<td><a class='btn actionfrm excluir excluirbutton' title='Excluir Interessado' data-id='" + dados.val() + "' >" +
            "<i class='icon-trash'></i>" +
            "</a><input type='hidden' id='entidade_" + dados.val() + "' name='entidade_" + dados.val() + "' value='" + dados.val() + "' /></td>" +
            "<td>" + dados.text() + "</td>" +
            "</tr>";
        $("#listagemInteressados")
            .removeClass('hide')
            .find("table tbody").prepend($row);
        $("#nenhumregistro").hide();
        $("#tabela").show();
    };

    $(document.body).on('click', "#btn-adicionar", function (event) {
        $row = getEntidade();
        if ($row.val() != "") {
            $add = checkEntidadeAdicionada($row.val());
            addEntidade($row, $add);
        } else {
            setNotify("Selecione uma Entidade Externa.", "error");
        }
    });

    $(document.body).on('click', ".excluirbutton", function (event) {
        var tr = $(this).closest('tr');
        var $id = $(this).closest('tr').attr("data-row");
        tr.css("background-color", "#FF3700");
        tr.fadeOut(400, function () {
            tr.remove();
        });
        $linha = $("#listagemInteressados")
            .removeClass('hide')
            .find("table tbody")
            .find("tr");
        console.log($linha.length);
        if ($linha.length <= 1) {
            $("#nenhumregistro").show();
            $("#tabela").hide();
        }
        setNotify("Entidade removida com sucesso.", "success");
        return false;
    });

    function addEntidade(row, add) {
        if (add) {
            $.adicionar(row);
            setNotify("Entidade adicionada com sucesso.", "success");
        }
    }

    function checkEntidadeAdicionada(data) {
        $add = true;
        $linha = $("#listagemInteressados")
            .removeClass('hide')
            .find("table tbody")
            .find('tr[data-row=' + data + ']');
        if ($linha.length > 0) {
            setNotify("A Entidade Externa já foi adicionada.", "error");
            $add = false;
        }
        return $add;
    }

    function getEntidade() {
        $row = new Array();
        $row = $("#entidadeexterna option:selected");
        return $row;
    }

    //TODO findentidade retornando a entidade ou false

    function setNotify(msg, type) {
        $.pnotify({
            text: msg,
            type: type,
            hide: true
        });
        if (type == "error") {
            return false;
        }
    }
});

