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
                <h4 class="m-b-0 text-white">Listado de Ventas por Producto</h4>
              </div>
              <div class="card-body">

                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">


                  <?php
                  $buscaTipo = "1";
                  //   echo "busca tipo -->".$buscaTipo;
                  $fechaAct = date('d-m-Y');
                  if (isset($_POST['fechaInicial'])) {
                    $fechaInicial = $_POST['fechaInicial'];
                    $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                    $filtroFechas = "vnta.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaInicial = "";
                    $filtroFechas = "1=1";
                  }
                  if (isset($_POST['fechaFinal'])) {
                    $fechaFinal = $_POST['fechaFinal'];
                    $filtroFechas = "vnta.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                    $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                    $filtroFechas = "vnta.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaFinal = "";
                    $filtroFechas = "1=1";
                  }



                  if (isset($_POST['buscaDepto']) and $_POST['buscaDepto'] >= 1) {
                    $buscaDepto = $_POST['buscaDepto'];
                    $filtroDepto = "dpto.id=" . $_POST['buscaDepto'];
                  } else {
                    $filtroDepto = '';
                    $buscaDepto = '';
                  }



                  $sql = "SELECT * FROM departamentos WHERE estatus=1 ORDER BY nombre";
                  $resDepto = mysqli_query($link, $sql) or die("Problemas al enlistar Departamentos.");

                  $listaDepto = '';
                  while ($datos = mysqli_fetch_array($resDepto)) {
                    $activeDepto = ($datos['id'] == $buscaDepto) ? 'selected' : '';
                    $listaDepto .= '<option value="' . $datos['id'] . '" ' . $activeDepto . '>' . $datos['nombre'] . '</option>';
                  }



                  if (isset($_POST['buscaTipo'])) {
                    $buscaTipo = $_POST['buscaTipo'];
                  } else {
                    $buscaTipo = "0";
                  }

                  $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                  $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaSuc = '';
                  while ($datos = mysqli_fetch_array($resSuc)) {
                    $activeSuc = ($datos['id'] == $buscaTipo) ? 'selected' : '';
                    $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                  }

                  ?>
                  <div class="border p-3 mb-3">
                    <h4><i class="fas fa-filter"></i> Filtrado</h4>

                    <div class="row">
                      <form method="post" action="seguimientoProductos.php">

                        <div class="col-6">

                        </div>
                        <div class="col-6">

                        </div>
                    </div>


                    <!--/span-->

                    <div class="row">

                      <form method="post" action="seguimientoProductos.php">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>

                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <input class="form-control" autocomplete="off" type="date" value="<?= $fechaInicial ?>" id="rangeBa1" name="fechaInicial" />

                                  </div>

                                </div>

                              </div>

                            </div>
                            <div class="col-md-6">

                              <div class="form-group">
                                <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>

                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <input class="form-control" autocomplete="off" type="date" value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Búsqueda por departamento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-domain"></i></span>
                              </div>
                              <select class="select2 form-control custom-select" name="buscaDepto" id="buscaDepto" onchange="" style="width: 80%;">

                                <option value=""> Todos los Departamentos</option>
                                <?= $listaDepto ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Tipo de Seguimiento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="fas fa-chart-line"></i></span>
                              </div>
                              <select class="select2 form-control custom-select" name="buscaTipo" id="buscaTipo" onchange="" style="width: 80%;">

                                <option value="0">Global</option>
                                <?= $listaSuc ?>

                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-2 mt-4 pt-1">
                          <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                        </div>
                      </form>
                      <!-- /.row (nested) -->
                    </div>
                  </div>



                </div>


                <br>

                <div class="table-responsive">
                  <?php  //echo "busca tipo -->".$buscaTipo;
                  if ($buscaTipo == "0") {

                  ?>

                    <table id="tablaimpresion" class="table table-bordered footer_callback" data-toggle-column="first">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Producto</th>
                          <th>Departamento</th>
                          <?php
                          $sqlSuc = "SELECT nameFact AS nombre, id FROM sucursales ORDER BY id";
                          $ressuc = mysqli_query($link, $sqlSuc) or die('Problemas al listar los Sucursales1, notifica a tu Administrador');
                          $numSucursales = mysqli_num_rows($ressuc);
                          while ($suc = mysqli_fetch_array($ressuc)) {
                            echo '<th data-breakpoints="all" data-title="' . $suc['nombre'] . '">' . $suc['nombre'] . '</th>';
                          }


                          ?>

                          <th>Total de Ventas</th>
                          <th>Stock Global</th>


                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        $arrayTotales = array();
                        //QUERY DE DETALLADO DE PRODUCTO
                        if ($filtroFechas == '' && $filtroDepto == '') {
                          $stringWhere = '';
                        } else if ($filtroFechas == '') {
                          $stringWhere = 'WHERE ' . $filtroDepto;
                        } else if ($filtroFechas != '' && $filtroDepto != '') {
                          $stringWhere = 'WHERE ' . $filtroFechas . ' AND ' . $filtroDepto;
                        } else if ($filtroDepto == '') {
                          $stringWhere = 'WHERE ' . $filtroFechas;
                        }
                        //  echo "ES EL FILTRO:".$stringWhere;

                        $sqlProductos = "SELECT
                    pro.id,
                    pro.codBarra,
                    pro.descripcion,
                    dpto.nombre AS dpto,
                    pro.estatus,
                  IF
                    ( totstk.totalStock IS NULL, 0.0, totstk.totalStock ) AS  cantGlobalStk,
                  IF
                    ( vnta.cantVenta IS NULL, 0.0, vnta.cantVenta ) AS vtastotal 
                  FROM
                    productos pro
                    INNER JOIN departamentos dpto ON pro.idDepartamento = dpto.id
                    INNER JOIN (
                    SELECT
                      stk.idProducto,
                      SUM( stk.cantActual ) AS totalStock 
                    FROM
                      stocks stk
                      LEFT JOIN productos pro ON pro.id = stk.idProducto 
                    GROUP BY
                      pro.id 
                    ) totstk ON pro.id = totstk.idProducto
                    
                  LEFT JOIN (
                    SELECT vt.id AS idVenta, vt.fechaReg,
                      SUM( dv.cantidad ) AS cantVenta,
                      pro.id AS idProducto 
                    FROM
                      detventas dv
                      INNER JOIN ventas vt ON dv.idVenta = vt.id
                      INNER JOIN productos pro ON pro.id = dv.idProducto 
                      AND vt.estatus = 2
                      GROUP BY pro.id
                    ) vnta ON vnta.idProducto = pro.id
                    
                  LEFT JOIN detventas dv ON dv.idVenta = vnta.idVenta
                  $stringWhere
                    AND pro.seguimiento = 1 
                  GROUP BY
                    pro.id 
                  ORDER BY
                    vnta.cantVenta DESC";
                     // echo 'Productos --->'.$sqlProductos."<br>";
                        $resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador' . mysqli_error($link));
                        //OBTENER LA QUERY DE LAS SUCURSALES
                        $sqlQuery = "SELECT
                        GROUP_CONCAT(DISTINCT 
                        CONCAT(
                        'SUM(CASE WHEN idSucursal = \"',
                        scs.id,
                        '\"  THEN cantVtas END) AS ','Suc',
                        scs.nameFact 
                        )ORDER BY scs.id) AS query FROM sucursales scs";
                       // echo "<br>".$sqlQuery;
                        $resquery = mysqli_query($link, $sqlQuery) or die($error = 'Problemas al listar los Sucursales, notifica a tu Administrador' . mysqli_error($link));
                        $Query = mysqli_fetch_array($resquery);
                        $sqlSuc = "SELECT nombre FROM sucursales ORDER BY id ASC";
                        $ressuc = mysqli_query($link, $sqlSuc) or die('Problemas al listar los Sucursales1, notifica a tu Administrador');
                        //echo $Query['query'];
                        $iteracion = 1;
                        $cant = mysqli_num_rows($respro);

                        $totales = 0;


                        while ($pro = mysqli_fetch_array($resPro)) {

                          //QUERY DE DETALLADO DE SUCURSAL
                          $sqlDetallado = 'SELECT ' . $Query['query'] . ' FROM (SELECT SUM(detvtas.cantidad) AS cantVtas, vtas.idSucursal 
                          FROM detventas detvtas INNER JOIN ventas vtas ON detvtas.idVenta= vtas.id INNER JOIN productos pdc ON detvtas.idProducto = pdc.id INNER JOIN sucursales scs 
                          ON vtas.idSucursal = scs.id WHERE detvtas.idProducto=' . $pro['id'] . ' AND vtas.estatus=2
                          GROUP BY vtas.idSucursal ORDER BY scs.id) agrupVTAS';
                          // echo $sqlDetallado.'</br></br>';
                          $resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los Sucursales2, notifica a tu Administrador' . mysqli_error($link));

                          $disabled = ($pro['stock'] != 0) ? 'disabled' : '';

                          $estatus = ($pro['estatus'] == 1) ? '<center class="text-success"  data-toggle="tooltip" data-placement="top"
														title="" data-original-title="Activo"><button class="btn-circle"  type="button" ' . $disabled . ' style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="estatusPro(' . $pro['id'] . ', 0);"
														class="btn ink-reaction btn-icon-toggle"><i class="fas fa-check text-success"></i></button></center>' :
                            '<center class="text-danger"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Desactivado">
														 <button type="button" class="btn-circle"  style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="estatusPro(' . $pro['id'] . ', 1);"
														class="btn ink-reaction btn-icon-toggle"><i class="fas fa-times text-danger"></i></button></center>';

                          $estatusText = ($pro['vtastotal'] == 0) ? 'class="text-danger table-danger"' : '';
                          echo '<tr ' . $estatusText . '>
                                          <td class="text-center">' . $iteracion . '</td>
                                          <td id="descPro-' . $iteracion . '">' . $pro['descripcion'] . '</td>
                                          <td id="descPro-' . $iteracion . '">' . $pro['dpto'] . '</td>
                                         ';
                          $resArray = mysqli_fetch_array($resdet);
                          $totales = $totales + $pro['vtastotal'];
                          $total_row = 0;
                          for ($i = 0; $i <= (count($resArray) / 2) - 1; $i++) {

                            if ($resArray[$i] != '') {

                              if (empty($arrayTotales)) {
                                array_push($arrayTotales, $resArray[$i]);
                              } else {
                                $arrayTotales[$i] = $arrayTotales[$i] + $resArray[$i];
                              }
                              echo '<td id="detallado-' . $i . '" >' . $resArray[$i] . '</td>';
                              $total_row = $total_row + $resArray[$i];
                            } else {
                              if (empty($arrayTotales)) {
                                array_push($arrayTotales, $resArray[$i]);
                              } else {
                                $arrayTotales[$i] = $arrayTotales[$i] + $resArray[$i];
                              }
                              echo '<td class="text-danger table-danger" id="detallado-' . $i . '">0</td>';
                            }
                          }
                          echo '  <td id="vtaPro-' . $iteracion . '">' . $pro['vtastotal'] . '</td>
                      ';

                          echo '  <td id="totalPro-' . $iteracion . '">' . $pro['cantGlobalStk'] . '</td>';




                          $iteracion++;
                          $arrayId = array_merge($arrayId, [$pro['id']]);
                        }


                        ?>


                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="2" style="text-align:right">Totales:</th>
                          <?php
                          for ($i = 0; $i <= $numSucursales + 1; $i++) {
                            echo ' <th></th>';
                          }
                          ?>

                        </tr>
                      </tfoot>
                    </table>
                  <?php } else { ?>

                    <table id="tablaimpresion" class="table table-bordered footer_callback" data-toggle-column="first">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Producto</th>
                          <th>Total de Ventas</th>
                          <th> Total Stock </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sqlxSuc = "SELECT
                      pro.id,
                      pro.descripcion,
                    IF
                      ( vnta.cantVenta IS NULL, 0.0, vnta.cantVenta ) AS totalVentas,
                    IF
                      ( totstk.totalStock IS NULL, 0.0, totstk.totalStock ) AS totalStock 
                    FROM
                      productos pro
                      INNER JOIN (
                      SELECT stk.idProducto, SUM(stk.cantActual) AS totalStock  FROM stocks stk
                      LEFT JOIN productos pro ON pro.id=stk.idProducto WHERE stk.idSucursal = '$buscaTipo' GROUP BY pro.id
                      )
                      totstk ON pro.id = totstk.idProducto 
                      
                      LEFT JOIN (
                      SELECT vt.id AS idVenta,
                        SUM( dv.cantidad ) AS cantVenta,
                        pro.id AS idProducto 
                      FROM
                        detventas dv
                        INNER JOIN ventas vt ON dv.idVenta = vt.id
                        INNER JOIN productos pro ON pro.id = dv.idProducto 
                        AND vt.idSucursal = '$buscaTipo' 
                        AND vt.estatus = 2
                        GROUP BY pro.id
                      ) vnta ON vnta.idProducto = pro.id
                      
                    LEFT JOIN detventas dv ON dv.idVenta = vnta.idVenta
                    
                    WHERE
                      pro.seguimiento = 1 
                    
                    ORDER BY
                      pro.descripcion";
                        //echo $sqlxSuc;
                        $ressuc = mysqli_query($link, $sqlxSuc) or die('Problemas al listar los productos por sucursal, notifica a tu Administrador');
                        $iter = 1;

                        //$classdanger=($sucxseg['totalVentas']=='0.00') ?  "": 'table-danger';
                        while ($sucxseg = mysqli_fetch_array($ressuc)) {

                          if ($sucxseg['totalVentas'] <= 0) {
                            $classdanger = 'table-danger';
                          } else {
                            $classdanger = '';
                          }
                          echo '<tr class="' . $classdanger . '">';
                          echo '<td >' . $iter . '</td>';

                          echo '<td >' . $sucxseg['descripcion'] . '</td>';
                          echo '<td >' . $sucxseg['totalVentas'] . '</td>';
                          echo '<td >' . $sucxseg['totalStock'] . '</td>';
                          echo '</tr>';
                          $iter++;
                        }


                        ?>
                      </tbody>
                    </table>

                  <?php } ?>

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
  <script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- dataTable js -->
  <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min-ESP.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
  <script>
    $(document).ready(function() {






      $('.datepicker').datepicker({
        language: 'es',
        format: 'dd-mm-yyyy',
      });
      $('#demo-foo-row-toggler').footable({
        "toggleColumn": "last",

      });
      $('.footer_callback').DataTable({
        responsive: false,
				dom: 'B<"clear">lfrtip',
				fixedColumns: true,
				fixedHeader: true,
				scrollCollapse: true,
				bSort: true,
				autoWidth: true,
				scrollCollapse: true,
				lengthMenu: [
					[5, 25, 50, -1],
					[5, 25, 50, "Todo"]
				],
				info: true,
				buttons: [{
						extend: 'excelHtml5',
						className: 'btn',
						text: "Excel",
						exportOptions: {
							columns: ":not(.no-exportar)"
						}
					},
					{
						extend: 'csvHtml5',
						className: 'btn',
						text: "Csv",
						exportOptions: {
							columns: ":not(.no-exportar)"
						}
					},
					{
						extend: 'pdfHtml5',
						className: 'btn',
            orientation: 'landscape',
            pageSize: 'LEGAL',
						text: "Pdf",
						exportOptions: {
							columns: ":not(.no-exportar)"
						},

					},
					{
						extend: 'copy',
						className: 'btn',
						text: "Copiar",
						exportOptions: {
							columns: ":not(.no-exportar)"
						}
					}
				],
				language: {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "Buscar:",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					},
					"decimal": ",",
					"thousands": "."
				},


        "footerCallback": function(row, data, start, end, display) {
          var api = this.api(),
            data;

          // Remove the formatting to get integer data for summation
          var intVal = function(i) {
            return typeof i === 'string' ?
              i.replace(/[\$,]/g, '') * 1 :
              typeof i === 'number' ?
              i : 0;
          };

          // Total over all pages
          <?php
          for ($i = 0; $i <= $numSucursales + 1; $i++) {
            $escribir = $i + 2;
            echo '
            total = api
            .column(' . $escribir . ')
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Total over this page
          pageTotal = api
            .column(' . $i . ', {
              page: "current"
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(' . $escribir . ').footer()).html(
           total 
          );
        


            ';
          }


          ?>
        }

      });

      $(".dt-button").addClass("btn-<?= $pyme; ?>");
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset($_SESSION['LZmsjInfoSegProducto'])) {
        echo "notificaBad('" . $_SESSION['LZmsjInfoSegProducto'] . "');";
        unset($_SESSION['LZmsjInfoSegProducto']);
      }
      if (isset($_SESSION['LZmsjSuccessSeg'])) {
        echo "notificaSuc('" . $_SESSION['LZmsjSuccessSeg'] . "');";
        unset($_SESSION['LZmsjSuccessSeg']);
      }
      ?>
    }); // Cierre de document ready

    $(".dt-button").addClass("btn-<?= $pyme; ?>");



    function limpiaCadena(dat, id) {
      //alert(id);
      dat = getCadenaLimpia(dat);
      $("#" + id).val(dat);
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