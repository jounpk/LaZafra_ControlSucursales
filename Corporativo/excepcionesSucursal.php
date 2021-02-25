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




                  $sql = "SELECT * FROM productos WHERE estatus=1 ORDER BY descripcion";
                  $resProductos = mysqli_query($link, $sql) or die("Problemas al enlistar Productos.");

                  $listaProductos = '';
                  while ($datos = mysqli_fetch_array($resProductos)) {
                    $listaProductos .= '<option value="' . $datos['id'] . '" ' . $activeProductos . '>' . $datos['descripcion'] . '</option>';
                  }
                  ?>
                  <?php




                  $sql = "SELECT
                  suc.nombre,
                  suc.id 
                  FROM
                  sucursales suc ORDER BY nombre";
                  $resSucursales = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listadoSucursales = '';
                  while ($datos = mysqli_fetch_array($resSucursales)) {
                    $listadoSucursales .= '<option value="' . $datos['id'] . '" ' . $activeProductos . '>' . $datos['nombre'] . '</option>';
                  }
                  ?>


                  <div class="row">
                    <form method="post" action="" id="precioExpForm">

                      <div class="col-6">

                      </div>
                      <div class="col-6">

                      </div>
                  </div>


                  <!--/span-->
                  <div class="border p-3 mb-3">
                    <h4><i class="fas fa-filter"></i> Registro de Excepciones de Precios</h4>

                    <div class="row ">

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="inputEmail3" class="control-label col-form-label">Producto</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="finVig1"><i class="fas fa-trademark"></i></span>
                            </div>
                            <select class="select2 form-control custom-select" name="buscaPro" id="pro" onchange="" style="width: 80%;">

                              <option value=""> Todos los Productos</option>
                              <?= $listaProductos ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="inputEmail3" class="control-label col-form-label">Sucursal</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="finVig1"><i class="mdi mdi mdi-animation"></i></span>
                            </div>
                            <select class="select2 form-control custom-select" name="buscaSuc" id="buscaSuc" onchange="" style="width: 80%;">

                              <option value="">Todas las sucursales</option>
                              <?= $listadoSucursales ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 ">
                        <div class="form-group">
                          <label for="inputEmail3" class="control-label col-form-label">Precio Excepción</label>
                          <div class="input-group ">

                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon8">$</span>
                            </div>
                            <input type="text" class="form-control " name="precio" id="precio" value="" placeholder="" onchange="" aria-describedby="basic-addon10">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 pt-4 mt-2">
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
                          <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Registrar"></input>
                        </div>
                      </div>
                      </form>
                      <!-- /.row (nested) -->
                    </div>
                  </div>






                  <div class="table-responsive">
                    <table class="table product-overview table-striped" id="zero_config">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Producto</th>
                          <th class="text-center">Sucursal</th>
                          <th class="text-center">Precio</th>
                          <th class="text-center">Usuario Registro</th>
                          <th class="text-center">Fecha Registro</th>
                          <th class="text-center">Acciones</th>






                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        $sqlProductos = 'SELECT
                        pr_exp.id,
                        pr_exp.precio,
                        suc.nombre AS sucursal,
                        pro.descripcion,
                        DATE_FORMAT(pr_exp.fechaReg,"%d-%m-%Y %H:%i:%s") AS fechaRegistro,
                        CONCAT(us.nombre, " ", us.appat) AS usuario
                      FROM
                        excepcionesprecio pr_exp
                        INNER JOIN productos pro ON pro.id = pr_exp.idProducto
                        INNER JOIN sucursales suc ON suc.id = pr_exp.idSucursal
                        INNER JOIN segusuarios us ON pr_exp.idUserReg = us.id

                      ORDER BY
                        pro.descripcion';
                        // echo $sqlProductos;
                        $resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador');
                        $iteracion = 1;
                        while ($pro = mysqli_fetch_array($resPro)) {
                          $id = $iteracion;
                          $idStock = $pro['id'];

                          echo '<tr >
                                          <td class="text-center">' . $iteracion . '</td>
                                          <td id="descPro-' . $id . '">' . $pro['descripcion'] . '</td>

                                          <td id="sucursalPro-' . $id . '">' . $pro['sucursal'] . '</td>
                                          <td id="precioPro-' . $id . '">$' . $pro['precio'] . '</td>
                                          <td id="precioPro-' . $id . '">' . $pro['usuario'] . '</td>
                                          <td id="precioPro-' . $id . '">' . $pro['fechaRegistro'] . '</td>

                                          <td class="text-center"> <center>
                                            <button class="btn btn-circle bg-danger" onclick="eliminaPrecio(' . $pro['id'] . ');" ><i class="fas fa-trash-alt text-white"></i></button></center>
                                        
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
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
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


      $("#precioExpForm").submit(function(event) {
        // if (id != '' && estatus != '') {
        $.post("../funciones/registraprecioexp.php", $.param($(this).serializeArray()),
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              bloqueoBtn("bloquear-btn1", 1);

              notificaSuc(resp[1]);
              setTimeout(function() {
                location.reload();
              }, 1000);
            } else {
              bloqueoBtn("bloquear-btn1", 2);

              notificaBad(resp[0]);
            }
          });

      });

      function escribeSucursal(ident) {
        $.post("../funciones/enlistaSucursal.php", {
            ident: ident,
          },
          function(respuesta) {
            //alert(respuesta);
            $("#buscaSuc").html(respuesta);
          });
      }


      function limpiaCadena(dat, id) {
        //alert(id);
        dat = getCadenaLimpia(dat);
        $("#" + id).val(dat);
      }


      function eliminaPrecio(ident) {
        // if (id != '' && estatus != '') {
        $.post("../funciones/borraPrecioExp.php", {
            ident: ident
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

      }
    </script>

</body>

</html>