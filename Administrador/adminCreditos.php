<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$idSucursal=$_SESSION['LZFidSuc'];
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

        <div class="row">
          <div class="col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Búsqueda de Créditos</h4>
              </div>
              <div class="card-body">


                <div class="border p-3 mb-3">
                  <h4><i class="fas fa-filter"></i> Filtrado</h4>
                  <?php
                  //-------------------------------------APLICACION DE FILTROS-------------------------------
                  $fechaAct = date('d-m-Y');
                  if (isset($_POST['fechaInicial']) and $_POST['fechaInicial'] != '') {
                    $fechaInicial = $_POST['fechaInicial'];
                    $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                    $filtroFechas = "vta.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaInicial = "";
                    $filtroFechas = "1=1";
                  }
                  if (isset($_POST['fechaFinal']) and $_POST['fechaFinal'] != '') {
                    $fechaFinal = $_POST['fechaFinal'];
                    $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                    $filtroFechas = "vta.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaFinal = "";
                    $filtroFechas = "1=1";
                  }

                  if (isset($_POST['buscaOpe']) and $_POST['buscaOpe'] >= 1) {
                    $buscaOpe = $_POST['buscaOpe'];
                    $filtroOperacion = "cr.estatus=" . $buscaOpe;
                  } else {
                    $filtroOperacion = "1=1";
                    $buscaOpe = '';
                  }


                  if (isset($_POST['buscaCliente']) and $_POST['buscaCliente'] >= 1) {
                    $buscaCliente = $_POST['buscaCliente'];
                    $filtroCliente = "cr.idCliente=" . $buscaCliente;
                  } else {
                    $filtroCliente = "1=1";
                    $buscaCliente = '';
                  }


                  ?>
                  <form method="post" action="adminCreditos.php">




                    <!--/span-->

                    <div class="row">

                      <div class="col-md-4">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>

                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <input class="form-control" type="date" value="<?= $fechaInicial ?>" id="rangeBa1" name="fechaInicial" />

                                </div>

                              </div>

                            </div>

                          </div>
                          <div class="col-md-6">

                            <div class="form-group">
                              <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>

                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <input class="form-control" type="date" value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />

                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="inputEmail3" class="control-label col-form-label">Clasificación</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="finVig1"><i class=" fas fa-tags"></i></span>
                            </div>
                            <select class="select2 form-control custom-select" name="buscaOpe" id="buscaOpe" onchange="" style="width: 80%;">

                              <option value="" selected>Todos los Créditos</option>
                              <option value="1" <?= $selectOP = $buscaOpe == '1' ? "selected" : "" ?>>Créditos Por Cobrar </option>
                              <option value="2" <?= $selectOP = $buscaOpe == '2' ? "selected" : "" ?>>Créditos Pagados</option>
                              <option value="3" <?= $selectOP = $buscaOpe == '3' ? "selected" : "" ?>>Créditos Cancelados</option>


                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="inputEmail3" class="control-label col-form-label">Clientes</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="finVig1"><i class="fas fa-users"></i></span>
                            </div>
                            <select class="select2 form-control custom-select" name="buscaCliente" id="buscaCliente" onchange="" style="width: 80%;">
                              <option value="">Todos los Clientes</option>

                              <?php
                              $sql = "SELECT
                                  cl.id,
                                  CONCAT( cl.nombre, '\"', cl.apodo, '\"' ) AS cliente
                                  FROM
                                    clientes cl 
                                  WHERE
                                    cl.estatus = '1'
                                  ORDER BY cl.nombre";
                              $resultXCliente = mysqli_query($link, $sql) or die('Problemas al consultar los Clientes, notifica a tu Administrador.');
                              while ($dat = mysqli_fetch_array($resultXCliente)) {
                                $selectCli = $dat["id"] == $buscaCliente ? "selected" : "";
                                echo "  <option value='" . $dat["id"] . "' $selectCli>" . $dat["cliente"] . "</option>";
                              }

                              ?>


                            </select>
                          </div>
                        </div>
                      </div>


                      <div class="col-md-2 pt-4 mt-2">
                        <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                      </div>
                  </form>
                  <!-- /.row (nested) -->
                </div>
              </div>
              <div class="table-responsive">
                <table class="table product-overview " id="tabla_creditos">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Cliente</th>
                      <th class="text-center">Venta</th>
                      <th class="text-center">Monto Crédito</th>
                      <th class="text-center">Saldo Deudor</th>
                      <th class="text-center">Prestamista</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Estatus</th>
                      <th class="text-center">Ticket</th>

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
  </div>
  </div>
  <?php
  //-------------------------------------Todos los creditos----------------------------------



  $sqlCreditos = "SELECT
    cr.id,
    cl.nombre,
    cr.totalDeuda,
    cr.montoDeudor,
    CONCAT( '$',FORMAT(cr.totalDeuda,2)) AS sg_tDeudor,
    CONCAT( '$',FORMAT(cr.montoDeudor,2)) AS sg_mDeudor,
    
    cr.idVenta,
    cr.estatus,
    CONCAT(usr.nombre,' ' ,usr.appat, ' ', usr.apmat) AS prestamista,
    DATE_FORMAT(vta.fechaReg,'%d-%m-%Y %H:%i:%s') fecha
  FROM
    creditos cr
    INNER JOIN ventas vta ON vta.id = cr.idVenta
    INNER JOIN segusuarios usr ON usr.id = vta.idUserReg
    INNER JOIN clientes cl ON cl.id=cr.idCliente
    WHERE $filtroFechas AND $filtroOperacion AND $filtroCliente AND vta.idSucursal='$idSucursal'
    ORDER BY cr.estatus ASC, vta.fechaReg ASC";
  //echo $sqlCreditos;
  ?>

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
  <!-- sample modal content -->
  <div class="modal fade" id="detComp" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="detCompContent">

          <h4 class="modal-title" id="detCompTitle"> Detalles del Complemento de Pago</h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>

        </div>
        <div class="modal-body" id="detCompBody">
        </div>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- sample modal content -->








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
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <script src="../assets/tablasZafra/datatable_configCred.js"></script>
  <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    

  <script>
    <?php

    $res = mysqli_query($link, $sqlCreditos) or die('<option value="">Error de Consulta de Todos los Creditos </option>');
    $arreglo['data'] = array();

    while ($datos = mysqli_fetch_array($res)) {
      $arreglo['data'][] = $datos;
    }
    $var = json_encode($arreglo);
    mysqli_free_result($res);
    echo 'var datsJson= ' . $var . ';';
    echo 'var pyme = "' . $pyme . '";';
    ?>



    $(document).ready(function() {
      <?php
      #  include('../funciones/basicFuctions.php');
      #  alertMsj($nameLk);

      if (isset($_SESSION['LZFmsjAdminCreditos'])) {
        echo "notificaBad('" . $_SESSION['LZFmsjAdminCreditos'] . "');";
        unset($_SESSION['LZFmsjAdminCreditos']);
      }
      if (isset($_SESSION['LZFmsjSuccessAdminCreditos'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessAdminCreditos'] . "');";
        unset($_SESSION['LZFmsjSuccessAdminCreditos']);
      }
      ?>








    });



    function ejecutandoCarga(identif) {
      var selector = 'DIV' + identif;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("../funciones/cargaContenidoCreditos.php", {
          ident: identif
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });

    }


    function imprimeTicketVenta(idVenta) {

      $('<form action="../imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
    }

    function hacerComplemento(ident_pago, idVenta) {
      //alert("El id de pago es: "+ident_pago);
      $.post("../funciones/modalComplemento.php", {
          ident_pago: ident_pago,
          idVenta: idVenta
        },
        function(respuesta) {
          $("#detCompBody").html(respuesta);
          $("#detComp").modal("show");

        });
    }

    
    function cambioMunicipio(idEstado) {

      $.post("../funciones/listaMunicipios.php", {
          idEstado: idEstado
        },
        function(respuesta) {

          $("#municipios").html(respuesta);

        }
      );
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