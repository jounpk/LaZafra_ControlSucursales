<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$btonD = (isset($_POST['switchDatosFisc']) && $_POST['switchDatosFisc'] != '') ? $_POST['switchDatosFisc'] : '' ;
$regresar = (!empty($_POST['variable'])) ? $_POST['variable'] : 1 ;
$nombre = (isset($_POST['newNombre']) && $_POST['newNombre'] != '') ? trim($_POST['newNombre']) : '' ;
$apodo = (isset($_POST['newApodo']) && $_POST['newApodo'] != '') ? trim($_POST['newApodo']) : '' ;
$estado = (!empty($_POST['newEstado'])) ? $_POST['newEstado'] : 0 ;
$municipio = (!empty($_POST['newMpio'])) ? $_POST['newMpio'] : 0 ;
$direccion = (isset($_POST['newDireccion']) && $_POST['newDireccion'] != '') ? trim($_POST['newDireccion']) : '' ;
$tel = (isset($_POST['newTelefono']) && $_POST['newTelefono'] != '') ? $_POST['newTelefono'] : '' ;
$credito = (!empty($_POST['newCredito'])) ? $_POST['newCredito'] : 2 ;
$limiteCredito = (!empty($_POST['newLimite'])) ? str_replace(',','',$_POST['newLimite']) : 0 ;
################################### datos fiscales ##############################
$rfc = (isset($_POST['newRFC']) && $_POST['newRFC'] != '') ? $_POST['newRFC'] : '' ;
$razonSoc = (isset($_POST['newRazonSoc']) && $_POST['newRazonSoc'] != '') ? trim($_POST['newRazonSoc']) : '' ;
$correo = (isset($_POST['newCorreo']) && $_POST['newCorreo'] != '') ? trim($_POST['newCorreo']) : '' ;
$correo2 = (isset($_POST['newCorreo2']) && $_POST['newCorreo2'] != '') ? trim($_POST['newCorreo2']) : '' ;
$dirFiscal = (isset($_POST['newDireccionFisc']) && $_POST['newDireccionFisc'] != '') ? trim($_POST['newDireccionFisc']) : '' ;
$codPost = (isset($_POST['newCP']) && $_POST['newCP'] != '') ? $_POST['newCP'] : '' ;
$noExt = (isset($_POST['newNoExterior']) && $_POST['newNoExterior'] != '') ? $_POST['newNoExterior'] : '' ;
$noInt = (isset($_POST['newNoInterior']) && $_POST['newNoInterior'] != '') ? $_POST['newNoInterior'] : '' ;
$colonia = (isset($_POST['newColonia']) && $_POST['newColonia'] != '') ? trim($_POST['newColonia']) : '' ;
$edoFisc = (!empty($_POST['newEstadoFisc'])) ? $_POST['newEstadoFisc'] : 0 ;
$mpioFisc = (!empty($_POST['newMpioFisc'])) ? $_POST['newMpioFisc'] : 0 ;
$cfdi = (isset($_POST['newUsoCfdi']) && $_POST['newUsoCfdi'] != '') ? $_POST['newUsoCfdi'] : '' ;


$userReg = $_SESSION['LZFident'];
$idSuc = $_SESSION['LZFidSuc'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$nombre: '.$nombre;
echo '<br>$apodo: '.$apodo;
echo '<br>$estado: '.$estado;
echo '<br>$municipio: '.$municipio;
echo '<br>$direccion: '.$direccion;
echo '<br>$tel: '.$tel;
echo '<br>$credito: '.$credito;
echo '<br>$limiteCredito: '.$limiteCredito;

echo '<br>$btonD: '.$btonD;
echo '<br>$rfc: '.$rfc;
echo '<br>$razonSoc: '.$razonSoc;
echo '<br>$correo: '.$correo;
echo '<br>$correo2: '.$correo2;
echo '<br>$dirFiscal: '.$dirFiscal;
echo '<br>$codPost: '.$codPost;
echo '<br>$noExt: '.$noExt;
echo '<br>$noInt: '.$noInt;
echo '<br>$colonia: '.$colonia;
echo '<br>$edoFisc: '.$edoFisc;
echo '<br>$mpioFisc: '.$mpioFisc;
echo '<br>$cfdi: '.$cfdi;
echo '<br> ############################################# <br>';

exit(0);
#*/
$texto = '';
$cant = 0;
if ($nombre == '') {    $texto .= ' un nombre,';       $cant++;  }
if ($apodo == '') {    $texto .= ' un apodo,';       $cant++;  }
if ($estado < 1) {    $texto .= ' un estado,';       $cant++;  }
if ($municipio < 1) {    $texto .= ' un municipio,';       $cant++;  }
if ($direccion == '') {    $texto .= ' una direccion,';       $cant++;  }

$texto = trim($texto,',');
if ($cant > 0) {
  errorBD('Debes de ingresar o seleccionar información en lo(s) siguiente(s) campo(s): '.$texto,$regresar);
}

$sqlCon = "SELECT * FROM clientes WHERE nombre = '$nombre' AND rfc = '$rfc'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Proveedores, notifica a tu Administrador',$regresar));
$cant = mysqli_num_rows($resCon);
#echo '<br> Línea 41';
if ($cant > 0) {
  errorBD('Ya se encuentra un Cliente con ese nombre y RFC, revisa en el listado, en caso contrario, notifica a tu Administrador',$regresar);
#  echo '<br> Línea 44';
}

if ($btonD == 'on' && !is_valid_email($correo)) {
  $_SESSION['LZFmsjCatalogoClientes'] = 'El correo no es válido, ingresa un correo válido para el Cliente';
#  echo '<br>Correo no válido';
#  echo '<br> Línea 52';
}

if ($btonD == 'on' && $correo2 != '' && !is_valid_email($correo2)) {
  $_SESSION['LZFmsjCatalogoClientes'] = 'El correo no es válido, ingresa un correo válido para el Cliente';
#  echo '<br>Correo no válido';
#  echo '<br> Línea 52';
}

$sql = "INSERT INTO clientes(nombre,apodo,estado,municipio,direccion,telefono,rfc,correo,estatus,idSucursal,idUserReg,fechaReg,credito,limiteCredito)
                      VALUES('$nombre','$apodo','$estado','$municipio','$direccion','$tel','$rfc','$correo','1','$idSuc','$userReg',NOW(),'$credito','$limiteCredito')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al capturar al Cliente, notifica a tu Administrador',$regresar));
$newID = mysqli_insert_id($link);

#echo '<br> -------------------- se evalua los datos fiscales, si no se cumple se captura el cliente pero no sus datos. ----------------------- <br>';
if ($btonD == 'on') {

$texto2 = '';
$cant2 = 0;
if ($rfc == '') {    $texto2 .= ' un rfc,';       $cant2++;  }
if ($razonSoc == '') {    $texto2 .= ' una razón social,';       $cant2++;  }
if ($correo == '') {    $texto2 .= ' un correo,';       $cant2++;  }
if ($dirFiscal == '') {    $texto2 .= ' una direccion fiscal,';       $cant2++;  }
if ($codPost == '') {    $texto2 .= ' un código postal,';       $cant2++;  }
if ($noExt == '') {    $texto2 .= ' un número exterior,';       $cant2++;  }
if ($colonia == '') {    $texto2 .= ' una colonia,';       $cant2++;  }
if ($edoFisc == '') {    $texto2 .= ' un estado,';       $cant2++;  }
if ($mpioFisc == '') {    $texto2 .= ' un municipio,';       $cant2++;  }
if ($cfdi == '') {    $texto2 .= ' un uso de CFDI,';       $cant2++;  }

#################################################################################################################
##############################################    aqui me quedé    ##############################################
#################################################################################################################
$texto2 = trim($texto2,',');
if ($cant2 > 0) {
errorDatosFisc('pero no se capturó los Datos Fiscales del Cliente debido a que faltaron los siguientes datos: '.$texto2,$regresar);
} else {
  $sqlConEmp = "SELECT idEmpresa FROM sucursales WHERE id = '$idSuc'";
  $resConEmp = mysqli_query($link,$sqlConEmp) or die(errorDatosFisc('pero no se capturó los Datos Fiscales del Cliente porque no se reconoció la empresa, inténtalo nuevamente, si persiste notifica a tu Administrador.',$regresar));
  $emp = mysqli_fetch_array($resConEmp);
  $idEmpresa = $emp['idEmpresa'];

 /* $sql = "INSERT INTO datosfisc(rfc,razonSocial,usoCfdi,calle,noExt,noInt,colonia,codigoPostal,municipio,estado,correo,correo2,correo3,
    verificaDatos,nombre,apellidos,portalCarga,idUserReg,fechaReg,tel,idEmpresa,estatus,idCliente)
          VALUES('$rfc','$razonSoc','$cfdi','$dirFiscal','$noExt','$noInt','$colonia','$codPost','$mpioFisc','$edoFisc','$correo','$correo2',null,
      '0','$nombre',null,'1','$userReg',NOW(),null,'$idEmpresa','1','$newID')";
  $res = mysqli_query($link,$sql) or die(errorDatosFisc(', pero no se capturó los Datos Fiscales del Cliente, inténtalo nuevamente, si persiste notifica a tu Administrador.',$regresar));*/




//----------------------------------Conexion a API FACTURACION ----------------------//
require("CFDIS/CFDI_FACTURACION_FACTCOM.PHP");
$datosClientes=array(
  "rfc"=>$rfc,
  "razonSocial"=>$razonSoc,
  "nombre"=>$nombre,
  "apellido"=>'',
  "calle"=>$dirFiscal,
  "noInt"=>$noInt,
  "noExt"=>$noExt,
  "colonia"=>$colonia,
  "municipio"=>$mpioFisc,
  "estado"=>$edoFisc,
  "codpostal"=>$codPost,
  "correo"=>$correo,
  "telefono"=>NULL,
  "correo2"=>$correo2,
  "correo3"=>NULL,
  "usoCFDI"=>$cfdi,
  "idCliente"=>$newID,
  "operacion"=>'1');
  $resp=registraCliente($datosClientes, $link,'1', $idEmpresa);
  if($resp['estatus']=='1'){
    
    if ($regresar == 1) {
      $_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente '.$nombre.' se ha registrado correctamente';
      header('location: ../Corporativo/catalogoClientes.php');
    } else {
      $_SESSION['LZFmsjSuccessConfiguraClientes'] = 'El Cliente '.$nombre.' se ha registrado correctamente';
      header('location: ../Corporativo/configuraCliente.php');
    }
    
  }else{


    errorDatosFisc('No se pudo validar los Datos Fiscales, Intentalo de Nuevo.',$regresar);

  }
}

}  


if ($regresar == 1) {
  $_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente '.$nombre.' se ha registrado correctamente';
  header('location: ../Corporativo/catalogoClientes.php');
} else {
  $_SESSION['LZFmsjSuccessConfiguraClientes'] = 'El Cliente '.$nombre.' se ha registrado correctamente';
  header('location: ../Corporativo/configuraCliente.php');
}
exit(0);

function errorDatosFisc($error,$regresar){
  if ($regresar == 1) {
    $_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente '.$nombre.' se ha registrado correctamente'.$error;
    //header('location: ../Corporativo/catalogoClientes.php');
  } else {
    $_SESSION['LZFmsjSuccessConfiguraClientes'] = 'El Cliente '.$nombre.' se ha registrado correctamente'.$error;
    //header('location: ../Corporativo/configuraCliente.php');
  }
}

function errorBD($error,$regresar){
  #  echo '<br> Línea 18';
  #  echo '<br> error';
  if ($regresar == 1) {
    $_SESSION['LZFmsjCatalogoClientes'] = $error;
    //header('location: ../Corporativo/catalogoClientes.php');
  } else {
    $_SESSION['LZFmsjConfiguraClientes'] = $error;
   // header('location: ../Corporativo/configuraCliente.php');
  }
  exit(0);
}

function is_valid_email($str){
  $matches = null;
  return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}
 ?>
