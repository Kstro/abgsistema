
var arrayEspecialidad = [];
/*
 $(document).on('click', "#txtUnpoco", function (e) {
 
 $('.input-large').css("width", "490px");
 $('.input-large').css("height", "85px");
 $('.input-large').css("resize", "auto");
 
 $('.popover').css("width", "60%");
 $('.popover').css("height", "8%");
 $('.popover').css("resize", "auto");
 });*/
/*
 $(document).on('click', "#txtMunicipio", function (e) {
 
 
 
 });
 */
/*$(document).on('click', "#txtNombres", function (e) {
 $('.popover-content').css("width", "350px");
 $('.popover-content').css("height", "85px");
 $('.popover-content').css("resize", "auto");
 
 });
 */
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

  /*  $('#txtTituloProfesional').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        },
        source: [
            {value: '3', text: 'Doctorado'},
            {value: '4', text: 'Estudiante'},
            {value: '1', text: 'Licenciatura'},
            {value: '2', text: 'Magíster'}
        ]

    });*/
    $('#txtTituloU').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        }
    });

    $('#txtGenero').editable({
        validate: function (value) {
            if (value === "")
                return 'requerido';
        },
        source: [
            {value: 'F', text: 'Femenino'},
            {value: 'M', text: 'Masculino'}
        ]
    });

    $('#txtMovil').editable({
        validate: function (value) {
            if (value === "")
            {
                return 'requerido';
            } else if (value.length < 9)
            {
                return 'Formato no valido. ej. 7777-7777';
            }
        },
        type: 'text',
        name: 'zip',
        tpl: '   <input type="text" id ="zipiddemo" class="form-control    input-sm" style="padding-right: 24px;">'
    }).on('shown', function () {
        $("input#zipiddemo").mask("0000-0000");

    });
    $('#txtOficina').editable({
        validate: function (value) {
            if (value === "")
            {
                return 'requerido';
            } else if (value.length < 9)
            {
                return 'Formato no valido. ej. 2222-2222';
            }
        },
        type: 'text',
        name: 'zip',
        tpl: '   <input type="text" id ="zipiddemo" class="form-control    input-sm" style="padding-right: 24px;">'
    }).on('shown', function () {
        $("input#zipiddemo").mask("0000-0000");

    });
    $('#txtDireccion').editable({
        type: 'text',
        title: 'Enter username',
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
    $('#sitioweb2').editable(
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
    /*
     $('#txtMunicipio').editable({
     // url: '/post',
     title: 'Departamento ',
     value: {
     SDepartamento: "Moscow",
     sEstado: "Lenina"
     }
     
     });*/


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
/*
    $('#txtTituloProfesional').on('save', function (e, params) {
        if ((params.newValue["txtTituloProfesional"] === ""))
        {
        } else
        {

            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {tituloProfesional: params.newValue, hPersona: $('input#hPersona').val(), n: 3},
                url: Routing.generate('edit_persona'),
                success: function (data)
                {
          alert('Saved value: ' + params.newValue);
            $(this).editable('setValue', params.newValue);
       
           //    $('#txtTituloProfesional').editable({text:"vkjdasnvs"});
                },
                error: function (xhr, status)
                {
                    alert('Disculpe, existió un problema');
                }
            });
        }
    });*/
 /*   
  $('#txtTituloProfesional').on('hidden', function(e, reason) {

      
               $(this).editable('setValue', 'gsgsgs');

});*/

    $('#txtTituloU').on('save', function (e, params) {
        if ((params.newValue["txtTituloU"] === ""))
        {
        } else
        {

            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {tituloPuesto: params.newValue, hPersona: $('input#hPersona').val(), n: 13},
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


    $('#txtGenero').on('save', function (e, params) {

        if ((params.newValue["txtGenero"] === ""))
        {

        } else
        {

            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {genero: params.newValue, hPersona: $('input#hPersona').val(), n: 11},
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

    $('#sitioweb2').on('save', function (e, params) {
        //  $('#txtUrlPersonalizada').editable('validate');
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {sitio: params.newValue, hPersona: $('input#hPersona').val()},
            url: Routing.generate('sitio'),
            success: function (data)
            {
                $('#sitioweb').editable('setValue', params.newValue);

            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
            }
        });

    });
    /*
     $('#txtMunicipio').on('save', function (e, params) {
     
     $.ajax({
     type: 'POST',
     async: false,
     dataType: 'json',
     data: {ciudad: $("#sCiuda").val(), hPersona: $('input#hPersona').val(), n: 9},
     url: Routing.generate('edit_persona'),
     success: function (data)
     {
     //$("#txtMunicipio").text($("#sCiuda").val());
     
     $("#divMunicipiox").empty();
     $("#txtMunicipio").remove();
     var datos;
     datos = '<p style="color: 5555555; margin-left: 11px; font-size: 12px; margin-bottom: 5px;" class="sansli">';
     datos += '<a href="#" id="txtMunicipio" data-type="address" data-pk="1" data-title="Departamento, municipio" data-placement="right" class="editable editable-click" ><b>' + data.dept + '</b> |' + data.ciu + '</a></p>';
     $("#divMunicipiox").append(datos);
     
     $('#txtMunicipio').editable({
     title: 'Departamento ',
     value: {
     SDepartamento: "Moscow",
     sEstado: $("#sCiuda").val()
     }
     });
     },
     error: function (xhr, status)
     {
     alert('Disculpe, existió un problema');
     }
     });
     
     });*/

    $('#sitioweb').on('save', function (e, params) {

        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {sitio: params.newValue, hPersona: $('input#hPersona').val()},
            url: Routing.generate('sitio'),
            success: function (data)
            {
                $('#sitioweb2').editable('setValue', params.newValue);
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
