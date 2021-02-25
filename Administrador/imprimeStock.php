<?php
require_once 'seg.php';
session_start();

$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');
$fecha = date('d-m-Y H:i:s');
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
    <link rel="icon" type="../image/png" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
    <title><?= $info->nombrePag; ?></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="font-22 mdi mdi-email-outline"></i>

                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                                <span class="with-arrow">
                                    <span class="bg-danger"></span>
                                </span>
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title text-white bg-danger">
                                            <h4 class="m-b-0 m-t-5">5 New</h4>
                                            <span class="font-light">Messages</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center message-body">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img">
                                                    <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle">
                                                    <span class="profile-status online pull-right"></span>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Pavan kumar</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link text-dark" href="javascript:void(0);">
                                            <b>See all e-Mails</b>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->


                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown border-right">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell-outline font-22"></i>
                                <span class="badge badge-pill badge-info noti">3</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                <span class="with-arrow">
                                    <span class="bg-primary"></span>
                                </span>
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title bg-primary text-white">
                                            <h4 class="m-b-0 m-t-5">4 New</h4>
                                            <span class="font-light">Notifications</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center notifications">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-danger btn-circle">
                                                    <i class="fa fa-link"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Luanch Admin</h5>
                                                    <span class="mail-desc">Just see the my new admin!</span>
                                                    <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-success btn-circle">
                                                    <i class="ti-calendar"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Event today</h5>
                                                    <span class="mail-desc">Just a reminder that you have event</span>
                                                    <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-info btn-circle">
                                                    <i class="ti-settings"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Settings</h5>
                                                    <span class="mail-desc">You can customize this template as you want</span>
                                                    <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-primary btn-circle">
                                                    <i class="ti-user"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Pavan kumar</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center m-b-5 text-dark" href="javascript:void(0);">
                                            <strong>Check all notifications</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
                                <h4 class="m-b-0 text-white">Stock de productos por Sucursal</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">

                                    <a class="btn btn-circle bg-<?= $pyme; ?>" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" data-toggle="tooltip" data-placement="top" title="" data-original-title="Stock" href="stock.php"> <i class="fas fa-reply"></i></a>

                                </div>
                                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                                <div class="row ml-5 pl-4">
                                    <!--<div class="card-deck mt-3">-->
                                        <?php
                                        $idSucursal = $_SESSION['LZFidSuc'];
                                        $sql = "SELECT DISTINCT(anaquel) FROM stocks WHERE idSucursal='$idSucursal' ORDER BY anaquel ASC";
                                        $res = mysqli_query($link, $sql) or die('Problemas al listar anaqueles');
                                        $anaq = 0;
                                        if (mysqli_num_rows($res) == 0) {
                                            echo '
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Alerta</strong> No hay stock en la Sucursal.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        ';
                                        }
                                        while ($dat = mysqli_fetch_array($res)) {
                                            $anaq++;
                                            $noAnaquel = $dat['anaquel'];

                                            echo '
             <div class="card border-' . $pyme . '"> <!-- INICIO DE CARD -->
                <div class="card-header" ><!-- INICIO DE HEADER CARD -->
                    <div class="row">
                         <div class="">
                            <h3>Anaquel No. ' . $dat['anaquel'] . '</h3>
                        </div>
                        <div class="col-md-2">
                            <div class="btn-group mx-5">
                                <button type="button" onclick="imprimeAnaquel(' . $dat['anaquel'] . ')" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" class="btn btn-circle btn-' . $pyme . ' ">
                                <i class="fas fas fa-print"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div><!-- FIN DE HEADER CARD -->
                <div class="todo-widget scrollable" style="height:422px;">

                <div class="card-body " id="cont' . $dat['anaquel'] . '" ><!-- INICIO DE BODY CARD -->

                    <table border="0"  style="font-size:13px" width="260px"  >
                  		<tr>
                        	<th colspan="3" align="center" style="font-size:18px">' . $_SESSION['sucursalName'] . '</th>
                  	    </tr>
                  		<tr>
                      	    <th colspan="3" align="center" style="font-size:12px">' . $fecha . '<br> ANAQUEL: ' . $dat['anaquel'] . '</th>
                  	     </tr>
                  		<tr>
                  	        <td style="border-top:1px dotted #999; border-left:1px dotted #999; border-bottom:1px dotted #999;">DESCRIPCION</td>
                            <td style="border-top:1px dotted #999; border-left:1px dotted #999; border-bottom:1px dotted #999;">CANT ACTUAL</td>
                            <td style="border-top:1px dotted #999; border-left:1px dotted #999; border-bottom:1px dotted #999; border-right:1px dotted #999;">CANT REAL</td>
                        </tr>
                        ';

                                            $sql = "SELECT sto.*, pro.descripcion as producto
                    FROM stocks sto
                    INNER JOIN productos pro ON sto.idProducto=pro.id
                    WHERE sto.idSucursal='$idSucursal' AND sto.anaquel='$noAnaquel'
                    ORDER BY sto.anaquel, producto ASC";
                                            $result = mysqli_query($link, $sql) or die('Problemas al listar productos');

                                            while ($row = mysqli_fetch_array($result)) {
                                                echo '
                   <tr>
                     <td style="font-size:11px">' . $row['producto'] . '</td>
                     <td align="center">' . number_format($row['cantActual'], 2, ".", "'") . '</td>
                     <td align="center" style="border-bottom:1px dotted #999;"> </td>
                   </tr>';
                                            }

                                            echo '
                    <tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:50px; "><hr aling="center" size="2"></td></tr>
              	    <tr><th colspan="3" align="center">REVISO ANAQUEL</th></tr>
                    </table>
                    </div><!-- FIN DE BODY CARD -->
                    </div>
                </div><!-- fIN DE CARD -->';

                                            if ($anaq = 3) {
                                                echo '';
                                                $anaq = 0;
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    </div>
                                    <!--end .table-responsive -->
                                <!--</div>-->
                                <!--end .col -->
                            </div>
                            <!--end .row -->
                            <!-- END DATATABLE 1 -->
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                    <!-- END ACTION -->




                </div>









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
    <!--Custom JavaScript -->
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>
    <script src="../assets/jquery.PrintArea.js"></script>

    <script>
        $(document).ready(function() {
            <?php
            #include('../funciones/basicFuctions.php');
            #alertMsj($nameLk);

            if (isset($_SESSION['LZmsjInfoAltaStock'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoAltaStock'] . "');";
                unset($_SESSION['LZmsjInfoAltaProducto']);
            }
            if (isset($_SESSION['LZmsjSuccessProducto'])) {
                echo "notificaSuc('" . $_SESSION['LZmsjSuccessStock'] . "');";
                unset($_SESSION['LZmsjSuccessStock']);
            }
            ?>
        }); // Cierre de document ready



        function limpiaCadena(dat, id) {
            //alert(id);
            dat = getCadenaLimpia(dat);
            $("#" + id).val(dat);
        }

        function imprimeAnaquel(idAnaquel) {
            $('<form action="../funciones/ticketLanzaAnaqueles.php" target="_blank" method="POST"><input type="hidden" name="idAnaquel" value="'+idAnaquel+'"></form>').appendTo('body').submit();
        }
    </script>

</body>

</html>
