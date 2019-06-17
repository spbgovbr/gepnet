jQuery.validator.addMethod("dateBR", function (value, element) {
    //contando chars
    if (value.length != 10) return (this.optional(element) || false);
    // verificando data
    var data = value;
    var dia = data.substr(0, 2);
    var barra1 = data.substr(2, 1);
    var mes = data.substr(3, 2);
    var barra2 = data.substr(5, 1);
    var ano = data.substr(6, 4);
    if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) return (this.optional(element) || false);
    if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) return (this.optional(element) || false);
    if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) return (this.optional(element) || false);
    if (ano < 1900) return (this.optional(element) || false);
    return (this.optional(element) || true);
}, "Data inválida"); // Mensagem padrão 

jQuery.validator.addMethod("custo", function (value, element) {
    var elementodespesa = $("#idelementodespesa").val();
    //***********
    var custo = $("#vlratividade").val();
    var custo = custo.replace(',', '.');
    var custoValor = parseFloat(custo).toFixed(2);
    //**********
    var custoEstimado = $("#vlratividadebaseline").val();
    var custoEstimado = custoEstimado.replace(',', '.');
    var custoEstimadoValor = parseFloat(custoEstimado).toFixed(2);
    //contando chars
    if (custoValor <= 0) {
        custo = 0;
    }

    if (((custo > 0) || (custoEstimado > 0) || (elementodespesa != '')) && value == '') {
        //console.log('validator custo false');
        return false;
    }
    //console.log('validator custo false');
    return true;
}, "Campo de preenchimento obrigatório quanto existir custo"); // Mensagem padrão

jQuery.validator.addMethod("flgAquisicao", function (value, element) {
    var flgaquisicao = $("#flaaquisicao").val();
    //***********
    var custo = $("#vlratividade").val();
    var custo = custo.replace(',', '.');
    var custoValor = parseFloat(custo).toFixed(2);
    //**********
    var custoEstimado = $("#vlratividadebaseline").val();
    var custoEstimado = custoEstimado.replace(',', '.');
    var custoEstimadoValor = parseFloat(custoEstimado).toFixed(2);
    //**********
    if (((custoValor <= 0) || (custoEstimadoValor <= 0)) && (flgaquisicao == 'S')) {
        return false;
    }
    return true;
}, "Informe o Custo e o Elemento de Despesa quando 'Aquisição'."); // Mensagem padrão

/* jQuery.validator.addMethod("flgCustoElementoDespesa", function (value, element) {
    var flgaquisicao = $("#flaaquisicao").val();
    var elementodespesa = $("#idelementodespesa").val();
    //***********
    var custo = $("#vlratividade").val();
    var custo = custo.replace(',','.');
    var custoValor = parseFloat(custo).toFixed(2);
    //**********
    var custoEstimado = $("#vlratividadebaseline").val();
    var custoEstimado = custoEstimado.replace(',','.');
    var custoEstimadoValor = parseFloat(custoEstimado).toFixed(2);
    //**********
    if(((elementodespesa!='')||(custoValor > 0)||(custoEstimadoValor > 0))&&(flgaquisicao =='N')) {
        return false;
    }
    return true;
}, "Informe 'Sim' em 'Aquisição' quando Custo ou Elemento de Despesa preenchidos."); // Mensagem padrão
/**/
jQuery.validator.addMethod("flgCusto", function (value, element) {
    var flgaquisicao = $("#flaaquisicao").val();
    var elementodespesa = $("#idelementodespesa").val();
    //***********
    var custo = $("#vlratividade").val();
    var custo = custo.replace(',', '.');
    var custoValor = parseFloat(custo).toFixed(2);
    //**********
    var custoEstimado = $("#vlratividadebaseline").val();
    var custoEstimado = custoEstimado.replace(',', '.');
    var custoEstimadoValor = parseFloat(custoEstimado).toFixed(2);
    //**********
    if (((custoValor <= 0) || (custoEstimadoValor <= 0)) && ((elementodespesa != '') || flgaquisicao == 'S')) {
        return false;
    }
    return true;
}, "Informe o 'Custo Real' quando 'Elemento de Despesa' ou 'Aquisição' informados."); // Mensagem padrão

jQuery.validator.addMethod("flgCustoEstimado", function (value, element) {
    var flgaquisicao = $("#flaaquisicao").val();
    var elementodespesa = $("#idelementodespesa").val();
    //***********
    var custo = $("#vlratividade").val();
    var custo = custo.replace(',', '.');
    var custoValor = parseFloat(custo).toFixed(2);
    //**********
    var custoEstimado = $("#vlratividadebaseline").val();
    var custoEstimado = custoEstimado.replace(',', '.');
    var custoEstimadoValor = parseFloat(custoEstimado).toFixed(2);
    //**********
    if (((custoValor <= 0) || (custoEstimadoValor <= 0)) && ((elementodespesa != '') || flgaquisicao == 'S')) {
        return false;
    }
    return true;
}, "Informe o 'Custo Estimado' quando 'Elemento de Despesa' ou 'Aquisição' informados."); // Mensagem padrão

// jQuery.validator.addMethod("flgInformatica", function (value, element) {
// var custo = $("#vlratividade").val();
// var flginfo = $("#flainformatica").val();
// if(((custo === '') || (custo == '0') || (custo = 0))&&(flginfo =='S')) {
// return false;
// }
// return true;
// }, "Informe o Custo e o Elemento de Despesa quando 'Material de Informática'."); // Mensagem padrão

//jQuery.validator.addMethod("custoInformatica", function (value, element) {
//    //console.log('validator custoInformatica');
//    var custo = $("#vlratividade").val();
//    //console.log(custo);
//    //contando chars
//    if(custo === '' || custo == '0,00' || custo == '0') {
//        custo = 0;
//    }
//    
//    if (custo !== 0 && value == 'N') {
//        return false;
//    }
//    
//    return true;
//}, "O valor deve ser SIM quanto existir custo"); // Mensagem padrão 

$(function () {

    $('#status').click(function () {
        if ($(this).val() == '50') {
            $('.intervalo-percentual').show();
        } else {
            $('.intervalo-percentual').hide();
        }
    });

    CRONOGRAMA.altura.doc = $(document).height();
    CRONOGRAMA.init();
    CRONOGRAMA.grupo.init();
    CRONOGRAMA.entrega.init();
    CRONOGRAMA.atividade.init();

    $("form#ac_atividade_pesquisar").validate();

    $("#submitbutton_pesq").click(function (event) {
        if ($("form#ac_atividade_pesquisar").valid()) {
            event.preventDefault();
            event.stopPropagation();
            $("body").trigger("fitrarCronograma");
            return false;
        }
    });

    $("#closebutton_pesq").click(function (event) {
        $("#btn-buscar").click();
    });

});
