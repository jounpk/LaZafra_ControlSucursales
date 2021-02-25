<meta charset="utf-8">
<?php

$id=$_REQUEST['idVenta'];

  $sql="SELECT suc.nombre AS sucursal,suc.direccion, CONCAT(usuEn.nombre,' ',usuEn.appat,' ',usuEn.apmat) AS useremitio,
  emp.nombre AS nameEmp, emp.rfc, emp.logo, emp.anchoLogo, ajs.*
FROM ajustes ajs
INNER JOIN sucursales suc ON ajs.idSucursal = suc.id
INNER JOIN empresas emp ON suc.idEmpresa = emp.id
LEFT JOIN segusuarios usuEn ON ajs.idUserAplica = usuEn.id
WHERE ajs.id = '$id'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
$var=mysqli_fetch_array($result);
$cabeceraEntrada='<tr>
<th colspan="4" align="center">Ajuste de Entrada</th>
</tr>';
$cabeceraSalida='<tr>
<th colspan="4" align="center">Ajuste de Salida</th>
</tr>';
$cabeceradeCampos='<tr>
<td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DESCRIPCION</td>
<td style="border-top:1px dotted #999; border-bottom:1px dotted #999;"></td>
<td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">CANTIDAD</td>
<td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">CHECK</td>
</tr>';
$tablaLote='';
$productoEntrada="";
$productoSalida="";

/*switch ($var['detalle']) {
    case "0":
        $estatus='Ajuste de Entrada';
        break;
		case "1":
        $estatus='Ajuste de Salida';
        break;
}*/

	?>

	<table border="0" style="font-size:13px" width="260px">
		<tr>
			<th colspan="4" align="center"><img class="img-circle" src="../<?=$var['logo'];?>" width="<?=$var['anchoLogo'];?>"></th>
		</tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:18px"><?=$var['sucursal'];?></th>
	  </tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:12px"><?=$var['direccion'];?></th>
	  </tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:12px"><br><strong>Ajustes de Producto</strong></th>
	  </tr>
		<tr>
    	<td colspan="2"><br><?=$var['fechaAplica'];?></td>
      <td colspan="2" align="center"><br>Folio: <?=$var['id'];?></td>
    </tr>
		<?=$cabeceraEntrada?>
		<?=$cabeceradeCampos?>
		<?php
		$sql="SELECT pro.descripcion,det.*
		FROM detajustes det
		LEFT JOIN productos pro ON det.idProducto = pro.id
				  WHERE idAjuste = '$id' AND det.tipo='1'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado del Ajuste.');
		while ($row=mysqli_fetch_array($result))
		{
			$iddetAjs=$row["id"];
			echo '
				<tr>
					<td colspan="2">'.$row['descripcion'].'</td>
					<td align="center">'.$row['cantidad'].'</td>
					<td align="center"> <input type="checkbox" value=></td>
				</tr>';
			$sqllote="SELECT lote.lote, dtlote.cantidad
			FROM detajustelote dtlote
			INNER JOIN lotestocks lote ON lote.id = dtlote.idLote
			INNER JOIN detajustes dtajs ON dtajs.id = dtlote.idDetAjuste
		    WHERE dtajs.idAjuste = '$id' AND dtajs.id='$iddetAjs'";
			$resultlote=mysqli_query($link,$sqllote) or die('Problemas al Consultar el Detallado del Lote.');
			while ($rowLote=mysqli_fetch_array($resultlote))
			{
				echo '
				<tr>
				<td colspan="3">'.$rowLote['lote'].'</td>
				<td colspan="2" class="text-center">'.$rowLote['cantidad'].'</td>
				<tr>
				';
			}
		}

		?>
		<?=$cabeceraSalida?>
		<?=$cabeceradeCampos?>
		<?php
		$sql="SELECT pro.descripcion,det.*
		FROM detajustes det
		LEFT JOIN productos pro ON det.idProducto = pro.id
				  WHERE idAjuste = '$id' AND det.tipo='2'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado del Ajuste.');
		while ($row=mysqli_fetch_array($result))
		{
			$iddetAjs=$row["id"];
			echo '
				<tr>
					<td colspan="2">'.$row['descripcion'].'</td>
					<td align="center">'.$row['cantidad'].'</td>
					<td align="center"> <input type="checkbox" value=></td>
				</tr>';
			$sqllote="SELECT lote.lote, dtlote.cantidad
			FROM detajustelote dtlote
			INNER JOIN lotestocks lote ON lote.id = dtlote.idLote
			INNER JOIN detajustes dtajs ON dtajs.id = dtlote.idDetAjuste
					  WHERE dtajs.idAjuste = '$id' AND dtajs.id='$iddetAjs'";
			$resultlote=mysqli_query($link,$sqllote) or die('Problemas al Consultar el Detallado del Lote.');
			while ($rowLote=mysqli_fetch_array($resultlote))
			{
				echo '
				<tr>
				<td colspan="3">'.$rowLote['lote'].'</td>
				<td colspan="2" class="text-center">'.$rowLote['cantidad'].'</td>
				<tr>
				';
			}
		}

		?>
    <tr><th colspan="4" align="center"><br><?=$var['descripcion'];?></th></tr>
		<tr><td colspan="4" align="center" style=" margin-top:15px; padding-top:50px; ">
      <hr aling="center" size="2"></td></tr>
		<tr><th colspan="4" align="center"><?=$var['useremitio']?></th></tr>

  </table>

    <script>
			window.print();
		</script>
