
$(document).on('click', '#btnEnviarExp', function () {

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

            $("#btnEnviarExp").button('loading');
            $("#btnEnviarExp").prop("disabled", true);
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
                            datos += '$(this).append($(\'<span  style="margin-left:83px;"><i class ="fa fa-pencil fa-x2 btn btnperfil" ';
                            datos += 'onclick="editExperiencia(' + val.id + ')"> &nbsp; Editar </i>&nbsp;<i class="fa fa-trash-o btn  btnperfil" ';
                            datos += 'onclick="removeExperiencia(' + val.id + ')">&nbsp;Eliminar</i></span>\'));';
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

            });
            //   $(':submit').attr('disabled', 'disabled');
            $("#btnEnviarExp").prop("disabled", false);
            $("#btnEnviarExp").button('reset');

            return false;
        }
    }
});



var contaCer = 0;
$(document).on('click', '#addCertificacion', function () {

    contaCer = contaCer + 1;
    if ($("#txtCerti").val() !== "" && $("#txtAutorida").val() !== "")
    {
        $("#addCertificacion").button('loading');
        $("#addCertificacion").prop("disabled", true);
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
            $("#addCertificacion").prop("disabled", false);
            $("#addCertificacion").button('reset');
            return false;
        } else if ($("#txtFechFinC").val() !== "" && $("#txtFechIniC").val() == "")
        {
            Lobibox.notify("warning", {
                size: 'mini',
                msg: "<p>Seleccione fecha inicio. </p>"
            });
            $("#addCertificacion").prop("disabled", false);
            $("#addCertificacion").button('reset');
            return false;
        } else if ($("#txtFechFinC").val() !== "" && $("#txtFechIniC").val() !== "")
        {
            fechafin = new Date($("#txtFechFinC").val());
            fechain = new Date($("#txtFechIniC").val());

            if (fechafin < fechain)
            {
                if (contaCer == 1)
                {
                    Lobibox.notify("warning", {
                        size: 'mini',
                        msg: "<p>Fecha fin menor a fecha inicio. </p>"
                    });

                    $("#Pcer").text("Fecha fin menor a fecha inicio.");
                }
                $("#addCertificacion").prop("disabled", false);
                $("#addCertificacion").button('reset');
                return false;
            } else if (fechafin >= fechain)
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
                        $("#Pcer").empty();
                    }
                    ,
                    error: function (errors)
                    {

                    }
                });
                $("#addCertificacion").prop("disabled", false);
                $("#addCertificacion").button('reset');
                return false;
            }
            return false;
        } else if ($("#txtFechFinC").val() == "" && $("#txtFechIniC").val() == "")
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
                    $("#Pcer").empty();
                }
                ,
                error: function (errors)
                {

                }
            });
            $("#addCertificacion").prop("disabled", false);
            $("#addCertificacion").button('reset');
            return false;
        }
        return false;
        //     event.preventDefault();

        //     });
    }

});


var conta = 0;
$(document).on('click', '#addEdu', function (e) {
    conta = conta + 1;

    if ($("#txtCentro").val() !== "" && $("#txtTitulo").val() !== "" && $("#Sdisciplina").val() !== "" && $("#txtAnioIni").val() !== "")
    {
        $("#addEdu").button('loading');
        $("#addEdu").prop("disabled", true);
        var datos;
        if (($("#txtAnioIni").val() > $("#txtAnioFin").val()) && ($("#txtAnioFin").val() != ""))
        {
            if (conta == 1) {
                console.log(conta);
                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p> Año fin menor que año inicio</p>"
                });
                $("#Pedu").text("Fecha fin menor a fecha inicio.");
            }
            $("#addEdu").prop("disabled", false);
            $("#addEdu").button('reset');

        } else if ($("#txtAnioFin").val() == "")
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
                            datos = '<div class="row" id="Edu' + val.idEs + '" style="margin-bottom:10px;">';
                            datos += '<div class="col-xs-11" id="hfd">';
                            datos += '<span style="font-size: 15px;">' + val.institucion + '&nbsp;</span></br>';
                            datos += '<span style="font-size: 13px;">' + val.titulo + ' </br>' + val.anioIni;
                            if (val.anio)
                            {
                                datos += ' - ' + val.anio;
                            } else {
                                datos += ' - Actualmente';
                            }
                            datos += '</span>';
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
                        $("#addEdu").prop("disabled", false);
                        $("#addEdu").button('reset');
                        $("#div2").remove();
                        $("#Pedu").empty();
                        return false;
                        e.preventDefault();

                    } else {
                        Lobibox.notify("warning", {
                            size: 'mini',
                            msg: "<p>" + data.error + "</p>"
                        });
                        $("#addEdu").prop("disabled", false);
                        $("#addEdu").button('reset');
                        $("#Pedu").empty();
                        e.preventDefault();
                        return false;
                    }
                    e.preventDefault();
                }
                ,
                error: function (errors)
                {

                }
            });
            return false;
        } else if (($("#txtAnioIni").val() <= $("#txtAnioFin").val()) && ($("#txtAnioFin").val() != ""))
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
                            datos = '<div class="row" id="Edu' + val.idEs + '" style="margin-bottom:10px;">';
                            datos += '<div class="col-xs-11" id="hfd">';
                            datos += '<span style="font-size: 15px;">' + val.institucion + '&nbsp;</span></br>';
                            datos += '<span style="font-size: 13px;">' + val.titulo + ' </br>' + val.anioIni;
                            if (val.anio)
                            {
                                datos += ' - ' + val.anio;
                            } else {
                                datos += ' - Actualmente';
                            }
                            datos += '</span>';
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
                        $("#addEdu").prop("disabled", false);
                        $("#addEdu").button('reset');
                        $("#div2").remove();
                        return false;
                        e.preventDefault();

                    } else {
                        Lobibox.notify("warning", {
                            size: 'mini',
                            msg: "<p>" + data.error + "</p>"
                        });
                        $("#addEdu").prop("disabled", false);
                        $("#addEdu").button('reset');
                        e.preventDefault();
                        return false;
                    }
                    e.preventDefault();
                }
                ,
                error: function (errors)
                {

                }
            });
            return false;
        }

        return false;
    }

});


var contacm = 0;
$(document).on('click', '#addCurso', function (e) {
    contacm = contacm + 1;
    if ($("#txtCurso").val() !== "" && $("#txtAutoridaCM").val() !== "" && $("#txtFechIniCM").val() !== "" && $("#txtFechFinCM").val() !== "")
    {
        $("#addCurso").button('loading');
        $("#addCurso").prop("disabled", true);

        var fechafin = new Date($("#txtFechFinCM").val());
        var fechaini = new Date($("#txtFechIniCM").val());


        var datos;
        //  $("#fCurso").submit(
        //        function (event) {
        var datos;
        if (fechafin < fechaini)
        {
            if (contacm == 1) {

                Lobibox.notify("warning", {
                    size: 'mini',
                    msg: "<p>Fecha fin menor a fecha inicio. </p>",
                });
                $("#Pcurso").text("Fecha fin menor a fecha inicio.");
            }
            $("#addCurso").prop("disabled", false);
            $("#addCurso").button('reset');
        } else if (fechafin >= fechaini)
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
                        $("#addCurso").prop("disabled", false);
                        $("#addCurso").button('reset');
                        $("#div7").remove();
                    } else {
                        Lobibox.notify("warning", {
                            size: 'mini',
                            msg: "<p>" + data.error + "</p>"
                        });
                        $("#addCurso").prop("disabled", false);
                        $("#addCurso").button('reset');
                    }
                    $("#Pcurso").empty();
                }
                ,
                error: function (errors)
                {

                }

            });
            return false;
        }
        //      event.preventDefault();
        //   });
        return false;
    }

});