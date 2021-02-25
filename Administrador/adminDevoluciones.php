<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad-1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
$idSucursal = $_SESSION['LZFidSuc'];
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
                      <div class="card border-<?=$pyme;?>">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="m-b-0 text-white">Búsquedad de Devoluciones</h4>
                          </div>
                      <div class="card-body">
                        <?php
                        $fechaAct=date('Y-m-d');
                        if (isset($_POST['fechaInicial'])) {
                          $fechaInicial = $_POST['fechaInicial'];
                        }else {
                          $fechaInicial1 = strtotime ( '-1 week' , strtotime ( $fechaAct ) ) ;
                          $fechaInicial = date ( 'Y-m-d' , $fechaInicial1 );
                        }
                        if (isset($_POST['fechaFinal'])) {
                          $fechaFinal = $_POST['fechaFinal'];
                        }else {
                          $fechaFinal1 = strtotime ( '+15 day' , strtotime ( $fechaAct ) ) ;
                          $fechaFinal = date ( 'Y-m-d' , $fechaFinal1 );
                        }

                        $filtroFecha = " AND v.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'";

                        if (isset($_POST['tipo']) && $_POST['tipo'] != '') {
                          $tipo = $_POST['tipo'];
                        } else {
                          $tipo = '';
                        }

                        switch ($tipo) {
                          case 1:
                            $act1 = 'selected';
                            $act2 = '';
                            break;
                          case 2:
                            $act1 = '';
                            $act2 = 'selected';
                            break;

                          default:
                            $act1 = '';
                            $act2 = '';
                            break;
                        }
                      #  echo 'Tipo: '.$tipo;
                         ?>
                         <div class="col-md-12">
                           <div class="row">
                             <div class="col-md-2"></div>
                           <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                             <div class="col-md-12">
                               <h5 class="m-t-30 text-center text-<?=$pyme;?>">Selecciona un rango de Fechas</h5>
                             </div>
                             <div class="col-md-12">
                               <form role="form" action="#" method="post">
                                 <div class="input-daterange input-group" id="date-range">
                                     <input type="date" class="form-control" name="fechaInicial" value="<?=$fechaInicial;?>" />
                                     <div class="input-group-append">
                                         <span class="input-group-text bg-<?=$pyme;?> b-0 text-white"> A </span>
                                     </div>
                                     <input type="date" class="form-control" name="fechaFinal" value="<?=$fechaFinal;?>" />
                                 </div>
                             </div>
                           </div>
                           <div class="col-md-5">
                             <div class="col-md-12">
                                 <div class="row">

                                 <div class="col-md-7">
                                   <h5 class="m-t-30 text-<?=$pyme;?>">Filtrar por:</h5>
                                 </div>
                                 <div class="col-md-5">
                                 </div>
                               </div>
                             </div>
                             <div class="row">
                             <div class="col-md-5">
                               <select class="select2 form-control custom-select" name="tipo" id="tipo" style="width: 95%; height:100%;">
                                 <option value="">Todos</option>
                                 <option value="1" <?=$act1;?>>Devoluciones</option>
                                 <option value="2" <?=$act2;?>>Cancelaciones</option>
                               </select>
                             </div>

                             <div class="col-md-3">
                               <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                             </div>

                           </div>
                         </div>
                         <div class="col-md-1"></div>
                       </div>
                     </div>
                       </form>
                      </div>
                  </div>
                </div>
              </div>

              <?php
              if ($tipo == 1) {
               ?>
              <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table product-overview" id="zero_config">
                              <thead>
                                <tr>
                                  <th class="text-center">Folio de Venta</th>
                                  <th class="text-center">Producto</th>
                                  <th class="text-center">Cantidad</th>
                                  <th class="text-center">Motivo</th>
                                  <th class="text-center">Fecha</th>
                                  <th class="text-center">Usuario</th>
                                  <th class="text-center">Ticket</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $sqlDev = "SELECT dv.idVenta, p.descripcion AS nomProducto, d.cantidad AS cantDevuelto, d.motivo, d.fechaReg, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario
                                            FROM devoluciones d
                                            INNER JOIN ventas v ON d.idVenta = v.id
                                            INNER JOIN detventas dv ON d.idDetVenta = dv.id
                                            INNER JOIN productos p ON dv.idProducto = p.id
                                            INNER JOIN segusuarios u ON d.idUserReg = u.id
                                            WHERE d.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal' AND d.idSucursal = '$idSucursal' AND v.estatus = '2'
                                            ORDER BY d.fechaReg DESC";
                                  $resDev = mysqli_query($link,$sqlDev) or die('Problemas al consultar las devoluciones, notifica a tu Administrador.');

                                  while ($dev = mysqli_fetch_array($resDev)) {

                                    echo '<tr>
                                            <td class="text-center">'.$dev['idVenta'].'</td>
                                            <td>'.$dev['nomProducto'].'</td>
                                            <td class="text-center">'.$dev['cantDevuelto'].'</td>
                                            <td>'.$dev['motivo'].'</td>
                                            <td class="text-center">'.$dev['fechaReg'].'</td>
                                            <td>'.$dev['nomUsuario'].'</td>
                                            <td class="text-center"><button type="button" class="btn btn-info btn-circle"><i class="fas fa-ticket-alt"></i></button></td>
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
            } elseif ($tipo == 2) {
               ?>
              <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table product-overview" id="zero_config2">
                              <thead>
                                <tr>
                                  <th class="text-center">Folio de Venta</th>
                                  <th class="text-center">Motivo</th>
                                  <th class="text-center">Fecha</th>
                                  <th class="text-center">Usuario</th>
                                  <th class="text-center">Ticket</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                #  /*
                                $sqlCancel = "SELECT c.idVenta,c.motivo, c.fecha, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario
                                          FROM cancelaciones c
                                          INNER JOIN ventas v ON c.idVenta = v.id
                                          INNER JOIN segusuarios u ON c.idUsuario = u.id
                                          WHERE c.fecha BETWEEN '$fechaInicial' AND '$fechaFinal' AND c.idSucursal = $idSucursal AND v.estatus = 3
                                          ORDER BY c.fecha DESC";
                                $resCancel = mysqli_query($link,$sqlCancel) or die('Problemas al consultar las devoluciones, notifica a tu Administrador.');

                                while ($cancel = mysqli_fetch_array($resCancel)) {

                                  echo '<tr>
                                          <td class="text-center">'.$cancel['idVenta'].'</td>
                                          <td>'.$cancel['motivo'].'</td>
                                          <td class="text-center">'.$cancel['fecha'].'</td>
                                          <td>'.$cancel['nomUsuario'].'</td>
                                          <td class="text-center"><button type="button" class="btn btn-info btn-circle"><i class="fas fa-ticket-alt"></i></button></td>
                                        </tr>';
                                }
                                #  */
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
            } else {
              ?>
              <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table product-overview" id="zero_config3">
                              <thead>
                                <tr>
                                  <th class="text-center">Folio de Venta</th>
                                  <th class="text-center">Tipo</th>
                                  <th class="text-center">Motivo</th>
                                  <th class="text-center">Cantidad</th>
                                  <th class="text-center">Fecha</th>
                                  <th class="text-center">Usuario</th>
                                  <th class="text-center">Ticket</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                #  /*
                                $sqlCancel = "SELECT a.idVenta, a.cant, a.motivo,a.tipo, a.fechaReg, a.nomUsuario
                                              FROM ventas v
                                              INNER JOIN (
                                              SELECT c.idVenta, 'Todos' AS cant, c.motivo, 'Cancelación' AS tipo, c.fecha AS fechaReg, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario
                                              FROM cancelaciones c INNER JOIN segusuarios u ON c.idUsuario = u.id
                                              UNION
                                              SELECT d.idVenta, SUM(d.cantidad) AS cant, d.motivo, 'Devolución' AS tipo, d.fechaReg, CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat) AS nomUsuario
                                              FROM devoluciones d INNER JOIN ventas v ON d.idVenta = v.id
                                              INNER JOIN segusuarios u2 ON d.idUserReg = u2.id
                                              WHERE v.estatus = '2'
                                              ) a ON a.idVenta = v.id
                                              WHERE v.idSucursal = '$idSucursal' AND a.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'
                                              ORDER BY a.fechaReg DESC";
                                $resCancel = mysqli_query($link,$sqlCancel) or die('Problemas al consultar las devoluciones, notifica a tu Administrador.');

                                while ($cancel = mysqli_fetch_array($resCancel)) {

                                  echo '<tr>
                                          <td class="text-center">'.$cancel['idVenta'].'</td>
                                          <td class="text-center">'.$cancel['tipo'].'</td>
                                          <td>'.$cancel['motivo'].'</td>
                                          <td class="text-center">'.$cancel['cant'].'</td>
                                          <td class="text-center">'.$cancel['fechaReg'].'</td>
                                          <td>'.$cancel['nomUsuario'].'</td>
                                          <td class="text-center"><button type="button" class="btn btn-info btn-circle"><i class="fas fa-ticket-alt"></i></button></td>
                                        </tr>';
                                }
                                #  */
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

    <script>
    $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);
      ?>
    });

    $("#zero_config2").DataTable();
    $("#zero_config3").DataTable();
    </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>
