<?php 
include('../config.php');
include('../config_seguridad.php');
function ud($anho,$mes)
{ 
   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
       $dias_febrero = 29; 
   } else { 
       $dias_febrero = 28; 
   }        
   $mes = (int)$mes;
   switch($mes) 
   { 
       case 1: return 31; break; 
       case 2: return $dias_febrero; break; 
       case 3: return 31; break; 
       case 4: return 30; break; 
       case 5: return 31; break; 
       case 6: return 30; break; 
       case 7: return 31; break; 
       case 8: return 31; break; 
       case 9: return 30; break; 
       case 10: return 31; break; 
       case 11: return 30; break; 
       case 12: return 31; break; 
   } 
}
$sql = "SELECT idusuario,login as nombres 
    FROM usuario 
    where estado = 1";
$qs = $ConnS->Query($sql);
$usuarios = array();
while($rows = $ConnS->FetchArray($qs))
{
  $usuarios[$rows['idusuario']] = $rows['nombres'];
}

$sql = "SELECT  f.idfacturacion,
                f.idatencion,
                f.facturacion_fecha,
                f.fecha_pay,
                f.comprobante_serie||'-'||f.comprobante_numero as comprobante_num,
                f.dni_ruc,
                f.nombres,
                case f.idmoneda when 1 then '€' 
                     when 2 then '$'
                     when 3 then 'S/.'
                else '-' end as moneda,
                f.tipo_cambio,
                f.total as importe_to,
                coalesce(t1.total_p,0) as importe_pa,
                f.total-(coalesce(t1.total_p,0)) as importe_pe,
                case f.estado_credito when 0 then 'PENDIENTE' else 'CANCELADO' end as estado,
                f.observaciones,
                DATE_PART('day', now() - f.facturacion_fecha) as dt
              from facturacion as f left outer join 
          (select sum(monto) as total_p,
              idfacturacion
            from facturacion_pagos
           group by idfacturacion ) as t1 on t1.idfacturacion = f.idfacturacion 
              where f.idforma_pago = 10 ";
 
 //$where .= "  and k.anio = '".$_GET['anio']."' and kds.idsituacion = 2";


 if(isset($_GET['q']))    
 {
    $campo = "";
    switch ($_GET['q']) {
      case 1:$campo = "f.nombres";break;
      case 2:$campo = "f.dni_ruc";break;
      case 3:$campo = "f.comprobante_serie||'-'||f.comprobante_numero";break;
      default:$campo="f.nombres";break;
    }
    $where = " and ".$campo." ilike '%".$_GET['q']."%'";
 }
 
 
 switch ($_GET['tt']) 
 {
  case 1:
    if($_GET['anio']!="") $where .= "  and f.anio = '".$_GET['anio']."' "; 
    break;
  case 2:
    $fi = "";
    $ff = "";

    if($_GET['anioi']>$_GET['aniof'])
    {
      $ff = $_GET['anioi']."-".$_GET['mesi']."-".ud($_GET['anioi'],$_GET['mesi']);      
      $fi = $_GET['aniof']."-".$_GET['mesf']."-01";       
    }
    else
    {
      if($_GET['anioi']==$_GET['aniof'])
      {
        if($_GET['mesi']==$_GET['mesf'])
        {
          $ff = $_GET['anioi']."-".$_GET['mesi']."-".ud($_GET['anioi'],$_GET['mesi']);
          $fi = $_GET['anioi']."-".$_GET['mesi']."-01";             
        }
        else
        {
          if($_GET['mesi']>$_GET['mesf'])
          {
            $ff = $_GET['anioi']."-".$_GET['mesi']."-".ud($_GET['anioi'],$_GET['mesi']);
            $fi = $_GET['anioi']."-".$_GET['mesf']."-01";             
          }
          else
          {
            $ff = $_GET['anioi']."-".$_GET['mesf']."-".ud($_GET['anioi'],$_GET['mesf']);
            $fi = $_GET['anioi']."-".$_GET['mesi']."-01";               
          }
        }
      }
      else
      {
        $ff = $_GET['aniof']."-".$_GET['mesf']."-".ud($_GET['aniof'],$_GET['mesf']);      
        $fi = $_GET['anioi']."-".$_GET['mesi']."-01";   
      }
    }
    $where .= " and f.facturacion_fecha between '".$fi."' and  '".$ff."'";
    break;
  case 3: 
      $where .= " and f.facturacion_fecha between '".$Conn->CodFecha($_GET['fechai'])."' and  '".$Conn->CodFecha($_GET['fechaf'])."'";
      break;
  default:
    # code...
    break;
 }

 $sql = $sql.$where." order by f.facturacion_fecha desc limit 200";
 //echo $sql;
 $Consulta = $Conn->Query($sql);

 $cont = 0;
 $bg = "#FFFFFF";
 while($row = $Conn->FetchArray($Consulta))
 {
    $c +=1;  
    $bg = "";
    if($row['estado']=="CANCELADO")
      {
        $bg = "#4BBECD";
      }
    else
      {
        if($row['fecha_pay']!="")
        {
          $bg = "#97EFFB";
        }
      }
  ?>
  <tr <?php if($bg!="") echo 'style="background:'.$bg.'"'; ?>>
    <td align="center"><?php echo str_pad($c,3,'0',0); ?></td>
    <td align="center"><?php echo $row['idatencion'] ?></td>
    <td align="center"><?php echo $Conn->DecFecha($row['facturacion_fecha']) ?></td>
    <td align="center"><b><?php echo $Conn->DecFecha($row['fecha_pay']) ?></b></td>
    <td align="center"><?php echo $row['comprobante_num'] ?></td>
    <td align="center"><?php echo $row['dni_ruc'] ?></td>
    <td align="left"><?php echo $row['nombres']; ?> <span style="font-size:8px; ">(<?php echo strtoupper($row['observaciones']); ?>)</span></td>    
    <td align="right"><?php echo "".number_format($row['importe_to'],2); ?></td>    
    <td align="right"><b><?php echo number_format($row['importe_pa'],2); ?></b></td>    
    <td align="right"><span style="color:#BA1818;font-weight:bold"><?php echo number_format($row['importe_pe'],2); ?></span></td>    
    <td align="center"><?php echo $row['estado'] ?></td>    
    <td align="center"><?php if($row['estado']=="PENDIENTE") echo priority($row['dt']) ?></td>    
    <td align="center" bgcolor="#FFFFFF"><a href="#" class="myButton btn-pay" id="<?php echo $row['idfacturacion'] ?>">P&aacute;gos</a></td>  
  </tr>
  <?php
 }
?>  
<tr>
  <td colspan="20"><?php //echo $sql; ?></td>
</tr>
<?php 
  function priority($d)
  {
      $color = "";
      if($d>=0&&$d<=10)
      {
        //green
        $color = "#41BA55";
      }
      else
      {
        if($d>10&&$d<=15)
        {
            //organge
            $color = "#FB7E4D";
        }
        else
        {
          if($d>15)
          {
            //red
            $color = "#F42E1C";
          }
        }        
      }
      $html = '<div style="width:10px; height:10px; background:'.$color.'">&nbsp;</div>';
      return $html;
  }
?>