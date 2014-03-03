<?php
if(!session_id()){ session_start(); }	
    include('../../config.php');	
    include("../../libs/clasemantem.php");	
    $Op		= $_GET["Op"];
    $IdKardex	= $_GET["IdKardex"];
    $IdDependencia	= $_GET["IdDependencia"];
    $IdSituacion	= $_GET["IdSituacion"];	
    $mantem = new dbMantimiento($Conn->GetConexion());
    $Conn->NuevaTransaccion();	
    if ($Op==3){
        $Sql = "DELETE FROM kardex_derivacion_situacion WHERE idkardex='$IdKardex' AND iddependencia='$IdDependencia' AND idsituacion='$IdSituacion'";
        $Consulta = $Conn->Query($Sql);
    }else{
        if ($IdDependencia==''){
            $Sql = $mantem->__dbMantenimiento($_POST, "formS", "kardex_derivacion_situacion", $Op);
            $Consulta = $Conn->Query($Sql);
        }else{
            $Sql = "DELETE FROM kardex_derivacion_situacion WHERE idkardex='$IdKardex' AND iddependencia='$IdDependencia' AND idsituacion='$IdSituacion'";
            $Consulta = $Conn->Query($Sql);			
            $Cont	= $_POST["ConSituacion".$IdDependencia];
            for ($i=1; $i<=$Cont; $i+= 1){			
                if (isset($_POST["0formD".$IdDependencia."S".$i."_idkardex"])){	
                    if ($IdSituacion==$_POST["0formD".$IdDependencia."S".$i."_idsituacion"]){
                        $nPost = array();
                        $FormN = "formD".$IdDependencia."S".$i;
                        foreach($_POST as $ind=>$val){
                            if(stripos($ind, $FormN.'_')!==false){
                                $nPost[$ind] = $val;
                            }
                        }
                        $mantem = new dbMantimiento($Conn->GetConexion());
                        $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "kardex_derivacion_situacion", 0);
                        $Consulta2 = $Conn->Query($Sql2);
                    }
                }
            }
        }
    }
    $Conn->TerminarTransaccion("COMMIT");
?>