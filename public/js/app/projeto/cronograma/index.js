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
    //console.log('validator custo');
    //console.log('valor ' + value);
    //console.log('valor ' + $(element).val());
    var custo = $("#vlratividade").val();
    //contando chars
    if(custo === '' || custo == '0') {
        custo = 0;
    }
    
    if (custo !== 0 && value == '') {
        //console.log('validator custo false');
        return false;
    }
    //console.log('validator custo false');
    return true;
}, "Campo de preenchimento obrigatório quanto existir custo"); // Mensagem padrão 

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

$(function() {

    $('#status').click( function() {
        if($(this).val() == '50'){
            $('.intervalo-percentual').show();
            $('#percentualinicio').focus();
        }else{
            $('.intervalo-percentual').hide();
        }
    });
    
    CRONOGRAMA.altura.doc      = $(document).height();
    CRONOGRAMA.init();
    CRONOGRAMA.grupo.init();
    CRONOGRAMA.entrega.init();
    CRONOGRAMA.atividade.init();
    
    $("#submitbutton_pesq").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $("body").trigger("fitrarCronograma");

        return false;
    });
   
   
});
