<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$ident=(isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$cantActual = (isset($_POST['cantActual']) AND $_POST['cantActual'] != '') ? $_POST['cantActual'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
//INICIA TRANSACCION
mysqli_autocommit($link, FALSE);
mysqli_begin_transaction($link);
$total_decremento=0;
$total_incremento=0;
if ($ident == '' || $cantActual == '') {
 errorCarga('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
//MODICAR LA CANTIDAD DEL STOCK SELECCIONADA
$sqlCon = "SELECT id, lote, cant, idProducto FROM lotestocks WHERE (id ='$ident')";

$resCon = mysqli_query($link,$sqlCon) or die(errorCarga('Problemas al consultar el producto en lotes, notifica a tu Administrador'));


$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorCarga('No se encuentra el lote con ese nombre, notifica a tu Administrador');
}
$array=mysqli_fetch_array($resCon);
$cantlote=$array['cant'];
$idProd=$array['idProducto'];
$nombre_lote=$array['lote'];
if($cantlote<$cantActual){
  $total_incremento=($cantActual-$cantlote);
}else if($cantlote>$cantActual){
  $total_decremento=($cantlote-$cantActual);
}
//$namePro=mysqli_fetch_array($resCon);
//$namePro=$namePro['descripcion'];
$sql_update="UPDATE lotestocks SET cant='$cantActual' WHERE id='$ident'";
$resCon = mysqli_query($link,$sql_update) or die(errorCarga('Problemas al guardar los cambios de lotes, notifica a tu Administrador'));

//ver la cantidad actual del stock
$sql="SELECT id,cantActual FROM stocks WHERE idProducto=$idProd AND idSucursal=$sucursal";
//echo $sql;
$resCon = mysqli_query($link,$sql) or die(errorCarga('Problemas al ver la cantidad Actual, notifica a tu Administrador'));
$arrayStck=mysqli_fetch_array($resCon);
$cantActualS=$arrayStck['cantActual'];
$idStock=$arrayStck['id'];
$cantmodif=$cantActualS-$total_decremento+$total_incremento;

if($cantmodif>=0){
  $sql = "UPDATE stocks SET cantActual=$cantmodif, fechaReg=NOW() WHERE id=$idStock";
  //echo $sql;
  $resCon = mysqli_query($link,$sql) or die(errorCarga('Problemas al Capturar la Cantidad Actual, notifica a tu Administrador'));

$sql="INSERT INTO deteditastock (idStock,cantOrig, cantFinal, fechaReg, idUserReg, idSucReg) VALUES
($idStock, $cantActualS,$cantmodif,NOW(),$userReg,$sucursal);";
  $resCon = mysqli_query($link,$sql) or die(errorCarga('Problemas al Guardar la edición, notifica a tu Administrador'));

}else{
  errorCarga('Verifica las cantidades modificadas, intentalo de nuevo.');
}



//  AL FINAL DEL PROCESO MANDAMOS RESULTADOS
if (mysqli_commit($link)) {
    echo '1|Edición de Productos del lote <b>'.$nombre_lote.'</b> Correcto';
}
 else {
    errorCarga('Error al cargar los datos, notifica a tu Administrador.'.mysqli_error($link));
}
function errorCarga($error)
{
    $link = $GLOBALS["link"];
    echo '0|' . $error;
    mysqli_rollback($link);
    exit(0);
}
 ?>
