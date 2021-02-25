<?php

function cotizacion($ident){

    require('include/connect.php');
    #require('plantilla.php');
    require('Fpdf/encabezado.php');

    $sql="SELECT dc.*, prod.descripcion
					FROM detcotizaciones dc
					INNER JOIN productos prod ON dc.idProducto = prod.id
					WHERE dc.idCotizacion = '$ident'";
		$resDetCot=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');

    $sqlCon = "SELECT c.*, YEAR(c.fechaReg) AS anio, MONTH(c.fechaReg) AS mes, DAY(c.fechaReg) AS dia, DAYOFWEEK(c.fechaReg) AS nDia,
              mps.anchoLogo,mps.logo, scs.nombre AS nomSuc, scs.direccion, mps.rfc,scs.idEmpresa,
                    CONCAT(usu. nombre,' ',usu.appat) as Cajero
              FROM cotizaciones c
              INNER JOIN sucursales scs ON c.idSucursal = scs.id
              INNER JOIN empresas mps ON scs.idEmpresa = mps.id
              INNER JOIN segusuarios usu ON c.idUserReg = usu.id
              WHERE c.id = '$ident' LIMIT 1";
    $resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la cotización, notifica a tu Administrador');
    $var = mysqli_fetch_array($resCon);
    $folio = $var['folio'];
    $cliente = $var['cliente'];
    $cajero = $var['Cajero'];
    $idEmpresa = $var['idEmpresa'];
    #echo $folio;

    $sqlMarcaAgua="SELECT c.*, DATE_FORMAT(c.fechaReg, '%d-%m-%Y %H:%i:%s') AS fechaHora, mps.anchoLogo,mps.logo, scs.nombre AS nomSuc, scs.direccion, mps.rfc, 
       IF( c.tipo = 2, CONCAT( c.nameCliente, ' (Público en General)' ), cl.nombre ) AS cliente,
      CONCAT(usu. nombre,' ',usu.appat,' ',usu.apmat) as Cajero, mps.id AS idEmp,
        IF(DATE_ADD(c.fechaAut,INTERVAL c.cantPeriodo DAY)>=NOW(), '0', '1') AS cancel
       FROM cotizaciones c
          LEFT JOIN clientes cl ON c.idCliente = cl.id
          INNER JOIN sucursales scs ON c.idSucursal = scs.id
          INNER JOIN empresas mps ON scs.idEmpresa = mps.id
          INNER JOIN segusuarios usu ON c.idUserReg = usu.id
          WHERE c.id = '$ident'";
 
      $resMarcaAgua = mysqli_query($link,$sqlMarcaAgua) or die('Problemas al consultar la cotización, notifica a tu Administrador');
      $varMarcaAgua = mysqli_fetch_array($resMarcaAgua);
      $cancelar = $varMarcaAgua['cancel'];
      $cliente = $varMarcaAgua['cliente'];
      $diasExpirar=$varMarcaAgua['cantPeriodo'];



    $_SESSION['logoEncabezado'] = $var['logo'];
    $_SESSION['nSuc'] = $var['nomSuc'];
    $_SESSION['dirSuc'] = $var['direccion'];
    $_SESSION['folio'] = $var['folio'];
    $pdf = new newPDF('P','mm','Letter');                   //Se crea el objeto con orientación de la Hoja y medidas 'P' -> 'Portrait' (vertical) y 'L' -> 'Landscape' (horizontal)
    $pdf->SetMargins(15, 5 , 15);
    $pdf->AliasNbPages(); //Permite mostrar el total de paginas del documento
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
    $pdf->SetWidths(array(40,80,90));          //Permite colocar el ancho de las columnas, el alto es automatico
    $pdf->SetAligns(array('L','L','C'));    //Permite centrar las columnas del array
    #$pdf->Row2(array(utf8_decode('Fecha: '), utf8_decode($nDia.', '.$dia.' de '.$mes.' de '.$anio),'', '',''));
    $pdf->Row2(array(utf8_decode('Fecha: '), utf8_decode($nDia.', '.$dia.' de '.$mes.' de '.$anio),'FOLIO:'));
    $pdf->SetWidths(array(40,80,90));          //Permite colocar el ancho de las columnas, el alto es automatico
    $pdf->Row2(array(utf8_decode('Cliente: '), utf8_decode($cliente),utf8_decode($folio)));
    $pdf->SetWidths(array(40,80,90));          //Permite colocar el ancho de las columnas, el alto es automatico
    $pdf->Ln(10);

    //Validacion para poner marca de agua en PDF
if($cancelar==1){
  $pdf->SetFont('Arial','B',55);
  $pdf->SetTextColor(255,192,203);
  $pdf->RotatedText(50,190,'C A N C E L A D O',45);
 }
########################## Se carga la tabla ##########################
$pdf->SetFillColor(254,254,254);                                                                  //para que puedan aparecer los acentos y caracteres especiales del idioma
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,5,utf8_decode('Detalle de Productos'),0,1,'C',0);
$pdf->SetAligns(array('C','C','C','C','C'));    //Permite centrar las columnas del array
$pdf->SetTextColor(0,0,0);
#$pdf->SetDrawColor(57,164,17);                    //Coloca el color del borde de la tabla
$pdf->SetFillColor(17,51,75);                     //Coloca el color del fondo de la celda
$pdf->SetTextColor(255,255,255);                      //coloca el color del texto en formato RGB
$pdf->SetFont('Arial','B',10);                      //Coloca el formato al texto (familia, estilo y altura)
#$pdf->Cell($a,13,'',0,1,'C');
$a=25;$b=20;$c=85;$d=22;$e=22;$f=40;;       //Declaro el ancho de las columnas de la tabla
$pdf->SetWidths(array($b,$c,$d,$e,$f));          //Permite colocar el ancho de las columnas, el alto es automatico
$pdf->Cell(20,5,utf8_decode('CANTIDAD'),0,0,'C',1);
$pdf->Cell(85,5,utf8_decode('PRODUCTO'),0,0,'L',1);
$pdf->Cell(22,5,utf8_decode('FTO.PR.'),0,0,'C',1);
$pdf->Cell(22,5,utf8_decode('PRECIO'),0,0,'C',1);
$pdf->Cell(40,5,utf8_decode('SUBTOTAL'),0,0,'C',1);
$pdf->Ln(5);

#$pdf->Row(array('#','DESCRIPCION','CANTIDAD','P/U','SUBTOTAL'));       //Agrego 6 columnas para los títulos, se utiliza el 'utf8_decode'
$pdf->SetFillColor(254,254,254);                                                                  //para que puedan aparecer los acentos y caracteres especiales del idioma
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',10);
$pdf->SetAligns(array('C','L','R','R','R'));    //Permite centrar las columnas del array
$cont = 0;
$tipoPfrecio;
$pdf->SetWidths(array($b,$c,$d,$e,$f));          //Permite colocar el ancho de las columnas, el alto es automatico
$subTotal = $precio = 0;
while ($eq = $resDetCot->fetch_assoc()) {
  $precio = ($eq['precioCoti'] * $eq['cantidad']);
  if($eq['asignaPrecio']==1){
   $tipoPrecio="PB";
  }else{
    $tipoPrecio="PPer";
  }
  $subTotal += $precio;
  $pdf->Row(array(utf8_decode($eq['cantidad']),utf8_decode($eq['descripcion']),utf8_decode($tipoPrecio),utf8_decode('$ '.number_format($eq['precioCoti'],2,'.',',')), utf8_decode('$ '.number_format($precio,2,'.',','))));
}
$pdf->SetFillColor(254,254,254);                       //Coloca el color del fondo de la celda
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,5,utf8_decode(''),0,0,'C');
$pdf->Cell(85,5,utf8_decode(''),0,0,'C');
$pdf->Cell(22,5,utf8_decode(''),0,0,'C');
$pdf->Cell(22,5,utf8_decode('TOTAL = '),0,0,'C');
#$pdf->SetTextColor(255,255,255);
$pdf->Cell(40,5,utf8_decode('$'.number_format($subTotal,2,'.',',')),1,1,'R',1);
$pdf->Ln(10);
$pdf->SetWidths(array(200));          //Permite colocar el ancho de las columnas, el alto es automatico
$pdf->SetAligns(array('L'));    //Permite centrar las columnas del array
 
$sqlCuentas = "SELECT b.nombreCorto AS nomBanco, c.noCuenta,c.claBe
FROM cuentasbancarias c
INNER JOIN catbancos b ON c.idClaveBanco = b.id
WHERE c.estatus = 1 AND c.imprimeCot = 1 AND c.idEmpresa = '$idEmpresa'
ORDER BY b.nombreCorto ASC";
$resCuentas = mysqli_query($link,$sqlCuentas) or die('Problemas al consultar las cuentas bancarias, notifica a tu Administrador.');
$cantCuentas = mysqli_num_rows($resCuentas);
$cuentas = mysqli_fetch_array($resCuentas);
$nomBanco=$cuentas["nomBanco"];
$noCuenta=$cuentas["noCuenta"];
$claveCuenta=$cuentas["claBe"];

if ($cantCuentas > 0) {
  $pdf->Ln(100);
  $pdf->SetWidths(array(25,50,50,70,50));          //Permite colocar el ancho de las columnas, el alto es automatico
  $pdf->SetAligns(array('L','L','R','L','L'));    //Permite centrar las columnas del array
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,5,utf8_decode('DATOS BANCARIOS'),0,1,'L',0);
  $pdf->Ln(2);
  $pdf->SetFont('Arial','',12);
  #$pdf->Row2(array(utf8_decode('Fecha: '), utf8_decode($nDia.', '.$dia.' de '.$mes.' de '.$anio),'', '',''));
  $pdf->Row2(array(utf8_decode('BANCO: '),utf8_decode($nomBanco),'','',''));
  $pdf->SetWidths(array(25,50,50,70,50));          //Permite colocar el ancho de las columnas, el alto es automatico
  $pdf->Row2(array(utf8_decode('CUENTA: '), utf8_decode($noCuenta),utf8_decode('Generó:'), utf8_decode($cajero),''));
  $pdf->SetWidths(array(25,50,50,70,50)); 
  $pdf->Row2(array(utf8_decode('CLABE: '), utf8_decode($claveCuenta),'', '',''));
  $pdf->SetWidths(array(25,50,50,70,50));          //Permite colocar el ancho de las columnas, el alto es automatico 
}
$pdf->Ln(10);
$pdf->SetWidths(array(190));          //Permite colocar el ancho de las columnas, el alto es automatico
$pdf->SetAligns(array('C'));    //Permite centrar las columnas del array
$pdf->SetFont('Arial','B',10);
$pdf->Row2(array(utf8_decode('NOTA: Precios sujetos a cambios sin previo aviso.')));
$pdf->Row2(array(utf8_decode('Dias por expirar: '.$diasExpirar. ' dias')));
$pdf->SetWidths(array(190));


########################## Se imprime el documento ##########################
    $pdf->Output();           //Imprime el documento, si no se encuentra entonces no mostrará nada

    unset($_SESSION['logoEncabezado']);
    unset($_SESSION['nSuc']);
    unset($_SESSION['dirSuc']);
}
 ?>
