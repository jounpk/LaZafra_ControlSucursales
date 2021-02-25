<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$ident_pago = (isset($_POST['ident_pago'])) ? $_POST['ident_pago'] : '';
$idVenta = (isset($_POST['idVenta'])) ? $_POST['idVenta'] : '';
$pyme = $_SESSION['LZFpyme'];
if ($ident_pago == '') {
  errorBD('No se reconoció el pago, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}
//----------------------------------Obtener datos para el complemento-----------------------
$sql = "SELECT
fg.uidDatosFisc,
df.rfc,
df.razonSocial,
df.correo,
dp.apiKey,
dp.secretKey,
fg.uuidFact,
fact.rfcEm,
fact.rfcRe
FROM
ventas vta
INNER JOIN vtasfact vf ON vf.idVenta =vta.id AND vta.id='$idVenta'
INNER JOIN facturasgeneradas fg ON fg.id=vf.idFactgen
INNER JOIN datosfisc df ON df.uid = fg.uidDatosFisc
INNER JOIN datospacs dp ON dp.idEmpresa = df.idEmpresa
INNER JOIN facturas fact ON fact.uuid=fg.uuidFact AND fact.estatus=1";
//echo $sql;
$respXcomp =  mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Datos de Complementos.' . mysqli_error($link)));
$num_fact = mysqli_num_rows($respXcomp);
if ($num_fact == 0) {
  echo '<div class="alert alert-warning" role="alert">
  Factura primero la venta para poder hacer un <b>Complemento de Pago</b>.
</div>';
} else {
  $arrayXComp = mysqli_fetch_array($respXcomp);

?>
  <form action="" method="post" id="formComplementoCFDI">

    <div>
      <input type='hidden' value='<?= $ident_pago ?>' name='idPagoCredito'>
      <input type='hidden' value='<?= $arrayXComp["uidDatosFisc"] ?>' name='uidDatosFisc'>
      <input type='hidden' value='<?= $arrayXComp["correo"] ?>' name='correo'>
      <input type='hidden' value='<?= $arrayXComp["rfcRe"] ?>' name='rfcRe'>
      <input type='hidden' value='<?= $arrayXComp["rfcEm"] ?>' name='rfcEm'>

      <div class="row">
        <div class="col-md-6">
          <label><b>RFC Receptor: </b><?= $arrayXComp['rfc'] ?></label>
          <label><b>Razón Social Receptor: </b><?= $arrayXComp['razonSocial'] ?></label>

        </div>
        <div class="col-md-6">
          <label><b>UUID Factura: </b><?= $arrayXComp['uuidFact'] ?></label>
          <label><b>Folio Venta: </b>#<?= $idVenta ?></label>

        </div>
      </div>

    </div>
    <hr>
    <h3>Detalles de Complemento de Pago</h3>
    <div class="row">
      <div class="col-md-12">
        <?php
        $sql = "SELECT 
    pc.monto,
    s_fp.nombre
    FROM pagoscreditos pc 
    INNER JOIN sat_formapago s_fp ON s_fp.id = pc.idformapago
    WHERE pc.id='$ident_pago'";
        $respXPago =  mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Datos de Complementos.' . mysqli_error($link)));
        $arrayXPago = mysqli_fetch_array($respXPago);
        ?>
        <label><b>Monto: </b>$<?= $arrayXPago['monto'] ?></label><br>
        <label><b>Forma Pago: </b><?= $arrayXPago['nombre']  ?></label>

      </div>



    </div>
    <br>
    <div class='modal-footer' id='facturaVentaFooter'>
      <div class="row">

        <div class="col-md-12">
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
          <div id="desbloquear-btn1">
          <?php
        }
        if ($num_fact > 0) { ?>
            <button type="submit" class="btn btn-success waves-effect waves-light">Crear</button>
          <?php } ?>
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
          </div>

        </div>
      </div>
  </form>
  <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
  <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
  <script>
    $("#formComplementoCFDI").submit(function(e) {
      e.preventDefault();
      datos = $("#formComplementoCFDI").serializeArray();
      // bloqueoBtn("bloquear-btn1", 1); //  console.log(datos);
      bloqueoBtn("bloquear-btn1", 1);

      $.post("../funciones/crearComplementoCFDI.php",
        datos,
        function(respuesta) {
          var resp = respuesta.split('|');
          if (resp[0] == 0 || resp[0] == 3) {
            bloqueoBtn("bloquear-btn1", 2);

            Swal.fire({
              type: 'error',
              title: 'Error de Complementos de Pago',
              text: resp[1],
              footer: "<b>Errores Encontrados:<b><br>" + resp[2]
            });


          } else if (resp[0] == 1) {
            // notificaSuc(resp[1]);
            location.reload();
          }


        });

    });
  </script>

  <?php




  function errorBD($error)
  {
    echo '0|' . $error;
  }
  ?>