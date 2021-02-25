<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$vista = (!empty($_POST['vista'])) ? $_POST['vista'] : 0 ;
$idUsuario = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$nombre = (isset($_POST['editNombre']) && $_POST['editNombre'] != '') ? trim($_POST['editNombre']) : '' ;
$ApPaterno = (isset($_POST['editApPat']) && $_POST['editApPat'] != '') ? trim($_POST['editApPat']) : '' ;
$ApMaterno = (isset($_POST['editApMat']) && $_POST['editApMat'] != '') ? trim($_POST['editApMat']) : '' ;
$direccion = (isset($_POST['editDireccion']) && $_POST['editDireccion'] != '') ? trim($_POST['editDireccion']) : '' ;
$usuario = (isset($_POST['editUsuario']) && $_POST['editUsuario'] != '') ? trim($_POST['editUsuario']) : '' ;
$pass = (isset($_POST['editContraseña']) && $_POST['editContraseña'] != '') ? trim($_POST['editContraseña']) : '' ;
$pass2 = (isset($_POST['editContraseña2']) && $_POST['editContraseña2'] != '') ? trim($_POST['editContraseña2']) : '' ;
$nivel = (!empty($_POST['editNivel'])) ? $_POST['editNivel'] : 0 ;
$sucursal = (!empty($_POST['editSucursal'])) ? $_POST['editSucursal'] : 0 ;
$userReg = $_SESSION['LZFident'];


/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$idUsuario: '.$idUsuario;
echo '<br>$nombre: '.$nombre;
echo '<br>$ApPaterno: '.$ApPaterno;
echo '<br>$ApMaterno: '.$ApMaterno;
echo '<br>$direccion: '.$direccion;
echo '<br>$usuario: '.$usuario;
echo '<br>$pass: '.$pass;
echo '<br>$pass2: '.$pass2;
echo '<br>$nivel: '.$nivel;
echo '<br>$sucursal: '.$sucursal;
echo '<br> ############################################# <br>';
echo '<br> Línea 15';
#exit(0);
#*/
if ($idUsuario < 1) {
  errorBD('No se reconoció al Usuario, actualiza e inténtalo de nuevo, si el problema persiste notifica a tu Administrador.');
}
#echo '<br> Línea 40';
$texto = '';
$cant = 0;
if ($nombre == '') {     $texto .= 'nombre, ';    $cant++;    }
if ($ApPaterno == '') {     $texto .= 'apellido paterno, ';    $cant++;    }
if ($direccion == '') {     $texto .= 'apellido materno, ';    $cant++;    }
if ($usuario == '') {     $texto .= 'usuario, ';    $cant++;    }
if ($nivel < 1) {     $texto .= 'nivel, ';    $cant++;    }
if ($sucursal < 1) {     $texto .= 'sucursal, ';    $cant++;    }
$text = trim($texto, ', ');
#echo '<br> Línea 45';
if ($cant > 0) {
  errorBD('Debe ingresar uno de los siguientes campos: '.$texto);
}
#echo '<br> Línea 54';
$sqlCon = "SELECT * FROM segusuarios WHERE usuario = '$usuario' AND id != '$idUsuario'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los usuarios, notifica a tu Administrador.',$vista));
$rows = mysqli_num_rows($resCon);

if ($rows > 0) {
  errorBD('Ya se encuentra registrado alguien con el nombre de usuario '.$usuario.', prueba con otro',$vista);
}
#echo '<br> Línea 62';
if ($pass != '' || $pass2 != '') {
  if ($pass != $pass2 ) {
    errorBD('Las contraseñas no coinciden, deben ser iguales',$vista);
  }
#  echo '<br> Línea 67';
  $sql = "UPDATE segusuarios SET nombre = '$nombre',appat = '$ApPaterno',apmat = '$ApMaterno',direccion = '$direccion',usuario = '$usuario',pass = MD5('$pass'),idNivel = '$nivel',idSucursal = '$sucursal',idUserReg = '$userReg', fechaReg = NOW() WHERE id = '$idUsuario'";
} else {
#  echo '<br> Línea 70';
  $sql = "UPDATE segusuarios SET nombre = '$nombre',appat = '$ApPaterno',apmat = '$ApMaterno',direccion = '$direccion',usuario = '$usuario',idNivel = '$nivel',idSucursal = '$sucursal',idUserReg = '$userReg', fechaReg = NOW() WHERE id = '$idUsuario'";
}
  $res = mysqli_query($link,$sql) or die(errorBD('Problemas al registrar al usuario, notifica a tu Administrador.',$vista));
#echo '<br>$sql: '.$sql;
#echo '<br> Línea 74';
#echo '<br> Aquí ya capturó y regresó a la vista de Usuario';
if ($vista == 2) {
$_SESSION['LZFmsjSuccessCatalogoUsuarios'] = 'El Usuario '.$nombre.' se ha editado correctamente';
header('location: ../Corporativo/catalogoUsuarios.php');
exit(0);
} else {
  $_SESSION['LZFmsjSuccessAdminUsuarios'] = 'El Usuario '.$nombre.' se ha editado correctamente';
  header('location: ../Administrador/usuarios.php');
  exit(0);
}

function errorBD($error,$vista){
#  echo '<br> Línea 57';
#  echo '<br> error: '.$error;
if ($vista == 2) {
  $_SESSION['LZFmsjCatalogoUsuarios'] = $error;
  header('location: ../Corporativo/catalogoUsuarios.php');
  exit(0);
} else {
  $_SESSION['LZFmsjAdminUsuarios'] = $error;
  header('location: ../Administrador/usuarios.php');
  exit(0);
}
}
 ?>
