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
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <!--<link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
-->
    <link href="../assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">


    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <style>
        .index {
            background: none repeat scroll 0 0;
            border: 2px solid #ffffff;
            border-radius: 25px;
            color: #fff;
            font-size: 28px;
            font-weight: bold;
            padding: 5px 15px;
            position: absolute;
            right: -10px;
            top: -10px;
        }

        .btn-circle-sm {
            width: 35px;
            height: 35px;
            line-height: 35px;
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

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?= $info->generaMenuLateral(); ?>
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <?php
            $sucursal = $_SESSION['LZFidSuc'];

            $userReg = $_SESSION['LZFident'];
            $sql = "SELECT * FROM traspasos WHERE idUserEnvio='$userReg' AND idSucSalida='$sucursal' AND estatus='1'";
            // echo $sql;
            $res = mysqli_query($link, $sql) or die("Error de Consulta:" . $sql);
            $var = mysqli_fetch_array($res, MYSQLI_ASSOC);
            $act = mysqli_num_rows($res);
            $idTraspasos = $var['id'];
            $idSucSalida = $var['idSucSalida'];
            $idSucEntrada = $var['idSucEntrada'];
            if ($idSucEntrada != 0) {

                $disabledBtn = "disabled";
            } else {
                $disabledBtn = "";
            }
            echo '<input type="hidden" value="' . $idTraspasos . '" id="identTras">';
            ?>
            <!--SELECT DE SUCURSAL ENVIA-->
            <?php
            $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
            $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

            $listaSucEnvia = '';
            while ($datos = mysqli_fetch_array($resSuc)) {
                if ($datos['id'] == $sucursal) {
                    $listaSucEnvia .= '<option value="' . $datos['id'] . '" selected>' . $datos['nombre'] . '</option>';
                }
            }
            ?>
            <!--SELECT DE SUCURSAL RECIBE-->

            <?php
            $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
            $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

            $listaSucDes = '';
            while ($datos = mysqli_fetch_array($resSuc)) {
                $activeSuc = ($datos['id'] == $idSucEntrada) ? 'selected' : '';
                if ($datos['id'] != $sucursal) {
                    $listaSucDes .= '<option value="' . $datos['id'] . '" ' . $activeSuc . ' >' . $datos['nombre'] . '</option>';
                }
            }
            //echo $listaSucDes;
            ?>
            <?php
            $sql = "SELECT * FROM productos WHERE estatus=1 ORDER BY descripcion";
            $resProd = mysqli_query($link, $sql) or die("Problemas al enlistar Productos.");

            $listaProd = '';
            while ($datos = mysqli_fetch_array($resProd)) {
                $listaProd .= '<option value="' . $datos['id'] . '">' . $datos['descripcion'] . '</option>';
            }
            ?>

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
                    <div class="col-md-5">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Datos Generales de Traspasos</h4>
                            </div>

                            <div class="card-body">
                                <?php
                                $sql = "SELECT
                                COUNT( tras.id ) AS pendientes
                            FROM
                                traspasos tras
                            WHERE
                                tras.idUserEnvio = '$userReg'
                                AND tras.idSucSalida = '$sucursal'
                                AND tras.estatus =1";
                                $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>' . mysqli_error($link));
                                $dato = mysqli_fetch_array($res);
                                $pendiente = $dato['pendientes'];

                                ?>
                                <span class="index bg-<?= $pyme ?>"><?= $pendiente ?></span>

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="inputEmail3" class="control-label col-form-label">Sucursal que Envía:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <select class="select2 form-control custom-select" name="sucRem" id="sucRem" disabled onchange="" style="width: 75%;">

                                                    <option value=""></option>
                                                    <?= $listaSucEnvia ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="inputEmail3" class="control-label col-form-label">Sucursal que Recibe:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <select class="select2 form-control custom-select" name="sucDes" id="sucDes" onchange="" style="width: 75%;" <?= $disabledBtn ?>>
                                                    <option value=""> </option>
                                                    <?= $listaSucDes ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3">
                                        <button title="Aceptar Traspaso" id="agregarProv" class="btn btn-circle bg-<?= $pyme; ?>" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" onclick="iniciaTraspaso();" type="button"><i class="fas  fas fa-check"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label for="inputEmail3" class="control-label col-form-label">Producto</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <select class="select2 form-control custom-select" name="prod" id="producto" onchange="regProducto(<?= $idTraspasos ?>, this.value);" style="width: 87%;">

                                                    <option value=""> Agrega más productos</option>
                                                    <?= $listaProd ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>
                        <!--end .table-responsive -->
                    </div>


                    <div class="col-md-7">
                        <div class="card border-<?= $pyme; ?>" <?php if ($idTraspasos == '') {
                                                                    echo 'style="height: 18.5rem;"';
                                                                } ?>>
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Detallado de Producto</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                // echo 'IDTraspasos-->'.$idTraspasos.'<br>';
                                if ($idTraspasos != '') {
                                ?>
                                    <div class="table-responsive">
                                        <form id="formTraspasos" method="post">
                                            <table class="table product-overview ">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Descripción</th>
                                                        <th class="text-center">Cantidad Actual</th>
                                                        <th class="text-center">Cantidad Enviar</th>
                                                        <th class="text-center">Eliminar</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT det.*,prod.descripcion as producto, prod.medios, sto.cantActual, prod.id AS idprod
                                            FROM traspasos tras

                                            LEFT JOIN dettraspasos det ON det.idTraspaso=tras.id
                                            INNER JOIN productos prod ON det.idProducto=prod.id
                                            INNER JOIN stocks sto ON det.idProducto = sto.idProducto AND sto.idSucursal = $sucursal
                                            WHERE det.idTraspaso='$idTraspasos' AND tras.estatus=1 AND tras.idUserEnvio='$userReg'  ORDER BY id ASC";
                                                    $it = 1;
                                                    $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>' . mysqli_error($link));
                                                    while ($datos = mysqli_fetch_array($res)) {
                                                        $descripcion = $datos['medios'] == '1' ? 'step="0.1"' : 'step="1"';

                                                        echo '<tr id="tr' . $datos['idprod'] . '">
                                                <td><input type="checkbox" class="loteoCheck" id="loteo_manual-' . $datos['idprod'] . '" name="chck_auto_' . $datos['id'] . '"  onclick="cargalotes(' . $datos['idprod'] . ',' . $datos['cantEnvio'] . ')" value="1"> </td>
                                              <td>' . $datos['producto'] . '</td>
                                              <td>' . $datos['cantActual'] . '</td>
                                              <td> <div class="input-group px-3 mb-3">

                                              <input value="' . $datos['cantEnvio'] . '" type="number" ' . $descripcion . ' id="cantEnvio' . $datos['id'] . '" class="form-control" min="1" onchange="cambiaEnvio(' . $datos['id'] . ',this.value)";></div></td>
                                              <td><center  data-toggle="tooltip" data-placement="top"
                                              title="" data-original-title="Eliminar" >
                                              <button type="button"  class="btn-circle btn-danger" style="color:#fff, box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);"
                                              onClick="eliminaProducto(' . $datos['id'] . ');"><i class="fas  fas fa-trash"></i></button></center></td>
                                          </tr>';
                                                        $it++;
                                                    }
                                                    ?>
                                                    <tr>

                                                </tbody>
                                            </table>

                                    </div>
                                    <hr>
                                    <!-- <form action="../funciones/generaTraspasos.php" method="post">-->

                                    <input type="hidden" name="ident" id="identTraspasoFinal" value="<?= $idTraspasos; ?>">

                                    <div id="bloquear-btn1" style="display:none;">
                                        <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                        </button>
                                        <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                        </button>
                                        <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                    <div id="desbloquear-btn1">
                                        <div class="text-right">
                                            <button type="button" onclick="cancelarEdicion();" class="btn btn-danger ">Cancelar</button>

                                            <button type="button" onclick="mandaFormulario()" class="btn btn-success ">Finalizar Traspaso</button>

                                        </div>
                                        </form>
                                    </div>
                                <?php
                                }
                                ?>




                                </form>

                            </div>


                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Recepción de Traspasos</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">
                                    <a class="btn btn-circle bg-<?= $pyme ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Historial" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="historialtraspasos.php">
                                        <i class="fas fa-calendar-alt"></i></a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table product-overview " id="tablatrasp">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Ticket</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sucursal Envía</th>
                                                <th class="text-center">Usuario</th>
                                                <th class="text-center">Sucursal Recepción</th>
                                                <th class="text-center">Recibir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT tras.id, suc1.nombre as sucSalida, suc2.nombre as sucEntrada, DATE_FORMAT(tras.fechaEnvio,'%d/%m/%Y %H:%i:%s') AS fechaEnvio, CONCAT(user.nombre,' ',user.appat,' ',user.apmat) AS usuarioEnvio
                                            FROM traspasos tras
                                            INNER JOIN sucursales suc1 ON tras.idSucSalida=suc1.id
                                            INNER JOIN sucursales suc2 ON tras.idSucEntrada=suc2.id
                                            INNER JOIN segusuarios user ON tras.idUserEnvio=user.id
                                            WHERE tras.idSucEntrada='$sucursal' AND tras.estatus='2'
                                            ORDER BY fechaEnvio ASC";
                                            //echo $sql;
                                            $total = 0;
                                            $it = 1;
                                            $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>' . $sql);
                                            while ($datos = mysqli_fetch_array($res)) {

                                                echo '<tr id="tr' . $datos['id'] . '"> <td>' . $it . '</td> <td>' . $datos['id'] . '</td> <td>' . $datos['fechaEnvio'] . '</td>
                                              <td>' . $datos['sucSalida'] . '</td>
                                              <td>' . $datos['usuarioEnvio'] . '</td>
                                              <td>' . $datos['sucEntrada'] . '</td>
                                              <td><center>
                                              <button type="button" class="btn-circle btn-circle-sm2 p-0 btn-success"
                                              onClick="recibirProducto(' . $datos['id'] . ',\'Desde la Sucursal: ' . $datos['sucSalida'] . '<br> Fecha de Envío: ' . $datos['fechaEnvio'] . '\');"><i class="fab fa-dropbox"></i></button></center></td>
                                          </tr>';
                                                $it++;
                                            }
                                            ?>

                                        </tbody>
                                    </table>

                                </div>


                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Solicitudes de Traspasos</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="table-traspaso" class="table display table-bordered table-striped no-wrap">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Ticket</th>
                                                        <th>Fecha</th>
                                                        <th>Sucursal Envía</th>
                                                        <th>Usuario</th>
                                                        <th class="text-center">Acciones</th>

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
            <!-- Modal -->
            <div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                            <h4 class="modal-title" id="myModalLabel">
                                <div id="tituloModal"></div>
                            </h4>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body" id="contenidoModal">
                            <div id="preloadProducto">
                                <!-- <center><img src="images/preloader.GIF"></center>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
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
    <script src="../assets/tablasZafra/datatable_configTras.js"></script>

    <script>
        <?php
        $sucursal = $_SESSION['LZFidSuc'];
        $button = '<div class="text-center"><button data-toggle="tooltip" data-placement="top" title="" data-original-title="Aceptar" class=" btn-circle btn-circle-sm2 p-0 btn-success"  onClick="" type="submit"><i class="fas fa-check"></i></button></form></div>';
        $inputhidden = '<form action="../funciones/cambiaUsuarioTraspaso.php" method="post"><input type="hidden" name="ident" id="ident" value="\',tras.id,\'">';
        $sql = "SELECT tras.id, CONCAT(tras.id,'$inputhidden') AS idinput, suc1.nombre AS sucSalida, suc2.nombre AS sucEntrada, DATE_FORMAT(sol.fechaReg, '%d/%m/%Y %H:%i:%s') AS fechaSolicitud, CONCAT(USER .nombre,' ', USER .appat, ' ', USER .apmat) AS usuarioSolicita,
  CONCAT('$inputhidden','$button') AS button
  FROM traspasos tras
  LEFT JOIN sucursales suc1 ON tras.idSucSalida = suc1.id
  LEFT JOIN sucursales suc2 ON tras.idSucEntrada = suc2.id
  LEFT JOIN solicitudestrasp sol ON tras.idSolicitud = sol.id
  LEFT JOIN segusuarios USER ON sol.idUsuario = USER .id
  WHERE tras.idSucSalida ='$sucursal' AND tras.estatus = '1' AND sol.estatus='2'
   AND tras.idUserEnvio='0' ORDER BY fechaEnvio ASC";
        $total = 0;
        $it = 1;

        $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>');
        $arreglo['data'] = array();

        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
            //$arreglo['data'][] = array_map("utf8_encode", $datos);
            //  echo array_map("utf8_encode", $datos);
        }
        $var = json_encode($arreglo);
        mysqli_free_result($resPro);
        echo 'var datsJson = ' . $var . ';';
        echo 'var pyme = "' . $pyme . '";';

        ?>
        //console.log(datsJson.data);
        $(document).ready(function() {

            $('#tablatrasp').DataTable({});
            //QUITAR ENVIO DE FORMULARIOS
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }


            <?php


            if (isset($_SESSION['LZmsjInfoTraspasos'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoTraspasos'] . "');";
                unset($_SESSION['LZmsjInfoTraspasos']);
            }
            if (isset($_SESSION['LZmsjSuccessTraspasos'])) {

                echo "
               notificaSuc('" . $_SESSION['LZmsjSuccessTraspasos'] . "');";
                unset($_SESSION['LZmsjSuccessTraspasos']);
            }
            ?>
        }); // Cierre de document ready
        function mandaFormulario(){
          
            $("#formTraspasos").submit();

        }
        $("#formTraspasos").submit(function(event) {
            event.preventDefault();
            var formData = $("#formTraspasos").serializeArray();
            //console.log(formData);
            id = $("#identTraspasoFinal").val();
            //  $.post("../funciones/guardaTraspasoFinal.php",
            bloqueoBtn("bloquear-btn1", 1);









            $.post("../funciones/loteoTrasp.php",
                formData,
                function(respuesta) {
                    //  alert(respuesta);
                    var id = $("#identTraspasoFinal").val();
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        //  bloqueoBtn("bloquear-btn1", 1);
                        notificaSuc(resp[1]);
                        //location.href = "reimprimeTickets.php?idVenta=" + id + "&tipoTicket=lanzaTraspaso";
                        $('<form action="../funciones/ticketLanzaTraspaso.php" method="POST"><input type="hidden" name="idTraspaso" value="' + id + '"></form>').appendTo('body').submit();
                    } else {
                        //  alert(respuesta);
                        bloqueoBtn("bloquear-btn1", 2);
                        notificaBad(resp[1]);
                    }
                });


        });

        function iniciaTraspaso() {
            idSuc = $('#sucDes option:selected').val();
            location.href = "../funciones/altaTraspaso.php?ident=" + idSuc;
        }

        function regProducto(idTraspasos, prod) {
            cant = $('#rcant').val();
            if ($("#producto").val() != "") {
                location.href = "../funciones/detalleTraspasos.php?idTraspasos=" + idTraspasos + "&producto=" + prod + "&cant=" + cant;
            }
        };

        function cargalotes(ident, cantidad) {

            if ($('#loteo_manual-' + ident).is(':checked')) {

                $.post("../funciones/cargaLoteoManualTrasp.php", {
                        ident: ident,
                        cantidad: cantidad
                    },
                    function(respuesta) {
                        $(".loteo-" + ident).remove();
                        $("#tr" + ident).after(respuesta);
                    });
            } else {
                $(".loteo-" + ident).remove();
            }
        }

        function cancelarEdicion() {
            ident = $('#identTraspasoFinal').val();

            if (ident != "") {
                $.post("../funciones/cancelaEdicionTraspasos.php", {
                        ident: ident,
                    },
                    function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {
                            notificaSuc(resp[1]);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else if (resp[0] == 0) {
                            notificaBad(resp[1]);
                        }
                    });
                // location.href = "../funciones/cancelaEdicionTraspasos.php?ident=" + ident;
            }
        };

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

        function selectTodo(clase) {
            selectClase(clase);
            obtenCreditos();
        }

        function selectClase(clase) {
            var varBase = $('[name="cred"]:checked').map(function() {
                return this.value;
            }).get();

            var varArreglo = varBase.join(',');
            console.log('arreglo: '+varArreglo);

          
            $("#idents").val(varArreglo);
            //  alert('Entra a función con valor: '+clase+' clase: '+clase);
            var isChecked = $("#" + clase).prop("checked");
            if (isChecked) {
                //alert('Si');
                $("." + clase).each(function() {
                    this.checked = true;
                });
                return;
            } else {
                //alert('No');
                $("." + clase).each(function() {
                    this.checked = false;
                });
                return;
            }
        }

        




        function limpiaCadena(dat, id) {
            //alert(id);
            dat = getCadenaLimpia(dat);
            $("#" + id).val(dat);
        }

        function eliminaProducto(id) {
            location.href = "../funciones/borraDetTraspaso.php?id=" + id;
        };

        function cambiaCant(id, cant) {
            location.href = "../funciones/editaCompraCant.php?id=" + id + "&cant=" + cant;
        };

        function cambiaPrecio(id, precio) {
            location.href = "../funciones/editaCompraPrecio.php?id=" + id + "&costo=" + precio;
        };
        
        function rechazarSolicitud(id) {

            $.post("../funciones/desactivaSolicitudTras.php", {
                    ident: id,
                },
                function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        notificaSuc(resp[1]);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        notificaBad(resp[1]);
                    }
                });

        }

        function revisarcantidad(value, cantidad, id_input, lote, clase, cantidadTotal, tipo=2) {
            //tipo1: Entrada tipo:2 Salida
            if (tipo == '2') {
                var suma = 0;
                $("." + clase).each(function() {
                    suma += parseFloat($(this).val());
                });
                //console.log("La cantidad total es: "+cantidadTotal);
                //console.log("La cantidad : "+cantidadTotal);

                if (value > cantidad || suma > cantidadTotal) {
                    notificaBad("Revisa la cantidad capturada en el lote " + lote);
                    $('#' + id_input).val(0);
                    $('#' + id_input).focus();
                }
            }
        }

        function recibirProducto(ident, name) {
            //alert(ident);
            $('#tituloModal').html(name);

            $.post("../funciones/formModalRecepcion.php", {
                    ident: ident
                },
                function(respuesta) {
                    // $("#preloadProducto").css("display", "none");
                    $("#contenidoModal").html(respuesta);
                    $('#modalProductos').modal('show');
                });

        }

        function hacerRecepcion(ident) {
            bloqueoBtn("bloquear-btn1", 1);
            $.post("../funciones/guardaRecepcion.php", {
                    ident: ident,
                },
                function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        //
                        notificaSuc(resp[1]);
                        $('<form action="../funciones/ticketLanzaTraspaso.php" method="POST"><input type="hidden" name="idTraspaso" value="' + ident + '"></form>').appendTo('body').submit();
                    } else {
                        bloqueoBtn("bloquear-btn1", 2);
                        notificaBad(resp[1]);
                    }
                });
        }
        /*    $.post("../funciones/guardaRecepcion.php", {
                    ident: ident,
                },
                function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        bloqueoBtn("bloquear-btn1", 1);
                        notificaSuc(resp[1]);
                        location.href="reimprimeTickets.php?idVenta="+ident+"&tipoTicket=lanzaTraspaso";
                                        } else {
                        bloqueoBtn("bloquear-btn1", 2);
                        notificaBad(resp[1]);
                    }
                });*/


        function cambiaEnvio(id, cant) {
            location.href = "../funciones/editaTrasCant.php?id=" + id + "&cant=" + cant;
        };

        function listaDeptos() {
            //  var mensaje = 'Mensaje';
            $.post("../funciones/listarDeptos.php", {},
                function(respuesta) {
                    $("#validation").html(respuesta);
                });
        }
    </script>

</body>

</html>