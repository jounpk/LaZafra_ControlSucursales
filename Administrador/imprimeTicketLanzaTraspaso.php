<?php

$idSucursal = $_SESSION['LZFidSuc'];

 ?>
<meta charset="utf-8">
<?php
$id=$_REQUEST['idVenta'];
$sql="SELECT
sucEn.id AS idSucursalEn,
sucEn.nombre AS sucursalEnt,
sucSal.direccion AS direccionSal,
sucEn.direccion AS direccionEnv,
CONCAT( usuEn.nombre, ' ', usuEn.appat, ' ', usuEn.apmat ) AS userEnt,
sucSal.nombre AS sucursalSal,
CONCAT( usuSal.nombre, ' ', usuSal.appat, ' ', usuSal.apmat ) AS userSal,
empSal.nombre AS nameEmpSal,
empSal.rfc AS rfcSal,
empSal.logo AS logoSal,
empSal.anchoLogo AS anchoLogoSal,
empEnv.nombre AS nameEmpEnv,
empEnv.rfc AS rfcEnv,
empEnv.logo AS logoEnv,
empEnv.anchoLogo AS anchoLogoEnv,
tras.* 
FROM
traspasos tras
LEFT JOIN sucursales sucEn ON tras.idSucEntrada = sucEn.id
INNER JOIN sucursales sucSal ON tras.idSucSalida = sucSal.id
INNER JOIN empresas empSal ON sucSal.idEmpresa = empSal.id
INNER JOIN empresas empEnv ON sucEn.idEmpresa = empEnv.id
LEFT JOIN segusuarios usuEn ON tras.idUserRecepcion = usuEn.id
LEFT JOIN segusuarios usuSal ON tras.idUserEnvio = usuSal.id 
WHERE tras.id = '$id'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Traspaso.');
$var=mysqli_fetch_array($result);
$logoEmp=($idSucursal==$var["idSucursalEn"])?$var["logoEnv"]:$var["logoSal"];
$anchologo=($idSucursal==$var["idSucursalEn"])?$var["anchoLogoEnv"]:$var["anchoLogoSal"];
$nombreSucursal=($idSucursal==$var["idSucursalEn"])?$var["sucursalEnt"]:$var["sucursalSal"];
$direccion=($idSucursal==$var["idSucursalEn"])?$var["direccionEnv"]:$var["direccionEnv"];
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
			<th colspan="4" align="center"><img class="img-circle" src="../<?=$logoEmp;?>" width="<?=$anchologo;?>"></th>
		</tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:18px"><?=$nombreSucursal;?></th>
	  </tr>
		<tr>
    	<th colspan="4" align="center" style="font-size:12px"><?=$direccion;?></th>
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
			$iddet=$row["id"];
			echo '
				<tr>
					<td>'.$row['descripcion'].'</td>
					<td align="center"></td>
					<td align="center">'.$row['cantEnvio'].'</td>
					<td align="center"> <input type="checkbox" value=></td>
				</tr>
		';


	if($var['userEnt']!=''){
		$sqllote="SELECT lote.lote, dtlote.cantidad
		FROM dettrasplote dtlote
		INNER JOIN lotestocks lote ON lote.id = dtlote.idLoteEntrada
		INNER JOIN dettraspasos dt ON dt.id = dtlote.idDetTraspaso
		WHERE dt.idTraspaso = '$id' AND dt.id='$iddet'";
		//echo $sqllote;
		 }else{
       $sqllote="SELECT lote.lote, dtlote.cantidad
       FROM dettrasplote dtlote
       INNER JOIN lotestocks lote ON lote.id = dtlote.idLoteSalida
       INNER JOIN dettraspasos dt ON dt.id = dtlote.idDetTraspaso
       WHERE dt.idTraspaso = '$id' AND dt.id='$iddet'";	
	//echo $sqllote;

		 }
	
			





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
		<tr><td colspan="4" align="center" style=" margin-top:15px; padding-top:50px; "><hr aling="center" size="2"></td></tr>
		<tr><th colspan="4" align="center"><?=$var['userSal'];?><br><?=$estatus;?></th></tr>
  </table>

    <script>
			window.print();
		</script>
