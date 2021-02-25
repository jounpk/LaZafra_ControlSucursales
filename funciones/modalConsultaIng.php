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

</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                </div>
                <select class="select2 form-control custom-select" id="ingredienteAct" style="width: 80%; height:100%;">
                    <?php
                    #  /*
                    $sql = "SELECT DISTINCT(c.nombre)
                        FROM productos p
                        INNER JOIN catingact c ON p.idTagsIngredienteActivo LIKE CONCAT('%',c.nombre,'%')
                        WHERE c.estatus = '1'";
                    $res = mysqli_query($link, $sql);
                    $cont = 0;
                    while ($rows = mysqli_fetch_array($res)) {
                        if ($cont == 0) {
                            echo '<option value="">Ingresa el Ingrediente</option>';
                        }
                        echo '<option value="' . $rows['nombre'] . '">' . $rows['nombre'] . '</option>';
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
                </div>
                <select class="select2 form-control custom-select" id="descIngActivo" style="width: 80%; height:100%;">
                    <?php
                    $sql = "SELECT DISTINCT(c.nombre), c.descripcion
                    FROM productos p
                    INNER JOIN catingact c ON p.idTagsIngredienteActivo LIKE CONCAT('%',c.nombre,'%')
                    WHERE c.estatus = '1'";
                    $res = mysqli_query($link, $sql);
                    echo '<option value="">Escribe el Uso del Producto</option>';
                    while ($rows = mysqli_fetch_array($res)) {
                        echo '<option value="' . $rows['nombre'] . '">' . $rows['descripcion'] . '</option>';
                    }
                    echo '</select>';

                    ?>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <div class="input-group input-group-lg text-center">
                <button type="button" class="btn bg-<?= $pyme ?> btn-circle cardDeBusquedaSuc" onclick="buscarIngredienteActivo(<?= $idVenta; ?>);"><i class="fa fa-search text-white"></i> </button>

            </div>
        </div>
    </div>
</div>
<div class="row" id="tablaRespIngrediente">

</div>
</div>
<div class="modal-footer">
    <?php
    $sql = "SELECT COUNT(tp.id) AS solOpen
            FROM traspasos tp
			INNER JOIN solicitudestrasp stp ON tp.idSolicitud = stp.id
            WHERE stp.estatus = 1 AND tp.idSucEntrada = '$idSucursal' AND tp.estatus = 1";
    //echo $sql;
    $res = mysqli_query($link, $sql);
    $solOp = mysqli_fetch_array($res);
    $solOpen = ($solOp['solOpen'] >= 1) ? '' : 'disabled';
    ?>
    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Cerrar</button>
</div>
</div>
<!-- /.modal-content -->
<!-- /.modal-dialog -->
<script>
    $("#ingredienteAct").select2();
    $("#descIngActivo").select2();
</script>