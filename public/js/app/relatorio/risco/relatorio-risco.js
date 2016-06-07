$(function(){
        $("#idescritorio").select2({
          allowClear:true
        });
        
        $("#idprojeto").select2({
          allowClear:true
        });
        
        $("#idnatureza").select2({
          allowClear:true
        });
        
        $('#resetbutton').click(function(e){
            e.preventDefault();
            $("#idescritorio").select2("val", "").val('');
            $("#idprojeto").select2("val", "").val('');
            $("#idnatureza").select2("val", "").val('');
        });
        
        $('#btnpesquisar').click(function(e){
            e.preventDefault();
            $("form#form-risco-pesquisar").submit();
        });
  });