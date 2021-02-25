<?php
#require_once '../seg.php';
#$info = new Seguridad();
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


$idRecepcion = (isset($_REQUEST['idRecepcion']) AND $_REQUEST['idRecepcion'] >= 1) ? $_REQUEST['idRecepcion'] : 0 ;

$sql="SELECT r.*, s.nombre AS 'nomSuc', s.direccion, s.idEmpresa, e.logo, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nombreRecibe,p.nombre AS 'nomProveedor'
FROM recepciones r
INNER JOIN compras c ON r.idCompra = c.id
INNER JOIN proveedores p ON c.idProveedor = p.id
INNER JOIN sucursales s ON r.idSucursal = s.id
INNER JOIN empresas e ON s.idEmpresa = e.id
INNER JOIN segusuarios u ON r.idUserReg = u.id
WHERE r.id = '$idRecepcion'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
$var=mysqli_fetch_array($result);

$logo = '../'.$var['logo'];;
$nomSuc = $var['nomSuc'];;
$dirSuc = $var['direccion'];;
$idEmp = $var['idEmpresa'];;
$nomRecibe = $var['nombreRecibe'];
$nombreEntrega = $var['entrega'];
#echo 'Si entra';
#echo '<br>$idTraspaso'.$idTraspaso;
$_SESSION['logoEncabezado'] = $logo;
$_SESSION['nSuc'] = $nomSuc;
$_SESSION['dirSuc'] = $dirSuc;
$_SESSION['idEmpresa'] = $idEmp;

$pdf = new newPDF('P','mm',array(80,297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
$pdf->SetMargins(5, 2 , 5);
$pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
$pdf->AddPage();

########################## comienza la cabecera ##########################
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,5,utf8_decode('***RECEPCION DE MERCANCIA***'),0,1,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(18,4,utf8_decode('No Compra: '),0,0,'L',0);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(48,4,utf8_decode($var['idCompra']),0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetFont('Arial','',8);
$pdf->Cell(18,4,utf8_decode('Proveedor: '),0,0,'L',0);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(48,4,utf8_decode($var['nomProveedor']),0,'L');
$pdf->SetFont('Arial','',8);



$pdf->Ln(2);
$pdf->Cell(10,5,utf8_decode($var['fechaReg']),0,0,'L',0);
$pdf->Cell(0,5,utf8_decode('Recepción: '.(int)$var['id']),0,1,'R',0);

########################## comienza encabezado ##########################
$pdf->SetFont('Arial','B',9);
$pdf->Cell(45,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
$pdf->Cell(25,5,utf8_decode('RECIBIDO'),'B,T',1,'L');
########################## termina encabezado ##########################

		$sql="SELECT dtr.idRecepcion,r.fechaReg,p.id AS idProducto,IF(p.id>0,p.descripcion,dc.nombreProducto) AS 'nomProducto',
					IF(lts.id > 0,SUM(dtr.cantidad),dtr.cantidad) AS 'cantidad', CONCAT(lts.lote, IF(lts.caducidad > 0, CONCAT(' (',lts.caducidad,')'),'')) AS Lote
					FROM recepciones r
					INNER JOIN detrecepciones dtr ON r.id = dtr.idRecepcion
					INNER JOIN detcompras dc ON dtr.idDetCompra = dc.id
					LEFT JOIN productos p ON dtr.idProducto = p.id
					LEFT JOIN lotestocks lts ON dtr.idLote = lts.id
					WHERE r.id = '$idRecepcion'
					GROUP BY IF(lts.id > 0,lts.id,dc.id)
          ORDER BY nomProducto DESC";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de recepciones.');
    $idPro = $idProAnt = '';
		while ($row=mysqli_fetch_array($result))
		{
      $idPro = $row['nomProducto'];
      if ($idPro != $idProAnt) {
        $caracteres = 0;
        $caracteres = strlen($row['nomProducto']);
        $cantLineas = abs($caracteres)/20;
  			$pdf->SetFont('Arial','',8);
  			$pdf->SetFont('Arial','B',9);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $pdf->MultiCell(45,4,utf8_decode($row['nomProducto']),0,'L');
        $pdf->SetXY($current_x + 45, $current_y);
        $pdf->Cell(25,4,utf8_decode(number_format($row['cantidad'],2,'.',',')),0,1,'R');

          if ($caracteres > 35) {
            $pdf->Ln(round($cantLineas+6));
          }elseif ($caracteres > 24 && $caracteres < 36) {
            $pdf->Ln(round($cantLineas+2));
          } elseif ($caracteres > 15 && $caracteres < 25) {
            $pdf->Ln(round($cantLineas+1));
          }else {

          }
        }
        if ($row['Lote'] != '') {
  				$pdf->SetFont('Arial','',8);
          $current_y = $pdf->GetY();
          $current_x = $pdf->GetX();
          $pdf->MultiCell(45,4,utf8_decode($row['Lote']),0,'L');
          $pdf->SetXY($current_x + 45, $current_y);
          $pdf->Cell(25,4,utf8_decode(number_format($row['cantidad'],2,'.',',')),0,1,'R');
    			$pdf->Ln(3);
          $pdf->SetFont('Arial','B',9);
      } else {
        $pdf->Ln(3);
      }
      #$pdf->Ln(5);
/*
      if ($idPro != $idProAnt) {
        $pdf->MultiCell(35,4,utf8_decode('('.$caracteres.')'.'('.$cantLineas.')'.'('.round($cantLineas).')'),0,'L');
      }
#*/
      $idProAnt = $idPro;
		}
    ########################## comienza pie de página ##########################
    $pdf->Ln(20);
    $pdf->SetFont('Arial','B',9);
		$pdf->MultiCell(0,4,utf8_decode($nomRecibe),'T','C');
    $pdf->Cell(0,4,utf8_decode('Receptor'),0,1,'C');


    $pdf->Ln(20);
    $pdf->SetFont('Arial','B',9);
		$pdf->MultiCell(0,4,utf8_decode($nombreEntrega),'T','C');
    $pdf->Cell(0,4,utf8_decode('Entrega'),0,1,'C');

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
    ?>
