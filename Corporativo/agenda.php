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
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

    <!--<link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
-->
    <link href="../assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">
    <link href="../assets/libs/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
    <link href="../assets/extra-libs/calendar/calendar.css" rel="stylesheet" />


    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <style>
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







                <div class="col-lg-12">
                    <div class="card border-<?= $pyme; ?>">
                        <div class="card-header bg-<?= $pyme; ?>">
                            <h4 class="m-b-0 text-white">Calendario de Actividades</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-right">
                                <button class="btn btn-outline-<?= $pyme; ?> btn-rounded" data-target="#modalEvento" data-toggle="modal"> <i class="fas fa-plus"></i> Nuevo Evento</button>
                            </div>
                            <div class="card-body b-l calender-sidebar">
                                <div id="calendar"></div>
                            </div>
                        </div>

                    </div>
                </div>
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
    <div id="modalEvento" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                    <h4 class="modal-title" id="lblEditMetodo">Agendar Evento</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>


                </div>
                <div class="modal-body">
                    <form role="form" method="post" id="formReunion">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nombre" class="control-label col-form-label">Nombre de la Evento</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="nombre"><i class="fas fa-thumbtack"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="nombre" aria-describedby="nombre" name="nombre" required>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="fecha" class="control-label col-form-label">Fecha</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    </div>
                                    <input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" class="form-control" id="fecha" aria-describedby="fecha" name="fecha" required>
                                </div>

                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <label for="descripcion" class="control-label col-form-label">Descripción de la Evento</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="descripcion"><i class="fas fa-pencil-alt"></i></span>
                                    </div>
                                    <textarea class="form-control" id="descripcion" aria-describedby="descripcion" rows="8" name="descripcion"></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="descripcion" class="control-label col-form-label">Sucursales Participantes</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    </div>
                                    <select class="form-control" multiple="" style="height: 50px;width: 98%;" name="sucursales[]" id="sucursales" aria-hidden="true">
                                        <?php
                                        $sql = "SELECT id, nombre FROM sucursales WHERE estatus='1' ORDER BY nombre";
                                        $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");
                                        echo "<option value='ALL'>Todas las Sucursales</option>";
                                        while ($dat = mysqli_fetch_array($resSuc)) {
                                            echo "<option value='" . $dat["id"] . "'>" . $dat["nombre"] . "</option>";
                                        }
                                        ?>


                                    </select>
                                </div>

                            </div>
                        </div>

                </div>
                <div class="modal-footer">
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
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="registrarGastobtn" class="btn btn-success waves-effect waves-light">Registrar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- BEGIN MODAL -->
    <div class="modal fade none-border" id="EditarEvento">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" method="POST" action='' id="formReunion_Editar">
                    <div class="modal-header bg-<?= $pyme ?>">
                        <h4 class="modal-title text-white">Modificar Evento</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <label for="nombre" class="control-label col-form-label">Nombre de la Evento</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="nombre"><i class="fas fa-thumbtack"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="nombre" aria-describedby="nombre" name="nombre" required>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="fecha" class="control-label col-form-label">Fecha</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    </div>
                                    <input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" class="form-control" id="fecha" aria-describedby="fecha" name="fecha" required>
                                </div>

                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <label for="descripcion" class="control-label col-form-label">Descripción de la Evento</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="descripcion"><i class="fas fa-pencil-alt"></i></span>
                                    </div>
                                    <textarea class="form-control" id="descripcion" aria-describedby="descripcion" name="descripcion"></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="descripcion" class="control-label col-form-label">Sucursales Participantes</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    </div>
                                    <select class="form-control select2-hidden-accessible" multiple="" style="height: 50px;width: 98%;" name="sucursales[]" id="sucursales_edicion" data-select2-id="13" aria-hidden="true">
                                        <?php
                                        $sql = "SELECT id, nombre FROM sucursales WHERE estatus='1' ORDER BY nombre";
                                        $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");
                                        echo "<option id='ALL' value='ALL'>Todas las Sucursales</option>";
                                        while ($dat = mysqli_fetch_array($resSuc)) {
                                            echo "<option id='option_suc_" . $dat["id"] . "' value='" . $dat["id"] . "'>" . $dat["nombre"] . "</option>";
                                        }
                                        ?>


                                    </select>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2 mx-2">

                        </div>
                    </div>

                    <input type="hidden" name="ident" class="form-control" id="id">


                    <div class="modal-footer ">
                        <div id="bloquear-btn2" style="display:none;">
                            <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                            </button>
                            <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                        <div id="desbloquear-btn2">
                            <button type="button" id="EliminarBtn" class="btn btn-outline-danger waves-effect" onclick="deleteEvento()" data-dismiss="modal">Eliminar</button>

                            <button type="button" id="CerrarEditar" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</button>

                            <button type="submit" id="EditarBtn" class="btn btn-success waves-effect waves-light">Editar</button>
                        </div>
                    </div>
                </form>

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
        agenda";
    $resXAgenda = mysqli_query($link, $sql) or die("Problemas al enlistar Eventos.");


    ?>

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
    <!--This page JavaScript -->
    <script src="../assets/libs/moment/min/moment.min.js"></script>
    <script src="../assets/libs/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="../assets/libs/fullcalendar/dist/locale/es.js"></script>
    <script src="../dist/js/pages/calendar/cal-agenda.js"></script>

    <script>
        $("#sucursales_edicion").select2();
        $("#sucursales").select2();
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
        $('.datepicker').datepicker({
            language: 'es',
            format: 'dd-mm-yyyy',
        });
        $('#demo-foo-row-toggler').footable({
            "toggleColumn": "last",

        });

        // console.log(datsJson.data);
        $(document).ready(function() {
            <?php
            #include('../funciones/basicFuctions.php');
            #alertMsj($nameLk);
            if (isset($_SESSION['LZmsjInfoAgenda'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoAgenda'] . "');";
                unset($_SESSION['LZmsjInfoAgenda']);
            }
            if (isset($_SESSION['LZmsjSuccessAgenda'])) {
                echo "notificaSuc('" . $_SESSION['LZmsjSuccessAgenda'] . "');";
                unset($_SESSION['LZmsjSuccessAgenda']);
            }
            ?>
        }); // Cierre de document ready
        function deleteEvento() {
            var ident = $('#id').val();
            $.post("../funciones/eliminarReunion.php",
                {ident:ident},
                function(respuesta) {
                    var res = respuesta.split('|');
                    if (res[0] == 1) {
                        location.reload();


                    } else {

                        notificaBad(res[1]);
                    }
                });
        }
        $("#formReunion").submit(function(e) {
            e.preventDefault();
            datos = $("#formReunion").serializeArray();
            // console.log(datos);
            bloqueoBtn("bloquear-btn1", 1);

            $.post("../funciones/guardaReunion.php",
                datos,
                function(respuesta) {
                    var res = respuesta.split('|');
                    if (res[0] == 1) {
                        location.reload();


                    } else {

                        bloqueoBtn("bloquear-btn1", 2);
                        notificaBad(res[1]);
                    }
                });


        });
        $("#formReunion_Editar").submit(function(e) {
            e.preventDefault();
            datos = $("#formReunion_Editar").serializeArray();
            // console.log(datos);
            bloqueoBtn("bloquear-btn2", 1);

            $.post("../funciones/editarReunion.php",
                datos,
                function(respuesta) {
                    var res = respuesta.split('|');
                    if (res[0] == 1) {
                        location.reload();


                    } else {

                        bloqueoBtn("bloquear-btn2", 2);
                        notificaBad(res[1]);
                    }
                });


        });
    </script>

</body>

</html>
