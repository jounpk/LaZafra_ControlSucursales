<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$idFisc = (isset($_POST['id'])) ? $_POST['id'] : '' ;

if($idFisc==''){
    errorBD("No se encontraron datos especificos, intentalo de Nuevo");
}

$radiosButtons="";
$sql="SELECT DISTINCT
dts.rfc, 
dts.razonSocial
FROM datosfisc dts WHERE dts.rfc='$idFisc'";
$resXRFCS = mysqli_query($link,$sql) or die('Problemas al consultar los RFC del Cliente, notifica a tu Administrador.');
while($dat=mysqli_fetch_array($resXRFCS)){
    $radiosButtons.="<input type='radio' id='datosEnc'  name='datosEnc' onclick='recuperarCliente(this.value)' value='".$dat["rfc"]."'>
    <label for='datosEnc'>".$dat["razonSocial"]." (".$dat["rfc"].")</label><br>";
}


echo $radiosButtons;

function errorBD($error){
    echo '0|'.$error;
  }
?>