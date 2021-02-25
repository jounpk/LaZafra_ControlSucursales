<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident=(isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
if ($ident==""){
	//echo "Compra Null";
errorBD("Selecciona un producto para continuar. Inténtalo de Nuevo.");
    }
    $sqlCon="SELECT
    suc.nombre AS sucursal
  FROM
    excepcionesprecio pr_exp
    INNER JOIN sucursales suc ON pr_exp.idSucursal = suc.id
  WHERE pr_exp.id=".$ident;
    $resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Detalles del Precio, notifica a tu Administrador'));
    
    $cant = mysqli_num_rows($resCon);
    
    if ($cant < 0) {
        errorBD('No se encuentra el Detalle de la Excepción del Precio seleccionado, notifica a tu Administrador');
      }
$arrayCon=mysqli_fetch_array($resCon);
$nombrePro=$arrayCon['sucursal'];
$sql="DELETE FROM excepcionesprecio WHERE id = '$ident'";
mysqli_query($link,$sql) or die (errorBD('Error al borrar el precio, notifica a tu Administrador'));

echo'1|El Precio  de la sucursal: <b>'.$nombrePro.'</b> se ha creado correctamente.';

  

function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
?>
