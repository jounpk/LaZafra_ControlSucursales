<?php
require_once 'seg.php';
$info = new Seguridad();
session_start();
define('INCLUDE_CHECK',1);
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

                <div class="row">
                  <div class="col-lg-3 col-md-6">
                      <div class="card border-left border-bottom border-info">
                          <div class="card-body">
                              <div class="d-flex no-block align-items-center">
                                  <div>
                                    <h2>&nbsp;</h2>
                                      <h6 class="text-info">Inicio de Caja</h6>
                                  </div>
                                  <div class="ml-auto">
                                      <span class="text-info display-6"><i class="fas fa-laptop"></i></span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-md-6">
                      <div class="card border-left border-bottom border-cyan">
                          <div class="card-body">
                              <div class="d-flex no-block align-items-center">
                                  <div>
                                      <h2>2</h2>
                                      <h6 class="text-cyan">Ventas Abiertas</h6>
                                  </div>
                                  <div class="ml-auto">
                                      <span class="text-cyan display-6"><i class="ti-clipboard"></i></span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-md-6">
                      <div class="card border-left border-bottom border-success">
                          <div class="card-body">
                              <div class="d-flex no-block align-items-center">
                                  <div>
                                      <h2>1</h2>
                                      <h6 class="text-success">Cortes Abiertos</h6>
                                  </div>
                                  <div class="ml-auto">
                                      <span class="text-success display-6"><i class="ti-wallet"></i></span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-md-6">
                      <div class="card border-left border-bottom border-orange">
                          <div class="card-body">
                              <div class="d-flex no-block align-items-center">
                                  <div>
                                      <h2>2</h2>
                                      <h6 class="text-orange">Devoluciones del día</h6>
                                  </div>
                                  <div class="ml-auto">
                                      <span class="text-orange display-6"><i class="ti-stats-down"></i></span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <div class="card">
                              <div class="card-body">
                                  <div class="d-flex align-items-center">
                                      <div>
                                          <h4 class="card-title mb-0">Latest Sales</h4>
                                      </div>
                                      <div class="ml-auto">
                                          <select class="custom-select border-0 text-muted">
                                              <option value="0" selected="">August 2018</option>
                                              <option value="1">May 2018</option>
                                              <option value="2">March 2018</option>
                                              <option value="3">June 2018</option>
                                          </select>
                                      </div>
                                  </div>
                              </div>
                              <div class="card-body bg-light">
                                  <div class="row align-items-center">
                                      <div class="col-xs-12 col-md-6">
                                          <h3 class="m-b-0 font-light">August 2018</h3>
                                          <span class="font-14 text-muted">Sales Report</span>
                                      </div>
                                      <div class="col-xs-12 col-md-6 align-self-center display-6 text-info text-right">$3,690</div>
                                  </div>
                              </div>
                              <div class="table-responsive">
                                  <table class="table table-hover">
                                      <thead>
                                          <tr>
                                              <th class="border-top-0">NAME</th>
                                              <th class="border-top-0">STATUS</th>
                                              <th class="border-top-0">DATE</th>
                                              <th class="border-top-0">PRICE</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>

                                              <td class="txt-oflo">Elite admin</td>
                                              <td><span class="label label-success label-rounded">SALE</span> </td>
                                              <td class="txt-oflo">April 18, 2017</td>
                                              <td><span class="font-medium">$24</span></td>
                                          </tr>
                                          <tr>

                                              <td class="txt-oflo">Real Homes WP Theme</td>
                                              <td><span class="label label-info label-rounded">EXTENDED</span></td>
                                              <td class="txt-oflo">April 19, 2017</td>
                                              <td><span class="font-medium">$1250</span></td>
                                          </tr>
                                          <tr>

                                              <td class="txt-oflo">Ample Admin</td>
                                              <td><span class="label label-purple label-rounded">Tax</span></td>
                                              <td class="txt-oflo">April 19, 2017</td>
                                              <td><span class="font-medium">$1250</span></td>
                                          </tr>
                                          <tr>

                                              <td class="txt-oflo">Medical Pro WP Theme</td>
                                              <td><span class="label label-success label-rounded">Sale</span></td>
                                              <td class="txt-oflo">April 20, 2017</td>
                                              <td><span class="font-medium">-$24</span></td>
                                          </tr>
                                          <tr>

                                              <td class="txt-oflo">Hosting press html</td>
                                              <td><span class="label label-success label-rounded">SALE</span></td>
                                              <td class="txt-oflo">April 21, 2017</td>
                                              <td><span class="font-medium">$24</span></td>
                                          </tr>
                                          <tr>

                                              <td class="txt-oflo">Digital Agency PSD</td>
                                              <td><span class="label label-danger label-rounded">Tax</span> </td>
                                              <td class="txt-oflo">April 23, 2017</td>
                                              <td><span class="font-medium">-$14</span></td>
                                          </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Donute Chart</h4>
                                    <div id="morris-donut-chart"><svg height="342" version="1.1" width="849" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.2.0</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#2962ff" d="M424.5,278.3333333333333A107.33333333333333,107.33333333333333,0,0,0,525.858884759001,206.31035152552312" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#2962ff" stroke="#ffffff" d="M424.5,281.3333333333333A110.33333333333333,110.33333333333333,0,0,0,528.6918970659295,207.29728681660916L571.816639960287,222.32063513647458A156,156,0,0,1,424.5,327Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#55ce63" d="M525.858884759001,206.31035152552312A107.33333333333333,107.33333333333333,0,0,0,328.2168788296735,123.56578215945092" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#55ce63" stroke="#ffffff" d="M528.6918970659295,207.29728681660916A110.33333333333333,110.33333333333333,0,0,0,325.5257356913724,122.23998103968403L280.0753182445102,99.84867323917638A161,161,0,0,1,576.5383271385015,223.96552728828465Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#2f3d4a" d="M328.2168788296735,123.56578215945092A107.33333333333333,107.33333333333333,0,0,0,424.46628023940616,278.3333280366457" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#2f3d4a" stroke="#ffffff" d="M325.5257356913724,122.23998103968403A110.33333333333333,110.33333333333333,0,0,0,424.46533776162556,281.3333278886016L424.4509911554102,326.99999230170863A156,156,0,0,1,284.56055680834527,102.05834177212121Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="424.5" y="161" text-anchor="middle" font-family="&quot;Arial&quot;" font-size="15px" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 15px; font-weight: 800;" font-weight="800" transform="matrix(1.6764,0,0,1.6764,-287.113,-116.3332)" stroke-width="0.5965320910973085"><tspan dy="6" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">In-Store Sales</tspan></text><text x="424.5" y="181" text-anchor="middle" font-family="&quot;Arial&quot;" font-size="14px" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 14px;" transform="matrix(2.2361,0,0,2.2361,-524.8547,-213.8472)" stroke-width="0.4472049689440994"><tspan dy="5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">30</tspan></text></svg></div>
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
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      include('../funciones/basicFuctions.php');
      //alertMsj($nameLk);
      ?>
    });
    </script>

</body>

</html>
<?php
#$_SESSION['MSJhomeWar'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeDgr'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeInf'] = 'Te envio un MSJ desde el mas aca.';
#$_SESSION['MSJhomeSuc'] = 'Te envio un MSJ desde el mas aca.';
?>
