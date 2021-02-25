<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0;
$nombre = (isset($_POST['eNombre']) && $_POST['eNombre'] != '') ? trim($_POST['eNombre']) : '';
$apodo = (isset($_POST['eApodo']) && $_POST['eApodo'] != '') ? trim($_POST['eApodo']) : '';
$estado = (!empty($_POST['eEstado'])) ? $_POST['eEstado'] : 0;
$municipio = (!empty($_POST['eMpio'])) ? $_POST['eMpio'] : 0;
$direccion = (isset($_POST['eDireccion']) && $_POST['eDireccion'] != '') ? trim($_POST['eDireccion']) : '';
$tel = (isset($_POST['eTelefono']) && $_POST['eTelefono'] != '') ? $_POST['eTelefono'] : '';
$credito = (!empty($_POST['eCredito'])) ? $_POST['eCredito'] : 0;
$limiteCredito = (!empty($_POST['eLimite'])) ? $_POST['eLimite'] : 0;

################################### datos fiscales ##############################
$btonD = (isset($_POST['switchDatosFisc2']) && $_POST['switchDatosFisc2'] != '') ? $_POST['switchDatosFisc2'] : '';
$rfc = (isset($_POST['edRFC']) && $_POST['edRFC'] != '') ? $_POST['edRFC'] : '';
$razonSoc = (isset($_POST['edRazonSoc']) && $_POST['edRazonSoc'] != '') ? trim($_POST['edRazonSoc']) : '';
$correo = (isset($_POST['edCorreo']) && $_POST['edCorreo'] != '') ? trim($_POST['edCorreo']) : '';
$correo2 = (isset($_POST['edCorreo2']) && $_POST['edCorreo2'] != '') ? trim($_POST['edCorreo2']) : '';
$dirFiscal = (isset($_POST['edDireccionFisc']) && $_POST['edDireccionFisc'] != '') ? trim($_POST['edDireccionFisc']) : '';
$codPost = (isset($_POST['edCP']) && $_POST['edCP'] != '') ? $_POST['edCP'] : '';
$noExt = (isset($_POST['edNoExterior']) && $_POST['edNoExterior'] != '') ? $_POST['edNoExterior'] : '';
$noInt = (isset($_POST['edNoInterior']) && $_POST['edNoInterior'] != '') ? $_POST['edNoInterior'] : '';
$colonia = (isset($_POST['edColonia']) && $_POST['edColonia'] != '') ? trim($_POST['edColonia']) : '';
$edoFisc = (!empty($_POST['edEstadoFisc'])) ? $_POST['edEstadoFisc'] : 0;
$mpioFisc = (!empty($_POST['edMpioFisc'])) ? $_POST['edMpioFisc'] : 0;
$cfdi = (isset($_POST['edUsoCfdi']) && $_POST['edUsoCfdi'] != '') ? $_POST['edUsoCfdi'] : '';

$userReg = $_SESSION['LZFident'];
$idSuc = $_SESSION['LZFidSuc'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$ident: '.$ident;
echo '<br>$nombre: '.$nombre;
echo '<br>$apodo: '.$apodo;
echo '<br>$estado: '.$estado;
echo '<br>$municipio: '.$municipio;
echo '<br>$direccion: '.$direccion;
echo '<br>$rfc: '.$rfc;
echo '<br>$tel: '.$tel;
echo '<br>$correo: '.$correo;
echo '<br>$credito: '.$credito;
echo '<br>$limiteCredito: '.$limiteCredito;
echo '<br><br> ################################### datos fiscales ##############################<br>';
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
if ($nombre == '') {
  $texto .= ' un nombre,';
  $cant++;
}
if ($apodo == '') {
  $texto .= ' un apodo,';
  $cant++;
}
if ($estado < 1) {
  $texto .= ' un estado,';
  $cant++;
}
if ($municipio < 1) {
  $texto .= ' un municipio,';
  $cant++;
}
if ($direccion == '') {
  $texto .= ' una direccion,';
  $cant++;
}

$texto = trim($texto, ',');
if ($cant > 0) {
  errorBD('Debes de ingresar o seleccionar información en lo(s) siguiente(s) campo(s): ' . $texto);
}
$sqlCon = "SELECT * FROM clientes WHERE nombre = '$nombre' AND rfc = '$rfc' AND estatus < 3 AND id != '$ident'";
#echo '<br>$sqlCon'.$sqlCon;
$resCon = mysqli_query($link, $sqlCon) or die(errorBD('Problemas al consultar los Proveedores, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);
#echo '<br> Línea 41';
if ($cant > 0) {
  errorBD('Ya se encuentra un Cliente con ese nombre y RFC, revisa en el listado, en caso contrario, notifica a tu Administrador');
  #  echo '<br> Línea 44';
}

if ($btonD == 'on' && !is_valid_email($correo)) {
  $_SESSION['LZFmsjCatalogoClientes'] = 'El correo no es válido, ingresa un correo válido para el Cliente';
  #  echo '<br>Correo no válido';
  #  echo '<br> Línea 52';
}
if ($btonD == 'on' && $correo2 != '' && !is_valid_email($correo2)) {
  $_SESSION['LZFmsjAdminClientes'] = 'El correo no es válido, ingresa un correo válido para el Cliente';
  #  echo '<br>Correo no válido';
  #  echo '<br> Línea 52';
}

$sql = "UPDATE clientes SET nombre = '$nombre',apodo = '$apodo',estado = '$estado',municipio = '$municipio',direccion = '$direccion',telefono = '$tel',rfc = '$rfc',correo = '$correo',idUserReg = '$userReg',fechaReg = NOW(),credito = '$credito',limiteCredito ='$limiteCredito' WHERE id = '$ident'";
#echo '<br>$sql'.$sql;
$res = mysqli_query($link, $sql) or die(errorBD('Problemas al editar al Cliente, notifica a tu Administrador'));

#echo '<br> -------------------- se evalua los datos fiscales, si no se cumple se captura el cliente pero no sus datos. ----------------------- <br>';
if ($btonD == 'on') {

  $texto2 = '';
  $cant2 = 0;
  if ($rfc == '') {
    $texto2 .= ' un rfc,';
    $cant2++;
  }
  if ($razonSoc == '') {
    $texto2 .= ' una razón social,';
    $cant2++;
  }
  if ($correo == '') {
    $texto2 .= ' un correo,';
    $cant2++;
  }
  if ($dirFiscal == '') {
    $texto2 .= ' una direccion fiscal,';
    $cant2++;
  }
  if ($codPost == '') {
    $texto2 .= ' un código postal,';
    $cant2++;
  }
  if ($noExt == '') {
    $texto2 .= ' un número exterior,';
    $cant2++;
  }
  if ($colonia == '') {
    $texto2 .= ' una colonia,';
    $cant2++;
  }
  if ($edoFisc == '') {
    $texto2 .= ' un estado,';
    $cant2++;
  }
  if ($mpioFisc == '') {
    $texto2 .= ' un municipio,';
    $cant2++;
  }
  if ($cfdi == '') {
    $texto2 .= ' un uso de CFDI,';
    $cant2++;
  }

  $texto2 = trim($texto2, ',');
  #################################################################################################################
  #echo '<br><br> -------------------- se procede para cargar los datos fiscales. ----------------------- <br>';
  #################################################################################################################
  if ($cant2 > 0) {
    errorDatosFisc('pero no se capturó los Datos Fiscales del Cliente debido a que faltaron los siguientes datos: ' . $texto2);
  } else {
    $sqlConEmp = "SELECT idEmpresa FROM sucursales WHERE id = '$idSuc'";
    #echo '<br>$sqlConEmp'.$sqlConEmp;
    $resConEmp = mysqli_query($link, $sqlConEmp) or die(errorDatosFisc('pero no se capturó los Datos Fiscales del Cliente porque no se reconoció la empresa, inténtalo nuevamente, si persiste notifica a tu Administrador.'));
    $emp = mysqli_fetch_array($resConEmp);
    $idEmpresa = $emp['idEmpresa'];

    $sqlConDatosFisc = "SELECT * FROM datosfisc WHERE idCliente = '$ident' AND idEmpresa = '$idEmpresa'";
    #echo '<br>$sqlConDatosFisc'.$sqlConDatosFisc;
    $resConDatosFisc = mysqli_query($link, $sqlConDatosFisc) or die(errorDatosFisc('pero no se capturó los Datos Fiscales del Cliente porque hubo un error al consultar los datos fiscales, inténtalo nuevamente, si persiste notifica a tu Administrador.'));
    $cantRows = mysqli_num_rows($resConDatosFisc);

    if ($cantRows > 0) {
      #echo '<br> -------------------- Si hay un registro se actualiza. ----------------------- <br>';
      /* $sql = "UPDATE datosfisc SET rfc = '$rfc',razonSocial = '$razonSoc',usoCfdi = '$cfdi',calle = '$dirFiscal',noExt = '$noExt',noInt = '$noInt',colonia = '$colonia',codigoPostal = '$codPost',municipio = '$mpioFisc',estado = '$edoFisc',correo = '$correo',
    correo2 = '$correo2',nombre = '$nombre',idUserReg = '$userReg',fechaReg = NOW() WHERE idCliente = '$ident' AND idEmpresa = '$idEmpresa'";*/
      $array = mysqli_fetch_array($sqlConDatosFisc);
      $idDatosFisc = $array['id'];
      require("CFDIS/CFDI_FACTURACION_FACTCOM.PHP");
      $datosClientes = array(
        "rfc" => $rfc,
        "razonSocial" => $razonSoc,
        "nombre" => $nombre,
        "apellido" => '',
        "calle" => $dirFiscal,
        "noInt" => $noInt,
        "noExt" => $noExt,
        "colonia" => $colonia,
        "municipio" => $mpioFisc,
        "estado" => $edoFisc,
        "codpostal" => $codPost,
        "correo" => $correo,
        "telefono" => NULL,
        "correo2" => $correo2,
        "correo3" => NULL,
        "usoCFDI" => $cfdi,
        "idCliente" => $ident,
        "operacion" => '2'
      );
      $resp = registraCliente($datosClientes, $link, '1', $idEmpresa);
      PRINT_R($resp);
      if ($resp['estatus'] == '1') {

        if ($regresar == 1) {
          $_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente ' . $nombre . ' se ha editado correctamente';
          header('location: ../Corporativo/catalogoClientes.php');
        } else {
          $_SESSION['LZFmsjSuccessConfiguraClientes'] = 'El Cliente ' . $nombre . ' se ha editado correctamente';
          header('location: ../Corporativo/configuraCliente.php');
        }
      }
    } else {
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
  "idCliente"=>'',
  "operacion"=>'1');
  $resp=registraCliente($datosClientes, $link,'1', $idEmpresa);
  if($resp['estatus']=='1'){

    if ($regresar == 1) {
      $_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente ' . $nombre . ' se ha editado correctamente';
      header('location: ../Corporativo/catalogoClientes.php');
    } else {
      $_SESSION['LZFmsjSuccessConfiguraClientes'] = 'El Cliente ' . $nombre . ' se ha editado correctamente';
      header('location: ../Corporativo/configuraCliente.php');
    }
    }
    #  echo '<br>$sql'.$sql;
    $res = mysqli_query($link, $sql) or die(errorDatosFisc(', pero no se capturó los Datos Fiscales del Cliente, inténtalo nuevamente, si persiste notifica a tu Administrador.'));
  }
}




}


#echo '<br> -------------------- Manda de regreso. ----------------------- <br>';
$_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente ' . $nombre . ' se ha editado correctamente';
header('location: ../Corporativo/catalogoClientes.php');
exit(0);

function errorDatosFisc($error, $regresar)
{
  #echo '<br> -------------------- Entra en función de error. ----------------------- <br>';
  #echo '<br> errorDatosFisc';
  $_SESSION['LZFmsjSuccessCatalogoClientes'] = 'El Cliente ' . $nombre . ' se ha editado correctamente' . $error;
    header('location: ../Corporativo/catalogoClientes.php');

}

function errorBD($error)
{
  #echo '<br> -------------------- Entra en función de error. ----------------------- <br>';
  #echo '<br> errorBD';
    $_SESSION['LZFmsjCatalogoClientes'] = $error;
    header('location: ../Corporativo/catalogoClientes.php');
  exit(0);
}

function is_valid_email($str)
{
  $matches = null;
  return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}
