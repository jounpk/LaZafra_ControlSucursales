<?php

require_once 'seg.php';
$info = new Seguridad();
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
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
  <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
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
        <?= $info->customizer('2'); ?>

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

        <div class="row" <?= $style1; ?>>
          <div class="col-lg-12">

            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">

                <?php
                $ident = (isset($_POST['ident'])) ? $_POST['ident'] : '';
                //  PRINT_R($ident);
                //echo $ident;
                if ($ident != '') {
                  require('../include/connect.php');
                  $sql = 'SELECT pro.codBarra, pro.descripcion, pro.costo, pro.descripcionlarga, dpto.id AS dpto,  pro.medios, m.id AS marca, pro.seguimiento AS seg, clvuni.id AS unid,pro.cantEmbalaje, pro.unidadEmbalaje, pro.presentacion,
                  clv.codigo AS clvpro, pro.idTagsIngredienteActivo AS ing, pro.foto, pro.seguimiento AS seg, pro.fichaTecnica, pro.foto
                  FROM productos pro INNER JOIN catmarcas m ON pro.idCatMarca=m.id INNER JOIN departamentos dpto ON pro.idDepartamento=dpto.id
                  LEFT  JOIN sat_claveunidad clvuni ON clvuni.id=pro.idClavUniProducto
                  LEFT JOIN sat_claveproducto clv ON pro.idClaveProducto=clv.codigo WHERE pro.id=' . $ident . ' GROUP BY pro.id ASC';
                  //echo $sql.'<br>';
                  $resxProd = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador de este Problema.</p>');
                  $datpro = mysqli_fetch_array($resxProd);
                  $cabecera = "Edición del Producto: " . $datpro['descripcion'];
                  $array = explode(",", $datpro['ing']);
                  $bloqueo = "readonly";
                  $rutaFicha = $datpro['fichaTecnica'];
                  $rutaImg = $datpro['foto'];
                  $fichaTecnica = ($rutaFicha != '') ? ' <button type="button" class="btn btn-outline-danger" onclick="verPDF(\'' . $datpro['descripcion'] . '\', \'' . $rutaFicha . '\')"><i class="fas fa-file-pdf"></i> Ver PDF de la Ficha Técnica </button><br>
                  ' : '<span class="text-danger">No hay Ficha Precargada</span><br>';


                  $imagen = ($rutaImg != '') ? ' <button type="button" class="btn btn-outline-info" onclick="verIMG(\'' . $datpro['descripcion'] . '\', \'' . $rutaImg . '\')"><i class=" fas fa-file-image"></i> Ver Foto </button><br>
                  ' : '<span class="text-danger">No hay Imagen Precargada</span><br>';
                } else {
                  $cabecera = "Registro de Nuevo Producto";
                  $bloqueo = "";
                }

                ?>
                <h4 class="m-b-0 text-white"><?= $cabecera ?></h4>
              </div>

              <br>
              <div class="card-body">

                <a type="button" id="back_to_inbox" href="catalogoProductos.php" class="btn btn-<?= $pyme ?> font-18 m-r-10 mb-2 mt-0 text-right "><i class="text-white mdi mdi-arrow-left"></i></a>


                <?php if ($ident == '') {
                  echo '<form class="form-horizontal" id="formRegPro" role="form" method="post"  enctype="multipart/form-data">';
                } else {
                  echo '<form class="form-horizontal" id="formEdiPro" role="form" method="post"  enctype="multipart/form-data">';
                } ?>
                <div class="row">

                  <div class="col-md-12">

                    <div class="form-body ">
                      <h4 class="card-title text-<?= $pyme ?>">Información General</h4>
                      <hr class="text-<?= $pyme ?>">
                      <br>
                      <div class="row ">

                        <?php
                        $codBarra = $datpro['codBarra'] == 'null' ? "" : $datpro['codBarra'];
                        ?>
                      </div>
                      <div class="row">
                        <input type="hidden" name="ident" value="<?= $ident; ?>">
                        <input type="hidden" name="fichaTecnica" value="<?= $rutaFicha; ?>">
                        <input type="hidden" name="rutaImg" value="<?= $rutaImg; ?>">

                        <div class="col-md-4">
                          <label for="rcodigo" class="control-label col-form-label px-3">Código de Barras</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend ">
                              <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" class="form-control " name="codbarra" id="rcodigo" value="<?= $codBarra ?>" aria-describedby="basic-addon1">
                          </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                          <label for="rdescripcion" class="control-label col-form-label px-3">Nombre del producto</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon2"><i class="fas fa-box-open"></i></span>
                            </div>
                            <input type="text" class="form-control" name="nombre" id="rnombre" value="<?= $datpro['descripcion'] ?>" placeholder="" aria-describedby="basic-addon2" required>
                          </div>


                        </div>
                        <div class="col-md-4">
                          <label for="rformato" class="control-label col-form-label px-3">Formato de Venta</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon4"><i class="mdi mdi-file-document-box"></i></span>
                            </div>
                            <select class="form-control" name="medios" id="rformato" style="width: 90%;" onchange="">
                              <?php
                              echo $datpro['medios'];
                              if ($datpro['medios'] == '1') {
                                echo
                                '
      <option value="">Formato de Venta</option>
    <option value="1" selected>Agranel</option>
    <option value="2">Enteros</option>';
                              } else if ($datpro['medios'] == '2') {
                                echo
                                '
       <option value="">Formato de Venta</option>
    <option value="1">Agranel</option>
    <option value="2" selected>Enteros</option>';
                              } else {
                                echo
                                '
       <option value="">Formato de Venta</option>
    <option value="1">Agranel</option>
    <option value="2">Enteros</option>';
                              }
                              ?>

                            </select>
                          </div>
                        </div>

                        <!--/span-->
                      </div>
                      <!--/row-->
                      <div class="row">
                        <div class="col-md-4">
                          <label for="rmarca" class="control-label col-form-label px-3">Asigna Marca</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon4"><i class="fas fa-trademark"></i></span>
                            </div>

                            <?php
                            $sql = "SELECT id, nombre FROM catmarcas WHERE estatus = 1 ORDER BY nombre ASC";
                            $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                            ?>
                            <select class="select2 form-control custom-select" name="marca" id="rmarca" onchange="" style="width: 92%;" required>
                              <option value="">Selecciona la marca del producto</option>

                              <?php
                              while ($dat = mysqli_fetch_array($res)) {
                                if ($datpro['marca'] == $dat['id']) {
                                  echo '<option value="' . $dat['id'] . '" selected>' . $dat['nombre'] . '</option>';
                                } else {
                                  echo '<option value="' . $dat['id'] . '">' . $dat['nombre'] . '</option>';
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                          <label for="prioridad" class="control-label col-form-label px-3">Prioridad de Venta</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon4"><i class="fas fa-cubes"></i></span>
                            </div>

                            <select class="form-control custom-select color-prioridad" name="prioridad" id="prioridad" onchange="" style="width: 92%;">
                              <?php
                              if ($datpro['prioridad'] == '1') {
                                echo
                                '<option value="0" data-style="" data-flag="">Selecciona la Prioridad del Producto</option>
                              <option value="1" data-style="text-success" data-flag="circle" selected> Alta</option>
                              <option value="2" data-style="text-warning" data-flag="circle">Media</option>
                              <option value="3" data-style="text-secondary" data-flag="circle">Baja</option>';
                              } else if ($datpro['prioridad'] == '2') {
                                echo
                                '<option value="0" data-style="" data-flag="">Selecciona la Prioridad del Producto</option>
                            <option value="1" data-style="text-success" data-flag="circle"> Alta</option>
                            <option value="2" data-style="text-warning" data-flag="circle" selected>Media</option>
                            <option value="3" data-style="text-secondary" data-flag="circle">Baja</option>';
                              } else if ($datpro['prioridad'] == '3') {
                                echo
                                '<option value="0" data-style="" data-flag="">Selecciona la Prioridad del Producto</option>
                            <option value="1" data-style="text-success" data-flag="circle"> Alta</option>
                            <option value="2" data-style="text-warning" data-flag="circle">Media</option>
                            <option value="3" data-style="text-secondary" data-flag="circle" selected>Baja</option>';
                              } else {
                                echo
                                '<option value="0" data-style="" data-flag="" selected>Selecciona la Prioridad del Producto</option>
                            <option value="1" data-style="text-success" data-flag="circle"> Alta</option>
                            <option value="2" data-style="text-warning" data-flag="circle">Media</option>
                            <option value="3" data-style="text-secondary" data-flag="circle">Baja</option>';
                              }
                              ?>

                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label for="rfoto" class="control-label col-form-label px-3">Fotografía</label>
                          <?= $imagen ?>
                          <div class="input-group px-3 mb-3 ">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon5"><i class="mdi mdi-file-image"></i></span>
                            </div>
                            <input type="file" name="fotopro" id="rfoto" title="Foto del Producto" accept=".jpeg, .jpg" class="form-control foto">
                          </div>
                        </div>
                        <!--/span-->
                      </div>
                      <!--/row-->
                      <div class="row">
                        <div class="col-md-4 ">
                          <label for="rdepto" class="control-label col-form-label px-3">Asigna Departamento</label>

                          <div class="input-group px-3 mb-3 ">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon3"><i class="mdi mdi-animation"></i></span>
                            </div>
                            <?php
                            $sql = "SELECT id, nombre FROM departamentos WHERE estatus = 1 ORDER BY nombre ASC";
                            $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                            ?>
                            <select class="select2 form-control custom-select" name="departamento" id="rdpto" onchange="" style="width: 92%; height:50px;">
                              <option value="">Selecciona el Departamento del producto</option>

                              <?php
                              while ($dat = mysqli_fetch_array($res)) {
                                if ($datpro['dpto'] == $dat['id']) {
                                  echo '<option value="' . $dat['id'] . '" selected>' . $dat['nombre'] . '</option>';
                                } else {
                                  echo '<option value="' . $dat['id'] . '">' . $dat['nombre'] . '</option>';
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label for="ringact" class="control-label col-form-label px-3">Ingredientes Activos</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon5"><i class="mdi mdi-bowl"></i></span>
                            </div>
                            <?php
                            $sql = "SELECT id, nombre FROM catingact WHERE estatus = 1 ORDER BY nombre ASC";
                            $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                            ?>
                            <select class="select2 form-control" placeholder="" multiple="multiple" title="Ingrediente Activo" id="ringact" name="ingredienteActivo[]" style="width: 92%;">


                              <?php
                              $array = explode(",", $datpro['ing']);
                              while ($dat = mysqli_fetch_array($res)) {
                                $busq = in_array($dat['nombre'], $array);
                                if ($busq) {

                                  echo '<option value="' . $dat['nombre'] . '" selected>' . $dat['nombre'] . '</option>';
                                } else {
                                  echo '<option value="' . $dat['nombre'] . '" >' . $dat['nombre'] . '</option>';
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">

                          <label for="ficha" class="control-label col-form-label px-3">Ficha Técnica/Manual de Procedimiento</label>
                          <?= $fichaTecnica ?>
                          <div class="input-group px-3 mb-3 ">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon5"><i class="fas fa-file-pdf"></i></span>
                            </div>
                            <input type="file" name="ficha" id="ficha" title="Foto del Producto" accept="application/pdf" class="form-control foto">
                          </div>
                        </div>
                        <!---∫  <label for="ringact" class="control-label col-form-label px-3">Seguimiento del Producto</label>

                          <div class="input-group px-3 mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon4"><i class="mdi mdi-tumblr-reblog"></i></span>
                            </div>
                            <select class="form-control" name="seg" title="Seguimiento del Producto" id="seg" style="width: 95%;" onchange="" required>
                              <?php
                              if ($datpro['seg'] == '1') {
                                echo
                                '<option value="" title="Seguimiento del Producto"></option>
      <option value="1" title="Seguimiento del Producto" selected>Si Seguimiento</option>
    <option value="2" title="Seguimiento del Producto">No Seguimiento</option>';
                              } else if ($datpro['seg'] == '2') {
                                echo
                                '<option value="" title="Seguimiento del Producto">Segumiento del Producto</option>
      <option value="1" title="Seguimiento del Producto">Si Seguimiento</option>
    <option value="2" title="Seguimiento del Producto" selected>No Seguimiento</option>';
                              } else {
                                echo
                                '<option value="" title="Seguimiento del Producto">Segumiento del Producto</option>
    <option value="1" title="Seguimiento del Producto">Si Seguimiento</option>
    <option value="2" title="Seguimiento del Producto">No Seguimiento</option>';
                              }
                              ?>

                            </select>
                          </div>
                        </div>-->


                      </div>
                    </div>
                  </div>


                </div>
                <div class="row form-group">
                  <div class="col-md-12">
                    <label for="ringact" class="control-label col-form-label px-3">Descripción del Producto</label>

                    <div class="input-group px-3 mb-3">

                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon4"><i class="mdi mdi-file-document-box"></i></span>
                      </div>
                      <textarea class="form-control" rows="3" name="descripcion" id="descripcion"><?php echo $datpro['descripcionlarga']; ?></textarea>
                    </div>
                  </div>
                </div>
                <br>
                <?php
                $unidadEmbalaje = $datpro['unidadEmbalaje'] == 'null' ? "" : $datpro['unidadEmbalaje'];
                $cantEmbalaje = $datpro['cantEmbalaje'] == 'null' ? "" : $datpro['cantEmbalaje'];
                $presentacion = $datpro['presentacion'] == 'null' ? "" : $datpro['presentacion'];


                ?>

                <div class="row">
                  <div class="col-md-12">

                  </div>
                </div>


                <div class="row">
                  <div class="col-md-12">
                    <h4 class="card-title mt-3 text-<?= $pyme ?>">Embalaje y Presentación</h4>
                    <hr>
                  </div>
                </div>
                <div class="row">

                  <div class="col-md-4">
                    <label for="rcodigo" class="control-label col-form-label px-3">Presentación</label>

                    <div class="input-group px-3 mb-3">
                      <div class="input-group-prepend ">
                        <span class="input-group-text"><i class=" fas fa-clipboard-list"></i></span>
                      </div>
                      <input type="text" class="form-control " name="presentacion" id="presentacion" value="<?= $presentacion ?>" aria-describedby="basic-addon1">
                    </div>

                  </div>


                  <div class="col-md-4">
                    <label for="rcodigo" class="control-label col-form-label px-3">Cantidad Embalaje</label>

                    <div class="input-group px-3 mb-3">
                      <div class="input-group-prepend ">
                        <span class="input-group-text"><i class=" fas fa-dolly-flatbed"></i></span>
                      </div>
                      <input type="number" step="0.01" min='0.00' class="form-control " name="cantEmbalaje" id="cantEmbalaje" value="<?= $cantEmbalaje ?>" aria-describedby="basic-addon1">
                    </div>

                  </div>


                  <div class="col-md-4">
                    <label for="rcodigo" class="control-label col-form-label px-3">Unidad Embalaje</label>

                    <div class="input-group px-3 mb-3">
                      <div class="input-group-prepend ">
                        <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                      </div>
                      <input type="text" class="form-control " name="unidadEmbalaje" id="unidadEmbalaje" value="<?= $unidadEmbalaje ?>" aria-describedby="basic-addon1">
                    </div>

                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <h4 class="card-title mt-3 text-<?= $pyme ?>">Costos y precios</h4>
                    <hr>
                    <br>
                    <div class="row">
                      <div class="col-md-12 col-sm-12">
                        <label for="rcsto" class="control-label col-form-label px-3 ">Costo Unitario</label>
                        <div class="input-group px-3 mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon7">$</span>
                          </div>
                          <input type="number" min="0" step="0.01" class="form-control" name="costo" value="<?= $datpro['costo'] ?>" id="costo" placeholder="" onchange="actualizaPorcentajes(this.value)" aria-describedby="basic-addon7" required>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-sm-12  px-4">


                          <div class="border p-3 mx-3 mt-0">
                            <div class="row">

                              <div class="col-md-10">
                                <label class="control-label col-form-label px-3">Registro de precios base</label>
                              </div>
                              <div class="col-md-2">

                              </div>
                            </div>
                            <input type="hidden" name="precios" id="precios" value="">

                            <div class="row px-3" id="areaprecios">

                              <div class="col-md-12 precio">
                                <div class="row">
                                  <div class="col-md-6 col-lg-6">
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon8">$</span>
                                      </div>
                                      <input type="number" min="0" step="0.01" class="form-control " name="precio" id="precio" value="" placeholder="" onchange="" aria-describedby="basic-addon10">
                                    </div>
                                  </div>



                                  <div class="col-md-6 col-lg-6">
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon8"><i class="fas fa-boxes"></i></span>
                                      </div>
                                      <input type="number" class="form-control " step="0.01" min="0" name="cantidadLibera" id="cantidadLibera" value="" placeholder="" onchange="" aria-describedby="basic-addon10">
                                    </div>
                                  </div>


                                  <div class="col-md-6 col-lg-12 mb-2">

                                    <button type="button" onclick="guardaprecio()" class="btn btn-success">Agregar</button>
                                  </div>
                                </div>
                              </div>
                              <table class="table stylish-table">
                                <thead>
                                  <tr>
                                    <th>Precio</th>
                                    <th>Monto</th>
                                    <th>Cantidad Libera</th>
                                    <th>Accion</th>
                                  </tr>
                                </thead>
                                <tbody id="lista_precio">

                                  <?php if ($ident != "") {

                                    $sql = "SELECT pb.id, pb.precio, pb.cantLibera FROM preciosbase pb WHERE pb.idProducto='$ident' ORDER BY pb.precio DESC";
                                    //echo $sql;
                                    $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador de este Problema.</p>' . mysqli_error($link));
                                    $it = 1;
                                    while ($data = mysqli_fetch_array($res)) {
                                      $porcentajeDeGanancia = (($data['precio'] - $datpro['costo']) * 100) / $datpro['costo'];
                                      //((valorPrecio - costo) * 100) / costo;
                                      $porcentajeDeGanancia = round($porcentajeDeGanancia, 2);

                                      echo '
                        <tr class="table-info">
                         <td>' . $it . '</td>
                         <td><div class="input-group mb-1">
                           <input type="text" class="form-control form-control-sm precios" data-id="' . $data['id'] . '" id="price' . $data['id'] . '" value="' . $data['precio'] . '" placeholder="$ ' . $data['precio'] . '" onchange="recalcular(' . $data['id'] . ',this.value,' . $datpro['costo'] . ')" aria-label="" aria-describedby="basic-addon' . $data['id'] . '">
                           </div>
                           <label class="text-danger porcentajes" id="porcentajeGanancia-' . $data['id'] . '">' . $porcentajeDeGanancia . '%</label>    
                           </td>
                         <td>
                          <div class="input-group mb-1">
                            <input type="text" class="form-control form-control-sm"  id="cantLibera' . $data['id'] . '" value="' . $data['cantLibera'] . '" placeholder="$ ' . $data['cantLibera'] . '" aria-label="" aria-describedby="basic-addon' . $data['id'] . '">
                          </div>
                         </td>
                         <td>
                          <button class="btn" onclick="eliminaprecioReg(' . $data['id'] . ')"><i class="fas fa-trash-alt text-danger"></i></button>
                          <button class="btn" onclick="updateCantPrecio(' . $data['id'] . ')"><i class="fas fa-check text-success"></i></button>
                         </td>

                         </tr>
                          ';
                                      $it++;
                                    }
                                  } ?>

                                </tbody>
                              </table>

                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <h4 class="card-title mt-3 text-<?= $pyme ?>">Datos Fiscales</h4>
                    <hr>
                    <br>
                    <!--/row-->
                    <div class="row">
                      <div class="col-md-12">
                        <label for="claveuni" class="control-label col-form-label px-3">Asigna Clave Unidad</label>

                        <div class="input-group px-3 mb-3">
                          <div class="input-group-prepend ">
                            <span class="input-group-text" id="basic-addon4"><i class="mdi mdi-tooltip-edit"></i></span>
                          </div>
                          <?php
                          $sql = "SELECT id, nombre FROM sat_claveunidad WHERE estatus = 1 ORDER BY nombre ASC";
                          $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                          ?>
                          <select class="select2 form-control custom-select" name="claveuni" id="claveuni" onchange="" style="width: 93%;" required>
                            <option value="">Asigna Clave Unidad del Producto</option>

                            <?php
                            while ($dat = mysqli_fetch_array($res)) {
                              if ($datpro['unid'] == $dat['id']) {
                                echo '<option value="' . $dat['id'] . '" selected><b>' . $dat['id'] . '</b>- ' . $dat['nombre'] . '</option>';
                              } else {
                                echo '<option value="' . $dat['id'] . '"<b>' . $dat['id'] . '</b>- ' . $dat['nombre'] . '</option>';
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <!--/span-->
                      <div class="col-md-12">
                        <label for="clavepro" class="control-label col-form-label px-3">Asigna Clave Producto</label>

                        <div class="input-group px-3 mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon4"><i class="mdi mdi-nutrition"></i></span>
                          </div>
                          <?php
                          $sql = "SELECT codigo, descripcion FROM sat_claveproducto WHERE estatus = 1 ORDER BY descripcion ASC";
                          $res = mysqli_query($link, $sql) or die('<p class="text-danger">Notifica al Administrador</p>');
                          ?>
                          <select class="select2 form-control custom-select" name="clavepro" id="clavepro" onchange="" style="width: 93%;" required>

                            <option value="">Asigna Clave Producto</option>

                            <?php
                            while ($dat = mysqli_fetch_array($res)) {
                              if ($datpro['clvpro'] == $dat['codigo']) {
                                echo '<option value="' . $dat['codigo'] . '" selected><b>' . $dat['codigo'] . '</b>- ' . $dat['descripcion'] . '</option>';
                              } else {
                                echo '<option value="' . $dat['codigo'] . '"><b>' . $dat['codigo'] . '</b>- ' . $dat['descripcion'] . '</option>';
                              }
                            }


                            ?>
                          </select>
                        </div>
                      </div>
                    </div>


                  </div>


                </div>


                <!-- ################################################################################################################################################################################################################################## -->


              </div>

              <div class="modal-footer">


                <?php if ($ident == '') {
                  echo '  <div class="checkbox checkbox-styled mx-4">
		                          <label>
		                            <input type="checkbox" id="return" name="return" value="1">
		                            <span>Guardar y registrar nuevo Producto.</span>
		                          </label>
		                        </div>';
                } ?>




                <div id="bloquear-btn1" style="display:none;">
                  <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="sr-only">Loading...</span>
                  </button>
                  <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="sr-only">Loading...</span>
                  </button>
                  <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                  </button>
                </div>
                <div id="desbloquear-btn1">

                  <a href="catalogoProductos.php"><button type="button" class="btn btn-danger">Cancelar</button></a>

                  <?php if ($ident == '') {
                    echo ' 	<button type="submit"  class="btn btn-success">Registrar</button></div>';
                  } else {
                    echo ' 	<button type="submit" class="btn btn-success">Modificar</button></div>';
                  } ?>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
  <div class="modal fade" id="verIMG" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verIMGContent">

          <h4 class="modal-title" id="verIMGTitle"> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        </div>
        <div class="modal-body" id="verIMGBody">
        </div>
        <div class="modal-footer" id="verIMGFooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>

        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->



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
  <!--Menu sidebar -->
  <script src="../dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>

  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>

  <script>
    localStorage.setItem('numinputprecios', "1");
    localStorage.setItem('precios', "[]");
    var arrayprecios = [];
    $(document).ready(function() {
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset($_SESSION['LZmsjInfoAltaProducto'])) {
        echo "notificaBad('" . $_SESSION['LZmsjInfoAltaProducto'] . "');";
        unset($_SESSION['LZmsjInfoAltaProducto']);
      }
      if (isset($_SESSION['LZmsjSuccessProducto'])) {
        echo "notificaSuc('" . $_SESSION['LZmsjSuccessProducto'] . "');";
        unset($_SESSION['LZmsjSuccessProducto']);
      }
      ?>
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);
      ?>






      $("#plus").click(function(e) {
        e.preventDefault();
        var precio = parseInt(localStorage.getItem('numinputprecios'));
        var templateprecio =
          `<div class="col-md-6 precio${precio}">
            <label for="preciob${precio}" class="control-label col-form-label px-3">Precio Base ${precio}</label>
            <div class="input-group px-3 mb-3">
                  <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon8">$</span>
                  </div>
                  <input type="text" class="form-control " name="preciob${precio}" id="${precio}" value="" placeholder="" onchange="guardaprecio(${precio})" aria-describedby="basic-addon10">
                  </div>
                  </div>`;
        $('#areaprecios').append(templateprecio);
        precio++;
        localStorage.setItem('numinputprecios', precio);
      });

      $("#trash").click(function(e) {
        $('#areaprecios').html("");
        localStorage.setItem('numinputprecios', 1);
        arrayprecios = [];
      });
      $('select2').select2({
        language: {

          noResults: function() {

            return "No hay resultado";
          },
          searching: function() {

            return "Buscando..";
          }
        }
      });

    });

    function verPDF(name, link) {

      $("#verIMGTitle").html('<b>' + name + '</b>');
      $("#verIMGBody").html('<embed src="../' + link + '" type="application/pdf" width="100%" height="600"  ></embed>');
      $("#verIMG").modal('show');



    }



    function verIMG(name, link) {

      $("#verIMGTitle").html('<b>' + name + '</b>');
      $("#verIMGBody").html('<img class="img-thumbnail responsive" src="../' + link + '" width="200%"  type="application/pdf">');
      $("#verIMG").modal('show');



    }







    $("#formRegPro").submit(function(event) {
      event.preventDefault();
      var stringPrecios = JSON.stringify(arrayprecios);
      $("#precios").val(stringPrecios);
      var formElement = document.getElementById("formRegPro");
      var formGasto = new FormData(formElement);
      bloqueoBtn("bloquear-btn1", 1);
      $.ajax({
        type: 'POST',
        url: "../funciones/registraNuevoProducto.php",
        data: formGasto,
        processData: false,
        contentType: false,

        success: function(respuesta) {
          var resp = respuesta.split('|');
          // alert(respuesta);
          if (resp[0] == 1) {

            notificaSuc(resp[1]);
            setTimeout(function() {
              if ($("#return").prop('checked')) {
                location.reload();

              } else {
                window.location.replace('catalogoProductos.php');
              }
            }, 1000);
          } else {
            bloqueoBtn("bloquear-btn1", 2);
            notificaBad(resp[1]);
          }
        }
      });
    });
    $(".color-prioridad").select2({
      minimumResultsForSearch: Infinity,
      templateResult: iconFormat,
      templateSelection: iconFormat,
      escapeMarkup: function(es) {
        return es;
      }
    });

    function guardaprecio() {
      if ($("#precio").val() < 0) {
        $("#precio").focus();
        $("#cantidadLibera").val("");
      } else if ($("#cantidadLibera").val() < 0) {
        $("#cantidadLibera").focus();
        $("#precio").val("");
      } else {
        var valorPrecio = $("#precio").val();
        var cantidadLibera = $("#cantidadLibera").val();
        var posicion = arrayprecios.length;
        var arrayParejita = [];
        if (valorPrecio != '') {


          arrayParejita.push(valorPrecio);
          arrayParejita.push(cantidadLibera);


          arrayprecios.push(arrayParejita);
          //arrayprecios.push(cantidadLibera);
          //  console.log(arrayprecios);
          <?php if ($ident == '') {
            echo 'var noPrecio = arrayprecios.length;';
          } else  if ($ident != '') {
            echo 'var noPrecio =' . $it . ';';
          }
          ?>

          //-----------------Creacion de PopOver de Porcentajes------------------------//

          var costo = parseFloat($('#costo').val());

          var porcent = ((valorPrecio - costo) * 100) / costo;

          var porcent = porcent.toFixed(3) + ' %';
          //alert('El costo es '+costo);                         $("#verIMGBody").html('<embed src="' + respuesta + '" type="application/pdf" width="100%" height="600"  ></embed>');

          //-------------------------------------------------------------------------------//
          var precio = ` <tr id="fila_${posicion}">
      <td> ${noPrecio}</td>

      <td>$${valorPrecio}<br><div class='text-danger'>${porcent} </div></td>
      <td>${cantidadLibera} </td>
      <td><button class="btn" onclick="eliminaprecio(${posicion})"><i class="fas fa-trash-alt text-danger"></i></button></td>
      </tr>`;

          $("#lista_precio").append(precio);
          $("#precio").val("");
          $("#cantidadLibera").val("");


        }
      }

    }

    function eliminaprecio(posicion) {
      arrayprecios.splice(posicion, 1);
      // console.log(arrayprecios);
      $("#fila_" + posicion).remove();
    }

    function updateCantPrecio(ident) {
      event.preventDefault();
      var monto = $("#price" + ident).val();
      var cantLibera = $("#cantLibera" + ident).val();

      $.post("../funciones/actualizaPrecioMontoBase.php", {
          ident: ident,
          monto: monto,
          cantLibera: cantLibera
        },
        function(respuesta) {
          var resp = respuesta.split('|');
          if (resp[0] == 1) {
            notificaSuc(resp[1]);
          } else {
            notificaBad(resp[1]);
          }
        });
    }

    function eliminaprecioReg(ident) {
      event.preventDefault();
      $.post("../funciones/eliminaPrecioBase.php", {
          ident: ident
        },
        function(respuesta) {
          var resp = respuesta.split('|');
          if (resp[0] == 1) {
            bloqueoBtn("bloquear-btn2", 1);

            notificaSuc(resp[1]);
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            bloqueoBtn("bloquear-btn2", 2);

            notificaBad(resp[1]);
          }
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

    $("#formEdiPro").submit(function(event) {
      event.preventDefault();
      var stringPrecios = JSON.stringify(arrayprecios);
      $("#precios").val(stringPrecios);
      var formElement = document.getElementById("formEdiPro");
      var formGasto = new FormData(formElement);
      bloqueoBtn("bloquear-btn1", 1);
      $.ajax({
        type: 'POST',
        url: "../funciones/editaProducto.php",
        data: formGasto,
        processData: false,
        contentType: false,

        success: function(respuesta) {
          var resp = respuesta.split('|');
          if (resp[0] == 1) {

            notificaSuc(resp[1]);
            setTimeout(function() {

              window.location.replace('catalogoProductos.php');

            }, 1000);
          } else {
            bloqueoBtn("bloquear-btn1", 2);
            notificaBad(resp[1]);
          }
        }
      });
    });


    function recalcular(id, precio, costo) {

      var porcent = ((precio - costo) * 100) / costo;
      var porcent = porcent.toFixed(3) + ' %';
      $('#porcentajeGanancia-' + id).html(porcent);



    }

    function actualizaPorcentajes(costo) {
      debug = 1;
      clase = ".precios";
      $(clase).each(function() {
        var id = $(this).data("id");

        if (debug == 1) {
          console.log("Valor de Precio" + $(this).val());
          console.log("ID: " +id);

        }
        var porcent = (($(this).val() - costo) * 100) / costo;
        var porcent = porcent.toFixed(3) + ' %';
        $('#porcentajeGanancia-' + id).html(porcent);
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