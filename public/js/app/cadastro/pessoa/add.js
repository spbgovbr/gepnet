$.validator.addMethod("cpf", function(value) {
    String.prototype.isCPF = function() {
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
    };

    var retorno = value.isCPF();
    return retorno;
}, 'Informe um CPF válido.');

$(function() {
    $.pnotify.defaults.history = false;
    //$("#id_unidade").select2();
    $('.formulario')
            .find('input, textarea, button, select').attr('disabled', true);

    $(".importar, #resetbutton").click(function() {
        $('.formulario')
                .find('input, textarea, button, select').attr('disabled', true)
                .end()
                .addClass('hidden');

        //$('.container-importar').slideToggle();
        //$("#importar").select2('data', null);
        $("#alert-import").html('').hide();
        //$("#btn-importar").attr('disabled', true);
        $("select#domcargo option").attr('disabled', false);
    });

//    $('#nompessoa, #numcpf, #nummatricula, #domcargo')
//            .attr('readonly', false)
//            .focus(function() {
//        $(this).blur();
//    });

    $('.mask-cpf').mask("999.999.999-99");
    $('.mask-tel').mask("(99) 9999-9999");
    $('.mask-cel').mask("(99) 9999-9999?9").focusout(function() {
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

    var $form = $("form#form-pessoa");

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function(form) {
            enviar_ajax("/cadastro/pessoa/add/format/json", $form, function(data) {
                if (data.success) {
                    $("#resetbutton").trigger('click');
                }
            });
            //console.log('enviando');
        }
    });

    var
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        btnSubmit = $("button#btn-grid-pessoa"),
        btnCloseGridPessoa = $("#btn-close-grid-pessoa"),
        formPesquisa = $("form#importar-pessoa");

    //idpessoa, nompessoa, numcpf, desunidade, nummatricula, desfuncao
    colNames = ['Nome', 'Operações'];
    colModel = [{
            name: 'id',
            index: 'id',
            width: 50,
            search: false,
            hidden: true,
            sortable: false
        }, {
            name: 'nome',
            index: 'nome',
            width: 200,
            hidden: false,
            search: false
        }];

    grid = jQuery("#list-grid-pessoa").jqGrid({
        //caption: "Documentos",
        url: base_url + "/cadastro/pessoa/buscar?tipo=" + $("input[name='origem-importacao']").val(),
        datatype: "json",
        mtype: 'post',
        width: '350',
        height: '200px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager-grid-pessoa',
        sortname: 'nompessoa',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function() {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
        },
        ondblClickRow: function(rowid) {
            var row = grid.getRowData(rowid);
            //selectRow(row);
            $("input#id_servidor, input#idpessoa").val('');
            $.ajax({
                url: base_url + '/cadastro/pessoa/importarjson',
                dataType: 'json',
                type: 'POST',
                data: {
                    id: row.id,
                    tipo: $("input[name='origem-importacao']:checked").val()
                },
                //processData:false,
                success: function(data) {
                    if (data.success) {
                        var tipo = $("input[name='origem-importacao']:checked").val();
                        $('.formulario')
                                .find('input, textarea, button, select').attr('disabled', false)
                                .end()
                                .removeClass('hidden');
                        $.each(data.dados, function(i, val) {
                            if (val != null) {
                                $("#" + i).val(val);
                            }
                        });

                        $("#alert-import").html(data.msg).show();
                        $("select#domcargo option[value=COL]").remove();

                        if (tipo == '1') {
                            var linhas = $("select#domcargo").get(0);

                            $.each(data, function(i, item) {
                                linhas.options[linhas.length] = new Option('COL', 'COL');
                            });
                            $("select#domcargo option").attr('selected', false);
                            $("select#domcargo option[value=COL]").attr('selected', true);
                        }

                        $("select#domcargo :not(option:selected)").attr('disabled', true);
                        return;
                    }
                },
                error: function() {
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: false
                    });
                }
            });
        }
    });

    //grid.jqGrid('filterToolbar');
    grid.jqGrid('navGrid', '#pager-grid-pessoa', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');

    formPesquisa.on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        grid.setGridParam({
            url: base_url + "/cadastro/pessoa/buscar?nome=" + $("#gridpessoa").val() + "&tipo=" + $("input[name='origem-importacao']:checked").val(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });

    btnCloseGridPessoa.on('click', function() {
        $(".grid-append").slideUp('slow');
        $('.container-pessoa').find('.control-group').removeClass('input-selecionado');
    });

    $("input[name='origem-importacao']").on('click',function(){
        grid.setGridParam({
            url: base_url + "/cadastro/pessoa/buscar?tipo=" + $("input[name='origem-importacao']:checked").val(),
            page: 1
        }).trigger("reloadGrid");
    });

    innerLayout.sizePane("east", 367);
});