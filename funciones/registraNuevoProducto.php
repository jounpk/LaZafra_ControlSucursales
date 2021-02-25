<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
require_once('../assets/scripts/Thumb.php');

$codbarra = (isset($_POST['codbarra']) AND $_POST['codbarra'] != '') ? $_POST['codbarra'] : '' ;
$desc = (isset($_POST['descripcion']) && $_POST['descripcion'] != '') ? $_POST['descripcion'] : '' ;
$nombre=(isset($_POST['nombre']) && $_POST['nombre'] != '') ? $_POST['nombre'] : '' ;
$medios = (isset($_POST['medios']) AND $_POST['medios'] != '') ? $_POST['medios'] : '' ;
$presentacion = (isset($_POST['presentacion']) AND $_POST['presentacion'] != '') ? $_POST['presentacion'] : '' ;
$unidadEmbalaje = (isset($_POST['unidadEmbalaje']) AND $_POST['unidadEmbalaje'] != '') ? $_POST['unidadEmbalaje'] : '0' ;
$cantEmbalaje = (isset($_POST['cantEmbalaje']) AND $_POST['cantEmbalaje'] != '') ? $_POST['cantEmbalaje'] : '' ;

//$desc = (isset($_POST['']) && $_POST['descripcion'] != '') ? $_POST['descripcion'] : '' ;
$dep = (isset($_POST['departamento']) AND $_POST['departamento'] != '') ? $_POST['departamento'] : '' ;
$marca = (isset($_POST['marca']) AND $_POST['marca'] != '') ? $_POST['marca'] : '' ;
$costo = (isset($_POST['costo']) AND $_POST['costo'] != '') ? trim($_POST['costo']) : '' ;

$seg = (isset($_POST['seg']) AND $_POST['seg'] != '') ? $_POST['seg'] : '' ;
$precios=(isset($_POST['precios']) AND $_POST['precios'] != '') ? $_POST['precios'] : '' ;
$ingredienteActivo = (isset($_POST['ingredienteActivo']) AND $_POST['ingredienteActivo'] != '') ? $_POST['ingredienteActivo'] : '' ;

$clavepro = (isset($_POST['clavepro']) AND $_POST['clavepro'] != '') ? $_POST['clavepro'] : '' ;
$claveuni = (isset($_POST['claveuni']) AND $_POST['claveuni'] != '') ? $_POST['claveuni'] : '' ;
$return = (isset($_POST['return']) AND $_POST['return'] != '') ? $_POST['return'] : '' ;
$prioridad = (isset($_POST['prioridad']) AND $_POST['prioridad'] != '') ? $_POST['prioridad'] : '' ;
#$montoExtra = (isset($_POST['montoExtra']) AND $_POST['montoExtra'] != '') ? $_POST['montoExtra'] : '2' ;

$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($nombre == '' || $medios == '' || $dep == '' || $marca == '' || $costo <=0 
|| $clavepro == '' || $claveuni == ''|| $seg=='') {
 errorCarga('Capturar todos los datos necesarios para registrar un producto, inténtalo de nuevo');
}
$tagsActivo = implode(", ", $ingredienteActivo);
if($codbarra!=''){
$sqlCon = "SELECT * FROM productos WHERE codBarra = '$codbarra'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Productos, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorCarga('Ya se encuentra un Producto con el mismo código de barras, notifica a tu Administrador');
}
}
$sql = "INSERT INTO productos (codBarra,descripcionlarga,costo,idDepartamento, 
medios, idCatMarca, idClaveProducto, idTagsIngredienteActivo, idUserReg,fechaReg,idSucursalReg, estatus, seguimiento, idClavUniProducto, prioridad,descripcion,
unidadEmbalaje, cantEmbalaje, presentacion) VALUES('$codbarra', '$desc', '$costo', '$dep', 
'$medios', '$marca', '$clavepro', '$tagsActivo', '$userReg', NOW(), '$sucursal', 1, '$seg', '$claveuni', '$prioridad', '$nombre', '$unidadEmbalaje', '$cantEmbalaje', '$presentacion')";
$res = mysqli_query($link,$sql) or die(errorCarga('Problemas al Capturar el Producto, notifica a tu Administrador'));
$iden = mysqli_insert_id($link);
/*echo 'id: '.$iden.'<br>';*/
$error = '';
$arrayprecios=json_decode($precios);
//print_r($arrayprecios);
for ($i=0; $i <= count($arrayprecios)-1 ; $i++) { 
  $precio=trim($arrayprecios[$i][0]);
  $cantLibera=trim($arrayprecios[$i][1]);
 $sql="INSERT INTO preciosbase(idProducto,precio,cantLibera, fechaReg,userReg) VALUES('$iden','$precio','$cantLibera',NOW(),'$userReg')";
 $res = mysqli_query($link,$sql) or die(errorCarga('Problemas al Capturar el Precio, notifica a tu Administrador'));

}
$sql = "SELECT nombre FROM catmarcas WHERE id = '$marca'";
$result=mysqli_query($link,$sql) or die(errorCarga(' pero <br><b>No se pudo validar la imagen reintente subir la foto.</b>.'));
$marka = mysqli_fetch_array($result);
$mark = $marka['nombre'];

//============================================ Carga de FOTO ====================================================
if ($_FILES["fotopro"]["error"] > 0 ){
  //echo "Error: " . $_FILES['foto']['error'] . "<br>";
  $error = ' pero <br><b>No se pudo cargar la Imagen</b>';

}else {
  // La Foto no Genera Error.

  //--------------------- Obteniendo extencion del Archivo -------------------
  //------Fotografia
  $archivo = $_FILES['fotopro']['name'];
  $valores = explode(".", $archivo);
  $extension = $valores[count($valores)-1];

  $name = eliminar_simbolos($mark);
  $fileName = 'ID'.$iden.'-'.$mark.'_codb'.$codbarra;
  $fileName = str_replace(" ", "_", $fileName).'.'.$extension;

  //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  $carpetaAlm = 'doctos/Productos/';
  $carpeta = '../doctos/Productos/';
  if (!file_exists($carpeta)) {
      mkdir($carpeta, 0777, true);
  }
  $carpetaOrig = '../doctos/Productos/original/';
  if (!file_exists($carpetaOrig)) {
      mkdir($carpetaOrig, 0777, true);
  }

  $url = $carpeta.'original/'.$fileName;
  $urlAvatar = $carpeta.$fileName;

  /*--------------------------Datos para Carga de Archivos Lista ------------------------------
   echo"Nombre Nuevo: " . $fileName . "<br>";
   echo"Tipo: " . $_FILES['foto']['type'] . "<br>";
   echo"Tamaño: " . ($_FILES["foto"]["size"] / 1024) . " kB<br>";
   echo"Carpeta temporal: " . $_FILES['foto']['tmp_name'] . " <br>";
   echo'<br>';

   echo"Carpeta Final: " . $carpetaAlm . " <br>";
   echo"URL Original: " . $url . " <br>";
   echo"URL Avatar: " . $urlAvatar . " <br><br>";*/

  //--------------------------Se mueven Archivos para trabajarlos ------------------------------
  move_uploaded_file($_FILES['fotopro']['tmp_name'], $url);

  $thumb = new Thumb();
  $thumb->loadImage($url);
  $thumb->resize(500, 300);
  $thumb->save($urlAvatar, 100, false);

  //echo"URL Generado: " . $urlAvatar . " <br><br>";
  $urlAvatar2 = str_replace('.'.$extension, '', $urlAvatar);
  $urlAvatar .= '.jpeg';
  $urlAvatar2 .= '.jpeg';

  //echo"URL Avatar1: " . $urlAvatar . " <br>";
  //echo"URL Avatar2: " . $urlAvatar2 . " <br><br>";

  rename($urlAvatar, $urlAvatar2);
  $urlReg = str_replace('../', '', $urlAvatar2);

  $sql = "UPDATE productos SET foto = '$urlReg' WHERE id = '$iden'";
  //echo '<br>'.$sql.'<br>';
  mysqli_query($link, $sql) or die(errorCarga(' pero <br><b>No se pudo cargar la Imagen</b>.'));
}
echo'1|El Producto  <b>'.$nombre.'</b> se ha creado correctamente.';

//$_SESSION['LZmsjSuccessProducto'] = 'El Producto <b>'.$desc.'</b> se a creado Corrrectamente.';
//header('location: ../Corporativo/catalogoProductos.php');
//echo "Listo";
/*if($return!="1"){
    header('location: ../Corporativo/catalogoProductos.php');
}
  else{
    header('location: ../Corporativo/configuraProducto.php');
}
  */
function errorCarga($error){
  echo '0|'.$error;
  exit(0);
}


function errorBD($msj)
{
  echo '0|'.$msj;
   exit(0);
}


 ?>