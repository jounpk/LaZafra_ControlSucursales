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
$debug = 0;
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

  <!-- This Page CSS -->
  <link rel="stylesheet" type="text/css" href="assets/libs/select2/dist/css/select2.min.css">
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
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <?= $info->generaMenuLateral(); ?>
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
          <?php
          if (!empty($_POST['idCliente'])) {
            $idCliente = $_POST['idCliente'];
            $disabled = '';
          } else {
            $idCliente = 0;
            $disabled = 'disabled';
          }

          ?>
          <!-- ############################################################################################ -->
          <!-- #################################  buscador  ########################################## -->
          <!-- ############################################################################################ -->
          <div class="card border-<?= $pyme; ?>">
            <div class="card-header bg-<?= $pyme; ?>">
              <h4 class="card-title text-white">Búsqueda del Cliente</h4>
            </div>
            <div class="card-body">
              <form action="creditos.php" method="post">
                <div class="row">
                  <div class="input-group">
                    <select class="select2 form-control custom-select" name="idCliente" id="idCliente" style="width: 94%; height:36px;">
                      <?php
                      echo '<option value="">Ingresa el nombre o apodo del Cliente</option>';
                      $sqlCliente = "SELECT id,nombre,apodo
                                    FROM clientes
                                     WHERE estatus = 1";
                      $resCliente = mysqli_query($link, $sqlCliente) or die('Problemas al consultar los clientes, notifica a tu Administrador.');

                      while ($cli = mysqli_fetch_array($resCliente)) {
                        $activa = ($idCliente == $cli['id']) ? 'selected' : '';
                        echo '<option value="' . $cli['id'] . '"  ' . $activa . '>' . $cli['nombre'] . ' (' . $cli['apodo'] . ')</option>';
                      }
                      ?>
                    </select>
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-outline-<?= $pyme; ?>" type="button"><i class="fas fa-search"></i></button>
                    </div>
                    <br>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <br>
          <!-- ############################################################################################ -->
          <!-- ##############################  Listado de creditos  ####################################### -->
          <!-- ############################################################################################ -->
          <style>
            .muestraSombra {
              background: #fff;
              box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
            }

            .alinearCentro {
              display: inline-block;
              text-align: center;
              vertical-align: middle;
              line-height: 150%;
              padding-top: 15%;
            }
          </style>
          <?php
          if ($idCliente > 0) {
          ?>
            <div class="row">
              <div class="col-md-8">
                <div class="card border-<?= $pyme; ?>">
                  <div class="card-header bg-<?= $pyme; ?>">
                    <h5 class="m-b-0 text-white">Créditos Pendientes</h5>
                  </div>
                  <div class="card-body">
                    <?php
                    $sqlConCred = "SELECT CONCAT(s.nameFact, ' (',COUNT(c.id),')') AS credPendientes
                                   FROM ventas v
                                   INNER JOIN sucursales s ON v.idSucursal = s.id
                                   LEFT JOIN creditos c ON v.id = c.idVenta
                                    WHERE v.estatus = 2 AND c.estatus = 1 AND v.idSucursal != '$idSucursal'
                                     GROUP BY s.id";
                    //----------------devBug------------------------------
                    if ($debug == 1) {
                      $resConCred = mysqli_query($link, $sqlConCred) or die('Problemas al consultar los créditos en las demás sucursales, notifica a tu Administrador.');
                      echo '<br>Listado de Creditos de las Demas Sucursales: ' .    $sqlConCred  . '<br>';
                    } else {
                      $resConCred = mysqli_query($link, $sqlConCred) or die('Problemas al consultar los créditos en las demás sucursales, notifica a tu Administrador.');
                    } //-------------Finaliza devBug------------------------------
                    $cantCredPend = mysqli_num_rows($resConCred);
                    if ($cantCredPend > 0) {
                      $crdPnd = '';
                      while ($crd = mysqli_fetch_array($resConCred)) {
                        $crdPnd .= $crd['credPendientes'] . ', ';
                      }
                      $crdPnd = '<big><b class="text-danger">Nota</b></big>: El Cliente tiene créditos pendientes por pagar en: ' . trim($crdPnd, ", ");
                    } else {
                      $crdPnd = '';
                    }
                    ?>
                    <div><?= $crdPnd; ?></div>
                    <div class="text-right">
                      <b class="text-success"> &nbsp;&nbsp;&nbsp;Seleccionar todo</b>&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="selectTodo('crdChkBx');" id="crdChkBx" value="">&nbsp;&nbsp;<br>
                    </div>
                    <div id="accordian-3">
                      <?php
                      $sqlCred = "SELECT v.id AS idVenta,cdt.id AS idCred, v.fechaReg, scs.nameFact, IF(SUM(cdt.montoDeudor) > 0,SUM(cdt.montoDeudor),0) AS montoDeuda
                                            FROM creditos cdt
                                            INNER JOIN ventas v ON cdt.idVenta = v.id
                                            INNER JOIN sucursales scs ON v.idSucursal = scs.id
                                            WHERE cdt.idCliente = '$idCliente' AND cdt.estatus = 1 AND v.idSucursal = '$idSucursal'
                                            GROUP BY v.id
                                            ORDER BY v.fechaReg ASC,v.id ASC";
                      //----------------devBug------------------------------
                      if ($debug == 1) {
                        $resCred = mysqli_query($link, $sqlCred) or die('Problemas al consultar las ventas a crédito del cliente, notifica a tu Administrador.');
                        echo '<br>Listado de Ventas de Crédito de Cliente: ' .    $sqlConCred  . '<br>';
                      } else {
                        $resCred = mysqli_query($link, $sqlCred) or die('Problemas al consultar las ventas a crédito del cliente, notifica a tu Administrador.');
                      } //-------------Finaliza devBug------------------------------
                      while ($cr = mysqli_fetch_array($resCred)) {
                        $idVenta = $cr['idVenta'];
                        echo '<div class="card">
                                            <a class="card-header" id="heading' . $idVenta . '">
                                              <a href="JavaScript>:void(0);" data-toggle="collapse" data-target="#' . $cr['nameFact'] . '-' . $idVenta . '" aria-controls="' . $cr['nameFact'] . '-' . $idVenta . '">
                                                  <h5 class="mb-0">
                                                    # ' . $idVenta . ' - ' . $cr['nameFact'] . ' (Adeudo: $' . number_format($cr['montoDeuda'], 2, '.', ',') . ')
                                                  </h5>
                                              </a>
                                              <div class="text-right">
                                              <input type="checkbox" class="crdChkBx" name="cred" onClick="obtenCreditos();" value="' . $cr['idCred'] . '"> &nbsp;
                                              <input type="hidden" id="monto-' . $cr['idCred'] . '" value="' . $cr['montoDeuda'] . '">
                                              </div>

                                            </a>
                                            <div id="' . $cr['nameFact'] . '-' . $idVenta . '" class="collapse" aria-labelledby="heading' . $idVenta . '" data-parent="#accordian-3">
                                                <div class="card-body">
                                                <div class="text-right">
                                                  <button class="btn btn-outline-' . $pyme . ' btn-circle muestraSombra" title="Ver pagos realizados" onClick="cambiadiv(' . $idVenta . ');"><i class="far fa-money-bill-alt"></i></button>&nbsp;
                                                  <button class="btn btn-outline-' . $pyme . ' btn-circle muestraSombra" title="Imprimir Ticket" onClick="imprimeTicketVenta(' . $idVenta . ');"><i class="fas fa-print"></i></button>
                                                </div>';

                        $sqlConVenta = "SELECT d.*, p.descripcion
                                        FROM ventas v
                                        INNER JOIN detventas d ON v.id = d.idVenta
                                        INNER JOIN productos p ON d.idProducto = p.id
                                        WHERE  v.id = '$idVenta'
                                        ORDER BY p.descripcion ASC";
                        //----------------devBug------------------------------
                        if ($debug == 1) {
                          $resConVenta = mysqli_query($link, $sqlConVenta) or die('Problemas al consultar Detalles de la venta, notifica a tu Administrador.');
                          echo '<br>Listado de Detalles de la Ventas de Crédito de Cliente: ' .    $sqlConCred  . '<br>';
                        } else {
                          $resConVenta = mysqli_query($link, $sqlConVenta) or die('Problemas al consultar Detalles de la venta, notifica a tu Administrador.');
                        } //-------------Finaliza devBug------------------------------

                        echo '<br><div class="table-responsive" id="detalleDeVenta-' . $idVenta . '">
                                                      <h4>Productos de la venta </h4>
                                                      <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Producto</th>
                                                                <th class="text-right">Precio</th>
                                                                <th class="text-right">Cantidad</th>
                                                                <th class="text-right">Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';

                        $cont = $subt = 0;
                        while ($lProd = mysqli_fetch_array($resConVenta)) {
                          ++$cont;
                          $subt = $lProd['cantidad'] * $lProd['precioVenta'];
                          echo '<tr>
                               <td class="txt-center">' . $cont . '</td>
                               <td>' . $lProd['descripcion'] . '</td>
                               <td class="text-right"><span class="font-medium">$ ' . number_format($lProd['precioVenta'], 2, '.', ',') . '</span></td>
                               <td class="text-right"><span class="font-medium">' . $lProd['cantidad'] . '</span></td>
                               <td class="text-right"><span class="font-medium">$ ' . number_format($subt, 2, '.', ',') . '</span></td>
                             </tr>';
                        }

                        echo '</tbody>
                                                      </table>
                                                    </div>';

                        $sqlConPagos = "SELECT pcd.*, scs.nombre AS noSucursal, bnk.nombreCorto AS banco, cmp.nombre AS formaPago, DATE_FORMAT(pcd.fechaReg,'%d/%m/%Y') AS fecha, CONCAT(usu.nombre,' ',usu.appat,' ',usu.apmat) AS cajero
                                        FROM pagoscreditos pcd
																				INNER JOIN segusuarios usu ON pcd.idUserReg = usu.id
                                        LEFT JOIN creditos cd ON pcd.idCredito = cd.id
                                        LEFT JOIN sucursales scs ON pcd.idSucursal = scs.id
                                        LEFT JOIN catbancos bnk ON pcd.idBanco = bnk.id
                                        LEFT JOIN sat_formapago cmp ON pcd.idFormaPago = cmp.id
                                        WHERE cd.idVenta = '$idVenta'";

                        //----------------devBug------------------------------
                        if ($debug == 1) {
                          $resConPagos = mysqli_query($link, $sqlConPagos) or die('Problemas al consultar los detalles del Credito , notifica a tu Administrador.');
                          echo '<br>Listado de detalles del Credito de Cliente: ' .    $sqlConCred  . '<br>';
                        } else {
                          $resConPagos = mysqli_query($link, $sqlConPagos) or die('Problemas al consultar los detalles del Credito , notifica a tu Administrador.');
                        } //-------------Finaliza devBug------------------------------
                        $d = 0;
                        $d = mysqli_num_rows($resConPagos);
                        echo '<div class="table-responsive" id="detalleDePagos-' . $idVenta . '" style="display:none;">
                                                        <h4>Pagos Realizados </h4>
                                                          <table class="table table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Sucursal</th>
                                                                    <th>Cajero</th>
                                                                    <th class="text-center">Fecha</th>
                                                                    <th class="text-right">Pagado</th>
                                                                    <th class="text-center">Forma de Pago</th>
                                                                    <th class="text-right">Resta</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>';
                        if ($d > 0) {
                          $cont2 = $subt = 0;
                          while ($cPagos = mysqli_fetch_array($resConPagos)) {
                            ++$cont2;
                            echo '<tr>
                                                                <td class="txt-center">' . $cont2 . '</td>
                                                                <td>' . $cPagos['noSucursal'] . '</td>
                                                                <td>' . $cPagos['cajero'] . '</td>
                                                                <td class="text-center"><span class="font-medium">' . $cPagos['fecha'] . '</span></td>
                                                                <td class="text-right"><span class="font-medium">$ ' . number_format($cPagos['monto'], 2, '.', ',') . '</span></td>
                                                                <td class="text-center"><span class="font-medium">' . $cPagos['formaPago'] . '</span></td>
                                                                <td class="text-right"><span class="font-medium">$ ' . number_format($cPagos['residual'], 2, '.', ',') . '</span></td>
                                                              </tr>';
                          }
                        } else {
                          echo '<tr><td colspan="6" class="text-center">No hay pagos registrados en este crédito</td></tr>';
                        }
                        echo '</tbody>
                              </table>
                              </div>';

                        echo '</div>
                              </div>
                              </div>';
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ############################################################################################ -->
              <!-- ########################  monto de creditos seleccionados  ################################# -->
              <!-- ############################################################################################ -->
              <div class="col-md-4">
                <div class="card border-<?= $pyme; ?>">
                  <div class="card-header bg-<?= $pyme; ?>">
                    <h5 class="m-b-0 text-white"> Monto a Pagar</h5>
                  </div><br>
                  <h3 class="text-success text-right"><span id="monto">$ 0.00</span>&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                  <input type="hidden" id="subtotalDeCreditos" value="0">
                </div>
                <br>
                <!-- ############################################################################################ -->
                <!-- ###########################  cierre de pago de creditos #################################### -->
                <!-- ############################################################################################ -->
                <div class="card border-<?= $pyme; ?>">
                  <div class="card-header bg-<?= $pyme; ?>">
                    <h5 class="m-b-0 text-white"> Selección de pago</h5>
                  </div>
                  <div class="card-body">
                    <form method="post" id="capturaPagoDeCredito" role="form" action="funciones/capturaPagoCredito.php">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group input-group-lg">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-cash-usd"></i></span>
                              </div>
                              <select class="form-control" name="formaPago" onchange="seleccionDePago(this.value);" id="formaPago" required <?= $disabled; ?>>
                                <?php
                                echo '<option value="">Seleccione la forma de pago</option>';
                                $sqlFpago = "SELECT * FROM sat_formapago WHERE estatus = 1 AND estatusPagoCredito = 1";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                  $resFpago = mysqli_query($link, $sqlFpago) or die('Problemas al listar las formas de Pago, notifica a tu Administrador.');
                                  echo '<br>Listado de detalles del Formas de Pago: ' . $sql . '<br>';
                                } else {
                                  $resFpago = mysqli_query($link, $sqlFpago) or die('Problemas al listar las formas de Pago, notifica a tu Administrador.');
                                } //-------------Finaliza devBug------------------------------
                                while ($fila = mysqli_fetch_array($resFpago)) {
                                  echo '<option value="' . $fila['id'] . '">' . $fila['nombre'] . '</option>';
                                }
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
                                <span class="input-group-text"><i class="mdi mdi-cash-usd"></i></span>
                              </div>
                              <select class="form-control" name="claveBanco" id="claveBanco" onchange="muestraMontoComision(this.value)">
                                <?php
                                echo '<option value="">Seleccione el Banco</option>';
                                $sqlBancos = "SELECT * FROM catbancos WHERE estatus = 1";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                  $resBancos = mysqli_query($link, $sqlBancos) or die('Problemas al listar Bancos, notifica a tu Administrador.');
                                  echo '<br>Listado de Bancos: ' . $sql . '<br>';
                                } else {
                                  $resBancos = mysqli_query($link, $sqlBancos) or die('Problemas al listar Bancos, notifica a tu Administrador.');
                                } //-------------Finaliza devBug------------------------------
                                while ($row = mysqli_fetch_array($resBancos)) {
                                  echo '<option value="' . $row['id'] . '">' . $row['nombreCorto'] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row" style="display:none;" id="listadoFolios">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group input-group-lg">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                              </div>
                              <input type="text" min="0" class="form-control" name="folio" id="folio">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row" style="display:none;" id="listadoIne">
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
                      </div>
                      <label id="lbl-1"></label><br>
                      <div class="row" style="display:none;" id="listadoEfectivo">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group input-group-lg">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-cash-multiple"></i></span>
                              </div>
                              <input type="text" onkeyup="mascaraMonto(this,Monto)" onchange="verificaComision();" class="form-control" step="0.01" name="pago" id="pago" placeholder="Monto">
                            </div>
                          </div>
                        </div>
                      </div>
                      <label id="lbl-2"></label><br>

                      <div class="mt-3 text-right">
                        <input type="hidden" id="idents" name="idents" value="">
                        <input type="hidden" id="comision" name="comision" value="">
                        <input type="hidden" id="porcentajeComision" name="porcentajeComision" value="">
                        <input type="hidden" name="idCliente" value="<?= $idCliente; ?>">
                        <button type="button" id="cargarPago" class="btn btn-success" disabled>&nbsp;Pagar&nbsp;</button>
                      </div>
                    </form>
                  </div>
                </div>
                <br>


              </div>
            </div>
          <?php
          }
          ?>

        </section>

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
        Powered By
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
  <!--Custom JavaScript -->
  <script src="dist/js/custom.min.js"></script>
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
  <script type="text/javascript">
    $(document).ready(function() {
      <?php
      #  include('funciones/basicFuctions.php');
      #  alertMsj($nameLk);

      if (isset($_SESSION['LZFmsjCreditos'])) {
        echo "notificaBad('" . $_SESSION['LZFmsjCreditos'] . "');";
        unset($_SESSION['LZFmsjCreditos']);
      }
      if (isset($_SESSION['LZFmsjSuccessCreditos'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessCreditos'] . "');";
        unset($_SESSION['LZFmsjSuccessCreditos']);
      }
      ?>


      $("#capturaPagoDeCredito").submit(function(e) {
        var pago = $("#pago").val();
        var subtotal = $("#subtotalDeCreditos").val();
        var idents = $("#idents").val();
        if (idents == '' && pago > 0) {
          var msn = "Al no seleccionar uno o varios créditos se tomará en consideración los créditos más antiguos para saldar o abonar";
        } else {
          var msn = '';
        }
        if (msn != '') {
          var resp = confirm(msn);
          if (resp) {
            return true;
          } else {
            return false;
          }
        }
      });

      obtenCreditos();

    }); //cierre de $(document).ready(function(){})

    function seleccionDePago(op) {
      //alert('Entro a funcion, op: '+op);
      $("#credencialIne").val('');
      $("#folio").val('');
      $("#pago").val('');
      $("#porcentajeComision").val(0);
      $("#cargarPago").prop("disabled", true);
      $("#lbl-1").html('');
      $("#lbl-2").html('');
      $("#claveBanco").val('');
      if (op == 1) {
        // pago en efectivo
        //alert('pago 1');

        $("#listadoEfectivo").show();
        $("#listadoBancos").hide();
        $("#listadoFolios").hide();
        $("#listadoIne").hide();
        $("#credencialIne").prop('required', false);
        $("#pago").prop('required', true);
        $("#claveBanco").prop('required', false);
        $("#folio").prop('required', false);
        $("#folio").val('');
      } else if (op == 2) {
        // pago en efectivo
        //alert('pago mayor a 1');
        $("#listadoBancos").show();
        $("#listadoFolios").show();
        $("#listadoEfectivo").show();
        $("#listadoIne").hide();
        $("#credencialIne").prop('required', false);
        $("#pago").prop('required', true);
        $("#claveBanco").prop('required', true);
        $("#folio").val('');
        $("#folio").prop('required', true);
        $("#folio").prop('placeholder', 'Ingresa el Folio del Cheque');
        $("#folio").attr('minLength', 8);
        $("#folio").attr('maxlength', 10);
      } else if (op == 3) {
        // pago en efectivo
        //alert('pago mayor a 1');
        $("#listadoBancos").show();
        $("#listadoFolios").show();
        $("#listadoEfectivo").show();
        $("#listadoIne").hide();
        $("#credencialIne").prop('required', false);
        $("#pago").prop('required', true);
        $("#folio").val('');
        $("#claveBanco").prop('required', true);
        $("#folio").prop('required', true);
        $("#folio").prop('placeholder', 'Ingresa el Folio de la Transferencia');
        $("#folio").attr('minLength', 8);
        $("#folio").attr('maxlength', 30);
      } else if (op == 4 || op == 5) {
        // pago en efectivo
        //alert('pago en tarjeta');
        $("#listadoBancos").show();
        $("#listadoFolios").show();
        $("#listadoEfectivo").show();
        $("#listadoIne").show();
        $("#credencialIne").prop('required', true);
        $("#pago").prop('required', true);
        $("#claveBanco").prop('required', true);
        $("#folio").val('');
        $("#folio").prop('required', true);
        $("#folio").prop('placeholder', 'Ingresa el Número de la Tarjeta');
        $("#folio").attr('minLength', 4);
        $("#folio").attr('maxlength', 4);
      } else {
        // pago en efectivo
        //alert('pago 0');
        $("#listadoEfectivo").hide();
        $("#listadoBancos").hide();
        $("#listadoFolios").hide();
        $("#listadoIne").hide();
        $("#credencialIne").prop('required', false);
        $("#pago").prop('required', false);
        $("#claveBanco").prop('required', false);
        $("#folio").prop('required', false);
        $("#folio").val('');
      }

    }

    function selectTodo(clase) {
      selectClase(clase);
      obtenCreditos();
    }

    function selectClase(clase) {

      //  alert('Entra a función con valor: '+clase+' clase: '+clase);
      var isChecked = $("#" + clase).prop("checked");
      if (isChecked) {
        //alert('Si');
        $("." + clase).each(function() {
          this.checked = true;
        });
        return;
      } else {
        //alert('No');
        $("." + clase).each(function() {
          this.checked = false;
        });
        return;
      }
    }

    function obtenCreditos() {
   
      var debug = 0;
       /*----------------------------------------------------*/
       if (debug == 1) {
        console.log('Bienvenida a Obten Creditos');

      }
      /*----------------------------------------------------*/
      var subtotal = 0;
      var varBase = $('[name="cred"]:checked').map(function() {
        return this.value;
      }).get();
      /*----------------------------------------------------*/
      if (debug == 1) {
        console.log('Arreglo de Creditos' + varBase);

      }
      /*----------------------------------------------------*/

      var varArreglo = varBase.join(',');
      //console.log('arreglo: '+varArreglo);
        /*----------------------------------------------------*/
        if (debug == 1) {
        console.log('JSON de Creditos' + varArreglo);

      }
      /*----------------------------------------------------*/

      var b = varArreglo.split(",");
      for (var i = 0; i < b.length; i++) {
        //console.log('id: #monto-'+b[i]);
        subtotal += Number($("#monto-" + b[i]).val());
        //console.log('monto: '+monto);
      }
       /*----------------------------------------------------*/
       if (debug == 1) {
        console.log('Subtotal' + subtotal);

      }
      /*----------------------------------------------------*/
      $("#idents").val(varArreglo);
      $("#monto").html('$ ' + $.number(subtotal));
      $("#subtotalDeCreditos").val(subtotal);
      //console.log('subtotal: '+subtotal);
    }

    function cambiadiv(div) {
      //  alert('Muestra uno y oculta otro');
      $("#detalleDeVenta-" + div).toggle('fast');
      $("#detalleDePagos-" + div).toggle('fast');
    }

    function imprimeTicketVenta(idVenta) {

      $('<form action="imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="' + idVenta + '"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
    }

    function muestraMontoComision(idBanco) {
      $("#credencialIne").val('');
      $("#folio").val('');
      $("#lbl-1").html('');
      $("#lbl-2").html('');
      $("#pago").val('');
      $("#cargarPago").prop("disabled", true);
      var formaPago = $("#formaPago option:selected").val();
      if (formaPago == 2 || formaPago == 4 || formaPago == 5) {
        var total = parseFloat($("#subtotalDeCreditos").val());

        $.post("funciones/verificaComision.php", {
            banco: idBanco,
            formaPago: formaPago
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              var com1 = resp[1];
              var com2 = resp[2];
              if (formaPago == 2) {
                var com = com1;
              } else {
                var com = com2;
              }
              if (com > 0) {
                var cComision = com / 100;
                if (total > 0) {
                  $("#porcentajeComision").val(com);
                  $("#lbl-1").html('<p class="text-center">Comisión (' + com + '%) = </p>');
                  $("#inputPago").attr('placeholder', "Ingrese el Monto");
                } else {
                  $("#porcentajeComision").val(com);
                  $("#lbl-1").html('<p class="text-center">Comisión (' + com + '%) = </p>');
                  $("#inputPago").prop('onChange', 'verificaComision');
                  $("#inputPago").attr('placeholder', "Ingrese el Monto para calcular la comisión");
                }
              } else {
                $("#lbl-1").html('<p class="text-center"><b class="text-dark">Este banco no cuenta con comisión</b></p>');
              }
            } else {
              $("#porcentajeComision").val(0);
              $("#lbl-1").html('<p class="text-center">Este banco no genera comisión con ésta Forma de Pago</p>');
            }
          });
      } else {
        $("#lbl-1").html('');
        $("#lbl-2").html('');
        $("#porcentajeComision").val(0);
        $("#inputPago").attr('placeholder', 'Ingrese el Monto');
      }
    }

    function verificaComision() {
      var com = parseFloat($("#porcentajeComision").val());
      var formaPago = $("#formaPago option:selected").val();
      var monto = $("#pago").val();
      var total = parseFloat(monto.replace(",", ""));
      if (total > 0) {
        if (total > 0 && com > 0) {
          var cComision = com / 100;
          var tTotal = cComision * total;
          var tComision = total + tTotal;
          //alert('Total: '+total+', cComision: '+cComision+', tTotal: '+tTotal+', tComision: '+tComision);
          $("#cargarPago").removeAttr("type").attr("type", "submit");
          $("#cargarPago").prop("disabled", false);
          $("#comision").val(tTotal);
          $("#lbl-1").html('<p class="text-center">Comisión (' + com + '%) = <b class="text-warning">$' + $.number(tTotal, 2) + ' </b></p>');
          $("#lbl-2").html('<h4 class="text-center">Total a Cobrar = <b class="text-success">$' + $.number(tComision, 2) + '</b></h4>');
        } else {
          $("#cargarPago").prop("disabled", false);
          $("#cargarPago").removeAttr("type").attr("type", "submit");
          $("#lbl-2").html('');
          if (formaPago == 2 || formaPago == 4 || formaPago == 5) {
            $("#lbl-1").html('<p class="text-center"><b class="text-dark">Este banco no cuenta con comisión</b></p>');
          }
        }
      } else {
        $("#cargarPago").removeAttr("type").attr("type", "button");
        $("#cargarPago").prop("disabled", true);
      }
    }
  </script>

</body>

</html>