<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$debug = 0;
$pyme = $_SESSION['LZFpyme'];
$idSucursal = $_SESSION['LZFidSuc'];

?>


<div class="row">

</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="mdi mdi-barcode-scan"></i></span>
                </div>
                <select class="select2 form-control custom-select" id="codBarraBusqSuc" style="width:75%;">
                    <?php
                    #  /*
                    $sql = "SELECT codBarra,id,prioridad FROM productos WHERE estatus = 1 GROUP BY codBarra ORDER BY descripcion ASC";
                    //echo $sql;
                    $res = mysqli_query($link, $sql);
                    $cont = 0;
                    while ($rows = mysqli_fetch_array($res)) {
                        if ($cont == 0) {
                            echo '<option value="">Selecciona Código de Barras</option>';
                        }
                        if (isset($rows['codBarra']) && $rows['codBarra'] != '') {
                            echo '<option value="' . $rows['id'] . '">' . $rows['codBarra'] . '</option>';
                        }
                        $cont++;
                    }
                    echo '</select>';
                    #  */
                    ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="mdi mdi-barley"></i></span>
                </div>
                <select class="select2 form-control custom-select" id="productoBusqSuc" style="width: 80%; height:100%;">
                    <?php
                    #  /*
                    $sql = "SELECT id,descripcion FROM productos WHERE estatus = 1";
                    //echo $sql;
                    $res = mysqli_query($link, $sql);
                    echo '<option value="">Ingresa el Nombre del Producto</option>';
                    while ($rows = mysqli_fetch_array($res)) {
                        echo '<option value="' . $rows['id'] . '">' . $rows['descripcion'] . '</option>';
                    }
                    echo '</select>';
                    #  */
                    ?>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <div class="input-group input-group-lg text-center">
                <button type="button" class="btn bg-<?= $pyme ?> btn-circle cardDeBusquedaSuc" onclick="buscarProductoenSucursal();"><i class="fa fa-search text-white"></i> </button>
            </div>
        </div>
    </div>
</div>
<div class="row" id="tablaRespBusquedaSuc">

</div>
<?php
$sql = "SELECT COUNT(tp.id) AS solOpen
                        FROM traspasos tp
						INNER JOIN solicitudestrasp stp ON tp.idSolicitud = stp.id
                        WHERE
                        stp.estatus = 1 AND tp.idSucEntrada = '$idSucursal' AND tp.estatus = 1";
//echo $sql;
$res = mysqli_query($link, $sql);
$solOp = mysqli_fetch_array($res);
$solOpen = ($solOp['solOpen'] >= 1) ? '' : 'disabled';
?>
</div>

<div class="modal-footer">

    <button type="button" class="btn btn-info waves-effect" id="verProdSolicitados" onClick="listaSolicitudesAbiertas();" <?= $solOpen; ?>><i class="fas fa-edit"></i> Ver Productos Solicitados</button>
    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Cerrar</button>
</div>
</div>
<!-- /.modal-content -->
<!-- /.modal-dialog -->
<script>
    $("#codBarraBusqSuc").select2();
    $("#productoBusqSuc").select2();
</script>