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

                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white">Lista de Pedidos Pendientes</h4>
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
                            $fecha= date('Y-m-d');
                            $sql ="SELECT t.*, CONCAT(u.nombre,' ',u.appat) AS nomUsuario, s.nombre AS nomSucursal, IF(t.idUserBodega > 0,CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat),'') AS despachador
                                    FROM traspasos t
                                    INNER JOIN segusuarios u ON t.idUserEnvio = u.id
                                    INNER JOIN sucursales s ON t.idSucEntrada = s.id
																		LEFT JOIN segusuarios u2 ON t.idUserBodega = u2.id
                                    WHERE t.idSucSalida = '$idSucursal' AND (DATE_FORMAT(t.fechaBodega,'%Y-%m-%d') = '$fecha' OR t.estatusBodega IN(0,1)) AND t.estatus IN(2,3)
                                    ORDER BY t.fechaEnvio ASC";
                            $res = mysqli_query($link,$sql) or die('Problemas al consultar los pedidos, notidica a tu Administrador.');
                            $conta = 0;
                            while ($ls = mysqli_fetch_array($res)) {

                            switch ($ls['estatusBodega']) {
                              case '0':
                                $color = 'table-danger';
                                #$textColor = 'text-danger';
                                $btn = '<a id="btnNo-'.$ls['id'].'"><button class="btn btn-circle-small muestraSombra btn-danger" data-toggle="modal" data-target="#modalPedido" onClick="listaPedido('.$ls['id'].',\''.$ls['nomSucursal'].'\')"><i class="fas fa-bars"></i></button><a id="btnNo-'.$ls['id'].'">';
                                break;
                              case '1':
                                $color = 'table-warning';
                                #$textColor = 'text-warning';
                                $btn = '<button class="btn btn-circle-small muestraSombra btn-warning" onClick="capturaEnvio('.$ls['id'].')"><i class="mdi mdi-codepen"></i></button>
                                          <a href="imprimeTicketTraspaso.php?idTraspaso='.$ls['id'].'&cat=1" target="_blank" class="btn btn-circle-small muestraSombra btn-info"><i class="fas fa-print"></i></a>';
                                break;
                              case '2':
                                $color = 'table-success';
                                #$textColor = 'text-success';
                                $btn = '<a id="btnNo-'.$ls['id'].'"><a href="imprimeTicketTraspaso.php?idTraspaso='.$ls['id'].'&cat=1" target="_blank" class="btn btn-circle-small muestraSombra btn-info"><i class="fas fa-print"></i></a></a>';
                                break;
                            }
                            $fechaBod = ($ls['fechaBodega'] > 0) ? $ls['fechaBodega'] : '';
                              echo '<tr class="'.$color.'" id="trNo-'.$ls['id'].'">
                                <td class="text-center">'.++$conta.'</td>
                                <td class="text-center">'.$ls['id'].'</td>
                                <td>'.$ls['nomUsuario'].'</td>
                                <td>'.$ls['nomSucursal'].'</td>
                                <td class="text-center">'.$ls['fechaEnvio'].'</td>
                                <td>'.$ls['despachador'].'</td>
                                <td class="text-center">'.$fechaBod.'</td>
                                <td class="text-center" id="btnNo-'.$ls['id'].'">
                                  '.$btn.'
                                </td>
                              </tr>';
                            }
                             ?>

                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

            <!-- sample modal content -->
            <div id="modalPedido" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="lblPedido">Detallado de: </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body" id="bodyTraspaso">

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->

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
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjBodeguero'])) {
        echo "notificaBad('".$_SESSION['LZFmsjBodeguero']."');";
        unset($_SESSION['LZFmsjBodeguero']);
      }
      if (isset( $_SESSION['LZFmsjSuccessBodeguero'])) {
        echo "notificaSuc('".$_SESSION['LZFmsjSuccessBodeguero']."');";
        unset($_SESSION['LZFmsjSuccessBodeguero']);
      }
      ?>
    });
    function listaPedido(id, nomSucursal){
      $.post("../funciones/muestraDesgloceTraspaso.php",
    {idTraspaso:id},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#bodyTraspaso").html(resp[1]);
        $("#lblPedido").html('Detallado de: '+nomSucursal);
      } else {
        notificaBad(resp[0]);
        $("#modalPedido").modal('hide');
      }
    });
    }

    function capturaEnvio(id){
      var tipo = 2;
      if (id > 0) {
        $.post("../funciones/cambiaEstausBodega.php",
      {idTraspaso:id, tipo:tipo},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#btnNo-"+id).html(resp[2]);
        $("#trNo-"+id).removeClass('table-warning');
        $("#trNo-"+id).addClass('table-success');
        notificaSuc(resp[1]);
      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció el traspaso, notifica a tu Administrador.');
      }
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
