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
$idSucursal = $_SESSION['LZFidSuc'];


$idAnaquel = (isset($_REQUEST['idAnaquel']) AND $_REQUEST['idAnaquel'] >= 1) ? $_REQUEST['idAnaquel'] : 0 ;
$cat = (isset($_REQUEST['cat']) AND $_REQUEST['cat'] >= 1) ? $_REQUEST['cat'] : 0 ;

$sql="SELECT s.direccion,s.nombre AS nomSuc, s.idEmpresa,e.logo
FROM sucursales s
INNER JOIN empresas e ON s.idEmpresa = e.id
WHERE s.id = '$idSucursal'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
$var=mysqli_fetch_array($result);

	$logo = '../'.$var['logo'];;
	$nomSuc = $var['nomSuc'];;
	$dirSuc = $var['direccion'];;
	$idEmp = $var['idEmpresa'];;
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
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,5,utf8_decode('***ANAQUEL No. '.$idAnaquel.'***'),0,1,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial','B',8);
$fecha = date('Y-m-d H:i:s');
$pdf->Cell(0,5,utf8_decode($fecha),0,1,'L',0);
$pdf->Cell(0,5,utf8_decode('ANAQUEL: '.$idAnaquel),0,1,'L',0);

########################## comienza encabezado ##########################
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
$pdf->Cell(20,5,utf8_decode('CANT'),'B,T',0,'R');
$pdf->Cell(10,5,utf8_decode('REAL'),'B,T',1,'R');
########################## termina encabezado ##########################
$pdf->Ln(2);

		$sql="SELECT sto.*, pro.descripcion as producto
                    FROM stocks sto
                    INNER JOIN productos pro ON sto.idProducto=pro.id
                    WHERE sto.idSucursal='$idSucursal' AND sto.anaquel='$idAnaquel'
                    ORDER BY sto.anaquel, producto ASC";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado del Anaquel.');
    $count = $nPag = 0;
		while ($row=mysqli_fetch_array($result))
		{
			$idStock = $row['id'];
      $caracteres = 0;
      $caracteres = strlen($row['producto']);
      $cantLineas = abs($caracteres)/16;
			$pdf->SetFont('Arial','',8);
			$pdf->SetFont('Arial','B',9);
			$iddet=$row["id"];
      $current_y = $pdf->GetY();
      $current_x = $pdf->GetX();
      $pdf->MultiCell(40,4,utf8_decode($row['producto']),0,'L');
      $pdf->SetXY($current_x + 40, $current_y);
      $pdf->Cell(20,4,utf8_decode(number_format($row['cantActual'],2,'.',',')),0,0,'R');
			$pdf->SetFont('Arial','B',9);
      $pdf->Cell(10,4,'','B',1,'R');


			if ($caracteres > 24 && $caracteres < 36) {
				$pdf->Ln(round($cantLineas+4));
			}elseif ($caracteres > 35) {
				$pdf->Ln(round($cantLineas+5));
			} elseif ($caracteres > 15 && $caracteres < 25) {
				$pdf->Ln(round($cantLineas+3));
			}else {

			}

			$sqllote="SELECT * FROM lotestocks WHERE idStock = '$idStock' AND cant > 0";

			$resultlote=mysqli_query($link,$sqllote) or die('Problemas al Consultar el Detallado del Lote.');
			$count2 = 0;
			while ($rowLote=mysqli_fetch_array($resultlote))
			{
				$count2++;
				$pdf->SetFont('Arial','',9);
				$current_y = $pdf->GetY();
				$current_x = $pdf->GetX();
				$pdf->MultiCell(40,4,utf8_decode($rowLote['lote']),0,'L');
				$pdf->SetXY($current_x + 40, $current_y);
				$pdf->Cell(20,4,utf8_decode(number_format($rowLote['cant'],2,'.',',')),0,0,'R');
				$pdf->Cell(10,4,utf8_decode(''),0,1,'C');
				#$pdf->Ln(2);
			}

			if ($count2 > 0) {
				$count += $count2 + 1;
			} else {
				$count++;
			}

			if ($count == 17 && $nPag < 1) {
        $nPag++;
        $pdf->AddPage();
        $pdf->Ln(5);
        $count = 0;
      } elseif ($count == 22 && $nPag > 0) {
        $nPag++;
        $pdf->AddPage();
        $pdf->Ln(5);
        $count = 0;
      }else {
        $pdf->Ln(2);
      }
      $pdf->SetFont('Arial','B',9);
      /*
      if ($count == 18 && $nPag < 1) {
      $pdf->MultiCell(35,4,utf8_decode(''),0,'L');
      }
      $pdf->MultiCell(35,4,utf8_decode('('.$caracteres.')'.'('.$cantLineas.')'.'('.round($cantLineas).')'),0,'L');
      #*/
		}
    ########################## comienza pie de página ##########################
    $pdf->Ln(20);
    $pdf->SetFont('Arial','B',9);
      $pdf->Cell(0,5,utf8_decode(''),'B',1,'C');
        $pdf->MultiCell(70,4,utf8_decode('Nombre y Firma de Quien Revisa'),0,'C');


#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
    ?>
