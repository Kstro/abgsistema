/**
 Address editable input.
 Internally value stored as {SDepartamento: "Moscow", sEstado: "Lenina", building: "15777"}
 
 @class address
 @extends abstractinput
 @final
 @example
 <a href="#" id="address" data-type="address" data-pk="1">awesome</a>
 <script>
 $(function(){
 $('#address').editable({
 url: '/post',
 title: 'Enter SDepartamento, sEstado and building #',
 value: {
 SDepartamento: "Moscow", 
 sEstado: "Lenina", 
 building: "155555"
 }
 });
 });
 </script>
 **/




(function ($) {
    "use strict";
    var Address = function (options) {
        this.init('address', options, Address.defaults);
    };
    //inherit from Abstract input
    $.fn.editableutils.inherit(Address, $.fn.editabletypes.abstractinput);
    $.extend(Address.prototype, {
        /**
         Renders input from tpl
         
         @method render() 
         **/
        render: function () {
            this.$input = this.$tpl.find('input');
        },
        /**
         Gets value from element's html
         
         @method html2value(html) 
         **/
        html2value: function (html) {
            /*
             you may write parsing method to get value by element's html
             e.g. "Moscow, st. Lenina, bld. 1599999" => {SDepartamento: "Moscow", sEstado: "Lenina", building: "1588888888"}
             but for complex structures it's not recommended.
             Better set value directly via javascript, e.g. 
             editable({
             value: {
             SDepartamento: "Moscow", 
             sEstado: "Lenina", 
             building: "1566666666"
             }
             });
             */
            return null;
        },
        /**
         Converts value to string. 
         It is used in internal comparing (not for sending to server).http://abg.localhost/app_dev.php/abgpersona/admin/orlando
         
         @method value2str(value)  
         **/
        value2str: function (value) {
            var str = '';
            if (value) {
                for (var k in value) {
                    str = str + k + ':' + value[k] + ';';
                }
            }
            return str;
        },
        /*
         Converts string to value. Used for reading value from 'data-value' attribute.
         
         @method str2value(str)  
         */
        str2value: function (str) {
            /*http://abg.localhost/app_dev.php/abgpersona/admin/orlando
             this is mainly for parsing value defined in data-value attribute. 
             If you will always set value by javascript, no need to overwrite it
             */
            return str;
        },
        /**
         Sets value of input.
         
         @method value2input(value) 
         @param {mixed} value
         **/
        value2input: function (value) {

            if (!value) {
                return;
            }
            var estado;
            /*  $.ajax({
             type: "GET",
             url: Routing.generate('departamento'),
             async: false,
             dataType: 'json',
             success: function (data)
             {
             $("#divD").empty();
             estado = '<label for="ejemplo_archivo_1">Estado: </label>\
             <select class="form-control input-sm editable-address"  name="sEstado" id="sEstado" onChange=ciudad()>';
             estado += '<option value="0">Seleccione estado</option>';
             
             $.each(data.depto, function (indice, val) {
             estado += '<option value="' + val.id + '">' + val.nombre + '</option>';
             });
             estado += ' </select></div></div> ';
             $("#divD").append(estado);
             },
             error: function (errors)
             {
             
             }
             })*/
            this.$input.filter('[name="SDepartamento"]').val(
                    $.ajax({
                        type: "GET",
                        url: Routing.generate('departamento'),
                        async: false,
                        dataType: 'json',
                        success: function (data)
                        {
                            $("#divD").empty();
                            estado='<div class="row"><div class="col-sm-12"> ';
                            estado += '<label for="ejemplo_archivo_1"> </label>\
                                      <select   class="form-control input-sm editable-address select2"  name="sEstado" id="sEstado" onChange=ciudad()>';
                            estado += '<option value="0">Seleccione Deptamento</option>';
                            $.each(data.depto, function (indice, val) {
                                estado += '<option value="' + val.id + '">' + val.nombre + '</option>';
                            });
                            estado += ' </select></div></div> ';
                             estado+='</div></div> ';
                            $("#divD").append(estado);
                              $('.select2').select2();
                        },
                        error: function (errors)
                        {

                        }
                    })
                    );
                $('.select3').select2(); 
            //  this.$input.filter('[name="building"]').val(value.building);
        },
        /**
         Returns value of input.
         
         @method input2value() 
         **/
        input2value: function () {

            return {
                   SDepartamento: this.$input.filter('[name="SDepartamento"]').val(),
                sEstado: this.$input.filter('[name="sEstado"]').val()
                //sEstado: 'Ciudad',
             

            };
        },
        /**
         Activates input: sets focus on the first field.
         @method activate() 
         **/
        activate: function () {
            this.$input.filter('[name="SDepartamento"]').focus();
        },
        /**
         Default method to show value in element. Can be overwritten by display option.
         
         @method value2html(value, element) 
         **/
        value2html: function (value, element) {

            if (!value) {
                $(element).empty();
                return;
            }
            //  console.log($("#SDepartamento").val());
            var html = $('<div>').text(value.SDepartamento).html() + ' | ' + $('<div>').text(value.sEstado).html();
            $(element).html(html);
        },
        /**
         Attaches handler to submit form in case of 'showbuttons=false' mode
         
         @method autosubmit() 
         **/
        autosubmit: function () {
            this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        },
    });

    Address.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-address"><div id="divD">'
                + '<select style="resize:none;width:250px;" class="form-control input-sm" name="SDepartamento" id="SDepartamento" onChange="puestoDept()">' +
                '<option></option><select></div>' +
                '<div class="editable-address" id="divC" style="margin-top:7px; "><label for="ejemplo_archivo_1"></label>\
                            <select class="form-control input-sm  select3" name="sEstado" id="sEstado">'
                + '<option value="0">Seleccione ciudad</option><select></div>',
        // '<div class="editable-address"><label><span>Street: </span><input type="text" name="sEstado" class="input-small"></label></div>' +
        inputclass: ''
        
    });
      
    $.fn.editabletypes.address = Address;
 
}(window.jQuery));

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
            ciudad = '<label for="ejemplo_archivo_1"></label>\
                            <select class="form-control input-sm select3"  name="sCiuda" id="sCiuda" >';
            ciudad += '<option value="0">Seleccione ciudad</option>';
            $.each(data.ciudad, function (indice, val) {
                ciudad += '<option value="' + val.id + '">' + val.nombre + '</option>';
            });
            ciudad += ' </select></div></div> ';
            $("#divC").append(ciudad);
     $('.select3').select2();     
        },
        error: function (errors)
        {

        }
    });
    
}

 