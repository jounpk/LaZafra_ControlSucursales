<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];

if($id=='' OR $id==0){
	errorBD("Problemas en el ingreso de la Traspaso. Inténtalo de Nuevo.");

	}


$sql="SELECT * FROM traspasos WHERE id='$id' AND estatus=2 ORDER BY id";
$res=mysqli_query($link,$sql) or die(errorBD("Problemas al consultar registro del traspaso, notifica a tu Administrador."));
$cant=mysqli_num_rows($res);

if($cant==0){
	errorBD("Traspaso efectuado o no registrada con éxito, notifica a tu Administrador.");

	//header('Location:../reimprimeTickets.php?idVenta='.$id.'&tipoTicket=lanzaCompra');
}
else{
  $sql="CALL SP_RecibeTraspaso('$id','$userReg','$sucursal')";
  $res=mysqli_query($link,$sql) or die(errorBD("Problemas al guardar detalles de la recepción. Inténtalo de Nuevo.".mysqli_error($link)));
  $dat = mysqli_fetch_array($res);
  
	$estatus = $dat['estatus'];
  $msj = $dat['mensaje'];
  if ($estatus == 1){
    echo'1|'.$msj;


  }else {
		errorBD($msj);
	}
}

function errorBD($error){
  echo '0|'.$error;
    exit(0);
 }
 

?>