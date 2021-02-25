<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');
$hoy = date('d-m-Y');
$mes = date('m-Y');
$fechaAct = date('Y-m-d');
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
  <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <!--<link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
-->
  <link href="../assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">


  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <style>
    #listaSuc {
      column-count: 2;
    }

    .select2-container {
      width: 100%;
    }
  </style>

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
    <!--- <div class="col-lg-2">
                          <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Vigencia del Nombramiento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                              </div>
                              <input name="fechaInicial" class="datepicker form-control" type="text" value="<?= $fechaInicial; ?>" id="rangeBa" />
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-2">
                        <div class="form-group">
                            <label for="inputEmail3" class="control-label col-form-label">Vigencia del Nombramiento</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                              </div>
                              <input name="fechaFinal" class="datepicker form-control" type="text" value="<?= $fechaFinal; ?>" id="rangeBb" />
                            </div>
                          </div>
                        </div>-->

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
                  <div class="border p-3 mb-3">
                    <h4><i class="fas fa-filter"></i> Filtrado</h4>

                    <div class="row">
                      <form method="post" action="bajaProducto.php">

                        <div class="col-6">

                        </div>
                        <div class="col-6">

                        </div>
                    </div>


                    <!--/span-->

                    <div class="row">

                      <form method="post" action="bajaProducto.php">
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

                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="table-responsive">
                        <table id="table-stock" class="table display  table-striped no-wrap">
                          <thead>
                            <th class="">#</th>
                          <th class="">Foto</th>
                          <th class="">Nombre</th>
                          <th class="">Marca</th>
                          <th class="">Departamento</th>
                          <th class="">Stock General</th>
                          <th class="">Estatus</th>
                          </thead>

                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!--<div class="table-responsive">
                    <table id="demo-foo-row-toggler" class="table table-bordered table-striped " data-toggle-column="first">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Foto</th>
                          <th>Cód. Barras</th>
                          <th>Descripción</th>
                          <th>Marca</th>
                          <th>Departamento</th>
                          <th>Stock General</th>
                          <th>Estatus</th>





                        </tr>
                      </thead>
                      <tbody>


              </div>-->
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
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
  <script src="../dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <!--This page JavaScript -->
  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../assets/libs/moment/moment.js"></script>
  <script src="../assets/libs/footable/js/footable.min.js"></script>
  <script src="../assets/libs/footable/js/footable.min.js"></script>
  <script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js"></script>
  <script src="../assets/tablasZafra/datatable_configBaja.js"></script>

  <script>
      <?php


//QUERY DE DETALLADO DE PRODUCTO
if ($filtroMarca == '' && $filtroDepto == '') {
  $stringWhere = '';
} else if ($filtroMarca == '') {
  $stringWhere = 'WHERE ' . $filtroDepto;
} else if ($filtroMarca != '' && $filtroDepto != '') {
  $stringWhere = 'WHERE ' . $filtroMarca . ' AND ' . $filtroDepto;
} else if ($filtroDepto == '') {
  $stringWhere = 'WHERE ' . $filtroMarca;
}
$img='<img onclick=\"verIMG(\'Detalles del Producto: ",pro.descripcion, "\', \'", pro.foto,"\');\" data-toggle=\"modal\" data-target=\"#verIMG\" class=\"\" height=\"40\" src=\"../", pro.foto, "\" alt=\"\" />';

$sqlProductos = 'SELECT pro.id, pro.codBarra, pro.descripcion, dpto.nombre AS dpto, m.nombre AS marca, CONCAT("'.$img.'") AS foto,IF(SUM(stk.cantActual) IS NULL, 0,SUM(stk.cantActual))  AS stock, IF(pro.estatus=1,CONCAT("<center class=\"text-success ley_edicion\" id=\'btn-", pro.id, "\' data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Activo\"><button class=\"btn-circle\"  type=\"button\" ", IF(SUM(stk.cantActual)=0 OR SUM(stk.cantActual) IS NULL,"","disabled"), " style=\"background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);\" onclick=\"estatusPro(", pro.id,",0);\"
class=\"btn ink-reaction btn-icon-toggle\"><i class=\"fas fa-check text-success\"></i></button></center>"),
CONCAT("<center class=\"text-success ley_edicion\" id=\'btn-", pro.id, "\'  data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Desactivado\"><button class=\"btn-circle\"  type=\"button\" ", IF(SUM(stk.cantActual)=0 OR SUM(stk.cantActual) IS NULL,"","disabled"), " style=\"background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);\" onclick=\"estatusPro(", pro.id,",1);\"
class=\"btn ink-reaction btn-icon-toggle\"><i class=\"fas fa-times text-danger\"></i></button></center>")) AS statusBTN
FROM productos pro
INNER JOIN catmarcas m ON pro.idCatMarca=m.id
INNER JOIN departamentos dpto ON pro.idDepartamento=dpto.id
INNER JOIN sat_claveunidad clvuni ON clvuni.id= pro.idClavUniProducto
INNER JOIN sat_claveproducto clv ON pro.idClaveProducto=clv.codigo
LEFT JOIN stocks stk ON stk.idProducto=pro.id '.$stringWhere.'
GROUP BY pro.id ORDER BY stock ASC';
//echo $sqlProductos;
$resPro = mysqli_query($link, $sqlProductos) or die('Problemas al listar los Productos, notifica a tu Administrador');


$arreglo['data'] = array();


while ($pro = mysqli_fetch_array($resPro)) {
  $arreglo['data'][] = $pro;


}
$var = json_encode($arreglo);
mysqli_free_result($resPro);

echo 'var datsJson = ' . $var . ';';
echo 'var pyme = "' . $pyme . '";';


?>
//console.log(datsJson.data);
    $(document).ready(function() {

      /*  jQuery('.datepicker').datepicker({
          toggleActive: true,
          language: 'es',
          format: 'yyyy-mm-dd'
        });*/

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
    $('table').on('hover', function(e){
            if($('.ley_edicion').length>1)
            $('.ley_edicion').tooltip('hide');
            $(e.target).tooltip('toggle');

            });
    function ejecutandoCarga(identif) {
                var selector = 'DIV' + identif;
                var finicio = $('#fStart').val();
                var ffin = $('#fEnd').val();

                $.post("../funciones/cargaContenido.php", {
                        ident: identif
                    },
                    function(respuesta) {
                        $("#" + selector).html(respuesta);
                    });

            }

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
           /* setTimeout(function() {
              location.reload();
            }, 1000);*/
            if(estatus==1){
              $("#btn-"+id).html("<button class='btn-circle' type='button' style='background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);' onclick='estatusPro("+id+",0);'><i class='fas fa-check text-success'></i></button>");
            }
            if(estatus==0){
              $("#btn-"+id).html("<button class='btn-circle' type='button' style='background:#fff; box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);' onclick='estatusPro("+id+",1);'><i class='fas fa-times text-danger'></i></button>");
            }

          } else {
            notificaBad(resp[1]);
          }
        });
      /* } else {
         notificaBad('No se reconoció el Producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
       }*/
    }

    function cambiaEstatus(id, estatus) {
      if (id > 0 && estatus > 0) {
        $.post("../funciones/cambiaEstatusDepto.php", {
            idDepto: id,
            estatus: estatus
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
              notificaSuc(resp[1]);
              if (estatus == 1) {
                $("#estatusDpt-" + id).html(resp[3]);
                $("#btnCambiaEstatus-" + id).html(resp[2]);
              } else {
                $("#estatusDpt-" + id).html(resp[3]);
                $("#btnCambiaEstatus-" + id).html(resp[2]);
              }
            } else {
              notificaBad(resp[0]);
            }
          });
      } else {
        notificaBad('No se reconoció el Departamento, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
      }
    }

    function listaDeptos() {
      //  var mensaje = 'Mensaje';
      $.post("../funciones/listarDeptos.php", {},
        function(respuesta) {
          $("#validation").html(respuesta);
        });
    }
  </script>

</body>

</html>
