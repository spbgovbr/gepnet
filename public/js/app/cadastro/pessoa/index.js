$.validator.addMethod("cpf", function (value) {
    String.prototype.isCPF = function () {
        if (this.length <= 0) return true;
        else {
            var c = this;
            if ((c = c.replace(/[^\d]/g, "").split("")).length != 11)
                return false;
            if (new RegExp("^" + c[0] + "{11}$").test(c.join("")))
                return false;
            for (var s = 10, n = 0, i = 0; s >= 2; n += c[i++] * s--)
                ;
            if (c[9] != (((n %= 11) < 2) ? 0 : 11 - n))
                return false;
            for (var s = 11, n = 0, i = 0; s >= 2; n += c[i++] * s--)
                ;
            if (c[10] != (((n %= 11) < 2) ? 0 : 11 - n))
                return false;
            return true;
        }
    };

    var retorno = value.isCPF();
    return retorno;
}, 'Informe um CPF válido.');

$(function () {

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        $dialogExcluir = $('#dialog-excluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formEditar = $("form#form-pessoa");


    $('.mask-cpf').mask("999.999.999-99");

    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Pessoa - Detalhar',
        width: '810px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Documento - Editar',
        width: '830px',
        modal: false,
        open: function (event, ui) {
            $('#form-pessoa input[name=nompessoa], #form-pessoa input[name=numcpf], #form-pessoa input[name=nummatricula], #form-pessoa select[name=domcargo]');

            //.attr('readonly', false)
            //.focus(function() {
            //$(this).blur();
            // });

            $("form#form-pessoa").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
                    //console.log('enviar');
                    enviar_ajax("/cadastro/pessoa/edit/format/json", "form#form-pessoa", function () {
                        grid.trigger("reloadGrid");
                        $(this).dialog('close');
                    });
                }
            });
            $('.mask-cel').mask("(99) 9999-9999?9").focusout(function () {
                var phone, element;
                element = $(this);
                element.unmask();
                phone = element.val().replace(/\D/g, '');
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            }).trigger('focusout');
            $('.mask-tel').mask("(99) 9999-9999");
            $('.mask-cpf').mask("999.999.999-99");
            //$("form#form-pessoa input").trigger('focusout');
        },
        close: function (event, ui) {
            $dialogEditar.parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                //console.log('submit');
                //$formEditar.on('submit');
                $("form#form-pessoa").trigger('submit');
                if ($("form#form-pessoa").valid()) {
                    $dialogEditar.parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    $(this).dialog('close');
                }

            },
            'Fechar': function () {
                $dialogEditar.parent().find("button").each(function () {
                    $(this).attr('disabled', false);
                });
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
            //data: $formEditar.serialize(),
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

    $(document.body).on('click', "a.excluir, a.editar", function (event) {
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

    var $form = $("form#pessoa-pesquisar");

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/cadastro/pessoa/edit',
                excluir: base_url + '/cadastro/pessoa/form-excluir',
                detalhar: base_url + '/cadastro/pessoa/detalhar'
            };

        console.log(r);
        params = '/idpessoa/' + r[3];
        //console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>';
    }

    //idpessoa, nompessoa, numcpf, desunidade, nummatricula, desfuncao
    colNames = ['Nome', 'Matrícula', 'CPF', 'Operações'];
    colModel = [{
        name: 'nompessoa',
        index: 'nompessoa',
        width: 200,
        hidden: false,
        search: false
    }, {
        name: 'nummatricula',
        index: 'nummatricula',
        width: 60,
        search: true
    }, {
        name: 'numcpf',
        index: 'numcpf',
        width: 60,
        search: true
    }, {
        name: 'idpessoa',
        index: 'idpessoa',
        width: 50,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/cadastro/pessoa/pesquisarjson",
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
        sortname: 'nompessoa',
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
    $form.on('submit', function (e) {
        e.preventDefault();
    });

    /*$form.on('submit', function (e) {
     e.preventDefault();
     grid.setGridParam({
     url: base_url + "/cadastro/pessoa/pesquisarjson?" + $form.serialize(),
     page: 1
     }).trigger("reloadGrid");
     //$("a.actionfrm").tooltip();
     return false;
     });/**/
    //altura_ocupada = 100;
    /*
     $form.validate({
     errorClass: 'error',
     validClass: 'success',
     submitHandler: function (form) {
     form.submit();
     }
     });/**/

    $form.validate({
        rules: {
            cpf: {
                cpf: true,
                required: true
            }
        },
        messages: {
            cpf: {
                cpf: 'CPF inválido'
            }
        }
        , submitHandler: function (form) {
            grid.setGridParam({
                url: base_url + "/cadastro/pessoa/pesquisarjson?" + $form.serialize(), page: 1
            }).trigger("reloadGrid");
        }
    });

    resizeGrid();
});