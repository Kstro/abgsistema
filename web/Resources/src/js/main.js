
jQuery(function ($) {
    /*$("#txtdui").mask("99999999-9");
     $("#txtnit").mask("9999-999999-999-9");
     $("#txtfijo").mask("9999-9999");*/
    $("#txtMovil").mask("9999-9999");

});
$(document).ready(function () {

    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';


    $('#txtMovil').editable({
        name: 'username', //name of field (column in db)
        pk: 1, //primary key (record id)
        value:$('#txtMovil').html(),
        type: "GET",
   //     data:{},
        //url: Routing.generate('movil',{a:this.value()}),
        async: false,
        dataType: 'json',
    });

    //make status editable
    $('#txtOficina').editable({
        type: 'select',
        title: 'Select status',
        placement: 'right',
        value: 2,
        source: [
            {value: 1, text: 'status 1'},
            {value: 2, text: 'status 2'},
            {value: 3, text: 'status 3'}
        ]
                /*
                 //uncomment these lines to send data on server
                 ,pk: 1
                 ,url: '/post'
                 */
    });
});