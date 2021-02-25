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
                <h4 class="m-b-0 text-white">Listado de Solicitudes de Edición</h4>
              </div>
              <div class="card-body">
              <div class="text-right">
                                    <a class="btn btn-circle bg-<?= $pyme; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ir a Stock Global" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="stockGlobal.php"> <i class="fas fa-warehouse"></i></a>

                                </div>
                                <br>
                <?php
                $fechaAct = date('d-m-Y');
                if (isset($_POST['fechaInicial'])) {
                  $fechaInicial = $_POST['fechaInicial'];
                  $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                  $filtroFechas = "solc.fechaAutoriza BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                } else {
                  $fechaInicial = $fechaAct;
                  $filtroFechas = "1=1";
                }
                if (isset($_POST['fechaFinal'])) {
                  $fechaFinal = $_POST['fechaFinal'];
                  $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                  $filtroFechas = "solc.fechaAutoriza BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                } else {
                  $fechaFinal = $fechaAct;
                  $filtroFechas = "1=1";
                }

                if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                  $buscaSuc = $_POST['buscaSuc'];
                  $filtroSuc = "solc.idSucSolicita=" . $_POST['buscaSuc'];
                } else {
                  $filtroSuc = '1=1';
                  $buscaSuc = '';
                }

                $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                $listaSuc = '';
                while ($datos = mysqli_fetch_array($resSuc)) {
                  $activeSuc = ($datos['id'] == $buscaSuc) ? 'selected' : '';
                  $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                }




                ?>

                <div class="row">
                  <form method="post" action="solicitudesEdicion.php">

                    <div class="col-6">

                    </div>
                    <div class="col-6">

                    </div>
                </div>


                <!--/span-->
                <div class="border p-3 mb-3">
                  <div class="row">

                    <form method="post" action="solicitudesEdicion.php">
                      <div class="col-md-10 mt-2 mx-4">
                        <h4><i class="fas fa-filter "></i> Filtrado</h4>
                        <div class="row">
                          <div class="col-md-3">
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


                          <div class="col-md-3">

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


                          <div class="col-md-5">
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
                        </div>
                      </div>
                      <div class="col-md-1 pt-4">
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
                        <th>Folio Solicitud</th>
                        <th>Fecha de Emisión</th>
                        <th>Solicitante</th>
                        <th>Sucursal</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Aprobación</th>
                        <th class="text-center">Desactivar</th>






                      </tr>
                    </thead>
                    <tbody>
                      <?php
                
                     // echo '-->'.$filtroSuc.'<br>';
                      //echo '-->'.$stringWhere.'<br>';
                      $idSucursal = $_SESSION['LZFidSuc'];
                      $sqlsolic = "SELECT solc.id,solc.descripcion, solc.estatus AS estatussol, suc.nombre AS sucursal, CONCAT(usr.nombre,' ',usr.appat,' ',usr.apmat) AS usuario, CONCAT(usrAut.nombre,' ',usrAut.appat,' ',usrAut.apmat) AS usuarioAut, DATE_FORMAT(solc.fechaSolicita,'%d-%m-%Y  %h:%i %p') AS fechaEmision,   IF(fechaAutoriza>DATE_SUB(NOW(),INTERVAL 2 DAY),1,0) AS permisoVigente,
                      DATE_FORMAT(solc.fechaAutoriza,'%d-%m-%Y  %h:%i %p') AS fechaAutoriza  FROM activaredicionstock solc 
                      INNER JOIN sucursales suc ON solc.idSucSolicita=suc.id 
                      INNER JOIN segusuarios usr ON solc.idUserSolicita=usr.id
											LEFT JOIN segusuarios usrAut ON solc.idUserAutoriza=usrAut.id  WHERE $filtroFechas AND $filtroSuc
                      ORDER BY solc.estatus ASC, sucursal ASC";
                     // echo $sqlsolic;
                     $ressolic = mysqli_query($link, $sqlsolic) or die('Problemas al listar los Gastos, notifica a tu Administrador');
                      $iteracion = 1;
                      while ($solic = mysqli_fetch_array( $ressolic )) {
                        $id = $iteracion;
                        $alerta = ($gstos['estatussol'] == "3") ? 'table-danger' : '';



                        echo '<tr class="' . $alerta .'">
                                          <td class="text-center">' . $id . '</td>
                                          <td class="text-center">' . $solic['id'] . '</td>
                                          <td class="text-center">' . $solic['fechaEmision'] . '</td>

                                          <td id="gstoDesc-' . $id . '" >' . $solic['usuario'] . '</td>
                                          <td id="gstoMonto-' . $id . '" class="text-center">' . $solic['sucursal'] . '</td>
                                          <td id="gstoFecha-' . $id . '" class="text-center">' . $solic['descripcion'] . '</td>
                                      ';
                        if($solic['estatussol']==2 && $solic['permisoVigente']==1){
                            echo '<td class="text-success">' . $solic['fechaAutoriza'] . ' <br><b> Por: '.$solic['usuarioAut'].'</td>
                            <td><center class="text-success"  data-toggle="tooltip" data-placement="top"
                            title="" data-original-title="Deshabilitar"><button type="button" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);"  onclick="estatusSolic('.$solic['id'].', 3);"
                            class="btn ink-reaction btn-circle "><i class="fas fa-minus text-danger"></i></button></center></td>
                            ';
                                                    }
                            else if($solic['estatussol']==2 && $solic['permisoVigente']==0 ){
                                echo '<td class="text-danger">Permiso Expirado </td>
                                <td><center class="text-success"  data-toggle="tooltip" data-placement="top"
                                title="" data-original-title="Deshabilitar"><button type="button" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" disabled onclick="estatusSolic('.$solic['id'].', 3);"
                                class="btn ink-reaction btn-circle "><i class="fas fa-minus text-danger"></i></button></center></td>
                                ';

                                                    }
                        else  if($solic['estatussol']==3){
                            echo '<td class="text-danger">Solicitud Negada <br><b> Por: '.$solic['usuarioAut'].'</td>
                            <td><center class="text-success"  data-toggle="tooltip" data-placement="top"
                            title="" data-original-title="Deshabilitar"><button type="button" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" disabled onclick="estatusSolic('.$solic['id'].', 3);"
                            class="btn ink-reaction btn-circle "><i class="fas fa-minus text-danger"></i></button></center></td>
                            ';

                        }else{
                            echo'<td class="text-center"><center><button id="btnSave'. $solic['id'].'" onclick="estatusSolic(' .$solic['id'].', 2);" type="button" class="btn btn-success btn-circle btn-circle-tablita " title="Aprobar"><i class=" fas fa-check"></i></button><button type="button" class="btn btn-danger btn-circle btn-circle-tablita " onclick="estatusSolic('.$solic['id'].', 3);" title="Cancelar"><i class="fa fa-times"></i></button><span id="res' .$solic['id']. '"></span></center> </td>
                            <td><center class="text-success"  data-toggle="tooltip" data-placement="top"
                            title="" data-original-title="Deshabilitar"><button type="button" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" disabled onclick="estatusSolic('.$solic['id'].', 3);"
                            class="btn ink-reaction btn-circle btn-circle-tablita"><i class="fas fa-minus text-danger"></i></button></center></td>
                            </tr>';
                        }
                                
                                          
                                    
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


        <!-- /.modal -->
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
  <script type="text/javascript" src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js" charset="UTF-8"></script>

  <!--Custom JavaScript -->
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.datepicker').datepicker({
        language: 'es',
        format: 'dd-mm-yyyy',
      });
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset($_SESSION['LZmsjInfoAltaSolic'])) {
        echo "notificaBad('" . $_SESSION['LZmsjInfoAltaSolic'] . "');";
        unset($_SESSION['LZmsjInfoAltaSolic']);
      }
      if (isset($_SESSION['LZmsjSuccessSolic'])) {
        echo "notificaSuc('" . $_SESSION['LZmsjSuccessSolic'] . "');";
        unset($_SESSION['LZmsjSuccessSolic']);
      }
      ?>
    }); // Cierre de document ready


      function limpiaCadena(dat, id) {
      //alert(id);
      dat = getCadenaLimpia(dat);
      $("#" + id).val(dat);
    }

    
    function estatusSolic(id, estatus) {

      $.post("../funciones/cambiaEstatusSolicitud.php", {
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
            notificaBad(resp[1]);
          }
        });

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