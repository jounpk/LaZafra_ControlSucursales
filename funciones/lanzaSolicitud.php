<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idSucursalSalida=$_POST['idSucursal'];
$idProducto=$_POST['idProducto'];
$cantidad=$_POST['cantidad'];
$idUsuario=$_SESSION['LZFident'];
$idSucursalEntrada=$_SESSION['LZFidSuc'];
#$fecha=date("Y-m-d H:i:s");
$debug=0;
//----------------devBug------------------------------
if ($debug == 1) {
    print_r($_POST);
    echo '<br><br>';
} else {
    error_reporting(0);
}  //-------------Finaliza devBug------------------------------
$sql = "CALL SP_insertaProductoParaSolicitud('$idProducto', '$cantidad', '$idUsuario', '$idSucursalEntrada', '$idSucursalSalida')";
//----------------devBug------------------------------
if ($debug == 1) {
    $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Querys, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
    $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Querys, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$dat = mysqli_fetch_array( $resultXQuery);
echo $dat['estatus'].'|'.$dat['mensaje'].'|'.$sql;
function errorBD($error, $NecesitaRollBack)
{
    $link = $GLOBALS["link"];
    echo '0|' . $error;
    if ($NecesitaRollBack == '1') {
        mysqli_rollback($link);
    }
    if ($GLOBALS['debug'] == 1) {
        echo '<br><br>Det Error: ' . $error;
        echo '<br><br>Error Report: ' . mysqli_error($link);
    } else {
        echo '0|' . $error;
    }
    exit(0);
}
?>
