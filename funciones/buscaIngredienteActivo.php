<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$idSucursal = $_SESSION['LZFidSuc'];
$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0;
$vista = (!empty($_POST['vista'])) ? $_POST['vista'] : 0;
$ingrediente = (isset($_POST['ingrediente']) && $_POST['ingrediente'] != '') ? $_POST['ingrediente'] : '';
$contenido = $contenido2 = '';
$cant = 0;
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------
?>

<?php
$sql = "SELECT p.id,p.descripcion,p.prioridad,p.idTagsIngredienteActivo, p.descripcionlarga AS descIngrediente,p.estatus,
  GROUP_CONCAT(DISTINCT(IF(scs.aplicaExtra = 1 AND a.tipoPrecio = 1,IF(scs.tipoExtra = 1,FORMAT(a.precio + scs.cantExtra,2),FORMAT(a.precio *(1 + (scs.cantExtra / 100)),2)),FORMAT(a.precio,2))) SEPARATOR '</li><li>$') AS listaprecios,
IF(s.cantActual > 0,s.cantActual,0) AS cantStock
  FROM productos p
  INNER JOIN (
			SELECT '1' AS tipoPrecio, precio, idProducto, cantLibera FROM preciosbase
			UNION
			SELECT '2' AS tipoPrecio, precio, idProducto, '1' AS cantLibera FROM excepcionesprecio WHERE idSucursal = '$idSucursal'
		) a ON a.idProducto = p.id
  LEFT JOIN stocks s ON p.id = s.idProducto
	LEFT JOIN sucursales scs ON s.idSucursal = scs.id
  LEFT JOIN catingact cin ON cin.nombre = '$ingrediente'
   WHERE p.estatus = 1 AND p.`idTagsIngredienteActivo` LIKE '%$ingrediente%'
	GROUP BY p.id
   ORDER BY p.prioridad ASC, p.descripcion ASC, a.precio DESC";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Querys, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Querys, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

$contenido .= '<div class="table-responsive">
                  <table class="table product-overview" id="zero_config">
                    <thead>
                        <tr>
                            <th class="text-center">Prioridad</th>
                            <th>Producto</th>
                            <th>Ingrediente Activo</th>
                            <th>Descripción</th>
                            <th class="text-center">Precios</th>
                            <th class="text-center">Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
while ($datos = mysqli_fetch_array($resultXQuery)) {
  $estatus = $datos['estatus'];
  if ($estatus == 0) {
    $contenido .= '<tr class="danger">';
    $desactiva = ' disabled="disabled" ';
  } else {
    $contenido .= '<tr>';
    $desactiva = '';
  }
  switch ($datos['prioridad']) {
    case 1:
      $punto = '<i class="fa fa-circle text-success" data-toggle="tooltip" data-placement="top" title="Prioridad Alta" data-original-title="Prioridad Alta"></i>';
      break;
    case 2:
      $punto = '<i class="fa fa-circle text-warning" data-toggle="tooltip" data-placement="top" title="Prioridad Media" data-original-title="Prioridad Media"></i>';
      break;


    default:
      $punto = '<i class="fa fa-circle text-default" data-toggle="tooltip" data-placement="top" title="Prioridad Baja" data-original-title="Prioridad Baja"></i>';
      break;
  } #fas fa-dollar-sign
  $listaPrecios = ($datos['listaprecios'] != '') ? '<ul class="list-style-none"><li>$' . $datos['listaprecios'] . '</li></ul>' : '';
  $activa = ($datos['cantStock'] > 0) ? '' : 'disabled';
  $onClick = ($datos['cantStock'] > 0) ? 'onClick="cambiaProducto(' . $idVenta . ', ' . $datos['id'] . ',' . $vista . ',\'\')"' : '';

  $contenido .= '<td class="text-center">' . $punto . '</td>
                      <td>' . $datos['descripcion'] . '</td>
                      <td>' . $datos['idTagsIngredienteActivo'] . '</td>
                      <td>' . $datos['descIngrediente'] . '</td>
                      <td class="text-right">' . $listaPrecios . '</td>
                      <td class="text-right">' . number_format($datos['cantStock'], 2, '.', ',') . '</td>
                      <td>
                        <center>
                          <button type="button" ' . $onClick . ' type="button" class="btn btn-success btn-circle" ' . $activa . '><i class="fa fa-check"></i>
                          </button>
                        </center>
                      </td>
                    </tr>';
}
$contenido .= '</tbody>
        </table>
        </div>';


echo '1|' . $contenido . '|' . $sql;
exit(0);


function errorBD($error)
{
  echo '0|' . $error;
  exit(0);
}
