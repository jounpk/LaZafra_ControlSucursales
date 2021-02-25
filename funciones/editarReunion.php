<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');

$nombre = (isset($_POST['nombre']) AND $_POST['nombre'] != '') ? $_POST['nombre'] : '' ;
$fecha = (isset($_POST['fecha']) AND $_POST['fecha'] != '') ? $_POST['fecha'] : '' ;
$descripcion = (isset($_POST['descripcion']) AND $_POST['descripcion'] != '') ? $_POST['descripcion'] : '' ;
$sucursales = (isset($_POST['sucursales']) AND $_POST['sucursales'] != '') ? $_POST['sucursales'] : '' ;
$ident=(isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$sucursal=implode(',',$sucursales);

$userReg = $_SESSION['LZFident'];
if ($nombre=='' || $fecha == ''  || $sucursales=='') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}

   $sql="UPDATE agenda SET fechaHora='$fecha', sucursalesPart='$sucursal', fechaReg=NOW(), userReg='$userReg', descripcion='$descripcion', nombre='$nombre' WHERE id='$ident'";
   $res = mysqli_query($link,$sql) or die(errorBD("Error al editar el evento, notifica a tu Administrador."));
  // print_r($sql);


 



 echo'1|Reunion Editada Correctamente.';
$_SESSION['LZmsjSuccessAgenda'] = 'Reunión Editada correctamente.';


function errorBD($error){
 // $_SESSION['CCMregconvenio'] = 'La Nueva <b>Programación Automatica</b> se a creado Corrrectamente.';

echo '0|'.$error;
   exit(0);
}
