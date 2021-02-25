<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('include/connect.php');
require('Fpdf/tickets.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = 'imprimeTicketCorte.php';
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];

//define('INCLUDE_CHECK',1);
//require "include/connect.php";
//session_start();
$id=(!empty($_REQUEST['idCorte'])) ? $_REQUEST['idCorte'] : 0 ;
$id2 = ($_SESSION['LZFticketCorte'] != '') ? $_SESSION['LZFticketCorte'] : 0;
if ($id > 0) {
  $idCorte = $id;
} elseif ($id2 > 0) {
  $idCorte = $id2;
}  else {
  $idCorte = 0;
}
unset($_SESSION['LZFticketCorte']);
//$_SESSION['sucursalDir']="Carretera Axochiapan Izucar Col. Saragoza";
#echo '<br>$idCorte: '.$idCorte.'<br>';

  $sql="SELECT c.*, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS username,
        DATE_FORMAT(c.fechaReg,'%d-%m-%Y  %H:%m:%s') AS fechaApertura,
        DATE_FORMAT(c.fechaCierre,'%d-%m-%Y  %H:%m:%s') AS fechaCierre,
        s.nombre AS nomSuc, s.direccion, e.rfc AS rfcEmpresa,
        (c.totalDevoluciones + c.totalGastos) AS egresos,e.id AS idEmpresa,
        e.logo,de.*
      FROM cortes c
      INNER JOIN segusuarios u ON c.idUserReg = u.id
      INNER JOIN sucursales s ON c.idSucursal = s.id
      INNER JOIN empresas e ON c.idEmpresa = e.id
			INNER JOIN desglocesefectivo de ON c.id = de.idCorte
      WHERE c.id = '$idCorte'";
  $result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Caja.');
  $var=mysqli_fetch_array($result);
  #echo $sql;
  $logo = $var['logo'];
  $sucursal = $var['nomSuc'];
  $direccion = $var['direccion'];
  $idEmp = $var['idEmpresa'];
/*
  $_SESSION['logoEncabezado'] = $logo;
  $_SESSION['nSuc'] = $sucursal;
  $_SESSION['dirSuc'] = $direccion;
  $_SESSION['idEmpresa'] = $idEmp;
#*/
#/*
  $_SESSION['logoEncabezado'] = $var['logo'];
  $_SESSION['nSuc'] = $var['nomSuc'];
  $_SESSION['dirSuc'] = $var['direccion'];
  $_SESSION['idEmpresa'] = $var['idEmpresa'];
  #*/

  #error_reporting(E_ALL);
/*
    echo '<br>##################################################<br>';
    echo '<br>$id: '.$id;
    echo '<br>$id2: '.$id2;
    echo '<br>$idCorte: '.$idCorte;
    echo '<br>$_SESSION[\'logoEncabezado\']: '.$_SESSION['logoEncabezado'];
    echo '<br>$_SESSION[\'nSuc\']: '.$_SESSION['nSuc'];
    echo '<br>$_SESSION[\'dirSuc\']: '.$_SESSION['dirSuc'];
    echo '<br>$_SESSION[\'idEmpresa\']: '.$_SESSION['idEmpresa'];
    echo '<br>##################################################<br>';
  # */

  $pdf = new newPDF('P','mm',array(80,297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
  $pdf->SetMargins(5, 2 , 5);
  $pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
  $pdf->AddPage();
  ########################## comienza la cabecera ##########################

  $pdf->SetFont('Arial','',8);
  $pdf->Cell(0,5,utf8_decode('Usuario: '.$var['username']),0,1,'L',0);
  $pdf->Cell(0,5,utf8_decode('RFC: '.$var['rfcEmpresa']),0,1,'L',0);
  $pdf->Ln(2);
  $pdf->Cell(10,5,utf8_decode($var['fechaCierre']),0,0,'L',0);
  $pdf->Cell(0,5,utf8_decode('Corte: '.$idCorte),0,1,'R',0);

  ########################## Detallado de gastos ##########################

if ($var['totalGastos']>0) {
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(0,5,utf8_decode('DETALLADO DE GASTOS'),'B,T',1,'L');
    $tGasto=0;
    $sql="SELECT * FROM gastos WHERE idCorte = '$idCorte'";
    $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Pagos'.$sql);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
    $pdf->SetFont('Arial','',8);
    while ($pag= mysqli_fetch_array($datPago)) {
      $current_y = $pdf->GetY();
      $current_x = $pdf->GetX();
      $pdf->MultiCell(40,4,utf8_decode($pag['descripcion']),0,'L');
      $pdf->SetXY($current_x + 41, $current_y);
      $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
      $pdf->Ln(5);
      $tGasto += $pag['monto'];
    }

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(40,5,utf8_decode('TOTAL DE GASTOS'),'T',0,'L');
    $pdf->Cell(30,5,utf8_decode('$ '.number_format($tGasto,2,".",",")),'T',1,'R');
    $pdf->Ln(5);
    }

    #################################################################################
		############################ DETALLADO DE CREDITOS ##############################
		#################################################################################

    $tCredito=0;
    $sql="SELECT v.id,c.nombre AS nomCliente, SUM(pv.monto) AS monto
          FROM ventas v
          INNER JOIN pagosventas pv ON v.id = pv.idVenta
          INNER JOIN clientes c ON v.idCliente = c.id
          WHERE pv.idFormaPago = '7' AND v.estatus = '2' AND v.idCorte = '$idCorte'
          GROUP BY v.id
          ORDER BY v.id ASC";
    $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Creditos'.$sql);
    //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
    $cantCred = mysqli_num_rows($datPago);
    if ($cantCred > 0) {
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,5,utf8_decode('DETALLADO DE CREDITOS'),'B,T',1,'L');
        $pdf->SetFont('Arial','',8);
        while ($pag= mysqli_fetch_array($datPago)) {
          $current_y = $pdf->GetY();
          $current_x = $pdf->GetX();
          $pdf->MultiCell(40,4,utf8_decode($pag['nomCliente']),0,'L');
          $pdf->SetXY($current_x + 41, $current_y);
          $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
          $pdf->Ln(5);
          $tCredito += $pag['monto'];
        }

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(40,5,utf8_decode('TOTAL DE CREDITOS'),'T',0,'L');
        $pdf->Cell(30,5,utf8_decode('$ '.number_format($tCredito,2,".",",")),'T',1,'R');
        $pdf->Ln(5);
      }

      #################################################################################
  		########################## DETALLADO DE TRANFERENCIAS ###########################
  		#################################################################################

      $tTransfer=0;
      $sql="SELECT v.id, SUM(pv.monto) AS monto
            FROM ventas v
            INNER JOIN pagosventas pv ON v.id = pv.idVenta
            WHERE pv.idFormaPago = '3' AND v.estatus = '2' AND v.idCorte = '$idCorte'
            GROUP BY v.id
            ORDER BY v.id ASC";
      $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Transferencias'.$sql);
      //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
      $catnTrans = mysqli_num_rows($datPago);
      if ($catnTrans > 0) {
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(0,5,utf8_decode('DETALLADO DE TRANFERENCIAS'),'B,T',1,'L');
          $pdf->SetFont('Arial','',8);
          while ($pag= mysqli_fetch_array($datPago)) {
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $pdf->MultiCell(40,4,utf8_decode('Venta con Folio: '.$pag['id']),0,'L');
            $pdf->SetXY($current_x + 41, $current_y);
            $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
            $pdf->Ln(5);
            $tTransfer += $pag['monto'];
          }

          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(40,5,utf8_decode('TOTAL DE TRANFERENCIAS'),'T',0,'L');
          $pdf->Cell(30,5,utf8_decode('$ '.number_format($tTransfer,2,".",",")),'T',1,'R');
          $pdf->Ln(5);
        }

        #################################################################################
    		############################# DETALLADO DE CHEQUES ##############################
    		#################################################################################

        $tCheque=0;
        $sql="SELECT v.id, SUM(pv.monto) AS monto
              FROM ventas v
              INNER JOIN pagosventas pv ON v.id = pv.idVenta
              WHERE pv.idFormaPago = '2' AND v.estatus = '2' AND v.idCorte = '$idCorte'
              GROUP BY v.id
              ORDER BY v.id ASC";
        $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Cheques'.$sql);
        //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
        $catnCheque = mysqli_num_rows($datPago);
        if ($catnCheque > 0) {
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(0,5,utf8_decode('DETALLADO DE CHEQUES'),'B,T',1,'L');
            $pdf->SetFont('Arial','',8);
            while ($pag= mysqli_fetch_array($datPago)) {
              $current_y = $pdf->GetY();
              $current_x = $pdf->GetX();
              $pdf->MultiCell(40,4,utf8_decode('Venta con Folio: '.$pag['id']),0,'L');
              $pdf->SetXY($current_x + 41, $current_y);
              $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
              $pdf->Ln(5);
              $tCheque += $pag['monto'];
            }

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(40,5,utf8_decode('TOTAL DE CHEQUES'),'T',0,'L');
            $pdf->Cell(30,5,utf8_decode('$ '.number_format($tCheque,2,".",",")),'T',1,'R');
            $pdf->Ln(5);
          }

          #################################################################################
      		############################# DETALLADO DE BOLETAS ##############################
      		#################################################################################

          $tBoleta=0;
          $sql="SELECT v.id, SUM(pv.monto) AS monto
                    FROM ventas v
                    INNER JOIN pagosventas pv ON v.id = pv.idVenta
                    WHERE pv.idFormaPago = '6' AND v.estatus = '2' AND v.idCorte = '$idCorte'
                    GROUP BY v.id
                    ORDER BY v.id ASC";
          $datBol=mysqli_query($link,$sql) or die('Problemas al Listar Boletas'.$sql);
          //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
          $catnBol = mysqli_num_rows($datBol);
          if ($catnBol > 0) {
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(0,5,utf8_decode('DETALLADO DE BOLETAS'),'B,T',1,'L');
              $pdf->SetFont('Arial','',8);
              while ($pag= mysqli_fetch_array($datBol)) {
                $current_y = $pdf->GetY();
                $current_x = $pdf->GetX();
                $pdf->MultiCell(40,4,utf8_decode('Venta con Folio: '.$pag['id']),0,'L');
                $pdf->SetXY($current_x + 41, $current_y);
                $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
                $pdf->Ln(5);
                $tBoleta += $pag['monto'];
              }

              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(40,5,utf8_decode('TOTAL DE BOLETAS'),'T',0,'L');
              $pdf->Cell(30,5,utf8_decode('$ '.number_format($tBoleta,2,".",",")),'T',1,'R');
              $pdf->Ln(5);
            }

            #################################################################################
        		############################# DETALLADO DE PAGO DE CREDITOS #####################
        		#################################################################################

            $tPagoCred = $tPagoCredEfectivo = 0;
            $sql="SELECT v.id,cm.id AS formaPago,cm.nombre AS nomPago, SUM(pc.monto) AS monto
                    FROM ventas v
										INNER JOIN creditos c ON v.id = c.idVenta
                    INNER JOIN pagoscreditos pc ON c.id = pc.idCredito
										INNER JOIN sat_formapago cm ON pc.idFormaPago = cm.id
                    WHERE v.estatus = '2' AND v.idCorte = '$idCorte'
                    GROUP BY v.id
                    ORDER BY v.id ASC";
            $datBol=mysqli_query($link,$sql) or die('Problemas al Listar Pagos de Créditos'.$sql);
            //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
            $catnCred = mysqli_num_rows($datBol);
            if ($catnCred > 0) {
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(0,5,utf8_decode('CREDITOS PAGADOS'),'B,T',1,'L');
                $pdf->SetFont('Arial','',8);
                while ($pag= mysqli_fetch_array($datBol)) {
                  $current_y = $pdf->GetY();
                  $current_x = $pdf->GetX();
                  $pdf->MultiCell(40,4,utf8_decode('Venta: '.$pag['id'].' '.$pag['nomPago']),0,'L');
                  $pdf->SetXY($current_x + 41, $current_y);
                  $pdf->Cell(28,4,utf8_decode('$ '.number_format($pag['monto'],2,'.',',')),0,1,'R');
                  $pdf->Ln(5);
                  $tPagoCred += $pag['monto'];

                  if ($pag['formaPago'] == 1) {
                    $tPagoCredEfectivo += $pag['monto'];
                  }
                }

                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(40,5,utf8_decode('TOTAL DE PAGO'),'T',0,'L');
                $pdf->Cell(30,5,utf8_decode('$ '.number_format($tPagoCred,2,".",",")),'T',1,'R');
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
            $sqlFormaPago = "SELECT dc.*,cm.nombre
                            FROM detcortes dc
                            INNER JOIN sat_formapago cm ON dc.idFormaPago = cm.id
                            WHERE dc.idCorte = '$idCorte'
                            ORDER BY cm.id";
            $resFormaPago = mysqli_query($link,$sqlFormaPago) or die('Problemas al consultar las formas de pago, notifica a tu Administrador.');
              $texto='';
              $pdf->SetFont('Arial','',8);
              while ($lst = mysqli_fetch_array($resFormaPago)) {
                if ($lst['tipo'] == 2) {
                  $texto = 'Credito Pag ('.$lst['nombre'].')';
                } else {
                   $texto = $lst['nombre'];
                }
                if ($lst['monto'] > 0) {
                  $current_y = $pdf->GetY();
                  $current_x = $pdf->GetX();
                  $pdf->MultiCell(37,4,utf8_decode($texto),0,'L');
                  $pdf->SetXY($current_x + 37, $current_y);
                  $pdf->Cell(33,4,utf8_decode('$ '.number_format($lst['monto'],2,'.',',')),0,1,'R');
                  $pdf->Ln(2);
              }

              }

              ########################## INICIO DE CAJA ##########################
              $pdf->SetFont('Arial','',8);
              $pdf->Cell(37,5,utf8_decode('Inicio de Caja'),'B,T',0,'L');
              $pdf->Cell(33,5,utf8_decode('$ '.number_format($var['montoInicio'],2,".",",")),'B,T',1,'R');
              $pdf->Ln(2);
              if ($var['totalGastos']>0) {
                $pdf->Cell(37,5,utf8_decode('Gastos'),0,0,'L');
                $pdf->Cell(33,5,utf8_decode('$ '.number_format($var['totalGastos'],2,".",",")),0,1,'R');
              }
              if ($var['totalDevoluciones']>0) {
                $pdf->Cell(37,5,utf8_decode('Devoluciones'),0,0,'L');
                $pdf->Cell(33,5,utf8_decode('$ '.number_format($var['totalDevoluciones'],2,".",",")),0,1,'R');
              }
              if ($var['montoEfectivo']>0) {
                $pdf->Cell(37,5,utf8_decode('Total Efectivo'),0,0,'L');
                $pdf->Cell(33,5,utf8_decode('$ '.number_format($var['montoEfectivo'],2,".",",")),0,1,'R');
              }

      $ingresos = $var['totalVta'] + $tPagoCredEfectivo;


      $pdf->Cell(30,5,utf8_decode('Ingresos='),"T,B",0,'R');
      $pdf->Cell(40,5,utf8_decode('$ '.number_format($ingresos,2,".",",")),"T,B",1,'R');
      $pdf->Cell(30,5,utf8_decode('Egresos='),"T,B",0,'R');
      $pdf->Cell(40,5,utf8_decode('$ '.number_format($var['egresos'],2,".",",")),"T,B",1,'R');
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(30,5,utf8_decode('TOTAL VENTA='),"T",0,'R');
      $pdf->Cell(40,5,utf8_decode('$ '.number_format($var['totalVta'],2,".",",")),"T",1,'R');
      $pdf->Ln(5);

      ########################## DESGLOCE DE BILLETES ##########################

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(35,5,utf8_decode('DETALLE'),'B,T',0,'L');
      $pdf->Cell(15,5,utf8_decode('CANT'),'B,T',0,'C');
      $pdf->Cell(20,5,utf8_decode('MONTO'),'B,T',1,'C');
      ##########################################################################
      ############################# BILLETES ###################################
      ##########################################################################
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(35,5,utf8_decode('Billetes de $1,000'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['b1000']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['b1000']*1000),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Billetes de $500'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['b500']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['b500']*500),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Billetes de $200'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['b200']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['b200']*200),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Billetes de $100'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['b100']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['b100']*100),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Billetes de $50'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['b50']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['b50']*50),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Billetes de $20'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['b20']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['b20']*20),2,".","'")),0,1,'R');
      ##########################################################################
      ############################# MONEDAS ####################################
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Monedas de $100'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['m100']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['m100']*100),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Monedas de $20'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['m20']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['m20']*20),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Monedas de $10'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['m10']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['m10']*10),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Monedas de $5'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['m5']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['m5']*5),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Monedas de $2'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['m2']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['m2']*2),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Monedas de $1'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['m1']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['m1']*1),2,".","'")),0,1,'R');
      ##########################################################################
      $pdf->Cell(35,5,utf8_decode('Cambio'),0,0,'L');
      $pdf->Cell(15,5,utf8_decode($var['cambio']),0,0,'C');
      $pdf->Cell(20,5,utf8_decode('$ '.number_format(($var['cambio']),2,".","'")),0,1,'R');

      ########################## MONTO FINAL ###################################

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(30,5,utf8_decode('MONTO FINAL='),"T",0,'R');
      $pdf->Cell(40,5,utf8_decode('$ '.number_format($var['montoEfectivo'],2,".",",")),"T",1,'R');
      $pdf->Ln(10);

      $pdf->Cell(0,5,utf8_decode($var['username']),"T",1,'C');
      $pdf->Cell(0,5,utf8_decode('Corte de Caja'),0,1,'C');

      $pdf->Ln(2);

#$pdf->AutoPrint();

$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
      ?>
