
    $(document).ready(function(){
        $('#buscar_head').focus();
        var url = Routing.generate("busqueda_ab_input");
        $(document).on('click','#btnBuscarHead', function(){
            var busqueda = $('#buscar_head').val();
            window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu=";
        });
        $('#buscar_head').autocomplete({
            serviceUrl: url,
        });
        $(document).on('keyup','#buscar_head',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var busqueda = $('#buscar_head').val();
                window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda+"&ciu=";
            }
        });
    });