$(document).ready(function() {
  
    $("#prev").show();
    
    
 $.fn.editable.defaults.mode = 'popup';
 
 
    //queremos que esta variable sea global
    var fileExtension = "";
    var flag = true;
    //función que observa los cambios del campo file y obtiene información
    
    $(document).on("change","#file-input",function(){
        $("#prev").show();
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
        
        
        if (fileExtension == 'PNG' || fileExtension == 'png' ||fileExtension == 'JPG'|| fileExtension == 'jpg' && fileType =='imagen' || fileType =='image' )
        {
            flag = true;
        }else{
            flag = false;
        }
        
                if (flag==false){
                    alert("Archivo invalido, seleccione una imagen  ");
                    $("#file-input").val("");
                    $("#prev").hide();
                     $("#saveImagen").hide();

                }else{  
                    
                     var pesoKb=(fileSize/1024);
                     if (pesoKb<=1024){

                        
                        var frm = new FormData($("#frmEmpresaConFoto")[0]);
                      
                             if (flag != false) {

                                $.ajax({ 
                                       data:frm,
                                       url:Routing.generate('ingresar_foto'),
                                       type: 'POST',
                                      //necesario para subir archivos via ajax
                                       cache: false,
                                       contentType: false,
                                       processData: false,

                                       //una vez finalizado correctamente

                                       success: function(data){
                                           console.log(data);
                                           
                                            
                                             
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
    
    
    
    
    
    
    
    function mostrarImagen(input) {
            if (input.files && input.files[0]) {
                 var reader = new FileReader();
                    reader.onload = function (e) {
                          $('#prev').attr('src', e.target.result);
                     }
                    reader.readAsDataURL(input.files[0]);
                  }
              } 
                             $("#file-input").change(function () {
                                                    mostrarImagen(this);

                                               });
       
            
         
         
            
        
             
             
   

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
                                        
                                        var url=Routing.generate('ctlempresa_dashbord');
                                        window.open(url,"_self"); 
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
      

 
 
    
    



//$(document).on("click","#saveImagen",function() {
	
        
        
        //$(document).on("submit","#frmEmpresaConFoto",function(e) {
	//e.preventDefault();
    	//información del formulario
        
//        var frm = new FormData($("#frmEmpresaConFoto")[0]);
//        console.log(frm);
//        if (flag != false) {
//            
//     $.ajax({ 
//            data:frm,
//            url:Routing.generate('ingresar_foto'),
//            type: 'POST',
//           //necesario para subir archivos via ajax
//            cache: false,
//            contentType: false,
//            processData: false,
//           
//            //una vez finalizado correctamente
//            
//            
//            
//            success: function(data){
//                console.log(data);
                
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
//            }
//        });
//
//   
//            
//    	}
	
//});
        

//});




       

 
    
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


//La parte del X-Editable



 $('#txtMovil').editable();

 

 $('#txtMovil').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {movil: params.newValue, empresa: $('input#empresaId').val(), n: 1},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                   console.log(data);
                
                  $.each(data,function (i,value)  {
                            
			 $('#txtFijo').html(value['valor']);
                         console.log(value['valor']);
			});
             
                
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
                
            }
        });
    });

 $('#txtFijo').editable();
 
    $('#txtFijo').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {fijo: params.newValue, empresa: $('input#empresaId').val(), n: 2},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                
                $('#txtFijo').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
                
            }
        });
    });
    
    
    
    $('#txtCorreoElectronico').editable();
 
    $('#txtCorreoElectronico').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {correoEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 3},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                
                $('#txtCorreoElectronico').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
                
            }
        });
    });
    
    
   $('#txtDireccion').editable();
 
    $('#txtDireccion').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {direccionEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 4},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                
                $('#txtDireccion').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
                
            }
        });
    }); 
    
    
    
    $('#txtSitioWeb').editable();
 
    $('#txtSitioWeb').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {sitiowebEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 5},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                    
                 Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Datos modificados con exito'
                                    });
                
                
                $('#txtSitioWeb').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
                
            }
        });
    }); 
    
    
    
    
    $("#txtNombreEmpresa").editable();
    $('#txtNombreEmpresa').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {nombreEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 0},
             url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                    
                 Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Datos modificados con exito'
                                    });
                
        
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
                
            }
        });
    }); 
    
    




});

