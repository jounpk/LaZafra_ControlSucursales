<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('include/connect.php');

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
    <link rel="icon" type="../image/png" sizes="16x16" href="assets/images/<?= $pyme; ?>.ico">
    <title><?= $info->nombrePag; ?></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/libs/select2/dist/css/select2.min.css">

    <link href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet">

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


                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->

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
                                <h4 class="m-b-0 text-white">Listado de Gastos</h4>
                            </div>
                            <div class="card-body">
                            <div class="text-right">
                                                <button class="btn btn-outline-<?= $pyme; ?> btn-rounded" data-target="#modalregGasto" data-toggle="modal"> <i class="fas fa-plus"></i> Nuevo Gasto</button>
                                    </div>
                                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">


                                    <div class="row">


                                        <div class="col-6">

                                        </div>
                                        <div class="col-6">

                                        </div>
                                    </div>


                                    <!--/span-->
                                   
                                       

                                    <div class="table-responsive">
                                        <table class="table product-overview table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Folio</th>
                                                    <th class="">Descripción</th>
                                                    <th class="text-center">Monto</th>
                                                    <th class="text-center">Fecha Registro</th>
                                                    <th class="text-center">Docto Comprobante</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $idSucursal = $_SESSION['LZFidSuc'];
                                                $sqlgstos = "SELECT gstos.* FROM gastos gstos WHERE idSucursal='$idSucursal'  AND idCorte=0 ORDER BY fechaReg DESC";
                                                //  echo $sqlgstos;
                                                $resgstos = mysqli_query($link, $sqlgstos) or die('Problemas al listar los Gastos y Pagos, notifica a tu Administrador');
                                                $iteracion = 1;
                                                while ($gsto = mysqli_fetch_array($resgstos)) {
                                                    $id = $iteracion;
                                                    $disabled = ($gsto['idCorte'] != 0) ? 'disabled' : '';
                                                    $doctoEvid = $gsto['doctoComprobante'] != '' ? '<center><button type="button" onclick="verIMGGsto(\'' . $gsto['descripcion'] . '\',\'' . $gsto['id'] . '\',\'' . $gsto['extcomprobante'] . '\');"  class="btn btn-danger btn-circle-tablita" id=""><i class="fas fa-file-pdf"></i> </button></center>' : ' ';

                                                    $estatus = '<div class="text-center"><button data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" type="button"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" ' . $disabled . ' onclick="eliminarGsto(' . $gsto['id'] . ');"
                                                    class="btn ink-reaction btn-icon-toggle btn-circle" ><i class="fas fa-trash-alt text-danger"></i></button>
													 <button data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" type="button"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" ' . $disabled . ' onclick="editarGsto(' . $gsto['id'] . ',1);"
														class="btn ink-reaction btn-icon-toggle btn-circle"><i class="fas fa-pencil-alt text-info"></i></button></div>';
                                                    echo '<tr class="' . $alerta . '">
                                          <td class="text-center">' . $id . '</td>
                                          <td class="text-center">' . $gsto['id'] . '</td>
                                          <td id="gstoDesc-' . $id . '" >' . $gsto['descripcion'] . '</td>
                                          <td id="gstoMonto-' . $id . '" class="text-center">
                                          <label id="lblmonto' . $gsto['id'] . '">' . $gsto['monto'] . '</label>
                                          <input type="hidden" min="0" pattern="[0-9]" maxlength="10" id="monto-' . $gsto['id'] . '" value="' . $gsto['monto'] . '" class="form-control" >
                                                                                      </td>
                                          <td id="gstoFecha-' . $id . '" class="text-center">' . date_format(date_create($gsto['fechaReg']), 'd-m-Y H:i:s')  . '</td>
                                          <td>'.$doctoEvid.'</td>
                                          <td id="boton-' . $gsto['id'] . '">' . $estatus . '</td>
                                      
                                         ';
                                                    $iteracion++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end .table-responsive -->
                                </div>
                                <!--end .col -->
                            </div>
                            <!--end .row -->
                            <!-- END DATATABLE 1 -->
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                    <!-- END ACTION -->





                    <!-- sample modal content -->

                    <div id="modalEditPago" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                    <h4 class="modal-title" id="lblEditMarca">Edición del Pago Registrado</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                                </div>
                                <div class="modal-body" id="PagoContent">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->



                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Listado de Pagos Pendientes</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">
                                    <button class="btn btn-outline-<?= $pyme; ?> btn-rounded" data-target="#modalregPago" data-toggle="modal"> <i class="fas fa-plus"></i> Nuevo Pago</button>
                                </div>
                                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                                    <?php
                                    $fechaAct = date('d-m-Y');
                                    if (isset($_POST['fechaInicial'])) {
                                        $fechaInicial = $_POST['fechaInicial'];
                                        $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                                        $filtroFechas = "AND gstos.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                                    } else {
                                        $fechaInicial = $fechaAct;
                                        $filtroFechas = "";
                                    }
                                    if (isset($_POST['fechaFinal'])) {
                                        $fechaFinal = $_POST['fechaFinal'];
                                        $filtroFechas = "AND gstos.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                                        $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                                        $filtroFechas = "AND gstos.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                                    } else {
                                        $fechaFinal = $fechaAct;
                                        $filtroFechas = "";
                                    }






                                    ?>

                                    <div class="row">
                                        <form method="post" action="registrogastos.php#pagos">

                                            <div class="col-6">

                                            </div>
                                            <div class="col-6">

                                            </div>
                                    </div>


                                    <!--/span-->
                                    <div class="border p-2 mb-3" id="pagos">
                                        <div class="row mx-4">

                                            <form method="post" action="registrogastos.php#pagos">
                                                <div class="col-md-6 mt-2">
                                                    <h4><i class="fas fa-filter "></i> Búsqueda por rango de fechas</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>

                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                                        <input class="datepicker form-control" type="text" value="<?= $fechaInicial ?>" id="rangeBa1" name="fechaInicial" />

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>

                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                                        <input class="datepicker form-control" type="text" value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pt-4">
                                                    <input type="submit" id="buscarConexion" class="btn btn-success mt-5" value="Buscar"></input>


                                                </div>

                                        </div>
                                    </div>
                                    </form>






                                    <div class="table-responsive">
                                        <table class="table product-overview table-striped" id="zero_config">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Folio</th>
                                                    <th class="text-center">Descripción</th>
                                                    <th class="text-center">Monto</th>
                                                    <th class="text-center">Fecha Vencimiento</th>

                                                    <th class="text-center">Eliminar</th>
                                                    <th class="text-center">Acciones</th>






                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $idSucursal = $_SESSION['LZFidSuc'];
                                                $sqlpagos = "SELECT
                                                gstos.*,
                                                serv.nombre AS servicio,
                                            IF
                                                ( gstos.fechaVencimiento < NOW() AND gstos.pagado = 0, 'table-danger', '' ) AS vence 
                                            FROM
                                                pagos gstos 
                                                INNER JOIN catservicios serv ON gstos.idServicio = serv.id
                                            WHERE
                                                idSucursal = '$idSucursal' $filtroFechas
                                                AND pagado = 0 
                                            ORDER BY
                                                gstos.fechaVencimiento ASC";
                                                // echo $sqlgastos;
                                                $respgos = mysqli_query($link, $sqlpagos) or die('Problemas al listar los Gastos y Pagos, notifica a tu Administrador');
                                                $iteracion = 1;
                                                while ($pgo = mysqli_fetch_array($respgos)) {
                                                    $id = $iteracion;
                                                    $disabled = ($pgo['pagado'] == 1) ? 'disabled' : '';
                                                    // echo(strtotime(date("d-m-Y H:i:00",time()))>=strtotime($gsto['fechaVencimiento']."24:00:00"))."<br>";
                                                    // echo strtotime($gsto['fechaVencimiento'])."<br>";

                                                    $alerta = ($pgo['pagado'] == 1) ? 'table-success' : '';


                                                    $estatus =
                                                        '<center class="text-danger"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar">
														 <button type="button"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" ' . $disabled . ' onclick="eliminarPago(' . $pgo['id'] . ');"
														class="btn ink-reaction btn-icon-toggle btn-circle"><i class="fas fa-trash-alt text-danger"></i></button></center>';
                                                    echo '<tr class="' . $alerta . ' ' . $pgo['vence'] . '">
                                          <td class="text-center">' . $id . '</td>
                                          <td class="text-center">' . $pgo['id'] . '</td>
                                          <td id="pgoDesc-' . $id . '" >' . $pgo['servicio'] . '</td>
                                          <td id="pgpMonto-' . $id . '" class="text-center">$' . $pgo['monto'] . '</td>
                                          <td id="pgpFecha-' . $id . '" class="text-center">' . date_format(date_create($pgo['fechaVencimiento']), 'd-m-Y')  . '</td>
                                          <td id="pgoEstatus-' . $id . '">' . $estatus . '</td>
                                      
                                          <td class="text-center"> <center class="text-success" >
                                          <div class="btn-group">
                                          <button type="button" class="btn btn-' . $pyme . ' btn-sm dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-cog"></i> <span class="caret"></span> </button>
                                          <div class="dropdown-menu">
                                          <input type="hidden" name="id" class="form-control" value="' . $pgo['id'] . '" id="id">';
                                                    if ($pgo['pagado'] == 0) {
                                                        echo '<a class="dropdown-item editarGsto"   onclick="editarPago(' . $pgo['id'] . ')">Editar</a>
                           ';
                                                    }
                                                    echo '<a class="dropdown-item editarGsto" onclick="verIMG(\'' . $pgo['descripcion'] . '\',\'' . $pgo['id'] . '\',\'' . $pgo['extensionRecibos'] . '\');" >Detalles del Recibo</a>
                          ';
                                                    if ($pgo['pagado'] == 1) {
                                                        echo ' <a class="dropdown-item editarGsto"  >Detalles de Pago</a>';
                                                    }
                                                    echo '</div></td></td> </tr>';

                                                    $iteracion++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end .table-responsive -->
                                </div>
                                <!--end .col -->
                            </div>
                            <!--end .row -->
                            <!-- END DATATABLE 1 -->
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                    <!-- END ACTION -->

                    <!-- sample modal content -->
                    <div id="modalregPago" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                    <h4 class="modal-title" id="lblEditMetodo">Registro de Pagos</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                                </div>
                                <div class="modal-body">
                                    <form role="form" method="post" id="formRegPago" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="rdescGasto" class="control-label col-form-label">Descripción del Pago</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="rdescGasto1"><i class=" fas fa-bars"></i></span>
                                                    </div>
                                                    <!-- <input type="text" class="form-control" id="rdescGasto" aria-describedby="nombre" name="rdescGasto" oninput="limpiaCadena(this.value,'rdescGasto');" required>-->
                                                    <select class="form-control" name="rdescGasto" required>
                                                        <option value=""></option>
                                                        <?php
                                                        $sql = "SELECT id, nombre FROM catservicios WHERE estatus='1' ORDER BY nombre";
                                                        $resXserv = mysqli_query($link, $sql) or die('Problemas al listar los Gastos y Pagos, notifica a tu Administrador');
                                                        while ($dat = mysqli_fetch_array($resXserv)) {
                                                            echo "<option value='" . $dat["id"] . "'>" . $dat["nombre"] . "</option>";
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="rMonto" class="control-label col-form-label">Monto</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rMonto1">$</span>
                                            </div>
                                            <input type="text" class="form-control" id="rMonto" aria-describedby="nombre" name="rMonto" oninput="limpiaCadena(this.value,'rMonto');" required>
                                        </div>
                                        <label for="rfechavence" class="control-label col-form-label">Fecha Vencimiento</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rfechavence1"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input class="form-control datepicker" type="text" min="<?= date('Y-m-d'); ?>" value="<?= date('d-m-Y') ?>" id="rfechavence" name="rfechavence" />
                                        </div>




                                        <label for="rdocto" class="control-label col-form-label">Documento del pago</label>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon5"><i class=" fas fa-file-pdf"></i></span>
                                            </div>
                                            <input type="file" name="rdocto" id="rdocto" title="Documento del Pago" class="form-control" required>
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

                    <div id="modalregGasto" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                    <h4 class="modal-title" id="lblEditMetodo">Registro de Gastos</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                                </div>
                                <div class="modal-body">
                                    <form role="form" method="post" id="formRegGasto" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="rdescGasto" class="control-label col-form-label">Descripción del Gasto</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id=""><i class=" fas fa-bars"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="rdescGasto" aria-describedby="nombre" name="rdescGasto" oninput="limpiaCadena(this.value,'rdescGasto');" required>

                                               
                                        </div>
                                        <label for="rMonto" class="control-label col-form-label">Monto</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="rMonto1">$</span>
                                            </div>
                                            <input type="text" class="form-control" id="rMonto" aria-describedby="nombre" name="rMonto" required>
                                        </div>
                    
                                        <label for="rdocto" class="control-label col-form-label">Comprobante del Gasto</label>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon5"><i class=" fas fa-file-pdf"></i></span>
                                            </div>
                                            <input type="file" name="rdocto" id="rdocto" title="Documento del Pago" class="form-control" required>
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























                

                   

                    <!-- sample modal content -->

                    <div id="modalEditPago" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                    <h4 class="modal-title" id="lblEditMarca">Edición del Pago Registrado</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                                </div>
                                <div class="modal-body" id="PagoContent">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->
                </div>
                </div>
                <footer class="footer text-center">
                    Powered by
                    <b class="text-info">RVSETyS</b>.
                </footer>

            </div>

        </div>
        <div id="verimagen_espacio"></div>

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
        <!-- dataTable js -->
        <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min-ESP.js"></script>
        <script src="dist/js/pages/datatable/datatable-basic.init.js"></script>
        <!--Menu sidebar -->
        <script src="assets/libs/select2/dist/js/select2.full.min.js"></script>
        <script src="assets/libs/select2/dist/js/select2.min.js"></script>
        <script src="dist/js/pages/forms/select2/select2.init.js"></script>
        <script src="dist/js/sidebarmenu.js"></script>
        <script type="text/javascript" src="assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js" charset="UTF-8"></script>

        <!--Custom JavaScript -->
        <script src="assets/scripts/basicFuctions.js"></script>
        <script src="assets/scripts/notificaciones.js"></script>
        <script src="dist/js/custom.min.js"></script>
        <script src="assets/libs/toastr/build/toastr.min.js"></script>

        <script>
            $(document).ready(function() {


                $('.datepicker').datepicker({
                    language: 'es',
                    format: 'dd-mm-yyyy',
                });
                <?php
                #include('../funciones/basicFuctions.php');
                #alertMsj($nameLk);

                if (isset($_SESSION['LZmsjInfoAltaProducto'])) {
                    echo "notificaBad('" . $_SESSION['LZmsjInfoAltaProducto'] . "');";
                    unset($_SESSION['LZmsjInfoAltaProducto']);
                }
                if (isset($_SESSION['LZmsjSuccessProducto'])) {
                    echo "notificaSuc('" . $_SESSION['LZmsjSuccessProducto'] . "');";
                    unset($_SESSION['LZmsjSuccessProducto']);
                }
                ?>
            }); // Cierre de document ready


            function editarPago(ident) {
                $.post("funciones/formEditaPago.php", {
                        ident: ident
                    },
                    function(respuesta) {
                        $("#PagoContent").html(respuesta);
                        $('#modalEditPago').modal('show');
                    });
            }

            function limpiaCadena(dat, id) {
                //alert(id);
                dat = getCadenaLimpia(dat);
                $("#" + id).val(dat);
            }
            $('#verIMG').on('hidden', function() {
                $(this).data('modal').$element.removeData();
            })

            function verIMG(name, id, ext) {
                $.post("funciones/visualizarimg.php", {
                        ident: id,
                        color: '<?= $pyme; ?>'
                    },
                    function(respuesta) {

                        //alert(respuesta);
                        // $('#verIMG').removeData();
                        $("#verimagen_espacio").html(respuesta);
                        $('#verIMG').modal('show');
                    });
                   


                /* $("#verIMGTitle").html('<b>' + name + '</b>');
                 switch (ext) {
                     case 'pdf':
                         $.post("funciones/visualizarimg.php", {
                        ident:id
                     },
                     function(respuesta) {
                         $("#verIMGBody").html('<embed src="' + respuesta + '" type="application/pdf" width="100%" height="600"  ></embed>');
                     });
                        

                         break;

                     default:
                     $.post("funciones/visualizarimg.php", {
                        ident:id
                     },
                     function(respuesta) {
                         $("#verIMGBody").html('<img class="img-thumbnail responsive" src="' + respuesta + '" width="100%"  >');
                     });
                        
                         break;
                 }*/

            }
            function verIMGGsto(name, id, ext) {
                $.post("funciones/visualizarimgGsto.php", {
                        ident: id,
                        color: '<?= $pyme; ?>'
                    },
                    function(respuesta) {

                        //alert(respuesta);
                        // $('#verIMG').removeData();
                        $("#verimagen_espacio").html(respuesta);
                        $('#verIMG').modal('show');
                    });

                }      
            $("#formRegPago").submit(function(event) {
                event.preventDefault();
                var formElement = document.getElementById("formRegPago");
                var formGasto = new FormData(formElement);
                $.ajax({
                    type: 'POST',
                    url: "funciones/registranuevoPago.php",
                    data: formGasto,
                    processData: false,
                    contentType: false,

                    success: function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {
                            bloqueoBtn("bloquear-btn1", 1);
                            notificaSuc(resp[1]);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            bloqueoBtn("bloquear-btn1", 2);
                            notificaBad(resp[1]);
                        }
                    }
                });
            });

            $("#formRegGasto").submit(function(event) {
                event.preventDefault();
                var formElement = document.getElementById("formRegGasto");
                var formGasto = new FormData(formElement);
                $.ajax({
                    type: 'POST',
                    url: "funciones/registraNuevoGsto.php",
                    data: formGasto,
                    processData: false,
                    contentType: false,

                    success: function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {
                            bloqueoBtn("bloquear-btn1", 1);
                            notificaSuc(resp[1]);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            bloqueoBtn("bloquear-btn1", 2);
                            notificaBad(resp[1]);
                        }
                    }
                });
            });



            function eliminarPago(id) {

                $.post("funciones/eliminaPagoSuc.php", {
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

            function editarGsto(id, no) {
                if (no == 1) {
                    $("#lblmonto" + id).hide();
                    $("#monto-" + id).attr("type", 'number');
                    $("#boton-" + id).html('<div class="text-center"><button id="btnSave' + id + '" onclick="guardaGsto(' + id + ');" type="button" class="btn btn-success btn-circle" title="Capturar"><i class=" fas fa-check"></i></button><button type="button" class="btn btn-danger btn-circle" onclick="editarGsto(' + id + ',2)" title="Cancelar"><i class="fa fa-times"></i></button><span id="res' + id + '"></span></div>');
                } else {
                    $("#lblmonto" + id).show();

                    var val1 = $("#lblmonto" + id).html();

                    $("#monto-" + id).attr("type", 'hidden');
                    $("#monto-" + id).attr("value", val1);

                    $("#boton-" + id).html(`<div class="text-center"><button data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" type="button"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" ' . $disabled . ' onclick="eliminarGsto(${id});"
    class="btn ink-reaction btn-icon-toggle btn-circle" ><i class="fas fa-trash-alt text-danger"></i></button>
    <button data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" type="button"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" ' . $disabled . ' onclick="editarGsto(${id},1);"
    class="btn ink-reaction btn-icon-toggle btn-circle"><i class="fas fa-pencil-alt text-info"></i></button></div>
    `);
                }
            }

            function guardaGsto(ident) {
                var monto = $("#monto-" + ident).val();
                if (monto < 0) {
                    editarGsto(ident, 2);
                    notificaBad('Cantidades registradas no aceptadas, intentálo de nuevo.');
                } else {
                    $.post("funciones/editaGsto.php", {
                            ident: ident,
                            monto: monto

                        },
                        function(respuesta) {
                            $("#lblmonto" + ident).html(monto);

                            editarGsto(ident, 2);
                            var res = respuesta.split('|');
                            if (res[0] == 1) {
                                notificaSuc(res[1]);
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            } else {
                                notificaBad(res[1]);
                            }
                        });

                }
            }

            function eliminarGsto(id) {

                $.post("funciones/eliminaGstoSuc.php", {
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

            function listaDeptos() {
                //  var mensaje = 'Mensaje';
                $.post("funciones/listarDeptos.php", {},
                    function(respuesta) {
                        $("#validation").html(respuesta);
                    });
            }
        </script>

</body>

</html>