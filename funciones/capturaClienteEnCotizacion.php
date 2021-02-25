<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$clienteNoRegistrado=(isset($_POST['clienteNoRegistrado']) AND $_POST['clienteNoRegistrado'] != '') ? $_POST['clienteNoRegistrado'] : '' ;
$clienteRegistrado=(isset($_POST['clienteRegistrado']) AND $_POST['clienteRegistrado'] != '') ? $_POST['clienteRegistrado'] : '' ;

$idUser = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];



/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados por $_POST:';
print_r($_POST);
echo '<br>$cliente: '.$cliente;
echo '<br>';
echo "<br>###########################################################<br>";
#exit(0);
#*/
#echo '<br>------------------------ Se consulta la sucursal para obtener su código de facturacion ------------------------<br>';
  $sqlSuc = "SELECT serieFact FROM sucursales WHERE id = '$idSucursal' AND estatus = '1'";
#echo '<br>$sqlSuc: '.$sqlSuc.'<br>';
  $resSuc = mysqli_query($link,$sqlSuc) or die(errorBD('Problemas al consultar las Sucursales, notifica a tu Administrador.'));
  $dt = mysqli_fetch_array($resSuc);
  $serieSuc = $dt['serieFact'];
#echo '<br>$serieSuc: '.$serieSuc;

#echo '<br>------------------------ Se consultan las cotizaciones para obtener la serie máxima de la sucursal ------------------------<br>';
  $sqlCon="SELECT IF(MAX(incSerie) > 0,MAX(incSerie),0) AS Maximo FROM cotizaciones WHERE idSucursal = '$idSucursal' AND idUserReg = '$idUser'";
#echo '<br>$sqlCon: '.$sqlCon.'<br>';
  $resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las cotizaciones, notifica a tu Administrador.'));
  $dat = mysqli_fetch_array($resCon);
  $numero = $dat['Maximo'];
#echo '<br>$numero: '.$numero;
  $numero++;
#echo '<br>$numero incrementado: '.$numero;
  $serie = str_pad($numero, 6, '0', STR_PAD_LEFT);
#echo '<br>$serie: '.$serie;
  $folio = 'COT-'.$serieSuc.'-'.$serie;
#echo '<br>$folio: '.$folio;

#echo '<br>------------------------ Se realiza la captura de la cotización ------------------------<br>';
if($clienteRegistrado!=''){
   $cliente=$clienteRegistrado;
   $sqlIn = "INSERT INTO cotizaciones(folio,incSerie,idSucursal,idCliente,idUserReg,fechaReg,montoTotal,estatus) VALUES('$folio','$numero','$idSucursal','$cliente','$idUser',NOW(),0,'1')";
} else if ($clienteNoRegistrado!=''){
  $cliente=$clienteNoRegistrado;
  $sqlIn = "INSERT INTO cotizaciones(folio,incSerie,idSucursal,cliente,idUserReg,fechaReg,montoTotal,estatus) VALUES('$folio','$numero','$idSucursal','$cliente','$idUser',NOW(),0,'1')";
}
#echo '<br>$sqlIn: '.$sqlIn.'<br>';
$resIn = mysqli_query($link,$sqlIn) or die(errorBD('Problemas al capturar la cotización, notifica a tu Administrador.'));

$idCot = mysqli_insert_id($link);

$sqlConMail = "SELECT DISTINCT(a.mail) AS mail,a.nomCliente
FROM cotizaciones c
LEFT JOIN (
SELECT nombre AS nomCliente,correo AS mail FROM clientes WHERE estatus = 1
UNION
SELECT c.cliente AS nomCliente, dc.correo AS mail FROM detcotcorreos dc INNER JOIN cotizaciones c ON dc.idCotizacion = c.id
UNION
SELECT ct.cliente AS nomCliente, dct.correo AS mail FROM detcotcorreos dct INNER JOIN cotizaciones ct ON dct.idCotizacion = ct.id
) a ON a.nomCliente = c.cliente
WHERE a.nomCliente LIKE '%$cliente%'";
#echo '<br><br>$sqlConMail:<br>'.$sqlConMail;
$resConMail = mysqli_query($link,$sqlConMail) or die(errorBD('Problemas al consultar los correos del cliente, notifica a tu Administrador'));
$cant = mysqli_num_rows($resConMail);

if ($cant > 0) {
$sqlMail = "INSERT INTO detcotcorreos(correo,idCotizacion) SELECT DISTINCT(a.mail) AS mail,'$idCot'
FROM cotizaciones c
LEFT JOIN (
SELECT nombre AS nomCliente,correo AS mail FROM clientes WHERE estatus = 1
UNION
SELECT c.cliente AS nomCliente, dc.correo AS mail FROM detcotcorreos dc INNER JOIN cotizaciones c ON dc.idCotizacion = c.id
UNION
SELECT ct.cliente AS nomCliente, dct.correo AS mail FROM detcotcorreos dct INNER JOIN cotizaciones ct ON dct.idCotizacion = ct.id
) a ON a.nomCliente = c.cliente
WHERE a.nomCliente LIKE '%$cliente%'";
#echo '<br><br>$sqlMail:<br>'.$sqlMail;
$resMail = mysqli_query($link,$sqlMail) or die(errorBD('Problemas al capturar los correos en la cotización, notifica a tu Administrador.'));
}

#exit(0);
$_SESSION['LZFmsjSuccessAdminCotizaciones'] = 'Se ha iniciado el proceso de cotización';
header('location: ../Administrador/cotizaciones.php');


  function errorBD($msj){
#echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZFmsjAdminCotizaciones'] = $msj;
    header('location: ../Administrador/cotizaciones.php');
    exit(0);
  }
?>
