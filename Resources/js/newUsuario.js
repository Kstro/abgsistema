
$(document).on('ready', function () {
$(document).on("click", "#bntRegistrar", function (e) {
alert("hdffhf");
    $.ajax({
        data: {dato: $("#frmEmpresaUsuarioPersona").serialize()},
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
                            var url = Routing.generate('confirma_cuenta');
                            //       var url = Routing.generate('perfil');
                            //var url = Routing.generate('abogado_login');
                            window.open(url, "_self");
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


var permisoCorreo = false;

    $(document).on("input", ".correo", function () {

        var email = $(this).val();
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;
        if (email == "") {

            $(".msg").hide();
            $(".error").hide();

        } else if (re.test(email)) {
            $('.msg').hide();
            $('.success').show();
            permisoCorreo = true;
        } else {
            $('.msg').hide();
            $('.error').show();
            permisoCorreo = false;
        }


    });
});        