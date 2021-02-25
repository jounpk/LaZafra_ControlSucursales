<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$id = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$nombre = (isset($_POST['editNombre']) && $_POST['editNombre'] != '') ? trim($_POST['editNombre']) : '' ;
$telefono = (isset($_POST['editTelefono']) && $_POST['editTelefono'] != '') ? $_POST['editTelefono'] : '' ;
$correo = (isset($_POST['editCorreoSucursal']) && $_POST['editCorreoSucursal'] != '') ? $_POST['editCorreoSucursal'] : '' ;



if ($id < 1) {
  errorBD('No se reconoció el Contacto, intenta de nuevo, si persiste notifica a tu Administrador');
}
if ($nombre != '') {
  function is_valid_email($str){
    $matches = null;
    return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
  }
} else {
   errorBD('Debes ingresar un Nombre ');
  # echo '<br> Línea 30';
}
if (!is_valid_email($correo)) {
  $_SESSION['LZFmsjCatalogoProveedores'] = 'El correo no es válido, ingresa un correo válido para el Proveedor';
#  echo '<br>Correo no válido';
#  echo '<br> Línea 39';
}
#echo '<br> Línea 41';
$sqlCon = "SELECT * FROM contactosprov WHERE nombre = '$nombre'  AND correoSucursal = '$correo' AND id != '$id'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los contactos, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);
#echo '<br> Línea 45';
if ($cant > 0) {
  errorBD('Ya se encuentra un contacto con ese nombre y correo, revisa en el listado, en caso contrario, notifica a tu Administrador');
#  echo '<br> Línea 44';
}
#echo '<br> Línea 50';
$sql = "UPDATE contactosprov SET nombre = '$nombre', 
 telefono = '$telefono', correoSucursal = '$correo' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al agregar al Proveedor, notifique a su Administrador'));


#echo '<br>$sql: '.$sql;
#echo '<br> Línea 54';
#echo '<br> Aquí ya editó y regresó a la vista de Proveedores';
$_SESSION['LZFmsjSuccessCatalogoProveedores'] = 'El contacto '.$nombre.' se ha actualizado correctamente';
header('location: ../Corporativo/catalogoProveedores.php');
exit(0);

function errorBD($error){
#  echo '<br> Línea 61';
#  echo '<br> error';
  $_SESSION['LZFmsjCatalogoProveedores'] = $error;
  header('location: ../Corporativo/catalogoProveedores.php');
  exit(0);
}
 ?>
