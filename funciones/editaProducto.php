<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
require_once('../assets/scripts/Thumb.php');
$iden=(isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$codbarra = (isset($_POST['codbarra']) AND $_POST['codbarra'] != '') ? $_POST['codbarra'] : '' ;
$desc = (isset($_POST['descripcion']) && $_POST['descripcion'] != '') ? $_POST['descripcion'] : '' ;
$nombre = (isset($_POST['nombre']) && $_POST['nombre'] != '') ? $_POST['nombre'] : '' ;
$presentacion = (isset($_POST['presentacion']) AND $_POST['presentacion'] != '') ? $_POST['presentacion'] : '' ;
$unidadEmbalaje = (isset($_POST['unidadEmbalaje']) AND $_POST['unidadEmbalaje'] != '') ? $_POST['unidadEmbalaje'] : '0' ;
$cantEmbalaje = (isset($_POST['cantEmbalaje']) AND $_POST['cantEmbalaje'] != '') ? $_POST['cantEmbalaje'] : '' ;
$medios = (isset($_POST['medios']) AND $_POST['medios'] != '') ? $_POST['medios'] : '' ;
$tagsActivo = (isset($_POST['ingredienteActivo']) AND $_POST['ingredienteActivo'] != '') ? $_POST['ingredienteActivo'] : '' ;

//$desc = (isset($_POST['']) && $_POST['descripcion'] != '') ? $_POST['descripcion'] : '' ;
$dep = (isset($_POST['departamento']) AND $_POST['departamento'] != '') ? $_POST['departamento'] : '' ;
$marca = (isset($_POST['marca']) AND $_POST['marca'] != '') ? $_POST['marca'] : '' ;
$costo = (isset($_POST['costo']) AND $_POST['costo'] != '') ? $_POST['costo'] : '' ;
$precios=(isset($_POST['precios']) AND $_POST['precios'] != '') ? $_POST['precios'] : '' ;
$prioridades = (isset($_POST['prioridad']) AND $_POST['prioridad'] != '') ? $_POST['prioridad'] : '' ;
$ingredienteActivo = (isset($_POST['ingredienteActivo']) AND $_POST['ingredienteActivo'] != '') ? $_POST['ingredienteActivo'] : '' ;
$clavepro = (isset($_POST['clavepro']) AND $_POST['clavepro'] != '') ? $_POST['clavepro'] : '' ;
$claveuni = (isset($_POST['claveuni']) AND $_POST['claveuni'] != '') ? $_POST['claveuni'] : '' ;
$return = (isset($_POST['return']) AND $_POST['return'] != '') ? $_POST['return'] : '' ;
$seg = (isset($_POST['seg']) AND $_POST['seg'] != '') ? $_POST['seg'] : '' ;
$fichaTecnica = (isset($_POST['fichaTecnica']) AND $_POST['fichaTecnica'] != '') ? $_POST['fichaTecnica'] : '' ;
$img = (isset($_POST['rutaImg']) AND $_POST['rutaImg'] != '') ? $_POST['rutaImg'] : '' ;

#$montoExtra = (isset($_POST['montoExtra']) AND $_POST['montoExtra'] != '') ? $_POST['montoExtra'] : '2' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$fecha=date('Ymd_his');
if ( $nombre == '' || $dep == '' || $marca == '' || $costo <= 0 || $clavepro == '' || $claveuni == ''  ) {
 errorCarga('Capturar todos los datos necesarios para registrar un producto, inténtalo de nuevo');
}
if($tagsActivo!=''){
  $tagsActivo = implode(", ", $ingredienteActivo);

}

$sqlCon = "SELECT * FROM productos WHERE id = '$iden'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Productos, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  

$sql = "UPDATE productos SET codBarra = '$codbarra', descripcionlarga = '$desc', costo = '$costo', idDepartamento = '$dep',
medios = '$medios', idCatMarca = '$marca',idClaveProducto = '$clavepro', idTagsIngredienteActivo = '$tagsActivo', fechaReg=NOW(), prioridad='$prioridades',
cantEmbalaje='$cantEmbalaje', unidadEmbalaje='$unidadEmbalaje', presentacion='$presentacion' WHERE id='$iden' ";
//echo $sql;

$res = mysqli_query($link,$sql) or die(errorCarga('Problemas al Editar el Producto, notifica a tu Administrador'));
/*echo 'id: '.$iden.'<br>';*/
$error = '';

$arrayprecios=json_decode($precios);
for ($i=0; $i <= count($arrayprecios)-1 ; $i++) { 

 $precio=trim($arrayprecios[$i][0]);
  $cantLibera=trim($arrayprecios[$i][1]);
 $sql="INSERT INTO preciosbase(idProducto,precio,cantLibera, fechaReg,userReg) VALUES('$iden','$precio','$cantLibera',NOW(),'$userReg')";
 $res = mysqli_query($link,$sql) or die(errorCarga('Problemas al Capturar el Precio, notifica a tu Administrador'));

}



if($_FILES['fotopro']['name'] != null){
$sql = "SELECT nombre FROM catmarcas WHERE id = '$marca'";
$result=mysqli_query($link,$sql) or die(errorCarga(' pero <br><b>No se pudo validar la imagen reintente subir la foto.</b>.'));
$marka = mysqli_fetch_array($result);
$mark = $marka['nombre'];
if(!empty( $_FILES['fotopro'])){
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

  $name = eliminar_simbolos($nombre);
  $fileName = 'Prod_ID'.$iden.'-'.$name.'_'.$fecha;
 $fileName = str_replace(" ", "_", $fileName).'.'.$extension;

  //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  $carpetaAlm = 'doctos/Productos/';
  $carpeta = '../doctos/Productos/';
  if($img!=''){
    unlink('../'.$img);
  }
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
}
}
if(!empty( $_FILES['ficha'])){

if ($_FILES["ficha"]["error"] > 0 ){
  //echo "Error: " . $_FILES['foto']['error'] . "<br>";
  $error = 'Pago se ha registrado pero  <br><b>No se pudo cargar el archivo del pago.</b>';

}else {
      //--------------------- Obteniendo extencion del Archivo -------------------

  $archivo = $_FILES['ficha']['name'];
  $valores = explode(".", $archivo);
  $extensiondocto = $valores[count($valores)-1];
 
  //echo "Tipo de archivo: ".$extensiondocto;
  $fileName = 'FichaTecnicaID-'.$iden.'-'.$fecha;
  $fileName = str_replace(" ", "_", $fileName).'.'.$extensiondocto;
  //------ Se valida que exista La Carpeta y si no se Crea-------------------------
  if($fichaTecnica!=''){
    unlink('../'.$fichaTecnica);

  }
 // rmdir('../'.$fichaTecnica);
$carpetaAlm = 'doctos/Productos/FichasTecnicas';
$carpeta = '../doctos/Productos/FichasTecnicas';
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}
$url = $carpeta.'/'.$fileName;
$urlReg= $carpetaAlm.'/'.$fileName;
//echo "URL: ".$url;



if(move_uploaded_file($_FILES['ficha']['tmp_name'], $url)){
$sql = "UPDATE productos SET fichaTecnica = '$urlReg' WHERE id = '$iden'";
mysqli_query($link,$sql) or die(errorBD('Error el Resguardar Ficha Tecnica'));

}
//echo '<br>'.$sql.'<br>';


}
}


















echo'1|El Producto <b>'.$nombre.'</b> se a editado Corrrectamente.';
//header('location: ../Corporativo/catalogoProductos.php');
//echo "Listo";


}else{
  errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
}
  
function errorCarga($error){
  echo '0|'.$error;
  exit(0);
}


function errorBD($msj)
{
  echo '0|'.$msj;
  exit(0);
}
function rrmdir($dir) { 
  if (is_dir($dir)) { 
      $objects = scandir($dir); 
      foreach ($objects as $object) { 
          if ($object != "." && $object != "..") { 
              if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
          } 
      }
      reset($objects); 
      rmdir($dir); 
  }
}

 ?>