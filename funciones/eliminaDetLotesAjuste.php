
<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$idDetAjuste= (isset($_POST['idDetAjuste'])) ? trim($_POST['idDetAjuste']) : '' ;
$debug = 0;

//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

if ($idDetAjuste=='') {
    errorBD('No se reconoció  Los Datos del Lote A Descontar, actualiza e intenta otra vez, si persiste notifica a tu Administrador', '');
  }
$sql="DELETE FROM detajustelote WHERE idDetAjuste='$idDetAjuste'";


//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Desvincular Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: '.$sql.'<br>';
  echo '<br>Cant de Registros Cargados: '.$canInsert;
} else {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Desvincular Lotes en el Detallado, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
echo "1|Desvinculo de Lote Correcto";
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