<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$idStock = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$cantMinima = (isset($_POST['cantMinima']) AND $_POST['cantMinima'] != '') ? $_POST['cantMinima'] : '' ;
$cantMaxima = (isset($_POST['cantMaxima']) AND $_POST['cantMaxima'] != '') ? $_POST['cantMaxima'] : '' ;
$anaquel = (isset($_POST['anaquel']) AND $_POST['anaquel'] != '') ? $_POST['anaquel'] : '' ;


if ($idStock == '' || $cantMinima == '' || $cantMaxima == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}

$sqlCon = "SELECT pro.descripcion FROM stocks stk INNER JOIN productos pro ON stk.idProducto=pro.id WHERE (stk.id ='$idStock')";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar Producto en el Stock, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorBD('No se encuentra producto en el Stock con ese nombre, notifica a tu Administrador');
}
$namePro=mysqli_fetch_array($resCon);
$namePro=$namePro['descripcion'];
$sql = "UPDATE stocks SET cantMinima='$cantMinima', cantMaxima='$cantMaxima', anaquel='$anaquel' WHERE id = '$idStock'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar la Cantidad Actual, notifica a tu Administrador'));


echo '1|Se ha modificado el producto <b>'.$namePro.'</b> en el stock.';

function errorBD($error){
    echo '0|'.$error;
    exit(0);
    }
 ?>

