<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
$idSucursal = $_SESSION['LZFidSuc'];
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
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <link href="../assets/libs/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
    <link href="../assets/extra-libs/calendar/calendar.css" rel="stylesheet" />
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
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-left border-bottom border-info">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2>&nbsp;</h2>
                                        <h6 class="text-info">Inicio de Caja</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-info display-6"><i class="fas fa-laptop"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-left border-bottom border-cyan">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2>2</h2>
                                        <h6 class="text-cyan">Ventas Abiertas</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-cyan display-6"><i class="ti-clipboard"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-left border-bottom border-success">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2>1</h2>
                                        <h6 class="text-success">Cortes Abiertos</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-success display-6"><i class="ti-wallet"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-left border-bottom border-orange">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2>2</h2>
                                        <h6 class="text-orange">Devoluciones del día</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-orange display-6"><i class="ti-stats-down"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body b-l calender-sidebar">
                                <div id="calendar"></div>
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
        agenda WHERE $idSucursal IN (sucursalesPart) OR sucursalesPart LIKE '%ALL%' ";
                        //echo $sql;
                        $resXAgenda = mysqli_query($link, $sql) or die("Problemas al enlistar Eventos.".$sql);


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
            <!--Menu sidebar -->
            <script src="../dist/js/sidebarmenu.js"></script>
            <!--Custom JavaScript -->
            <script src="../assets/scripts/basicFuctions.js"></script>
            <script src="../assets/scripts/notificaciones.js"></script>
            <script src="../dist/js/custom.min.js"></script>
            <script src="../assets/libs/toastr/build/toastr.min.js"></script>
            <script src="../dist/js/pages/calendar/cal-general.js"></script>
            <script src="../assets/libs/moment/min/moment.min.js"></script>
            <script src="../assets/libs/fullcalendar/dist/fullcalendar.min.js"></script>
            <script src="../assets/libs/fullcalendar/dist/locale/es.js"></script>
            <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>

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
                    include('../funciones/basicFuctions.php');
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