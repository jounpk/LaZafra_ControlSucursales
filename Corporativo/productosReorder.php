<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');

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
  <link rel="icon" type="../image/png" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->

  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <style>
        
        .btn-circle-tablita {
          width: 30px;
          height: 30px;
          text-align: center;
          padding: 6px 0;
          font-size: 12px;
          line-height: 1.428571429;
          border-radius: 15px;
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
                <h4 class="m-b-0 text-white">Listado de Productos</h4>
              </div>
              <div class="card-body">
                <div class="text-right">
                </div>
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <?php


                  if (isset($_POST['buscaDepto']) and $_POST['buscaDepto'] >= 1) {
                    $buscaDepto = $_POST['buscaDepto'];

                  $filtroDepto = "AND pd.idDepartamento =" . $_POST['buscaDepto'];
                  } else {
                    $filtroDepto = '';
                    $buscaDepto = '';
                  }

                  $buscaMaxMin = (isset($_POST['buscaMaxMin'])) ? $_POST['buscaMaxMin'] : '';

                  $sql = "SELECT * FROM departamentos WHERE estatus=1 ORDER BY nombre";
                  $resDepto = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaDepto = '';
                  while ($datos = mysqli_fetch_array($resDepto)) {
                    $activeDepto = ($datos['id'] == $buscaDepto) ? 'selected' : '';
                    $listaDepto .= '<option value="' . $datos['id'] . '" ' . $activeDepto . '>' . $datos['nombre'] . '</option>';
                  }




                  if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                    $buscaSuc = $_POST['buscaSuc'];
                    // $filtroSuc = "AND stk.idSucursal=" . $_POST['buscaSuc'];
                } else {
                    $buscaSuc= '0';
                    //  $buscaSuc = 'AND 1=1';
                }

                  $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                  $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaSuc = '';
                  while ($datos = mysqli_fetch_array($resSuc)) {
                      $activeSuc = ($datos['id'] == $buscaSuc) ? 'selected' : '';
                      $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                  }



                  $select = ($buscaMaxMin == 'ALL') ? 'selected' : '';
                  $listaMax =  '<option value="ALL" ' . $select . '>Todos los Productos.</option>';
                  $select = ($buscaMaxMin == 'MIN') ? 'selected' : '';
                  $listaMax .= '<option value="MIN" ' . $select . '>Rebasaron el Minimo.</option>';
                  $select = ($buscaMaxMin == 'MAX') ? 'selected' : '';
                  $listaMax .= '<option value="MAX" ' . $select . '>Rebasaron el Máximo.</option>';
                  $select = ($buscaMaxMin == 'AMBOS') ? 'selected' : '';
                  $listaMax .= '<option value="AMBOS" ' . $select . '>Con Minimos o Maximos.</option> ';

                  switch ($buscaMaxMin) {
                    case 'MIN':
                      $sqlMaxMin = 'stk.cantActual < stk.cantMinima';
                      break;

                    case 'MAX':
                      $sqlMaxMin = 'stk.cantActual > stk.cantMaxima';
                      break;

                    case 'AMBOS':
                      $sqlMaxMin = 'stk.cantActual < stk.cantMinima OR stk.cantActual > stk.cantMaxima';
                      break;

                    default:
                      $sqlMaxMin = '1 = 1';
                      break;
                  }
                  $sqlLabel = "SELECT COUNT(stk.id) AS cantidad
                                          FROM stocks stk
                                          INNER JOIN productos pd ON stk.idProducto = pd.id
                                          INNER JOIN departamentos dpto ON pd.idDepartamento = dpto.id
                                          LEFT JOIN stocks bdg ON stk.idProducto = bdg.idProducto AND  bdg.idSucursal = 1
                                          WHERE stk.idSucursal = '$buscaSuc' AND stk.cantActual < stk.cantMinima
                                          	AND ($sqlMaxMin)
                                          	$filtroDepto
                                          ORDER BY dpto.nombre, pd.descripcion ASC";
                  //echo $sqlLabel;
                  $resLabel = mysqli_query($link, $sqlLabel) or die($_SESSION['LZmsjInfoReorder'] = "Problemas para consultar productos, notifica a tu administrador.");
                  $etiqueta = mysqli_fetch_array($resLabel) or die($_SESSION['LZmsjInfoReorder'] = "Problemas para consultar productos para reorder, notifica a tu administrador.");
                 /* echo 
                  "busca Suc-->".$buscaSuc."<br>".
                  "busca dpto-->".$buscaDepto."<br>".
                  "busca MAXMIN-->".$buscaMaxMin."<br>".
                  "busca Suc-->".$buscaSuc."<br>";*/
                  ?>

                  <div class="row">
                    <form method="post" action="productosReorder.php">

                      <div class="col-6">

                      </div>
                      <div class="col-6">

                      </div>
                  </div>


                  <!--/span-->
                  <div class="border pt-3 px-3 pb-3 mb-3">
                    <div class="row">
                      <div class="col-md-2">
                      </div>
                      <div class="col-md-4">

                        <h4 class=""><i class="fas fa-filter "></i> Filtrado</h4>
                      </div>
                    </div>
                    <div class="row">

                      <form method="post" action="productosReorder.php">
                        <div class="col-md-2">

                          <div class="row">
                            <div class="col-md-12">
                              <label class="text-default">
                                <h3>Para Reorder:&nbsp;&nbsp;</h3>
                              </label>
                            </div>
                            <div class="row  px-5 px-sm-3">
                              <div class="col-md-8">
                              </div>
                              <div class="col-md-2">
                                <label class="text-danger">
                                  <h1> <b><?= $etiqueta['cantidad']; ?></b></h1>
                                </label>
                              </div>
                              <div class="col-md-2">
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class="col-md-3">

                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Búsqueda por máximos y mínimos</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-elevator"></i></span>
                              </div>
                              <select class="select2 form-control custom-select" name="buscaMaxMin" id="buscaMaxMin" onchange="" style="width: 80%;">
                                <?= $listaMax ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Búsqueda por departamento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-animation"></i></span>
                              </div>
                              <select class="select2 form-control custom-select" name="buscaDepto" id="buscaDepto" onchange="" style="width: 80%;">

                                <option value=""> Todos los Departamentos</option>
                                <?= $listaDepto ?>
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

                                <option value="">Elige una sucursal</option>
                                <?= $listaSuc ?>
                              </select>
                            </div>

                          </div>
                        </div>
                        <div class="col-md-1 pt-4 mt-2">
                          <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                        </div>
                      </form>
                      <!-- /.row (nested) -->
                    </div>
                  </div>






                  <div class="table-responsive">
                    <table class="table product-overview table-striped" id="tablaimpresion">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Estatus</th>
                          <th>Departamento</th>
                          <th>Anaquel</th>
                          <th class="text-center">Producto</th>
                          <th class="text-center">Cantidad Mínima</th>
                          <th class="text-center">Cantidad Máxima</th>
                          <th class="text-center">Cantidad Actual</th>
                          <th class="text-center">Acciones</th>

                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if ($filtroDepto == '') {
                          $stringWhere = '';
                        } else {
                          $stringWhere = 'AND ' . $filtroDepto;
                        }
                        $idSucursal = $_SESSION['LZFidSuc'];
                        $sqlProductos = "SELECT stk.id, pd.descripcion AS producto, dpto.nombre AS depto, stk.cantActual, stk.cantMinima, stk.cantMaxima, bdg.cantActual AS cantBodega, stk.anaquel
                        FROM stocks stk
                        INNER JOIN productos pd ON stk.idProducto = pd.id
                        INNER JOIN departamentos dpto ON pd.idDepartamento = dpto.id
                        LEFT JOIN stocks bdg ON stk.idProducto = bdg.idProducto AND  bdg.idSucursal = '$buscaSuc'
                        WHERE stk.idSucursal = '$buscaSuc'
                        	AND ($sqlMaxMin)
                        	$filtroDepto
                        ORDER BY dpto.nombre, pd.descripcion ASC";
                        //  echo $sqlProductos;
                        $resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador');
                        $iteracion = 1;
                        while ($pro = mysqli_fetch_array($resPro)) {
                          $id = $iteracion;
                          $lineColor = ($pro['cantActual'] < $pro['cantMinima']) ? 'danger' : 'success';

                          $elemento = ($pro['cantActual'] < $pro['cantMinima']) ? '<div class="text-center"><i data-toggle="tooltip" data-placement="top" title="" data-original-title="Revasan el Mínimo" class="fas fa-times text-danger"></i> Abastecer<div>' : '<div class="text-center"><i data-toggle="tooltip" data-placement="top" title="" data-original-title="Producto al Corriente" class="fas fa-check text-success"></i>  Surtido</div> ';

                          $estatus = ($pro['estatus'] == 1) ? '<center class="text-success"  data-toggle="tooltip" data-placement="top"
                         title="" data-original-title="Activo"><i class="fas fa-check text-success"></i></center>' : '<center class="text-success"  data-toggle="tooltip" data-placement="top"
                         title="" data-original-title="Desactivado"><i class="fas fa-times text-danger"></i></center>';
                        
                          echo '<tr class="table-' . $lineColor . '">
                                          <td class="text-center">' . $pro['id'] . '</td>
                                          <td>' . $elemento . '</td>
                                          <td id="dptoPro-' . $id . '">' . $pro['depto'] . '</td>
                                          <td><label id="lblanaquel' . $pro['id'] . '">' . $pro['anaquel'] . '</label>
                                          <input type="hidden" min="0" pattern="[0-9]" maxlength="10" id="anaquel-' . $pro['id'] . '" value="' . $pro['anaquel'] . '" class="form-control" >
                                          </td>
                                          <td id="descPro-' . $id . '">' . $pro['producto'] . '</td>

                                          <td ><label id="lblminima' . $pro['id'] . '">' . $pro['cantMinima'] . '</label>
                                          <input type="hidden" min="0" pattern="[0-9]" maxlength="10" id="cantMinima-' . $pro['id'] . '" value="' . $pro['cantMinima'] . '" class="form-control" >

                                          </td>
                                          <td ><label id="lblmaxima' . $pro['id'] . '">' . $pro['cantMaxima'] . '</label>
                                          <input type="hidden" min="0" pattern="[0-9]" maxlength="10" id="cantMaxima-' . $pro['id'] . '" value="' . $pro['cantMaxima'] . '" class="form-control" >

                                          </td>
                                          <td id="cantActualPro-' . $id . '"><b>' . $pro['cantActual'] . '</b></td>

                                

                                          <td class="text-center"> 
                                          <div id="boton-' . $pro['id'] . '">
                                          <center class="text-success"  data-toggle="tooltip" data-placement="top"
                                          title="" data-original-title="Editar">
                                            <button class="btn btn-circle btn-circle-tablita " style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="editarPro(' . $pro['id'] . ',1)" ><i class="fas fa-pencil-alt"></i></button></center>
                                          </div>
                                          </td>
                                        </tr>';
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
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
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

    <script>
      $(document).ready(function() {
        $('#tablaimpresion').DataTable({
          dom: 'Bfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
          ]
        });
        $(".dt-button").addClass("btn-<?= $pyme; ?>");
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
      function editarPro(id, no) {
        if (no == 1) {
          $("#lblminima" + id).hide();
          $("#lblmaxima" + id).hide();
          $("#lblanaquel" + id).hide();
          $("#cantMinima-" + id).attr("type", 'number');
          $("#cantMaxima-" + id).attr("type", 'number');
          $("#anaquel-" + id).attr("type", 'number');


          $("#boton-" + id).html('<center><button id="btnSave' + id + '" onclick="guardaProd(' + id + ');" type="button" class="btn btn-success btn-circle btn-circle-tablita " title="Capturar"><i class=" fas fa-check"></i></button><button type="button" class="btn btn-danger btn-circle btn-circle-tablita " onclick="editarPro(' + id + ',2)" title="Cancelar"><i class="fa fa-times"></i></button><span id="res' + id + '"></span></center>');
        } else {
          $("#lblminima" + id).show();
          $("#lblmaxima" + id).show();
          $("#lblanaquel" + id).show();
          var val1 = $("#lblminima" + id).html();
          var val2 = $("#lblmaxima" + id).html();
          var val3 = $("#lblanaquel" + id).html();
          $("#cantMaxima-" + id).attr("type", 'hidden');
          $("#cantMinima-" + id).attr("type", 'hidden');
          $("#anaquel-" + id).attr("type", 'hidden');
          $("#cantMinima-" + id).attr("value", val1);
          $("#cantMaxima-" + id).attr("value", val2);
          $("#anaquel-" + id).attr("value", val3);
          $("#boton-" + id).html(`<center class="text-success"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar">
        <button  class="btn btn-circle btn-circle-tablita " style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);"
        onclick="editarPro(${id},1)" >
        <i class="fas fa-pencil-alt"></i></button></center>`);
        }
      }


      function limpiaCadena(dat, id) {
        //alert(id);
        dat = getCadenaLimpia(dat);
        $("#" + id).val(dat);
      }

      function guardaProd(ident) {
        var cantMinima = $("#cantMinima-" + ident).val();
        var cantMaxima = $("#cantMaxima-" + ident).val();
        var anaquel = $("#anaquel-" + ident).val();
        if (cantMinima < 0 || cantMaxima < 0 || anaquel < 0) {
          editarPro(ident, 2);
          notificaBad('Cantidades registradas no aceptadas, intentálo de nuevo.');
        } else {
          $.post("../funciones/editaMinMax.php", {
              ident: ident,
              cantMinima: cantMinima,
              cantMaxima: cantMaxima,
              anaquel: anaquel
            },
            function(respuesta) {
              $("#lblminima" + ident).html(cantMinima);
              $("#lblmaxima" + ident).html(cantMaxima);
              $("#lblanaquel" + ident).html(anaquel);
              $('#cantMinima-' + ident).val(cantMinima);
              $('#cantMaxima-' + ident).val(cantMaxima);
              $('#anaquel-' + ident).val(anaquel);
              editarPro(ident, 2);
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