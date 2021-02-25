<meta charset="utf-8">
<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = 'imprimeTicketTraspaso.php';
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];

$id = (!empty($_REQUEST['idTraspaso'])) ? $_REQUEST['idTraspaso'] : 0 ;
$cat = (!empty($_REQUEST['cat'])) ? $_REQUEST['cat'] : 0 ;


$sql="SELECT sEn.nombre AS sucursalEnt, sSal.nombre AS sucursalSal, sSal.direccion, CONCAT(uRe.nombre,' ',uRe.appat,' ',uRe.apmat) AS userEnt,
CONCAT(uSal.nombre,' ',uSal.appat,' ',uSal.apmat) AS userSal,t.*,CONCAT(uBod.nombre,' ',uBod.appat,' ',uBod.apmat) As userBod, e.anchoLogo,e.logo
FROM traspasos t
INNER JOIN sucursales sSal ON t.idSucSalida = sSal.id
INNER JOIN empresas e ON sSal.idEmpresa = e.id
LEFT JOIN sucursales sEn ON t.idSucEntrada = sEn.id
LEFT JOIN segusuarios uRe ON t.idUserRecepcion = uRe.id
LEFT JOIN segusuarios uSal ON t.idUserEnvio = uSal.id
LEFT JOIN segusuarios uBod ON t.idUserBodega = uBod.id
WHERE t.id = '$id'";
//echo $sql;
$result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
$var=mysqli_fetch_array($result);

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
    <td colspan="4">Usuario Autoriza:<?=$var['userSal'];?></th>
  </tr>
  <tr>
    <td colspan="4">Sucursal Recibe:<?=$var['sucursalEnt'];?></th>
  </tr>
  <?php
    if ($cat == 2) {
      echo '<tr>
          		<td colspan="4">Recibo por: '.$var['userEnt'].'</th>
          	</tr>';
    }
   ?>
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
  $sql="SELECT p.descripcion,dt.*
        FROM dettraspasos dt
        LEFT JOIN productos p ON dt.idProducto = p.id
        WHERE dt.idTraspaso = '$id'";
  $result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Ventas.');
  while ($row=mysqli_fetch_array($result))
  {
    echo '
      <tr>
        <td>'.$row['descripcion'].'</td>
        <td align="center"></td>
        <td align="center">'.$row['cantidadEnvio'].'</td>
        <td align="center"> <input type="checkbox" value=></td>
      </tr>
      ';
  }
  ?>
  <tr><td colspan="4" align="center" style=" margin-top:15px; padding-top:50px; "><hr aling="center" size="2"></td></tr>
  <tr><th colspan="4" align="center"><?=$var['userBod'];?><br>Carga en Bodega</th></tr>
</table>

  <script>
    window.print();
  </script>
