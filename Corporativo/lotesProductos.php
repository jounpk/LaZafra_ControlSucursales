<?php
require_once 'seg.php';
$info = new Seguridad();
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

                        if (isset($_POST['sucursal']) && $_POST['sucursal'] > 0) {
                          $sucursal = $_POST['sucursal'];
                          $filtroSucursal = " AND ls.idSucursal = '$sucursal'";
                        } elseif ($_POST['sucursal'] == 'ALL') {
                          $sucursal = '';
                          $filtroSucursal = '';
                        } else {
                          $sucursal = 1;
                          $filtroSucursal = " AND ls.idSucursal = '$sucursal'";
                        }

                        if (isset($_POST['producto']) && $_POST['producto'] > 0) {
                          $producto = $_POST['producto'];
                          $filtroProducto = " AND ls.idProducto = '$producto'";
                        } else {
                          $producto = '';
                          $filtroProducto = '';
                        }


                      #  echo 'Tipo: '.$tipo;
                         ?>
                         <style>
                           .muestraSombra{
                             box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);
                           }
                           .alinearCentro{
                             /*display: inline-block;*/
                              text-align: center;
                              vertical-align:middle;
                              line-height: 150%;
                              padding-top: 15%;
                           }
                           .verticalText {
                             vertical-align:middle;
                             text-align: center;
                            -webkit-transform: rotate(-90deg);
                            -moz-transform: rotate(-90deg);
                            }
                         </style>
                       <div class="col-md-12">
                         <div class="row">
                           <div class="col-md-2"></div>
                           <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                             <div class="col-md-12">
                               <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona una Sucursal</h5>
                             </div>
                             <div class="col-md-12">
                               <form role="form" action="#" method="post">
                                 <select class="select2 form-control custom-select" name="sucursal" id="sucursal" style="width: 95%; height:100%;">
                                   <?php
                                   echo '<option value="ALL">Todas las Sucursales</option>';
                                   $sql="SELECT id,nombre FROM sucursales WHERE estatus = '1'";
                                 #  echo $sql;
                                   $res=mysqli_query($link,$sql);
                                    while ($rows = mysqli_fetch_array($res)) {
                                      $activa = ($sucursal == $rows['id']) ? 'selected' : '' ;
                                      echo '<option value="'.$rows['id'].'" '.$activa.'>'.$rows['nombre'].'</option>';
                                    }
                                    ?>
                                 </select>
                             </div>
                           </div>
                           <div class="col-md-5">
                             <div class="col-md-12">
                                 <div class="row">

                                 <div class="col-md-12">
                                   <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona un Producto:</h5>
                                 </div>
                                 <div class="col-md-5">
                                 </div>
                               </div>
                             </div>
                             <div class="row">
                             <div class="col-md-8">
                               <select class="select2 form-control custom-select" name="producto" id="producto" style="width: 95%; height:100%;">
                                 <?php
                                 echo '<option value="">Todos los productos</option>';
                                 $sql="SELECT id,descripcion FROM productos WHERE estatus = 1 ORDER BY descripcion ASC";
                               #  echo $sql;
                                 $res=mysqli_query($link,$sql);
                                  while ($rows2 = mysqli_fetch_array($res)) {
                                    $activa2 = ($producto == $rows2['id']) ? 'selected' : '' ;
                                    echo '<option value="'.$rows2['id'].'" '.$activa2.'>'.$rows2['descripcion'].'</option>';
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
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered dataTable" id="zero_config2">
                              <thead>
                                <tr>
                                  <th>Sucursal</th>
                                  <th>Producto</th>
                                  <th>Lote</th>
                                  <th class="text-center">Caducidad</th>
                                  <th class="text-center">Cantidad</th>
                                  <th class="text-center">Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                #/*
                                  $sqlLt = "SELECT ls.id AS idLote,scs.nombre AS nomSucursal, p.descripcion AS nomProducto, ls.lote, DATE_FORMAT(ls.caducidad,'%d-%m-%Y') AS fechaCaducidad,ls.caducidad, ls.cant
                                            FROM lotestocks ls
                                            INNER JOIN productos p ON ls.idProducto = p.id
                                            INNER JOIN sucursales scs ON ls.idSucursal = scs.id
                                            WHERE 1=1 $filtroProducto $filtroSucursal
                                            ORDER BY ls.idProducto,scs.nameFact ASC";
                                  $resLt = mysqli_query($link,$sqlLt) or die('Problemas al consultar los Lotes, notifica a tu Administrador.');
                                  while ($lts = mysqli_fetch_array($resLt)) {
                                    if (is_null($lts['caducidad'])) {
                                      $caducidad = 'No Aplica';
                                      $cad = 0;
                                    } else {
                                       $caducidad = $lts['fechaCaducidad'];
                                       $cad = $lts['caducidad'];
                                    }
                                    $color = ($lts['cant'] == 0) ? 'class="table-danger"' : '' ;
                                    echo '<tr '.$color.'>
                                            <td>'.$lts['nomSucursal'].'</td>
                                            <td>'.$lts['nomProducto'].'</td>
                                            <td id="lt-'.$lts['idLote'].'">'.$lts['lote'].'</td>
                                            <td class="text-center">'.$caducidad.'</td>
                                            <td class="text-center">'.$lts['cant'].'</td>
                                            <td class="text-center">
                                              <button type="button" class="btn btn-info btn-circle muestraSombra" data-toggle="modal" data-target="#modalEditLote" title="Editar Caducidad" onClick="mandaCaducidad('.$lts['idLote'].','.$cad.')">
                                                <i class="fas fa-pencil-alt"></i>
                                              </button>
                                            </td>
                                          </tr>';
                                  }
                                  /*
                                  <td class="text-center">
                                  <button type="button" class="btn btn-info btn-circle muestraSombra" title="Imprimir Ticket"><i class="fas fa-print"></i></button></button>
                                  </td>
                                  #*/
                                 ?>

                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- sample modal content -->
                <div id="modalEditLote" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="lblEditLote">Editar Caducidad</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                              <form role="form" method="post" id="formEditaLote" action="../funciones/editaCaducidadLote.php">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon11"><i class="  far fa-calendar-alt"></i></span>
                                        </div>
                                        <input class="form-control form-white" placeholder="Fecha de Caducidad" type="date" name="caducidad" id="caducidad" required>
                                    </div>
                                    <br>
                                    <div class="modal-footer">
                                        <input type="hidden" id="idLote" name="idLote" value="">
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Editar</button>
                                    </div>
                                </form>
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
      /*
      include('../funciones/basicFuctions.php');
      alertMsj($nameLk);
      */
      if (isset( $_SESSION['LZFmsjLotesStock'])) {
        echo "notificaBad('".$_SESSION['LZFmsjLotesStock']."');";
        unset($_SESSION['LZFmsjLotesStock']);
      }
      if (isset( $_SESSION['LZFmsjSuccessLotesStock'])) {
        echo "notificaSuc('".$_SESSION['LZFmsjSuccessLotesStock']."');";
        unset($_SESSION['LZFmsjSuccessLotesStock']);
      }
      ?>
    });
    $("#zero_config2").DataTable();

    function mandaCaducidad(idLote,caducidad){
      $("#idLote").val(idLote);
      $("#caducidad").val(caducidad);
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
