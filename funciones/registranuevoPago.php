<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');

$desc = (isset($_POST['rdescGasto']) AND $_POST['rdescGasto'] != '') ? trim($_POST['rdescGasto']) : '' ;
$monto = (isset($_POST['rMonto']) AND $_POST['rMonto'] != '') ? $_POST['rMonto'] : '' ;
$fechavence = (isset($_POST['rfechavence']) AND $_POST['rfechavence'] != '') ? $_POST['rfechavence'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($desc == '' || $monto == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
$fechavence=date_format(date_create($fechavence), 'Y-m-d');
$sql = "INSERT INTO pagos (idSucursal,monto, idServicio, idUserReg,fechaReg, pagado,fechaVencimiento)
VALUES('$sucursal','$monto','$desc','$userReg', NOW(),0,'$fechavence')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Pago, notifica a tu Administrador'.mysqli_error($link)));
$iden = mysqli_insert_id($link);

//TRATAR ARCHIVO DEL GASTO
$sql = "SELECT nombre FROM sucursales WHERE id = '$sucursal'";
$result=mysqli_query($link,$sql) or die(errorBD('Pago registrado  <b>'.$desc.'</b> pero <br><b>No se pudo validar el archivo del gasto.</b>.'));
$arraysuc = mysqli_fetch_array($result);
$namesuc = $arraysuc['nombre'];
//echo "<br>Sucursal: ".$namesuc;
if ($_FILES["rdocto"]["error"] > 0 ){
    //echo "Error: " . $_FILES['foto']['error'] . "<br>";
    $error = 'Pago se ha registrado pero  <br><b>No se pudo cargar el archivo del pago.</b>';

  }else {
        //--------------------- Obteniendo extencion del Archivo -------------------

    $archivo = $_FILES['rdocto']['name'];
    $valores = explode(".", $archivo);
    $extensiondocto = $valores[count($valores)-1];
    //echo "Tipo de archivo: ".$extensiondocto;
    $name = eliminar_simbolos($namesuc);
    $fileName = 'ID'.$iden.'-'.$desc;
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
move_uploaded_file($_FILES['rdocto']['tmp_name'], $url);
$sql = "UPDATE pagos SET doctoRecibos = '$urlReg', extensionRecibos='$extensiondocto' WHERE id = '$iden'";
  //echo '<br>'.$sql.'<br>';
 mysqli_query($link,$sql) or die(errorBD('Pago registrado  pero <br><b>No se pudo validar el archivo del pago.</b>.'));
 echo'1|El Pago se ha creado correctamente.';

  }

function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
