<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('CFDIS/CFDI_FACTURACION_FACTCOM.PHP');


$ids_Ventas = (isset($_POST['facturar'])) ? $_POST['facturar'] : '' ;
$idSucursal = (isset($_POST['idSucursal'])) ? $_POST['idSucursal'] : '' ;
print_r($_POST);
if ($ids_Ventas == '' ) {
    errorBD('No se reconocieron las ventas, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
  }
  $operacion='3';
  $datosCliente=array(
    "rfc"=>'XAXX010101000',
    "razonSocial"=>'Publico en General',
    "nombre"=>'',
    "apellido"=>'',
    "calle"=>'',
    "noInt"=>'',
    "noExt"=>'',
    "colonia"=>'',
    "municipio"=>'',
    "estado"=>'',
    "codpostal"=>'',
    "correo"=>'',
    "telefono"=>'',
    "correo2"=>'',
    "correo3"=>'',
    "usoCFDI"=>'',
    "idCliente"=>'28',
    "operacion"=>$operacion);


$respuesta=facturar($ids_Ventas, $datosCliente, $link, '1', '1');
print_r($respuesta);















  /*$respuesta=facturar($ids_Ventas, $datosCliente, $link, '1', '1');
  //print_r($respuesta);
  switch ($respuesta["estatus"]){
    case '1':
        echo '1|'.$respuesta["mensaje"];
        $_SESSION['LZFmsjSuccessAdminVentas']="Facturación Correcta";
      break;
    case '0':
    
      $arrayErrores=array($respuesta["idsErroneos"]);
    //  print_r($arrayErrores);
      if(is_array($arrayErrores[0])){
   // $arrayErrores1= array($arrayErrores[0]);
    //print_r($arrayErrores1);
        if(array_key_exists('message',$arrayErrores[0])){
         // print_r("Estupida1");
          
           echo '0|'.$respuesta["mensaje"]."|".$arrayErrores[0]["message"];
         }else{
         $stringError=json_decode($arrayErrores[0]);
        // print_r($stringError);
           echo '0|'.$respuesta["mensaje"]."|".$stringError[0];
         }
      }
      else{
       // print_r("Estupida3");
        echo '0|'.$respuesta["mensaje"]."|".json_decode($respuesta["idsErroneos"])[0];
      }
      break;
    case '3':
        echo '0|'.$respuesta["mensaje"]."|".json_decode($respuesta["idsErroneos"])[0];
      break;  
  
  }*/





























function errorBD($error){
    echo '0|'.$error;
    exit(0);
  }
?>