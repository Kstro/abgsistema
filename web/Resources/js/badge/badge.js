$(document).ready(function() {
    //almomentoDelaCarga();
    
    
     $(document).on("click","#idMostrarBadge",function() {
         var codqr = document.getElementById('codigo_badge');
        codqr.style.display = 'block';
         
         var x =1;
         almomentodelclick();
          
          
          $.ajax({
                                    type: 'POST',
                                    async: false,
                                    dataType: 'json',
                                    data: {x:x},
                                    url: Routing.generate('mostrarUrlBadge'),
                                    success: function (data)
                                    {
                                         
                                     $("#anclaBadge").attr("href",""+data.direccionWeb+"");
                                     
                                     $("#badgeCode").val("<a href='"+data.direccionWeb+"' target='_blank'>"+ 
                                                                "<img src='http://www.abogados.com.sv/badge1.png'>"+ 
                                                             "</a>");
                                  
                                         
                                          
                                    },
                                    error: function (xhr, status)
                                    {
                                        
                                        
                                        
                                    }
                   });
          
          
          
          
         
         
     });
   
    $(document).on("click","#cancelarBadge",function() {
        
        almomentodeCerrar();
        
    });
   
   
   
});

function almomentodelclick(){
    
    
     $("#idMostrarBadge").hide(); 
          $("#badgeMuestra").show();
          $("#cancelarBadge").show();
          $("#formBadge").show();
}

function almomentoDelaCarga(){
     $("#cancelarBadge").hide();
    $("#formBadge").hide();
    $("#badgeMuestra").hide();
    
    
}


function almomentodeCerrar(){
    
    
     $("#idMostrarBadge").show(); 
          $("#badgeMuestra").hide();
          $("#cancelarBadge").hide();
          $("#formBadge").hide();
            $('#badgeCode').val("");
}
