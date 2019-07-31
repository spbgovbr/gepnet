$(document).ready(function () {

//    innerLayout.sizePane("east", 380);
//    myLayout.open('east');

    var
        $form = $("form#form-agenda")

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });

    $form.validate({
        errorClass: 'error',
        validClass: 'success',
        submitHandler: function (form) {
            enviar_ajax("/agenda/index/add/format/json", "form#form-agenda", function (data) {
                if (data.success) {
//                    console.log(data);
//                    window.location.href = base_url + "/agenda/index/edit/idagenda/" + data.idagenda;
//                    $('#link_participantes').attr('style', 'display:block');

                }
            });
        }
    });


//    $("#btn-t").trigger("click");

//    var $form = $("form#pessoa-pesquisar");

//    $form.on('submit', function(e) {
//        e.preventDefault();
//        grid.setGridParam({
//            url: base_url + "/cadastro/pessoa/pesquisarjson?" + $form.serialize(),
//            page: 1
//        }).trigger("reloadGrid");
//        //$("a.actionfrm").tooltip();
//        return false;
//    });

    /*colNames = ['Cargo', 'Nome', 'Matrícula', 'CPF', 'Lotação', 'Operações'];
    colModel = [{
        name: 'domcargo',
        index: 'domcargo',
        width: 50,
        hidden: true,
        search: false
    }, {
        name: 'nompessoa',
        index: 'nompessoa',
        width: 200,
        hidden: false,
        search: false
    }, {
        name: 'nummatricula',
        index: 'nummatricula',
        width: 60,
        hidden: true,
        search: true
    }, {
        name: 'numcpf',
        index: 'numcpf',
        width: 60,
        hidden: true,
        search: true
    }, {
        name: 'unidade',
        index: 'unidade',
        width: 200,
        hidden: true,
        search: true
    }, {
        name: 'idpessoa',
        index: 'idpessoa',
        width: 50,
        search: false,
        hidden: true,
        sortable: false
//        formatter: formatadorLink
    }];*/

    /*var i = 0;
    grid = jQuery("#list2").jqGrid({
        //caption: "Documentos",
        url: base_url + "/cadastro/pessoa/pesquisarjson",
        datatype: "json",
        mtype: 'post',
        width: '297',
        height: '150',
        colNames: colNames,
        colModel: colModel,
        rownumbers: false,
        rowNum: 50,
        rowList: [20, 50, 100],
        pager: '#pager2',
        sortname: 'nompessoa',
        viewrecords: true,
        sortorder: "asc",
        gridComplete: function() {
            // console.log('teste');
            //$("a.actionfrm").tooltip();
        },
        ondblClickRow: function(rowid) {
            var row = grid.getRowData(rowid)
                data = '<input type="checkbox" class="chk" name="chk_' + i + '" >' + row.nompessoa + '<br>';
                i++;
            $('#participantes').prepend(data);
//            console.log(row);
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

    grid.jqGrid('setLabel', 'rn', 'Ord');*/

//    $form.validate({
//        errorClass: 'error',
//        validClass: 'success',
//        submitHandler: function(form) {
//            enviar_ajax("/cadastro/pessoa/pesquisarjson?", "form#pessoa-pesquisar", function(data) {
//                if (data.success) {
////                    window.location.href = base_url + "/agenda";
//                }
//            });
//        }
//    });

    /*$form.on('submit', function(e) {
        e.preventDefault();
        grid.setGridParam({
            url: base_url + "/cadastro/pessoa/pesquisarjson?" + $form.serialize(),
            page: 1
        }).trigger("reloadGrid");
        //$("a.actionfrm").tooltip();
        return false;
    });*/
});