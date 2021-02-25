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
                <h4 class="m-b-0 text-white">Stock de productos por Sucursal</h4>
              </div>
              <div class="card-body">

                <div class="text-right">
                  <?php
                  /*$sucursalId = $_SESSION['LZFidSuc'];
                  $sql = "SELECT act.*, CONCAT(us.nombre,' ',us.appat,' ',us.apmat) as usuarioName, suc.nombre as sucursalName,
                        IF(fechaAutoriza>DATE_SUB(NOW(),INTERVAL 2 DAY),1,0) AS permisoVigente
                        FROM activaredicionstock act
                        INNER JOIN segusuarios us ON act.idUserSolicita=us.id
                        INNER JOIN sucursales suc ON act.idSucSolicita=suc.id
                        WHERE act.idSucSolicita='$sucursalId' AND fechaAutoriza=(SELECT MAX(fechaAutoriza) FROM activaredicionstock WHERE idSucSolicita='$sucursalId')
                        ORDER BY act.fechaSolicita DESC LIMIT 1";
                  $res = mysqli_query($link, $sql) or die('Error en la consulta.' . $sql);
                  $dat = mysqli_fetch_array($res);
                  $cantRes = mysqli_num_rows($res);
                  // echo '---'.$dat['estatus'].'---'.$dat['permisoVigente'].'---'.$cantRes;
                  if ($dat['estatus'] == 2 && $dat['permisoVigente'] == 1) {
                    echo '<a class="btn btn-circle bg-' . $pyme . '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar Stock" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="edicionStock.php">
                          <i class="fas fa-pencil-alt"></i></a>';
                  } else if ($dat['estatus'] == 1) {
                    echo '<button class="btn btn-circle bg-' . $pyme . '" data-target="#AccesoPendiente" data-toggle="modal"
                    style="box-shadow:7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff"> <i class="fas fa-pencil-alt"></i></button>
                    ';
                  } else {
                    echo '<button class="btn btn-circle bg-' . $pyme . '" data-target="#modalSolicitud" data-toggle="modal"
                          style="box-shadow:7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff"> <i class="fas fa-pencil-alt"></i></button>
                          ';
                  }
                 */
                  /* echo '<a class="btn btn-circle bg-'.$pyme.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar Stock" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="edicionStock.php">
                         <i class="fas fa-pencil-alt"></i></a>';*/
                  ?>
                  <!--   <a class="btn btn-circle bg-<?= $pyme; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar Stock" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="edicionStock.php"> <i class="fas fa-pencil-alt"></i></a>-->
                  <a class="btn btn-circle bg-<?= $pyme; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Imprimir Listado" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="imprimeStock.php"> <i class="fas fas fa-print"></i></a>
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
                    <form method="post" action="stock.php">

                      <div class="col-6">

                      </div>
                      <div class="col-6">

                      </div>
                  </div>


                  <!--/span-->
                  <div class="border p-3 mb-3">

                    <h4><i class="fas fa-filter"></i> Filtrado</h4>

                    <div class="row ">
                      <form method="post" action="stock.php">

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
                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-animation"></i></span>
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
                    <table class="table product-overview" id="table-stock">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Departamento</th>
                          <th class="text-center">Anaquel</th>
                          <th class="text-center">Producto</th>
                          <th class="text-center">Marca</th>
                          <th class="text-center">Costos</th>
                          <th class="text-center">Precio </th>
                          <th class="text-center">Cantidad Mínima</th>
                          <th class="text-center">Cantidad Máxima</th>
                          <th class="text-center">Cantidad Actual</th>
                          <th class="text-center">Fecha de Edición</th>
                        </tr>
                      </thead>
                      <tbody>

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
                <div class="modal-footer">
                  <button type="button" class="btn btn-<?= $pyme ?>" data-dismiss="modal">Salir</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

          <div class="modal fade" id="AccesoPendiente" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="">

                  <h4 class="modal-title" id="verIMGTitle"> </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                </div>
                <div class="modal-body" id="">
                  <div class="alert alert-warning" role="alert">
                    La solicitud está en proceso de aprobación.
                  </div>


                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-<?= $pyme ?>" data-dismiss="modal">Salir</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

          <div id="modalSolicitud" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                  <h4 class="modal-title" id="lblEditMetodo">Edición de Stock de Sucursal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>


                </div>
                <div class="modal-body">
                  <form role="form" method="post" id="formSolicStock">
                    <?php
                    $sqlSolicitante = 'SELECT CONCAT(usr.nombre," ",usr.appat," ",usr.apmat) AS usuario, DATE_FORMAT(NOW(),"%d-%m-%Y  %h:%i %p") AS fecha, suc.nombre AS sucursal FROM segusuarios usr
                                    INNER JOIN sucursales suc ON usr.idSucursal=suc.id WHERE usr.id= ' . $_SESSION['LZFident'];
                    $resSol = mysqli_query($link, $sqlSolicitante) or die('Problemas al listar los Usuarios, notifica a tu Administrador');
                    $sol = mysqli_fetch_array($resSol);
                    $solicitante = $sol['usuario'];
                    $fecha = $sol['fecha'];
                    $sucursal = $sol['sucursal'];
                    ?>
                    <h5>Se va a generar la solicitud para la modificación del Stock de esta sucursal, con los siguientes datos:</h5>
                    <hr>
                    <p> Solicitante: <b><?= $solicitante ?></b></p>
                    <p> Sucursal: <b><?= $sucursal ?></b></p>
                    <p> Fecha de Solicitud: <b><?= $fecha ?></b></p>
                    <label for="rmotivo" class="control-label col-form-label">Motivo de la Solicitud</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="rNombre1"><i class="mdi mdi-table-edit"></i></span>
                      </div>
                      <textarea class="form-control" id="rmotivo" aria-describedby="motivo" name="rmotivo" oninput="limpiaCadena(this.value,'rmotivo');" required></textarea>
                    </div>
                    <hr>
                    <div class="modal-footer">
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
                        <input type="hidden" id="idMarca">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">Solicitar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
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
    <script src="../assets/tablasZafra/datatable_verLotes.js"></script>

    <script>
      <?php
      if ($filtroMarca == '' && $filtroDepto == '') {
        $stringWhere = '';
      } else if ($filtroMarca == '') {
        $stringWhere = 'AND ' . $filtroDepto;
      } else if ($filtroMarca != '' && $filtroDepto != '') {
        $stringWhere = 'AND ' . $filtroMarca . ' AND ' . $filtroDepto;
      } else if ($filtroDepto == '') {
        $stringWhere = 'AND ' . $filtroMarca;
      }
      $sqlProductos = 'SELECT pro.*, dpto.nombre AS dpto, m.nombre AS marca, stk.cantMinima, stk.cantMaxima, stk.cantActual, stk.fechaReg AS fechaEdi, stk.anaquel, pciomas.preciomayor,
      	GROUP_CONCAT( DISTINCT pb.precio ORDER BY pb.precio DESC SEPARATOR "<br>$" ) AS preciosbases,
        IF(dpto.id="12" OR dpto.id="13", pro.costo, "----") AS pcosto,
        IF (pro.presentacion IS NULL, "Presentación No Especificada",CONCAT("<b>", pro.presentacion,"</b><br><b>Cantidad Embalaje: ",pro.cantEmbalaje,
        " ", pro.unidadEmbalaje)) AS presentacion
       FROM productos pro
       LEFT JOIN stocks stk ON stk.idProducto=pro.id
       LEFT JOIN catmarcas m ON pro.idCatMarca=m.id
       LEFT JOIN departamentos dpto ON pro.idDepartamento=dpto.id
      
       LEFT JOIN preciosbase pb ON pb.idProducto = pro.id
	LEFT JOIN (
	SELECT
		MAX( precio ) AS preciomayor,
		prod.id AS idProducto 
	FROM
		preciosbase pb
		INNER JOIN productos prod ON pb.idProducto = prod.id 
	GROUP BY
		prod.id 
	ORDER BY
		prod.id 
	) pciomas ON pciomas.idProducto = pro.id 
       WHERE stk.idSucursal=' . $_SESSION['LZFidSuc'] . " " . $stringWhere . ' GROUP BY
pro.id
ORDER BY
pro.id ASC';
      $resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador' . mysqli_error($link));
      $arreglo['data'] = array();

      while ($data = mysqli_fetch_array($resPro)) {
        $arreglo['data'][] = $data;
      }
      $var = json_encode($arreglo);
      mysqli_free_result($resPro);

      echo 'var datsJson = ' . $var . ';';
      echo 'var pyme = "' . $pyme . '";';

      ?>
      $(document).ready(function() {
        <?php
        #include('../funciones/basicFuctions.php');
        #alertMsj($nameLk);

        if (isset($_SESSION['LZmsjInfoStock'])) {
          echo "notificaBad('" . $_SESSION['LZmsjInfoStock'] . "');";
          unset($_SESSION['LZmsjInfoStock']);
        }
        if (isset($_SESSION['LZmsjSuccessStock'])) {
          echo "notificaSuc('" . $_SESSION['LZmsjSuccessStock'] . "');";
          unset($_SESSION['LZmsjSuccessStock']);
        }
        ?>
      }); // Cierre de document ready
      function verIMG(name, link) {

        $("#verIMGTitle").html('<b>' + name + '</b>');
        $("#verIMGBody").html('<img class="img-thumbnail responsive" src="../' + link + '" width="100%"  type="application/pdf">');
      }


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

      $("#formSolicStock").submit(function(event) {
        event.preventDefault();
        $.post("../funciones/solicitudstock.php",
          $("#formSolicStock").serialize(),
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              bloqueoBtn("bloquear-btn1", 1);
              notificaSuc(resp[1]);
              setTimeout(function() {
                location.reload();
              }, 1000);
            } else {
              bloqueoBtn("bloquear-btn1", 2);
              notificaBad(resp[1]);
            }
          })
      });


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
              notificaBad(resp[1]);
            }
          });
        /* } else {
           notificaBad('No se reconoció el Producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
         }*/
      }

      function ejecutandoCarga(identif) {
        var selector = 'DIV' + identif;
        var finicio = $('#fStart').val();
        var ffin = $('#fEnd').val();

        $.post("../funciones/cargaContenidoLote.php", {
            ident: identif
          },
          function(respuesta) {
            //alert(respuesta);

            $("#" + selector).html(respuesta);
          });

      }
    </script>

</body>

</html>