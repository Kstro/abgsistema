
function serializeToJson(a){
	var o = {};
	$.each(a, function() {
	   if (o[this.name]) {
	       if (!o[this.name].push) {
	           o[this.name] = [o[this.name]];
	       }
	       o[this.name].push(this.value || '');
	   } else {
	       o[this.name] = this.value || '';
	   }
	});
	return o;
}
function SerializarTr (tr) {
	frm = tr.find("input,select");//encuentro el valor contenido en el input
	frm = serializeToJson(frm.serializeArray());//convierto los datos en un array de tipo form
	return frm;
}
// Validar los keypress 
	function probarExp(exp,texto){
		return exp.test(texto);
	}

	function getCharFromEvent(e){
		asccii 		= e.which;
		character 	=  String.fromCharCode(asccii);
		return character;
	}

	function testExpression(e,expresion){
		character = getCharFromEvent(e);
		return probarExp(expresion,character);
	}
	//Validar si es un numero
	function isNumber(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}

	//Funcion Para Matar la sesion 
function logOut(frm){
	//console.log("la url es: ",getBaseURL());
	$.ajax({
         data:{
           form: JSON.stringify(frm)
         },
         url:  "login/logOut",
         type:   "POST",
         success: function(data){
           var datos = jQuery.parseJSON(data);
           window.location = "login";
         }
     });
}

function GetAlert (titulo,texto,imagen,tiempo) {
	 $.gritter.add({
		title: titulo,
		text: texto,
		image: imagen,
		time: tiempo,
	});
}