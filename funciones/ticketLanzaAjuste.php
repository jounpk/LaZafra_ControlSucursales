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

$debug = 0;
$idAjuste = (isset($_REQUEST['idAjuste']) AND $_REQUEST['idAjuste'] >= 1) ? $_REQUEST['idAjuste'] : 0 ;

$sql="SELECT suc.nombre AS sucursal,suc.direccion, CONCAT(usuEn.nombre,' ',usuEn.appat,' ',usuEn.apmat) AS useremitio,
emp.id AS 'idEmp',emp.nombre AS nameEmp, emp.rfc, emp.logo, emp.anchoLogo, ajs.*
FROM ajustes ajs
INNER JOIN sucursales suc ON ajs.idSucursal = suc.id
INNER JOIN empresas emp ON suc.idEmpresa = emp.id
LEFT JOIN segusuarios usuEn ON ajs.idUserAplica = usuEn.id
WHERE ajs.id = '$idAjuste'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
$var=mysqli_fetch_array($result);

	$logo = '../'.$var['logo'];;
	$nomSuc = $var['sucursal'];;
	$dirSuc = $var['direccion'];;
	$idEmp = $var['idEmp'];;

#echo 'Si entra';
#echo '<br>$idAjuste'.$idAjuste;
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
$pdf->Cell(0,4,utf8_decode('***AJUSTES DE PRODUCTO***'),0,1,'C',0);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,5,utf8_decode('Fecha: '.$var['fechaAplica']),'B',0,'L',0);
$pdf->Cell(0,5,utf8_decode('Folio: '.$var['id']),'B',1,'R',0);
$pdf->Ln(4);


			$sql="SELECT pro.descripcion,det.*
			FROM detajustes det
			LEFT JOIN productos pro ON det.idProducto = pro.id
						WHERE idAjuste = '$idAjuste' AND det.tipo='1'";
			$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado del Ajuste.');
			$cantEntrada = mysqli_num_rows($result);
			if ($cantEntrada > 0) {
					########################## comienza encabezado ##########################
					$pdf->SetFont('Arial','B',9);
					$pdf->Cell(0,4,utf8_decode('Ajustes de Entrada'),'B',1,'C',0);
					$pdf->Cell(35,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
					$pdf->Cell(20,5,utf8_decode('CANT'),'B,T',0,'L');
					$pdf->Cell(15,5,utf8_decode('CHECK'),'B,T',1,'L');
					########################## termina encabezado ##########################
					$count = $nPag = 0;
		  		while ($row=mysqli_fetch_array($result))
		  		{
						$count++;
		  			$iddetAjs=$row["id"];
		        $pdf->SetFont('Arial','B',9);
		  			$iddet=$row["id"];
		        $caracteres = 0;
		        $caracteres = strlen($row['descripcion']);
		        $cantLineas = abs($caracteres)/16;

		        $current_y = $pdf->GetY();
		        $current_x = $pdf->GetX();
		        $pdf->MultiCell(40,4,utf8_decode($row['descripcion']),0,'L');
		        $pdf->SetXY($current_x + 35, $current_y);
		        $pdf->Cell(20,4,utf8_decode(number_format($row['cantidad'],2,'.',',')),0,0,'R');
		  			$pdf->SetFont('ZapfDingbats','B',15);
		        $pdf->Cell(10,4,chr('111'),0,1,'C');

		        if ($caracteres > 24 && $caracteres < 36) {
		          $pdf->Ln(round($cantLineas+4));
		        }elseif ($caracteres > 35) {
		          $pdf->Ln(round($cantLineas+6));
		        } elseif ($caracteres > 15 && $caracteres < 25) {
		          $pdf->Ln(round($cantLineas+3));
		        }else {

		        }
		        if ($debug == 1) {
		         #$pdf->Ln(12);
		          $pdf->SetFont('Arial','B',9);
		          $pdf->MultiCell(35,4,utf8_decode('('.$caracteres.')'.'('.$cantLineas.')'.'('.round($cantLineas).')'),0,'L');
		        }
		  			$sqllote="SELECT lote.lote, dtlote.cantidad
		  			FROM detajustelote dtlote
		  			INNER JOIN lotestocks lote ON lote.id = dtlote.idLote
		  			INNER JOIN detajustes dtajs ON dtajs.id = dtlote.idDetAjuste
		  		    WHERE dtajs.idAjuste = '$idAjuste' AND dtajs.id='$iddetAjs'";
		  			$resultlote=mysqli_query($link,$sqllote) or die('Problemas al Consultar el Detallado del Lote.');
						$count2 = 0;
		  			while ($rowLote=mysqli_fetch_array($resultlote))
		  			{
							$count2++;
		          $iddetAjs=$row["id"];
		          $pdf->SetFont('Arial','',8);
		          $iddet=$row["id"];

		          $current_y = $pdf->GetY();
		          $current_x = $pdf->GetX();
		          $pdf->MultiCell(40,4,utf8_decode($rowLote['lote']),0,'L');
		          $pdf->SetXY($current_x + 35, $current_y);
		          $pdf->Cell(20,4,utf8_decode(number_format($rowLote['cantidad'],2,'.',',')),0,0,'R');
		          $pdf->Cell(10,4,'',0,1,'C');

		  			}

						if ($count2 > 0) {
							$count += $count2;
						}

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
						}else {
							$pdf->Ln(2);
						}

		  		}
		}

      ########################## comienza encabezado ##########################
			$sql="SELECT pro.descripcion,det.*
			FROM detajustes det
			LEFT JOIN productos pro ON det.idProducto = pro.id
						WHERE idAjuste = '$idAjuste' AND det.tipo='2'";
			$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado del Ajuste.');
			$cantSalida = mysqli_num_rows($result);
			if ($cantSalida > 0) {
	      ########################## comienza encabezado ##########################
	      $pdf->Ln(5);
	      $pdf->SetFont('Arial','B',9);
	      $pdf->Cell(0,4,utf8_decode('Ajustes de Salida'),'B',1,'C',0);
	      $pdf->Cell(35,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
	      $pdf->Cell(20,5,utf8_decode('CANT'),'B,T',0,'L');
	      $pdf->Cell(15,5,utf8_decode('CHECK'),'B,T',1,'L');
	      ########################## termina encabezado ##########################
				$count++;

	        		while ($row=mysqli_fetch_array($result))
	        		{
	        			$iddetAjs=$row["id"];
	              $pdf->SetFont('Arial','B',9);
	        			$iddet=$row["id"];
	              $caracteres2 = 0;
	              $caracteres2 = strlen($row['descripcion']);
	              $cantLineas2 = abs($caracteres)/16;

	              $current_y = $pdf->GetY();
	              $current_x = $pdf->GetX();
	              $pdf->MultiCell(40,4,utf8_decode($row['descripcion']),0,'L');
	              $pdf->SetXY($current_x + 35, $current_y);
	              $pdf->Cell(20,4,utf8_decode(number_format($row['cantidad'],2,'.',',')),0,0,'R');
	        			$pdf->SetFont('ZapfDingbats','B',15);
	              $pdf->Cell(10,4,chr('111'),0,1,'C');


	              if ($caracteres2 > 24 && $caracteres2 < 36) {
	                $pdf->Ln(round($cantLineas2+4));
	              }elseif ($caracteres2 > 35) {
	                $pdf->Ln(round($cantLineas2+6));
	              } elseif ($caracteres2 > 15 && $caracteres2 < 25) {
	                $pdf->Ln(round($cantLineas2+3));
	              }else {

	              }

	              if ($debug == 1) {
	               $pdf->Ln(5);
	                $pdf->SetFont('Arial','',8);
	                $pdf->MultiCell(35,4,utf8_decode('('.$caracteres2.')'.'('.$cantLineas2.')'.'('.round($cantLineas2).')'),0,'L');
	              }

	        			$sqllote="SELECT lote.lote, dtlote.cantidad
	        			FROM detajustelote dtlote
	        			INNER JOIN lotestocks lote ON lote.id = dtlote.idLote
	        			INNER JOIN detajustes dtajs ON dtajs.id = dtlote.idDetAjuste
	        					  WHERE dtajs.idAjuste = '$idAjuste' AND dtajs.id='$iddetAjs'";
	        			$resultlote=mysqli_query($link,$sqllote) or die('Problemas al Consultar el Detallado del Lote.');
								$count3 = 0;
	        			while ($rowLote=mysqli_fetch_array($resultlote))
	        			{
									$count3++;
	                $iddetAjs=$row["id"];
	                $pdf->SetFont('Arial','',8);
	                $iddet=$row["id"];
	                $current_y = $pdf->GetY();
	                $current_x = $pdf->GetX();
	                $pdf->MultiCell(35,4,utf8_decode($rowLote['lote']),0,'L');
	                $pdf->SetXY($current_x + 35, $current_y);
	                $pdf->Cell(20,4,utf8_decode(number_format($rowLote['cantidad'],2,'.',',')),0,0,'R');
	                $pdf->Cell(15,4,'',0,1,'C');

	        			}


								if ($count3 > 0) {
									$count += $count3;
								}

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
								}else {
									$pdf->Ln(2);
								}

								$count++;
	        		}
					}

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
					}else {
						$pdf->Ln(2);
					}

    ########################## comienza pie de página ##########################
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,5,utf8_decode(),0,0,'C');
    $pdf->Ln(20);
    $pdf->Cell(0,5,utf8_decode($var['useremitio']),'T',1,'C');

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
		?>
