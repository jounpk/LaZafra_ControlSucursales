<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$respuesta = '';
$sqlCult = "SELECT ctc.id,ctc.nombre
            FROM cultivosxmunicipios cxm
            INNER JOIN catcultivos ctc ON cxm.idCultivo = ctc.id
            WHERE cxm.idMunicipio = '$id'";
$resCult = mysqli_query($link,$sqlCult) or die("Problemas al consultar los <b>cultivos</b>, por favor notifica a tu Administrador");
$cant = mysqli_num_rows($resCult);
#echo '<br>Cant: '.$cant.'<br>';
if ($cant > 0 ) {
  $respuesta .= '<option value="">Selecciona un Cultivo</option>';
  while ($cult = mysqli_fetch_array($resCult)) {
    $respuesta .= '<option value="'.$cult['id'].'">'.$cult['nombre'].'</option>';
    }
  } else {
    $respuesta = '<option value="">Selecciona un Municipio</option>';
  }
echo $respuesta;
 ?>
