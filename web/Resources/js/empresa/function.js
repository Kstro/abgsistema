


function SaveDatosEmpresa(frm) {
     console.log(frm);
     
    $.ajax({ 
            data:frm,
            url:Routing.generate('ingresar_empresa'),
            type: 'POST',
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            
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


}

