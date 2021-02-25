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
                  <div class="col-md-12">
                    <div class="card border-<?=$pyme;?>">
                      <div class="card-header bg-<?=$pyme;?>">
                        <h4 class="text-white">Filtro</h4>
                      </div>
                      <div class="card-body">
                        <!-- ######################################################################################## -->
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

                        $tipo = (!empty($_POST['tipoFecha'])) ? $_POST['tipoFecha'] : 1 ;

                        switch ($tipo) {
                          case '2':
                          $sel = 'selected';
                          $sel2 = '';
                          $campo = 'c.fechaReg';
                            break;
                          case '3':
                          $sel = '';
                          $sel2 = 'selected';
                          $campo = 'd.fechaAutoriza';
                            break;

                          default:
                          $sel = '';
                          $sel2 = '';
                          $campo = 'd.fechaReg';
                            break;
                        }


                        $filtroFecha = " AND $campo BETWEEN '$fechaInicial' AND '$fechaFinal'";
                        #$filtroFecha2 = " AND t.fechaEnvio BETWEEN '$fechaInicial' AND '$fechaFinal'";

                      #  echo '<br>$filtroFecha: '.$filtroFecha;
                      #  echo '<br>$filtroFecha2: '.$filtroFecha2;
                        if (isset($_POST['sucursal']) && $_POST['sucursal'] > 0) {
                          $sucursal = $_POST['sucursal'];
                          $filtroSucursal = " AND c.idSucursal = '$sucursal'";
                        } else {
                          $sucursal = '';
                          $filtroSucursal = '';
                        }

                         ?>

                       <div class="col-md-12">
                         <div class="row">

                           <div class="col-md-1"></div>

                           <div class="col-md-4" style="align-items:right;vertical-align: middle;">
                             <div class="col-md-12">
                               <h5 class="m-t-30 text-center text-<?=$pyme;?>">Selecciona un rango de Fechas</h5>
                             </div>
                             <div class="col-md-12">
                               <form role="form" action="#" method="post">
                                 <div class="input-daterange input-group" id="date-range">
                                     <input type="date" class="form-control" name="fechaInicial" value="<?=$fechaInicial;?>" />
                                     <div class="input-group-append">
                                         <span class="input-group-text bg-<?=$pyme;?> b-0 text-white"> A </span>
                                     </div>
                                     <input type="date" class="form-control" name="fechaFinal" value="<?=$fechaFinal;?>" />
                                 </div>
                             </div>
                          </div>
                          <div class="col-md-2">
                             <div class="col-md-12">
                                 <h5 class="m-t-30 text-<?=$pyme;?>">Fecha de:</h5>
                             </div>
                             <div class="row">
                             <div class="col-md-12">
                               <select class="select2 form-control custom-select" name="tipoFecha" id="tipoFecha" style="width: 95%; height:100%;">
                                 <option value="1">Recolección</option>
                                 <option value="2" <?=$sel;?>>Corte</option>
                                 <option value="3" <?=$sel2;?>>Autorización</option>
                               </select>
                             </div>
                           </div>
                          </div>
                          <div class="col-md-3">
                           <div class="col-md-12">
                               <h5 class="m-t-30 text-<?=$pyme;?>">Sucursal:</h5>
                           </div>
                           <div class="row">
                             <div class="col-md-12">
                               <select class="select2 form-control custom-select" name="sucursal" id="sucursal" style="width: 95%; height:100%;">
                                 <?php
                                 echo '<option value="">Selecciona la Sucursal</option>';
                                 $sql="SELECT id, nombre FROM sucursales WHERE estatus = '1'";
                               #  echo $sql;
                                 $res=mysqli_query($link,$sql);
                                  while ($rows = mysqli_fetch_array($res)) {
                                    $activa = ($sucursal == $rows['id']) ? 'selected' : '' ;
                                    $disabled = ($idSucursal == $rows['id']) ? 'disabled' : '' ;
                                    echo '<option value="'.$rows['id'].'" '.$activa.' '.$disabled.'>'.$rows['nombre'].'</option>';
                                  }
                                  ?>
                               </select>
                             </div>
                           </div>
                          </div>
                           <div class="col-md-1">
                             <div class="col-md-12">
                                 <h5 class="m-t-30">&nbsp;</h5>
                             </div>
                             <div class="row">
                             <div class="col-md-12">
                               <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                             </div>
                           </div>
                         </div>

                         <div class="col-md-1"></div>

                       </div>
                     </div>
                       </form>
                      </div>
                      <!-- ######################################################################################## -->
                    </div>
                  </div>
                </div>

                <!-- ============================================================== -->
                <!-- Comienza información de los cortes  -->
                <!-- ============================================================== -->
                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white">Sucursales con Cortes por recolectados</h4>
                  </div>
                  <div class="card-body">
                    <!-- ######################################################################################################################### --->
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <!-- Nav tabs -->
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                              <?php
                                $sql = "SELECT c.idSucursal,COUNT(c.id) AS cantidad, SUM(c.montoEfectivo) AS efectivo, s.nombre AS nomSucursal
                                        FROM cortes c
                                        INNER JOIN sucursales s ON c.idSucursal = s.id
                                        INNER JOIN depositos d ON c.id = d.idCorte
                                        WHERE c.montoEfectivo > 0 AND d.idUserReg = '$idUser' $filtroFecha $filtroSucursal
                                        GROUP BY c.idSucursal
                                        ORDER BY s.nombre";
                                #echo '$sql: '.$sql;
                                $res = mysqli_query($link,$sql) or die('Problemas al consultar los cortes, notifica a tu Administrador.');
                                $cont = 0;
                                while ($c = mysqli_fetch_array($res)) {
                                  $active = ($cont == 0) ? 'active' : '' ;
                                  echo '<a class="nav-link '.$active.'" id="sucursalNo-'.$c['idSucursal'].'-tab" data-toggle="pill" href="#sucursalNo-'.$c['idSucursal'].'" role="tab" aria-controls="sucursalNo-'.$c['idSucursal'].'" aria-selected="true">
                                          <div class="d-flex align-items-center">
                                                <div>
                                                    <h4>'.$c['nomSucursal'].'</h4>
                                                </div>
                                                <div class="ml-auto">
                                                   <span class="badge badge-pill badge-light noti">'.$c['cantidad'].'</span>
                                                </div>
                                            </div>
                                        </a>

                                        ';
                                        $cont++;
                                }
                      echo '</div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                          <div class="tab-content" id="v-pills-tabContent">';

                        $sqlListado = "SELECT c.id,c.idSucursal,CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario,c.fechaCierre,c.montoEfectivo,s.nameFact,DATE_FORMAT(c.fechaCierre,'%Y-%m-%d') AS fechaTicket,
                                        d.estatus AS eDeposito
                                        FROM cortes c
                                        INNER JOIN sucursales s ON c.idSucursal = s.id
                                        INNER JOIN segusuarios u ON c.idUserReg = u.id
                                        INNER JOIN depositos d ON c.id = d.idCorte
                                        WHERE c.montoEfectivo > 0 AND d.idUserReg = '$idUser' $filtroFecha $filtroSucursal
                                        ORDER BY d.estatus,s.nombre,c.id ASC";
                        $resListado = mysqli_query($link,$sqlListado) or die('Problemas al consultar los cortes, notifica a tu Administrador.');
                        $cont2 = $sucAnt = $sucAct = 0;
                        $claseAnt = '';
                        while ($l = mysqli_fetch_array($resListado)) {
                          switch ($l['eDeposito']) {
                            case '1':
                            $btnDeposito = '<button class="btn btn-outline-success btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$l['id'].');" title="Autorizado"><i class="fas fa-check"></i></button>';
                              $color = 'table-success';
                              break;
                            case '2':
                            $btnDeposito = '<button class="btn btn-outline-warning btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$l['id'].');" title="Autorización Pendiente"><i class="fas fa-clock"></i></button>';
                              $color = 'table-warning';
                              break;
                            case '3':
                            $btnDeposito = '<button class="btn btn-outline-danger btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$l['id'].');" title="Rechazado"><i class="fas fa-times"></i></button>';
                              $color = 'table-danger';
                              break;

                            default:
                            $btnDeposito = '<button class="btn btn-outline-info btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$l['id'].');" title="Falta subir Depósito"><i class="fas fa-file-alt"></i></button>';
                              $color = '';
                              break;
                          }
                          $active2 = ($cont2 == 0) ? 'show active' : '' ;
                          $sucAct = $l['idSucursal'];
                          # cierro contenido de la sucursal de la Pill si el id de la sucursal Actual es distinta al id Anterior y no es la primera iteración
                          if ($sucAnt != $sucAct && $cont2 > 0) {
                              echo '<input type="hidden" id="idents-'.$claseAnt.'">
                                </tbody>
                              </table>
                            </div>
                          </div>';
                          }
                          # abro contenido de la sucursal de la Pill si el id de la sucursal Actual es distinta al id Anterior
                      if ($sucAnt != $sucAct) {
                        $cont3 = 0;
                      echo '<div class="tab-pane fade '.$active2.'" id="sucursalNo-'.$l['idSucursal'].'" role="tabpanel" aria-labelledby="sucursalNo-'.$l['idSucursal'].'-tab">
                              <div class="table-responsive">
                                <table class="table table-striped">
                                  <thead>
                                    <tr>
                                      <th class="text-center">#</th>
                                      <th class="text-center">Folio</th>
                                      <th class="text-center">Fecha</th>
                                      <th>Usuario</th>
                                      <th class="text-center">Monto</th>
                                      <th class="text-center">Acciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>';
                    }
                    ++$cont3;
                      # muestro el contenido de la pill
                          echo '<tr class="'.$color.'">
                                  <td class="text-center">'.$cont3.'</td>
                                  <td class="text-center">'.$l['id'].'</td>
                                  <td class="text-center">'.$l['fechaCierre'].'</td>
                                  <td>'.$l['nomUsuario'].'</td>
                                  <td class="text-right">$ '.number_format($l['montoEfectivo'],2,'.',',').'</td>
                                  <td class="text-center" id="btnCheck-'.$l['id'].'">
                                    '.$btnDeposito.'
                                    <a href="../funciones/imprimeTicketRecolecta.php?idCorte='.$l['id'].'&tipo=1" target="_blank" class="btn btn-circle-tablita btn-outline-info muestraSombra" title="Imprimir corte #'.$l['id'].'"><i class="fas fa-print"></i></a>
                                    <input type="hidden" id="monto-'.$l['id'].'" value="'.$l['montoEfectivo'].'">
                                  </td>
                                </tr>';


                      $sucAnt = $sucAct;
                      $cont2++;
                      $claseAnt = $l['nameFact'];

                          }
                          # cierre del último pill, divs de contenido y col-lg-9
                        echo '<input type="hidden" id="idents-'.$claseAnt.'">
                          </tbody>
                        </table>
                      </div>
                    </div>';
                               ?>
                    </div>

                  </div>
                </div>


            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

            <!-- sample modal content -->
            <div id="modalDetallaCorte" class="modal fade show" role="dialog" aria-labelledby="modalDetallaCorteLabel" aria-hidden="true" style="display: none;" >
                <div class="modal-dialog">
                    <div class="modal-content" id="detallaCorteContent">

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
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #  include('../funciones/basicFuctions.php');
      #  alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjHistorialRecolector'])) {
        echo "notificaBad('".$_SESSION['LZFmsjHistorialRecolector']."');";
        unset($_SESSION['LZFmsjHistorialRecolector']);
      }
      if (isset( $_SESSION['LZFmsjSuccessHistorialRecolector'])) {
        echo "notificaSuc('".$_SESSION['LZFmsjSuccessHistorialRecolector']."');";
        unset($_SESSION['LZFmsjSuccessHistorialRecolector']);
      }
      ?>
    });

    function detallaCorte(idCorte){
      //alert('entra a funcion');
      $.post("../funciones/formDetalleCorte.php",
    {idCorte:idCorte},
  function(respuesta){
      $("#vista").val('2');
      $("#detallaCorteContent").html(respuesta);
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
    contenido +=  '<select class="form-control" id="calveBanco'+num2+'" name="idBanco[]" onclick="muestraCuentas(this.value,'+num2+')" required>'+opciones+'</select></div>';
    contenido +=  '<div class="input-group mb-3" id="muestraNoCuenta'+num2+'"></div><label class="control-label">Folio del Ticket de Depósito</label>';
    contenido +=  '<div class="input-group mb-3"><input type="text" id="folioTicket'+num2+'" class="form-control" name="folioTicket[]" required></div>';
    contenido +=  '<label class="control-label">Monto</label><div class="input-group mb-3"><input type="numer" step="any" min="0.01" id="deposito'+num2+'" name="deposito[]" onkeyup="mascaraMonto(this,Monto)" class="form-control" required></div>';
    contenido +=  '<label class="control-label">Foto</label><div class="input-group mb-3"><input type="file" id="foto'+num2+'" class="form-control" name="foto'+num2+'" required></div>';
      $("#divAgregaCampos").append(contenido);
      //alert('termina');
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
