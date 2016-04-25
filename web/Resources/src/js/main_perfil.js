
var arrayEspecialidad = [];

jQuery(function ($) {
    /*$("#txtdui").mask("99999999-9");
     $("#txtnit").mask("9999-999999-999-9");
     $("#txtfijo").mask("9999-9999");*/



});

$(document).on('click', "#txtUnpoco", function (e) {
    $('.input-large').css("width", "490px");
    $('.input-large').css("height", "85px");
    $('.input-large').css("resize", "auto");
/*
    $('.popover').css("width", "60%");
    $('.popover').css("height", "8%");
    $('.popover').css("resize", "auto");*/
});

$(document).ready(function () {

    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';
    var a = '';
    $.ajax({
        async: false,
        dataType: 'json',
        url: Routing.generate('especialida'),
        success: function (data)
        {
            $.each(data.esp, function (indice, val) {
                arrayEspecialidad.push(val.nombre);

            });
        }

    });

    //a = datacheck.slice(0, -2);
    // a=datacheck.slice(0,-2);
    // console.log(eval(datacheck.slice(0,-2)));

    $('#txtMovil').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }

    });
    $('#txtOficina').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }

    });
    $('#txtDireccion').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }

    });
    $('#txtCorreo').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }

    });
    $('#sitioweb').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }

    });
    $('#txtUrlPersonalizada').editable(
            {
                validate: function (value) {
                    if (value === "")
                        return 'requerido';
                }

            });
    $('#txtEducacion').editable(
            {
                validate: function (value) {
                    if (value === "")
                        return 'requerido';
                }

            });
    $('#txtMunicipio').editable({
        // url: '/post',
        title: 'Departamento ',
        value: {
            SDepartamento: "Moscow",
            sEstado: "Lenina"
        }
    });
    $('#txtNombres').editable();
    $('#txtUnpoco').editable({
        row: 4,
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }


    });



    $('#txtNombres').on('save', function (e, params) {

        if ((params.newValue["txtnombre"] == "") || (params.newValue["txtApellido"] == ""))
        {

        } else
        {
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {nombres: params.newValue, hPersona: $('input#hPersona').val(), n: 1},
                url: Routing.generate('edit_persona'),
                success: function (data)
                {

                },
                error: function (xhr, status)
                {
                    alert('Disculpe, existió un problema');
                }
            });
        }
    });
    $('#checkEspecialida').editable({
        source: eval(arrayEspecialidad),
        display: function (value, sourceData) {

            var html = [],
                    checked = $.fn.editableutils.itemsByValue(value, sourceData);

            if (checked.length) {
                $.each(checked, function (i, v) {
                    html.push($.fn.editableutils.escape(v.text));
                });
                $(this).html(html.join(', '));
            } else {
                $(this).empty();
            }
        }
    });

    /* $('#checkEspecialida').on('save', function (e, params) {
     if (params.newValue.length > 3)
     {
     Lobibox.notify("warning", {
     size: 'mini',
     msg: 'Se deben seleccionar maxino 3 especialidades.'
     });
     }
     console.log(params.newValue.length);
     console.log(params.newValue);
     
     $.ajax({
     type: 'POST',
     async: false,
     dataType: 'json',
     data: {ciudad: $("#sEstado").val(), hPersona: $('input#hPersona').val(), n: 9},
     url: Routing.generate('edit_persona'),
     success: function (data)
     {
     $('#txtMunicipio').editable('setValue', data.ciu);
     },
     error: function (xhr, status)
     {
     alert('Disculpe, existió un problema');
     }
     });
     
     });*/

    $('#txtUrlPersonalizada').on('save', function (e, params) {
        //  $('#txtUrlPersonalizada').editable('validate');
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {url: params.newValue, hPersona: $('input#hPersona').val()},
            url: Routing.generate('url_persona'),
            success: function (data)
            {

            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
            }
        });

    });

    $('#txtMunicipio').on('save', function (e, params) {

        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {ciudad: $("#sCiuda").val(), hPersona: $('input#hPersona').val(), n: 9},
            url: Routing.generate('edit_persona'),
            success: function (data)
            {

                $("#divMunicipiox").empty();
                var datos;
                datos = '<p style="color: 5555555; margin-left: 11px; font-size: 12px; margin-bottom: 5px;" class="sansli"><a href="#" id="txtMunicipio" data-type="address" data-pk="1" data-title="Departamento, municipio" data-placement="right" class="editable editable-click" ><b>' + data.dept + '</b> |' + data.ciu + '</a></p>';
                $("#divMunicipiox").append(datos);
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
            }
        });

    });

    $('#sitioweb').on('save', function (e, params) {

        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {sitio: params.newValue, hPersona: $('input#hPersona').val()},
            url: Routing.generate('sitio'),
            success: function (data)
            {
                //$('#sitioweb').editable('setValue', data.sitio);
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
    $('#txtCorreo').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {correo: params.newValue, hPersona: $('input#hPersona').val(), n: 5},
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