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

                <div class="card border-<?=$pyme;?>">
                  <div class="card-header bg-<?=$pyme;?>">
                    <h4 class="text-white">Listado de Cortes</h4>
                  </div>
                  <div class="card-body">
                    <!-- ######################################################################################################################### --->
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Sucursal</th>
                            <th class="text-center">Folio</th>
                            <th class="text-center">Fecha de Recolección</th>
                            <th>Usuario</th>
                            <th class="text-center">Monto</th>
                            <th>Motivo</th>
                            <th class="text-center">Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sql = "SELECT d.*, CONCAT(u.nombre,' ',u.appat,' ',apmat) AS nomUser, s.nombre AS nomSucursal
                                  FROM cortes c
                                  INNER JOIN depositos d ON c.id = d.idCorte
                                  INNER JOIN segusuarios u ON c.idUserReg = u.id
                                  INNER JOIN sucursales s ON c.idSucursal = s.id
                                  WHERE d.estatus != 1 AND d.estatus !=4 AND d.idUserReg = '$idUser'
                                  ORDER BY d.estatus, d.fechaReg,c.id ASC";
                          $res = mysqli_query($link,$sql) or die('Problemas al consultar los cortes pendientes, notifica a tu Administrador.');
                          $count = 0;
                          while ($row = mysqli_fetch_array($res)) {
                            switch ($row['estatus']) {
                              case '2':
                                $estatus = '<button class="btn btn-outline-warning btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$row['idCorte'].');" title="Autorización Pendiente"><i class="fas fa-clock"></i></button>';
                                $color = '';
                                break;
                              case '3':
                                $estatus = '<button class="btn btn-outline-danger btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$row['idCorte'].');" title="Rechazado"><i class="fas fa-times"></i></button>';
                                $color = 'table-danger';
                                break;

                              default:
                                $estatus = '<button class="btn btn-outline-info btn-circle-tablita muestraSombra" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte('.$row['idCorte'].');" title="Falta subir Depósito"><i class="fas fa-file-alt"></i></button>';
                                $color = 'table-warning';
                                break;
                            }
                            echo '<tr class="'.$color.'">
                                    <td class="text-center">'.++$count.'</td>
                                    <td>'.$row['nomSucursal'].'</td>
                                    <td class="text-center">'.$row['idCorte'].'</td>
                                    <td class="text-center">'.$row['fechaReg'].'</td>
                                    <td>'.$row['nomUser'].'</td>
                                    <td class="text-center">$ '.number_format($row['total'],2,'.',',').'</td>
                                    <td>'.$row['motivo'].'</td>
                                    <td class="text-center">
                                      <a id="btn-'.$row['idCorte'].'">'.$estatus.'</a>
                                      <a href="../funciones/imprimeTicketRecolecta.php?idCorte='.$row['idCorte'].'&tipo=1" target="_blank" class="btn btn-circle-tablita btn-outline-info muestraSombra" title="Imprimir corte #'.$row['id'].'"><i class="fas fa-print"></i></a>
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

    if (isset( $_SESSION['LZFmsjCortesPendientes'])) {
      echo "notificaBad('".$_SESSION['LZFmsjCortesPendientes']."');";
      unset($_SESSION['LZFmsjCortesPendientes']);
    }
    if (isset( $_SESSION['LZFmsjSuccessCortesPendientes'])) {
      echo "notificaSuc('".$_SESSION['LZFmsjSuccessCortesPendientes']."');";
      unset($_SESSION['LZFmsjSuccessCortesPendientes']);
    }
      ?>
    });

    function detallaCorte(idCorte){
      //alert('entra a funcion');
      $.post("../funciones/formDetalleCorte.php",
    {idCorte:idCorte},
  function(respuesta){
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
    contenido +=  '<label class="control-label">Monto</label><div class="input-group mb-3"><input type="text" step="any" min="0.01" id="deposito'+num2+'" name="deposito[]" onkeyup="mascaraMonto(this,Monto)" class="form-control" required></div>';
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
