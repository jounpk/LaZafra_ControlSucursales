<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
$ident = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;

$desc = (isset($_POST['rdescGasto']) AND $_POST['rdescGasto'] != '') ? trim($_POST['rdescGasto']) : '' ;
$monto = (isset($_POST['rMonto']) AND $_POST['rMonto'] != '') ? $_POST['rMonto'] : '' ;
$fechavence = (isset($_POST['rfechavence']) AND $_POST['rfechavence'] != '') ? $_POST['rfechavence'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($desc == '' || $monto == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
$sqlCon = "SELECT * FROM pagos gstos WHERE (id = '$ident') AND idSucursal='$sucursal' AND idUserReg='$userReg'";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar el Pago, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorBD('No se encuentra el Pago emitido por la sucursal, notifica a tu Administrador');
}
//TRATAR ARCHIVO DEL GASTO
$sql = "SELECT nombre FROM sucursales WHERE id = '$sucursal'";
$result=mysqli_query($link,$sql) or die(errorBD('No se pudo validar el archivo del gasto.</b>.'));
$arraysuc = mysqli_fetch_array($result);
$namesuc = $arraysuc['nombre'];
//echo "<br>Sucursal: ".$namesuc;
if ($_FILES["rdocto"]["error"] > 0 ){
    //echo "Error: " . $_FILES['foto']['error'] . "<br>";
    errorBD('No se pudo cargar el archivo del gasto.');

  }else {
        //--------------------- Obteniendo extencion del Archivo -------------------

    $archivo = $_FILES['rdocto']['name'];
    $valores = explode(".", $archivo);
    $extensiondocto = $valores[count($valores)-1];
    //echo "Tipo de archivo: ".$extensiondocto;
    $name = eliminar_simbolos($namesuc);
    $fileName = 'ID'.$ident.'-'.$desc;
    $fileName = str_replace(" ", "_", $fileName).'.'.$extensiondocto;
    //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  $carpetaAlm = 'doctos/Pagos/Recibos/'.$name.'/'.date("Y-m-d");
  $carpeta = '../doctos/Pagos/Recibos/'.$name.'/'.date("Y-m-d");
  if (!file_exists($carpeta)) {
      mkdir($carpeta, 0777, true);
  }
$url = $carpeta.'/'.$fileName;
$urlReg= $carpetaAlm.'/'.$fileName;
//echo "URL: ".$url;
if(move_uploaded_file($_FILES['rdocto']['tmp_name'], $url)){
  $sql = "UPDATE pagos SET doctoRecibos = '$urlReg', extensionRecibos='$extensiondocto' WHERE id = '$ident'";
  //echo '<br>'.$sql.'<br>';
 mysqli_query($link,$sql) or die(errorBD('Pago actualizado  <b>'.$desc.'</b> pero <br><b>No se pudo validar el archivo del gasto.</b>.'));
 $resarray=mysqli_fetch_array($resCon);
//$name=$resarray['descripcion'];
$fechavence=date_format(date_create($fechavence), 'Y-m-d');

$sql = "UPDATE pagos SET idServicio='$desc', monto='$monto', fechaVencimiento='$fechavence' WHERE id='$ident'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Actualizar el Gasto, notifica a tu Administrador'));




 echo'1|El Pago   se ha actualizado correctamente.';
}
else{
  echo'0|El Pago  no se ha actualizado, intentálo de nuevo o Notifica a tu Administrador.';

}

  }

function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
