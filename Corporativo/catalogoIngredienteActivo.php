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
                        <h4 class="m-b-0 text-white">Listado de Ingredientes</h4>
                      </div>
                      <div class="card-body">
                        <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                          <div class="table-responsive">
                            <table class="table product-overview" id="zero_config">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Nombre</th>
                                  <th>Descripción</th>
                                  <th class="text-center">Estatus</th>
                                  <th class="text-center">Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $sqlIngredientes = "SELECT * FROM catingact ORDER BY nombre ASC";
                                $resIngredientes = mysqli_query($link,$sqlIngredientes) or die('Problemas al listar los Ingredientes, notifica a tu Administrador');

                                while ($ing = mysqli_fetch_array($resIngredientes)) {
                                  $id = $ing['id'];
                                  $estatus = ($ing['estatus'] == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;
                                  $boton = ($ing['estatus'] == 1) ? '<a id="btnCambiaEstatus-'.$id.'" title="Deshabilitar"><button class="btn btn-outline-danger" onclick="cambiaEstatus('.$id.','.$ing['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' : '<a id="btnCambiaEstatus-'.$id.'" title="Habilitar"><button class="btn btn-outline-warning"  onclick="cambiaEstatus('.$id.','.$ing['estatus'].');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' ;
                                  echo '<tr>
                                          <td class="text-center">'.$ing['id'].'</td>
                                          <td id="nomIngAct-'.$id.'">'.$ing['nombre'].'</td>
                                          <td id="descIngAct-'.$id.'">'.$ing['descripcion'].'</td>
                                          <td id="estatusIngAct-'.$id.'" class="text-center">'.$estatus.'</td>
                                          <td class="text-center">
                                            <button class="btn btn-outline-info" data-toggle="modal" data-target="#modalEditIngrediente" onclick="mandaModal('.$id.');"><i class="fas fa-pencil-alt"></i></button>
                                            '.$boton.'
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
                        <h4 class="m-b-0 text-white">Nuevo Ingrediente</h4>
                      </div>
                      <div class="card-body">
                        <form role="form" id="formNewIngrediente">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-bowl"></i></span>
                            </div>
                              <input type="text" class="form-control" placeholder="Ingresa el nombre del Ingrediente" id="nombreIngrediente" name="nombreIngrediente" oninput="limpiaCadena(this.value,'nombreIngrediente');" aria-label="Ingrediente" aria-describedby="basic-addon1" required>
                            </div>

                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-basket-fill"></i></span>
                              </div>
                                <textarea class="form-control" placeholder="Describe el Ingrediente" rows="3" id="descIngrediente" name="descIngrediente" oninput="limpiaCadena(this.value,'descIngrediente');" required></textarea>
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
                                  <button type="button" class="btn btn-success waves-effect waves-light" onclick="crearIngrediente('bloquear-btn');">Capturar</button>
                                </div>
                              </div>
                          </form>
                      </div>
                  </div>
                </div>
              </div>

              <!-- sample modal content -->
              <div id="modalEditIngrediente" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title" id="lblEditIngrediente">Editar</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          </div>
                          <div class="modal-body">
                            <form role="form" id="formEditaIngrediente">

                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="eNombre1"><i class="mdi mdi-bowl"></i></span>
                                      </div>
                                      <input type="text" class="form-control" placeholder="Ingresa el nombre del Ingrediente" id="eNombreIngrediente" aria-describedby="nombre" name="eNombreIngrediente" oninput="limpiaCadena(this.value,'eNombreIngrediente');" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="eDesc1"><i class="mdi mdi-basket-fill"></i></span>
                                      </div>
                                      <textarea class="form-control" rows="3" placeholder="Describe el Ingrediente" id="eDescIngrediente" name="eDescIngrediente" oninput="limpiaCadena(this.value,'eDescIngrediente');" required></textarea>
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
                                      <input type="hidden" id="idIngrediente">
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
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjCatalogoIngredientes'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCatalogoIngredientes']."');";
				unset($_SESSION['LZFmsjCatalogoIngredientes']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCatalogoIngredientes'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCatalogoIngredientes']."');";
        unset($_SESSION['LZFmsjSuccessCatalogoIngredientes']);
			}
      ?>
    }); // Cierre de document ready

    function crearIngrediente(boton){
      var nombre = $("#nombreIngrediente").val();
      var desc = $("#descIngrediente").val();
      if (nombre != '') {
      bloqueoBtn(boton,1);
      $.post("../funciones/registraNuevoIngrediente.php",
      {nombre:nombre, desc:desc},
      function(respuesta){
        var resp = respuesta.split('|');
        if (resp[0] == 1) {
          listaIngredientes();
          notificaSuc(resp[1]);
          bloqueoBtn(boton,2);
          $("#nombreIngrediente").val('');
          $("#descIngrediente").val('');
          $("#nombreIngrediente").focus();
        } else {
          notificaBad(resp[1]);
          bloqueoBtn(boton,2);
          $("#nombreIngrediente").focus();
        }
      });
    } else {
      notificaBad('Debes introducir una Nombre, inténtalo de nuevo.');
    }
    }

    function mandaEdición(boton){
      var nombre = $("#eNombreIngrediente").val();
      var desc = $("#eDescIngrediente").val();
      var id = $("#idIngrediente").val();
      if (nombre != '') {
        bloqueoBtn(boton,1);
        $.post("../funciones/editaIngrediente.php",
      {idIngrediente:id, nombre:nombre, desc:desc},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        listaIngredientes();
        bloqueoBtn(boton,2);
        notificaSuc(resp[1]);
        $("#modalEditIngrediente").modal('hide');
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
      var nombre = $("#nomIngAct-"+id).html();
      var desc = $("#descIngAct-"+id).html();
      $("#lblEditIngrediente").html('Editar '+nombre);
      $("#eNombreIngrediente").val(nombre);
      $("#eDescIngrediente").val(desc);
      $("#idIngrediente").val(id);
    }
  }


  function limpiaCadena(dat,id){
      //alert(id);
      dat=getCadenaLimpia(dat);
    $("#"+id).val(dat);
  }



    function cambiaEstatus(id,estatus){
      if (id > 0 && estatus > 0) {
        $.post("../funciones/cambiaEstatusIngrediente.php",
      {idIngrediente:id, estatus:estatus},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        notificaSuc(resp[1]);
        if (estatus == 1) {
          $("#estatusIngAct-"+id).html(resp[3]);
          $("#btnCambiaEstatus-"+id).html(resp[2]);
        } else {
          $("#estatusIngAct-"+id).html(resp[3]);
          $("#btnCambiaEstatus-"+id).html(resp[2]);
        }
      } else {
        notificaBad(resp[0]);
      }
    });
      } else {
      notificaBad('No se reconoció el Ingrediente, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
      }
    }

    function listaIngredientes(){
    //  var mensaje = 'Mensaje';
      $.post("../funciones/listarIngredientes.php",
    {},
  function(respuesta){
    $("#validation").html(respuesta);
  });
    }
    </script>

</body>

</html>
