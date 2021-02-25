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


$idCorte = (!empty($_REQUEST['idCorte'])) ? $_REQUEST['idCorte'] : '' ;
$tipo = (!empty($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : 0 ;
$fecha = (isset($_REQUEST['fecha']) && $_REQUEST['fecha'] != '') ? $_REQUEST['fecha'] : '' ;
$idSuc = (!empty($_REQUEST['suc'])) ? $_REQUEST['suc'] : 0 ;

if ($tipo == 1) {
  ##########################################################################
  ########################## Sí es sólo 1 corte o ##########################
  ########################## impresión individual ##########################
  ##########################################################################
$sql="SELECT c.id AS noCorte, c.montoEfectivo,e.anchoLogo,e.logo,s.nombre AS nomSuc,s.direccion,e.rfc,CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS Cajero,
      CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat) AS Recolector, DATE_FORMAT(IF(d.fechaReg > 0,d.fechaReg,c.fechaCierre),'%Y-%m-%d') AS fechaHora,
      CONCAT(u3.nombre,' ',u3.appat,' ',u3.apmat) AS EntregaCorte, c.idEntregaCorte, e.id AS idEmpresa
      FROM cortes c
      INNER JOIN sucursales s ON c.idSucursal = s.id
      INNER JOIN empresas e ON s.idEmpresa = e.id
      INNER JOIN segusuarios u ON c.idUserReg = u.id
      LEFT JOIN depositos d ON c.id = d.idCorte
      LEFT JOIN segusuarios u2 ON d.idUserReg = u2.id
      LEFT JOIN segusuarios u3 ON c.idEntregaCorte = u3.id
      WHERE c.id = '$idCorte'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar los Cortes.');
$var=mysqli_fetch_array($result);

	$logo = '../'.$var['logo'];
	$nomSuc = $var['nomSuc'];;
	$dirSuc = $var['direccion'];;
	$idEmp = $var['idEmpresa'];;
  $rfc = $var['rfc'];
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
$pdf->SetFont('Arial','',8);
$pdf->Cell(12,4,utf8_decode('RFC: '),0,0,'L',0);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(58,4,utf8_decode($rfc),0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(10,5,utf8_decode($var['fechaHora']),0,1,'L',0);

########################## comienza encabezado ##########################
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,5,utf8_decode('#'),'B,T',0,'L');
$pdf->Cell(30,5,utf8_decode('CORTE'),'B,T',0,'L');
$pdf->Cell(30,5,utf8_decode('SUBTOTAL'),'B,T',1,'C');
########################## termina encabezado ##########################
$pdf->Cell(10,5,utf8_decode('1'),'B,T',0,'L');
$pdf->Cell(30,5,utf8_decode($var['noCorte']),'B,T',0,'L');
$pdf->Cell(30,5,utf8_decode('$ '.number_format($var['montoEfectivo'],2,".","'")),'B,T',1,'R');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,5,utf8_decode('TOTAL= '),'T',0,'R');
$pdf->Cell(28,5,utf8_decode('$ '.number_format($var['montoEfectivo'],2,'.',',')),'T',1,'R');

$pdf->Ln(15);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,5,utf8_decode(''),'B',1,'C');
$pdf->MultiCell(70,4,utf8_decode('Nombre y Firma de Quien Entrega'),0,'C');

$pdf->Ln(15);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,5,utf8_decode(''),'B',1,'C');
$pdf->MultiCell(70,4,utf8_decode('Recolector: '.$_SESSION['LZFnombreUser']),0,'C');

} else {
  ##########################################################################
  ########################## Sí es más de 1 corte ##########################
  ##########################################################################
  $sql="SELECT e.anchoLogo,e.logo, s.nombre As nomSuc, s.direccion,e.rfc,CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS Cajero,
CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat) AS Recolector, DATE_FORMAT(d.fechaReg,'%Y-%m-%d %H:%m:%s') AS fechaHora,
CONCAT(u3.nombre,' ',u3.appat,' ',u3.apmat) AS EntregaCorte, c.idEntregaCorte,e.id AS idEmpresa
FROM cortes c
INNER JOIN sucursales s ON c.idSucursal = s.id
INNER JOIN empresas e ON s.idEmpresa = e.id
INNER JOIN segusuarios u ON c.idUserReg = u.id
INNER JOIN depositos d ON c.idRecoleccion = d.id
INNER JOIN segusuarios u2 ON d.idUserReg = u2.id
LEFT JOIN segusuarios u3 ON c.idEntregaCorte = u3.id
WHERE c.idSucursal = '$idSuc' AND c.idRecoleccion > 0 AND d.estatus = '0' AND  DATE_FORMAT(d.fechaReg, '%Y-%m-%d') = '$fecha' LIMIT 1";
  //echo $sql;
  $result=mysqli_query($link,$sql) or die('Problemas al Consultar los Cortes.');
  $var=mysqli_fetch_array($result);

  	$logo = '../'.$var['logo'];
  	$nomSuc = $var['nomSuc'];;
  	$dirSuc = $var['direccion'];;
  	$idEmp = $var['idEmpresa'];;
    $rfc = $var['rfc'];
    $cajero = $var['Cajero'];
    $recolector = $var['Recolector'];
    $nomEntrega = ($var['idEntregaCorte'] > 0) ? $var['EntregaCorte'] : 'Nombre y firma de quien entrega' ;
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
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(12,4,utf8_decode('RFC: '),0,0,'L',0);
  $pdf->SetFont('Arial','B',8);
  $pdf->MultiCell(58,4,utf8_decode($rfc),0,'L');
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(10,5,utf8_decode($var['fechaHora']),0,1,'L',0);
  ########################## comienza encabezado ##########################
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(10,5,utf8_decode('#'),'B,T',0,'L');
  $pdf->Cell(30,5,utf8_decode('CORTE'),'B,T',0,'L');
  $pdf->Cell(30,5,utf8_decode('SUBTOTAL'),'B,T',1,'C');
  ########################## termina encabezado ##########################
  $sql="SELECT c.id AS noCorte,c.montoEfectivo
        FROM cortes c
        INNER JOIN depositos d ON c.id = d.idCorte
        WHERE DATE_FORMAT(d.fechaReg, '%Y-%m-%d') = '$fecha' AND c.idSucursal = '$idSuc' AND d.estatus = '0'";
        #echo '<br>$sql: '.$sql;
  $result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
  $numero = 1; $sumaCant = 0;
  while ($row=mysqli_fetch_array($result)){
    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    $pdf->Cell(10,5,utf8_decode($numero),0,0,'L');
    $pdf->MultiCell(30,4,utf8_decode($row['noCorte']),0,'L');
    $pdf->SetXY($current_x + 30, $current_y);
    $pdf->Cell(30,4,utf8_decode('$ '.number_format($row['montoEfectivo'],2,'.',',')),0,1,'R');
    $sumaCant += $row['montoEfectivo'];
    $numero++;
  }

  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(40,5,utf8_decode('TOTAL= '),'T',0,'R');
  $pdf->Cell(28,5,utf8_decode('$ '.number_format($sumaCant,2,'.',',')),'T',1,'R');


  $pdf->Ln(15);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(0,5,utf8_decode(''),'B',1,'C');
  $pdf->MultiCell(70,4,utf8_decode($nomEntrega),0,'C');
  #$pdf->Cell(0,5,utf8_decode('Entrega'),0,1,'C');

  $pdf->Ln(5);
  $pdf->Cell(0,5,utf8_decode('Recolector'),0,1,'C');
  $pdf->Ln(15);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(0,5,utf8_decode(''),'B',1,'C');
  $pdf->MultiCell(70,4,utf8_decode($recolector),0,'C');
}


#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
		?>
