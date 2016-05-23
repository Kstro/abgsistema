$(document).ready(function() {    
    var contenedor = $('.pos1');
    var tiempo = 5000;
    contenedor.css({'background-image':'url(http://hidefwalls.com/wp-content/g/hd-2/lion_hd_wallpaper.jpg)'}); 

    function image(){
        setTimeout(function() {
            contenedor.fadeTo('slow', 0.3, function() {
                $(this).css({
                    'background-image':'url(http://hidefwalls.com/wp-content/g/hd-2/lion_hd_wallpaper.jpg)'
                }); 
                otra_imagen();
            }).fadeTo('slow', 1); 
        },tiempo); 
    }

    function otra_imagen(){
        setTimeout(function() {
        contenedor.fadeTo('slow', 0.3, function() {
            $(this).css({
                'background-image':'url(http://www.fondosypantallas.com/wp-content/uploads/2010/02/HD_Wallpapers_Wide_Pack__21__30_.jpg)'
            }); 
            otra_img();
        }).fadeTo('slow', 1); },tiempo);
    }

    function otra_img(){
        setTimeout(function() {
            contenedor.fadeTo('slow', 0.3, function() {
                $(this).css({
                    'background-image':'url(http://newevolutiondesigns.com/images/freebies/nature-hd-background-25.jpg)'
                }); 
                image();
            }).fadeTo('slow', 1); 
        },tiempo);
    }

    otra_imagen();
});