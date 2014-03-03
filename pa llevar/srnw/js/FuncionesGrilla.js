	//Funciones de Pintado de GRILLA
	function color_over(Fila)
	{
		var color="rgb(153, 153, 153)";
		if(document.all) {color="#999999";}
		if (Fila.style.backgroundColor!=color)
		{
   			Fila.style.backgroundColor="#cccccc";
		}
	}
	
	function color_out(Fila)
	{
		var color="rgb(153, 153, 153)";
		if(document.all) color="#999999";
		if (Fila.style.backgroundColor!=color)
		{
   			Fila.style.backgroundColor="#ffffff";
		}
	}
	
	function Limpiar(Fila) 
	{ 
		document.getElementById(Fila).style.backgroundColor="#ffffff"
	} 
	
	function SeleccionaId(obj)
	{
		Id = 'Id=' + obj.id;
		IdAnt=Id2;
		Id2 = obj.id;
		if (IdAnt!='')
		{
			Limpiar(IdAnt);
		}
		obj.style.backgroundColor="#999999";
	}
	
	function BuscarG(Op)
	{
		//var Campo = document.getElementById('Campo').value
		var Valor = document.getElementById('Valor').value
		var Op2 = ''
		if (Op!=0)
		{
			Op2 = '&Op=' + Op;
		}
		location.href='index.php?Valor=' + Valor + '&pagina=' + Pagina + Op2; //Campo=' + Campo + '&
	}
	
	function ValidarEnterG(evt, Op)
      {
        var keyPressed = (evt.which) ? evt.which : event.keyCode
		if (keyPressed==13)
        {
			Buscar(Op);
			event.returnValue = false;
		}
      }