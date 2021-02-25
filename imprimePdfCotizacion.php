<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('include/connect.php');
require_once('funciones/PDFcotizacion.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = 'imprimePdfCotizacion.php';
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];

$idCotizacion = (!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0 ;

if (!ctype_digit($idCotizacion)) {
      $idCotizacion = 0;
    }
    error_reporting(E_ALL ^ E_NOTICE);
/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados:<br>';
print_r($_REQUEST);
echo '<br>';
echo '<br>$idCotizacion: '.$idCotizacion;
echo '<br>';
echo "<br>###########################################################<br>";
#exit(0);
#*/

if ($idCotizacion > 0) {
  cotizacion($idCotizacion);
} else {
  echo "<script>parent.window.close();</script>";
  exit(0);
}
exit(0);
?>
