<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');
$hoy = date('d-m-Y');
$mes = date('m-Y');
$fechaAct = date('Y-m-d');
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
session_start();
$debug = 1;
$userReg = $_SESSION['LZFident'];
$sucursal = $_SESSION['LZFidSuc'];
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

  <!--<link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
-->
  <link href="../assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">


  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <style>
    #listaSuc {
      column-count: 2;
    }

    .select2-container {
      width: 100%;
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
                <h4 class="m-b-0 text-white">Historial de Cotizaciones</h4>
              </div>
              <div class="card-body">
                <div class="text-right">
                  <a class="btn btn-circle bg-<?= $pyme ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Cotizaciones" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="corpCotizaciones.php">
                    <i class="fas fa-reply"></i></a>
                </div>
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">


                  <?php

                  $fechaAct = date('d-m-Y');
                  if (isset($_POST['fechaInicial']) and $_POST['fechaInicial'] != '') {
                    $fechaInicial = $_POST['fechaInicial'];
                    if ($debug == 1) {
                      print_r("FECHA INICIAL" . $fechaInicial);
                    }
                    $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                    $filtroFechas = "AND ct.fechaAut BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaInicial = "";
                    $filtroFechas = "";
                  }
                  if (isset($_POST['fechaFinal']) and $_POST['fechaFinal'] != '') {
                    $fechaFinal = $_POST['fechaFinal'];
                    $formFF = date_format(date_create($fechaFinal), 'Y-m-d');

                    $filtroFechas = "AND ct.fechaAut BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaFinal = "";
                    $filtroFechas = "";
                  }

                  if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                    $buscaSuc = $_POST['buscaSuc'];
                    // $filtroSuc = "gstos.idSucursal=" . $_POST['buscaSuc'];
                  } else {
                    $filtroSuc = '';
                    // $buscaSuc = '';
                  }
                  if (isset($_POST['buscaTipo']) and $_POST['buscaTipo'] > 0) {
                    $buscaTipo = $_POST['buscaTipo'];
                    $filtroTipo = "AND ct.estatus=" . $_POST['buscaTipo'];
                  } else {
                    $filtroTipo = "AND 1=1";
                    $buscaTipo = '';
                  }
                  $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                  $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaSuc = '';
                  while ($datos = mysqli_fetch_array($resSuc)) {
                    $activeSuc = ($datos['id'] == $buscaSuc) ? 'selected' : '';
                    $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                  }


                  ?>
                  <div class="border p-3 mb-3">
                    <h4><i class="fas fa-filter"></i> Filtrado</h4>

                    <div class="row">
                      <form method="post" action="corphistorialcotizaciones.php">

                        <div class="col-6">

                        </div>
                        <div class="col-6">
                        </div>
                    </div>


                    <!--/span-->

                    <div class="row">

                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="rangeBa1" class="control-label col-form-label">Tipo de Traspaso</label>

                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="finVig1"><i class="mdi mdi-store"></i></span>
                            </div>
                            <select class="form-control custom-select" name="buscaTipo" id="buscaTipo" onchange="" style="width: 80%;">
                              <?php
                              switch ($buscaTipo) {
                                case '0':
                                  echo '
                                    <option value="0" selected>Todas</option>
                                    <option value="3">Aceptadas</option>
                                    <option value="4">Expiradas</option>
                                    <option value="5">Utilizadas</option>';
                                  break;
                                case '3':
                                  echo '
                                      <option value="0">Todas</option>
                                      <option value="3"  selected>Aceptadas</option>
                                      <option value="4">Expiradas</option>
                                      <option value="5">Utilizadas</option>';

                                  break;
                                case '4':
                                  echo '
                                        <option value="0">Todos</option>
                                        <option value="3">Aceptadas</option>
                                        <option value="4" selected>Expiradas</option>
                                        <option value="5">Utilizadas</option>';
                                  break;
                                case '5':
                                  echo '
                                          <option value="0">Todos</option>
                                          <option value="3">Aceptadas</option>
                                          <option value="4">Expiradas</option>
                                          <option value="5" selected>Utilizadas</option>';
                                  break;
                                default:
                                  echo '
                                    <option value="0">Todas</option>
                                    <option value="3">Aceptadas</option>
                                    <option value="4">Expiradas</option>
                                    <option value="5">Utilizadas</option>';
                                  break;
                              }




                              ?>


                            </select>
                          </div>

                        </div>

                      </div>
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



                      <div class="col-md-2 pt-4 mt-2">
                        <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                      </div>
                      </form>
                      <!-- /.row (nested) -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="table-responsive">
                        <table id="table-cotiHis" class="table display no-wrap">
                          <thead>
                            <th class="text-center">#</th>
                            <th>Serie/Folio</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Usuario Emitio</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Fecha Aut.</th>
                            <th class="text-center">Ver</th>
                          </thead>

                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

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

        <div class="modal fade" id="verIMG" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verIMGContent">


                <h4 class="modal-title" id="verIMGTitle"> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

              </div>
              <div class="modal-body" id="verIMGBody">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


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

  <!--Menu sidebar -->
  <script src="../dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <!--This page JavaScript -->
  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../assets/libs/moment/moment.js"></script>
  <script src="../assets/libs/footable/js/footable.min.js"></script>
  <script src="../assets/libs/footable/js/footable.min.js"></script>
  <script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js"></script>
  <script src="../assets/tablasZafra/datatable_configHisCoti.js"></script>

  <script>
    $('.datepicker').datepicker({
      language: 'es',
      format: 'dd-mm-yyyy',
    });
    $('#demo-foo-row-toggler').footable({
      "toggleColumn": "last",

    });
    <?php
    //QUERY DE DETALLADO DE PRODUCTO
    if ($filtroFechas == '' && $filtroSuc == '') {
      $stringWhere = '';
    } else if ($filtroFechas == '') {
      $stringWhere = 'AND ' . $filtroSuc;
    } else if ($filtroFechas != '' && $filtroSuc != '') {
      $stringWhere = 'AND ' . $filtroFechas . ' AND ' . $filtroSuc;
    } else if ($filtroSuc == '') {
      $stringWhere = 'AND ' . $filtroFechas;
    }

    $sql = "SELECT
    ct.idSucursal,
    suc.nombre AS sucursales,
    ct.id,
    IF(ct.folio IS NULL, '********', ct.folio) AS folio,
     IF
    ( ct.tipo = 2, CONCAT( ct.nameCliente, ' <span><b>(Público en General)</b></span>' ), cl.nombre ) AS cliente,
    ct.nameCliente,
     IF
    ( ct.montoTotal IS NULL, '$0.0', CONCAT( '$ ', FORMAT( ct.montoTotal, 2 )) ) AS montoTotal,
    CONCAT( usr.nombre, ' ', usr.appat, ' ', usr.apmat ) AS usuario,
    DATE_FORMAT( ct.fechaAut, '%d-%m-%Y %H:%i:%s' ) AS fecha,
    IF(DATE_ADD(ct.fechaAut,INTERVAL ct.cantPeriodo DAY)>=NOW(), 'label-success', 'label-danger') AS etiquetita,
    IF(DATE_ADD(ct.fechaAut,INTERVAL ct.cantPeriodo DAY)>=NOW(), 'Activa', 'Expirada') AS etiquetitaText,
    ct.estatus

    FROM
    cotizaciones ct
    LEFT JOIN clientes cl ON ct.idCliente = cl.id
    INNER JOIN sucursales suc ON ct.idSucursal = suc.id
    INNER JOIN segusuarios usr ON ct.idUserReg = usr.id 
    WHERE 1=1  $filtroTipo $filtroFechas";
  
    //echo $sql;
    //----------------devBug------------------------------
    if ($debug == 1) {
      $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
      $precioSeleccionado .= '<br>Listado de Precios: ' .    $sql  . '<br>';
    } else {
      $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
    } //-------------Finaliza devBug------------------------------"

    //echo "console.log(\"$sqlProductos\");";
    $arreglo['data'] = array();

    while ($data = mysqli_fetch_array($resXQueryPrecio)) {
      $arreglo['data'][] = $data;
    }
    $var = json_encode($arreglo);
    mysqli_free_result($resXQueryPrecio);

    echo 'var datsJson = ' . $var . ';';
    echo 'var pyme = "' . $pyme . '";';



    ?>
    // console.log(datsJson.data);
    $(document).ready(function() {
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset($_SESSION['LZmsjInfoAltaSeguimiento'])) {
        echo "notificaBad('" . $_SESSION['LZmsjInfoAltaSeguimiento'] . "');";
        unset($_SESSION['LZmsjInfoAltaSeguimiento']);
      }
      if (isset($_SESSION['LZmsjSuccessSeguimiento'])) {
        echo "notificaSuc('" . $_SESSION['LZmsjSuccessSeguimiento'] . "');";
        unset($_SESSION['LZmsjSuccessSeguimiento']);
      }
      ?>
    }); // Cierre de document ready


    function ejecutandoCarga(identif) {
      var selector = 'DIV' + identif;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("../funciones/cargaContenidoTras.php", {
          ident: identif
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });

    }

    function muestraTicket(ident) {
      $('<form action="../funciones/ticketLanzaTraspaso.php" method="POST"><input type="hidden" name="idTraspaso" value="' + ident + '"></form>').appendTo('body').submit();
    }

    function limpiaCadena(dat, id) {
      //alert(id);
      dat = getCadenaLimpia(dat);
      $("#" + id).val(dat);
    }



    function sigueProducto(ident, seguimiento) {
      if (seguimiento == 1) {
        var value = '0';
      } else {
        var value = '1';
      }
      $('<form action="../funciones/sigueProducto.php" method="POST"><input type="hidden" name="value" value="' + value + '"><input type="hidden" name="ident" value="' + ident + '"></form>').appendTo('body').submit();

    }

    function listaDeptos() {
      //  var mensaje = 'Mensaje';
      $.post("../funciones/listarDeptos.php", {},
        function(respuesta) {
          $("#validation").html(respuesta);
        });
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