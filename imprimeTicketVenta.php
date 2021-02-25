<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('include/connect.php');
require('Fpdf/tickets.php');

$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = 'imprimeTicketVenta.php';
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
//echo error_reporting(E_ALL);
//define('INCLUDE_CHECK',1);
//require "include/connect.php";
//session_start();

#$idVenta=$_REQUEST['idVenta'];
//$_SESSION['sucursalDir']="Carretera Axochiapan Izucar Col. Saragoza";

######### El tipo es para saber si es reimpresión #########
######### 1 es impresión de venta, 2 es para la reimpresión del Ticket #########
######### Si es reimpresión no aparecerá el cambio #########
$idVenta=(!empty($_REQUEST['idVenta'])) ? $_REQUEST['idVenta'] : 0 ;
$tipo=(!empty($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : 0 ;
//$_SESSION['sucursalDir']="Carretera Axochiapan Izucar Col. Saragoza";

if (!ctype_digit($idVenta)) {
      $idVenta = 0;
    }

if (!ctype_digit($tipo)) {
  $tipo = 0;
}
/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados:<br>';
print_r($_POST);
echo '<br>$idVenta: '.$idVenta;
echo '<br>$tipo: '.$tipo;
echo '<br>';
echo "<br>###########################################################<br>";
exit(0);
#*/
$ticket = '';
if ($tipo == 0 && $idVenta == 0) {
  echo "<script>parent.window.close();</script>";
exit(0);
} elseif ($idVenta == 0) {
  if ($tipo == 1) {
    header('location: venta.php');
  } else{
    header('location: ventaEspecial.php');
  }
  exit(0);
}
########################## se consltan los datos de la venta ##########################
#echo '<br>'.$idVenta;
$sql="SELECT vta.*, cli.nombre AS cliente, DATE_FORMAT(vta.fechaReg, '%d-%m-%Y %H:%i:%s') AS fechaHora, mps.anchoLogo,mps.logo, scs.nombre AS nomSuc, scs.direccion, mps.rfc,
      CONCAT(usu. nombre,' ',usu.appat,' ',usu.apmat) as Cajero, SUM(pgvn.monto) AS boletas,mps.id AS idEmpresa
FROM ventas vta
INNER JOIN sucursales scs ON vta.idSucursal = scs.id
INNER JOIN empresas mps ON scs.idEmpresa = mps.id
INNER JOIN segusuarios usu ON vta.idUserReg = usu.id
INNER JOIN pagosventas pgvn ON vta.id = pgvn.idVenta
LEFT JOIN clientes cli ON vta.idCliente=cli.id
WHERE vta.id = '$idVenta'";

$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
$var=mysqli_fetch_array($result);

error_reporting(0);
$cancel = ($var['estatus']==3) ? 1 : 0 ;
$cancel = ($var['estatus']==5) ? 1 : $cancel ;
$ancho = $var['anchoLogo']*1.75;
$_SESSION['logoEncabezado'] = $var['logo'];
$_SESSION['nSuc'] = $var['nomSuc'];
$_SESSION['dirSuc'] = $var['direccion'];
$_SESSION['idEmpresa'] = $var['idEmpresa'];
$rfc = $var['rfc'];
$cajero = $var['Cajero'];
#$total = $var['total'];
$clienteOp = 'Público en General';
$condicion1 = ($var['idCliente']==0) ? $clienteOp : $var['cliente'] ;


$pdf = new newPDF('P','mm',array(80,297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
$pdf->SetMargins(5, 2 , 5);
$pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
$pdf->AddPage();

########################## comienza la cabecera ##########################
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,5,utf8_decode('Cliente: '.$condicion1),0,1,'L',0);
$pdf->Cell(0,5,utf8_decode('RFC: '.$rfc),0,1,'L',0);
$pdf->Ln(2);
if ($cancel == 1) {
  $pdf->SetFont('Arial','B',30);
  $pdf->SetTextColor(255,192,203);
  $pdf->RotatedText(10,140,'C A N C E L A D O',45);
}
$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(10,5,utf8_decode($var['fechaHora']),0,0,'L',0);
$pdf->Cell(0,5,utf8_decode('Folio: '.$var['id']),0,1,'R',0);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(27,5,utf8_decode('DESCRIPCION'),'B,T',0,'L');
$pdf->Cell(15,5,utf8_decode('PRECIO'),'B,T',0,'L');
$pdf->Cell(10,5,utf8_decode('CANT'),'B,T',0,'L');
$pdf->Cell(18,5,utf8_decode('SUBTOTAL'),'B,T',1,'L');
########################## finaliza la cabecera ##########################
    ########################## se imprimen los productos, al igual que sus devoluciones si es que existen ##########################
$pdf->SetWidths(array(27,15,10,18));          //Permite colocar el ancho de las columnas, el alto es automatico
$pdf->SetAligns(array('L','R','R','R'));    //Permite centrar las columnas del array
$pdf->SetFont('Arial','',8);

		$sql="SELECT detv.*, prod.descripcion
					FROM detventas detv
					INNER JOIN productos prod ON detv.idProducto=prod.id
					WHERE detv.idVenta = '$idVenta'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
    $subTotal = $montoDev = $cuentaFilas = $nPag = 0;
    //print_r(mysqli_fetch_array($result));
    
		while ($row=mysqli_fetch_array($result))
		{
     
      ++$cuentaFilas;
  $pdf->Row2(array(utf8_decode($row['descripcion']),utf8_decode('$'.number_format($row['precioVenta'],2,'.',',')),number_format($row['cantidad'],0,'.',','), '$ '.number_format($row['precioVenta']*$row['cantidad'],2,".",",")));
  
  if ($row['cantCancel'] > 0) {
    $pdf->SetFont('Arial','B',30);
    $pdf->SetTextColor(255,192,203);
    $pdf->RotatedText(15,150,'D E V U E L T O',45);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(0,0,utf8_decode(''),1,0,'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(0,0,utf8_decode('DEVOLUCIÓN'),0,0,'C');
    $pdf->Ln(4);
    $pdf->SetFont('Arial','',8);
    $pdf->Row2(array(utf8_decode($row['descripcion']),'',$row['cantCancel'], '-$ '.number_format($row['precioVenta']*$row['cantCancel'],2,".",",")));
    $pdf->Cell(0,0,utf8_decode(''),1,0,'L');
  
    $montoDev += ($row['precioVenta'] * $row['cantCancel']);
    
  }

        $subTotal += ($row['precioVenta'] * $row['cantidad']);

        if ($cuentaFilas == 17 && $nPag < 1) {
          $nPag++;
          $pdf->AddPage();
          $pdf->Ln(5);
          $cuentaFilas = 0;
        } elseif ($cuentaFilas == 22 && $nPag > 0) {
          $nPag++;
          $pdf->AddPage();
          $pdf->Ln(5);
          $cuentaFilas = 0;
        }else {
          $pdf->Ln(2);
        }
		}
    $subTotal = $subTotal - $montoDev;

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

$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,5,utf8_decode('TOTAL:'),'T',0,'R');
$pdf->Cell(28,5,utf8_decode('$ '.number_format($subTotal,2,'.',',')),'T',1,'R');
$pdf->Ln(2);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(40,5,utf8_decode('SU PAGO'),0,0,'R');
$pdf->Cell(28,5,'',0,1,'L');
$pdf->SetFont('Arial','B',9);
$pdf->Ln(2);
    ########################## se imprimen las formas de pago ##########################
      $sqlFormaPago = "SELECT cmp.id,cmp.nombre, bnk.nombreCorto AS nomBanco, pv.folio,pv.montoPagado AS montoPago, md.monto AS montoDevuelto, pv.idFormaPago AS formaDePago,a.contador
                      FROM pagosventas pv
                      INNER JOIN sat_formapago cmp ON pv.idFormaPago = cmp.id
											LEFT JOIN catbancos bnk ON pv.idBanco = bnk.id
											LEFT JOIN devoluciones d ON pv.idVenta = d.idVenta
											LEFT JOIN montosdevueltos md ON d.id = md.idDevolucion AND cmp.id = md.idFormaPago
											LEFT JOIN (SELECT IF(COUNT(id) > 0,COUNT(id),0) AS contador, idVenta FROM pagosventas WHERE idFormaPago = 1 AND idVenta = '$idVenta') a ON pv.idVenta = a.idVenta
                      WHERE pv.idVenta = '$idVenta'";
      $resFormaPago =mysqli_query($link,$sqlFormaPago) or die('Problemas al consultar los pagos, notifica a tu Administrador.');
      $sumMonto = $dif = $cambio = $sumEfectivo = $hayCredito = $sumaCredito = 0;
      $conta = 1;

      while ($pagos = mysqli_fetch_array($resFormaPago)) {
        if ($pagos['id'] == 1) {
          $dif++;
        }
        if ($pagos['montoDevuelto'] > 0) {
          $pagoCliente = $pagos['montoPago']; - $pagos['montoDevuelto'];
        } else {
          $pagoCliente = $pagos['montoPago'];
        }
        $idFormaPago = $pagos['formaDePago'];

        if ($idFormaPago == 7) {
          $hayCredito = 1;
          $sumaCredito += $pagoCliente;
        }

        if ($pagoCliente > 0){
          switch ($idFormaPago) {
            case '1':
              $textoPago = $pagos['nombre'];
              break;
            case '2':
              $textoPago = $pagos['nombre'].' '.$pagos['nomBanco'].' Folio: #'.$pagos['folio'];
              break;
            case '3':
              $textoPago = $pagos['nombre'].' '.$pagos['nomBanco'].' Folio: #'.$pagos['folio'];
              break;
            case '4':
              $textoPago = $pagos['nombre'].' '.$pagos['nomBanco'].'  ******'.$pagos['folio'];
              break;
            case '5':
              $textoPago = $pagos['nombre'].' '.$pagos['nomBanco'].'  ******'.$pagos['folio'];
              break;
            case '6':
              $textoPago = $pagos['nombre'].' '.$pagos['nomBanco'].' Folio: #'.$pagos['folio'];
              break;

            default:
              $textoPago = $pagos['nombre'];
              break;
          }
            if ($idFormaPago == 1 && $conta < $pagos['contador']) {
              $sumEfectivo += $pagoCliente;
              $sumMonto += $pagoCliente;
              $conta++;
            } elseif ($idFormaPago == 1 && $conta == $pagos['contador']) {
              $conta++;
              $sumEfectivo += $pagoCliente;
              $sumMonto += $pagoCliente;
              $pdf->SetFont('Arial','B',9);
              $current_y = $pdf->GetY();
              $current_x = $pdf->GetX();
              $pdf->MultiCell(43,3,utf8_decode($textoPago.':'),0,'R');
              $pdf->SetXY($current_x + 43, $current_y);
              $pdf->Cell(25,3,utf8_decode('$ '.number_format($sumEfectivo,2,'.',',')),0,1,'R');
              $pdf->Ln(6);
              $cuentaFilas++;
            } else {

              $pdf->SetFont('Arial','B',9);
              $current_y = $pdf->GetY();
              $current_x = $pdf->GetX();
              $pdf->MultiCell(43,3,utf8_decode($textoPago),0,'R');
              $pdf->SetXY($current_x + 43, $current_y);
              $pdf->Cell(25,3,utf8_decode('$ '.number_format($pagoCliente,2,'.',',')),0,1,'R');
              $pdf->Ln(6);
              $sumMonto += $pagoCliente;
              $cuentaFilas++;

            }
        }

        if ($cuentaFilas == 17 && $nPag < 1) {
          $nPag++;
          $pdf->AddPage();
          $pdf->Ln(5);
          $cuentaFilas = 0;
        } elseif ($cuentaFilas == 22 && $nPag > 0) {
          $nPag++;
          $pdf->AddPage();
          $pdf->Ln(5);
          $cuentaFilas = 0;
        }else {
          $pdf->Ln(2);
        }
      }
  if ($dif > 0) {
    $cambio =  $sumMonto - $subTotal;
    #$pdf->Cell(0,5,utf8_decode('$sumMonto: '.$sumMonto.', $total: '.$subTotal),0,0,'R');
    $pdf->Cell(43,5,utf8_decode('Cambio: '),0,0,'R');
    $pdf->Cell(25,5,utf8_decode('$ '.number_format($cambio,2,'.',',')),'T',1,'R');
    $cuentaFilas++;
          }
          
          ########################## Se imprime el pie de ticket ##########################
          $pdf->Ln(5);
          if ($hayCredito == 1) {
            $pdf->Ln(15);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,5,utf8_decode($condicion1),'T',1,'C',0);
            $pdf->Cell(0,5,utf8_decode('Compra a Crédito: $'.number_format($sumaCredito,2,'.',',')),0,1,'C',0);

            $cuentaFilas+=2;

            if ($cuentaFilas == 17 && $nPag < 1) {
              $nPag++;
              $pdf->AddPage();
              $pdf->Ln(5);
              $cuentaFilas = 0;
            } elseif ($cuentaFilas == 22 && $nPag > 0) {
              $nPag++;
              $pdf->AddPage();
              $pdf->Ln(5);
              $cuentaFilas = 0;
            }else {
              $pdf->Ln(2);
            }
            $pdf->Ln(5);
          }

          $pdf->SetFont('Arial','',8);
          $pdf->Cell(0,5,utf8_decode('Cajero:'.$cajero),0,1,'L',0);
          $pdf->Ln(5);
          $cuentaFilas++;
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(0,5,utf8_decode('COD FACTURACION: '.base64_encode($idVenta)),'T,B',1,'C');
          $pdf->Ln(2);
          $cuentaFilas++;

          if ($cuentaFilas == 17 && $nPag < 1) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          } elseif ($cuentaFilas == 22 && $nPag > 0) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          }else {
            $pdf->Ln(2);
          }

          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(0,5,utf8_decode('Link para facturación:'),0,1,'C');
          $cuentaFilas++;
          $pdf->Cell(0,5,utf8_decode('https://lazafra.com.mx/facturacion'),0,1,'C');
          #$pdf->Ln(2);
          $cuentaFilas++;
          if ($cuentaFilas == 17 && $nPag < 1) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          } elseif ($cuentaFilas == 22 && $nPag > 0) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          }else {
            $pdf->Ln(2);
          }

          $pdf->Cell(0,5,utf8_decode('Para emitir su factura en línea'),0,1,'C');
          $cuentaFilas++;
          $pdf->Cell(0,5,utf8_decode('sólo cuenta con 72 horas'),0,1,'C');

          $cuentaFilas++;
          if ($cuentaFilas == 17 && $nPag < 1) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          } elseif ($cuentaFilas == 22 && $nPag > 0) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          }else {
            $pdf->Ln(2);
          }

          $pdf->Ln(5);
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(0,5,utf8_decode('*** GRACIAS POR SU COMPRA ***'),0,1,'C');
          $cuentaFilas++;
          $pdf->Cell(0,5,utf8_decode('NOTA: Salida la mercancia'),0,1,'C');

          $cuentaFilas++;
          if ($cuentaFilas == 17 && $nPag < 1) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          } elseif ($cuentaFilas == 22 && $nPag > 0) {
            $nPag++;
            $pdf->AddPage();
            $pdf->Ln(5);
            $cuentaFilas = 0;
          }else {
            $pdf->Ln(2);
          }
          $pdf->Cell(0,5,utf8_decode('no hay cambios ni devoluciones.'),0,1,'C');
          $cuentaFilas++;
          $pdf->Ln(2);

         

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
    ?>
