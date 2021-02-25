<!--<button type="button" class="close text-white" onclick="cancelar()">Cancelar</button>-->
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
                <h4 class="m-b-0 text-white">Consulta de Facturación por Sucursal</h4>
              </div>
              <div class="card-body">
                <div id="validation" class="m-t-0 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <?php
                //  echo "Sucursal Env: " . $_POST['buscaSuc'];
                //  echo "<br>Fecha Inicial: " . $_POST['fechaInicial'];
                //  echo "<br>Fecha Final: " . $_POST['fechaFinal'];
                  $sucursal = $_SESSION['LZFidSuc'];
                  $fechaAct = date('d-m-Y');
                  if (isset($_POST['fechaInicial']) and $_POST['fechaInicial'] != '') {
                    $fechaInicial = $_POST['fechaInicial'];
                    $formFI = date_format(date_create($fechaInicial), 'Y-m-d');
                    $busq_fecha = "fact.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                   // $filtroFechas = "CAST(fecha AS DATE) BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaInicial = "";
                    $busq_fecha = "1=1";
                  }
                  if (isset($_POST['fechaFinal'])  and $_POST['fechaFinal'] != '') {
                    $fechaFinal = $_POST['fechaFinal'];
                    
                    $formFF = date_format(date_create($fechaFinal), 'Y-m-d');
                    $busq_fecha = "fact.fechaReg BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                    //$filtroFechas = "CAST(fecha AS DATE) BETWEEN '$formFI 00:00:00' AND '$formFF 23:59:59' ";
                  } else {
                    $fechaFinal = "";
                   $busq_fecha = "1=1";
                  }

                  if (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] >= 1) {
                    $sucursal = $_POST['buscaSuc'];
                    $busq_sucursales = "suc.id=" . $_POST['buscaSuc'];
                  } else {
                    $sucursal = '';
                     $busq_sucursales = '1=1';
                  }
                  $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY nombre";
                  $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaSuc = '';
                  while ($datos = mysqli_fetch_array($resSuc)) {
                    $activeSuc = ($datos['id'] == $sucursal) ? 'selected' : '';
                    $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '>' . $datos['nombre'] . '</option>';
                  }

                


                  ?>

                </div>
                <div class="border p-3 mb-3">
                  <h4><i class="fas fa-filter"></i> Filtrado</h4>
                  <div class="row">
                    <form method="post" action="facturacionXsucursal.php">
                      <div class="col-6">
                      </div>
                      <div class="col-6">
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4  offset-md-0">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>
                            <input class="form-control" type="date" autocomplete="off" value="<?= $fechaInicial ?>" id="rangeBa1" name="fechaInicial" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>
                            <input class="form-control" type="date" autocomplete="off" value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />
                          </div>
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
                    <div class="col-md-4 mt-4 pt-1">
                      <input type="submit" id="buscarConexion" class="btn btn-success mt-2" value="Buscar"></input>
                    </div>
                  </div>
                  </form>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table id="zero_config" class="table display  no-wrap">
                        <thead>
                          <th>#</th>
                          <th>Fecha</th>
                          <th>UUID Factura</th>
                          <th>Serie/folio</th>
                          <th>Ticket(s) Venta</th>
                          <th>Cliente</th>
                          <th>Sucursal</th>
                          <th>Monto</th>
                          <th>Usuario</th>
                          <th>Estatus</th>
                          <th>Accion</th>
                        </thead>
                        <tbody>
                          <?php
                          $sql = "SELECT
                          DATE_FORMAT( fact.fechaReg, '%d-%m-%Y %H:%i:%s' ) fechaReg,
                          fact.uuid,
                          fact.uid,
                          fact.doctoXML,
                          fact.doctoPDF,
                          CONCAT(  fact.serie,'-',fact.folio ) folio_serie,
                          fact.monto,
                          CONCAT( usr.nombre, ' ', usr.appat, ' ', usr.apmat ) AS usuario,
                          fact.estatus,
                          GROUP_CONCAT( DISTINCT vf.idVenta ) AS ticketsVtas,
                          suc.nombre AS sucursal,
                          CONCAT( df.razonSocial, '<br>', df.rfc ) AS cliente,
                          cf.acuseXML AS acuseXMLCancel
                        FROM
                          facturas fact
                          INNER JOIN segusuarios usr ON usr.id = fact.idUserReg
                          INNER JOIN facturasgeneradas fg ON fg.uuidFact = fact.uuid
                          INNER JOIN vtasfact vf ON vf.idFactgen = fg.id
                          INNER JOIN ventas vtas ON vf.idVenta = vtas.id
                          INNER JOIN datosfisc df ON df.uid = fg.uidDatosFisc
                          INNER JOIN sucursales suc ON vtas.idSucursal = suc.id 
                          LEFT JOIN cancelafact cf ON cf.idVenta = vtas.id
                          WHERE $busq_sucursales AND $busq_fecha
                        GROUP BY
                          vf.idVenta 
                        ORDER BY
                          fact.fechaReg DESC";
                      //  echo $sql;
                        $respXTable =  mysqli_query($link, $sql) or die('Problemas al consultar Datos Facturables.' . mysqli_error($link));
                        $c=1;
                        while($dat=mysqli_fetch_array($respXTable)){
                          $acuseXML=$dat["acuseXMLCancel"]!=''?"<a type='button' class='btn btn-outline-danger' 
                          target='_blank' href='../" . $dat["acuseXMLCancel"] . "'><i class='fas fa-times'></i> </a>" :"";
                          $acciones="
                          $acuseXML
                          <a type='button' class='btn bg-success text-white' title='Documento XML' target='_blank' href='../" . $dat["doctoXML"] . "'><i class='far fa-file-code'></i> </a>
                          <a type='button' class='btn bg-danger text-white' title='Documento PDF' target='_blank' href='../" . $dat["doctoPDF"] . "'><i class='far fa-file-pdf'></i> </a>
                          ";
                          switch ($dat["estatus"]){
                            case '1':
                              $estatus="<button class='btn btn-circle' style='background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);' onclick='cancelar(\"".$dat["uid"]."\",\"".$dat["monto"]."\", \"".$dat["fechaReg"]."\")'> <i class='fas fa-check text-success'></i></button>";
                              break;
                            case '2':
                              $estatus="<i class='fas fa-times text-primary'></i>";
                              break;
                            case '3':
                              $estatus="<i class='fas fa-times text-danger'></i>";
                              break;

                          }
                            echo "
                            <tr>
                            <td>".$c."</td>
                            <td>".$dat["fechaReg"]."</td>
                            <td>".$dat["uuid"]."</td>
                            <td>".$dat["folio_serie"]."</td>
                            <td>".$dat["ticketsVtas"]."</td>
                            <td>".$dat["cliente"]."</td>
                            <td>".$dat["sucursal"]."</td>
                            <td>$".number_format($dat["monto"],2)."</td>
                            <td>".$dat["usuario"]."</td>
                            <td class='text-center'>".$estatus."</td>
                            <td>".$acciones."</td>
                            </tr>
                            ";

                          $c++;



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
        </div>
      </div>
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
  <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>

  <script>
  $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

    if (isset( $_SESSION['LZFmsjFactSuc'])) {
      echo "notificaBad('".$_SESSION['LZFmsjFactSuc']."');";
      unset($_SESSION['LZFmsjFactSuc']);
    }
    if (isset( $_SESSION['LZFmsjSuccessFactSuc'])) {
      echo "notificaSuc('".$_SESSION['LZFmsjSuccessFactSuc']."');";
      unset($_SESSION['LZFmsjSuccessFactSuc']);
    }
      ?>

    });
    function cancelar(uid, monto, fechaReg) {
      
      $.post("../funciones/cancelarCFDI.php", {
          uid: uid,
          monto: monto,
          fechaReg: fechaReg
        },
        function(respuesta) {
        
          var resp = respuesta.split('|');
           if (resp[0] == 1) {
            // notificaSuc(resp[1]);
           location.reload();
           } else if(resp[0] == 1) {
             notificaBad(resp[1]);
           }else if(resp[0] == 2) {
            Swal.fire({
              type: 'error',
              title: 'Error de Cancelación',
              text: resp[1]
             
            });
           }
        });






    }
  </script>