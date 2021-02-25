<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idProd = (!empty($_POST['idProd'])) ? $_POST['idProd'] : 0 ;
$idUser = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];

$requiere = ($idProd > 0) ? 'required' : '' ;
  echo '<label for="selectLote" class="control-label">Selecciona el Lote:</label>
  <br>
  <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text" id="selectLote1">LT</span>
      </div>
      <select class="form-control" id="listaLote" name="lote[]" '.$requiere.'>
        <option value="">Sin lote (producto sin Stock)</option>';

        $sqlLt = "SELECT *
                 FROM lotestocks
                 WHERE idProducto = '$idProd' AND idSucursal = '$idSucursal' AND estatus = '1'";
        $resLt = mysqli_query($link,$sqlLt) or die('Problemas al listar los lotes, notifica a tu Administrador.');
        $cant = mysqli_num_rows($resLt);
        if ($cant > 0) {
            while ($lt = mysqli_fetch_array($resLt)) {
              $caducidad = ($lt['caducidad'] != '') ? $lt['caducidad'] : 'No caduca' ;
              echo '<option value="'.$lt['id'].'">'.$lt['lote'].' ('.$caducidad.')</option>';
            }
          } 
    echo '</select>
  </div>
  <label for="cantLote" class="control-label">Cantidad de producto en el lote:</label>
  <br>
  <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text" id="cantLote1">CNT</span>
      </div>
      <input type="number" min="0" class="form-control cantProductos" placeholder="Ingresa la cantidad" name="cantLote[]" id="cantLote">
  </div>
  <br>';

 ?>
