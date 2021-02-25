<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$ident = (isset($_GET['id']) and $_GET['id'] != '') ? $_GET['id'] : '';
$cantidad = (isset($_GET['cant']) and $_GET['cant'] != '') ? $_GET['cant'] : '';

if ($ident == "") {
    //echo "Compra Null";
    errorCarga("Selecciona un producto para continuar. Inténtalo de Nuevo.");
}
if($cantidad<0){
    errorCarga("Verifica la cantidad. Inténtalo de Nuevo.");
}
$sqlCon="SELECT det.id, pro.descripcion FROM dettraspasos det INNER JOIN productos pro ON det.idProducto=pro.id  WHERE det.id=".$ident;
$resCon = mysqli_query($link, $sqlCon) or die(errorBD('Problemas al consultar los Detalles de Traspasos, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant < 0) {
    errorCarga('No se encuentra el Detalle de Traspasos seleccionado, notifica a tu Administrador');
}
$arrayCon=mysqli_fetch_array($resCon);
$nombrePro=$arrayCon['descripcion'];
$sql = "UPDATE dettraspasos SET cantEnvio='$cantidad' WHERE id='$ident'";
mysqli_query($link, $sql) or die(errorCarga('Error al modificar la cantidad, notifica a tu Administrador'));
$_SESSION['LZmsjSuccessTraspasos'] = 'Se ha actualizado la cantidad <b>'.$nombrePro.'<b>.';

header('location: ../Administrador/traspasos.php');
function errorCarga($error)
{
    $_SESSION['LZmsjInfoTraspasos'] = $error;
    // echo 'cayo: '.$error;
    header('location: ../Administrador/traspasos.php');
    exit(0);
}
function errorBD($msj)
{
    //echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoTraspasos'] = $msj;
    header('location: ../Administrador/traspasos.php');
    exit(0);
}
?>
