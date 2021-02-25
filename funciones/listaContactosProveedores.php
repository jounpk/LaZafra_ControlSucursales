<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idProv'])) ? $_POST['idProv'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;
$pyme = $_SESSION['LZFpyme'];
if ($id < 1) {
  mandaError('No se reconoció el Proveedor, actualiza e inténtalo otra vez, si persiste notifica a tu Administrador');
}
$disable = ($estatus == 1) ? '' : 'disabled' ;
$contenido = '';
$contenido .= '<div class="text-right">
                <button class="btn btn-outline-'.$pyme.' btn-rounded" '.$disable.' data-toggle="modal" data-target="#modalnewContacto" onclick="mandaModalContacto('.$id.');"><i class="fas fa-plus"></i> Nuevo Contacto</button>
              </div>
              <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                <div class="table-responsive">
                      <table class="table product-overview" id="zero_config2">
                        <thead>
                        <tr>
                          <td>Nombre</td>
                          <td class="text-center">Teléfono</td>
                          <td>Correo</td>
                          <td class="text-center">Acciones</td>
                         
                          
                        </tr>
                        </thead>
                        <tbody id="cuerpoTabla">';

                      $sqlContactos = "SELECT * FROM contactosprov WHERE idProveedor = '$id' ORDER BY nombre";
                      $resContactos = mysqli_query($link,$sqlContactos) or die(mandaError('Problemas al consultar los Contactos, notifica a tu Administrador'));

                      while ($cto = mysqli_fetch_array($resContactos)) {
                        $idContacto = $cto['id'];

                      
                        $elimina = ($estatus == 1) ? '<a href="javaScript:void(0);" class="text-danger" onclick="eliminaContacto('.$idContacto.','.$id.');"><i class="fas fa-trash"></i></a>' : '<a class="text-danger" disabled><i class="fas fa-trash"></i></a>' ;
                        

                $contenido .= '<tr id="fila-'.$idContacto.'">
                                
                                <td>'.$cto['nombre'].'</td>
                                <td class="text-center" >'.$cto['telefono'].'</td>
                                <td>'.$cto['correoSucursal'].'</td>
                                <td>'.$elimina.'
                                <a href="javascript:void(0);" class="text-info" onclick="editaContacto('.$idContacto.')" data-toggle="modal" data-target="#modalEditProveedor1"><i class="fas fa-pencil-alt"></i></a>&nbsp;&nbsp;
                                 </td>
                                
                                
                              </tr>';

                      }

          $contenido .= '</tbody>
                      </table>
                      <input type="hidden" name="identProveedor" id="identProveedor" value="'.$id.'">
                      </div>
                      </div>
          
              <script>
                $("#zero_config2").DataTable();
              </script>
              ';

              echo '1|'.$contenido;

function mandaError($error){
  echo '0|'.$error;
}
 ?>
