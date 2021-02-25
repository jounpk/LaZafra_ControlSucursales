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

######### 1 es impresión de venta, 2 es para la reimpresión del Ticket #########
$idCompra=(!empty($_REQUEST['idCompra'])) ? $_REQUEST['idCompra'] : 0 ;

if (!ctype_digit($idCompra)) {
      $idCompra = 0;
    }
/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados:<br>';
print_r($_POST);
echo '<br>$idCompra: '.$idCompra;
echo '<br>';
echo "<br>###########################################################<br>";
#exit(0);
#*/
$ticket = '';
if ($idCompra == 0) {
    header('location: ../Corporativo/compras.php');
  exit(0);
}
########################## se consltan los datos de la Compra ##########################
#echo '<br>'.$idVenta;
$sql="SELECT c.id AS 'idCompra',e.logo,s.nombre AS nSuc, s.direccion AS dirSuc, s.idEmpresa, p.nombre AS nomProveedor,
CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS 'userCompra', c.fechaCompra,e.rfc, c.total,c.nota, e2.nombre AS empresaCompra
FROM compras c
INNER JOIN proveedores p ON c.idProveedor = p.id
INNER JOIN sucursales s ON c.idSucursal = s.id
INNER JOIN empresas e ON s.idEmpresa = e.id
INNER JOIN empresas e2 ON p.idEmpresa = e2.id
INNER JOIN segusuarios u ON c.idUserReg = u.id
WHERE c.id = '$idCompra'";
$result=mysqli_query($link,$sql) or die('Problemas al Consultar la Compra.');
$var=mysqli_fetch_array($result);

error_reporting(0);
$cancel = ($var['estatus']==3) ? 1 : 0 ;
$_SESSION['logoEncabezado'] = '../'.$var['logo'];
$_SESSION['nSuc'] = $var['nSuc'];
$_SESSION['dirSuc'] = $var['dirSuc'];
$_SESSION['idEmpresa'] = $var['idEmpresa'];
$rfc = $var['rfc'];
$cajero = $var['userCompra'];
$total = $var['total'];
$nota = $var['nota'];
$proveedor = $var['nomProveedor'];
$empresaCompra = $var['empresaCompra'];

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
$pdf = new newPDF('P','mm',array(80,297));                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
$pdf->SetMargins(5, 2 , 5);
$pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
$pdf->AddPage();

########################## comienza la cabecera ##########################
$pdf->Ln(2);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,5,utf8_decode('***** COMPRA *****'),0,1,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(18,4,utf8_decode('Proveedor: '),0,0,'L',0);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(48,4,utf8_decode($proveedor),0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Ln(3);
$pdf->SetFont('Arial','',8);
$pdf->Cell(18,4,utf8_decode('Comprador: '),0,0,'L',0);
$pdf->SetFont('Arial','B',9);
$pdf->MultiCell(48,4,utf8_decode($empresaCompra),0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Ln(4);
$pdf->Cell(10,5,utf8_decode('Fecha: '.$var['fechaCompra']),0,0,'L',0);
$pdf->Cell(0,5,utf8_decode('Compra: '.$var['idCompra']),0,1,'R',0);

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

		$sql="SELECT dc.*, IF(dc.idProducto > 0,p.descripcion,dc.nombreProducto) AS descripcion
                                              FROM detcompras dc
																							LEFT JOIN productos p ON dc.idProducto = p.id
                                              WHERE dc.idCompra = '$idCompra'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Compras.');
    $subTotal = $count = $nPag = $montoDev = 0;
		while ($row=mysqli_fetch_array($result))
		{
  $pdf->Row2(array(utf8_decode($row['descripcion']),utf8_decode('$'.number_format($row['costoUnitario'],2,'.',',')),number_format($row['cantidad'],0,'.',','), '$ '.number_format($row['costoUnitario']*$row['cantidad'],2,".",",")));


        $subTotal += ($row['costoUnitario'] * $row['cantidad']);

        $count++;

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
$pdf->Cell(40,5,utf8_decode('TOTAL= '),'T',0,'R');
$pdf->Cell(28,5,utf8_decode('$ '.number_format($total,2,'.',',')),'T',1,'R');
$pdf->Ln(2);
$pdf->Ln(2);
    ########################## se imprime el pie de página ##########################

          if ($cancel == 1) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',20);
            #$pdf->RotatedText(25,120,'CANCELADO',45);
            $pdf->RotatedImage('../assets/images/cancelado-260.png',5,110,60,48,10);
          }
          ########################## Se imprime el pie de ticket ##########################
          $pdf->Ln(15);
          $pdf->SetFont('Arial','',8);
          $pdf->MultiCell(70,4,utf8_decode($cajero),'T','C');
          $pdf->MultiCell(70,4,utf8_decode('Compra de Mercancía'),0,'C');
          $pdf->Ln(5);
          $pdf->SetFont('Arial','',10);
          $pdf->MultiCell(0,4,utf8_decode('NOTA: '.$nota),0,'L');
          $pdf->Ln(2);

#$pdf->AutoPrint();
$pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
unset($_SESSION['logoEncabezado']);
unset($_SESSION['nSuc']);
unset($_SESSION['dirSuc']);
unset($_SESSION['idEmpresa']);
    ?>
