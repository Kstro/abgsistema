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
        
        console.log(fileType);
        if (fileExtension == 'png' || fileExtension == 'jpg' && fileType =='imagen')
        {
            flag = true;
        }else{
            flag = false;
        }
        
        if (flag==false){
            alert("Archivo invalido, seleccione una imagen  ");
            $("#imagen").val("");
            
        }
        else{
            
            $("#imagen").change(function () {
                  mostrarImagen(this);
             });
         
            
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
            
            
        
             
             
   

$(document).on("submit","#frmEmpresaUsuarioPersona",function(e) {
        
        var estadoCorreo;
	e.preventDefault();
	frm = serializeToJson($(this).serializeArray());
    //Ajax que valida  el correo
    $.ajax({ 
           data:{
                    frm: JSON.stringify(frm)
		},
           url:Routing.generate('validar_correo'),
           type: 'POST',
 

           success: function(data){
           data = jQuery.parseJSON(data);
           console.log(data);
          

            if (data) {
                      //Ajax de insersion de datos               
                         $.ajax({ 
                                 data:{
                                       frm: JSON.stringify(frm)
                                       },
                                   url:Routing.generate('ingresar_usuarioEmpresa'),
                                  type: 'POST',


                                 success: function(data){
                                      data = jQuery.parseJSON(data);//convirtiendo datos
                       //                 var mensaje = "Alumno Guardado Correctamento. Espere un momento...";
                                if (data.estado == true) {
                                    alert(data.valor);
                                       }else{
                                           alert(data.valor);
                                     }
                                   }
                           });

                   
                   
                    
               }else{
                  
                  
                    alert("El Correo ya existe, intenta con otro");
                  
                  
                  
                    
               }
              
            }
        });
       

       
        
 });
      

 
 
    
    

$(document).on("submit","#frmEmpresaConFoto",function(e) {
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
       

 
    
$("#contrasenha").strength();  
    
//  $('#contrasenha').strength({
//        strengthClass: 'strength',
//        strengthMeterClass: 'strength_meter',
//        strengthButtonClass: 'button_strength',
//        strengthButtonText: 'Show password',
//        strengthButtonTextToggle: 'Hide Password'
//    });       




 $('#colorSelector  ').ColorPicker({
	color: '#0000ff',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#colorSelector div').css('backgroundColor', '#' + hex);
	}
}); 











});

