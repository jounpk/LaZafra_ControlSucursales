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

        $sqlDepartamentos = "SELECT * FROM departamentos ORDER BY estatus ASC";
        $resDepartamentos = mysqli_query($link,$sqlDepartamentos) or die('Problemas al consultar los Departamentos, notifica a tu Administrador');

        while ($dpt = mysqli_fetch_array($resDepartamentos)) {
          $id = $dpt['id'];
          $estatus = ($dpt['estatus'] == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;
          $boton = ($dpt['estatus'] == 1) ? '<a id="btnCambiaEstatus-'.$id.'" title="Desactivar"><button class="btn btn-outline-danger" onclick="cambiaEstatus('.$id.','.$dpt['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' : '<a id="btnCambiaEstatus-'.$id.'" title="Activar"><button class="btn btn-outline-warning" onclick="cambiaEstatus('.$id.','.$dpt['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' ;
          echo '<tr>
                  <td class="text-center">'.$dpt['id'].'</td>
                  <td id="nomDpt-'.$id.'">'.$dpt['nombre'].'</td>
                  <td id="descDpt-'.$id.'">'.$dpt['descripcion'].'</td>
                  <td id="estatusDpt-'.$id.'" class="text-center">'.$estatus.'</td>
                  <td class="text-center">
                    <button class="btn btn-outline-info" data-toggle="modal" data-target="#modalEditDepto" onclick="mandaModal('.$id.');"><i class="fas fa-pencil-alt"></i></button>
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
