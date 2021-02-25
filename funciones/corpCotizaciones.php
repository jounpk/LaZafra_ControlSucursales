<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad-1];
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
   <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?=$pyme;?>.ico">
    <title><?=$info->nombrePag;?></title>

    <!-- Custom CSS -->
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
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
                <?=$info->customizer('2');?>

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
                        <?=$info->generaMenuUsuario();?>
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?=$info->generaMenuLateral();?>
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
                <style>
                  .muestraSombra{
                    box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);
                  }
                  .muestraSombra2{
                    background:#fff;
                    box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);
                  }
                  .alinearCentro{
                    display: inline-block;
                     text-align: center;
                     vertical-align:middle;
                     line-height: 150%;
                     padding-top: 15%;
                  }
                </style>
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="card-title text-white">Cotizaciones Pendientes</h4>
                        </div>
                        <div class="card-body">
                          <div class="row">
                          <div class="col-md-12">
                            <br>
                            <br>
                            <?php
                             $sqlPendientes = "SELECT c.*, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsu, scs.nombre AS nomSucursal, cli.correo AS mailCliente
                                               FROM cotizaciones c
                                               INNER JOIN sucursales scs ON c.idSucursal = scs.id
                                               INNER JOIN segusuarios u ON c.idUserReg = u.id
                                               LEFT JOIN clientes cli ON c.cliente = cli.nombre
                                               WHERE c.estatus = 2";
                             #echo '$sqlPendientes: '.$sqlPendientes;
                             $resPendientes = mysqli_query($link,$sqlPendientes) or die('Problemas al consultar las cotizaciones, notifica a tu Administrador.');
                             ?>
                            <div class="table-responsive">
                              <table class="table product-overview" id="zero_config">
                                <thead class="text-dark">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Folio</th>
                                        <th>Sucursal</th>
                                        <th>Atendió</th>
                                        <th>Cliente</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Ver</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $no = 0;
                                     while ($pend = mysqli_fetch_array($resPendientes)) {
                                       ++$no;
                                       $idCotizacion = $pend['id'];
                                       $btnAcciones = '
                                       <a href="../imprimeTicketCotizacion.php?idCotizacion='.$idCotizacion.'" target="_blank" class="btn btn-outline-success btn-square btn-sm muestraSombra" title="Imprimir Ticket"><i class="fas fa-file-alt"></i></a>
                                       <a href="../imprimePdfCotizacion.php?idCotizacion='.$idCotizacion.'" target="_blank" class="btn btn-outline-danger btn-square btn-sm muestraSombra" title="Imprimir PDF"><i class="fas fa-file-pdf"></i></a>
                                       ';
                                       $estado = 'Por Autorizar';
                                       echo '
                                           <tr '.$color.'>
                                               <td class="text-center">'.$no.'</td>
                                               <td class="text-center">'.$pend['folio'].'</td>
                                               <td>'.$pend['nomSucursal'].'</td>
                                               <td>'.$pend['nomUsu'].'</td>
                                               <td>'.$pend['cliente'].'</td>
                                               <td class="text-center">$ '.number_format($pend['montoTotal'],2,'.',',').'</td>
                                               <td class="text-center">'.$pend['fechaReg'].'</td>
                                               <td class="text-center">'.$estado.'</td>
                                               <td class="text-center">'.$btnAcciones.'</td>
                                               <td class="text-center">
                                                  <button type="button" onClick="autorizaRechaza('.$idCotizacion.',1);" class="muestraSombra btn btn-outline-success btn-circle" title="Autorizar"><i class="fas fa-check"></i></button>
                                                  <button type="button" onClick="autorizaRechaza('.$idCotizacion.',2);" class="muestraSombra btn btn-outline-danger btn-circle" title="Rechazar"><i class="fas fa-times"></i></button>
                                               </td>
                                           </tr>
                                       ';
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
              <br>
              <br>
                 <?php
                 #print_r($_POST);
                 $fechaAct=date('Y-m-d');
                 if (isset($_POST['fechaInicial'])) {
                   $fechaInicial = $_POST['fechaInicial'];
                 }else {
                   $fechaInicial1 = strtotime ( '-1 week' , strtotime ( $fechaAct ) ) ;
                   $fechaInicial = date ( 'Y-m-d' , $fechaInicial1 );
                 }
                 if (isset($_POST['fechaFinal'])) {
                   $fechaFinal = $_POST['fechaFinal'];
                 }else {
                   $fechaFinal1 = strtotime ( '+15 day' , strtotime ( $fechaAct ) ) ;
                   $fechaFinal = date ( 'Y-m-d' , $fechaFinal1 );
                 }

                 $filtroFecha = " AND v.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'";

                 if (isset($_POST['estado']) && $_POST['estado'] > 1) {
                   $estado = $_POST['estado'];
                   $filtroEstado = " AND c.estatus = '$estado'";
                 } else {
                   $estado = '';
                   $filtroEstado = ' AND c.estatus > 1';
                 }
                 $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = '';
                 switch ($estado) {
                   case '2':
                     $sel2 = 'selected';
                     break;
                   case '3':
                     $sel3 = 'selected';
                     break;
                   case '4':
                     $sel4 = 'selected';
                     break;
                   case '5':
                     $sel5 = 'selected';
                     break;

                   default:
                     $sel1 = 'selected';
                     break;
                 }

                 if (isset($_POST['sucursal']) && $_POST['sucursal'] > 0) {
                   $idSuc = $_POST['sucursal'];
                   $filtroSucursal = " AND scs.id = '$idSuc'";
                 } else {
                   $idSuc = '0';
                   $filtroSucursal = '';
                 }
                  ?>

                 <div class="row">
                   <div class="col-md-12 col-lg-12">
                     <div class="card border-<?=$pyme;?>">
                         <div class="card-header bg-<?=$pyme;?>">
                           <h4 class="card-title text-white">Historial de cotizaciones</h4>
                         </div>
                         <div class="card-body">
                           <div class="row">
                             <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                               <div class="col-md-12">
                                 <h5 class="m-t-30 text-center text-<?=$pyme;?>">Selecciona un rango de Fechas</h5>
                               </div>
                               <div class="col-md-12">
                                 <form role="form" action="#" method="post">
                                   <div class="input-daterange input-group" id="date-range">
                                       <input type="date" class="form-control" name="fechaInicial" value="<?=$fechaInicial;?>" />
                                       <div class="input-group-append">
                                           <span class="input-group-text bg-<?=$pyme;?> b-0 text-white"> A </span>
                                       </div>
                                       <input type="date" class="form-control" name="fechaFinal" value="<?=$fechaFinal;?>" />
                                   </div>
                               </div>
                             </div>
                             <div class="col-md-8">
                               <div class="col-md-12">
                                 <div class="row">
                                   <div class="col-md-4">
                                     <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona un Estatus:</h5>
                                   </div>
                                   <div class="col-md-4">
                                     <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona una Sucursal:</h5>
                                   </div>
                                   <div class="col-md-4">
                                   </div>
                                 </div>
                               </div>
                               <div class="row">
                                 <div class="col-md-4">
                                   <select class="select2 form-control custom-select" name="estado" id="estado" style="width: 95%; height:100%;">
                                     <option value="" <?=$sel1;?>>Selecciona el estatus</option>
                                     <option value="2" <?=$sel2;?>>Por Autorizar</option>
                                     <option value="3" <?=$sel3;?>>Autorizado</option>
                                     <option value="4" <?=$sel4;?>>Rechazados</option>
                                     <option value="5" <?=$sel5;?>>Cancelado por el Usuario</option>
                                   </select>
                                 </div>
                                 <div class="col-md-4">
                                   <select class="select2 form-control custom-select" name="sucursal" id="sucursal" style="width: 95%; height:100%;">
                                     <option value="">Todas las Sucursales</option>
                                     <?php
                                      $sqlSuc = "SELECT id,nombre
                                                FROM sucursales s
                                                WHERE estatus = '1'
                                                ORDER BY nombre ASC";
                                      $resSuc = mysqli_query($link,$sqlSuc) or die('Problemas al consultar las sucursales, notifica a tu Administrador.');
                                      while ($lsc = mysqli_fetch_array($resSuc)) {
                                        $activa = ($lsc['id'] == $idSuc) ?  'selected' : '' ;
                                        echo '<option value="'.$lsc['id'].'" '.$activa.'>'.$lsc['nombre'].'</option>';
                                      }
                                      ?>
                                   </select>
                                 </div>
                                 <div class="col-md-4">
                                   <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                                 </div>
                               </form>
                             </div>
                           </div>

                           <div class="col-md-12">
                             <br>
                             <br>
                             <?php
                              $sqlHistorial = "SELECT c.*, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsu, scs.nombre AS nomSucursal, cli.correo AS mailCliente
                                                FROM cotizaciones c
                                                INNER JOIN sucursales scs ON c.idSucursal = scs.id
                                                INNER JOIN segusuarios u ON c.idUserReg = u.id
                                                LEFT JOIN clientes cli ON c.cliente = cli.nombre
                                                WHERE c.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal' $filtroEstado $filtroSucursal";
                              #echo '$sqlHistorial: '.$sqlHistorial;
                              $resHistorial = mysqli_query($link,$sqlHistorial) or die('Problemas al consultar las cotizaciones, notifica a tu Administrador.');
                              ?>
                             <div class="table-responsive">
                               <table class="table product-overview" id="zero_config">
                                 <thead class="text-dark">
                                     <tr>
                                         <th class="text-center">#</th>
                                         <th class="text-center">Folio</th>
                                         <th>Sucursal</th>
                                         <th>Atendió</th>
                                         <th>Cliente</th>
                                         <th class="text-center">Total</th>
                                         <th class="text-center">Fecha</th>
                                         <th class="text-center">Estado</th>
                                         <th class="text-center">Envío por correo</th>
                                         <th class="text-center">Acciones</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                   <?php
                                   $no = 0;
                                      while ($d = mysqli_fetch_array($resHistorial)) {
                                        ++$no;
                                        switch ($d['estatus']) {
                                          case '2':
                                            $estado = 'Pendiente';
                                            $color = '';
                                            $btnAccion = '
                                                          <a href="../imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-success btn-square btn-sm" title="Imprimir Ticket"><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-danger btn-square btn-sm" title="Imprimir PDF"><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;
                                          case '3':
                                            $estado = 'Autorizado';
                                            $color = 'class="table-success"';
                                            $btnAccion = '
                                                          <button type="button" onClick="enviaCotizacion('.$d['id'].');" class="btn btn-outline-info btn-square btn-sm muestraSombra2" title="Enviar por correo al Cliente"><i class="fas fa-envelope"></i></button>
                                                          <a href="../imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-success btn-square btn-sm muestraSombra2" title="Imprimir Ticket"><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-danger btn-square btn-sm muestraSombra2" title="Imprimir PDF"><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;
                                          case '4':
                                            $estado = 'Rechazado';
                                            $color = 'class="table-danger"';
                                            $btnAccion = '
                                                          <a href="../imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-success btn-square btn-sm muestraSombra2" title="Imprimir Ticket"><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-danger btn-square btn-sm muestraSombra2" title="Imprimir PDF"><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;

                                          default:
                                            $estado = 'Cancelado';
                                            $color = 'class="table-danger"';
                                            $btnAccion = '
                                                          <a href="../imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-success btn-square btn-sm muestraSombra2" title="Imprimir Ticket"><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-outline-danger btn-square btn-sm muestraSombra2" title="Imprimir PDF"><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;
                                        }
                                        if ($d['enviado'] == 1) {
                                          $btnEnviado = '<a class="text-warning" title="Pendiente"><i class="fas fa-exclamation-triangle"</a>';
                                        } else {
                                          $btnEnviado = '<a class="text-success" title="Enviado"><i class="fas fa-check"</a>';
                                        }

                                        echo '
                                            <tr '.$color.'>
                                                <td class="text-center">'.$no.'</td>
                                                <td class="text-center">'.$d['folio'].'</td>
                                                <td>'.$d['nomSucursal'].'</td>
                                                <td>'.$d['nomUsu'].'</td>
                                                <td>'.$d['cliente'].'</td>
                                                <td class="text-center">$ '.number_format($d['montoTotal'],2,'.',',').'</td>
                                                <td class="text-center">'.$d['fechaReg'].'</td>
                                                <td class="text-center">'.$estado.'</td>
                                                <td class="text-center">'.$btnEnviado.'</td>
                                                <td class="text-center">'.$btnAccion.'</td>
                                            </tr>
                                        ';
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



                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
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
    <script src="../dist/js/pages/datatable/datatable-api.init.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/block-ui/jquery.blockUI.js"></script>
    <script src="../assets/extra-libs/block-ui/block-ui.js"></script>
    <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/libs/sweetalert2/sweet-alert.init.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjCorporativonCotizaciones'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCorporativonCotizaciones']."');";
				unset($_SESSION['LZFmsjCorporativonCotizaciones']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCorporativonCotizaciones'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCorporativonCotizaciones']."');";
        unset($_SESSION['LZFmsjSuccessCorporativonCotizaciones']);
			}
      ?>
    });

    function autorizaRechaza(idCoti,tipo){
      $('<form action="../funciones/autorizaCotizacion.php" method="POST"><input type="hidden" name="tipo" value="'+tipo+'"><input type="hidden" name="idCoti" value="'+idCoti+'"></form>').appendTo('body').submit();
    }

    function enviaCotizacion(idCotizacion){
      var pagina = 'Corporativo/corpCotizaciones.php' ;
      var alerta = 'CorporativonCotizaciones';
      $('<form action="../funciones/creaCotizacion.php" method="POST"><input type="hidden" name="idCotizacion" value="'+idCotizacion+'"><input type="hidden" name="pagina" value="'+pagina+'"><input type="hidden" name="alerta" value="'+alerta+'"></form>').appendTo('body').submit();
    }
    </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>
