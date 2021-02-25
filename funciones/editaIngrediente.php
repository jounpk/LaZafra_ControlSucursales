<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idIngrediente'])) ? $_POST['idIngrediente'] : 0 ;
$nombre = (isset($_POST['nombre']) AND $_POST['nombre'] != '') ? trim($_POST['nombre']) : '';
$desc = (isset($_POST['desc']) AND $_POST['desc'] != '') ? $_POST['desc'] : '';

if ($id < 1) {
  errorBD('No se reconoció el Ingrediente a editar, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($nombre == '') {
  errorBD('Debes ingresar un nombre');
}

$sqlCon = "SELECT * FROM catingact WHERE nombre = '$nombre' AND id != '$id'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar el Ingrediente, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorBD('Lo sentimos, ya se encuentra un Ingrediente con ese nombre');
}

$sql = "UPDATE catingact SET nombre = '$nombre',descripcion = '$desc' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el Ingrediente, notifica a tu Administrador'));
 echo '1|El Ingrediente fue editado exitosamente.';

exit(0);

function errorBD($error){
echo '0|'.$error;
exit(0);
}
 ?>
