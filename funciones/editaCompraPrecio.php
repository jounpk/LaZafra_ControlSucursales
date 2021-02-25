<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$ident = (isset($_GET['id']) and $_GET['id'] != '') ? $_GET['id'] : '';
$costo = (isset($_GET['costo']) and $_GET['costo'] != '') ? $_GET['costo'] : '';

if ($ident == "") {
    //echo "Compra Null";
    errorCarga("Selecciona un producto para continuar. Inténtalo de Nuevo.");
}
$sqlCon = "SELECT id FROM detcompras WHERE id=" . $ident;
$resCon = mysqli_query($link, $sqlCon) or die(errorBD('Problemas al consultar los Detalles de Compra, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant < 0) {
    errorCarga('No se encuentra el Detalle de Compra seleccionado, notifica a tu Administrador');
}
$sql = "UPDATE detcompras SET costoUnitario='$costo' WHERE id='$ident'";
mysqli_query($link, $sql) or die(errorCarga('Error al modificar costo unitario, notifica a tu Administrador'));
$_SESSION['LZmsjSuccessCompra'] = 'Se ha actualizado el costo unitario de la compra.';

header('location: ../Administrador/compras.php');
function errorCarga($error)
{
    $_SESSION['LZmsjInfoCompra'] = $error;
    // echo 'cayo: '.$error;
    header('location: ../Administrador/compras.php');
    exit(0);
}
function errorBD($msj)
{
    //echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoCompra'] = $msj;
    header('location: ../Administrador/compras.php');
    exit(0);
}
?>
