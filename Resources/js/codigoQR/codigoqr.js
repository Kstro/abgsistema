 var dataImagen="";
$(document).ready(function() {

    
    
//    $("#formQR").hide();
//    $("#exportarImagen").hide();
//    $("#carncelarQR").hide();
    
    
 });
    $(document).on("click","#idAqrContenedor",function() {
        var codqr = document.getElementById('mostrar_qr');
        codqr.style.display = 'block';
        
        $("#idAqrContenedor").hide();
        $("#carncelarQR").show();
        $("#formQR").show();

         var x=1;
                 $.ajax({
                                    type: 'POST',
                                    async: false,
                                    dataType: 'json',
                                    data: {x:x},
                                    url: Routing.generate('mostrarUrl'),
                                    success: function (data)
                                    {
                                         
                                        
                                        $("#qrcode").qrcode({
                                          "render": "image",
                                          "size": 200,
                                          "color": "#3a3",
                                          "text": data.url
                                          });
                                          
                               
                                         
                                   dataImagen = $("#qrcode").children().attr('src');
                                   
                                   
                                      $("#ancla32jpg").attr("href",""+dataImagen+"");
                                      $("#ancla32jpg").attr("download","CODIGOQR.jpg"); 
                                  
                                    $("#ancla32svg").attr("href",""+dataImagen+"");
                                    $("#ancla32svg").attr("download","CODIGOQR.svg");
                                         
                                    $("#ancla32eps").attr("href",""+dataImagen+"");
                                    $("#ancla32eps").attr("download","CODIGOQR.eps");  
                                         
                                    $("#ancla32png").attr("href",""+dataImagen+"");
                                    $("#ancla32png").attr("download","CODIGOQR.png");
                                   
                                  
                                    $("#exportarImagen").show();
                                         
                                          
                                    },
                                    error: function (xhr, status)
                                    {
                                        
                                        
                                        
                                    }
                                });
         
                });
              
 
      $(document).on("click","#jpg",function() {
       
                $("#ancla32jpg").click();
 
                });
   
     $(document).on("click","#svg",function() {
       
	$(".descargasvg").click();
                                        
         
 
         
    });
    
    $(document).on("click","#eps",function() {
       
	 $(".descargaeps").click();
        
                                       
 
         
    });
    
    $(document).on("click","#png",function() {
       
	$(".descargapng").click();
    });
    
    $(document).on("click","#carncelarQR",function() {
         $('#formQR div').html('');
	 $("#formQR").hide();
         $("#exportarImagen").hide();
           $("#idAqrContenedor").show();
           $("#carncelarQR").hide();
         
    });
    
    
     
    
    
    
    
    
    
    
    
    
 
    
    

