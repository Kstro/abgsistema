
jQuery(function ($) {
    /*$("#txtdui").mask("99999999-9");
     $("#txtnit").mask("9999-999999-999-9");
     $("#txtfijo").mask("9999-9999");*/

});
$(document).ready(function () {

    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';


    $('#txtMovil').editable();
    $('#txtOficina').editable();
    $('#txtDireccion').editable();
    $('#txtMunicipio').editable({
        // url: '/post',
        title: 'Enter city, street and building #',
        value: {
            SDepartamento: "Moscow", 
            sEstado: "Lenina"
        }
    
    });
    
    $('#txtUnpoco').editable({
        row:3
    });
    
    $('#txtMunicipio').on('save', function (e, params) {
     // console.log(params.newValue());
   
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data:{ciudad:$("#sEstado").val(), hPersona:$('input#hPersona').val(),n:9},
            url: Routing.generate('edit_persona'),
            success: function(data) 
                {
            $(document).ready(function () {
                     $('#txtMunicipio').editable({
        // url: '/post',
        title: 'Enter city, street and building #',
        value: {
            SDepartamento: "Moscow", 
            sEstado: "Lenina"
        }
     });
    });
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                }
        });
         
    });
    
    $('#txtMovil').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data:{movil:params.newValue, hPersona:$('input#hPersona').val(),n:8},
            url: Routing.generate('edit_persona'),
            success: function(data) 
                {
                     $('#txtMovil').editable({
                         value:data.tel
                     });
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                }
        });
    });
    
   $('#txtOficina').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data:{oficina:params.newValue, hPersona:$('input#hPersona').val(),n:7},
            url: Routing.generate('edit_persona'),
            success: function(data) 
                {
                     $('#txtOficina').editable({
                         value:data.tel
                     });
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                }
        });
    });
   $('#txtDireccion').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data:{direccion:params.newValue, hPersona:$('input#hPersona').val(),n:6},
            url: Routing.generate('edit_persona'),
            success: function(data) 
                {
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                }
        });
    });
    
      
    $('#txtUnpoco').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data:{descripcion:params.newValue, hPersona:$('input#hPersona').val(),n:10},
            url: Routing.generate('edit_persona'),
            success: function(data) 
                {
                     
                },
                  error : function(xhr, status) 
                  {
                   alert('Disculpe, existió un problema');
                }
        });
    });
   
});