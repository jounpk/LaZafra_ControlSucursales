<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$pyme = $_SESSION['LZFpyme'];
$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0;
$volverFacturar = (!empty($_POST['volverFacturar'])) ? $_POST['volverFacturar'] : 0;
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------
if ($idVenta !='') {
  errorBD('No se reconoció la venta, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}

$sql = "SELECT COUNT(fact.id) AS conteoFact FROM ventas vta
INNER JOIN vtasfact fact ON fact.idVenta=vta.id
WHERE fact.idVenta=$idVenta";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de Facturas, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de Facturas, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$dat = mysqli_fetch_array($resultXQuery);
$conteoFact = $dat["conteoFact"];
if ($conteoFact < 1 || $volverFacturar == '1') {
?>
  <div class="row">

    <div class="col-md-8 col-sm-8">
      <p><i class="fas fa-search"></i> Busqueda por RFC</p>
      <select class="form-control select2" id="busq_rfc" style="width:100%;">
        <?php
        $sql = "SELECT DISTINCT dtsf.rfc
        FROM
        datosfisc dtsf WHERE uid IS NOT NULL ORDER BY rfc";
        //----------------devBug------------------------------
        if ($debug == 1) {
          $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el RFC, notifica a tu Administrador', mysqli_error($link)));
          $canInsert = mysqli_affected_rows($link);
          echo '<br>SQL: ' . $sql . '<br>';
          echo '<br>Cant de Registros Cargados: ' . $canInsert;
        } else {
          $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el RFC, notifica a tu Administrador', mysqli_error($link)));
          $canInsert = mysqli_affected_rows($link);
        } //-------------Finaliza devBug------------------------------
        echo "<option value=''>Selecciona un RFC</option>";
        while ($dat = mysqli_fetch_array($resultXQuery)) {
          echo "<option value='" . $dat["rfc"] . "'>" . $dat["rfc"] . "</option>";
        } ?>
      </select>
    </div>
    <div class="col-md-4 col-sm-4">
      <button type="button" class="btn btn-<?= $pyme ?> btn-circle muestraSombra mt-4" onClick="buscarXRFC()"><i class="fas fa-search text-white"></i></button></button>
    </div>
  </div>
  <div class="row">

    <div class="col-md-8 col-sm-8">
      <p><i class="fas fa-search"></i> Busqueda por Cliente</p>
      <select class="form-control select2" id="busq_nombre" style="width:100%;">
        <?php
        $sql = "SELECT
      DISTINCT dtsf.razonSocial,
      IF (dtsf.idCliente IS NULL,CONCAT(dtsf.nombre,' ',IF (dtsf.apellidos IS NULL, '', dtsf.apellidos)), CONCAT(cl.nombre,' ',cl.apodo)) AS nombreComp,
      dtsf.rfc,
      cl.id AS idCliente
      FROM
      datosfisc dtsf
      LEFT JOIN clientes cl ON dtsf.idCliente=cl.id WHERE dtsf.uid!=''";
        //----------------devBug------------------------------
        if ($debug == 1) {
          $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
          $canInsert = mysqli_affected_rows($link);
          echo '<br>SQL: ' . $sql . '<br>';
          echo '<br>Cant de Registros Cargados: ' . $canInsert;
        } else {
          $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
          $canInsert = mysqli_affected_rows($link);
        } //-------------Finaliza devBug------------------------------
        echo "<option value=''>Selecciona un nombre</option>";
        while ($dat = mysqli_fetch_array($resultXQuery)) {
          echo "<option value='" . $dat["rfc"] . "'>" . $dat["razonSocial"] . "</option>";
        } ?>
      </select>
    </div>
    <div class="col-md-4 col-sm-4 pt-2">

      <button type="button" class="btn btn-<?= $pyme ?> btn-circle muestraSombra mt-4" onClick="buscarXCliente()"><i class="fas fa-search text-white"></i></button></button>
    </div>
  </div>



  <hr>
  <div class="row">
    <div class="col-md-8 col-sm-8" id="resultadosBusq">
    </div>
    <div class="col-md-4 col-sm-4 px-4">
      <button class="btn btn-outline-<?= $pyme ?> btn-rounded" onClick="nuevoDatoFiscal()"> <i class="fas fa-plus"></i> Nuevo</button>
    </div>
  </div>

  <hr>
  <form action="" method="post" id="formCFDI">

    <div>
      <input type='hidden' value='<?= $idVenta ?>' name='idVenta'>
      <input type='hidden' value='<?= $idCliente ?>' name='idCliente'>
      <div class='row'>
        <div class='col-md-6'>
          <label for='nombre' class='control-label col-form-label'>RFC</label>
          <input type='text' class='form-control' pattern="^[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$" id='rfc' name='rfc' value='' readonly required>
        </div>
        <div class='col-md-6'>
          <label for='apellido' class='control-label col-form-label'>Razón Social</label>
          <input type='text' class='form-control' id='razonSocial' name='razonSocial' value='' readonly required>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='nombre' class='control-label col-form-label'>Nombre</label>
          <input type='text' class='form-control' id='nombre' name='nombre' value='' readonly>
        </div>
        <div class='col-md-6'>
          <label for='apellido' class='control-label col-form-label'>Apellidos</label>
          <input type='text' class='form-control' id='apellido' name='apellido' value='' readonly>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='calle' class='control-label col-form-label'>Calle</label>
          <input type='text' class='form-control' id='calle' name='calle' value='' readonly>
        </div>
        <div class='col-md-3'>
          <label for='noExt' class='control-label col-form-label'>No. Ext</label>
          <input type='text' class='form-control' id='noExt' name='noExt' value='' readonly>
        </div>
        <div class='col-md-3'>
          <label for='noInt' class='control-label col-form-label'>No. Int</label>
          <input type='text' class='form-control' id='noInt' name='noInt' value='' readonly>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='colonia' class='control-label col-form-label'>Colonia</label>
          <input type='text' class='form-control' id='colonia' name='colonia' value='' readonly>
        </div>
        <div class='col-md-6'>
          <label for='municipio' class='control-label col-form-label'>Municipio</label>
          <select class='select2 form-control custom-select' id='municipios' id='municipio' style="width:100%;" name='municipio' readonly>
            <option value="" selected>Seleccione un Estado</option>

            <?php
            $sql = "SELECT id, nombre FROM catmunicipios WHERE idCatEstado='$estado' ORDER BY nombre";
            //----------------devBug------------------------------
            if ($debug == 1) {
              $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
              $canInsert = mysqli_affected_rows($link);
              echo '<br>SQL: ' . $sql . '<br>';
              echo '<br>Cant de Registros Cargados: ' . $canInsert;
            } else {
              $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
              $canInsert = mysqli_affected_rows($link);
            } //-------------Finaliza devBug------------------------------
            while ($dat = mysqli_fetch_array($resultXQuery)) {
              if ($municipio == $dat['id']) {
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
          <select class='select2 form-control custom-select' id='estados' style="width:100%;" onchange='cambioMunicipio(this.value)' name='estado' readonly>
            <option></option>
            <?php
            $sql = "SELECT id, nombre FROM catestados  ORDER BY nombre";
            //----------------devBug------------------------------
            if ($debug == 1) {
              $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
              $canInsert = mysqli_affected_rows($link);
              echo '<br>SQL: ' . $sql . '<br>';
              echo '<br>Cant de Registros Cargados: ' . $canInsert;
            } else {
              $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
              $canInsert = mysqli_affected_rows($link);
            } //-------------Finaliza devBug------------------------------
            while ($dat = mysqli_fetch_array($resultXQuery)) {
              if ($estado == $dat['id']) {
                echo "<option value='" . $dat["id"] . "' selected>" . $dat['nombre'] . "</option>";
              } else {
                echo "<option value='" . $dat["id"] . "'>" . $dat['nombre'] . "</option>";
              }
            } ?>
          </select>
        </div>
        <div class='col-md-6 '>
          <label for='codpostal' class='control-label col-form-label'>Codigo Postal</label>
          <input type='text' class='form-control' id='codpostal' name='codpostal' value='' readonly>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='correo' class='control-label col-form-label'>Correo</label>
          <input type='text' class='form-control' id='correo' name='correo' value='' required readonly>
        </div>

        <div class='col-md-6'>
          <label for='telefono' class='control-label col-form-label'>Telefono</label>
          <input type="tel" pattern="[0-9() -]{14}" class="form-control phone-inputmask" id='telefono' name='telefono' value='' readonly>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <label for='correo' class='control-label col-form-label'>Correo Alternativo 1</label>
          <input type='text' class='form-control' id='correo' name='correo2' value='' readonly>
        </div>
        <div class='col-md-6'>
          <label for='correo' class='control-label col-form-label'>Correo Alternativo 2</label>
          <input type='text' class='form-control' id='correo' name='correo3' value='' readonly>
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
            <select class='select2 form-control custom-select' id='cfdis' style="width:80%;" name='cfdi' required readonly>
              <option value="">Seleccione un Uso de CFDI</option>
              <?php
              $sql = "SELECT id, descripcion FROM sat_usocfdi WHERE estatus='1' ORDER BY descripcion";
              //----------------devBug------------------------------
              if ($debug == 1) {
                $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
                $canInsert = mysqli_affected_rows($link);
                echo '<br>SQL: ' . $sql . '<br>';
                echo '<br>Cant de Registros Cargados: ' . $canInsert;
              } else {
                $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Cliente, notifica a tu Administrador', mysqli_error($link)));
                $canInsert = mysqli_affected_rows($link);
              } //-------------Finaliza devBug------------------------------
              while ($dat = mysqli_fetch_array( $resultXQuery)) {
                echo "<option value='" . $dat["id"] . "'>" . $dat["id"] . "-" . $dat['descripcion'] . "</option>";
              } ?>
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

                  <button type="submit" class="btn btn-success waves-effect waves-light">Crear</button>
                  <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                </div>

              </div>
            </div>
  </form>
  </div>
  <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
  <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>

  <script>
   
      $('#formCFDI select option:not(:selected)').attr('disabled', true);
  

    function nuevoDatoFiscal() {
      $("#formCFDI input").attr("readonly", false);
      $("#formCFDI select").attr("readonly", false);
      $("#formCFDI input").val("");
      $("#formCFDI select").val("");
      $("#resultadosBusq").html("");
      $("#busq_rfc").val("");
      $("#busq_nombre").val("");
      $("#btnModificar").remove();
      $("#formCFDI").append("<input type='hidden' value='<?= $idVenta ?>' name='idVenta'>");
      $('#formCFDI select option:not(:selected)').attr('disabled', false);
    }

    function modificarCliente() {
      $("#formCFDI input").attr("readonly", false);
      $("#formCFDI select").attr("readonly", false);
      $('#formCFDI select option:not(:selected)').attr('disabled', false);
      $("#formCFDI").append("<input type='hidden' value='1' name='modificar'>");
      $("#formCFDI").append("<input type='hidden' value='<?= $idVenta ?>' name='idVenta'>");

    }
    $("#formCFDI").submit(function(e) {
      e.preventDefault();
      datos = $("#formCFDI").serializeArray();
      bloqueoBtn("bloquear-btn35", 1); //  console.log(datos);
      $.post("../funciones/crearCFDIMulti.php",
        datos,
        function(respuesta) {
          var resp = respuesta.split('|');
          if (resp[0] == 0 || resp[0] == 3) {
            bloqueoBtn("bloquear-btn35", 2);

            Swal.fire({
              type: 'error',
              title: 'Error de Facturacion',
              text: resp[1],
              footer: "<b>Errores Encontrados:<b><br>" + resp[2]
            });


          } else if (resp[0] == 1) {
            // notificaSuc(resp[1]);
            location.reload();
          }


        });

    });

    function recuperarCliente(id_value) {
      $.post("../funciones/recuperarDatosClienteMulti.php", {
          id: id_value
        },
        function(respuesta) {
          var resp = respuesta.split('|');

          if (resp[0] == 0) {
            notificaBad(resp[1]);
          } else {

            $("#formCFDI").html(respuesta);
            $("#formCFDI").append("<input type='hidden' value='<?= $idVenta ?>' name='idVenta'>");
            //  $('#formCFDI select option:not(:selected)').attr('disabled',true);
          }
        });

    }
  </script>

<?php
} else if ($conteoFact >= 1) {
  $sql = "SELECT DISTINCT
  dts.rfc,
  dts.razonSocial,
  dts.correo,
  fact.uuid,
  fact.estatus,
  fact.doctoPDF,
  fact.doctoXML,
  fact.serie,
  fact.folio,
  suc.nombre AS sucursal,
  em.nombre AS empresa,
  IF(fact.portalCarga='0', 'Usuario Portal',CONCAT(usr.nombre,' ',usr.appat, ' ', usr.apmat)) AS usuarioReg
  FROM ventas vta
  INNER JOIN vtasfact vf ON vta.id=vf.idVenta
  INNER JOIN facturasgeneradas fg ON fg.id=vf.idFactgen
  INNER JOIN datosfisc dts ON dts.uid=fg.uidDatosFisc
  INNER JOIN facturas fact ON fact.uuid = fg.uuidFact
  INNER JOIN segusuarios usr ON fact.idUserReg= usr.id
  INNER JOIN sucursales suc ON vta.idSucursal=suc.id
  INNER JOIN empresas em ON em.id=suc.idEmpresa
  WHERE vta.id='$idVenta'
  ORDER BY fact.fechaReg DESC";
  $resXFacts = mysqli_query($link, $sql) or die('Problemas al consultar CFDIS anteriores, notifica a tu Administrador.');
  $total_facturas = mysqli_num_rows($resXFacts);
  $vuelta = 0;
  $tabla_historial = "<div class='col-md-12 table-responsive'><table class='table'><tbody>";
  $estatus_icon = "";
  $estatus = "";
  while ($datFacts = mysqli_fetch_array($resXFacts)) {
    $estatus = ($datFacts["estatus"] == '1') ? "<td class='texte-success'>Activa</td>" : $estatus;
    $estatus = ($datFacts["estatus"] == '2') ? "<td class='texte-primary'>En Proceso</td>" : $estatus;
    $estatus = ($datFacts["estatus"] == '3') ? "<td class='texte-danger'>Cancelación</td>" : $estatus;
    if ($vuelta == 0) {
      $RFC = $datFacts["rfc"];
      $razonSocial = $datFacts["razonSocial"];
      $correo = $datFacts["correo"];
      $uuid = $datFacts["uuid"];
      $pdfActual = $datFacts["doctoPDF"];
      $xmlActual = $datFacts["doctoXML"];
      $estatus_actual = $datFacts["estatus"];
      $sucursal = $datFacts["sucursal"];
      $empresa = $datFacts["empresa"];
      $folio = $datFacts["folio"];
      $serie = $datFacts["serie"];
      $estatus_icon = ($datFacts["estatus"] == '1') ? "fa-check-circle fa-7x text-success" : $estatus_icon;
      $estatus_icon = ($datFacts["estatus"] == '2') ? "fas fa-spin fa-spinner fa-7x text-primary" : $estatus_icon;
      $estatus_icon = ($datFacts["estatus"] == '3') ? "fas fas fa-times-circle fa-7x text-danger" : $estatus_icon;
      // echo $estatus_icon;
      $vuelta++;
    } else {
      $tabla_historial .= "
      <tr>
        <td colspan='4'>" . $datFacts['usuarioReg'] . "</td>
        " . $estatus . "
        <td>
        <button type='button' class='btn bg-success text-white' href='../funciones/verArchivo.php?ident=$uuid&tipo=1'><i class='far fa-file-code'></i> </button>
        <button type='button' class='btn bg-danger text-white'  href='../funciones/verArchivo.php?ident=$uuid&tipo=1'><i class='far fa-file-pdf'></i> </button>
        </td>
      </tr>      
      ";
      /*
      <a type='button' class='btn bg-success text-white' target='_blank' href='../" . $datFacts["doctoXML"] . "'><i class='far fa-file-code'></i> </a>
        <a type='button' class='btn bg-danger text-white' target='_blank' href='../" . $datFacts["doctoPDF"] . "'><i class='far fa-file-pdf'></i> </a>
      
      */

      $vuelta++;
    }
  }
  $tabla_historial .= "</tbody></table></div>";
?>
  <div class="row">

    <div class="col-md-8 col-sm-8">

      <p>RFC: <?= $RFC ?> </p>
      <p>Razón Social: <?= $razonSocial ?> </p>
      <p>Correo: <?= $correo ?> </p>
      <p>UUID: <?= $uuid ?></p>



    </div>

    <div class="col-md-4 col-sm-4">
      <p>Estatus</p>
      <i class="far <?= $estatus_icon ?>"></i>

      <p>Total de Facturas <?= $total_facturas ?></p>





    </div>




  </div>
  <div class="row">
    <div class="col-md-5 col-sm-5">
      <a type="button" class="btn bg-success text-white" href='../funciones/verArchivo.php?ident=<?= $uuid ?>&tipo=2'><i class="far fa-file-code"></i> Ver XML del CFDI </a>

    </div>

    <div class="col-md-5 col-sm-5">
      <a type="button" class="btn bg-danger text-white" href='../funciones/verArchivo.php?ident=<?= $uuid ?>&tipo=1'><i class="fas fa-file-pdf"></i> Ver PDF del CFDI </a>

    </div>

  </div>
  <br>
  <div class="row" id="complementosAnex">

    <?php
    $sqlComp = "SELECT
  comp.doctoPDF,
  comp.doctoXML,
  IF(comp.portalCarga='0', 'Usuario Portal',CONCAT(usr.nombre,' ',usr.appat, ' ', usr.apmat)) AS usuarioReg,
  detcomp.importePagado
  FROM
    complementos comp
    INNER JOIN detcomplementos detcomp ON detcomp.idComplemento = comp.id
    INNER JOIN facturas fact ON detcomp.uuidFact = fact.uuid 
    INNER JOIN segusuarios usr ON comp.idUserReg= usr.id
    AND detcomp.uuidFact ='$uuid'";
    $resXContComp = mysqli_query($link, $sqlComp) or die('Problemas al consultar CFDIS de Complementos anteriores, notifica a tu Administrador.');
    if (mysqli_num_rows($resXContComp) > 0) {
      echo '<center><i class=" fas fa-puzzle-piece text-primary"></i> Complementos Anexados</center>';

      echo "<div class='col-md-12 table-responsive'><table class='table'><tbody>";

      while ($dat = mysqli_fetch_array($resXContComp)) {
        echo "
  <tr>
    <td colspan='4'>" . $dat['usuarioReg'] . "</td>
    <td>$" . $dat['importePagado'] . "</td>
    <td>
    <a type='button' class='btn bg-success text-white' target='_blank' href='../" . $dat["doctoXML"] . "'><i class='far fa-file-code'></i> </a>
    <a type='button' class='btn bg-danger text-white' target='_blank' href='../" . $dat["doctoPDF"] . "'><i class='far fa-file-pdf'></i> </a>
    </td>
  </tr>      
  ";
      }
      echo "</tbody></table></div>";
    }
    ?>


  </div>
  <div class="row" id="resultadosFacturas">
    <?= $tabla_historial ?>

  </div>
  <hr>
  <form action="" method="post" id="formReenvio">
    <p>Reenviar Factura Emitida</p>

    <div class="row" id="correoReenviar">
      <input type="hidden" name="sucursal" value='<?= $sucursal ?>'>
      <input type="hidden" name="empresa" value='<?= $empresa ?>'>
      <input type="hidden" name="serie" value='<?= $serie ?>'>
      <input type="hidden" name="folio" value='<?= $folio ?>'>
      <input type="hidden" name="uuid" value='<?= $uuid ?>'>
      <input type="hidden" name="doctoXML" value='<?= $xmlActual ?>'>
      <input type="hidden" name="doctoPDF" value='<?= $pdfActual ?>'>
      <input type="hidden" name="idVenta" value='<?= $idVenta ?>'>
      <div class="col-md-8 col-sm-8">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="email_i"><i class="fas fa-at"></i></span>
          </div>
          <input type="email" class="form-control" id="email" aria-describedby="nombre" name="email" required>

        </div>
      </div>
      <div class="col-md-4 col-sm-4">
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

          <button type="submit" class="btn btn-success waves-effect waves-light">Enviar</button>
        </div>

      </div>

    </div>
  </form>
  <br>
  <div class="modal-footer">
    <div id="bloquear-btn2" style="display:none;">
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
    <div id="desbloquear-btn2">
      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
      <?php if ($estatus_actual == '3') { ?>
        <button type="submit" class="btn btn-success waves-effect waves-light" onclick="volverFacturar('<?= $idVenta ?>')">Crear Nueva</button>
      <?php } ?>
    </div>
  </div>

  <script>
    function volverFacturar(idVenta) {
      if (idVenta > 0) {
        //alert('Entra, idVenta: '+idVenta);
        $.post("../funciones/formularioFacturacionMulti.php", {
            idVenta: idVenta,
            volverFacturar: '1'
          },
          function(respuesta) {
            $("#lblFacturaVenta").html("Ticket No.: " + idVenta);
            $("#facturaVentaBody").html(respuesta);
          });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
    }
    $("#formReenvio").submit(function(e) {
      e.preventDefault();
      datos = $("#formReenvio").serializeArray();
      // console.log(datos);
      bloqueoBtn("bloquear-btn1", 1);

      $.post("../funciones/reenviarCorreoFacts.php",
        datos,
        function(respuesta) {
          var res = respuesta.split('|');
          if (res[0] == 1) {
            bloqueoBtn("bloquear-btn1", 2);
            notificaSuc(res[1]);
            $("#email").val("");


          } else {

            bloqueoBtn("bloquear-btn1", 2);
            notificaBad(res[1]);
            $("#email").focus();
          }
        });


    });
  </script>


<?php

}








function errorBD($error)
{
  echo '0|' . $error;
}

?>