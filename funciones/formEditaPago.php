<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$id = (isset($_POST['ident']) and $_POST['ident'] >= 1) ? $_POST['ident'] : '';
$idSucursal = $_SESSION['LZFidSuc'];

$sql = "SELECT gstos.*  FROM pagos gstos WHERE idSucursal='$idSucursal' AND gstos.id='$id'";
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



    <form class="form-horizontal" role="form" id="formEdiPago" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ident" value="<?= $dat['id']; ?>">

        <div class="modal-body" id="editaConducBody">
        <label for="rdescGasto" class="control-label col-form-label">Descripción del Pago</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="rdescGasto1"><i class="far fa-envelope"></i></span>
                    </div>
                   <!-- <input type="text" class="form-control" id="rdescGasto" value="<?=$dat['descripcion']?>" aria-describedby="nombre" name="rdescGasto" oninput="limpiaCadena(this.value,'rdescGasto');" required>-->
                   <select class="form-control"  name="rdescGasto" required>
                                                <option value=""></option>
                                                <?php 
                                                    $sql="SELECT id, nombre FROM catservicios WHERE estatus='1' ORDER BY nombre";
                                                    $resXserv = mysqli_query($link, $sql) or die('Problemas al listar los Gastos y Pagos, notifica a tu Administrador');
                                                    while($datServ=mysqli_fetch_array($resXserv)){
                                                      if($datServ["id"]==$dat['idServicio']){
                                                        echo "<option value='".$datServ["id"]."' selected>".$datServ["nombre"]."</option>";

                                                      }else{
                                                        echo "<option value='".$datServ["id"]."'>".$datServ["nombre"]."</option>";

                                                      }
                                                    }

                                                ?>
                                           </select>
                  </div>
                  <label for="rMonto" class="control-label col-form-label">Monto</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="rMonto1">$</span>
                    </div>
                    <input type="text" class="form-control" value="<?=$dat['monto']?>" id="rMonto" aria-describedby="nombre" name="rMonto" oninput="limpiaCadena(this.value,'rMonto');" required>
                  </div>
                  <label for="rfechavence" class="control-label col-form-label">Fecha Vencimiento</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="rfechavence1">$</span>
                    </div>
                    <input class="form-control datepicker" type="text" min="<?=date('Y-m-d');?>" value="<?= date_format(date_create($dat['fechaVencimiento']),'d-m-Y'); ?>" id="rfechavence" name="rfechavence" />
                  </div>
                  <label for="rdocto" class="control-label col-form-label">Recibo del Pago</label> <small class="text-danger">* Por seguridad, al editar carga de nuevo el documento.</small>

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon5"><i class=" fas fa-industry"></i></span>
                    </div>
                    <input type="file" name="rdocto" id="rdocto" title="Documento del Pago" class="form-control" required>
                  </div>
                  <div class="modal-footer">
                    <div id="bloquear-btn2" style="display:none;">
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
                    <div id="desbloquear-btn2">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                      <button type="submit" id="registrarGastobtn" class="btn btn-success waves-effect waves-light">Editar</button>
                    </div>
                  </div>
    </form>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js" charset="UTF-8"></script>

    <script>
        $('.datepicker').datepicker({
          language: 'es',
          format: 'dd-mm-yyyy',
        });
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
        $("#formEdiPago").submit(function(event) {
        event.preventDefault();
        var formElement = document.getElementById("formEdiPago");
        var formGasto = new FormData(formElement);
        $.ajax({
          type: 'POST',
          url: "funciones/editaPago.php",
          data: formGasto,
          processData: false,
          contentType: false,

          success: function(respuesta) {
            
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              bloqueoBtn("bloquear-btn2", 1);
              notificaSuc(resp[1]);
              setTimeout(function() {
                location.reload();
              }, 1000);
            } else {
              bloqueoBtn("desbloquear-btn2", 2);
              notificaBad(resp[1]);
            }
          }
        });
      });
    </script>