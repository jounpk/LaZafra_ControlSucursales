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

    <!-- This Page CSS -->
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/morris.js/morris.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
td.details-control {
    background: url('../dist/js/pages/datatable/details_open.png') no-repeat center center;
    cursor: pointer;
}

tr.shown td.details-control {
    background: url('../dist/js/pages/datatable/details_close.png') no-repeat center center;
}
</style>
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
                <?php
                # se obtiene los datos por $_POST
                $prod = (isset($_POST['producto']) && $_POST['producto'] != '') ? $_POST['producto'] : '' ;
                $prov = (isset($_POST['proveedor']) && $_POST['proveedor'] != '') ? $_POST['proveedor'] : '' ;
                # se declaran los filtro
                $filtroProducto = '';
                $filtroProveedor = '';
                # se evalúa que si hay datos por post se obtiene la cadena dividida por "|" con el explode("separador",$variable) para generar el arreglo (en este caso $valores)
                if ($prod != '') {
                  $valores = explode("|",$prod);
                  # se obtiene el tipo para el filtro de productos
                  $tipo = $valores[0];
                  # se declara el filtro de productos dependiendo del tipo
                  if ($tipo == 1) {
                    $idProd = $valores[1];
                    $filtroProducto = " AND dc.idProducto = '$idProd'";
                    $filtroProducto2 = " AND dv.idProducto = '$idProd'";
                  } else {
                    $nombreProd = $valores[1];
                    $filtroProducto = " AND dc.nombreProducto = '$valores[1]'";
                    $filtroProducto2 = "";
                  }
                }
                # se evalúa que haya proveedores, sí hay se genera el filtro
                if ($prov != '') {
                  $idProv = implode(",",$prov);
                  $filtroProveedor = " AND c.idProveedor IN($idProv)";
                }
                $_SESSION['filtroProducto'] = $filtroProducto;
                $_SESSION['filtroProveedor'] = $filtroProveedor;
                # se evalúa que se haya mandado un producto para mostrar sólo el filtro o el filtro con los datos
                #echo '$prov: '.var_dump($prov);
                echo '<br>$filtroProveedor: '.$filtroProveedor  ;
                echo '<br>$filtroProducto: '.$filtroProducto  ;
                if ($prod == '') {
                 ?>
                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white"><i class="fas fa-filter"></i> Consulta de Costos</h4>
                  </div>
                  <div class="card-body">
                  <form role="form" method="post" action="#">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-5">
                            <div class="input-group">
                                <select class="select2 form-control custom-select select2Producto" name="producto" onchange="listaProv(this.value,1);" id="producto1" style="width: 100%; height:36px;" required>
                                  <optgroup label="Productos">
                              <?php
                                $sqlProd = "SELECT DISTINCT(a.nomProducto) AS nomProducto, CONCAT(a.tipo,'|',IF(a.idProducto > 0,a.idProducto,a.nomProducto)) AS id
                                          FROM compras c
                                          INNER JOIN (
                                          SELECT dc.idCompra,'1' AS tipo, dc.idProducto, p.descripcion AS nomProducto FROM detcompras dc INNER JOIN productos p ON dc.idProducto = p.id WHERE dc.idProducto > 0
                                          UNION
                                          SELECT dc.idCompra,'2' AS tipo, dc.idProducto, dc.nombreProducto AS nomProducto FROM detcompras dc WHERE dc.nombreProducto != ''
                                          ) a ON a.idCompra = c.id
                                          WHERE c.estatus = 2 ORDER BY nomProducto ASC";
                                $resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los Productos, notifica a tu Administrador.');

                                while ($cli = mysqli_fetch_array($resProd)) {
                                  $activa = ($prod == $cli['id']) ? 'selected' : '' ;
                                  echo '<option value="'.$cli['id'].'"  '.$activa.'>'.$cli['nomProducto'].'</option>';
                                }
                               ?>
                                  </optgroup>
                                </select>
                            </div>
                          </div>
                          <div class="col-md-5">
                            <div class="input-group">
                              <select class="select2 form-control select2Proveedor" multiple="multiple" name="proveedor[]" id="proveedor1" style="height: 36px;width: 100%;">
                                <optgroup label="Proveedores">
                              <?php
                                $sqlProd = "SELECT id,nombre
                                                FROM proveedores
                                                WHERE estatus = 1";
                                $resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los Productos, notifica a tu Administrador.');
                                while ($cli = mysqli_fetch_array($resProd)) {
                                  $pos = strpos($idProv, $cli['id']);
                                  $act2 = ($pos !== false) ? 'selected' : '' ;
                                  echo '<option value="'.$cli['id'].'"  '.$act2.'>'.$cli['nombre'].'</option>';
                                }
                               ?>
                                </optgroup>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-block btn-rounded">Buscar</button>
                          </div>
                        </form>
                        </div>
                      </div>
                      <div class="col-md-3"></div>
                    </div>
                  </div>
                </div>
                <!-- ############################################################### -->
                <?php
              } else {
                 ?>
                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white"><i class="fas fa-filter"></i> Filtro</h4>
                  </div>
                  <div class="card-body">
                    <form role="form" method="post" action="#">
                      <div class="row">
                        <div class="col-md-5">
                          <div class="input-group">
                              <select class="select2 form-control custom-select select2Producto" name="producto" id="producto2" onchange="listaProv(this.value,2);" style="width: 100%; height:36px;">
                                <optgroup label="Productos">
                              <?php
                              $sqlProd = "SELECT DISTINCT(a.nomProducto) AS nomProducto, CONCAT(a.tipo,'|',IF(a.idProducto > 0,a.idProducto,a.nomProducto)) AS id
                                        FROM compras c
                                        INNER JOIN (
                                        SELECT dc.idCompra,'1' AS tipo, dc.idProducto, p.descripcion AS nomProducto FROM detcompras dc INNER JOIN productos p ON dc.idProducto = p.id WHERE dc.idProducto > 0
                                        UNION
                                        SELECT dc.idCompra,'2' AS tipo, dc.idProducto, dc.nombreProducto AS nomProducto FROM detcompras dc WHERE dc.nombreProducto != ''
                                        ) a ON a.idCompra = c.id
                                        WHERE c.estatus = 2 ORDER BY nomProducto ASC";
                              $resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los Productos, notifica a tu Administrador.');

                              while ($cli = mysqli_fetch_array($resProd)) {
                                $activa = ($prod == $cli['id']) ? 'selected' : '' ;
                                echo '<option value="'.$cli['id'].'"  '.$activa.'>'.$cli['nomProducto'].'</option>';
                              }
                             ?>
                                </optgroup>
                              </select>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="input-group">
                            <select class="select2 form-control select2Proveedor" placeholder="Selecciona el Proveedor" multiple="multiple" name="proveedor[]" id="proveedor2" style="height: 36px;width: 100%;">
                              <optgroup label="Proveedores">
                            <?php
                              $sqlProd = "SELECT DISTINCT(p.id),p.nombre
                                              FROM compras c
                                              INNER JOIN proveedores p ON c.idProveedor = p.id
                                              INNER JOIN detcompras dc ON c.id = dc.idCompra
                                              WHERE dc.idProducto = '$idProd'";
                              $resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los Productos, notifica a tu Administrador.');

                              while ($cli = mysqli_fetch_array($resProd)) {
                                $pos = strpos($idProv, $cli['id']);
                                $act2 = ($pos !== false) ? 'selected' : '' ;
                                echo '<option value="'.$cli['id'].'"  '.$act2.'>'.$cli['nombre'].'</option>';
                              }
                             ?>
                              </optgroup>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <button type="submit" class="btn btn-info btn-block btn-rounded">Buscar</button>
                        </div>
                      </div>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="white-box text-center" id="mostrarImg">  </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-6">

                            <h4 class="box-title m-t-40"><b>Descripción</b></h4>
                            <?php
                              if ($tipo == 1) {
                                $sqlDatos = "SELECT IF(ISNULL(p.descripcionlarga) OR p.descripcionlarga = '','Sin descripción',p.descripcionlarga) AS descripcionlarga,p.foto,
                                              p.costo, GROUP_CONCAT(pb.precio SEPARATOR ',') AS precios,p.idTagsIngredienteActivo,p.descripcion
                                              FROM productos p
                                              LEFT JOIN preciosbase pb ON p.id = pb.idProducto
                                              WHERE p.id = '$idProd'
                                              ORDER BY pb.precio DESC";
                                $resDatos = mysqli_query($link,$sqlDatos) or die('Problemas al consultar la descripción del producto, notifica a tu Administrador.');
                                $d = mysqli_fetch_array($resDatos);
                                $nombreProd = $d['descripcion'];
                                $foto = ($d['foto'] != '') ? $d['foto'] : 'assets/images/noimg.png';
                                echo '<input type="hidden" id="imgProd" value="../'.$foto.'">';
                            echo '<p>&nbsp;&nbsp;'.$d['descripcionlarga'].'.</p>';
                            echo '<small class="text-info"><b>COSTO:</b></small>
                                <h2 class="m-t-1"><u>$'.number_format($d['costo'],2,'.',',').'</u></h2>';
                                  if ($d['precios'] != '') {
                          echo '<div class="btn-group">
                                  <button type="button" class="btn btn-danger btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Precios de Venta
                                  </button>
                                  <div class="dropdown-menu">';
                                  # se realiza el listado de los precios
                            $precios = explode(',',$d['precios']);
                            for ($i=0; $i < sizeof($precios); $i++) {
                              echo '<a class="dropdown-item">$'.$precios[$i].'</a>';
                            }
                            echo '</div>
                                </div>';
                              }
                              $ingAct = $d['idTagsIngredienteActivo'];
                              if ($ingAct != '') {
                          echo '<h3 class="box-title m-t-20">Ingredientes Activos</h3>
                                <div>';
                                $sqlIng = "SELECT id,nombre
                                          FROM catingact
                                          WHERE id IN($ingAct)";
                                $resIng = mysqli_query($link,$sqlIng) or die('Problemas al consultar los ingredientes, notifica a tu Administrador.');
                                $ing = mysqli_fetch_array($resIng);
                            echo '<button type="button" class="btn btn-rounded btn-info">'.$ing['nombre'].'</button>';

                          echo '</div>';
                              } else {
                                echo '<h3 class="box-title m-t-20">Sin Ingredientes Activos</h3>';
                              }

                              } else {
                                $sqlDatos = "SELECT dc.costoUnitario FROM detcompras dc INNER JOIN compras c ON dc.idCompra = c.id WHERE dc.nombreProducto = '$nombreProd' ORDER BY c.fechaReg DESC";
                                $resDatos = mysqli_query($link,$sqlDatos) or die('Problemas al consultar la descripción del producto, notifica a tu Administrador.');
                                $p = mysqli_fetch_array($resDatos);
                                echo '<input type="hidden" id="imgProd" value="../assets/images/noimg.png">';
                                echo '<p>&nbsp;&nbsp;Sin información.</p>
                              <small class="text-info"><b>COSTO:</b></small>
                              <h2 class="m-t-1"><u>$ '.number_format($p['costoUnitario'],2,'.',',').'</u></h2>
                              <h5 class="box-title m-t-20">No disponibles para su venta en sucursal</h5>
                              <h3 class="box-title m-t-20">Sin Ingredientes Activos</h3>';
                              }
                             ?>

                  </div>
                </div>
              </div>
            </div>
              <br>
              <!-- ##################################################################################### -->
              <!-- #################################### Tabla de compras ############################## -->
              <!-- ##################################################################################### -->
              <div class="card border-<?=$pyme;?>">
                <div class="card-header bg-<?=$pyme;?>">
                  <h4 class="text-white">Compras de: <?=$nombreProd;?></h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped extra-info" width="100%">
                      <thead>
                          <tr>
                            <th width="25px"></th>
                            <th width="25px">#</th>
                            <th>Proveedor</th>
                            <th class="text-center">Folio</th>
                            <th class="text-center">Fecha de Compra</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Crédito</th>
                            <th>Meta</th>
                            <th class="text-center">Costo Promedio</th>
                            <th class="text-center">Costo</th>
                          </tr>
                      </thead>

                    </table>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-9">
                  <div class="card">
                          <div class="card-body">
                              <div class="d-md-flex align-items-center">
                                  <div>
                                      <h4 class="card-title text-info"><b id="lblTitulo"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalVentas">Ventas <i class="fas fa-info-circle"></i></a></b></h4>
                                  </div>
                                  <div class="ml-auto">
                                      <!-- Tabs -->
                                      <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                                          <li class="nav-item">
                                              <a class="nav-link active" id="pills-home-tab2" data-toggle="pill" href="#divVentas" role="tab" onclick="selTab('Ventas',1);" aria-selected="true">Ventas</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link" id="pills-profile-tab2" data-toggle="pill" href="#divCompras" role="tab" onclick="selTab('Compras',2);" aria-selected="false">Compras</a>
                                          </li>

                                      </ul>
                                      <!-- Tabs -->
                                  </div>
                              </div>
                              <!-- ##################################################################################### -->
                              <!-- #################################### Grafico de ventas ############################## -->
                              <!-- ##################################################################################### -->
                              <div class="tab-content m-t-20" id="pills-tabContent2">
                                  <div class="tab-pane fade show active" id="divVentas" role="tabpanel" aria-labelledby="pills-home-tab2">
                                    <div>
                                      <?php
                                      $colores = ["#922B21", "#C39BD3", "#2E86C1", "#F1C40F", "#EB984E", "#17202A", "#F1948A", "#808B96"];
                                      #/*
                                      if ($filtroProducto2 != '') {
                                        $sqlVentas = "SELECT DATE_FORMAT(v.fechaReg,'%Y') AS anio,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '01' THEN dv.cantidad ELSE 0 END) AS ene,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '02' THEN dv.cantidad ELSE 0 END) AS feb,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '03' THEN dv.cantidad ELSE 0 END) AS mar,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '04' THEN dv.cantidad ELSE 0 END) AS abr,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '05' THEN dv.cantidad ELSE 0 END) AS may,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '06' THEN dv.cantidad ELSE 0 END) AS jun,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '07' THEN dv.cantidad ELSE 0 END) AS jul,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '08' THEN dv.cantidad ELSE 0 END) AS ago,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '09' THEN dv.cantidad ELSE 0 END) AS sep,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '10' THEN dv.cantidad ELSE 0 END) AS oct,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '11' THEN dv.cantidad ELSE 0 END) AS nov,
                                                SUM(CASE DATE_FORMAT(v.fechaReg,'%m') WHEN '12' THEN dv.cantidad ELSE 0 END) AS dic
                                                FROM ventas v
                                                INNER JOIN detventas dv ON v.id = dv.idVenta
                                                WHERE DATE_FORMAT(v.fechaReg,'%Y') BETWEEN (DATE_FORMAT(NOW(),'%Y') - 2) AND DATE_FORMAT(NOW(),'%Y')
                                                 AND 1=1 $filtroProducto2
                                                GROUP BY DATE_FORMAT(v.fechaReg,'%Y')
                                                ORDER BY DATE_FORMAT(v.fechaReg,'%Y') ASC";
                                                #echo $sqlVentas;
                                        $resVentas = mysqli_query($link,$sqlVentas) or die('Problemas al consultar las ventas, notifica a tu Administrador.');
                                        $colorVenta = '';
                                        $dataVenta = '';
                                        while ($venta = mysqli_fetch_array($resVentas)) {
                                          $colorVenta = $colores[mt_rand(0, count($colores) - 1)];
                                          $ene = $venta['ene'];
                                          $feb = $venta['feb'];
                                          $mar = $venta['mar'];
                                          $abr = $venta['abr'];
                                          $may = $venta['may'];
                                          $jun = $venta['jun'];
                                          $jul = $venta['jul'];
                                          $ago = $venta['ago'];
                                          $sep = $venta['sep'];
                                          $oct = $venta['oct'];
                                          $nov = $venta['nov'];
                                          $dic = $venta['dic'];
                                          $anio = $venta['anio'];
                                          $dataVenta .= "{
                                          data: [$ene,$feb,$mar,$abr,$may,$jun,$jul,$ago,$sep,$oct,$nov,$dic],
                                                    label: \"$anio\",
                                                    borderColor: \"$colorVenta\",
                                                    fill: false
                                                  },";
                                        }
                                      } else {
                                        $fecha = date('Y');
                                        $colorVenta = $colores[mt_rand(0, count($colores) - 1)];
                                        $dataVenta = "{
                                        data: [0,0,0,0,0,0,0,0,0,0,0,0],
                                                  label: \"$fecha\",
                                                  borderColor: \"$colorVenta\",
                                                  fill: false
                                                },";
                                      }

                                        $dataVenta = trim($dataVenta, ",");

                                        #*/
                                       ?>
                                        <canvas id="line-chart-ventas" height="150"></canvas>
                                    </div>
                                  </div>
                                  <!-- ##################################################################################### -->
                                  <!-- #################################### Grafico de Compras ############################## -->
                                  <!-- ##################################################################################### -->
                                  <div class="tab-pane fade" id="divCompras" role="tabpanel" aria-labelledby="pills-profile-tab2">
                                    <div>
                                      <?php
                                      #/*
                                        $sqlCompras = "SELECT DATE_FORMAT(c.fechaCompra, '%Y') AS anio,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '01' THEN dc.cantidad ELSE 0 END) AS ene,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '02' THEN dc.cantidad ELSE 0 END) AS feb,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '03' THEN dc.cantidad ELSE 0 END) AS mar,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '04' THEN dc.cantidad ELSE 0 END) AS abr,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '05' THEN dc.cantidad ELSE 0 END) AS may,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '06' THEN dc.cantidad ELSE 0 END) AS jun,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '07' THEN dc.cantidad ELSE 0 END) AS jul,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '08' THEN dc.cantidad ELSE 0 END) AS ago,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '09' THEN dc.cantidad ELSE 0 END) AS sep,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '10' THEN dc.cantidad ELSE 0 END) AS oct,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '11' THEN dc.cantidad ELSE 0 END) AS nov,
                                                        SUM(CASE DATE_FORMAT(c.fechaCompra,'%m') WHEN '12' THEN dc.cantidad ELSE 0 END) AS dic
                                                        FROM compras c
                                                        INNER JOIN detcompras dc ON c.id = dc.idCompra
                                                        WHERE DATE_FORMAT(c.fechaCompra,'%Y') BETWEEN (DATE_FORMAT(NOW(),'%Y') - 2) AND DATE_FORMAT(NOW(),'%Y')
                                                         AND 1=1 $filtroProducto
                                                        GROUP BY DATE_FORMAT(c.fechaCompra,'%Y'),dc.idProducto
                                                        ORDER BY anio ASC";
                                        $resCompras = mysqli_query($link,$sqlCompras) or die('Problemas al consultar las compras, notifica a tu Administrador.');
                                        $colorCompra = '';
                                        $dataCompra = '';
                                        while ($compra = mysqli_fetch_array($resCompras)) {
                                          $colorCompra = $colores[mt_rand(0, count($colores) - 1)];
                                          $ene = $compra['ene'];
                                          $feb = $compra['feb'];
                                          $mar = $compra['mar'];
                                          $abr = $compra['abr'];
                                          $may = $compra['may'];
                                          $jun = $compra['jun'];
                                          $jul = $compra['jul'];
                                          $ago = $compra['ago'];
                                          $sep = $compra['sep'];
                                          $oct = $compra['oct'];
                                          $nov = $compra['nov'];
                                          $dic = $compra['dic'];
                                          $anio = $compra['anio'];
                                          $dataCompra .= "{
                                          data: [$ene,$feb,$mar,$abr,$may,$jun,$jul,$ago,$sep,$oct,$nov,$dic],
                                                    label: \"$anio\",
                                                    borderColor: \"$colorCompra\",
                                                    fill: false
                                                  },";
                                        }

                                        $dataCompra = trim($dataCompra, ",");

                                        #*/
                                       ?>
                                        <canvas id="line-chart-compras" height="150"></canvas>
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                </div>
                <!-- ============================================================== -->
                <!-- Gráfico de Dona  -->
                <!-- ============================================================== -->
                <div class="col-md-3">
                  <div class="card border-<?=$pyme;?>">
                    <div class="card-header bg-<?=$pyme;?>">
                      <h4 class="text-white">Total Comprado en el Año</h4>
                    </div>
                    <div class="card-body">
                      <?php
                        $sqlComprado = "SELECT p.nombre AS nomProveedor, SUM(dc.cantidad) AS cantProd
                                        FROM compras c
                                        INNER JOIN detcompras dc ON c.id = dc.idCompra
                                        INNER JOIN proveedores p ON c.idProveedor = p.id
                                        WHERE 1=1 AND DATE_FORMAT(c.fechaCompra, '%Y') = DATE_FORMAT(NOW(),'%Y') $filtroProducto $filtroProveedor
                                        GROUP BY p.id
                                        ORDER BY cantProd ASC";
                        $resComprado = mysqli_query($link,$sqlComprado) or die('Problemas al consultar la cantidad comprada a cada Proveedor, notifica a tu Administrador.');
                        $data = '';
                        $colorAleatorio = '';

                        while ($c = mysqli_fetch_array($resComprado)) {
                          $data .= '{
                                    label : "'.$c['nomProveedor'].'",
                                    value : '.$c['cantProd'].'
                                  },';
                        $colorAleatorio .= '\''.$colores[mt_rand(0, count($colores) - 1)].'\',';
                        }
                        $data = trim($data, ",");
                        $colorAleatorio = trim($colorAleatorio, ",");
                      ?>
                      <div id="totalComprado"></div>
                    </div>
                  </div>
                  <!-- ##################################################################################### -->
                  <!-- ################################# card con el mejor precio ########################## -->
                  <!-- ##################################################################################### -->
                  <div class="card border-bottom border-success border-right border-success">
                    <div class="card-body">
                      <h5 class="text-info"><b>Mejor Precio del Año</b></h5>
                      <div class="d-flex flex-row">
                          <?php
                          $sqlPr = "SELECT MIN(dc.costoUnitario) AS minimo, p.nombre AS nomProveedor, SUM(dc.cantidad) AS comprado, dc.tipoUnidad
                                    FROM  compras c
                                    INNER JOIN detcompras dc ON c.id = dc.idCompra
                                    INNER JOIN proveedores p ON c.idProveedor = p.id
                                    WHERE 1=1 $filtroProducto AND DATE_FORMAT(c.fechaCompra,'%Y') = DATE_FORMAT(NOW(),'%Y')";
                          $resPr = mysqli_query($link,$sqlPr) or die('Problemas al consultar el proveedor con mejor precio, notifica a tu Administrador.');
                          $r = mysqli_fetch_array($resPr);
                          $preMin = number_format($r['minimo'],2,'.',',');
                          $nomProveedor = $r['nomProveedor'];
                          $cantComprado = number_format($r['comprado'],2,'.',',').' '.$r['tipoUnidad'];
                           ?>
                          <div class="align-self-center display-6 text-success"><i class="fas fa-dollar-sign"></i></div>
                          <div class="p-10 align-self-center">
                              <h4 class="m-b-0">$<?=$preMin;?></h4>
                              <span><?=$nomProveedor;?></span>
                          </div>
                          <div class="ml-auto align-self-center">
                              <h2 class="font-medium m-b-0"><?=$cantComprado;?></h2>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ============================================================== -->
              <!-- Modals -->
              <!-- ============================================================== -->

              <div id="modalVentas" class="modal bs-example-modal-xl fade show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="myLargeModalLabel">Detalles de Ventas</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                      <div class="table-responsive">
                        <table class="table product-overview">
                          <thead class="bg-<?=$pyme;?> text-white">
                            <tr>
                              <th>Año</th>
                              <th>Ene</th>
                              <th>Feb</th>
                              <th>Mar</th>
                              <th>Abr</th>
                              <th>May</th>
                              <th>Jun</th>
                              <th>Jul</th>
                              <th>Ago</th>
                              <th>Sep</th>
                              <th>Oct</th>
                              <th>Nov</th>
                              <th>Dic</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            mysqli_data_seek($resVentas,0);
                            while ($tVentas = mysqli_fetch_array($resVentas)) {

                              echo '<tr>
                                      <td class="text-center">'.$tVentas['anio'].'</td>
                                      <td class="text-right">'.number_format($tVentas['ene'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['fec'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['mar'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['abr'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['may'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['jun'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['jul'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['ago'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['sep'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['oct'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['nov'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tVentas['dic'],0, '' , ',').'</td>
                                    </tr>';
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

              <div id="modalCompras" class="modal bs-example-modal-xl fade show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="myLargeModalLabel">Detalles de Compras</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                      <div class="table-responsive">
                        <table class="table product-overview">
                          <thead class="bg-<?=$pyme;?> text-white">
                            <tr>
                              <th>Año</th>
                              <th>Ene</th>
                              <th>Feb</th>
                              <th>Mar</th>
                              <th>Abr</th>
                              <th>May</th>
                              <th>Jun</th>
                              <th>Jul</th>
                              <th>Ago</th>
                              <th>Sep</th>
                              <th>Oct</th>
                              <th>Nov</th>
                              <th>Dic</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            mysqli_data_seek($resCompras,0);
                            while ($tCompras = mysqli_fetch_array($resCompras)) {

                              echo '<tr>
                                      <td class="text-center">'.$tCompras['anio'].'</td>
                                      <td class="text-right">'.number_format($tCompras['ene'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['fec'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['mar'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['abr'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['may'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['jun'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['jul'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['ago'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['sep'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['oct'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['nov'],0, '' , ',').'</td>
                                      <td class="text-right">'.number_format($tCompras['dic'],0, '' , ',').'</td>
                                    </tr>';
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

              <?php
                }
               ?>



            <!-- ============================================================== -->
            <!-- End Container fluid  -->
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
    <script src="../assets/libs/raphael/raphael.min.js"></script>
    <script src="../assets/libs/morris.js/morris.min.js"></script>
    <script src="../assets/libs/chart.js/dist/Chart.min.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);
      ?>
      //console.log('Colores: '+<?=$colorAleatorio;?>);
      muestraImg();
    });

    function muestraImg(){
      var imagen = $("#imgProd").val();
      $("#mostrarImg").html('<img src="'+imagen+'" class="img-responsive rounded-circle" width = "400">');
    }

    function selTab(titulo,no){
      if (no == 1) {
        var nomModal = "modalVentas";
      }else {
        var nomModal = "modalCompras";
      }
        $("#lblTitulo").html('<a href="javascript:void(0)" data-toggle="modal" data-target="#'+nomModal+'">'+titulo+' <i class="fas fa-info-circle"></i></a>');
    }

    // Morris donut chart para Total de Compras
    $(function () {
        "use strict";
           Morris.Donut({
               element: 'totalComprado',
               data: [
                  <?=$data;?>
             ],
               resize: true,
               colors:[<?=$colorAleatorio;?>]
           });

           //Line Chart para Ventas

         	new Chart(document.getElementById("line-chart-ventas"), {
         	  type: 'line',
         	  data: {
              /////// Aquí es donde se coloca el texto horizontal
         		labels: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
         		datasets: [
              /////// Aquí es donde se coloca la informacion
              <?=$dataVenta;?>
              /////// Aquí es donde se termina la informacion
         		]
         	  },
         	  options: {
         		title: {
         		  display: true,
         		  text: 'Ventas de <?=$nombreProd;?>'
         		}
         	  }
         	});

          //Line Chart para Compras

        	new Chart(document.getElementById("line-chart-compras"), {
        	  type: 'line',
        	  data: {
              ///// Aquí es donde se coloca el texto horizontal
        		labels: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        		datasets: [
              ///// Aquí comienza el ingreso de la información
              <?=$dataCompra;?>
              //// Aquí termina los datos de la grafica
        		]
        	  },
        	  options: {
        		title: {
        		  display: true,
        		  text: 'Compras de <?=$nombreProd;?>'
        		}
        	  }
        	});

        });

        $(".select2Producto").select2({
          width: '100%',
          height: '30px',
          placeholder: "Selecciona un Producto",
          allowClear: true
        });
        $(".select2.form-control.select2Proveedor").select2({
              multiple:true,
              width: '100%',
              height: '30px',
              placeholder: "Selecciona dos o más",
              allowClear: true
          });
          $('.select2-search__field').css('width', '100%');

          //==================================================//
          // filas ocultas (Mostrar extra / información detallada)   //
          //==================================================//
          /* funcion de formato para filas con detalle - modificar por si se requiere */
          function format(d) {
              // `d` es el objeto que contiene la información de las filas

              return '<table width="100%">' +
              '<tr>' +
                  '<td class="text-center">Compra</td>' +
                  '<td class="text-center">Fecha de Compra</td>' +
                  '<td class="text-center">Unidad</td>' +
                  '<td class="text-center">Cantidad</td>' +
                  '<td class="text-center">Costo Unitario</td>' +
                '</tr>' + d.info +
              '</table>';

          }



          //=============================================//
          // -- filas hijo
          //=============================================//
        var tableChildRows = $('.extra-info').DataTable({
            "ajax": "../funciones/tablaCostoXProveedor.php", //aquí obtiene el archivo con la información
            "columns": [{
                      "data": null,
                      "className": 'details-control',
                      "orderable": false,
                      "defaultContent": ''
                },
                { "data": "cont","className": 'text-center' },
                { "data": "proveedor" },
                { "data": "folio","className": 'text-center' },
                { "data": "fecha","className": 'text-center' },
                { "data": "cant" },
                { "data": "credito" },
                { "data": "meta" },
                { "data": "costoPromedio","className": 'text-center' },
                { "data": "costo","className": 'text-center' }
            ],
            "order": [
                [1, 'desc']
            ]
        },

       );

          //=============================================//
          // se agrega un listener para abrir y cerrar detalles
          //=============================================//
          $('.extra-info tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = tableChildRows.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }

            });

            function listaProv(idProd,num){
              //alert('entra a función con valores: idProd - '+idProd+', num - '+num);
              $.post("../funciones/listaProvXproducto.php",
            {idProd:idProd},
          function(respuesta){
            $("#proveedor"+num).html(respuesta);
          });
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
