<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');

$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
session_start();

#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$debug = 0;
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
setlocale(LC_TIME, "es_ES.UTF-8");
setlocale(LC_TIME, "spanish");
$mes = strftime("%B");
$year = strftime("%Y");
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
  <link rel="icon" type="../image/png" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <link href="../assets/libs/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <style>
    .btn-circle-tablita {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 6px 0;
      font-size: 12px;
      line-height: 1.428571429;
      border-radius: 15px;
      margin-left: 5px;
    }
  </style>


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

      <div class="container-fluid">
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
        <br>

        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Cotizaciones Por Autorizar</h4>
              </div>
              <div class="card-body">

                <div class="row">
                  <?php
                  $sql = "SELECT COUNT(cot.id) AS totalCoti, IF(SUM(cot.montoTotal) IS NULL,'0.0',FORMAT(SUM(cot.montoTotal),2)) AS totalMonto
                  FROM cotizaciones cot WHERE cot.estatus='3' AND 
                  DATE_FORMAT(fechaAut,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')";
                  //----------------devBug------------------------------
                  if ($debug == 1) {
                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al cotizaciones Activas, notifica a tu Administrador.');
                    echo '<br>Listado de Correos: ' .    $sql  . '<br>';
                  } else {
                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al cotizaciones Activas, notifica a tu Administrador.');
                  } //-------------Finaliza devBug------------------------------
                  $arrayTotal = mysqli_fetch_array($resXQuery);
                  $activas = $arrayTotal['totalCoti'];
                  $totalMonto = $arrayTotal['totalMonto'];
                  ?>
                  <div class="col-lg-4 col-md-6">
                    <div class="card border-bottom border-<?= $pyme ?>">
                      <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                          <div>
                            <h2>$<?= $totalMonto ?></h2>
                            <h6 class="text-<?= $pyme ?>">Aceptadas (<?= $mes ?> - <?= $year ?>)</h6>
                            <h6 class="text-<?= $pyme ?>"><?= $activas ?> Cantidad</h6>

                          </div>
                          <div class="ml-auto">
                            <span class="text-<?= $pyme ?> display-6"><i class="fas fa-check-circle"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="card border-bottom border-danger">
                      <div class="card-body">
                        <?php
                        $sql = "SELECT COUNT(cot.id) AS totalCoti, IF(SUM(cot.montoTotal) IS NULL,'0.0',FORMAT(SUM(cot.montoTotal),2)) AS totalMonto
                                FROM cotizaciones cot WHERE cot.estatus='4' AND 
                                DATE_FORMAT(fechaAut,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')";
                        //----------------devBug------------------------------
                        if ($debug == 1) {
                          $resXQuery = mysqli_query($link, $sql) or die('Problemas al cotizaciones Activas, notifica a tu Administrador.');
                          echo '<br>Listado de Correos: ' .    $sql  . '<br>';
                        } else {
                          $resXQuery = mysqli_query($link, $sql) or die('Problemas al cotizaciones Activas, notifica a tu Administrador.');
                        } //-------------Finaliza devBug------------------------------
                        $arrayTotal = mysqli_fetch_array($resXQuery);
                        $desactivas = $arrayTotal['totalCoti'];
                        $totalMontoDes = $arrayTotal['totalMonto'];
                        ?>
                        <div class="d-flex no-block align-items-center">
                          <div>
                            <h2>$<?= $totalMontoDes ?></h2>
                            <h6 class="text-danger">Rechazadas (<?= $mes ?> - <?= $year ?>)</h6>
                            <h6 class="text-danger"><?= $desactivas ?> Cantidad</h6>
                          </div>
                          <div class="ml-auto">
                            <span class="text-danger display-6"><i class="fas fa-times-circle"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="card border-bottom border-warning">
                      <div class="card-body">
                        <div class="d-flex no-block align-items-center">
                          <?php
                          $sql = "SELECT COUNT(cot.id) AS totalCoti, IF(SUM(cot.montoTotal) IS NULL,'0.0',FORMAT(SUM(cot.montoTotal),2)) AS totalMonto
                                FROM cotizaciones cot WHERE cot.estatus='2' AND 
                                DATE_FORMAT(fechaReg,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')";
                          //----------------devBug------------------------------
                          if ($debug == 1) {
                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al cotizaciones Activas, notifica a tu Administrador.');
                            echo '<br>Listado de Correos: ' .    $sql  . '<br>';
                          } else {
                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al cotizaciones Activas, notifica a tu Administrador.');
                          } //-------------Finaliza devBug------------------------------
                          $arrayTotal = mysqli_fetch_array($resXQuery);
                          $pendientes = $arrayTotal['totalCoti'];
                          $totalMontoPen = $arrayTotal['totalMonto'];
                          ?>
                          <div>
                            <h2>$<?= $totalMontoPen ?></h2>
                            <h6 class="text-warning">Pendientes (<?= $mes ?> - <?= $year ?>)</h6>
                            <h6 class="text-warning"><?= $pendientes ?> Cantidad</h6>
                          </div>
                          <div class="ml-auto">
                            <span class="text-warning display-6"><i class="fas fa-spinner"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="text-right">
                  <a class="btn btn-circle bg-<?= $pyme; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Historial de Cotizacion" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="corphistorialcotizaciones.php"> <i class=" fas fa-tasks"></i></a>

                </div>
                <br>
                <?php
                $fechaAct = date('d-m-Y');
                if (isset($_POST['fechaInicial']) and $_POST['fechaInicial'] != '') {
                  $fechaInicial = $_POST['fechaInicial'];
                  $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                  $filtroFechas = "ct.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                } else {
                  $fechaInicial = "";
                  $filtroFechas = "1=1";
                }
                if (isset($_POST['fechaFinal']) and $_POST['fechaFinal'] != '') {
                  $fechaFinal = $_POST['fechaFinal'];

                  $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                  $filtroFechas = "ct.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                } else {
                  $fechaFinal = "";
                  $filtroFechas = "1=1";
                }

                if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                  $buscaSuc = $_POST['buscaSuc'];
                  $filtroSuc = "ct.idSucursal=" . $_POST['buscaSuc'];
                } else {
                  $filtroSuc = '1=1';
                  $buscaSuc = '';
                }

                $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                $listaSuc = '';
                while ($datos = mysqli_fetch_array($resSuc)) {
                  $activeSuc = ($datos['id'] == $buscaSuc) ? 'selected' : '';
                  $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                }




                ?>

                <div class="row">
                  <form method="post" action="corpCotizaciones.php">

                    <div class="col-6">

                    </div>
                    <div class="col-6">

                    </div>
                </div>


                <!--/span-->
                <div class="border p-3 mb-3">
                  <div class="row">

                    <form method="post" action="corpCotizaciones.php">
                      <div class="col-md-10 mt-2 mx-4">
                        <h4><i class="fas fa-filter "></i> Filtrado</h4>
                        <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>

                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <input class="form-control" type="date" value="<?= $fechaInicial ?>" id="rangeBa1" name="fechaInicial" />

                                </div>

                              </div>

                            </div>

                          </div>


                          <div class="col-md-3">

                            <div class="form-group">
                              <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>

                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <input class="form-control" type="date" value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />

                                </div>
                              </div>
                            </div>
                          </div>


                          <div class="col-md-5">
                            <div class="form-group">
                              <label for="rangeBa1" class="control-label col-form-label">Sucursal</label>

                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="finVig1"><i class="mdi mdi-store"></i></span>
                                </div>
                                <select class="select2 form-control custom-select" name="buscaSuc" id="buscaSuc" onchange="" style="width: 80%;">

                                  <option value=""> Todas las Sucursales</option>
                                  <?= $listaSuc ?>
                                </select>
                              </div>

                            </div>

                          </div>
                        </div>
                      </div>
                      <div class="col-md-1 pt-4">
                        <input type="submit" id="buscarConexion" class="btn btn-success mt-5" value="Buscar"></input>


                      </div>

                  </div>
                </div>
                </form>






                <div class="table-responsive">
                  <table class="table product-overview" id="tabla_ajustessol">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th>Sucursal</th>
                        <th>Cliente</th>
                        <th>Monto Total</th>
                        <th>Emisor</th>
                        <th>Fecha Emitida</th>
                        <th class="text-center">Aprobación</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
                <!--end .table-responsive -->
              </div>
              <!--end .col -->
            </div>
            <!--end .row -->
            <!-- END DATATABLE 1 -->
          </div>
          <!--end .card-body -->
        </div>
        <!--end .card -->
        <!-- END ACTION -->


        <!-- /.modal -->
      </div>

      <footer class="footer text-center">
        Powered by
        <b class="text-info">RVSETyS</b>.
      </footer>

    </div>

  </div>


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
  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <script type="text/javascript" src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js" charset="UTF-8"></script>

  <!--Custom JavaScript -->
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <script src="../assets/tablasZafra/datatable_cotizaciones.js"></script>
  <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>



  <script>
    <?php

    $sql = "SELECT ct.idSucursal, suc.nombre AS sucursales,ct.id,IF(ct.tipo=2,CONCAT(ct.nameCliente, ' <span><b>(Público en General)</b></span>'),cl.nombre) AS cliente, ct.nameCliente, IF(ct.montoTotal IS NULL,'$0.0',CONCAT('$ ',FORMAT(ct.montoTotal,2)) )AS montoTotal, CONCAT(usr.nombre, ' ', usr.appat, ' ', usr.apmat) AS usuario, DATE_FORMAT(ct.fechaReg, '%d-%m-%Y %H:%i:%s') AS fecha 
      FROM cotizaciones ct
      LEFT JOIN clientes cl ON ct.idCliente=cl.id
      INNER JOIN sucursales suc ON ct.idSucursal=suc.id
      INNER JOIN segusuarios usr ON ct.idUserReg=usr.id
      WHERE ct.estatus='2'
      AND $filtroFechas AND $filtroSuc";
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
      $('.datepicker').datepicker({
        language: 'es',
        format: 'dd-mm-yyyy',
      });
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset($_SESSION['LZmsjInfoCotizacionesCorp'])) {
        echo "notificaBad('" . $_SESSION['LZmsjInfoCotizacionesCorp'] . "');";
        unset($_SESSION['LZmsjInfoCotizacionesCorp']);
      }
      if (isset($_SESSION['LZmsjSuccessCotizacionesCorp'])) {
        echo "notificaSuc('" . $_SESSION['LZmsjSuccessCotizacionesCorp'] . "');";
        unset($_SESSION['LZmsjSuccessCotizacionesCorp']);
      }
      ?>
    }); // Cierre de document ready


    function limpiaCadena(dat, id) {
      //alert(id);
      dat = getCadenaLimpia(dat);
      $("#" + id).val(dat);
    }


    function estatusSolic(id, estatus, idSucursalEmite) {
      if(estatus=='3'){

        Swal.fire({
        title: 'Días de Vigencia de Cotización',
        input: 'number',
        inputAttributes: {
          autocapitalize: 'off',
          step: '1',
          min: '1',
          id: 'vigencia-' + id

        },

        showCancelButton: true,
        confirmButtonText: 'Guardar',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {

          $.post("../funciones/cambiaEstatusSolicitudCot.php", {
              ident: id,
              estatus: estatus,
              dias:login,
              idSucursalEmite:idSucursalEmite
            },
            function(respuesta) {
              var resp = respuesta.split('|');
              if (resp[0] == 1) {

                location.reload();

              } else {
                notificaBad(resp[1]);
              }
            });



        }
       
      });


      }else{
        $.post("../funciones/cambiaEstatusSolicitudCot.php", {
              ident: id,
              estatus: estatus,
              dias:'0',
              idSucursalEmite:idSucursalEmite
            },
            function(respuesta) {
              var resp = respuesta.split('|');
              if (resp[0] == 1) {

                location.reload();

              } else {
                notificaBad(resp[1]);
              }
            });

      }
    }








    

    function ejecutandoCarga(identif) {
      var selector = 'DIV' + identif;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("../funciones/cargaContenidoCotizacion.php", {
          ident: identif
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });

    }
  </script>

</body>

</html>