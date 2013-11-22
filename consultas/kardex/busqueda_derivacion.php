<?php
if(!session_id()){ session_start(); } 
	include("../../config.php");	
	$NroKardex = (isset($_GET["NroKardex"]))?$_GET["NroKardex"]:'';
	$Anio = (isset($_GET["Anio"]))?$_GET["Anio"]:'';
?>
<script>
	function CerrarVentana(){
            window.close();
	}
</script>
<style type="text/css">
body{background:url(../../images/LogoCisneros.jpg)}
.TitDetalle{
    font-family:"Courier New", Courier, monospace;
    font-size:12px		
}
.Titulo {
    font-family:"Courier New", Courier, monospace;
    color:#333333;
    font-size:16px;
    font-weight:bold;
    text-align:center
}
.CajaTexto {
    padding-left:5;
    padding-right:5;
    background:#fff;
    border:#000 solid 1px; 
    color:#000;
    font-family:"Courier New", Courier, monospace;
    background-color:#D9E5F2;
    font-size:12px;
}
-->
</style>
<body topmargin="0">
<table width="723" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" >
  <tr>
    <td colspan="2" align="left" class="TitDetalle" style="color:#009900">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="TitDetalle" style="color:#009900">N&ordm; de Kardex:<?php echo $NroKardex;?></td>
    <td align="right"><input type="button" name="Cerrar" id="Cerrar" value="Cerrar Ventana" style="cursor:pointer; font-family:'comic Sans MS'; font-size:10px" onClick="CerrarVentana();" /></td>
  </tr>
  <tr>
    <td colspan="3"><hr /></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <?php 
  	$SQLCabecera 	= "SELECT dependencia.descripcion, kardex_derivacion.fecha, kardex_derivacion.iddependencia, kardex_derivacion.idusuario, kardex_derivacion.imagen FROM kardex_derivacion INNER JOIN dependencia ON (kardex_derivacion.iddependencia = dependencia.iddependencia) INNER JOIN public.kardex ON (kardex_derivacion.idkardex = public.kardex.idkardex) WHERE kardex.correlativo='$NroKardex' AND kardex.anio= '$Anio' AND kardex.idnotaria='".$_SESSION['notaria']."'";
	$ConsCabecera 	= $Conn->Query($SQLCabecera);
	while($rowcabecera     = $Conn->FetchArray($ConsCabecera)){
		$IdDependencia = $rowcabecera[2];		
		$Responsable = "Ingresado por: ".$rowcabecera[3];		
		$Fecha = $Conn->DecFecha($rowcabecera[1]);
  ?>
 <tr class="TitDetalle">
    <td width="188" align="right">&nbsp;</td>
    <td width="293">&nbsp;</td>
    <td width="276">&nbsp;</td>
 </tr>
 <tr class="TitDetalle">
    <td align="left" colspan="5"><?php echo $Responsable;?></td>
  </tr>
 <tr class="TitDetalle">
    <td width="188" align="right">&nbsp;</td>
    <td width="293">&nbsp;</td>
    <td width="276">&nbsp;</td>
  </tr>
  <tr bgcolor="#750000" style="color:#FFFFFF; font-weight:bold" class="TitDetalle">
    <td width="188" align="right">&nbsp;</td>
    <td width="293"><?php echo $rowcabecera[0];?></td>
    <td width="276"><?php echo $Fecha;?></td>
  </tr>
  <?php 
		$SQLItem = "SELECT kardex_derivacion_situacion.titulo_numero, situacion.descripcion, kardex_derivacion_situacion.fecha, kardex_derivacion_situacion.idsituacion ";
		$SQLItem .=" FROM situacion INNER JOIN kardex_derivacion_situacion ON (situacion.idsituacion = kardex_derivacion_situacion.idsituacion) ";
		$SQLItem .=" INNER JOIN public.kardex ON (kardex_derivacion_situacion.idkardex = public.kardex.idkardex) ";
		$SQLItem .=" WHERE kardex_derivacion_situacion.iddependencia='$IdDependencia' AND kardex.correlativo='$NroKardex' AND kardex.idnotaria='".$_SESSION['notaria']."' order by kardex_derivacion_situacion.fecha asc ";
		$ConsItem	= $Conn->Query($SQLItem);
		while($rowitem	= $Conn->FetchArray($ConsItem)){
			$IdSituacion 	= $rowitem[3];
			$NroTitulo		= $rowitem[0];
			
			$FechaTitulo = $Conn->DecFecha($rowitem[2]);
  ?>  
     <tr class="TitDetalle">
       <td align="center">&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr class="TitDetalle" bgcolor="#E1FFE1">
       <td width="188" align="center"><?php echo $rowitem[0];?></td>
       <td width="293"><?php echo $rowitem[1];?></td>
       <td width="276"><?php echo $FechaTitulo;?></td>
     </tr>
  <?php 
			$SQLSituacion  	= "SELECT kardex_derivacion_situacion.titulo_numero, kardex_derivacion_situacion.monto, kardex_derivacion_situacion.asiento_numero, ";
			$SQLSituacion	.= " kardex_derivacion_situacion.partida_numero, kardex_derivacion_situacion.observacion, kardex.correlativo, kardex_derivacion_situacion.fecha, ";
			$SQLSituacion	.= " kardex_derivacion_situacion.presentacion_fecha, kardex_derivacion_situacion.vencimiento_fecha, kardex_derivacion_situacion.subsanacion_fecha ";
			$SQLSituacion	.= " FROM kardex INNER JOIN kardex_derivacion_situacion ON (kardex.idkardex = kardex_derivacion_situacion.idkardex) ";
			$SQLSituacion	.= " WHERE kardex_derivacion_situacion.iddependencia='$IdDependencia' AND kardex_derivacion_situacion.idsituacion='$IdSituacion' AND kardex.correlativo='$NroKardex' AND TRIM(kardex_derivacion_situacion.titulo_numero)='$NroTitulo'";
			$ConsSituacion	= $Conn->Query($SQLSituacion);
			while($rowsituacion	= $Conn->FetchArray($ConsSituacion)){
				$NroTitulo 		= $rowsituacion[0];
				$Monto			= number_format($rowsituacion[1],2);
				$NroAsiento		= $rowsituacion[2];
				$NroPartida		= $rowsituacion[3];
				$Observacion	= utf8_decode($rowsituacion[4]);
				$gifObservacion = $rowsituacion[5];//.".gif";
				$gif = "image_derivacion.php?NroKardex=".$gifObservacion;				
				$FechaSituacion = $Conn->DecFecha($rowsituacion[6]);				
				$FechaPresentacion = $Conn->DecFecha($rowsituacion[7]);				
				$FechaVencimiento = $Conn->DecFecha($rowsituacion[8]);				
				$Subsanacion = $Conn->DecFecha($rowsituacion[9]);				
        if($IdSituacion==1){
  ?>   
  <tr align="center">
    <td colspan="3">
    <table width="300" border="0" cellspacing="0" cellpadding="0">
      <tr class="TitDetalle">
        <td width="103" align="right">N&ordm; Titulo:</td>
        <td width="197"><?php echo $NroTitulo;?></td>
      </tr>
      <tr class="TitDetalle">
        <td align="right">Fecha:</td>
        <td><?php echo $FechaSituacion;?></td>
      </tr>
      <tr class="TitDetalle">
        <td align="right">Monto:</td>
        <td><?php echo $Monto;?></td>
      </tr>
      <tr class="TitDetalle">
        <td valign="top" align="right">Observación:</td>
        <td valign="top"><?php echo $Observacion;?></td>
      </tr>
    </table>    
    </td>
  </tr>
  <?php 
        }else{
            if($IdSituacion==2){
   ?>
   <tr align="center">
   <td colspan="3">
   <table width="300" border="0" cellspacing="0" cellpadding="0">
     <tr class="TitDetalle">
       <td width="104" align="right">Fecha:</td>
       <td width="196"><?php echo $FechaSituacion;?></td>
     </tr>
     <tr class="TitDetalle">
       <td align="right">N&ordm; Asiento:</td>
       <td><?php echo $NroAsiento;?></td>
     </tr>
     <tr class="TitDetalle">
       <td align="right">N&ordm; Partida:</td>
       <td><?php echo $NroPartida;?></td>
     </tr>
     <tr class="TitDetalle">
       <td align="right">Monto:</td>
       <td><?php echo $Monto;?></td>
     </tr>
     <tr class="TitDetalle">
       <td align="right" valign="top">Observación:</td>
	<td valign="top"><?php echo $Observacion;?><br><a target="_top" href="<?php echo $gif;?>"> Ver Obervaci&oacute;n</a></td>
     </tr>
   </table>   </td>
   </tr>
   <?php 
    }else{
            if($IdSituacion==3){
   ?>
   <tr align="center">
     <td colspan="3"><table width="483" border="0" cellspacing="0" cellpadding="0">
       <tr class="TitDetalle">
         <td width="194" align="right">Fecha de Presentaci&oacute;n:</td>
         <td width="289"><?php echo $FechaPresentacion;?></td>
       </tr>
       <tr class="TitDetalle">
         <td align="right">Fecha de Vencimiento:</td>
         <td><?php echo $FechaVencimiento;?></td>
       </tr>
       <tr class="TitDetalle">
         <td align="right">Ultimo día de Subsanaci&oacute;n:</td>
         <td><?php echo $Subsanacion;?></td>
       </tr>
       <tr class="TitDetalle">
         <td valign="top" align="right">Observaci&oacute;n:</td>
	 <td valign="top"><?php echo $Observacion;?><br><a target="_top" href="<?php echo $gif;?>"> Ver Obervaci&oacute;n</a></td>
       </tr>
     </table></td>
   </tr>
   <?php 
   		}else{
			if($IdSituacion==4 || $IdSituacion==5 || $IdSituacion==6 || $IdSituacion==7 || $IdSituacion==8){
   ?>
   <tr align="center">
     <td colspan="3"><table width="300" border="0" cellspacing="0" cellpadding="0">
       <tr class="TitDetalle">
         <td width="104" align="right">Fecha:</td>
         <td width="196"><?php echo $FechaSituacion;?></td>
       </tr>
       <tr class="TitDetalle">
         <td align="right">Monto:</td>
         <td><?php echo $Monto;?></td>
       </tr>
       <tr class="TitDetalle">
         <td valign="top" align="right">Observacion:</td>
	 <td valign="top"><?php echo $Observacion;?><br><a target="_top" href="<?php echo $gif;?>" class='verobervacion'/> Ver Observaci&oacute;n </a></td>
       </tr>
     </table></td>
   </tr>
   <tr align="center">
     <td colspan="3">&nbsp;</td>
   </tr>
<?php 	
			}
  			}
  			}
		}
  			}
  		}
  	}
?>
  <tr>
    <td colspan="3"><hr /></td>
  </tr>
</table>
</body>
