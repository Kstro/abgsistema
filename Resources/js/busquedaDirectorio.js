

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $('#searchBox').focus();
    var url = Routing.generate("busqueda_ab_input");
    var urlCiudad = Routing.generate("depto_ciudad");
    $(document).on('click', '.submitsearch', function () {
        var busqueda = $('#search').val();
        console.log(busqueda);
        window.location = Routing.generate("directorio_index") + "?busqueda=" + busqueda;
    });

    $(document).on('click', '#buscar', function () {
        var busqueda = $('#search').val();
        var ciudad = $('#searchInputDept').val();


        window.location = Routing.generate("directorio_index") + "?busqueda=" + busqueda + "&ciu=" + ciudad;
    });


    $('#searchBox').autocomplete({
        serviceUrl: url,
    });


    $('#searchInputDept').autocomplete({
        serviceUrl: urlCiudad,
    });



    $(document).on('keyup', '#searchBox, #searchBoxMov, #searchInputDept', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var busqueda = $('#searchBox').val();
            var ciu = $('#searchInputDept').val();
            buscar(busqueda, inicio, longitud, paginaActual,orderBy);
            //window.location = Routing.generate("directorio_index") + "?busqueda=" + busqueda + "&ciu=" + ciu;
        }
        return false;
    });
    
    $(document).on('input', '#searchBox, #searchInputDept', function (event) {
        $('#randomDir').val('');
    });
});

