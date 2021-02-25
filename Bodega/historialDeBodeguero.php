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
$idSucursal = $_SESSION['LZFidSuc'];
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
                <style>

                .btn-circle-small {
                  width: 30px;
                  height: 30px;
                  text-align: center;
                  padding: 6px 0;
                  font-size: 12px;
                  line-height: 1.428571429;
                  border-radius: 15px;
                }

                .alinearCentro{
                  display: inline-block;
                  text-align: center;
                  vertical-align:middle;
                  line-height: 150%;
                  padding-top: 15%;
                }

                .muestraSombra{
                  box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);
                }

                </style>

                <div class="row">
                  <div class="col-md-12">
                    <div class="card border-<?=$pyme;?>">
                      <div class="card-header bg-<?=$pyme;?>">
                        <h4 class="text-white">Filtro</h4>
                      </div>
                      <div class="card-body">
                        <!-- ######################################################################################## -->
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
                        $tipo = (!empty($_POST['tipoFecha'])) ? $_POST['tipoFecha'] : 1 ;

                        if ($tipo == 2) {
                          $sel = 'selected';
                          $campo = 't.fechaBodega';
                        } else {
                          $sel = '';
                          $campo = 't.fechaEnvio';
                        }

                        $filtroFecha = " AND $campo BETWEEN '$fechaInicial' AND '$fechaFinal'";
                        #$filtroFecha2 = " AND t.fechaEnvio BETWEEN '$fechaInicial' AND '$fechaFinal'";

                      #  echo '<br>$filtroFecha: '.$filtroFecha;
                      #  echo '<br>$filtroFecha2: '.$filtroFecha2;
                        if (isset($_POST['sucursal']) && $_POST['sucursal'] > 0) {
                          $sucursal = $_POST['sucursal'];
                          $filtroSucursal = " AND t.idSucEntrada = '$sucursal'";
                        } else {
                          $sucursal = '';
                          $filtroSucursal = '';
                        }

                        if (isset($_POST['despachador']) && $_POST['despachador'] > 0) {
                          $idDespachador = $_POST['despachador'];
                          $filtroDespachador = " AND t.idUserBodega = '$idDespachador'";
                        } else {
                          $idDespachador = '';
                          $filtroDespachador = '';
                        }


                         ?>

                       <div class="col-md-12">
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
                           <div class="col-md-2">
                             <div class="col-md-12">
                                 <h5 class="m-t-30 text-<?=$pyme;?>">Fecha de:</h5>
                             </div>
                             <div class="row">
                             <div class="col-md-12">
                               <select class="select2 form-control custom-select" name="tipoFecha" id="tipoFecha" style="width: 95%; height:100%;">
                                 <option value="1">Autorización</option>
                                 <option value="2" <?=$sel;?>>Envío</option>
                               </select>
                             </div>
                           </div>
                         </div>
                         <div class="col-md-3">
                           <div class="col-md-12">
                               <h5 class="m-t-30 text-<?=$pyme;?>">Sucursal de Entrada:</h5>
                           </div>
                           <div class="row">
                           <div class="col-md-12">
                             <select class="select2 form-control custom-select" name="sucursal" id="sucursal" style="width: 95%; height:100%;">
                               <?php
                               echo '<option value="">Selecciona la Sucursal</option>';
                               $sql="SELECT id, nombre FROM sucursales WHERE estatus = '1'";
                             #  echo $sql;
                               $res=mysqli_query($link,$sql);
                                while ($rows = mysqli_fetch_array($res)) {
                                  $activa = ($sucursal == $rows['id']) ? 'selected' : '' ;
                                  $disabled = ($idSucursal == $rows['id']) ? 'disabled' : '' ;
                                  echo '<option value="'.$rows['id'].'" '.$activa.' '.$disabled.'>'.$rows['nombre'].'</option>';
                                }
                                ?>
                             </select>
                           </div>
                         </div>
                       </div>
                         <div class="col-md-3">
                           <div class="col-md-12">
                               <div class="row">

                               <div class="col-md-12">
                                 <h5 class="m-t-30 text-<?=$pyme;?>">Despachador:</h5>
                               </div>
                               <div class="col-md-5">
                               </div>
                             </div>
                           </div>
                           <div class="row">
                           <div class="col-md-8">
                             <select class="select2 form-control custom-select" name="despachador" id="despachador" style="width: 95%; height:100%;">
                               <?php
                               echo '<option value="">Selecciona el Bodeguero</option>';
                               $sql="SELECT DISTINCT(t.idUserBodega), CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomBodeguero
                                      FROM traspasos t
                                      INNER JOIN segusuarios u ON t.idUserBodega = u.id
                                      ORDER BY u.appat ASC";
                             #  echo $sql;
                               $res=mysqli_query($link,$sql);
                                while ($row = mysqli_fetch_array($res)) {
                                  $activa2 = ($idDespachador == $row['id']) ? 'selected' : '' ;
                                  echo '<option value="'.$row['id'].'" '.$activa2.'>'.$row['nomBodeguero'].'</option>';
                                }
                                ?>
                             </select>
                           </div>
                           <div class="col-md-4">
                             <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                           </div>
                         </div>
                       </div>
                       </div>
                     </div>
                       </form>
                      </div>
                      <!-- ######################################################################################## -->
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card border-<?=$pyme;?>">
                      <div class="card-header bg-<?=$pyme;?>">
                        <h4 class="text-white">Listado de Envíos Realizados</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table product-overview" id="zero_config">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Folio</th>
                                <th>Autoriza</th>
                                <th>Suc. Recibe</th>
                                <th class="text-center">Fecha Autoriza</th>
                                <th>Despachador</th>
                                <th class="text-center">Fecha Envío</th>
                                <th class="text-center">Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                              $sql = "SELECT t.*, CONCAT(u.nombre,' ',u.appat) AS nomUsuario, s.nombre AS nomSucursal, IF(t.idUserBodega > 0,CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat),'') AS despachador
                                      FROM traspasos t
                                      INNER JOIN segusuarios u ON t.idUserEnvio = u.id
                                      INNER JOIN sucursales s ON t.idSucEntrada = s.id
  																		LEFT JOIN segusuarios u2 ON t.idUserBodega = u2.id
                                      WHERE t.idSucSalida = '$idSucursal' AND t.estatusBodega > 0 $filtroSucursal $filtroDespachador $filtroFecha
                                      ORDER BY t.estatus,t.fechaEnvio ASC";
                              $res = mysqli_query($link,$sql) or die('Problemas al consultar los pedidos, notifica a tu Administrador.');
                              $conta = 0;
                              while ($a = mysqli_fetch_array($res)) {
                                $fecha = ($a['fechaBodega'] > 0) ? $a['fechaBodega'] : '' ;
                                if ($a['estatusBodega'] == 1) {
                                  $texto = '';
                                  $fondo = 'table-warning';
                                  $msn = 'Despachando en Bodega';
                                } else {
                                  $texto = 'text-'.$pyme;
                                  $fondo = '';
                                  $msn = 'Recepción Pendiente de la Sucursal';
                                }
                                if ($a['estatus'] == 3) {
                                  $texto = '';
                                  $fondo = 'table-success';
                                  $msn = 'Recibido por la Sucursal';
                                }
                                echo '<tr class="'.$fondo.' '.$texto.'" title="'.$msn.'">
                                        <td class="text-center">'.++$conta.'</td>
                                        <td class="text-center">'.$a['id'].'</td>
                                        <td>'.$a['nomUsuario'].'</td>
                                        <td>'.$a['nomSucursal'].'</td>
                                        <td class="text-center">'.$a['fechaEnvio'].'</td>
                                        <td>'.$a['despachador'].'</td>
                                        <td class="text-center">'.$fecha.'</td>
                                        <td class="text-center"><a href="imprimeTicketTraspaso.php?idTraspaso='.$a['id'].'&cat=2" target="_blank" class="btn btn-circle-small muestraSombra btn-info"><i class="fas fa-print"></i></a></td>
                                      </tr>';
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
      include('../funciones/basicFuctions.php');
      alertMsj($nameLk);
      ?>
    });
    </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>
