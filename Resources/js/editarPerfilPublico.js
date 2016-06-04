
$("#btnEditExp").click(function () {

    $.ajax({
        type: "GET",
        url: Routing.generate('edit_perfil_persona'),
        success: function (data)
        {
            document.getElementById('divEditEmpresa').style.display = 'none';
            document.getElementById('divPuesto').style.display = 'block';
            
            $("#verPerfil").show();
            $("#verPerfil").html(data);
            $("#editarPerfil").hide();
        },
        error: function (errors)
        {
        }
    });
});


