var datacheck='';
jQuery(function ($) {
    /*$("#txtdui").mask("99999999-9");
     $("#txtnit").mask("9999-999999-999-9");
     $("#txtfijo").mask("9999-9999");*/

});
$(document).ready(function () {

    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';

$.ajax({
                async: false,
                dataType: 'json',
                url:Routing.generate('especialida'),
                success: function(data) 
                {                
                     $.each(data.esp, function (indice, val) {
                         datacheck+={value:+ val.id,text:+"'"+ val.nombre+"'"+},;
                     });
                }
               
            });
             console.log(datacheck.slice(0,-1)); 
    $('#txtMovil').editable();
    $('#txtOficina').editable();
    $('#txtDireccion').editable();
    $('#txtMunicipio').editable({
        // url: '/post',
        title: 'Departamento ',
        value: {
            SDepartamento: "Moscow",
            sEstado: "Lenina"
        }

    });

    $('#txtUnpoco').editable({
        row: 3
    });
 $('#checkEspecialida').editable({
     
  //value: [2, 3],
  source:[datacheck.slice(0,-1)],
  
  display: function(value, sourceData) {
   
    var html = [],
      checked = $.fn.editableutils.itemsByValue(value, sourceData);

    if (checked.length) {
      $.each(checked, function(i, v) {
        html.push($.fn.editableutils.escape(v.text));
      });
      $(this).html(html.join(', '));
    } else {
      $(this).empty();
    }
  }
});

    $('#txtMunicipio').on('save', function (e, params) {

        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {ciudad: $("#sEstado").val(), hPersona: $('input#hPersona').val(), n: 9},
            url: Routing.generate('edit_persona'),
            success: function (data)
            {
                $('#txtMunicipio').editable('setValue',data.ciu);
            },
            error: function (xhr, status)
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
            data: {movil: params.newValue, hPersona: $('input#hPersona').val(), n: 8},
            url: Routing.generate('edit_persona'),
            success: function (data)
            {
                $('#txtMovil').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
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
            data: {oficina: params.newValue, hPersona: $('input#hPersona').val(), n: 7},
            url: Routing.generate('edit_persona'),
            success: function (data)
            {
                $('#txtOficina').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
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
            data: {direccion: params.newValue, hPersona: $('input#hPersona').val(), n: 6},
            url: Routing.generate('edit_persona'),
            success: function (data)
            {
            },
            error: function (xhr, status)
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
            data: {descripcion: params.newValue, hPersona: $('input#hPersona').val(), n: 10},
            url: Routing.generate('edit_persona'),
            success: function (data)
            {

            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
            }
        });
    });

});