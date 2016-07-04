    $(document).ready(function () {
    $("#btnEnviarExp").click(function () {
  //      alert("hola");
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
                        }
                        );
                        event.preventDefault();
                        return false;
                    });
        }
    }
  
    });
});

