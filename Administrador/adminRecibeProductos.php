<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
$debug = 0;
$userReg = $_SESSION['LZFident'];
$sucursal = $_SESSION['LZFidSuc'];
$idCompra = '';
if (isset($_POST['idCompra'])) {
  $idCompra = $_POST['idCompra'];
}
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
  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
  <style>
    .btn-circle-small {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 6px 0;
      font-size: 12px;
      line-height: 1.428571429;
      border-radius: 15px;
    }

    .alinearCentro {
      display: inline-block;
      text-align: center;
      vertical-align: middle;
      line-height: 150%;
      padding-top: 15%;
    }

    .muestraSombra {
      box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
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
        <?php
        //----------------devBug------------------------------
        if ($debug == 1) {
          print_r($_POST);
          echo '<br><br>';
        } else {
          error_reporting(0);
        }   //----------------devBug------------------------------
        ?>
        <div class="row">
          <div class="col-md-4">


            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="text-white">Descripción de la compra</h4>
              </div>
              <div class="card-body">
                <form method="post" action="adminRecibeProductos.php">
                  <div class="row">
                    <div class="col-md-9">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">O.C</span>
                        </div>
                        <input type="number" id="idCompra" name="idCompra" min="0" value="<?= $idCompra ?>" placeholder="Ingresa el no. de compra" class="form-control" aria-label="idCompra" aria-describedby="basic-addon1">
                      </div>
                    </div>
                    <div class="col-md-2 text-center">
                      <button type="submit" class="btn btn-<?= $pyme; ?>">Buscar</button>
                    </div>
                  </div>
                </form>
                <?php
                $sql = "SELECT c.*, suc.nombre AS nameSuc, DATE_FORMAT(c.fechaCompra, '%d-%m-%Y') AS fecha,
                        CONCAT(usr.nombre,' ',usr.appat, ' ', usr.apmat) AS user, c.nota,
                        prov.nombre AS proveedor FROM compras c
                        INNER JOIN sucursales suc ON c.idSucursal=suc.id
                        INNER JOIN segusuarios usr ON c.idUserReg=usr.id
                        INNER JOIN proveedores prov ON c.idProveedor= prov.id WHERE c.id='$idCompra'";
                //----------------devBug------------------------------
                if ($debug == 1) {
                  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Compra, notifica a tu Administrador', mysqli_error($link)));
                  $canInsert = mysqli_affected_rows($link);
                  echo '<br>SQL: ' . $sql . '<br>';
                  echo '<br>Cant de Registros Cargados: ' . $canInsert;
                } else {
                  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Compra, notifica a tu Administrador', mysqli_error($link)));
                  $canInsert = mysqli_affected_rows($link);
                } //-------------Finaliza devBug------------------------------
                if (mysqli_num_rows($resultXquery) > 0) {
                  $display = "";
                } else {
                  $display = "display: none";
                }
                $ArrayDatosCompra = mysqli_fetch_array($resultXquery);
                $nameSuc = $ArrayDatosCompra['nameSuc'];
                $fechaCompra = $ArrayDatosCompra['fecha'];
                $Proveedor = $ArrayDatosCompra['proveedor'];
                $user = $ArrayDatosCompra['user'];
                $nota = $ArrayDatosCompra['nota'];
                ?>

                <div class="row" style="<?= $display ?>">

                  <div class="col-md-5">
                    <label class="control-label"><br>Ord. de Comp.:</b>&nbsp;&nbsp;</label><label class="control-label text-info">#<b><?= $idCompra; ?></b></label>
                    <label class="control-label"><br>Fecha de Compra:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?= $fechaCompra; ?></b></label>
                  </div>
                  <div class="col-md-7">
                    <label class="control-label"><br>Sucursal:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?= $nameSuc; ?></b></label>
                    <br>
                    <label class="control-label"><br>Comprador:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?= $user; ?></b></label>
                  </div>

                  <div class="col-md-12">
                    <label class="control-label"><br>Proveedor:</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?= $Proveedor; ?></b></label>
                  </div>
                  <div class="col-md-12">
                    <label class="control-label"><br>Nota de Compra</b>&nbsp;&nbsp;</label><label class="control-label text-info"><b><?= $nota; ?></b></label>
                  </div>
                </div>
                <div class="modal-footer">
                  <form method="post" role="form" action="../funciones/cierraRecibeProductos.php">
                    <input type="hidden" name="idCompra" value="<?= $idCompra; ?>">
                    <input type="hidden" name="tipo" value="3">
                    <input type="hidden" name="vista" value="2">
                  </form>

                </div>
              </div>
            </div>
          </div>

          <div class="col-md-8">


            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="text-white">Listado de Productos</h4>
              </div>
              <div class="card-body">

                <div class="table-responsive">
                  <table class="table product-overview table-striped" id="table-recepcion">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th>Producto</th>
                        <th class="text-center">Cantidad Comprada</th>
                        <th class="text-center">Cantidad restante</th>
                        <th class="text-center">Cantidad a Recibir</th>
                        <th class="text-center">Asig.</th>
                      </tr>
                    </thead>
                    <tbody id="tablaBody">
                      <?php

                      /*$sql = "SELECT
                      dc.*,
                      dc.id AS idDetCompra,
                      dc.cantidad AS cantComprada,
                      IF (dr.cantidad IS NULL,
                        FORMAT( dc.cantidad, 2 ),
                        FORMAT( dc.cantidad - dr.cantidad, 2 )) AS cantidRec,
                      IF(dc.idProducto IS NULL, nombreProducto, pr.descripcion) AS producto
                      FROM
                        compras c
                        INNER JOIN detcompras dc ON c.id = dc.idCompra
                        INNER JOIN productos pr ON dc.idCompra =pr.id
                        LEFT JOIN recepciones r ON c.id = r.idCompra
                        LEFT JOIN detrecepciones dr ON r.id = dr.idRecepcion
                      WHERE c.id='$idCompra'";
                      //----------------devBug------------------------------
                      if ($debug == 1) {
                        $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Compra, notifica a tu Administrador', mysqli_error($link)));
                        $canInsert = mysqli_affected_rows($link);
                        echo '<br>SQL: ' . $sql . '<br>';
                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                      } else {
                        $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Compra, notifica a tu Administrador', mysqli_error($link)));
                        $canInsert = mysqli_affected_rows($link);
                      } //-------------Finaliza devBug------------------------------
                      $cont = 1;
                      $cantTotalResta = 0;
                      while ($data = mysqli_fetch_array($resultXquery)) {
                        echo '<tr>
                                      <td class="text-center">' . $cont++ . '</td>
                                      <td>' . $data['producto'] . '</td>
                                      <td class="text-right">' . number_format($data['cantComprada'], 2, '.', ',') . '</td>
                                      <td class="text-right">' . $data['cantidRec'] . '</td>
                                      <td class="text-right"><input name="CantARecibir-' . $data['idDetCompra'] . '" id="CantARecibir" type="number" value=""></input></td>
                                 
                                    </tr>';
                      }

                      if ($cantTotalResta == 0) {
                        echo '<input type="hidden" id="cantTotalResta" value="' . $cantTotalResta . '">';
                      }*/

                      ?>
                    </tbody>
                  </table>
                </div>

                </hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="rangeBa2" class="control-label col-form-label">Personal que entrega producto</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="finVig1"><i class="fas fa-user"></i></span>
                          <input class="form-control" type="text" value="" size="60" id="rangeBa2" name="personal_entrega" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8">
                  </div>
                  <div class="col-md-4">
                    <button type="submit" class="btn btn-danger">Cancelar</button>

                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#modalCierraRegistro">Cerrar Recepción</button>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalAsignacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
              <h4 class="modal-title" id="myModalLabel">
                <div id="tituloModal">Detalle de Asignacion Por Lote</div>
              </h4>

              <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" id="contenidoModal">
              <div id="preloadProducto">
                <!-- <center><img src="images/preloader.GIF"></center>-->
              </div>
            </div>
            <div class="modal-footer">
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->





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
  <script src="../assets/libs/jquery-validation/dist/jquery.validate.min-ESP.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <script src="../assets/tablasZafra/datatable_configRec.js"></script>


  <script>
    $(document).ready(function() {
      <?php
      #  include('../funciones/basicFuctions.php');
      #  alertMsj($nameLk);

      if (isset($_SESSION['LZFmsjRecibeProductoAdmin'])) {
        echo "notificaBad('" . $_SESSION['LZFmsjRecibeProductoAdmin'] . "');";
        unset($_SESSION['LZFmsjRecibeProductoAdmin']);
      }
      if (isset($_SESSION['LZFmsjSuccessRecibeProductoAdmin'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessRecibeProductoAdmin'] . "');";
        unset($_SESSION['LZFmsjSuccessRecibeProductoAdmin']);
      }


      ?>

      $("#formRegistraRecepcionDeProd").submit(function() {
        var idPd = $("#idProducto").val();
        var cantRestante = $("#cantProd-" + idPd).val();
        var suma = 0;
        $('.cantProductos').each(function() {
          suma += parseFloat($(this).val());
        });
        //alert('Suma: '+suma+', cantRestante: '+cantRestante);
        if (suma <= cantRestante) {
          //notificaSuc('Excelente, datos enviados');
          return true;
        } else {
          notificaBad('La cantidad de productos que ingresaste excede la cantidad por recibir (' + cantRestante + '), verifica los datos');
          return false;
        }
      });

      reenviaVacio();

    });


    function ejecutandoCarga(identif) {
      var selector = 'DIV' + identif;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("../funciones/cargaAsignacionLotes.php", {
          ident: identif
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });

    }


    function agregaRecepcion(idDetCompra, idProd) {
      var cantProd = $("#cantProd-" + idProd).val();
      console.log('cantProd: ' + cantProd);
      $.post("../funciones/listaRecepcionDeLotes.php", {
          idProd: idProd
        },
        function(respuesta) {
          $("#listadoLotes").html(respuesta);
          $("#idProducto").val(idProd);
          $("#idDetCompra").val(idDetCompra);
          $("#idProducto2").val(idProd);
          $("#idDetCompra2").val(idDetCompra);
          $("#cantResta").val(cantProd);
          $("#cantResta2").val(cantProd);
        });
    }

    function agregarCampos() {
      var idProducto = $("#idProducto").val();
      $.post("../funciones/listaRecepcionDeLotes.php", {
          idProd: idProducto
        },
        function(respuesta) {
          $("#listadoLotes").append(respuesta);
        });
    }

    function agregaNuevoLote() {
      var idProd = $("#idProducto").val();
      var idDetCompra = $("#idDetCompra").val();
      $("#modalRecibeProducto").modal('hide');
      $("#modalNuevoLote").modal('show');
      $("#idProducto2").val(idProd);
      $("#idDetCompra2").val(idDetCompra);
    }

    function regresaModalRecepcion() {
      var idProd = $("#idProducto2").val();
      var idDetCompra = $("#idDetCompra2").val();
      agregaRecepcion(idDetCompra, idProd);
      $("#modalNuevoLote").modal('hide');
      $("#modalRecibeProducto").modal('show');
    }

    function registraLote() {
      $("#btnRegistraLote").prop('disabled', true);
      var idProd = $("#idProducto2").val();
      var idCompra = $("#idCompra").val();
      var idRecepcion = $("#idRecepcion").val();
      var nomLote = $("#nomLote").val();
      var caducidad = $("#caducidad").val();
      var cantidad = parseInt($("#cantidad").val());
      var idDetCompra = $("#idDetCompra2").val();
      var cantResta = parseInt($("#cantResta").val());
      var cantTotal = 0;
      cantTotal = cantResta - cantidad;
      if (cantTotal >= 0) {
        $.post("../funciones/registraNuevoLote.php", {
            idCompra: idCompra,
            nomLote: nomLote,
            caducidad: caducidad,
            cantidad: cantidad,
            idProd: idProd,
            idRecepcion: idRecepcion,
            idDetCompra: idDetCompra,
            cantResta: cantResta
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              $("#idRecepcion").val(resp[2]);
              $("#btnRegistraLote").prop('disabled', false);
              $("#nomLote").val('');
              $("#caducidad").val('');
              $("#cantidad").val('');
              listaTablaRecepciones(idCompra);
              $("#modalRecibeProducto").modal('hide');
              agregaRecepcion(idDetCompra, idProd);
            } else {
              notificaBad(resp[1]);
              $("#idRecepcion").val(idRecepcion);
              $("#btnRegistraLote").prop('disabled', false);
            }
          });
      } else {
        console.log('cantidad: ' + cantidad + ' <= cantResta: ' + cantResta + ', cantTotal: ' + cantTotal);
        notificaBad('No se permite recibir más producto del debido, la cantidad máxima a recibir en esta compra es: ' + cantResta);
        $("#btnRegistraLote").prop('disabled', false);
      }
    }

    function listaTablaRecepciones(idCompra) {
      $.post("../funciones/listaTablaRecepciones.php", {
          idCompra: idCompra
        },
        function(respuesta) {
          $("#tablaBody").html(respuesta);
        });
    }

    function reenviaVacio() {
      var cantResta = parseInt($("#cantTotalResta").val());
      console.log('No hay productos por recibir. cantResta: ' + cantResta);
      if (cantResta == 0) {
        //console.log('Ejecuta Form. cantResta: '+cantResta);
        notificaBad('La compra ya fue recibida en su totalidad.');
        setTimeout(function() {
          reenviar();
        }, 3000);
      }
    }

    <?php
    $sql = "SELECT
   dc.*,
   dc.id AS idDetCompra,
   dc.cantidad AS cantComprada,
   IF (dr.cantidad IS NULL,
     FORMAT( dc.cantidad, 2 ),
     FORMAT( dc.cantidad - dr.cantidad, 2 )) AS cantidRec,
   IF(dc.idProducto IS NULL, nombreProducto, pr.descripcion) AS producto
   FROM
     compras c
     INNER JOIN detcompras dc ON c.id = dc.idCompra
     INNER JOIN productos pr ON dc.idCompra =pr.id
     LEFT JOIN recepciones r ON c.id = r.idCompra
     LEFT JOIN detrecepciones dr ON r.id = dr.idRecepcion
   WHERE c.id='$idCompra'";
    //----------------devBug------------------------------
    if ($debug == 1) {
      $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Compra, notifica a tu Administrador', mysqli_error($link)));
      $canInsert = mysqli_affected_rows($link);
      echo '<br>SQL: ' . $sql . '<br>';
      echo '<br>Cant de Registros Cargados: ' . $canInsert;
    } else {
      $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Compra, notifica a tu Administrador', mysqli_error($link)));
      $canInsert = mysqli_affected_rows($link);
    } //-------------Finaliza devBug------------------------------

    $arreglo['data'] = array();

    while ($datos = mysqli_fetch_array($resultXquery)) {
      $arreglo['data'][] = $datos;
      //$arreglo['data'][] = array_map("utf8_encode", $datos);
      //  echo array_map("utf8_encode", $datos);
    }
    $var = json_encode($arreglo);
    mysqli_free_result($resultXquery);
    echo 'var datsJson = ' . $var . ';';
    echo 'var pyme = "' . $pyme . '";';
    ?>
  </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>