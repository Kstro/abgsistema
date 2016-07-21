
var editExp = "", actExp, editEdu = "", idEspS = null;
var Especialida = [];
var DataEspecialida = [];
var Nidioma, Idioma = [];
var DatosIdiomas = [];
var EspecialidaSelect = [];
var datos = "", datosMostrados = "";
var persaEmpresa = 0;

$(document).on('ready', function () {
    $.ajax({
        type: "GET",
        url: Routing.generate('departamento'),
        async: false,
        dataType: 'json',
        success: function (data)
        {
            $("#divD").empty();
            estado = '<div class="col-sm-12 col-xs-12"  style="margin-bottom:7px;"> ';
            estado += '<select  width:100%; class="form-control input-sm select2"  name="sEstado" id="sEstado" onChange=ciudad()>';
            estado += '<option value="0">Seleccione Departamento</option>';
            $.each(data.depto, function (indice, val) {
                estado += '<option value="' + val.id + '">' + val.nombre + '</option>';
            });
            estado += ' </select>';
            estado += '<p style=" color: #FF0000;margin-top:2px;margin-bottom:0px;" id="pDepto"></p></div>';

            $("#divD").append(estado);
        },
        error: function (errors)
        {

        }
    });

    $("#btnExtracto").click(function () {

        document.getElementById('divExtracto').style.display = 'block';
    });


    $("#btnVerperfil").click(function () {
        $.ajax({
            type: "GET",
            url: Routing.generate('ver_perfil'),
            success: function (data)
            {
                $("#verPerfil").show();
                $("#verPerfil").html(data);
                $("#editarPerfil").hide();
            },
            error: function (errors)
            {

            }
        });
    });


    $('#myonoffswitch').change(function () {
        var estado;
        if ($('#myonoffswitch').is(":checked")) {
            estado = 1;
        } else {
            estado = 0;
        }

        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {estado: estado, hPersona: $('input#hPersona').val(), n: 2},
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

    $("#btnDatosContacto").click(function () {

        if ($("#div001").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('datos_contacto'),
                //  data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div001"  style="background-color: #FBFBFB;margin-bottom:-10px">' + data + '</div>';
                    $("#datosContacto").before(div);
                    $("#datosContacto").hide();

                },
                error: function (errors)
                {

                }
            });
        }
    });

    $("#btnUnpoco").click(function () {

        if ($("#div002").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('sobremi'),
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div002"  style="background-color: #FBFBFB;margin-bottom:-10px;">' + data + '</div>';
                    $("#conetenedorUnpoco").hide();
                    $("#conetenedorUnpoco").before(div);


                },
                error: function (errors)
                {

                }
            });
        }
    });
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

        if ($("#div01").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('especialida'),
                data: {hPersona: $('input#hPersona').val()},
                //   async: false,
                //  dataType: 'json',

                success: function (data)
                {
                    // $("#contenido").append(data);
                    div = '<div class="nueva-Experiencia" id="div01"  style="background-color: #FBFBFB;margin-bottom:10px;border-bottom:1px solid #F1EEEE">' + data + '</div><br>';
                    $("#contenido").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });
    var div, divn;
    $("#btnExperiencia").click(function () {

        if ($("#div1").length > 0) {

        } else
        {

            $.ajax({
                type: "GET",
                url: Routing.generate('from_experiencia'),
                data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    actExp = 0;
                    div = '<div class="nueva-Experiencia" id="div1"  style="background-color: #FBFBFB;margin-bottom:10px;border-bottom:1px solid #F1EEEE">' + data + '</div><br>';
                    $("#consultas").before(div);

                },
                error: function (errors)
                {

                }
            });
        }
    });


    $("#btnEducacion").click(function () {

        if ($("#div2").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('from_educacion'),
                data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div2"  style="background-color:#FBFBFB;margin-bottom:-30px;border-bottom:1px solid #F1EEEE">' + data + '</div><br><br>';
                    $("#consultaEducacion").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });


    $("#btnCertificacion").click(function () {

        if ($("#div6").length > 0) {

        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('from_certificacion'),
                data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div6"  style="background-color: #FBFBFB;margin-bottom:-11px;border-bottom:1px solid #F1EEEE">' + data + '</div><br><br>';
                    $("#consultaCertificacion").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });

    $("#btnCurso").click(function () {
        if ($("#div7").length > 0) {
        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('from_curso'),
                data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div7"  style="background-color: #FBFBFB;margin-bottom:10px;border-bottom:1px solid #F1EEEE">' + data + '</div>';
                    $("#consultaCurso").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });

    $("#btnOrganizacion").click(function () {
        if ($("#div5").length > 0) {
        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('from_organizacion'),
                data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div5"  style="background-color: #FBFBFB;margin-bottom:10px;border-bottom:1px solid #F1EEEE">' + data + '</div><br>';
                    $("#consultaOrg").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });

    $("#btnIdioma").click(function () {
        if ($("#div3").length > 0) {
        } else {
            $.ajax({
                type: "GET",
                url: Routing.generate('from_idioma'),
                data: {hPersona: $('input#hPersona').val()},
                success: function (data)
                {
                    div = '<div class="nueva-Experiencia" id="div3"  style="background-color: #FBFBFB;margin-bottom:-10px;border-bottom:1px solid #F1EEEE">' + data + '</div>';
                    $("#consultaIdiomas").hide();
                    $("#consultaIdiomas").before(div);
                },
                error: function (errors)
                {

                }
            });
        }
    });


});
function Fsobremi()
{
    if ($("#div002").length > 0) {

    } else
    {
        $("#btnUnpoco").click();
    }
}
function editIdioma()
{
    if ($("#div3").length > 0) {

    } else
    {
        $('#btnIdioma').click();
    }
}

function editEspe()
{
    if ($("#div01").length > 0) {

    } else {
        $('#btnespecialida').click();
    }
}
function editExperiencia(val)
{
    var div, divn;
    if ($("#div1").length > 0) {

    } else
    {
        $.ajax({
            type: "GET",
            url: Routing.generate('from_experiencia'),
            data: {experiencia: val},
            success: function (data)
            {
                if ($("#div1").length > 0) {

                } else
                {
                    actExp = 1;
                    div = '<div class="nueva-Experiencia" id="div1"  style="background-color: #FBFBFB;margin-bottom:10px;border-bottom:1px solid #F1EEEE">' + data + '</div><br>';
                    $("#consultas").before(div);
                    $("#" + val).hide();
                    editExp = val;
                }

            },
            error: function (errors)
            {

            }
        });
    }
}
function removeExperiencia(val)
{

    var div, divn;
    $.ajax({
        type: "GET",
        url: Routing.generate('remove_experiencia'),
        data: {experiencia: val},
        async: false,
        dataType: 'json',
        success: function (data)
        {
            if (data.msj !== false)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: "<p>" + data.msj + "</p>"
                });
                $("#Exp" + val).remove();
            } else
            {
                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p>" + data.error + "</p>"
                });
            }

        },
        error: function (errors)
        {

        }
    });
}
function fil(idcheckbox)
{

    if (Especialida.indexOf(idcheckbox) === -1)
    {
        if (Especialida.length < 3)
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
            Lobibox.notify("warning", {
                size: 'mini',
                msg: '<p>Se deben seleccionar maxino 3 especialidades.</p>'
            });
            $("#" + idcheckbox).prop('checked', false);
            document.getElementById('div' + idcheckbox).style.display = 'none';
        }
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

function editDatosContacto() {

    if ($("#txtFijo").val() !== "" && $("#txtDirecion").val() !== "" && $("#txtEmail").val() !== "")
    {
        var datos;
        $("#fdatosContacto").submit(
                function (event) {
                    $.ajax({
                        type: 'POST',
                        async: false,
                        dataType: 'json',
                        url: Routing.generate('edit_persona'),
                        data: {hPersona: $('input#hPersona').val(), dato: $("#fdatosContacto").serialize(), n: 0},
                        success: function (data)
                        {

                            if (data.msj !== false) {
                                $('#txtOficina').editable('setValue', $("#txtFijo").val());
                                $('#txtMovil').editable('setValue', $("#txtMovil").val());
                                $('#txtCorreo').editable('setValue', $("#txtEmail").val());
                                $('#txtDireccion').editable('setValue', $("#txtDirecion").val());
                                $('#sitioweb').editable('setValue', $("#txtsitio").val());
                                $('#sitioweb2').editable('setValue', $("#txtsitio").val());
                                $("#div001").remove();
                                $("#datosContacto").show();
                                Lobibox.notify("success", {
                                    size: 'mini',
                                    msg: "<p>" + data.msj + "</p>"
                                });
                            }
                        }
                        ,
                        error: function (errors)
                        {

                        }
                    });
                    event.preventDefault();
                });
    }
}

function editSobremi() {
    tinymce.remove('.txtUnpoco1');

    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: Routing.generate('edit_persona'),
        data: {hPersona: $('input#hPersona').val(), descripcion: $("#txtUnpoco1").val(), n: 10},
        success: function (data)
        {
            $('#txtunpoco').empty();
            if (data.msj !== false) {
                $('#txtunpoco').html($("#txtUnpoco1").val());

                $("#div002").remove();
                $("#conetenedorUnpoco").show();
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: "<p>" + data.msj + "</p>"
                });
            }
        }
        ,
        error: function (errors)
        {

        }
    });

}
function addEspecialida()
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
            url: Routing.generate('subespecialida'),
            data: {hPersona: $('input#hPersona').val(), DataEspecialida: DataEspecialida, dato: $("#fEspecialida").serialize()},
            success: function (data)
            {
                if (data.msj !== false) {
                    $.each($(data.Esp), function (indice, val) {

                        Esp = val.id;
                        n = n + 1;
                        datos = '<div class="form-group">';
                        datos += '<div class="row">';
                        datos += '<div class="col-xs-12" style="margin-top: .5em; margin-bottom: .5em;"><ul class="prob">';
                        datos += ' <li><strong><p class="sans">' + val.nombre.toUpperCase() + '<p class="sans" ></strong></li>';
                        datos += ' <li><p class="sans" style="text-align:justify;" >' + val.descripcion + '</p></strong></li>';
                        datos += '</ul></div>';
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
                    datos += '$(this).append($(\'<span><a class ="fa fa-pencil fa-x2 btn btnperfil" ';
                    datos += 'onclick="editIdioma()"> &nbsp; Editar </a>';
                    datos += '</span>\'));';
                    datos += '},function(){';
                    datos += '$(this).find("span:last").remove();';
                    datos += '});';
                    datos += '</script></p></div>';
                    $("#contenido").append(boton);
                    Lobibox.notify("success", {
                        size: 'mini',
                        msg: "<p>" + data.msj + "</p>"
                    });

                } else {
                    Lobibox.notify("warning", {
                        size: 'mini',
                        msg: "<p>" + data.error + "</p>"
                    });
                }

                $("#div01").remove();
                $('div.divEspe').children('br').remove();
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

function addExperiencia()
{
    if ($('#txtEditEmpresa').is(':hidden'))
    {
        $('#txtEditEmpresa').val("");
    }
    if ($('#ScambioEmpresa').is(':hidden'))
    {
        $('#ScambioEmpresa').select2();
    }


    if ($("#txtpuesto").val() !== "" && $("#txthubicacion").val() !== "" && $("#txtFechaIni").val() !== "" && ($("#txtEditEmpresa").val() !== "" || $("#ScambioEmpresa").val() !== null))
    {
        if (persaEmpresa == 0)
        {
            $("#fExperiencia").submit(
                    function (event) {


                        var empresa, tipo;
                        if ($('#cambioEmpresa').is(":visible")) {
                            empresa = ($("#ScambioEmpresa").val());
                            tipo = 1;
                        } else {
                            empresa = $("#txtEditEmpresa").val();
                            tipo = 0;
                        }
                        var datos;


                        $.ajax({
                            type: 'POST',
                            async: false,
                            dataType: 'json',
                            url: Routing.generate('registrar_experiencia'),
                            data: {hPersona: $('input#hPersona').val(), dato: $("#fExperiencia").serialize(), empresa: empresa, tipo: tipo},
                            success: function (data)
                            {
                                $('div.experienc').children('br').remove();
                                if (data.msj !== false) {
                                    $.each($(data.Exp), function (indice, val) {
                                        $("#Exp" + val.id).remove();
                                        datos = '<div class="row" id="Exp' + val.id + '">';
                                        datos += '<div class="col-xs-1">';
                                        if (val.src !== null)
                                        {
                                            datos += '<a href="' + Routing.generate('busquedaPerfil', {url: val.url}) + '">';
                                            datos += '<img src="/' + val.src + '" style="max-width:50px;max-height:50px;"></a>';
                                        } else
                                        {

                                            datos += '<img src="/Resources/src/img/empresa/empresa.png" style="max-width:50px;max-height:50px;">';
                                        }
                                        datos += '</div>';
                                        datos += '<div class="col-xs-11">';
                                        if (val.url !== null)
                                        {
                                            datos += '<a href="' + Routing.generate('busquedaPerfil', {url: val.url}) + '">';
                                            datos += '<span style="font-size: 15px;">' + val.empresa + '&nbsp;</span></a>';
                                            datos += '&nbsp;<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"';
                                            datos += 'title="Haz click en el nombre/foto de la empresa para ir a la Pagina de la Compañia"></i>';
                                        } else
                                        {
                                            datos += '<span style="font-size: 15px;">' + val.empresa + '&nbsp;</span>';
                                        }
                                        datos += '</br>';
                                        datos += '<span style="font-size: 13px;">' + val.puesto + ' </br>';

                                        if (val.dias === null) {
                                            datos += val.fechaIn + ' - Actualmente';
                                        } else {
                                            datos += val.fechaIn + ' - ' + val.fechaFin + '&nbsp;';
                                            datos += '(&nbsp;' + parseFloat(val.dias / 365).toFixed(0) + '&nbsp;años&nbsp' + ((parseFloat(val.dias / 365) - (parseFloat(val.dias / 365).toFixed(0))) * 12).toFixed(0) + '&nbsp;meses)&nbsp;|&nbsp;';
                                        }
                                        datos += val.hubicacion + '</span>';
                                        datos += '<p style = "width: 90%; margin-top: 5px;text-align:justify;">' + val.funcion;
                                        datos += '<script type="text/javascript">';
                                        datos += '$("#Exp' + val.id + '").hover(';
                                        datos += 'function(){';
                                        datos += '$(this).append($(\'<span  style="margin-left:83px;"><a class="btn btn-primary  btn-sm btn-flat" style="width:80px;"';
                                        datos += 'onclick="editExperiencia(' + val.id + ')"> &nbsp; Editar </a>&nbsp;<a class="btn btn-default  btn-sm btn-flat" style="width:80px;"';
                                        datos += 'onclick="removeExperiencia(' + val.id + ')">&nbsp;Eliminar</a></span>\'));';
                                        datos += '},function(){';
                                        datos += '$(this).find("span:last").remove();';
                                        datos += '});';
                                        datos += '</script></p>';
                                        datos += '</div>';
                                        datos += '</div>';
                                        if (val.fechaFin == null)
                                        {
                                            $('#experienciaActual').text(val.empresa);
                                        }
                                    }
                                    );
                                    $("#consultas").append(datos);
                                    if (data.val != null && data.val == 1)
                                    {

                                        persaEmpresa = data.val;
                                        Lobibox.notify("success", {
                                            size: 'mini',
                                            msg: "<p>" + data.msj + "</p>"
                                        });
                                    } else
                                    {

                                        Lobibox.notify("success", {
                                            size: 'mini',
                                            msg: "<p>" + data.msj + "</p>"
                                        });
                                        $("#div1").remove();
                                    }
                                } else {
                                    Lobibox.notify("warning", {
                                        size: 'mini',
                                        msg: "<p>" + data.error + "</p>"
                                    });
                                }
                            }
                            ,
                            error: function (errors)
                            {

                            }
                        }
                        );
                        event.preventDefault();
                        return false;
                        $(':submit').attr('disabled', 'disabled');
                    });
        }
    }
    return false;
}
/*
 function addEdu() {
 if ($("#txtCentro").val() !== "" && $("#txtTitulo").val() !== "" && $("#Sdisciplina").val() !== "" && $("#txtAnioIni").val() !== "")
 {
 var datos;
 $("#fEdu").submit(
 function (event) {
 if (($("#txtAnioIni").val() > $("#txtAnioFin").val()) && ($("#txtAnioFin").val() != ""))
 {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p> Año fin menor que año inicio</p>"
 });
 } else
 {
 $.ajax({
 type: 'POST',
 async: false,
 dataType: 'json',
 url: Routing.generate('registrar_edu'),
 data: {hPersona: $('input#hPersona').val(), dato: $("#fEdu").serialize()},
 success: function (data)
 {
 $('div.education').children('br').remove();
 if (data.msj !== false) {
 $.each($(data.Edu), function (indice, val) {
 $("#Edu" + val.idEs).remove();
 datos = '<div class="row" id="Edu' + val.idEs + '">';
 datos += '<div class="col-xs-11" id="hfd">';
 datos += '<span style="font-size: 15px;">' + val.institucion + '&nbsp;</span></br>';
 datos += '<span style="font-size: 13px;">' + val.disciplina + ' | ' + val.titulo + ' </br>' + val.anioIni + ' - ' + val.anio + '</span>';
 
 datos += '<div >';
 datos += '<script type="text/javascript">';
 datos += '$("#Edu' + val.idEs + '").hover(';
 datos += 'function(){';
 datos += '$(this).append($(\'<span style="width: 90%;margin:13px;margin-top:10px;margin-bottom:10px;"><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
 datos += 'onclick="editEducacion(' + val.idEs + ')"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn btnperfil" ';
 datos += 'onclick="removeEdu(' + val.idEs + ')">&nbsp;Eliminar</i></span>\'));';
 datos += '},function(){';
 datos += '$(this).find("span:last").remove();';
 datos += '});';
 datos += '</script></div>';
 datos += '</div>';
 datos += '</div>';
 
 if (val.anio == "")
 {
 $('#estudioActual').text(val.institucion);
 
 }
 });
 $("#consultaEducacion").append(datos);
 Lobibox.notify("success", {
 size: 'mini',
 msg: "<p>" + data.msj + "</p>"
 });
 $("#div2").remove();
 
 
 } else {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>" + data.error + "</p>"
 });
 }
 }
 ,
 error: function (errors)
 {
 
 }
 });
 }
 event.preventDefault();
 });
 
 }
 }
 */
function removeEdu(val)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('remove_educacion'),
        async: false,
        dataType: 'json',
        data: {edu: val},
        success: function (data)
        {
            if (data.msj)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: "<p>" + data.msj + "</p>"
                });
        
                $("#Edu" + val).remove();
            } else
            {
                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p>" + data.error + "</p>"
                });
            }

        },
        error: function (errors)
        {

        }
    });
}
/*
 function addOrganizacion() {
 
 
 
 if ($("#txtOrg").val() !== "" && $("#txtPuestoOrg").val() !== "" && $("#txtFechIniOrg").val() !== "")
 {
 var fechafin = new Date($("#txtFechFinOrg").val());
 var fechain = new Date($("#txtFechIniOrg").val());
 var datos;
 $("#fOrg").submit(
 function (event) {
 var datos;
 if (fechafin < fechain)
 {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>Fecha fin menor a fecha inicio.</p>"
 });
 event.preventDefault();
 } else
 {
 $.ajax({
 type: 'POST',
 async: false,
 dataType: 'json',
 url: Routing.generate('registrar_org'),
 data: {hPersona: $('input#hPersona').val(), dato: $("#fOrg").serialize()},
 success: function (data)
 {
 
 $('div.organiz').children('br').remove();
 if (data.msj !== false) {
 $.each($(data.Organizacion), function (indice, val) {
 $("#Org" + val.id).remove();
 datos = '<div class="row" id="Org' + val.id + '">';
 datos += '<div class="col-xs-12">';
 datos += '<span style="font-size: 16px;">&nbsp;' + val.nombre + '&nbsp;</span></br>';
 datos += '<span style="font-size: 13px;">' + val.puesto + ' </br>' + val.fechaIn + ' - ' + val.fechaFin + '</span>';
 datos += '<p style = "width:100%; margin-top: 5px;text-align:justify;">' + val.descripcion + '</p>';
 datos += '<script type="text/javascript">';
 datos += '$("#Org' + val.id + '").hover(';
 datos += 'function(){';
 datos += '$(this).append($(\'<span style="margin-left:13px;"><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
 datos += 'onclick="editOrganizacion(' + val.id + ')"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn btnperfil" ';
 datos += 'onclick="removeOrg(' + val.id + ')">&nbsp;Eliminar</i></span>\'));';
 datos += '},function(){';
 datos += '$(this).find("span:last").remove();';
 datos += '});';
 datos += '</script>';
 datos += '</div>';
 datos += '</div>';
 });
 $("#consultaOrg").append(datos);
 
 Lobibox.notify("success", {
 size: 'mini',
 msg: "<p>" + data.msj + "</p>"
 });
 $("#div5").remove();
 } else if (data.error !== false) {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>" + data.error + "</p>"
 });
 }
 },
 error: function (errors)
 {
 }
 });
 }
 event.preventDefault();
 
 });
 
 }
 }*/
function removeOrg(val)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('remove_org'),
        async: false,
        dataType: 'json',
        data: {org: val},
        success: function (data)
        {
            if (data.msj)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: "<p>" + data.msj + "</p>"
                });

                $("#Org" + val).remove();
            } else
            {
                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p>" + data.error + "</p>"
                });
            }

        },
        error: function (errors)
        {

        }
    });
}
function editOrganizacion(val)
{
    var div, divn;
    if ($("#div5").length > 0) {
    } else {
        $.ajax({
            type: "GET",
            url: Routing.generate('from_organizacion'),
            data: {organizacion: val},
            success: function (data)
            {
                if ($("#div5").length > 0) {
                } else {
                    div = '<div class="nueva-Experiencia" id="div5"  style="background-color:#FBFBFB;margin-bottom:-38px;border-bottom:1px solid #F1EEEE;">' + data + '</div><br>';
                    $("#consultaOrg").before(div);
                    $("#Org" + val).hide();
                    editEdu = val;
                }
            },
            error: function (errors)
            {

            }
        });
    }
}

function editEducacion(val)
{
    var div, divn;
    if ($("#div2").length > 0) {
    } else {
        $.ajax({
            type: "GET",
            url: Routing.generate('from_educacion'),
            data: {educacion: val},
            success: function (data)
            {
                if ($("#div2").length > 0) {
                } else {
                    div = '<div class="nueva-Experiencia" id="div2"  style="background-color:#f4f4f4;margin-bottom:-28px;border-bottom:1px solid #F1EEEE">' + data + '</div><br><br>';
                    $("#consultaEducacion").before(div);
                    $("#Edu" + val).hide();
                    editEdu = val;
                }
            },
            error: function (errors)
            {

            }
        });
    }
}

/*
 function addCertificacion() {
 
 
 if ($("#txtCerti").val() !== "" && $("#txtAutorida").val() !== "")
 {
 var fechafin, fechain;
 
 var datos;
 //  $("#fCerti").submit(
 //        function (event) {
 
 var datos;
 if ($("#txtFechFinC").val() == "" && $("#txtFechIniC").val() !== "")
 {
 fechafin = new Date($("#txtFechFinC").val());
 fechain = new Date($("#txtFechIniC").val());
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>Seleccione fecha fin. </p>"
 });
 } else if ($("#txtFechFinC").val() !== "" && $("#txtFechIniC").val() == "")
 {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>Seleccione fecha inicio. </p>"
 });
 } else if ($("#txtFechFinC").val() !== "" && $("#txtFechIniC").val() !== "")
 {
 fechafin = new Date($("#txtFechFinC").val());
 fechain = new Date($("#txtFechIniC").val());
 
 if  (fechafin < fechain)
 {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>Fecha fin menor a fecha inicio. </p>"
 });
 } 
 else
 {
 
 $.ajax({
 type: 'POST',
 async: false,
 dataType: 'json',
 url: Routing.generate('registrar_certi'),
 data: {hPersona: $('input#hPersona').val(), dato: $("#fCerti").serialize()},
 success: function (data)
 {
 $('div.certif').children('br').remove();
 if (data.msj !== false) {
 
 $.each($(data.Cert), function (indice, val) {
 $("#Cert" + val.id).remove();
 datos = '<div class="row" id="Cert' + val.id + '">';
 datos += '<div class="col-xs-11">';
 datos += '<span style="font-size: 15px;">' + val.institucion + '&nbsp;</span></br>';
 datos += '<span style="font-size: 13px;">' + val.nombre + '</br>';
 if (val.fechaIn != null)
 {
 datos += val.fechaIn + ' - ' + val.fechaFin;
 }
 datos += '</span>';
 datos += '<p style = "width: 90%; margin-top: 5px;text-align:justify;">';
 datos += '<script type="text/javascript">';
 datos += '$("#Cert' + val.id + '").hover(';
 datos += 'function(){';
 datos += '$(this).append($(\'<span><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
 datos += 'onclick="editCertificacion(' + val.id + ')"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn btnperfil" ';
 datos += 'onclick="removeCertificacion(' + val.id + ')">&nbsp;Eliminar</i></span>\'));';
 datos += '},function(){';
 datos += '$(this).find("span:last").remove();';
 datos += '});';
 datos += '</script></p>';
 datos += '</div>';
 datos += '</div>';
 });
 $("#consultaCertificacion").append(datos);
 Lobibox.notify("success", {
 size: 'mini',
 msg: "<p>" + data.msj + "</p>"
 });
 $("#div6").remove();
 } else {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>" + data.error + "</p>"
 });
 }
 
 }
 ,
 error: function (errors)
 {
 
 }
 });
 }
 }else if ($("#txtFechFinC").val() == "" && $("#txtFechIniC").val() == "")
 {
 $("#txtFechFinC").val(null);
 $("#txtFechIniC").val(null);
 $.ajax({
 type: 'POST',
 async: false,
 dataType: 'json',
 url: Routing.generate('registrar_certi'),
 data: {hPersona: $('input#hPersona').val(), dato: $("#fCerti").serialize()},
 success: function (data)
 {
 $('div.certif').children('br').remove();
 if (data.msj !== false) {
 
 $.each($(data.Cert), function (indice, val) {
 $("#Cert" + val.id).remove();
 datos = '<div class="row" id="Cert' + val.id + '">';
 datos += '<div class="col-xs-11">';
 datos += '<span style="font-size: 15px;">' + val.institucion + '&nbsp;</span></br>';
 datos += '<span style="font-size: 13px;">' + val.nombre + '</br>';
 if (val.fechaIn != null)
 {
 datos += val.fechaIn + ' - ' + val.fechaFin;
 }
 datos += '</span>';
 datos += '<p style = "width: 90%; margin-top: 5px;text-align:justify;">';
 datos += '<script type="text/javascript">';
 datos += '$("#Cert' + val.id + '").hover(';
 datos += 'function(){';
 datos += '$(this).append($(\'<span><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
 datos += 'onclick="editCertificacion(' + val.id + ')"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn btnperfil" ';
 datos += 'onclick="removeCertificacion(' + val.id + ')">&nbsp;Eliminar</i></span>\'));';
 datos += '},function(){';
 datos += '$(this).find("span:last").remove();';
 datos += '});';
 datos += '</script></p>';
 datos += '</div>';
 datos += '</div>';
 });
 $("#consultaCertificacion").append(datos);
 Lobibox.notify("success", {
 size: 'mini',
 msg: "<p>" + data.msj + "</p>"
 });
 $("#div6").remove();
 } else {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>" + data.error + "</p>"
 });
 }
 
 }
 ,
 error: function (errors)
 {
 
 }
 });
 } 
 return false;
 //     event.preventDefault();
 
 //     });
 }
 }
 
 */
function removeCertificacion(val)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('remove_certificacion'),
        async: false,
        dataType: 'json',
        data: {org: val},
        success: function (data)
        {
            if (data.msj)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: "<p>" + data.msj + "</p>"
                });

                $("#Cert" + val).remove();
            } else
            {
                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p>" + data.error + "</p>"
                });
            }

        },
        error: function (errors)
        {

        }
    });
}
function editCertificacion(val)
{
    var div, divn;
    if ($("#div6").length > 0) {
    } else {
        $.ajax({
            type: "GET",
            url: Routing.generate('from_certificacion'),
            data: {certificacion: val},
            success: function (data)
            {
                if ($("#div6").length > 0) {
                } else {
                    div = '<div class="nueva-Experiencia" id="div6"  style="background-color:#f4f4f4;margin-bottom:-30px;border-bottom:1px solid #F1EEEE">' + data + '</div><br><br>';
                    $("#consultaCertificacion").before(div);
                    $("#Cert" + val).hide();
                    editEdu = val;
                }
            },
            error: function (errors)
            {

            }
        });
    }
}

/*
 function addCurso() {
 if ($("#txtCurso").val() !== "" && $("#txtAutoridaCM").val() !== "" && $("#txtFechIniCM").val() !== "" && $("#txtFechFinCM").val() !== "")
 {
 var fechafin = new Date($("#txtFechFinCM").val());
 var fechaini = new Date($("#txtFechIniCM").val());
 
 
 var datos;
 //  $("#fCurso").submit(
 //        function (event) {
 var datos;
 if (fechafin < fechaini)
 {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>Fecha fin menor a fecha inicio. </p>",
 });
 } else
 {
 $.ajax({
 type: 'POST',
 async: false,
 dataType: 'json',
 url: Routing.generate('registrar_curso'),
 data: {hPersona: $('input#hPersona').val(), dato: $("#fCurso").serialize()},
 success: function (data)
 {
 if (data.msj !== false) {
 $.each($(data.Curso), function (indice, val) {
 $("#CM" + val.id).remove();
 datos = '<div class="row" id="CM' + val.id + '">';
 datos += '<div class="col-xs-11">';
 datos += '<span style="font-size: 15px;">' + val.institucion + '&nbsp;</span></br>';
 datos += '<span style="font-size: 13px;">' + val.nombre + '</br>' + val.fechaIn + ' - ' + val.fechaFin + '</span>';
 datos += '<p style = "width: 90%; margin-top: 5px;text-align:justify;">' + val.descripcion;
 datos += '<script type="text/javascript">';
 datos += '$("#CM' + val.id + '").hover(';
 datos += 'function(){';
 datos += '$(this).append($(\'<span><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
 datos += 'onclick="editCurso(' + val.id + ')"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn btnperfil" ';
 datos += 'onclick="removeSeminario(' + val.id + ')">&nbsp;Eliminar</i></span>\'));';
 datos += '},function(){';
 datos += '$(this).find("span:last").remove();';
 datos += '});';
 datos += '</script></p>';
 datos += '</div>';
 datos += '</div>';
 });
 $("#consultaCurso").append(datos);
 
 Lobibox.notify("success", {
 size: 'mini',
 msg: "<p>" + data.msj + "</p>"
 });
 $("#div7").remove();
 } else {
 Lobibox.notify("warning", {
 size: 'mini',
 msg: "<p>" + data.error + "</p>"
 });
 }
 
 }
 ,
 error: function (errors)
 {
 
 }
 
 });
 }
 //      event.preventDefault();
 //   });
 return false;
 }
 
 }*/
function removeSeminario(val)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('remove_seminario'),
        async: false,
        dataType: 'json',
        data: {sem: val},
        success: function (data)
        {
            if (data.msj)
            {
                Lobibox.notify("success", {
                    size: 'mini',
                    msg: "<p>" + data.msj + "</p>"
                });
                Fsobremi

                $("#CM" + val).remove();
            } else
            {
                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p>" + data.error + "</p>"
                });
            }

        },
        error: function (errors)
        {

        }
    });
}

function validation()
{
    var val;
    $.each($('.newIdioma'), function (indice, val) {
        if ($("#" + $(this).attr('id')).val() === "")
        {
            Nidioma = 0;
            return false;
        } else
        {
            Nidioma = 1;
            return true;
        }

    });
    return Nidioma;
}

function addIdiomas() {

    if (validation())
    {
        $.each($('.newIdioma'), function (indice, val) {
            Idioma.push($(this).attr('id'));
            if (Idioma.length % 2 == 0)
            {
                DatosIdiomas.push(Idioma);
                Idioma = [];
            }

        });
        var datos;
        $("#fIdiomas").submit(
                function (event) {
                    var datos;
                    $.ajax({
                        type: 'POST',
                        async: false,
                        dataType: 'json',
                        url: Routing.generate('registrar_idioma'),
                        data: {hPersona: $('input#hPersona').val(), dato: $("#fIdiomas").serialize(), DatosIdiomas: DatosIdiomas},
                        success: function (data)
                        {
                            $("#consultaIdiomas").empty();
                            if (data.msj !== false) {
                                datos = '<div class="row " id="Idioma">';
                                $.each($(data.Idiomas), function (indice, val) {
                                    datos += '<div class="col-xs-3" style="margin-top: .5em; margin-bottom: .5em;">';
                                    datos += '<ul class="prob"><li><strong>' + val.nombre + '</strong></li>';
                                    datos += '<li>' + val.nivel + '</li>';
                                    datos += '</ul>';
                                    datos += '</div>';
                                });
                                datos += '<div class="clearfix"></div>';
                                datos += '<script type="text/javascript">';
                                datos += '$("#Idioma").hover(';
                                datos += 'function(){';
                                datos += '$(this).append($(\'<span style="margin-left:13px;"><a class="btn btn-primary  btn-sm btn-flat" style="width:80px;"  ';
                                datos += 'onclick="editIdioma()"> &nbsp; Editar </a>';
                                datos += '</span>\'));';
                                datos += '},function(){';
                                datos += '$(this).find("span:last").remove();';
                                datos += '});';
                                datos += '</script></div>';
                                $("#consultaIdiomas").append(datos);
                                $("#consultaIdiomas").show();

                                Lobibox.notify("success", {
                                    size: 'mini',
                                    msg: "<p>" + data.msj + "</p>"
                                });
                                $("#div3").remove();

                            } else {
                                Lobibox.notify("warning", {
                                    size: 'mini',
                                    msg: "<p>" + data.error + "</p>"
                                });
                            }

                            DatosIdiomas = [];
                            Idioma = [];
                        }
                        ,
                        error: function (errors)
                        {

                        }
                    });
                    event.preventDefault();
                });
    } else
    {
        if ($('.newIdioma').length === 0) {

            $("#divContenedor").append("<p  style='font-size:13px; color: #F33;'><strong>Debe de seleccionar almenos un idioma</strong></p>");

        }

    }
}

function editCurso(val)
{
    var div, divn;
    console.log($("#div7"));
    if ($("#div7").length > 0) {
    } else {
        $.ajax({
            type: "GET",
            url: Routing.generate('from_curso'),
            data: {curso: val},
            success: function (data)
            {
                if ($("#div7").length > 0) {
                } else {
                    div = '<div class="nueva-Experiencia" id="div7"  style="background-color:#f4f4f4;margin-bottom:-48px;border-bottom:1px solid #F1EEEE">' + data + '</div><br>';
                    $("#consultaCurso").before(div);
                    $("#CM" + val).hide();
                    editEdu = val;
                }
            },
            error: function (errors)
            {

            }
        });
    }
}

function ciudad()
{
    var ciudad;
    $.ajax({
        type: "GET",
        url: Routing.generate('ciudad'),
        async: false,
        dataType: 'json',
        data: {estado: $("#sEstado").val()},
        success: function (data)
        {
            $("#divC").empty();
            $("#pDepto").empty();
            ciudad = '<div class="col-sm-12 col-xs-12"  style="margin-bottom:7px;"> ';
            ciudad += ' <select class="form-control input-sm select3"  name="sCiuda" id="sCiuda" onChange=removeP()>';
            ciudad += '<option value="0">Seleccione ciudad</option>';
            $.each(data.ciudad, function (indice, val) {
                ciudad += '<option value="' + val.id + '">' + val.nombre + '</option>';
            });
            ciudad += ' </select>';
            ciudad += '<p style=" color: #FF0000;margin-top:2px;margin-bottom:0px;" id="pMuni"></p></div>';
            $("#divC").append(ciudad);
            // $('.select3').select2();
        },
        error: function (errors)
        {

        }
    });

}
function removeP()
{
    $("#pMuni").empty();
}