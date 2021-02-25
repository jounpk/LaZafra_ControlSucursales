<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

echo '<div class="table-responsive">
        <table class="table product-overview" id="zero_config2">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Nombre</th>
              <th class="text-center">Estatus</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody id="cuerpoTabla">';

        $sqlMarcas = "SELECT * FROM catmarcas ORDER BY estatus ASC";
        $resMarcas = mysqli_query($link,$sqlMarcas) or die('Problemas al consultar las Marcas, notifica a tu Administrador');

        while ($lista = mysqli_fetch_array($resMarcas)) {
          $id = $lista['id'];
          $estatus = ($lista['estatus'] == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;
          $cambiaEstatus = ($lista['estatus'] == 1) ? '<button class="btn btn-outline-danger" title="Desactivar" onclick="cambiaEstatus('.$id.','.$lista['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Activar" onclick="cambiaEstatus('.$id.','.$lista['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button>' ;

          echo '<tr>
                  <td class="text-center">'.$id.'</td>
                  <td id="nombreMarca-'.$id.'">'.$lista['nombre'].'</td>
                  <td class="text-center" id="estatusMarca-'.$id.'">'.$estatus.'</td>
                  <td class="text-center" id="botonesMarca-'.$id.'">
                    <button class="btn btn-outline-info" data-target="#modalEditMarca" data-toggle="modal" onclick="mandaModal('.$id.');"><i class="fas fa-pencil-alt"></i></button>
                    '.$cambiaEstatus.'
                  </td>
                </tr>';

        }

    echo '</tbody>
        </table>
      </div>
      <script>
        $("#zero_config2").DataTable();
      </script>';

 ?>
