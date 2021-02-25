<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$descripcion = (isset($_POST['descripcion']) AND $_POST['descripcion'] != '') ? $_POST['descripcion'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];

if($id=='' OR $id==0){
	errorBD("Problemas al ingresar al Ajuste. Inténtalo de Nuevo.");

	}


$sql="SELECT * FROM ajustes WHERE id='$id' AND estatus=1 ORDER BY id";
$res=mysqli_query($link,$sql) or die(errorBD("Problemas al consultar registro del traspaso, notifica a tu Administrador."));
$cant=mysqli_num_rows($res);

if($cant==0){
	errorBD("Ajuste efectuado o no registrado con éxito, notifica a tu Administrador.");
}
else{
  $sql="UPDATE ajustes SET estatus='2', descripcion='$descripcion', fechaReg=NOW() WHERE id='$id'";
  $res=mysqli_query($link,$sql) or die("Error al actualizar el ajuste, notifica a tu Administrador.");
  
  echo '1|Solicitud de Ajuste <b>'.$descripcion.'</b> efectuada con éxito.';
}

function errorBD($error){
  echo '0|'.$error;
    exit(0);
 }
 

?>
