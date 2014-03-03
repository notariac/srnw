<?php
if(!session_id()){ session_start(); }	
    include('../../config.php');	
    include("../../libs/clasemantem.php");	
    $Cont	= $_POST["ConParticipantes"];
    for ($i=1; $i<=$Cont; $i+= 1){			
        if (isset($_POST["0formD".$i."_idkardex"])){	
            $Firmo = $_POST['0formD'.$i.'_firmo'];
            $Fecha = $_POST['0formD'.$i.'_firmofecha'];			
            $Sql2 = "update kardex_participantes set firmo=".$Firmo.", firmofecha='".$Conn->CodFecha($Fecha)."' where idkardex=".$_POST['0formD'.$i.'_idkardex'].' AND idparticipante='.$_POST['0formD'.$i.'_idparticipante'];
            $Consulta2 = $Conn->Query($Sql2);
        }
    }
    $Conn->TerminarTransaccion("COMMIT");
    $Res=1;
    $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>