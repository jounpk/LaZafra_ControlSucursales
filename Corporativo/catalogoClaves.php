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
#error_reportin(E_ALL);
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
                #/*
                $estatusProd = (isset($_POST['estatusProd']) && $_POST['estatusProd'] != '') ? $_POST['estatusProd'] : 1 ;

                  if ($estatusProd == 2) {
                    $sel1 = '';
                    $sel2 = 'selected';
                  } else {
                    $sel1 = 'selected';
                    $sel2 = '';
                  }

                $estatusUnid = (isset($_POST['estatusUnid']) && $_POST['estatusUnid'] != '') ? $_POST['estatusUnid'] : 1;

                    if ($estatusUnid == 2) {
                      $sel1b = '';
                      $sel2b = 'selected';
                    } else {
                      $sel1b = 'selected';
                      $sel2b = '';
                    }
                    #*/
                 ?>
                 <style>
                 label.alinearCentro{
                   display: inline-block;
                    text-align: center;
                    vertical-align:middle;
                    line-height: 150%;
                    padding-top: 12%;
                 }
                 </style>
                <div class="row">
                  <div class="col-md-6">
                    <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="m-b-0 text-white">Listado de Claves de Productos</h4>
                        </div>
                        <br>
                        <div class="ml-auto">
                          <div class="input-group">
                            <div class="text-center">
                              <label class="control-label text-<?=$pyme;?> alinearCentro">Mostrando:&nbsp;&nbsp;</label>
                            </div>
                                <select class="custom-select" id="selectEstatusProd">
                                    <option value="1" <?=$sel1;?>>Activos</option>
                                    <option value="2" <?=$sel2;?>>Inactivos</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-<?=$pyme;?>" onclick="listaProd(3);" type="button"><i class="fas fa-search"></i></button>
                                </div>
                                <div class="col-1"></div>
                            </div>
                        </div>
                        <div class="card-body">
                          <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                            <div class="table-responsive">
                              <table class="table product-overview" id="zero_config">
                                <thead>
                                  <tr>
                                    <th class="text-center">Clave</th>
                                    <th>Nombre</th>
                                    <th>Departamento</th>
                                    <th class="text-center">Estatus</th>
                                    <th class="text-center">Acciones</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  #  /*
                                    $sqlCproductos = "SELECT * FROM sat_claveproducto WHERE estatus = '$estatusProd' ORDER BY estatus ASC, descripcion ASC";
                                    $resProductos = mysqli_query($link,$sqlCproductos) or die('Problemas al consultar las Claves de Productos, notifica a tu Administrador');
                                    while ($pd = mysqli_fetch_array($resProductos)) {
                                      $id = $pd['codigo'];
                                      $iconProd = ($pd['estatus'] == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';
                                      $btnProd = ($pd['estatus'] == 1) ? '<button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatusProd('.$id.', '.$pd['estatus'].');"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatusProd('.$id.', '.$pd['estatus'].');"><i class="fas fa-exchange-alt"></i></button>' ;

                                         echo '<tr>
                                                  <td class="text-center">'.$id.'</td>
                                                  <td>'.$pd['descripcion'].'</td>
                                                  <td>'.$pd['departamento'].'</td>
                                                  <td class="text-center" id="iconProd-'.$id.'">'.$iconProd.'</td>
                                                  <td class="text-center" id="eProd-'.$id.'">'.$btnProd.'</td>
                                                </tr>';
                                    }
                                  #  */
                                   ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="card border-<?=$pyme;?>">
                          <div class="card-header bg-<?=$pyme;?>">
                            <h4 class="m-b-0 text-white">Listado de claves de Unidades</h4>
                          </div>
                          <br>
                          <div class="ml-auto">
                            <div class="input-group">
                              <div class="text-center">
                                <label class="control-label text-<?=$pyme;?> alinearCentro">Mostrando:&nbsp;&nbsp;</label>
                              </div>
                                  <select class="custom-select" id="selectEstatusUnid">
                                      <option value="1" <?=$sel1b;?>>Activos</option>
                                      <option value="2" <?=$sel2b;?>>Inactivos</option>
                                  </select>
                                  <div class="input-group-append">
                                      <button class="btn btn-outline-<?=$pyme;?>" onclick="listaUnid(3);" type="button"><i class="fas fa-search"></i></button>
                                  </div>
                                  <div class="col-1"></div>
                              </div>
                          </div>
                          <div class="card-body">
                            <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                              <div class="table-responsive">
                                <table class="table product-overview" id="zero_config2">
                                  <thead>
                                    <tr>
                                      <th class="text-center">Clave</th>
                                      <th>Nombre</th>
                                      <th>Descripción</th>
                                      <th class="text-center">Estatus</th>
                                      <th class="text-center">Acciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    #/*
                                      $sqlUnidades = "SELECT * FROM sat_claveunidad WHERE estatus = '$estatusUnid' ORDER BY nombre ASC";
                                      $resUnidades = mysqli_query($link,$sqlUnidades) or die('Problemas al consultar las Unidades, notifica a tu Administrador');

                                      while ($uni = mysqli_fetch_array($resUnidades)) {
                                        $idUni = $uni['id'];
                                        $iconUni = ($uni['estatus'] == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';
                                        $btnUni = ($uni['estatus'] == 1) ? '<button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatusUni(\''.$idUni.'\', '.$uni['estatus'].');"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatusUni(\''.$idUni.'\', '.$uni['estatus'].');"><i class="fas fa-exchange-alt"></i></button>' ;
                                        echo '<tr>
                                                <td class="text-center">'.$idUni.'</td>
                                                <td>'.$uni['nombre'].'</td>
                                                <td>'.$uni['descripcion'].'</td>
                                                <td class="text-center" id="iconUnid-'.$idUni.'">'.$iconUni.'</td>
                                                <td class="text-center" id="eUnid-'.$idUni.'">'.$btnUni.'</td>
                                              </tr>';
                                      }
                                      #*/
                                     ?>

                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>

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

    <script>
    $(document).ready(function(){
      <?php
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);
      ?>
    });

    $("#zero_config2").DataTable();

    function listaProd(estatus){
      if (estatus == 3) {
        var estatus = $("#selectEstatusProd option:selected").val();
      }
      //alert("estatus: "+estatus);
        $('<form action="#" method="POST"><input type="hidden" name="estatusProd" value="'+estatus+'"></form>').appendTo('body').submit();
   }

   function listaUnid(estatus){
     if (estatus == 3) {
       var estatus = $("#selectEstatusUnid option:selected").val();
     }
     //alert("estatus: "+estatus);
       $('<form action="#" method="POST"><input type="hidden" name="estatusUnid" value="'+estatus+'"></form>').appendTo('body').submit();
  }

  function cambiaEstatusProd(idProd,estatus){
    if (idProd > 0) {
      //alert('idProd: '+idProd+', estatus: '+estatus);
      $.post("../funciones/cambiaEstatusClaveProd.php",
    {idProd:idProd, estatus:estatus},
  function(respuesta){
    var resp = respuesta.split('|');
    if (resp[0] == 1) {
      notificaSuc(resp[1]);
      $("#iconProd-"+idProd).html(resp[2]);
      $("#eProd-"+idProd).html(resp[3]);

    } else {
      notificaBad(resp[0]);
    }
  });
    } else {
    notificaBad('No se reconoció la Clave del Producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
    }
  }

  function cambiaEstatusUni(idUnid,estatus){
    if (idUnid > 0) {
      //alert('idUnid: '+idUnid+', estatus: '+estatus);
      $.post("../funciones/cambiaEstatusClaveUnid.php",
      {idUnid:idUnid, estatus:estatus},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        notificaSuc(resp[1]);
        $("#iconUnid-"+idUnid).html(resp[2]);
        $("#eUnid-"+idUnid).html(resp[3]);

      } else {
        notificaBad(resp[0]);
      }
    });
      } else {
      notificaBad('No se reconoció la Clave de Unidad, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
      }
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
