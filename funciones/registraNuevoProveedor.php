<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idEmpresa = (!empty($_POST['idEmpresa'])) ? $_POST['idEmpresa'] : 0;
$nombre = (isset($_POST['newNombre']) && $_POST['newNombre'] != '') ? trim($_POST['newNombre']) : '' ;
$rfc = (isset($_POST['newRFC']) && $_POST['newRFC'] != '') ? $_POST['newRFC'] : '' ;
$tel = (isset($_POST['newTelefono']) && $_POST['newTelefono'] != '') ? $_POST['newTelefono'] : '' ;
$correo = (isset($_POST['newCorreo']) && $_POST['newCorreo'] != '') ? trim($_POST['newCorreo']) : '' ;
$credito = (isset($_POST['credito']) && $_POST['credito']) ? $_POST['credito'] : '';
$cantLimite = (isset($_POST['cantLimite']) && $_POST['cantLimite']) ? $_POST['cantLimite'] : '';
$tipoLimite = (isset($_POST['tipoLimite']) && $_POST['tipoLimite']) ? $_POST['tipoLimite'] : '';
$limiteCred = (isset($_POST['limiteCred']) && $_POST['limiteCred']) ? str_replace(",", "", $_POST['limiteCred']) : '';
$userReg = $_SESSION['LZFident'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$nombre: '.$nombre;
echo '<br>$rfc: '.$rfc;
echo '<br>$tel: '.$tel;
echo '<br>$correo: '.$correo;
echo '<br>$userReg: '.$userReg;
echo '<br>$credito: '.$credito;
echo '<br>$cantLimite: '.$cantLimite;
echo '<br>$tipoLimite: '.$tipoLimite;
echo '<br>$limiteCred: '.$limiteCred;
echo '<br> ############################################# <br>';
exit(0);
#*/
#echo '<br> Línea 21';
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
#  echo '<br> Línea 35';
}
#echo '<br> Línea 37';
$sqlCon = "SELECT * FROM proveedores WHERE nombre = '$nombre' AND rfc = '$rfc' AND idEmpresa = '$idEmpresa'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Proveedores, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);
#echo '<br> Línea 41';
if ($cant > 0) {
  errorBD('Ya se encuentra un Proveedor con ese nombre y RFC, revisa en el listado, en caso contrario, notifica a tu Administrador');
#  echo '<br> Línea 44';
}
#echo '<br> Línea 46';
$sql = "INSERT INTO proveedores(nombre, rfc, telEmpresa, correoPagos, estatus, fechaReg, idUserReg, idEmpresa,credito,limiteCredito,cantidadLimite,tipoLimite) VALUES('$nombre', '$rfc', '$tel', '$correo', '1', NOW(), '$userReg', '$idEmpresa','$credito','$limiteCred','$cantLimite','$tipoLimite')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al agregar al Proveedor, notifique a su Administrador'));

#echo '<br> Línea 50';
#echo '<br> Aquí ya capturó y regresó a la vista de Proveedores';
$_SESSION['LZFmsjSuccessCatalogoProveedores'] = 'El Proveedor '.$nombre.' se ha registrado correctamente';
header('location: ../Corporativo/catalogoProveedores.php');
exit(0);

function errorBD($error){
#  echo '<br> Línea 57';
#  echo '<br> error';
  $_SESSION['LZFmsjCatalogoProveedores'] = $error;
  header('location: ../Corporativo/catalogoProveedores.php');
  exit(0);
}
 ?>
