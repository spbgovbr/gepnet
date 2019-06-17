var keycloakAction = function (url) {
    window.location.href = url;
};

function enviar_ajax(url, form, callback) {
    $.ajax({
        url: base_url + url,
        dataType: 'json',
        type: 'POST',
        data: $(form).serialize(),
        //processData:false,
        success: function (data) {
            //console.log(data);
            if (typeof data.msg.text !== 'string') {
                $.formErrors(data.msg.text);
                return;
            }
            $.pnotify(data.msg);
            if (callback && typeof (callback) === "function") {
                callback(data);
            }
        },
        error: function (xhr, status, thrownError) {
            console.log(xhr.responseText);
            console.log(status);
            console.log(thrownError);

            $.pnotify({
                text: 'Falha ao enviar a requisição',
                type: 'error',
                hide: false
            });
        }
    });
}

var
    myLayout,
    innerLayout,
    altura_ocupada = 50;

function resizeGrid() {
    //console.log('wilton');
    //console.log($('.ui-jqgrid-btable:visible'));
    if (grid = $('.ui-jqgrid-btable:visible')) {
        //console.log(grid.length);
        grid.each(function (index) {
            //console.log('wilton1');
            var gridId = $(this).attr('id');
            $('#' + gridId).setGridWidth(innerLayout.state.center.innerWidth - 20);
            $('#' + gridId).setGridHeight(innerLayout.state.center.innerHeight - (altura_ocupada + 30));
        });
    }
}

$(function () {

    $.pnotify.defaults.history = false;
    /*
    */
    $("ul.navigation").kendoMenu();
    $("ul.menu-lateral").kendoMenu({
        orientation: 'vertical'
    });

    myLayout = $('body').layout({
        north__showOverflowOnHover: true,
        east__showOverflowOnHover: true,
        spacing_open: 3,
        west__initClosed: false,
        east__initClosed: true,

        center: {
            closable: false,
            resizable: false,
            slidable: false,
            //onresize: resizeGrid,
            triggerEventsOnLoad: true  // resize the grin on load also
        },

        south__onclose: function () {
            if (window.south__onclose) {
                south__onclose();
            }
        },
        north__onclose: function () {
            if (window.north__onclose) {
                north__onclose();
            }
        },
        south__onopen: function () {
            if (window.south__onopen) {
                south__onopen();
            }
        },
        north__onopen: function () {
            if (window.north__onopen) {
                north__onopen();
            }
        },
        center__childOptions: {
            center: {
                closable: false,
                resizable: false,
                slidable: false,
                onresize: resizeGrid,
                triggerEventsOnLoad: true  // resize the grin on load also
            },
            center__paneSelector: ".region-center",
            west__paneSelector: ".region-west",
            east__paneSelector: ".region-east",
            east__initClosed: true,
            west__showOverflowOnHover: true,
            //west__size:				150,
            east__size: 300,
            spacing_open: 3, // ALL panes
            spacing_closed: 6/*,
            center__onresize: resizeGrid
            */
        },
        onload_end: function () {
            var width = $('.region-west').outerWidth();
            $('.region-center').css('left', width + 20);
        }

    });

    innerLayout = $('body > .ui-layout-center').layout();

    if ($('.toggle-region').length > 0) {
        $('.toggle-region').each(function (i, val) {
            var $this = $(this);
            innerLayout.addToggleBtn("#" + $this.attr('id'), $this.data('region'));
        });
    }

    if ($("#closebutton").length > 0) {
        innerLayout.addCloseBtn("#closebutton", "east");
    }


    /*
    function resizeGrid(pane, $Pane, paneState) 
    {
        console.log('wilton');
        console.log(paneState.innerHeight);
        //console.log($('.ui-jqgrid-btable:visible'));
        if(grid = $('.ui-jqgrid-btable:visible')) {
            console.log(grid.length);
          grid.each(function(index) {
              console.log('wilton1');
            var gridId = $(this).attr('id');
            $('#' + gridId).setGridWidth(paneState.innerWidth - 2);
            $('#' + gridId).setGridHeight(paneState.innerHeight - (sobra + 50));
          });
        } 
    }
    */

    /*
     // ALL panes
         center__onresize: function (pane, $Pane) {
         console.log($Pane.innerWidth());
         $("#novos-recursos").jqGrid('setGridWidth',$Pane.innerWidth()-40);
         }
    $('.toggle-region').click(function(){
        var
            $this = $(this),
            region = $this.data('region')
            ;

        if(false == innerLayout.state.east.isOpen){
            $(".region-" +region).find("input:first").focus();
        }
    });

 function resizeGrid(pane, $Pane, paneState) {
 if (grid = $('.ui-jqgrid-htable:visible')) {
 grid.each(function(index) {
 var gridId = $(this).attr('id');
 $('#' + gridId).setGridWidth(paneState.innerWidth - 2);
 });
 }
 }

 function resizePaneGrid ( pane, $pane, paneState, paneOptions ) {
 // search within the pane and find the first jqGrid object
 $pane.find(".ui-jqgrid:first")
 .jqGrid("setGridWidth",  paneState.innerWidth )
 .jqGrid("setGridHeight", paneState.innerHeight )
 ;

 $("#novos-recursos")
 .jqGrid("setGridWidth",  paneState.innerWidth )
 .jqGrid("setGridHeight", paneState.innerHeight );
 };
 $('body').layout({
 closable: true,
 resizable: true,
 slidable: true,
 center__onresize:  resizePaneGrid,
 west__onresize:    resizePaneGrid,
 east__onresize:    resizePaneGrid,

 north: {
 spacing_open: 1, // cosmetic spacing
 togglerLength_open: 0, // HIDE the toggler button
 togglerLength_closed: -1, // "100%" OR -1 = full width of pane
 resizable: false,
 slidable: false,
 //	override default effect
 fxName: "none"
 }


 });
 */
    /*
     $(".navigation").wijmenu({
     orientation: 'horizontal',
     animation: {
     animated: "slide", 
     duration: 100, 
     easing: null
     },
     hideAnimation: {
     animated: "fade", 
     duration: 400, 
     easing: null
     },
     showDelay: 100,
     hideDelay: 100
     });
     */
    $(document).ajaxStart(function () {
        $("div#ajax-indicator").show();
    }).ajaxStop(function () {
        $("div#ajax-indicator").hide();
    });

    $.formErrors = function (data) {
        $.each(data, function (element, errors) {
            //var ul = $("<ul>").attr("class", "errors help-inline");
            var ul = $("<ul>").attr("class", "errors");
            $.each(errors, function (name, message) {
                ul.append($("<li>").text(message));
            });
            $("#" + element).parent().find('ul').remove();
            $("#" + element).after(ul);
        });
    };

    $("a.link_sair").click(function () {

        var url = "/index/logout";

        if (undefined === base_url) {
            var base_url = "";
        }

        $.ajax({
            url: base_url + url,
            dataType: 'json',
            type: 'GET',
            success: function (data) {
                keycloakAction(data.redirect);
            },
            error: function () {
                keycloakAction('index');
            }
        });
        return false;
    });

//    $('#dialog-perfil').dialog({
//        autoOpen: false,
//        title: 'Mudar Perfil',
//        width: '550px',
//        modal: false,
//        open: function(event, ui) {
//            $("#idperfil").select2();
//        },
//        close: function(event, ui) {
////$dialogEditar.empty();
//        },
//        buttons: {
//            'Fechar': function() {
//                $(this).dialog('close');
//            },
//            'Enviar': function() {
////console.log('submit');
////$formEditar.on('submit');
//                $("form#form-perfil").trigger('submit');
//            }
//        }
//    });
//    $('#form-perfil').submit(function(event) {
//        event.preventDefault();
//        var url = '/index/mudar-perfil/format/json',
//                $this = $(this);
//        $.ajax({
//            url: base_url + url,
//            dataType: 'json',
//            type: 'POST',
//            data: $this.serialize(),
//            //processData:false,
//            success: function(data) {
//                if (typeof data.msg.text !== 'string') {
//                    $.formErrors(data.msg.text);
//                    return;
//                }
//                $.pnotify(data.msg);
//                location.href = base_url + '/index/boas-vindas';
//            },
//            error: function() {
//                $.pnotify({
//                    text: 'Falha ao enviar a requisição',
//                    type: 'error',
//                    hide: false
//                });
//            }
//        });
//    });
//    $("a.link_perfil").click(function() {
//        $('#dialog-perfil').dialog('open');
//    });
});


function number_format(number, decimals, dec_point, thousands_sep) {

    var n = number, prec = decimals;
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;

    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

    var abs = Math.abs(n).toFixed(prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0, i + (n < 0)) +
            _[0].slice(i).replace(/(\d{3})/g, sep + '$1');

        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    return s;
}

