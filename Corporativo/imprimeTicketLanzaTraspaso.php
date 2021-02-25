<meta charset="utf-8">
<?php
$id=$_REQUEST['idVenta'];
$sql="SELECT sucEn.nombre AS sucursalEnt,sucSal.direccion, CONCAT(usuEn.nombre,' ',usuEn.appat,' ',usuEn.apmat) AS userEnt,
sucSal.nombre AS sucursalSal, CONCAT(usuSal.nombre,' ',usuSal.appat,' ',usuSal.apmat) AS userSal, emp.nombre AS nameEmp,
emp.rfc, emp.logo, emp.anchoLogo, tras.*
FROM traspasos tras
LEFT JOIN sucursales sucEn ON tras.idSucEntrada = sucEn.id
INNER JOIN sucursales sucSal ON tras.idSucSalida = sucSal.id
INNER JOIN empresas emp ON sucSal.idEmpresa = emp.id
LEFT JOIN segusuarios usuEn ON tras.idUserRecepcion = usuEn.id
LEFT JOIN segusuarios usuSal ON tras.idUserEnvio = usuSal.id
WHERE tras.id = '$id'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
$var=mysqli_fetch_array($result);
switch ($var['estatus']) {
    case "1":
        $estatus='Generando Envio';
        break;
		case "2":
        $estatus='Envio de Mercancia';
        break;
    default:
        $estatus='Envio de Mercancia';
        break;
}

	?>

	<table border="0" style="font-size:13px" width="260px">
		<tr>
			<th colspan="4" align="center"><img class="img-circle" src="../<?=$var['logo'];?>" width="<?=$var['anchoLogo'];?>"></th>
		</tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:18px"><?=$var['sucursalSal'];?></th>
	  </tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:12px"><?=$var['direccion'];?></th>
	  </tr>
    <tr>
			<td colspan="4">Sucursal Recibe: <?=$var['sucursalEnt'];?></th>
		</tr>
    <tr>
		<?php if($var['userEnt']!=''){?>
			<td colspan="4">Usuario Recibe: <?=$var['userEnt'];?></th>
		<?php }?>

		</tr>
    <tr>
			<td colspan="4"</th>
		</tr>
		<tr>
    	<td colspan="2"><br><?=$var['fechaEnvio'];?></td>
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
          FROM dettraspasos det
          LEFT JOIN productos pro ON det.idProducto = pro.id
					WHERE idTraspaso = '$id'";
		$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
		while ($row=mysqli_fetch_array($result))
		{
			echo '
				<tr>
					<td>'.$row['descripcion'].'</td>
					<td align="center"></td>
					<td align="center">'.$row['cantEnvio'].'</td>
					<td align="center"> <input type="checkbox" value=></td>
				</tr>
        ';
		}
		?>
		<tr><td colspan="4" align="center" style=" margin-top:15px; padding-top:50px; "><hr aling="center" size="2"></td></tr>
		<tr><th colspan="4" align="center"><?=$var['userSal'];?><br><?=$estatus;?></th></tr>
  </table>

    <script>
			window.print();
		</script>
