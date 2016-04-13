
$(document).ready(function() {
   
     $("#prev").show();
     $("#previus").hide();
     
   
  
    //queremos que esta variable sea global
    var fileExtension = "";
    var flag = true;
    //función que observa los cambios del campo file y obtiene información
    
    $(document).on("change","#file-inputs",function(){
       
      
        
        $("#saveImagen").hide();
        
        
        //obtenemos un array con los datos del archivo
        var file = $(this)[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
       
        
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        console.log(fileType);
        console.log(fileExtension);
        
        
        if (fileExtension == 'PNG' || fileExtension == 'png' ||fileExtension == 'JPG'|| fileExtension == 'jpg' && fileType =='imagen' || fileType =='image' || fileType =='image/jpeg' )
        {
            flag = true;
        }else{
            flag = false;
        }
        
                if (flag==false){
                    alert("Archivo invalido, seleccione una imagen ");
                    $("#file-input").val("");
                    $("#prev").hide();
                    $("#saveImagen").hide();
                     

                }else{  
                    
                     var pesoKb=(fileSize/1024);
                     if (pesoKb<=1024){

                        
                        var frm = new FormData($("#frmPersonaConFoto")[0]);
                      
                             if (flag != false) {

                                $.ajax({ 
                                       data:frm,
                                       url:Routing.generate('ingresar_foto_persona'),
                                       type: 'POST',
                                       dataType: 'json',
                                      
                                       cache: false,
                                       contentType: false,
                                       processData: false,

                                      
                                       success: function(data){
                                           
                                        $("#prev").show();
                                        $("#prev").attr('src', "/abgsistema/web/"+data.direccion);
                                        $("#perfilAdmin").attr('src', "/abgsistema/web/"+data.direccion);
                                        
                                        $("#previus").hide();
                                            
                                             
                                       }
                                             
                                             
                                     });


                                 }
                        }
                        else{
                            console.log(pesoKb);
                            alert("Imagen demasiado grande, intente con una mas pequeña");
                        }
                }

        });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

});

