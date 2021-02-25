<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

    $idCotizacion = (!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0 ;
    $pagina = (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != '') ? $_REQUEST['pagina'] : '' ;
    $alerta = (isset($_REQUEST['alerta']) && $_REQUEST['alerta'] != '') ? $_REQUEST['alerta'] : '' ;

if (file_exists('../doctos/Cotizacion/cotizacion.pdf')) {
    if (!unlink("../doctos/Cotizacion/cotizacion.pdf")) { }
}
    $dir = '../doctos/Cotizacion/';
    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }


        require('../include/connect.php');
        #require('plantilla.php');
        require('../Fpdf/encabezado.php');
        $dir = '../doctos/Cotizacion/';
        $sql="SELECT dc.*, prod.descripcion
    					FROM detcotizaciones dc
    					INNER JOIN productos prod ON dc.idProducto = prod.id
    					WHERE dc.idCotizacion = '$idCotizacion'";
    		$resDetCot=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');

        $sqlCon = "SELECT c.*, YEAR(c.fechaReg) AS anio, MONTH(c.fechaReg) AS mes, DAY(c.fechaReg) AS dia, DAYOFWEEK(c.fechaReg) AS nDia,
                  mps.anchoLogo,mps.logo, scs.nombre AS nomSuc, scs.direccion, mps.rfc,
                        CONCAT(usu. nombre,' ',usu.appat) as Cajero
                  FROM cotizaciones c
                  INNER JOIN sucursales scs ON c.idSucursal = scs.id
                  INNER JOIN empresas mps ON scs.idEmpresa = mps.id
                  INNER JOIN segusuarios usu ON c.idUserReg = usu.id
                  WHERE c.id = '$idCotizacion' LIMIT 1";
        $resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la cotización, notifica a tu Administrador');
        $var = mysqli_fetch_array($resCon);
        $folio = $var['folio'];
        $cliente = $var['cliente'];
        $cajero = $var['Cajero'];
        #echo $folio;

        $_SESSION['logoEncabezado'] = '../'.$var['logo'];
        $_SESSION['nSuc'] = $var['nomSuc'];
        $_SESSION['dirSuc'] = $var['direccion'];
        $pdf = new newPDF('P','mm','Letter');                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
        $pdf->SetMargins(15, 5 , 15);
        $pdf->AliasNbPages();                             //Permite mostrar el total de paginas del documento
        $pdf->AddPage();                                  //agrega la pagina de inicio

    ########################## Se obtiene el mes ##########################
        switch ($var['mes']) {
          case 1: $mes = 'Enero';      break;
          case 2: $mes = 'Febrero';    break;
          case 3: $mes = 'Marzo';      break;
          case 4: $mes = 'Abril';      break;
          case 5: $mes = 'Mayo';       break;
          case 6: $mes = 'Junio';      break;
          case 7: $mes = 'Julio';      break;
          case 8: $mes = 'Agosto';     break;
          case 9: $mes = 'Septiembre'; break;
          case 10: $mes = 'Octubre';    break;
          case 11: $mes = 'Noviembre';  break;
          default:   $mes = 'Diciembre';  break;
        }
        ########################## Se obtiene el día ##########################
        switch ($var['nDia']) {
          case 2:     $nDia = 'Lunes';       break;
          case 3:     $nDia = 'Martes';      break;
          case 4:     $nDia = 'Miércoles';   break;
          case 5:     $nDia = 'Jueves';      break;
          case 6:     $nDia = 'Viernes';     break;
          case 7:     $nDia = 'Sábado';      break;
          default:    $nDia = 'Domingo';     break;
        }
        $dia = str_pad($var['dia'], 2, "0", STR_PAD_LEFT);
        $anio = $var['anio'];

        $pdf->Ln(3);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetWidths(array(25,77,10,25,80));          //Permite colocar el ancho de las columnas, el alto es automatico
        $pdf->SetAligns(array('J','R','','J','J'));    //Permite centrar las columnas del array
        #$pdf->Row2(array(utf8_decode('Fecha: '), utf8_decode($nDia.', '.$dia.' de '.$mes.' de '.$anio),'', '',''));
        $pdf->Row2(array(utf8_decode('Fecha: '), utf8_decode($nDia.', '.$dia.' de '.$mes.' de '.$anio),'', utf8_decode('Folio: '), utf8_decode($folio)));
        $pdf->SetWidths(array(25,77,10,25,50));          //Permite colocar el ancho de las columnas, el alto es automatico
        $pdf->Row2(array(utf8_decode('Cliente: '), utf8_decode($cliente),'', utf8_decode('Generó: '), utf8_decode($cajero)));
        $pdf->SetWidths(array(35,82,40,45,50));          //Permite colocar el ancho de las columnas, el alto es automatico
        $pdf->SetAligns(array('J','R','','J','R'));    //Permite centrar las columnas del array

        $pdf->Ln(10);
    ########################## Se carga la tabla ##########################
    $pdf->SetFillColor(254,254,254);                                                                  //para que puedan aparecer los acentos y caracteres especiales del idioma
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,utf8_decode('Detalle de Productos'),0,1,'C',1);
    $pdf->SetAligns(array('C','C','C','C','C'));    //Permite centrar las columnas del array
    $pdf->SetTextColor(0,0,0);
    #$pdf->SetDrawColor(57,164,17);                    //Coloca el color del borde de la tabla
    $pdf->SetFillColor(17,51,75);                     //Coloca el color del fondo de la celda
    $pdf->SetTextColor(255,255,255);                      //coloca el color del texto en formato RGB
    $pdf->SetFont('Arial','B',10);                      //Coloca el formato al texto (familia, estilo y altura)
    #$pdf->Cell($a,13,'',0,1,'C');
    $a=25;$b=10;$c=75;$d=35;$e=25;$f=40;;       //Declaro el ancho de las columnas de la tabla
    $pdf->SetWidths(array($b,$c,$d,$e,$f));          //Permite colocar el ancho de las columnas, el alto es automatico
    $pdf->Cell(10,5,utf8_decode('#'),0,0,'C',1);
    $pdf->Cell(75,5,utf8_decode('DESCRIPCION'),0,0,'L',1);
    $pdf->Cell(35,5,utf8_decode('PRECIO'),0,0,'C',1);
    $pdf->Cell(25,5,utf8_decode('CANTIDAD'),0,0,'C',1);
    $pdf->Cell(40,5,utf8_decode('SUBTOTAL'),0,0,'C',1);
    $pdf->Ln(5);
    #$pdf->Row(array('#','DESCRIPCION','CANTIDAD','P/U','SUBTOTAL'));       //Agrego 6 columnas para los títulos, se utiliza el 'utf8_decode'
    $pdf->SetFillColor(254,254,254);                                                                  //para que puedan aparecer los acentos y caracteres especiales del idioma
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','',10);
    $pdf->SetAligns(array('C','L','R','R','R'));    //Permite centrar las columnas del array
    $cont = 0;
    $pdf->SetWidths(array($b,$c,$d,$e,$f));          //Permite colocar el ancho de las columnas, el alto es automatico
    $subTotal = $precio = 0;
    while ($eq = $resDetCot->fetch_assoc()) {
      $precio = ($eq['precioVenta'] * $eq['cantidad']);
      $subTotal += $precio;
      $pdf->Row(array(++$cont,utf8_decode($eq['descripcion']),utf8_decode('$ '.number_format($eq['precioVenta'],2,'.',',')), utf8_decode($eq['cantidad']), utf8_decode('$ '.number_format($precio,2,'.',','))));
    }
    $pdf->SetFillColor(254,254,254);                       //Coloca el color del fondo de la celda
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(10,5,utf8_decode(''),0,0,'C');
    $pdf->Cell(75,5,utf8_decode(''),0,0,'C');
    $pdf->Cell(35,5,utf8_decode(''),0,0,'C');
    $pdf->Cell(25,5,utf8_decode('TOTAL = '),0,0,'C');
    #$pdf->SetTextColor(255,255,255);
    $pdf->Cell(40,5,utf8_decode('$'.number_format($subTotal,2,'.',',')),1,1,'R',1);
    $pdf->Ln(10);
    $pdf->SetWidths(array(200));          //Permite colocar el ancho de las columnas, el alto es automatico
    $pdf->SetAligns(array('L'));    //Permite centrar las columnas del array

    $pdf->Ln(10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(254,254,254);
    $pdf->Cell(0,5,utf8_decode('NOTA: Los precios tienen una validez de 15 días desde su fecha de emisión.'),0,0,'C',1);


    ########################## Se imprime el documento ##########################
      #  $pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada
       $pdf->Output('F', '../doctos/Cotizacion/cotizacion.pdf');           //Imprime el documento, si no se encuentra entonces no mostrará nada
       header('location: enviaPDFcotizacion.php?idCotizacion='.$idCotizacion.'&pagina='.$pagina.'&alerta='.$alerta);

       unset($_SESSION['logoEncabezado']);
       unset($_SESSION['nSuc']);
       unset($_SESSION['dirSuc']);
       ?>
