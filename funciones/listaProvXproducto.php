<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idProd = (isset($_POST['idProd']) AND $_POST['idProd'] != '') ? $_POST['idProd'] : '' ;
$val = explode("|",$idProd);
if ($val[0] == 1) {
  $filtroProducto = " dc.idProducto = '$val[1]'";
} else {
  $filtroProducto = " dc.nombreProducto = '$val[1]'";
}
$respuesta = '';
$sqlProv = "SELECT DISTINCT(p.id),p.nombre
            FROM compras c
						INNER JOIN proveedores p ON c.idProveedor = p.id
						INNER JOIN detcompras dc ON c.id = dc.idCompra
            WHERE $filtroProducto AND c.estatus = '2'";
$resProv = mysqli_query($link,$sqlProv) or die("Problemas al consultar los <b>cultivos</b>, por favor notifica a tu Administrador");
$cant = mysqli_num_rows($resProv);
#echo '<br>Cant: '.$cant.'<br>';
if ($cant > 0 ) {
  $respuesta .= '<optgroup label="Proveedores">';
  while ($pr = mysqli_fetch_array($resProv)) {
    $respuesta .= '<option value="'.$pr['id'].'">'.$pr['nombre'].'</option>';
    }
  } else {
    $respuesta .= '<option value=""></option>';
  }
  $respuesta .= '</optgroup>';
echo $respuesta;
 ?>
