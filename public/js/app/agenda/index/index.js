$(document).ready(function () {

    var
        grid = null,
        colModel = null,
        colNames = null,
        $dialogIncluir = $('#dialog-incluir'),
        $dialogEditar = $('#dialog-editar'),
        $dialogDetalhar = $('#dialog-detalhar');
    $dialogParticipantes = $('#dialog-participantes');
    $dialogExcluir = $('#dialog-excluir');

    innerLayout.sizePane("west", 395);

    $dialogIncluir.dialog({
        autoOpen: false,
        title: 'Agenda - Incluir',
        width: '1185px',
        modal: true,
        open: function (event, ui) {
//            $('#')
//                    .attr('readonly', true)
//                    .focus(function() {
//                $(this).blur();
//            });
            $('#datagenda').val($('#display-data').attr('data-value-field'));
            $('.mask-hora').mask('99:99:99');
            $("form#form-agenda").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
//                    console.log('enviar');
                    enviar_ajax("/agenda/index/add/format/json", "form#form-agenda", function () {
//                        grid.jqGrid("clearGridData", true);
                        grid.trigger("reloadGrid");
                    });
                }
            });
            //$("form#form-pessoa input").trigger('focusout');
        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                //console.log('submit');
                //$formEditar.on('submit');
                $("form#form-agenda").trigger('submit');
                marcaDiasComEvento();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogDetalhar.dialog({
        autoOpen: false,
        title: 'Agenda - Detalhar',
        width: '810px',
        modal: true,
        buttons: {
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogExcluir.dialog({
        autoOpen: false,
        title: 'Agenda - Excluir',
        width: '810px',
        modal: true,
        open: function (event, ui) {
//            $('#')
//                    .attr('readonly', true)
//                    .focus(function() {
//                $(this).blur();
//            });

            //$("form#form-pessoa input").trigger('focusout');
        },
        buttons: {
            'Excluir': function () {
                $.ajax({
                    url: base_url + "/agenda/index/excluir/format/json/",
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        idagenda: $('#xidagenda').val()
                    },
                    success: function (data) {
//                console.log('success!');
                        if (data.success) {
                            console.log(data);
                            grid.jqGrid("clearGridData", true);
                            grid.trigger("reloadGrid");
                            marcaDiasComEvento();
                            $('#dialog-excluir').dialog('close');
                        }
                        $.pnotify(data.msg);
                    },
                    error: function (data) {
                        $.pnotify({
                            text: 'Falha ao enviar a requisição',
                            type: 'error',
                            hide: false
                        });
                    }
                });
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogEditar.dialog({
        autoOpen: false,
        title: 'Agenda - Editar',
        width: '1185px',
        modal: false,
        open: function (event, ui) {
//            $('#')
//                    .attr('readonly', true)
//                    .focus(function() {
//                $(this).blur();
//            });
            $("form#form-agenda").validate({
                errorClass: 'error',
                validClass: 'success',
                submitHandler: function (form) {
//                    console.log('enviar');
                    enviar_ajax("/agenda/index/edit/format/json", "form#form-agenda", function () {
                        grid.jqGrid("clearGridData", true);
                        grid.trigger("reloadGrid");
                    });
                }
            });
            //$("form#form-pessoa input").trigger('focusout');
        },
        close: function (event, ui) {
            $dialogEditar.empty();
        },
        buttons: {
            'Salvar': function () {
                //console.log('submit');
                //$formEditar.on('submit');
                $("form#form-agenda").trigger('submit');
                marcaDiasComEvento();
            },
            'Fechar': function () {
                $(this).dialog('close');
            }
        }
    });

    $dialogParticipantes.dialog({
        autoOpen: false,
        title: 'Agenda - Participantes',
        width: '1185px',
        modal: false,
        open: function (event, ui) {

        },
        close: function (event, ui) {
            grid.trigger("reloadGrid");
            $dialogParticipantes.empty();
        },
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

    /*$(document.body).on('click', "a.excluir", function(event) {
        event.preventDefault();
        var
            $this = $(this);

        $.ajax({
            url: base_url + "/agenda/index/excluir/format/json/",
            dataType: 'json',
            type: 'POST',
            data: {
                idagenda: $this.data('agenda')
            },
            success: function(data) {
//                console.log('success!');
//                console.log(data);
                $.pnotify(data.msg);
                if (data.success) {

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
    });*/

    $(document.body).on('click', "a.incluir, a.excluir-agenda, a.editar, a.participantes", function (event) {
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


//    $('#calendar').fullCalendar('render');

//    innerLayout.sizePane("east", 780);
//    myLayout.open('east');

    function formatadorLink(cellvalue, options, rowObject) {
        var r = rowObject,
            params = '',
            url = {
                editar: base_url + '/agenda/index/edit',
                excluir: base_url + '/agenda/index/excluir',
                detalhar: base_url + '/agenda/index/detalhar',
                participantes: base_url + '/agenda/index/participantes'
            };
        params = '/idagenda/' + r[7];
        //console.log(rowObject);

        return '<a data-target="#dialog-editar" class="btn actionfrm editar" title="Editar" data-id="' + cellvalue + '" href="' + url.editar + params + '"><i class="icon-edit"></i></a>' +
            '<a data-target="#dialog-participantes" class="btn actionfrm editar" title="Participantes" data-id="' + cellvalue + '" href="' + url.participantes + params + '"><i class="icon-user"></i></a>' +
            '<a data-target="#dialog-deta" class="btn actionfrm detalhar" title="Detalhar" data-id="' + cellvalue + '" href="' + url.detalhar + params + '"><i class="icon-tasks"></i></a>' +
            '<a data-target="#dialog-excluir" class="btn actionfrm excluir-agenda" title="Excluir" data-id="' + cellvalue + '" href="' + url.excluir + params + '"><i class="icon-trash"></i></a>';
    }

    //'Data', 'Hora', 'Local', 'Assunto', 'Participantes', 'Usuário', 'Enviou Email'
    colNames = ['Data', 'Hora', 'Local', 'Assunto', 'Participantes', 'Usuário', 'Enviou Email', 'Operações'];
    colModel = [{
        name: 'datagenda',
        index: 'datagenda',
        width: 10,
        hidden: false,
        search: false
    }, {
        name: 'hragendada',
        index: 'hragendada',
        width: 10,
        hidden: false,
        search: false
    }, {
        name: 'deslocal',
        index: 'deslocal',
        width: 10,
        search: true
    }, {
        name: 'desassunto',
        index: 'desassunto',
        width: 10,
        search: true
    }, {
        name: 'nompessoa',
        index: 'nompessoa',
        width: 25,
        search: true
    }, {
        name: 'cadastrador',
        index: 'cadastrador',
        width: 10,
        search: true
    }, {
        name: 'flaenviaemail',
        index: 'flaenviaemail',
        width: 12,
        search: true
    }, {
        name: 'idagenda',
        index: 'idagenda',
        width: 17,
        search: false,
        sortable: false,
        formatter: formatadorLink
    }];

    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/agenda/index/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '1106',
        height: '300',
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

    $('#calendar').attr('style', 'width:350px');
    $('#calendar').fullCalendar({
        dayClick: function (date, allDay, jsEvent, view) {
//            alert('teste');
            grid.jqGrid("clearGridData", true);
            $('#display-data').html('Agenda de ' + $.fullCalendar.formatDate(date, 'dd/MM/yyyy'));
            $('#display-data').attr('data-value-field', $.fullCalendar.formatDate(date, 'dd/MM/yyyy'));
            grid.setGridParam({
                url: base_url + "/agenda/index/pesquisarjson/data/" + $.fullCalendar.formatDate(date, 'yyyy-MM-dd'),
                page: 1
            }).trigger("reloadGrid");
        },
        viewRender: function (view, element) {
            marcaDiasComEvento()
        },
        selectable: true,
        color: 'yellow',   // an option!
        textColor: 'black', // an option!
        height: 265
    });

    function marcaDiasComEvento() {
        $.ajax({
            url: base_url + "/agenda/index/retorna-dias-com-eventos",
            dataType: 'json',
            type: 'POST',
            async: true,
            cache: true,
            data: {mes: getMonth()},
            processData: true,
            success: function (data) {
                $.each(data, function (i, item) {
//                        console.log(item.datagenda);
                    $('.fc-day[data-date="' + item.datagenda + '"]').css('background', '#F3F3F3');
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

    function getMonth() {
        var date = $("#calendar").fullCalendar('getDate');
        var month_int = date.getMonth();
        return month_int + 1;
    }

    $("#btn-t").trigger("click");

    //$('.fc-day[data-date="2014-04-04"]').css('background', '#cccccc');


    //retirando o auto resize
//    myLayout.children.center.layout1.center.options.onresize = null;
    resizeGrid();
});