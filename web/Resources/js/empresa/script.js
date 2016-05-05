
var tipoEmpresa = [];

var trs, trs2, idEspS = null;
var Especialida = [];
var DataEspecialida = [];

var datos = "", datosMostrados = "";

$(document).ready(function() {
  
  $("#gif").hide();
  
  $(document).on("click","#botonModal",function() {
	 $("#idModalFoto").modal();
         
	
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
           dataType: 'json',
 

           success: function(data){
                 console.log(data);
           data = jQuery.parseJSON(data);
         
          

            if (data==true) {
                      //Ajax de insersion de datos               
                         $.ajax({ 
                                 data:{
                                       frm: JSON.stringify(frm)
                                       },
                                   url:Routing.generate('ingresar_usuarioEmpresa'),
                                  type: 'POST',
                                   dataType: 'json',


                                 success: function(data){
                                    
                                if (data.estado == true) {
                                        
                                        var url=Routing.generate('admin_abg',{username: data.username});
                                        window.open(url,"_self"); 
                                        
                                        Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Registro exitoso, espere un momento'
                                    });
                                    
                                        
                                       }else{
                                           
                                        Lobibox.notify("error", {
                                        size: 'mini',
                                        msg: 'Error al ingresar los datos'
                                    });
                                     }
                                   }
                           });

                   
                   
                    
               }else{
                  
                  
                    Lobibox.notify("error", {
                                        size: 'mini',
                                        msg: 'Correo ya existente, intenete con otro.'
                                    });
                    
               }
              
            }
        });
       

       
        
 });
      

 
 

 

 $('#txtMovil').editable({
         
         type: 'text',
         name: 'zip',
         tpl:'   <input type="text" id ="zipiddemo" class="form-control    input-sm" style="padding-right: 24px;">'
            }).on('shown',function(){
    $("input#zipiddemo").mask("0000-0000");
  });



// 

 $('#txtMovil').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {movil: params.newValue, empresa: $('input#empresaId').val(), n: 1},
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
                 Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
            }
        });
    });

 $('#txtFijo').editable({
         type: 'text',
         name: 'zip',
         tpl:'   <input type="text" id ="telefonoFijo" class="form-control    input-sm" style="padding-right: 24px;">'
            }).on('shown',function(){
    $("input#telefonoFijo").mask("0000-0000");
  });
 
    $('#txtFijo').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {fijo: params.newValue, empresa: $('input#empresaId').val(), n: 2},
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
                 Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
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
                
                Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Datos modificados con exito'
                                    });
            },
            error: function (xhr, status)
            {
                 Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
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
                Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
            }
        });
    }); 
    
    
    
    
    $("#txtNombreEmpresa").editable({
                tpl: '<input type="text" maxlength="45">'
                
              });
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
                Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
            }
        });
    }); 
    
 //Funcion que cambia el color del banner
    
    var exa;
   $('#colorSelector').ColorPicker({
	color: '#4ec24e',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
                $(".colorpicker").css('float','right');
		return true;
	},
	onHide: function (colpkr,hex) {
		$(colpkr).fadeOut(500);
//                alert(exa);
                    var empresaId= $("#empresaIdColor").val();
                    $.ajax({
                                type: 'POST',
                                async: false,
                                dataType: 'json',
                                data: {colorEmpresa: exa, idEmpresa: $('input#empresaIdColor').val()},
                                 url: Routing.generate('edit_color'),
                                success: function (data)
                                {
                                     
                                     $('#colorBanner div').css('backgroundColor', '#' +  data.color);
                                     Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Datos modificados con exito'
                                    });


                                },
                                error: function (xhr, status)
                                {
                                    Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });

                                }
                            });
                            
                            
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#colorSelector div').css('backgroundColor', '#' + hex);
                exa = '#' + hex;
	}
    });
    
    
    
                 $.ajax({
                                type: 'POST',
                                async: false,
                                dataType: 'json',
                             
                                 url: Routing.generate('mostarTipoEmpresa'),
                                success: function (data)
                                {
                                    
                                                                    
                                    $.each(data.valores,function (i,values)  {
                                      
                                          
                                          tipoEmpresa.push(values['nombre']);

                                     });

                                     
                                        
                                       console.log(tipoEmpresa); 

                                }
                                
                            });

   
        
        
        
$('#tipoEmpresa').editable({
     value: 'Buffet',    
     source:eval(tipoEmpresa)
       
    });
    
  $('#tipoEmpresa').on('save', function (e, params) {
      
      
      
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {tipoEmpresas: params.newValue, empresa: $('input#empresaId').val(), n: 6},
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
                Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
            }
    });
    }); 
    
  
 
    $("#txtFechaFundacion").editable();
    $('#txtFechaFundacion').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {anhoFundacion: params.newValue, empresa: $('input#empresaId').val(), n: 7},
             url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                 console.log(data);

                if (data.valor){
                     
                     Lobibox.notify("succes", {
                                        size: 'mini',
                                        msg: 'Año ingresado con exito'
                                    });
                    
                }else{
                     Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'No se puede insertar un año invalido'
                                    });
                    
                }
                    
                     
                
        
            },
            error: function (xhr, status)
            {
                Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
            }
        });
    });    
 
    
   
 $('#cantidadEmpleados').editable({
        value: 'De 1 a 10 empleados',    
       source: [
              {value:'De 1 a 10 empleados', text: 'De 1 a 10 empleados'},
              {value: 'De 11 a 50 empleados', text: 'De 11 a 50 empleados'},
              {value: 'De 51 a 100 empleados', text: 'De 51 a 100 empleados'},
              {value: 'Mas 100 empleados', text: 'Mas 100 empleados'}
           ]
       
    });
   
    
  $('#cantidadEmpleados').on('save', function (e, params) {
      
      
      
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {cantidadEmpleados: params.newValue, empresa: $('input#empresaId').val(), n: 8},
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
                Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });
                
            }
    });
    
    
    });    
    
    $('#somecomponent').locationpicker();
    
    var elemento = $("#elemento").val();
                         
                         if(elemento==1){
                              $(".listarEmpleados").prop('checked', true);
                            console.log(elemento);
                             
                         }else{
                             
                         $(".listarEmpleados").prop('checked', false);
                             console.log(elemento);
                         }
                         
    
    
    
    
    $(document).on("click",".listarEmpleados",function() {

                if ($(this).is(':checked')) {
                   numero =1;
                    $.ajax({
                            type: 'POST',
                            async: false,
                            dataType: 'json',
                            data: {valor: numero , empresa: $('input#empresaId').val(), n: 10},
                            url: Routing.generate('edit_empresa'),
                            success: function (data)
                            {
                                 Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Modificando datos, espere un momento'
                                    });
                              location.reload();
                              
                            },
                            error: function (xhr, status)
                            {
                               Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });

                            }
                    });
                   
                }else{
                    numero =0;
                    $.ajax({
                            type: 'POST',
                            async: false,
                            dataType: 'json',
                            data: {valor: numero , empresa: $('input#empresaId').val(), n: 10},
                            url: Routing.generate('edit_empresa'),
                            success: function (data)
                            {
                                Lobibox.notify("success", {
                                        size: 'mini',
                                        msg: 'Modificando datos, espere un momento'
                                    });
                                location.reload();
                              
                            },
                            error: function (xhr, status)
                            {
                                Lobibox.notify("danger", {
                                        size: 'mini',
                                        msg: 'Lo sentimos, ocurrio un error'
                                    });

                            }
                    });
                   
                    
                    
                }
              
    });    
    
    
   $("#btnespecialidad").click(function () {
     
        if ($("#div01").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('especialidaE'),
                data: {hPersona: $('input#empresaId').val()},
                //   async: false,
                //  dataType: 'json',

                success: function (data)
                {
                    // $("#contenido").append(data);
                    div = '<div class="nueva-Experiencia" id="div01"  style="background-color: #f4f4f4; border: 1px solid #e0e0e0;">' + data + '</div>';
                    $("#contenido").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });
    
    
    
    
    
    
    
    
    
    
    

});


 function addEspecialida()
{
    
     $("#fEspecialida").submit();
    if (DataEspecialida.length > 0)
    {
        // $("#div01").remove();
        $("#contenido").empty();
        var Esp, n = 0;
        var datos;
        $.ajax({
            type: "GET",
            url: Routing.generate('subespecialidad'),
            data: {hPersona: $('input#empresaId').val(), DataEspecialida: DataEspecialida, dato: $("#fEspecialida").serialize()},
            async: false,
            dataType: 'json',
          success: function (data)
           {
               if (data.msj !== false) {
                   $.each($(data.Esp), function (indice, val) {

                       Esp = val.id;
                       n = n + 1;
                       datos = '<div class="form-group">';
                       datos += '<div class="row">';
                       datos += '<div class="col-xs-12" style="margin-top: .5em; margin-bottom: .5em;"><ul class="prob">';
                       datos += ' <li><strong><p class="sans">' + val.nombre.toUpperCase() + '<p class="sans" ></strong></li>';
                       datos += ' <li><p class="sans" style="text-align:justify;" >' + val.descripcion + '</p></strong></li>';
                       datos += '</ul></div>';
                       if ((n > 0) && (n % 3 === 0))
                       {
                           datos += '<div class="clearfix"></div>';
                       }
                       datos += '</div></div>';

                       $("#div01").remove();
                       $("#contenido").append(datos);
                   });
                   var boton = '<div><p><script type="text/javascript">';
                               datos += '$("#Idioma").hover(';
                               datos += 'function(){';
                               datos += '$(this).append($(\'<span><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
                               datos += 'onclick="editIdioma()"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn btnperfil" ';
                               datos += 'onclick="editIdioma()">&nbsp;Eliminar</i></span>\'));';
                               datos += '},function(){';
                               datos += '$(this).find("span:last").remove();';
                               datos += '});';
                               datos += '</script></p></div>';
                               $("#contenido").append(boton);
                   Lobibox.notify("success", {
                       size: 'mini',
                       msg: data.msj
                   });

               } else {
                   Lobibox.notify("warning", {
                       size: 'mini',
                       msg: data.error
                   });
               }

               $("#div01").remove();
               $("#contenido").append(data);

           },
            error: function (errors)
            {

            }

        });
    } else
    {
        $("#contenido").empty();
        $("#contenido").append(datosMostrados);
    }
}

           function editEspe()
{
   $('#btnespecialidad').click();
}

        
                     
                     
                $(function editarFoto() {
                  $('.image-editor').cropit();
                  $('form').submit(function() {
                     
                   
                    // Move cropped image data to hidden input
                    var imageData = $('.image-editor').cropit('export');
                    $('.hidden-image-data').val(imageData);
                    //alert(imageData);
                    var usuario = $("#usuario").val();
                    var empresaId = $("#empresaId").val();
                    $("#gif").show();
                    //Aqui tiene que ir el ajax
                        $.ajax({
                            type: 'POST',
                            async: false,
                            dataType: 'json',
                            data: {imageDatas: imageData, usuario: usuario,empresaId:empresaId},
                            url: Routing.generate('ingresar_foto'),
                            success: function (data)
                            {
                               if(data.estado==true){
                                   
                                Lobibox.notify("success", {
                                                       size: 'mini',
                                                       msg: 'Datos modificados con exito'
                                                   });
                                 $("#prev").attr('src', "/abgsistema/web/"+data.direccion);
                                 $("#gif").hide();
                                 $(".close").click();
                                
                                 

                           }
                            },
                            error: function (xhr, status)
                            {
                                 Lobibox.notify("danger", {
                                                        size: 'mini',
                                                        msg: 'Lo sentimos, ocurrio un error'
                                                    });
                                    $("#gif").hide();
                                    $(".close").click();
                                
                            }
                        });
                        
                    // Print HTTP request params
                    var formValue = $(this).serialize();
                    $('#result-data').text(formValue);
                    // Prevent the form from actually submitting
                    return false;
                  });
                });

              