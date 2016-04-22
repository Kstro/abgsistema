
var trs, trs2, idEspS = null;
var Especialida = [];
var SubEspecialida = [];

var datos = "", datosMostrados = "";
//   jQuery.noConflict();
/*   jQuery(function($){
 $("#txtdui").mask("99999999-9");
 $("#txtnit").mask("9999-999999-999-9");
 $("#txtfijo").mask("9999-9999");
 $("#txtmovil").mask("9999-9999");
 
 });*/

$(document).on('ready', function () {



    $("#enfiarf").click(function () {
        $.ajax({
            type: "GET",
            url: Routing.generate('usuario'),
            async: false,
            dataType: 'json',
            data: {dato: $("#fdatos").serialize()},
            success: function (data)
            {
                var url = Routing.generate('admin_abg', {username: data.username});
                window.open(url, "_self");
            },
            error: function (errors)
            {

            }
        });
    });



    $("#btnespecialida").click(function () {
        $("#contenido").empty();
        $.ajax({
            type: "GET",
            url: Routing.generate('especialida'),
            data: {hPersona: $('input#hPersona').val()},
            //   async: false,
            //  dataType: 'json',

            success: function (data)
            {
                $("#contenido").append(data);
                //  var url=Routing.generate('admin_abg',{username:data.username});
                // window.open(url,"_self");                   
            },
            error: function (errors)
            {

            }
        });
    });
    
    

    $("#btnExperiencia").click(function () {
        var div,divn;
        $.ajax({
            type: "GET",
            url: Routing.generate('from_experiencia'),
            data: {hPersona: $('input#hPersona').val()},
            //   async: false,
            //  dataType: 'json',

            success: function (data)
            {//$("#contenidoExp").empty();
  
             //  divn=$('div #contenidoExp').children('div').length;
                div='<div class="nueva-Experiencia " id="div1"  style="background-color: #f6f6f6; border: 1px solid #e0e0e0;">'+data+'</div>';
                $("#contenedorExp").append(div);
                               
            },
            error: function (errors)
            {

            }
        });
    });


});

function fil(idcheckbox, idEsp)
{
    if (Especialida.indexOf(idEsp) === -1)
    {
        if (Especialida.length < 3)
        {
            Especialida.push(idEsp);
            SubEspecialida = [];
            $.each($('.subEspecialida'), function (indice, val) {
                if ($(this).is(':checked')) {
                    SubEspecialida.push(parseInt($(this).attr('id')));
                }
            });
        } else {
            Lobibox.notify("warning", {
                size: 'mini',
                msg: 'Se deben seleccionar maxino 3 especialidades.'
            });
            $("#" + idcheckbox).prop('checked', false);
        }
    } else {
        SubEspecialida = [];
        Especialida = [];
        $.each($('.subEspecialida'), function (indice, val) {
            if ($(this).is(':checked')) {
                if (Especialida.indexOf(parseInt($(this).attr('name'))) === -1)
                {
                    Especialida.push(parseInt($(this).attr('name')));
                }
                SubEspecialida.push(parseInt($(this).attr('id')));
            }
        });
    }
}



function addSubEspecialida()
{
    if (SubEspecialida.length > 0)
    {
        var Esp, n = 0;
        $("#contenido").empty();
        $.ajax({
            type: "GET",
            url: Routing.generate('subespecialida'),
            data: {hPersona: $('input#hPersona').val(), SubEspecialida: SubEspecialida},
            async: false,
            dataType: 'json',
            success: function (data)
            {
                $.each($(data.Esp), function (indice, val) {

                    Esp = val.id;
                    n = n + 1;
                    datos = '<div class="col-xs-4"  style="margin-top: .5em; margin-bottom: .5em;">';
                    datos += '<strong><p class="sans" >' + val.nombre.toUpperCase() + '<p class="sans" ></strong>';
                    $.each($(data.subEsp), function (indice, val) {
                        if (Esp === val.idEsp)
                        {
                            datos += '<p class="sans" style="font-size: 13px;">' + val.nombre + '</p>';
                        }
                    });
                    datos += '</div>';
                    if ((n > 0) && (n % 3 === 0))
                    {
                        datos += '<div class="clearfix"></div>';
                    }

                    $("#contenido").append(datos);
                });

            },
            error: function (errors)
            {

            }

        });
    } else
    {
        $("#contenido").empty();
        $("#contenido").append(datosMostrados);
    }
}


function addExperiencia() {

    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: Routing.generate('registrar_experiencia'),
        data: {hPersona: $('input#hPersona').val(), dato: $("#fExperiencia").serialize()},
        success: function (data)
        {
            Lobibox.notify("success", {
                size: 'mini',
                msg: data.msj
            });

        },
        error: function (errors)
        {

        }
    });
}

function departamentoEmpresa()
{
    var Dept;
    //  Datos de departamentos de la sucursal
    $("#divDepartamento").empty();
    $.ajax({
        async: false,
        dataType: 'json',
        data: {idSucursal: $("#SSucursal").prop('selectedIndex')},
        url: Routing.generate('datos_dep_suc'),
        success: function (data)
        {
            Dept = '<label for="ejemplo_archivo_1">Departamento</label>\
                            <select class="form-control"  name="SDepartamento" id="SDepartamento" onChange="puestoDept()">';
            Dept += '<option value="0">Seleccione Tipo empleo --></option>';
            $.each(data.ArrayDep, function (indice, val) {
                Dept += '<option value="' + val.id + '">' + val.nombre + '</option>';
            });
            Dept += ' </select></div></div> ';
            $("#divDepartamento").append(Dept);
        }
    });
}

function puestoDept()
{
    var puesto;
    //  Datos de departamentos de la sucursal
    $("#divPuesto").empty();
    $.ajax({
        async: false,
        dataType: 'json',
        data: {idDepartamento: $("#SDepartamento").prop('selectedIndex')},
        url: Routing.generate('datos_puesto'),
        success: function (data)
        {
            puesto = '<label for="ejemplo_archivo_1">Puesto de trabajo</label>\
                            <select class="form-control"  name="SPuesto" id="SPuesto">';
            puesto += '<option >Seleccione Tipo empleo</option>';
            $.each(data.ArrayDep, function (indice, val) {
                puesto += '<option value="' + val.id + '">' + val.nombre + '</option>';
            });
            puesto += ' </select></div></div> ';
            $("#divPuesto").append(puesto);
        }
    });
}

function ciudad()
{




    var Dataciudad;
    $("#divCiudad").empty();
    $.ajax({
        async: false,
        dataType: 'json',
        data: {idEstado: $("#sEstado").prop('selectedIndex')},
        url: Routing.generate('datos_ciudad'),
        success: function (data)
        {

            Dataciudad = '<label for="ejemplo_archivo_1">Ciudad</label>\
                            <select class="form-control"  name="sCiudad" id="sCiudad" >';
            $.each(data.ArrayCiudad, function (indice, val) {

                Dataciudad += '<option value="' + val.id + '">' + val.nombre + '</option>';
            });
            Dataciudad += ' </select></div></div> ';
        }
    });
    $("#divCiudad").append(Dataciudad);
}

function enviarf()
{
    console.log($("#SEstado").val());
    jQuery(document).ready(function ($) {

        $.ajax({
            type: "GET",
            url: Routing.generate('registrar_persona'),
            data: {dato: $("#fdatos").serialize(), datosDetalleEmpleo: $("#fDetalleEmpleo").serialize(), datosPersonaPP: $("#fPeronaPP").serialize(),
                datosContacto: $("#fContacto").serialize(), datosFormacion: datosFormacion, datosExperiencia: datosExperiencia},
            success: function (data)
            {
                alert(data);
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
            },
        });
    });
    return false;
}



// Estructura de salario
function enviarEstructura()
{
    console.log(datosIngresos);
    console.log(datosDeduccion);
    jQuery(document).ready(function ($) {

        $.ajax({
            type: "GET",
            url: Routing.generate('registrar_estructura'),
            data: {persona: $("#sPersona").prop('selectedIndex'), datosIngreso: datosIngresos, datosDeduccion: datosDeduccion},
            success: function (data)
            {
                alert(data);
            },
            error: function (xhr, status)
            {
                alert('Disculpe, existió un problema');
            },
        });
    });
    return false;
}