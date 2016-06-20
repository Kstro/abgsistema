$(document).on('ready', function () {

    $("#btnFactura").click(function () {

        if ($("#STipoServicio").val() !== 0 && $("#txtcosto").val() !== "")
        {
            /* $("#ffacturacion").submit(
             function (event) {*/
            $.ajax({
                type: "POST",
                url: Routing.generate('facturacion'),
                async: false,
                dataType: 'json',
                data: {dato: $("#ffacturacion").serialize()},
                success: function (data)
                {
                    if (data.msj !== false)
                    {
                        Lobibox.notify("success", {
                            size: 'mini',
                            msg: data.msj
                        });

                    } else
                    {
                        Lobibox.notify("warning", {
                            size: 'mini',
                            msg: data.error
                        });
                    }

                },
                error: function (errors)
                {

                }
            });
            /*  event.preventDefault();
             });*/
        }
    });

    $("#btnbuscar").click(function () {
        

        if ($("#div3").length > 0) {
        } else {
            $.ajax({
                type: "GET",
                async: false,
                dataType: 'json',
                 data: {dato: $("#fConsultaFactura").serialize()},
                url: Routing.generate('consulta_fact'),
                success: function (data)
                {
                    var datos;
                    $("#body").empty(datos);
                    $.each($(data.facturacion), function (indice, val) {

                        datos = '<tr>';
                        datos += '<td align = "justify" width="5%">' + val.idFact + '</td>';
                        datos += '<td align = "justify" width="10%">' + val.fechaPago + '</td>';
                        datos += '<td align = "justify" width="15%">' + val.servicio + '</td>';
                        datos += '<td align = "justify" width="15%">' + val.plazo + ' dias</td>';
                        datos += '<td align = "justify" width="10%">' + val.monto + '</td>';
                        datos += '<td align = "justify"  width="40%">' + val.descripcion + '</td>';
                        datos += '</tr>';
                        $("#body").append(datos);
                    });


                },
                error: function (errors)
                {

                }
            });
        }
    });
});
    function referencia()
    {
     if (($("#STipoPago option:selected").text()==="Bancaria") || ($("#STipoPago option:selected").text()==="Tarjeta" )){
            document.getElementById('divReferencia').style.display = 'block';
        }
       else {
            document.getElementById('divReferencia').style.display = 'none';
        }
    }
    
   