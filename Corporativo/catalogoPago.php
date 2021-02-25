<?php
require_once 'seg.php';
$info = new Seguridad();
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

                        <!-- ============================================================== -->
                        <!-- Comment -->
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
                <br>
                <div class="row">
                    <div class="col-md-8 col-lg-12">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Listado de Métodos de Pagos</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">
                              <!---      <button data-target="#modalregPago" data-toggle="modal" class="btn btn-outline-<?= $pyme; ?> btn-rounded"> <i class="fas fa-plus"></i> Registrar Nuevo Método de Pago</a>-->
                                </div>
                                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                                    <div class="table-responsive">
                                        <table class="table product-overview table-striped" id="zero_config">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Descripción</th>
                                                    <th class="text-center">Clave</th>
                                                    <th class="text-center">Banco Aceptado</th>

                                                    <th class="text-center">Estatus</th>
                                                 <!--   <th class="text-center">Acciones</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpoTabla">
                                                <?php

                                                $sqlPago = "SELECT cp.*,IF(cb.nombreCorto!='',cb.nombreCorto, 'No Aplica') AS banco FROM sat_formapago cp LEFT JOIN catbancos cb ON cp.idBanco=cb.id ORDER BY cp.estatus DESC, cp.nombre ASC";
                                                $resPago = mysqli_query($link, $sqlPago) or die('Problemas al consultar las Marcas, notifica a tu Administrador');
                                                $iteracion = 1;
                                                while ($lista = mysqli_fetch_array($resPago)) {
                                                    $id = $lista['id'];
                                                    // $estatus = ($lista['estatus'] == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;
                                                    $estatus = ($lista['estatus'] == 1) ? '<center class="text-success"  data-toggle="tooltip" data-placement="top"
														title="" data-original-title="Activo"><button class="btn btn-circle" type="button" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="estatusPago(' . $lista['id'] . ', 0);"
														class="btn-circle"><i class="fas fa-check text-success"></i></button></center>' :
                                                        '<center class="text-danger"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Desactivado">
														 <button type="button" class="btn btn-circle"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="estatusPago(' . $lista['id'] . ', 1);"
														class="btn ink-reaction btn-icon-toggle"><i class="fas fa-times text-danger"></i></button></center>';
                                                    $cierra = ($lista['cierraPago'] == 1) ? '<i class="fas fa-check text-center text-success"></i>' : '<i class="fas fa-times text-center text-danger"></i>';

                                                    //$cambiaEstatus = ($lista['estatus'] == 2) ? '<a id="btnCambiaEstatus-'.$lista['id'].'" title="Activar"><button class="btn btn-outline-warning" onclick="cambiaEstatus('.$id.','.$lista['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' : '<a id="btnCambiaEstatus-'.$lista['id'].'" title="Desactivar"><button class="btn btn-outline-danger" onclick="cambiaEstatus('.$id.','.$lista['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' ;
                                                    $estatusText = ($lista['estatus'] != 0) ? '' : 'class="text-danger"';

                                                    echo '<tr ' . $estatusText . '>
                                            <td class="text-center">' . $iteracion . '</td>
                                            <td id="descPago-' . $iteracion . '" class="text-center">' . $lista['nombre'] . '</td>
                                            <td id="clavePago-' . $iteracion . '" class="text-center">' . $lista['clave'] . '</td>
                                            <td id="bancoPago-' . $iteracion . '" class="text-center">' . $lista['banco'] . '</td>
                                            <td class="text-center" id="estatusPago-' . $iteracion . '">' . $estatus . '</td>
                                           <!-- <td class="text-center" id="botonesMarca-' . $iteracion . '"><center class="text-success"  data-toggle="tooltip" data-placement="top"
                                            title="" data-original-title="Editar">
                                              <button class="btn btn-circle"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);"  onclick="editaMetodo(' . $id . ');"><i class="fas fa-pencil-alt"></i></button></center>

                                            </td>-->
                                          </tr>';
                                                    $iteracion++;
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- sample modal content -->
                    <div id="modalregPago" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                    <h4 class="modal-title" id="lblEditMetodo">Registro de Método de Pago</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                                </div>
                                <div class="modal-body">
                                    <form role="form" method="post" action="../funciones/registranuevometodo.php" id="formRegMetodo">

                                        <label for="rNombre" class="control-label col-form-label">Descripción del Pago</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rNombre1"><i class="far fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="rNombre" aria-describedby="nombre" name="rNombre" oninput="limpiaCadena(this.value,'eNombre');" required>
                                        </div>
                                        <label for="rClave" class="control-label col-form-label">Clave</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rClave1"><i class="far fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="rClave" aria-describedby="nombre" name="rClave" oninput="limpiaCadena(this.value,'eClave');" required>
                                        </div>
                                        <label for="rBanco" class="control-label col-form-label">Banco Aceptado <small>* Si es necesario</small></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rBanco1"><i class="far fa-envelope"></i></span>
                                            </div>
                                            <?php
                                            $sql = "SELECT id, nombreCorto FROM catbancos WHERE estatus = 1 ORDER BY nombreCorto ASC";
                                            $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                                            ?>
                                            <select class="select2 form-control custom-select" name="rBanco" id="rBanco" onchange="" style="width: 90.8%;">
                                                <option value="">Asigna un Banco</option>

                                                <?php
                                                while ($dat = mysqli_fetch_array($res)) {

                                                    echo '<option value="' . $dat['id'] . '">' . $dat['nombreCorto'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <label for="rCierra" class="control-label col-form-label">Cierre de Pago</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rCierra1"><i class="far fa-envelope"></i></span>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="rSiCierra" value='1' name="rCierre" required="">
                                                    <label for="rSiCierra">Si</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="rNoCierra" value='0' name="rCierre" required="">
                                                    <label for="rNoCierra">No</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div id="bloquear-btn1" style="display:none;">
                                                <button class="btn btn-primary" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    <span class="sr-only">Loading...</span>
                                                </button>
                                                <button class="btn btn-primary" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    <span class="sr-only">Loading...</span>
                                                </button>
                                                <button class="btn btn-primary" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                            <div id="desbloquear-btn1">
                                                <input type="hidden" id="idMarca">
                                                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success waves-effect waves-light">Registrar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- sample modal content -->
                    <div id="modalEditMet" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                    <h4 class="modal-title" id="lblEditMarca">Edición de Método de Pago</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                                </div>
                                <div class="modal-body" id="MetodoContent">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->


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
        <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
        <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
        <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
        <script src="../assets/scripts/basicFuctions.js"></script>
        <script src="../assets/scripts/notificaciones.js"></script>
        <script src="../dist/js/custom.min.js"></script>
        <script src="../assets/libs/toastr/build/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
                <?php
                #  include('../funciones/basicFuctions.php');
                #  alertMsj($nameLk);
                if (isset($_SESSION['LZFmsjCatalogoMet'])) {
                    echo "notificaBad('" . $_SESSION['LZFmsjCatalogoMet'] . "');";
                    unset($_SESSION['LZFmsjCatalogoMet']);
                }
                if (isset($_SESSION['LZFmsjSuccessCatalogoMet'])) {
                    echo "notificaSuc('" . $_SESSION['LZFmsjSuccessCatalogoMet'] . "');";
                    unset($_SESSION['LZFmsjSuccessCatalogoMet']);
                }
                ?>
                $("#rBanco").select2({
                    language: {

                        noResults: function() {

                            return "No hay resultado";
                        },
                        searching: function() {

                            return "Buscando..";
                        }
                    }
                });
            }); // Cierre de document ready

            function editaMetodo(ident) {


                $.post("../funciones/formEditaMetodo.php", {
                        ident: ident
                    },
                    function(respuesta) {
                        $("#MetodoContent").html(respuesta);
                        $('#modalEditMet').modal('show');
                    });

            }



            function estatusPago(id, estatus) {

                // if (id != '' && estatus != '') {
                $.post("../funciones/cambiaEstatusPago.php", {
                        ident: id,
                        estatus: estatus
                    },
                    function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {
                            notificaSuc(resp[1]);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            notificaBad(resp[0]);
                        }
                    });
                /* } else {
                   notificaBad('No se reconoció el Producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
                 }*/
            }
        </script>

</body>

</html>
