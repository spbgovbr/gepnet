$(function () {
    var cron = {};
    cron.projeto = {};
    var
        atividadedesatualizada = false,
        formInclusaoAtivo = false,
        grid = null,
        lastsel = null,
        gridEnd = null,
        colModel = null,
        colNames = null,
        actions = {
            pesquisar: {
                form: $("form#form-pesquisar"),
                url: base_url + "/projeto/statusreport/pesquisarjson?" + $("form#form-pesquisar").serialize()
            },
            incluir: {
                form: $("form#form-status-report-incluir"),
                url: base_url + '/projeto/relatorio/add/format/json',
                dialog: $('#dialog-incluir')
            },
            detalhar: {
                url: base_url + '/projeto/relatorio/detalhar/format/json',
                dialog: $('#dialog-detalhar')
            },
            editar: {
                form: $("form#form-status-report-editar"),
                url: base_url + '/projeto/relatorio/editar/format/json',
                dialog: $('#dialog-editar')
            },
            excluir: {
                form: $("form#form-status-report-excluir"),
                url: base_url + '/projeto/relatorio/excluir/format/json',
                dialog: $('#dialog-excluir')
            }

        };


    //Reset button
    $("#resetbutton").click(function () {
        $("#idprojeto").val('');
        $("#idescritorio").val('');
        $("#idprograma").val('');
        $("#domstatusprojeto").val('');
        $("#codobjetivo").val('');
        $("#codacao").val('');
        $("#codnatureza").val('');
        $("#codsetor").val('');
    });

    $("form#form-status-report-editar").validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("form#form-status-report-incluir").validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            form.submit();
        }
    });

    /*xxxxxxxxxx INCLUIR xxxxxxxxxx*/
    var options = {
        url: actions.incluir.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            $('#dialog-incluir').dialog('close');
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                if ($("#idStatusSelecionado").val().length > 0) {
                    $("#idUltimoStatus").val(data.msg.acompanhamento.idstatusreport);
                    atualizarCabecalhoProjeto();
                    atualizaOpcoesAcompanhamento(data.msg.acompanhamento.idprojeto);
                } else {
                    var url = base_url + "/projeto/statusreport/visualizarimpressao/idprojeto/" + data.msg.acompanhamento.idprojeto + "/idstatusreport/" + data.msg.acompanhamento.idstatusreport;
                    $("#idst").val(data.msg.acompanhamento.idstatusreport);
                    $("#idUltimoStatus").val(data.msg.acompanhamento.idstatusreport);
                    $("#domcorrisco").val(data.msg.acompanhamento.domcorrisco);
                    $("#cf").val(data.msg.acompanhamento.numcriteriofarol);
                    $("#dtacompanhamento").text(data.msg.acompanhamento.datacompanhamento);
                    $("#visualizarimpressao").prop("href", url);
                    atualizarCabecalhoProjeto();
                    gerachartacompanhamento();
                    gerachartatraso();
                    gerachartprazo();
                    charRisco(data.msg.acompanhamento.domcorrisco);
                    gerachartPercentualConcluidoMarco();
                    populaAcompanhamento(data.msg.acompanhamento);
                    atualizaOpcoesAcompanhamento(data.msg.acompanhamento.idprojeto);
                }
                grid.trigger("reloadGrid");
            }
        },
        error: function () {
            $('#dialog-incluir').dialog('close');
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    };

    actions.incluir.form.ajaxForm(options);

    actions.incluir.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Incluir',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function (event, ui) {
            $('form#form-status-report-incluir').parent().find('input[id="QtAtividadesDesatualizadas"]').each(function () {
                atividadedesatualizada = true;
            });
            $("#numprocessosei").mask("99999.999999/9999-99", {reverse: true});
            $('#dialog-incluir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
                if (atividadedesatualizada) {
                    if (($(this).text() == "Salvar")) {
                        $(this).text("Atualizar cronograma");
                    }
                    if (($(this).text() == "Fechar")) {
                        $(this).attr('disabled', true);
                        $(this).attr("style", "display:none");
                    }
                }
            });
        },
        close: function (event, ui) {
            actions.incluir.dialog.empty();
            //gerachartacompanhamento();
            //gerachartatraso();
            $('#dialog-incluir').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
            formInclusaoAtivo = false;
        },
        buttons: {
            'Salvar': function (e) {
                e.preventDefault();
                if (atividadedesatualizada == true) {
                    // Verifica se o formulário já foi carregado
                    formInclusaoAtivo = false;
                    $('form#form-status-report-incluir').parent().find('select[id="domstatusprojeto"]').each(function () {
                        formInclusaoAtivo = true;
                    });
                    if (formInclusaoAtivo) {
                        if(parseInt($('#domstatusprojeto option:selected').val()) > 2){
                            $("label.error").remove();
                            $('#idmarco').remove();
                        }
                        if ($('form#form-status-report-incluir').valid()) {
                            $('#dialog-incluir').parent().find("button").each(function () {
                                $(this).attr('disabled', true);
                            });
                            e.preventDefault();
                            $('form#form-status-report-incluir').submit();
                        }
                    } else {
                        ignoraatividadedesatualizada($('#dialog-incluir'));
                    }
                } else {
                    if(parseInt($('#domstatusprojeto option:selected').val()) > 2){
                        $("label.error").remove();
                        $('#idmarco').remove();
                    }
                    if ($('form#form-status-report-incluir').valid()) {
                        $('#dialog-incluir').parent().find("button").each(function () {
                            $(this).attr('disabled', true);
                        });
                        $('form#form-status-report-incluir').submit();
                    }
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

    ignoraatividadedesatualizada = function ($dialog) {
        var $this = $(this);
        $.ajax({
            url: base_url + "/projeto/cronograma/index/idprojeto/" + $("#ip").val(),
            dataType: 'html',
            type: 'GET',
            async: true,
            cache: true,
            processData: false,
            success: function (data) {
                window.location.href = base_url + "/projeto/cronograma/index/idprojeto/" + $("#ip").val();
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: false
                });
            }
        });
    };

    function atualizaOpcoesAcompanhamento(projeto) {

        $.ajax({
            url: base_url + "/projeto/relatorio/atualizaacompanhamento",
            dataType: 'json',
            type: 'POST',
            data: {
                'idprojeto': projeto,
            },
            success: function (data) {
                var objSelect = $("#idstatusreport"),
                    cont = 1;
                $("#idstatusreport option").remove();
                $("#idstatusreport").append('<option value="">Selecione</option>');
                $.each(data, function (key, value) {
                    var texto = cont + " - " + value;
                    var o = new Option(texto, key);
                    $(o).html(texto);
                    $('#idstatusreport').append(o);
                    cont++;
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
    }

    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    var options = {
        url: actions.editar.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            $('#dialog-editar').dialog('close');
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                //$("#resetbutton").trigger('click');
                $("#idprojeto").val(data.msg.idprojeto);

                if ($("#idStatusSelecionado").val() != $("#idUltimoStatus").val()) {
                    if (data.msg.idstatus == $("#idUltimoStatus").val()) {
                        atualizarCabecalhoProjeto();
                    } else if (data.msg.idstatus == $("#idStatusSelecionado").val()) {
                        var risco = parseInt(data.msg.acompanhamento.domcorrisco);
                        charRisco(risco);
                        populaAcompanhamento(data.msg.acompanhamento);
                    }
                } else if ($("#idStatusSelecionado").val() == $("#idUltimoStatus").val()) {
                    if (data.msg.idstatus == $("#idStatusSelecionado").val()) {
                        var risco = parseInt(data.msg.acompanhamento.domcorrisco);
                        charRisco(risco);
                        atualizarCabecalhoProjeto();
                        gerachartprazo();
                        populaAcompanhamento(data.msg.acompanhamento);
                    }
                }
                if($("#idStatusSelecionado").val().length == 0 && data.msg.idstatus ==$("#idUltimoStatus").val()){
                    charRisco(data.msg.acompanhamento.domcorrisco);
                    populaAcompanhamento(data.msg.acompanhamento);
                }
                gerachartPercentualConcluidoMarco();
                atualizaOpcoesAcompanhamento(data.msg.idprojeto);
                grid.trigger("reloadGrid");
            }
        },
        error: function () {
            $('#dialog-editar').dialog('close');
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    };

    actions.editar.form.ajaxForm(options);

    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Editar',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function (event, ui) {
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        close: function (event, ui) {
            actions.editar.dialog.empty();
            $('#dialog-editar').parent().find("button").each(function () {
                $(this).attr('disabled', false);
            });
        },
        buttons: {
            'Salvar': function (e) {
                if ($('form#form-status-report-editar').valid()) {
                    $('#dialog-editar').parent().find("button").each(function () {
                        $(this).attr('disabled', true);
                    });
                    e.preventDefault();
                    $('form#form-status-report-editar').submit();
                }
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);


    atualizarCabecalhoProjeto = function () {
        var
            locations, arr, idprojeto, ini;
        locations = window.location.href;
        arr = locations.indexOf("idprojeto");
        ini = arr + 10;
        //console.log(locations.substring(ini));
        // Recupera o ID do Projeto do campo hidden
        if ($("#idprojeto").val().length == 0) {
            idprojeto = locations.substring(ini);
        } else {
            idprojeto = $("#idprojeto").val();
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + '/projeto/relatorio/atualizarcabecalhojson/format/json',
            data: {idprojeto: idprojeto},
            success: function (data) {
                if (data != null) {
                    cron.projeto = data;
                    atualizarCabecalhoRisco();
                    atualizarCabecalhoAtraso();
                    atualizarDataStatusReports();
                }
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao atualizar projeto',
                    type: 'error',
                    hide: false
                });
            }
        });
    };

    atualizarCabecalhoRisco = function () {
        var spanRisco = document.getElementById("spanRisco"),
            classeAtualRisco = spanRisco.getAttribute("class"),
            novaClasseRisco = 'badge badge-' + cron.projeto.descricaoRisco;
        spanRisco.className = novaClasseRisco;
        spanRisco.innerHTML = cron.projeto.ultimoStatusReport.nomdomcorrisco;
    };

    atualizarCabecalhoAtraso = function () {
        var spanAtraso = document.getElementById("spanAtraso"),
            classeAtualAtraso = spanAtraso.getAttribute("class"),
            novaClasseAtraso = 'badge badge-' + cron.projeto.descricaoPrazoCabecalho;
        spanAtraso.className = novaClasseAtraso;
        spanAtraso.innerHTML = cron.projeto.prazoEmDias + " dias";
    };

    atualizarDataStatusReports = function () {
        var spanUltimoRelatorio = document.getElementById("spanUltimoRelatorio");
        spanUltimoRelatorio.innerHTML = cron.projeto.ultimoStatusReport.datacompanhamento;
        var spanTendencia = document.getElementById("spanTendencia"),
            htmlTexto = cron.projeto.datinicio + " a " + cron.projeto.ultimoStatusReport.datfimprojetotendencia + " - " + cron.projeto.tendenciaEmDias + " dias";
        spanTendencia.innerHTML = htmlTexto;
        var spanPrevisto = document.getElementById("spanPrevisto");
        spanPrevisto.innerHTML = cron.projeto.ultimoStatusReport.numpercentualprevisto + "%";
        var spanConcluido = document.getElementById("spanConcluido");
        spanConcluido.innerHTML = cron.projeto.ultimoStatusReport.numpercentualconcluido + "%";
        var spanStatus = document.getElementById("spanStatus");
        spanStatus.innerHTML = cron.projeto.ultimoStatusReport.nomdomstatusprojeto;
    };


    $(document.body).on('click', "a.incluir, a.editar, a.detalhar", function (event) {
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
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR'
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
    var options = {
        url: actions.excluir.url,
        dataType: 'json',
        type: 'POST',
        delegation: true,
        success: function (data) {
            $('#dialog-excluir').dialog('close');
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (data.success) {
                if ($("#idStatusSelecionado").val().length > 0) {
                    if ($("#idStatusSelecionado").val() != $("#idUltimoStatus").val()) {
                        if (data.msg.idstatus == $("#idUltimoStatus").val()) {
                            atualizarCabecalhoProjeto();
                        }
                    } else {
                        if ($("#idStatusSelecionado").val() == $("#idUltimoStatus").val()) {
                            var url = base_url + "/projeto/statusreport/visualizarimpressao/idprojeto/" + data.msg.acompanhamento.idprojeto + "/idstatusreport/" + data.msg.acompanhamento.idstatusreport;
                            $("#idst").val(data.msg.acompanhamento.idstatusreport);
                            $("#idUltimoStatus").val(data.msg.acompanhamento.idstatusreport);
                            $("#domcorrisco").val(data.msg.acompanhamento.domcorrisco);
                            $("#cf").val(data.msg.acompanhamento.numcriteriofarol);
                            $("#idUltimoStatus").val(data.msg.acompanhamento.idstatusreport);
                            $("#dtacompanhamento").text(data.msg.acompanhamento.datacompanhamento);
                            $("#visualizarimpressao").prop("href", url);
                            $("#idStatusSelecionado").val(data.msg.acompanhamento.idstatusreport);
                            atualizarCabecalhoProjeto();
                            gerachartacompanhamento();
                            gerachartatraso();
                            gerachartprazo();
                            charRisco(data.msg.acompanhamento.domcorrisco);
                            gerachartPercentualConcluidoMarco();
                            populaAcompanhamento(data.msg.acompanhamento);
                        }
                    }
                } else {
                    window.location.href = window.location.href;
                }
                atualizaOpcoesAcompanhamento(data.msg.acompanhamento.idprojeto);
                grid.trigger("reloadGrid");
            }
        },
        error: function () {
            $('#dialog-excluir').dialog('close');
            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    };

    actions.excluir.form.ajaxForm(options);

    actions.excluir.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Excluir',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.excluir.dialog.empty();
        },
        buttons: {
            'Excluir': function () {

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "/projeto/relatorio/relatoriojson/idprojeto/" + $("#ip").val(),

                    success: function (data) {
                        if (data.records > 1) {
                            $('form#form-status-report-excluir').submit();
                            actions.excluir.dialog.html(data).dialog('close');
                        } else {
                            actions.excluir.dialog.html(data).dialog('close');
                            alert('Acompanhamento só poderá ser deletado se houver mais de um');
                            return false;
                        }
                    }
                });
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);

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

    //##### DETALHAR #######

    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Relatório - Detalhar',
        width: '1123px',
        heigth: '768px',
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            actions.detalhar.dialog.empty();
        },
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    }).css("maxHeight", window.innerHeight - 150);


    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                detalhar: base_url + '/projeto/relatorio/detalhar',
                editar: base_url + '/projeto/relatorio/editar',
                imprimir: base_url + '/projeto/statusreport/visualizarimpressao',
                excluir: base_url + '/projeto/relatorio/excluir'
            };
        params = '/idprojeto/' + $("#ip").val() + '/idstatusreport/' + r[8];

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-detalhar" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="_blank" target="_blank" class="btn actionfrm imprimir" title="Imprimir" data-id="' + cellvalue + '" href="' + url.imprimir + params + '"><i class="icon-print"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>'
    }

    colNames = ['Data Acompanhamento', 'Previsto', 'Concluído', 'Tendência Encerramento', 'Cronograma PDF', 'Usuário', 'Atraso', 'Risco', 'Operações', 'numIdIstatusReport'];
    colModel = [
        {
            name: 'datacompanhamento',
            index: 'datacompanhamento',
            align: 'center',
            width: 21,
            hidden: false,
            search: false
        }, {
            name: 'numpercentualprevisto',
            index: 'numpercentualprevisto',
            align: 'center',
            width: 9,
            hidden: false,
            search: false
        }, {
            name: 'numpercentualconcluido',
            index: 'numpercentualconcluido',
            align: 'center',
            width: 11,
            hidden: false,
            search: false
        }, {
            name: 'datfimprojetotendencia',
            index: 'datfimprojetotendencia',
            align: 'center',
            width: 21,
            search: true,
        }, {
            name: 'cronogramapdf',
            index: 'cronogramapdf',
            align: 'center',
            width: 15,
            search: false,
            sortable: false,
        }, {
            name: 'idcadastrador',
            index: 'idcadastrador',
            align: 'center',
            width: 55,
            search: true
        }, {
            name: 'prazo',
            index: 'prazo',
            width: 15,
            align: 'center',
            search: false,
            sortable: false,
        }, {
            name: 'Risco',
            index: 'risco',
            width: 15,
            align: 'center',
            sortable: false,
            search: false,
        }, {
            name: 'idstatusreport',
            index: 'idstatusreport',
            width: 25,
            search: false,
            sortable: false,
            formatter: formatadorLink
        }, {
            name: 'numIdIstatusReport',
            index: 'numIdIstatusReport',
            width: 25,
            search: false,
            hidden: true,
            sortable: false,
        }];

    grid = jQuery("#list2").jqGrid({
        caption: "Listagem de Acompanhamentos",
        url: base_url + "/projeto/relatorio/relatoriojson/idprojeto/" + $("#ip").val(),
        datatype: "json",
        mtype: 'post',
        width: '1210',
        height: '150px',
        colNames: colNames,
        colModel: colModel,
        rownumbers: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'idstatusreport',
        viewrecords: true,
        sortorder: "asc",
        loadComplete: function () {
            var rowIDs = jQuery("#list2").getDataIDs();   //busca ids das linhas do grid
            preencheRegistroSelecionado(rowIDs, $(this));
        },
        beforeSelectRow: function (rowid, event) {
            if (event) {
                var registroSelecionado = jQuery("#list2").getRowData(rowid);
                $(this).jqGrid('setRowData', registroSelecionado, false, {background: '#ccc', color: '#333'});
            }
        }
    });

    preencheRegistroSelecionado = function (arrayIDs, obj) {
        var idstatusreport = $("#idst").val();

        for (var i = 0; i < arrayIDs.length; i++) {     //entra nas linhas
            var rowData = $("#list2").jqGrid('getRowData', arrayIDs[i]);
            if (rowData.numIdIstatusReport == idstatusreport) {//valida o status report e modifica a cor da linha               
                obj.jqGrid('setRowData', arrayIDs[i], false, {background: '#ffef8f', color: '#333'});
            }
        }
    }
    
    grid.jqGrid('navGrid', '#pager2', {
        search: false,
        edit: false,
        add: false,
        del: false,
        view: false
    });

    grid.jqGrid('setLabel', 'rn', 'Ord');

    $("#pesquisa").click(function () {
        var locations = window.location.href,
            arr = locations.indexOf("idprojeto"),
            ini = arr + 10;

        if ($("#idprojeto").val().length == 0) {
            var verifica = locations.substring(ini).split("#");
            if (verifica.length > 0) {
                idprojeto = verifica[0];
            } else {
                idprojeto = locations.substring(ini);
            }
        } else {
            idprojeto = $("#idprojeto").val();
        }
        $("#idStatusSelecionado").val($("select#idstatusreport").val());
        window.location.href = base_url + "/projeto/relatorio/index/idprojeto/" + $("#idprojeto").val() + "/idstatusreport/" + $("select#idstatusreport").val();
    });

    $(document).on("click", ".accordion-heading", function () {
        if ($('.accordion-toggle').hasClass("collapsed")) {
            $("#img").attr("class", "icon-plus");
        } else {
            $("#img").attr("class", "icon-minus");
        }
    });
    resizeGrid();
});