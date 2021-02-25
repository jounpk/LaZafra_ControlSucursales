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

                        <!-- ============================================================== -->
                        <!-- Comment -->
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
                <br>
                <div class="row">
                  <div class="col-md-8 col-lg-8">
                    <div class="card border-<?=$pyme;?>">
                      <div class="card-header bg-<?=$pyme;?>">
                        <h4 class="m-b-0 text-white">Listado de Servicios</h4>
                      </div>
                      <div class="card-body">
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table product-overview" id="zero_config">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Nombre</th>
                                  <th class="text-center">Estatus</th>
                                  <th class="text-center">Acciones</th>
                                </tr>
                              </thead>
                              <tbody id="cuerpoTabla">
                                <?php

                                  $sqlServicios = "SELECT * FROM catservicios ORDER BY estatus ASC";
                                  $resServicios = mysqli_query($link,$sqlServicios) or die('Problemas al consultar los Servicios, notifica a tu Administrador');

                                  while ($lista = mysqli_fetch_array($resServicios)) {
                                    $id = $lista['id'];
                                    $estatus = ($lista['estatus'] == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;
                                    $cambiaEstatus = ($lista['estatus'] == 2) ? '<a id="btnCambiaEstatus-'.$lista['id'].'"><button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatus('.$id.','.$lista['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' : '<a id="btnCambiaEstatus-'.$lista['id'].'"><button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatus('.$id.','.$lista['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' ;

                                    echo '<tr>
                                            <td class="text-center">'.$id.'</td>
                                            <td id="nombreServicio-'.$id.'">'.$lista['nombre'].'</td>
                                            <td class="text-center" id="estatusServicio-'.$id.'">'.$estatus.'</td>
                                            <td class="text-center" id="botonesServicio-'.$id.'">
                                              <button class="btn btn-outline-info" data-target="#modalEditServicio" data-toggle="modal" onclick="mandaModal('.$id.');"><i class="fas fa-pencil-alt"></i></button>
                                              '.$cambiaEstatus.'
                                            </td>
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
                  <div class="col-md-4 col-lg-4">
                    <div class="card border-<?=$pyme;?>">
                      <div class="card-header bg-<?=$pyme;?>">
                        <h4 class="m-b-0 text-white">Ingreso de uno Nuevo Servicio</h4>
                      </div>
                      <div class="card-body">
                            <form role="form" id="formNewServicio">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-trademark"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Ingresa un Nuevo Servicio" id="nombreServicio" name="nombreServicio" oninput="limpiaCadena(this.value,'nombreServicio');" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>

                                  <div class="modal-footer">
                                    <div id="bloquear-btn" style="display:none;">
                                      <button class="btn btn-primary" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="sr-only">Loading...</span>
                                      </button>
                                      <button class="btn btn-primary" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="sr-only">Loading...</span>
                                      </button>
                                      <button class="btn btn-primary" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                      </button>
                                    </div>
                                    <div id="desbloquear-btn">
                                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                      <button type="button" class="btn btn-success waves-effect waves-light" onclick="crearServicio('bloquear-btn');">Capturar</button>
                                    </div>
                                  </div>
                              </form>
                      </div>
                    </div>
                  </div>
                </div>



                <!-- sample modal content -->
                <div id="modalEditServicio" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="lblEditServicio">Editar</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                              <form role="form" id="formEditaServicio">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="eNombre1"><i class="far fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="eNombre" placeholder="Ingresa el Nombre del Servicio" aria-describedby="nombre" name="eNombre" oninput="limpiaCadena(this.value,'eNombre');" required>
                                    </div>

                                    <div class="modal-footer">
                                      <div id="bloquear-btn1" style="display:none;">
                                        <button class="btn btn-primary" type="button" disabled>
                                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                          <span class="sr-only">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" disabled>
                                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                          <span class="sr-only">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" disabled>
                                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                          Loading...
                                        </button>
                                      </div>
                                      <div id="desbloquear-btn1">
                                        <input type="hidden" id="idServicio">
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="mandaEdición('bloquear-btn1');">Editar</button>
                                      </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal -->


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
			if (isset( $_SESSION['LZFmsjCatalogoServicios'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCatalogoServicios']."');";
				unset($_SESSION['LZFmsjCatalogoServicios']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCatalogoServicios'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCatalogoServicios']."');";
        unset($_SESSION['LZFmsjSuccessCatalogoServicios']);
			}
			?>

    }); // Cierre de document ready

    function crearServicio(boton){
      var nombre = $("#nombreServicio").val();
      if (nombre != '') {
      bloqueoBtn(boton,1);
      $.post("../funciones/registraNuevoServicio.php",
      {nombre:nombre},
      function(respuesta){
        var resp = respuesta.split('|');
        if (resp[0] == 1) {
          listaServicios();
          notificaSuc(resp[1]);
          bloqueoBtn(boton,2);
          $("#nombreServicio").val('');
          $("#nombreServicio").focus();
        } else {
          notificaBad(resp[1]);
          bloqueoBtn(boton,2);
          $("#nombreServicio").focus();
        }
      });
    } else {
      notificaBad('Debes introducir un Nombre, inténtalo de nuevo.');
    }
    }

    function mandaEdición(boton){
      var nombre = $("#eNombre").val();
      var id = $("#idServicio").val();
      if (nombre != '') {
        bloqueoBtn(boton,1);
        $.post("../funciones/editaServicio.php",
      {idServicio:id, nombre:nombre},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        listaServicios();
        notificaSuc(resp[1]);
        bloqueoBtn(boton,2);
        $("#modalEditServicio").modal('hide');
      } else {
      notificaBad(resp[1]);
      bloqueoBtn(boton,2);
      }
    });
      } else {
      notificaBad('Debes introducir una Nombre, inténtalo de nuevo.');
      bloqueoBtn(boton,2);
      }
    }

    function mandaModal(id){
      if (id > 0) {
      var nombre = $("#nombreServicio-"+id).html();
      $("#lblEditServicio").html('Editar '+nombre);
      $("#eNombre").val(nombre);
      $("#idServicio").val(id);
    }
  }

    function limpiaCadena(dat,id){
		    //alert(id);
		    dat=getCadenaLimpia(dat);
			$("#"+id).val(dat);
		}



    function cambiaEstatus(id,estatus){
      if (id > 0 && estatus > 0) {
        $.post("../funciones/cambiaEstatusDeServicio.php",
      {idServicio:id, estatus:estatus},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        notificaSuc(resp[1]);
        if (estatus == 1) {
          $("#estatusServicio-"+id).html(resp[3]);
          $("#btnCambiaEstatus-"+id).html(resp[2]);
        } else {
          $("#estatusServicio-"+id).html(resp[3]);
          $("#btnCambiaEstatus-"+id).html(resp[2]);
        }
      } else {
        notificaBad(resp[0]);
      }
    });
      } else {
      notificaBad('No se reconoció el Servicio, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
      }
    }

    function listaServicios(){
    //  var mensaje = 'Mensaje';
      $.post("../funciones/listarServicios.php",
    {},
  function(respuesta){
    $("#validation").html(respuesta);
  });
    }
</script>

</body>

</html>
