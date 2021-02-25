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

$idSucursal = $_SESSION['LZFidSuc'];
$fecha2 = date('Y-m-d');

######### 1 es impresión de venta, 2 es para la reimpresión del Ticket #########
$idCliente = (isset($_REQUEST['idCliente']) && $_REQUEST['idCliente'] != '') ? $_REQUEST['idCliente'] : 0;
$fecha = (isset($_REQUEST['fecha']) && $_REQUEST['fecha'] != '') ? $_REQUEST['fecha'] : $fecha2;
$idSuc = (isset($_REQUEST['idSuc']) && $_REQUEST['idSuc'] = '') ? $_REQUEST['idSuc'] : $idSucursal;
$idPagos = (isset($_REQUEST['idPagos']) && $_REQUEST['idPagos'] != '') ? $_REQUEST['idPagos'] : '';

if (!ctype_digit($idCliente)) {
  $idCliente = 0;
}
/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados:<br>';
print_r($_POST);
echo '<br>$idCliente: '.$idCliente;
echo '<br>';
echo "<br>###########################################################<br>";
#exit(0);
#*/
$ticket = '';
if ($idCliente == 0) {
  header('location: ../creditos.php');
  exit(0);
}
########################## se consltan los datos de la Compra ##########################
#echo '<br>'.$idVenta;
$sql = "SELECT s.nombre AS nSuc, s.direccion, s.idEmpresa,e.logo
FROM sucursales s
INNER JOIN empresas e ON s.idEmpresa = e.id
WHERE s.id = '$idSuc'";
$result = mysqli_query($link, $sql) or die('Problemas al Consultar la Compra.');
$var = mysqli_fetch_array($result);

error_reporting(0);
$cancel = ($var['estatus'] == 3) ? 1 : 0;
$_SESSION['logoEncabezado'] = '../' . $var['logo'];
$_SESSION['nSuc'] = $var['nSuc'];
$_SESSION['dirSuc'] = $var['direccion'];
$_SESSION['idEmpresa'] = $var['idEmpresa'];


$sqlPago = "SELECT id,nombre FROM clientes WHERE id = '$idCliente' LIMIT 1";
$resPago = mysqli_query($link, $sqlPago) or die('Problemas al consultar el pago, notifica a tu Administrador.');
$pg = mysqli_fetch_array($resPago);

$nombreCliente = $pg['nombre'];

/*
echo "<br>###########################################################<br>";
echo '<br>$_SESSION[\'logoEncabezado\']: '.$_SESSION['logoEncabezado'];
echo '<br>$_SESSION[\'nSuc\']: '.$_SESSION['nSuc'];
echo '<br>$_SESSION[\'dirSuc\']: '.$_SESSION['dirSuc'];
echo '<br>$_SESSION[\'idEmpresa\']: '.$_SESSION['idEmpresa'];
echo '<br>';
echo "<br>###########################################################<br>";
exit(0);
#*/
$pdf = new newPDF('P', 'mm', array(80, 297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
$pdf->SetMargins(5, 2, 5);
$pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
$pdf->AddPage();

########################## comienza la cabecera ##########################
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, utf8_decode('***** PAGO DE CREDITOS *****'), 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(18, 4, utf8_decode('Cliente: '), 0, 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(48, 4, utf8_decode($nombreCliente), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Ln(4);
$pdf->Cell(22, 5, utf8_decode('Fecha de Pago: '), 0, 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 5, utf8_decode($fecha), 0, 1, 'L', 0);

$pdf->SetFont('Arial', 'B', 9);
/*
$pdf->Cell(17,5,utf8_decode('VENTA'),'B,T',0,'L');
$pdf->Cell(19,5,utf8_decode('ADEUDO'),'B,T',0,'R');
$pdf->Cell(17,5,utf8_decode('PAGO'),'B,T',0,'R');
$pdf->Cell(17,5,utf8_decode('RESTA'),'B,T',1,'R');
*/
$pdf->Cell(0, 5, utf8_decode('DETALLADO DE PAGOS DEL DIA'), 'B,T', 1, 'C');
########################## finaliza la cabecera ##########################
########################## se imprimen los productos, al igual que sus devoluciones si es que existen ##########################
$pdf->SetWidths(array(24, 22, 24));          //Permite colocar el ancho de las columnas, el alto es automatico
$pdf->SetAligns(array('R', 'R', 'R'));    //Permite centrar las columnas del array
$pdf->SetFont('Arial', '', 8);

if ($idPagos != '') {
  $sql = "SELECT pc.*, c.idVenta, DATE_FORMAT(v.fechaReg,'%Y-%m-%d') AS fechaVenta, CONCAT(u.nombre,' ',u.appat,' ',apmat) AS cajero, sf.nombre AS nomFormaPago
            FROM pagoscreditos pc
            INNER JOIN creditos c ON pc.idCredito = c.id
            INNER JOIN ventas v ON c.idVenta = v.id
            INNER JOIN segusuarios u ON pc.idUserReg = u.id
            INNER JOIN sat_formapago sf ON pc.idFormaPago = sf.id
            WHERE DATE_FORMAT(pc.fechaReg, '%Y-%m-%d') = '$fecha' AND c.idCliente = '$idCliente' AND pc.id IN($idPagos)";
} else {
  $sql = "SELECT pc.*, c.idVenta, DATE_FORMAT(v.fechaReg,'%Y-%m-%d') AS fechaVenta, CONCAT(u.nombre,' ',u.appat,' ',apmat) AS cajero, sf.nombre AS nomFormaPago
            FROM pagoscreditos pc
            INNER JOIN creditos c ON pc.idCredito = c.id
            INNER JOIN ventas v ON c.idVenta = v.id
            INNER JOIN segusuarios u ON pc.idUserReg = u.id
            INNER JOIN sat_formapago sf ON pc.idFormaPago = sf.id
            WHERE DATE_FORMAT(pc.fechaReg, '%Y-%m-%d') = '$fecha' AND c.idCliente = '$idCliente'";
}
$result = mysqli_query($link, $sql) or die('Problemas al Consultar el Detallado de Compras.');
$subTotal = $count = $nPag = $compara = 0;
$cajero = $formaPago = $formaPagoAnt = $mensaje = '';
while ($row = mysqli_fetch_array($result)) {
  $formaPago = $row['nomFormaPago'];
  $cajero = $row['cajero'];
  $pdf->SetFont('Arial', '', 8);
  $pdf->Ln(2);
  $pdf->Cell(10, 5, utf8_decode('Venta: '), 0, 0, 'L');
  $pdf->SetFont('Arial', 'B', 9);
  $pdf->Cell(15, 5, utf8_decode($row['idVenta']), 0, 0, 'L');
  $pdf->SetFont('Arial', '', 8);
  $pdf->Cell(20, 5, utf8_decode('Fecha de Venta: '), 0, 0, 'R');
  $pdf->SetFont('Arial', 'B', 9);
  $pdf->Cell(0, 5, utf8_decode($row['fechaVenta']), 0, 1, 'L');
  $pdf->Ln(2);
  $pdf->SetFont('Arial', 'B', 9);
  $pdf->Cell(24, 5, utf8_decode('ADEUDO'), 'B,T', 0, 'R');
  $pdf->Cell(22, 5, utf8_decode('PAGO'), 'B,T', 0, 'R');
  $pdf->Cell(24, 5, utf8_decode('RESTA'), 'B,T', 1, 'R');
  $pdf->SetFont('Arial', '', 8);
  $pdf->Row2(array(utf8_decode('$' . number_format($row['adeudo'], 2, '.', ',')), '$ ' . number_format($row['monto'], 2, '.', ','), '$ ' . number_format($row['residual'], 2, ".", ",")));
  $pdf->Cell(0, 5, utf8_decode(''), 'B', 1, 'L');
  $pdf->Cell(0, 5, utf8_decode(''), 0, 1, 'L');
  $pdf->Ln(2);
  $subTotal += $row['monto'];

  if ($count > 0 && $formaPagoAnt != $formaPago) {
    $compara++;
  }

  $count++;
  $formaPagoAnt = $formaPago;

  if ($count == 18 && $nPag < 1) {
    $nPag++;
    $pdf->AddPage();
    $pdf->Ln(5);
    $count = 0;
  } elseif ($count == 24 && $nPag > 0) {
    $nPag++;
    $pdf->AddPage();
    $pdf->Ln(5);
    $count = 0;
  } else {
    $pdf->Ln(2);
  }
}

if ($compara > 0) {
  $mensaje = 'MULTIPLES FORMAS DE PAGO';
} else {
  $mensaje = $formaPago;
}

/*
    if (abs($vTotal) != abs($vSubTotal) && $var['estatus']==2) {
$ticket .= '<script type="text/javascript">
    alert("Hubo un inconveniente menor, por favor notifica a tu Administrador");
    </script>';
    }
    ###################
    echo  '<tr>
              <td>Cantidades</td>
              <td>$'.number_format($subTotal,2,'.',',').'</td>
              <td>divisor</td>
              <td>$'.number_format($total,2,'.',',').'</td>
           </tr>';
           */
########################## se muestran los totales ##########################
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(40, 5, utf8_decode('PAGO TOTAL= '), 0, 0, 'R');
$pdf->Cell(28, 5, utf8_decode('$ ' . number_format($subTotal, 2, '.', ',')), 0, 1, 'R');
$pdf->Ln(2);
$pdf->Ln(2);
########################## se imprime el pie de página ##########################
$pdf->Cell(0, 5, utf8_decode($mensaje), 0, 1, 'C');
########################## Se imprime el pie de ticket ##########################
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 4, utf8_decode('Recibido por:'), 0, 'C');
$pdf->MultiCell(0, 4, utf8_decode($cajero), 0, 'C');
$pdf->Ln(2);


########################## Se imprime el pie de ticket ##########################
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 4, utf8_decode('*** GRACIAS POR SU PAGO ***'), 0, 'C');
$pdf->MultiCell(0, 4, utf8_decode('*** QUE TENGA UN EXCELENTE DIA ***'), 0, 'C');
$pdf->Ln(2);

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
