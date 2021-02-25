<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident=(isset($_GET['id']) AND $_GET['id'] != '') ? $_GET['id'] : '' ;
if ($ident==""){
	//echo "Compra Null";
errorCarga("Selecciona un producto para continuar. Inténtalo de Nuevo.");
    }
    $sqlCon="SELECT det.id, pro.descripcion FROM dettraspasos det INNER JOIN productos pro ON det.idProducto=pro.id  WHERE det.id=".$ident;
    $resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Detalles de Traspasos, notifica a tu Administrador'));
    
    $cant = mysqli_num_rows($resCon);
    
    if ($cant < 0) {
        errorCarga('No se encuentra el Detalle de Traspasos seleccionado, notifica a tu Administrador');
      }
$arrayCon=mysqli_fetch_array($resCon);
$nombrePro=$arrayCon['descripcion'];
$sql="DELETE FROM dettraspasos WHERE id = '$ident'";
mysqli_query($link,$sql) or die (errorCarga('Error al borrar el producto, notifica a tu Administrador'));
$_SESSION['LZmsjSuccessTraspasos'] = 'El Producto <b>'.$nombrePro.'<b> se a eliminado del traspaso.';

header('location: ../Administrador/traspasos.php');
function errorCarga($error){
    $_SESSION['LZmsjInfoTraspasos'] = $error;
 // echo 'cayo: '.$error;
  header('location: ../Administrador/traspasos.php');
  exit(0);
  }
  function errorBD($msj)
  {
    //echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoTraspasos'] = $msj;
    header('location: ../Administrador/traspasos.php');
    exit(0);
  }
?>
