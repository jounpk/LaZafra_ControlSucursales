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
        <!--- <div class="col-lg-2">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Vigencia del Nombramiento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                              </div>
                              <input name="fechaInicial" class="datepicker form-control" type="text" value="<?= $fechaInicial; ?>" id="rangeBa" />
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-2">
                        <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Vigencia del Nombramiento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                              </div>
                              <input name="fechaFinal" class="datepicker form-control" type="text" value="<?= $fechaFinal; ?>" id="rangeBb" />
                            </div>
                          </div>
                        </div>-->

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
                                <h4 class="m-b-0 text-white">Cárdex de Productos</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">

                                </div>
                                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">


                                    <?php
                                    $sucursal = $_SESSION['LZFidSuc'];
                                    $fechaAct = date('d-m-Y');
                                    if (isset($_POST['fechaInicial']) and $_POST['fechaInicial'] != '') {
                                        $fechaInicial = $_POST['fechaInicial'];
                                        $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                                        $filtroFechas = "CAST(fecha AS DATE) BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                                    } else {
                                        $fechaInicial = "";
                                        $filtroFechas = "1=1";
                                    }
                                    if (isset($_POST['fechaFinal'])  and $_POST['fechaFinal'] != '') {
                                        $fechaFinal = $_POST['fechaFinal'];
                                        $filtroFechas = "stk.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                                        $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                                        $filtroFechas = "CAST(fecha AS DATE) BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";

                                    } else {
                                        $fechaFinal = "";
                                        $filtroFechas = "1=1";
                                    }

                                    if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                                        $sucursal = $_POST['buscaSuc'];
                                        $buscaSuc = "suc.id=" . $_POST['buscaSuc'];
                                    } else {
                                        $sucursal = '';
                                        $buscaSuc = '1=1';
                                    }
                                    if (isset($_POST['buscaMov']) and $_POST['buscaMov'] >= 1) {
                                        $buscaMov = $_POST['buscaMov'];
                                        // $filtroSuc = "AND stk.idSucursal=" . $_POST['buscaSuc'];
                                    } else {
                                        $buscaMov = '';
                                        //  $buscaSuc = 'AND 1=1';
                                    }

                                    $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                                    $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                                    $listaSuc = '';
                                    while ($datos = mysqli_fetch_array($resSuc)) {
                                        $activeSuc = ($datos['id'] == $sucursal) ? 'selected' : '';
                                        $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                                    }

                                    if (isset($_POST['buscaProd']) and $_POST['buscaProd'] >= 1) {
                                        $buscaProd = $_POST['buscaProd'];
                                        //$filtroDepto = "dpto.id=" . $_POST['buscaDepto'];
                                    } else {
                                        // $filtroDepto = '';
                                        $buscaProd = '';
                                    }



                                    $sql = "SELECT * FROM productos WHERE estatus=1 ORDER BY descripcion";
                                    $resPro = mysqli_query($link, $sql) or die("Problemas al enlistar Productos.");

                                    $listaPro = '';
                                    while ($datos = mysqli_fetch_array($resPro)) {
                                        $activeProd = ($datos['id'] == $buscaProd) ? 'selected' : '';
                                        $listaProd .= '<option value="' . $datos['id'] . '" ' . $activeProd . '>' . $datos['descripcion'] . '</option>';
                                    }
                                    //echo $filtroFechas;
                                    //echo $filtroDepto;


                                    ?>
                                    <div class="border p-3 mb-3">
                                        <h4><i class="fas fa-filter"></i> Filtrado</h4>

                                        <div class="row">
                                            <form method="post" action="cardex.php">

                                                <div class="col-6">

                                                </div>
                                                <div class="col-6">

                                                </div>
                                        </div>


                                        <!--/span-->

                                        <div class="row">

                                            <form method="post" action="cardex.php">
                                                <div class="col-md-4  offset-md-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>

                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <input class=" form-control" type="date" autocomplete="off" value="<?= $fechaInicial ?>" id="rangeBa1" name="fechaInicial" />

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>

                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <input class=" form-control" type="date" autocomplete="off"  value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3  offset-md-0">
                                                    <div class="form-group">
                                                        <label for="inputEmail3" class="control-label col-form-label">Búsqueda por Producto</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-animation"></i></span>
                                                            </div>
                                                            <select class="select2 form-control custom-select" name="buscaProd" id="buscaProd" onchange="" style="width: 80%;">

                                                                <option value=""> Elige un producto</option>
                                                                <?= $listaProd ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 offset-md-0">
                                                    <div class="form-group">
                                                        <label for="rangeBa1" class="control-label col-form-label">Sucursal</label>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-store"></i></span>
                                                            </div>
                                                            <select class="select2 form-control custom-select" name="buscaSuc" id="buscaSuc" onchange="" style="width: 80%;">

                                                                <option value=""> Todas las Sucursales</option>
                                                                <?= $listaSuc ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="col-md-2  offset-md-0">
                                                    <div class="form-group">
                                                        <label for="rangeBa1" class="control-label col-form-label">Tipo de Movimiento</label>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="finVig1"><i class="fas fa-bookmark"></i></span>
                                                            </div>
                                                            <select class="form-control custom-select" name="buscaMov" id="buscaMov" onchange="">
                                                                <?php switch ($buscaMov) {
                                                                    case '':
                                                                        echo ' <option value="" selected>Todos</option>
                                                                    <option value="1">Ajuste</option>
                                                                    <option value="3"> Devoluciones</option>
                                                                    <option value="4"> Ediciones de Stock</option>
                                                                    <option value="5"> Traspasos</option>
                                                                    <option value="6"> Ventas</option>';
                                                                        break;
                                                                    case '1':
                                                                        echo ' <option value="">Todos</option>
                                                                        <option value="1" selected>Ajuste</option>
                                                                        <option value="3"> Devoluciones</option>
                                                                        <option value="4"> Ediciones de Stock</option>
                                                                        <option value="5"> Traspasos</option>
                                                                        <option value="6"> Ventas</option>';
                                                                        break;
                                                                    case '2':
                                                                        echo ' <option value="">Todos</option>
                                                                            <option value="1">Ajuste</option>
                                                                            <option value="3"> Devoluciones</option>
                                                                            <option value="4"> Ediciones de Stock</option>
                                                                            <option value="5"> Traspasos</option>
                                                                            <option value="6"> Ventas</option>';
                                                                        break;
                                                                    case '2':
                                                                        echo ' <option value="">Todos</option>
                                                                                <option value="1">Ajuste</option>
                                                                                <option value="3"> Devoluciones</option>
                                                                                <option value="4"> Ediciones de Stock</option>
                                                                                <option value="5"> Traspasos</option>
                                                                                <option value="6"> Ventas</option>';
                                                                        break;
                                                                    case '3':
                                                                        echo ' <option value="">Todos</option>
                                                                                <option value="1">Ajuste</option>
                                                                                <option value="3" selected> Devoluciones</option>
                                                                                <option value="4"> Ediciones de Stock</option>
                                                                                <option value="5"> Traspasos</option>
                                                                                <option value="6"> Ventas</option>';
                                                                        break;
                                                                    case '4':
                                                                        echo ' <option value="">Todos</option>
                                                                                    <option value="1">Ajuste</option>
                                                                                    <option value="3"> Devoluciones</option>
                                                                                    <option value="5"> Traspasos</option>
                                                                                    <option value="6"> Ventas</option>';
                                                                        break;
                                                                    case '5':
                                                                        echo ' <option value="">Todos</option>
                                                                                        <option value="1">Ajuste</option>
                                                                                        <option value="3"> Devoluciones</option>
                                                                                        <option value="4"> Ediciones de Stock</option>
                                                                                        <option value="5" selected> Traspasos</option>
                                                                                        <option value="6"> Ventas</option>';
                                                                        break;
                                                                        case '6':
                                                                            echo ' <option value="">Todos</option>
                                                                                            <option value="1">Ajuste</option>
                                                                                            <option value="3"> Devoluciones</option>
                                                                                            <option value="4"> Ediciones de Stock</option>
                                                                                            <option value="5"> Traspasos</option>
                                                                                            <option value="6" selected> Ventas</option>';
                                                                            break;
                                                                }
                                                                ?>
                                                          

                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                                            </div>
                                        </div>

                                        </form>
                                        <!-- /.row (nested)SELECT pd.id,	pd.descripcion, des.fechaReg AS fecha, 'Edicion Stock' AS movimiento, '' AS sucMov, des.id AS folio, des.cantOrig AS cantidad,
                                          des.cantFinal, '5' AS cancelada, CONCAT(us.nombre, ' ', us.appat) AS usuario,'' AS urlTicket, suc.nombre AS sucursalVta, '4' AS tipo
                                        FROM productos pd
                                        LEFT JOIN stocks stk ON pd.id = stk.idProducto
                                        INNER JOIN deteditastock des ON stk.id=des.idStock
                                        LEFT JOIN segusuarios us ON des.idUserReg = us.id
                                        LEFT JOIN sucursales suc ON des.idSucReg = suc.id
                                        WHERE pd.id = '$buscaProd'  AND $buscaSuc
                                        
                                        UNION -->
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    
                                    $sql = "SELECT * FROM (
                                        
                                                              
                                        SELECT
                                        pd.id,
                                        pd.descripcion,
                                        aj.fechaAplica AS fecha,
                                        IF
                                        ( da.tipo = '2', 'Ajuste Sal. <i class=\"fa fa-arrow-up fa-fw\"></i>', 'Ajuste Ent. <i class=\"fa fa-arrow-down fa-fw\"></i>' ) AS movimiento,
                                        '' AS sucMov,
                                        aj.id AS folio,
                                        da.cantidad,
                                        da.cantFinal,
                                        0 AS cancelada,
                                        CONCAT( us.nombre, ' ', us.appat ) AS usuario,
                                        CONCAT( 'reimprimeTickets.php?idVenta=', aj.id, '&tipoTicket=lanzaAjuste' ) AS urlTicket,
                                        suc.nombre AS sucursalVta,
                                        '1' AS tipo
                                        FROM
                                          productos pd
                                          LEFT JOIN detajustes da ON pd.id = da.idProducto
                                          INNER JOIN ajustes aj ON da.idAjuste = aj.id
                                          LEFT JOIN segusuarios us ON aj.idUserReg = us.id
                                          LEFT JOIN sucursales suc ON aj.idSucursal = suc.id 
                                        WHERE
                                      pd.id = '$buscaProd' 
                                      AND $buscaSuc
                                        UNION
                  
                                                              
                                        SELECT pd.id, pd.descripcion, dvl.fechaReg AS fecha, 'Devolucion' AS movimiento, '' AS sucMov, dvl.idVenta AS folio, dvl.cantidad,
                                            dvl.cantFinal, '0' AS cancelada, CONCAT(us.nombre, ' ', us.appat) AS usuario,  CONCAT('../imprimeTicketVenta.php?idVenta=', dvl.idVenta) AS urlTicket,
                                           suc.nombre AS sucursalVta,  '3' AS tipo
                                        FROM productos pd
                                        LEFT JOIN detventas dv ON pd.id = dv.idProducto
                                        INNER JOIN devoluciones dvl ON dv.id=dvl.idDetVenta
                                        LEFT JOIN segusuarios us ON dvl.idUserReg = us.id
                                        LEFT JOIN sucursales suc ON dvl.idSucursal = suc.id
                                        WHERE pd.id = '$buscaProd' AND $buscaSuc
                                       
                                       /* UNION
                                        SELECT pd.id, pd.descripcion, cp.fechaReg AS fecha, 'Compra' AS movimiento, '' AS sucMov, cp.id AS folio, dc.cantidad,
                                            dc.cantFinal, 0 AS cancelada, CONCAT(us.nombre, ' ', us.appat) AS usuario, CONCAT('reimprimeTickets.php?idVenta=', cp.id, '&tipoTicket=lanzaTraspaso') AS urlTicket,
                                          suc.nombre AS sucursalVta,  '2' AS tipo
                                        FROM productos pd
                                        LEFT JOIN detcompras dc ON pd.id = dc.idProducto
                                        INNER JOIN compras cp ON dc.idCompra = cp.id
                                        LEFT JOIN segusuarios us ON cp.idUserReg = us.id
                                        LEFT JOIN sucursales suc ON cp.idSucursal = suc.id
                                        WHERE pd.id = '$buscaProd' AND cp.idSucursal = '$sucursal' */
                                        
                                        UNION
                                        SELECT pd.id, pd.descripcion,vt.fechaReg AS fecha,'Venta' AS movimiento, '' AS sucMov, vt.id AS folio, dv.cantidad, dv.cantFinal, '0' AS cancelada,CONCAT(us.nombre,' ',us.appat) AS usuario, 
                                        CONCAT('../imprimeTicketVenta.php?idVenta=', vt.id) AS urlTicket,
                                        suc.nombre AS sucursalVta,  '6' AS tipo
                                        FROM productos pd
                                        LEFT JOIN detventas dv ON pd.id=dv.idProducto
                                        INNER JOIN ventas vt ON dv.idVenta=vt.id
                                        LEFT JOIN segusuarios us ON vt.idUserReg=us.id
                                        LEFT JOIN sucursales suc ON vt.idSucursal=suc.id
                                        WHERE pd.id='$buscaProd' and $buscaSuc
                                         
                                        UNION
                                        
                                                              SELECT pd.id, pd.descripcion,tr.fechaRecepcion AS fecha,'Traspaso Ent. <i class=\"fa fa-arrow-down fa-fw\"></i>' AS movimiento, suce.nombre AS sucMov, tr.id AS folio,
                                        dt.cantRecepcion AS cantidad,dt.cantFinalRec AS cantFinal,'0' AS cancelada, CONCAT(usr.nombre, ' ', usr.appat) AS usuario, CONCAT('../funciones/ticketLanzaTraspaso.php?idTraspaso=', tr.id) AS urlTicket,
                                         suc.nombre AS sucursalVta,  '5' AS tipo
                                        FROM productos pd
                                        LEFT JOIN dettraspasos dt ON pd.id=dt.idProducto
                                        INNER JOIN traspasos tr ON dt.idTraspaso=tr.id
                                        LEFT JOIN sucursales suc ON tr.idSucEntrada=suc.id
                                        LEFT JOIN sucursales suce ON tr.idSucSalida = suce.id
                                        LEFT JOIN segusuarios usr ON tr.idUserRecepcion=usr.id
                                        WHERE pd.id='$buscaProd' AND $buscaSuc 
                                                              
                                        UNION
                                        SELECT pd.id, pd.descripcion, tr.fechaEnvio AS fecha,
                                            'Traspaso Sal. <i class=\"fa fa-arrow-up fa-fw\"></i>' AS movimiento, suce.nombre AS sucMov,
                                            tr.id AS folio, dt.cantEnvio AS cantidad, dt.cantFinalEnv AS cantFinal,'0' AS cancelada,
                                            CONCAT(usr.nombre, ' ', usr.appat) AS usuario, CONCAT('../funciones/ticketLanzaTraspaso.php?idTraspaso=', tr.id) AS urlTicket, suc.nombre AS sucursal,  '5' AS tipo
                                        FROM productos pd
                                        LEFT JOIN dettraspasos dt ON pd.id = dt.idProducto
                                        INNER JOIN traspasos tr ON dt.idTraspaso = tr.id
                                        LEFT JOIN sucursales suc ON tr.idSucSalida = suc.id
                                        LEFT JOIN sucursales suce ON tr.idSucEntrada = suce.id
                                        LEFT JOIN segusuarios usr ON tr.idUserEnvio = usr.id
                                        WHERE pd.id = '$buscaProd' AND $buscaSuc
                  
                                        UNION
                                        SELECT
                                          pd.id,
                                          pd.descripcion,
                                          dv.fechaRegCancel AS fecha,
                                          CONCAT('Venta Cancelada Folio:',vt.id) AS movimiento,
                                          '' AS sucMov,
                                          vt.id AS folio,
                                          dv.cantcancel AS cantidad,
                                          dv.cantFinalCancel AS cantFinal,
                                     
                                          '0' AS cancelada,
                                          CONCAT( us.nombre, ' ', us.appat ) AS usuario,
                                          CONCAT('../imprimeTicketVenta.php?idVenta=', vt.id) AS urlTicket,
                                          suc.nombre AS sucursalVta,  '6' AS tipo
                                        FROM
                                          productos pd
                                          LEFT JOIN detventas dv ON pd.id = dv.idProducto
                                          INNER JOIN ventas vt ON dv.idVenta = vt.id
                                          LEFT JOIN segusuarios us ON vt.idUserReg = us.id
                                          LEFT JOIN sucursales suc ON vt.idSucursal = suc.id 
                                        WHERE pd.id = '$buscaProd' AND $buscaSuc AND vt.estatus='3'
                    ) movimientosProd WHERE $filtroFechas ORDER BY fecha DESC";
                                    $result = mysqli_query($link, $sql) or die('Problemas al consultar movimientos del Producto.' . mysqli_error($link));
                                   // echo "tipo:-->" . $buscaMov;
                                 //   echo $sql;        
                                    ?>

                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="zero_config" class="table display table-bordered table-striped no-wrap">
                                                <thead>
                                                    <th>#</th>
                                                    <th>Fecha</th>
                                                    <th>Movimiento</th>
                                                    <th>Sucursal</th>
                                                    <th>SucursalMov.</th>
                                                    <th>Folio</th>
                                                    <th>Cantidad</th>
                                                    <th>Final</th>
                                                    <th>Usuario</th>
                                                    <th>Ticket</th>
                                                </thead>

                                                <tbody>

                                                    <?php
                                                    $cont = 0;
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        // $cancel = ($row['cancelada'] == 1) ? 'class="table-danger"' : '';
                                                        //$sucursal=$row['sucMov']==''?$row['sucursalVta']:$row['sucMov'];
                                                        $sucursal=$row['sucursalVta'];
                                                        $cancel = "";
                                                        $cancel = ($row['cancelada'] == 5) ? 'class="table-info"' : $cancel;
                                                        //$cancel = ($row['cancelada'] == 10) ? 'class="table-warning"' : $cancel;

                                                        $cont++;
                                                        if ($buscaMov != '') {
                                                            if ($row['tipo'] == $buscaMov) {
                                                                echo '
                                      <tr ' . $cancel . '>
                                          <td>' . $cont . '</td>
                                          <td>' . $row['fecha'] . '</td>
                                          <td>' . $row['movimiento'] . '</td>
                                          <td>' . $sucursal . '</td>
                                          <td>' . $row['sucMov'] . '</td>

                                          <td>' . $row['folio'] . '</td>
                                          <td>' . $row['cantidad'] . '</td>
                                          <td>' . $row['cantFinal'] . '</td>
                                          <td>' . $row['usuario'] . '</td>';
                                                                if ($row['urlTicket'] != '') {
                                                                    echo '
                                    <td><center> <a class="btn btn-circle btn-circle-sm2 p-0 btn-success" 
                                    href="' . $row['urlTicket'] . '" target="_blank"><i class=" fas fa-sticky-note"></i></a></center> </td>
                                </tr>
                            ';
                                                                } else {
                                                                    echo '<td></td>';
                                                                }
                                                            }
                                                        } else {
                                                            echo '
                                      <tr ' . $cancel . '>
                                          <td>' . $cont . '</td>
                                          <td>' . $row['fecha'] . '</td>
                                          <td>' . $row['movimiento'] . '</td>
                                          <td>' . $sucursal . '</td>
                                          <td>' . $row['sucMov'] . '</td>
                                          <td>' . $row['folio'] . '</td>
                                          <td>' . $row['cantidad'] . '</td>
                                          <td>' . $row['cantFinal'] . '</td>
                                          <td>' . $row['usuario'] . '</td>';
                                                            if ($row['urlTicket'] != '') {
                                                                echo '
                                    <td><center> <a class="btn btn-circle btn-circle-sm2 p-0 btn-success" 
                                    href="' . $row['urlTicket'] . '" target="_blank"><i class=" fas fa-sticky-note"></i></a></center> </td>
                                </tr>
                            ';
                                                            } else {
                                                                echo '<td></td>';
                                                            }
                                                        }
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

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
            if (isset($_SESSION['LZmsjInfoAltaSeguimiento'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoAltaSeguimiento'] . "');";
                unset($_SESSION['LZmsjInfoAltaSeguimiento']);
            }
            if (isset($_SESSION['LZmsjSuccessSeguimiento'])) {
                echo "notificaSuc('" . $_SESSION['LZmsjSuccessSeguimiento'] . "');";
                unset($_SESSION['LZmsjSuccessSeguimiento']);
            }
            ?>
        }); // Cierre de document ready

        $("#zero_config").DataTable({"order": [
            [2, 'desc']
        ]});

        function ejecutandoCarga(identif) {
            var selector = 'DIV' + identif;
            var finicio = $('#fStart').val();
            var ffin = $('#fEnd').val();

            $.post("../funciones/cargaContenidoCardexAdmin.php", {
                    ident: identif
                },
                function(respuesta) {
                    $("#" + selector).html(respuesta);
                });

        }



        function limpiaCadena(dat, id) {
            //alert(id);
            dat = getCadenaLimpia(dat);
            $("#" + id).val(dat);
        }



        function sigueProducto(ident, seguimiento) {
            if (seguimiento == 1) {
                var value = '0';
            } else {
                var value = '1';
            }
            $('<form action="../funciones/sigueProducto.php" method="POST"><input type="hidden" name="value" value="' + value + '"><input type="hidden" name="ident" value="' + ident + '"></form>').appendTo('body').submit();

        }

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