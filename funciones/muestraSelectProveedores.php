<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idEmp = (isset($_POST['idEmpresa']) && $_POST['idEmpresa']) ? $_POST['idEmpresa'] : 0 ;

$sqlProv = "SELECT id,nombre FROM proveedores WHERE estatus = '1' AND idEmpresa = '$idEmp'";
$resProv = mysqli_query($link,$sqlProv) or die('Problemas al consutlar los Proveedores, notifica a tu Administrador.');
$listaProv = '';
while ($p = mysqli_fetch_array($resProv)) {
  $listaProv .= '<option value="'.$p['id'].'">'.$p['nombre'].'</option>';
}

echo $listaProv;
 ?>
