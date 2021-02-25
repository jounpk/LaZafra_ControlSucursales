<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$ident_pago = (isset($_POST['ident_pago'])) ? $_POST['ident_pago'] : '';
$pyme = $_SESSION['LZFpyme'];
if ($ident_pago == '') {
  errorBD('No se reconoció el pago, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}
//----------------------------------Obtener datos para el complemento-----------------------
$sql = "SELECT
pc.monto,
pc.residual,
s_fp.clave,
df.uid AS idDtsFisc,
df.rfc,
df.razonSocial,
df.nombre,
df.apellidos,
df.calle,
df.municipio,
df.estado,
df.codigoPostal,
df.colonia,
df.tel,
df.correo,
df.correo2,
df.correo3,
df.noExt,
df.noInt,
cr.idCliente,
suc.idEmpresa
FROM
pagoscreditos pc 
INNER JOIN sat_formapago s_fp ON  s_fp.id=pc.idFormaPago
INNER JOIN creditos cr ON cr.id=pc.idCredito
INNER JOIN sucursales suc ON suc.id=pc.idSucursal
LEFT JOIN datosfisc df ON df.idCliente =cr.idCliente AND df.idEmpresa = suc.idEmpresa
WHERE pc.id='$ident_pago'";
//echo $sql;
$respXcomp =  mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Datos de Complementos.' . mysqli_error($link)));
$arrayXComp = mysqli_fetch_array($respXcomp);
$rfc=$arrayXComp["rfc"];
$readonly_value="readonly";
if ($arrayXComp['idDtsFisc'] == '') {
  echo '<div class="alert alert-warning" role="alert">
  No hay datos fiscales relacionados con el <b>Cliente</b>.
</div>';
$readonly_value="";
}
?>
  <form action="" method="post" id="formComplementoCFDI">

    <div>
      <input type='hidden' value='<?= $ident_pago ?>' name='idPagoCredito'>
      <input type='hidden' value='<?= $arrayXComp["idCliente"]?>' name='idCliente'>
      <input type='hidden' value='<?= $arrayXComp["idEmpresa"]?>' name='idEmpresa'>
      <input type='hidden' value='<?= $arrayXComp["idDtsFisc"]?>' name='idDtsFisc'>

      <div class='row'>
        <div class='col-md-6'>
          <label for='nombre' class='control-label col-form-label'>RFC</label>
          <input type='text' class='form-control' pattern="^[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$" id='rfc' name='rfc' value='<?=$arrayXComp["rfc"]?>' <?=$readonly_value?> required>
        </div>
        <div class='col-md-6'>
          <label for='apellido' class='control-label col-form-label'>Razón Social</label>
          <input type='text' class='form-control' id='razonSocial' name='razonSocial' value='<?=$arrayXComp["razonSocial"]?>' <?=$readonly_value?> required>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='nombre' class='control-label col-form-label'>Nombre</label>
          <input type='text' class='form-control' id='nombre' name='nombre' value='<?=$arrayXComp["nombre"]?>' <?=$readonly_value?>>
        </div>
        <div class='col-md-6'>
          <label for='apellido' class='control-label col-form-label'>Apellidos</label>
          <input type='text' class='form-control' id='apellido' name='apellido' value='<?=$arrayXComp["apellidos"]?>' <?=$readonly_value?>>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='calle' class='control-label col-form-label'>Calle</label>
          <input type='text' class='form-control' id='calle' name='calle' value='<?=$arrayXComp["calle"]?>' <?=$readonly_value?>>
        </div>
        <div class='col-md-3'>
          <label for='noExt' class='control-label col-form-label'>No. Ext</label>
          <input type='text' class='form-control' id='noExt' name='noExt' value='<?=$arrayXComp["noExt"]?>' <?=$readonly_value?>>
        </div>
        <div class='col-md-3'>
          <label for='noInt' class='control-label col-form-label'>No. Int</label>
          <input type='text' class='form-control' id='noInt' name='noInt' value='<?=$arrayXComp["noInt"]?>' <?=$readonly_value?>>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='colonia' class='control-label col-form-label'>Colonia</label>
          <input type='text' class='form-control' id='colonia' name='colonia' value='<?=$arrayXComp["colonia"]?>' <?=$readonly_value?>>
        </div>
        <div class='col-md-6'>
          <label for='municipio' class='control-label col-form-label'>Municipio</label>
          <select class='select2 form-control custom-select' id='municipios'  style="width:100%;" name='municipio' <?=$readonly_value?>>
            <option value="">Seleccione un Estado</option>

            <?php
            $sql = "SELECT id, nombre FROM catmunicipios WHERE idCatEstado='".$arrayXComp["estado"]."' ORDER BY nombre";
            $resMun = mysqli_query($link, $sql) or die('Problemas al consultar municipios disponibles, notifica a tu Administrador.');
           
            while ($dat = mysqli_fetch_array($resMun)) {
              if ($arrayXComp["municipio"] == $dat['id']) {
                echo "<option value='" . $dat["id"] . "' selected>" . $dat['nombre'] . "</option>";
              } else {
                echo "<option value='" . $dat["id"] . "'>" . $dat['nombre'] . "</option>";
              }
            } ?>
          </select>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='estado' class='control-label col-form-label'>Estado</label>
          <select class='select2 form-control custom-select' id='estados' style="width:100%;" onchange='cambioMunicipio(this.value)' name='estado' <?=$readonly_value?>>
            <option></option>
            <?php
            $sql = "SELECT id, nombre FROM catestados  ORDER BY nombre";
            $resEst = mysqli_query($link, $sql) or die('Problemas al consultar estados disponibles, notifica a tu Administrador.');
            while ($dat = mysqli_fetch_array($resEst)) {
              if ($arrayXComp["estado"] == $dat['id']) {
                echo "<option value='" . $dat["id"] . "' selected>" . $dat['nombre'] . "</option>";
              } else {
                echo "<option value='" . $dat["id"] . "'>" . $dat['nombre'] . "</option>";
              }
            } ?>
          </select>
        </div>
        <div class='col-md-6 '>
          <label for='codpostal' class='control-label col-form-label'>Codigo Postal</label>
          <input type='text' class='form-control' id='codpostal' name='codpostal' value='<?=$arrayXComp["codigoPostal"]?>' <?=$readonly_value?>>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='correo' class='control-label col-form-label'>Correo</label>
          <input type='text' class='form-control' id='correo' name='correo' value='<?=$arrayXComp["correo"]?>' <?=$readonly_value?>>
        </div>

        <div class='col-md-6'>
          <label for='telefono' class='control-label col-form-label'>Telefono</label>
          <input type="tel" pattern="[0-9() -]{14}" class="form-control phone-inputmask" id='telefono' name='telefono' value='<?=$arrayXComp["tel"]?>' <?=$readonly_value?>>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='correo' class='control-label col-form-label'>Correo Alternativo 1</label>
          <input type='text' class='form-control' id='correo' name='correo2' value='<?=$arrayXComp["correo2"]?>' <?=$readonly_value?>>
        </div>
        <div class='col-md-6'>
          <label for='correo' class='control-label col-form-label'>Correo Alternativo 2</label>
          <input type='text' class='form-control' id='correo' name='correo3' value='<?=$arrayXComp["correo3"]?>' <?=$readonly_value?>>
        </div>
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

                  <button type="submit" class="btn btn-success waves-effect waves-light">Crear</button>
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