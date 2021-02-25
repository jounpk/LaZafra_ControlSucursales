<!--<button type="button" class="close text-white" onclick="cancelar()">Cancelar</button>-->
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

#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
$debug=0;
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
        .btn-circle-sm {
            width: 35px;
            height: 35px;
            line-height: 30px;
            font-size: 0.9rem;
            background: #fff;
            box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
        }

        .btn-circle-sm2 {
            width: 35px;
            height: 35px;
            line-height: 35px;
            font-size: 0.9rem;

        }

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
                                <h4 class="m-b-0 text-white">Total de Créditos por Pagar</h4>
                            </div>
                            <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            <div class="table-responsive">
                                                <table id="tabla_creditos" class="table display  no-wrap">
                                                    <thead>
                                                        <th class="text-center">#</th>
                                                        <th>Cliente</th>
                                                        <th>Deuda</th>
                                                        <th>Resta</th>
                                                        <th>Créditos Brind. </th>
                                                        <th>Limite Credito. </th>
                                                       
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
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

    </div>

    <footer class="footer text-center">
        Powered by
        <b class="text-info">RVSETyS</b>.
    </footer>

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
    <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script src="../assets/tablasZafra/datatable_Credito.js"></script>

    <script>
        var data_tablas_internas;
        $(document).ready(function() {
            <?php
            #  include('../funciones/basicFuctions.php');
            #  alertMsj($nameLk);

            if (isset($_SESSION['LZFmsjFactSuc'])) {
                echo "notificaBad('" . $_SESSION['LZFmsjFactSuc'] . "');";
                unset($_SESSION['LZFmsjFactSuc']);
            }
            if (isset($_SESSION['LZFmsjSuccessFactSuc'])) {
                echo "notificaSuc('" . $_SESSION['LZFmsjSuccessFactSuc'] . "');";
                unset($_SESSION['LZFmsjSuccessFactSuc']);
            }
            ?>

        });


        function seleccionarTodas() {
            if ($("#seleccionVentas").prop('checked')) {
                $('.ParaFacturar').prop('checked', true);
            } else {
                $('.ParaFacturar').prop('checked', false);
            }

        }


        function ejecutandoCarga(ident) {
            var selector = 'DIV' + ident;
            var finicio = $('#fStart').val();
            var ffin = $('#fEnd').val();

            $.post("../funciones/cargaDetalleCred.php", {
                    ident: ident
                },
                function(respuesta) {
                    $("#" + selector).html(respuesta);
                });

        }





        <?php

        $sql =
            "SELECT
            cl.id,
            cl.nombre,
            SUM( cr.totalDeuda ) AS totalDeuda,
            CONCAT ('$', FORMAT(SUM( cr.totalDeuda ),2)) AS format_totalDeuda, 
            SUM( cr.montoDeudor ) AS totalResta,
            CONCAT ('$', FORMAT(SUM( cr.montoDeudor ),2)) AS format_totalResta, 

            COUNT(cr.id) AS credBrindados,

            cl.limiteCredito,
            CONCAT ('$', FORMAT(cl.limiteCredito,2)) AS format_limCredito,
            SUM(  cr.montoDeudor  )/(cl.limiteCredito/100) AS porcentaje


            
        FROM
            creditos cr
            LEFT JOIN clientes cl ON cr.idCliente = cl.id
            INNER JOIN sucursales suc ON cl.idSucursal = suc.id
        WHERE
            cr.estatus = '1' 
        
        GROUP BY
            cr.idCliente";
        $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta de Créditos </option>');
        $arreglo['data'] = array();
        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($res);
        echo 'var jsonData= ' . $var . ';';
        echo 'var pyme = "' . $pyme . '";';

        ?>
    </script>