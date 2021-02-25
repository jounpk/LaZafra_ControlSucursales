<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idSucRec = (isset($_GET['ident']) AND $_GET['ident'] != '') ? $_GET['ident'] : '' ;
//if(isset($_GET['ident'])){$idProveedor=$_GET['ident'];}else{$idProveedor='';}
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];

if ($idSucRec==""){
	//echo "Compra Null";
errorCarga("Selecciona una Sucursal para continuar. Inténtalo de Nuevo.");
    }
    
$sqlCon="SELECT suc.nombre FROM sucursales suc WHERE id=".$idSucRec;
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las sucursales, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant < 0) {
    errorCarga('No se encuentra el sucursal seleccionado, notifica a tu Administrador');
  }
$Prov=mysqli_fetch_array($resCon);
$nameSuc=$Prov['nombre'];

$sql="INSERT INTO traspasos(idSucSalida,idSucEntrada,estatus, idUserEnvio, fechaEnvio) VALUES('$sucursal','$idSucRec',1,$userReg, NOW())";
mysqli_query($link,$sql) or die (errorCarga('Error al guardar el Traspaso.'.mysqli_error($link)));


//echo 'Ok';
$_SESSION['LZmsjSuccessTraspasos'] = 'El Traspaso para la sucursal <b>'.$nameSuc.'</b> se a seleccionado.';

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

