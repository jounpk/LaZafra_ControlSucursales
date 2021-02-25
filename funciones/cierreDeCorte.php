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
                $sql = "SELECT *
                FROM cortes
                WHERE estatus = '1' AND idSucursal = '$idSucursal' AND idUserReg = '$idUser'";
                $res = mysqli_query($link,$sql) or die('Problemas al consultar los cortes, notifica a tu Administrador.');
                $disponible = mysqli_num_rows($res);
                if ($disponible < 1) {
                  echo '$disponible: '.$disponible;
                  echo '<script>
                          location.href="home.php";
                        </script> ';
                  #exit(0);
                }
                $dc = mysqli_fetch_array($res);
                $idCorte = $dc['id'];
                $montoEfectivo = $dc['montoEfectivo'];
                if ($montoEfectivo > 0) {
                  $disabled = 'disabled = "disabled"';
                }
                echo '<input type="hidden" id="totalEfectivo" value="'.$montoEfectivo.'">';
                ?>

                <section id="contenido">
                  <div class="row">
                    <div>
                        <h2 class="text-<?=$pyme;?>">Corte de Caja</h2>
                    </div>
                    <div class="ml-auto">
                      <h4><b><?=$info->nombreSuc;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4>
                    </div>
                    <br><br>
                  </div>

                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card border-<?=$pyme;?>" id="divDetalleBilletes">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="m-b-0 text-white">Detallado de Billetes</h4>
                          </div>
                            <div class="card-body">
                              <div>
                                <form role="form" action="funciones/cierraCorte.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Monedas de $100</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="moneda100" name="moneda100" class="form-control" onchange="sumaMonto(this.value,'moneda100');" placeholder="Monedas de $100" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                      <label class="control-label">Billetes de $1000</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-danger" id="basic-addon1"><i class="far fa-money-bill-alt"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="billete1000" name="billete1000" class="form-control" onchange="sumaMonto(this.value,'billete1000');" placeholder="Billetes de $1000" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Monedas de $20</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="moneda20" name="moneda20" class="form-control" onchange="sumaMonto(this.value,'moneda20');" placeholder="Monedas de $20" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                      <label class="control-label">Billetes de $500</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-info" id="basic-addon1"><i class="far fa-money-bill-alt"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="billete500" name="billete500" class="form-control" onchange="sumaMonto(this.value,'billete500');" placeholder="Billetes de $500" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Monedas de $10</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="moneda10" name="moneda10" class="form-control" onchange="sumaMonto(this.value,'moneda10');" placeholder="Monedas de $10" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                      <label class="control-label">Billetes de $200</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-success" id="basic-addon1"><i class="far fa-money-bill-alt"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="billete200" name="billete200" class="form-control" onchange="sumaMonto(this.value,'billete200');" placeholder="Billetes de $200" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Monedas de $5</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="moneda5" name="moneda5" class="form-control" onchange="sumaMonto(this.value,'moneda5');" placeholder="Monedas de $5" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                      <label class="control-label">Billetes de $100</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="far fa-money-bill-alt"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="billete100" name="billete100" class="form-control" onchange="sumaMonto(this.value,'billete100');" placeholder="Billetes de $100" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Monedas de $2</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="moneda2" name="moneda2" class="form-control" onchange="sumaMonto(this.value,'moneda2');" placeholder="Monedas de $2" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                      <label class="control-label">Billetes de $50</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-danger" id="basic-addon1"><i class="far fa-money-bill-alt"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="billete50" name="billete50" class="form-control" onchange="sumaMonto(this.value,'billete50');" placeholder="Billetes de $50" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Monedas de $1</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="moneda1" name="moneda1" class="form-control" onchange="sumaMonto(this.value,'moneda1');" placeholder="Monedas de $1" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                      <label class="control-label">Billetes de $20</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-info" id="basic-addon1"><i class="far fa-money-bill-alt"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="billete20" name="billete20" class="form-control" onchange="sumaMonto(this.value,'billete20');" placeholder="Billetes de $20" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                      <label class="control-label">Cambio Extra</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-warning" id="basic-addon1"><i class="mdi mdi-coin"></i></span>
                                        </div>
                                            <input type="number" min="0" value="0" id="cambio" name="cambio" step="0.01" class="form-control" onchange="sumaMonto(this.value,'step');" placeholder="Cambio Extra" required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-3">
                                      <label class="control-label">Total Efectivo:</label>
                                      <div class="input-group mb-3">
                                            <h4 class="text-dark">&nbsp;&nbsp;&nbsp;&nbsp;<b>$<?=number_format($montoEfectivo,2,'.',',');?></b></h4>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-3">
                                      <label class="control-label">Monto Capturado:</label>
                                      <div class="input-group mb-3">
                                            <h5 class="text-success">&nbsp;&nbsp;&nbsp;&nbsp;<b id="lblMontoTot">$0</b></h5>
                                            <input type="hidden" name="montoTotal" id="montoTotal" value="">
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="idCorte" value="<?=$idCorte;?>">
                                        <div id="btnEnvio"><button type="button" class="btn btn-<?=$pyme;?>" id="btnCierraCorte" <?=$disabled;?>>Cerrar Corte</button></div>
                                    </div>
                                  </form>
                              </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4">
                      <div class="card border-<?=$pyme;?>">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="m-b-0 text-white">Ticket de Corte</h4>
                          </div>
                            <div class="card-body">
                              <div class="scrollable" style="height:650px;">
                                <div class="rows"><center>
                                  <div class="col-lg-12" id="ticket">
                                    <?php
                                    if ($idCorte > 0) {
                                      $sql="SELECT c.*, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS username,
                                            DATE_FORMAT(c.fechaReg,'%d-%m-%Y  %H:%m:%s') AS fechaApertura,
                                          	DATE_FORMAT(c.fechaCierre,'%d-%m-%Y  %H:%m:%s') AS fechaCierre,
                                          	s.nombre AS sucursal, s.direccion, e.rfc AS rfcEmpresa,
																						(c.totalDevoluciones + c.totalGastos) AS egresos,
																						e.iconoColor
																					FROM cortes c
                                          INNER JOIN segusuarios u ON c.idUserReg = u.id
                                          INNER JOIN sucursales s ON c.idSucursal = s.id
																					INNER JOIN empresas e ON c.idEmpresa = e.id
																					WHERE c.id = '$idCorte'";
                                      $result=mysqli_query($link,$sql) or die('Problemas al Consultar el Detallado de Caja.');
                                      $var=mysqli_fetch_array($result);

                                      //echo '===> '.$var['idSucursal'].' == '.$idSucursal.' ----->  '.$var['idUsuario'].' - '.$idUsuario;
                                      if ($var['idSucursal'] == $idSucursal && $var['idUserReg'] == $idUser) {

                                      //echo $sql;
                                      	?>

                                      	<table border="0" style="font-size:13px" width="260px">
                                      		<tr>
                                      			<th colspan="3" align="center"><center><img class="img-rounded" src="<?=$var['iconoColor'];?>" width="100px"></center></th>
                                      		</tr>
                                      		<tr>
                                          	<th colspan="3" align="center" style="font-size:18px"><center><?=$var['sucursal'];?></center></th>
                                      	  </tr>
                                      		<tr>
                                          	<th colspan="3" align="center" style="font-size:12px"><center><?=$var['direccion'];?></center></th>
                                      	  </tr>
                                          <tr>
                                      			<td colspan="3">Usuario:<?=$var['username'];?></th>
                                      		</tr>
                                          <tr>
                                      			<td colspan="3">RFC: <?=$var['rfcEmpresa'];?></th>
                                      		</tr>
                                      		<tr>
                                          	<td colspan="2"><br><?=$var['fechaCierre'];?></td>
                                            <td colspan="1" align="center"><br>Corte: <?=$var['id'];?></td>
                                          </tr>
                                          <?php
                                          if ($var['totalGastos']>0) {
                                            echo '
                                            <tr>
                                        	    <td colspan="3" style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DETALLADO DE GASTOS</td>
                                          	</tr>';
                                            $tGasto=0;
                                            $sql="SELECT * FROM gastos WHERE idCorte = '$idCorte'";
                                            $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Pagos'.$sql);
                                            //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
                                            while ($pag= mysqli_fetch_array($datPago)) {
                                              echo '
                                              <tr>
                                                <td colspan="2">'.$pag['descripcion'].'</td>
                                                <td>$'.number_format($pag['monto'],2,".","'").'</td>
                                              </tr>';
                                            }
                                            echo '<tr>
                                              <td colspan="2" align="right"><strong>TOTAL DE GASTOS: </strong></td>
                                              <td><strong>$'.number_format($var['totalGastos'],2,".","'").'</strong></td>
                                            </tr>
                                            <tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:35px; ">&nbsp;</td></tr>';

                                          }
                                          if ($var['vtaCredito']>0) {
                                            echo '
                                            <tr>
                                        	    <td colspan="3" style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DETALLADO DE CREDITOS</td>
                                          	</tr>';
                                            $tCredito=0;
                                            $sql="SELECT v.id,c.nombre AS nomCliente, SUM(pv.monto) AS monto
                                                  FROM ventas v
                                                  INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                                  INNER JOIN clientes c ON v.idCliente = c.id
                                                  WHERE pv.idFormaPago = '7' AND v.estatus = '2' AND v.idCorte = '$idCorte'
                                                  GROUP BY v.id
                                                  ORDER BY v.id ASC";
                                            $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Creditos'.$sql);
                                            //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
                                            while ($pag= mysqli_fetch_array($datPago)) {
                                              echo '
                                              <tr>
                                                <td colspan="2">'.$pag['nomCliente'].'</td>
                                                <td>$'.number_format($pag['monto'],2,".","'").'</td>
                                              </tr>';
                                              $tCredito += $pag['monto'];
                                            }
                                            echo '<tr>
                                              <td colspan="2" align="right"><strong>TOTAL DE CREDITOS:&nbsp;</strong><br><br></td>
                                              <td><strong>$'.number_format($tCredito,2,".","'").'</strong><br><br></td>
                                            </tr>
                                            <tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:35px; ">&nbsp;</td></tr>';
                                          }?>

                                          <?php
                                          if ($var['vtaTransferencia']>0) {
                                            echo '
                                            <tr>
                                        	    <td colspan="3" style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DETALLADO DE TRANFERENCIAS</td>
                                          	</tr>';
                                            $tTransfer=0;
                                            $sql="SELECT v.id, SUM(pv.monto) AS monto
                                                  FROM ventas v
                                                  INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                                  WHERE pv.idFormaPago = '3' AND v.estatus = '2' AND v.idCorte = '$idCorte'
                                                  GROUP BY v.id
                                                  ORDER BY v.id ASC";
                                            $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Transferencias'.$sql);
                                            //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
                                            while ($pag= mysqli_fetch_array($datPago)) {
                                              echo '
                                              <tr>
                                                <td colspan="2">Venta con Folio: '.$pag['id'].'</td>
                                                <td>$'.number_format($pag['monto'],2,".","'").'</td>
                                              </tr>';
                                              $tTransfer += $pag['monto'];
                                            }
                                            echo '<tr>
                                              <td colspan="2" align="right"><strong>TOTAL DE TRANSFERENCIAS:&nbsp;</strong><br><br></td>
                                              <td><strong>$'.number_format($tTransfer,2,".","'").'</strong><br><br></td>
                                            </tr>
                                            <tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:35px; ">&nbsp;</td></tr>';
                                          }?>

                                          <?php
                                          if ($var['vtaCheque']>0) {
                                            echo '
                                            <tr>
                                        	    <td colspan="3" style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DETALLADO DE CHEQUES</td>
                                          	</tr>';
                                            $tCheque=0;
                                            $sql="SELECT v.id, SUM(pv.monto) AS monto
                                                  FROM ventas v
                                                  INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                                  WHERE pv.idFormaPago = '2' AND v.estatus = '2' AND v.idCorte = '$idCorte'
                                                  GROUP BY v.id
                                                  ORDER BY v.id ASC";
                                            $datPago=mysqli_query($link,$sql) or die('Problemas al Listar Cheques'.$sql);
                                            //echo '<tr><td colspan="3">'.$sql.'</td></tr>';
                                            while ($pag= mysqli_fetch_array($datPago)) {
                                              echo '
                                              <tr>
                                                <td colspan="2">Venta con Folio: '.$pag['id'].'</td>
                                                <td>$'.number_format($pag['monto'],2,".","'").'</td>
                                              </tr>';
                                              $tCheque += $pag['monto'];
                                            }
                                            echo '<tr>
                                              <td colspan="2" align="right"><strong>TOTAL DE CHEQUES:&nbsp;</strong><br><br></td>
                                              <td><strong>$'.number_format($tCheque,2,".","'").'</strong><br><br></td>
                                            </tr>
                                            <tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:35px; ">&nbsp;</td></tr>';
                                          }?>

                                          <?php
                                          $sqlBol="SELECT v.id, SUM(pv.monto) AS monto
                                                    FROM ventas v
                                                    INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                                    WHERE pv.idFormaPago = '6' AND v.estatus = '2' AND v.idCorte = '$idCorte'
                                                    GROUP BY v.id
                                                    ORDER BY v.id ASC";
                                          $datBol=mysqli_query($link,$sqlBol) or die('Problemas al Listar Cheques'.$sql);
                                          $numres=mysqli_num_rows($datBol);
                                          //echo '<tr><td colspan="3">'.$sql.'</td></tr>';

                                          if ($numres>0) {
                                            echo '
                                            <tr>
                                              <td colspan="3" style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DETALLADO DE BOLETAS</td>
                                            </tr>';
                                            $tBoleta=0;
                                            while ($pag= mysqli_fetch_array($datBol)) {
                                              echo '
                                              <tr>
                                                <td colspan="2">Venta con Folio: '.$pag['id'].'</td>
                                                <td>$'.number_format($pag['monto'],2,".","'").'</td>
                                              </tr>';
                                              $tBoleta += $pag['monto'];
                                            }
                                            echo '<tr>
                                              <td colspan="2" align="right"><strong>TOTAL DE BOLETAS: </strong><br><br></td>
                                              <td><strong>$'.number_format($tBoleta,2,".","'").'</strong><br><br></td>
                                            </tr>
                                            <tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:35px; ">&nbsp;</td></tr>';
                                          }?>


                                      		<tr>
                                      	    <td colspan="3" style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DETALLADO FINAL</td>
                                        	</tr>
                                      		<tr>
                                      	    <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">DESCRIPCION</td>
                                            <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">INGRESOS</td>
                                            <td style="border-top:1px dotted #999; border-bottom:1px dotted #999;">GASTOS</td>
                                        	</tr>
                                          <?php
                                          $sqlFormaPago = "SELECT CONCAT('En ',cm.nombre) AS formaPago, SUM(pv.monto) AS monto, SUM(md.monto) AS montoDevuelto,
                                                            (SUM(pv.monto) - IF(SUM(md.monto) > 0,SUM(md.monto),0)) AS montoReal
                                                            FROM ventas v
                                                            INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                                            INNER JOIN sat_formapago cm ON pv.idFormaPago = cm.id
                                                            LEFT JOIN devoluciones d ON v.id = d.idVenta
                                                            LEFT JOIN montosdevueltos md ON d.id = md.idDevolucion AND cm.id = md.idFormaPago
                                                            WHERE v.idCorte = '$idCorte' AND v.estatus = '2'
                                                            GROUP BY pv.idFormaPago
                                                            ORDER BY cm.id ASC";
                                          $resFormaPago = mysqli_query($link,$sqlFormaPago) or die('Problemas al consultar las formas de pago, notifica a tu Administrador.');
                                            while ($lst = mysqli_fetch_array($resFormaPago)) {
                                              echo '<tr>
                                              <td>'.$lst['formaPago'].'</td>
                                              <td style="background-color: #dfdfdf;">$'.number_format($lst['montoReal'],2,".","'").'</td>
                                              <td></td>
                                              </tr>';

                                            }

                                          ?>
                                          <tr>
                                          	<td>Inicio de Caja</td>
                                            <td>$<?=number_format($var['montoInicio'],2,".","'");?></td>
                                            <td></td>
                                        	</tr>
                                          <tr><td colspan="3" align="center"><hr aling="center" size="1"></td></tr>
                                      		<?php

                                          $sqlConCredPag = "SELECT IF(SUM(pc.monto) > 0,SUM(pc.monto),0) AS creditosPagados
                                                            FROM ventas v
                                                            INNER JOIN creditos c ON v.id = c.idVenta
                                                            INNER JOIN pagoscreditos pc ON c.id = pc.idCredito
                                                            WHERE v.estatus = 2 AND pc.idCorte = '$idCorte'";
                                          $resConCredPag = mysqli_query($link,$sqlConCredPag) or die('Problemas al consultar los créditos pagados, notifica a tu Administrador.');
                                          $credPag = mysqli_fetch_array($resConCredPag);
                                          if ($credPag['creditosPagados']>0) {
                                            echo '<tr>
                                              <td>Creditos Pagados</td>
                                              <td>$'.number_format($credPag['creditosPagados'],2,".","'").'</td>
                                              <td></td>
                                            </tr>';
                                          }
                                          if ($var['totalGastos']>0) {
                                            echo '<tr>
                                              <td>Gastos</td>
                                              <td></td>
                                              <td>$'.number_format($var['totalGastos'],2,".","'").'</td>
                                            </tr>';
                                          }
                                          if ($var['totalDevoluciones']>0) {
                                            echo '<tr>
                                              <td>Devoluciones</td>
                                              <td></td>
                                              <td>$'.number_format($var['totalDevoluciones'],2,".","'").'</td>
                                            </tr>';
                                          }
                                          if ($var['montoEfectivo']>0) {
                                            echo '<tr>
                                              <td>Total Efectivo</td>
                                              <td>$'.number_format($var['montoEfectivo'],2,".","'").'</td>
                                              <td></td>
                                            </tr>';
                                          }
                                          $ingresos = $var['totalVta'] + $credPag['creditosPagados'];
                                      		?>
                                          <tr>
                                      			<td colspan="2" align="right" style="border-top:1px dotted #999;">Ingresos= </td>
                                      			<td align="right" style="border-top:1px dotted #999;">$<?=number_format($ingresos,2,".","'");?></td>
                                      		</tr>
                                          <tr>
                                            <td colspan="2" align="right" style="border-top:1px dotted #999;">Egresos= </td>
                                            <td align="right" style="border-top:1px dotted #999;">$<?=number_format($var['egresos'],2,".","'");?></td>
                                          </tr>
                                          <tr>
                                      			<th colspan="2" align="right" style="border-top:1px dotted #999;"><strong>TOTAL VENTA= </strong></th>
                                      			<th align="right" style="border-top:1px dotted #999;"><strong>$<?=number_format($var['totalVta'],2,".","'");?></strong></th>
                                      		</tr>
                                      		<tr><td colspan="3" align="center" style=" margin-top:15px; padding-top:50px; "><center><hr aling="center" size="2"></center></td></tr>
                                      		<tr><th colspan="3" align="center"><center><?=$var['username'];?></center></th></tr>
                                          <tr><th colspan="3" align="center"><center><strong>Corte de Caja</strong></center></th></tr>
                                        </table>
                                  </div></center>
                                  <div class="col-lg-12">
                                    <input type="hidden" id="idCorte" value="<?=$idCorte;?>">
                                    <button class="btn btn-info btn-block" onclick="recalcularCorte(<?=$idCorte;?>,<?=$idUser;?>,<?=$idSucursal;?>)">Recalcular</button>
                                  </div>
                                  <?php
                                    $mtoFinal = number_format($var['totalVta'],2,".","");
                                  } else {
                                  $mtoFinal = 'NoAplica';
                                  echo '<div class="col-lg-12"><center><h3>NO TIENES ACCESO A ESTE CORTE</h3></center></div>';
                                  }

                                  } else{
                                      echo '<div class="col-lg-12"><center><h3>No se ha seleccionado Ningún Corte</h3></center></div>';
                                      $mtoFinal = 'N/A';
                                  } ?>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>

                  </div>



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

    <script>
    $(document).ready(function(){
      <?php
    #  include('funciones/basicFuctions.php');
    #  alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjCierreDeCorte'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCierreDeCorte']."');";
				unset($_SESSION['LZFmsjCierreDeCorte']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCierreDeCorte'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCierreDeCorte']."');";
        unset($_SESSION['LZFmsjSuccessCierreDeCorte']);
			}
      ?>
        //sumaMonto();
    });

    function sumaMonto(monto,id){
      // comeinza a capturar el monto capturado
      var totalEfectivo = $("#totalEfectivo").val();
      if (monto < 0) {
        notificaBad('El monto no debe ser menor a 0');
        $("#"+id).val('0');
      }
      var total = 0;
      //monedas
      var m100 = (parseInt($("#moneda100").val()) * 100);
      var m20 = (parseInt($("#moneda20").val()) * 20);
      var m10 = (parseInt($("#moneda10").val()) * 10);
      var m5 = (parseInt($("#moneda5").val()) * 5);
      var m2 = (parseInt($("#moneda2").val()) * 2);
      var m1 = (parseInt($("#moneda1").val()) * 1);
      //billetes
      var b1000 = (parseInt($("#billete1000").val()) * 1000);
      var b500 = (parseInt($("#billete500").val()) * 500);
      var b200 = (parseInt($("#billete200").val()) * 200);
      var b100 = (parseInt($("#billete100").val()) * 100);
      var b50 = (parseInt($("#billete50").val()) * 50);
      var b20 = (parseInt($("#billete20").val()) * 20);
      var cambio = (parseFloat($("#cambio").val()) * 1);
      total = m100+m20+m10+m5+m2+m1+b1000+b500+b200+b100+b50+b20+cambio;
      var mtotal = $.number(total,2);
//      alert('Total: '+mtotal+', m100: '+m100+', m20: '+m20+', m10: '+m10+', b1000: '+b1000);
      $("#lblMontoTot").html('$'+mtotal);
      $("#montoTotal").val(total);
      console.log('$'+mtotal);
      if (total == totalEfectivo) {
        var idCorte = $("#idCorte").val();
        $("#btnEnvio").html('<button type="submit" class="btn btn-<?=$pyme;?>" id="btnCierraCorte">Cerrar Corte</button>');

    } else if (totalEfectivo == 0) {
      $("#btnEnvio").html('<button type="submit" class="btn btn-<?=$pyme;?>" id="btnCierraCorte">Cerrar Corte</button>');
    } else {
      $("#btnEnvio").html('<button type="button" class="btn btn-<?=$pyme;?>" id="btnCierraCorte" disabled>Cerrar Corte</button>');

     }
    }

    function recalcularCorte(idCorte,idUser,idSucursal){
      if (idCorte > 0) {
        $('<form action="funciones/recalcular.php" method="POST"><input type="hidden" name="idCorte" value="'+idCorte+'"><input type="hidden" name="idUser" value="'+idUser+'"><input type="hidden" name="idSucursal" value="'+idSucursal+'"></form>').appendTo('body').submit();
      } else {
        notificaBad('No se reconoció el corte, actualiza e inténtalo nuevamente, si persiste notifica a tu Administrador.');
      }
    }

    function imprimeTicketCorte(idCorte){
      $('<form action="imprimeTicketCorte.php" method="POST" target="_blank"><input type="hidden" name="idCorte" value="'+idCorte+'"></form>').appendTo('body').submit();
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
