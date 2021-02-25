<?php
#require_once '../seg.php';
#$info = new Seguridad();
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
require('../Fpdf/tickets.php');

#$cad = explode('/', $_SERVER["REQUEST_URI"]);
#$cantCad = COUNT($cad);
#$nameLk = $cad[$cantCad - 1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
#$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
$idUser = $_SESSION['LZFident'];

$idCotizacion = (!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0;
//$_SESSION['sucursalDir']="Carretera Axochiapan Izucar Col. Saragoza";

if (!ctype_digit($idCotizacion)) {
  $idCotizacion = 0;
}

$sql = "SELECT c.*, DATE_FORMAT(c.fechaReg, '%d-%m-%Y %H:%i:%s') AS fechaHora, mps.anchoLogo,mps.logo, scs.nombre AS nomSuc, scs.direccion, mps.rfc, 
IF( c.tipo = 2, CONCAT( c.nameCliente, ' (Público en General)' ), cl.nombre ) AS cliente,
      CONCAT(usu. nombre,' ',usu.appat,' ',usu.apmat) as Cajero, mps.id AS idEmp,
      IF(DATE_ADD(c.fechaAut,INTERVAL c.cantPeriodo DAY)>=NOW(), '0', '1') AS cancel
FROM cotizaciones c
LEFT JOIN clientes cl ON c.idCliente = cl.id
INNER JOIN sucursales scs ON c.idSucursal = scs.id
INNER JOIN empresas mps ON scs.idEmpresa = mps.id
INNER JOIN segusuarios usu ON c.idUserReg = usu.id
WHERE c.id = '$idCotizacion'";
//echo $sql;
$result = mysqli_query($link, $sql) or die('Problemas al Consultar el Detallado de cotizaciones.');
$var = mysqli_fetch_array($result);
$valEstatus = $var['estatus'];
$periodo = $var['cantPeriodo'];
if ($var['cliente'] != '') {
  $cliente = $var['cliente'];
} else if ($var['name_cliente'] != '') {
  $cliente = $var['name_cliente'];
}
$cancel=$var['cancel'];

$logo = '../' . $var['logo'];
$nomSuc = $var['nomSuc'];
$dirSuc = $var['direccion'];
$idEmp = $var['idEmp'];
$clienteOp = $cliente;
$rfc = $var['rfc'];
$cajero = $var['Cajero'];
#$total = $var['total'];
#echo 'Si entra';
#echo '<br>$idTraspaso'.$idTraspaso;
$_SESSION['logoEncabezado'] = $logo;
$_SESSION['nSuc'] = $nomSuc;
$_SESSION['dirSuc'] = $dirSuc;
$_SESSION['idEmpresa'] = $idEmp;


$pdf = new newPDF('P', 'mm', array(80, 297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)

$pdf->SetMargins(5, 2, 5);
$pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
$pdf->AddPage();
########################## comienza la cabecera ##########################
if ($cancel == 1) {
  $pdf->SetFont('Arial','B',30);
  $pdf->SetTextColor(255,192,203);
  $pdf->RotatedText(10,110,'C A N C E L A D O',45);
  $pdf->Ln(2);
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0, 5, utf8_decode('***** COTIZACIÓN *****'), 'T,B', 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 4, utf8_decode('Cliente: '), 0, 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(58, 4, utf8_decode($clienteOp), 0, 'L');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 4, utf8_decode('RFC: '), 0, 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(58, 4, utf8_decode($rfc), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Ln(2);
$pdf->Cell(10, 5, utf8_decode($var['fechaHora']), 0, 0, 'L', 0);
$pdf->Cell(0, 5, utf8_decode('Folio: ' . $var['folio']), 0, 1, 'R', 0);
########################## comienza encabezado ##########################
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(27, 5, utf8_decode('DESCRIPCION'), 'B,T', 0, 'L');
$pdf->Cell(15, 5, utf8_decode('PRECIO'), 'B,T', 0, 'L');
$pdf->Cell(10, 5, utf8_decode('CANT'), 'B,T', 0, 'L');
$pdf->Cell(18, 5, utf8_decode('SUBTOTAL'), 'B,T', 1, 'L');
########################## termina encabezado ##########################

########################## se imprimen los productos, al igual que sus devoluciones si es que existen ##########################
$pdf->SetWidths(array(27, 15, 10, 18));          //Permite colocar el ancho de las columnas, el alto es automatico
$pdf->SetAligns(array('L', 'R', 'R', 'R'));    //Permite centrar las columnas del array
$pdf->SetFont('Arial', '', 8);

$sql = "SELECT dc.*, prod.descripcion
      FROM detcotizaciones dc
      INNER JOIN productos prod ON dc.idProducto = prod.id
      WHERE dc.idCotizacion = '$idCotizacion'";
$result = mysqli_query($link, $sql) or die('Problemas al Consultar el Detallado de Ventas.');
$subTotal = $montoDev = $total = 0;
while ($row = mysqli_fetch_array($result)) {
  $pdf->Row2(array(utf8_decode($row['descripcion']), utf8_decode('$' . number_format($row['precioCoti'], 2, '.', ',')), number_format($row['cantidad'], 0, '.', ','), '$ ' . number_format($row['subtotal'], 2, ".", ",")));

  $subTotal += ($row['subtotal']);
}

$subTotal = $subTotal - $montoDev;
//$subTotal=$row['subtotal'];
$vTotal = number_format($total, 2, '.', ',');
$vSubTotal = number_format($subTotal, 2, '.', ',');

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(40, 5, utf8_decode('TOTAL= '), 'T', 0, 'R');
$pdf->Cell(28, 5, utf8_decode('$ ' . number_format($subTotal, 2, '.', ',')), 'T', 1, 'R');
$pdf->Ln(2);


########################## Se imprime el pie de ticket ##########################
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 4, utf8_decode('Generada por: '), 0, 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(50, 4, utf8_decode($cajero), 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 5, utf8_decode('*** GRACIAS POR SU COMPRA ***'), 0, 1, 'C');
$pdf->MultiCell(70, 4, utf8_decode('NOTA: Los precios tienen una validez de ' . $periodo . ' días desde su fecha de emisión.'), 0, 'C');

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
