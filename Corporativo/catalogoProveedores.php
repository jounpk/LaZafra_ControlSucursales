<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Favicon icon -->
  <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<body>
  <!-- Preloader - style you can find in spinners.css -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <!-- Main wrapper - style you can find in pages.scss -->
  <div id="main-wrapper">
    <!-- Topbar header - style you can find in pages.scss -->
    <header class="topbar">
      <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <?= $info->customizer('2'); ?>
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
          <!-- toggle and nav items -->
          <ul class="navbar-nav float-left mr-auto"> </ul>
          <!-- Right side toggle and nav items -->
          <ul class="navbar-nav float-right">
            <!-- Messages -->
            <audio id="player" src="../assets/images/soundbb.mp3"> </audio>

            <li class="nav-item dropdown border-right" id="notificaciones">
            </li>
            <!-- User profile  -->
            <?= $info->generaMenuUsuario(); ?>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <?= $info->generaMenuLateral(); ?>
    <!-- Page wrapper  -->
    <div class="page-wrapper">
      <!-- Bread crumb and right sidebar toggle -->

      <!-- Container fluid  -->
      <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
          <div>
            <h2 class="text-<?= $pyme; ?>"><?= $info->nombrePag; ?></h2>
            <h4><?= $info->detailPag; ?></h4>
          </div>
          <div class="ml-auto">
            <h4><b><?= $info->nombreSuc; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4>
          </div>
          <br><br>
        </div>

        <!-- aqui inserto pruebas de lo que se me ocurra jaja -->

        <!-- aqui acaban las pruebas de lo que se me ocurra jaja -->

        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Listado de Proveedores</h4>
              </div>
              <div class="card-body">
                <div class="text-right">
                  <button class="btn btn-outline-<?= $pyme; ?> btn-rounded" data-toggle="modal" data-target="#modalNewProveedor"><i class="fas fa-plus"></i> Nuevo Proveedor</button>
                </div>
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <div class="table-responsive">
                    <table class="table product-overview" id="zero_config">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Empresa</th>
                          <th>Proveedor</th>
                          <th>RFC</th>
                          <th>Correo</th>
                          <th class="text-center">Teléfono</th>
                          <th class="text-center">Crédito</th>
                          <th class="text-center">Limite de Crédito</th>
                          <th class="text-center">Estatus</th>
                          <th class="text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sqlCon = "SELECT p.*, e.nameCto AS nomEmpresa
                                              FROM proveedores p
                                              LEFT JOIN empresas e ON p.idEmpresa = e.id
                                              ORDER BY p.estatus ASC, p.nombre ASC";
                        $resCon = mysqli_query($link, $sqlCon) or die('Problemas al consultar los Proveedores, notifica a tu Administrador');
                        $cont = 0;
                        while ($prov = mysqli_fetch_array($resCon)) {
                          $id = $prov['id'];
                          $estatus = ($prov['estatus'] == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';
                          $btnCambiaEstatus = ($prov['estatus'] == 1) ? '<a href="javascript:void(0);" title="Deshabilitar" onclick="cambiaEstatus(\'activar-btn' . $id . '\', ' . $id . ',' . $prov['estatus'] . ');" class="text-danger"><i class="fas fa-trash"></i></a>&nbsp;&nbsp;' : '<a href="javascript:void(0);" title="Habilitar" onclick="cambiaEstatus(\'activar-btn' . $id . '\', ' . $id . ',' . $prov['estatus'] . ');" class="text-warning"><i class="fas fa-exchange-alt"></i></a>&nbsp;&nbsp;';
                          switch ($prov['tipoLimite']) {
                            case '1':
                              $tiempoCred = 'día(s)';
                              break;
                            case '2':
                              $tiempoCred = 'mes(es)';
                              break;
                            case '3':
                              $tiempoCred = 'semana(s)';
                              break;
                            case '4':
                              $tiempoCred = 'año(s)';
                              break;

                            default:
                              $tiempoCred = '';
                              break;
                          }
                          echo '<tr>
                                          <td class="text-center">' . ++$cont . '</td>
                                          <td>' . $prov['nomEmpresa'] . '</td>
                                          <td id="nombreProv-' . $id . '">' . $prov['nombre'] . '</td>
                                          <td id="rfcProv-' . $id . '">' . $prov['rfc'] . '</td>
                                          <td id="correoProv-' . $id . '">' . $prov['correoPagos'] . '</td>
                                          <td id="telProv-' . $id . '" class="text-center">' . $prov['telEmpresa'] . '</td>
                                          <td class="text-center">' . $prov['cantidadLimite'] . ' ' . $tiempoCred . '</td>
                                          <td class="text-center">$' . number_format($prov['limiteCredito'], 2, '.', ',') . '</td>
                                          <td class="text-center" id="estatus-' . $id . '">' . $estatus . '</td>
                                          <td class="text-center" id="botonesProveedor">
                                       
                                            <a href="javascript:void(0);" class="text-info" onclick="editaProveedor(' . $id . ')" data-toggle="modal" data-target="#modalEditProveedor"><i class="fas fa-pencil-alt"></i></a>&nbsp;&nbsp;

                                            <b id="desactivar-btn' . $id . '">' . $btnCambiaEstatus . '</b>
                                            
                                            <b id="activar-btn" style="display:none;"><a href="javascript:void(0);" class="spinner-border spinner-border-sm text-danger" role="estatus"><span class="sr-only">Cargando...</span></a>&nbsp;&nbsp;</b>
                                            
                                            <b id="desactivar2-btn' . $id . '" data-toggle="modal" data-target=".bs-example-modal-lg" ><a href="javascript:void(0);" onclick="listarContactos(' . $id . ', ' . $prov['estatus'] . ');" class="text-warning"><i class="fas fa-users"></i></a></b>

                                            <b id="activar2-btn' . $id . '" style="display:none;"><a href="javascript:void(0);" class="spinner-border spinner-border-sm text-warning"  role="estatus"><span class="sr-only">Cargando...</span></a>&nbsp;&nbsp;</b>

                                          
                                          </td>
                                        </tr>';
                        }
                        ?>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- otro <optgroup></optgroup>o otro-->
          <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header  bg-<?= $pyme; ?>">
                  <div class="card-header bg-<?= $pyme; ?>">
                    <h4 class="m-b-0 text-white" id="tituloContactos">Contactos</h4>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                  <div id="myDIV" style="display:none">
                    <input type="text" class="form-control" placeholder="Nombre Contacto"> <br>
                    <input type="text" class="form-control" placeholder="Telefono Contacto"> <br>
                    <input type="text" class="form-control" placeholder="Correo Sucursal">
                    <button class="btn btn-success">Guardar</button>
                  </div>

                  <div class="card border-<?= $pyme; ?>">
                    <div class="card-body" id="bodyContactos">
                      <div class="text-right">
                        <button class="btn btn-<?= $pyme; ?> btn-rounded" disabled><i class="fas fa-plus"></i> Nuevo Contacto</button>
                      </div>
                      <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                        <div class="table-responsive">
                          <table class="table  product-overview">
                            <thead>
                              <tr>
                                <th>Nombre</th>
                                <th class="text-center">Teléfono</th>
                                <th>Correo</th>
                                <th class="text-center">Acciones</th>

                              </tr>
                            </thead>

                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- fin del otro otro -->
          <!--

                <div class="col-md-4 col-lg-4">
                  <div class="card border-<?= $pyme; ?>">
                      <div class="card-header bg-<?= $pyme; ?>">
                        <h4 class="m-b-0 text-white" id="tituloContactos">Contactos</h4>
                      </div>
                      <div class="card-body" id="bodyContactos">
                        <div class="text-right">
                          <button class="btn btn-<?= $pyme; ?> btn-rounded" disabled><i class="fas fa-plus"></i> Nuevo Contacto</button>
                        </div>
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table product-overview">
                              <thead>
                                <tr>
                                  <th>Nombre</th>
                                  <th class="text-center">Teléfono</th>
                                  <th>Correo</th>
                                  <th class="text-center">Acciones</th>
                                </tr>
                              </thead>
                              <tbody>

                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>-->
        </div>


        <!-- sample modal content -->
        <div id="modalNewProveedor" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="lblNewProveedor">Nuevo Proveedor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <form role="form" method="post" action="../funciones/registraNuevoProveedor.php" id="formNewProveedor">

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="idEmpresa1"><i class="far fa-building"></i></span>
                    </div>
                    <select class="form-control" id="idEmpresa" name="idEmpresa" required>
                      <option value="">Selecciona una Empresa que realizará la compra</option>
                      <?php
                      $sqlEmp = "SELECT * FROM empresas WHERE estatus = '1'";
                      $resEmp = mysqli_query($link, $sqlEmp) or die('Problemas al consultar la empresas, notifica a tu Administrador.');
                      while ($e = mysqli_fetch_array($resEmp)) {
                        echo '<option value="' . $e['id'] . '">' . $e['nombre'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="newNombre1"><i class="mdi mdi-account"></i></span>
                    </div>
                    <input type="text" class="form-control" id="newNombre" placeholder="Ingresa el Nombre" aria-describedby="nombre" name="newNombre" oninput="limpiaCadena(this.value,'newNombre');" required>
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="newRFC1"><i class="mdi mdi-account-key"></i></span>
                    </div>
                    <input type="text" class="form-control" id="newRFC" placeholder="Ingresa su RFC" aria-describedby="rfc" onkeyup="cambiaMayusculas(this.value,'newRFC');" name="newRFC" required>
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="newTelefono1"><i class="mdi mdi-phone-plus"></i></span>
                    </div>
                    <input type="text" class="form-control phone-inputmask" id="newTelefono" placeholder="Ingresa su teléfono" aria-describedby="Telefono" name="newTelefono" required>
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="newCorreo1"><i class="mdi mdi-email-outline"></i></span>
                    </div>
                    <input type="email" class="form-control email-inputmask" placeholder="Ingresa el correo de la Empresa" id="newCorreo" aria-describedby="Correo" name="newCorreo">
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="editCorreo1">CR</span>
                    </div>
                    <select class="form-control" name="credito" id="credito" onchange="visualizaCampos(this.value,1);">
                      <option value="0">No brinda Crédito</option>
                      <option value="1">Si brinda Crédito</option>
                    </select>
                  </div>
                  <br>

                  <div id="divCreditos" style="display:none;">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="editCorreo1"><i class="fas fa-clock"></i></span>
                      </div>
                      <input type="number" class="form-control campoCredito" id="cantLimite" name="cantLimite" placeholder="Ingresa cuántos días, semanas, meses o años">
                    </div>
                    <br>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="editCorreo1"><i class="far fa-calendar-plus"></i></span>
                      </div>
                      <select class="form-control campoCredito" name="tipoLimite" id="tipoLimite">
                        <option value="">Selecciona el periodo</option>
                        <option value="1">Días</option>
                        <option value="2">Semanas</option>
                        <option value="3">Meses</option>
                        <option value="4">Años</option>
                      </select>
                    </div>
                    <br>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="editCorreo1"><i class="far fa-money-bill-alt"></i></span>
                      </div>
                      <input type="text" class="form-control campoCredito" onkeypress="mascaraMonto(this,Monto)" id="limiteCred" name="limiteCred" placeholder="Ingresa el monto de crédito permitido">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Registrar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /.modal -->


        <!-- sample modal content -->
        <div id="modalEditProveedor" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="lblEditProveedor">Edita Proveedor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body" id="modalBodyEditProveedor">

              </div>
            </div>
          </div>
        </div>
        <!-- /.modal -->

        <!-- modal edita contacto-->
        <div id="modalEditProveedor1" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="lblEditContacto">Edita contacto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body" id="modalBodyEditContacto">

              </div>
            </div>
          </div>
        </div>
        <!-- modal final de contacto-->

        <!-- MODAL DE NUEVO CONTACTO -->
        <div id="modalnewContacto" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="lblnewContacto">Nuevo Contacto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <form role="form" id="formnewContacto">

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="nNombre1"><i class="mdi mdi-account"></i></span>
                    </div>
                    <input type="text" class="form-control" id="nNombre" placeholder="Ingresa el Nombre" aria-describedby="nombre" name="nNombre" oninput="limpiaCadena(this.value,'nNombre');" required>
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="nTelefono1"><i class="mdi mdi-phone-plus"></i></span>
                    </div>
                    <input type="text" class="form-control phone-inputmask" id="nTelefono" placeholder="Ingresa su teléfono" aria-describedby="Telefono" name="nTelefono" required>
                  </div>
                  <br>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="nCorreo1"><i class="mdi mdi-email-outline"></i></span>
                    </div>
                    <input type="email" class="form-control email-inputmask" placeholder="Ingresa el correo" id="nCorreo" aria-describedby="Correo" name="nCorreo">
                  </div>

                  <div class="modal-footer">
                    <div id="bloquear-boton" style="display:none;">
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
                    <div id="desbloquear-boton">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-success waves-effect waves-light" onclick="mandaRegistroDeContacto('bloquear-boton');">Registrar</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /.modal -->

      </div>
      <!-- End Container fluid  -->
      <!-- ============================================================== -->
      <footer class="footer text-center">
        Powered by
        <b class="text-info">RVSETyS</b>.
      </footer>
      <!-- End footer -->
    </div>
    <!-- End Page wrapper  -->
  </div>
  <!-- End Wrapper -->
  <!-- customizer Panel -->
  <div class="chat-windows"></div>
  <!-- All Jquery -->
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <!-- mios si que si -->

  <!-- Bootstrap tether Core JavaScript -->
  <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- apps -->
  <script src="../dist/js/app.min.js"></script>
  <script src="../dist/js/app.init.mini-sidebar.js"></script>
  <script src="../dist/js/app-style-switcher.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
  <!--Wave Effects -->
  <script src="../dist/js/waves.js"></script>
  <!-- dataTable js -->
  <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min-ESP.js"></script>
  <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
  <!--Menu sidebar -->
  <script src="../dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
  <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>

  <script>
    function myFunction() {
      var x = document.getElementById("myDIV");
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }
  </script>
  <script>
    $(document).ready(function() {
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset($_SESSION['LZFmsjCatalogoProveedores'])) {
        echo "notificaBad('" . $_SESSION['LZFmsjCatalogoProveedores'] . "');";
        unset($_SESSION['LZFmsjCatalogoProveedores']);
      }
      if (isset($_SESSION['LZFmsjSuccessCatalogoProveedores'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessCatalogoProveedores'] . "');";
        unset($_SESSION['LZFmsjSuccessCatalogoProveedores']);
      }
      ?>
    }); // Cierre de document ready

    function listarContactos(idProv, estatus) {
      var boton = 'activar2-btn' + idProv;
      var nombre = $("#nombreProv-" + idProv).html();
      if (idProv > 0) {
        bloqueoBtn(boton, 1);
        $.post("../funciones/listaContactosProveedores.php", {
            idProv: idProv,
            estatus: estatus
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {

              $("#bodyContactos").html(resp[1]);
              $("#tituloContactos").html('Contactos de ' + nombre);
              bloqueoBtn(boton, 2);
              $('html, body').animate({
                scrollTop: $("#bodyContactos").offset().top
              }, 100);
            } else {
              notificaBad(resp[1]);
              bloqueoBtn(boton, 2);
            }
          });
      } else {
        notificaBad('No se reconoció el Proveedor, actualiza e intenta de nuevo, si persiste notifica a tu Administrador');
      }
    }

    function mandaModalContacto(idProv) {
      var boton = 'bloquear-boton';
      bloqueoBtn(boton, 2);
      var nombre = $("#nombreProv-" + idProv).html();
      $("#lblnewContacto").html('Nuevo Contacto de ' + nombre);
    }

    function eliminaContacto(idContacto, idProv) {
      if (idContacto > 0) {
        $.post("../funciones/eliminaContactoProv.php", {
            idContacto: idContacto
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              listarContactos(idProv, 1);
            } else {
              notificaBad(resp[1]);
            }
          });
      } else {
        notificaBad('No se reconoció al Contacto, actualiza e inténtalo nuevamente, si persiste notifica a tu Administrador');
      }
    }

    function editaContacto(idContacto) {
      //alert('idProv: '+idProv);
      if (idContacto > 0) {

        var empresa = $("#id-" + idContacto).val();
        var nombre = $("#nombre-" + idContacto).html();
        $.post("../funciones/formEditaContacto.php", {
            idContacto: idContacto
          },
          function(respuesta) {
            $("#modalBodyEditContacto").html(respuesta);
            $("#lblEditContacto").html(nombre);

          });
      } else {
        notificaBad('No se reconoció el contacto, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
      }
    }

    function editaProveedor(idProv) {
      //alert('idProv: '+idProv);
      if (idProv > 0) {
        var empresa = $("#idNomEmp-" + idProv).val();
        var nombre = $("#nombreProv-" + idProv).html();
        $.post("../funciones/formEditaProveedor.php", {
            idProv: idProv
          },
          function(respuesta) {
            $("#modalBodyEditProveedor").html(respuesta);
            $("#lblEditProveedor").html(nombre);
          });
      } else {
        notificaBad('No se reconoció el Proveedor, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
      }
    }

    function cambiaEstatus(boton, idProv, estatus) {
      if (idProv > 0) {
        bloqueoBtn(boton, 1);
        $.post("../funciones/cambiaEstatusProveedor.php", {
            idProv: idProv,
            estatus: estatus
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              $("#estatus-" + idProv).html(resp[2]);
              $("#desactivar-btn" + idProv).html(resp[3]);
              bloqueoBtn(boton, 2);
            } else {
              notificaBad(resp[1]);
              bloqueoBtn(boton, 2);
            }
          });
      } else {
        notificaBad('No se reconoció el Proveedor, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
      }
    }

    function mandaRegistroDeContacto(boton) {
      var idProv = $("#identProveedor").val();
      var nNombre = $("#nNombre").val();
      var nTelefono = $("#nTelefono").val();
      var nCorreo = $("#nCorreo").val();
      //alert('idProv:'+idProv+', nNombre: '+nNombre+', nTelefono: '+nTelefono+', nCorreo: '+nCorreo);
      if (idProv > 0) {
        bloqueoBtn(boton, 1);
        $.post("../funciones/registraContactoDeProveedor.php", {
            idProv: idProv,
            nNombre: nNombre,
            nTelefono: nTelefono,
            nCorreo: nCorreo
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              if (resp[2] == 1) {
                notificaBad(resp[3])
              }
              bloqueoBtn(boton, 2);
              listarContactos(idProv, 1);
              $("#nNombre").val('');
              $("#nTelefono").val('');
              $("#nCorreo").val('');
              $("#nNombre").focus();
            } else {
              notificaBad(resp[1]);
              bloqueoBtn(boton, 2);
            }
          });
      } else {
        notificaBad('No se reconoció el Proveedor, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
      }
    }

    function visualizaCampos(valor, no) {
      if (no == 1) {
        if (valor == 1) {
          $("#divCreditos").toggle('fast');
          $(".campoCredito").prop('required', true);
        } else {
          $("#divCreditos").toggle('fast');
          $(".campoCredito").prop('required', false);
        }
      } else {
        if (valor == 1) {
          $("#divCreditos2").toggle('fast');
          $(".campoCredito2").prop('required', true);
        } else {
          $("#divCreditos2").toggle('fast');
          $(".campoCredito2").prop('required', false);
        }
      }
    }

    function agregaCuentas2(idProv, nomProv) {
      $("#lblNuevaCuentaBancaria").html('Nueva cuenta Bancaria');
      $("#modalEditProveedor").modal('hide');
      $("#modalNuevaCuentaBancaria").modal('show');
      $("#ncIdProv").val(idProv);
    }
  </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>