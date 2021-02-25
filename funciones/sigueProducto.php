<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$value = (isset($_POST['value'])) ? $_POST['value'] : '' ;

if ($ident == '' OR $value == '') {
    errorBD('Problemas al consultar los Productos, notifica a tu Administrador');
} else {
  $sql="UPDATE productos SET seguimiento = '$value' WHERE id = '$ident'";
  mysqli_query($link,$sql) or die (errorCarga('Problemas al Editar el Producto, notifica a tu Administrador'));
//echo $sql;
}

$_SESSION['LZmsjSuccessSeguimiento'] = 'El Producto se a actualizado.';
//header('location: ../Corporativo/catalogoProductos.php');
//echo "Listo";
header('location: ../Corporativo/stockGlobal.php');

function errorCarga($error){
    $_SESSION['LZmsjInfoSeguimiento'] = $error;
  //echo 'cayo: '.$error;
  header('location: ../Corporativo/stockGlobal.php');exit(0);
  }
  
  
  function errorBD($msj)
  {
    //echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoSeguimiento'] = $msj;
    header('location: ../Corporativo/stockGlobal.php');  exit(0);
  }
?>
