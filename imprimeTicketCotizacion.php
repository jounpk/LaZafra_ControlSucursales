<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = 'imprimeTicketCotizacion.php';
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];

//define('INCLUDE_CHECK',1);
//require "include/connect.php";
//session_start();

#$idCotizacion=$_REQUEST['$idCotizacion'];
//$_SESSION['sucursalDir']="Carretera Axochiapan Izucar Col. Saragoza";

######### El tipo es para saber si es reimpresión #########
######### 1 es impresión de venta, 2 es para la reimpresión del Ticket #########
######### Si es reimpresión no aparecerá el cambio #########
$idCotizacion=(!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0 ;
//$_SESSION['sucursalDir']="Carretera Axochiapan Izucar Col. Saragoza";

if (!ctype_digit($idCotizacion)) {
      $idCotizacion = 0;
    }


/*
echo "<br>###########################################################<br>";
echo '<br>Datos enviados:<br>';
print_r($_POST);
echo '<br>$idCotizacion: '.$idCotizacion;
echo '<br>';
echo "<br>###########################################################<br>";
exit(0);
#*/

if ($idCotizacion < 1) {
  echo "<script languaje='javascript' type='text/javascript'>parent.window.close();</script>";
  echo "<script>window.close();</script>";
  echo 'cierra pesataña';
exit(0);
}
########################## se consltan los datos de la venta ##########################
#echo '<br>'.$idCotizacion;
$sql="SELECT c.*, DATE_FORMAT(c.fechaReg, '%d-%m-%Y %H:%i:%s') AS fechaHora, mps.anchoLogo,mps.logo, scs.nombre AS nomSuc, scs.direccion, mps.rfc, 
IF( c.tipo = 2, CONCAT( c.nameCliente, ' <span><b>(Público en General)</b></span>' ), cl.nombre ) AS cliente,
      CONCAT(usu. nombre,' ',usu.appat,' ',usu.apmat) as Cajero
FROM cotizaciones c
LEFT JOIN clientes cl ON c.idCliente = cl.id
INNER JOIN sucursales scs ON c.idSucursal = scs.id
INNER JOIN empresas mps ON scs.idEmpresa = mps.id
INNER JOIN segusuarios usu ON c.idUserReg = usu.id
WHERE c.id = '$idCotizacion'";
echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
$var=mysqli_fetch_array($result);

error_reporting(0);
$cancel = ($var['estatus']==4 || $var['estatus']==5) ? 'background:url(\'assets/images/cancelado-260.png\') no-repeat bottom center;' : '' ;
$ancho = $var['anchoLogo']*1.75;
$logo = $var['logo'];
$dir = $var['direccion'];
$nomSuc = $var['nomSuc'];
$rfc = $var['rfc'];
$cajero = $var['Cajero'];
$total = $var['total'];
$clienteOp = $var['cliente'];;
########################## se realiza la cabecera ##########################
	?>
	<table border="0" style="font-size:18px; <?=$cancel;?>" width="260px" padding="5px">
		<tr>
			<th colspan="4" align="center"><img class="img-circle" src="<?=$logo;?>" width="<?=$ancho;?>"></th>
		</tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:25px"><?=$nomSuc;?></th>
	  </tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:18px"><?=$dir;?></th>
	  </tr>
    <tr>
			<td colspan="4" style="font-size:18px">Cliente:<?=($var['idCliente']==0)? $clienteOp : $var['cliente'];?></th>
		</tr>
    <tr>
			<td colspan="4" style="font-size:18px">RFC: <?=$rfc;?></th>
		</tr>
		<tr>
    	<td colspan="1" style="font-size:18px"><br><?=$var['fechaHora'];?></td>
      <td colspan="3" align="center" style="font-size:18px"><br>Folio: <?=$var['folio'];?></td>
    </tr>
		<tr>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;"><b>DESCRIPCION</b></td>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;"><b>PRECIO</b></td>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;"><b>CANT</b></td>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;" align="right"><b>SUBTOTAL</b></td>
  	</tr>
		<?php
    ########################## se imprimen los productos, al igual que sus devoluciones si es que existen ##########################
		$sql="SELECT dc.*, prod.descripcion
					FROM detcotizaciones dc
					INNER JOIN productos prod ON dc.idProducto = prod.id
					WHERE dc.idCotizacion = '$idCotizacion'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
    $subTotal = $montoDev = 0;
		while ($row=mysqli_fetch_array($result))
		{
      echo '
        <tr>
          <td style="font-size:18px" padding="5px" width="200px">'.$row['descripcion'].'</td>
          <td align="center" style="font-size:18px" padding="5px" width="15px">$'.$row['precioVenta'].'</td>
          <td align="center" style="font-size:18px" padding="5px" width="15px">'.$row['cantidad'].'</td>
          <td align="right" style="font-size:18px" padding="5px" width="15px">'.number_format($row['precioVenta']*$row['cantidad'],2,".",",").'</td>
        </tr>';

        $subTotal += ($row['precioVenta'] * $row['cantidad']);
		}
    $total=$subTotal-$montoDev;
    $subTotal = $subTotal - $montoDev;
    $vTotal = number_format($total,2,'.',',');
    $vSubTotal = number_format($subTotal,2,'.',',');

    if ($vTotal != $vSubTotal && $var['estatus']==2) {
      echo'<script type="text/javascript">
    alert("Hubo un inconveniente menor, por favor notifica a tu Administrador");
    </script>';
    }
    /*
    echo  '<tr>
              <td>Cantidades</td>
              <td>$'.number_format($subTotal,2,'.',',').'</td>
              <td>divisor</td>
              <td>$'.number_format($total,2,'.',',').'</td>
           </tr>';
           */
########################## se muestran los totales ##########################

		?>
    <tr>
      <th colspan="3" align="right" style="border-top:1px dotted #999; font-size:18px; padding-top:8px;"><strong>TOTAL= </strong></th>
      <th align="right" style="border-top:1px dotted #999; font-size:18px; padding-top:8px;"><strong>$<?=number_format($total,2,'.',',');?></strong></th>
    </tr>
    <tr>
      <th colspan="3" align="right">&nbsp; </th>
      <th align="right"></th>
    </tr>

    <?php

          ########################## Se imprime el pie de ticket ##########################
     ?>

		<tr><td colspan="4" align="left" style="font-size:16px" padding="5px">Generada por: <?=$cajero;?><br></td></tr>

    <tr>
            <td colspan="4" aling="center">
              <center>
              <h3><b> =================================== <br>
                COTIZACIÓN
                =================================== </b></h3></center>
            </td>
          </tr>


		 <tr><td colspan="4" align="center" style="font-size:18px" padding="5px"><br>*** GRACIAS POR SU PREFERENCIA ***<br></td></tr>
		 <tr><td colspan="4" align="left" style="font-size:18px" padding="5px">NOTA: Los precios tienen una validez de 15 días desde su fecha de emisión.<br></td></tr>
		<script>
      window.print();
			window.print();
		</script>
