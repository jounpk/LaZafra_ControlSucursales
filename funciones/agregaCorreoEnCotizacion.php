<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$correo = (isset($_POST['correo']) AND $_POST['correo'] != '') ? $_POST['correo'] : '' ;
$idCot = (!empty($_POST['idCot'])) ? $_POST['idCot'] : 0 ;

#echo '<br><br>------------------------ Se declara la función de validación del correo ------------------------<br><br>';

function is_valid_email($str){
  $matches = null;
  return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}

#echo '<br><br>------------------------ Primero se verifica que el id de la cotización haya sido pasado ------------------------<br><br>';
if ($idCot == 0) {
  errorBD('No se reconoció la cotización, actualiza e inténtalo nuevamente, si persiste notifica a tu Administrador.');
}

#echo '<br><br>------------------------ Se verifica que el correo ingresado sea el correcto ------------------------<br><br>';
if (is_valid_email($correo)) {
  #echo '<br><br>------------------------ se consulta que la cotización esté en estatus 1 para proseguir con el ingreso de los correos ------------------------<br><br>';
  $sqlCon = "SELECT * FROM cotizaciones WHERE id = '$idCot'";
  $resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las cotizaciones.'));
  $edo = mysqli_fetch_array($resCon);

  #echo '<br><br>------------------------ Se valida que su estatus no sea mayor a 1 ------------------------<br><br>';
  if ($edo['estatus'] > 1) {
    errorBD('No se agregó el correo porque la cotización ya fue cerrada o cancelada.');
  }

  #echo '<br><br>------------------------ se agrega el correo en la tabla de correos ------------------------<br><br>';
  $sqlIn = "INSERT INTO detcotcorreos(idCotizacion,correo) VALUES('$idCot','$correo')";
  $resIn = mysqli_query($link,$sqlIn) or die(errorBD('Problemas al capturar el correo, notifica a tu Administrador.'));

  #echo '<br><br>------------------------ Se consultan los correos y se enlistan para colocar en el div ------------------------<br><br>';
  $sql = "SELECT * FROM detcotcorreos WHERE idCotizacion = '$idCot'";
  $res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los correos, notifica a tu Administrador.'));
  $listado = '<ul>';
  while ($lst = mysqli_fetch_array($res)) {
    $listado .= '<li id="liCorreo-'.$lst['id'].'"><a href="JavaScript:void(0);" onClick="eliminaCorreo('.$lst['id'].','.$idCot.')" class="text-danger"><i class="fas fa-trash"></i></a> &nbsp;&nbsp;&nbsp;&nbsp;'.$lst['correo'].'</li>';
  }
  $listado .= '</li>';


} else {
  errorBD('El correo no es correcto, verifica el correo ingresado|'.$correo.'|'.$idCot);
}

#echo '<br><br>------------------------ Se envían los datos para su inserción en el div ------------------------<br><br>';
echo'1|Se ha capturado el correo correctamente.|'.$listado.'|'.$correo.'|'.$idCot;
exit(0);


#echo '<br><br>------------------------ Se declara la función de error ------------------------<br><br>';
function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
?>
