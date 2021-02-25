<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$vista = (!empty($_POST['vista'])) ? $_POST['vista'] : 0 ;
$nombre = (isset($_POST['newNombre']) && $_POST['newNombre'] != '') ? trim($_POST['newNombre']) : '' ;
$ApPaterno = (isset($_POST['newApPat']) && $_POST['newApPat'] != '') ? trim($_POST['newApPat']) : '' ;
$ApMaterno = (isset($_POST['newApMat']) && $_POST['newApMat'] != '') ? trim($_POST['newApMat']) : '' ;
$direccion = (isset($_POST['newDireccion']) && $_POST['newDireccion'] != '') ? trim($_POST['newDireccion']) : '' ;
$usuario = (isset($_POST['newUsuario']) && $_POST['newUsuario'] != '') ? trim($_POST['newUsuario']) : '' ;
$pass = (isset($_POST['newContraseña']) && $_POST['newContraseña'] != '') ? trim($_POST['newContraseña']) : '' ;
$pass2 = (isset($_POST['newContraseña2']) && $_POST['newContraseña2'] != '') ? trim($_POST['newContraseña2']) : '' ;
$nivel = (!empty($_POST['newNivel'])) ? $_POST['newNivel'] : 0 ;
$sucursal = (!empty($_POST['newSucursal'])) ? $_POST['newSucursal'] : 0 ;
$userReg = $_SESSION['LZFident'];


/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$nombre: '.$nombre;
echo '<br>$ApPaterno: '.$ApPaterno;
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
$texto = '';
$cant = 0;
if ($nombre == '') {     $texto .= 'nombre, ';    $cant++;    }
if ($ApPaterno == '') {     $texto .= 'apellido paterno, ';    $cant++;    }
if ($direccion == '') {     $texto .= 'apellido materno, ';    $cant++;    }
if ($usuario == '') {     $texto .= 'usuario, ';    $cant++;    }
if ($pass == '') {     $texto .= 'contraseña, ';    $cant++;    }
if ($pass2 == '') {     $texto .= 'reescribir contraseña, ';    $cant++;    }
if ($nivel < 1) {     $texto .= 'nivel, ';    $cant++;    }
if ($sucursal < 1) {     $texto .= 'sucursal, ';    $cant++;    }
$text = trim($texto, ', ');
#echo '<br> Línea 45';
if ($cant > 0) {
  errorBD('Debe ingresar uno de los siguientes campos: '.$texto,$vista);
}
#echo '<br> Línea 49';
if ($pass != $pass2) {
  errorBD('Las contraseñas no coinciden, deben ser iguales');
}
#echo '<br> Línea 53';
$sqlCon = "SELECT * FROM segusuarios WHERE usuario = '$usuario'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los usuarios, notifica a tu Administrador.',$vista));
$rows = mysqli_num_rows($resCon);

if ($rows > 0) {
  errorBD('Ya se encuentra registrado alguien con el nombre de usuario '.$usuario.' prueba con otro',$vista);
}
#echo '<br> Línea 61';
$sql = "INSERT INTO segusuarios(nombre,appat,apmat,direccion,usuario,pass,idNivel,idSucursal,estatus,idUserReg,fechaReg) VALUES('$nombre','$ApPaterno','$ApMaterno','$direccion','$usuario',MD5('$pass'),'$nivel','$sucursal','1','$userReg',NOW())";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al registrar al usuario, notifica a tu Administrador.',$vista));

#echo '<br> Línea 65';
#echo '<br> Aquí ya capturó y regresó a la vista de Usuario';
if ($vista == 2) {
$_SESSION['LZFmsjSuccessCatalogoUsuarios'] = 'El Usuario '.$nombre.' se ha registrado correctamente';
header('location: ../Corporativo/catalogoUsuarios.php');
exit(0);
} else {
  $_SESSION['LZFmsjSuccessAdminUsuarios'] = 'El Usuario '.$nombre.' se ha registrado correctamente';
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
