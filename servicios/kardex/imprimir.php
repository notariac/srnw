<?php
session_start();
include("../../config.php");	

$IdKardex = (isset($_GET["IdKardex"]))?$_GET["IdKardex"]:'';	
$SQL      = "SELECT archivo,digital FROM kardex WHERE idkardex='$IdKardex'";
$Consulta = $Conn->Query($SQL);
$row      = $Conn->FetchArray($Consulta);
if($row['digital']=="")
{

//abre kardex digital
  if($row['archivo']!='')
  {
  ?>
    <script>
    document.location.href = "archivos/<?php echo $_SESSION['notaria']; ?>/<?php echo $row[0];?>";
    </script>
   <?php
  }
  else
    { echo 
  	   '<script>
        document.location.href = "../../error_url.php";
        </script>';
      } 

}//fin if $row
//abre kardex en word
else{
?>
<script>
    document.location.href = "../../editor/print.php?idkardex=<?php echo $IdKardex;?>";
</script>
<?php } ?>