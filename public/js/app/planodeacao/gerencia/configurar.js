/**
 * Comment
 */

$(function () {

    var
        idrecurso = null,
        txrecurso = null,
        idpermissao = null,
        no_permissao = null,
        ctpermissao = null,
        idparte = null,
        idplanodeacao = null,
        lastsel = null,
        colModel = null,
        colNames = null,
        janTabs = false,
        janTabsD = false,
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar'),
        $formPermissao = $("form#form-permissao"),
        $formEditar = $("form#form-permissao-editar");

    colNames = ['Descrição', 'Situação'];
    colModel = [{
        name: 'despermissao',
        index: 'despermissao',
        width: 75,
        align: 'left',
        hidden: false,
        search: false
    }, {
        name: 'stpermissao',
        index: 'stpermissao',
        width: 10,
        align: 'center',
        hidden: false,
        search: false
    }];
    $('ul').find('a').on("click", function (e) {
        e.preventDefault();
    });
    $("#accordion").accordion();

    $.pnotify.defaults.history = false;

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $('.mask-tel').mask("(99) 9999-9999");

    actions = {
        detalhar: {
            form: $("form#form-permissao"),
            url: base_url + '/planodeacao/gerencia/listapermissao',
            dialog: $('#dialog-detalhar')
        },
        editar: {
            form: $("form#form-permissao"),
            url: base_url + '/planodeacao/gerencia/editapermissao',
            dialog: $('#dialog-editar')
        }
    };
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
            }
        }
    };
    actions.editar.form.ajaxForm(options);
    /*xxxxxxxxxx EDITAR xxxxxxxxxx*/
    actions.editar.dialog.dialog({
        autoOpen: false,
        title: 'Parte Interessada - Gerenciar Permissões',
        width: 1000,
        height: 580,
        modal: true,
        open: function (event, ui) {
            $(this).parent().focus();
            $("#tabs").tabs({});
            if (!janTabs) {
                var selector = '#tabs > ul > li';
                var activeTabID = $(selector).eq(0).attr('id');
                var activeTabName = $(selector).eq(0).attr('name');
                renderPermissaoEditar(activeTabName, activeTabID);
                janTabs = true;
            }
            $('#tabs').bind('tabsselect', function (event, ui) {
                var selector = '#tabs > ul > li';
                var activeTabID = $(selector).eq(ui.index).attr('id');
                var activeTabName = $(selector).eq(ui.index).attr('name');
                renderPermissaoEditar(activeTabName, activeTabID);
            });
        },
        close: function (event, ui) {
            janTabs = false;
            actions.editar.dialog.empty();
        },
        buttons: {
            'Fechar': function () {
                janTabs = false;
                $(this).dialog('close');
            }
        }
    });
    chkchange = function (chkitem, icon, idrecurso, idpermissao) {
        idparteinteressada = $("#dialog-editar").find('input[name="idparteinteressada"]').val();
        idplanodeacao = $("#dialog-editar").find('input[name="idplanodeacao"]').val();
        idpessoa = $("#dialog-editar").find('input[name="idpessoa"]').val();
        urlper = base_url + "/planodeacao/gerencia/atualizapermissao";
        var verChkVer1 = chkitem.prop("checked");
        var verChkVer2 = chkitem.is(":checked");
        stpermissao = 'N';
        if ((verChkVer1) || (verChkVer2)) {
            stpermissao = 'S';
        }
        $.ajax({
            url: urlper,
            dataType: 'json',
            type: 'POST',
            data: {
                idplanodeacao: idplanodeacao,
                idparteinteressada: idparteinteressada,
                idpessoa: idpessoa,
                idrecurso: idrecurso,
                idpermissao: idpermissao,
                stpermissao: stpermissao,
            },
            success: function (data) {
                if ((verChkVer1) || (verChkVer2)) {
                    $("#" + icon).attr("class", "icon-ok");
                } else {
                    $("#" + icon).attr("class", "icon-remove");
                }
                $.pnotify(data.msg);
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
    renderPermissaoEditar = function (name, idrecurso) {
        idpessoa = $("#dialog-editar").find('input[name="idpessoa"]').val();
        idparteinteressada = $("#dialog-editar").find('input[name="idparteinteressada"]').val();
        idplanodeacao = $("#dialog-editar").find('input[name="idplanodeacao"]').val();
        urlper = base_url + "/planodeacao/gerencia/editapermissao"
        $.ajax({
            url: urlper,
            dataType: 'html',
            type: 'POST',
            cache: true,
            data: {
                idplanodeacao: idplanodeacao,
                idparteinteressada: idparteinteressada,
                idrecurso: idrecurso,
                idpessoa: idpessoa,
            },
            success: function (data) {
                $("#dialog-editar div#tabs-" + name).empty();
                $(data).appendTo($("#dialog-editar div#tabs-" + name));
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
    $(document.body).on('click', "a.detalhar", function (event) {
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
    actions.detalhar.dialog.dialog({
        autoOpen: false,
        title: 'Parte Interessada - Detalhar Permissões',
        width: 1000,
        height: 580,
        modal: true,
        open: function (event, ui) {
            $(this).parent().focus();
            $("#tabs").tabs({});
            if (!janTabsD) {
                var selector = '#tabs > ul > li';
                var activeTabID = $(selector).eq(0).attr('id');
                var activeTabName = $(selector).eq(0).attr('name');
                renderPermissaoDetalhar(activeTabName, activeTabID);
                janTabsD = true;
            }
            $('#tabs').bind('tabsselect', function (event, ui) {
                var selector = '#tabs > ul > li';
                var activeTabID = $(selector).eq(ui.index).attr('id');
                var activeTabName = $(selector).eq(ui.index).attr('name');
                renderPermissaoDetalhar(activeTabName, activeTabID);
            });
        },
        close: function (event, ui) {
            janTabsD = false;
            actions.detalhar.dialog.empty();
        },
        buttons: {
            'Fechar': function () {
                janTabsD = false;
                $(this).dialog('close');
            }
        }
    });
    renderPermissaoDetalhar = function (name, idrecurso) {
        idparteinteressada = $("#dialog-detalhar").find('input[name="idparteinteressada"]').val()
        idplanodeacao = $("#dialog-detalhar").find('input[name="idplanodeacao"]').val()
        urlper = base_url + "/planodeacao/gerencia/listapermissao"
        $.ajax({
            url: urlper,
            dataType: 'html',
            type: 'POST',
            cache: true,
            data: {idplanodeacao: idplanodeacao, idparteinteressada: idparteinteressada, idrecurso: idrecurso,},
            success: function (data) {
                $("#dialog-detalhar div#tabs-" + name).empty();
                $(data).appendTo($("#dialog-detalhar div#tabs-" + name));
                console.log('ok');
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
});
$(document).ready(function () {
    $("ul").find('li').removeClass('disabled').addClass('enabled');
    $('ul').find('a').unbind('click');
});
