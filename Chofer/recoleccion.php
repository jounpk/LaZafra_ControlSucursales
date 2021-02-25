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
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">
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

                <style>
                .muestraSombra{
                  background:#fff;
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
                      <h4 class="text-white">Sucursales con Cortes por recolectar</h4>
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
                                          WHERE c.idRecoleccion = 0 AND c.estatus = 2 
                                          GROUP BY c.idSucursal
                                          ORDER BY s.nombre";
                                  $res = mysqli_query($link,$sql) or die('Problemas al consultar los cortes, notifica a tu Administrador.');
                                  $cont = 0;
                                  while ($c = mysqli_fetch_array($res)) {
                                    $active = ($cont == 0) ? 'active' : '' ;
                                    echo '<a class="nav-link '.$active.'" id="sucursalNo-'.$c['idSucursal'].'-tab" data-toggle="pill" href="#sucursalNo-'.$c['idSucursal'].'" role="tab" aria-controls="sucursalNo-'.$c['idSucursal'].'" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                  <div>
                                                      <h4>'.$c['nomSucursal'].'&nbsp;<sup><span class="badge badge-pill badge-success noti">'.$c['cantidad'].'</span></sup></h4>
                                                  </div>
                                                  <div class="ml-auto">
                                                    $ '.number_format($c['efectivo'],2,'.',',').'
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

                          $sqlListado = "SELECT c.id,c.idSucursal,CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsuario,c.fechaCierre,c.montoEfectivo,s.nameFact,DATE_FORMAT(c.fechaCierre,'%Y-%m-%d') AS fechaTicket
                                          FROM cortes c
                                          INNER JOIN sucursales s ON c.idSucursal = s.id
                                          INNER JOIN segusuarios u ON c.idUserReg = u.id
                                          WHERE c.idRecoleccion = 0 AND c.estatus = 2 
                                          ORDER BY s.nombre,c.id ASC";
                          $resListado = mysqli_query($link,$sqlListado) or die('Problemas al consultar los cortes, notifica a tu Administrador.');
                          $cont2 = $sucAnt = $sucAct = 0;
                          $claseAnt = '';
                          while ($l = mysqli_fetch_array($resListado)) {
                            $active2 = ($cont2 == 0) ? 'show active' : '' ;
                            $sucAct = $l['idSucursal'];
                            # cierro contenido de la sucursal de la Pill si el id de la sucursal Actual es distinta al id Anterior y no es la primera iteración
                            if ($sucAnt != $sucAct && $cont2 > 0) {
                                echo '<tfooter>
                                        <tr>
                                          <td colspan="4" class="text-right"><h4><b>TOTAL: </b></h4></td>
                                          <td colspan="2" class="text-left text-info" id="monto-'.$claseAnt.'"><h4><b>$ 0</b></td>
                                        </tr>
                                      </tfooter>
                                    <input type="hidden" id="idents-'.$claseAnt.'">
                                  </tbody>
                                </table>
                                <div class="col-md-12"><div class="text-right" id="btnAceptaCortes-'.$claseAnt.'"><button class="btn btn-success btn-rounded" id="btnAceptaRecoleccion-'.$claseAnt.'" data-toggle="modal" data-target="#modalCierraRecoleccion" onClick="recolectionData(\''.$claseAnt.'\','.$sucAnt.');" disabled>Cargar Cortes Seleccionados</button></div></div>
                              </div>
                            </div>';
                            }
                            # abro contenido de la sucursal de la Pill si el id de la sucursal Actual es distinta al id Anterior
                        if ($sucAnt != $sucAct) {
                          $cont3 = 0;
                        echo '<div class="tab-pane fade '.$active2.'" id="sucursalNo-'.$l['idSucursal'].'" role="tabpanel" aria-labelledby="sucursalNo-'.$l['idSucursal'].'-tab">
                                <div class="text-right">
                                  <b class="text-success"> &nbsp;&nbsp;&nbsp;Seleccionar todo</b>&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="selectTodo(\''.$l['nameFact'].'\');" id="'.$l['nameFact'].'" value="">&nbsp;&nbsp;<br>
                                </div>
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
                            echo '<tr>
                                    <td class="text-center">'.$cont3.'</td>
                                    <td class="text-center">'.$l['id'].'</td>
                                    <td class="text-center">'.$l['fechaCierre'].'</td>
                                    <td>'.$l['nomUsuario'].'</td>
                                    <td class="text-right">$ '.number_format($l['montoEfectivo'],2,'.',',').'</td>
                                    <td class="text-center" id="btnCheck-'.$l['id'].'">
                                      <input type="checkbox" class="'.$l['nameFact'].'" name="'.$l['nameFact'].'" onclick="sumaEfectivo(\''.$l['nameFact'].'\');" value="'.$l['id'].'">&nbsp;&nbsp;
                                      <a href="../funciones/imprimeTicketRecolecta.php?idCorte='.$l['id'].'&tipo=1" target="_blank" class="btn btn-circle-tablita btn-outline-info muestraSombra" title="Imprimir corte #'.$l['id'].'"><i class="fas fa-print"></i></a>
                                      <input type="hidden" id="monto-'.$l['id'].'" value="'.$l['montoEfectivo'].'">
                                    </td>
                                  </tr>';


                        $sucAnt = $sucAct;
                        $cont2++;
                        $claseAnt = $l['nameFact'];

                            }
                            # cierre del último pill, divs de contenido y col-lg-9
                          echo '<tfooter>
                                  <tr>
                                    <td colspan="4" class="text-right"><h4><b>TOTAL: </b></h4></td>
                                    <td colspan="2" class="text-left text-info" id="monto-'.$claseAnt.'"><h4><b>$ 0</b></td>
                                  </tr>
                                </tfooter>
                              <input type="hidden" id="idents-'.$claseAnt.'">
                            </tbody>
                          </table>
                          <div class="col-md-12"><div class="text-right" id="btnAceptaCortes-'.$claseAnt.'"><button class="btn btn-success btn-rounded" id="btnAceptaRecoleccion-'.$claseAnt.'" data-toggle="modal" data-target="#modalCierraRecoleccion" onClick="recolectionData(\''.$claseAnt.'\','.$sucAnt.');" disabled>Cargar Cortes Seleccionados</button></div></div>
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
            <div id="modalCierraRecoleccion" class="modal fade show" role="dialog" aria-labelledby="modalCierraRecoleccionLabel" aria-hidden="true" style="display: none;" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-<?=$pyme;?>">
                            <h4 class="modal-title text-white">Selecciona quién es la persona que entrega el corte</h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" id="divComunidades">
                                
                                <select class="select2 form-control custom-select" id="idUsuario" style="width: 100%; height:100%;">
                                  <option value="">Selecciona una opción</option>
                                  <?php
                                    $sqlUsu = "SELECT id, CONCAT(nombre,' ',appat,' ',apmat) AS nomUsuario FROM segusuarios WHERE estatus = 1 AND idNivel NOT IN(5,6)";
                                    $resUsu = mysqli_query($link,$sqlUsu) or die('Problemas al consultar los usuarios, notifica a tu Administrador.');
                                    while ($a = mysqli_fetch_array($resUsu)) {
                                      echo '<option value="'.$a['id'].'">'.$a['nomUsuario'].'</option>';
                                    }
                                   ?>
                                 </select>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="modal-footer">
                              <input type="hidden" id="nomClase" value="">
                              <input type="hidden" id="idSucAnt" value="">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                              <button type="button" onClick="cargaCortesSel();" class="btn btn-success">Capturar</button>
                            </div>
                        </div>
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
    <script src="../assets/scripts/jquery.number.js"></script>
    <script src="../assets/scripts/jquery.number.min.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php

    #  include('../funciones/basicFuctions.php');
    #  alertMsj($nameLk);
      ?>
    });

    function selectTodo(clase){
      // console.log('Entró a funcion selectTodo()');
      selectClase(clase);
      sumaEfectivo(clase);
    }

    function selectClase(clase){

      //  alert('Entra a función con valor: '+clase+' clase: '+clase);
        var isChecked = $("#"+clase).prop("checked");
        if(isChecked){
          //alert('Si');
          $("."+clase).each(function () {
              this.checked = true;
            });
          return;
        } else {
          //alert('No');
          $("."+clase).each(function () {
              this.checked = false;
            });
          return;
        }
      }

    function sumaEfectivo(clase){
    //  console.log('Entro a función sumaEfectivo, clase: '+clase);
      var subtotal = 0;
      var varBase = $('[name="'+clase+'"]:checked').map(function(){
        return this.value;
      }).get();

      var varArreglo = varBase.join(',');
      //console.log('arreglo: '+varArreglo);

      var b = varArreglo.split(",");
      for (var i = 0; i < b.length; i++) {
        //console.log('id: #monto-'+b[i]);
        subtotal += Number($("#monto-"+b[i]).val());
        //console.log('monto: '+monto);
      }
      if (varArreglo != '') {
        $("#btnAceptaRecoleccion-"+clase).prop('disabled',false);
      } else {
        $("#btnAceptaRecoleccion-"+clase).prop('disabled',true);
      }
      $("#idents-"+clase).val(varArreglo);
      $("#monto-"+clase).html('<h4><b>$ '+$.number(subtotal)+'</b>');
    //  console.log('subtotal: '+subtotal+', monto-'+clase+', idents-'+clase+', varArreglo: '+varArreglo);
    }

    function cargaCortesSel(){
      var clase = $("#nomClase").val();
      var idSucursal = $("#idSucAnt").val();
      var idEntrega = $("#idUsuario option:selected").val();
      var cadena = $("#idents-"+clase).val();
      var btnCargaCorte = '<button class="btn btn-success btn-rounded" id="btnAceptaRecoleccion-'+clase+'" data-toggle="modal" data-target="#modalCierraRecoleccion" recolectionData(\''+clase+'\','+idSucursal+') disabled>Cargar Cortes Seleccionados</button>';
      var btnSpinner = '<button class="btn btn-rounded btn-warning" type="button" disabled=""><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando selección...</button>';
      // console.log('clase: '+clase+', cadena: '+cadena);
      console.log(btnCargaCorte);
      $("#btnAceptaCortes-"+clase).html(btnSpinner);

      $.post("../funciones/recogeCortes.php",
        {cadena:cadena, clase:clase, idSucursal:idSucursal, idEntrega:idEntrega},
      function(respuesta){
        var resp = respuesta.split('|');
        if (resp[0] == 1) {
          notificaSuc(resp[1]);
          quitaChecbox(cadena);
          $("#btnAceptaCortes-"+clase).html(resp[2]+''+btnCargaCorte);
          //$("#btnAceptaCortes-"+clase).html(btnCargaCorte);
          $("#modalCierraRecoleccion").modal('hide');
          $("#idUsuario").val('');
        } else {
          notificaBad(resp[1]);
          $("#btnAceptaCortes-"+clase).html(btnCargaCorte);
        }
      });
    }

    function quitaChecbox(cadena){
      var a = cadena.split(',');
      for (var i = 0; i < a.length; i++) {
        var txt = '<a href="../funciones/imprimeTicketRecolecta.php?idCorte='+a[i]+'&tipo=1" target="_blank" class="btn btn-circle-tablita btn-outline-info muestraSombra" title="Imprimir corte #'+a[i]+'"><i class="fas fa-print"></i></a>';
        $("#btnCheck-"+a[i]).html(txt);
      }
    }

    function recolectionData(clase,idSucursal){
      $("#nomClase").val(clase);
      $("#idSucAnt").val(idSucursal);
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
