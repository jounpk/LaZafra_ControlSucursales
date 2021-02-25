<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCoti = (!empty($_POST['idCoti'])) ? $_POST['idCoti'] : 0 ;
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 0 ;
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$idCoti: '.$idCoti;
echo '<br>$tipo: '.$tipo;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br>$idUser: '.$idUser;
echo '<br> ############################################# <br>';
echo '<br> Línea 19';
#exit(0);
#*/
if ($idCoti == 0) {
	errorBD('No se reconoció la Cotización, inténtalo nuevamente, si persiste notifica a tu Administrador.');
}

#echo '<br><br>------------------------ Se consulta el estatus de la cotización ------------------------<br>';
if ($tipo == 1) {
	$estatus = 3;
	$msn = 'autorizada';
} else {
	$estatus = 4;
	$msn = 'cancelada';
}

$sqlUpdate="UPDATE cotizaciones SET estatus = '$estatus' WHERE id = '$idCoti' LIMIT 1";
$resUpdate = mysqli_query($link,$sqlUpdate) or die(errorBD('Problemas al actualizar la cotización, notifica a tu Administrador'));

#echo '<br><br>------------------------ Se manda a el mensaje de éxito y se redirige a la página ------------------------<br>';
$_SESSION['LZFmsjSuccessCorporativonCotizaciones'] = 'La cotización ha sido '.$msn.' correctamente.';
header('location: ../Corporativo/corpCotizaciones.php');
exit(0);

#echo '<br><br>------------------------ En caso de que exista el error se manda el mensaje de error y se redirige a la página ------------------------<br>';
function errorBD($error){
		$_SESSION['LZFmsjCorporativonCotizaciones'] = $error;
		header('location: ../Corporativo/corpCotizaciones.php');
		#echo '<br>$error: '.$error;
		exit(0);

}

 ?>
