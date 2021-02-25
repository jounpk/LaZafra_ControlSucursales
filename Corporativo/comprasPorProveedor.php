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

                <div class="row">
                  <div class="col-lg-12">
                      <div class="card border-<?=$pyme;?>">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="m-b-0 text-white"><i class="fas fa-filter"></i> Filtros</h4>
                          </div>
                      <div class="card-body">
                        <?php
                        #print_r($_POST);
                        $fechaAct=date('Y-m-d');
                        if (isset($_POST['fechaInicial'])) {
                          $fechaInicial = $_POST['fechaInicial'];
                        }else {
                          $fechaInicial1 = strtotime ( '-1 week' , strtotime ( $fechaAct ) ) ;
                          $fechaInicial = date ( 'Y-m-d' , $fechaInicial1 );
                        }
                        if (isset($_POST['fechaFinal'])) {
                          $fechaFinal = $_POST['fechaFinal'];
                        }else {
                          $fechaFinal1 = strtotime ( '+15 day' , strtotime ( $fechaAct ) ) ;
                          $fechaFinal = date ( 'Y-m-d' , $fechaFinal1 );
                        }

                        $filtroFecha = " AND c.fechaCompra BETWEEN '$fechaInicial' AND '$fechaFinal'";

                        if (isset($_POST['proveedor']) && $_POST['proveedor'] > 0) {
                          $proveedor = $_POST['proveedor'];
                          $filtroProveedor = " AND p.id = '$proveedor'";
                        } else {
                          $proveedor = '';
                          $filtroProveedor = '';
                        }

                        $_SESSION['filtroFecha'] = $filtroFecha;
                        $_SESSION['proveedor'] = $filtroProveedor;
                         ?>
                         <style>
                           .muestraSombra{
                             box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);
                           }
                           .alinearCentro{
                             display: inline-block;
                              text-align: center;
                              vertical-align:middle;
                              line-height: 150%;
                              padding-top: 15%;
                           }
                         </style>
                       <div class="row">
                           <div class="col-md-5">
                             <form role="form" action="#" method="post">
                               <div class="input-daterange input-group" id="date-range">
                                   <input type="date" class="form-control" name="fechaInicial" value="<?=$fechaInicial;?>" />
                                   <div class="input-group-append">
                                       <span class="input-group-text bg-<?=$pyme;?> b-0 text-white"> A </span>
                                   </div>
                                   <input type="date" class="form-control" name="fechaFinal" value="<?=$fechaFinal;?>" />
                               </div>
                           </div>
                           <div class="col-md-5">
                              <select class="select2 form-control custom-select" name="proveedor" id="proveedor" style="width: 95%; height:100%;">
                                 <?php
                                 echo '<option value="">Ingresa el Nombre del Proveedor</option>';
                                 $sql="SELECT id,nombre
                                                 FROM proveedores
                                                 WHERE estatus = '1'";
                               #  echo $sql;
                                 $res=mysqli_query($link,$sql);
                                  while ($rows = mysqli_fetch_array($res)) {
                                    $activa = ($proveedor == $rows['id']) ? 'selected' : '' ;
                                    echo '<option value="'.$rows['id'].'" '.$activa.'>'.$rows['nombre'].'</option>';
                                  }
                                  ?>
                               </select>
                           </div>
                           <div class="col-md-2">
                             <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                           </div>
                       </div>
                     </div>
                       </form>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="card border-<?=$pyme;?>">
                      <div class="card-header bg-<?=$pyme;?>">
                        <h4 class="text-white">Historial de compras</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped extra-info" width="100%">
                            <thead>
                              <tr>
                                <td width="25px"></td>
                                <td width="25px">#</td>
                                <td class="text-center">Folio</td>
                                <td>Proveedor</td>
                                <td>Comprador</td>
                                <td class="text-center">Fecha de compra</td>
                                <td class="text-center">Total</td>
                              </tr>
                            </thead>

                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

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
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      /*
      include('../funciones/basicFuctions.php');
      alertMsj($nameLk);
      */
      ?>
    });

    //==================================================//
    // filas ocultas (Mostrar extra / información detallada)   //
    //==================================================//
    /* funcion de formato para filas con detalle - modificar por si se requiere */
  ///*
    function format(d) {
        // `d` es el objeto que contiene la información de las filas

        return '<table width="100%">' +
        '<tr>' +
            '<td>Producto</td>' +
            '<td class="text-right">Cantidad</td>' +
            '<td class="text-right">Costo Unitario</td>' +
            '<td class="text-right">Subtotal</td>' +
          '</tr>' + d.info +
        '</table>';

    }



    //=============================================//
    // -- filas hijo
    //=============================================//
  var tableChildRows = $('.extra-info').DataTable({
      "ajax": "../funciones/tablaComprasXproveedor.php", //aquí obtiene el archivo con la información
      "columns": [{
                "data": null,
                "className": 'details-control',
                "orderable": false,
                "defaultContent": ''
          },
          { "data": "cont","className": 'text-center' },
          { "data": "folio","className": 'text-center' },
          { "data": "proveedor" },
          { "data": "comprador" },
          { "data": "fecha","className": 'text-center' },
          { "data": "totalCompra","className": 'text-right' }
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

      // */
</script>

</body>

</html>
