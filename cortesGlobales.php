<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../funciones/detCortes.php');
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
                <div class="row">
                  <div>
                      <h2 class="text-<?=$pyme;?>"><?=$info->nombrePag;?></h2>
                      <h4><?=$info->detailPag;?></h4>
                  </div>
                  <div class="ml-auto">
                    <h4><b><?=$info->nombreSuc;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4>
                  </div>
                  <br><br>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                      <div class="card border-<?=$pyme;?>">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="m-b-0 text-white">Búsquedad de Créditos</h4>
                          </div>
                      <div class="card-body">
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
                          $fechaFinal1 = strtotime ( '+1 day' , strtotime ( $fechaAct ) ) ;
                          $fechaFinal = date ( 'Y-m-d' , $fechaFinal1 );
                        }

                        $filtroFecha = " AND v.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'";

                        if (isset($_POST['sucursal']) && $_POST['sucursal'] > 0) {
                          $idSuc = $_POST['sucursal'];
                        } else {
                          $idSuc = '1';
                        }

                      #  echo 'Tipo: '.$tipo;
                         ?>
                         <style>
                           .muestraSombra{
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
                         <div class="col-md-12">
                           <div class="row">
                             <div class="col-md-2"></div>
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
                             <div class="col-md-5">
                               <div class="col-md-12">
                                   <div class="row">

                                   <div class="col-md-12">
                                     <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona una Sucursal:</h5>
                                   </div>
                                   <div class="col-md-5">
                                   </div>
                                 </div>
                               </div>
                               <div class="row">
                               <div class="col-md-8">
                                 <select class="select2 form-control custom-select" name="sucursal" id="sucursal" style="width: 95%; height:100%;">
                                   <?php
                                   echo '<option value="">Ingresa la Sucursal</option>';
                                   $sql="SELECT id,nombre FROM sucursales WHERE estatus = '1'";
                                 #  echo $sql;
                                   $res=mysqli_query($link,$sql);
                                    while ($rows = mysqli_fetch_array($res)) {
                                      $activa = ($idSuc == $rows['id']) ? 'selected' : '' ;
                                      echo '<option value="'.$rows['id'].'" '.$activa.'>'.$rows['nombre'].'</option>';
                                    }
                                    ?>
                                 </select>
                               </div>
                               <div class="col-md-4">
                                 <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                               </div>
                             </div>
                           </div>

                           <div class="col-md-1"></div>
                         </div>
                       </div>
                       </form>
                      </div>
                  </div>
                </div>
              </div>
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title"><b>Resultados de la Búsqueda</b></h4>
                            <br>
                            <div class="col-lg-12">
                              <div id="accordian-3">
                            <?php
                              ###################### se consultan los cortes ######################
                              $sqlConFechas = "SELECT DATE_FORMAT(c.fechaCierre, '%Y-%m-%d') AS fechaDeCierre, c.idSucursal,DATE_FORMAT(c.fechaCierre, '%d-%m-%Y') AS fechaDeCierre2
                                              FROM cortes c
                                              WHERE c.estatus = 2 AND c.idSucursal = '$idSuc' AND (DATE_FORMAT(c.fechaCierre,'%Y-%m-%d') BETWEEN '$fechaInicial' AND '$fechaFinal')
                                              GROUP BY fechaDeCierre
                                              ORDER BY fechaDeCierre DESC";
                              #echo '$sqlConFechas: '.$sqlConFechas;
                              $resConFechas = mysqli_query($link,$sqlConFechas) or die('Problemas al consultar los cortes por día, notifica a tu Administrador.');
                              $no = $fechaAnt = $abierto = 0;
                              while ($crt = mysqli_fetch_array($resConFechas)) {
                                ++$no;
                                $fechaCorte = $crt['fechaDeCierre'];
                                $fechaCorteGral = $crt['fechaDeCierre2'];
                                #echo '<br>$idCorte: '.$idCorte;
                                ###################### se evalua si el corte está abierto y sí la fecha es distinta de ese corte, si es distinta se cierra el acordeon ######################
                                if ($fechaAnt != $fechaCorte && $abierto == 1) {
                                  echo '</div>
                                    </div>
                                </div>';
                                }
                                ###################### se abre el acordeon ######################
                                if ($fechaAnt != $fechaCorte) {
                                  $abierto = 1;
                                  ###################### Se muestran los cortes con respecto a la fecha ######################
                              echo '<div class="card">
                                        <a class="card-header" id="noAcordeon-'.$no.'">
                                        <!--    #############################  -->
                                          <div class="d-flex align-items-center">
                                                  <a href="JavaScript>:void(0);" data-toggle="collapse" data-target="#colapsaCorte-'.$no.'" aria-controls="colapsaCorte-'.$no.'">
                                                      <h5 class="mb-0">
                                                        Cortes con fecha '.$fechaCorte.'
                                                      </h5>
                                                  </a>
                                                <div class="ml-auto">
                                                    <div class="btn btn-outline-info" id="btnImprimeCorteDelDia" onClick="imprimeCorteDelDía(\''.$fechaCorteGral.'\','.$idSuc.');" >Imprimir Corte del Día</div>
                                                </div>
                                          </div>
                                        <!--    #############################  -->
                                        </a>
                                        <div id="colapsaCorte-'.$no.'" class="collapse" aria-labelledby="noAcordeon-'.$no.'" data-parent="#accordian-3" style="">
                                            <div class="card-body">';
                                          }
                                    ###################### comienzan los folios de los cortes ######################
                                    $sqlConCortes = "SELECT c.id,scs.nombre, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario
                                                      FROM cortes c
                                                      INNER JOIN segusuarios u ON c.idUserReg = u.id
                                                      INNER JOIN sucursales scs ON c.idSucursal = scs.id
                                                      INNER JOIN desglocesefectivo df ON c.id = df.idCorte
                                                      WHERE DATE_FORMAT(c.fechaCierre, '%Y-%m-%d') = '$fechaCorte' AND c.estatus = '2' AND scs.id = '$idSuc'
                                                      ORDER BY c.id ASC";
                                    #echo '$sqlConCortes: '.$sqlConCortes;
                                    $resConCortes = mysqli_query($link,$sqlConCortes) or die('Problemas al listar los cortes, notifica a tu Administrador');
                                    $no2 = $abierto2 = $idCorte = $corteAnt = 0;

                                    echo '<!-- Nav tabs -->
                                    <ul class="nav nav-pills m-t-30 m-b-30">';

                                    while ($datos = mysqli_fetch_array($resConCortes)) {
                                      $idCorte = $datos['id'];
                                      ++$no2;
                                      if ($no2 == 1) {
                                        $activo = 'active';
                                      } else {
                                        $activo = '';
                                      }

                                      echo '<li class="nav-item"> <a class="nav-link '.$activo.'" data-toggle="tab" href="#corteConFolio-'.$idCorte.'" role="tab"><span class="hidden-xs-down">Folio: '.$idCorte.'</span></a> </li>';
                                      $corteAnt = $idCorte;
                                    }
                                    echo '</ul>
                                    <!-- Tab panes -->
                                      <div class="tab-content">';
                                      $no3 = 0;
                                      ############### reseteo la consulta y cargo los datos del cuerpo del tab ###############
                                    mysqli_data_seek($resConCortes,0);
                                    while ($rows = mysqli_fetch_array($resConCortes)) {
                                      ++$no3;
                                      $idCorte2 = $rows['id'];
                                      if ($no3 == 1) {
                                        $activo2 = 'active';
                                      } else {
                                        $activo2 = '';
                                      }
                                      echo '<div class="tab-pane  '.$activo2.'" id="corteConFolio-'.$idCorte2.'" role="tabpanel">
                                                <div class="p-20">
                                                    <h4><u><a class="text-'.$pyme.'" href="JavaScript:void(0);" onClick="imprimeCorte('.$idCorte2.');"><i class="fas fa-print"></i></a> '.$rows['nombre'].' '.$idCorte2.' <small class="text-muted">'.$rows['nomUsuario'].'</small></u></h4>
                                                </div>';
                                                detalladoDeCortes($idCorte2,$link);
                                      echo '</div>';
                                    }
                                    echo '</div>';

                                    $fechaAnt = $fechaCorte;
                              }
                                  echo '</div>
                                    </div>
                                </div>';

                             ?>


                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                    <!-- sample modal content -->
                    <div id="modalDesgloceVenta" class="modal bs-example-modal-lg fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="lblDesgloceVenta">Desgloce de Venta: </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body" id="desgloceVentaBody">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->

                    <!-- sample modal content -->
                    <div id="modalFacturaVenta" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="lblFacturaVenta">Ticket No.: </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body" id="facturaVentaBody">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->

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
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

    if (isset( $_SESSION['LZFmsjAdminConsultaCortes'])) {
      echo "notificaBad('".$_SESSION['LZFmsjAdminConsultaCortes']."');";
      unset($_SESSION['LZFmsjAdminConsultaCortes']);
    }
    if (isset( $_SESSION['LZFmsjSuccessAdminConsultaCortes'])) {
      echo "notificaSuc('".$_SESSION['LZFmsjSuccessAdminConsultaCortes']."');";
      unset($_SESSION['LZFmsjSuccessAdminConsultaCortes']);
    }
      ?>
    });

    function imprimeTicketVenta(idVenta){
        $('<form action="../imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="'+idVenta+'"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
    }

    function muestraDesgloce(idVenta){
      if (idVenta > 0) {
        //alert('Entra, idVenta: '+idVenta);
        $.post("../funciones/muestraDesgloceVenta.php",
      {idVenta:idVenta},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#lblDesgloceVenta").html('Desgloce de Venta: '+idVenta);
        $("#desgloceVentaBody").html(resp[1]);
      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
    }

    function modalFacturaVenta(idVenta){
      if (idVenta > 0) {
        //alert('Entra, idVenta: '+idVenta);
        $.post("../funciones/formularioFacturacion.php",
      {idVenta:idVenta},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#lblFacturaVenta").html('Ticket No.: '+idVenta);
        $("#facturaVentaBody").html(resp[1]);
      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
    }
    function imprimeCorte(idCorte){
        $('<form action="../imprimeTicketCorte.php" target="_blank" method="POST"><input type="hidden" name="idCorte" value="'+idCorte+'"></form>').appendTo('body').submit();
    }

    function imprimeCorteDelDía(fechaCorte,idSucursal){
      //alert('fechaCorte: '+fechaCorte+', idSucursal: '+idSucursal);
        $('<form action="../funciones/corteGeneralDia.php" target="_blank" method="POST"><input type="hidden" name="fechaCorte" value="'+fechaCorte+'"><input type="hidden" name="idSucursal" value="'+idSucursal+'"></form>').appendTo('body').submit();
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
