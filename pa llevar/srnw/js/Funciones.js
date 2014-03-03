	function CambiarFoco(evt, Obj){
            if (VeriEnter(evt)){		
                Tab(Obj);
                return false;
            }
  	}	
	function Tab(Obj){
            document.getElementById(Obj).focus();
	}	
	function esnulo(campo){ return (campo == null||campo=="");}
	    function esnumero(campo){ return (!(isNaN( campo )));}
	    function eslongrucok(ruc){return ( ruc.length == 11 );}
	    function esrucok(ruc)
	    {
	      return (!( esnulo(ruc) || !esnumero(ruc) || !eslongrucok(ruc) || !valruc(ruc) ));
	    }
	    function valruc(valor)
	    {
	      valor = trim(valor)
	      if ( esnumero( valor ) ) {
		if ( valor.length == 8 ){
		  suma = 0
		  for (i=0; i<valor.length-1;i++){
		    digito = valor.charAt(i) - '0';
		    if ( i==0 ) suma += (digito*2)
		    else suma += (digito*(valor.length-i))
		  }
		  resto = suma % 11;
		  if ( resto == 1) resto = 11;
		  if ( resto + ( valor.charAt( valor.length-1 ) - '0' ) == 11 ){
		    return true
		  }
		} else if ( valor.length == 11 ){
		  suma = 0
		  x = 6
		  for (i=0; i<valor.length-1;i++){
		    if ( i == 4 ) x = 8
		    digito = valor.charAt(i) - '0';
		    x--
		    if ( i==0 ) suma += (digito*x)
		    else suma += (digito*x)
		  }
		  resto = suma % 11;
		  resto = 11 - resto
		  
		  if ( resto >= 10) resto = resto - 10;
		  if ( resto == valor.charAt( valor.length-1 ) - '0' ){
		    return true
		  }      
		}
	      }
	      return false
	    }
	function ValidarRUC(evt, campo, valor, Foco){
            var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                valor = trim(valor);
                if ($('#' + campo).val()==4){
                    if (valor.length == 11){
                            if (EsNumero(valor)){
                                suma = 0;
                                x = 6;
                                for (i=0; i<valor.length-1;i++){
                                    if ( i == 4 ) {x = 8;}
                                    digito = valor.charAt(i) - '0';
                                    x--;
                                    if ( i==0 ) { suma += (digito*x); }
                                    else{ suma += (digito*x); }
                                }
                                resto = suma % 11;
                                resto = 11 - resto;					  
                                if ( resto >= 10) {resto = resto - 10;}
                                if ( resto == valor.charAt(valor.length - 1) - '0' ){
                                    Tab(Foco);
                                    return true;
                                }
                            }else{
                                alert('RUC no valido!');
                                    event.returnValue = false;
                                    return false;
                            }
                    }
                    alert('RUC no valido!');
                        event.returnValue = false;
                        return false;    
                }
                Tab(Foco);
                return true;
            }
	}
	function EsNumero(cadena){
            if(!isNaN(cadena)){ return true;}
            return false;
	}	
	function trim(cadena){
            for(i=0; i<cadena.length;){
                if(cadena.charAt(i)==" ") cadena=cadena.substring(i+1, cadena.length);
                else break;
            }	
            for(i=cadena.length-1; i>=0; i=cadena.length-1){
                if(cadena.charAt(i)==" ") cadena=cadena.substring(0, i);
                else break;
            }
            return cadena;
	}	
	function existe(url){
            var req = this.window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
            if (!req) {throw new Error('XMLHttpRequest not supported');}
            req.open('HEAD', url, false);
            req.send(null);
            if (req.status == 200){
                return true;
            }
            return false;
	}	
	
        function permite(elEvento, permitidos) 
        {
		// Variables que definen los caracteres permitidos
		var numeros = "0123456789.,";
		var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ-/";
		var numeros_caracteres = numeros + caracteres;
		var teclas_especiales = [8, 37, 39, 46, 13, 9];
		// 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
		// Seleccionar los caracteres a partir del par�metro de la funci�n
  		switch(permitidos){
    		case 'num':
    			permitidos = numeros;
    			break;
    		case 'car':
                    permitidos = caracteres;
                    break;
            case 'num_car':
                    permitidos = numeros_caracteres;
                    break;
		};
		// Obtener la tecla pulsada
		var evento = elEvento || window.event;
		var codigoCaracter = evento.charCode || evento.keyCode;
		var caracter = String.fromCharCode(codigoCaracter);
		// Comprobar si la tecla pulsada es alguna de las teclas especiales
		// (teclas de borrado y flechas horizontales)
        //alert(codigoCaracter);
		var tecla_especial = false;
		for(var i in teclas_especiales){
                    if(codigoCaracter == teclas_especiales[i]){
                        tecla_especial = true;
                        break;
                    }
		}
		// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
		// o si es una tecla especial
		return permitidos.indexOf(caracter) != -1 || tecla_especial;
	}
        function comphour(h1,m1,h2,m2){
            var hsal=$("#"+h1).val(); var msal=$("#"+m1).val();
            var hreturn=$("#"+h2).val(); var mreturn=$("#"+m2).val();
            var S=0;
            var H=0;
            if(hsal==hreturn && msal==mreturn) 
            return false;
            else{
               if(msal>mreturn) S++;
               else if(msal<mreturn) H++;
               else {S++;H++}
               if(hsal>hreturn) S+=2;
               else if(hsal<hreturn) H+=2;
               else {S+=2;H+=2}
               if(S>=H) return false;
               else return true;
           }
        }
        function SoloHora(evt){	
            var keyPressed = (evt.which) ? evt.which : evt.keyCode
            //alert(keyPressed)
            return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57) && keyPressed!=46);
        }
        function valhora(t,inp){
            //var te = String.fromCharCode(keyPressed);
            var te=$("#"+inp).val();        
            var lm=0;
            if(t=="hr") lm=24;
            else lm=60;
            if(te>=lm){
                $("#"+inp).val(te[0]);            
            }
        }
        function complete(i){	
            var v=$("#"+i).val(); var ti=0;
            if(v.length==2 && v[0]=="0") ti=1;
            if(v<10 && ti==0){ 
                $("#"+i).val();$("#"+i).val("0"+v);
            }
        }
        function moneda(evt){
            var keyPressed = (evt.which) ? evt.which : event.keyCode;
            if(keyPressed==8) return true;// /^\d{2,4}-I{1,2}$/
            var patron=/^[\d{1,9}\.?d{2,2}]/;
            var te = String.fromCharCode(keyPressed);
            return patron.test(te);
            //return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57) &&keyPressed!=46 );
        }	  
	function str_replace(cadena, cambia_esto, por_esto){	//alert(" ==>" + cadena )
            return cadena.split(cambia_esto).join(por_esto);
	}		
	function substr_count(Pajar,Aguja,start,length){
            var c = 0;
            if(start) { Pajar = Pajar.substr(start); }
            if(length) { Pajar = Pajar.substr(0,length); }
            for (var i=0;i<Pajar.length;i++)
                    {
                     if(Aguja == Pajar.substr(i,Aguja.length))
                     c++;
                    }
            return c;
	}
	function VeriEnter(e){
            if (!e) e = window.event; 
                if(e && e.keyCode == 9 || e && e.keyCode == 13)
                    return true
                else
                    return false
	}	
	function SoloNumeros(evt){
            var keyPressed = (evt.which) ? evt.which : event.keyCode
            //return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57) &&keyPressed!=46 );
            //alert(keyPressed);
            if (keyPressed > 47 && keyPressed < 58){
                    return true
            }
            if (keyPressed > 95 && keyPressed < 106){
                    return true
            }
            if (keyPressed == 8 || keyPressed == 46 || keyPressed == 13){
                    return true
            }
            if (keyPressed > 36 && keyPressed < 41){
                    return true
            }
            return false
 	}
    function in_array (needle, haystack, argStrict) {
  // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
  // *     returns 1: true
  // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
  // *     returns 2: false
  // *     example 3: in_array(1, ['1', '2', '3']);
  // *     returns 3: true
  // *     example 3: in_array(1, ['1', '2', '3'], false);
  // *     returns 3: true
  // *     example 4: in_array(1, ['1', '2', '3'], true);
  // *     returns 4: false
  var key = '',
    strict = !! argStrict;

  if (strict) {
    for (key in haystack) {
      if (haystack[key] === needle) {
        return true;
      }
    }
  } else {
    for (key in haystack) {
      if (haystack[key] == needle) {
        return true;
      }
    }
  }

  return false;
}
var Mensaje = function(msg,title, icon, fpostprocess){

        var div = document.createElement("div");
        
        $(div).html(msg);

        $(document).append(div);


        $(div).dialog({
            title: title,
            modal: true,
            show: "scale",
            hide: "scale",
            buttons: {
                "OK": function() {
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                    $(div).remove();
                    if ( typeof(fpostprocess) == 'function' ) fpostprocess();
                }
            },
            open: function(e,ui){
                $(div).find("button").focus();
            },
            close: function(e,ui) {
                if ( typeof(fpostprocess) == 'function' ) fpostprocess();
            }
        });
    };
function validarDNI(text)
{
    var RegExPattern = /(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{8,10})$/;    
    if ((text.match(RegExPattern)) && (text.value!='')) {
        
    } 
    else 
    {
        alert("Ingrese un DNI válido");
    } 
}


function json_encode (mixed_val) {
  // http://kevin.vanzonneveld.net
  // +      original by: Public Domain (http://www.json.org/json2.js)
  // + reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      improved by: Michael White
  // +      input by: felix
  // +      bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *        example 1: json_encode(['e', {pluribus: 'unum'}]);
  // *        returns 1: '[\n    "e",\n    {\n    "pluribus": "unum"\n}\n]'
/*
    http://www.JSON.org/json2.js
    2008-11-19
    Public Domain.
    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
    See http://www.JSON.org/js.html
  */
  var retVal, json = this.window.JSON;
  try {
    if (typeof json === 'object' && typeof json.stringify === 'function') {
      retVal = json.stringify(mixed_val); // Errors will not be caught here if our own equivalent to resource
      //  (an instance of PHPJS_Resource) is used
      if (retVal === undefined) {
        throw new SyntaxError('json_encode');
      }
      return retVal;
    }

    var value = mixed_val;

    var quote = function (string) {
      var escapable = /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
      var meta = { // table of character substitutions
        '\b': '\\b',
        '\t': '\\t',
        '\n': '\\n',
        '\f': '\\f',
        '\r': '\\r',
        '"': '\\"',
        '\\': '\\\\'
      };

      escapable.lastIndex = 0;
      return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
        var c = meta[a];
        return typeof c === 'string' ? c : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
      }) + '"' : '"' + string + '"';
    };

    var str = function (key, holder) {
      var gap = '';
      var indent = '    ';
      var i = 0; // The loop counter.
      var k = ''; // The member key.
      var v = ''; // The member value.
      var length = 0;
      var mind = gap;
      var partial = [];
      var value = holder[key];

      // If the value has a toJSON method, call it to obtain a replacement value.
      if (value && typeof value === 'object' && typeof value.toJSON === 'function') {
        value = value.toJSON(key);
      }

      // What happens next depends on the value's type.
      switch (typeof value) 
      {
      case 'string':
        return quote(value);

      case 'number':
        // JSON numbers must be finite. Encode non-finite numbers as null.
        return isFinite(value) ? String(value) : 'null';

      case 'boolean':
      case 'null':
        // If the value is a boolean or null, convert it to a string. Note:
        // typeof null does not produce 'null'. The case is included here in
        // the remote chance that this gets fixed someday.
        return String(value);

      case 'object':
        // If the type is 'object', we might be dealing with an object or an array or
        // null.
        // Due to a specification blunder in ECMAScript, typeof null is 'object',
        // so watch out for that case.
        if (!value) {
          return 'null';
        }
        if ((this.PHPJS_Resource && value instanceof this.PHPJS_Resource) || (window.PHPJS_Resource && value instanceof window.PHPJS_Resource)) {
          throw new SyntaxError('json_encode');
        }

        // Make an array to hold the partial results of stringifying this object value.
        gap += indent;
        partial = [];

        // Is the value an array?
        if (Object.prototype.toString.apply(value) === '[object Array]') {
          // The value is an array. Stringify every element. Use null as a placeholder
          // for non-JSON values.
          length = value.length;
          for (i = 0; i < length; i += 1) {
            partial[i] = str(i, value) || 'null';
          }

          // Join all of the elements together, separated with commas, and wrap them in
          // brackets.
          v = partial.length === 0 ? '[]' : gap ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' : '[' + partial.join(',') + ']';
          gap = mind;
          return v;
        }

        // Iterate through all of the keys in the object.
        for (k in value) {
          if (Object.hasOwnProperty.call(value, k)) {
            v = str(k, value);
            if (v) {
              partial.push(quote(k) + (gap ? ': ' : ':') + v);
            }
          }
        }

        // Join all of the member texts together, separated with commas,
        // and wrap them in braces.
        v = partial.length === 0 ? '{}' : gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' : '{' + partial.join(',') + '}';
        gap = mind;
        return v;
      case 'undefined':
        // Fall-through
      case 'function':
        // Fall-through
      default:
        throw new SyntaxError('json_encode');
      }
    };

    // Make a fake root object containing our value under the key of ''.
    // Return the result of stringifying the value.
    return str('', {
      '': value
    });

  } catch (err) { // Todo: ensure error handling above throws a SyntaxError in all cases where it could
    // (i.e., when the JSON global is not available and there is an error)
    if (!(err instanceof SyntaxError)) {
      throw new Error('Unexpected error type in json_encode()');
    }
    this.php_js = this.php_js || {};
    this.php_js.last_error_json = 4; // usable by json_last_error()
    return null;
  }
}  