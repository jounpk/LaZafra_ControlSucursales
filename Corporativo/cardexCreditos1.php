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
                <h4 class="m-b-0 text-white">Búsquedad de Créditos</h4>
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

                if (isset($_POST['cliente']) && $_POST['cliente'] > 0) {
                  $cliente = $_POST['cliente'];
                  $filtroCliente = " AND cl.id = '$cliente'";
                } else {
                  $cliente = '';
                  $filtroCliente = '';
                }
                if (isset($_POST['empresa']) && $_POST['empresa'] > 0) {
                  $empresa = $_POST['empresa'];
                  $filtroEmpresa = " AND s.idEmpresa = '$empresa'";
                } else {
                  $empresa = '';
                  $filtroEmpresa = '';
                }
                //empresa

                if (isset($_POST['tipo']) && $_POST['tipo'] > 0) {
                  $tipo = $_POST['tipo'];
                } else {
                  $tipo = '';
                }

                if (isset($_POST['estatusCred']) && $_POST['estatusCred'] > 0) {
                  $estatusCred = 'c.estatus = "' . $_POST['estatusCred'] . '"';
                  $selCred = $_POST['estatusCred'];
                } else {
                  $estatusCred = 'c.estatus > 0';
                  $selCred = '';
                }
                #echo '$estatusCred: '.$estatusCred;
                switch ($selCred) {
                  case '1':
                    $sel1 = 'selected';
                    break;
                  case '2':
                    $sel2 = 'selected';
                    break;
                  case '3':
                    $sel3 = 'selected';
                    break;

                  default:
                    $sel1 = $sel2 = $sel3 = '';
                    break;
                }
                #  echo 'Tipo: '.$tipo;
                ?>
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
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                      <div class="col-md-12">
                        <h5 class="m-t-10 text-center text-<?= $pyme; ?>">Selecciona un rango de Fechas</h5>
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
                    <div class="col-md-7">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-3">
                            <h5 class="m-t-10 text-<?= $pyme; ?>">Selecciona una Empresa:</h5>
                          </div>
                          <div class="col-md-3">
                            <h5 class="m-t-10 text-<?= $pyme; ?>">Selecciona un Cliente:</h5>
                          </div>
                          <div class="col-md-3">
                            <h5 class="m-t-10 text-<?= $pyme; ?>">Estado del Crédito:</h5>
                          </div>
                          <div class="col-md-1">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <select class="select2 form-control custom-select" name="empresa" id="empresa" style="width: 95%; height:100%;">
                            <?php
                            echo '<option value="">Selecciona la empresa</option>';
                            $sql = "SELECT id,nombre FROM empresas WHERE estatus = '1'";
                            #  echo $sql;
                            $res = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($res)) {
                              $activa1 = ($empresa == $row['id']) ? 'selected' : '';
                              echo '<option value="' . $row['id'] . '" ' . $activa1 . '>' . $row['nombre'] . '</option>';
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <select class="select2 form-control custom-select" name="cliente" id="cliente" style="width: 95%; height:100%;">
                            <?php
                            echo '<option value="">Ingresa el Nombre del cliente</option>';
                            $sql = "SELECT id, nombre, apodo,credito, limitecredito FROM clientes WHERE estatus = '1'";
                            #  echo $sql;
                            $res = mysqli_query($link, $sql);
                            while ($rows = mysqli_fetch_array($res)) {
                              $apodo = ($rows['apodo'] != '') ? '(' . $rows['apodo'] . ')' : '';
                              $activa = ($cliente == $rows['id']) ? 'selected' : '';
                              echo '<option value="' . $rows['id'] . '" ' . $activa . '>' . $rows['nombre'] . ' ' . $apodo . '</option>';
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <select class="form-control" id="estatusCred" name="estatusCred">
                            <option value="0"> Selecciona un estado</option>
                            <option value="1" <?= $sel1; ?>> Pendiente</option>
                            <option value="2" <?= $sel2; ?>> Pagado</option>
                            <option value="3" <?= $sel3; ?>> Cancelado</option>
                          </select>
                        </div>
                        <div class="col-md-1">
                          <button type="submit" class="btn btn-<?= $pyme; ?>">Buscar</button>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php
        if ($cliente > 0) {
        ?>
          <div class="card" id="pagoPorDia">
            <div class="card-body">
              <div class="col-md-12 col-lg-12">
                <h4 class="card-title"><b>Pago por Día</b></h4>
                <div class="text-right">
                  <button type="button" class="btn btn-info btn-circle" onclick="cambiaPagos();"><i class="fas fa-check"></i></button>
                </div><br>
                <div id="accordian-3">
                  <?php
                  $sqlCred = "SELECT DATE_FORMAT(pc.fechaReg,'%d/%m/%Y') AS FechaHora,c.*,SUM(pc.monto) AS pago
                                          FROM creditos c
                                          INNER JOIN ventas v ON c.idVenta = v.id
                                          INNER JOIN pagoscreditos pc ON c.id = pc.idCredito
                                          INNER JOIN sucursales s ON pc.idSucursal = s.id
                                          WHERE c.idCliente = '$cliente' AND pc.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal' AND v.estatus < '3' $filtroEmpresa
                                          GROUP BY FechaHora
                                          ORDER BY pc.fechaReg DESC";
                  $resCred = mysqli_query($link, $sqlCred) or die('Problemas al consultar los créditos por fecha, notifica a tu Administrador.');
                  $countCred = 0;
                  $fechaReg = 0;
                  while ($fila = mysqli_fetch_array($resCred)) {
                    ++$countCred;
                    $fechaReg = $fila['FechaHora'];
                    $idCredito = $fila['id'];


                    echo '<div class="card">
                                          <a class="card-header" id="heading' . $idCredito . '-' . $countCred . '">
                                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#credito' . $idCredito . '-' . $countCred . '" aria-expanded="false" aria-controls="credito' . $idCredito . '-' . $countCred . '">
                                                  <h5 class="mb-0">Día ' . $fila['FechaHora'] . ', Pago $' . number_format($fila['pago'], 2, '.', ',') . '</h5>
                                              </button>
                                              <br>&nbsp;&nbsp;&nbsp;

                                          </a>
                                          <div id="credito' . $idCredito . '-' . $countCred . '" class="collapse" aria-labelledby="heading' . $idCredito . '-' . $countCred . '" data-parent="#accordian-3" style="">
                                              <div class="card-body">';
                    $sqlPagos = "SELECT s.nombre AS nomSucursal, CONCAT(u.nombre, ' ', u.appat, ' ', apmat) AS nomUsuario, pc.*, cm.nombre AS formaPago,pc.fechaReg,c.idVenta, c.estatus AS estatusCredito
                                                            FROM pagoscreditos pc
                                                            INNER JOIN creditos c ON pc.idCredito = c.id
                                                            INNER JOIN ventas v ON c.idVenta = v.id
                                                            INNER JOIN sat_formapago cm ON pc.idFormaPago = cm.id
                                                            INNER JOIN sucursales s ON pc.idSucursal = s.id
                                                            INNER JOIN segusuarios u ON pc.idUserReg = u.id
                                                          WHERE c.idCliente = '$cliente' AND DATE_FORMAT(pc.fechaReg,'%d/%m/%Y') = '$fechaReg' AND v.estatus = 2 $filtroEmpresa ORDER BY pc.fechaReg DESC";
                    #echo $sqlPagos;
                    $resPagos = mysqli_query($link, $sqlPagos) or die('Problemas al consultar los pagos, notifica a tu Administrador.');

                    echo '<div class="table-responsive">
                                                      <table class="table">
                                                          <thead class="text-dark">
                                                              <tr>
                                                                  <th class="text-center">#</th>
                                                                  <th class="text-center">Fecha de Pago</th>
                                                                  <th>Sucursal</th>
                                                                  <th>Atendió</th>
                                                                  <th class="text-center">Venta</th>
                                                                  <th>Forma de Pago</th>
                                                                  <th class="text-center">Monto Pagado</th>
                                                                  <th class="text-center">Estatus del Crédito</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>';
                    $countPago = 0;
                    while ($cPago = mysqli_fetch_array($resPagos)) {
                      switch ($cPago['estatusCredito']) {
                        case 1:
                          $estatusPago = 'Pendiente';
                          $color = 'text-warning';
                          break;
                        case 2:
                          $estatusPago = 'Pagado';
                          $color = 'text-success';
                          break;

                        default:
                          $estatusPago = 'Cancelado';
                          $color = 'text-danger';
                          break;
                      }
                      ++$countPago;
                      echo '<tr>
                                                        <td class="text-center">' . $countPago . '</td>
                                                        <td class="text-center">' . $cPago['fechaReg'] . '</td>
                                                        <td>' . $cPago['nomSucursal'] . '</td>
                                                        <td>' . $cPago['nomUsuario'] . '</td>
                                                        <td class="text-center">' . $cPago['idVenta'] . '</td>
                                                        <td>' . $cPago['formaPago'] . '</td>
                                                        <td class="text-right">$' . number_format($cPago['monto'], 2, '.', ',') . '</td>
                                                        <td class="text-center"><b class="' . $color . '">' . $estatusPago . '</b></td>
                                                      </tr>';
                    }
                    echo '</tbody>
                                                      </table>
                                                      </div>';

                    echo '</div>
                                          </div>
                                      </div>';
                  }
                  ?>

                </div>
              </div>
            </div>
          </div>


          <div class="row" id="pagoPorCredito" style="display: none;">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><b>Pago por Crédito</b></h4>
                  <div class="text-right">
                    <button type="button" class="btn btn-info btn-circle" onclick="cambiaPagos();"><i class="fas fa-check"></i></button>
                  </div>
                  <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered dataTable" id="zero_config">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Sucursal</th>
                            <th class="text-center">Venta</th>
                            <th class="text-center">Último Pago</th>
                            <th class="text-center">Pagado</th>
                            <th class="text-center">Total del Crédito</th>
                            <th class="text-center">Por Pagar</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-center">Ticket</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sqlPagInd = "SELECT c.idVenta, SUM(pc.monto) AS pagado, c.totalDeuda,pc.fechaReg,c.montoDeudor,
                                                  s.nombre AS nomSucursal, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario,c.estatus AS estadoDeCredito
                                                  FROM creditos c
                                                  INNER JOIN ventas v ON c.idVenta = v.id
                                                  INNER JOIN sucursales s ON v.idSucursal = s.id
                                                  INNER JOIN segusuarios u ON v.idUserReg = u.id
                                                  INNER JOIN clientes cl ON v.idCliente = cl.id
                                                  INNER JOIN pagoscreditos pc ON c.id = pc.idCredito
                                                  WHERE v.estatus < 4  AND pc.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal' AND cl.id = '$cliente' $filtroEmpresa
                                                  GROUP BY c.id
                                                  ORDER BY pc.fechaReg DESC";
                          #  echo '$sqlPagInd: '.$sqlPagInd;
                          $resPagInd = mysqli_query($link, $sqlPagInd) or die('Problemas al consultar los creditos pagados, notifica a tu Administrador.');
                          $contPagInd = 0;
                          while ($row = mysqli_fetch_array($resPagInd)) {
                            ++$contPagInd;
                            switch ($row['estadoDeCredito']) {
                              case 1:
                                $estatusPago = 'Pendiente';
                                $colorFila = '';
                                break;
                              case 2:
                                $estatusPago = 'Pagado';
                                $colorFila = 'class="table-success';
                                break;

                              default:
                                $estatusPago = 'Cancelado';
                                $colorFila = 'table-danger';
                                break;
                            }
                            echo '<tr class="' . $colorFila . '">
                                              <td class="text-center">' . $contPagInd . '</td>
                                              <td>' . $row['nomSucursal'] . '</td>
                                              <td class="text-center">' . $row['idVenta'] . '</td>
                                              <td class="text-center">' . $row['fechaReg'] . '</td>
                                              <td class="text-right">$' . number_format($row['pagado'], 2, '.', ',') . '</td>
                                              <td class="text-right">$' . number_format($row['totalDeuda'], 2, '.', ',') . '</td>
                                              <td class="text-right">$' . number_format($row['montoDeudor'], 2, '.', ',') . '</td>
                                              <td class="text-center">' . $row['nomUsuario'] . '</td>
                                              <td class="text-center">' . $estatusPago . '</td>
                                              <td class="text-center">
                                                <button type="button" class="btn btn-info btn-circle muestraSombra" title="Imprimir Ticket" onClick="imprimeTicketVenta(' . $row['idVenta'] . ');"><i class="fas fa-print"></i></button>
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
          </div>
        <?php
        }
        ?>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title"><b>Créditos Por Cobrar</b></h4>
                <br>
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered dataTable" id="zero_config2">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Cliente</th>
                          <th>Sucursal</th>
                          <th class="text-center">Venta</th>
                          <th class="text-center">Adeudo</th>
                          <th class="text-center">Usuario</th>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Ticket</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sqlPend = "SELECT c.*, v.fechaReg,s.nombre, CONCAT(u.nombre,' ', u.appat, ' ',u.apmat) AS nomUsuario, CONCAT(cl.nombre,' (',cl.apodo,')') AS nomCliente,v.id AS idVenta
                                                  FROM creditos c
                                                  INNER JOIN ventas v ON c.idVenta = v.id
                                                  INNER JOIN sucursales s ON v.idSucursal = s.id
                                                  INNER JOIN segusuarios u ON v.idUserReg = u.id
                                                  INNER JOIN clientes cl ON v.idCliente = cl.id
                                                  WHERE $estatusCred $filtroCliente $filtroEmpresa
                                                  ORDER BY v.fechaReg ASC";
                        #echo '$sqlPend: '.$sqlPend;
                        $resPend = mysqli_query($link, $sqlPend) or die('Problemas al consultar los créditos pendientes, notifica a tu Administrador.');
                        $conPend = 0;
                        while ($pend = mysqli_fetch_array($resPend)) {
                          switch ($pend['estatus']) {
                            case '2':
                              $tr = 'class="table-success"';
                              break;
                            case '3':
                              $tr = 'class="table-danger"';
                              break;

                            default:
                              $tr = '';
                              break;
                          }
                          ++$conPend;
                          echo '<tr ' . $tr . '>
                                                <td class="text-center">' . $conPend . '</td>
                                                <td>' . $pend['nomCliente'] . '</td>
                                                <td>' . $pend['nombre'] . '</td>
                                                <td class="text-center">' . $pend['idVenta'] . '</td>
                                                <td class="text-center">$ ' . number_format($pend['montoDeudor'], 2, '.', ',') . '</td>
                                                <td>' . $pend['nomUsuario'] . '</td>
                                                <td class="text-center">' . $pend['fechaReg'] . '</td>
                                                <td class="text-center">
                                                  <button type="button" class="btn btn-info btn-circle muestraSombra" title="Imprimir Ticket" onClick="imprimeTicketVenta(' . $pend['idVenta'] . ');"><i class="fas fa-print"></i></button>
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
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>

  <script>
    $(document).ready(function() {
      <?php
      #  include('../funciones/basicFuctions.php');
      #  alertMsj($nameLk);
      ?>
    });

    $("#zero_config2").DataTable();

    function cambiaPagos() {
      $("#pagoPorDia").toggle('fast');
      $("#pagoPorCredito").toggle('fast');
    }

    function imprimeTicketVenta(idVenta) {

      $('<form action="../imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
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