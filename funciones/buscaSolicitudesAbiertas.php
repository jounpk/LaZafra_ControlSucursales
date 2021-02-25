<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];

echo '<div class="table-responsive">';

$sql="SELECT
	tp.id, tp.idSolicitud, sucSal.nombre AS sucSale
FROM 	traspasos tp
INNER JOIN sucursales sucSal ON tp.idSucSalida = sucSal.id
LEFT JOIN solicitudestrasp stp ON tp.idSolicitud = stp.id
WHERE stp.estatus = 1
	AND tp.idSucEntrada = '$idSucursal'
	AND tp.estatus = 1
	AND stp.idUsuario = '$idUser'";
$res=mysqli_query($link,$sql) or die ('<p style="color:red;">Error al Consultar.<p>');
$cant=mysqli_num_rows($res);
//echo $sql.'--'.$cant;
if ($cant<1){
echo	'<br><div class="alert alert-warning alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>No hay solicitudes abiertas </strong>
	</div>';
}
if ($cant>=1){
echo'<br>
	<table class="table table-striped">
		<thead>
      <tr>
        <th class="">No Solicitud</th>
        <th class="">Folio Traspaso</th>
    		<th class="">Sucursal</th>
        <th class="">Productos Distintos</th>
        <th class="text-center">Acciones</th>
   		</tr>
    </thead>
 		<tbody>';

while($datos=mysqli_fetch_array($res)){
	$idtras = $datos['id'];
	echo '
      <tr>
        <td>'.$datos['idSolicitud'].'</td>
				<td>'.$datos['id'].'</td>
				<td>'.$datos['sucSale'].'</td>
				<td>';
	$query = "SELECT dtp.id,dtp.cantEnvio, pd.descripcion, a.cant FROM dettraspasos dtp
			INNER JOIN productos pd ON dtp.idProducto = pd.id
			INNER JOIN (SELECT idTraspaso, COUNT(id) AS cant FROM dettraspasos WHERE idTraspaso = '$idtras') a
			WHERE dtp.idTraspaso = '$idtras'";
	$val = mysqli_query($link,$query) or die('No se pudo revisar.');
	$cont = 1;
	while ($dat = mysqli_fetch_array($val)) {
    $btnTrash = ($dat['cant'] > 1) ? '<a href="javascript:void(0);" class="text-danger" onClick="eliminaProductoEnSolicitud('.$dat['id'].');"><i class="fas fa-trash"></i></a> ' : '' ;
		echo $btnTrash.'  '.number_format($dat['cantEnvio'],'1','.',',').' - '.$dat['descripcion'].'<br>';
		$cont++;
	}

		echo '</td>
        <td class="text-center">
          <button class="btn btn-outline-success btn-circle" placeholder="Lanzar solicitud a Sucursal" onClick="cierraSolicitud('.$idtras.',1);"><i class="fas fa-check"></i></button>
          <button class="btn btn-outline-danger btn-circle" placeholder="Cancela la solicitud" onClick="cierraSolicitud('.$idtras.',2);"><i class="fas fa-trash"></i></button>
        </td>
			</tr> ';
	}

echo '
		</tbody>
	</table>';
}

 ?>
