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
              <th>Descripción</th>
              <th class="text-center">Estatus</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody id="cuerpoTabla">';

        $sqlIngrdientes = "SELECT * FROM catingact ORDER BY estatus ASC";
        $resIngrdientes = mysqli_query($link,$sqlIngrdientes) or die('Problemas al consultar los Ingrdientes, notifica a tu Administrador');

        while ($ing = mysqli_fetch_array($resIngrdientes)) {
          $id = $ing['id'];
          $estatus = ($ing['estatus'] == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;
          $boton = ($ing['estatus'] == 1) ? '<a id="btnCambiaEstatus-'.$id.'" title="Desactivar"><button class="btn btn-outline-danger" onclick="cambiaEstatus('.$id.','.$ing['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' : '<a id="btnCambiaEstatus-'.$id.'" title="Activar"><button class="btn btn-outline-warning" onclick="cambiaEstatus('.$id.','.$ing['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' ;
          echo '<tr>
                  <td class="text-center">'.$ing['id'].'</td>
                  <td id="nomIngAct-'.$id.'">'.$ing['nombre'].'</td>
                  <td id="descIngAct-'.$id.'">'.$ing['descripcion'].'</td>
                  <td id="estatusIngAct-'.$id.'" class="text-center">'.$estatus.'</td>
                  <td class="text-center">
                    <button class="btn btn-outline-info" data-toggle="modal" data-target="#modalEditIngrediente" onclick="mandaModal('.$id.');"><i class="fas fa-pencil-alt"></i></button>
                    '.$boton.'
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
