<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
require_once('include/connect.php');
$info->Acceso($nameLk);
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
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
    <link rel="icon" type="image/icon" sizes="16x16" href="assets/images/<?= $pyme; ?>.ico">
    <title><?= $info->nombrePag; ?></title>

    <!-- This Page CSS -->
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/css-chart/css-chart.css">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <link href="assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">
    <link href="assets/libs/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
    <link href="assets/extra-libs/calendar/calendar.css" rel="stylesheet" />
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
                <?= $info->customizer(); ?>

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
                        <audio id="player" src="assets/images/soundbb.mp3"> </audio>

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

                <section id="contenido">
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
                        <div class="col-lg-4 col-md-6">
                            <?php
                            #
                            $sql = "SELECT montoInicio,id FROM cortes WHERE idSucursal='$idSucursal' AND estatus=1 AND idUserReg='$idUser'";
                            $res = mysqli_query($link, $sql) or die("Error de Consulta caja." . $sql);
                            $res1 = mysqli_fetch_array($res);
                            $cantInicio = mysqli_num_rows($res);

                            if ($cantInicio > 0) {
                                echo '<a href="javascrip:void(0);" data-toggle="modal" data-target="#modalCierraCaja">
                        <div class="card border-bottom border-right border-success">
                            <div class="card-body">
                                <div class="row p-t-10 p-b-10">
                                    <!-- Column -->
                                    <div class="col p-r-0">
                                        <h3 class="font-light">Caja</h3>
                                        <h5 class="text-success"><b>Generar Cierre de Caja</b></h5>
                                        <h6 class="text-muted"><b>Clic aquí</b></h6>
                                    </div>
                                    <!-- Column -->
                                    <div class="col text-right align-self-center">
                                        <div data-label="20%" class="css-bar m-b-0 css-bar-success css-bar-100"><i class="fas fa-laptop"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </a>';
                            } else {
                                echo '<a href="javascrip:void(0);" data-toggle="modal" data-target="#modalInicioCajja">
                          <div class="card border-bottom border-right border-danger">
                              <div class="card-body">
                                  <div class="row p-t-10 p-b-10">
                                      <!-- Column -->
                                      <div class="col p-r-0">
                                          <h3 class="font-light">Caja</h3>
                                          <h4 class="text-danger"><b>¡Apertura Caja!</b></h4>
                                          <h6 class="text-muted"><b>Click aquí</b></h6>
                                      </div>
                                      <!-- Column -->
                                      <div class="col text-right align-self-center">
                                          <div data-label="20%" class="css-bar m-b-0 css-bar-danger css-bar-100"><i class="fas fa-laptop"></i></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        </a>';
                            }

                            ?>


                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-bottom border-right border-info">
                                <div class="card-body">
                                    <div class="row p-t-10 p-b-10">
                                        <!-- Column -->
                                        <div class="col p-r-0">
                                            <?php
                                            /*
                                    $sql="SELECT COUNT(stk.id) AS cant
                                          FROM stocks stk
                                          INNER JOIN productos pd ON stk.idProducto = pd.id
                                          WHERE stk.idSucursal='$idSucursal' AND stk.cantMinima>stk.cantActual AND pd.estatus = '1'
                                          ORDER BY stk.cantMinima DESC";
                                    echo $sql;
                                    $res= mysqli_query($link,$sql) or die ("Problemas al consultar las cantidades de los productos, notifica a tu Administrador.".mysqli_error($link));
                                    $cant=mysqli_fetch_array($res);
                                    $numRes = $cant['cant'];
                                    #*/
                                            ?>
                                            <h1 class="font-light"><?= $numRes; ?></h1>
                                            <h6 class="text-muted">Productos a Surtir</h6>
                                        </div>
                                        <!-- Column -->
                                        <div class="col text-right align-self-center">
                                            <div data-label="20%" class="css-bar m-b-0 css-bar-primary css-bar-70"><i class="mdi mdi-account-circle"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-bottom border-right border-warning">
                                <div class="card-body">
                                    <div class="row p-t-10 p-b-10">
                                        <?php
                                        #/*
                                        $sqlCred = "SELECT COUNT(CASE cd.id WHEN (TIMESTAMPDIFF(DAY,now(),vnt.fechaReg) > 30) THEN cd.id END) AS day15
                                              FROM creditos cd
                                              INNER JOIN ventas vnt ON cd.idVenta = vnt.id
                                              WHERE cd.estatus = 1 AND vnt.idSucursal = '$idSucursal'";
                                        $resCred = mysqli_query($link, $sqlCred) or die('Problemas al consultar las fechas de los créditos, notifica a tu Administrador.');
                                        $dato = mysqli_fetch_array($resCred);
                                        $cantCred = $dato['day15'];
                                        #*/
                                        ?>
                                        <!-- Column -->
                                        <div class="col p-r-0">
                                            <h1 class="font-light"><?= $cantCred; ?></h1>
                                            <h6 class="text-muted">Créditos por Vencer</h6>
                                        </div>
                                        <!-- Column -->
                                        <div class="col text-right align-self-center">
                                            <div data-label="20%" class="css-bar m-b-0 css-bar-warning css-bar-40"><i class="fas fa-dollar-sign"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card border-<?= $pyme; ?>">
                                <div class="card-header bg-<?= $pyme; ?>">
                                    <h4 class="m-b-0 text-white">Calendario de Actividades</h4>
                                </div>
                                <div class="card-body">
                                    <div class="text-right">
                                    </div>
                                    <div class="card-body b-l calender-sidebar">
                                        <div id="calendar"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </section>

                <!-- sample modal content -->
                <div id="modalInicioCajja" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="lblinicioCajja">Inicio de Caja</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form role="form" id="formIniciaCaja" action="funciones/iniciaCorte.php" method="post">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inicioCorte1"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" min="0" step="0.01" class="form-control" id="inicioCorte" placeholder="Ingresa el Monto de Inicio de Caja" aria-describedby="inicioCorte" name="inicioCorte" required>
                                    </div>

                                    <div class="modal-footer">
                                        <input type="hidden" id="idMarca">
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
                <div id="modalCierraCaja" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="lblCierraCaja">¿Estás seguro(a) de realizar el corte?</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">

                                Antes de Generar el corte de caja debes estar seguro de:
                                <?php
                                $sql = "SELECT * FROM cortes WHERE idUserReg='$idUser' AND idSucursal='$idSucursal' AND estatus=1";
                                $result = mysqli_query($link, $sql) or die('Hubo un error de comparativa C notifique al administrador.');
                                $cant = mysqli_num_rows($result);
                                $caja = mysqli_fetch_array($result);
                                $clase = ($cant >= 1) ? 'class="text-success"' : 'class="text-danger"';

                                $sql = "SELECT * FROM ventas WHERE idUserReg='$idUser' AND idSucursal='$idSucursal' AND estatus=1";
                                $res = mysqli_query($link, $sql) or die('Hubo un error de comparativa V notifique al administrador.');
                                $cant1 = mysqli_num_rows($res);
                                $clase1 = ($cant1 == 0) ? 'class="text-success"' : 'class="text-danger"';
                                //echo $cant1;
                                ?>
                                <ul>
                                    <li <?= $clase1; ?>>No tener ventas abiertas.</li>
                                    <li <?= $clase; ?>>Haber registrado con cuanto inicio tu caja.</li>
                                </ul>
                                Te recordamos que si generas ventas despues de este corte se veran reflejadas en tu siguiente corte.

                                <div class="modal-footer">
                                    <form method="post" action="funciones/preparaCorte.php">
                                        <?php

                                        if ($cant >= 1 && $cant1 == 0) {
                                            echo '
                                        <input type="hidden" value="' . $caja['id'] . '" name="idCorte">
                                        <input type="submit" value="Si" class="btn btn-primary">';
                                        }
                                        ?>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <?php
            $sql = "SELECT
	    id,
	    fechaHora AS start,
	    nombre AS title,
        'bg-$pyme' AS className,
        descripcion,
        sucursalesPart
    FROM
        agenda WHERE $idSucursal IN (sucursalesPart ) OR sucursalesPart LIKE '%ALL%' ";
         ///  echo $sql;
            $resXAgenda = mysqli_query($link, $sql) or die("Problemas al enlistar Eventos.");


            ?>

         


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
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/app.init.mini-sidebar.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="assets/scripts/basicFuctions.js"></script>
    <script src="assets/scripts/notificacionesCaja.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/libs/toastr/build/toastr.min.js"></script>
    <!--This page JavaScript -->
    <script src="assets/libs/moment/min/moment.min.js"></script>
    <script src="assets/libs/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="assets/libs/fullcalendar/dist/locale/es.js"></script>
    <script src="dist/js/pages/calendar/cal-general.js"></script>
    <script src="assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>



    <script>
        <?php
        $arreglo['data'] = array();
        while ($datos = mysqli_fetch_array($resXAgenda)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($resXAgenda);
        echo 'var datsJson= ' . $var . ';';
        echo 'var pyme = "' . $pyme . '";';

        ?>
        $(document).ready(function() {
            <?php
            #  include('funciones/basicFuctions.php');
            #  alertMsj($nameLk);

            if (isset($_SESSION['LZFmsjInicioCaja'])) {
                echo "notificaBad('" . $_SESSION['LZFmsjInicioCaja'] . "');";
                unset($_SESSION['LZFmsjInicioCaja']);
            }
            if (isset($_SESSION['LZFmsjSuccessInicioCaja'])) {
                echo "notificaSuc('" . $_SESSION['LZFmsjSuccessInicioCaja'] . "');";
                unset($_SESSION['LZFmsjSuccessInicioCaja']);
            }
            ?>
        });
    </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>