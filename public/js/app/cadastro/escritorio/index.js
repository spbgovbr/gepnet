function selectRow(row) {
    //console.log(row);
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
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/cadastro/escritorio/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            detalhar: {
                dialog: $('#dialog-detalhar')
            },
            editar: {
                form: $("form#form-escritorio"),
                url: base_url + '/cadastro/escritorio/editar/format/json',
                dialog: $('#dialog-editar')
            },
            arquivo: {
                form: $("form#form-escritorio-arquivo"),
                url: base_url + '/cadastro/escritorio/editar-arquivo/format/json',
                dialog: $('#dialog-arquivo')
            },
            excluir: {
                form: $("form#form-escritorio-excluir"),
                url: base_url + '/cadastro/escritorio/excluir/format/json',
                dialog: $('#dialog-excluir')
            }
        };

    $(".select2").select2();

    $("#resetbutton").click(function () {
        //$('.container-importar').slideToggle();
        $(".select2").select2('data', null);
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

    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Escritorio - Editar',
        width: '800px',
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                console.log('submit');
                $('form#form-escritorio').submit();
                //$('form#form-documento').submit();
                //console.log(actions.editar.form);
                //$formEditar.on('submit');
                //$(actions.editar.form).trigger('submit');
                //enviar_ajax(actions.editar.url, actions.editar.form );
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
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
                actions.editar.dialog.html(data).dialog('open');
                $("#idtipodocumento").select2();
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
                });

                $('form#form-documento').validate();

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

    /*xxxxxxxxxx DETALHAR xxxxxxxxxx*/

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Escritorio - Detalhar',
        width: '810px',
        modal: false,
        buttons: {
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
            cache: false,
            //data: $formEditar.serialize(),
            processData: true,
            success: function (data) {
                //console.log(data);
                actions.detalhar.dialog.html(data).dialog('open');
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

    /*xxxxxxxxxx ARQUIVO xxxxxxxxxx*/
    var optionsArquivo = {
        url: actions.arquivo.url,
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
    actions.arquivo.form.ajaxForm(optionsArquivo);

    //url_editar = base_url + '/cadastro/documento/edit';
    actions.arquivo.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Editar arquivo',
        width: '800px',
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.arquivo.dialog.empty();
        },
        buttons: {
            'Salvar': function () {
                console.log('submit');
                $('form#form-documento-arquivo').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.arquivo, a.btn_editar", function (event) {
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
                actions.arquivo.dialog.html(data).dialog('open');
                $('form#form-documento-arquivo').validate();

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
    var optionsExcluir = {
        url: actions.excluir.url,
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

    actions.excluir.form.ajaxForm(optionsExcluir);

    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Documento - Excluir',
        width: '810px',
        modal: false,
        buttons: {
            'Excluir': function () {
                $('form#form-documento-excluir').submit();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $(document.body).on('click', "a.excluir", function (event) {
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


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/cadastro/escritorio/editar',
//            excluir: base_url + '/cadastro/escritorio/excluir',
                detalhar: base_url + '/cadastro/escritorio/detalhar'
                //arquivo: base_url + '/cadastro/escritorio/editar-arquivo'
            };
        params = '/idescritorio/' + r[7];
        //console.log(rowObject);

        return '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>';
        // '<a data-target="#dialog-arquivo" class="btn actionfrm arquivo" title="Editar arquivo" data-id="' + cellvalue + '" href="' + url.arquivo + params + '"><i class="icon-upload"></i></a>' +
//                '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
    }

    function formatadorImg(cellvalue, options, rowObject) {
        var path = base_url + '/img/escritorio/icone.jpg';
        return '<img src="' + path + '" />';
    }


    function formatadorSituacao(cellvalue, options, rowObject) {
        if (rowObject[5] == 'S') {
            return '<span class="label label-success">Ativo</span>';
        }
        return '<span class="label label-important">Inativo</span>';

    }


    //'Sigla', 'Nome', 'Responsavel-1', 'Responsavel-2', 'Mapa', 'Situação', 'Logo', 'Operações'
    colNames = ['Sigla', 'Nome', 'Responsavel-1', 'Responsavel-2', 'Mapa', 'Situação',/* 'Logo',*/ 'Operações'];
    colModel = [{
        name: 'sigla',
        index: 'sigla',
        align: 'center',
        width: 50,
        hidden: false,
        search: false
    }, {
        name: 'nome',
        index: 'nome',
        align: 'center',
        width: 50,
        hidden: false,
        search: false
    }, {
        name: 'responsavel1',
        index: 'responsavel1',
        align: 'center',
        width: 200,
        search: true
    }, {
        name: 'responsavel2',
        index: 'responsavel2',
        align: 'center',
        width: 200,
        search: true
    }, {
        name: 'mapa',
        index: 'mapa',
        align: 'center',
        width: 60,
        search: true
    }, {
        name: 'situacao',
        index: 'situacao',
        align: 'center',
        width: 50,
        search: true,
        formatter: formatadorSituacao
    },/*{
            name: 'logo',
            index: 'logo',
            width: 30,
            search: false,
            sortable: false,
            formatter: formatadorImg
        },*/{
        name: 'id',
        index: 'id',
        width: 50,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/cadastro/escritorio/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1170',
        height: '300px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nome',
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
            url: base_url + "/cadastro/escritorio/pesquisarjson?" + $("form#form-pesquisar").serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();

    });

    $(document.body).on('click', ".pessoa-button", function (event) {
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

    $("body").delegate("#numfone", "focusin", function () {
        var $this = $(this);
        $this.mask('(99) 9999-9999?9');
    });

    resizeGrid();
});