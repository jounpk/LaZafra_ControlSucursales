<?php
session_start();

define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$id = (isset($_POST['ident']) and $_POST['ident'] >= 1) ? $_POST['ident'] : '';
$sql = "SELECT cp.*, IF(b.nombreCorto!='',b.nombreCorto, 'No Aplica') AS banco, b.id AS idB
			FROM sat_formapago cp
			LEFT JOIN catbancos b ON cp.idBanco = b.id
            WHERE cp.id ='$id'";
//echo $sql;
$result = mysqli_query($link, $sql) or die('<span class="text-danger"><center>Problemas al consultar los Datos. Notifica a tu Administrador.</center></span>' . $sql);
$dat = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!-- END META -->
    <!-- BEGIN STYLESHEETS -->



    <form class="form-horizontal" role="form" method="post" action="../funciones/editametodo.php">
        <input type="hidden" name="ident" value="<?= $dat['id']; ?>">

        <div class="modal-body" id="editaConducBody">
            <label for="rNombre" class="control-label col-form-label">Descripción del Pago</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="eNombre1"><i class="far fa-envelope"></i></span>
                </div>
                <input type="text" class="form-control" id="eNombre" aria-describedby="nombre" name="eNombre" value="<?= $dat['nombre']; ?>" oninput="limpiaCadena(this.value,'eNombre');" readonly>
            </div>
            <label for="rClave" class="control-label col-form-label">Clave</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="eClave1"><i class="far fa-envelope"></i></span>
                </div>
                <input type="text" value="<?= $dat['clave']; ?>" class="form-control" id="eClave" aria-describedby="" name="eClave" oninput="limpiaCadena(this.value,'eClave');" required>
            </div>
            <label for="eBanco" class="control-label col-form-label">Banco Aceptado <small>* Si es necesario</small></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="eBanco1"><i class="far fa-envelope"></i></span>
                </div>
                <?php
                $sql = "SELECT id, nombreCorto FROM catbancos WHERE estatus = 1 ORDER BY nombreCorto ASC";
                $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                ?>
                <select class="select2 form-control " name="eBanco" id="eBanco" onchange="" style="width: 90.8%;">
                    <option value="">Asigna un Banco</option>

                    <?php

                    while ($datselect = mysqli_fetch_array($res)) {
                        if ($dat['idB'] == $datselect['id']) {
                            echo '<option value="' . $datselect['id'] . '" selected>' . $datselect['nombreCorto'] . '</option>';
                        } else {
                            echo '<option value="' . $datselect['id'] . '">' . $datselect['nombreCorto'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <label for="rCierra" class="control-label col-form-label">Cierre de Pago</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="rCierra1"><i class="far fa-envelope"></i></span>
                </div>
                <div class="col-sm-9">
                    <div class="custom-control custom-radio">
                        <?php
                        if ($dat['cierraPago'] == 1) {

                            echo '<input type="radio" id="rSiCierra" value="1" name="eCierre" required checked> <label for="rSiCierra">Si</label>  </div>';
                            echo '<div class="custom-control custom-radio">
                        <input type="radio" id="rNoCierra" value="0" name="eCierre" required=""><label for="rNoCierra">No</label></div>';
                        } else {
                            echo '<input type="radio" id="rSiCierra" value="1" name="eCierre" required=""> <label for="rSiCierra">Si</label></div>';
                            echo '<div class="custom-control custom-radio">
                        <input type="radio" id="rNoCierra" value="0" name="eCierre" required="" checked><label for="rNoCierra">No</label></div>';
                        }

                        ?>



                    </div>
                </div>
                <div class="modal-footer">
                    <div id="bloquear-btn1" style="display:none;">
                        <button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                        </button>
                        <button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                        </button>
                        <button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                    <div id="desbloquear-btn1">
                        <input type="hidden" id="idMarca">

                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">Modificar</button>
                    </div>
                </div>
    </form>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $("#eBanco").select2({
            language: {

                noResults: function() {

                    return "No hay resultado";
                },
                searching: function() {

                    return "Buscando..";
                }
            }
        });
    </script>
