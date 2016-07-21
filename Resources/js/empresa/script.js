
var tipoEmpresa = [];

var trs, trs2, idEspS = null;
var Especialida = [];
var DataEspecialida = [];
var EspecialidaSelect = [];
var datos = "", datosMostrados = "";

$(document).ready(function () {

    $("#gif").hide();

    $(document).on("click", "#botonModal", function () {
        $("#idModalFoto").modal();


    });
    
  
/*
    $(document).on("submit", "#frmEmpresaUsuarioPersona", function (e) {
alert("Nuevo usuario");
console.log("bgsdsa");
        var estadoCorreo;
        e.preventDefault();
        frm = serializeToJson($(this).serializeArray());
        //Ajax que valida  el correo
        $.ajax({
            data: {
                frm: JSON.stringify(frm)
            },
            url: Routing.generate('validar_correo'),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
            
                data = jQuery.parseJSON(data);
                if (data == true) {
                    //Ajax de insersion de datos               
                    $.ajax({
                        data: {
                            frm: JSON.stringify(frm)
                        },
                        url: Routing.generate('ingresar_usuarioEmpresa'),
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) 
                        {
                            if (data.estado == true) {
                             //      var url = Routing.generate('confirma_cuenta');
                         //       var url = Routing.generate('perfil');
                                //var url = Routing.generate('abogado_login');
                           //   window.open(url, "_self");
                                Lobibox.notify("success", {
                                    size: 'mini',
                                    msg: 'Registro exitoso, espere un momento5555'
                                });
                            } else {

                                Lobibox.notify("error", {
                                    size: 'mini',
                                    msg: 'Error al ingresar los datos'
                                });
                            }
                            return false;
                        }
                    });
                } else {
                    Lobibox.notify("error", {
                        size: 'mini',
                        msg: 'Correo ya existente, intenete con otro sdbhjbwjdbwhwb.'
                    });

                }
            }
        });
    });
    
    */







    $('#txtMovil').editable({
          validate: function (value) {
            if (value === "")
            {return 'requerido';}
              else if(value.length<9)
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



// 

    $('#txtMovil').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {movil: params.newValue, empresa: $('input#empresaId').val(), n: 1},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });


            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });

    $('#txtFijo').editable({
          validate: function (value) {
            if (value === "")
            {return 'requerido';}
              else if(value.length<9)
        {
              return 'Formato no valido. ej. 2222-2222';
        }
        },
        type: 'text',
        name: 'zip',
        tpl: '   <input type="text" id ="telefonoFijo" class="form-control    input-sm" style="padding-right: 24px;">'
    }).on('shown', function () {
        $("input#telefonoFijo").mask("0000-0000");
    });

    $('#txtFijo').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {fijo: params.newValue, empresa: $('input#empresaId').val(), n: 2},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });

            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });







    $('#txtCorreoElectronico').editable();

    $('#txtCorreoElectronico').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {correoEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 3},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {

                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });
            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });


    $('#txtDireccion').editable();

    $('#txtDireccion').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {direccionEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 4},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {

                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');

            }
        });
    });



    $('#txtSitioWeb').editable();

    $('#txtSitioWeb').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {sitiowebEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 5},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {

                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });


                $('#txtSitioWeb').editable({
                    value: data.tel
                });
            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });




    $("#txtNombreEmpresa").editable({
        tpl: '<input type="text" maxlength="45">'

    });
    $('#txtNombreEmpresa').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {nombreEmpresa: params.newValue, empresa: $('input#empresaId').val(), n: 0},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {

                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });


            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });

    //Funcion que cambia el color del banner

    var exa;
    $('#colorSelector').ColorPicker({
        color: '#4ec24e',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            $(".colorpicker").css('float', 'right');
            return true;
        },
        onHide: function (colpkr, hex) {
            $(colpkr).fadeOut(500);
//                alert(exa);
            var empresaId = $("#empresaIdColor").val();
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {colorEmpresa: exa, idEmpresa: $('input#empresaIdColor').val()},
                url: Routing.generate('edit_color'),
                success: function (data)
                {

                    $('#colorBanner div').css('backgroundColor', '#' + data.color);
                    Lobibox.notify("success", {
                        size: 'mini',
                        msg: 'Datos modificados con exito'
                    });


                },
                error: function (xhr, status)
                {
                    Lobibox.notify("danger", {
                        size: 'mini',
                        msg: 'Lo sentimos, ocurrio un error'
                    });

                }
            });


            return false;
        },
        onChange: function (hsb, hex, rgb) {
            $('#colorSelector div').css('backgroundColor', '#' + hex);
            exa = '#' + hex;
        }
    });



    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: Routing.generate('mostarTipoEmpresa'),
        success: function (data)
        {


            $.each(data.valores, function (i, values) {


                tipoEmpresa.push(values['nombre']);

            });



            console.log(tipoEmpresa);

        }

    });





    $('#tipoEmpresa').editable({
        value: 'Buffet',
        source: eval(tipoEmpresa)

    });

    $('#tipoEmpresa').on('save', function (e, params) {



        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {tipoEmpresas: params.newValue, empresa: $('input#empresaId').val(), n: 6},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {

                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });
            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });



    $("#txtFechaFundacion").editable({
          validate: function (value) {
            if (value === "")
            {return 'requerido';}
              else if(value.length<4)
        {
              return 'Formato no valido. ej. 2016';
        }
        },
        type: 'text',
        name: 'zip',
        tpl: '   <input type="text" id ="txtFechaFundacion" class="form-control    input-sm" style="padding-right: 24px;">'
    }).on('shown', function () {
        $("input#txtFechaFundacion").mask("0000");
    });
    
    
    $('#txtFechaFundacion').on('save', function (e, params) {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {anhoFundacion: params.newValue, empresa: $('input#empresaId').val(), n: 7},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {
                console.log(data);

                if (data.valor) {

                    Lobibox.notify("succes", {
                        size: 'mini',
                        msg: 'Año ingresado con exito'
                    });

                } else {
                    Lobibox.notify("danger", {
                        size: 'mini',
                        msg: 'No se puede insertar un año invalido'
                    });

                }




            },
            error: function (xhr, status)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });
    });



    $('#cantidadEmpleados').editable({
        value: 'De 1 a 10 empleados',
        source: [
            {value: 'De 1 a 10 empleados', text: 'De 1 a 10 empleados'},
            {value: 'De 11 a 50 empleados', text: 'De 11 a 50 empleados'},
            {value: 'De 51 a 100 empleados', text: 'De 51 a 100 empleados'},
            {value: 'Mas 100 empleados', text: 'Mas 100 empleados'}
        ]

    });


    $('#cantidadEmpleados').on('save', function (e, params) {



        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {cantidadEmpleados: params.newValue, empresa: $('input#empresaId').val(), n: 8},
            url: Routing.generate('edit_empresa'),
            success: function (data)
            {

                Lobibox.notify("success", {
                    size: 'mini',
                    msg: 'Datos modificados con exito'
                });
            },
            error: function (xhr, status)
            {
                Lobibox.notify("danger", {
                    size: 'mini',
                    msg: 'Lo sentimos, ocurrio un error'
                });

            }
        });


    });

    $('#somecomponent').locationpicker();

    var elemento = $("#elemento").val();

    if (elemento == 1) {
        $(".listarEmpleados").prop('checked', true);
        console.log(elemento);

    } else {

        $(".listarEmpleados").prop('checked', false);
        console.log(elemento);
    }





    $(document).on("click", ".listarEmpleados", function () {

        if ($(this).is(':checked')) {
            numero = 1;
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {valor: numero, empresa: $('input#empresaId').val(), n: 10},
                url: Routing.generate('edit_empresa'),
                success: function (data)
                {
                    Lobibox.notify("success", {
                        size: 'mini',
                        msg: 'Modificando datos, espere un momento'
                    });
                    location.reload();

                },
                error: function (xhr, status)
                {
                    Lobibox.notify("danger", {
                        size: 'mini',
                        msg: 'Lo sentimos, ocurrio un error'
                    });

                }
            });

        } else {
            numero = 0;
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {valor: numero, empresa: $('input#empresaId').val(), n: 10},
                url: Routing.generate('edit_empresa'),
                success: function (data)
                {
                    Lobibox.notify("success", {
                        size: 'mini',
                        msg: 'Modificando datos, espere un momento'
                    });
                    location.reload();

                },
                error: function (xhr, status)
                {
                    Lobibox.notify("danger", {
                        size: 'mini',
                        msg: 'Lo sentimos, ocurrio un error'
                    });

                }
            });



        }

    });


    $("#btnespecialidadEmp").click(function () {

        if ($("#div01").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('especialidad_emp'),
                data: {empresaId: $('input#empresaId').val()},
                
                success: function (data)
                {
                    // $("#contenido").append(data);
                    div = '<div class="nueva-Experiencia" id="div01"  style="background-color: #f4f4f4; border: 1px solid #e0e0e0;">' + data + '</div><br><br><br><br>';
                    $("#contenido").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });












});

function fil(idcheckbox)
{

    if (Especialida.indexOf(idcheckbox) === -1)
    {
       
            Especialida = [];
            DataEspecialida = [];
            $.each($('.Especialida'), function (indice, val) {
                if ($(this).is(':checked')) {
                    Especialida.push(parseInt($(this).attr('id')));
                    EspecialidaSelect.push(parseInt($(this).attr('id')));
                    EspecialidaSelect.push('txt' + parseInt($(this).attr('id')));

                    if (EspecialidaSelect.length % 2 === 0)
                    {
                        DataEspecialida.push(EspecialidaSelect);
                        EspecialidaSelect = [];
                    }
                    document.getElementById('div' + parseInt($(this).attr('id'))).style.display = 'block';
                } else {
                    document.getElementById('div' + parseInt($(this).attr('id'))).style.display = 'none';
                }
            });

    } else {

        Especialida = [];
        DataEspecialida = [];
        $.each($('.Especialida'), function (indice, val) {
            if ($(this).is(':checked')) {

                if (Especialida.indexOf(parseInt($(this).attr('name'))) === -1)
                {
                    Especialida.push(parseInt($(this).attr('name')));
                    EspecialidaSelect.push(parseInt($(this).attr('id')));
                    EspecialidaSelect.push('txt' + parseInt($(this).attr('id')));

                    if (EspecialidaSelect.length % 2 === 0)
                    {
                        DataEspecialida.push(EspecialidaSelect);
                        EspecialidaSelect = [];
                    }
                    document.getElementById('div' + parseInt($(this).attr('id'))).style.display = 'block';
                }

            } else {
                document.getElementById('div' + parseInt($(this).attr('id'))).style.display = 'none';
            }
        });
    }
}
function addEspecialidad()
{

    if (DataEspecialida.length > 0)
    {
        // $("#div01").remove();
        $("#contenido").empty();
        var Esp, n = 0;
        var datos;
        $.ajax({
            type: "POST",
            async: false,
            dataType: 'json',
            url: Routing.generate('subespecialidad'),
            data: {empresaId: $('input#empresaId').val(), DataEspecialida: DataEspecialida, dato: $("#fEspecialida").serialize()},
            success: function (data)
            {
                if (data.msj !== false) {
                    $.each($(data.Esp), function (indice, val) {

                        Esp = val.id;
                        n = n + 1;
                        datos = '<div class="form-group sans">';
                        datos += '<div class="row">';
                        datos += '<div class="col-xs-12" style="margin-top: .5em; margin-bottom: .5em;">';
                        datos += ' <strong><p class="sans">' + val.nombre.toUpperCase() + '<p class="sans" ></strong>';
                        datos += ' <p class="sans" style="text-align:justify;" >' + val.descripcion + '</p></strong>';
                        datos += '</div>';
                        if ((n > 0) && (n % 3 === 0))
                        {
                            datos += '<div class="clearfix"></div>';
                        }
                        datos += '</div></div>';

                        $("#div01").remove();
                        $("#contenido").append(datos);
                    });
                    var boton = '<div><p><script type="text/javascript">';
                    datos += '$("#Idioma").hover(';
                    datos += 'function(){';
                    datos += '$(this).append($(\'<span><a class="btn btn-primary sans btn-sm btn-flat " style="width:80px;"';
                    datos += 'onclick="editIdioma()"> Editar </a>';
                    datos += '</span>\'));';
                    datos += '},function(){';
                    datos += '$(this).find("span:last").remove();';
                    datos += '});';
                    datos += '</script></p></div>';
                    $("#contenido").append(boton);
                    Lobibox.notify("success", {
                        size: 'mini',
                        msg: data.msj
                    });

                } else {
                    Lobibox.notify("warning", {
                        size: 'mini',
                        msg: data.error
                    });
                }

                $("#div01").remove();
                $('div.divEspeEmp').children('br').remove();
                $("#contenido").append(data);

            },
            error: function (errors)
            {

            }

        });
    } else
    {
        // $("#contenido").empty();
        // $("#contenido").append(datosMostrados);
    }
}

function editEspeEmp()
{
    $('#btnespecialidadEmp').click();
}




//$(function editarFoto() {
//    $('.image-editor').cropit();
//    $('form').submit(function () {
//
//
//        // Move cropped image data to hidden input
//        var imageData = $('.image-editor').cropit('export');
//        $('.hidden-image-data').val(imageData);
//        //alert(imageData);
//        var usuario = $("#usuario").val();
//        var empresaId = $("#empresaId").val();
//        $("#gif").show();
//        //Aqui tiene que ir el ajax
//        $.ajax({
//            type: 'POST',
//            async: false,
//            dataType: 'json',
//            data: {imageDatas: imageData, usuario: usuario, empresaId: empresaId},
//            url: Routing.generate('ingresar_foto'),
//            success: function (data)
//            {
//                if (data.estado == true) {
//
//                    Lobibox.notify("success", {
//                        size: 'mini',
//                        msg: 'Datos modificados con exito'
//                    });
//                    $("#prev").attr('src', "/" + data.direccion);
//                    $("#gif").hide();
//                    $(".close").click();
//
//
//
//                }
//            },
//            error: function (xhr, status)
//            {
//                Lobibox.notify("danger", {
//                    size: 'mini',
//                    msg: 'Lo sentimos, ocurrio un error'
//                });
//                $("#gif").hide();
//                $(".close").click();
//
//            }
//        });
//
//        // Print HTTP request params
//        var formValue = $(this).serialize();
//        $('#result-data').text(formValue);
//        // Prevent the form from actually submitting
//        return false;
//    });
//});
//
//              
