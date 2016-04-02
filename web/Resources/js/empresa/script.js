$(document).ready(function() {
    
    //queremos que esta variable sea global
    var fileExtension = "";
    var flag = true;
    //función que observa los cambios del campo file y obtiene información
    $(document).on("change","#imagen",function()
    {
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
        
        
        var isimage = isImage(fileExtension);

        if (isimage === false) {
        	flag = false;
             var mensaje = "Formato de imagen seleccionado no es admitido. Seleccione otro archivo";
             
        }else{
        	flag = true;
        }
        
    });

        






 




function mostrarImagen(input) {
      if (input.files && input.files[0]) {
           var reader = new FileReader();
              reader.onload = function (e) {
                    $('#img_destino').attr('src', e.target.result);
               }
              reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imagen").change(function () {
            mostrarImagen(this);
        });


 
 
 
$(document).on("submit","#frmEmpresa",function(e) {
       
	e.preventDefault();
	frm = serializeToJson($(this).serializeArray());
        console.log(frm);
        
        $.ajax({ 
            data:{
		frm: JSON.stringify(frm)
		},
            url:Routing.generate('ingresar_usuarioEmpresa'),
            type: 'POST',
 

//            success: function(data){
//                console.log(data);
//                data = jQuery.parseJSON(data);//convirtiendo datos
//                //console.log(data);
//                 var mensaje = "Alumno Guardado Correctamento. Espere un momento...";
//                if (data.estado == true) {
//                    GetAlert("Éxito",mensaje,"../recursos/imagenes/Ok-icon.png",3000);
//                    setTimeout(function() {
//                        location.reload();
//                    }, 1800);
//                }else{
//                    alertify.error(data.mensaje);
//                }
//            }
        });
       
        





$(document).on("submit","#frmEmpresaCompleto",function(e) {
	e.preventDefault();
    	//información del formulario
        var frm = new FormData($(this)[0]);
        console.log(frm);
        if (flag != false) {
            
     $.ajax({ 
            data:frm,
            url:Routing.generate('ingresar_empresa'),
            type: 'POST',
           //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
           
            //una vez finalizado correctamente
            
            
            
            success: function(data){
                console.log(data);
//               data = jQuery.parseJSON(data);//convirtiendo datos
                //console.log(data);
//                 var mensaje = "Alumno Guardado Correctamento. Espere un momento...";
//                if (data.estado == true) {
//                    GetAlert("Éxito",mensaje,"../recursos/imagenes/Ok-icon.png",3000);
//                    setTimeout(function() {
//                        location.reload();
//                    }, 1800);
//                }else{
//                    alertify.error(data.mensaje);
//                }
            }
        });

   
            
    	}
	
});
       

    
    
    
    
    
        
        
	
	
});


    











    


});