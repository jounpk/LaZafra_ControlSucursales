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

                <div class="card border-<?=$pyme;?>">
                  <?php
                  if (!empty($_POST['estatusBanco'])) {
                    $filtroEstatus = $_POST['estatusBanco'];
                  } else {
                    $filtroEstatus = 1;
                  }

                    if ($filtroEstatus == 2) {
                      $sel1 = '';
                      $sel2 = 'selected';
                    } else {
                      $sel1 = 'selected';
                      $sel2 = '';
                    }
                   ?>
                   <style>
                   label.alinearCentro{
                     display: inline-block;
                      text-align: center;
                      vertical-align:middle;
                      line-height: 150%;
                      padding-top: 15%;
                   }
                   </style>
                    <div class="card-header bg-<?=$pyme;?>">
                      <h4 class="m-b-0 text-white">Listado de Bancos</h4>
                    </div>
                    <br>
                    <div class="ml-auto">
                      <div class="input-group">
                        <div class="text-center">
                          <label class="control-label text-<?=$pyme;?> alinearCentro">Mostrando:&nbsp;&nbsp;</label>
                        </div>
                            <select class="custom-select" id="selectEstatus">
                                <option value="1" <?=$sel1;?>>Activos</option>
                                <option value="2" <?=$sel2;?>>Inactivos</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-<?=$pyme;?>" onclick="listaBancos(3);" type="button"><i class="fas fa-search"></i></button>
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
                                <th class="text-center">#</th>
                                <th>Nombre</th>
                                <th>Razón Social</th>
                                <th class="text-center">Comisión</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php

                              $sqlBancos = "SELECT * FROM catbancos WHERE estatus = '$filtroEstatus' ORDER BY nombreCorto ASC";
                              $resBancos = mysqli_query($link,$sqlBancos) or die('Problemas al listar los Departamentos, notifica a tu Administrador');
                              #echo $sqlBancos;
                              while ($bnk = mysqli_fetch_array($resBancos)) {
                                $id = $bnk['id'];
                                $estadoBnk = ($bnk['estatus'] == 1) ? 1 : 2 ;
                                $iEstat = '<input type="hidden" id="estBnk'.$id.'" value="'.$estadoBnk.'">';
                                $estatus = ($estadoBnk == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>'.$iEstat : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>'.$iEstat ;
                                $boton = ($estadoBnk == 1) ? '<a id="btnCambiaEstatus-'.$id.'"><button title="Desactivar" class="btn btn-outline-danger" onclick="cambiaEstatus(\''.$id.'\');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button></a>' : '<a id="btnCambiaEstatus-'.$id.'"><button title="Activar" class="btn btn-outline-warning" onclick="cambiaEstatus(\''.$id.'\');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button></a>' ;
                                $cCheque = ($bnk['comisionCheque'] > 0) ? $bnk['comisionCheque'] : 0 ;
                                $cTarjeta = ($bnk['comisionTarjeta'] > 0) ? $bnk['comisionTarjeta'] : 0 ;
                                echo '<tr>
                                        <td class="text-center">'.$bnk['id'].'</td>
                                        <td id="nombreBnk-'.$id.'">'.$bnk['nombreCorto'].'</td>
                                        <td>'.$bnk['razonSocial'].'</td>
                                        <td class="text-center"><b id="cheque-'.$id.'"> '.$cCheque.'</b> % &nbsp;&nbsp;<a class="text-dark" title="Cheque"><i class="far fa-money-bill-alt"></i></a><br><b id="tarjeta-'.$id.'"> '.$cTarjeta.'</b> % &nbsp;&nbsp;<a class="text-info" title="Tarjeta de Crédito"><i class="fas fa-credit-card"></i></a></td>
                                        <td id="estatusBnk-'.$id.'" class="text-center">'.$estatus.'</td>
                                        <td class="text-center">
                                          <button class="btn btn-outline-info" data-toggle="modal" data-target="#modalEditBanco" onclick="mandaModal('.$id.');"><i class="fas fa-pencil-alt"></i></button>
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

                <!-- sample modal content -->
                <div id="modalEditBanco" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="lblEditBanco">Editar Comisión</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                              <form role="form" id="formEditaBanco">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="cheque1"><i class="far fa-envelope"></i></span>
                                        </div>
                                        <input type="number" min="0" max="100" step=".01" placeholder="Comisión de Cheque" class="form-control" id="cheque" aria-describedby="nombre" name="cheque" required>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="tarjeta1"><i class="far fa-envelope"></i></span>
                                        </div>
                                        <input type="number" min="0" max="100" step=".01" placeholder="Comisión de Tarjeta" class="form-control" id="tarjeta" aria-describedby="nombre" name="tarjeta" required>
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
                                        <input type="hidden" id="idBanco">
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="mandaEdición('bloquear-btn');">Editar</button>
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
    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjCatalogoBancos'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCatalogoBancos']."');";
				unset($_SESSION['LZFmsjCatalogoBancos']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCatalogoBancos'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCatalogoBancos']."');";
        unset($_SESSION['LZFmsjSuccessCatalogoBancos']);
			}
      ?>
    });

    function cambiaEstatus(id){

      if (id > 0) {
        var estatus = $("#estBnk"+id).val();
        //alert('id: '+id+', estatus: '+estatus);
        $.post("../funciones/cambiaEstatusBanco.php",
      {idBanco:id, estatus:estatus},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        notificaSuc(resp[1]);
        $("#estatusBnk-"+id).html(resp[3]);
        $("#btnCambiaEstatus-"+id).html(resp[2]);

      } else {
        notificaBad(resp[0]);
      }
    });
      } else {
      notificaBad('No se reconoció el Banco, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
      }
    }

    function mandaModal(id){

      if (id > 0) {
      var nombre = $("#nombreBnk-"+id).html();
      var cheque = $("#cheque-"+id).html();
      var tarjeta = $("#tarjeta-"+id).html();
      $("#lblEditBanco").html('Editar comisiones de '+nombre);
      $("#cheque").val(cheque);
      $("#tarjeta").val(tarjeta);
      $("#idBanco").val(id);
    }
  }

  function mandaEdición(boton){
    bloqueoBtn(boton,2);
    var cheque = $("#cheque").val();
    var tarjeta = $("#tarjeta").val();
    var id = $("#idBanco").val();
    var estatus = $("#estBnk-"+id).val();
      $.post("../funciones/editaBanco.php",
    {idBanco:id, cheque:cheque, tarjeta:tarjeta},
  function(respuesta){
    var resp = respuesta.split('|');
    if (resp[0] == 1) {
      listaBancos(1);
      notificaSuc(resp[1]);
      $("#modalEditBanco").modal('hide');
    } else {
    notificaBad(resp[1]);
    }
  });

  }

  function listaBancos(estatus){
    if (estatus == 3) {
      var estatus = $("#selectEstatus option:selected").val();
    }
    //alert("estatus: "+estatus);
      $('<form action="#" method="POST"><input type="hidden" name="estatusBanco" value="'+estatus+'"></form>').appendTo('body').submit();
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
