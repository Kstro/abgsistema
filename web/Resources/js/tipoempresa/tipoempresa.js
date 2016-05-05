
 $(document).ready(function(){
       var numero_edicion = 0;
     
     
      $(".delete").hide();
         $(".modificar").hide();
         $(".guardar").hide();
         $(".cancelar").hide();
         $("#guardarModificacion").hide();
         $("#cancelarModificacion").hide();
     
     
     
     
     $('#formularioInsercionTipoEmpresa').hide();
     $('.guardar').hide();
     $('.eliminar').hide();
     
  
     
      $(".insertar").on("click",function (){
                    $('#formularioInsercionTipoEmpresa').show();
                    $(".guardar").show();
                    $(".cancelar").show();
                    $(".insertar").hide();

           });    
      
      
      $(".cancelar").on('click', function(e) {
            $(".insertar").show();
            $("#formularioInsercionTipoEmpresa").hide();
             $(".guardar").hide();
             $(".cancelar").hide();
             
             
        });
        
        
        $(".modificar").on('click', function(e) {
                $("#formularioEdicion").show();
                $("#guardarModificacion").show();
                $("#cancelarModificacion").show();
                $("#insertar").hide();
                $(".modificar").hide();

              

        });  
     
     
     $("#cancelarModificacion").on("click",function (e){
            $("#formularioEdicionTerritorio").hide();
            $(".modificar").show();
            $(".insertar").show();
            $(".delete").show();
            $("#guardarModificacion").hide();
            $("#cancelarModificacion").hide();
            
        });
     
     //Ajax que llena el data table
     
     
     
     
     //Eventos de los CheckBoxs para poder controlar los seleccionados
            $(document).on('click',".idtipoempresa", function(e) {
             $(".guardar").hide();
             $(".cancelar").hide();
             $("#formularioInsercionTipoEmpresa").hide();
              $(".insertar").show();

             var num =0;

                    $('.idtipoempresa').each(
                       function (){
                        if($(this).prop("checked")){
                           num++;
                        }
                     

                       });

                    if(num == 0){
                            $(".modificar").hide(); 
                            $(".delete").hide();
                            $("#formularioEdicion").hide();
                            $("#cancelarModificacion").hide();
                            $("#guardarModificacion").hide();
                            $("#insertar").show();
                         } else if(num==1){
                             
                            $(".modificar").show(); 
                            $(".delete").show();
                          
                            
                          //Para editar 
                          numero_edicion = $(this).attr("id");//El valor del id del chechboxs
                          
                          
//                      $("#formularioEdicion").load(numero_edicion+"/edit");
                            
                            
                         }else{
                             $(".modificar").hide();
                             $(".delete").show();
                             $("#formularioEdicion").hide();
                             $("#guardarModificacion").hide();
                             $("#cancelarModificacion").hide();
                         } 
            
             
            
            });
     
     
     
     
 
                   
   
     
  
  
 });



function enviarf()
{
   
     jQuery(document).ready(function($) {
  
       $.ajax({
                type: "GET",
                url:Routing.generate('ingresar_tipoempresa'),              
                data:{dato:$("#tipoempresa").serialize()},
                success: function(data) 
                {
                alert(data); 
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                },
            });
        });
 
    return false;
}
           
 