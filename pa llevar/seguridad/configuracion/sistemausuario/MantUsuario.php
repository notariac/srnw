<?php
	include("../../config.php");

	include("../../clases/main.php");

	CuerpoSuperior("Mantenimiento de Sistemas por Usuario");

	$Guardar 	= (isset($_GET["Op"]))?$_GET["Op"]:'';
	$Id 		= (isset($_GET["Id"]))?$_GET["Id"]:'';

	if ($Id != '')
	{
		$SQL 		= "SELECT nombres FROM usuario WHERE idusuario = '$Id'";
		$Consulta	= $Conn->Query($SQL);
		$row		= $Conn->FetchArray($Consulta);

		$Guardar = $Guardar."&Id2=".$Id;
	}
?>
<script>
	var cont 	= 0; 
	var nDest 	= 0;
	var nDestC 	= 0;
	var val		= 0;
	
	function Cancelar()
	{
		location.href = 'index.php';
	}

        function TraerPerifl()
        {
            $.ajax({
                    url:'../../clases/perfil.php',
                    type:'POST',
                    async:true,
                    data:'IdSistema=' + $('#IdSistema').val(),
                    success:function(data){
                        $("#DivPerfil").html(data);
                     }
            })
        }

        function AgregarPerfil()
	{
		var Dest = 1;
		var x ;
		var x1;

		var IdSistema 	= document.getElementById('IdSistema').value;
		var Sistema 	= document.getElementById("IdSistema").options[document.getElementById("IdSistema").selectedIndex].text;
				
		var IdPerfil 	= document.getElementById('IdPerfil').value;
		var Perfil 		= document.getElementById("IdPerfil").options[document.getElementById("IdPerfil").selectedIndex].text;
		
		var IdAdmin 	= document.getElementById('Admin').value;
		var Admin 		= document.getElementById("Admin").options[document.getElementById("Admin").selectedIndex].text;

                val = 0;
                for (x = 1; x <= document.getElementById('Cont').value; x++)
                {
                    try
                    {
                        if((eval("document.getElementById('IdSistema" + x + "').value")== IdSistema) && (eval("document.getElementById('IdPerfil" + x + "').value")== IdPerfil))
                        {
                                alert("El Perfil " + Perfil + " se Encuentra Agregado para Este Sistema");
                                val=1;
                        }
                    }catch(exp)
                    {

                    }
                }

		if (val == 0)
		{
			if (document.getElementById('IdPerfil').value != 0)
			{
				nDest 	= nDest + 1;
				nDestC	= nDestC + 1;

				var miTabla = document.getElementById('ListaMenu').insertRow(nDest);
				var celda1=miTabla.insertCell(0);
				var celda2=miTabla.insertCell(1);
				var celda3=miTabla.insertCell(2);
				var celda4=miTabla.insertCell(3);

				celda1.innerHTML = "<input type='hidden' name='0formd" + nDestC + "_idusuario' id='IdUsuario" + nDestC + "' value='<?=$Id?>'/><input name='0formd" + nDestC + "_idsistema' id='IdSistema" + nDestC + "' type='hidden' value='" + IdSistema + "' />" + Sistema ;
				celda2.innerHTML = "<input name='0formd" + nDestC + "_idperfil' id='IdPerfil" + nDestC + "' type='hidden' value='" + IdPerfil + "' />" + Perfil;
				celda3.innerHTML = "<input name='0formd" + nDestC + "_administrador' id='Administrador" + nDestC + "' type='hidden' value='" + IdAdmin + "' />" + Admin;
				celda4.innerHTML = "<img src='<?=$UrlDir?>images/quitar.png' width='16' height='16' onclick='QuitaFilaD(this, 1);' style='cursor:pointer'/>";

				document.getElementById('Cont').value = nDestC;
				//nDest = nDestC;
			}else{
				alert("Seleccione el Perfil a Registrar");
			}
		}
	}

        function QuitaFilaD(x, det)
	{	
		while (x.tagName.toLowerCase() !='tr')
		{
			if(x.parentElement)
				x=x.parentElement;
			else if(x.parentNode)
				x=x.parentNode;
			else
				return;
		}
		
		var rowNum=x.rowIndex;
		while (x.tagName.toLowerCase() !='table')
		{
			if(x.parentElement)
				x=x.parentElement;
			else if(x.parentNode)
				x=x.parentNode;
			else
				return;
		}
		x.deleteRow(rowNum);
		if (det==1)
		{
			nDest = nDest - 1;
		}
	}
	function ValidarForm()
	{
		document.form1.submit();
	}
</script>
<script>

	function Sobre(obj)
	{
		obj.style.width=90;
	}
	function Fuera(obj)
	{
		obj.style.width=85;
	}

</script>
<form action="guardar.php?Op=<?=$Guardar?>" method="post" enctype="multipart/form-data" name="form1">
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" bgcolor="#5398FF" class="Titulo">Mantenimiento de Sistema por Usuario</td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td width="75" class="MantTitulo">Codigo :</td>
      <td width="424" class="MantItem" colspan="4">
        <input type="text" name="Codigo" id="Codigo" class="inputtext" style="text-transform:uppercase" readonly="readonly" value="<?=(isset($Id))?$Id:''?>"/>      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Usuario :</td>
      <td class="MantItem" colspan="4">
        <input name="Descripcion" readonly="readonly" type="text" class="inputtext" id="Descripcion" style="text-transform:uppercase" size="60" maxlength="60" value="<?=(isset($row[0]))?$row[0]:''?>"/>      </td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td class="MantTitulo">Sistemas :</td>
      <td class="MantItem">
        <select name="IdSistema" id="IdSistema" class="select" onChange="TraerPerifl();">
        <?
            $SQL1 	= "SELECT * FROM sistemas WHERE estado=1";
            $Consulta1	= $Conn->Query($SQL1);
            while($row1 = $Conn->FetchArray($Consulta1))
            {
        ?>
            <option value="<?=$row1[0]?>"><?=$row1[1]?></option>
        <?
            }
        ?>
        </select>      </td>
      <td><div id="DivPerfil">&nbsp;</div></td>
      <td><select name="Admin" id="Admin" class="select">
        <option value="0">No Admin</option>
        <option value="1">Admin</option>
      </select>
      </td>
      <td><img src="<?=$UrlDir?>images/agregar.png" width="16" height="16" style="cursor:pointer" onclick="AgregarPerfil();" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr><input type="hidden" name="Cont" id="Cont"/>
    <tr>
      <td colspan="5" align="center">
          <table width="400" cellspacing="1" border="0" align="center" id="ListaMenu">
              <thead>
        <tr class="cabecera">
          <td align="center">Sistema</td>
          <td align="center">Perfil</td>
          <td align="center">Admin</td>
          <td width="31">&nbsp;</td>
        </tr>
              </thead>
        <?php
			$Contador = 0;

			$SQL2   = "SELECT usuario_sistemas.idsistema, sistemas.descripcion, usuario_sistemas.idperfil, perfiles.descripcion, usuario_sistemas.administrador ";
			$SQL2  .= "FROM usuario_sistemas INNER JOIN sistemas ON sistemas.idsistema = usuario_sistemas.idsistema ";
                        $SQL2  .= "INNER JOIN perfiles ON perfiles.idperfil = usuario_sistemas.idperfil ";
			$SQL2  .= "WHERE usuario_sistemas.idusuario='$Id'";
			$Consulta2 = $Conn->Query($SQL2);
			while($row2 = $Conn->FetchArray($Consulta2))
			{
				$Contador = $Contador + 1;
		?>
        <tr>
          <td align="left">
              <input type="hidden" name="0formd<?=$Contador?>_idusuario" id="IdUsuario<?=$Contador?>" value="<?=$Id?>"/>
              <input type="hidden" name="0formd<?=$Contador?>_idsistema" id="IdSistema<?=$Contador?>" value="<?=$row2[0]?>"/><?=$row2[1]?>          </td>
          <td><input name="0formd<?=$Contador?>_idperfil" id="IdPerfil<?=$Contador?>" type="hidden" value="<?=$row2[2]?>" /><?=$row2[3]?></td>
          <td><input name="0formd<?=$Contador?>_administrador" id="Administrador<?=$Contador?>" type="hidden" value="<?=$row2[4]?>" /><? if ($row2[4]==0) {echo 'No Adminb';} else { echo 'Admin';}?></td>
          <td align="center"><img src='<?=$UrlDir?>images/quitar.png' width='16' height='16' onclick='QuitaFilaD(this, 1);' style='cursor:pointer'/></td>
        </tr>
        <?php } ?>
      </table></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr class="Pie">
      <td colspan="5" align="center" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="200" align="center"><img id="BtnAceptar" src="../../images/btnGuardar.png" width="85" style="cursor:pointer" onclick="ValidarForm();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
        <td>&nbsp;</td>
        <td width="200" align="center"><img id="BtnCancelar" src="../../images/btnCancelar.png" width="85" style="cursor:pointer" onclick="Cancelar();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<script>
	document.getElementById("Cont").value=<?=$Contador?>;
	cont 	= <?=$Contador?>;
	nDest 	= <?=$Contador?>;
	nDestC 	= <?=$Contador?>;

        TraerPerifl();
</script>
<?
    CuerpoInferior();
?>