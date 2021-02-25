
<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$idLote= (isset($_POST['idLote'])) ? trim($_POST['idLote']) : '' ;
$cantidad= (isset($_POST['cantidad'])) ? trim($_POST['cantidad']) : '' ;
$idDetTrasp= (isset($_POST['idDetTrasp'])) ? trim($_POST['idDetTrasp']) : '' ;
$debug = 0;

//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

if ($idLote == '' || $cantidad== '' || $idDetTrasp=='') {
    errorBD('No se reconoció  Los Datos del Lote A Agregar, actualiza e intenta otra vez, si persiste notifica a tu Administrador', '');
  }
$sql="SELECT id FROM dettrasplote WHERE idLoteSalida='$idLote' AND idDetTraspaso='$idDetTrasp'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Buscar Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: '.$sql.'<br>';
  echo '<br>Cant de Registros Cargados: '.$canInsert;
} else {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Buscar Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$cantres=mysqli_num_rows($resultXquery);
if($cantres>0){
  $array=mysqli_fetch_array($resultXquery);
  $idDetTraspLote=$array["id"]!=''?$array["id"]:'';
  
  $sql="UPDATE dettrasplote SET  cantidad= '$cantidad' WHERE id='$idDetTraspLote'";


  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Actualizar Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: '.$sql.'<br>';
    echo '<br>Cant de Registros Cargados: '.$canInsert;
  } else {
    $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Actualizar Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------

}

else{

  $sql="INSERT INTO dettrasplote(idDetTraspaso,idLoteSalida, cantidad) VALUES('$idDetTrasp','$idLote','$cantidad')";


  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: '.$sql.'<br>';
    echo '<br>Cant de Registros Cargados: '.$canInsert;
  } else {
    $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------



}





echo "1|Registro de Lote Correcto";
function errorBD($error, $sql_error){
  if ($GLOBALS['debug'] == 1) {
    echo '<br><br>Det Error: '.$error;
    echo '<br><br>Error Report: '.$sql_error;
    

  } else {
    echo '0|'.$error;
  }
  exit(0);
  }

?>