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
                <h4 class="m-b-0 text-white">Historial de Productos</h4>
              </div>
              <div class="card-body">
              <div class="text-right">
                <a class="btn btn-circle bg-<?=$pyme?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Traspasos" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="traspasos.php">
                          <i class="fas fa-reply"></i></a>
                </div>
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">


                  <?php

                  $fechaAct = date('d-m-Y');
                  if (isset($_POST['fechaInicial'])) {
                    $fechaInicial = $_POST['fechaInicial'];
                    $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                  /*  $filtroFechas = "stk.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";*/
                  } else {
                    $fechaInicial = "";
                    $filtroFechas = "";
                  }
                  if (isset($_POST['fechaFinal'])) {
                    $fechaFinal = $_POST['fechaFinal'];
                    $filtroFechas = "stk.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                    $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                   /* $filtroFechas = "stk.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23
  :59:59' ";*/
                  } else {
                    $fechaFinal = "";
                    $filtroFechas = "";
                  }

                  if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                    $buscaSuc = $_POST['buscaSuc'];
                   // $filtroSuc = "gstos.idSucursal=" . $_POST['buscaSuc'];
                  } else {
                    $filtroSuc = '';
                   // $buscaSuc = '';
                  }
                  if (isset($_POST['buscaTipo']) and $_POST['buscaTipo'] >= 0) {
                    $buscaTipo = $_POST['buscaTipo'];
                   // $filtroSuc = "gstos.idSucursal=" . $_POST['buscaSuc'];
                  } else {
                  //  $filtroSuc = '';
                    $buscaTipo = '';
                  }
                  $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                  $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaSuc = '';
                  while ($datos = mysqli_fetch_array($resSuc)) {
                    $activeSuc = ($datos['id'] == $buscaSuc) ? 'selected' : '';
                    $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                  }
                /*FILTRO FINAL*/
                if($buscaTipo!=''){

                    switch ($buscaTipo) {
                        case '0':
                           if($formFI!='' AND $formFF!=''){
                            $filtroFechas1="tras.fechaEnvio BETWEEN '$formFI 00:00:00' AND '$formFF 23
                            :59:59'";
                            $filtroFechas2="tras.fechaRecepcion BETWEEN '$formFI 00:00:00' AND '$formFF 23
                            :59:59'";
                           }
                           else{
                               $filtroFechas1='1=1';
                               $filtroFechas2='1=1';
                           }
                           if($buscaSuc!=''){
                                $filtroSuc1="tras.idSucSalida= '$buscaSuc'";
                               $filtroSuc2="tras.idSucEntrada= '$buscaSuc'";
                           }
                           else{
                               $filtroSuc1='1=1';
                               $filtroSuc2='1=1';
                           }
                           $filtroTipo="1=1";
                            break;
                        case '1':
                            if($formFI!='' AND $formFF!=''){
                                $filtroFechas1="tras.fechaEnvio BETWEEN '$formFI 00:00:00' AND '$formFF 23
                                :59:59'";
                                $filtroFechas2="tras.fechaRecepcion BETWEEN '$formFI 00:00:00' AND '$formFF 23
                                :59:59'";
                               }
                               else{
                                   $filtroFechas1='1=1';
                                   $filtroFechas2='1=1';
                               }
                               if($buscaSuc!=''){
                                    $filtroSuc1="tras.idSucSalida= '$buscaSuc'";
                                   $filtroSuc2="tras.idSucEntrada= '$buscaSuc'";
                               }
                               else{
                                   $filtroSuc1='1=1';
                                   $filtroSuc2='1=1';
                               }
                               $filtroTipo="idSucSalida=".$_SESSION['LZFidSuc'];
                            break;
                        case '2':
                            if($formFI!='' AND $formFF!=''){
                                $filtroFechas1="tras.fechaEnvio BETWEEN '$formFI 00:00:00' AND '$formFF 23
                                :59:59'";
                                $filtroFechas2="tras.fechaRecepcion BETWEEN '$formFI 00:00:00' AND '$formFF 23
                                :59:59'";
                               }
                               else{
                                   $filtroFechas1='1=1';
                                   $filtroFechas2='1=1';
                               }
                               if($buscaSuc!=''){
                                    $filtroSuc1="tras.idSucSalida= '$buscaSuc'";
                                   $filtroSuc2="tras.idSucEntrada= '$buscaSuc'";
                               }
                               else{
                                   $filtroSuc1='1=1';
                                   $filtroSuc2='1=1';
                               }
                               $filtroTipo="idSucEntrada=".$_SESSION['LZFidSuc'];
                            break;
                        default:

                            break;
                    }
                } else{
                    $filtroFechas1="1=1";
                    $filtroFechas2="1=1";
                    $filtroSuc1="1=1";
                    $filtroSuc2="1=1";
                    $filtroTipo="1=1";
                }


                  ?>
                  <div class="border p-3 mb-3">
                    <h4><i class="fas fa-filter"></i> Filtrado</h4>

                    <div class="row">
                      <form method="post" action="historialtraspasos.php">

                        <div class="col-6">

                        </div>
                        <div class="col-6">
                        </div>
                    </div>


                    <!--/span-->

                    <div class="row">

                      <form method="post" action="historialtraspasos.php">
                      <div class="col-md-2">
                            <div class="form-group">
                              <label for="rangeBa1" class="control-label col-form-label">Tipo de Traspaso</label>

                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="finVig1"><i class="mdi mdi-store"></i></span>
                                </div>
                                <select class="form-control custom-select" name="buscaTipo" id="buscaTipo" onchange="" style="width: 80%;">
                                <?php
                                switch ($buscaTipo) {
                                  case '0':
                                    echo '
                                    <option value="0" selected>Todos</option>
                                    <option value="1">Envío</option>
                                    <option value="2">Recepción</option>';
                                    break;
                                    case '1':
                                      echo '
                                      <option value="0">Todos</option>
                                      <option value="1"  selected>Envío</option>
                                      <option value="2">Recepción</option>';
                                      break;
                                    case '2':
                                        echo '
                                        <option value="0">Todos</option>
                                        <option value="1"  >Envío</option>
                                        <option value="2" selected>Recepción</option>';
                                        break;
                                    default:
                                    echo '
                                    <option value="0">Todos</option>
                                    <option value="1">Envío</option>
                                    <option value="2">Recepción</option>';
                                    break;


                                }




                                ?>


                               </select>
                              </div>

                            </div>

                          </div>
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>

                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                    <input class="datepicker form-control" type="text" value="<?=$fechaInicial?>" id="rangeBa1" name="fechaInicial" />

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
                                    <input class="datepicker form-control" type="text" value="<?=$fechaFinal?>" id="rangeBa2" name="fechaFinal" />

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
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


                    <div class="col-md-2 pt-4 mt-2">
                        <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                      </div>
                      </form>
                      <!-- /.row (nested) -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="table-responsive">
                        <table id="table-traspaso" class="table display no-wrap">
                          <thead>
                            <th>#</th>
                            <th>Ticket</th>
                            <th>Sucursal Envia</th>
                            <th>Usuario Envia</th>
                            <th>Fecha Envia</th>
                            <th>Sucursal Recepcion</th>
                            <th>Estatus Recepción</th>
                            <th>Usuario Recepción</th>
                            <th>Fecha Recepción</th>
                            <th>Estatus Bodeguero</th>
                            <th>Imprimir</th>
                          </thead>

                          <tbody>
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

        <div class="modal fade" id="verIMG" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verIMGContent">


                <h4 class="modal-title" id="verIMGTitle"> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

              </div>
              <div class="modal-body" id="verIMGBody">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php 
        $sqlProductos="SELECT
id,
sucSalida,
sucEntrada,
fechaEnvio,
IF
( fechaRec = 0, 'No asignada', fechaRec ) AS fechaRec,
usuarioEnvio,
IF
( usuarioRec IS NULL, 'No asignado', usuarioRec ) AS usuarioRec,
idSucEntrada,
idSucSalida,
estatusRec,
estatusBodeguero 
FROM
((
  SELECT
    tras.id,
    suc1.nombre AS sucSalida,
    suc2.nombre AS sucEntrada,
    DATE_FORMAT( tras.fechaEnvio, '%d/%m/%Y %H:%i:%s' ) AS fechaEnvio,
    DATE_FORMAT( tras.fechaRecepcion, '%d/%m/%Y %H:%i:%s' ) AS fechaRec,
    CONCAT( USER.nombre, ' ', USER.appat, ' ', USER.apmat ) AS usuarioEnvio,
    CONCAT( user2.nombre, ' ', user2.appat, ' ', user2.apmat ) AS usuarioRec,
    tras.idSucEntrada,
    tras.idSucSalida,
    tras.estatus AS estatusRec,
    tras.estatusBodega  AS estatusBodeguero 
  FROM
    traspasos tras
    INNER JOIN sucursales suc1 ON tras.idSucSalida = suc1.id
    INNER JOIN sucursales suc2 ON tras.idSucEntrada = suc2.id
    INNER JOIN segusuarios USER ON tras.idUserEnvio = USER.id
    LEFT JOIN segusuarios user2 ON tras.idUserRecepcion = user2.id 
  WHERE
    tras.idSucEntrada = ". $_SESSION['LZFidSuc']."
    AND ( tras.estatus = '3' OR tras.estatus = '2' ) 
    AND 1 = 1 
    AND 1 = 1 
  ORDER BY
    fechaEnvio ASC 
    ) UNION
  (
  SELECT
    tras.id,
    suc1.nombre AS sucSalida,
    suc2.nombre AS sucEntrada,
    DATE_FORMAT( tras.fechaEnvio, '%d/%m/%Y %H:%i:%s' ) AS fechaEnvio,
    DATE_FORMAT( tras.fechaRecepcion, '%d/%m/%Y %H:%i:%s' ) AS fechaRec,
    CONCAT( USER.nombre, ' ', USER.appat, ' ', USER.apmat ) AS usuarioEnvio,
    CONCAT( user2.nombre, ' ', user2.appat, ' ', user2.apmat ) AS usuarioRec,
    tras.idSucEntrada,
    tras.idSucSalida,
    tras.estatus AS estatusRec,
    tras.estatusBodega  AS estatusBodeguero 
  FROM
    traspasos tras
    INNER JOIN sucursales suc1 ON tras.idSucSalida = suc1.id
    INNER JOIN sucursales suc2 ON tras.idSucEntrada = suc2.id
    INNER JOIN segusuarios USER ON tras.idUserEnvio = USER.id
    LEFT JOIN segusuarios user2 ON tras.idUserRecepcion = user2.id 
  WHERE
    tras.idSucSalida = ". $_SESSION['LZFidSuc']."
    AND ( tras.estatus = '3' OR tras.estatus = '2' ) 
    AND 1 = 1 
    AND 1 = 1 
  ORDER BY
    fechaEnvio ASC 
  )) traspasos
  WHERE $filtroTipo
";
//echo $sqlProductos;
?>

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
  <script src="../assets/tablasZafra/datatable_configHis.js"></script>

  <script>
    $('.datepicker').datepicker({
      language: 'es',
      format: 'dd-mm-yyyy',
    });
    $('#demo-foo-row-toggler').footable({
      "toggleColumn": "last",

    });
    <?php
    //QUERY DE DETALLADO DE PRODUCTO
    if ($filtroFechas == '' && $filtroSuc == '') {
      $stringWhere = '';
    } else if ($filtroFechas == '') {
      $stringWhere = 'AND ' . $filtroSuc;
    } else if ($filtroFechas != '' && $filtroSuc != '') {
      $stringWhere = 'AND ' . $filtroFechas . ' AND ' . $filtroSuc;
    } else if ($filtroSuc == '') {
      $stringWhere = 'AND ' . $filtroFechas;
    }

 /*$sqlProductos = "SELECT id, sucSalida,sucEntrada, fechaEnvio, IF(fechaRec =0,'No asignada',fechaRec) AS fechaRec, usuarioEnvio, IF(usuarioRec IS NULL,'No asignado',usuarioRec) AS usuarioRec, idSucEntrada, idSucSalida, 	estatusRec,
 estatusBodeguero FROM
 ((SELECT tras.id, suc1.nombre as sucSalida, suc2.nombre as sucEntrada, DATE_FORMAT(tras.fechaEnvio,'%d/%m/%Y %H:%i:%s') AS fechaEnvio, DATE_FORMAT(tras.fechaRecepcion,'%d/%m/%Y %H:%i:%s') AS fechaRec, CONCAT(user.nombre,' ',user.appat,' ',user.apmat) AS usuarioEnvio,
 CONCAT(user2.nombre,' ',user2.appat,' ',user2.apmat) AS usuarioRec, tras.idSucEntrada, tras.idSucSalida,
 IF (tras.estatus='3', '<i class=\"fas fa-check text-center text-success\"></i>', '<i class=\"fas fa-clock text-center text-danger\"></i> ') AS estatusRec,
IF (tras.estatusBodega='2', '<i class=\"fas fa-check text-center text-success\"></i>', '<i class=\"fas fa-clock text-center  text-danger\"></i> ') AS estatusBodeguero
  FROM traspasos tras
  INNER JOIN sucursales suc1 ON tras.idSucSalida=suc1.id
  INNER JOIN sucursales suc2 ON tras.idSucEntrada=suc2.id
  INNER JOIN segusuarios user ON tras.idUserEnvio=user.id
 LEFT JOIN segusuarios user2 ON tras.idUserRecepcion=user2.id
 WHERE tras.idSucEntrada=  ". $_SESSION['LZFidSuc']." AND (  tras.estatus = '3' OR tras.estatus = '2'  )  AND $filtroFechas1 AND $filtroSuc1 ORDER BY fechaEnvio ASC)
UNION
(SELECT tras.id, suc1.nombre as sucSalida, suc2.nombre as sucEntrada, DATE_FORMAT(tras.fechaEnvio,'%d/%m/%Y %H:%i:%s') AS fechaEnvio, DATE_FORMAT(tras.fechaRecepcion,'%d/%m/%Y %H:%i:%s') AS fechaRec, CONCAT(user.nombre,' ',user.appat,' ',user.apmat) AS usuarioEnvio,
CONCAT(user2.nombre,' ',user2.appat,' ',user2.apmat) AS usuarioRec, tras.idSucEntrada, tras.idSucSalida,
IF (tras.estatus='3', '<i class=\"fas fa-check text-center  text-success\"></i>', '<i class=\"fas fa-clock textcenter  text-danger\"></i> ') AS estatusRec,
IF (tras.estatusBodega='2', '<i class=\"fas fa-check text-center text-success\"></i>', '<i class=\"fas fa-clock text-center text-danger\"></i> ') AS estatusBodeguero
 FROM traspasos tras
 INNER JOIN sucursales suc1 ON tras.idSucSalida=suc1.id
 INNER JOIN sucursales suc2 ON tras.idSucEntrada=suc2.id
 INNER JOIN segusuarios user ON tras.idUserEnvio=user.id
LEFT JOIN segusuarios user2 ON tras.idUserRecepcion=user2.id
WHERE tras.idSucSalida= ". $_SESSION['LZFidSuc']." AND (  tras.estatus = '3' OR tras.estatus = '2'  )  AND $filtroFechas1 AND $filtroSuc1 ORDER BY fechaEnvio ASC)) traspasos WHERE $filtroTipo";*/
$sqlProductos="SELECT
id,
sucSalida,
sucEntrada,
fechaEnvio,
IF
( fechaRec = 0, 'No asignada', fechaRec ) AS fechaRec,
usuarioEnvio,
IF
( usuarioRec IS NULL, 'No asignado', usuarioRec ) AS usuarioRec,
idSucEntrada,
idSucSalida,
estatusRec,
estatusBodeguero 
FROM
((
SELECT
tras.id,
suc1.nombre AS sucSalida,
suc2.nombre AS sucEntrada,
DATE_FORMAT( tras.fechaEnvio, '%d/%m/%Y %H:%i:%s' ) AS fechaEnvio,
DATE_FORMAT( tras.fechaRecepcion, '%d/%m/%Y %H:%i:%s' ) AS fechaRec,
CONCAT( USER.nombre, ' ', USER.appat, ' ', USER.apmat ) AS usuarioEnvio,
CONCAT( user2.nombre, ' ', user2.appat, ' ', user2.apmat ) AS usuarioRec,
tras.idSucEntrada,
tras.idSucSalida,
tras.estatus AS estatusRec,
tras.estatusBodega  AS estatusBodeguero 
FROM
traspasos tras
INNER JOIN sucursales suc1 ON tras.idSucSalida = suc1.id
INNER JOIN sucursales suc2 ON tras.idSucEntrada = suc2.id
INNER JOIN segusuarios USER ON tras.idUserEnvio = USER.id
LEFT JOIN segusuarios user2 ON tras.idUserRecepcion = user2.id 
WHERE
tras.idSucEntrada = ". $_SESSION['LZFidSuc']."
AND ( tras.estatus = '3' OR tras.estatus = '2' ) 
AND 1 = 1 
AND 1 = 1 
ORDER BY
fechaEnvio ASC 
) UNION
(
SELECT
tras.id,
suc1.nombre AS sucSalida,
suc2.nombre AS sucEntrada,
DATE_FORMAT( tras.fechaEnvio, '%d/%m/%Y %H:%i:%s' ) AS fechaEnvio,
DATE_FORMAT( tras.fechaRecepcion, '%d/%m/%Y %H:%i:%s' ) AS fechaRec,
CONCAT( USER.nombre, ' ', USER.appat, ' ', USER.apmat ) AS usuarioEnvio,
CONCAT( user2.nombre, ' ', user2.appat, ' ', user2.apmat ) AS usuarioRec,
tras.idSucEntrada,
tras.idSucSalida,
tras.estatus AS estatusRec,
tras.estatusBodega  AS estatusBodeguero 
FROM
traspasos tras
INNER JOIN sucursales suc1 ON tras.idSucSalida = suc1.id
INNER JOIN sucursales suc2 ON tras.idSucEntrada = suc2.id
INNER JOIN segusuarios USER ON tras.idUserEnvio = USER.id
LEFT JOIN segusuarios user2 ON tras.idUserRecepcion = user2.id 
WHERE
tras.idSucSalida = ". $_SESSION['LZFidSuc']."
AND ( tras.estatus = '3' OR tras.estatus = '2' ) 
AND 1 = 1 
AND 1 = 1 
ORDER BY
fechaEnvio ASC 
)) traspasos
WHERE $filtroTipo
";

//echo "console.log(\"$sqlProductos\");";
    $resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador'.mysqli_error($link));
    $arreglo['data'] = array();

    while ($data = mysqli_fetch_array($resPro)) {
      $arreglo['data'][] = $data;
    }
    $var = json_encode($arreglo);
    mysqli_free_result($resPro);

    echo 'var datsJson = ' . $var . ';';
    echo 'var pyme = "' . $pyme . '";';



    ?>
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

        function muestraTicket(ident){
          $('<form action="../funciones/ticketLanzaTraspaso.php" method="POST"><input type="hidden" name="idTraspaso" value="'+ident+'"></form>').appendTo('body').submit();
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
