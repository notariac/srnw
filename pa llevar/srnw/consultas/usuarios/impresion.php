<?php 
if(!session_id()){ session_start(); }	
    include("../../config.php");
    include('../../config_seguridad.php');	
    $IdUsuario	= $_SESSION["id_user"];
    $Usuario	= $_SESSION["Usuario"];
    $Desde	= isset($_GET["Desde"])?$_GET["Desde"]:'';
    $Hasta	= isset($_GET["Hasta"])?$_GET["Hasta"]:'';	
?>
<style>
.FilaCabecera{
    border:1px #999 dashed;
}
.TextoCabecera{
    text-align:center;
    font-family:"Arial";
    font-size:10px;
    background-color:#CCCCCC
}
.TextoFila{
    font-family:"Arial";
    font-size:10px;
}
.BorderDiv{
    border:1px #666 dashed; 
    height:20px;
    background:#FFFFC6;		
}
</style>
<title>Reporte de Escritura</title>
<div class="BorderDiv" align="right">
  <label>
    <input type="submit" name="btnimprimir" id="btnimprimir" value="Imprimir" style="font-family:'Comic Sans MS', cursive; font-size:10px; width:120; cursor:pointer" onClick="javascript:window.print();">
  </label>
</div>
<div style="height:10px"></div>
<div style="height:30px; vertical-align:top" class="TextoFila">
    <?php echo "Escritura pendiente del Usuario $Usuario";?>
</div>
<div style="height:80%;">
<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#000000" align="center">
  <tr align="center" class="TextoCabecera">
    <td width="141" height="15">Kardex</td>
    <td width="551">Servicio</td>
    <td width="551">Cliente</td>
    <td width="315" align="center">Fecha</td>
    <td width="309">Generado</td>
    <td width="309">Cancelado</td>
  </tr>
<?php
$SQL  = "SELECT atencion_detalle.correlativo, 
                servicio.descripcion as servicio, 
                coalesce(c.nombres,'')||' '||coalesce(c.ape_paterno,'')||' '||coalesce(c.ap_materno) as cliente, 
                atencion.fecha, 
                atencion_detalle.estado as estadod,
                atencion.estado ";
$SQL .= " FROM atencion_detalle INNER JOIN servicio ON (atencion_detalle.idservicio = servicio.idservicio) INNER JOIN atencion ON (atencion_detalle.idatencion = atencion.idatencion)   inner join cliente as c on c.idcliente = atencion.idcliente" ;
$SQL .= " WHERE servicio.legal = 1 AND atencion.fecha BETWEEN  '".$Conn->CodFecha($Desde)."' AND '".$Conn->CodFecha($Hasta)."' AND atencion.idusuario='$IdUsuario' ";
$SQL .= " ORDER BY atencion.fecha ASC ";	

$Consulta 	= $Conn->Query($SQL);
    while($row	= $Conn->FetchArray($Consulta)){
        $Fecha = $Conn->DecFecha($row[3]);
?>
  <tr class="TextoFila">
    <td align="center"><?php echo $row['correlativo'];?></td>
    <td style="padding-left:5px"><?php echo $row['servicio'];?></td>
    <td style="padding-left:5px"><?php echo strtoupper(utf8_decode($row['cliente']));?></td>
    <td align="center"><?php echo $Fecha;?></td>
    <td align="center"><?php echo $d='';$row['estadod']==2?$d="GENERADO":$d="PENDIENTE";echo $d;?></td>
    <td align="center"><?php echo $d='';$row['estado']==1?$d="CANCELADO":$d="SIN CANCELAR";echo $d;?></td>
  </tr>
<?php 
    }
?>
  </table>
</div>
<div style="height:10px"></div>
<div class="BorderDiv" align="right"></div>