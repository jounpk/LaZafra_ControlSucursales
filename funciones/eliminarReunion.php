<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');

$ident=(isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;

if ($ident=='') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}

   $sql="DELETE FROM agenda WHERE id='$ident'";
   $res = mysqli_query($link,$sql) or die(errorBD("Error al Eliminar el evento, notifica a tu Administrador."));
  // print_r($sql);


 



 echo'1|Reunion Eliminar Correctamente.';
$_SESSION['LZmsjSuccessAgenda'] = 'Reunión Eliminar correctamente.';


function errorBD($error){
 // $_SESSION['CCMregconvenio'] = 'La Nueva <b>Programación Automatica</b> se a creado Corrrectamente.';

echo '0|'.$error;
   exit(0);
}
