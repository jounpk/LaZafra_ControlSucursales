<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$debug = 0;
$pyme = $_SESSION['LZFpyme'];
$idSucursal = $_SESSION['LZFidSuc'];
$idVenta = $_POST['idVenta'];
//----------------devBug------------------------------
if ($debug == 1) {
    print_r($_POST);
    echo '<br><br>';
} else {
    error_reporting(0);
}  //-------------Finaliza devBug------------------------------
?>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                </div>
                <input type="text" class="control-form" placeholder="Ingresa el Folio" id="idCotizacion" onkeyup="cambiaMayusculas(this.value, 'idCotizacion');">
            </div>
        </div>

    </div>
    <div class="col-lg-2">
        <button type="button" class="btn bg-<?= $pyme ?> btn-circle cardDeBusquedaSuc" onclick="buscaCotizacion();"><i class="fa fa-search text-white"></i> </button>

    </div>
</div>
<div id="cotizacion" style="display: none;">
    
</div>

<!-- /.modal-content -->
<!-- /.modal-dialog -->
<script>
    $("#ingredienteAct").select2();
    $("#descIngActivo").select2();
</script>