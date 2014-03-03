<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/claseindex.php");
    $SqlConsulta = $Conn->Query("SELECT * FROM consulta WHERE idconsulta='1'");
    $costo=$Conn->FetchArray($SqlConsulta);
    CuerpoSuperior("Mantenimiento de Costo por Consulta");
?>
<form name="form1" action="MantModulos.php" method="POST" >
    <table align="center">
        <tr>
            <td>
                Búsqueda :
            </td>
            <td>
                <input type="text" id="valor1" name="valor1" value="<?php echo number_format($costo[1],2);?>" style="text-align: center;">
            </td>
        </tr>
        <tr>
            <td>
                Descarga :
            </td>
            <td>
                <input type="text" id="valor2" name="valor2" value="<?php echo number_format($costo[2],2);?>" style="text-align: center;">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="guardar" value="Guardar">
                <input type="button" name="cancelar" value="Cancelar" onclick="location.href='http://localhost/seguridad/';">
            </td>
        </tr>
    </table>
</form>
<?php
    Pie();
    CuerpoInferior();
?>