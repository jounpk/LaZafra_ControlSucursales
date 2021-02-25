<?php
session_start();
$pyme = $_SESSION['LZFpyme'];
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$id = (!empty($_POST['id'])) ? $_POST['id'] : 0;
$idVenta = "";
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------
if ($id == '') {
  errorBD('No se reconoció los Datos Fiscales, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}
$sql = "SELECT * FROM datosfisc WHERE rfc='$id' LIMIT 1";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de Datos Fiscales, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de Datos Fiscales, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
while ($dat = mysqli_fetch_array($resultXQuery)) {
  $razonSocial = $dat['razonSocial'];
  $rfc = $dat['rfc'];
  $nombre = $dat['nombre'];
  $apellido = $dat['apellidos'];
  $idCliente = $dat['id'];
  $calle = $dat['calle'];
  $noExt = $dat['noExt'];
  $noInt = $dat['noInt'];
  $colonia = $dat['colonia'];
  $municipio = $dat['municipio'];
  $estado = $dat['estado'];
  $codpostal = $dat['codigoPostal'];
  $correo = $dat['correo'];
  $correo2 = $dat['correo2'] == '' ? "" : $dat['correo2'];
  $correo3 = $dat['correo3'] == '' ? "" : $dat['correo3'];
  $telefono = $dat['tel'] == '' ? "" : $dat['tel'];
}

?>
<input type='hidden' value='<?= $idVenta ?>' name='idVenta'>
<input type='hidden' value='<?= $idCliente ?>' name='idCliente'>
<div class='row'>
  <div class='col-md-6'>
    <label for='nombre' class='control-label col-form-label'>RFC</label>
    <input type='text' class='form-control' pattern="^[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$" id='rfc' name='rfc' value='<?= $rfc ?>' readonly required>
  </div>
  <div class='col-md-6'>
    <label for='apellido' class='control-label col-form-label'>Razón Social</label>
    <input type='text' class='form-control' id='razonSocial' name='razonSocial' value='<?= $razonSocial ?>' readonly required>
  </div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <label for='nombre' class='control-label col-form-label'>Nombre</label>
    <input type='text' class='form-control' id='nombre' name='nombre' value='<?= $nombre ?>' readonly>
  </div>
  <div class='col-md-6'>
    <label for='apellido' class='control-label col-form-label'>Apellidos</label>
    <input type='text' class='form-control' id='apellido' name='apellido' value='<?= $apellido ?>' readonly>
  </div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <label for='calle' class='control-label col-form-label'>Calle</label>
    <input type='text' class='form-control' id='calle' name='calle' value='<?= $calle ?>' readonly>
  </div>
  <div class='col-md-3'>
    <label for='noExt' class='control-label col-form-label'>No. Ext</label>
    <input type='text' class='form-control' id='noExt' name='noExt' value='<?= $noExt ?>' readonly>
  </div>
  <div class='col-md-3'>
    <label for='noInt' class='control-label col-form-label'>No. Int</label>
    <input type='text' class='form-control' id='noInt' name='noInt' value='<?= $noInt ?>' readonly>
  </div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <label for='colonia' class='control-label col-form-label'>Colonia</label>
    <input type='text' class='form-control' id='colonia' name='colonia' value='<?= $colonia ?>' readonly>
  </div>
  <div class='col-md-6'>
    <label for='municipio' class='control-label col-form-label'>Municipio</label>
    <select class='select2 form-control custom-select' id='municipios' id='municipio' name='municipio' readonly>
      <option>Seleccione un Municipio</option>

      <?php
      $sql = "SELECT id, nombre FROM catmunicipios WHERE idCatEstado='$estado' ORDER BY nombre";
      //----------------devBug------------------------------
      if ($debug == 1) {
        $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Municipios, notifica a tu Administrador', mysqli_error($link)));
        $canInsert = mysqli_affected_rows($link);
        echo '<br>SQL: ' . $sql . '<br>';
        echo '<br>Cant de Registros Cargados: ' . $canInsert;
      } else {
        $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Municipios, notifica a tu Administrador', mysqli_error($link)));
        $canInsert = mysqli_affected_rows($link);
      } //-------------Finaliza devBug------------------------------
      while ($dat = mysqli_fetch_array($resultXQuery)) {
        if ($municipio == $dat['id']) {
          echo "<option value='" . $dat["id"] . "' selected>" . $dat['nombre'] . "</option>";
        } else {
          echo "<option value='" . $dat["id"] . "'>" . $dat['nombre'] . "</option>";
        }
      }
      ?>
    </select>
  </div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <label for='estado' class='control-label col-form-label'>Estado</label>
    <select class='select2 form-control custom-select' id='estados' id='estado' onchange='cambioMunicipio(this.value)' name='estado' readonly>
      <option>Seleccione un Estado</option>
      <?php
      $sql = "SELECT id, nombre FROM catestados  ORDER BY nombre";
      //----------------devBug------------------------------
      if ($debug == 1) {
        $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Estados, notifica a tu Administrador', mysqli_error($link)));
        $canInsert = mysqli_affected_rows($link);
        echo '<br>SQL: ' . $sql . '<br>';
        echo '<br>Cant de Registros Cargados: ' . $canInsert;
      } else {
        $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Estados, notifica a tu Administrador', mysqli_error($link)));
        $canInsert = mysqli_affected_rows($link);
      } //-------------Finaliza devBug------------------------------
      while ($dat = mysqli_fetch_array($resultXQuery)) {
        if ($estado == $dat['id']) {
          echo "<option value='" . $dat["id"] . "' selected>" . $dat['nombre'] . "</option>";
        } else {
          echo "<option value='" . $dat["id"] . "'>" . $dat['nombre'] . "</option>";
        }
      }
      ?>
    </select>
  </div>
  <div class='col-md-6'>
    <label for='codpostal' class='control-label col-form-label'>Codigo Postal</label>
    <input type='text' class='form-control' id='codpostal' name='codpostal' value='<?= $codpostal ?>' readonly>
  </div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <label for='correo' class='control-label col-form-label'>Correo</label>
    <input type='text' class='form-control' id='correo' name='correo' value='<?= $correo ?>' readonly>
  </div>

  <div class='col-md-6'>
    <label for='telefono' class='control-label col-form-label'>Telefono</label>
    <input type="tel" pattern="[0-9() -]{14}" class="form-control phone-inputmask" id='telefono' name='telefono' value='<?= $telefono ?>' readonly>
  </div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <label for='correo' class='control-label col-form-label'>Correo Alternativo 1</label>
    <input type='text' class='form-control' id='correo' name='correo2' value='<?= $correo2 ?>' readonly>
  </div>
  <div class='col-md-6'>
    <label for='correo' class='control-label col-form-label'>Correo Alternativo 2</label>
    <input type='text' class='form-control' id='correo' name='correo3' value='<?= $correo3 ?>' readonly>
  </div>
</div>
<hr>
<div class='row'>
  <div class='col-md-12'>
    <label for='busq_nombre' class='control-label col-form-label'>Uso de CFDI</label>
    <div class='input-group'>
      <div class='input-group-prepend'>
        <span class='input-group-text'><i class=' fas fa-people-carry'></i></span>
      </div>
      <select class='select2 form-control custom-select' id='cfdis' name='cfdi' required>
        <option value="">Seleccione un Uso de CFDI</option>
        <?php
        $sql = "SELECT id, descripcion FROM sat_usocfdi WHERE estatus='1' ORDER BY descripcion";
        //----------------devBug------------------------------
        if ($debug == 1) {
          $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver CFDIs, notifica a tu Administrador', mysqli_error($link)));
          $canInsert = mysqli_affected_rows($link);
          echo '<br>SQL: ' . $sql . '<br>';
          echo '<br>Cant de Registros Cargados: ' . $canInsert;
        } else {
          $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver CFDIs, notifica a tu Administrador', mysqli_error($link)));
          $canInsert = mysqli_affected_rows($link);
        } //-------------Finaliza devBug------------------------------
        while ($dat = mysqli_fetch_array($resultXQuery )) {
          echo "<option value='" . $dat["id"] . "'>" . $dat["id"] . "-" . $dat['descripcion'] . "</option>";
        }
        ?>
      </select>
    </div>

    <div class='modal-footer' id='facturaVentaFooter'>
      <div class="row">

        <div class="col-md-12">
          <div id="bloquear-btn35" style="display:none;">
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
          <div id="desbloquear-btn35">
            <button type="button" onclick="modificarCliente()" id="btnModificar" class="btn btn-primary waves-effect waves-light">Modificar</button>

            <button type="submit" class="btn btn-success waves-effect waves-light">Crear</button>
            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
          </div>

        </div>
      </div>
      <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
      <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
      <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
      <script src="../dist/js/pages/forms/select2/select2.init.js"></script>