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
                    <div class="card-header bg-<?=$pyme;?>">
                      <h4 class="m-b-0 text-white">Listado de Usuarios</h4>
                    </div>
                    <br>

                    <div class="card-body">
                      <div class="text-right">
                        <button class="btn btn-outline-<?=$pyme;?> btn-rounded" onclick="mandaCapturaEdicion(1,0);" data-toggle="modal" data-target="#modalNewUsuario"><i class="fas fa-plus"></i> Nuevo Usuario</button>
                      </div>
                      <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
                        <div class="table-responsive">
                          <table class="table product-overview" id="zero_config">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Dirección</th>
                                <th>Nivel</th>
                                <th>Sucursal</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-right">Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php

                                $sql = "SELECT usu.*, CONCAT(usu.appat,' ',usu.apmat) AS apellidosUsuario, scs.nameFact AS nomSucursal, lvl.nombre AS nomNivel
                                        FROM segusuarios usu
                                        INNER JOIN sucursales scs ON usu.idSucursal = scs.id
                                        INNER JOIN segniveles lvl ON usu.idNivel = lvl.id ORDER BY usu.estatus ASC,usu.nombre ASC";
                                $res = mysqli_query($link,$sql) or die('Problemas al consutlar los Clientes, notifica a tu Administrador');
                                while ($usu = mysqli_fetch_array($res)) {
                                  $id = $usu['id'];
                                  $iconEstatus = ($usu['estatus'] == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>' ;
                                  $btnCambiaEstatus = ($usu['estatus'] == 1) ? '<a id="btnEstatus-'.$id.'"><button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatus('.$id.', '.$usu['estatus'].')"><i class="fas fa-trash"></i></button></a>' : '<a id="btnEstatus-'.$id.'"><button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatus('.$id.', '.$usu['estatus'].')"><i class="fas fa-exchange-alt"></i></button></a>' ;
                                  echo '<tr>
                                          <td class="text-center">'.$id.'</td>
                                          <td id="nomUsu-'.$id.'">'.$usu['nombre'].'</td>
                                          <td id="apeUsu-'.$id.'">
                                            '.$usu['apellidosUsuario'].'
                                          </td>
                                          <td id="dirUsu-'.$id.'">'.$usu['direccion'].'</td>
                                          <td>'.$usu['nomNivel'].'</td>
                                          <td>'.$usu['nomSucursal'].'</td>
                                          <td id="nickUsu-'.$id.'" class="text-center">'.$usu['usuario'].'</td>
                                          <td class="text-center" id="iconoEstatus-'.$id.'">'.$iconEstatus.'</td>
                                          <td class="text-right">
                                            <button class="btn btn-outline-info" onclick="editaUsuario('.$id.');" data-toggle="modal" data-target="#modalEditUsuario"><i class="fas fa-pencil-alt"></i></button>
                                            '.$btnCambiaEstatus.'
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
                  <div id="modalNewUsuario" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title" id="lblNewUsuario">Nuevo Usuario</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                              </div>
                              <div class="modal-body">
                                <form role="form" method="post" action="../funciones/registraNuevoUsuario.php" id="formNewUsuario">

                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newNombre1"><i class="fas fa-user"></i></span>
                                      </div>
                                      <input type="text" class="form-control" id="newNombre" placeholder="Ingresa el Nombre" aria-describedby="nombre" name="newNombre" oninput="limpiaCadena(this.value,'newNombre');" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newApPat1">A.P.</span>
                                      </div>
                                      <input type="text" class="form-control" id="newApPat" placeholder="Ingresa el Apellido Paterno" aria-describedby="apPaterno" name="newApPat" oninput="limpiaCadena(this.value,'newApPat');" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newApMat1">A.M.</span>
                                      </div>
                                      <input type="text" class="form-control" id="newApMat" placeholder="Ingresa el Apellido Materno" aria-describedby="apMaterno" name="newApMat" oninput="limpiaCadena(this.value,'newApMat');" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newDireccion1"><i class="fas fa-map-marker-alt"></i></span>
                                      </div>
                                      <input type="text" class="form-control" id="newDireccion" placeholder="Ingresa su Dirección" aria-describedby="direcion" name="newDireccion" oninput="limpiaCadena(this.value,'newDireccion');" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newNivel1"><i class="fas fa-key"></i></span>
                                      </div>
                                      <select class="form-control" id="newNivel" name="newNivel" required>
                                        <option value="">Selecciona un Nivel de Acceso</option>
                                        <?php
                                            $sqlLvl = "SELECT * FROM segniveles WHERE id != '7'  ORDER BY orden ASC";
                                            $resLvl = mysqli_query($link,$sqlLvl) or die('Problemas al consultar los niveles, notifica a tu Administrador');
                                            while ($lvl = mysqli_fetch_array($resLvl)) {
                                              $disabled = ($lvl['estatus'] == 1) ? '' : 'disabled' ;
                                              echo '<option value="'.$lvl['id'].'" '.$disabled.'>'.$lvl['nombre'].'</option>';
                                            }
                                         ?>
                                      </select>
                                  </div>
                                  <br>

                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newSucursal1"><i class="fas fa-building"></i></span>
                                      </div>
                                      <select class="form-control" id="newSucursal" name="newSucursal" required>
                                        <option value="">Selecciona la Sucursal en donde estará</option>
                                        <?php
                                            $sqlSuc = "SELECT * FROM sucursales  ORDER BY nameFact ASC";
                                            $resSuc = mysqli_query($link,$sqlSuc) or die('Problemas al consultar los niveles, notifica a tu Administrador');
                                            while ($suc = mysqli_fetch_array($resSuc)) {
                                              $disabled = ($suc['estatus'] == 1) ? '' : 'disabled' ;
                                              echo '<option value="'.$suc['id'].'" '.$disabled.'>'.$suc['nameFact'].'</option>';
                                            }
                                         ?>
                                      </select>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newUsuario1"><i class="fas fa-user-circle"></i></span>
                                      </div>
                                      <input type="text" class="form-control" id="newUsuario" placeholder="Ingresa el Nombre con el que Iniciará Sesión" aria-describedby="usuario" name="newUsuario" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newContraseña1"><i class="mdi mdi-key"></i></span>
                                      </div>
                                      <input type="password" class="form-control" id="newContraseña" placeholder="Ingresa la Contraseña" aria-describedby="contraseña" name="newContraseña" required>
                                  </div>
                                  <br>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="newContraseña21"><i class="mdi mdi-key-variant"></i></span>
                                      </div>
                                      <input type="password" class="form-control" id="newContraseña2" placeholder="Repite la Contraseña" aria-describedby="contraseña2" name="newContraseña2" required>
                                  </div>

                                      <div class="modal-footer">
                                          <input type="hidden" name="vista" value="2">
                                          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                          <button type="submit" class="btn btn-success waves-effect waves-light">Registrar</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- /.modal -->

                  <!-- sample modal content -->
                  <div id="modalEditUsuario" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title" id="lblEditUsuario">Edita Usuario</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                              </div>
                              <div class="modal-body" id="editaUsuarioBody">

                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- /.modal -->

                <!-- ============================================================== -->
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

      if (isset( $_SESSION['LZFmsjCatalogoUsuarios'])) {
				echo "notificaBad('".$_SESSION['LZFmsjCatalogoUsuarios']."');";
				unset($_SESSION['LZFmsjCatalogoUsuarios']);
			}
			if (isset( $_SESSION['LZFmsjSuccessCatalogoUsuarios'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessCatalogoUsuarios']."');";
        unset($_SESSION['LZFmsjSuccessCatalogoUsuarios']);
      }
      ?>
    });

    function cambiaEstatus(idUsuario,estatus){
      if (idUsuario > 0) {
        $.post("../funciones/cambiaEstatusUsuario.php",
      {idUsuario:idUsuario, estatus:estatus},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        notificaSuc(resp[1]);
        $("#btnEstatus-"+idUsuario).html(resp[2]);
        $("#iconoEstatus-"+idUsuario).html(resp[4]);
      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció el Usuario, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
      }
    }

    function editaUsuario(idUsuario){
      if (idUsuario > 0) {
        var vista = 2;
        var nombre = $("#nomUsu-"+idUsuario).html();
        var apellidos = $("#apeUsu-"+idUsuario).html();
        $.post("../funciones/formEditaUsuario.php",
      {idUsuario:idUsuario, vista:vista},
    function(respuesta){
      $("#lblEditUsuario").html('Editar a '+nombre+' '+apellidos);
      $("#editaUsuarioBody").html(respuesta);
    });
      } else {
        notificaBad('No se reconoció el Usuario, actualiza e intenta de nuevo, si persiste el problema notifica a tu Administrador');
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
