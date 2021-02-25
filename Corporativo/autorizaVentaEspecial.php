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
  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
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
        <style>
          .muestraSombra {

            box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
          }

          .alinearCentro {
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            line-height: 150%;
            padding-top: 15%;
          }
        </style>
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
        <!--  Comienza la solicitud de ventas especiales -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card border-<?= $pyme; ?>" id="cardPendientes">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Solicitudes Pendientes</h4>
              </div>
              <div class="card-body">
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <div class="table-responsive">
                    <table class="table " id="tablaAutXVta">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Sucursal</th>
                          <th>Solicita</th>
                          <th>Motivo</th>
                          <th>Cliente</th>
                          <th class="text-center">Fecha de solicitud</th>
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
          </div>
        </div>

        <!--  Comienza el historial de ventas especiales -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Historial</h4>
              </div>
              <div class="card-body">
                <?php
                #print_r($_POST);
                $fechaAct = date('Y-m-d');
                if (isset($_POST['fechaInicial'])) {
                  $fechaInicial = $_POST['fechaInicial'];
                } else {
                  $fechaInicial1 = strtotime('-1 week', strtotime($fechaAct));
                  $fechaInicial = date('Y-m-d', $fechaInicial1);
                }
                if (isset($_POST['fechaFinal'])) {
                  $fechaFinal = $_POST['fechaFinal'];
                } else {
                  $fechaFinal1 = strtotime('+15 day', strtotime($fechaAct));
                  $fechaFinal = date('Y-m-d', $fechaFinal1);
                }

                $filtroFecha = " AND v.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'";

                if (isset($_POST['selectSucursal']) && $_POST['selectSucursal'] > 0) {
                  $sucursal = $_POST['selectSucursal'];
                  $filtroSucursal = " AND scs.id = '$sucursal'";
                } else {
                  $sucursal = '';
                  $filtroSucursal = '';
                }


                #echo '<br>$sucursal: '.$sucursal;
                ?>
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                      <div class="col-md-12">
                        <h5 class="m-t-30 text-center text-<?= $pyme; ?>">Selecciona un rango de Fechas</h5>
                      </div>
                      <div class="col-md-12">
                        <form role="form" action="#" method="post">
                          <div class="input-daterange input-group" id="date-range">
                            <input type="date" class="form-control" name="fechaInicial" value="<?= $fechaInicial; ?>" />
                            <div class="input-group-append">
                              <span class="input-group-text bg-<?= $pyme; ?> b-0 text-white"> A </span>
                            </div>
                            <input type="date" class="form-control" name="fechaFinal" value="<?= $fechaFinal; ?>" />
                          </div>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="col-md-12">
                        <div class="row">

                          <div class="col-md-12">
                            <h5 class="m-t-30 text-<?= $pyme; ?>">Selecciona una Sucursal:</h5>
                          </div>
                          <div class="col-md-5">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-8">
                          <select class="select2 form-control custom-select" name="selectSucursal" id="sucursal" style="width: 95%; height:100%;">
                            <?php
                            echo '<option value="">Todas las Sucursales</option>';
                            $sql = "SELECT id, nombre FROM sucursales WHERE estatus = '1'";
                            #  echo $sql;
                            $res = mysqli_query($link, $sql);
                            while ($rows = mysqli_fetch_array($res)) {
                              $activa = ($sucursal == $rows['id']) ? 'selected' : '';
                              echo '<option value="' . $rows['id'] . '" ' . $activa . '>' . $rows['nombre'] . '</option>';
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <button type="submit" class="btn btn-<?= $pyme; ?>">Buscar</button>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-1"></div>
                  </div>
                </div>
                </form>
                <br>
                <div class="col-md-12">
                  <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered dataTable" id="zero_config2">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Sucursal</th>
                            <th>Solicita</th>
                            <th class="text-center">Fecha Solicita</th>
                            <th>Motivo</th>
                            <th>Autorizó</th>
                            <th class="text-center">Fecha Autoriza</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Ticket</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          #/*
                          $sqlAuto = "SELECT v.id, scs.nombre AS nomSucursal, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario,
                                                 v.fechaSolicita,v.fechaAutoriza, v.motivoVtaEsp, CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat) AS nomUsuAut, v.estatus
                                                 FROM ventas v
                                                 INNER JOIN segusuarios u ON v.idUserReg = u.id
                                                 INNER JOIN sucursales scs ON v.idSucursal = scs.id
                                                 INNER JOIN segusuarios u2 ON v.idUserAut = u2.id
                                                 WHERE v.ventaEspecial = 1 AND v.estatus > 0 $filtroSucursal $filtroFecha
                                                 ORDER BY v.fechaReg, v.estatus ASC";
                          $resAuto = mysqli_query($link, $sqlAuto) or die('Problemas al consultar los créditos pendientes, notifica a tu Administrador.');
                          while ($lst = mysqli_fetch_array($resAuto)) {
                            switch ($lst['estatus']) {
                              case '2':
                                $color = 'class="table-success"';
                                $estado = 'Venta cerrada';
                                $boton = '<button type="button" class="btn btn-info btn-circle muestraSombra" onClick="imprimeTicketVenta(' . $lst['id'] . ');"><i class="fas fa-print"></i></button>';
                                break;
                              case '3':
                                $color = 'class="table-danger"';
                                $estado = 'Venta cancelada';
                                $boton = '<button type="button" class="btn btn-danger btn-circle muestraSombra" title="Sin Acciones"><i class="fas fa-times"></i></button>';
                                break;
                              case '5':
                                $color = 'class="table-danger"';
                                $estado = 'No autorizada';
                                $boton = '<button type="button" class="btn btn-danger btn-circle muestraSombra" title="Sin Acciones"><i class="fas fa-times"></i></button>';
                                break;

                              default:
                                $color = '';
                                $estado = 'En proceso de Venta';
                                $boton = '<button type="button" class="btn btn-warning btn-circle muestraSombra" title="Sin Acciones"><i class="fas fa-exclamation-triangle"></i></button>';
                                break;
                            }
                            echo '<tr ' . $color . '>
                                               <td class="text-center">' . $lst['id'] . '</td>
                                               <td>' . $lst['nomSucursal'] . '</td>
                                               <td>' . $lst['nomUsuario'] . '</td>
                                               <td class="text-center">' . $lst['fechaSolicita'] . '</td>
                                               <td>' . $lst['motivoVtaEsp'] . '</td>
                                               <td>' . $lst['nomUsuAut'] . '</td>
                                               <td class="text-center">' . $lst['fechaAutoriza'] . '</td>
                                               <td class="text-center">' . $estado . '</td>
                                               <td class="text-center">' . $boton . '</td>
                                             </tr>';
                          }
                          #*/
                          ?>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>

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
  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../assets/libs/block-ui/jquery.blockUI.js"></script>
  <script src="../assets/extra-libs/block-ui/block-ui.js"></script>
  <script src="../assets/tablasZafra/datatable_AutXVta.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>

  <script>
    <?php
    $sql = "SELECT v.id, scs.nombre AS nomSucursal, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario,
    v.fechaSolicita AS fecha, v.motivoVtaEsp,c.nombre AS nomCliente
    FROM ventas v
    INNER JOIN segusuarios u ON v.idUserReg = u.id
    INNER JOIN sucursales scs ON v.idSucursal = scs.id
    INNER JOIN clientes c ON v.idCliente = c.id
    WHERE v.ventaEspecial = 1 AND v.idUserAut = 0 AND v.estatus = 1
    ORDER BY v.fechaReg ASC";
    $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>' . $sql);
    $arreglo['data'] = array();
    while ($datos = mysqli_fetch_array($res)) {
      $arreglo['data'][] = $datos;
    }
    $var = json_encode($arreglo);
    mysqli_free_result($res);
    echo 'var dataJson= ' . $var . ';';
    echo 'var pyme = "' . $pyme . '";';
    ?>

    $(document).ready(function() {
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);
      ?>
    });

    function imprimeTicketVenta(idVenta) {
      $('<form action="../imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
    }

    function aceptaVentaEsp(idVenta, tipo) {
      if (idVenta > 0) {
        bloqueaCard('cardPendientes', 1);
        $.post("../funciones/autorizaVtaEsp.php", {
            idVenta: idVenta,
            tipo: tipo
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              bloqueaCard('cardPendientes', 2);
              notificaSuc(resp[1]);
              setTimeout(function() {
                location.reload();
              }, 2000);
            } else {
              bloqueaCard('cardPendientes', 2);
              notificaBad(resp[1]);
            }
          });
      } else {
        notificaBad('No se reconoció la venta, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador.');
      }
    }

    function ejecutandoCarga(identif) {
      var selector = 'DIV' + identif;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("../funciones/cargaContenidoAutXVta.php", {
          ident: identif
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });

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