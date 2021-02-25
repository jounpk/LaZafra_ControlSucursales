<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
$nombreDelDocto = (isset($_POST['nombreDelDocto'])) ? trim($_POST['nombreDelDocto']) : '';
$idCliente = (isset($_POST['idCliente'])) ? trim($_POST['idCliente']) : '';



$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$ident = (isset($_POST['ident'])) ? trim($_POST['ident']) : '';
$debug = 0;



//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

if ($nombreDelDocto == ''  and $idCliente == '') {
  errorBD('No se reconoció  datos Del Documento A Guardar, actualiza e intenta otra vez, si persiste notifica a tu Administrador', '');
}





//============================================ Carga de FOTO ====================================================
if ($_FILES["docto"]["error"] > 0) {
  //echo "Error: " . $_FILES['foto']['error'] . "<br>";
  $error = ' pero <br><b>No se pudo cargar la Imagen</b>';
} else {
  //------Fotografia
  $archivo = $_FILES['docto']['name'];
  $valores = explode(".", $archivo);
  $extension = $valores[count($valores) - 1];
  $fecha = date('YmdHis');
  $fileName = 'ClienteID-' . $idCliente . $fecha;
  $fileName = str_replace(" ", "_", $fileName) . '.' . $extension;

  //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  $carpetaAlm = 'doctos/Clientes';
  $carpeta = '../doctos/Clientes';
  if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true) or die("No se puede crear el directorio");
  }


  $url = $carpeta . '/' . $fileName;
  $urlReg = $carpetaAlm . '/' . $fileName;

  if (move_uploaded_file($_FILES['docto']['tmp_name'], $url)) {
    $sql = "INSERT INTO doctoclientes(idCliente,tipo,fechaReg,userReg, docto) VALUES('$idCliente','$nombreDelDocto',NOW(),'$userReg', '$urlReg')";
    //----------------devBug------------------------------
    if ($debug == 1) {
      $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Guardar Documento, notifica a tu Administrador', mysqli_error($link)));
      $canInsert = mysqli_affected_rows($link);
      echo '<br>SQL: ' . $sql . '<br>';
      echo '<br>Cant de Registros Cargados: ' . $canInsert;
    } else {
      $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Guardar Documento, notifica a tu Administrador', mysqli_error($link)));
      $canInsert = mysqli_affected_rows($link);
    } //-------------Finaliza devBug------------------------------
    echo "1|Registro de Clientes Correcto";
    $_SESSION['ADCmsjSuccessClientes'] = "Registro de Documentos de Clientes  Correcto";
  }
}





function errorBD($error, $sql_error)
{
  if ($GLOBALS['debug'] == 1) {
    echo '<br><br>Det Error: ' . $error;
    echo '<br><br>Error Report: ' . $sql_error;
  } else {
    echo '0|' . $error;
  }
  exit(0);
}
