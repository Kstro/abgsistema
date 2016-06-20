

$(document).ready(function () {



   






    $('#txtMovil').editable({
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



    $("#txtFechaFundacion").editable();
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



});

