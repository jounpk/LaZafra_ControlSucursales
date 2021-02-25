<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('CFDIS/CFDI_FACTURACION_FACTCOM.PHP');
$idVenta = (isset($_POST['idVenta'])) ? $_POST['idVenta'] : '' ;
$rfc= (isset($_POST['rfc'])) ? $_POST['rfc'] : '' ;
$razonSocial = (isset($_POST['razonSocial'])) ? $_POST['razonSocial'] : '' ;
$nombre= (isset($_POST['nombre'])) ? $_POST['nombre'] : '' ;
$apellidos= (isset($_POST['apellido'])) ? $_POST['apellido'] : '' ;
$calle= (isset($_POST['calle'])) ? $_POST['calle'] : '' ;
$noInt= (isset($_POST['noInt'])) ? $_POST['noInt'] : '' ;
$noExt= (isset($_POST['noExt'])) ? $_POST['noExt'] : '' ;
$colonia= (isset($_POST['colonia'])) ? $_POST['colonia'] : '' ;
$municipio= (isset($_POST['municipio'])) ? $_POST['municipio'] : '' ;
$estado= (isset($_POST['estado'])) ? $_POST['estado'] : '' ;
$codpostal= (isset($_POST['codpostal'])) ? $_POST['codpostal'] : '' ;
$correo= (isset($_POST['correo'])) ? $_POST['correo'] : '' ;
$telefono= (isset($_POST['telefono'])) ? $_POST['telefono'] : '' ;
$correo2= (isset($_POST['correo2'])) ? $_POST['correo2'] : '' ;
$correo3= (isset($_POST['correo3'])) ? $_POST['correo3'] : '' ;
$cfdi= (isset($_POST['cfdi'])) ? $_POST['cfdi'] : '' ;
$idCliente= (isset($_POST['idCliente'])) ? $_POST['idCliente'] : '' ;
$modificar= (isset($_POST['modificar'])) ? $_POST['modificar'] : '0' ;
$operacion='';
$telefono=str_replace("(", "", $telefono);
$telefono=str_replace(")", "", $telefono);
$telefono=str_replace("-", "", $telefono);
$telefono=str_replace(" ", "", $telefono);
if ($idVenta <  1) {
    errorBD('No se reconoció la venta, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
  }
$correo=($correo=="Sin Capturar")?"":$correo;
$correo2=($correo2=="Sin Capturar")?"":$correo2;
$correo3=($correo2=="Sin Capturar")?"":$correo3;
$operacion=($modificar=='0' AND $idCliente=='')?'1':$operacion; //REGISTRO DEL CLIENTE
$operacion=($modificar=='1' AND $idCliente!='')?'2':$operacion; //MODIFICAR DEL CLIENTE
$operacion=($modificar=='0' AND $idCliente!='')?'3':$operacion; //SOLO FACTURAR
//print_r($_POST);
$datosCliente=array(
    "rfc"=>$rfc,
    "razonSocial"=>$razonSocial,
    "nombre"=>$nombre,
    "apellido"=>$apellidos,
    "calle"=>$calle,
    "noInt"=>$noInt,
    "noExt"=>$noExt,
    "colonia"=>$colonia,
    "municipio"=>$municipio,
    "estado"=>$estado,
    "codpostal"=>$codpostal,
    "correo"=>$correo,
    "telefono"=>$telefono,
    "correo2"=>$correo2,
    "correo3"=>$correo3,
    "usoCFDI"=>$cfdi,
    "idCliente"=>$idCliente,
    "operacion"=>$operacion);
//print_r("Esta es la razon Social-->".$razonSocial);
$ids_Ventas=array($idVenta);
$respuesta=facturar($ids_Ventas, $datosCliente, $link, '1', '0');
//print_r($respuesta);
switch ($respuesta["estatus"]){
  case '1':
      echo '1|'.$respuesta["mensaje"];
      $_SESSION['LZFmsjSuccessCorpBol']="Facturación Correcta";
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

}





function errorBD($error){
    echo '0|'.$error;
  }
?>