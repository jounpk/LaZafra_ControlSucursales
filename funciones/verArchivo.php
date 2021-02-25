<?php
session_start();
define('INCLUDE_CHECK',1);
require "../include/connect.php";

// =========================================   Se recibe la venta por post, tipo 1 (PDF) y tipo 2 (XML) =========================================================
$ident = (isset($_REQUEST['ident']) AND $_REQUEST['ident'] != '' ) ? $_REQUEST['ident'] : '';
$tipo = (isset($_REQUEST['tipo']) AND $_REQUEST['tipo'] >= 1 ) ? $_REQUEST['tipo'] : '';

// =========================================   Se consulta la venta en la BD =========================================================
$sql = "SELECT
emp.nameCto AS Empresa,
scs.nameFact AS Sucursal,
YEAR ( fc.fechaReg ) AS anio,
MONTH ( fc.fechaReg ) AS mes,
fc.uuid,
fc.doctoPDF,
fc.doctoXML,
dtpc.apiKey,
dtpc.secretKey,
fc.uid AS uidFisc,
dtpc.link 
FROM
facturas fc
INNER JOIN facturasgeneradas vtgn ON vtgn.uuidFact=fc.uuid
INNER JOIN vtasfact vtfc ON vtfc.idFactgen=vtgn.id
INNER JOIN ventas vnt ON vnt.id=vtfc.idVenta
INNER JOIN sucursales scs ON vnt.idSucursal = scs.id
INNER JOIN empresas emp ON scs.idEmpresa = emp.id
INNER JOIN datospacs dtpc ON emp.rfc = dtpc.rfc 
WHERE
fc.uuid =  '$ident'";
echo $sql;
$resp = mysqli_query($link,$sql) or die('Hubo un problema al consutlar los archivos, notifica a tu Administrador');
$fila = mysqli_fetch_array($resp);
#$cant = mysqli_num_rows($resp);


if ($tipo == 1) {
// =========================================   Se comprueba que exista el PDF si no existe lo descarga =========================================================
if ($fila['doctoPDF'] == '') {

  // =========================================   Descargar PDF y XML =========================================================
  $anioCoriente = $fila['anio'];
  $numMes = str_pad($fila['mes'] , 2, "0", STR_PAD_LEFT);   // rellena con 0 a la izquierda hasta tener 2 dígitos
  $empresa = $fila['Empresa'];
  $nameSuc = $fila['Sucursal'];
  $dominio = $fila['link'];
  $uid = $fila['uidFisc'];
  $UUID = $fila['uuid'];
  $apiKey = $fila['apiKey'];
  $secretKey = $fila['secretKey'];

  $carpeta = 'FacturasEmitidas/'.$empresa.'/'.$nameSuc.'/'.$anioCoriente.'/'.$numMes.'/';
  $carpetaOrig = '../FacturasEmitidas/'.$empresa.'/'.$nameSuc.'/'.$anioCoriente.'/'.$numMes.'/';
  if (!file_exists($carpetaOrig)) {
      mkdir($carpetaOrig, 0777, true);
  }
  $nameFile = $carpetaOrig.$UUID;
  $nameFileSave = $carpeta.$UUID;

  //   ------------------------------------   DESCARGA DE PDF   ---------------------------------
  $ch = curl_init();
  $ruta = $dominio.'/api/v3/cfdi33/'.$uid.'/pdf';
  curl_setopt($ch, CURLOPT_URL, "$ruta");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
     "Content-Type: application/json",
      "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
      "F-Api-Key: ". $apiKey,
      "F-Secret-Key: " . $secretKey
  ));
  $response = curl_exec($ch);
  curl_close($ch);
  file_put_contents( $nameFile.'.PDF', $response);

  //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
  $filePDF = (file_exists($nameFile.'.PDF')) ? $nameFileSave.'.PDF' : '' ;

  #                    echo '<br>** link de PDF: '.$filePDF.'<br>** link de XML: '.$fileXML;

  $sqlUpdateFiles = "UPDATE facturas SET doctoPDF = '$filePDF' WHERE uuid = '$UUID' ";
  #                    echo '<br>Consulta actualizar doctos: '.$sqlUpdateFiles;
  mysqli_query($link, $sqlUpdateFiles) or die('0|E8.- Tuvimos Problemas al actualizar los Archivos:'.mysqli_error($link).'<br> '.$sqlUpdateFiles);

} // Cierra el if de comprobación y descarga del archivo PDF
$direccionPDF = ($fila['doctoPDF'] != '') ? $fila['doctoPDF'] : $filePDF ;
header('Location: ../'.$direccionPDF);
} else {
if ($fila['doctoXML'] == '') {
  // =========================================   Descargar PDF y XML =========================================================
  $anioCoriente = $fila['anio'];
  $numMes = str_pad($fila['mes'] , 2, "0", STR_PAD_LEFT);   // rellena con 0 a la izquierda hasta tener 2 dígitos
  $empresa = $fila['Empresa'];
  $nameSuc = $fila['Sucursal'];
  $dominio = $fila['link'];
  $uid = $fila['uidFisc'];
  $UUID = $fila['uuid'];
  $apiKey = $fila['apiKey'];
  $secretKey = $fila['secretKey'];

  $carpeta = 'FacturasEmitidas/'.$empresa.'/'.$nameSuc.'/'.$anioCoriente.'/'.$numMes.'/';
  $carpetaOrig = '../FacturasEmitidas/'.$empresa.'/'.$nameSuc.'/'.$anioCoriente.'/'.$numMes.'/';
  if (!file_exists($carpetaOrig)) {
      mkdir($carpetaOrig, 0777, true);
  }
  $nameFile = $carpetaOrig.$UUID;
  $nameFileSave = $carpeta.$UUID;

  //   ------------------------------------   DESCARGA DE XML   ---------------------------------
  $ch = curl_init();
  $ruta = $dominio.'/api/v3/cfdi33/'.$uid.'/xml';
  curl_setopt($ch, CURLOPT_URL, "$ruta");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
      "F-Api-Key: ". $apiKey,
      "F-Secret-Key: " . $secretKey
  ));
  $response = curl_exec($ch);
  curl_close($ch);
  file_put_contents($nameFile.'.XML', $response);

  //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
  $fileXML = (file_exists($nameFile.'.XML')) ? $nameFileSave.'.XML' : '' ;

  #                    echo '<br>** link de PDF: '.$filePDF.'<br>** link de XML: '.$fileXML;

  $sqlUpdateFiles = "UPDATE facturas SET doctoXML = '$fileXML' WHERE uuid = '$UUID' ";
  #                    echo '<br>Consulta actualizar doctos: '.$sqlUpdateFiles;
  mysqli_query($link, $sqlUpdateFiles) or die('0|E8.- Tuvimos Problemas al actualizar los Archivos:'.mysqli_error($link).'<br> '.$sqlUpdateFiles);

} //cierra if de consulta y descarga del XML

$direccionXML = ($fila['doctoXML'] != '') ? $fila['doctoXML'] : $fileXML ;
header('Location: ../'.$direccionXML);
} // Cierra el else de tipo 2


 ?>
