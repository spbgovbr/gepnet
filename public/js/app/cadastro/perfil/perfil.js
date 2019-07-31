perfilpessoa = (function () {
    var
        urlChangeFlag = base_url + '/cadastro/perfilpessoa/trocarsituacao/format/json'
    idperfilpessoa = null,
        dados = [],
        types = {
            deny: 'deny',
            allow: 'allow'
        },
        buttonClass = {
            success: 'btn-success',
            danger: 'btn-danger'
        },
        buttonTitle = {
            success: 'ativar',
            danger: 'inativar'
        },
        iconClass = {
            ativar: 'icon-ok',
            inativar: 'icon-off'
        };

    setFromArray = function (arr) {
        this.dados = arr;
    };

    clearDados = function () {
        this.dados = [];
    };

    /*isAllow = function(perm)
    {
        
        if(this.dados.length <= 0){
            return false;
        }
        return in_array(perm, this.dados);
    };*/

    allow = function (idperfilpessoa) {
        this.setPermission(this.urlChangeFlag, idperfilpessoa, 'S');
    };

    deny = function (idperfilpessoa) {
        this.setPermission(this.urlChangeFlag, idperfilpessoa, 'N');
    };

    setIcon = function (btn, type) {
        //console.log(btn);
        btn.find('i').removeClass(perfilpessoa.iconClass.ativar);
        btn.find('i').removeClass(perfilpessoa.iconClass.inativar);
        if (type === this.types.allow) {
            btn.find('i').addClass(perfilpessoa.iconClass.ativar);
            return;
        }
        btn.find('i').addClass(perfilpessoa.iconClass.inativar);
        return;
    },

        setButton = function (btn, type) {
            btn.removeClass(perfilpessoa.buttonClass.success);
            btn.removeClass(perfilpessoa.buttonClass.danger);
            if (type === perfilpessoa.types.allow) {
                btn.addClass(perfilpessoa.buttonClass.success);
                btn.attr('title', perfilpessoa.buttonTitle.danger);
                btn.data('permission', perfilpessoa.types.deny);
                return;
            }
            btn.addClass(perfilpessoa.buttonClass.danger);
            btn.attr('title', perfilpessoa.buttonTitle.success);
            btn.data('permission', perfilpessoa.types.allow);
            return;
        },

        toggle = function (btn) {
            tipo = btn.data('permission');
            id = btn.data('id');

            if (tipo === this.types.allow) {
                this.allow(id);
            } else {
                this.deny(id);
            }

            this.setButton(btn, tipo);
            this.setIcon(btn, tipo);
            this.idperfilpessoa = id;
        },

        setPermission = function (url, idperfilpessoa, flag) {
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                data: {
                    'idperfilpessoa': idperfilpessoa,
                    'flaativo': flag
                },
                success: function (data) {
                    grid.trigger("reloadGrid");
                    $.pnotify_remove_all();
                    $.pnotify(data.msg);

                },
                error: function () {
                    $.pnotify_remove_all();
                    $.pnotify({
                        text: 'Falha ao enviar a requisição',
                        type: 'error',
                        hide: true
                    });
                }
            });
        };


    init = function () {
        //this.loadPermissions();
    };

    return {
        urlChangeFlag: urlChangeFlag,
        idperfilpessoa: idperfilpessoa,
        dados: dados,
        types: types,
        iconClass: iconClass,
        buttonClass: buttonClass,
        buttonTitle: buttonTitle,
        setFromArray: setFromArray,
        clearDados: clearDados,
        //isAllow: isAllow,
        allow: allow,
        deny: deny,
        setIcon: setIcon,
        setButton: setButton,
        toggle: toggle,
        setPermission: setPermission,
        init: init
    };
})();

function in_array(needle, haystack, argStrict) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',
        strict = !!argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}

