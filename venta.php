<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
require_once('include/connect.php');
$info->Acceso($nameLk);
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];
$debug = 1;
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
  <link rel="icon" type="image/icon" sizes="16x16" href="assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
  <link href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/libs/select2/dist/css/select2.min.css">
  <link href="dist/css/style.min.css" rel="stylesheet">
  <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
  <style>
    .btn-circle-tablita {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 6px 0;
      font-size: 12px;
      line-height: 1.428571429;
      border-radius: 15px;
      margin-left: 5px;
    }

    .muestraSombra {
      box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
    }

    td.details-control {
      background: url('dist/js/pages/datatable/details_open.png') no-repeat center center;
      cursor: pointer;
    }

    tr.shown td.details-control {
      background: url('dist/js/pages/datatable/details_close.png') no-repeat center center;
    }
  </style>
</head>

<body>
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar">
      <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <?= $info->customizer(); ?>

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
            <?= $info->generaMenuUsuario(); ?>
            <!-- ============================================================== -->
          </ul>
        </div>
      </nav>
    </header>

    <?= $info->generaMenuLateral(); ?>

    <div class="page-wrapper">
      <?php
      #verifica si hay errores en los montos de la Bd y de la vista, si hay nos informa

      $sql = "SELECT vta.id AS idetVenta, vta.*, cli.*, IF(SUM(cr.montoDeudor)>0,SUM(cr.montoDeudor),0) AS deudaActual
             FROM ventas vta
             LEFT JOIN clientes cli ON vta.idCliente=cli.id
						 LEFT JOIN creditos cr ON cli.id = cr.idCliente AND cr.estatus = 1
             WHERE vta.idUserReg = '$idUser' AND vta.estatus < '2' AND vta.idSucursal = '$idSucursal' AND vta.ventaEspecial = '0'";
      //----------------devBug------------------------------
      if ($debug == 1) {
        $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar ventas, notifica a tu Administrador.');
        echo '<br>Listado de Ventas codigo: ' .    $sql  . '<br>';
      } else {
        $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar ventas, notifica a tu Administrador.');
      } //-------------Finaliza devBug------------------------------
      $var = mysqli_fetch_array($resXQuery);
      $idVenta = $var['idetVenta'];
      $saldo = $var['saldo'];
      $credito = ($var['credito'] > 0) ? $var['credito'] : 0;
      $deudaActual = $var['deudaActual'];
      $limiteCred = $var['limiteCredito'];
      $client = $var['idCliente'];
      $idVenta = ($idVenta == '' || $idVenta == 0) ? 0 : $idVenta;
      $saldo = ($saldo == '' || $saldo == 0) ? 0 : $saldo;
      $limiteCredito = ($limiteCred == '' || $limiteCred == 0) ? 0 : $limiteCred;
      $desactivaBtn = ($idVenta > 0) ? 'disabled="disabled"' : '';
      $disabledCliente = $client != '' ? 'disabled' : '';
      #echo '$client: '.$client;
      if ($debug == 1) {
        echo "EL ID DE LA VENTA ES: " . $idVenta;
      }
      ?>

      <div class="container-fluid">
        <section id="contenido">
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

          <div class="row">
            <div class="col-md-9 col-lg-9">
              <div class="card border-<?= $pyme; ?>">
                <div class="card-header bg-<?= $pyme; ?>">
                  <!----------------PAGOS DE VENTAS------------------>

                  <h4 class="card-title text-white">Descripción de Venta</h4>
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 col-lg-4">
                      <button type="button" class="btn bg-<?= $pyme ?> btn-circle" data-toggle="modal" data-target="#modalBusquedaSucursales" data-toggle="tooltip" title="Búsqueda de Productos por Producto" onclick="colocarModalProd()"><i class="fas fa-search text-white"></i></button>
                      <button type="button" class="btn bg-<?= $pyme ?> btn-circle" data-toggle="modal" data-target="#modalBusquedaIngrediente" title="Búsqueda de Productos por Ingrediente Activo" onclick="colocarModalIng(<?= $idVenta ?>)"><i class="fas fa-vial text-white"></i></button>
                      <button type="button" class="btn bg-<?= $pyme ?> btn-circle" data-toggle="modal" data-target="#modalSeleccionaCotizacion" title="Activar Cotización" onclick="colocarModalCoti(<?= $idVenta ?>)"><i class="fas fa-money-bill-alt text-white"></i></button>

                    </div>
                    <div class="col-md-8 col-lg-8">
                      <label for="cliente">Cliente</label>
                      <div class="input-group mb-3">
                        <select class="select2 form-control custom-select" <?= $disabledCliente ?> name="idCliente" id="idCliente" onChange="ingresaCliente('<?= $idVenta ?>', this.value)" style="width: 100%;" <?= $disabledBtn ?>>
                          <option value=''>Selecciona Cliente Registrado</option>
                          <?php
                          $sql = "SELECT
                                  cl.id,
                                  IF (cl.apodo IS NULL OR cl.apodo='',cl.nombre, CONCAT(cl.nombre,' \"',cl.apodo,'\" ')) AS cliente
                                  FROM clientes cl  WHERE cl.estatus = '1' ORDER BY cliente ASC";
                          //----------------devBug------------------------------
                          if ($debug == 1) {
                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar cliente, notifica a tu Administrador.');
                            echo '<br>Listado de Cliente: ' .    $sql  . '<br>';
                          } else {
                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar cliente, notifica a tu Administrador.');
                          } //-------------Finaliza devBug------------------------------
                          while ($clientes = mysqli_fetch_array($resXQuery)) {
                            if ($client == $clientes['id']) {
                              echo '<option value="' . $clientes['id'] . '" ' . $activeSuc . ' selected>' . $clientes['cliente'] . '</option>';
                            } else {
                              echo '<option value="' . $clientes['id'] . '" ' . $activeSuc . '>' . $clientes['cliente'] . '</option>';
                            }
                          }

                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <hr>

                  <div class="row">
                    <div class="col-md-4 col-lg-4">
                      <div class="form-group">
                        <div class="input-group input-group-lg">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-barcode-scan"></i></span>
                          </div>
                          <?php
                          $sql = "SELECT codBarra,id,prioridad FROM productos WHERE estatus = 1 GROUP BY codBarra ORDER BY descripcion ASC";
                          //----------------devBug------------------------------
                          if ($debug == 1) {
                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar codigo, notifica a tu Administrador.');
                            echo '<br>Listado de Ventas codigo: ' .    $sql  . '<br>';
                          } else {
                            $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar codigo, notifica a tu Administrador.');
                          } //-------------Finaliza devBug------------------------------
                          $cont = 0;
                          while ($rows = mysqli_fetch_array($resXQuery)) {

                            if ($cont == 0) {
                              echo '<select class="select2 form-control custom-select" id="codBarra" onChange="cambiaProducto(' . $idVenta . ', this.value,1,\'\')" style="width: 75%;">';
                              echo '<option value="">Ingresa el Código de Barras</option>';
                            }
                            if (isset($rows['codBarra']) && $rows['codBarra'] != '') {
                              echo '<option value="' . $rows['id'] . '"> ' . $rows['codBarra'] . '</option>';
                            }
                            $cont++;
                          }
                          echo '</select>';
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                      <div class="form-group">
                        <div class="input-group input-group-lg">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-barley"></i></span>
                          </div>
                          <select class="form-control custom-select color-prioridad" id="producto" onChange="cambiaProducto('<?= $idVenta ?>', this.value,1,'')" style="width: 90%;">
                            <?php
                            $sql = "SELECT
                            id,
                            descripcion,
                            prioridad,
                          CASE
                              prioridad 
                              WHEN '1' THEN
                              'text-success' 
                              WHEN '2' THEN
                              'text-warning' 
                              WHEN '3' THEN
                              'text-warning' 
                            END AS colorsito
                          
                          FROM
                            productos 
                          WHERE
                            estatus = '1' 
                          ORDER BY
                            descripcion ASC";
                            //----------------devBug------------------------------
                            if ($debug == 1) {
                              $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador.');
                              echo '<br>Listado de Ventas producto: ' .    $sql  . '<br>';
                            } else {
                              $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador.');
                            } //-------------Finaliza devBug------------------------------
                            $cont = 0;
                            while ($rows = mysqli_fetch_array($resXQuery)) {
                              $colorsito = $rows['colorsito'];
                              if ($cont == 0) {
                                echo '<option value="">Selección de Producto ...</option>';
                              }
                              echo '<option value="' . $rows['id'] . '" data-style="' . $colorsito . '" data-flag="circle">' . $rows['descripcion'] . '</option>';
                              $cont++;
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table product-overview" id="tabla_venta">
                      <thead>
                        <tr>
                          <th width="25px">Lotes</th>
                          <th>Descripción</th>
                          <th>Actual</th>
                          <th>Precio</th>
                          <th>Cantidad</th>
                          <th class="text-center">Subtotal</th>
                          <th class="text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
            <?php
            /************************************* Consulta de Pago en Ventas  ****************************************/

            $sql = "SELECT cmp.id,cmp.nombre,SUM(pv.monto) AS montoPago, IF((cmp.clave = '04' OR cmp.clave = '28'),MAX(bnk.comisionTarjeta),0) AS comTarjeta, IF((cmp.clave = '02'),MAX(bnk.comisionCheque),0) AS comCheque
                    FROM pagosventas pv
                    INNER JOIN sat_formapago cmp ON pv.idFormaPago = cmp.id
                    LEFT JOIN catbancos bnk ON pv.idBanco = bnk.id
                    WHERE pv.idVenta = '$idVenta'
                    GROUP BY cmp.id";
            //----------------devBug------------------------------
            if ($debug == 1) {
              $resXQuery = mysqli_query($link, $sql) or die('Problemas al Pagos Efectuados, notifica a tu Administrador.');
              echo '<br>Listado de Pagos Efectuados: ' .    $sql  . '<br>';
            } else {
              $resXQuery = mysqli_query($link, $sql) or die('Problemas al Pagos Efectuados, notifica a tu Administrador.');
            } //-------------Finaliza devBug------------------------------
            $n = $comTarjeta = $comCheque = $montoPagado = 0;
            $formasDePago = '';
            while ($pago = mysqli_fetch_array($resXQuery)) {
              if ($n > 0) {
                $formasDePago .= '<hr>';
              }
              $pagoEfectivo += ($pago['id'] == 1) ? 1 : 0;
              $formasDePago .= '<div class="col-md-8"><h6><a href="JavaScript:void(0);" class="text-danger" onClick="eliminaFormaDePago(' . $idVenta . ',' . $pago['id'] . ')" class="text-color"><i class="fas fa-trash"></i></a><b> ' . $pago['nombre'] . ': </b></h6></div><div class="col-md-4 text-right"><h6> $ ' . number_format($pago['montoPago'], 2, '.', ',') . '</h6></div>';
              $no++;


              $comTarjeta = ($comTarjeta < $pago['comTarjeta']) ? $pago['comTarjeta'] : $comTarjeta;
              $comCheque = ($comCheque < $pago['comCheque']) ? $pago['comCheque'] : $comCheque;
              $montoPagado += $pago['montoPago'];
              $no++;
            }
            /************************************************************************************************************/
            /*************************************  Ver Cambio y Resta de Pagos  ****************************************/
            $sql = "SELECT SUM(precioVenta*cantidad) AS totalVenta
                           FROM detventas
                           WHERE idVenta = '$idVenta'";
            //----------------devBug------------------------------
            if ($debug == 1) {
              $resXQuery = mysqli_query($link, $sql) or die('Problemas al precios y pago Ventas, notifica a tu Administrador.');
              echo '<br>Listado de Detallado Ventas: ' .    $sql  . '<br>';
            } else {
              $resXQuery = mysqli_query($link, $sql) or die('Problemas al precios y pago Ventas, notifica a tu Administrador.');
            } //-------------Finaliza devBug------------------------------
            /******************************************************************************************************/
            $vDat = mysqli_fetch_array($resXQuery);
            $subTotalVta = $vDat['totalVenta'];
            $comision = $comision2 = 0;
            $porcentajeComision = ($comTarjeta > $comCheque) ? $comTarjeta : $comCheque;
            $comision = $porcentajeComision / 100;
            $comisionReal = $subTotalVta * $comision;
            $totalVta = $subTotalVta + $comisionReal;
            $resta=0;
            $resta = (float)round($totalVta,2) - round($montoPagado,2);
            $lblTotalAcumulado = ($comision > 0) ? 'Total Acumulado + comisión (' . $comision . '%)' : 'Total Acumulado';
            if($debug=='1'){
              echo "<br>Subtotal de Ventas: ".$subTotalVta;
              echo "<br>Comision %: ".$porcentajeComision;
              echo "<br>Comision Total: ". $comisionReal;
              echo "<br>Comision Total: ". $comisionReal;
              echo "<br>Total de la Venta: ". $totalVta;
              echo "<br>monto Pagado: ". $montoPagado;
              echo "<br>Resta: ". $resta;
            }
            if ($comision > 0) {
              $lblPrecioVenta = '<h4 class="text-warning">$ ' . number_format($subTotalVta, 2, '.', ',') . ' + ' . number_format($comisionReal, 2, '.', ',') . ' </h4>Total Neto<h3><b>$ ' . number_format($totalVta, 2, '.', ',') . '</b></h3><b>Pagado</b><h3 class="text-success"><b>$ ' . number_format($montoPagado, 2, '.', ',') . '</b></h3>';
            } else {
              $lblPrecioVenta = '<h3><b>$ ' . number_format($totalVta, 2, '.', ',') . '</b></h3><b>Pagado</b><h3 class="text-success"><b>$ ' . number_format($montoPagado, 2, '.', ',') . '</b></h3>';
            }
            if ($pagoEfectivo > 0) {
              if ($resta < 0) {
                $cambio = $montoPagado - $totalVta;
              } else {
                $cambio = 0;
              }
              $muestraCambio = '<b class="text-dark">Cambio</b><h3 class="text-success"><b>$ ' . number_format($cambio, 2, '.', ',') . '</b></h3>';
            } else {
              $muestraCambio = '';
            }
            if($debug==1){
              echo "<br>Resta Neto: ".$resta;

              echo "<br>Rastreo de Resta: ".number_format($resta, 4, '.', ',');
            }

            $lblRestaVenta = '<h4><b>$ ' . number_format($resta, 2, '.', ',') . '</b></h4>' . $muestraCambio;
            if ($resta <= 0 && $totalVta > 0) {
              $disabled = '';
              $tipoBtn = 'submit';
            } else {
              $disabled = 'disabled';
              $tipoBtn = 'button';
            }
            $bloqueaBtnPago = ($idVenta > 0) ? '' : 'disabled';
            $bloqueaBtnPago2 = ($idVenta > 0 && $subTotalVta > 0) ? '' : 'disabled';
            ?>
            <!-- Column -->
            <div class="col-md-3 col-lg-3">
              <div class="card border-<?= $pyme; ?>">
                <div class="card-header bg-<?= $pyme; ?>">
                  <h5 class="m-b-0 text-white"> Precio de la Venta</h5>
                </div>
                <div class="card-body text-right">
                  <b id="lblTotalAcumulado"><?= $lblTotalAcumulado; ?></b>
                  <span id="lblPrecioVenta"><?= $lblPrecioVenta; ?></span>
                  <hr>
                  <div class="mt-3 text-right">
                    <button class="btn btn-info" data-toggle="modal" data-target="#modalFormaPago" <?= $bloqueaBtnPago2; ?>>&nbsp;Agregar Pago&nbsp;</button>
                  </div>
                </div>
              </div>
              <!-- ############################################################################################ -->
              <!-- ################################# Cierre de Venta ########################################## -->
              <!-- ############################################################################################ -->
              <div class="card">
                <div class="card-body">
                  <div class="text-right">
                    <b>Resta:</b><b id="lblRestaVenta" class="text-warning"><?= $lblRestaVenta; ?></b>
                  </div>
                  <form role="form" autocomplete="off" method="post" action="funciones/cierraVenta.php" onsubmit="return checkSubmit();">
                    <div class="mt-3 text-right">
                      <button type="button" class="btn btn-danger btn-outline" id="btnCancelaVenta" data-toggle="modal" data-target="#modalCancelaVenta" <?= $bloqueaBtnPago; ?>>Cancelar Venta</button>
                      <input type="hidden" name="tipoVenta" value="1">
                      <input type="hidden" name="montoSubtotal" value="<?= $subTotalVta; ?>">
                      <input type="hidden" name="montoComision" value="<?= $comisionReal; ?>">
                      <input type="hidden" id="inpPrecioVenta" name="precioVenta" value="<?= $totalVta; ?>">
                      <input type="hidden" id="inpMontoPagado" name="montoPagado" value="<?= $montoPagado; ?>">
                      <input type="hidden" name="comision" id="registraComision" value="<?= $porcentajeComision; ?>">
                      <input type="hidden" name="cambio" id="cambio" value="<?= $cambio; ?>">
                      <input type="hidden" name="idVenta" value="<?= $idVenta; ?>">
                      <button type="<?= $tipoBtn; ?>" class="btn btn-success btn-outline" id="btnCierraVenta" <?= $disabled; ?>>&nbsp;&nbsp;Cerrar Venta&nbsp;&nbsp;&nbsp;</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h5 class="card-title text-white">Forma(s) de Pago</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <h6><b>Nombre</b></h6>
                  </div>
                  <div class="col-md-4 text-center">
                    <h6><b>Monto</b></h6>
                  </div>
                  <?php
                  echo $formasDePago;
                  ?>
                  <input type="hidden" id="hayPagoEfectivo" value="<?= $pagoEfectivo; ?>">
                  <input type="hidden" id="comisionVenta" value="<?= $comision; ?>">
                  <input type="hidden" id="montoPagado" value="<?= $montoPagado; ?>">
                  <hr>
                </div>
              </div>
            </div>


            <div id="modalBusquedaSucursales" class="modal bs-example-modal-lg fade show" role="dialog" aria-labelledby="modalDevolucionesLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header bg-<?= $pyme; ?>">
                    <h4 class="modal-title text-white" id="modalDevolucionesLabel">Búsqueda de Producto en las Sucursales</h4>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body" id="modal-bodySucursales">
                  </div>
                </div>
              </div>
            </div>
            <div id="modalBusquedaIngrediente" class="modal bs-example-modal-xl fade show" role="dialog" aria-labelledby="modalDevolucionesLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header bg-<?= $pyme; ?>">
                    <h4 class="modal-title text-white" id="modalBusquedaIngredienteLabel">Busq. de Producto por Ingrediente Activo o Apl. en Campo</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body" id="modal-bodyIngrediente">
                  </div>
                </div>
              </div>
            </div>
            <div id="modalSeleccionaCotizacion" class="modal bs-example-modal-sm fade show" role="dialog" aria-labelledby="modalDevolucionesLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header bg-<?= $pyme; ?>">
                    <h4 class="modal-title text-white" id="modalSeleccionaCotizacionLabel">Carga de Cotización</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body" id="modal-bodyCotizacion">

                  </div>

                </div>
              </div>
            </div>
          </div>

          <div id="modalFormaPago" class="modal bs-example-modal fade show" tabindex="-1" role="dialog" aria-labelledby="modalFormaPagoLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-<?= $pyme; ?>">
                  <h4 class="modal-title text-white" id="modalFormaPagoLabel">Selecciona una Forma de Pago</h4><br>

                  <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <div class="input-group input-group-lg">
                          <h6 class="modal-title" id="modalFormaPagoLabel"><b class="text-danger">NOTA:</b> Al seleccionar tarjeta de Crédito se aumentará un porcentaje de comisión a toda la venta</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                  <form role="form" id="registraMetodoPago" autocomplete="off" method="post" action="funciones/registraMetodoDePago.php" onsubmit="checkSubmit();">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-cash-usd"></i></span>
                            </div>
                            <select class="form-control" name="formaPago" onchange="seleccionDePago(this.value);" id="formaPago">
                              <?php
                              echo '<option value="">Seleccione la Forma de Pago</option>';
                              $opCred = ($credito == 1 && $limiteCredito > $deudaActual) ? '<option value="7">Crédito</option>' : '';
                              $sql = "SELECT * FROM sat_formapago WHERE estatus = 1 AND clave != '99'";
                              //----------------devBug------------------------------
                              if ($debug == 1) {
                                $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar codigo, notifica a tu Administrador.');
                                echo '<br>Listado de Ventas codigo: ' .    $sql  . '<br>';
                              } else {
                                $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar codigo, notifica a tu Administrador.');
                              } //-------------Finaliza devBug------------------------------
                              while ($fila = mysqli_fetch_array($resXQuery)) {
                                echo '<option value="' . $fila['id'] . '">' . $fila['nombre'] . '</option>';
                              }
                              echo $opCred;
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display:none;" id="listadoBancos">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-bank"></i></span>
                            </div>
                            <select class="form-control" name="claveBanco" onchange="muestraMontoVenta(this.value)" id="claveBanco">
                              <?php
                              echo '<option value="">Seleccione el Banco</option>';
                              $sqlBancos = "SELECT * FROM catbancos WHERE estatus = 1";
                              $resBancos = mysqli_query($link, $sqlBancos) or die('Problemas al listar las formas de Pago, notifica a tu Administrador.');
                              while ($row = mysqli_fetch_array($resBancos)) {
                                echo '<option value="' . $row['id'] . '">' . $row['nombreCorto'] . '</option>';
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display:none;" id="rowPagoEfectivo">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-cash-multiple"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" maxlength='20' class="form-control" step="0.01" name="pago" id="pago" placeholder="Monto">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display:none;" id="rowPagoCheque">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-key-variant"></i></span>
                            </div>
                            <input class="form-control" name="folioCheq" id="folioCheq" placeholder="Ingresa el Folio del Cheque" minLength="8" maxlength="10" type="text" class="form-control" onchange="validaFolio(this.value,2)">
                          </div>
                        </div>
                      </div>
                      <label id="lbl-2"></label><br>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-cash-multiple"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" min="0" maxlength='20' class="form-control" step="0.01" name="pagoCheque" id="pagoCheque" placeholder="Monto del Cheque">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display:none;" id="rowPagoTransferencia">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-key"></i></span>
                            </div>
                            <input class="form-control" name="folioTransferencia" id="folioTransferencia" placeholder="Ingresa el Folio de la Transferencia" minLength="8" maxlength="30" type="text" class="form-control" onchange="validaFolio(this.value,3)">
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-cash-multiple"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" min="0" maxlength='20' class="form-control" step="0.01" name="pagoTransfer" id="pagoTransfer" placeholder="Monto de Transferencia">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display:none;" id="rowPagoTarjeta">
                      <label id="lbl-4"></label><br>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fas fa-key"></i></span>
                            </div>
                            <input id="noTarjeta" name="noTarjeta" minLength="4" step="0.01" placeholder="Ingresa el Número de Tarjeta" maxlength="4" type="text" onkeyup="soloNumeros(this.value,'noTarjeta')" class="form-control">
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                            </div>
                            <input type="text" maxlength="13" class="form-control" name="credencialIne" id="credencialIne" onkeyup="soloNumeros(this.value,'credencialIne')" placeholder="Ingresa el código OCR del INE">
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-cash-multiple"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" min="0" class="form-control" maxlength='20' step="0.01" name="pagoTarjeta" id="pagoTarjeta" placeholder="Monto a cobrar">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" style="display:none;" id="rowPagoCredito">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-cash-multiple"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" min="0" maxlength='20' class="form-control" step="0.01" name="pagoCredito" id="pagoCredito" placeholder="Monto a Crédito">
                          </div>
                        </div>
                      </div>
                    </div>


                </div>
                <div class="modal-footer">
                  <input type="hidden" value="<?= $idVenta; ?>" name="idVenta">
                  <input type="hidden" value="1" name="tipoVenta">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                  <button type="submit" id="btnRegistrar" class="btn btn-info"><i class="fas fa-edit"></i> Registrar</button>
                </div>
              </div>
              </form>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>


      </div>

      <div id="modalCancelaVenta" class="modal bs-example-modal-lg fade show" tabindex="-1" role="dialog" aria-labelledby="modalBoletasLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="col-md-12">
                <center>
                  <div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
                </center>
              </div>
              <div class="col-md-12">
                <h3 class="text-dark text-center"><b>¿Deseas cancelar la venta?</b></h3>
              </div>
              <div class="col-md-12">
                <h5 class="text-center">¡Se cancelará la venta con todos los productos!</h5>
              </div>
              <br>
              <form role="form" autocomplete="off" method="post" action="funciones/cancelaVenta.php" onsubmit="checkSubmit();">
                <div class="row">
                  <input type="hidden" name="tipoVenta" value="1">
                  <input type="hidden" name="idVenta" value="<?= $idVenta; ?>">
                  <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-info" data-dismiss="modal">
                      <h3>No</h3>
                    </button>&nbsp;
                    <button type="submit" class="btn btn-danger">
                      <h3>&nbsp;Si&nbsp;</h3>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div id="modalBoletas" class="modal bs-example-modal-lg fade show" tabindex="-1" role="dialog" aria-labelledby="modalBoletasLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header bg-<?= $pyme ?>">
              <h4 class="modal-title text-white" id="modalBoletasLabel ">Captura de Boletas</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#kiloXkilo" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Kilo por Kilo</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#cnc" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">CNC</span></a> </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="kiloXkilo" role="tabpanel">

                  <form action="funciones/registraMetodoDePago.php" method="post" id="modalRegistraBoleta" role="form">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-domain"></i></span>
                            </div>
                            <input type="text" class="form-control" name="folioBoleta" id="folioBoleta" onchange="validaFolio(this.value,6);" placeholder="Ingresa el Folio de la Boleta" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-domain"></i></span>
                            </div>
                            <select class="form-control" name="municipio" id="municipio" onclick="muestraCultivos(this.value);" required>
                              <?php
                              $sql = "SELECT cmp.id, cmp.nombre AS nombreMpio
                                      FROM cultivosxmunicipios cxm
                                      INNER JOIN catmunicipios cmp ON cxm.idMunicipio = cmp.id
                                      GROUP BY cmp.id";
                              //----------------devBug------------------------------
                              if ($debug == 1) {
                                $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar codigo, notifica a tu Administrador.');
                                echo '<br>Listado de Ventas codigo: ' .    $sql  . '<br>';
                              } else {
                                $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar codigo, notifica a tu Administrador.');
                              } //-------------Finaliza devBug------------------------------

                              echo '<option value="">Selecciona un Municipio</option>';
                              while ($mpio = mysqli_fetch_array($resXQuery)) {
                                echo '<option value="' . $mpio['id'] . '">' . $mpio['nombreMpio'] . '</option>';
                              }
                              ?>

                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-barley"></i></span>
                            </div>
                            <select class="form-control" name="cultivo" id="cultivo" required>
                              <option value="">Selecciona cultivo</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-vector-square"></i></span>
                            </div>
                            <select class="form-control" name="superficie" id="superficie" required>
                              <option value="">Selecciona la Superficie</option>
                              <option value="1">1 Hectáreas</option>
                              <option value="2">2 Hectáreas</option>
                              <option value="3">3 Hectáreas</option>
                              <option value="4">4 Hectáreas</option>
                              <option value="5">5 Hectáreas</option>
                              <option value="6">6 Hectáreas</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-account-location"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombre del Productor" name="productor" id="productor" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-account-switch"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombre de Quien entrega la Boleta" name="entregaBoleta" id="entregaBoleta" required>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6"></div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-square-inc-cash"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" class="form-control" name="pagoBol" id="pagoBol" min="0" placeholder="Ingresa el monto de la boleta" required>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <input type="hidden" name="idVentaBoleta" value="<?= $idVenta; ?>">
                      <input type="hidden" name="tipoVenta" value="1">
                      <input type="hidden" name="formaPago" value="6">
                      <input type="hidden" name="tipoBol" value="1">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btn-warning" onclick="cambiaModal(1);" data-dismiss="modal">Regresar a Métodos de Pago</button>
                      <button type="submit" class="btn btn-info" id="botonRegistraBoleta" disabled><i class="fas fa-edit"></i> Capturar</button>
                    </div>
                  </form>

                </div>
                <!--  ###################################################################################################################################################  -->
                <div class="tab-pane  p-20" id="cnc" role="tabpanel">
                  <form action="funciones/registraMetodoDePago.php" method="post" id="modalRegistraBoleta" role="form">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-domain"></i></span>
                            </div>
                            <input type="text" class="form-control" name="folioBoleta" id="folioBoleta" onchange="validaFolio(this.value,6);" placeholder="Ingresa el Folio de la Boleta" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-domain"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Clave Cañera" onkeyup="cambiaMayusculas(this.value,'claveCanera');" name="claveCanera" id="claveCanera" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-barley"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombre del Ejido" onkeyup="cambiaMayusculas(this.value,'ejido');" name="ejido" id="ejido" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-vector-square"></i></span>
                            </div>
                            <input type="number" step="any" class="form-control" min=".01" placeholder="Ingresa la Superficie" name="superficie" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-account-location"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombre del Productor" name="productor" id="productor" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-account-switch"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombre de Quien entrega la Boleta" name="entregaBoleta" id="entregaBoleta" required>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-square-inc-cash"></i></span>
                            </div>
                            <select class="form-control" name="cultivo" id="cultivo" required>
                              <option value="3">Caña</option>
                            </select>

                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="mdi mdi-square-inc-cash"></i></span>
                            </div>
                            <input type="text" onkeyup="mascaraMonto(this,Monto)" class="form-control" name="pagoBol" id="pagoBol" min="0" placeholder="Ingresa el monto de la boleta" required>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <input type="hidden" name="idVentaBoleta" value="<?= $idVenta; ?>">
                      <input type="hidden" name="tipoVenta" value="1">
                      <input type="hidden" name="formaPago" value="6">
                      <input type="hidden" name="tipoBol" value="2">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btn-warning" onclick="cambiaModal(1);" data-dismiss="modal">Regresar a Métodos de Pago</button>
                      <button type="submit" class="btn btn-info" id="botonRegistraBoleta2" disabled><i class="fas fa-edit"></i> Capturar</button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      </div>




      <footer class="footer text-center">
        Powered By
        <b class="text-info">RVSETyS</b>.
      </footer>

    </div>

  </div>

  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- customizer Panel -->
  <!-- ============================================================== -->

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
  <!-- dataTable js -->
  <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min-ESP.js"></script>
  <script src="dist/js/pages/datatable/datatable-api.init.js"></script>
  <!--Menu sidebar -->
  <script src="dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="assets/scripts/basicFuctions.js"></script>
  <script src="assets/scripts/cadenas.js"></script>
  <script src="assets/scripts/jquery.number.js"></script>
  <script src="assets/scripts/jquery.number.min.js"></script>
  <script src="assets/scripts/notificaciones.js"></script>
  <script src="dist/js/custom.min.js"></script>
  <script src="assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="assets/libs/block-ui/jquery.blockUI.js"></script>
  <script src="assets/extra-libs/block-ui/block-ui.js"></script>
  <script src="assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="assets/libs/sweetalert2/sweet-alert.init.js"></script>
  <script src="assets/libs/toastr/build/toastr.min.js"></script>
  <script src="assets/tablasZafra/datatable_Ventas.js"></script>

  <script>
    <?php
    $idSucursal = $_SESSION['LZFidSuc'];
    $sql =
      "SELECT d.*, p.costo,p.descripcion,p.medios, s.cantActual, s.id AS idStock
      FROM ventas v
      INNER JOIN detventas d ON v.id = d.idVenta
      INNER JOIN productos p ON d.idProducto = p.id
      INNER JOIN stocks s ON d.idProducto = s.idProducto AND s.idSucursal = '$idSucursal'
      WHERE  v.estatus = '1' AND v.idSucursal = '$idSucursal' AND v.idUserReg = '$idUser' AND v.ventaEspecial = '0'
      AND v.id='$idVenta'
      ORDER BY d.id ASC";
    //echo 'console.log(\'' . $sql . '\');';
    $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>' . $sql);
    $arreglo['data'] = array();
    while ($datos = mysqli_fetch_array($res)) {
      $arreglo['data'][] = $datos;
    }
    $var = json_encode($arreglo);
    mysqli_free_result($res);
    echo 'var dataJson= ' . $var . ';';
    echo 'var pyme = "' . $pyme . '";';
    ?>
    $(document).ready(function() {
      <?php
      #  include('funciones/basicFuctions.php');
      #  alertMsj($nameLk);

      if (isset($_SESSION['LZFmsjInicioVenta'])) {

        echo "notificaBad('" . $_SESSION['LZFmsjInicioVenta'] . "');";
        unset($_SESSION['LZFmsjInicioVenta']);
      }
      if (isset($_SESSION['LZFmsjSuccessInicioVenta'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessInicioVenta'] . "');";
        unset($_SESSION['LZFmsjSuccessInicioVenta']);
      }
      ?>
      /*
      setTimeout( function(){
          sumaPago();
       }, 200);
*/
    });
    $(".color-prioridad").select2({
      //minimumResultsForSearch: Infinity,
      templateResult: iconFormat,
      templateSelection: iconFormat,
      escapeMarkup: function(es) {
        return es;
      }
    });

    function ejecutandoCarga(identif) {
      var selector = 'DIV' + identif;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("funciones/cargaContenidoVta.php", {
          ident: identif
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });

    }

    function eliminaProductoEnVenta(idDetVenta) {
      $('<form action="funciones/eliminaProductoEnVenta.php" method="POST"><input type="hidden" name="idDetVenta" value="' + idDetVenta + '"><input type="hidden" name="tipoVenta" value="1"></form>').appendTo('body').submit();
    }

    function eliminaFormaDePago(idVenta, formaPago) {
      $('<form action="funciones/eliminaFormaDePago.php" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="formaPago" value="' + formaPago + '"><input type="hidden" name="tipoVenta" value="1"></form>').appendTo('body').submit();
    }

    function eliminaBoleta(idVenta, folio) {
      //alert('venta: '+idVenta+', folio: '+folio);
      $('<form action="funciones/eliminaBoleta.php" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="folio" value="' + folio + '"><input type="hidden" name="tipoVenta" value="1"></form>').appendTo('body').submit();
    }

    function muestraCultivos(id) {
      $.post("funciones/listaCultivos.php", {
          ident: id
        },
        function(respCultivo) {
          $("#cultivo").html(respCultivo);
        });
    }



    function iconFormat(ficon) {
      var originalOption = ficon.element;
      if (!ficon.id) {
        return ficon.text;
      }
      var $ficon = "<i class='fas fa-" + $(ficon.element).data('flag') + " " + $(ficon.element).data('style') + "'></i> " + ficon.text;
      // console.log(ficon.element);
      return $ficon;
    }

    function colocarModalProd() {
      $.post("funciones/modalConsultaProducto.php",
        function(respuesta) {
          $("#modal-bodySucursales").html(respuesta);
        });
    }

    function colocarModalIng(idVenta) {
      $.post("funciones/modalConsultaIng.php", {
          idVenta: idVenta
        },
        function(respuesta) {
          $("#modal-bodyIngrediente").html(respuesta);
        });
    }

    function colocarModalCoti(idVenta) {
      $.post("funciones/modalConsultaCoti.php", {
          idVenta: idVenta
        },
        function(respuesta) {

          $("#modal-bodyCotizacion").html(respuesta);
        });
    }


    function buscarProductoenSucursal() {
      var p1 = $("#codBarraBusqSuc option:selected").val();
      var p2 = $("#productoBusqSuc option:selected").val();
      var idProducto = p1 == '' ? p2 : p1;
      var debug = 0;
      if (debug == 1) {
        console.log("PRODUCTO SELECCIONADO: " + idProducto);
      }
      if (idProducto > 0) {
        procesaBusqueda('modalBusquedaSucursales', 1);
        $.post("funciones/buscaProductosEnSucursales.php", {
            idProducto: idProducto
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              $("#tablaRespBusquedaSuc").html(resp[1]);
              procesaBusqueda('modalBusquedaSucursales', 2);
            } else {
              procesaBusqueda('modalBusquedaSucursales', 2);
              notificaBad(resp[1]);
            }
          });
      } else {
        notificaBad('Debes seleccionar un producto o un código de barras.')
      }
    }

    function procesaBusqueda(div, no) {
      if (no == 1) {
        var block_ele = $(this).closest('.card');
        $('#' + div).block({
          message: '<b class="text-white">Procesando...</b><br><i class="fas fa-spin fa-sync text-white"></i>',
          overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.5,
            cursor: 'wait'
          },
          css: {
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
          }
        });
      } else {
        $('#' + div).unblock();
      }
    }


    function validaFolio(folio, metodoPago) {
      //  alert('Entro, folio: '+folio+',metodoPago: '+metodoPago);
      if (folio != '') {
        $.post("funciones/verificaFolios.php", {
            folio: folio,
            metodoPago: metodoPago
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            // si es boleta
            if (metodoPago == 6) {
              if (resp[0] == 1) {
                //    alert('libre');
                $("#botonRegistraBoleta").prop("disabled", false);
                $("#botonRegistraBoleta2").prop("disabled", false);
              } else {
                notificaBad(resp[1]);
                //    alert('libre');
                $("#botonRegistraBoleta").prop("disabled", true);
                $("#botonRegistraBoleta2").prop("disabled", true);
              }
            } else {
              // si es cheque o Transferencia
              if (resp[0] == 0) {
                notificaBad(resp[1]);
              }
            }
          });
      }
    }

    function generaSol(idSuc, idProd, maxim) {
      $("#bt" + idSuc + "n" + idProd).prop("disabled", true);
      cant = $("#cant" + idSuc + "p" + idProd).val();
      espacio = cant.length;
      var debug = '1';
      if (cant < 1) {
        $("#bt" + idSuc + "n" + idProd).prop("disabled", false);
        notificaBad("La cantidad solicitada debe ser mayor a 0.");
      } else {
        if (cant > maxim) {
          notificaBad("Estas solicitando más de los que hay en la sucursal.");
          $("#bt" + idSuc + "n" + idProd).prop("disabled", false);
        } else {
          $.post("funciones/lanzaSolicitud.php", {
            idSucursal: idSuc,
            idProducto: idProd,
            cantidad: cant
          }, function(htmlexterno) {
            var msj = htmlexterno.split('|');
            if (msj[0] == 1) {
              notificaSuc("Excelente. " + msj[1]);
              $("#bt" + idSuc + "n" + idProd).attr("disabled", "disabled");
              $("#bt" + idSuc + "n" + idProd).prop("disabled", true);
              $("#verProdSolicitados").prop("disabled", false);

            } else {
              if (debug == '1') {
                console.log(msj[1] + ", sql: " + msj[2]);
              }
              notificaBad("Ocurrió un problema, Notifica A tu Administrador.");
              $("#bt" + idSuc + "n" + idProd).prop("disabled", false);
            }

          });

        }
      }
    }

    function cierraSolicitud(idTraspaso, no) {
      if (idTraspaso > 0) {
        procesaBusqueda('modalBusquedaSucursales', 1);
        $.post("funciones/cierraSolicitudDeTraspaso.php", {
            idTraspaso: idTraspaso,
            tipo: no
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              listaSolicitudesAbiertas();
            } else {
              notificaBad(resp[1]);
            }
          });
        procesaBusqueda('modalBusquedaSucursales', 2);
      } else {
        notificaBad('No se reconoció el traspado, actualiza y reintenta, si persiste notifica a tu Administrador.');
      }
    }

    function listaSolicitudesAbiertas() {
      $("#preloadBusca").css("display", "inline");
      procesaBusqueda('modalBusquedaSucursales', 1);
      $.post("funciones/buscaSolicitudesAbiertas.php", {},
        function(respuesta) {
          $("#tablaRespBusquedaSuc").html(respuesta);
          procesaBusqueda('modalBusquedaSucursales', 2);
        });
    }

    function buscarIngredienteActivo(idVenta) {
      var debug = 0;
      var p1 = $("#ingredienteAct option:selected").val();
      var p2 = $("#descIngActivo option:selected").val();
      var ingrediente = p1 == '' ? p2 : p1;
      var vista = 1;
      if (debug == 1) {
        console.log("INGREDIENTE ACTIVO: " + ingrediente);
      }
      if (ingrediente != '') {
        procesaBusqueda('modalBusquedaIngrediente', 1);

        //bloqueaCard('modalBusquedaIngrediente', 1);
        $.post("funciones/buscaIngredienteActivo.php", {
            ingrediente: ingrediente,
            idVenta: idVenta,
            vista: vista
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (debug == 1) {
              console.log(respuesta);
            }
            if (resp[0] == 1) {
              $("#tablaRespIngrediente").html(resp[1]);
              $("#ingredienteAct").prop('selectedIndex', '');
              $("#descIngActivo").prop('selectedIndex', '');
              procesaBusqueda('modalBusquedaIngrediente', 2);

              // bloqueaCard('modalBusquedaIngrediente', 2);
            } else {
              $("#tablaRespIngrediente").html(resp[2]);
              notificaBad(resp[1]);
            }
          });
      } else {
        notificaBad('No se reconoció el ingrediente, prueba otra vez, si persiste notifica a tu Administrador.');
      }
    }


    function cambiaProducto(idVenta, idprod, tipoVenta, autorizada) {
      $("#codBarra").val("");
      if (idprod > 0) {
        $.post("funciones/existenciaProducto.php", {
            idprod: idprod,
            cod: 'PD',
            idVenta: idVenta,
            tipoVenta: tipoVenta,
            idAutorizada: autorizada
          },
          function(respuesta) {
            var subCadenas = respuesta.split('|');
            if (subCadenas[0] == 0) {
              notificaBad(subCadenas[1]);
            } else {
              location.reload();
            }
          });
      }
    }

    function buscaCotizacion() {
      var debug = 0;
      var folioCotizacion = $('#idCotizacion').val();
      if (debug == 1) {
        console.log(folioCotizacion);
      }
      $.post("funciones/buscaCoti.php", {
          folio: folioCotizacion
        },
        function(respuesta) {
          if (debug == 1) {
            console.log(respuesta);
          }
          $("#cotizacion").html(respuesta);
          $("#cotizacion").toggle();

        });

    }

    function cargaCotizacion(idCotizacion) {
      var debug = 0;
      var idCot = idCotizacion;
      if (idCot != '') {
        $.post("funciones/cargaCotizacionEnVenta.php", {
            idCot: idCot
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (debug == 1) {
              console.log(respuesta);
            }
            if (resp[0] == 1) {
              location.reload();
            } else {
              notificaBad(resp[1]);
            }
          });
      } else {
        notificaBad('Folio no válido o el inexistente, verifica si hay un folio de tipo númerico ingresado.');
      }
    }

    function cambiaPrecioProd(id, precio) {
      //alert('Cambio Precio'+precio);
      $('<form action="funciones/editaDetalleVentaPrecio.php" method="POST"><input type="hidden" name="id" value="' + id + '"><input type="hidden" name="tipo" value="1"><input type="hidden" name="precio" value="' + precio + '"></form>').appendTo('body').submit();
    };

    function cambiaCantProd(id, cant, max, actual) {
      if (cant > max) {
        $("#cant" + id).val(max);
        notificaWarn('En inventario solo tienes: ' + actual);

      } else {
        $('<form action="funciones/editaDetalleVenta.php" method="POST"><input type="hidden" name="id" value="' + id + '"><input type="hidden" name="tipo" value="1"><input type="hidden" name="cant" value="' + cant + '"></form>').appendTo('body').submit();
      }
    };

    function ingresaCliente(idVenta, idCliente) {
      $('<form action="funciones/clienteVenta.php" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="tipoVenta" value="1"><input type="hidden" name="idCliente" value="' + idCliente + '"></form>').appendTo('body').submit();
    }

    function seleccionDePago(valor) {
      //alert('Entro a funcion, valor: '+valor);
      $("#lbl-2").html('');
      $("#lbl-4").html('');
      var idVenta = $("#idVenta").val();
      switch (valor) {
        case '1':
          // se muestra los elementos
          $("#rowPagoEfectivo").show();
          $("#listadoBancos").hide();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").hide();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", true);
          $("#claveBanco").prop("required", false);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", false);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", false);
          $("#credencialIne").prop("required", false);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
          break;
        case '2':
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").show();
          $("#rowPagoCheque").show();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").hide();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", true);
          $("#folioCheq").prop("required", true);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", false);
          $("#pagoCheque").prop("required", true);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", false);
          $("#credencialIne").prop("required", false);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
          break;
        case '3':
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").show();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").show();
          $("#rowPagoTarjeta").hide();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", true);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", true);
          $("#noTarjeta").prop("required", false);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", true);
          $("#pagoTarjeta").prop("required", false);
          $("#credencialIne").prop("required", false);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
          break;
        case '4':
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").show();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").show();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", true);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", true);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", true);
          $("#credencialIne").prop("required", true);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
          break;
        case '5':
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").show();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").show();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", true);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", true);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", true);
          $("#credencialIne").prop("required", true);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
          break;

        case '6':
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").hide();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").hide();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", false);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", false);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", false);
          $("#credencialIne").prop("required", false);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#pagoCredito").val('');
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#credencialIne").val('');
          $("#idVentaBoleta").val(idVenta);
          $("#formaPagoBoleta").val('31');
          $("#modalFormaPago").modal('hide');
          $("#modalBoletas").modal('show');
          break;

        case '7':
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").hide();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").hide();
          $("#rowPagoCredito").show();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", false);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", false);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", false);
          $("#credencialIne").prop("required", false);
          $("#pagoCredito").prop("required", true);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
          break;

        default:
          // se muestra los elementos
          $("#rowPagoEfectivo").hide();
          $("#listadoBancos").hide();
          $("#rowPagoCheque").hide();
          $("#rowPagoTransferencia").hide();
          $("#rowPagoTarjeta").hide();
          $("#rowPagoCredito").hide();
          // se les coloca la propiedad de requerido
          $("#pago").prop("required", false);
          $("#claveBanco").prop("required", false);
          $("#folioCheq").prop("required", false);
          $("#folioTransferencia").prop("required", false);
          $("#noTarjeta").prop("required", false);
          $("#pagoCheque").prop("required", false);
          $("#pagoTransfer").prop("required", false);
          $("#pagoTarjeta").prop("required", false);
          $("#credencialIne").prop("required", false);
          $("#pagoCredito").prop("required", false);
          // los demas se valuan a 0
          $("#claveBanco").val('');
          $("#folioCheq").val('');
          $("#folioTransferencia").val('');
          $("#noTarjeta").val('');
          $("#pago").val('');
          $("#pagoCheque").val('');
          $("#pagoTransfer").val('');
          $("#pagoTarjeta").val('');
          $("#credencialIne").val('');
          $("#pagoCredito").val('');
      }
    }
    $('.modal').on('hidden.bs.modal', function() {
      $(this).find('form')[0].reset();
    });

    function muestraMontoVenta(val) {
      var pagado = parseFloat($("#montoPagado").val());
      var resta = 0;
      var comision = $("#comisionVenta").val();
      var tipoPago = $("#formaPago option:selected").val();
      var total = 0;
      $(".subtotalVenta").each(function() {
        if (isNaN(parseFloat($(this).val()))) {
          total += 0;
        } else {
          total += parseFloat($(this).val());
        }
      });
      if (tipoPago == 2) {
        $.post("funciones/verificaComision.php", {
            banco: val,
            formaPago: tipoPago
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              if (resp[1] > comision) {
                var com = resp[1];
              } else {
                var com = comision;
              }
              // se toma la comisión
              var cComision = com / 100;
              var tTotal = cComision * total;
              var tComision = tTotal + total;
              $("#lbl-2").html('<p class="text-center">Comisión (' + com + '%) = <b class="text-warning">$' + $.number(tTotal, 2) + ' </b>, Total de la Venta = <b class="text-success">$' + $.number(tComision, 2) + '</b></p>');
              resta = tComision - pagado;
              $("#pagoCheque").val(resta.toFixed(2));
            }
          });
      } else if (tipoPago == 4 || tipoPago == 5) {
        $.post("funciones/verificaComision.php", {
            banco: val,
            formaPago: tipoPago
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              if (resp[2] > comision) {
                var com = resp[2];
              } else {
                var com = comision;
              }
              // se toma la comisión
              var cComision = com / 100;
              var tTotal = cComision * total;
              var tComision = tTotal + total;
              $("#lbl-4").html('<p class="text-center">Comisión (' + com + '%) = <b class="text-warning">$' + $.number(tTotal, 2) + ' </b>, Total de la Venta = <b class="text-success">$' + $.number(tComision, 2) + '</b></p>');
              resta = tComision - pagado;
              $("#pagoTarjeta").val(resta.toFixed(2));
            }
          });
      } else {
        $("#lbl-2").html('');
        $("#lbl-4").html('');
      }
    }
  </script>

</body>

</html>