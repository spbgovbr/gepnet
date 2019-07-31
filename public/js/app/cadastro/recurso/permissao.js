//var permissoes = [];
permissao = (function () {
    var
        urlPermissions = base_url + "/cadastro/recurso/retorna-por-perfil/format/json/idperfil/",
        urlCadastrarRecurso = base_url + "/cadastro/recurso/cadastrar/format/json",
        perfil = null,
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
            success: 'conceder',
            danger: 'revogar'
        },
        iconClass = {
            conceder: 'icon-ok',
            revogar: 'icon-off'
        };

    setFromArray = function (arr) {
        this.dados = arr;
    };

    clearDados = function () {
        this.dados = [];
    };

    isAllow = function (perm) {
        if (this.dados.length <= 0) {
            return false;
        }
        return in_array(perm, this.dados);
    };

    allow = function (idpermission) {
        this.setPermission(this.urlAllow, idpermission);
    };

    deny = function (idpermission) {
        this.setPermission(this.urlDeny, idpermission);
    };

    setIcon = function (btn, type) {
        //console.log(btn);
        btn.find('i').removeClass(permissao.iconClass.conceder);
        btn.find('i').removeClass(permissao.iconClass.revogar);
        if (type === this.types.allow) {
            btn.find('i').addClass(permissao.iconClass.conceder);
            return;
        }
        btn.find('i').addClass(permissao.iconClass.revogar);
        return;
    },

        setButton = function (btn, type) {
            btn.removeClass(permissao.buttonClass.success);
            btn.removeClass(permissao.buttonClass.danger);
            if (type === permissao.types.allow) {
                btn.addClass(permissao.buttonClass.success);
                btn.attr('title', permissao.buttonTitle.danger);
                btn.data('permission', permissao.types.deny);
                return;
            }
            btn.addClass(permissao.buttonClass.danger);
            btn.attr('title', permissao.buttonTitle.success);
            btn.data('permission', permissao.types.allow);
            return;
        },

        toggle = function (btn) {
            tipo = btn.data('permission');
            idpermission = btn.data('id');

            if (tipo === this.types.allow) {
                this.allow(idpermission);
            } else {
                this.deny(idpermission);
            }

            this.setButton(btn, tipo);
            this.setIcon(btn, tipo);
        },

        setPermission = function (url, idpermissao) {
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                data: {
                    'idpermissao': idpermissao,
                    'idperfil': this.perfil
                },
                success: function (data) {
                    //actions.editar.dialog.html(data).dialog('open');
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

    loadPermissions = function () {
        if ($("select#idperfil").select2()) {
            perfil = $("select#idperfil").select2("val");
        } else {
            perfil = $("select#idperfil").val();
        }
        this.perfil = perfil;
        $.ajax({
            url: this.urlPermissions + perfil,
            dataType: 'json',
            type: 'GET',
            async: false,
            success: function (data) {
                if (data.success) {
                    permissao.setFromArray(data.dados);
                    return;
                }
                permissao.clearDados();
            },
            error: function () {
                $.pnotify({
                    text: 'Falha ao enviar a requisição',
                    type: 'error',
                    hide: true
                });
            }
        });
    };

    cadastrar = function (btn) {
        var
            ds_recurso = btn.data('recurso'),
            no_permissao = btn.data('permissao');

        $.ajax({
            url: this.urlCadastrarRecurso,
            dataType: 'json',
            type: 'POST',
            data: {
                'ds_recurso': ds_recurso,
                'no_permissao': no_permissao
            },
            success: function (data) {
                if (data.success) {
                    $.pnotify_remove_all();
                    $.pnotify(data.msg);
                    btn.closest('tr').remove();
                }
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
        urlDeny: base_url + '/cadastro/perfil/revogar-permissao/format/json',
        urlAllow: base_url + '/cadastro/perfil/conceder-permissao/format/json',
        urlPermissions: urlPermissions,
        urlCadastrarRecurso: urlCadastrarRecurso,
        perfil: perfil,
        dados: dados,
        types: types,
        iconClass: iconClass,
        buttonClass: buttonClass,
        buttonTitle: buttonTitle,
        setFromArray: setFromArray,
        clearDados: clearDados,
        isAllow: isAllow,
        allow: allow,
        deny: deny,
        setIcon: setIcon,
        setButton: setButton,
        toggle: toggle,
        setPermission: setPermission,
        loadPermissions: loadPermissions,
        cadastrar: cadastrar,
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