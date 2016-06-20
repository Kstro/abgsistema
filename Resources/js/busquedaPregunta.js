

$(document).ready(function () {
    var longitud = 8; //numero de registros que se van a mostrar
    var paginaActual = 1; //primer registro a mostrar
    var inicio = $('#inicioInput').val();
    $('#searchInput').focus();
    var url = Routing.generate("busqueda_pregunta_publica");
    /*   $(document).on('click','.submitsearch', function(){
     var busqueda = $('.search').val();
     console.log(busqueda);
     window.location = Routing.generate("directorio_index")+"?busqueda="+busqueda;;
     
     });*/
    $('#searchInput').autocomplete({
        serviceUrl: url,
    });

    var urlDept = Routing.generate("depto_ciudad");

    $('#searchInputDept').autocomplete({
        serviceUrl: urlDept,
    });

    $(document).on('keyup', '#searchInput', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
           
            var busqueda = $('#searchInput').val();
            var busquedaDept = $('#searchInputDept').val();
            buscar(busqueda, inicio, longitud, paginaActual, busquedaDept);
        }
    });

    $(document).on('keyup', '#searchInputDept', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var busquedaDept = $('#searchInputDept').val();
            var busqueda = $('#searchInput').val();

            buscar(busqueda, inicio, longitud, paginaActual, busquedaDept);
        }
    });
});


