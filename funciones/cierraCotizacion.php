<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCot = (!empty($_POST['idCot'])) ? $_POST['idCot'] : 0 ;
$total = (!empty($_POST['total'])) ? $_POST['total'] : 0 ;
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 0 ;
$idUser = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];

#echo '<br>------------------------ Se verifica que los valores sean mayores a 0 ------------------------<br>';
if ($idCot < 1) {
  errorBD('No se reconoció la cotización, inténtalo nuevamente, si persiste notifica a tu Administrador.');
}
if ($tipo < 1) {
  errorBD('No se reconoció si deseas cerrar o cancelar la venta, inténtalo nuevamente, si persiste notifica a tu Administrador.');
}

/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados por $_POST:';
print_r($_POST);
echo '<br>$idCot: '.$idCot;
echo '<br>$total: '.$total;
echo '<br>$tipo: '.$tipo;
echo '<br>$idUser: '.$idUser;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br>';
echo "<br>###########################################################<br>";
#exit(0);
#*/
#echo '<br>------------------------ Se consulta el estatus de la cotización ------------------------<br>';
$sql = "SELECT * FROM cotizaciones WHERE id = '$idCot'";
$res = mysqli_query($link,$sql) or die('Problemas al consultar las cotizaciones, notifica a tu Administrador.');
$dat = mysqli_fetch_array($res);
#echo '<br>------------------------ Se valida el estatus, si es diferente a uno manda a error y no se realiza nada ------------------------<br>';
if ($dat['estatus'] != 1) {
  errorBD('No se realizó ninguna acción porque la venta ya fue cerrada o cancelada.');
}
#echo '<br>------------------------ Se valida el tipo, si se cierra o se cancela la cotización  ------------------------<br>';
if ($tipo == 1) {
  $sqlUp = "UPDATE cotizaciones SET montoTotal = '$total', fechaReg = NOW(), estatus = '2' WHERE id = '$idCot'";
  $mensaje = 'Se ha cerrado correctamente.';
} else {
  $sqlUp = "UPDATE cotizaciones SET montoTotal = '$total', fechaReg = NOW(), estatus = '5' WHERE id = '$idCot'";
  $mensaje = 'Se ha cancelado correctamente.';
}
#echo '<br>------------------------ Se ejecuta el sql ------------------------<br>';
$resUp = mysqli_query($link,$sqlUp) or die(errorBD('Prolemas al cerrar la venta, notifica a tu Administrador.'));


#echo '<br>------------------------ Todo ok y se redirige a la página de cotizaciones ------------------------<br>';
#exit(0);

$_SESSION['LZFmsjSuccessAdminCotizaciones'] = $mensaje;
header('location: ../Administrador/cotizaciones.php');

#echo '<br>------------------------ Se crea la función de error ------------------------<br>';
  function errorBD($msj){
#echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZFmsjAdminCotizaciones'] = $msj;
    header('location: ../Administrador/cotizaciones.php');
    exit(0);
  }
?>
