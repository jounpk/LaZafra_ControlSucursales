<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
$ident = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($ident == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
$sqlCon = "SELECT gstos.idServicio, suc.nombre AS sucursal, gstos.idSucursal AS idSuc FROM pagos gstos INNER JOIN sucursales suc ON gstos.idSucursal=suc.id  WHERE (gstos.id = '$ident')";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar el Gasto, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorBD('No se encuentra al Gasto emitido por la sucursal, notifica a tu Administrador');
}
//TRATAR ARCHIVO DEL GASTO
$array = mysqli_fetch_array($resCon);
$sucursal=$array['sucursal'];
$idsucursal=$array['idSuc'];

//$sql = "SELECT nombre FROM sucursales WHERE id = '$idsucursal'";
//$result=mysqli_query($link,$sql) or die(errorBD('No se pudo validar el archivo del pago.</b>.'));
//$arraysuc = mysqli_fetch_array($result);
//$namesuc = $arraysuc['nombre'];
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
    $name = eliminar_simbolos($sucursal);
    $desc=$array['descripcion'];
    $fileName = 'ID'.$ident.'-'.$desc;
    $fileName = str_replace(" ", "_", $fileName).'.'.$extensiondocto;
    //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  $carpetaAlm = 'doctos/Pagos/'.$name.'/'.date("Y-m-d");
  $carpeta = '../doctos/Pagos/'.$name.'/'.date("Y-m-d");
  if (!file_exists($carpeta)) {
      mkdir($carpeta, 0777, true);
  }
$url = $carpeta.'/'.$fileName;
$urlReg= $carpetaAlm.'/'.$fileName;
//echo "URL: ".$url;
if(move_uploaded_file($_FILES['rdocto']['tmp_name'], $url)){
  $sql = "UPDATE pagos SET doctoPago = '$urlReg', extensionPago='$extensiondocto' WHERE id = '$ident'";
  //echo '<br>'.$sql.'<br>';
 mysqli_query($link,$sql) or die(errorBD('No se pudo validar el archivo del Pago.</b>.'));
 $resarray=mysqli_fetch_array($resCon);
$name=$resarray['descripcion'];
$sql = "UPDATE pagos SET pagado=1, fechaPago=NOW(), idUserPago='$userReg', idSucPago='$idsucursal' WHERE id='$ident'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Registrar el Pago, notifica a tu Administrador'));




 echo'1|El Pago  <b>'.$desc.'</b> se ha registrado correctamente.';
}
else{
  echo'0|El Pago  <b>'.$desc.'</b> no se ha registrados, intentálo de nuevo o Notifica a tu Administrador.';

}

  }

function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
