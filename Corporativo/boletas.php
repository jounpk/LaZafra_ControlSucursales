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
  <link href="../assets/libs/jsgrid/dist/jsgrid-theme.min.css" rel="stylesheet">
  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
  <link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
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

        <div class="row">
          <div class="col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Búsqueda de Boletas</h4>
              </div>
              <div class="card-body">
                <?php
                $fechaAct = date('Y-m-d');
                if (isset($_POST['fechaInicial'])) {
                  $fechaInicial = $_POST['fechaInicial'];
                } else {
                  $fechaInicial1 = strtotime('-1 week', strtotime($fechaAct));
                  $fechaInicial = date('Y-m-d', $fechaInicial1);
                }
                if (isset($_POST['fechaFinal'])) {
                  $fechaFinal = $_POST['fechaFinal'];
                } else {
                  $fechaFinal1 = strtotime('+15 day', strtotime($fechaAct));
                  $fechaFinal = date('Y-m-d', $fechaFinal1);
                }

                $filtroFecha = " AND v.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'";

                if (isset($_POST['empresa']) && $_POST['empresa'] != '') {
                  $filtroEmp = $_POST['empresa'];
                  $filtroEmpresa = " AND scs.idEmpresa = '$filtroEmp'";
                } else {
                  $filtroEmp = 0;
                  $filtroEmpresa = '';
                }

                if (isset($_POST['sucursal']) && $_POST['sucursal'] != '') {
                  $filtroSuc = $_POST['sucursal'];
                  $filtroSucursal = " AND scs.id = '$filtroSuc'";
                } else {
                  $filtroSuc = 0;
                  $filtroSucursal = '';
                }

                if (isset($_POST['municipio']) && $_POST['municipio'] != '') {
                  $filtroMpio = $_POST['municipio'];
                  $filtroMunicipio = " AND cm.id = '$filtroMpio'";
                } else {
                  $filtroMpio = 0;
                  $filtroMunicipio = '';
                }


                ?>
                <div class="border p-3 mb-3">
                  <h4><i class="fas fa-filter"></i> Filtrado</h4>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4" style="align-items:center;vertical-align: middle;">
                          <div class="col-md-12">
                            <h5 class="m-t-30 text-center text-<?= $pyme; ?>">Selecciona un rango de Fechas</h5>
                          </div>
                          <div class="col-md-12">
                            <form role="form" action="#" method="post">
                              <div class="input-daterange input-group" id="date-range">
                                <input type="date" class="form-control" name="fechaInicial" value="<?= $fechaInicial; ?>" />
                                <div class="input-group-append">
                                  <span class="input-group-text bg-<?= $pyme; ?> b-0 text-white"> A </span>
                                </div>
                                <input type="date" class="form-control" name="fechaFinal" value="<?= $fechaFinal; ?>" />
                              </div>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-3">
                                <h5 class="m-t-30 text-<?= $pyme; ?>">Filtro por Empresa</h5>
                              </div>
                              <div class="col-md-3">
                                <h5 class="m-t-30 text-<?= $pyme; ?>">Filtro por Sucursal</h5>
                              </div>
                              <div class="col-md-3">
                                <h5 class="m-t-30 text-<?= $pyme; ?>">Filtro por Municipio</h5>
                              </div>
                              <div class="col-md-3">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-3">
                              <select class="select2 form-control custom-select" name="empresa" id="empresa" style="width: 95%; height:100%;">
                                <option value="">Todas las Empresas</option>
                                <?php
                                $sqlEmpresa = "SELECT * FROM empresas WHERE estatus = 1";
                                $resEmpresa = mysqli_query($link, $sqlEmpresa) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                while ($emp = mysqli_fetch_array($resEmpresa)) {
                                  $act1 = ($filtroEmp == $emp['id']) ? 'selected' : '';
                                  echo '<option value="' . $emp['id'] . '" ' . $act1 . '>' . $emp['nombre'] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <select class="select2 form-control custom-select" name="sucursal" id="sucursal" style="width: 95%; height:100%;">
                                <option value="">Todas las Sucursales</option>
                                <?php
                                $sqlSuc = "SELECT id,nombre FROM sucursales WHERE estatus = 1";
                                $resSuc = mysqli_query($link, $sqlSuc) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                while ($suc = mysqli_fetch_array($resSuc)) {
                                  $act2 = ($filtroSuc == $suc['id']) ? 'selected' : '';
                                  echo '<option value="' . $suc['id'] . '" ' . $act2 . '>' . $suc['nombre'] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <select class="select2 form-control custom-select" name="municipio" id="municipio" style="width: 95%; height:100%;">
                                <option value="">Todos los Municipios</option>
                                <?php
                                $sqlMpio = "SELECT cm.id,cm.nombre FROM cultivosxmunicipios cxm INNER JOIN catmunicipios cm ON cxm.idMunicipio = cm.id WHERE cxm.estatus = 1 GROUP BY cm.id";
                                $resMpio = mysqli_query($link, $sqlMpio) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                while ($mpio = mysqli_fetch_array($resMpio)) {
                                  $act3 = ($filtroMpio == $mpio['id']) ? 'selected' : '';
                                  echo '<option value="' . $mpio['id'] . '" ' . $act3 . '>' . $mpio['nombre'] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <button type="submit" class="btn btn-<?= $pyme; ?>">Buscar</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  <table id="file_export_table" class="table  display">
                    <thead>
                      <tr>
                        <th class="text-center">Boleta</th>
                        <th>Sucursal</th>
                        <th>Municipio</th>
                        <th class="text-center">Corte</th>
                        <th class="text-center">Venta</th>
                        <th>Productor</th>
                        <th class="text-center">Cultivo</th>
                        <th class="text-center">Hect.</th>
                        <th class="text-right">Monto</th>
                        <th class="text-right">Monto<br>de Venta</th>
                        <th class="text-center">Fecha Reg.</th>
                        <th class="text-center">Fact.</th>
                        <th class="text-center">Ticket</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sqlBoletas = "SELECT scs.nombre AS nomSucursal, cm.nombre AS nomMpio, v.idCorte, pv.idVenta,
                      pv.folio, pv.productor, pv.idCultivo, cc.nombre AS cultivo, pv.superficie,
                      pv.monto, v.total AS montoVenta, DATE_FORMAT(v.fechaReg, '%d-%m-%Y %H:%i:%s') AS fecha,
                      vf.id AS idVtFact

                                              FROM ventas v
                                              INNER JOIN pagosventas pv ON v.id = pv.idVenta
                                              INNER JOIN catcultivos cc ON pv.idCultivo = cc.id
                                              INNER JOIN catmunicipios cm ON pv.idMunicipio = cm.id
                                              INNER JOIN sucursales scs ON v.idSucursal = scs.id
                                              LEFT JOIN vtasfact vf ON v.id=vf.idVenta

                                            WHERE pv.idFormaPago = '6' $filtroFecha $filtroEmpresa $filtroSucursal $filtroMunicipio
                                            ORDER BY v.fechaReg,v.id ASC";

                    //  echo '$sqlBoletas: '.$sqlBoletas;
                      $resBoletas = mysqli_query($link, $sqlBoletas) or die('Problemas al consultar las boletas, notifica a tu Administrador.');
                      while ($bol = mysqli_fetch_array($resBoletas)) {
                      //  echo "El id-->".$dat['idVtFact'];
                        if ($bol['idVtFact'] > 0 || $bol['idVtFact'] != '') {

                          $btnFacturar = '<button type="button" class="btn btn-success btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta(' . $bol['idVenta'] . ')" id="btn-' . $bol['idVenta'] . '"><i class="fas fa-download text-white"></i></button></button>';
                        } elseif ($bol['idVtFact'] <= 0 || $bol['idVtFact'] == '') {
                          $btnFacturar = '<button type="button" class="btn bg-' . $pyme . ' btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta(' . $bol['idVenta'] . ')" id="btn-' . $bol['idVenta'] . '"><i class="fas fa-copy text-white"></i></button></button>';
                        }
                        if ($bol['idCultivo'] == 1) {
                          $color = 'info';
                        } else {
                          $color = 'primary';
                        }
                        $boton = '<button type="button" class="btn btn-info btn-circle muestraSombra" onClick="imprimeTicketVenta('.$bol['idVenta'].');"><i class="fas fa-print"></i></button>';

                        echo '<tr>
                                          <td class="text-center">' . $bol['folio'] . '</td>
                                          <td>' . $bol['nomSucursal'] . '</td>
                                          <td>' . $bol['nomMpio'] . '</td>
                                          <td class="text-center">' . $bol['idCorte'] . '</td>
                                          <td class="text-center">' . $bol['idVenta'] . '</td>
                                          <td>' . $bol['productor'] . '</td>
                                          <td class="text-center text-' . $color . '"><b>' . $bol['cultivo'] . '</b></td>
                                          <td class="text-center">' . number_format($bol['superficie'], 2, '.', ',') . '</td>
                                          <td class="text-right">$' . number_format($bol['monto'], 2, '.', ',') . '</td>
                                          <td class="text-right">$' . number_format($bol['montoVenta'], 2, '.', ',') . '</td>
                                          <td class="text-center">' . $bol['fecha'] . '</td>
                                          <td class="text-center">' . $btnFacturar . '</td>
                                          <td class="text-center">' . $boton . '</td>
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
        <!-- sample modal content -->
        <div id="modalFacturaVenta" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-<?= $pyme ?>">
                <h4 class="modal-title text-white" id="lblFacturaVenta">Ticket No.: </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body" id="facturaVentaBody">

              </div>

            </div>
          </div>
        </div>
        <!-- /.modal -->

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
  <script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!--This page JavaScript -->
  <!--chartis chart-->
  <!--c3 charts -->
  <script src="../assets/libs/jsgrid/dist/jsgrid.min.js"></script>

  <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.js"></script>
  <!-- start - This is for export functionality only -->
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
  <script src="../dist/js/pages/datatable/datatable-advanced.init.js"></script>
  <!--Custom JavaScript -->

  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>

  <script>
    $(document).ready(function() {
      <?php
      include('../funciones/basicFuctions.php');
      //  alertMsj($nameLk);
      ?>
        <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

    if (isset( $_SESSION['LZFmsjCorpBol'])) {
      echo "notificaBad('".$_SESSION['LZFmsjCorpBol']."');";
      unset($_SESSION['LZFmsjCorpBol']);
    }
    if (isset( $_SESSION['LZFmsjSuccessCorpBol'])) {
      echo "notificaSuc('".$_SESSION['LZFmsjSuccessCorpBol']."');";
      unset($_SESSION['LZFmsjSuccessCorpBol']);
    }
      ?>

    });
    $('#file_export_table').DataTable({
      responsive: false,
      dom: 'B<"clear">lfrtip',
      fixedColumns: true,
      fixedHeader: true,
      scrollCollapse: true,
      bSort: true,
      autoWidth: true,
      scrollCollapse: true,
      lengthMenu: [
        [5, 25, 50, -1],
        [5, 25, 50, "Todo"]
      ],
      info: true,
      buttons: [{
          extend: 'excelHtml5',
          className: 'btn',
          text: "Excel",
          exportOptions: {
            columns: ":not(.no-exportar)"
          }
        },
        {
          extend: 'csvHtml5',
          className: 'btn',
          text: "Csv",
          exportOptions: {
            columns: ":not(.no-exportar)"
          }
        },
        {
          extend: 'pdfHtml5',
          className: 'btn',
          text: "Pdf",
          exportOptions: {
            columns: ":not(.no-exportar)"
          },

        },
        {
          extend: 'copy',
          className: 'btn',
          text: "Copiar",
          exportOptions: {
            columns: ":not(.no-exportar)"
          }
        }
      ],
      language: {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "decimal": ",",
        "thousands": "."
      }


    });
    $(".dt-button").addClass("btn-<?= $pyme; ?>");

    function modalFacturaVenta(idVenta) {
      if (idVenta > 0) {
        //alert('Entra, idVenta: '+idVenta);
        $.post("../funciones/formularioFacturacionBol.php", {
            idVenta: idVenta
          },
          function(respuesta) {
            $("#lblFacturaVenta").html("Ticket No.: " + idVenta);
            $("#facturaVentaBody").html(respuesta);
          });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
    }

    function buscarXCliente() {
      nombreCliente = $('#busq_nombre').val();
      //alert(nombreCliente);
      $.post("../funciones/busquedaCliente.php", {
          id: nombreCliente
        },
        function(respuesta) {
          if (respuesta[0] == 0) {
            var resp = respuesta.split('|');
            notificaBad(resp[1]);
          } else {
            $("#resultadosBusq").html(respuesta);
          }
        });



    }


    function buscarXRFC() {
      rfcCliente = $('#busq_rfc').val();
      //alert(nombreCliente);
      $.post("../funciones/busquedaRFC.php", {
          id: rfcCliente
        },
        function(respuesta) {
          if (respuesta[0] == 0) {
            var resp = respuesta.split('|');
            notificaBad(resp[1]);
          } else {

            $("#resultadosBusq").html(respuesta);

          }
        });



    }

    function cambioMunicipio(idEstado) {

      $.post("../funciones/listaMunicipios.php", {
          idEstado: idEstado
        },
        function(respuesta) {

          $("#municipios").html(respuesta);

        }
      );
    }

    function imprimeTicketVenta(idVenta){
        $('<form action="../imprimeTicketVenta.php" target="_blank" method="POST"><input type="hidden" name="idVenta" value="'+idVenta+'"><input type="hidden" name="tipo" value="2"></form>').appendTo('body').submit();
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
