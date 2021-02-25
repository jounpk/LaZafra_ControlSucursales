<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$montoInicio = (!empty($_POST['inicioCorte'])) ? $_POST['inicioCorte'] : 0 ;
$idUser = $_SESSION['LZFident'];
$idSuc = $_SESSION['LZFidSuc'];

$sqlCon = "SELECT idEmpresa FROM sucursales WHERE id = '$idSuc'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al conusltar las empresas, notifica a tu Administrador.'));
$dat = mysqli_fetch_array($resCon);
$idEmp = $dat['idEmpresa'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$montoInicio: '.$montoInicio;
echo '<br>$idUser: '.$idUser;
echo '<br>$idSuc: '.$idSuc;
echo '<br>$idEmp: '.$idEmp;
echo '<br>';
echo '<br> ############################################# <br>';
#*/
# se verifica que no haya un inicio de corte abierto por parte del usuarios
$sqlRev = "SELECT COUNT(id) AS cantCortes FROM cortes WHERE idUserReg = '$idUser' AND idSucursal = '$idSuc' AND estatus = '1'";
$resRev = mysqli_query($link,$sqlRev) or die(errorBD('Problemas al verificar si hay cortes abiertos por el usuario, notifica a tu Administrador'));
$dato = mysqli_fetch_array($resRev);
$cant = $dato['cantCortes'];
if ($cant > 0) {
  errorBD('Ya tienes un corte de caja abierto, Cortes abiertos: '.$cant);
} else {
  $sqlIn = "INSERT INTO cortes(idUserReg,fechaReg,idSucursal,idEmpresa,montoInicio,estatus) VALUES('$idUser',NOW(),'$idSuc','$idEmp','$montoInicio','1')";
  $resIn = mysqli_query($link,$sqlIn) or die(errorBD('Problemas al registrar el inicio de caja, notifica a tu Administrador'));
#  echo '<br>Se realizó con éxito el registro';
  $_SESSION['LZFmsjSuccessInicioCaja'] = 'Se ha registrado correctamente';
  header('location: ../home.php');
  exit(0);
}




function errorBD($error){
#  echo '<br>Sucedió un error: '.$error;
  $_SESSION['LZFmsjInicioCaja'] = $error;
  header('location: ../home.php');
}
 ?>
