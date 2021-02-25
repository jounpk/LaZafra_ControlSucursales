<?php
session_start();
define('INCLUDE_CHECK',1);
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


$fecha = (isset($_REQUEST['fechaCorte'])) ? $_REQUEST['fechaCorte'] : date('d-m-Y') ;
$idSuc1 = (isset($_REQUEST['idSucursal']) AND $_REQUEST['idSucursal'] >= 1) ? $_REQUEST['idSucursal'] : $_SESSION['LZFidSuc'] ;
/*
echo '<br>$_REQUEST:<br><br>';
print_r($_REQUEST);
echo '<br><br>';
#*/
//$fecha=date('d/m/Y');
$idUsuario=$_SESSION['LZFident'];
if ($idSuc1 > 0) {
	$idSucursal = $idSuc1;
} else {
	$idSucursal = $_SESSION['LZFidSuc'];
}
$ingresos=0;
$egresos=0;

$nameUsuario = $_SESSION['LZFnombreUser'] ;
#echo 'fecha: '.$fecha.'<br>';
#echo 'idSucursal: '.$idSucursal.'<br>';

$sql="SELECT c.id
FROM cortes c
WHERE c.idSucursal = '$idSucursal'
AND DATE_FORMAT(c.fechaCierre, '%d-%m-%Y') = '$fecha'";

//echo $sql;
$res=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Caja.');
$idsCortes='';
while ($res1=mysqli_fetch_array($res)) {
  $idsCortes=$idsCortes.$res1['id'].',';
}
$idsCortes=trim($idsCortes,',');

$sql="SELECT c.idEmpresa,e.logo,e.anchoLogo,e.rfc, a.efectivo, s.direccion, s.nombre AS nomSuc, a.cheque, a.transferencia, a.tarjetaD,a.tarjetaC,
a.boleta, a.creditos, SUM(c.totalDevoluciones) AS devuelto, SUM(totalGastos) AS gastos, SUM(totalVta) AS ventaTotal
FROM cortes c
INNER JOIN empresas e ON c.idEmpresa = e.id
INNER JOIN sucursales s ON c.idSucursal = s.id
INNER JOIN (
SELECT dc.idCorte,
SUM(CASE dc.idFormaPago WHEN '1' THEN dc.monto END) AS efectivo,
SUM(CASE dc.idFormaPago WHEN '2' THEN dc.monto END) AS cheque,
SUM(CASE dc.idFormaPago WHEN '3' THEN dc.monto END) AS transferencia,
SUM(CASE dc.idFormaPago WHEN '4' THEN dc.monto END) AS tarjetaD,
SUM(CASE dc.idFormaPago WHEN '5' THEN dc.monto END) AS tarjetaC,
SUM(CASE dc.idFormaPago WHEN '6' THEN dc.monto END) AS boleta,
SUM(CASE dc.idFormaPago WHEN '7' THEN dc.monto END) AS creditos
FROM detcortes dc WHERE dc.tipo = 1 AND dc.idCorte IN($idsCortes)
) a ON a.idCorte = c.id
WHERE c.id IN($idsCortes)";
//echo $sql.'<br>';
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Caja.');
$var=mysqli_fetch_array($result);
//echo $sql.'<br>';



$_SESSION['logoEncabezado'] = '../'.$var['logo'];
$_SESSION['nSuc'] = $var['nomSuc'];
$_SESSION['dirSuc'] = $var['direccion'];
$_SESSION['idEmpresa'] = $var['idEmpresa'];
#echo '<br>-->'.$idsCortes.'<--<br>';
$pdf = new newPDF('P','mm',array(80,297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
$pdf->SetMargins(5, 2 , 5);
$pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
$pdf->AddPage();

$sqlCredPag = "SELECT IF(SUM(monto) > 0,SUM(monto),0) AS credPag
FROM pagoscreditos
WHERE idCorte IN($idsCortes)";
$resCredPag = mysqli_query($link,$sqlCredPag) or die('Problemas al consultar los pagos de créditos, notifica a tu Administrador.');
$crPg = mysqli_fetch_array($resCredPag);
$totCredPag = $crPg['credPag'];

########################## comienza la cabecera ##########################
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,5,utf8_decode('Usuario:'.$nameUsuario),0,1,'L',0);
$pdf->Cell(0,5,utf8_decode('RFC: '.$var['rfc']),0,1,'L',0);
$pdf->Ln(2);
$pdf->Cell(10,5,utf8_decode($fecha),0,0,'L',0);
$pdf->Cell(0,5,utf8_decode('Corte: Sucursal por Día'),0,1,'R',0);


    $sql="SELECT * FROM gastos WHERE idCorte IN ($idsCortes)";
    $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Pagos'.$sql);
    $numres=mysqli_num_rows($datPago);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
    if ($numres>0) {
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,5,utf8_decode('DETALLADO DE GASTOS'),'B,T',1,'C');

      $tGasto=0;
			$pdf->SetFont('Arial','',8);
      while ($pag= mysqli_fetch_array($datPago)) {
				$current_y = $pdf->GetY();
	      $current_x = $pdf->GetX();
	      $pdf->MultiCell(40,4,utf8_decode($pag['descripcion']),0,'L');
	      $pdf->SetXY($current_x + 41, $current_y);
	      $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
	      $pdf->Ln(5);
	      $tGasto += $pag['monto'];
        $pdf->Ln(2);
      }

			    $pdf->SetFont('Arial','B',9);
			    $pdf->Cell(40,5,utf8_decode('TOTAL DE GASTOS'),'T',0,'L');
			    $pdf->Cell(30,5,utf8_decode('$ '.number_format($tGasto,2,".",",")),'T',1,'R');
			    $pdf->Ln(5);

    }

		#################################################################################
		############################ DETALLADO DE CREDITOS ##############################
		#################################################################################

    $sql="SELECT v.*,md.*, IF(SUM(md.monto) > 0, (SUM(pv.monto) - SUM(md.monto)), SUM(pv.monto)) AS montoCredito,cl.nombre AS nomCliente
		FROM ventas v
		INNER JOIN pagosventas pv ON v.id = pv.idVenta
		INNER JOIN clientes cl ON v.idCliente = cl.id
		LEFT JOIN creditos c ON v.id = c.idVenta
		LEFT JOIN devoluciones d ON v.id = d.idVenta
		LEFT JOIN montosdevueltos md ON d.id = md.idDevolucion AND md.idFormaPago = '7'
		WHERE v.idCorte IN($idsCortes) AND pv.idFormaPago = '7'
		GROUP BY v.id
		ORDER BY v.fechaReg";
    $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Creditos'.$sql);
    $numres=mysqli_num_rows($datPago);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';

    if ($numres>0) {
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,5,utf8_decode('DETALLADO DE CREDITOS'),'B,T',1,'L');
			$pdf->SetFont('Arial','',8);
      $tCredito=0;
      while ($pag= mysqli_fetch_array($datPago)) {
        $cancelado = ($pag['estatus'] > 2) ? ' <b><u class="text-danger">(CANCELADA)</u></b>' : '' ;
				if ($pag['montoCredito'] > 0) {
					$current_y = $pdf->GetY();
          $current_x = $pdf->GetX();
          $pdf->MultiCell(40,4,utf8_decode($pag['nomCliente']),0,'L');
          $pdf->SetXY($current_x + 41, $current_y);
          $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['montoCredito'],2,'.',',')),0,1,'R');
          $pdf->Ln(5);
          $tCredito += $pag['monto'];

	        if ($pag['estatus'] < 3) {
	          $tCredito += $pag['montoCredito'];
	        }
				}
      }
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(40,5,utf8_decode('TOTAL DE CREDITOS'),'T',0,'L');
			$pdf->Cell(30,5,utf8_decode('$ '.number_format($tCredito,2,".",",")),'T',1,'R');
			$pdf->Ln(5);

    }

		#################################################################################
		########################## DETALLADO DE TRANFERENCIAS ###########################
		#################################################################################

    $sql="SELECT v.*,md.*, IF(SUM(md.monto) > 0, (SUM(pv.monto) - SUM(md.monto)), SUM(pv.monto)) AS montoTotal,cl.nombre AS nomCliente, v.id AS idVenta
					FROM ventas v
					INNER JOIN pagosventas pv ON v.id = pv.idVenta
					LEFT JOIN clientes cl ON v.idCliente = cl.id
					LEFT JOIN creditos c ON v.id = c.idVenta
					LEFT JOIN devoluciones d ON v.id = d.idVenta
					LEFT JOIN montosdevueltos md ON d.id = md.idDevolucion AND md.idFormaPago = '3'
					WHERE v.idCorte IN($idsCortes) AND pv.idFormaPago = '3' AND v.estatus = '2'
					GROUP BY v.id
					ORDER BY v.fechaReg";
    $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Transferencias'.$sql);
    $numres=mysqli_num_rows($datPago);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';

    if ($numres>0) {
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,5,utf8_decode('DETALLADO DE TRANFERENCIAS'),'B,T',1,'L');
			$pdf->SetFont('Arial','',8);

      $tTransfer=0;
      while ($pag= mysqli_fetch_array($datPago)) {
				if ($pag['montoTotal'] > 0) {
				$current_y = $pdf->GetY();
				$current_x = $pdf->GetX();
				$pdf->MultiCell(40,4,utf8_decode('Venta con Folio: '.$pag['idVenta']),0,'L');
				$pdf->SetXY($current_x + 41, $current_y);
				$pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['montoTotal'],2,'.',',')),0,1,'R');
				$pdf->Ln(5);
	        $tTransfer += $pag['montoTotal'];
				}
      }
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(40,5,utf8_decode('TOTAL DE TRANFERENCIAS'),'T',0,'L');
        $pdf->Cell(30,5,utf8_decode('$ '.number_format($tTransfer,2,".",",")),'T',1,'R');
        $pdf->Ln(5);
    }

		#################################################################################
		############################# DETALLADO DE CHEQUES ##############################
		#################################################################################

    $sql="SELECT v.*,md.*, IF(SUM(md.monto) > 0, (SUM(pv.monto) - SUM(md.monto)), SUM(pv.monto)) AS montoTotal,cl.nombre AS nomCliente, v.id AS idVenta
					FROM ventas v
					INNER JOIN pagosventas pv ON v.id = pv.idVenta
					LEFT JOIN clientes cl ON v.idCliente = cl.id
					LEFT JOIN creditos c ON v.id = c.idVenta
					LEFT JOIN devoluciones d ON v.id = d.idVenta
					LEFT JOIN montosdevueltos md ON d.id = md.idDevolucion AND md.idFormaPago = '2'
					WHERE v.idCorte IN($idsCortes) AND pv.idFormaPago = '2' AND v.estatus = '2'
					GROUP BY v.id
					ORDER BY v.fechaReg";
    $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Cheques'.$sql);
    $numres=mysqli_num_rows($datPago);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';

    if ($numres>0) {
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,5,utf8_decode('DETALLADO DE CHEQUES'),'B,T',1,'L');
			$pdf->SetFont('Arial','',8);

      $tCheque=0;
      while ($pag= mysqli_fetch_array($datPago)) {
				if ($pag['montoTotal'] > 0) {
					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();
					$pdf->MultiCell(40,4,utf8_decode('Venta con Folio: '.$pag['idVenta']),0,'L');
					$pdf->SetXY($current_x + 41, $current_y);
					$pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['montoTotal'],2,'.',',')),0,1,'R');
					$pdf->Ln(5);

	        $tCheque += $pag['montoTotal'];
				}
      }
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(40,5,utf8_decode('TOTAL DE CHEQUES'),'T',0,'L');
			$pdf->Cell(30,5,utf8_decode('$ '.number_format($tCheque,2,".",",")),'T',1,'R');
			$pdf->Ln(5);

    }

		#################################################################################
		############################# DETALLADO DE BOLETAS ##############################
		#################################################################################

    $sqlBol="SELECT v.*,md.*, IF(SUM(md.monto) > 0, (SUM(pv.monto) - SUM(md.monto)), SUM(pv.monto)) AS montoTotal,cl.nombre AS nomCliente, v.id AS idVenta
					FROM ventas v
					INNER JOIN pagosventas pv ON v.id = pv.idVenta
					LEFT JOIN clientes cl ON v.idCliente = cl.id
					LEFT JOIN creditos c ON v.id = c.idVenta
					LEFT JOIN devoluciones d ON v.id = d.idVenta
					LEFT JOIN montosdevueltos md ON d.id = md.idDevolucion AND md.idFormaPago = '6'
					WHERE v.idCorte IN($idsCortes) AND pv.idFormaPago = '6' AND v.estatus = '2'
					GROUP BY v.id
					ORDER BY v.fechaReg";
    $datBol=mysqli_query($link,$sqlBol) or die('Problemas al Listar Cheques'.$sql);
    $numres=mysqli_num_rows($datBol);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';

    if ($numres>0) {
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,5,utf8_decode('DETALLADO DE BOLETAS'),'B,T',1,'L');
			$pdf->SetFont('Arial','',8);

      $tBoleta=0;
      while ($pag= mysqli_fetch_array($datBol)) {
				if ($pag['montoTotal'] > 0) {
					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();
					$pdf->MultiCell(40,4,utf8_decode('Venta con Folio: '.$pag['idVenta']),0,'L');
					$pdf->SetXY($current_x + 41, $current_y);
					$pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['montoTotal'],2,'.',',')),0,1,'R');
					$pdf->Ln(5);

	        $tBoleta += $pag['montoTotal'];
				}
      }
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(40,5,utf8_decode('TOTAL DE BOLETAS'),'T',0,'L');
			$pdf->Cell(30,5,utf8_decode('$ '.number_format($tBoleta,2,".",",")),'T',1,'R');
			$pdf->Ln(5);
    }

		#################################################################################
		############################## DETALLADO DE VENTAS ##############################
		#################################################################################

		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(0,5,utf8_decode('DETALLADO FINAL'),'B,T',1,'C');
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(37,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
		$pdf->Cell(33,5,utf8_decode('INGRESOS'),'B,T',1,'C');
		$pdf->Ln(1);


		$tarjeta = 0;
		$tarjeta = $var['tarjetaD'] + $var['tarjetaC'];
    if ($tarjeta>0) {
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(37,5,utf8_decode('Ventas Tarjeta'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($tarjeta,2,".",",")),0,1,'R');
			$pdf->Ln(1);

		}
    if ($var['creditos']>0) {
			$pdf->Cell(37,5,utf8_decode('Ventas Crédito'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['creditos'],2,".",",")),0,1,'R');
			$pdf->Ln(1);

		}
    if ($var['transferencia']>0) {
			$pdf->Cell(37,5,utf8_decode('Ventas Transferencia'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['transferencia'],2,".",",")),0,1,'R');
			$pdf->Ln(1);

    }
    if ($var['cheque']>0) {
			$pdf->Cell(37,5,utf8_decode('Ventas Cheque'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['cheque'],2,".",",")),0,1,'R');
			$pdf->Ln(1);

    }
    if ($var['boleta']>0) {
			$pdf->Cell(37,5,utf8_decode('Ventas Boletas'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['boleta'],2,".",",")),0,1,'R');
			$pdf->Ln(1);

    }

		if ($var['efectivo']>0) {
      $ingresos= $ingresos+ $var['efectivo'];
			$pdf->Cell(37,5,utf8_decode('Ventas en Efectivo'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['efectivo'],2,".",",")),0,1,'R');
			$pdf->Ln(1);

		}
    if ($totCredPag>0) {
      $ingresos= $ingresos+ $totCredPag;
			$pdf->Cell(37,5,utf8_decode('Créditos Pagados'),0,0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($totCredPag,2,".",",")),0,1,'R');
			$pdf->Ln(1);

    }

    if ($var['gastos']>0) {
      $egresos= $egresos+ $var['gastos'];
			$pdf->Cell(37,5,utf8_decode('Gastos'),"T,B",0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['gastos'],2,".",",")),"T,B",1,'R');
			$pdf->Ln(1);

    }
    if ($var['devuelto']>0) {
      $egresos= $egresos+ $var['devuelto'];
			$pdf->Cell(37,5,utf8_decode('Devoluciones'),"T,B",0,'L');
			$pdf->Cell(33,5,utf8_decode('$ '.number_format($var['devuelto'],2,".",",")),"T,B",1,'R');
			$pdf->Ln(1);

    }
		$efectivo = $var['efectivo'] + $totCredPag;

    $totVentas = $var['creditos'] + $var['boleta'] + $tarjeta + $var['transferencia'] + $var['cheque'] + $var['efectivo'];
		#echo '<br>$totVentas ('.$totVentas.') = $var[\'creditos\'] ('.$var['creditos'].') + $var[\'boleta\'] ('.$var['boleta'].') + $tarjeta ('.$tarjeta.') + $var[\'transferencia\'] ('.$var['transferencia'].') + $var[\'cheque\'] ('.$var['cheque'].') + $var[\'efectivo\'] ('.$var['efectivo'].')';

		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,utf8_decode('Ingresos='),"T,B",0,'L');
		$pdf->Cell(33,5,utf8_decode('$ '.number_format($ingresos,2,".",",")),"T,B",1,'R');
		$pdf->Cell(37,5,utf8_decode('Egresos='),"T,B",0,'L');
		$pdf->Cell(33,5,utf8_decode('$ '.number_format($egresos,2,".",",")),"T,B",1,'R');

		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(37,5,utf8_decode('TOTAL EFECTIVO='),"T,B",0,'L');
		$pdf->Cell(33,5,utf8_decode('$ '.number_format($ingresos,2,".",",")),"T,B",1,'R');
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(37,5,utf8_decode('TOTAL VENTAS='),"T,B",0,'L');
		$pdf->Cell(33,5,utf8_decode('$ '.number_format($totVentas,2,".",",")),"T,B",1,'R');

		$pdf->Ln(10);

		$pdf->Cell(0,5,utf8_decode($nameUsuario),"T",1,'C');
		$pdf->Cell(0,5,utf8_decode('Corte de Caja por Sucursal'),0,1,'C');

		      $pdf->Ln(2);

		#$pdf->AutoPrint();
		$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
		unset($_SESSION['logoEncabezado']);
		unset($_SESSION['nSuc']);
		unset($_SESSION['dirSuc']);
		unset($_SESSION['idEmpresa']);
		?>
