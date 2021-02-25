<meta charset="utf-8">
<?php

$id=$_REQUEST['idVenta'];

  $sql="SELECT suc.nombre AS sucursalName,suc.direccion, CONCAT(usuEn.nombre,' ',usuEn.appat,' ',usuEn.apmat) AS userName,
  emp.nombre AS nameEmp, emp.rfc, emp.logo, emp.anchoLogo, c.*
FROM compras c
INNER JOIN sucursales suc ON c.idSucursal = suc.id
INNER JOIN empresas emp ON suc.idEmpresa = emp.id
INNER JOIN segusuarios usuEn ON c.idUserReg = usuEn.id
WHERE c.id ='$id'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Compras.');
$var=mysqli_fetch_array($result);

switch ($var['estatus']) {
    case "1":
        $estatus='Compra de Mercancia';
        break;
    default:
        $estatus='Compra de Mercancia';
        break;
}
error_reporting(0);
$ancho = $var['anchoLogo']*1.75;
$logo = '../'.$var['logo'];
$dir = $var['direccion'];
$nomSuc = $var['sucursalName'];
$rfc = $var['rfc'];

	?>

	<table border="0" style="font-size:13px" width="260px">
		<tr>
			<th colspan="4" align="center"><img class="img-circle" src="<?=$logo;?>" width="<?=$ancho;?>"></th>
		</tr>
    <tr>
    	<th colspan="4" align="center" style="font-size:18px"><?=$nomSuc;?><br><br><b>COMPRA</b></th>
	  </tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:12px"><br><?=$dir;?></th>
	  </tr>
    <tr>
			<td colspan="4"</th>
		</tr>
		<tr>
    	<td colspan="2"><br><?=$var['fecha'];?></td>
      <td colspan="2" align="center"><br>Folio: <?=$var['id'];?></td>
    </tr>
		<tr>
	    <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DESCRIPCION</td>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;"></td>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">ENVIO</td>
      <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">CHECK</td>
  	</tr>
		<?php
		$sql="SELECT pro.descripcion,det.*
		FROM detcompras det
		LEFT JOIN productos pro ON det.idProducto = pro.id
				  WHERE idCompra = '$id'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Compras.');
		while ($row=mysqli_fetch_array($result))
		{
			echo '
				<tr>
					<td>'.$row['descripcion'].'</td>
					<td align="center"></td>
					<td align="center">'.$row['cant'].'</td>
					<td align="center"> <input type="checkbox" value=></td>
				</tr>
        ';
		}
		?>
    <tr><th colspan="4" align="center"><?=$var['nota'];?></th></tr>
		<tr><td colspan="4" align="center" style=" margin-top:15px; padding-top:50px; "><hr aling="center" size="2"></td></tr>
		<tr><th colspan="4" align="center"><?=$var['userName'];?><br><?=$estatus;?></th></tr>
  </table>

    <script>
			window.print();
		</script>
