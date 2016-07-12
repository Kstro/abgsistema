$(document).ready(function () {
    var flag = true;
    var Extension = "";
    $("#enviarImagenV").hide();
    $("#prevVerificacion").hide();
    $("#cancelarImagenV").hide();

    $(document).on("change", "#imagenV", function ()
    {
        readURL(this);
        //obtenemos un array con los datos del archivo
        var file = $(this)[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        Extension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;

        if (Extension == "png" || Extension == "bmp"
                || Extension == "jpeg" || Extension == "jpg") {
            flag = true;

        } else {
            flag = false;
        }


        if (flag == true) {

            $("#prevVerificacion").show();
            $("#cancelarImagenV").show();
            $("#enviarImagenV").show();

        } else {
            $("#enviarImagenV").hide();
            $("#imagenV").val("");
            Lobibox.notify("error", {
                size: 'mini',
                msg: 'Seleccione una imagen.'});

        }
    });


    $(document).on("click", "#enviarImagenV", function () {

        var frm = new FormData($("#frmVerificacion")[0]);

        if (flag == true) {
            $("#enviarImagenV").button('loading');
            $("#enviarImagenV").prop("disabled", true);
            $.ajax({
                data: frm,
                dataType: 'json',
                url: Routing.generate('verficacionAbogado'),
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data.estado == true) {
                        $("#cancelarImagenV").hide();
                        $("#enviarImagenV").hide();
                                                
                        Lobibox.notify("success", {
                            size: 'mini',
                            msg: '<p>Su solicitud de verificación se ha enviado.</p>'});
                    } else {
                        Lobibox.notify("error", {
                            size: 'mini',
                            msg: 'Lo sentimos, ocurrio un error, intentelo mas tarde.'});
                    }
                      $("#enviarImagenV").prop("disabled", false);
            $("#enviarImagenV").button('reset');
                }
            });
          
        } else {

            alert("Acm1pt");


        }

    });





    $(document).on("click", "#cancelarImagenV", function (e) {

        cancelar();


    });














});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#prevVerificacion').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function cancelar() {
    $("#enviarImagenV").hide();
    $("#prevVerificacion").hide();
    $("#cancelarImagenV").hide();
    $("#imagenV").val("");
}