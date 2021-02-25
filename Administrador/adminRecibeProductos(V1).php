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
$idUser = $_SESSION['LZFident'];
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
                <?php
                  $sql1 = "SELECT * FROM recepciones WHERE estatus = '1' AND idSucursal = '$idSucursal' AND idUserReg = '$idUser'";
                  $res1 = mysqli_query($link,$sql1) or die('Problemas al consultar las ordenes de compra');
                  $d = mysqli_fetch_array($res1);
                  $idCompra1 = $d['idCompra'];

                  $idCompra2 = (isset($_POST['idCompra']) && $_POST['idCompra'] != '') ? $_POST['idCompra'] : 0 ;

                  $idCompra = ($idCompra1 > 0) ? $idCompra1 : $idCompra2;

                  $_SESSION['ordenCompra'] = $idCompra;
                  if ($idCompra > 0) {
                    $sql = "SELECT * FROM compras WHERE id = '$idCompra' AND estatus = '2'";
                    $res = mysqli_query($link,$sql) or die('Problemas al consultar las ordenes de compra');
                    $cant = mysqli_num_rows($res);
                  } else {
                    $cant = 0;
                  }
                  #en caso de que no haya una venta con ese id que esté cerrada le volverá a pedir que no exite una venta disponible con ese código
                  if ($cant == 0) {
                 ?>
                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white"><i class="fas fa-filter"></i> Orden De compra</h4>
                  </div>
                  <div class="card-body">
                    <form role="form" method="post" action="#">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon1">O.C</span>
                              </div>
                              <input type="number" id="idCompra" name="idCompra" min="0" placeholder="ingresa el número de compra" class="form-control" aria-label="idCompra" aria-describedby="basic-addon1">
                          </div>
                        </div>
                        <div class="col-md-2 text-center">
                          <button type="submit" class="btn btn-<?=$pyme;?>">Aceptar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <?php
              } else {
                 ?>
                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white">Descripción de la compra</h4>
                  </div>
                  <div class="card-body">
                    <?php
                      $sqlCon = "SELECT c.*, s.nombre AS nomSucursal, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario, r.id AS noRecepcion,p.nombre AS nomProveedor
                                FROM compras c
                                INNER JOIN sucursales s ON c.idSucursal = s.id
                                INNER JOIN segusuarios u ON c.idUserReg = u.id
																INNER JOIN proveedores p ON c.idProveedor = p.id
																LEFT JOIN recepciones r ON c.id = r.idCompra AND r.idSucursal = '$idSucursal' AND r.idUserReg = '$idUser' AND r.estatus = '1'
                                WHERE c.id = '$idCompra' LIMIT 1";
                      $resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la compra, notifica a tu Administrador.');
                      $row = mysqli_fetch_array($resCon);

                      $idRecepcion = ($row['noRecepcion'] > 0) ? (int)$row['noRecepcion'] : 0 ;
                      $hojaEntrada = ($row['noRecepcion'] > 0) ? (int)$row['noRecepcion'] : '' ;
                      $fechaCompra = $row['fechaCompra'];
                      $nomSucursal = $row['nomSucursal'];
                      $Comprador = $row['nomUsuario'];
                      $nota = $row['nota'];
                      $nomProveedor = $row['nomProveedor'];

                     ?>
                     <div class="row">
                       <div class="col-md-12">
                         <label class="control-label"><br>Hoja de Entrada:</b>&nbsp;&nbsp;</label><label class="control-label text-info">#<b><?=$hojaEntrada;?></b></label>
                       </div>
                       <div class="col-md-6">
                           <label class="control-label"><br>Orden de Compra:</b>&nbsp;&nbsp;</label><label class="control-label text-info">#<b><?=$idCompra;?></b></label>
                           <br>
                           <label class="control-label"><br>Fecha de Compra:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?=$fechaCompra;?></b></label>
                       </div>
                       <div class="col-md-6">
                           <label class="control-label"><br>Sucursal:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?=$nomSucursal;?></b></label>
                           <br>
                           <label class="control-label"><br>Comprador:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?=$Comprador;?></b></label>
                       </div>
                       <div class="col-md-6">
                         <label class="control-label"><br>Nota de Compra:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?=$nota;?></b></label>
                       </div>
                       <div class="col-md-6">
                         <label class="control-label"><br>Proveedor:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?=$nomProveedor;?></b></label>
                       </div>
                     </div>
                     <div class="modal-footer">
                       <form method="post" role="form" action="../funciones/cierraRecibeProductos.php">
                         <input type="hidden" name="idCompra" value="<?=$idCompra;?>">
                         <input type="hidden" name="tipo" value="3">
                         <input type="hidden" name="vista" value="2">
                         <button type="submit" class="btn btn-danger">Cancelar</button>
                       </form>

                         <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#modalCierraRegistro">Cerrar Recepción</button>
                     </div>
                  </div>
                </div>

                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white">Listado de Productos</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table product-overview table-striped">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad Comprada</th>
                            <th class="text-center">Cantidad restante</th>
                            <th class="text-center">Cantidad Recibida</th>
                            <th class="text-center">Lotes</th>
                            <th class="text-center">Acciones</th>
                          </tr>
                        </thead>
                        <tbody id="tablaBody">
                          <?php

                            $sqlProd = "SELECT dc.idProducto AS 'idProducto',dc.id AS 'idDetCompra',dc.descripcion AS nomProducto, dc.cantidad AS cantComprada,
                                        IF(SUM(dr1.cantidad) > 0, (dc.cantidad - SUM(dr1.cantidad)), dc.cantidad ) AS cantResta,
                                        IF(SUM(dr2.cantidad) >0,SUM(dr2.cantidad),0) AS cantRecibida, GROUP_CONCAT(dr2.lote SEPARATOR ',') AS lotes
                                        FROM compras c
                                        INNER JOIN (
																					SELECT dc.id,dc.cantidad,dc.idCompra,IF(dc.idProducto > 0,dc.idProducto,0) AS idProducto, IF(dc.idProducto > 0,p.descripcion,dc.nombreProducto) AS descripcion
                                              FROM detcompras dc
                                              LEFT JOIN productos p ON dc.idProducto = p.id
                                              WHERE dc.idCompra = '$idCompra'
																				) dc ON c.id = dc.idCompra
                                        LEFT JOIN (
                                        SELECT idDetCompra, IF(SUM(cantidad)>0,SUM(cantidad),0) AS 'cantidad',idProducto FROM detrecepciones WHERE estatus = '2' GROUP BY idDetCompra
                                        ) dr1 ON dc.id = dr1.idDetCompra AND dc.idProducto = dr1.idProducto
                                        LEFT JOIN (
                                        SELECT dr.idDetCompra, IF(SUM(dr.cantidad)>0,SUM(dr.cantidad),0) AS 'cantidad',lts.lote,dr.idProducto
																				FROM detrecepciones dr
																				LEFT JOIN lotestocks lts ON dr.idLote = lts.id
																				WHERE dr.estatus = '1' GROUP BY dr.idDetCompra
                                        ) dr2 ON dc.id = dr2.idDetCompra AND dc.idProducto = dr2.idProducto
                                        WHERE c.id = '$idCompra'
                                        GROUP BY dc.id
                                        ORDER BY dc.descripcion ASC";
                            $resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los productos, notifica a tu Administrador.');
                            $cont = 0;
                            $cantTotalResta = 0;
                            while ($lst = mysqli_fetch_array($resProd)) {
                              $lstLotes = ($lst['lotes'] != '') ? '<ul class="list-style-none"><li>'.$lst['lotes'].'</li></ul>' : '' ;
                              $cantResta = $lst['cantResta'] - $lst['cantRecibida'];
                              $bloquea = ($cantResta <= 0) ? 'disabled' : '' ;
                              $cantTotalResta += $lst['cantResta'];
                              echo '<tr>
                                      <td class="text-center">'.++$cont.'</td>
                                      <td>'.$lst['nomProducto'].'</td>
                                      <td class="text-right">'.number_format($lst['cantComprada'],2,'.',',').'</td>
                                      <td class="text-right">'.number_format($lst['cantResta'],2,'.',',').'</td>
                                      <td class="text-right">'.number_format($lst['cantRecibida'],2,'.',',').'</td>
                                      <td>'.$lstLotes.'</td>
                                      <td class="text-center">
                                        <button type="button" class="btn btn-outline-info btn-circle" data-toggle="modal" data-target="#modalRecibeProducto" onclick="agregaRecepcion('.$lst['idDetCompra'].','.$lst['idProducto'].')" '.$bloquea.'>
                                          <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <input type="hidden" id="cantProd-'.$lst['idProducto'].'" value="'.$cantResta.'">
                                      </td>
                                    </tr>';
                            }

                            if ($cantTotalResta == 0) {
                                echo '<input type="hidden" id="cantTotalResta" value="'.$cantTotalResta.'">';
                            }

                           ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <?php
              }
              $fecha = date('Y-m-d');
                 ?>

                 <!-- sample modal content -->
                 <div id="modalRecibeProducto" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                     <div class="modal-dialog">
                         <div class="modal-content border-<?=$pyme;?>">
                             <div class="modal-header bg-<?=$pyme;?>">
                                 <h4 class="modal-title text-white" id="lblPDF">Registro de recepción</h4>
                                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                             </div>
                             <div class="modal-body" id="recibeProductoBody">
                               <ul class="nav nav-tabs" role="tablist">
                                  <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#seleccionaLote" role="tab"><span class="hidden-sm-up"> <span class="hidden-xs-down">Selecciona Lotes</span></a> </li>
                                  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ingresaLote" role="tab"> <span class="hidden-xs-down">Ingresa Lote</span></a> </li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content tabcontent-border">
                                  <div class="tab-pane active" id="seleccionaLote" role="tabpanel">
                                    <form role="form" method="post" id="formRegistraRecepcionDeProd" action="../funciones/registraRecepcionDeProductos.php" onsubmit="return checkSubmit2();">
                                      <div id="listadoLotes">

                                      </div>

                                      <div class="input-group">
                                          <button type="button" class="btn btn-outline-info" onclick="agregarCampos();"><i class="fas fa-plus"></i></button>
                                          <label for="cantLote" class="control-label">&nbsp;&nbsp; Agregar otra cantidad con lote distinto</label>
                                      </div>
                                      <br>

                                      <div class="modal-footer">
                                        <input type="hidden" name="idCompra" id="idCompra" value="<?=$idCompra;?>">
                                        <input type="hidden" name="idRecepcion" id="idRecepcion" value="<?=$idRecepcion;?>">
                                        <input type="hidden" name="idDetCompra" id="idDetCompra" value="">
                                        <input type="hidden" name="idProducto" id="idProducto" value="">
                                        <input type="hidden" name="cantResta" id="cantResta" value="">
                                        <input type="hidden" name="vista" value="2">
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" id="btnRegistraRecepcionProductos" class="btn btn-success waves-effect waves-light">Registrar</button>
                                      </div>
                                    </form>
                                  </div>

                                  <!-- ############################################################### -->
                                  <div class="tab-pane  p-20" id="ingresaLote" role="tabpanel">
                                    <label for="cantLote" class="control-label">Caducidad:</label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="caducidad1"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" step="any" min="<?=$fecha;?>" class="form-control" name="caducidad" id="caducidad">
                                    </div>
                                    <br>
                                    <label for="cantLote" class="control-label">Cantidad:</label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="cantLote1">CNT</span>
                                        </div>
                                        <input type="number" class="form-control" min="0" name="cantidad" placeholder="Ingresa la cantidad" id="cantidad">
                                    </div>
                                    <br>
                                    <div class="modal-footer">
                                      <input type="hidden" name="idProducto" id="idProducto2" value="">
                                      <input type="hidden" id="idDetCompra2" value="">
                                      <input type="hidden" name="cantResta" id="cantResta2" value="">
                                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                      <button type="button" class="btn btn-success waves-effect waves-light" onclick="registraLote();" id="btnRegistraLote">Registrar</button>
                                    </div>
                                  </div>
                              </div>

                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- /.modal -->

                 <div id="modalCierraRegistro" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                     <div class="modal-dialog">
                         <div class="modal-content border-<?=$pyme;?>">
                             <div class="modal-header bg-<?=$pyme;?>">
                                 <h4 class="modal-title text-white" id="lblPDF">Cierre de recepción</h4>
                                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                             </div>
                             <div class="modal-body" id="recibeProductoBody">
                               <form method="post" role="form" action="../funciones/cierraRecibeProductos.php" onsubmit="return checkSubmit2();">
                                 <label for="cantLote" class="control-label">¿Quién Entregó el Producto?</label>
                                 <br>
                                   <input type="text" class="form-control" name="nombreEntrega" placeholder="Ingresa el nombre de quien entrega el Producto" id="nombreEntrega" required>
                                 <br>
                                 <div class="modal-footer">
                                   <input type="hidden" name="idCompra" value="<?=$idCompra;?>">
                                   <input type="hidden" name="tipo" value="2">
                                   <input type="hidden" name="vista" value="2">
                                   <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                   <button type="submit" class="btn btn-success waves-effect waves-light">Terminar registro</button>
                                 </div>
                             </form>
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
    <script src="../assets/libs/jquery-validation/dist/jquery.validate.min-ESP.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjRecibeProductoAdmin'])) {
        echo "notificaBad('".$_SESSION['LZFmsjRecibeProductoAdmin']."');";
        unset($_SESSION['LZFmsjRecibeProductoAdmin']);
      }
      if (isset( $_SESSION['LZFmsjSuccessRecibeProductoAdmin'])) {
        echo "notificaSuc('".$_SESSION['LZFmsjSuccessRecibeProductoAdmin']."');";
        unset($_SESSION['LZFmsjSuccessRecibeProductoAdmin']);
      }


      ?>

      $("#formRegistraRecepcionDeProd").submit(function() {
        var idPd = $("#idProducto").val();
        var cantRestante = $("#cantProd-"+idPd).val();
        var suma = 0;
          $('.cantProductos').each(function(){
                 suma += parseFloat($(this).val());
          });
          //alert('Suma: '+suma+', cantRestante: '+cantRestante);
        if (suma <= cantRestante) {
          //notificaSuc('Excelente, datos enviados');
          return true;
        } else {
          notificaBad('La cantidad de productos que ingresaste excede la cantidad por recibir ('+cantRestante+'), verifica los datos');
          return false;
        }
    });

    reenviaVacio();

    });

    function agregaRecepcion(idDetCompra,idProd){
      var cantProd = $("#cantProd-"+idProd).val();
      console.log('cantProd: '+cantProd);
      $.post("../funciones/listaRecepcionDeLotes.php",
        {idProd:idProd},
      function(respuesta){
        $("#listadoLotes").html(respuesta);
        $("#idProducto").val(idProd);
        $("#idDetCompra").val(idDetCompra);
        $("#idProducto2").val(idProd);
        $("#idDetCompra2").val(idDetCompra);
        $("#cantResta").val(cantProd);
        $("#cantResta2").val(cantProd);
      });
    }

    function agregarCampos(){
      var idProducto = $("#idProducto").val();
      $.post("../funciones/listaRecepcionDeLotes.php",
        {idProd:idProducto},
      function(respuesta){
        $("#listadoLotes").append(respuesta);
      });
    }

    function agregaNuevoLote(){
      var idProd = $("#idProducto").val();
      var idDetCompra = $("#idDetCompra").val();
      $("#modalRecibeProducto").modal('hide');
      $("#modalNuevoLote").modal('show');
      $("#idProducto2").val(idProd);
      $("#idDetCompra2").val(idDetCompra);
    }

    function regresaModalRecepcion(){
      var idProd = $("#idProducto2").val();
      var idDetCompra = $("#idDetCompra2").val();
      agregaRecepcion(idDetCompra,idProd);
      $("#modalNuevoLote").modal('hide');
      $("#modalRecibeProducto").modal('show');
    }

    function registraLote(){
      $("#btnRegistraLote").prop('disabled',true);
      var idProd = $("#idProducto2").val();
      var idCompra = $("#idCompra").val();
      var idRecepcion = $("#idRecepcion").val();
      var nomLote = $("#nomLote").val();
      var caducidad = $("#caducidad").val();
      var cantidad = parseInt($("#cantidad").val());
      var idDetCompra = $("#idDetCompra2").val();
      var cantResta = parseInt($("#cantResta").val());
      var cantTotal = 0;
      cantTotal = cantResta - cantidad;
        if (cantTotal >= 0) {
            $.post("../funciones/registraNuevoLote.php",
          {idCompra:idCompra,nomLote:nomLote, caducidad:caducidad, cantidad:cantidad, idProd:idProd, idRecepcion:idRecepcion, idDetCompra:idDetCompra, cantResta:cantResta},
        function(respuesta){
          var resp = respuesta.split('|');
          if (resp[0] == 1) {
            notificaSuc(resp[1]);
            $("#idRecepcion").val(resp[2]);
            $("#btnRegistraLote").prop('disabled',false);
            $("#nomLote").val('');
            $("#caducidad").val('');
            $("#cantidad").val('');
            listaTablaRecepciones(idCompra);
            $("#modalRecibeProducto").modal('hide');
            agregaRecepcion(idDetCompra,idProd);
          } else {
            notificaBad(resp[1]);
            $("#idRecepcion").val(idRecepcion);
            $("#btnRegistraLote").prop('disabled',false);
          }
        });
      } else {
        console.log('cantidad: '+cantidad+' <= cantResta: '+cantResta+', cantTotal: '+cantTotal);
        notificaBad('No se permite recibir más producto del debido, la cantidad máxima a recibir en esta compra es: '+cantResta);
        $("#btnRegistraLote").prop('disabled',false);
      }
    }

    function listaTablaRecepciones(idCompra){
      $.post("../funciones/listaTablaRecepciones.php",
    {idCompra:idCompra},
  function(respuesta){
    $("#tablaBody").html(respuesta);
  });
    }

    function reenviaVacio(){
      var cantResta = parseInt($("#cantTotalResta").val());
      console.log('No hay productos por recibir. cantResta: '+cantResta);
      if (cantResta == 0) {
        //console.log('Ejecuta Form. cantResta: '+cantResta);
        notificaBad('La compra ya fue recibida en su totalidad.');
        setTimeout(function(){ reenviar(); }, 3000);
      }
    }

    function reenviar(){
      $('<form action="adminRecibeProductos.php" method="POST"><input type="hidden" name="idCompra" value="0"></form>').appendTo('body').submit();
    }

    var statSend = false;
    var statSend2 = false;
    var no = 0;
    function evitaDobleSubmit(boton,formulario){
      if ($('#'+formulario).validate()){
        if (no == 0 && statSend == true) {
          no = 1;
          statSend = false;
          //return false;
        }
        checkSubmit();
        $("#"+boton).prop("disabled",true);
          $("#"+formulario).submit();
        } else {
          no = 0;
          statSend = false;
          $("#"+boton).prop("disabled",false);
        }

    }

    function checkSubmit() {
        if (!statSend) {
            statSend = true;
            return true;
        } else {
            if (no == 0) {
              notificaBad("Faltan datos, por favor revisa tu información...");
              return false;
            } else {
              notificaBad("Se ha enviado la información, por favor espere...");
              return false;
            }
        }
    }

    function checkSubmit2() {
        if (!statSend2) {
            statSend2 = true;
            return true;
        } else {
            notificaBad("Se está enviendo la información, por favor espere...");
            return false;
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
