<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad-1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
require_once('include/connect.php');
$info->Acceso($nameLk);
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
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
   <link rel="icon" type="image/icon" sizes="16x16" href="assets/images/<?=$pyme;?>.ico">
    <title><?=$info->nombrePag;?></title>

    <!-- This Page CSS -->
    <link rel="stylesheet" type="text/css" href="assets/libs/select2/dist/css/select2.min.css">
    <link href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/css-chart/css-chart.css">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
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
                <?=$info->customizer();?>

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
                        <audio id="player" src="assets/images/soundbb.mp3"> </audio>

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
                <?php
                #print_r($_SESSION);
                if (!empty($_POST['folioVenta'])) {
                  $idVenta = $_POST['folioVenta'];
                } else {
                  $idVenta = 0;
                }

                $filtroid = ($idVenta > 0) ? $idVenta : '' ;
                if ($idVenta > 0) {

                  $sqlCon = "SELECT v.estatus,v.idSucursal, s.nombre AS nomSucursal, v.idCorte, COUNT(vf.id) AS facturada, SUM(IF(pv.idFormaPago = 2, 1, 0)) AS cheques,
                              SUM(IF(pv.idFormaPago = 3, 1, 0)) AS transferencia,SUM(IF(pv.idFormaPago = 4, 1, 0)) AS tarjetaD,
                              SUM(IF(pv.idFormaPago = 5, 1, 0)) AS tarjetaC,SUM(IF(pv.idFormaPago = 6, 1, 0)) AS boletas
                              FROM ventas v
                              INNER JOIN sucursales s ON v.idSucursal = s.id
                              LEFT JOIN vtasfact vf ON v.id = vf.idVenta
                              LEFT JOIN pagosventas pv ON v.id = pv.idVenta
                              WHERE v.id = '$idVenta' LIMIT 1";
                  $resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la venta, notifica a tu Administrador');
                  $numRows = mysqli_num_rows($resCon);
                  if ($numRows > 0) {
                    $val = $cont = 0;
                    $mensaje ='';
                    $dat = mysqli_fetch_array($resCon);
                    $val = $dat['estatus'];
                    $idSuc = $dat['idSucursal'];
                    $nomSuc = $dat['nomSucursal'];
                    #echo '$VAL: '.$val;
                    if ($val == '1') {
                       $mensaje .= 'Venta abierta,';
                       $cont++;
                    } elseif ($val == '3') {
                      $mensaje .= ' Venta Cancelada,';
                      $cont++;
                    } elseif ($val == '5') {
                      $mensaje .= ' Venta Cancelada en 0,';
                      $cont++;
                    } else {
                      $mensaje .= '';
                    }
                    if ($dat['idCorte'] > 0) {
                      $mensaje .= ' Ya se encuentra en un corte,';
                      $cont++;
                    }
                    if ($dat['facturada'] > 0) {
                      $mensaje .= ' Ya se encuentra facturada,';
                      $cont++;
                    }

                    if ($dat['cheques'] > 0) {
                      $mensaje .= ' Contiene pago con cheques,';
                      $cont++;
                    }
                    if ($dat['transferencia'] > 0) {
                      $mensaje .= ' Contiene pago con transferencia,';
                      $cont++;
                    }
                    if ($dat['tarjetaD'] > 0) {
                      $mensaje .= ' Contiene pago con tarjeta de débito,';
                      $cont++;
                    }
                    if ($dat['tarjetaC'] > 0) {
                      $mensaje .= ' Contiene pago con tarjeta de crédito,';
                      $cont++;
                    }
                    if ($dat['boletas'] > 0) {
                      $mensaje .= ' Contiene boletas,';
                      $cont++;
                    }
                    if ($idSuc != $idSucursal) {
                      $mensaje .= ' Pertenece a '.$nomSuc;
                      $mensaje2 = ' y de la sucursal donde se <b class="text-danger">realizó la venta</b>';
                      $cont++;
                    } else {
                      $mensaje = '';
                    }
                    if ($cont > 0) {
                      $mensaje = trim($mensaje, ',');
                    }

                  }
                }
                 ?>
                <section id="contenido">
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
                    <div class="col-md-12">
                      <div class="card border-<?=$pyme;?>">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="card-title text-white">Listado de productos a Devolver</h4>
                          </div>
                          <div class="card-body">
                            <div class="col-md-3">
                              <form role="form" action="#" method="post">
                              <div class="input-group">
                                    <input type="number" class="form-control" id="folioVenta" min="0" name="folioVenta" placeholder="Ingresa el folio de venta" value="<?=$filtroid;?>" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-<?=$pyme;?>" type="button"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                              </form>
                            </div>
                            <br>
                            <div class="col-md-9"></div>
                            <?php
                            if ($cont > 0) {
                             echo '<div class="text-center">
                                    <h5>No se permite la devolución de la venta por lo siguiente: <b class="text-danger">'.$mensaje.'</b>,  sólo se permiten devolver ventas con pago en <b class="text-danger">Efectivo</b> o <b class="text-danger">Crédito</b>'.$mensaje2.'</h5>
                                   </div>';
                            } else {
                            }
                            if ($cont == 0 AND $idVenta > 0) {
                             ?>
                             <div class="row">
                               <div class="col-md-12">
                                 <center>
                                   <h5 class="text-info">
                                     <b class="text-danger">Nota: </b>Selecciona los productos y el lote correspondiente para agregar al listado, si deseas cancelar todo, selecciona el botón <b class="text-danger">"Cancelar todo"</b>
                                   </h5>
                                 </center>
                                 <br>
                               </div>
                               <div class="col-md-3">
                                 <?php
                                 $sql="SELECT p.id,p.descripcion
                                        FROM detventas dv
                                        INNER JOIN productos p ON dv.idProducto = p.id
                                        WHERE dv.idVenta = $idVenta";
                                 //echo $sql;
                                 $res=mysqli_query($link,$sql);
                                 $cont = 0;
                                 while($rows=mysqli_fetch_array($res))
                                 {
                                   if ($cont == 0) {
                                     #<select class="select2 form-control custom-select select2-hidden-accessible" style="width: 100%; height:100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                     echo '<select class="select2 form-control custom-select" onChange="listarLotes(this.value,'.$idVenta.');" id="productos" style="width: 90%; height:100%;">';
                                     echo '<option value="">Selecciona un Producto</option>';
                                   }
                                   echo '<option value="'.$rows['id'].'">'.$rows['descripcion'].'</option>';
                                   $cont++;

                                 }
                                 echo '</select>';
                                  ?>
                               </div>
                               <div class="col-md-4" id="divLotes">
                                  <select class="form-control" id="lotestocks">
                                       <option value="">Selecciona primero un Producto para ver el Lote</option>

                                   </select>
                               </div>
                               <div class="col-md-2">
                                   <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ingresa el folio de venta" min="0"  value="0" disabled>
                                   <input type="hidden" id="cantMaxima" value="">
                               </div>
                               <div class="col-md-3">
                                   <button type="button" class="btn btn-success" onclick="agregaLoteATabla();" type="button">Agregar a la lista</button>
                                   <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCancelaVenta">Cancelar Venta Completa</button>
                               </div>
                             </div>
                             <br>
                            <div class="col-md-12" id="divTablaProductos" style="display:none;">
                              <div class="table-responsive">
                                <table class="table product-overview">
                                  <thead>
                                    <tr>
                                      <th class="text-center">#</th>
                                      <th>Nombre</th>
                                      <th class="text-center">Cantidad</th>
                                      <th>Lote</th>
                                      <th class="text-center">Devolver</th>
                                      <th class="text-center">Acciones</th>
                                    </tr>
                                  </thead>
                                  <tbody id="cuerpoTablaDev">
                                    <input id="contador" value="1" type="hidden">

                                  </tbody>
                                </table>
                                <br>
                                  <textarea class="form-control" rows="3" id="descripcion" placeholder="Ingresa el motivo de la devolución"></textarea>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-info" onclick="devuelveProductos(<?=$idVenta;?>);" <?=$disabled;?>>Devolver seleccionados</button>
                                </div>
                              </div>
                            </div>
                          <?php } ?>
                          </div>
                      </div>
                    </div>
                  </div>

            </section>

            <!-- sample modal content -->
            <div id="modalCancelaVenta" class="modal bs-example-modal-lg fade show" tabindex="-1" role="dialog" aria-labelledby="modalBoletasLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                          <div class="col-md-12">
                            <center><div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div></center>
                          </div>
                          <div class="col-md-12">
                            <h3 class="text-dark text-center"><b>¿Deseas cancelar la venta?</b></h3>
                          </div>
                          <!--
                         <form role="form" autocomplete="off" method="post" action="#">
                         -->
                         <form role="form" autocomplete="off" method="post" action="funciones/cancelaVentaProd.php">
                            <div class="col-md-12">
                              <h5 class="text-center">¡Se cancelará la venta con todos los productos!</h5>
                              <br>
                              <center><b class="text-info">Ingresa el motivo de la cancelación</b></center>
                            </div>
                            <div class="col-md-12">
                              <textarea name="desc" id="desc" class="form-control" value="" rows="3" required></textarea>
                            </div>

                          <br>
                          <div class="row">
                            <input type="hidden" name="tipoVenta" value="2">
                            <input type="hidden" name="idVenta" value="<?=$idVenta;?>">
                            <div class="col-md-12 text-center">
                              <button type="button" class="btn btn-info"  data-dismiss="modal"><h3>No</h3></button>&nbsp;
                              <button type="submit" class="btn btn-danger"><h3>&nbsp;Si&nbsp;</h3></button>
                            </div>
                          </div>
                        </form>
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
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/app.init.mini-sidebar.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!-- dataTable js -->
    <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min-ESP.js"></script>
    <script src="dist/js/pages/datatable/datatable-basic.init.js"></script>
    <!--Custom JavaScript -->
    <script src="assets/scripts/basicFuctions.js"></script>
    <script src="assets/scripts/jquery.number.js"></script>
    <script src="assets/scripts/jquery.number.min.js"></script>
    <script src="assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="assets/scripts/notificaciones.js"></script>
    <script src="assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="assets/libs/sweetalert2/sweet-alert.init.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
    #  include('funciones/basicFuctions.php');
    #  alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjDevoluciones'])) {
				echo "notificaBad('".$_SESSION['LZFmsjDevoluciones']."');";
				unset($_SESSION['LZFmsjDevoluciones']);
			}
			if (isset( $_SESSION['LZFmsjSuccessDevoluciones'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessDevoluciones']."');";
        unset($_SESSION['LZFmsjSuccessDevoluciones']);
			}
      if (isset( $_SESSION['cambioDevoluciones'])) {
        $msj = explode('|', $_SESSION['cambioDevoluciones']);
				echo "ocultaAlerta('Debes devolver al cliente la cantidad de: $ <b class=\"text-success\">".number_format($msj[0],2,'.',',')."',".$msj[1].");";
        unset($_SESSION['cambioDevoluciones']);
			}
      ?>
    });

    function devuelveProductos(idVenta){
      var desc = $("#descripcion").val();
          if (idVenta > 0) {
            if (desc != '') {
          var varCantidad = $('.cantidad').map(function(){
            return this.value;
          }).get();

          var varLotes = $('.idLotes').map(function(){
            return this.value;
          }).get();

          var varDetVenta = $('.idDetVenta').map(function(){
            return this.value;
          }).get();

          var varCantidades = varCantidad.join(', ');

          var varIdLotes = varLotes.join(', ');

          var varIdDetVenta = varDetVenta.join(', ');

          $('<form action="funciones/devuelveProductos.php" method="POST"><input type="hidden" name="idVenta" value="'+idVenta+'"><input type="hidden" name="desc" value="'+desc+'"><input type="hidden" name="varIdLotes" value="'+varIdLotes+'"><input type="hidden" name="varIdDetVenta" value="'+varIdDetVenta+'"><input type="hidden" name="varCantidades" value="'+varCantidades+'"></form>').appendTo('body').submit();
        } else {
          notificaBad("No ingresaste un motivo de su devolución, debes ingresar uno para continuar con la devolución.");
        }
      } else {
        notificaBad("No se reconoció la venta, verifica que hayas ingresado un folio de Venta.");
      }
    }


    //Success Message
    function ocultaAlerta(msj,idVenta){
    //  alert('idVenta: '+idVenta);
        Swal.fire("Devolución Realizada", msj, "success").then(function(){
          $('<form action="imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="'+idVenta+'"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
        });
    }


    function listarLotes(idProd,idVenta){
      if (idProd > 0) {
        $.post("funciones/listaLotes.php",
      {idProd:idProd, idVenta:idVenta},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#divLotes").html(resp[1]);
      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció el producto, actualiza y vuelve a intentarlo, si persis notifica a tu Administrador.');
      }
    }

    function habilitaCant(idLote){
      if (idLote > 0) {
        var cant = $("#lote-"+idLote).val();
        $("#cantMaxima").val(cant);
        $("#cantidad").prop('disabled',false);
        $("#cantidad").attr('max',cant);
      } else {
         notificaBad('No se reconoció el lote, vuelve a intentarlo, si persiste notifica a tu Administrador.');
      }
    }

    function agregaLoteATabla(){
      var cant = parseInt($("#cantidad").val());
      var cantMax = parseInt($("#cantMaxima").val());
      if (cant > 0) {
        if (cant <= cantMax) {
        //  console.log("Entro y muestra la fila");
          var cont = parseInt($("#contador").val());
            var fila = '';
            var idProd = $("#productos option:selected").val();
            var nomProducto = $("#productos option:selected").html();
            var idLote = $("#lotestocks option:selected").val();
            var idDetVenta = $("#idDetVenta-"+idLote).val();
            var nomLote = $("#lotestocks option:selected").html();

            var varBase3 = $('.idLotes').map(function(){
              return this.value;
            }).get();
            var varIdLotes = varBase3.join(', ');
            if (varIdLotes.indexOf(idLote) == -1) {

            fila += '<tr id="fila-'+cont+'">';
            fila += '<td class="text-center">'+cont+'</td>';
            fila += '<td>'+nomProducto+'</td>';
            fila += '<td class="text-center">'+cantMax+'</td>';
            fila += '<td>'+nomLote+'</td>';
            fila += '<td class="text-center">'+cant+'</td>';
            fila += '<td class="text-center"><button class="text-danger" onClick="eliminaFila('+cont+');"><i class="fas fa-trash"></i></button></td>';
            fila += '<input type="hidden" class="idLotes" value="'+idLote+'">';
            fila += '<input type="hidden" class="cantidad" value="'+cant+'">';
            fila += '<input type="hidden" class="idDetVenta" value="'+idDetVenta+'">';
            fila += '</tr>';
            $("#cuerpoTablaDev").append(fila);
            var newCont = cont + 1 ;
            $("#divTablaProductos").show();
            $("#contador").val(newCont);
          } else {
            notificaBad('Ya se encuentra ingresado ese lote en la lista, primero debe eliminar ese registro de la lista para agregarlo.');
          }
        } else {
        //  console.log("cantidad: "+cant+", cantMax: "+cantMax);
          notificaBad('La cantidad no debe ser mayor a '+cantMax);
          $("#cantidad").val(0);
        }
      } else {
        notificaBad('La cantidad no debe ser menor a 0');
        $("#cantidad").val(0);
      }
    }

    function eliminaFila(no){
      $("#fila-"+no).remove();
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
