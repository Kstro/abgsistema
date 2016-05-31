/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $(document).ready(function(){
        $('#searchInput').focus();
        var url = Routing.generate("busqueda_ab_input");
        $(document).on('click','.submitsearch', function(){
            var busqueda = $('.search').val();
            console.log(busqueda);
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda;;

        });
        $('#searchInput').autocomplete({
            serviceUrl: url,

        
        });

        

        $(document).on('keyup','#searchInput',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var busqueda = $('.search').val();
                console.log(busqueda);
                window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda;;
            }
        });
    });
