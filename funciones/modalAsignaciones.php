<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident']; ?>

<div class='row'>
  <div class='col-md-12'>
    <div class='card'>
      <div class='card-header'>
        <h4 class='m-b-0'>Designación de Lotes</h4>
      </div>
      <div class='card-body'>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="rangeBa2" class="control-label col-form-label">Nueva Caducidad</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <input class="form-control" type="date" value="" id="rangeBa2" name="fecha_caducidad" />
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mt-4">
            <div id="bloquear-btn1" style="display:none;">
              <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="sr-only">Loading...</span>
              </button>
              <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="sr-only">Loading...</span>
              </button>
              <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
              </button>
            </div>
            <div id="desbloquear-btn1" class="pt-1">
              <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar Lote
              </button>
            </div>

          </div>
        </div>


        <table class='table'>
          <thead>
            <th>#</th>
            <th>Lote</th>
            <th>Cantidad Actual</th>
            <th>Agregar</th>
          </thead>
          <tbody>


            <?php

            $ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0;
            if ($ident < 1) {
              errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
            }

            $sqlDetallado = "
            SELECT
            prod.descripcion AS producto,
            detajs.cantidad AS cantidad,
            detajs.tipo 
            FROM
            ajustes ajs 
            INNER JOIN detajustes detajs ON detajs.idAjuste=ajs.id 
            INNER JOIN productos prod ON detajs.idProducto=prod.id
            WHERE ajs.id='$ident'
            ";
            $resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
            $cant_rows_entrada = 1;
            $cant_rows_salida = 1;
            while ($res = mysqli_fetch_array($resdet)) {

              if ($res['tipo'] == 1) {
                echo '<tr><td  class="">' . $cant_rows_entrada . '</td>';
                echo '<td  class=""><b>' . $res["producto"] . '</b></td>';
                echo '<td  class=""><b>' . $res["cantidad"] . '</b></td></tr>';
                $cant_rows_entrada++;
              }
            }


            echo '</tbody></table></div></div></div>';
