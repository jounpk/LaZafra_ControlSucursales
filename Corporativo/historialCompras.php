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
    <link rel="stylesheet" type="text/css" href="../assets/extra-libs/prism/prism.css">
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
                  .alinearCentro{
                    display: inline-block;
                     text-align: center;
                     vertical-align:middle;
                     line-height: 150%;
                     padding-top: 15%;
                  }

                  .btn-circle-mini {
                    width: 30px;
                    height: 30px;
                    text-align: center;
                    padding: 6px 0;
                    font-size: 12px;
                    line-height: 1.428571429;
                    border-radius: 15px;
                  }

                </style>
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
                <!--  Comienza la solicitud de ventas especiales -->


                <!--  Comienza el historial de ventas especiales -->
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="card border-<?=$pyme;?>">
                            <div class="card-header bg-<?=$pyme;?>">
                              <h4 class="m-b-0 text-white">Historial</h4>
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

                          if (isset($_POST['proveedor']) && $_POST['proveedor'] > 0) {
                            $proveedor = $_POST['proveedor'];
                            $filtroProveedor = " AND p.id = '$proveedor'";
                          } else {
                            $proveedor = '';
                            $filtroProveedor = '';
                          }


                          #echo '<br>$sucursal: '.$sucursal;
                           ?>
                         <div class="col-md-12">
                           <div class="row">
                             <div class="col-md-2"></div>
                             <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                               <div class="col-md-12">
                                 <h5 class="m-t-30 text-center text-<?=$pyme;?>">Selecciona un rango de Fechas (Fecha de Compra)</h5>
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
                                     <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona un Proveedor:</h5>
                                   </div>
                                   <div class="col-md-5">
                                   </div>
                                 </div>
                               </div>
                               <div class="row">
                               <div class="col-md-8">
                                 <select class="select2 form-control custom-select" name="proveedor" id="proveedor" style="width: 95%; height:100%;">
                                   <?php
                                   echo '<option value="">Ingresa el Nombre del Proveedor</option>';
                                   $sql="SELECT id,nombre FROM proveedores WHERE estatus = 1";
                                 #  echo $sql;
                                   $res=mysqli_query($link,$sql);
                                    while ($rows = mysqli_fetch_array($res)) {
                                      $activa = ($proveedor == $rows['id']) ? 'selected' : '' ;
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
                         <br>
                         <div class="col-md-12">
                           <div id="validation" class="m-t-10 jsgrid" style="position: relative; height: auto; width: 100%;">
                             <div class="table-responsive">
                               <table class="table table-bordered dataTable" id="zero_config2">
                                 <thead class="bg-<?=$pyme;?> text-white">
                                   <tr>
                                     <th class="text-center">#</th>
                                     <th class="text-center">Folio</th>
                                     <th>Proveedor</th>
                                     <th>Empresa</th>
                                     <th>Comprador</th>
                                     <th class="text-center">Fecha de Compra</th>
                                     <th class="text-center">Total</th>
                                     <th class="text-center">Monto a Crédito</th>
                                     <th>Nota</th>
                                     <th class="text-center">Comprobante</th>
                                     <th class="text-center">Estado</th>
                                     <th class="text-center">Acciones</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                                   <?php
                                   #/*
                                     $sqlAuto = "SELECT c.id AS idCompra,c.nota, c.total, DATE_FORMAT(c.fechaCompra, '%Y-%m-%d') AS 'fechaCompra', c.estatus, e.nameCto AS 'nomEmpresa',p.nombre AS 'nomProveedor',
                                                CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS 'nomComprador',c.doctoCompra,c.estatusCompra,c.montoAcredito,c.doctoPago,c.extDoctoCompra,c.extDoctoPago
                                                FROM compras c
                                                INNER JOIN proveedores p ON c.idProveedor = p.id
                                                INNER JOIN empresas e ON p.idEmpresa = e.id
                                                INNER JOIN segusuarios u ON c.idUserReg = u.id
                                                WHERE c.fechaCompra BETWEEN '$fechaInicial' AND '$fechaFinal'
                                                ORDER BY c.id DESC";
                                     #echo '$sqlAuto: '.$sqlAuto;
                                     $resAuto = mysqli_query($link,$sqlAuto) or die('Problemas al consultar los créditos pendientes, notifica a tu Administrador.');
                                     $cont = 0;
                                     while ($lst = mysqli_fetch_array($resAuto)) {
                                       $verCompra = ($lst['extDoctoCompra'] == 'pdf') ? 'verPDF' : 'verIMG' ;
                                       $verPago = ($lst['extDoctoPago']  == 'pdf') ? 'verPDF' : 'verIMG' ;
                                       if ($lst['doctoCompra'] != '' && $lst['estatus'] == 2) {
                                         $onclick = 'onclick="'.$verCompra.'('.$lst['idCompra'].',\'' . $lst['doctoCompra'] . '\');"';
                                         $disabled = '';
                                         $colorBtn = 'success';
                                         $icono = 'fas fa-file-pdf';
                                         $btnTitulo = 'Ver Comprobante de Compra';
                                       } elseif ($lst['doctoCompra'] == '' && $lst['estatus'] == 2) {
                                         $onclick = 'data-toggle="modal" data-target="#modalCargaDocto" onclick="cargaDocto('.$lst['idCompra'].');"';
                                         $icono = 'far fa-folder-open';
                                         $disabled = '';
                                         $colorBtn = 'info';
                                         $btnTitulo = 'Subir Archivo';
                                       } else {
                                         $disabled = 'disabled';
                                         $icono = 'fas fa-times';
                                         $onclick = '';
                                         $colorBtn = 'gray';
                                         $btnTitulo = 'Sin acciones';
                                       }

                                       switch ($lst['estatus']) {
                                         case '2':
                                           $color = '';
                                           $estado = 'Compra cerrada';
                                           $boton = '<button type="button" class="btn btn-info btn-circle-mini muestraSombra" onClick="imprimeTicketCompra('.$lst['idCompra'].');"" data-toggle="tooltip" data-placement="top" title="Imprimir ticket de Compra"><i class="fas fa-print"></i></button>';
                                           $doctoPdf = '<button class="btn btn-outline-'.$colorBtn.' btn-circle-mini muestraSombra" '.$onclick.' title="'.$btnTitulo.'" '.$disabled.'><i class="'.$icono.'"></i></button>';
                                           $btnRecepciones = '<button class="btn btn-purple btn-circle-mini muestraSombra" title="Ver Recepciones" onClick="verRecepciones('.$lst['idCompra'].')"><i class="fas fa-bars"></i></button>';
                                           break;
                                         case '3':
                                           $color = 'class="table-danger"';
                                           $estado = 'Compra cancelada';
                                           $boton = '<button type="button" class="btn btn-danger btn-circle-mini muestraSombra" data-toggle="tooltip" data-placement="top" title="Sin Acciones"><i class="fas fa-times"></i></button>';
                                           $doctoPdf = '<button class="btn btn-outline-'.$colorBtn.' btn-circle-mini muestraSombra" '.$onclick.' title="'.$btnTitulo.'" '.$disabled.'><i class="'.$icono.'"></i></button>';
                                           $btnRecepciones = '';
                                           break;

                                         default:
                                           $color = 'class="table-warning"';
                                           $estado = 'En proceso de Compra';
                                           $boton = '<button type="button" class="btn btn-warning btn-circle-mini muestraSombra" data-toggle="tooltip" data-placement="top" title="Sin Acciones"><i class="fas fa-exclamation-triangle"></i></button>';
                                           $doctoPdf = '<button class="btn btn-outline-'.$colorBtn.' btn-circle-mini muestraSombra" '.$onclick.' title="'.$btnTitulo.'" '.$disabled.'><i class="'.$icono.'"></i></button>';
                                           $btnRecepciones = '';
                                           break;
                                       }

                                       $btnConfirmaPago = ($lst['estatusCompra'] == '1') ? '<button class="btn btn-secondary btn-circle-mini muestraSombra" onclick="cargaDocto2('.$lst['idCompra'].');" data-toggle="modal" data-target="#confirmaCompra" title="Confirma Pago"><i class="fas fa-hand-holding-usd"></i></button>' : '' ;
                                       $btnColor = ($lst['estatusCompra'] == 2) ? 'class="table-success"' : $color ;
                                       $doctoCompruebaPago = ($lst['doctoPago'] != '' && $lst['estatusCompra'] == 2) ? '<button class="btn btn-outline-danger btn-circle-mini muestraSombra" onclick="'.$verPago.'('.$lst['idCompra'].',\'' . $lst['doctoPago'] . '\');" title="Comprobante de pago"><i class="fas fa-file-pdf"></i></button>' : '' ;

                                       echo '<tr '.$btnColor.'>
                                               <td class="text-center">'.++$cont.'</td>
                                               <td class="text-center">'.$lst['idCompra'].'</td>
                                               <td>'.$lst['nomProveedor'].'</td>
                                               <td>'.$lst['nomEmpresa'].'</td>
                                               <td>'.$lst['nomComprador'].'</td>
                                               <td class="text-center">'.$lst['fechaCompra'].'</td>
                                               <td class="text-center">$'.number_format($lst['total'],2,'.',',').'</td>
                                               <td class="text-center">$'.number_format($lst['montoAcredito'],2,'.',',').'</td>
                                               <td>'.$lst['nota'].'</td>
                                               <td class="text-center">
                                                '.$doctoPdf.'
                                                '.$doctoCompruebaPago.'
                                                </td>
                                               <td class="text-center">'.$estado.'</td>
                                               <td class="text-center">
                                                '.$boton.'
                                                '.$btnRecepciones.'
                                                '.$btnConfirmaPago.'
                                                </td>
                                             </tr>';
                                     }
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
                  <br>

                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->

                <div class="modal fade" id="verIMG" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verIMGContent">

                        <h4 class="modal-title" id="verIMGTitle"> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                      </div>
                      <div class="modal-body" id="verIMGBody">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->


                <div class="modal fade" id="confirmaCompra" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header bg-<?=$pyme;?> text-white" id="confirmaCompraContent">
                        <h4 class="modal-title" id="confirmaCompraTitle"> Comprobante de Pago</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>

                      </div>
                      <div class="modal-body" id="confirmaCompraBody">
                        <form role="form" action="../funciones/registraComprobanteCompra.php" method="post" enctype="multipart/form-data" onsubmit="return checkSubmit();">
                          <div>
                            <label class="control-label">Selecciona el comprobante de pago</label>
                            <input type="file" id="comprobantePagoCompra" name="comprobantePagoCompra" accept="application/pdf,image/jpeg">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                          <input type="hidden" name="idCompra" id="idCompra2" value="">
                          <button type="submit" class="btn btn-success">Cargar Comprobante</button>
                        </div>
                      </form>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->


                <div class="modal fade" id="modalRecepciones" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header" id="modalRecepcionesContent">

                        <h4 class="modal-title" id="modalRecepcionesTitle"> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                      </div>
                      <div class="modal-body" id="modalRecepcionesBody">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->



                <div class="modal fade" id="modalCargaDocto" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xs">
                    <div class="modal-content">
                      <div class="modal-header bg-<?=$pyme;?>" id="modalCargaDoctoContent">
                        <h4 class="modal-title text-white" id="modalCargaDoctoTitle">Carga de Comprobante</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>

                      </div>
                      <div class="modal-body" id="modalCargaDoctoBody">
                        <form method="post" role="form" action="../funciones/cargaComprobanteCompra.php" enctype="multipart/form-data" onsubmit="return checkSubmit();">
                          <div>
                            <label class="control-label">Ingresa el comprobante de la compra</label>
                            <input type="file" accept="application/pdf,image/jpeg" class="form-control" id="comprobanteCompra" name="comprobanteCompra" required>
                          </div>
                          <br>
                          <div class="modal-footer">
                            <input type="hidden" name="idCompra" id="idCompra" value="">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Cargar Comprobante</button>
                          </div>
                        </form>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

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
    <script src="../assets/libs/block-ui/jquery.blockUI.js"></script>
    <script src="../assets/extra-libs/block-ui/block-ui.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>
    <script src="../assets/extra-libs/prism/prism.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjCorporativoHistorialCompras'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCorporativoHistorialCompras']."');";
				unset($_SESSION['LZFmsjCorporativoHistorialCompras']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCorporativoHistorialCompras'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCorporativoHistorialCompras']."');";
        unset($_SESSION['LZFmsjSuccessCorporativoHistorialCompras']);
			}
      ?>
      $("#zero_config2").DataTable();
    });

    function imprimeTicketCompra(idCompra){
      $('<form action="../funciones/ticketLanzaCompra.php" target="_blank" method="POST"><input type="hidden" name="idCompra" value="'+idCompra+'"></form>').appendTo('body').submit();
    }

    function imprimeTicketRecepcion(idRecepcion){
      $('<form action="../funciones/ticketLanzaRecepcion.php" target="_blank" method="POST"><input type="hidden" name="idRecepcion" value="'+idRecepcion+'"></form>').appendTo('body').submit();
    }

      function aceptaVentaEsp(idVenta,tipo){
        if (idVenta > 0) {
          bloqueaCard('cardPendientes',1);
        $.post("../funciones/autorizaVtaEsp.php",
        {idVenta:idVenta, tipo:tipo},
        function(respuesta){
          var resp = respuesta.split('|');
          if (resp[0] == 1) {
            bloqueaCard('cardPendientes',2);
            notificaSuc(resp[1]);
            setTimeout( function(){
                location.reload();
             }, 2000);
          } else {
            bloqueaCard('cardPendientes',2);
            notificaBad(resp[1]);
          }
        });
      }else {
        notificaBad('No se reconoció la venta, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador.');
        }
      }

      function verIMG(noCompra,link) {
        console.log('verIMG');
        $("#verIMGTitle").html('<b>Compra ' + noCompra + '</b>');
        $("#verIMGBody").html('<img class="img-thumbnail responsive" src="../' + link + '" width="100%"  >');
        $('#verIMG').modal('show');
        console.log('noCompra: '+noCompra+', Link: '+link+', ext: '+ext);
      }

      function verPDF(noCompra,link) {
        console.log('verPDF');
        $("#verIMGTitle").html('<b>Compra ' + noCompra + '</b>');
        $("#verIMGBody").html('<embed src="../' + link + '" type="application/pdf" width="100%" height="600"  ></embed>');
        $('#verIMG').modal('show');
        console.log('noCompra: '+noCompra+', Link: '+link+', ext: '+ext);
      }

      var statSend = false;
      function checkSubmit() {
          if (!statSend) {
              statSend = true;
              return true;
          } else {
              notificaBad("Se ha enviado la información, por favor espere...");
              return false;
          }
      }

      function cargaDocto(idCompra){
        $("#idCompra").val(idCompra);
      }

      function cargaDocto2(idCompra){
        $("#idCompra2").val(idCompra);
      }

      function verRecepciones(idCompra) {
        $.post("../funciones/listadoDeRecepciones.php",
      {idCompra:idCompra},
      function(respuesta){
        $("#modalRecepcionesBody").html(respuesta);
        $("#modalRecepcionesTitle").html('Recepciones de la Compra <b>' + idCompra + '</b>');
        $('#modalRecepciones').modal('show');
      });

        //console.log('Link: '+link);
      }

      var statSend = false;
    function checkSubmit() {
        if (!statSend) {
            statSend = true;
            return true;
        } else {
              notificaBad("Se ha enviado la información, por favor espere...");
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
