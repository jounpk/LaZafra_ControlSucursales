<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');

$idProveedor = (isset($_POST['idProveedor']) AND $_POST['idProveedor'] != '') ? trim($_POST['idProveedor']) : '' ;
$anotaciones = (isset($_POST['anotaciones']) AND $_POST['anotaciones'] != '') ? $_POST['anotaciones'] : '' ;
$anotaciones = (isset($_POST['anotaciones']) AND $_POST['anotaciones'] != '') ? $_POST['anotaciones'] : '' ;
$tipo = (isset($_POST['tipo']) AND $_POST['tipo'] != '') ? $_POST['tipo'] : '' ;
//print_r($_POST);
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($idProveedor == '' || $tipo== '' ) {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
//---------------------------------------CONSULTAR EL NOMBRE DEL PROVEEDOR-------------------//
$sql="SELECT nombre FROM proveedores WHERE id='$idProveedor'";
$resultXProv=mysqli_query($link,$sql) or die(errorBD('No se pudo validar el registro del proveedor, consulta a tu Administrador.'));
$name_proveedor=mysqli_fetch_array($resultXProv)["nombre"];
//---------------------------------------GUARDAR ARCHIVO-------------------------------------//
if ($_FILES["preciosDocto"]["error"] > 0 ){
  //echo "Error: " . $_FILES['foto']['error'] . "<br>";
  $error = '<b>No se pudo cargar el archivo de lista de Precios.</b>';

}else {
//--------------------- Obteniendo extencion del Archivo -------------------
  $archivo = $_FILES['preciosDocto']['name'];
  $valores = explode(".", $archivo);
  $fechaActual=date("Ymd");
  $extensiondocto = $valores[count($valores)-1];

  if($tipo=='1'){
    $fileName = 'convenio_ID_'.$idProveedor.'-'.$name_proveedor.$fechaActual;


  }
  else{
    $fileName = 'listaPrec_ID_'.$idProveedor.'-'.$name_proveedor.$fechaActual;

  }
  $fileName = str_replace(" ", "_", $fileName).'.'.$extensiondocto;
  //------ Se valida que exista La Carpeta y si no se Crea-------------------------
$carpetaAlm = 'doctos/Proveedores/'.$name_proveedor;
$carpeta = '../doctos/Proveedores/'.$name_proveedor;
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}
$url = $carpeta.'/'.$fileName;
$urlReg= $carpetaAlm.'/'.$fileName;
//echo "URL: ".$url;
move_uploaded_file($_FILES['preciosDocto']['tmp_name'], $url);
$sql = "INSERT INTO doctosprov (idProveedor, docto, tipo, userReg, fechaReg, anotaciones) VALUES ('$idProveedor','$urlReg','$tipo', '$userReg', NOW(),'$anotaciones') ";
//echo '<br>'.$sql.'<br>';
mysqli_query($link,$sql) or die(errorBD('Error al Guardar Lista de precios, Notifica a tu Administrador.'));
echo'1|El archivo del Proveedor se ha guardado correctamente.';
$_SESSION['LZmsjSuccessConvenios']='El archivo del Proveedor se ha guardado correctamente.';



}




//-------------------------------------------------------------------------------------------//



function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
?>