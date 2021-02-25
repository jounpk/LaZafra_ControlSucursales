<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idVenta=(!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$tipo=(!empty($_POST['tipo'])) ? $_POST['tipo'] : 0 ;
$userReg = $_SESSION['LZFident'];
if ($idVenta == 0){
	//echo "Compra Null";
errorBD("No se reconoció la venta, vuelve a intentarlo, si persiste notifica a tu Administrador.");
    }

    if ($tipo == 1) {
      $estatus = 1;
      $msn = 'autorizada';
    } else {
      $estatus = 5;
      $msn = 'cancelada';
    }

    $sqlUpdate="UPDATE ventas SET estatus = '$estatus', idUserAut = '$userReg', fechaAutoriza = now() WHERE id = '$idVenta' LIMIT 1";
    $resUpdate = mysqli_query($link,$sqlUpdate) or die(errorBD('Problemas al actualizar la venta especial, notifica a tu Administrador'));

echo'1|La venta ha sido '.$msn.' correctamente.';
exit(0);

function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
?>
