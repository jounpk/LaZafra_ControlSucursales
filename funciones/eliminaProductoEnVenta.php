<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idDetVenta = (!empty($_POST['idDetVenta'])) ? $_POST['idDetVenta'] : 0 ;
$tipoVenta = (!empty($_POST['tipoVenta'])) ? $_POST['tipoVenta'] : 1 ;
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idDetVenta: '.$idDetVenta;
echo '<br>$tipoVenta: '.$tipoVenta;
echo '<br> ############################################# <br>';
echo '<br> Línea 26';
#exit(0);
#*/
if ($idDetVenta == 0) {
  errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador',$tipoVenta);
}

$sqlCon = "SELECT v.estatus FROM ventas v INNER JOIN detventas d ON v.id = d.idVenta WHERE d.id = '$idDetVenta'";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la venta, notifica a tu Administrador.');
$dt = mysqli_fetch_array($resCon);

if ($dt['estatus'] > '1') {
	errorBD('No se realizó ningún cambio porque la venta ya fue cerrada o cancelada.',$tipoVenta);
}

$sql = "DELETE FROM detventas WHERE id = '$idDetVenta' LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus de la Marca, notifica a tu Administrador',$tipoVenta));

#se redirecciona al lugar donde se hizo la petición
			if ($tipoVenta == 1) {
#				echo '<br> Línea 41';
#        echo '<br>Manda a venta, todo ok';
				$_SESSION['LZFmsjSuccessInicioVenta'] = 'El producto ha sido eliminado correctamente.';
				header('location: ../venta.php');
				exit(0);
			} else {
#				echo '<br> Línea 45';
#        echo '<br>Manda a venta especial, todo ok';
				$_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'El producto ha sido eliminado correctamente.';
				header('location: ../ventaEspecial.php');
				exit(0);
			}

function errorBD($error,$tipoVenta){
  if ($tipoVenta == 1) {
#  	echo '<br> Línea 61';
#    echo '<br>Manda a venta, todo bad';
    $_SESSION['LZFmsjInicioVenta'] = $error;
    header('location: ../venta.php');
    exit(0);
  } else {
#  	echo '<br> Línea 66';
#    echo '<br>Manda a venta especial, todo bad';
    $_SESSION['LZFmsjInicioVentaEspecial'] = $error;
    header('location: ../ventaEspecial.php');
    exit(0);
  }
}
 ?>
