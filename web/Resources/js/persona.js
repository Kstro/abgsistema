  
   var trs,trs2;
   var datosFormacion=[];
   var formacion= [];
   var datosExperiencia=[];
   var experiencia= [];
   var datosIngresos=[];
   var ingresos=[];
   var deduccion=[];
   var datosDeduccion=[];
 //   jQuery.noConflict();
 /*   jQuery(function($){
    $("#txtdui").mask("99999999-9");
   $("#txtnit").mask("9999-999999-999-9");
   $("#txtfijo").mask("9999-9999");
   $("#txtmovil").mask("9999-9999");
     
});*/

 
 $(document).on('ready',function(){
     
     
       /*       
          $('#sEstado').select2();
             $('#sCiudad').select2();
             */
             $( "#enfiarf" ).click(function() {
          $.ajax({
                type: "GET",
                url:Routing.generate('usuario'),     
                async: false,
                dataType: 'json',
                data:{dato:$("#fdatos").serialize()},
                success: function(data) 
                { 
                  var url=Routing.generate('perfil',{username:data.username});
                  window.open(url,"_self");                   
                },
                  error : function(errors) 
                  {
                        var msg = "";
                        if(errors && errors.responseText) { 
                            msg = errors.responseText;
                        } else {
                            $.each(errors, function(k, v) { msg += v+"<br>"; });
                        } 
                        $("#msg").removeClass("alert-success").addClass("alert-error")
                                 .html(msg).show();   
                  alert(msg);
                },
            });
         });

             /*
                 var estado;
                 var tipoEmpleo;
                 var sucursal;
         
             $('#fechaNacimiento').Zebra_DatePicker({
                            months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            format: 'd-m-Y',
                            direction:false,
                            show_clear_date:false,
                            show_select_today: "Hoy",
                        });
              $('#fechaIniC').Zebra_DatePicker({
                            months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            format: 'd-m-Y',
                            direction:false,
                            show_clear_date:false,
                            show_select_today: "Hoy",
                            pair: $('#fechaFinC'),
                        });
            $('#fechaFinC').Zebra_DatePicker({
                            months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            format: 'd-m-Y',
                           // direction:false,
                            show_clear_date:false,
                            show_select_today: "Hoy",
                        });
                        
                         $('#fechaIniPuesto').Zebra_DatePicker({
                            months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            format: 'd-m-Y',
                            direction:false,
                            show_clear_date:false,
                            show_select_today: "Hoy",
                            pair: $('#fechaFinC'),
                        });
            $('#fechaFinPuesto').Zebra_DatePicker({
                            months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            format: 'd-m-Y',
                           // direction:false,
                            show_clear_date:false,
                            show_select_today: "Hoy",
                        });
    
                 
                     
    */
    /*
     $("#divEsatdo").empty();
    $.ajax({
                async: false,
                dataType: 'json',
                url:Routing.generate('datos_esatdo'),
                success: function(data) 
                {
               
                    estado = '<label for="ejemplo_archivo_1">Estado</label>\
                            <select class="form-control"  name="sEstado" id="sEstado" onChange=ciudad()>';
                   estado+= '<option value="0">Seleccione Estado --></option>';
                     $.each(data.ArrayEstado, function (indice, val) {
                         estado+= '<option value="' + val.id + '">' + val.nombre + '</option>';
                     });
                     estado += ' </select></div></div> ';
                      $("#divEsatdo").append(estado);
                }
               
            });
            //  Datos de tipo de empleo
             $("#divTipoEmpleo").empty();
             $.ajax({
                async: false,
                dataType: 'json',
                url:Routing.generate('datos_tipo_empleo'),
                success: function(data) 
                {
                    tipoEmpleo = '<label for="ejemplo_archivo_1">Tipo empleo</label>\
                            <select class="form-control"  name="divTipoEmpleo" id="divTipoEmpleo">';
                   tipoEmpleo+= '<option value="0">Seleccione Tipo empleo --></option>';
                     $.each(data.ArrayTipoemp, function (indice, val) {
                         tipoEmpleo+= '<option value="' + val.id + '">' + val.nombre + '</option>';
                     });
                     tipoEmpleo += ' </select></div></div> ';
                      $("#divTipoEmpleo").append(tipoEmpleo);
                }
               
            });
             
               //  Datos de sucursal
             $("#divSucursal").empty(); $("#button").click( function()
           {
             alert('button clicked');
           }
        );
             $.ajax({
                async: false,
                dataType: 'json',
                url:Routing.generate('datos_sucursal'),
                success: function(data) 
                {
                    sucursal = '<label for="ejemplo_archivo_1">Sucuesal</label>\
                            <select class="form-control"  name="SSucursal" id="SSucursal" onChange="departamentoEmpresa()">';
                   sucursal+= '<option value="0">Seleccione Tipo empleo --></option>';
                     $.each(data.ArrayTipoemp, function (indice, val) {
                         sucursal+= '<option value="' + val.id + '">' + val.nombre + '</option>';
                     });
                     sucursal += ' </select></div></div> ';
                      $("#divSucursal").append(sucursal);
                }
               
            });
                  */       
                 
                   
        if($(":hidden#hIdPersona").val())
        {         
           var DataExperiencia;
           var DataFormacion;
           var n=0;
           var m=0;
            $("#trExperiencia").empty();
            $("#trFormacion").empty();
         $.ajax({
                async: false,
                dataType: 'json',
                url:Routing.generate('formacion_academica'),              
                data:{idPersona:$(":hidden#hIdPersona").val()},
                success: function(data) 
                {
                    $.each(data.ArrayForm, function (indice, val) {
                                  
                        DataFormacion= '<tr id="'+val.id+'">';
                        formacion.push(val.id);
                        DataFormacion+= '<td>'+val.inst+'</td>';
                        formacion.push(val.inst);
                        DataFormacion+= '<td>'+val.nivel+'</td>';
                        formacion.push(val.nivel);
                        DataFormacion+= '<td >'+val.anio+'</td>';
                        formacion.push(val.anio);
                        DataFormacion+= '<td >'+val.cal+'</td>';
                        formacion.push(val.cal);
                        DataFormacion+= '<td >'+val.titulo+'</td>';
                        formacion.push(val.titulo);
                        DataFormacion += '<td><a onClick="eliminarFila('+val.id+','+n+')"><i class="fa fa-trash-o fa-lg"></i></a> </td>';
                        DataFormacion+= '</tr>';
                       $("#trFormacion").append(DataFormacion);
                       datosFormacion.push(formacion);
                       formacion=[];
                       n++;
                     }); 
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                },
            });
            
         $.ajax({
                async: false,
                dataType: 'json',
                url:Routing.generate('experiencia_lab'),              
                data:{idPersona:$(":hidden#hIdPersona").val()},
                success: function(data) 
                {
                    $.each(data.ArrayExp, function (indice, val) {
                                  
                        DataExperiencia= '<tr id="'+val.id+'">';
                        experiencia.push(val.id);
                        DataExperiencia+= '<td>'+val.compania+'</td>';
                        experiencia.push(val.compania);
                        DataExperiencia+= '<td>'+val.puesto+'</td>';
                        experiencia.push(val.puesto);
                        DataExperiencia+= '<td >'+val.salario+'</td>';
                        experiencia.push(val.salario);
                        DataExperiencia+= '<td >'+val.contacto+'</td>';
                        experiencia.push(val.contacto);
                           DataExperiencia+= '<td >'+val.tel+'</td>';
                        experiencia.push(val.tel);
                        DataExperiencia+= '<td >'+val.finicio+'</td>';
                        experiencia.push(val.finicio);
                         DataExperiencia+= '<td >'+val.ffin+'</td>';
                        experiencia.push(val.ffin);
                        DataExperiencia += '<td><a onClick="eliminarFilaExperiencia('+val.id+','+m+')"><i class="fa fa-trash-o fa-lg"></i></a> </td>';
                        DataExperiencia+= '</tr>';
                       $("#trExperiencia").append(DataExperiencia);
                       datosExperiencia.push(experiencia);
                       experiencia=[];
                        m++;
                     }); 
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                },
            });
            
        }

   });

    /*    function eliminarFila(id)
{   
   $('#tabla tr#'+id).remove();
   formacion.splice(id,1);
   datosFormacion.splice(id,1);
    console.log(id);
   console.log( formacion);
   console.log(datosFormacion);
}*/
function departamentoEmpresa()
{
    var Dept;
     //  Datos de departamentos de la sucursal
             $("#divDepartamento").empty();
             $.ajax({
                async: false,
                dataType: 'json',
                data: {idSucursal: $("#SSucursal").prop('selectedIndex')},
                url:Routing.generate('datos_dep_suc'),
                success: function(data) 
                {
                    Dept = '<label for="ejemplo_archivo_1">Departamento</label>\
                            <select class="form-control"  name="SDepartamento" id="SDepartamento" onChange="puestoDept()">';
                   Dept+= '<option value="0">Seleccione Tipo empleo --></option>';
                     $.each(data.ArrayDep, function (indice, val) {
                         Dept+= '<option value="' + val.id + '">' + val.nombre + '</option>';
                     });
                     Dept += ' </select></div></div> ';
                      $("#divDepartamento").append(Dept);
                }   
            });          
}

function puestoDept()
{
    var puesto;
     //  Datos de departamentos de la sucursal
             $("#divPuesto").empty();
     
             $.ajax({
                async: false,
                dataType: 'json',
                data: {idDepartamento: $("#SDepartamento").prop('selectedIndex')},
                url:Routing.generate('datos_puesto'),
                success: function(data) 
                {
                    puesto = '<label for="ejemplo_archivo_1">Puesto de trabajo</label>\
                            <select class="form-control"  name="SPuesto" id="SPuesto">';
                   puesto+= '<option >Seleccione Tipo empleo</option>';
                     $.each(data.ArrayDep, function (indice, val) {
                         puesto+= '<option value="' + val.id + '">' + val.nombre + '</option>';
                     });
                     puesto += ' </select></div></div> ';
                      $("#divPuesto").append(puesto);
                }   
            });          
}

 function ciudad()
  {
   
     
         
         
         var Dataciudad;
          $("#divCiudad").empty();
    $.ajax({
                async: false,
                dataType: 'json',
               data: {idEstado: $("#sEstado").prop('selectedIndex')},
                url:Routing.generate('datos_ciudad'),
                success: function(data) 
                {
                    
                    Dataciudad = '<label for="ejemplo_archivo_1">Ciudad</label>\
                            <select class="form-control"  name="sCiudad" id="sCiudad" >';
                     $.each(data.ArrayCiudad, function (indice, val) {
                       
                         Dataciudad+= '<option value="' + val.id + '">' + val.nombre + '</option>';
                     });
                     Dataciudad += ' </select></div></div> ';
                }
            });
             $("#divCiudad").append(Dataciudad);
                
      
  }
function enviarf()
{
   console.log($("#SEstado").val());
     jQuery(document).ready(function($) {
  
       $.ajax({
                type: "GET",
                url:Routing.generate('registrar_persona'),              
                data:{dato:$("#fdatos").serialize(),datosDetalleEmpleo:$("#fDetalleEmpleo").serialize(),datosPersonaPP:$("#fPeronaPP").serialize(),
                      datosContacto:$("#fContacto").serialize(),datosFormacion:datosFormacion,datosExperiencia:datosExperiencia},
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
           
 // Estructura de salario
 function enviarEstructura()
{
   console.log(datosIngresos);
     console.log(datosDeduccion);
     jQuery(document).ready(function($) {
  
       $.ajax({
                type: "GET",
                url:Routing.generate('registrar_estructura'),              
                data:{persona:$("#sPersona").prop('selectedIndex'),datosIngreso:datosIngresos,datosDeduccion:datosDeduccion},
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