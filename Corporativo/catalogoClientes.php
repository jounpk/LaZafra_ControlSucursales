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
</head>

<body>
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar">
      <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <?= $info->customizer('2'); ?>

        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
          <!-- ============================================================== -->
          <!-- toggle and nav items -->
          <!-- ============================================================== -->
          <ul class="navbar-nav float-left mr-auto"> </ul>
          <!-- ============================================================== -->
          <!-- Right side toggle and nav items -->
          <!-- ============================================================== -->
          <ul class="navbar-nav float-right">
            <!-- ============================================================== -->
            <!-- Messages -->
            <!-- ============================================================== -->
            <audio id="player" src="../assets/images/soundbb.mp3"> </audio>

            <li class="nav-item dropdown border-right" id="notificaciones">
            </li>
            <!-- ============================================================== -->


            <!-- ============================================================== -->
            <!-- User profile  -->
            <!-- ============================================================== -->
            <?= $info->generaMenuUsuario(); ?>
            <!-- ============================================================== -->
          </ul>
        </div>
      </nav>
    </header>
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <?= $info->generaMenuLateral(); ?>
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
      <!-- ============================================================== -->
      <!-- Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->

      <!-- ============================================================== -->
      <!-- Container fluid  -->
      <!-- ============================================================== -->
      <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
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

        <div class="card border-<?= $pyme; ?>">
          <div class="card-header bg-<?= $pyme; ?>">
            <h4 class="m-b-0 text-white">Listado de Clientes</h4>
          </div>
          <br>

          <div class="card-body">
            <div class="text-right">
              <button class="btn btn-outline-<?= $pyme; ?> btn-rounded" onclick="mandaCapturaEdicion(1,0);"><i class="fas fa-plus"></i> Nuevo Cliente</button>
            </div>
            <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
              <div class="table-responsive">
                <table class="table product-overview" id="zero_config">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nombre/RFC</th>

                      <th>Dirección</th>
                      <th class="text-center">Contacto</th>
                      <th class="text-right">Límite de Crédito</th>
                      <th class="text-center">Estatus</th>
                      <th class="text-right">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $sql = "SELECT c.*, e.nombre AS nomEdo, m.nombre AS nomMpio,
                                            IF (c.correo IS NULL, '*******', c.correo) AS correoActual
                                            FROM clientes c
                                            INNER JOIN catestados e ON c.estado = e.id
                                            LEFT JOIN catmunicipios m ON c.municipio = m.id
                                            ORDER BY c.estatus ASC, c.nombre ASC";
                    $res = mysqli_query($link, $sql) or die('Problemas al consutlar los Clientes, notifica a tu Administrador');
                    while ($cli = mysqli_fetch_array($res)) {
                      $id = $cli['id'];
                      $nombre = ($cli['apodo'] != '') ? $cli['nombre'] . ' (' . $cli['apodo'] . ')' : $cli['nombre'];
                      $btnEstatus = ($cli['estatus'] == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';
                      $btnCambiaEstatus = ($cli['estatus'] == 1) ? '<a id="btnEstatus-' . $id . '"><button class="btn btn-outline-danger btn-xs" title="Deshabilitar" onclick="cambiaEstatus(' . $id . ', ' . $cli['estatus'] . ')"><i class="fas fa-trash"></i></button></a>' : '<a id="btnEstatus-' . $id . '"><button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatus(' . $id . ', ' . $cli['estatus'] . ')"><i class="fas fa-exchange-alt"></i></button></a>';
                      echo '<tr>
                                              <td class="text-center">' . $id . '</td>
                                              <td>' . $nombre . '<br><b>' . $cli['rfc'] . '</b></td>
                                              <td>' . $cli['direccion'] . ' ' . $cli['nomMpio'] . ' ' . $cli['nomEdo'] . ' </td>
                                              <td class=""><b>Telefono: </b>' . $cli['telefono'] . '<br><b>Correo: </b>' . $cli['correoActual'] . '</td>
                                              <td class="text-right">$ ' . number_format($cli['limiteCredito'], 2, '.', ',') . '</td>
                                              <td class="text-center" id="iconEstat-' . $id . '">' . $btnEstatus . '</td>
                                              <td class="text-center">
                                                <button class="btn btn-outline-info btn-xs" onclick="mandaCapturaEdicion(2,' . $id . ');"><i class="fas fa-pencil-alt"></i></button>
                                                ' . $btnCambiaEstatus . '
                                                <button class="btn btn-outline-primary btn-xs" data-target="#verDoctos" data-toggle="modal"  onclick="cargarArchivos(' . $id . ');"><i class="fas fa-file-alt"></i></button>

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

        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
      </div>
      <div class="modal fade" id="verDoctos" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-<?= $pyme ?> " style="color:#fff;" id="verIMGContent">

              <h4 class="modal-title" id="verIMGTitle">Subir Documentación de Clientes</h4>
              <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>

            </div>
            <div class="modal-body" id="verIMGBody">

              <div>
                <form action='' method='post' id="FormularioDeDoctos">
                  <div class="row">

                    <div class="col-md-5">
                      <label for="nombreDelDocto" class="px-3"> Descripción </label>
                      <div class="input-group px-3 mb-3">
                        <input type="text" class="form-control " autocomplete="off" name="nombreDelDocto" id="nombreDelDocto" aria-describedby="basic-addon1" required>
                      </div>
                    </div>

                    <div class="col-md-5">
                      <label for="docto" class="px-3">Nuevo Documento</label>
                      <div class="input-group px-3 mb-3">

                        <input type="file" class="form-control" accept="application/pdf" autocomplete="off" name="docto" id="docto" aria-describedby="basic-addon1" required>
                      </div>
                    </div>
                    <div class="col-md-2 mt-4">
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
                      <button type="submit" class="btn btn-success btn-circle"><i class="fa fa-check"></i>
                      </button>
                    </div>
                    </div>
                  </div>
                
                </form>

              </div>
              <hr>
              <div id="AccordionDeDoctos">

              </div>
            </div>
            <div class="modal-footer" id="verIMGFooter">
              <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>


            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <!-- ============================================================== -->
      <!-- End Container fluid  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->
      <footer class="footer text-center">
        Powered by
        <b class="text-info">RVSETyS</b>.
      </footer>
      <!-- ============================================================== -->
      <!-- End footer -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Wrapper -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- customizer Panel -->
  <!-- ============================================================== -->

  <div class="chat-windows"></div>
  <!-- ============================================================== -->
  <!-- All Jquery -->
  <!-- ============================================================== -->
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
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
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <script src="../assets/extra-libs/prism/prism.js"></script>


  <script>
    $(document).ready(function() {
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);


      if (isset($_SESSION['LZFmsjCatalogoClientes'])) {
        echo "notificaBad('" . $_SESSION['LZFmsjCatalogoClientes'] . "');";
        unset($_SESSION['LZFmsjCatalogoClientes']);
      }
      if (isset($_SESSION['LZFmsjSuccessCatalogoClientes'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessCatalogoClientes'] . "');";
        unset($_SESSION['LZFmsjSuccessCatalogoClientes']);
      }
      ?>
    });

    function cargarArchivos(idCliente) {
      var debug = 0;
      $.post("../funciones/cargaArchivosClientes.php", {

          ident: idCliente
        },
        function(respuesta) {
          if (debug == 1) {
            console.log(respuesta);
          }
          $("#FormularioDeDoctos").append(' <input type="hidden" value="' + idCliente + '" name="idCliente">');
          $('#AccordionDeDoctos').html(respuesta);
        });

    }


    $("#FormularioDeDoctos").submit(function(event) {
      event.preventDefault();
      datos = $("#FormularioDeDoctos").serializeArray();
      bloqueoBtn("bloquear-btn1", 1);
      var formElement = document.getElementById("FormularioDeDoctos");
      var formCompras = new FormData(formElement);
      $.ajax({
        type: 'POST',
        url: "../funciones/registraDoctos.php",
        data: formCompras,
        processData: false,
        contentType: false,

        success: function(respuesta) {
          var resp = respuesta.split('|');
          if (resp[0] == 1) {

            location.reload();
          } else {
            bloqueoBtn("bloquear-btn1", 2);
            notificaBad(resp[1]);
          }
        }
      });
    });



    function mandaCapturaEdicion(tipo, id) {
      //alert("estatus: "+estatus);
      $('<form action="configuraCliente.php" method="POST"><input type="hidden" name="tipo" value="' + tipo + '"><input type="hidden" name="id" value="' + id + '"></form>').appendTo('body').submit();
    }

    function cambiaEstatus(idCliente, estatus) {
      if (idCliente > 0) {
        $.post("../funciones/cambiaEstatusCliente.php", {
            idCliente: idCliente,
            estatus: estatus
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              $("#btnEstatus-" + idCliente).html(resp[2]);
              $("#iconEstat-" + idCliente).html(resp[4]);

            } else {
              notificaBad(resp[1]);
            }
          });
      } else {
        notificaBad('No se reconoció el Cliente, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
      }
    }
  </script>

</body>

</html>