Handlebars.registerHelper("compare", function (v1, op, v2, options) {

    var c = {
        "eq": function (v1, v2) {
            return v1 == v2;
        },
        "neq": function (v1, v2) {
            return v1 != v2;
        },
    }

    if (Object.prototype.hasOwnProperty.call(c, op)) {
        return c[op].call(this, v1, v2) ? options.fn(this) : options.inverse(this);
    }
    return options.inverse(this);
});


var CRONOGRAMA = (function ($, Handlebars) {
    var
        cron = {};

    cron.projeto = {};
    cron.tplProjeto = null;
    cron.tplGrupo = null;
    cron.tplEntrega = null;
    cron.tplAtividade = null;
    cron.tplMarco = null;
    cron.nav = [];
    cron.msgerror = 'Falha ao enviar a requisição. Atualize o navegador pressionando \"Ctrl + F5\". \nSe o problema persistir, informe o gestor do sistema (cige@dpf.gov.br).';

    cron.retornaProjeto = function () {
        var idprojeto = $(".idprojeto").attr('id');
        $.ajax({
            url: base_url + '/projeto/cronograma/retorna-projeto/format/json',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                idprojeto: idprojeto
            },
            success: function (data) {
                //console.log('aqui');
                cron.projeto = data.projeto;
                //console.log(data.projeto);
                cron.renderProjeto();
                //console.log(cron.projeto);
                // console.log(cron.projeto.numcriteriofarol);
                //data.projeto.numcriteriofarol;
            },
            error: function () {
                $.pnotify({
                    text: cron.msgerror,
                    type: 'error',
                    hide: false
                });
            }
        });
    };

    cron.renderProjeto = function () {
        //cron.tplProjeto   = Handlebars.compile($('#tpl-projeto').html());
        cron.tplGrupo = Handlebars.compile($('#tpl-grupo').html());

        Handlebars.registerPartial("helperEntrega", $("#tpl-entrega").html());
        Handlebars.registerPartial("helperAtividade", $("#tpl-atividade").html());

        //$('#dados-projeto').html(cron.tplProjeto(cron.projeto));
        $('.container-grupo-detalhar').html(cron.tplGrupo(cron.projeto));
        /*cron.events();

        if(cron.itemChecado !== null){
            $(cron.itemChecado).attr("checked",true).trigger('click');
        }

        if(cron.itemativo !== null){
            $(cron.itemativo).addClass("success");
        }
        cron.nav = $(cron.checkItemCronograma);*/
    };

    cron.events = function () {

    };

    cron.init = function () {
        //cron.retornaProjeto();


    };

    return cron;

}(jQuery, Handlebars));


//XXXXXXXXXX GRUPO XXXXXXXXXX
CRONOGRAMA.grupo = (function ($, Handlebars) {
    var o = {};


    return o;
}(jQuery, Handlebars));


//XXXXXXXXXX ENTREGA XXXXXXXXXX
CRONOGRAMA.entrega = (function ($, Handlebars) {
    var o = {};

    return o;
}(jQuery, Handlebars));

//XXXXXXXXXX ATIVIDADE XXXXXXXXXX
CRONOGRAMA.atividade = (function ($, Handlebars) {
    var o = {};

    /* o.$dialogAtividade = null;
     o.$dialogAtividadeExcluir = null;
     o.formAtividade = "form#ac-atividade";
     o.formAtividadeExcluir = "form#ac-atividade-excluir";
     o.formPercentual = "form#e_atividade";
     o.alertaPredecessora = "#alert-predecessora";
     */
    o.templatePredecessora = null;
    o.tablePredecessora = "table#table-predecessoras";

    o.compilarTemplates = function () {
        o.templatePredecessora = Handlebars.compile($('#tpl-predecessora').html());
    };

    o.init = function () {
        o.compilarTemplates();
        //o.initDialogs();
        //o.customEvents();
        //o.events();
    };

    return o;
}(jQuery, Handlebars));


$(function () {


    CRONOGRAMA.init();

    CRONOGRAMA.atividade.init();

});










