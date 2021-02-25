<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$idSucursal = $_SESSION['LZFidSuc'];

$idProducto = (!empty($_POST['idProducto'])) ? $_POST['idProducto'] : 0 ;

$sql = "SELECT s.*, p.costo, p.estatus AS estatusProd, p.descripcion AS producto, suc.nameFact AS sucursal
        FROM stocks s
        INNER JOIN productos p ON s.idProducto=p.id
        INNER JOIN sucursales suc ON s.idSucursal=suc.id
        WHERE p.id = '$idProducto'
        ORDER BY s.idSucursal";
$resp = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar en las sucursales, notifica a tu Administrador'));
$cant = mysqli_num_rows($resp);
$contenido = '';
$precioPd = 0;
if ($cant > 0) {

    $contenido .= '<div class="table-responsive">
                  <table class="table product-overview" id="zero_config">
                    <thead>
                        <tr>
                            <th width="140px">Sucursal</th>
                            <th>Producto</th>
                            <th width="150px" class="text-center">Precio</th>
                            <th width="90px" class="text-center">Cantidad</th>
                            <th width="90px" class="text-center">Solicita</th>
                            <th width="90px" class="text-center">Solicitud</th>
                        </tr>
                    </thead>
                    <tbody>';
  while ($datos = mysqli_fetch_array($resp)) {
    $idStock = $datos['id'];
    $estatus=$datos['estatusProd'];
  	if($estatus==0){
  		$contenido .= '<tr class="danger">';
  		$desactiva=' disabled="disabled" ';
  	}
  	else{
  		$contenido .='<tr>';
  		$desactiva='';
  	}
  	$disSuc =($datos['idSucursal']==$idSucursal) ? 'disabled="disabled"' : '' ;

    # comienza a buscar los productos en precios base
    $sqlPrecios = "SELECT DISTINCT(IF(scs.aplicaExtra = 1 AND a.tipoPrecio = 1,IF(scs.tipoExtra = 1,(a.precio + scs.cantExtra),(a.precio *(1 + (scs.cantExtra / 100)))),a.precio)) AS precioProd
                  FROM stocks s
                  INNER JOIN sucursales scs ON s.idSucursal = scs.id
                  INNER JOIN productos p ON s.idProducto = p.id
                  INNER JOIN (
                  SELECT '1' AS tipoPrecio, precio, idProducto FROM preciosbase
                  UNION
                  SELECT '2' AS tipoPrecio, precio, idProducto FROM excepcionesprecio WHERE idSucursal = '$idSucursal'
                  ) a ON a.idProducto = p.id
                  WHERE s.id = '$idStock'
                  ORDER BY precioProd DESC";
    $resPrecios = mysqli_query($link,$sqlPrecios) or die(errorBD('Problemas al consultar los precios de los productos, notifica a tu Administrador.'));
        $contenido .= '<td>'.$datos['sucursal'].'</td>
                      <td>'.$datos['producto'].'</td>
                      <td>
                        <select id="precios" class="form-control">
                          <optgroup label="Precios">';
                          $miCont = 0;
                          while ($pr = mysqli_fetch_array($resPrecios)) {

                            ++$miCont;

                              $precioPd = $pr['precioProd'];


                            $contenido .= '<option value="'.$miCont.'">'.number_format($precioPd,2,',',',').'</option>';
                          }


/*
                          $contenido .='</optgroup>
                          <optgroup label="costo">';
                          $contenido .= '<option value="costo" disabled="disabled">'.$datos['costo'].'</option>';
              $contenido .= '		</optgroup>
                      </select>
                    </td>
                    <td>'.$datos['cantActual'].'</td>
                    <td>
                      <input '.$disSuc.' value="0" type="number" id="cant'.$datos['idSucursal'].'p'.$datos['idProducto'].'" max="'.$datos['cantActual'].'" min="0" class="form-control" '.$desactiva.' >
                    </td>
                    <td>
                      <center>
                        <button '.$disSuc.' id="bt'.$datos['idSucursal'].'n'.$datos['idProducto'].'" onclick="generaSol('.$datos['idSucursal'].','.$datos['idProducto'].','.$datos['cantActual'].')" type="button" class="btn btn-success btn-circle"'.$desactiva.'><i class="fa fa-check"></i>
                        </button>
                      </center>
                    </td>
                  </tr>';
                  #*/
                  $contenido .='</optgroup>
              </select>
            </td>
            <td>'.$datos['cantActual'].'</td>
            <td>
              <input '.$disSuc.' value="0" type="number" id="cant'.$datos['idSucursal'].'p'.$datos['idProducto'].'" max="'.$datos['cantActual'].'" min="0" class="form-control" '.$desactiva.' >
            </td>
            <td>
              <center>
                <button '.$disSuc.' id="bt'.$datos['idSucursal'].'n'.$datos['idProducto'].'" onclick="generaSol('.$datos['idSucursal'].','.$datos['idProducto'].','.$datos['cantActual'].')" type="button" class="btn btn-success btn-circle"'.$desactiva.'><i class="fa fa-check"></i>
                </button>
              </center>
            </td>
          </tr>';
        }
$contenido .= '</tbody>
        </table>
        </div>';


    echo '1|'.$contenido;
    exit(0);
} else {
  errorBD('No se encontró el producto en sucursales.');
  exit(0);
}

function errorBD($error){
  echo '0|'.$error;
  exit(0);
}

 ?>
