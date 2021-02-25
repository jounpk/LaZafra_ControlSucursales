<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('CFDIS/CFDI_FACTURACION_FACTCOM.PHP');
$idPagoCredito = (isset($_POST['idPagoCredito'])) ? $_POST['idPagoCredito'] : '' ;
$uidDatosFisc=(isset($_POST['uidDatosFisc'])) ? $_POST['uidDatosFisc'] : '' ;
$correo=(isset($_POST['correo'])) ? $_POST['correo'] : '' ;
$rfcRe=(isset($_POST['rfcRe'])) ? $_POST['rfcRe'] : '' ;
$rfcEm=(isset($_POST['rfcEm'])) ? $_POST['rfcEm'] : '' ;

if ($idPagoCredito <  1) {
    errorBD('No se reconoció el Pago del Credito, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
  }

//print_r($_POST);

$ids_PagoCredito=array($idPagoCredito);
$datosClientes=array(
  "correo"=>$correo,
  "uid"=>$uidDatosFisc,
  "rfcEm"=>$rfcEm,
  "rfcRe"=>$rfcRe



);
$respuesta=hacerComplemento($ids_PagoCredito, $datosClientes, $link);
//print_r($respuesta);
switch ($respuesta["estatus"]){
  case '1':
      echo '1|'.$respuesta["mensaje"];
      $_SESSION['LZFmsjSuccessAdminCreditos']="Complemento Correcto";
    break;
  case '0':
  
    $arrayErrores=array($respuesta["idsErroneos"]);
    if(is_array($arrayErrores[0])){
      if(array_key_exists('message',$arrayErrores[0])){
        
         echo '0|'.$respuesta["mensaje"]."|".$arrayErrores[0]["message"];
       }else{
       $stringError=json_decode($arrayErrores[0]);
         echo '0|'.$respuesta["mensaje"]."|".$stringError[0];
       }
    }
    else{
      echo '0|'.$respuesta["mensaje"]."|".json_decode($respuesta["idsErroneos"])[0];
    }
    break;
  case '3':
      echo '0|'.$respuesta["mensaje"]."|".json_decode($respuesta["idsErroneos"])[0];
    break;  

}





function errorBD($error){
    echo '0|'.$error;
  }
?>