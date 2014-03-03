// JavaScript Document
	function CambiarFoco(evt, Obj)
   	{
    	//var keyPressed = (evt.which) ? evt.which : event.keyCode
	/*	var t=0;
		
		if (window.event)
	  	{
	           if (window.event.keyCode==13)
				t=1// Aqui escribe el nombre tu funcion que hace la busqueda...
	  	}
	  	else
       {            //Esto es para Firefox y creo otros navegadores
			if (e)
			{
			  if(e.which==13)
				t=1//Igual que arriba
			}
	   }*/
		//alert(t);
		
		if (VeriEnter(evt) )
		//if (t==1)
        {	
		
			Tab(Obj);
			return false;
		}
  	}
	
	function Tab(Obj)
	{
		document.getElementById(Obj).focus();
	}
	
	function existe(url) 
	{
		var req = this.window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		if (!req) {throw new Error('XMLHttpRequest not supported');}
		req.open('HEAD', url, false);
		req.send(null);
		if (req.status == 200){
			return true;
		}
		return false;
	}
	
function permite(elEvento, permitidos) {
// Variables que definen los caracteres permitidos

var numeros = "0123456789.,";
var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ-/";
var numeros_caracteres = numeros + caracteres;
var teclas_especiales = [8, 37, 39, 46, 13];
// 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
// Seleccionar los caracteres a partir del parámetro de la función
  switch(permitidos) {
    case 'num':
    permitidos = numeros;
    break;
    case 'car':
    permitidos = caracteres;
    break;
    case 'num_car':
    permitidos = numeros_caracteres;
    break;
}
// Obtener la tecla pulsada
var evento = elEvento || window.event;
var codigoCaracter = evento.charCode || evento.keyCode;
var caracter = String.fromCharCode(codigoCaracter);
// Comprobar si la tecla pulsada es alguna de las teclas especiales
// (teclas de borrado y flechas horizontales)
var tecla_especial = false;
for(var i in teclas_especiales) {
    if(codigoCaracter == teclas_especiales[i]) {
    tecla_especial = true;
    break;
  }
}
// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
// o si es una tecla especial
return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

function comphour(h1,m1,h2,m2)
{
	var hsal=$("#"+h1).val(); var msal=$("#"+m1).val();
	var hreturn=$("#"+h2).val(); var mreturn=$("#"+m2).val();
	var S=0;
	var H=0;
	 if(hsal==hreturn && msal==mreturn) 
	 return false;
	 else
	  {
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
function SoloHora(evt)
{	
	var keyPressed = (evt.which) ? evt.which : evt.keyCode
	//alert(keyPressed)
	return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57) && keyPressed!=46);
	
	
}
function valhora(t,inp)
{
    //var te = String.fromCharCode(keyPressed);
	var te=$("#"+inp).val();        
	var lm=0;
	if(t=="hr") lm=24;
	else lm=60;
	if(te>=lm)
	{
		$("#"+inp).val(te[0]);            
	}
	
}
function complete(i)
{	
	var v=$("#"+i).val(); var ti=0;
	if(v.length==2 && v[0]=="0") ti=1;
	if(v<10 && ti==0)
	{ 
		$("#"+i).val();$("#"+i).val("0"+v);
	}
	
 }
 
 function moneda(evt)
      {
        var keyPressed = (evt.which) ? evt.which : event.keyCode;
        if(keyPressed==8) return true;// /^\d{2,4}-I{1,2}$/
        var patron=/^[\d{1,9}\.?d{2,2}]/;
        var te = String.fromCharCode(keyPressed);
        return patron.test(te);
        //return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57) &&keyPressed!=46 );
      }
	  
	  
	function str_replace(cadena, cambia_esto, por_esto) 
	{	//alert(" ==>" + cadena )
     	 return cadena.split(cambia_esto).join(por_esto);
	}
	
	
	function substr_count(Pajar,Aguja,start,length)
	{
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
	function VeriEnter(e)
	{
	   if (!e) e = window.event; 
	  	if(e && e.keyCode == 9 || e && e.keyCode == 13)
			return true
		else
		  return false
	}