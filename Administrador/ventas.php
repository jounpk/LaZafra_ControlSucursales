<?php
//phpinfo();
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad-1];
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
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
   <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?=$pyme;?>.ico">
    <title><?=$info->nombrePag;?></title>

    <!-- Custom CSS -->
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
    <link href="../assets/libs/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

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
                <?=$info->customizer('2');?>

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
                        <?=$info->generaMenuUsuario();?>
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?=$info->generaMenuLateral();?>
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
                      <h2 class="text-<?=$pyme;?>"><?=$info->nombrePag;?></h2>
                      <h4><?=$info->detailPag;?></h4>
                  </div>
                  <div class="ml-auto">
                    <h4><b><?=$info->nombreSuc;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4>
                  </div>
                  <br><br>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                      <div class="card">
                        <div class="card-body">
                          <!--TARJETAS -->
                          <div class="row">
                          </div><!--FINAL DE TARJETAS-->
                          <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                            <div class="table-responsive">
                              <table class="table  dataTable" id="zero_config2">
                                <thead>
                                  <tr>
                                    <th class="text-center">#</th>
                                    <th>Ticket</th>
                                    <th class="text-center">Fecha y Hora</th>
                                    <th>Cliente</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Cajero</th>
                                    <th class="text-center">Desgloce</th>
                                    <th class="text-center">Factura</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  setlocale(LC_TIME, 'es_ES.UTF-8');
                                #/*
                                    $sqlVnt = "SELECT v.idSucursal,v.id AS idVenta, v.fechaReg, cli.nombre AS nomCliente, v.total,cd.estatus AS estadoCredito, v.estatus AS estatusVenta,
                                              CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomCajero, d.id AS devolucion
                                              FROM ventas v
                                              INNER JOIN segusuarios u ON v.idUserReg = u.id
                                              LEFT JOIN devoluciones d ON v.id = d.idVenta
                                              LEFT JOIN cortes c ON v.idCorte = c.id
                                              LEFT JOIN clientes cli ON v.idCliente = cli.id
                                              LEFT JOIN creditos cd ON v.id = cd.idVenta
                                              WHERE v.idSucursal = '$idSucursal'  AND IF(v.idCorte >= 1,c.estatus < '2', 1=1) AND DATE_FORMAT(v.fechaReg, '%Y-%m-%d')=DATE_FORMAT(NOW(), '%Y-%m-%d')
                                              ORDER BY v.estatus ASC,  v.fechaReg ASC";
                                   // echo $sqlVnt;
                                    $resVnt = mysqli_query($link,$sqlVnt) or die('Problemas al consultar los créditos pendientes, notifica a tu Administrador.'.mysqli_error($link));
                                    $cont = $idventa = 0;
                                    $estado = $tipoPagos = $color = $ids = '';
                                    $cant = mysqli_num_rows($resVnt);

                                  if ($cant > 0) {
                                    while ($vnt = mysqli_fetch_array($resVnt)) {
                                      $idventa = $vnt['idVenta'];
                                      if ($vnt['devolucion'] > 0) {
                                        $dev = '<br>(Dev. # '.$vnt['devolucion'].')';
                                      } else {
                                        $dev = '';
                                      }

                                      ++$cont;
                                      switch ($vnt['estatusVenta']) {
                                        case '1':
                                          $estado = 'Abierta';
                                          $color = '';
                                          break;
                                        case '2':
                                          $estado = 'Pagada';
                                          $color = '';
                                          break;
                                        case '3':
                                          $estado = 'Cancelada';
                                          $color = 'class="table-danger"';
                                          break;
                                        default:
                                          $estado = 'Cancelada en 0';
                                          $color = 'class="table-danger"';
                                          break;
                                      }
                                      if ($vnt['estatusVenta'] == 2 AND $vnt['estadoCredito'] == 1) {
                                        $estado = 'Crédito';
                                        $color = 'class="table-warning"';
                                      }
                                      $sqlConFact = "SELECT IF(ISNULL(vf.id),0,COUNT(vf.id)) AS facturada, IF(ISNULL(fg.idCancelada),0,COUNT( fg.idCancelada)) AS facturaCancelada
                                                      FROM vtasfact vf
                                                      INNER JOIN facturasgeneradas fg ON vf.idFactgen = fg.id
                                                      WHERE vf.idVenta = '$idventa'
                                                      GROUP BY vf.idVenta LIMIT 1";
                                      $resConFact = mysqli_query($link,$sqlConFact) or die('Problemas al consultar las facturas, notifica a tu Administrador.');
                                      $dat = mysqli_fetch_array($resConFact);

                                      if ($dat['facturaCancelada'] <= $dat['facturada'] AND $dat['facturada']>0 ) {
                                        $btnFacturar = '<button type="button" class="btn btn-success btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta('.$idventa.')" id="btn-'.$idventa.'"><i class="fas fa-download text-white"></i></button></button>';
                                      } elseif ($dat['facturaCancelada'] > $dat['facturada']) {
                                        $btnFacturar = '<button type="button" class="btn btn-warning btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta('.$idventa.')" id="btn-'.$idventa.'"><i class="fas fa-copy text-white"></i></button></button>';
                                      } else {
                                        $btnFacturar = '<button type="button" class="btn bg-'.$pyme.' btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta('.$idventa.')" id="btn-'.$idventa.'"><i class="fas fa-copy text-white"></i></button></button>';
                                      }
                                        if ($vnt['estatusVenta'] == 3 OR $vnt['estatusVenta'] == 5 OR $vnt['estatusVenta'] == 1 ){

                                          $btnFacturar = '<i class="fas fa-times-circle fa-2x text-danger "></i>';


                                        }
                                        $ids .= $idventa.',';
                                        $cliente= $vnt['nomCliente']==''? "<small>Sin Asignar</small>" : $vnt['nomCliente'];
                                      echo '<tr '.$color.'>
                                              <td class="text-center">'.$cont.' '.$dev.'</td>
                                              <td class="text-center">'.$idventa.'</td>
                                              <td class="text-center">'.strftime("%d-%m-%y  %H:%m:%S", strtotime($vnt['fechaReg'])).'</td>
                                              <td>'.$cliente.'</td>
                                              <td class="text-right">$ '.number_format($vnt['total'],2,'.',',').'</td>
                                              <td class="text-center">'.$estado.'</td>
                                              <td class="text-center">'.$vnt['nomCajero'].'</td>
                                              <td class="text-center">
                                                <button type="button" class="btn bg-'.$pyme.' btn-circle muestraSombra" title="Mostrar Desgloce" data-toggle="modal" data-target="#modalDesgloceVenta" onClick="muestraDesgloce('.$idventa.')"><i class="fas fa-bars text-white"></i></button></button>
                                              </td>
                                              <td class="text-center">
                                                '.$btnFacturar.'
                                              </td>
                                            </tr>';

                                    }
                                  }
                                    #*/
                                    $ids = trim($ids,',');
                                   ?>

                                </tbody>
                              </table>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <?php
                            $totTotal = $total = $totGastos = $totDev = 0;
                            #/*
                            if ($cant > 0) {
                              $sqlTotales = "SELECT SUM(CASE pv.idFormaPago WHEN '1' THEN pv.monto ELSE 0 END) AS Efectivo,
                                              SUM(CASE pv.idFormaPago WHEN '2' THEN pv.monto ELSE 0 END) AS Cheques,
                                              SUM(CASE pv.idFormaPago WHEN '3' THEN pv.monto ELSE 0 END) AS Transferencias,
                                              SUM(CASE pv.idFormaPago WHEN '4' THEN pv.monto ELSE 0 END) AS TarjetaD,
                                              SUM(CASE pv.idFormaPago WHEN '5' THEN pv.monto ELSE 0 END) AS TarjetaC,
                                              SUM(CASE pv.idFormaPago WHEN '6' THEN pv.monto ELSE 0 END) AS Boletas,
                                              SUM(CASE pv.idFormaPago WHEN '7' THEN pv.monto ELSE 0 END) AS Creditos,
                                              IF(dv.cantCancel>0,SUM(dv.precioVenta * dv.cantCancel),0) AS Devoluciones,
                                              a.Gastos
                                              FROM ventas v
                                              INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                              LEFT JOIN detventas dv ON v.id = dv.id
                                              LEFT JOIN (
                                                SELECT IF(SUM(g.monto)>0,SUM(g.monto),0) AS Gastos, g.idSucursal
                                                FROM gastos g LEFT JOIN cortes c ON g.idCorte = c.id
                                                WHERE g.idSucursal = '$idSucursal' AND g.idCorte >= 0 AND IF(g.idCorte > 0, c.estatus = 1, 1=1)
                                              ) a ON v.idSucursal = a.idSucursal
                                              WHERE v.id IN($ids) AND v.estatus < 3";
                              $resTotales = mysqli_query($link,$sqlTotales) or die('Problemas al consultar los totales del día, notifica a tu Administrador.');
                              #echo '<br>$sqlTotales: '.$sqlTotales;
                              $tot = mysqli_fetch_array($resTotales);
                            $tarjetas = $tot['TarjetaD'] + $tot['TarjetaC'];
                            #/*
                            if ($tot['Efectivo'] > 0) {
                                echo '<div class="col-md-3">
                                <div class="card border">
                                    <div class="d-flex flex-row">
                                        <div class="p-10 bg-'.$pyme.'">
                                            <h3 class="text-white box m-b-0"><i class="far fa-money-bill-alt"></i></h3></div>
                                        <div class="p-10">
                                            <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Efectivo'],2,'.',',').'</h3>
                                            <span class="text-muted">Total Efectivo</span>
                                        </div>
                                    </div>
                                </div>

                                      </div>';
                                    }
                            if ($tot['Cheques'] > 0) {
                                echo '
                                <div class="col-md-3">
                                <div class="card border">
                                    <div class="d-flex flex-row">
                                        <div class="p-10 bg-'.$pyme.'">
                                            <h3 class="text-white box m-b-0"><i class="far fa-list-alt"></i></h3></div>
                                        <div class="p-10">
                                            <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Cheques'],2,'.',',').'</h3>
                                            <span class="text-muted">Total Cheques</span>
                                        </div>
                                    </div>
                                </div>

                                      </div>';
                                    }
                            if ($tot['Transferencias'] > 0) {
                                echo '
                                <div class="col-md-3">
                                <div class="card border">
                                    <div class="d-flex flex-row">
                                        <div class="p-10 bg-'.$pyme.'">
                                            <h3 class="text-white box m-b-0"><i class="fas fa-tty"></i></h3></div>
                                        <div class="p-10">
                                            <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Transferencias'],2,'.',',').'</h3>
                                            <span class="text-muted">Total Transferencias</span>
                                        </div>
                                    </div>
                                </div>
                                      </div>';
                                    }
                            if ($tarjetas > 0) {
                                  echo '
                                  <div class="col-md-3">
                                  <div class="card border">
                                      <div class="d-flex flex-row">
                                          <div class="p-10 bg-'.$pyme.'">
                                              <h3 class="text-white box m-b-0"><i class="fas fa-credit-card"></i></h3></div>
                                          <div class="p-10">
                                              <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tarjetas,2,'.',',').'</h3>
                                              <span class="text-muted">Total Tarjetas</span>
                                          </div>
                                      </div>
                                  </div>
                                        </div>';
                                      }
                              if ($tot['Boletas'] > 0) {
                                  echo '
                                  <div class="col-md-3">
                                  <div class="card border">
                                      <div class="d-flex flex-row">
                                          <div class="p-10 bg-'.$pyme.'">
                                              <h3 class="text-white box m-b-0"><i class="fas fa-clipboard-check"></i></h3></div>
                                          <div class="p-10">
                                              <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Boletas'],2,'.',',').'</h3>
                                              <span class="text-muted">Total Boletas</span>
                                          </div>
                                      </div>
                                  </div>
                                        </div>';
                                      }
                              if ($tot['Creditos'] > 0) {
                                  echo '

                                  <div class="col-md-3">
                                  <div class="card border">
                                      <div class="d-flex flex-row">
                                          <div class="p-10 bg-'.$pyme.'">
                                              <h3 class="text-white box m-b-0"><i class="fas fa-handshake"></i></h3></div>
                                          <div class="p-10">
                                              <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Creditos'],2,'.',',').'</h3>
                                              <span class="text-muted">Total Creditos</span>
                                          </div>
                                      </div>
                                  </div>
                                        </div>';
                                      }
                              if ($tot['Devoluciones'] > 0) {
                                  echo '
                                  <div class="col-md-3">
                                  <div class="card border">
                                      <div class="d-flex flex-row">
                                          <div class="p-10 bg-'.$pyme.'">
                                              <h3 class="text-white box m-b-0"><i class="fas fa-dolly"></i></h3></div>
                                          <div class="p-10">
                                              <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Devoluciones'],2,'.',',').'</h3>
                                              <span class="text-muted">Total Devoluciones</span>
                                          </div>
                                      </div>
                                  </div>
                                        </div>';
                                      }
                              if ($tot['Gastos'] > 0) {
                                  echo '
                                  <div class="col-md-3">
                                  <div class="card border">
                                      <div class="d-flex flex-row">
                                          <div class="p-10 bg-'.$pyme.'">
                                              <h3 class="text-white box m-b-0"><i class="far fa-clipboard"></i></h3></div>
                                          <div class="p-10">
                                              <h3 class="text-'.$pyme.' m-b-0">$'.number_format($tot['Gastos'],2,'.',',').'</h3>
                                              <span class="text-muted">Total Gastos</span>
                                          </div>
                                      </div>
                                  </div>
                                        </div>';
                                      }

                                      $total = $tot['Efectivo'] + $tot['Cheques'] + $tot['Transferencias'] + $tarjetas + $tot['Boletas'] + $tot['Creditos'] - $tot['Devoluciones'] - $tot['Gastos'];


                                  #*/

                                }
                            #  echo '<br><br>$ids: '.$ids;
                             ?>
                             <div class="col-md-12">
                               <div class="row">
                                 <div class="col-md-4">
                                 </div>
                                  <div class="col-md-4">
                                    <div class="card card-hover">
                                      <div class="box bg-success text-center">
                                        <h4 class="font-light text-white">$ <?=number_format($total,2,'.',',');?></h4>
                                        <h6 class="text-white">Total Ventas</h6>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- sample modal content -->
                  <div id="modalDesgloceVenta" class="modal bs-example-modal-lg fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title" id="lblDesgloceVenta">Desgloce de Venta: </h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                              </div>
                              <div class="modal-body" id="desgloceVentaBody">

                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- /.modal -->

                  <!-- sample modal content -->
                  <div id="modalFacturaVenta" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header bg-<?=$pyme?>">
                                  <h4 class="modal-title text-white" id="lblFacturaVenta">Ticket No.: </h4>
                                  <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
                              </div>
                              <div class="modal-body" id="facturaVentaBody">

                              </div>

                          </div>
                      </div>
                  </div>
                  <!-- /.modal -->
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
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

    <script src="../dist/js/pages/forms/mask/mask.init.js"></script>               

    <script>
    $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

    if (isset( $_SESSION['LZFmsjAdminVentas'])) {
      echo "notificaBad('".$_SESSION['LZFmsjAdminVentas']."');";
      unset($_SESSION['LZFmsjAdminVentas']);
    }
    if (isset( $_SESSION['LZFmsjSuccessAdminVentas'])) {
      echo "notificaSuc('".$_SESSION['LZFmsjSuccessAdminVentas']."');";
      unset($_SESSION['LZFmsjSuccessAdminVentas']);
    }
      ?>

    });

    function formatoTickets(arrayTicket){
      resultado='';
      arrayTicket.forEach(element => resultado=''+resultado+''+element+' ');

      return resultado;
    }

    function buscarXCliente(){
    nombreCliente=$('#busq_nombre').val();
    //alert(nombreCliente);
    $.post("../funciones/busquedaCliente.php",
      {id:nombreCliente},
      function(respuesta){
        if (respuesta[0] == 0) {
        var resp = respuesta.split('|');
          notificaBad(resp[1]);
        } else {
          $("#resultadosBusq").html(respuesta);
        }
      });



    }


    function buscarXRFC(){
    rfcCliente=$('#busq_rfc').val();
    //alert(nombreCliente);
    $.post("../funciones/busquedaRFC.php",
      {id:rfcCliente},
      function(respuesta){
        if (respuesta[0] == 0) {
        var resp = respuesta.split('|');
          notificaBad(resp[1]);
        } else {

          $("#resultadosBusq").html(respuesta);

        }
      });



    }
  
    function imprimeTicketVenta(idVenta){
        $('<form action="../imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="'+idVenta+'"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
    }

    function muestraDesgloce(idVenta){
      if (idVenta > 0) {
        //alert('Entra, idVenta: '+idVenta);
        $.post("../funciones/muestraDesgloceVenta.php",
      {idVenta:idVenta},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#lblDesgloceVenta").html('Desgloce de Venta: '+idVenta);
        $("#desgloceVentaBody").html(resp[1]);
      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
    }
    function cambioMunicipio(idEstado){

      $.post("../funciones/listaMunicipios.php",
    {idEstado:idEstado},
    function(respuesta){

      $("#municipios").html(respuesta);

    }
);


    }
    function modalFacturaVenta(idVenta){
      if (idVenta > 0) {
        //alert('Entra, idVenta: '+idVenta);
        $.post("../funciones/formularioFacturacion.php",
      {idVenta:idVenta},
    function(respuesta){
      $("#lblFacturaVenta").html("Ticket No.: "+idVenta);
        $("#facturaVentaBody").html(respuesta);
    });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
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

