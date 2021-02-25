<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idEmpresa = (!empty($_POST['idEmpresa'])) ? $_POST['idEmpresa'] : 0;
$id = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$nombre = (isset($_POST['editNombre']) && $_POST['editNombre'] != '') ? trim($_POST['editNombre']) : '' ;
$rfc = (isset($_POST['editRFC']) && $_POST['editRFC'] != '') ? $_POST['editRFC'] : '' ;
$tel = (isset($_POST['editTelefono']) && $_POST['editTelefono'] != '') ? $_POST['editTelefono'] : '' ;
$correo = (isset($_POST['editCorreo']) && $_POST['editCorreo'] != '') ? $_POST['editCorreo'] : '' ;
$credito = (isset($_POST['edCredito']) && $_POST['edCredito']) ? $_POST['edCredito'] : '';
$cantLimite = (isset($_POST['cantLimite']) && $_POST['cantLimite']) ? $_POST['cantLimite'] : '';
$tipoLimite = (isset($_POST['tipoLimite']) && $_POST['tipoLimite']) ? $_POST['tipoLimite'] : '';
$limiteCred = (isset($_POST['limiteCred']) && $_POST['limiteCred']) ? str_replace(",","",$_POST['limiteCred']) : '';
$userReg = $_SESSION['LZFident'];

/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$id: '.$id;
echo '<br>$nombre: '.$nombre;
echo '<br>$rfc: '.$rfc;
echo '<br>$tel: '.$tel;
echo '<br>$correo: '.$correo;
echo '<br>$credito: '.$credito;
echo '<br>$cantLimite: '.$cantLimite;
echo '<br>$tipoLimite: '.$tipoLimite;
echo '<br>$limiteCred: '.$limiteCred;
echo '<br>$userReg: '.$userReg;
echo '<br> ############################################# <br>';
exit(0);
#*/
#echo '<br> Línea 23';
if ($id < 1) {
  errorBD('No se reconoció el Proveedor, intenta de nuevo, si persiste notifica a tu Administrador');
}
if ($nombre != '' && $rfc != '') {
  function is_valid_email($str){
    $matches = null;
    return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
  }
} else {
   errorBD('Debes ingresar un Nombre y un RFC para el Proveedor');
  # echo '<br> Línea 30';
}
if (!is_valid_email($correo)) {
  $_SESSION['LZFmsjCatalogoProveedores'] = 'El correo no es válido, ingresa un correo válido para el Proveedor';
#  echo '<br>Correo no válido';
#  echo '<br> Línea 39';
}
#echo '<br> Línea 41';
$sqlCon = "SELECT * FROM proveedores WHERE nombre = '$nombre' AND idEmpresa = '$idEmpresa' AND rfc = '$rfc' AND id != '$id'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Proveedores, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);
#echo '<br> Línea 45';
if ($cant > 0) {
  errorBD('Ya se encuentra un Proveedor con ese nombre y RFC, revisa en el listado, en caso contrario, notifica a tu Administrador');
#  echo '<br> Línea 44';
}
#echo '<br> Línea 50';
$sql = "UPDATE proveedores SET credito = '$credito', limiteCredito = '$limiteCred', tipoLimite = '$tipoLimite', cantidadLimite = '$cantLimite',
nombre = '$nombre', rfc = '$rfc', telEmpresa = '$tel', idEmpresa = '$idEmpresa', correoPagos = '$correo', fechaReg = NOW(), idUserReg = '$userReg' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al agregar al Proveedor, notifique a su Administrador'));
#echo '<br>$sql: '.$sql;
#echo '<br> Línea 54';
#echo '<br> Aquí ya editó y regresó a la vista de Proveedores';
$_SESSION['LZFmsjSuccessCatalogoProveedores'] = 'El Proveedor '.$nombre.' se ha actualizado correctamente';
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
