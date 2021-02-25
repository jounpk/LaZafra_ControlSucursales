<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
require_once('CFDIS/CFDI_FACTURACION_FACTCOM.PHP');
$idsVenta = (isset($_POST['idsVenta'])) ? $_POST['idsVenta'] : '';

$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------


if ($idsVenta =='') {
  errorBD('No se reconoció la venta, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}

$datosCliente = array(
  "rfc" => 'XAXX010101000',
  "razonSocial" => 'Publico En General',
  "nombre" => '',
  "apellido" => '',
  "calle" => '',
  "noInt" => '',
  "noExt" => '',
  "colonia" => '',
  "municipio" => '',
  "estado" => '',
  "codpostal" => '',
  "correo" => 'karen.dominguez@rvsetys.com',
  "telefono" => '',
  "correo2" => '',
  "correo3" => '',
  "usoCFDI" => 'G03',
  "idCliente" => '',
  "operacion" => '3'
);

$respuesta = facturar($idsVenta, $datosCliente, $link, '1', '1');
if ($debug == 1) {
  print_r($respuesta);
}
switch ($respuesta["estatus"]) {
  case '1':
    echo '1|' . $respuesta["mensaje"];
    $_SESSION['LZFmsjSuccessNoFact'] = "Facturación Correcta";
    break;
  case '0':

    $arrayErrores = array($respuesta["idsErroneos"]);
    if (is_array($arrayErrores[0])) {
      if (array_key_exists('message', $arrayErrores[0])) {
        if ($debug == 1) {
          PRINT_R($arrayErrores[0]);
          PRINT_R(isset($arrayErrores[0]["message"]->{'message'}));
        }
      } else {
        $stringError = json_decode($arrayErrores[0]);
        if ($debug == 1) {
          PRINT_R($stringError[0]);
        }
      }
    } else {
      echo '0|' . $respuesta["mensaje"] . "|" . json_decode($respuesta["idsErroneos"])[0];
    }
    break;
  case '3':
    echo '0|' . $respuesta["mensaje"] . "|" . json_decode($respuesta["idsErroneos"])[0];
    break;
}





function errorBD($error)
{
  echo '0|' . $error;
}
