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
            var busqueda = $('.searching').val();
            console.log(busqueda);
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda;
        });
        
        $(document).on('click','#buscar', function(){
            var busqueda = $('.searchInput').val();
            var ciudad = $('.ciudad').val();
            console.log(busqueda);
            console.log(ciudad);
            //return false;
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu="+ciudad;
        });
        $(document).on('click','#buscarMov', function(){
            var busqueda = $('.searchInputMov').val();
            var ciudad = $('.ciudadMov').val();
            console.log(busqueda);
            console.log(ciudad);
            //return false;
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu="+ciudad;
        });
        
        
        $('.searchInput,.searchInputMov').autocomplete({
            serviceUrl: url,
        });
        
        
        $('.ciudad,.ciudadMov').autocomplete({
            serviceUrl: urlCiudad,
        });

        

        $(document).on('keyup','.searchInput, .ciudad',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var busqueda = $('.searchInput').val();
                var ciu= $('.ciudad').val();

                window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu="+ciu;
            }
        });
        $(document).on('keyup','.searchInputMov, .ciudadMov',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var busqueda = $('.searchInputMov').val();
                var ciu= $('.ciudadMov').val();

                window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu="+ciu;
            }
        });
    });
