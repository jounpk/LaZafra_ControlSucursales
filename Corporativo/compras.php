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
$idUser = $_SESSION['LZFident'];
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
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
              <?php
              $sqlCon = "SELECT c.*,p.nombre AS nomProveedor
                          FROM compras c
                          INNER JOIN proveedores p ON c.idProveedor = p.id
                          WHERE c.estatus = '1' AND c.idUserReg = '$idUser' AND c.idSucursal = '$idSucursal' LIMIT 1";
              $resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar las compras, notifica a tu Administrador.');
              $cmp = mysqli_fetch_array($resCon);
              $cantCmp = mysqli_num_rows($resCon);
              $idCompra = $cmp['id'];
              $nomProv = $cmp['nomProveedor'];
              $fecha = $cmp['fechaCompra'];

              if ($cantCmp > 0) {
                $style = '';
                $style2 = 'style="display:none;"';
              } else {
                $style = 'style="display:none;"';
                $style2 = '';
              }

                $sqlEmp = "SELECT id,nombre FROM empresas WHERE estatus = '1'";
                $resEmp = mysqli_query($link,$sqlEmp) or die('Problemas al consultar las Empresas, notifica a tu Administrador.');
                $listaEmp = '';
                while ($e = mysqli_fetch_array($resEmp)) {
                  $listaEmp .= '<option value="'.$e['id'].'">'.$e['nombre'].'</option>';
                }

                $sqlProv = "SELECT id,nombre FROM proveedores WHERE estatus = '1' AND idEmpresa = 1";
                $resProv = mysqli_query($link,$sqlProv) or die('Problemas al consutlar los Proveedores, notifica a tu Administrador.');
                $listaProv = '';
                while ($p = mysqli_fetch_array($resProv)) {
                  $listaProv .= '<option value="'.$p['id'].'">'.$p['nombre'].'</option>';
                }
               ?>
               <br>
                  <div class="card border-<?=$pyme;?>" <?=$style2;?>>
                    <div class="card-header bg-<?=$pyme;?>">
                      <h4 class="text-center text-white">Inicio</h4>
                    </div>
                    <div class="card-body">
                      <form role="form" method="post" action="../funciones/registraNuevaCompra.php">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="control-label text-info"><h5><b>Empresa que Compra</b></h5></label>
                            <select class="select2 form-control custom-select" id="empresaCompra" name="empresaCompra" onchange="muestraProveedores(this.value);" style="width: 100%; height:100%;">
                              <?=$listaEmp;?>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <label class="control-label text-info"><h5><b>Proveedor</b></h5></label>
                            <div id="selectProveedor">
                              <select class="select2 form-control custom-select" id="proveedorCompra" name="proveedorCompra" style="width: 100%; height:100%;">
                                <?=$listaProv;?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <label class="control-label text-info"><h5><b>Fecha de Compra</b></h5></label>
                            <input type="date" class="form-control" id="fechaCompra" name="fechaCompra" required>
                          </div>
                          <div class="col-md-3">
                            <label class="control-label text-info"><h5><b>&nbsp;</b></h5></label>
                            <button type="submit" class="btn btn-success form-control">Aceptar</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                  <!-- =================================================================================================== -->

                <div class="row" <?=$style;?>>
                    <div class="col-md-8">
                      <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="text-white">Ingreso del Producto</h4>
                        </div>
                        <div class="card-body">
                          <form role="form" method="post" action="../funciones/capturaProductoEnCompra.php">
                            <div class="row">
                              <label class="control-label" for="selProd">Selecciona el Producto</label>
                              <select class="select2 form-control custom-select" id="producto" name="producto1" onchange="selCosto(this.value);" style="width: 100%; height:100%;">
                                <?php
                                $sql="SELECT id,descripcion,prioridad,costo FROM productos ORDER BY descripcion ASC";
                                //echo $sql;
                                $res=mysqli_query($link,$sql);
                                $costos = '';
                                echo '<option value="" disabled selected></option>';
                                while ($rows = mysqli_fetch_array($res)) {
                                  echo '<option value="'.$rows['id'].'">'.$rows['descripcion'].'</option>';
                                  $costos .= '<input type="hidden" id="costoProd-'.$rows['id'].'" value="'.$rows['costo'].'">';
                                }
                                 ?>
                              </select>
                              <?=$costos;?>
                            </div>
                            <br>
                            <hr>
                            <label class="control-label text-info"><h4><b>Sobre el Producto</b></h4></label>
                            <div class="row">
                              <div class="col-md-4">
                                <label class="control-label">Unidad</label>
                                <input type="text" id="unidades" name="unidades" class="form-control" value="Pieza">
                              </div>
                              <div class="col-md-3">
                                <label class="control-label">Precio Unitario</label>
                                <input type="text" id="costo" name="costo" class="form-control" step="0.01" onkeyup="mascaraMonto(this,Monto)" min="0" placeholder="Ingresa el precio" required>
                              </div>
                              <div class="col-md-3">
                                <label class="control-label">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control" min="0" placeholder="Ingresa la cantidad" required>
                              </div>
                              <div class="col-md-2">
                                <label class="control-label">&nbsp;</label>
                                <input type="hidden" name="idCompra" value="<?=$idCompra;?>">
                                <button type="submit" id="enviar" class="btn btn-block btn-success"><i class="fas fa-plus"></i> Agregar</button>
                              </div>
                            </div>
                        </form>
                          <br><hr><br>
                          <div class="row">
                            <h4 class="text-info">&nbsp;<b>Listado</b></h4>
                            <div class="table-responsive">
                              <table class="table product-overview table-striped">
                                <thead class="bg-<?=$pyme;?>">
                                  <tr class="text-white">
                                    <th class="text-center">#</th>
                                    <th>Nombre</th>
                                    <th>Unidad</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Precio Unitario</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Acciones</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $sqlProd = "SELECT dc.*, IF(dc.idProducto > 0,p.descripcion,dc.nombreProducto) AS descripcion
                                              FROM detcompras dc
                                              LEFT JOIN productos p ON dc.idProducto = p.id
                                              WHERE dc.idCompra = '$idCompra'";
                                  $resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los productos, notifica a tu Administrador.');
                                  $cont = $subT = $tot = 0;
                                  while ($p = mysqli_fetch_array($resProd)) {
                                    $subT = $p['costoUnitario'] * $p['cantidad'];
                                    $tot += $subT;
                                    echo '<tr id="fila-'.$p['id'].'">
                                            <td class="text-center">'.++$cont.'</td>
                                            <td>'.$p['descripcion'].'</td>
                                            <td>'.$p['tipoUnidad'].'</td>
                                            <td class="text-right">'.number_format($p['cantidad'],2,'.',',').'</td>
                                            <td class="text-right">$ '.number_format($p['costoUnitario'],2,'.',',').'</td>
                                            <td class="text-right">$ '.number_format($subT,2,'.',',').'</td>
                                            <td class="text-center"><a href="../funciones/eliminaProdEnCompra.php?idDet='.$p['id'].'&id='.$idCompra.'" class="btn btn-outline-danger btn-square"><i class="fas fa-trash"></i></a></td>
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
                    <div class="col-md-4">
                      <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="text-white">Detalles de la Compra</h4>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-3">
                              <label class="control-label"><h4><b>Folio:</b></h4></label>
                              <br>
                              <label class="control-label"><h4><b>Fecha:</b></h4></label>
                              <br>
                              <label class="control-label"><h4><b>Proveedor:</b></h4></label>
                              <br>
                              <label class="control-label"><h4><b>Monto:</b></h4></label>
                              <br>
                            </div>
                            <div class="col-md-9">
                              <label class="control-label text-info"><h4><b>#<?=$idCompra;?></b></h4></label>
                              <br>
                              <label class="control-label text-info"><h4><b><?=$fecha;?></b></h4></label>
                              <br>
                              <label class="control-label text-info"><h4><b><?=$nomProv;?></b></h4></label>
                              <br>
                              <label class="control-label text-danger"><h3><b>$ <?=number_format($tot,2,'.',',');?></b></h3></label>
                              <br>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>

                      <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="text-white">Notas</h4>
                        </div>
                        <div class="card-body">
                          <form role="form" method="post" action="../funciones/cierraCompra.php" onsubmit="return checkSubmit();" enctype="multipart/form-data">
                            <div>
                              <label class="control-label">Estatus de la compra</label>
                              <select class="form-control" id="estatusCompra" name="estatusCompra" onchange="muestraMontoCredito(this.value);" required>
                                <option value="">Selecciona una opcion</option>
                                <option value="1">Compra a Crédito</option>
                                <option value="2">Pagada</option>
                              </select>
                            </div>
                            <br>
                            <div id="divMontoCredito" style="display:none;">
                              <label class="control-label">Monto del crédito</label>
                              <input type="text" class="form-control" step="any" id="montoCreditoCompra" name="montoCreditoCompra" onkeyup="mascaraMonto(this,Monto)" placeholder="Ingresa el Monto a Crédito">
                              <br>
                            </div>
                            <div>
                              <label class="control-label">Ingresa el comprobante de la compra</label>
                              <input type="file" accept="application/pdf" class="form-control" id="comprobanteCompra" name="comprobanteCompra">
                            </div>
                            <br>
                            <div>
                              <label class="control-label">Descripción de la compra</label>
                              <textarea cols="2" rows="3" name="nota" class="form-control" placeholder="Ingresa la Nota de Compra" required></textarea>
                            </div>
                            <br>
                            <div class="modal-footer">
                              <input type="hidden" name="idCompra" value="<?=$idCompra;?>">
                              <input type="hidden" name="totalCompra" value="<?=$tot;?>">
                              <a href="../funciones/cancelaCompra.php?idCompra=<?=$idCompra;?>&totalCompra=<?=$tot;?>" class="btn btn-danger">Cancelar Compra</a>
                              <button type="submit" class="btn btn-success">Cerrar Compra</button>
                            </div>
                          </fom>
                        </div>
                      </div>
                    </div>
                </div>
            <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

            </div>
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
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjCorporativoCompras'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCorporativoCompras']."');";
				unset($_SESSION['LZFmsjCorporativoCompras']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCorporativoCompras'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCorporativoCompras']."');";
        unset($_SESSION['LZFmsjSuccessCorporativoCompras']);
			}
      ?>
    }); // Cierre de document ready

    function seleccionaProducto(no){
      if (no == 1) {
        $("#selProd1").show();
        $("#selProd2").hide();
        $("#producto").attr('required',false);
        $("#producto2").attr('required', true);
      } else {
        $("#selProd2").show();
        $("#selProd1").hide();
        $("#producto").attr('required',false);
        $("#producto2").attr('required', true);
      }
    }

    function selCosto(idProd){
      var costo = $("#costoProd-"+idProd).val();
    //  alert('idProd: '+idProd+' y costo: '+costo);
      $("#costo").val(costo);
    }

    function muestraProveedores(idEmpresa){
      $.post("../funciones/muestraSelectProveedores.php",
    {idEmpresa:idEmpresa},
  function(respuesta){
    $("#proveedorCompra").html(respuesta);
  });
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

    function muestraMontoCredito(valor){
      if (valor == 1) {
        $("#montoCreditoCompra").val('');
        $("#divMontoCredito").show('fast');
        $("#montoCreditoCompra").prop("required",true);
        $("#montoCreditoCompra").attr("min",1);
      } else {
        $("#divMontoCredito").hide('fast');
        $("#montoCreditoCompra").val('');
        $("#montoCreditoCompra").prop("required",false);
        $("#montoCreditoCompra").removeAttr('min');
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
