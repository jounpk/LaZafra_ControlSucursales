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


$idTraspaso = (isset($_REQUEST['idTraspaso']) AND $_REQUEST['idTraspaso'] >= 1) ? $_REQUEST['idTraspaso'] : 0 ;
$cat = (isset($_REQUEST['cat']) AND $_REQUEST['cat'] >= 1) ? $_REQUEST['cat'] : 0 ;

$sql="SELECT sucEn.nombre AS sucursalEnt, CONCAT(usuEn.nombre,' ',usuEn.appat,' ',usuEn.apmat) AS userEnt,
sucSal.nombre AS sucursalSal,sucSal.direccion AS direccionSal,sucEn.nombre AS sucursalEn,sucEn.direccion AS direccionEn,emp2.id AS idEmpresa2,emp.id AS idEmpresa,
CONCAT(usuSal.nombre,' ',usuSal.appat,' ',usuSal.apmat) AS userSal, emp.nombre AS nameEmp,CONCAT(usuBod.nombre,' ',usuBod.appat,' ',usuBod.apmat) AS userBod,
emp.rfc, emp.logo,emp2.logo AS 'logo2', tras.*
FROM traspasos tras
LEFT JOIN sucursales sucEn ON tras.idSucEntrada = sucEn.id
INNER JOIN sucursales sucSal ON tras.idSucSalida = sucSal.id
INNER JOIN empresas emp ON sucSal.idEmpresa = emp.id
INNER JOIN empresas emp2 ON sucEn.idEmpresa = emp2.id
LEFT JOIN segusuarios usuEn ON tras.idUserRecepcion = usuEn.id
LEFT JOIN segusuarios usuSal ON tras.idUserEnvio = usuSal.id
LEFT JOIN segusuarios usuBod ON tras.idUserBodega = usuBod.id
WHERE tras.id = '$idTraspaso'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
$var=mysqli_fetch_array($result);
$valEstatus = $var['estatus'];
switch ($var['estatus']) {
    case "1":
        $estatus='Generando Envío';
        break;
		case "2":
        $estatus='Envío de Mercancia';
        break;
    default:
        $estatus='Envío de Mercancia';
        break;
}
$userBod = ($var['userBod'] != '') ? $var['userBod'] : '' ;
if($var['userEnt']!=''){
	$logo = '../'.$var['logo2'];
	$nomSuc = $var['sucursalEn'];;
	$dirSuc = $var['direccionEn'];;
	$idEmp = $var['idEmpresa2'];;
} else {
	$logo = '../'.$var['logo'];;
	$nomSuc = $var['sucursalSal'];;
	$dirSuc = $var['direccionSal'];;
	$idEmp = $var['idEmpresa'];;
}
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
$pdf->Cell(0,5,utf8_decode('***TRASPASO DE MERCANCIA***'),0,1,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,4,utf8_decode('Sucursal Envía:  '.$var['sucursalSal']),0,1,'L',0);
$pdf->Cell(22,4,utf8_decode('Usuario Envía: '),0,0,'L',0);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(48,4,utf8_decode($var['userSal']),0,'L');
$pdf->SetFont('Arial','',8);
  $pdf->Ln(4);
$pdf->Cell(0,4,utf8_decode('Sucursal Recibe:  '.$var['sucursalEnt']),0,1,'L',0);
if($var['userEnt']!=''){
$pdf->Cell(22,4,utf8_decode('Usuario Recibe: '),0,0,'L',0);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(48,4,utf8_decode($var['userEnt']),0,'L');
}
$pdf->Ln(2);
$pdf->Cell(10,5,utf8_decode($var['fechaEnvio']),0,0,'L',0);
$pdf->Cell(0,5,utf8_decode('Folio: '.$var['id']),0,1,'R',0);

########################## comienza encabezado ##########################
$pdf->SetFont('Arial','B',9);
$pdf->Cell(35,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
$pdf->Cell(20,5,utf8_decode('ENVIO'),'B,T',0,'R');
$pdf->Cell(15,5,utf8_decode('CHECK'),'B,T',1,'R');
########################## termina encabezado ##########################

		$sql="SELECT pro.descripcion,det.*
          FROM dettraspasos det
          LEFT JOIN productos pro ON det.idProducto = pro.id
					WHERE idTraspaso = '$idTraspaso'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
    $count = $nPag = 0;
		while ($row=mysqli_fetch_array($result))
		{
      $caracteres = 0;
      $caracteres = strlen($row['descripcion']);
      $cantLineas = abs($caracteres)/16;
			$pdf->SetFont('Arial','',8);
			$pdf->SetFont('Arial','B',9);
			$iddet=$row["id"];
      $current_y = $pdf->GetY();
      $current_x = $pdf->GetX();
      $pdf->MultiCell(40,4,utf8_decode($row['descripcion']),0,'L');
      $pdf->SetXY($current_x + 40, $current_y);
      $pdf->Cell(20,4,utf8_decode(number_format($row['cantEnvio'],2,'.',',')),0,0,'R');
			$pdf->SetFont('ZapfDingbats','B',9);
      $pdf->Cell(10,4,chr('111'),0,1,'C');

      $count++;

	if($var['userEnt']!=''){
		$sqllote="SELECT lote.lote, dtlote.cantidad
		FROM dettrasplote dtlote
		INNER JOIN lotestocks lote ON lote.id = dtlote.idLoteEntrada
		INNER JOIN dettraspasos dt ON dt.id = dtlote.idDetTraspaso
		WHERE dt.idTraspaso = '$idTraspaso' AND dt.id='$iddet'";
		//echo $sqllote;
		 }else{
       $sqllote="SELECT lote.lote, dtlote.cantidad
       FROM dettrasplote dtlote
       INNER JOIN lotestocks lote ON lote.id = dtlote.idLoteSalida
       INNER JOIN dettraspasos dt ON dt.id = dtlote.idDetTraspaso
       WHERE dt.idTraspaso = '$idTraspaso' AND dt.id='$iddet'";
	//echo $sqllote;

		 }


			$resultlote=mysqli_query($link,$sqllote) or die('Problemas al Consultar el Detallado del Lote.');
      $count2 = 0;
      while ($rowLote=mysqli_fetch_array($resultlote))
			{
        if ($caracteres > 24 && $caracteres < 36) {
          $pdf->Ln(round($cantLineas+3));
        }elseif ($caracteres > 35) {
          $pdf->Ln(round($cantLineas+6));
        } elseif ($caracteres > 15 && $caracteres < 25) {
          $pdf->Ln(round($cantLineas+2));
        }else {

        }
				$pdf->SetFont('Arial','',9);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $pdf->MultiCell(40,4,utf8_decode($rowLote['lote']),0,'L');
        $pdf->SetXY($current_x + 40, $current_y);
        $pdf->Cell(20,4,utf8_decode(number_format($rowLote['cantidad'],2,'.',',')),0,0,'R');
        $pdf->Cell(10,4,utf8_decode(''),0,1,'C');
        $count2++;
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
      $pdf->SetFont('Arial','B',9);
      /*
      if ($count == 18 && $nPag < 1) {
      $pdf->MultiCell(35,4,utf8_decode(''),0,'L');
      }
      $pdf->MultiCell(35,4,utf8_decode('('.$caracteres.')'.'('.$cantLineas.')'.'('.round($cantLineas).')'),0,'L');
      */
		}
    ########################## comienza pie de página ##########################
    $pdf->Ln(30);
    $pdf->SetFont('Arial','B',9);
    if ($cat > 0) {
      $pdf->MultiCell(70,4,utf8_decode($userBod),'T','C');
      $pdf->MultiCell(70,4,utf8_decode('Carga en Bodega'),0,'C');
    } else {
      $pdf->Cell(0,5,utf8_decode($estatus),'T',1,'C');
      if ($valEstatus == 2 || $valEstatus == 3) {
        $pdf->MultiCell(70,4,utf8_decode('Nombre y Firma del Receptor'),0,'C');
      }
    }

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
    ?>
