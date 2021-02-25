<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');

$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
session_start();

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
  <link rel="icon" type="../image/png" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

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

    <!-- ============================================================== -->


    <!-- ============================================================== -->
    <!-- Comment -->
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

      <div class="container-fluid">
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
        <br>

        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Listado de Productos</h4>
              </div>
              <div class="card-body">
                <div class="text-right">
                  <a class="btn btn-outline-<?= $pyme; ?> btn-rounded" href="configuraProducto.php"> <i class="fas fa-plus"></i> Nuevo Producto</a>
                </div>
                <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <?php

                  if (isset($_POST['fechaInicial'])) {
                    $fechaInicial = $_POST['fechaInicial'];
                  } else {
                    $fechaInicial = strtotime('-1 month', strtotime($fechaAct));
                    $fechaInicial = date('Y-m-d', $fechaInicial);
                  }
                  if (isset($_POST['fechaFinal'])) {
                    $fechaFinal = $_POST['fechaFinal'];
                  } else {
                    $fechaFinal = strtotime('+1 day', strtotime($fechaAct));
                    $fechaFinal = date('Y-m-d', $fechaFinal);
                  }
                  //  $filtroFechas = "vta.fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' ";

                  if (isset($_POST['buscaDepto']) and $_POST['buscaDepto'] >= 1) {
                    $buscaDepto = $_POST['buscaDepto'];
                    $filtroDepto = "dpto.id=" . $_POST['buscaDepto'];
                  } else {
                    $filtroDepto = '';
                    $buscaDepto = '';
                  }

                  if (isset($_POST['buscaMarca']) and $_POST['buscaMarca'] >= 1) {
                    $buscaMarca = $_POST['buscaMarca'];
                    $filtroMarca = "m.id=" . $_POST['buscaMarca'];
                  } else {
                    $filtroMarca = '';
                    $buscaMarca = '';
                  }

                  $sql = "SELECT * FROM departamentos WHERE estatus=1 ORDER BY nombre";
                  $resDepto = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaDepto = '';
                  while ($datos = mysqli_fetch_array($resDepto)) {
                    $activeDepto = ($datos['id'] == $buscaDepto) ? 'selected' : '';
                    $listaDepto .= '<option value="' . $datos['id'] . '" ' . $activeDepto . '>' . $datos['nombre'] . '</option>';
                  }



                  $sql = "SELECT * FROM catmarcas WHERE estatus=1 ORDER BY nombre";
                  $resMarca = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                  $listaMarca = '';
                  while ($datos = mysqli_fetch_array($resMarca)) {
                    $activeMarca = ($datos['id'] == $buscaMarca) ? 'selected' : '';
                    $listaMarca .= '<option value="' . $datos['id'] . '" ' . $activeMarca . '>' . $datos['nombre'] . '</option>';
                  }
                  ?>

                  <div class="row">
                    <form method="post" action="catalogoProductos.php">

                      <div class="col-6">

                      </div>
                      <div class="col-6">

                      </div>
                  </div>


                  <!--/span-->
                  <div class="border p-3 mb-3">
                    <h4><i class="fas fa-filter"></i> Filtrado</h4>

                    <div class="row ">

                      <form method="post" action="catalogoProductos.php">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Búsqueda por marcas</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="fas fa-trademark"></i></span>
                              </div>
                              <select class="select2 form-control custom-select" name="buscaMarca" id="marca" onchange="" style="width: 80%;">

                                <option value=""> Todos las Marcas</option>
                                <?= $listaMarca ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Búsqueda por departamento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="mdi mdi mdi-animation"></i></span>
                              </div>
                              <select class="select2 form-control custom-select" name="buscaDepto" id="buscaDepto" onchange="" style="width: 80%;">

                                <option value=""> Todos los Departamentos</option>
                                <?= $listaDepto ?>
                              </select>
                            </div>
                          </div>
                        </div>


                        <div class="col-md-3 pt-4 mt-2">
                          <input type="submit" id="buscarConexion" class="btn btn-success mt-1" value="Buscar"></input>
                        </div>
                      </form>
                      <!-- /.row (nested) -->
                    </div>
                  </div>






                  <div class="table-responsive">
                    <table class="table product-overview table-striped" id="zero_config2">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Foto</th>
                          <th class="text-center">Nombre</th>
                          <th class="text-center">Marca</th>
                          <th class="text-center">Prioridad</th>
                          <th class="text-center col-lg-1">Descripción</th>
                          <th class="text-center">Medios</th>
                          <th class="text-center">Costos</th>
                          <th class="text-center col-md-1">Precios Registrados</th>
                          <th class="text-center">Ingredientes Activos</th>
                          <th class="text-center">Departamento</th>
                          <th class="text-center">Clave Unidades</th>
                          <th class="text-center">Clave Producto</th>
                          <th class="text-center">Estatus</th>
                          <th class="text-center">Ficha Técnica</th>
                          <th class="text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if ($filtroMarca == '' && $filtroDepto == '') {
                          $stringWhere = '';
                        } else if ($filtroMarca == '') {
                          $stringWhere = 'WHERE ' . $filtroDepto;
                        } else if ($filtroMarca != '' && $filtroDepto != '') {
                          $stringWhere = 'WHERE ' . $filtroMarca . ' AND ' . $filtroDepto;
                        } else if ($filtroDepto == '') {
                          $stringWhere = 'WHERE ' . $filtroMarca;
                        }
                        $sqlProductos = 'SELECT pro.id, pro.descripcion,
                                          IF(pro.descripcionlarga IS NULL, "<b>Sin asignar<b>", pro.descripcionlarga) AS descripcionlargas,
                                          pro.costo,
                                          dpto.nombre AS dpto,
                                          clvuni.nombre AS uni,
                                          clvuni.id AS iduni,
                                        IF
                                          ( pro.medios = 1, "Agranel", "Enteros" ) AS medios,
                                          m.nombre AS marca,
                                        CASE
                                            pro.prioridad
                                            WHEN 1 THEN "Alta"
                                            WHEN 2 THEN "Media"
                                            WHEN 3 THEN "Baja"
                                          END AS prioridadley,
                                          fichaTecnica,
                                          pro.prioridad,
                                          CONCAT( clv.codigo, " - ", clv.descripcion ) AS clvpro,
                                          pro.idTagsIngredienteActivo AS ing,
                                          pro.foto,
                                          pro.estatus,
                                          GROUP_CONCAT(CONCAT(pb.id,"|", pb.precio) ORDER BY  pb.precio DESC SEPARATOR "=") AS preciosbases,
                                          pciomas.preciomayor

                                        FROM productos pro
                                        INNER JOIN departamentos dpto ON pro.idDepartamento = dpto.id
                                        INNER JOIN sat_claveunidad clvuni ON pro.idClavUniProducto = clvuni.id
                                        INNER JOIN sat_claveproducto clv ON pro.idClaveProducto = clv.codigo
																				LEFT JOIN catmarcas m ON pro.idCatMarca = m.id
                                        LEFT JOIN preciosbase pb ON pb.idProducto = pro.id
                                        LEFT JOIN (
                                          SELECT MAX( precio ) AS preciomayor, prod.id AS idProducto
                                          FROM preciosbase pb
                                          INNER JOIN productos prod ON pb.idProducto = prod.id
                                          GROUP BY prod.id
                                          ORDER BY prod.id
                                          ) pciomas ON pciomas.idProducto = pro.id
                                        ' . $stringWhere . '
                                        GROUP BY pro.id
                                        ORDER BY pro.id ASC';
                        //echo $sqlProductos;

                        $resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador');
                        $iteracion = 1;
                        while ($pro = mysqli_fetch_array($resPro)) {
                          $id = $iteracion;
                          $foto = ($pro['foto'] == '') ? '../assets/images/noimg.png' : '../' . $pro['foto'];
                          $estatus = ($pro['estatus'] == 1) ? '<center class="text-success"  data-toggle="tooltip" data-placement="top"
                         title="" data-original-title="Activo"><i class="fas fa-check text-success"></i></center>' : '<center class="text-success"  data-toggle="tooltip" data-placement="top"
                         title="" data-original-title="Desactivado"><i class="fas fa-times text-danger"></i></center>';
                          //$boton = ($pro['estatus'] == 1) ? '<a id="btnCambiaEstatus-'.$id.'"><button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatus('.$id.','.$dpt['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' : '<a id="btnCambiaEstatus-'.$id.'"><button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatus('.$id.','.$dpt['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' ;
                          // $estatusText = ($pro['estatus'] == 1) ? '' : 'class="text-danger"' ;
                          $color = "";
                          $color = ($pro['prioridad'] == 1) ? 'text-success' : $color;
                          $color = ($pro['prioridad'] == 2) ? 'text-warning' : $color;
                          $color = ($pro['prioridad'] == 3) ? 'text-default' : $color;
                          $idProducto = $pro['id'];
                          $updatePrice = '';
                          $updateP = '';
                          $botonFicha = $pro['fichaTecnica'] == '' ? '<i class="fas fa-times"></i>' : ' <button class="btn btn-circle" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="verFicha(\'Ficha Técnica\',\'' . $pro['fichaTecnica'] . '\',' . $idProducto . ')" ><i class="fas fa-file-pdf text-danger"></i></button>';


                          $priceBases = explode("=", $pro['preciosbases']);
                          foreach ($priceBases as $val) {
                            $pBase = explode("|", $val);
                            $pBaseIdent = $pBase[0];
                            $pBaseMonto = $pBase[1];

                            $updatePrice .= '
                              <div class="input-group mb-1">
                                <input type="text" class="form-control form-control-sm" id="priceAnt' . $pBaseIdent . '" value="' . $pBaseMonto . '" placeholder="$ ' . $pBaseMonto . '" aria-label="" aria-describedby="basic-addon' . $pBaseIdent . '">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-success" onclick="modificaPrecio(' . $pBaseIdent . ')" id="btnChangP' . $pBaseIdent . '" type="button"><i id="icoModP' . $pBaseIdent . '" class="fa fa-check"></i></button>
                                </div>
                              </div>
                              ';
                            if (strlen($pro['descripcionlargas']) > 30) {

                              $corte = substr($pro['descripcionlargas'], 0, strpos($pro['descripcionlargas'], ' ', 30));
                            } else {
                              $corte = $pro['descripcionlargas'];
                            }
                            $updateP .= '
                              <div id="contain' . $pBaseIdent . '"><span ondblclick="preparaForEdition(' . $pBaseIdent . ', \'' . $pBaseMonto . '\')">$ ' . number_format($pBaseMonto, 2, '.', ',') . ' </span></div>';
                          }

                          $popoverDescripcion = '<span data-container="body" class="popoverBtn" data-html="true" title="Presentacion Para Inventario" data-toggle="popover" data-placement="right" data-content="' . $pro['descripcionlargas'] . '">' . $corte . '... </span >';


                          echo '<tr ' . $estatusText . '>
                                  <td class="text-center">' . $pro['id'] . '</td>
                                  <td onclick="verIMG(\'Detalles del Producto: ' . $pro['descripcion'] . '\' , \'' . $foto . '\', '.$idProducto.')"  data-toggle="modal" data-target="#verIMG"><img  class="" height="40" src="' . $foto . '" alt="" /></td>
                                  <td id="nomPro-' . $id . '">' . $pro['descripcion'] . ' <br> ' . $var1 . '</td>
                                  <td id="marcaPro-' . $id . '">' . $pro['marca'] . '</td>
                                  <td id="prioridadPro-' . $id . '"><i class="fas fas fa-circle ' . $color . '"></i> ' . $pro['prioridadley'] . '</td>
                                  <td id="descPro-' . $id . '">' . $popoverDescripcion . '</td>

                                  <td id="mediosPro-' . $id . '">' . $pro['medios'] . '</td>
                                  <td id="costosPro-' . $id . '">$' . $pro['costo'] . '</td>
                                  <td class="col-md-1"> ' . $updateP . '  </td>
                                  <td id="ingPro-' . $id . '">' . $pro['ing'] . '</td>
                                  <td id="dptoPro-' . $id . '">' . $pro['dpto'] . '</td>
                                  <td id="clavePro-' . $id . '">' . $pro['iduni'] . '- ' . $pro['uni'] . '</td>

                                  <td id="clavePro-' . $id . '">' . $pro['clvpro'] . '</td>
                                  <td id="estatusPro-' . $id . '" class="text-center">' . $estatus . '</td>
                                  <td id="fichaPro-' . $id . '" class="text-center">' . $botonFicha . '</td>
                                  <td class="text-center">
                                    <center class="text-success ley_edicion"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar">
                                      <button class="btn btn-circle" style="background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);" onclick="editarPro(' . $idProducto . ')" ><i class="fas fa-pencil-alt"></i></button>
                                    </center>
                                  </td>
                                </tr>';
                          $iteracion++;
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <!--end .table-responsive -->
                </div>
                <!--end .col -->
              </div>
              <!--end .row -->
              <!-- END DATATABLE 1 -->
            </div>
            <!--end .card-body -->
          </div>
          <!--end .card -->
          <!-- END ACTION -->

          <div class="modal fade" id="verIMG" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verIMGContent">

                  <h4 class="modal-title" id="verIMGTitle"> </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                </div>
                <div class="modal-body" id="verIMGBody">
                </div>
                <div class="modal-footer" id="verIMGfooter">
                  <button type="button" class="btn bg-<?= $pyme ?> text-white" data-dismiss="modal">Salir</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

          <div class="modal fade" id="verPDF" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verPDFContent">

                  <h4 class="modal-title" id="verPDFTitle"> </h4>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>

                </div>
                <div class="modal-body" id="verPDFBody">

                </div>
                <div class="modal-footer " id="verPDFfooter">
                  <button type="button" class="btn bg-<?= $pyme ?> text-white" data-dismiss="modal">Salir</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->









        </div>

        <footer class="footer text-center">
          Powered by
          <b class="text-info">RVSETyS</b>.
        </footer>

      </div>

    </div>


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
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#zero_config2').DataTable({
          drawCallback: function() {
            $('.popoverBtn').popover({});
            $('.ley_edicion').tooltip({});

          },
          language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
              "first": "Primero",
              "last": "Ultimo",
              "next": "Siguiente",
              "previous": "Anterior"
            }
          }
        });
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
      }); // Cierre de document ready
      function verIMG(name, link, idProducto) {

        $("#verIMGTitle").html('<b>' + name + '</b>');
        $("#verIMGBody").html('<img class="img-thumbnail responsive" src="' + link + '" width="100%"  type="application/pdf">');
        $("#verIMGfooter").html(" <button type='button' class='btn bg-<?= $pyme ?> text-white' data-dismiss='modal'>Salir</button><button type='button' class='btn bg-danger text-white' onclick='eliminarimg(" + idProducto + ")' data-dismiss='modal'>Eliminar</button>");

      }

      $('table').on('click', function(e) {
        if ($('.popoverBtn').length > 1)
          $('.popoverBtn').popover('hide');
        $(e.target).popover('toggle');

      });
      $('table').on('hover', function(e) {
        if ($('.ley_edicion').length > 1)
          $('.ley_edicion').tooltip('hide');
        $(e.target).tooltip('toggle');

      });

      function editarPro(ident) {

        $('<form action="configuraProducto.php" method="POST"><input type="hidden" name="ident" value="' + ident + '"></form>').appendTo('body').submit();

      }

      function mandaModal(id) {
        if (id > 0) {
          var nombre = $("#nomDpt-" + id).html();
          var desc = $("#descDpt-" + id).html();
          $("#eNombreDepto").val(nombre);
          $("#eDescDepto").val(desc);
          $("#idDepto").val(id);
        }
      }


      function limpiaCadena(dat, id) {
        //alert(id);
        dat = getCadenaLimpia(dat);
        $("#" + id).val(dat);
      }

      function verFicha(name, link, idProducto) {

        $("#verPDFTitle").html('<b>' + name + '</b>');
        //  $("#verPDFBody").html('<PDF class="PDF-thumbnail responsive" src="../' + link + '" width="100%"  type="application/pdf">');
        $("#verPDFBody").html('<embed src="../' + link + '" height="500px" width="100%" type="application/pdf">');
        $("#verPDFfooter").append("<button type='button' class='btn bg-<?= $pyme ?> text-white' data-dismiss='modal'>Salir</button><button type='button' class='btn bg-danger text-white' onclick='eliminarficha(" + idProducto + ")' data-dismiss='modal'>Eliminar</button>");
        $("#verPDF").modal("show");
      }


      function estatusPro(id, estatus) {

        // if (id != '' && estatus != '') {
        $.post("../funciones/cambiaEstatusPro.php", {
            ident: id,
            estatus: estatus
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              setTimeout(function() {
                location.reload();
              }, 1000);
            } else {
              notificaBad(resp[0]);
            }
          });

        /* } else {
           notificaBad('No se reconoció el Producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
         }*/
      }


      function eliminarficha(id, estatus) {

        // if (id != '' && estatus != '') {
        $.post("../funciones/borraPDF.php", {
            idProducto: id
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              location.reload();

            } else {
              notificaBad(resp[1]);
            }
          });
        }


        function eliminarimg(id, estatus) {

          // if (id != '' && estatus != '') {
          $.post("../funciones/borraFoto.php", {
              idProducto: id
            },
            function(respuesta) {
              var resp = respuesta.split('|');
              if (resp[0] == 1) {
                location.reload();

              } else {
                notificaBad(resp[1]);
              }
            });

          /* } else {
             notificaBad('No se reconoció el Producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
           }*/
        }

        function listaDeptos() {
          //  var mensaje = 'Mensaje';
          $.post("../funciones/listarDeptos.php", {},
            function(respuesta) {
              $("#validation").html(respuesta);
            });
        }

        function modificaPrecio(ident) {
          $("#btnChangP" + ident).prop("disabled", true);
          $("#btnChangP" + ident).html('<i id="icoModP' + ident + '" class="fas fa-spinner"></i>');
          var cantFin = $("#priceAnt" + ident).val();

          $.post("../funciones/cambiaPrecioBase.php", {
              ident: ident,
              precio: cantFin
            },
            function(respuesta) {
              var resp = respuesta.split('|');
              if (resp[0] == 1) {

                $("#contain" + ident).html('<div id="contain' + ident + '"><span ondblclick="preparaForEdition(' + ident + ', \'' + cantFin + '\')">$ ' + resp[1] + ' </span></div>');


              } else {
                $("#btnChangP" + ident).html('<i id="icoModP' + ident + '" class="fas fa-times"></i>');
                $("#btnChangP" + ident).removeClass("btn-success");
                $("#btnChangP" + ident).addClass("btn-danger");
              }
            });

        }


        function preparaForEdition(ident, monto) {
          //alert(monto + " en el id "+ ident);
          $("#contain" + ident).html('<div class="input-group mb-1"><input type="text" class="form-control form-control-sm" id="priceAnt' + ident + '" value="' + monto + '" placeholder="$ ' + monto + '" aria-label="" aria-describedby="basic-addon' + ident + '"><div class="input-group-append"><button class="btn btn-sm btn-success" onclick="modificaPrecio(' + ident + ')" id="btnChangP' + ident + '" type="button"><i id="icoModP' + ident + '" class="fa fa-check"></i></button></div></div>');
        }
    </script>

</body>

</html>
