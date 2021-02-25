<?php
require_once 'seg.php';
$info = new Seguridad();
require_once('../include/connect.php');
#require_once('secciones.php');
$cad = explode('/', $_SERVER["REQUEST_URI"]);
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad-1];
#echo 'uri: '.$_SERVER["REQUEST_URI"].'<br>';
#echo 'cantReg: '.$cantCad.'<br>';
#echo 'link: '.$nameLk.'<br>';
$info->Acceso($nameLk);
$pyme = $_SESSION['LZFpyme'];
$idSucursal = $_SESSION['LZFidSuc'];
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
    <link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
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
                <?php
        # si tipo es 1, significa que es un ingreso Nuevo
        # si tipo es 2, significa que es una edición y va amarrado con el id
              $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] :  1 ;
              $id = (!empty($_POST['id'])) ? $_POST['id'] :  0 ;
              if ($tipo == 1) {
                $style1 = '';
                $style2 = 'style="display:none;"';
              } else {
                $style2 = '';
                $style1 = 'style="display:none;"';
              }
            #  echo '$tipo: '.$tipo.' $id: '.$id.'<br>';
                ?>
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
                if ($tipo == '1') {
                 ?>
                <div class="row" <?=$style1;?>>
                <div class="col-lg-12">
                    <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="m-b-0 text-white">Registro de Clientes</h4>
                        </div>
                        <br>
                        <div class="card-body">
                            <form action="../funciones/registraNuevoCliente.php" method="post" id="formCreaCliente" role="form">
                                <div class="form-body">
                                  <div class="row">
                                     <div class="col-md-12 text-info"><center><h4><b>Datos Personales del Cliente</b></h4></center></div>
                                  </div>
                                  <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-plus"></i></span>
                                            </div>
                                                <input type="text" id="newNombre" name="newNombre" class="form-control" placeholder="Ingresa el nombre del Cliente" required>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-face"></i></span>
                                            </div>
                                                <input type="text" id="newApodo" name="newApodo" class="form-control" placeholder="Ingresa el apodo del Cliente" required>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-city"></i></span>
                                            </div>
                                              <select class="select2 form-control custom-select" name="newEstado" id="newEstado" onchange="muestraMunicipio(this.value,1)" style="width: 93%; height:100%;" required>
                                                <option value="">Selecciona un Estado</option>
                                                <?php
                                                  $sqlEdo = "SELECT * FROM catestados ORDER BY nombre";
                                                  $resEdo = mysqli_query($link,$sqlEdo) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                  while ($edo = mysqli_fetch_array($resEdo)) {
                                                    echo '<option value="'.$edo['id'].'">'.$edo['nombre'].'</option>';
                                                  }
                                                 ?>
                                              </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-domain"></i></span>
                                            </div>
                                              <select class="select2 form-control custom-select" name="newMpio" id="newMpio" style="width: 93%; height:100%;" required>
                                                <option value="">Selecciona primero un Estado</option>
                                              </select>
                                          </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-map-marker-radius"></i></span>
                                            </div>
                                                <textarea class="form-control" id="newDireccion" name="newDireccion" placeholder="Ingresa la dirección del Cliente" required></textarea>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-deskphone"></i></span>
                                            </div>
                                              <input type="text" class="form-control phone-inputmask" id="newTelefono" placeholder="Ingresa su teléfono" aria-describedby="Telefono" name="newTelefono">
                                          </div>
                                        </div>
                                        <!--/span-->
                                    </div>


                                      <?=$info->validaSeccion('limitCredito');?>

             <!-- ################################################################################################################################################################################################################################## -->
                                       <br>
                                       <div class="row">
                                          <div class="col-md-1 text-info text-center">
                                            <h6><b>¿Agregar Datos Fiscales?</b></h6>
                                          </div>
                                          <div class="col-md-11 bt-switch">
                                            <div class="m-b-30">
                                                <input type="checkbox" data-on-color="success" onchange="muestraDatosFisc(1);" name="switchDatosFisc" data-off-color="danger" data-on-text="Yes" data-off-text="No">
                                            </div>
                                          </div>
                                       </div>
                                     <div id="datosFiscales" style="display: none;">
                                       <div class="row">
                                          <div class="col-md-12 text-info"><center><h4><b>Datos Fiscales (Facturación)</b></h4></center></div>
                                       </div>
                                       <br>
                                       <!--/row-->
                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-key"></i></span>
                                               </div>
                                                 <input type="text" class="form-control" id="newRFC" placeholder="Ingresa su RFC" aria-describedby="rfc" min="12" maxlength="13" onkeyup="cambiaMayusculas(this.value,'newRFC');" name="newRFC">
                                             </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-key"></i></span>
                                               </div>
                                                <input type="text" class="form-control" id="razonSoc" placeholder="Ingresa su Razón Social" name="newRazonSoc">

                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>

                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-contact-mail"></i></span>

                                               </div>
                                                 <input type="text" class="form-control email-inputmask" id="newCorreo" placeholder="Ingresa el Correo" aria-describedby="Correo" name="newCorreo">
                                             </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-contact-mail"></i></span>
                                               </div>
                                                 <input type="text" class="form-control email-inputmask" id="newCorreo2" placeholder="Ingresa Segundo correo (Opcional)" aria-describedby="Correo2" name="newCorreo2">
                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-city"></i></span>
                                               </div>
                                                 <select class="select2 form-control custom-select" name="newEstadoFisc" id="newEstadoFisc" onchange="muestraMunicipio(this.value,3)" style="width: 93%; height:100%;">
                                                   <option value="">Selecciona un Estado</option>
                                                   <?php
                                                     $sqlEdo2 = "SELECT * FROM catestados ORDER BY nombre";
                                                     $resEdo2 = mysqli_query($link,$sqlEdo2) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                     while ($edo2 = mysqli_fetch_array($resEdo2)) {
                                                       echo '<option value="'.$edo2['id'].'">'.$edo2['nombre'].'</option>';
                                                     }
                                                    ?>
                                                 </select>
                                               </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-domain"></i></span>
                                               </div>
                                                 <select class="select2 form-control custom-select" name="newMpioFisc" id="newMpioFisc" style="width: 93%; height:100%;">
                                                   <option value="">Selecciona primero un Estado</option>
                                                 </select>
                                             </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-map-marker-radius"></i></span>

                                               </div>
                                                 <input type="text" class="form-control" id="newDireccionFisc" placeholder="Ingresa su Dirección Fiscal" name="newDireccionFisc">
                                             </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1">C.P.</span>
                                               </div>
                                                 <input type="number" class="form-control" id="newCP" maxlength="5" placeholder="Ingresa el Código Postal" aria-describedby="Código Postal" name="newCP">
                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                       <div class="row">
                                         <div class="col-md-3">
                                           <div class="input-group mb-3">
                                             <div class="input-group-prepend">
                                               <span class="input-group-text" id="basic-addon1"># Ext.</span>

                                             </div>
                                               <input type="text" class="form-control" id="newNoExterior" placeholder="No. Exterior" aria-describedby="No. Exterior" name="newNoExterior">
                                           </div>
                                         </div>
                                         <div class="col-md-3">
                                           <div class="input-group mb-3">
                                             <div class="input-group-prepend">
                                               <span class="input-group-text" id="basic-addon1"># Int.</span>

                                             </div>
                                               <input type="text" class="form-control" id="newNoInterior" placeholder="No. Interior" aria-describedby="No. Interior" name="newNoInterior">
                                           </div>
                                         </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-map-marker"></i></span>
                                               </div>
                                                 <input type="text" class="form-control" id="newColonia" placeholder="Ingresa la Colonia o Delegación" aria-describedby="Colonia" name="newColonia">
                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>

                                       <div class="row">
                                           <div class="col-md-12">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1">CFDI</span>

                                               </div>
                                               <select class="select2 form-control custom-select" name="newUsoCfdi" id="newUsoCfdi" style="width: 95%; height:100%;">
                                                 <option value="">Selecciona una Opción</option>
                                                 <?php
                                                   $sqlCfdi = "SELECT * FROM sat_usocfdi WHERE estatus = '1' ORDER BY id";
                                                   $resCfdi = mysqli_query($link,$sqlCfdi) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                   while ($cfdi = mysqli_fetch_array($resCfdi)) {
                                                     echo '<option value="'.$cfdi['id'].'">'.$cfdi['id'].' - '.$cfdi['descripcion'].'</option>';
                                                   }
                                                  ?>
                                               </select>
                                             </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                     </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="variable" id="variable" value="1">
                                    <a href="catalogoClientes.php"><button type="button" class="btn btn-danger">Cancelar</button></a>
                                    <button type="button" class="btn btn-info" onclick="registraYvuelve();"> <i class="fa fa-check"></i> Registrar y Cargar otro</button>
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Registrar</button>
                                </div>
                            </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- ##########################################################################  -->
                <?php
              } else {
                $sqlCon = "SELECT * FROM clientes WHERE id = '$id'";
                $resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar al cliente, notifica a tu Administrador');
                while ($list = mysqli_fetch_array($resCon)) {
                  $id = $list['id'];
                  $nombre = $list['nombre'];
                  $apodo = $list['apodo'];
                  $estado = $list['estado'];
                  $municipio = $list['municipio'];
                  $direccion = $list['direccion'];
                  $telefono = $list['telefono'];
                  $rfc = $list['rfc'];
                  $correo = $list['correo'];
                  $credito = $list['credito'];
                  $limiteCredito = $list['limiteCredito'];
                  $_SESSION['idCliente'] = $id;
                }

                }
                 ?>
                <div class="row" <?=$style2;?>>
                <div class="col-lg-12">
                    <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="m-b-0 text-white">Edición de Clientes</h4>
                        </div>
                        <br>
                        <div class="card-body">
                            <form action="../funciones/editaCliente.php" method="post" role="form">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-plus"></i></span>
                                            </div>
                                                <input type="text" id="eNombre" name="eNombre" class="form-control" placeholder="Ingresa el nombre del Cliente" value="<?=$nombre;?>" required>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-face"></i></span>
                                            </div>
                                                <input type="text" id="eApodo" name="eApodo" class="form-control" placeholder="Ingresa el apodo del Cliente" value="<?=$apodo;?>" required>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-city"></i></span>
                                            </div>
                                                <select class="select2 form-control custom-select" name="eEstado" id="eEstado" onchange="muestraMunicipio(this.value,2)" style="width: 93%; height:100%;" required>
                                                  <option value="">Selecciona un Estado</option>
                                                  <?php
                                                    $sqlEdo = "SELECT * FROM catestados ORDER BY nombre";
                                                    $resEdo = mysqli_query($link,$sqlEdo) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                    while ($edo = mysqli_fetch_array($resEdo)) {
                                                      $activa = ($estado == $edo['id']) ? 'selected' : '' ;
                                                      echo '<option value="'.$edo['id'].'" '.$activa.'>'.$edo['nombre'].'</option>';
                                                    }
                                                   ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-domain"></i></span>
                                            </div>
                                              <select class="select2 form-control custom-select" name="eMpio" id="eMpio" style="width: 93%; height:100%;" required>
                                                <?php
                                                echo '<option value="">Selecciona un Municipio </option>';
                                                  $sqlMpio = "SELECT * FROM catmunicipios
                                                              WHERE idCatEstado = '$estado'
                                                              ORDER BY nombre ";
                                                  $resMpio = mysqli_query($link,$sqlMpio) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                  while ($mpio = mysqli_fetch_array($resMpio)) {
                                                    $activa2 = ($municipio == $mpio['id']) ? 'selected' : '' ;
                                                    echo '<option value="'.$mpio['id'].'" '.$activa2.'>'.$mpio['nombre'].'</option>';
                                                  }
                                                 ?>
                                              </select>
                                          </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-map-marker-radius"></i></span>
                                            </div>
                                                <textarea class="form-control" id="eDireccion" name="eDireccion" placeholder="Ingresa la dirección del Cliente" value="<?=$direccion;?>" required><?=$direccion;?></textarea>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-deskphone"></i></span>
                                            </div>
                                              <input type="text" class="form-control phone-inputmask" id="eTelefono" placeholder="Ingresa su teléfono" aria-describedby="Telefono" value="<?=$telefono;?>" name="eTelefono">
                                          </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->

                                    <!-- ################################################################################################################################################################################################################################## -->
                                      <?=$info->validaSeccion('editLlimitCredito');?>


                                      <?php
                                    $sqlConDatosFisc = "SELECT df.* FROM datosfisc df
                                                        LEFT JOIN sucursales scs ON df.idEmpresa = scs.idEmpresa
                                                        WHERE df.idCliente = '$id' AND scs.id = '$idSucursal' AND df.estatus = '1' LIMIT 1";
                                    $resConDatosFisc = mysqli_query($link,$sqlConDatosFisc) or die('Problemas al consultar los datos fiscales del cliente, notifica a tu Administrador.');
                                    $rows = mysqli_fetch_array($resConDatosFisc);
                                    $edRfc = $rows['rfc'];
                                    $edRazonSoc = $rows['razonSocial'];
                                    $edCfdi = $rows['usoCfdi'];
                                    $edCalleFisc = $rows['calle'];
                                    $edNoExt = $rows['noExt'];
                                    $edNoInt = $rows['noInt'];
                                    $edColonia = $rows['colonia'];
                                    $edCodPostal = $rows['codigoPostal'];
                                    $edMpioFisc = $rows['municipio'];
                                    $edEdoFisc = $rows['estado'];
                                    $edCorreo = $rows['correo'];
                                    $edCorreo2 = $rows['correo2'];
                                    $edNombre = $rows['nombre'];
                                    $edTelFisc = $rows['tel'];

                                       ?>
                                       <!-- ################################################################################################################################################################################################################################## -->
                                       <br>
                                       <div class="row">
                                          <div class="col-md-1 text-info text-center">
                                            <h6><b>¿Agregar Datos Fiscales?</b></h6>
                                          </div>
                                          <div class="col-md-11 bt-switch2">
                                            <div class="m-b-30">
                                                <input type="checkbox" data-on-color="success" onchange="muestraDatosFisc(2);" name="switchDatosFisc2" data-off-color="danger" data-on-text="Yes" data-off-text="No">
                                            </div>
                                          </div>
                                       </div>
                                     <div id="editaDatosFiscales" style="display: none;">
                                       <div class="row">
                                          <div class="col-md-12 text-info"><center><h4><b>Datos Fiscales (Facturación)</b></h4></center></div>
                                       </div>
                                       <br>
                                       <!--/row-->
                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-key"></i></span>
                                               </div>
                                                 <input type="text" class="form-control" id="edRFC" value="<?=$edRfc;?>" placeholder="Ingresa su RFC" aria-describedby="rfc" min="12" maxlength="13" onkeyup="cambiaMayusculas(this.value,'edRFC');" name="edRFC">
                                             </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-account-key"></i></span>
                                               </div>
                                                <input type="text" class="form-control" id="razonSoc" value="<?=$edRazonSoc;?>" placeholder="Ingresa su Razón Social" name="edRazonSoc">

                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>

                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-contact-mail"></i></span>

                                               </div>
                                                 <input type="text" class="form-control email-inputmask" value="<?=$edCorreo;?>" id="edCorreo" placeholder="Ingresa el Correo" aria-describedby="Correo" name="edCorreo">
                                             </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-contact-mail"></i></span>
                                               </div>
                                                 <input type="text" class="form-control email-inputmask" value="<?=$edCorreo2;?>" id="edCorreo2" placeholder="Ingresa Segundo correo (Opcional)" aria-describedby="Correo2" name="edCorreo2">
                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-city"></i></span>
                                               </div>
                                                 <select class="select2 form-control custom-select" name="edEstadoFisc" id="edEstadoFisc" onchange="muestraMunicipio(this.value,3)" style="width: 93%; height:100%;">
                                                   <option value="">Selecciona un Estado</option>
                                                   <?php
                                                     $sqlEdo2 = "SELECT * FROM catestados ORDER BY nombre";
                                                     $resEdo2 = mysqli_query($link,$sqlEdo2) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                     while ($edo2 = mysqli_fetch_array($resEdo2)) {
                                                       $act = ($edo2['id'] == $edEdoFisc) ? 'selected' : '' ;
                                                       echo '<option value="'.$edo2['id'].'" '.$act.'>'.$edo2['nombre'].'</option>';
                                                     }
                                                    ?>
                                                 </select>
                                               </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-domain"></i></span>
                                               </div>
                                                 <select class="select2 form-control custom-select" name="edMpioFisc" id="edMpioFisc" style="width: 93%; height:100%;">
                                                   <?php
                                                   echo '<option value="">Selecciona un Municipio </option>';
                                                     $sqlMpio = "SELECT * FROM catmunicipios
                                                                 WHERE idCatEstado = '$estado'
                                                                 ORDER BY nombre ";
                                                     $resMpio = mysqli_query($link,$sqlMpio) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                     while ($mpio = mysqli_fetch_array($resMpio)) {
                                                       $activa2 = ($edMpioFisc == $mpio['id']) ? 'selected' : '' ;
                                                       echo '<option value="'.$mpio['id'].'" '.$activa2.'>'.$mpio['nombre'].'</option>';
                                                     }
                                                    ?>
                                                 </select>
                                             </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                       <div class="row">
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-map-marker-radius"></i></span>

                                               </div>
                                                 <input type="text" class="form-control" id="edDireccionFisc" value="<?=$edCalleFisc;?>" placeholder="Ingresa su Dirección Fiscal" name="edDireccionFisc">
                                             </div>
                                           </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1">C.P.</span>
                                               </div>
                                                 <input type="number" class="form-control" id="edCP" maxlength="5" value="<?=$edCodPostal;?>" placeholder="Ingresa el Código Postal" aria-describedby="Código Postal" name="edCP">
                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                       <div class="row">
                                         <div class="col-md-3">
                                           <div class="input-group mb-3">
                                             <div class="input-group-prepend">
                                               <span class="input-group-text" id="basic-addon1"># Ext.</span>

                                             </div>
                                               <input type="text" class="form-control" id="edNoExterior" placeholder="No. Exterior" value="<?=$edNoExt;?>" aria-describedby="No. Exterior" name="edNoExterior">
                                           </div>
                                         </div>
                                         <div class="col-md-3">
                                           <div class="input-group mb-3">
                                             <div class="input-group-prepend">
                                               <span class="input-group-text" id="basic-addon1"># Int.</span>

                                             </div>
                                               <input type="text" class="form-control" id="edNoInterior" placeholder="No. Interior" value="<?=$edNoInt;?>" aria-describedby="No. Interior" name="edNoInterior">
                                           </div>
                                         </div>
                                           <!--/span-->
                                           <div class="col-md-6">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-map-marker"></i></span>
                                               </div>
                                                 <input type="text" class="form-control" id="edColonia" placeholder="Ingresa la Colonia o Delegación" value="<?=$edColonia;?>" aria-describedby="Colonia" name="edColonia">
                                               </div>
                                           </div>
                                           <!--/span-->
                                       </div>

                                       <div class="row">
                                           <div class="col-md-12">
                                             <div class="input-group mb-3">
                                               <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1">CFDI</span>

                                               </div>
                                               <select class="select2 form-control custom-select" name="edUsoCfdi" id="edUsoCfdi" style="width: 95%; height:100%;">
                                                 <option value="">Selecciona una Opción</option>
                                                 <?php
                                                   $sqlCfdi = "SELECT * FROM sat_usocfdi WHERE estatus = '1' ORDER BY id";
                                                   $resCfdi = mysqli_query($link,$sqlCfdi) or die('Problemas al listar los Estados, notifica a tu Administrador');
                                                   while ($cfdi = mysqli_fetch_array($resCfdi)) {
                                                     $act2 = ($edCfdi == $cfdi['id']) ? 'selected' : '' ;
                                                     echo '<option value="'.$cfdi['id'].'" '.$act2.'>'.$cfdi['id'].' - '.$cfdi['descripcion'].'</option>';
                                                   }
                                                  ?>
                                               </select>
                                             </div>
                                           </div>
                                           <!--/span-->
                                       </div>
                                     </div>
                                </div>
                                <div class="modal-footer">
                                  <input type="hidden" name="ident" value="<?=$id;?>">
                                    <a href="catalogoClientes.php"><button type="button" class="btn btn-danger">Cancelar</button></a>
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Editar</button>
                                </div>
                            </form>
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
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
    <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);
      if (isset( $_SESSION['LZFmsjConfiguraClientes'])) {
				echo "notificaBad('".$_SESSION['LZFmsjConfiguraClientes']."');";
				unset($_SESSION['LZFmsjConfiguraClientes']);
			}
			if (isset( $_SESSION['LZFmsjSuccessConfiguraClientes'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessConfiguraClientes']."');";
        unset($_SESSION['LZFmsjSuccessConfiguraClientes']);
      }
      ?>
      radioswitch.init()
    });

    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
    $(".bt-switch2 input[type='checkbox'], .bt-switch2 input[type='radio']").bootstrapSwitch();
    var radioswitch = function() {
        var bt = function() {
            $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioState")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
            })
        };
        return {
            init: function() {
                bt()
            }
        }
    }();


    function muestraMunicipio(idEstado,no){
      if (idEstado > 0) {
        //alert("idEstado: "+idEstado);
        $.post("../funciones/listaMunicipios.php",
      {idEstado:idEstado},
    function(respuesta){
      switch (no) {
        case 1:
          $("#newMpio").html(respuesta);

          break;
        case 2:
          $("#eMpio").html(respuesta);
          break;
        case 3:
          $("#newMpioFisc").html(respuesta);
          break;
        case 4:
          $("#eMpioFisc").html(respuesta);
          break;

        default:

      }

    });
      }
    }

    function selCredito(opcion,no){
      if (no == 1) {
          $("#divNewLimite").toggle("fast");
      } else {
          $("#divEdLimite").toggle("fast");
      }

    }

    function registraYvuelve(){
      $("#variable").val('2');
      //alert('Entró');
      $("#formCreaCliente").submit();
    }

    function muestraDatosFisc(no){
    //  alert("entro, no: "+no);
      if (no == 1) {
        $("#datosFiscales").toggle("slow");
      } else {
        //alert("entro a editar.");
        $("#editaDatosFiscales").toggle("slow");
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
