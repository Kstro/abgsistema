/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $(document).ready(function(){
        $('#searchInput').focus();
        var url = Routing.generate("busqueda_ab_input");
        var urlCiudad = Routing.generate("depto_ciudad");
        $(document).on('click','.submitsearch', function(){
            var busqueda = $('.search').val();
            console.log(busqueda);
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda;
        });
        
        $(document).on('click','#buscar', function(){
            var busqueda = $('.search').val();
            var ciudad = $('#ciudad').val();
           
           
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu="+ciudad;
        });
        
        
        $('#searchInput').autocomplete({
            serviceUrl: url,
        });
        
        
        $('#ciudad').autocomplete({
            serviceUrl: urlCiudad,
        });

        

        $(document).on('keyup','#searchInput, #ciudad',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var busqueda = $('#searchInput').val();
                 var ciu= $('#ciudad').val();

                window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu="+ciu;
            }
        });
    });
