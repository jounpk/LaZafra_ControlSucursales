<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$tipoVenta = (!empty($_POST['tipoVenta'])) ? $_POST['tipoVenta'] : 1 ;
$folio = (isset($_POST['folio']) && $_POST['folio'] != '') ? $_POST['folio'] : '' ;
/*
echo '<br>######################################################';
echo '<br>';
echo '<br>$_POST: ';
echo '<br>'.print_r($_POST);
echo '<br>$tipoVenta: '.$tipoVenta;
echo '<br>$idVenta: '.$idVenta;
echo '<br>$folio: '.$folio;
$sql="DELETE FROM pagosventas WHERE idVenta = '$idVenta' AND folio = '$folio' LIMIT 1";
echo '<br>$sql: '.$sql;
echo '<br>';
echo '<br>######################################################';
echo '<br> Línea 18';
exit(0);
#*/

if ($idVenta == 0) {
  errorBD('No se reconoció la venta, vuelve a intentarlo, si persiste notifica a tu Administrador',$tipoVenta);
}

if ($folio == '') {
  errorBD('No se reconoció el folio, vuelve a intentarlo, si persiste notifica a tu Administrador',$tipoVenta);
}

$sql="DELETE FROM pagosventas WHERE idVenta = '$idVenta' AND folio = '$folio' LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al eliminar la boleta, notifica a tu Administrador',$tipoVenta));


#			 se redirecciona al lugar donde se hizo la petición
			if ($tipoVenta == 1) {
#				echo '<br> Línea 41';
				$_SESSION['LZFmsjSuccessInicioVenta'] = 'Se ha eliminado la boleta correctamente.';
				header('location: ../venta.php');
				exit(0);
			} else {
#				echo '<br> Línea 45';
				$_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'Se ha eliminado la boleta correctamente.';
				header('location: ../ventaEspecial.php');
				exit(0);
			}



function errorBD($error,$tipoVenta){
  #/*
  	if ($tipoVenta == 1) {
  #		echo '<br> Línea 61';
  		$_SESSION['LZFmsjInicioVenta'] = $error;
  		header('location: ../venta.php');
  		exit(0);
  	} else {
  #		echo '<br> Línea 66';
  		$_SESSION['LZFmsjInicioVentaEspecial'] = $error;
  		header('location: ../ventaEspecial.php');
  		exit(0);
  	}
  	#*/
  #	echo '<br>$error: '.$error;
  	exit(0);
}
 ?>
