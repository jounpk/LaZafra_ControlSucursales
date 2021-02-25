<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idProv = (!empty($_POST['idProv'])) ? $_POST['idProv'] : 0 ;
$nombre = (isset($_POST['nNombre']) && $_POST['nNombre'] != '') ? trim($_POST['nNombre']) : '' ;
$tel = (isset($_POST['nTelefono']) && $_POST['nTelefono'] != '') ? $_POST['nTelefono'] : '' ;
$correo = (isset($_POST['nCorreo']) && $_POST['nCorreo'] != '') ? $_POST['nCorreo'] : '' ;
$userReg = $_SESSION['LZFident'];
$texto = '';
/*
$texto .= '<br> ############################################# <br>';
#$texto .= '<br>Print:<br>';
#$texto .= print_r($_POST);
$texto .= '<br>$idProv: '.$idProv;
$texto .= '<br>$nombre: '.$nombre;
$texto .= '<br>$tel: '.$tel;
$texto .= '<br>$correo: '.$correo;
$texto .= '<br>$userReg: '.$userReg;
$texto .= '<br> ############################################# <br>';
#*/
#$texto .= '<br> Línea 21';
if ($idProv < 1) {
  errorBD('No se reconoció el Proveedor, intenta de nuevo, si persiste notifica a tu Administrador <br>'.$texto);
}
if ($nombre != '' && $tel != '') {
  function is_valid_email($str){
    $matches = null;
    return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
  }
} else {
   errorBD('Debes ingresar un Nombre y un Teléfono para el Contacto');
#   $texto .= '<br> Línea 30';
}
$val = 0;
if (!is_valid_email($correo)) {
  $texto = 'El correo no se registró porque no es un correo válido';
  $val = 1;
#  $texto .= '<br>Correo no válido';
#  $texto .= '<br> Línea 35';
}
#$texto .= '<br> Línea 37';
$sqlCon = "SELECT * FROM contactosprov WHERE nombre = '$nombre' AND idProveedor = '$idProv'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Contactos, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);
#$texto .= '<br> Línea 41';
if ($cant > 0) {
  errorBD('Ya se encuentra un Contacto con ese nombre en ese Proveedor, revisa en el listado, en caso contrario, notifica a tu Administrador');
#  $texto .= '<br> Línea 44';
}
#$texto .= '<br> Línea 46';
$sql = "INSERT INTO contactosprov(nombre, idProveedor, telefono, correoSucursal) VALUES('$nombre', '$idProv', '$tel', '$correo')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al agregar al Proveedor, notifique a su Administrador'));

#$texto .= '<br> Línea 50';
#$texto .= '<br> Aquí ya capturó y regresó a la vista de Proveedores';

echo '1|Contacto registrado con éxito|'.$val.'|'.$texto;
exit(0);

function errorBD($error){
#  $texto2 = '';
#  $texto2 .= '<br> Línea 57';
#  $texto2 .= '<br> error';
  echo '0|'.$error;
  exit(0);
}
 ?>
