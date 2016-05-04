
$(document).ready(function() {
    $("#formPassword").hide();
    $("#hideFormPassword").hide();
    
    
     $(document).on("click","#showFormPassword",function() {
         
        $("#formPassword").show();
        $("#showFormPassword").hide();
        $("#hideFormPassword").show();
         
     });
     $(document).on("click","#hideFormPassword",function() {
         
        $("#formPassword").hide();
        $("#showFormPassword").show();
        $("#hideFormPassword").hide();
        limpiarCajas();
         
     });
     $(document).on("click","#cmdCancelarCambiarContra",function() {
         
        $("#formPassword").hide();
        $("#showFormPassword").show();
        $("#hideFormPassword").hide();
        limpiarCajas();
         
     });
     
    
var x=false; 
 $("#cmdCambiarContra").prop('disabled', true);
 
 
    $(document).on("change",".verificacion",function() {
	
        var contraseNueva= $("#idcontrsenhaNueva").val();
        var contraseNuevaConfirmacion= $("#idcontrsenhaNuevaConfirmacion").val();
        
     if(contraseNueva=="" && contraseNuevaConfirmacion==""){
            
             $("#mensaje").text("");
            
      }else{
        
        if (contraseNueva==contraseNuevaConfirmacion){
            
            $("#mensaje").text("Las contraseñas coinciden");
            $("#mensaje").css("color", "Blue");
            x=true;
            
            if(x==true){
                $("#cmdCambiarContra").prop('disabled', false);
    

    
          } 
             
            
        }else if(contraseNueva!==contraseNuevaConfirmacion){
            
            $("#mensaje").text("Las contraseñas no coinciden");
            $("#mensaje").css("color", "Red");
            $("#cmdCambiarContra").prop('disabled', true);
           
        }
       
     }  

});


   $(document).on("click","#cmdCambiarContra",function() {
       
        var contraNueva= $("#idcontrsenhaNueva").val();
        var contraVieja= $("#idcontrasenhaAntigua").val();
        
                    $.ajax({
                                    type: 'POST',
                                    async: false,
                                    dataType: 'json',
                                    data: {contraNueva:contraNueva,contraVieja:contraVieja},
                                    url: Routing.generate('cambiarContra'),
                                    success: function (data)
                                    {
                                     if(data.estado){
                                         
                                          Lobibox.notify("success", {
                                                                size: 'mini',
                                                                msg: 'Contraseña modificada,espere un momento'
                                                            }); 
                                                            
                                           limpiarCajas();
                                            $("#showFormPassword").show();
                                            $("#hideFormPassword").hide();  
                                           $("#formPassword").hide();                  
                                         
                                     }else{
                                         Lobibox.notify("error", {
                                                                size: 'mini',
                                                                msg: 'Contraseña invalida'
                                                            });  
                                           limpiarCajas();
                                         
                                         
                                     }
                                          
                                    },
                                    error: function (xhr, status)
                                    {
                                         
                                    }
                                });
        
        
        
       
       
     });  
    
     
    
 });
function limpiarCajas(){
     $("#idcontrsenhaNueva").val("");
     $("#idcontrasenhaAntigua").val("");
     $("#idcontrsenhaNuevaConfirmacion").val("");
     $("#mensaje").text("");
     $("#cmdCambiarContra").prop('disabled', true);
    
    
    
}