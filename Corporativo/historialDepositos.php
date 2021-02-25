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
$idUser = $_SESSION['LZFident'];
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
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/extra-libs/prism/prism.css">
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
                .btn-circle-tablita {
                  width: 30px;
                  height: 30px;
                  text-align: center;
                  padding: 6px 0;
                  font-size: 12px;
                  line-height: 1.428571429;
                  border-radius: 15px;
                }
                </style>

              <div class="card border-<?=$pyme;?>" id="historialRecolecciones">
                <div class="card-header bg-<?=$pyme;?>">
                  <h4 class="text-white">Historial de Depósitos</h4>
                </div>
                <div class="card-body">
                  <?php
                      $fechaAct=date('Y-m-d');
                      if (isset($_REQUEST['fechaInicial'])) {
                        $fechaInicial = $_REQUEST['fechaInicial'];
                      }else {
                        $fechaInicial1 = strtotime ( '-1 week' , strtotime ( $fechaAct ) ) ;
                        $fechaInicial = date ( 'Y-m-d' , $fechaInicial1 );
                      }
                      if (isset($_REQUEST['fechaFinal'])) {
                        $fechaFinal = $_REQUEST['fechaFinal'];
                      }else {
                        $fechaFinal1 = strtotime ( '+15 day' , strtotime ( $fechaAct ) ) ;
                        $fechaFinal = date ( 'Y-m-d' , $fechaFinal1 );
                      }

                      if (isset($_REQUEST['recolector']) && $_REQUEST['recolector'] > 0) {
                        $recolector = $_REQUEST['recolector'];
                        $filtroRecolector = " AND u.id = '$recolector'";
                      } else {
                        $recolector = '';
                        $filtroRecolector = '';
                      }

                      if (isset($_REQUEST['estatus']) && $_REQUEST['estatus'] != '') {
                        $estatus = $_REQUEST['estatus'];
                        $filtroEstatus = " AND d.estatus = '$estatus'";
                      } else {
                        $estatus = '';
                        $filtroEstatus = ' AND d.estatus < 4';
                      }

                      $sel1 = $sel2 = $sel3 = $sel0 = '';
                      switch ($estatus) {
                        case '0':
                          $sel1 = '';
                          $sel2 = '';
                          $sel3 = '';
                          $sel0 = 'selected';
                          break;

                        case '1':
                          $sel1 = 'selected';
                          $sel2 = '';
                          $sel3 = '';
                          $sel0 = '';
                          break;

                        case '2':
                          $sel1 = '';
                          $sel2 = 'selected';
                          $sel3 = '';
                          $sel0 = '';
                          break;

                        case '3':
                          $sel1 = '';
                          $sel2 = '';
                          $sel3 = 'selected';
                          $sel0 = '';
                          break;

                      }
                   ?>
                   <div class="text-right">
                     <a href="depositos.php" class="btn btn-info text-white" title="Regresar a Depósitos"><i class="fas fa-reply"></i></a>
                   </div>
                     <div class="col-md-12">
                       <div class="row">
                         <div class="col-md-2"></div>
                         <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                           <div class="col-md-12">
                             <h5 class="m-t-10 text-center text-<?=$pyme;?>">Selecciona un rango de Fechas</h5>
                           </div>
                           <div class="col-md-12">
                             <form role="form" action="#historialRecolecciones" method="post">
                               <div class="input-daterange input-group" id="date-range">
                                   <input type="date" class="form-control" name="fechaInicial" value="<?=$fechaInicial;?>" />
                                   <div class="input-group-append">
                                       <span class="input-group-text bg-<?=$pyme;?> b-0 text-white"> A </span>
                                   </div>
                                   <input type="date" class="form-control" name="fechaFinal" value="<?=$fechaFinal;?>" />
                               </div>
                           </div>
                         </div>
                         <div class="col-md-5">
                           <div class="col-md-12">
                             <div class="row">
                               <div class="col-md-4">
                                 <h5 class="m-t-10 text-<?=$pyme;?>">Selecciona un Recolector:</h5>
                               </div>
                               <div class="col-md-4">
                                 <h5 class="m-t-10 text-<?=$pyme;?>">Selecciona un Estatus:</h5>
                               </div>
                               <div class="col-md-4">
                               </div>
                             </div>
                           </div>
                         <div class="col-md-12">
                           <div class="row">
                             <div class="col-md-4">
                               <select class="select2 form-control custom-select" name="recolector" id="recolector" style="width: 95%; height:100%;">
                                 <?php
                                 echo '<option value="">Ingresa el Nombre del Recolector</option>';
                                 $sql="SELECT id,CONCAT(nombre,' ',appat,' ',apmat) AS nombre FROM segusuarios WHERE idNivel = '6'";
                               #  echo $sql;
                                 $res=mysqli_query($link,$sql);
                                  while ($rows = mysqli_fetch_array($res)) {
                                    $activa = ($recolector == $rows['id']) ? 'selected' : '' ;
                                    echo '<option value="'.$rows['id'].'" '.$activa.'>'.$rows['nombre'].'</option>';
                                  }
                                  ?>
                               </select>
                             </div>
                             <div class="col-md-4">
                               <select class="select2 form-control custom-select" name="estatus" id="estatus" style="width: 95%; height:100%;">
                                 <option value="">Todos</option>
                                 <option value="0" <?=$sel0;?>>No depositados</option>
                                 <option value="2" <?=$sel2;?>>Por Autorizar</option>
                                 <option value="1" <?=$sel1;?>>Autorizado</option>
                                 <option value="3" <?=$sel3;?>>Rechazado</option>
                               </select>
                             </div>
                             <div class="col-md-4">
                               <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                             </div>
                           </div>
                         </div>
                       </div>

                       <div class="col-md-1"></div>
                     </div>
                   </div>
                   </form>
                   <br>

                  <!-- ######################################################################################################################### --->
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Sucursal</th>
                          <th class="text-center">Folio</th>
                          <th class="text-center">Fecha de Corte</th>
                          <th class="text-center">Fecha de Recolección</th>
                          <th class="text-center">Fecha de Autorización</th>
                          <th>Recolector</th>
                          <th>Autoriza</th>
                          <th>Motivo</th>
                          <th class="text-center">Monto</th>
                          <th class="text-center">Banco</th>
                          <th class="text-center">Cuenta</th>
                          <th class="text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $fecha= date('Y-m-d');
                        $sql = "SELECT d.*, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomRecolector,CONCAT(u2.nombre,' ',u2.appat,' ',u2.apmat) AS nomAutoriza, s.nombre AS nomSucursal,c.fechaCierre
                                FROM cortes c
                                INNER JOIN depositos d ON c.id = d.idCorte
                                INNER JOIN segusuarios u ON d.idUserReg = u.id
                                INNER JOIN sucursales s ON c.idSucursal = s.id
                                LEFT JOIN segusuarios u2 ON d.idUserAutoriza= u2.id
                                WHERE DATE_FORMAT(d.fechaReg,'%Y-%m-%d') BETWEEN '$fechaInicial' AND '$fechaFinal' $filtroRecolector $filtroEstatus
                                ORDER BY d.estatus, d.fechaReg,c.id ASC";
                        $res = mysqli_query($link,$sql) or die('Problemas al consultar los cortes pendientes, notifica a tu Administrador.');
                        $count = 0;
                        while ($row = mysqli_fetch_array($res)) {
                          $idDeposito = $row['id'];
                          $sqlCuentas = "SELECT bnk.nombreCorto AS nomBanco,cb.noCuenta
                                  FROM depositos dp
                                  INNER JOIN detdepositos dd ON dp.id = dd.idDepositoRecoleccion
                                  INNER JOIN catbancos bnk ON dd.idClaveBanco = bnk.id
                                  LEFT JOIN cuentasbancarias cb ON dd.idCuentaBancaria = cb.id
                                  WHERE dp.id = '$idDeposito'";
                          $resCuentas = mysqli_query($link,$sqlCuentas) or die('Problemas al consultar los cortes pendientes, notifica a tu Administrador.');
                          #echo '$sqlCuentas: '.$sqlCuentas;
                          $cuentas = $bancos = '';
                          $m = 0;
                          $no = mysqli_num_rows($resCuentas);
                          #echo '<br>$no: '.$no;
                          if ($no > 0) {
                            $bancos .= '<ul class="list-style-none">';
                            $cuentas .= '<ul class="list-style-none">';
                            while ($lst = mysqli_fetch_array($resCuentas)) {
                              $bancos .= '<li>'.$lst['nomBanco'].'</li>';
                              $cuentas .= '<li>'.$lst['noCuenta'].'</li>';
                            }

                            $bancos .= '</ul>';
                            $cuentas .= '</ul>';
                          }

                          switch ($row['estatus']) {
                            case '1':
                              $estatus = '<button class="btn btn-outline-success btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaDeposito" onclick="detallaDeposito('.$row['idCorte'].');" title="Autorización Pendiente"><i class="fas fa-clock"></i></button>';
                              $color = 'table-success';
                              break;
                            case '2':
                              $estatus = '<button class="btn btn-outline-warning btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaDeposito" onclick="detallaDeposito('.$row['idCorte'].');" title="Autorización Pendiente"><i class="fas fa-clock"></i></button>';
                              $color = '';
                              break;
                            case '3':
                              $estatus = '<button class="btn btn-outline-danger btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaDeposito" onclick="detallaDeposito('.$row['idCorte'].');" title="Rechazado"><i class="fas fa-times"></i></button>';
                              $color = 'table-danger';
                              break;

                            default:
                              $estatus = '<button class="btn btn-outline-info btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaDeposito" onclick="detallaDeposito('.$row['idCorte'].');" title="Falta subir Depósito"><i class="fas fa-file-alt"></i></button>';
                              $color = 'table-warning';
                              break;
                          }
                          echo '<tr class="'.$color.'">
                                  <td class="text-center">'.++$count.'</td>
                                  <td>'.$row['nomSucursal'].'</td>
                                  <td class="text-center">'.$row['idCorte'].'</td>
                                  <td class="text-center">'.$row['fechaCierre'].'</td>
                                  <td class="text-center">'.$row['fechaReg'].'</td>
                                  <td class="text-center">'.$row['fechaAutoriza'].'</td>
                                  <td>'.$row['nomRecolector'].'</td>
                                  <td>'.$row['nomAutoriza'].'</td>
                                  <td>'.$row['motivo'].'</td>
                                  <td>'.$bancos.'</td>
                                  <td>'.$cuentas.'</td>
                                  <td class="text-center">$ '.number_format($row['total'],2,'.',',').'</td>
                                  <td class="text-center">
                                    <a id="btn-'.$row['idCorte'].'">'.$estatus.'</a>
                                    <a href="../imprimeTicketCorte.php?idCorte='.$row['idCorte'].'&tipo=1" target="_blank" class="btn btn-circle-tablita btn-outline-info muestraSombra" title="Imprimir corte #'.$row['id'].'"><i class="fas fa-print"></i></a>
                                  </td>
                                </tr>';
                              }
                         ?>
                      </tbody>
                    </table>
                  <!-- ######################################################################################################################### --->
                </div>
              </div>
            </div>

          </div>


            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

            <!-- sample modal content -->
            <div id="modalDetallaDeposito" class="modal fade show" role="dialog" aria-labelledby="modalDetallaDepositoLabel" aria-hidden="true" style="display: none;" >
                <div class="modal-dialog">
                    <div class="modal-content" id="detallaDepositoContent">

                    </div>
                </div>
            </div>
            <!-- /.modal -->

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
    <script type="text/javascript" src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js" charset="UTF-8"></script>
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/jquery.number.js"></script>
    <script src="../assets/scripts/jquery.number.min.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/extra-libs/prism/prism.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php

    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);

    if (isset( $_SESSION['LZFmsjCorporativoDepositos'])) {
      echo "notificaBad('".$_SESSION['LZFmsjCorporativoDepositos']."');";
      unset($_SESSION['LZFmsjCorporativoDepositos']);
    }
    if (isset( $_SESSION['LZFmsjSuccessCorporativoDepositos'])) {
      echo "notificaSuc('".$_SESSION['LZFmsjSuccessCorporativoDepositos']."');";
      unset($_SESSION['LZFmsjSuccessCorporativoDepositos']);
    }
      ?>
    });

    function detallaDeposito(idCorte){
      //alert('entra a funcion');
      $.post("../funciones/formDetalleCorte.php",
    {idCorte:idCorte},
  function(respuesta){
      $("#detallaDepositoContent").html(respuesta);
  });
    }

function agregaCampos(){
  //alert('entra');
      var v1 = $("#valores").val();
      var fecha = $("#fechaDeposito0").val();
      switch (v1) {
        case '1':  var num2 = 2;  break;
        case '2':  var num2 = 3;  break;
        case '3':  var num2 = 4;  break;
        case '4':  var num2 = 5;  break;
        case '5':  var num2 = 6;  break;
        case '6':  var num2 = 7;  break;
        case '7':  var num2 = 8;  break;
        case '8':  var num2 = 9;  break;
        case '9':  var num2 = 10;  break;
        case '10':  var num2 = 11;  break;
        case '11':  var num2 = 12;  break;
        case '12':  var num2 = 13;  break;
        case '13':  var num2 = 14;  break;
        case '14':  var num2 = 15;  break;
        default:
        var num2 = 1;
      }
      var num3 = 1;
      var num4 = num2 + num3;
      //alert('num: '+v1);
      //alert('num2: '+num2);
      var contenido = '';
      $("#valores").val(num2);
      var opciones = <?php
      $miSql = "SELECT c.id,c.nombreCorto
                FROM catbancos c
                INNER JOIN cuentasbancarias cb ON c.id = cb.idClaveBanco GROUP BY c.id";
      $miResp = mysqli_query($link,$miSql) or die('No se puede mostrar los Bancos, notifica a tu Administrador');
      echo           '\'<option value="">Selecciona el Banco</option>';
      while ($bank = mysqli_fetch_array($miResp)) {
        echo           '<option value="'.$bank['id'].'">'.$bank['nombreCorto'].'</option>';
      }
      echo '\'';
      ?>;
    //  alert("num2: "+num2+', num4: '+num4);
    contenido +=  '<hr><br><br><h4>Depósito No. '+num4+'</h4><label class="control-label">Fecha</label><div class="input-group mb-3">';
    contenido +=  '<input type="date" class="form-control" name="fecha[]" id="fechaDeposito'+num2+'" value="'+fecha+'" required></div>';
    contenido +=  '<label class="control-label">Banco</label><div class="input-group mb-3">';
    contenido +=  '<select class="form-control" id="calveBanco'+num2+'" name="idBando[]" onclick="muestraCuentas(this.value,'+num2+')" required>'+opciones+'</select></div>';
    contenido +=  '<div class="input-group mb-3" id="muestraNoCuenta'+num2+'"></div><label class="control-label">Folio del Ticket de Depósito</label>';
    contenido +=  '<div class="input-group mb-3"><input type="text" id="folioTicket'+num2+'" class="form-control" name="folioTicket[]" required></div>';
    contenido +=  '<label class="control-label">Monto</label><div class="input-group mb-3"><input type="numer" step="any" min="0.01" id="deposito'+num2+'" name="deposito[]" onkeyup="mascaraMonto(this,Monto)" class="form-control" required></div>';
    contenido +=  '<label class="control-label">Foto</label><div class="input-group mb-3"><input type="file" id="foto'+num2+'" class="form-control" name="foto'+num2+'" required></div>';
      $("#divAgregaCampos").append(contenido);
      //alert('termina');
    }

    function autorizaDeposito(idDeposito,valor){
      var motivo = $("#motivo").val();
      if (valor == 3 && motivo == '') {
        notificaBad('Debes ingresar un motivo del rechazo.');
      } else {
      $('<form action="../funciones/autorizaDeposito.php" method="POST"><input type="hidden" name="idDeposito" value="'+idDeposito+'"><input type="hidden" name="valor" value="'+valor+'"><input type="hidden" name="motivo" value="'+motivo+'"></form>').appendTo('body').submit();
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
