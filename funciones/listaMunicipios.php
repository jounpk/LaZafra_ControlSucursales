<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idEstado'])) ? $_POST['idEstado'] : 0 ;

if ($id > 0) {
  $sqlMpio = "SELECT * FROM catmunicipios WHERE idCatEstado = '$id' ORDER BY nombre";
  $resMpio = mysqli_query($link,$sqlMpio) or die('Problemas al consultar los Municipios');
  while ($mpio = mysqli_fetch_array($resMpio)) {
    echo '<option value="'.$mpio['id'].'">'.$mpio['nombre'].'</option>';
  }
} else{
  echo '<option value="">Selecciona un Estado</option>';
}

 ?>
