$(document).ready(function() {
//    $("#cerarModificarionUrl").hide();
//    $("#urlform").hide();
//    $("#urlformE").hide();
//    $("#siPermiso").hide();
//    $("#noPermiso").hide();
    
      $("#cmdInsertarUrlEmp").prop('disabled', true);
      $("#UrlPersonalizadaEmp").prop('disabled', true);
    
    
    
    $(document).on("click","#urlAncla",function() {
             $("#cerarModificarionUrl").show();
             $("#urlAncla").hide();

        var m =1;
        $.ajax({
                                    type: 'POST',
                                    async: false,
                                    dataType: 'json',
                                    data: {m:m},
                                    url: Routing.generate('evaluarPermisoUrlEmp'),
                                    success: function (data)
                                    {
                                       if(data.estado){
                                            $("#siPermiso").show();
                                            $("#cmdInsertarUrlEmp").prop('disabled', false);
                                            $("#UrlPersonalizadaEmp").prop('disabled', false);
                                            
                                             $("#urlformE").show();
                                             $("#urlform").show();
                                             $("#UrlPersonalizada").focus();
         
                                       }else{
                                            $("#noPermiso").show();
                                            $("#urlformE").show();
                                            $("#urlform").show();
                                             $("#UrlPersonalizada").focus();
            
                                       }
                                          
                                    },
                                    error: function (xhr, status)
                                    {
                                         
                                    }
                                });
        
	
	
});
    
      
    $(document).on("click","#cmdCancelarUrl",function() {
	
         $("#urlAncla").show();
        $("#cerarModificarionUrl").hide();
        $("#urlform").hide();
        $("#urlformE").hide();
         
        	
    });
      
    $(document).on("click","#cmdCancelarUrlEmp",function() {
	$("#urlAncla").show();
        $("#cerarModificarionUrl").hide();
        $("#urlform").hide();
        $("#urlformE").hide();
         
        	
    });
    
  
  //Para los Abogados
  $(document).on("click","#cmdInsertarUrl",function() {
	
        
        var url= $("#UrlPersonalizada").val();
        if(url!=''){
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {url:url},
                url: Routing.generate('validarUrlPersonalizada'),
                success: function (data)
                {
                       console.log(data);

                       if (data.registro){

                           if(data.estado){


                                $.ajax({
                                        type: 'POST',
                                        async: false,
                                        dataType: 'json',
                                        data: {url:url},
                                        url: Routing.generate('insertarUrl'),
                                        success: function (data)
                                        {
                                            if (data.estados){
                                                Lobibox.notify("success", {
                                                                    size: 'mini',
                                                                    msg: 'Url modificada con exito,espere un momento'
                                                                });  
                                              $("#urlform").hide();
                                              $("#UrlPersonalizada").val("");
                                            }

                                        },
                                        error: function (xhr, status)
                                        {
                                             Lobibox.notify("error", {
                                                                    size: 'mini',
                                                                    msg: 'Lo sentimos, ocurrio un error dentro del sistema'
                                                                });
                                                $("#UrlPersonalizada").val("");
                                        }
                                    });


                                }else{

                                      Lobibox.notify("error", {
                                                    size: 'mini',
                                                    msg: 'Lo sentimos, ese nombre de url ya existente, intente con otro'
                                                });
                                       $("#UrlPersonalizada").val("");         

                                    }


                        }else{

                               Lobibox.notify("error", {
                                             size: 'mini',
                                             msg: 'Lo sentimos,usted ya ha cambiado su URL el numero de veces pertmitido.'
                                         });
                               $("#UrlPersonalizada").val("");
                        }

                },
                error: function (xhr, status)
                {
                     Lobibox.notify("error", {
                                            size: 'mini',
                                            msg: 'Lo sentimos, ocurrio un error'
                                        });
                     $("#UrlPersonalizada").val("");
                }
            });
        }
        else{
            Lobibox.notify("info", {
                size: 'mini',
                msg: 'Debe ingresar una url!'
            });
        }
      
      
      
    });
  
  
  
  
   $(document).on("click","#cmdInsertarUrlEmp",function() {
	 
        if(url!=''){
            var url1= $("#UrlPersonalizadaEmp").val();
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {url:url1},
                url: Routing.generate('validarUrlPersonalizadaEmpresa'),
                success: function (data)
                {
                       console.log(data);

                       if (data.registro){

                           if(data.estado){


                                $.ajax({
                                        type: 'POST',
                                        async: false,
                                        dataType: 'json',
                                        data: {url:url1},
                                        url: Routing.generate('insertarUrlEmpresa'),
                                        success: function (data)
                                        {
                                            if (data.estados){
                                                Lobibox.notify("success", {
                                                                    size: 'mini',
                                                                    msg: 'Url modificada con exito,espere un momento'
                                                                });  
                                              $("#urlform").hide();
                                              $("#UrlPersonalizadaEmp").val("");
                                            }

                                        },
                                        error: function (xhr, status)
                                        {
                                             Lobibox.notify("error", {
                                                                    size: 'mini',
                                                                    msg: 'Lo sentimos, ocurrio un error dentro del sistema'
                                                                });
                                                $("#UrlPersonalizadaEmp").val("");
                                        }
                                    });


                                }else{

                                      Lobibox.notify("error", {
                                                    size: 'mini',
                                                    msg: 'Lo sentimos, ese nombre de url ya existente, intente con otro'
                                                });
                                       $("#UrlPersonalizadaEmp").val("");         

                                    }


                        }else{

                               Lobibox.notify("error", {
                                             size: 'mini',
                                             msg: 'Lo sentimos,usted ya ha cambiado su URL el numero de veces pertmitido.'
                                         });
                               $("#UrlPersonalizadaEmp").val("");
                        }

                },
                error: function (xhr, status)
                {
                     Lobibox.notify("error", {
                                            size: 'mini',
                                            msg: 'Lo sentimos, ocurrio un error'
                                        });
                     $("#UrlPersonalizadaEmp").val("");
                }
            });
    
        }
        else{
            Lobibox.notify("info", {
                size: 'mini',
                msg: 'Debe ingresar una url!'
            });
        }
      
      
    });
  
  
   $(document).on("click","#cerarModificarionUrl",function() {
        $("#urlAncla").show();
        $("#cerarModificarionUrl").hide();
        $("#urlform").hide();
        $("#urlformE").hide();
    });  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

    
 });