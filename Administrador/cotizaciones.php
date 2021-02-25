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
$debug = 0;
$userReg = $_SESSION['LZFident'];
$sucursal = $_SESSION['LZFidSuc'];

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
                <?php
                $num_correo = 0;
                $activeNoReg = '';
                $activeReg = 'active';
                $disabled = '';
                $sql = "SELECT * FROM cotizaciones WHERE idUserReg='$userReg' AND estatus='1' AND idSucursal='$sucursal'";
                //----------------devBug------------------------------
                if ($debug == 1) {
                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar las ventas a crédito del cliente, notifica a tu Administrador.');
                    echo '<br>Listado de Ventas de Crédito de Cliente: ' .    $sql  . '<br>';
                } else {
                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar las ventas a crédito del cliente, notifica a tu Administrador.');
                } //-------------Finaliza devBug------------------------------
                $var = mysqli_fetch_array($resXQuery);
                $num_filas = mysqli_num_rows($resXQuery);
                $idCotizaciones = $var['id'];
                $nameCliente = $var['nameCliente'];
                $idCliente = $var['idCliente'];
                $tipo = $var['tipo'];
                $hayCotizacion = $num_filas > 0 ? '0' : '1';
                if ($idCliente != '') {
                    $activeReg = "active";
                    $activeNoReg = "";
                    $disabled = 'disabled';
                } else if ($nameCliente != '') {
                    $activeNoReg = "active";
                    $activeReg = "";
                    $disabled = 'disabled';
                }
                echo '<input type="hidden" value="' . $idCotizaciones . '" id="identCoti">';
                $sql = "SELECT * FROM detcotcorreos WHERE idCotizacion='$idCotizaciones'";
                //----------------devBug------------------------------
                if ($debug == 1) {
                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar Correos, notifica a tu Administrador.');
                    echo '<br>Listado de Correos: ' .    $sql  . '<br>';
                } else {
                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar Correos, notifica a tu Administrador.');
                } //-------------Finaliza devBug------------------------------
                $correoApuntados = mysqli_num_rows($resXQuery);
                $contenidoCorreos = '';
                if ($correoApuntados >= 1) {
                    while ($correos = mysqli_fetch_array($resXQuery)) {
                        $contenidoCorreos .= $correos['correo'] . '<br>';
                    }
                } else {
                    $contenidoCorreos .= "<b>SIN CORREOS ASIGNADOS</b>";
                }

                ?>


                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="m-b-0 text-white">Registro de Cotizaciones</h4>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs customtab" role="tablist">
                                            <li class="nav-item"> <a class="nav-link <?= $activeReg ?>" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class=" fas fa-check"></i></span> <span class="hidden-xs-down">Cliente Registrado</span></a> </li>
                                            <li class="nav-item"> <a class="nav-link <?= $activeNoReg ?>" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="fas fa-times"></i></span> <span class="hidden-xs-down">Cliente NO Registrado</span></a> </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane <?= $activeReg ?>" id="home2" role="tabpanel">
                                                <br>
                                                <form id="formRegistrado">

                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <select class="select2 form-control custom-select" <?= $disabled ?> name="idCliente" id="idCliente" onchange="" style="width: 100%;" <?= $disabledBtn ?>>
                                                                <option value=''>Selecciona Cliente Registrado</option>
                                                                <?php
                                                                $sql = "SELECT
                                                                        cl.id,
                                                                        IF (cl.apodo IS NULL OR cl.apodo='',cl.nombre, CONCAT(cl.nombre,' \"',cl.apodo,'\" ')) AS cliente
                                                                        FROM
                                                                        clientes cl 
                                                                        WHERE
                                                                        cl.estatus = '1' ORDER BY cliente ASC";
                                                                //----------------devBug------------------------------
                                                                if ($debug == 1) {
                                                                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar cliente, notifica a tu Administrador.');
                                                                    echo '<br>Listado de Ventas de Crédito de Cliente: ' .    $sql  . '<br>';
                                                                } else {
                                                                    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar cliente, notifica a tu Administrador.');
                                                                } //-------------Finaliza devBug------------------------------
                                                                while ($clientes = mysqli_fetch_array($resXQuery)) {
                                                                    if ($idCliente == $clientes['id']) {
                                                                        echo '<option value="' . $clientes['id'] . '" ' . $activeSuc . ' selected>' . $clientes['cliente'] . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $clientes['id'] . '" ' . $activeSuc . '>' . $clientes['cliente'] . '</option>';
                                                                    }
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button title="Iniciar Cotización" <?= $disabled ?> id="agregarCoti" type="submit" class="btn btn-circle bg-<?= $pyme; ?>" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" type="button"><i class="fas  fas fa-check"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane  p-20 <?= $activeNoReg ?>" id="profile2" role="tabpanel">
                                                <form id="formNoRegistrado">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" <?= $disabled ?> name="nameCliente" id="nameCliente" placeholder="Ingresa Nombre del Cliente ..." value='<?= $nameCliente ?>'></input>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <button title="Iniciar Cotización" <?= $disabled ?> type="submit" id="agregarCoti" class="btn btn-circle bg-<?= $pyme; ?>" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" type="button"><i class="fas  fas fa-check"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <hr>
                                <div class="text-right">
                                    <button type="button" class="btn btn-light text-white pt-2" data-toggle="popover" title="Correos Asignados" data-html="true" data-content="<?= $contenidoCorreos ?>"> <i class="far fa-envelope fa-lg text-dark"></i>
                                        <span class="badge badge-dark"><?= $correoApuntados ?></span></button>
                                </div>
                                <form id="formCorreo">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="email" class="control-label col-form-label">Envío de Correo:</label>
                                            <div class="input-group">
                                                <input type="hidden" name="idCotizacion" value="<?= $idCotizaciones ?>"></input>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Correo para Enviar ..."></input>
                                                <div class="input-group-prepend">
                                                    <button class="input-group-text" type="submit"><i class="far fa-paper-plane text-info"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-4">

                                        </div>

                                    </div>
                                </form>





                            </div>
                        </div>
                        <!--end .table-responsive -->
                    </div>


                    <div class="col-md-8">
                        <div class="card border-<?= $pyme; ?>" <?php if ($idCotizaciones == '') {
                                                                    echo 'style="height: 18.5rem;"';
                                                                } ?>>
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Producto A Cotizar</h4>
                            </div>
                            <div class="card-body">

                                <?php
                                // echo 'IDTraspasos-->'.$idTraspasos.'<br>';
                                if ($idCotizaciones != '') {
                                ?>
                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="inputEmail3" class="control-label col-form-label">Producto</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="finVig1"><i class="far fa-clipboard"></i></span>
                                                    </div>

                                                    <select class="select2 form-control custom-select" name="prod" id="producto" onchange="regProducto(<?= $idCotizaciones ?>, this.value);" style="width: 87%;">

                                                        <option value=""> Agrega más productos</option>
                                                        <?php
                                                        $sql = "SELECT pr.id, pr.descripcion FROM productos pr WHERE pr.estatus='1'";
                                                        //----------------devBug------------------------------
                                                        if ($debug == 1) {
                                                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar productos, notifica a tu Administrador.');
                                                            echo '<br>Listado de Productos: ' .    $sql  . '<br>';
                                                        } else {
                                                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar productos, notifica a tu Administrador.');
                                                        } //-------------Finaliza devBug------------------------------
                                                        while ($productos = mysqli_fetch_array($resXQuery)) {

                                                            echo '<option value="' . $productos['id'] . '" >' . $productos['descripcion'] . '</option>';
                                                        }

                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <form id="formTraspasos" method="post">
                                                <table class="table product-overview ">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" class="text-center">#</th>
                                                            <th>Descripción</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-center">Costo</th>
                                                            <th class="text-center">Precio</th>
                                                            <th class="text-center">Subtotal</th>
                                                            <th class="text-center">Eliminar</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT *, dc.id  AS idDetCotizacion, FORMAT(dc.costo, 2) AS formatCosto,FORMAT(dc.subtotal,2) AS formatSubtotal FROM detcotizaciones dc
                                                        INNER JOIN productos pr ON dc.idProducto=pr.id
                                                        WHERE idCotizacion='$idCotizaciones'";
                                                        //echo $sql;
                                                        //----------------devBug------------------------------
                                                        if ($debug == 1) {
                                                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al ver Cotizaciones, notifica a tu Administrador.');
                                                            echo '<br>Listado de Productos: ' .    $sql  . '<br>';
                                                        } else {
                                                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al ver Cotizaciones, notifica a tu Administrador.');
                                                        } //-------------Finaliza devBug------------------------------
                                                        $it = 1;
                                                        $encender = '';


                                                        while ($datos = mysqli_fetch_array($resXQuery)) {
                                                            $idDetCotizacion = $datos['idDetCotizacion'];
                                                            $descripcion = $datos['medios'] == '1' ? 'step="0.1"' : 'step="1"';
                                                            $precioSeleccionado = "";
                                                            $sql = 'SELECT pb.id, pb.precio, FORMAT(pb.precio,2) AS format_precio FROM preciosbase pb
                                                            INNER JOIN productos pr ON pr.id=pb.idProducto
                                                            WHERE idProducto="' . $datos['idProducto'] . '"';
                                                            //echo $sql;
                                                            //----------------devBug------------------------------
                                                            if ($debug == 1) {
                                                                $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
                                                                $precioSeleccionado .= '<br>Listado de Precios: ' .    $sql  . '<br>';
                                                            } else {
                                                                $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
                                                            } //-------------Finaliza devBug------------------------------
                                                            $precioSeleccionado .= "<div id='seccionPrecio-" . $idDetCotizacion . "'><select class='form-control custom-select' name='precio' id='precio' onchange='regPrecio($idDetCotizacion, this.value);'>";
                                                            $asignaPrecio = $datos['asignaPrecio'];
                                                            $precioSeleccionado .= '<option value="">--</option>';
                                                            while ($precios = mysqli_fetch_array($resXQueryPrecio)) {
                                                                $precioSeleccionado .= '<option value="' . $precios['id'] . '" >$' . $precios['format_precio'] . '</option>';
                                                            }
                                                            $precioSeleccionado .= '<option value="0">Otro Precio</option></select></div>';

                                                            if ($asignaPrecio == 1) {
                                                                $sql = 'SELECT pb.id, pb.precio, FORMAT(pb.precio,2) AS format_precio FROM preciosbase pb
                                                                INNER JOIN productos pr ON pr.id=pb.idProducto
                                                                WHERE idProducto="' . $datos['idProducto'] . '"';
                                                                //echo $sql;
                                                                //----------------devBug------------------------------
                                                                if ($debug == 1) {
                                                                    $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
                                                                    $precioSeleccionado .= '<br>Listado de Precios: ' .    $sql  . '<br>';
                                                                } else {
                                                                    $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
                                                                } //-------------Finaliza devBug------------------------------
                                                                $precioSeleccionado = "<div id='seccionPrecio-" . $idDetCotizacion . "'><select class='form-control custom-select' name='precio' id='precio' onchange='regPrecio($idDetCotizacion, this.value);'>";
                                                                $asignaPrecio = $datos['asignaPrecio'];
                                                                $precioSeleccionado .= '<option value="">--</option>';
                                                                while ($precios = mysqli_fetch_array($resXQueryPrecio)) {

                                                                    if ($datos['idPrecioBase'] == $precios['id']) {
                                                                        $precioSeleccionado .= '<option value="' . $precios['id'] . '" selected >$' . $precios['format_precio'] . '</option>';
                                                                    } else {
                                                                        $precioSeleccionado .= '<option value="' . $precios['id'] . '" >$' . $precios['format_precio'] . '</option>';
                                                                    }
                                                                }
                                                                $precioSeleccionado .= '<option value="0">Otro Precio</option></select></div>';
                                                            } else  if ($asignaPrecio == 2) {
                                                                $precioSeleccionado = '<input type="number" step="0.1" class="form-control" id="precioPersonalizado" onchange="regPrecioPersonalizado(' . $idDetCotizacion . ', this.value)" value="' . $datos['precioCoti'] . '"></input>';
                                                            }

                                                            echo '<tr id="tr' . $datos['id'] . '" class="' . $color . '">
                                                                    <td colspan="2">' . $it . '</td>
                                                                <td>' . $datos['descripcion'] . '</td>
                                                                <td><input type="number" class="form-control"' . $descripcion . ' min="0" max="1000" value="' . $datos['cantidad'] . '" onchange="regCantidad(' . $idDetCotizacion . ', this.value)"></td>
                                                                <td>$' . $datos['formatCosto'] . '</td>
                                                                <td>';
                                                            echo $precioSeleccionado;

                                                            echo '</td> 
                                                            <td>$' . $datos['formatSubtotal'] . '</td>
                                                               
                                                                <td><center  data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" >
                                                                <button type="button"  class="btn-circle btn-danger" style="color:#fff, box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);"
                                                                onClick="eliminaProducto(' . $datos['idDetCotizacion'] . ');"><i class="fas  fas fa-trash"></i></button></center></td>
                                                            </tr>';
                                                            $it++;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>

                                        </div>
                                        <hr>
                                        <!-- <form action="../funciones/generaTraspasos.php" method="post">-->
                                    </div>
                                    <input type="hidden" name="ident" id="identTraspasoFinal" value="<?= $idTraspasos; ?>">
                                    </form>
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
                                            <button type="button" onclick="cancelarEdicion('<?= $idCotizaciones; ?>');" class="btn btn-danger ">Cancelar</button>

                                            <button type="button" onclick="finalizaCotizacion('<?= $idCotizaciones; ?>')" class="btn btn-success ">Finalizar Cotización</button>

                                        </div>

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
                                <h4 class="m-b-0 text-white">Cotizaciones Activas</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">
                                    <a class="btn btn-circle bg-<?= $pyme ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Historial" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="historialcotizaciones.php">
                                        <i class="fas fa-calendar-alt"></i></a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table product-overview " id="tablatrasp">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Serie/Folio</th>
                                                <th class="text-center">Cliente</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Usuario Emitio</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-center">Fecha Aut.</th>
                                                <th class="text-center">Ver</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT
                                            ct.idSucursal,
                                            suc.nombre AS sucursales,
                                            ct.id,
                                            ct.folio,
                                             IF
                                            ( ct.tipo = 2, CONCAT( ct.nameCliente, ' <span><b>(Público en General)</b></span>' ), cl.nombre ) AS cliente,
                                            ct.nameCliente,
                                             IF
                                            ( ct.montoTotal IS NULL, '$0.0', CONCAT( '$ ', FORMAT( ct.montoTotal, 2 )) ) AS montoTotal,
                                            CONCAT( usr.nombre, ' ', usr.appat, ' ', usr.apmat ) AS usuario,
                                            DATE_FORMAT( ct.fechaAut, '%d-%m-%Y %H:%i:%s' ) AS fecha,
                                            IF(DATE_ADD(ct.fechaAut,INTERVAL ct.cantPeriodo DAY)>=NOW() AND ct.estatus='3', 'label-success', 'label-danger') AS etiquetita,
                                            IF(DATE_ADD(ct.fechaAut,INTERVAL ct.cantPeriodo DAY)>=NOW() AND ct.estatus='3', 'Activa', 'Expirada') AS etiquetitaText

                                            FROM
                                            cotizaciones ct
                                            LEFT JOIN clientes cl ON ct.idCliente = cl.id
                                            INNER JOIN sucursales suc ON ct.idSucursal = suc.id
                                            INNER JOIN segusuarios usr ON ct.idUserReg = usr.id 
                                             WHERE
                                            ct.estatus = '3' AND DATE_ADD(ct.fechaAut,INTERVAL ct.cantPeriodo DAY)>=NOW() AND ct.idSucursal='$sucursal'";
                                            //echo $sql;
                                            //----------------devBug------------------------------
                                            if ($debug == 1) {
                                                $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
                                                $precioSeleccionado .= '<br>Listado de Precios: ' .    $sql  . '<br>';
                                            } else {
                                                $resXQueryPrecio = mysqli_query($link, $sql) or die('Problemas al ver Precios, notifica a tu Administrador.');
                                            } //-------------Finaliza devBug------------------------------
                                            $total = 0;
                                            $it = 1;
                                            while ($datos = mysqli_fetch_array($resXQueryPrecio)) {


                                                $etiquetita = '<span class="label ' . $datos['etiquetita'] . ' label-rounded">' . $datos['etiquetitaText'] . '</span>';

                                                echo '<tr id="tr' . $datos['id'] . '">
                                               <td>' . $it . '</td> 
                                               <td>' . $datos['folio'] . '</td> 
                                               <td>' . $datos['cliente'] . '</td>
                                              <td>' . $datos['montoTotal'] . '</td>
                                              <td>' . $datos['usuario'] . '</td>
                                              <td>' .  $etiquetita . ' <button class="btn" data-toggle="modal" data-target="#modalEmails" onclick="listCorreos(' . $datos['id'] . ')"><i class="fas fa-envelope text-info" ></i></button></td>
                                              <td>' . $datos['fecha'] . '</td>
                                              <td><a href="../funciones/imprimeTicketCotizacion.php?idCotizacion=' . $datos['id'] . '" target="_blank" class="btn btn-sm btn-outline-success btn-square" title="Imprimir Ticket" disabled><i class="fas fa-file-alt"></i></a>
                                              <a href="../imprimePdfCotizacion.php?idCotizacion=' . $datos['id'] . '" target="_blank" class="btn btn-sm btn-outline-danger btn-square" title="Imprimir PDF" disabled><i class="fas fa-file-pdf"></i></a></td>
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

            <div class="modal fade" id="modalEmails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                            <h4 class="modal-title" id="myModalLabel">
                                <div id="tituloModal">Envio de cotización</div>
                            </h4>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">

                            <table class="table" id="tablaEmails">
                                <tbody id="contenidoModalEmails" class="text-center">

                                </tbody>
                            </table>
                            <form id="formNewEmail">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="email" class="control-label col-form-label">Ingrese un nuevo email:</label>
                                        <div class="input-group">
                                            <input type="email" name="email" class="form-control" required="" id="newEmail" placeholder="ejemplo@example.com" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}">

                                            <div class="input-group-prepend">
                                                <button class="input-group-text" type="submit" id="btnAddEmail"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form><br>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <label id="msnProcesoEmail"></label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" onclick="enviarEmails()" id="btnEnviarEmails">Enviar <i class="far fa-paper-plane text-white"></i></button>
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

    <script>
        function enviarEmails() {
            var idCotizacion = localStorage.getItem("idCoti");
            $("#msnProcesoEmail").html("");
            if (idCotizacion > 0) {
                $.ajax({
                    url: '../funciones/enviarEmails.php',
                    success: function(respuesta) {
                        $("#msnProcesoEmail").html("");
                        notificaSuc("LISTO SE HA ENVIADO CON EXITO");
                        $('#btnEnviarEmails').attr("disabled", false);
                    },
                    beforeSend: function() {
                        $('#btnEnviarEmails').attr("disabled", true);
                        $("#msnProcesoEmail").html("ESPERE UN MOMENTO SE ESTA ENVIANDO...");
                    },
                    error: function() {
                        $("#msnProcesoEmail").html("NO SE PUDO ENVIAR EL CORREO");
                    }
                });
            } else {
                notificaBad('No se reconoció la cotización, actualiza e inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
            }
        }


        function addNewEmail(idCotizacion, email) {
            console.log(email);
            if (idCotizacion > 0 || email !== "") {
                $.post("../funciones/registrarNewEmail.php", {
                    idCotizacion: idCotizacion,
                    email: email
                }, function(respuesta) {

                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {

                        notificaSuc(resp[0]);
                    } else {
                        //  alert(respuesta);
                        notificaBad(resp[1]);
                    }
                });
            } else {
                notificaBad('Algunos de los datos van vacios');
            }

        }

        function deleteEmail(idEmail){
          if(idEmail > 0){
           $.post("../funciones/borrarEmailModal.php",
               {idEmail:idEmail},function(respuesta){
                var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                      notificaSuc(resp[1]);
                        $("#contenidoModalEmails").load("../funciones/listEmailsModal.php?idCotizacion=" + localStorage.getItem('idCoti'));
                     
                    } else {
                        //  alert(respuesta);
                        notificaBad(resp[1]);
                    }
              });
             }else{
                notificaBad('No hay email con id 0'); 
             }
    }
        //agregar nuevo email
        $("#formNewEmail").submit(function(event) {
            event.preventDefault();
            var formData = $("#formNewEmail").serializeArray();

            $.post("../funciones/registrarNewEmail.php",
                formData,
                function(respuesta) {

                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        notificaSuc(resp[1]);
                        $("#contenidoModalEmails").load("../funciones/listEmailsModal.php?idCotizacion=" + localStorage.getItem('idCoti'));
                        $("#formNewEmail")[0].reset();

                    } else {
                        notificaBad(resp[1]);
                    }
                });
        });



        function listCorreos(idCotizacion) {

            if (idCotizacion > 0) {
                console.log('Entra a funcion con idCotizacion: ' + idCotizacion);

                localStorage.setItem('idCoti', idCotizacion);

                $.post("../funciones/listEmailsModal.php?idCotizacion=" + localStorage.getItem('idCoti'), {}, function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        $('#btnEnviarEmails').attr("disabled", false);
                        $("#contenidoModalEmails").html(respuesta);

                    } else {
                        $('#btnEnviarEmails').attr("disabled", true);
                        $("#contenidoModalEmails").html("NO TIENE NINGÚN CORREO ASIGNADO");
                    }
                });

            } else {
                notificaBad('No se reconoció al empleado, actualiza e inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
            }

            /* var pagina = 'Administrador/cotizaciones.php';
             var alerta = 'AdminCotizaciones';
             $('<form action="../funciones/creaCotizacion.php" method="POST"><input type="hidden" name="idCotizacion" value="' + idCotizacion + '"><input type="hidden" name="pagina" value="' + pagina + '"><input type="hidden" name="alerta" value="' + alerta + '"></form>').appendTo('body').submit();*/

        }
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


            if (isset($_SESSION['LZmsjInfoCotizacion'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoCotizacion'] . "');";
                unset($_SESSION['LZmsjInfoCotizacion']);
            }
            if (isset($_SESSION['LZmsjSuccessCotizacion'])) {

                echo "
               notificaSuc('" . $_SESSION['LZmsjSuccessCotizacion'] . "');";
                unset($_SESSION['LZmsjSuccessCotizacion']);
            }
            echo $encender;
            ?>

        }); // Cierre de document ready

        /* ---------------------------- cotizaciones -------------------------------------- */
        $("#formNoRegistrado").submit(function(event) {
            event.preventDefault();
            var formData = $("#formNoRegistrado").serializeArray();

            $.post("../funciones/registroClienteCotizacion.php",
                formData,
                function(respuesta) {
                    //  alert(respuesta);
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        location.reload();
                    } else {
                        //  alert(respuesta);
                        notificaBad(resp[1]);
                    }
                });


        });
        $("#formRegistrado").submit(function(event) {
            event.preventDefault();
            var formData = $("#formRegistrado").serializeArray();

            $.post("../funciones/registroClienteRegCotizacion.php",
                formData,
                function(respuesta) {
                    //  alert(respuesta);
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        location.reload();
                    } else {
                        //  alert(respuesta);
                        notificaBad(resp[1]);
                    }
                });


        });

        function eliminaProducto(id) {
            location.href = "../funciones/borraDetCotizacion.php?id=" + id;
        };


        function regProducto(idCotizaciones, prod) {
            cant = $('#rcant').val();
            if ($("#producto").val() != "") {
                location.href = "../funciones/detalleCotizaciones.php?idCotizacion=" + idCotizaciones + "&producto=" + prod;
            }
        }

        function regPrecio(idDetCotizacion, precio) {
            debug = 0;
            if (debug == 1) {
                console.log("Este es el precio: " + precio);
            }
            if (precio == '0') {
                $("#seccionPrecio-" + idDetCotizacion).html('<input type="number" step="0.1" id="precioPersonalizado" class="form-control" onchange="regPrecioPersonalizado(' + idDetCotizacion + ', this.value)" ></input>');

            } else {
                location.href = "../funciones/asignarPrecioBase.php?idDetCotizacion=" + idDetCotizacion + "&precio=" + precio;
            }
        }


        function regPrecioPersonalizado(idDetCotizacion, precio) {
            debug = 0;
            if (debug == 1) {
                console.log("Este es el precio: " + precio);
            }

            location.href = "../funciones/asignarPrecioPersonalizado.php?idDetCotizacion=" + idDetCotizacion + "&precio=" + precio;

        }


        function regCantidad(idDetCotizacion, cantidad) {
            debug = 0;
            if (debug == 1) {
                console.log("Este es el cantidad: " + cantidad);
            }

            location.href = "../funciones/asignarCantidad.php?idDetCotizacion=" + idDetCotizacion + "&cantidad=" + cantidad;

        }
        $("#formCorreo").submit(function(event) {
            debug = 1;
            event.preventDefault();
            var formData = $("#formCorreo").serializeArray();
            if (debug == 1) {
                console.log(formData);
            }
            $.post("../funciones/registroCorreo.php",
                formData,
                function(respuesta) {
                    if (debug == 1) {
                        console.log(respuesta);
                    }
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        location.reload();
                    } else {
                        //  alert(respuesta);
                        notificaBad(resp[1]);
                    }
                }
            );


        });

        function finalizaCotizacion(idCotizacion) {
            var debug = 0;
            $.post("../funciones/finalizaCotizacion.php", {
                    idCotizacion: idCotizacion
                },
                function(respuesta) {
                    if (debug == 1) {
                        console.log(respuesta);
                    }
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        location.reload();
                    } else {
                        //  alert(respuesta);
                        notificaBad(resp[1]);
                    }
                }
            );
        }

        function cancelarEdicion(idCotizacion) {
            if (idCotizacion != "") {
                $.post("../funciones/cancelaEdicionCotizacion.php", {
                        ident: idCotizacion,
                    },
                    function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {

                            location.reload();

                        } else if (resp[0] == 0) {
                            notificaBad(resp[1]);
                        }
                    });
                // location.href = "../funciones/cancelaEdicionTraspasos.php?ident=" + ident;
            }
        }
    </script>

</body>

</html>