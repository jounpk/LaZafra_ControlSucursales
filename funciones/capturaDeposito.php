<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
require_once('../assets/scripts/Thumb.php');

# recepción de las variables enviadas por post
$fecha = (isset($_POST['fecha']) && $_POST['fecha'] != '') ? $_POST['fecha'] : '' ;
$idBanco = (!empty($_POST['idBanco'])) ? $_POST['idBanco'] : '' ;
$cuenta = (isset($_POST['cuentabanco']) && $_POST['cuentabanco'] != '') ? $_POST['cuentabanco'] : '' ;
$folio = (isset($_POST['folioTicket']) && $_POST['folioTicket'] != '') ? $_POST['folioTicket'] : '' ;
$monto = (isset($_POST['deposito']) && $_POST['deposito'] > 0) ? $_POST['deposito'] : 0 ;
$idDeposito = (!empty($_POST['idDeposito'])) ? $_POST['idDeposito'] : '' ;
$idCorte = (!empty($_POST['idCorte'])) ? $_POST['idCorte'] : '' ;
$vista = (!empty($_POST['vista'])) ? $_POST['vista'] : 1;
$idUser = $_SESSION['LZFident'];
# impresion de las variables
$cant = sizeof($folio);
/*
echo '<br>########################################################################<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>$_FILES:<br>';
print_r($_FILES);
echo '<br>';
echo '<br>$fecha: '.var_dump($fecha);
echo '<br>$idBanco: '.var_dump($idBanco);
echo '<br>$cuenta: '.var_dump($cuenta);
echo '<br>$folio: '.var_dump($folio);
echo '<br>$monto: '.var_dump($monto);
echo '<br>$idDeposito: '.var_dump($idDeposito);
echo '<br>$idCorte: '.var_dump($idCorte);
echo '<br>$idUser: '.var_dump($idUser);
echo '<br>$cant: '.$cant;
echo '<br>';
echo '<br>########################################################################<br>';
exit(0);
# */
# se consulta el deposito para ver si existe y su estatus
$sqlCon = "SELECT d.*, e.nameCto AS nomEmpresa, s.nameCorto
FROM depositos d
INNER JOIN cortes c ON d.idCorte = c.id
INNER JOIN sucursales s ON c.idSucursal = s.id
INNER JOIN empresas e ON s.idEmpresa = e.id
WHERE d.id = '$idDeposito' LIMIT 1";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los depósitos, notifica a tu Administrador.',$vista));
$d = mysqli_fetch_array($resCon);
# se consulta que hay registros, con un solo registro se actualiza la información
$n = mysqli_num_rows($resCon);
$empresa = $d['nomEmpresa'];
$sucursal = $d['nameCorto'];
$idCorte2 = $d['idCorte'];

$sqlDel = "SELECT CONCAT('../',docto) AS dct FROM detdepositos WHERE idDepositoRecoleccion = '$idDeposito'";
$resDel = mysqli_query($link,$sqlDel) or die('Problemas al consultar los documentos del depósito, notifica a tu Administrador');
$cantDel = mysqli_num_rows($resDel);
if ($cantDel > 0) {
while ($a = mysqli_fetch_array($resDel)) {
  if (file_exists($a['dct'])) {
    if (!unlink($a['dct'])) { }
    }
  }

$sqlDel = "DELETE FROM detdepositos WHERE idDepositoRecoleccion = '$idDeposito'";
#echo '<br>$sqlDel: '.$sqlDel;
$resDel = mysqli_query($link,$sqlDel) or die('Problemas al borrar los registros rechazados del depósito, notifica a tu Administrador');
}
##########################################################################################
if ($d['estatus'] == '1') {
  errorBD('No se capturó la información debido a que ya fue autorizado.',$vista);
}
##########################################################################################
if ($d['estatus'] == '2') {
  errorBD('No se capturó la información debido a que está pendiente de autorización.',$vista);
}
##########################################################################################
#se comienza con la captura de la evidencia de cada registro
$anio = date("Y");
$mes = date("m");
$dia = date("d");
$hora = date("H");
$minuto = date("i");
$matriz = array('foto0', 'foto1', 'foto2', 'foto3', 'foto4', 'foto5', 'foto6', 'foto7', 'foto8', 'foto9', 'foto10', 'foto11', 'foto12', 'foto13', 'foto14', 'foto15');
$matrizName = array('docto', 'docto1', 'docto2', 'docto3', 'docto4', 'docto5', 'docto6', 'docto7', 'docto8', 'docto9', 'docto10', 'docto11', 'docto12', 'docto13', 'docto14', 'docto15');
$cont=0;
$varError=0;
$contError= '';
for ($i=0; $i < $cant ; $i++) {
  $mat = $matriz[$i];
  $mat2 = $matrizName[$i];

if ($_FILES[$mat]["error"] > 0 ){
  //echo "Error: " . $_FILES[$mat]['error'] . "<br>";
  $contError .= ' pero <br><b>No se pudo cargar la Imagen</b>';
  $cont++;
}else {
  // La Foto no Genera Error.

  //--------------------- Obteniendo extencion del Archivo -------------------
  //------Fotografia
  $archivo = $_FILES[$mat]['name'];
  $valores = explode(".", $archivo);
  $extension = $valores[count($valores)-1];

  $fileName = $mes.$dia.'-'.$hora.$minuto.'-'.$i.'-Corte-'.$idCorte2;
  $fileName2 = str_replace(" ", "_", $fileName);
  $fileName = str_replace(" ", "_", $fileName).'.'.$extension;

  //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  $carpetaAlm = 'doctos/Recoleccion/'.$anio.'/'.$empresa.'/'.$sucursal.'/';
  $carpeta = '../doctos/Recoleccion/'.$anio.'/'.$empresa.'/'.$sucursal.'/';
  if (!file_exists($carpeta)) {
      mkdir($carpeta, 0777, true);
  }
  $carpetaOrig = $carpeta;
  if (!file_exists($carpetaOrig)) {
      mkdir($carpetaOrig, 0777, true);
  }
  $urlB = $carpeta.$fileName2.'.jpg';
  $url = $carpeta.$fileName;
  $urlAvatar = $carpeta.$fileName;

  /*--------------------------Datos para Carga de Archivos Lista ------------------------------
   echo"Nombre Nuevo: " . $fileName . "<br>";
   echo"Tipo: " . $_FILES[$mat]['type'] . "<br>";
   echo"Tamaño: " . ($_FILES[$mat]["size"] / 1024) . " kB<br>";
   echo"Carpeta temporal: " . $_FILES[$mat]['tmp_name'] . " <br>";
   echo'<br>';

   echo"Carpeta Final: " . $carpetaAlm . " <br>";
   echo"URL Original: " . $url . " <br>";
   echo"URL Avatar: " . $urlAvatar . " <br><br>";*/

  //--------------------------Se mueven Archivos para trabajarlos ------------------------------
  move_uploaded_file($_FILES[$mat]['tmp_name'], $url);

  $thumb = new Thumb();
  $thumb->loadImage($url);
  $thumb->resize(500,'width');  // redimensiono la imagen a 100px en alto
  #$thumb->crop(250, 250, 'center');
  $thumb->save($urlAvatar, 80, false);

  //echo"URL Generado: " . $urlAvatar . " <br><br>";
  $urlAvatar2 = str_replace('.'.$extension, '', $urlAvatar);
  $urlAvatar .= '.jpeg';
  $urlAvatar2 .= '.jpeg';

  //echo"URL Avatar1: " . $urlAvatar . " <br>";
  //echo"URL Avatar2: " . $urlAvatar2 . " <br><br>";

  rename($urlAvatar, $urlAvatar2);
  $urlReg = str_replace('../', '', $urlAvatar2);

  $monto[$i] = str_replace(',','',$monto[$i]);
  #echo $monto[$i];
  $total += $monto[$i];
    if ($idDeposito >= 1) {
      $noCuenta = ($cuentabanco[$i] != '') ? $cuentabanco[$i] : '' ;
      $sql = "INSERT INTO detdepositos(idDepositoRecoleccion,monto,docto,extension,folio,fechaReg,idUserReg,idClaveBanco,idCuentaBancaria,fechaDeposito,estatus)
                            VALUES('$idDeposito','$monto[$i]','$urlReg','jpeg','$folio[$i]',NOW(),'$idUser','$idBanco[$i]','$cuenta[$i]','$fecha[$i]','2')";
      echo '<br>$sql: '.$sql.'<br>';
      mysqli_query($link, $sql) or die(errorCarga('<b>No se pudo cargar la Imagen</b>.',$vista));
    }
    if (!unlink($urlB)) { }
  }
}

if (!unlink($urlB)) { }

$sqlUp = "UPDATE depositos SET estatus = '2', fechaReg = NOW() WHERE id = '$idDeposito' LIMIT 1";
$resUp = mysqli_query($link,$sqlUp) or die(errorBD('Problemas al actualizar los depósitos, notifica a tu Administrador.',$vista));

#		echo '<br> Línea 66';
if ($vista == 1) {
  $_SESSION['LZFmsjSuccessCortesPendientes'] = 'Recolección capturada exitosamente.';
  header('location: ../Chofer/cortesPendientes.php');
  exit(0);
} else {
  $_SESSION['LZFmsjSuccessHistorialRecolector'] = 'Recolección capturada exitosamente.';
  header('location: ../Chofer/historialDeRecoleccion.php');
  exit(0);
}

  function errorBD($error,$vista){
    #		echo '<br> Línea 66';
    if ($vista == 1) {
      $_SESSION['LZFmsjCortesPendientes'] = $error;
      header('location: ../Chofer/cortesPendientes.php');
      exit(0);
    } else {
      $_SESSION['LZFmsjHistorialRecolector'] = $error;
      header('location: ../Chofer/historialDeRecoleccion.php');
    }
  }

  function errorCarga($error,$vista){
    #		echo '<br> Línea 66';
    if ($vista == 1) {
      $_SESSION['LZFmsjSuccessCortesPendientes'] = $error;
      header('location: ../Chofer/cortesPendientes.php');
      exit(0);
    } else {
      $_SESSION['LZFmsjSuccessHistorialRecolector'] = $error;
      header('location: ../Chofer/historialDeRecoleccion.php');
    }
  }
 ?>
