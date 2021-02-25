<!--<button type="button" class="close text-white" onclick="cancelar()">Cancelar</button>-->
<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');
$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
session_start();
$debug = 0;

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
  <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
  <title><?= $info->nombrePag; ?></title>

  <!-- Custom CSS -->
  <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

  <!--<link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
-->
  <link href="../assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">


  <link href="../dist/css/style.min.css" rel="stylesheet">
  <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <style>
    .btn-circle-sm {
      width: 35px;
      height: 35px;
      line-height: 30px;
      font-size: 0.9rem;
      background: #fff;
      box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
    }

    .btn-circle-sm2 {
      width: 20px;
      height: 20px;
      line-height: 20px;
      font-size: 0.9rem;

    }

    #listaSuc {
      column-count: 2;
    }

    .select2-container {
      width: 100%;
    }
  </style>

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
        <?= $info->customizer('2'); ?>

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
            <?= $info->generaMenuUsuario(); ?>
            <!-- ============================================================== -->
          </ul>
        </div>
      </nav>
    </header>

    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <?= $info->generaMenuLateral(); ?>
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">

      <div class="container-fluid">
        <div class="row">
          <div>
            <h2 class="text-<?= $pyme; ?>"><?= $info->nombrePag; ?></h2>
            <h4><?= $info->detailPag; ?></h4>
          </div>
          <div class="ml-auto">
            <h4><b><?= $info->nombreSuc; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4>
          </div>
          <br><br>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card border-<?= $pyme; ?>">
              <div class="card-header bg-<?= $pyme; ?>">
                <h4 class="m-b-0 text-white">Lista de Ventas No Facturadas Por Sucursal</h4>
              </div>
              <div class="card-body">
                <div id="validation" class="m-t-0 jsgrid" style="position: relative; height: auto; width: 100%;">
                  <?php
                  if ($debug == 1) {
                    print_r($_POST);
                  }

                  $fechaInicial = (isset($_POST['fechaInicial']) and $_POST['fechaInicial'] != '') ? $_POST['fechaInicial'] : '';
                  $fechaFinal = (isset($_POST['fechaFinal']) and $_POST['fechaFinal'] != '') ? $_POST['fechaFinal'] : '';
                  $sucursal = (isset($_POST['buscaSuc']) and $_POST['buscaSuc'] != '') ? $_POST['buscaSuc'] : '';

                  $sql = "SELECT * FROM sucursales WHERE estatus=1 ORDER BY id ASC";
                  $resSuc = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");
                  $listaSuc = '';

                  while ($datos = mysqli_fetch_array($resSuc)) {
                    $activeSuc = ($datos['id'] == $sucursal) ? 'selected' : '';
                    $listaSuc .= '<option value="' . $datos['id'] . '" ' . $activeSuc . '> ' . $datos['nombre'] . '</option>';
                  }

                  if ($sucursal == '') {
                    $filtroSuc = '';
                  } else {
                    $filtroSuc = " AND sc.id = '$sucursal'";
                  }

                  ?>

                </div>
                <div class="border p-3 mb-3">
                  <h4><i class="fas fa-filter"></i> Filtrado</h4>
                  <div class="row">
                    <form method="post" action="ventasNoFact.php">
                      <div class="col-6">
                      </div>
                      <div class="col-6">
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4  offset-md-0">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="rangeBa1" class="control-label col-form-label">Fecha Inicial</label>
                            <input class="form-control" type="date" autocomplete="off" value="<?= $fechaInicial ?>" onchange="reqFechas(this.value)" id="rangeBa1" name="fechaInicial" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="rangeBa2" class="control-label col-form-label">Fecha Final</label>
                            <input class="form-control" type="date" autocomplete="off" value="<?= $fechaFinal ?>" id="rangeBa2" name="fechaFinal" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 offset-md-0">
                      <div class="form-group">
                        <label for="rangeBa1" class="control-label col-form-label">Sucursal</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="finVig1"><i class="mdi mdi-store"></i></span>
                          </div>
                          <select class="select2 form-control custom-select" name="buscaSuc" id="buscaSuc" onchange="" style="width: 80%;">
                            <option value=""> Todas las Sucursales</option>
                            <?= $listaSuc ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mt-4 pt-1">
                      <input type="submit" id="buscarConexion" class="btn btn-success mt-2" value="Buscar"></input>
                    </div>
                  </div>
                  </form>
                </div>
                <div class="row">
                  <div class="col-12">
                    <?php
                    setlocale(LC_TIME, 'es_ES.UTF-8');
                    $sqlPym = "SELECT pym.id, pym.razonSocial AS nameEmpresa, COUNT(vta.id) AS cantFacturar
                                          FROM empresas pym
                                          INNER JOIN sucursales sc ON pym.id = sc.idEmpresa
                                          INNER JOIN ventas vta ON sc.id = vta.idSucursal
                                          LEFT JOIN (
                                            SELECT vtf.idVenta, MAX(fgen.id) AS idFacGen, count(fgen.id) AS cantFacts
                                            FROM vtasfact vtf
                                            INNER JOIN facturasgeneradas fgen ON vtf.idFactgen = fgen.id
                                            GROUP BY vtf.idVenta
                                            ORDER BY fgen.fechaReg DESC ) vnFac ON vta.id = vnFac.idVenta
                                          LEFT JOIN facturasgeneradas facg ON vnFac.idFacGen = facg.id
                                          WHERE (facg.idCancelada IS NOT NULL OR facg.id IS NULL) AND vta.estatus = '2'
                                          $filtroSuc
                                          GROUP BY pym.id
                                          ORDER BY pym.razonSocial ASC";
                    //=======================   Debug   =========================
                    if ($debug == 1) {
                      echo '<br>Query enlista pendientes por Empresa: ' . $sqlPym . '<br><br>';
                      $resPym = mysqli_query($link, $sqlPym) or die("Problemas al listar las Empresas." . mysqli_error($link));
                    } else {
                      $resPym = mysqli_query($link, $sqlPym) or die('<span class="text-danger">Problemas al listar las Empresas.</span>');
                    } //=======================   FIN Debug   =========================

                    ?>

                    <h4 class="card-title">Empresas Con Pendientes Por Facturar</h4>
                    <h6 class="card-subtitle">Ventas que aun no han sido Facturadas, organizado por Empresa y Sucursal.</h6><br>
                    <div id="accordian-pyme">
                      <?php
                      $show = 'show';
                      $exp = 'true';
                      while ($pym = mysqli_fetch_array($resPym)) {
                        $identifPyme = $pym['id'];
                        echo '
                                              <div class="card">
                                                  <a class="card-header" id="heading' . $pym['id'] . '">
                                                      <button class="btn btn-link" data-toggle="collapse" data-target="#collapse' . $pym['id'] . '" aria-expanded="' . $exp . '" aria-controls="collapse' . $pym['id'] . '">
                                                          <h5 class="mb-0">' . $pym['nameEmpresa'] . '</h5>
                                                      </button>
                                                      <span class="float-right">' . $pym['cantFacturar'] . ' Pendientes</span>
                                                  </a>
                                                  <div id="collapse' . $pym['id'] . '" class="collapse ' . $show . '" aria-labelledby="heading' . $pym['id'] . '" data-parent="#accordian-pyme">
                                                      <div class="card-body">
                                                          ';
                        #============================================   COMIENZA TABS   ========================================================

                        $sqlSuc = "SELECT
                        pym.razonSocial AS nameEmpresa,
                        sc.id AS identSuc,
                        sc.nameCorto AS nameSuc,
                        sc.nombre AS nameLargoSuc,
                        vta.idCorte,
                        vta.id,
                        DATE_FORMAT( IF ( ct.fechaCierre IS NULL, vta.fechaReg, ct.fechaCierre ), '%d - %b - %Y' ) AS fechaVenta,
                        vta.total,
                        CONCAT( TRIM( cl.nombre ), ' (', cl.apodo, ')' ) AS nameCliente,
                        dtvta.formsOfPayment AS tipoPgo,
                        DATE_FORMAT( IF ( ct.fechaCierre IS NULL, vta.fechaReg, ct.fechaCierre ), '%d-%m-%Y' ) AS fechaCortes 
                      FROM
                        empresas pym
                        INNER JOIN sucursales sc ON pym.id = sc.idEmpresa
                        INNER JOIN ventas vta ON sc.id = vta.idSucursal
                        LEFT JOIN clientes cl ON vta.idCliente = cl.id
                        INNER JOIN (
                        SELECT
                          pv.idVenta,
                          SUM( pv.monto ) AS totales,
                          GROUP_CONCAT( DISTINCT st.nombre ORDER BY st.nombre ASC SEPARATOR ', ' ) AS formsOfPayment,
                          SUM( pv.monto ) AS montoTotal 
                        FROM
                          pagosventas pv
                          INNER JOIN sat_formapago st ON pv.idFormaPago = st.id 
                        GROUP BY
                          pv.idVenta 
                        ) dtvta ON vta.id = dtvta.idVenta
                        LEFT JOIN cortes ct ON vta.idCorte = ct.id
                        LEFT JOIN (
                        SELECT
                          vtf.idVenta,
                          MAX( fgen.id ) AS idFacGen,
                          count( fgen.id ) AS cantFacts 
                        FROM
                          vtasfact vtf
                          INNER JOIN facturasgeneradas fgen ON vtf.idFactgen = fgen.id 
                        GROUP BY
                          vtf.idVenta 
                        ORDER BY
                          fgen.fechaReg DESC 
                        ) vnFac ON vta.id = vnFac.idVenta
                        LEFT JOIN facturasgeneradas facg ON vnFac.idFacGen = facg.id 
                      WHERE (facg.idCancelada IS NOT NULL OR facg.id IS NULL) AND vta.estatus = '2' AND pym.id = '$identifPyme'
                                            $filtroSuc
                                  ORDER BY sc.id, vta.idCorte, vta.id ASC
                                  ";
                        //=======================   Debug   =========================
                        if ($debug == 1) {
                          echo '<br>Query enlista pendientes por Sucursal: ' . $sqlSuc . '<br><br>';
                          $resSuc = mysqli_query($link, $sqlSuc) or die("Problemas al listar las Sucursales con su detalle." . mysqli_error($link));
                        } else {
                          $resSuc = mysqli_query($link, $sqlSuc) or die('<span class="text-danger">Problemas al listar las Sucursales con su detalle.</span>');
                        } //=======================   FIN Debug   =========================

                        $activeNav = 'active';
                        $liItems = '';
                        $tabsPane = '';
                        $tabOp = 0;
                        $sucName = '';
                        while ($suc = mysqli_fetch_array($resSuc)) {


                          //=======================   CREAMOS TABS PANELS PARA LA AGRUPACION POR SUCURSAL   ========================================
                          if ($sucName != $suc['nameSuc']) {
                            $liItems .= '<li class="nav-item"> <a class="nav-link ' . $activeNav . '" data-toggle="tab" href="#' . $suc['nameSuc'] . '" role="tab"> <span class="hidden-xs-down">' . $suc['nameSuc'] . '</span></a> </li>';

                            if ($tabOp == 1) {
                              if ($paneCortOp == 1) {
                                $tabsPane .= '
                                                                      </tbody>
                                                                    <tfoot><tr class="table-info"><td colspan="8" class="text-center"> <b>TOTAL: $ ' . number_format($sumaToria, 2, '.', ',') . '</b></td> </tr> </tfoot>
                                                                  </table>
                                                                  <div id="bloquear-btn' . $idSuc . '-' . $idCorte . '" style="display:none;">
                                                              <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                <span class="sr-only">Loading...</span>
                                                              </button>
                                                              <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                <span class="sr-only">Loading...</span>
                                                              </button>
                                                              <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                Loading...
                                                              </button>
                                                            </div>
                                                            <div id="desbloquear-btn' . $idSuc . '-' . $idCorte . '">
                                                                  <div class="float-right"> <br> <button type="button" onclick="mandarPaqueteFacturas(\'' . $idCorte . '\',\'' . $idSuc . '\' )" class="btn waves-effect waves-light btn-rounded btn-info"  id="pckbtn-' . $idSuc . '-' . $idCorte . '"> Facturar Seleccionados</button></div>
                                                                </div>
                                                              </div>
                                                            </div>
                                                        </div>';
                                $paneCortOp = 0;
                              }
                              $tabsPane .= '
                                                            </div>
                                                        </div>
                                                      </div>';

                              $tabOp = 0;
                            }

                            $tabsPane .= '
                                                    <div class="tab-pane ' . $activeNav . '" id="' . $suc['nameSuc'] . '" role="tabpanel">
                                                        <div class="p-20">
                                                          <h3>Listado de Cortes</h3>
                                                          <h4>Detalle de Ventas pendientes por Facturar de <b>' . $suc['nameLargoSuc'] . '</b></h4>

                                                          <div id="accordian-cortes">';
                            $tabOp = 1;
                            $paneCortOp = 0;
                          }
                          //====================================================================================================================

                          //==================   CREAMOS ACORDEONES PARA LA AGRUPACION POR CORTE Y LOS CONCATENAMOS AL TABPANE   ===============
                          $nameCorte = ($suc['idCorte'] == '0') ? 'Ventas en Corte aún Abierto del ' . $suc['fechaVenta'] : 'Corte #' . $suc['idCorte'] . ' del ' . $suc['fechaVenta'];
                          $idPaneCorte = $suc['idCorte'] . '-' . $suc['id'];
                          if ($suc['idCorte'] == '0') {
                            $icon = "fas fa-times-circle";
                            $funcionCorte = '';
                          } else {
                            $icon = "far fa-money-bill-alt";
                            $funcionCorte = 'imprimeCorteDelDía(\'' .  $suc['fechaCortes'] . '\',' . $suc['identSuc'] . ');';
                          }
                          if ($paneCortOp == 1) {
                            if ($cortInPaneCort != $suc['idCorte']) {

                              /* <span class="float-right"> <button type="button" class="btn btn-circle-sm2 btn-circle" onClick="imprimeCorteDelDía(\'' .  $suc['fechaCortes'] . '\',' . $suc['identSuc'] . ');"><i class="fas fa-paperclip text-primary"></i></button> <button type="button" class="btn btn-circle-sm2 btn-circle" onClick="imprimeCorteDelDía(\'' .  $suc['fechaCortes'] . '\',' . $suc['identSuc'] . ');"><i class="far fa-money-bill-alt text-primary"></i></button></span>*/
                              $tabsPane .= '
                                                                    </tbody>
                                                                  <tfoot><tr class="table-info"><td colspan="8" class="text-center"> <b>TOTAL: $ ' . number_format($sumaToria, 2, '.', ',') . '</b></td> </tr> </tfoot>
                                                                </table>
                                                                <div class="float-right"> <br> 
                                                                <div id="bloquear-btn' . $idSuc . '-' . $idCorte . '" style="display:none;">
                                                                <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                  <span class="sr-only">Loading...</span>
                                                                </button>
                                                                <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                  <span class="sr-only">Loading...</span>
                                                                </button>
                                                                <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                  Loading...
                                                                </button>
                                                              </div>
                                                              <div id="desbloquear-btn' . $idSuc . '-' . $idCorte . '">
                                                                
                                                                <button type="button" id="pckbtn-' . $idSuc . '-' . $idCorte . '" onclick="mandarPaqueteFacturas(\'' . $idCorte . '\',\'' . $idSuc . '\' )" class="btn waves-effect waves-light btn-rounded btn-info btn.info"  > Facturar Seleccionados</button></div>
                                                              </div>
                                                                </div>
                                                          </div>
                                                      </div>

                                                      <div class="card">
                                                        <a class="card-header" id="heading' . $idPaneCorte . '">
                                                          <button class="btn btn-link" data-toggle="collapse" data-target="#collapse' . $idPaneCorte . '" aria-expanded="' . $expCort . '" aria-controls="collapse' . $idPaneCorte . '">
                                                            <h5 class="mb-0">' . $nameCorte . '</h5>
                                                          </button>
                                                          <span class="float-right"> <button type="button" class="btn btn-circle-sm2 btn-circle" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte(' . $suc['idCorte'] . ');"><i class="fas fa-paperclip text-primary"></i></button> <button type="button" class="btn btn-circle-sm2 btn-circle" onClick="' . $funcionCorte . '"><i class="' . $icon . ' text-primary"></i></button></span>
                                                        </a>
                                                        <div id="collapse' . $idPaneCorte . '" class="collapse ' . $showCort . '" aria-labelledby="heading' . $idPaneCorte . '" data-parent="#accordian-cortes">
                                                          <div class="card-body">
                                                          <div class="float-right">
                                                            <fieldset class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="checkeaCortes' . $suc['identSuc'] . '-' . $suc['idCorte'] . '" onclick="selectClase(\'' . $suc['identSuc'] . '-' . $suc['idCorte'] . '\')"> Seleccionar Todo el Corte
                                                                </label>
                                                            </fieldset>
                                                          </div>
                                                            <table class="table table-hover table-sm m-b-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col"></th>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Ticket</th>
                                                                        <th scope="col">Cliente</th>
                                                                        <th scope="col" class="text-right">Total</th>
                                                                        <th scope="col" class="text-center">Forma Pago</th>
                                                                        <th scope="col">Ticket</th>
                                                                        <th scope="col">Factura</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                              $count = 0;
                              $sumaToria = 0;
                              $paneCortOp = 1;
                              $cortInPaneCort = $suc['idCorte'];
                            }
                          } else {
                            $debug = 0;
                            if ($debug == 1) {
                              echo 'Fecha Corte ' . $suc['fechaCortes'];
                            }
                            $tabsPane .= '<div class="card">
                                                      <a class="card-header" id="heading' . $idPaneCorte . '">
                                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse' . $idPaneCorte . '" aria-expanded="' . $expCort . '" aria-controls="collapse' . $idPaneCorte . '">
                                                          <h5 class="mb-0">' . $nameCorte . '</h5>
                                                        </button>
                                                        <span class="float-right"> <button type="button" class="btn btn-circle-sm2 btn-circle" data-toggle="modal" data-target="#modalDetallaCorte" onclick="detallaCorte(' . $suc['idCorte'] . ');"><i class="fas fa-paperclip text-primary"></i></button> <button type="button" class="btn btn-circle-sm2 btn-circle" onClick="' . $funcionCorte . '"><i class="' . $icon . ' text-primary"></i></button></span>

                                                      </a>
                                                      <div id="collapse' . $idPaneCorte . '" class="collapse ' . $showCort . '" aria-labelledby="heading' . $idPaneCorte . '" data-parent="#accordian-cortes">
                                                        <div class="card-body">
                                                          <div class="float-right">
                                                            <fieldset class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="checkeaCortes' . $suc['identSuc'] . '-' . $suc['idCorte'] . '" onclick="selectClase(\'' . $suc['identSuc'] . '-' . $suc['idCorte'] . '\')"> Seleccionar Todo el Corte
                                                                </label>
                                                            </fieldset>
                                                          </div>
                                                          <table class="table table-hover table-sm m-b-0">
                                                              <thead>
                                                                  <tr>
                                                                      <th scope="col"></th>
                                                                      <th scope="col">#</th>
                                                                      <th scope="col">Ticket</th>
                                                                      <th scope="col">Cliente</th>
                                                                      <th scope="col" class="text-right">Total</th>
                                                                      <th scope="col" class="text-center">Forma Pago</th>
                                                                      <th scope="col">Ticket</th>
                                                                      <th scope="col">Factura</th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody>';
                            $count = 0;
                            $sumaToria = 0;
                            $paneCortOp = 1;
                            $cortInPaneCort = $suc['idCorte'];
                          }

                          $count++;
                          $sumaToria += $suc['total'];
                          $tabsPane .= '
                                                   <tr>
                                                       <th scope="row" class="text-center">
                                                        <fieldset class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="checkCorte-' . $suc['idCorte'] . '[]" class="cte' . $suc['identSuc'] . '-' . $suc['idCorte'] . '"  value="' . $suc['id'] . '">
                                                            </label>
                                                        </fieldset>
                                                       </th>
                                                       <th scope="row">' . $count . '</th>
                                                       <td>' . $suc['id'] . '</td>
                                                       <td>' . $suc['nameCliente'] . '</td>
                                                       <td class="text-right"> $ ' . number_format($suc['total'], 2, '.', ',') . '</td>
                                                       <td class="text-center">' . $suc['tipoPgo'] . ' </td>
                                                       <td class="text-center">  <span> <a  class="btn btn-circle-sm2 btn-circle" href="../imprimeTicketVenta.php?idVenta=' . $suc['id'] . '&tipo=1" target="_blank"><i class="fas fa-shopping-cart text-primary"></i></a></span> </td>
                                                       <td>  <span> <button  class="btn btn-circle-sm2 btn-circle" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta(' . $suc['id'] . ')"><i class="fas fa-copy text-success"></i></button></span></td>
                                                   </tr>';


                          //====================================================================================================================  HASTA AQUI NOMAS

                          $sucName = $suc['nameSuc'];
                          $activeNav = '';
                          $idCorte = $suc['idCorte'];
                          $idSuc = $suc['identSuc'];
                        }
                        //================================  IMPRIMIMOS LOS TABS POR SUCURSAL CON SU CONTENIDO C/U   ========================
                        if ($debug == '1') {
                          echo 'ID CORTE ' . $idCorte;
                          echo 'ID SUCURSAL ' . $idSuc;
                        }
                        echo '
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs" role="tablist">
                                                    ' . $liItems . '
                                                </ul>
                                                <!-- Tab panes -->
                                                <div class="tab-content tabcontent-border">
                                                    ' . $tabsPane . '
                                                                  </tbody>
                                                                <tfoot><tr class="table-info"><td colspan="8" class="text-center"> <b>TOTAL: $ ' . number_format($sumaToria, 2, '.', ',') . '</b></td> </tr> </tfoot>
                                                              </table>
                                                              <div id="bloquear-btn' . $idSuc . '-' . $idCorte . '" style="display:none;">
                                                              <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                <span class="sr-only">Loading...</span>
                                                              </button>
                                                              <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                <span class="sr-only">Loading...</span>
                                                              </button>
                                                              <button class="btn btn-' . $pyme . '" type="button" disabled>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                Loading...
                                                              </button>
                                                            </div>
                                                            <div id="desbloquear-btn' . $idSuc . '-' . $idCorte . '">
                                                              <div class="float-right"> <br> <button type="button" onclick="mandarPaqueteFacturas(\'' . $idCorte . '\',\'' . $idSuc . '\' )" class="btn waves-effect waves-light btn-rounded btn-info" disabled id="pckbtn-' . $idSuc . '-' . $idCorte . '"> Facturar Seleccionados</button></div>
                                                            </div>
                                                             </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>';

                        //========================================================   FINALIZA TABS ==========================================================

                        echo '
                                                      </div>
                                                  </div>
                                              </div>
                                              ';
                        $show = '';
                        $exp = 'false';
                      }
                      echo '
                                              </div>
                                          </div>
                                          ';

                      ?>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
  <div id="modalFacturaVenta" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-<?= $pyme ?>">
          <h4 class="modal-title text-white" id="lblFacturaVenta">Ticket No.: </h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body" id="facturaVentaBody">

        </div>
      </div>
    </div>
  </div>
  <!-- sample modal content -->
  <div id="modalDetallaCorte" class="modal fade show" role="dialog" aria-labelledby="modalDetallaCorteLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content" id="detallaCorteContent">

      </div>
    </div>
  </div>
  <!-- /.modal -->


  <footer class="footer text-center">
    Powered by
    <b class="text-info">RVSETyS</b>.
  </footer>

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
  <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
  <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <script type="text/javascript" src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js" charset="UTF-8"></script>

  <!--Custom JavaScript -->
  <script src="../assets/scripts/basicFuctions.js"></script>
  <script src="../assets/scripts/notificaciones.js"></script>
  <script src="../dist/js/custom.min.js"></script>
  <script src="../assets/libs/toastr/build/toastr.min.js"></script>
  <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {
      <?php
      #  include('../funciones/basicFuctions.php');
      #  alertMsj($nameLk);
      if (isset($_SESSION['LZFmsjNoFact'])) {
        echo "notificaBad('" . $_SESSION['LZFmsjNoFact'] . "');";
        unset($_SESSION['LZFmsjNoFact']);
      }
      if (isset($_SESSION['LZFmsjSuccessNoFact'])) {
        echo "notificaSuc('" . $_SESSION['LZFmsjSuccessNoFact'] . "');";
        unset($_SESSION['LZFmsjSuccessNoFact']);
      }
      ?>
    });

    function selectClase(id) {
      var clase = 'cte' + id;
      /*alert('Entra a función con valor: ' + id + ' clase: ' + clase);*/
      var isChecked = $("#checkeaCortes" + id).prop("checked");
      if (isChecked) {
        /*alert('Si');*/
        $("." + clase).each(function() {
          this.checked = true;
        });
        //alert(('#pckbtn-' + id));
        $('#pckbtn-' + id).attr('disabled', false);

        return;
      } else {
        /* alert('No');*/
        $("." + clase).each(function() {
          this.checked = false;
        });
        return;
      }
    }

    function detallaCorte(idCorte) {
      //alert('entra a funcion');
      $.post("../funciones/formDetalleCorteVtas.php", {
          idCorte: idCorte
        },
        function(respuesta) {
          $("#detallaCorteContent").html(respuesta);
        });
    }

    function reqFechas(valor) {
      if (valor == '') {
        $("#rangeBa1").removeAttr("required");
        $("#rangeBa2").removeAttr("required");
      } else {
        $("#rangeBa1").prop("required", true);
        $("#rangeBa2").prop("required", true);
      }
    }

    function ejecutandoCarga(ident) {
      var selector = 'DIV' + ident;
      var finicio = $('#fStart').val();
      var ffin = $('#fEnd').val();

      $.post("../funciones/cargaContenidoVtasNoFact.php", {
          ident: ident
        },
        function(respuesta) {
          $("#" + selector).html(respuesta);
        });
    }



    function mandarPaqueteFacturas(id, sucursal) {

      var debug = 0;
      if (debug == 1) {
        console.log("EL ID DEL CORTE ES: " + id);
        console.log("EL ID DEL SUCURSAL ES: " + sucursal);

      }
      var clase = '.cte' + sucursal + '-' + id;
      var idsVentas = [];
      $(clase).each(function() {
        if ($(this).is(':checked')) {
          if (debug == 1) {
            console.log($(this).val());
          }
          idsVentas.push($(this).val());
        }

      });

      if (debug == 1) {
        console.log(idsVentas);
      }
      bloqueoBtn("bloquear-btn" + sucursal + '-' + id, 1);
      if (idsVentas != '') {
        $.post("../funciones/multifacturacion.php", {
            idsVenta: idsVentas
          },
          function(respuesta) {
            var resp = respuesta.split('|');
            if (resp[0] == 0 || resp[0] == 3) {
              bloqueoBtn("bloquear-btn" + sucursal + '-' + id, 2);


              Swal.fire({
                type: 'error',
                title: 'Error de Facturacion',
                text: resp[1],
                footer: "<b>Errores Encontrados:<b><br>" + resp[2]
              });


            } else if (resp[0] == 1) {
              location.reload();
            }


          });
      } else {
        notificaBad("Selecciona al menos una venta para facturar");

      }

    }




    function modalFacturaVenta(idVenta) {
      if (idVenta > 0) {
        $.post("../funciones/formularioFacturacionMulti.php", {
            idVenta: idVenta
          },
          function(respuesta) {

            $("#lblFacturaVenta").html('Ticket No.: ' + idVenta);
            $("#facturaVentaBody").html(respuesta);

          });
      } else {
        notificaBad('No se reconoció la venta, actualiza e intenta de nuevo, si persiste notifica a tu Administrador.');
      }
    }

    function imprimeCorte(idCorte) {
      $('<form action="../imprimeTicketCorte.php" target="_blank" method="POST"><input type="hidden" name="idCorte" value="' + idCorte + '"></form>').appendTo('body').submit();
    }

    function imprimeCorteDelDía(fechaCorte, idSucursal) {
      //alert('fechaCorte: '+fechaCorte+', idSucursal: '+idSucursal);
      $('<form action="../funciones/corteGeneralDia.php" target="_blank" method="POST"><input type="hidden" name="fechaCorte" value="' + fechaCorte + '"><input type="hidden" name="idSucursal" value="' + idSucursal + '"></form>').appendTo('body').submit();
    }

    function buscarXCliente() {
      nombreCliente = $('#busq_nombre').val();
      //alert(nombreCliente);
      $.post("../funciones/busquedaCliente.php", {
          id: nombreCliente
        },
        function(respuesta) {
          if (respuesta[0] == 0) {
            var resp = respuesta.split('|');
            notificaBad(resp[1]);
          } else {
            $("#resultadosBusq").html(respuesta);
          }
        });



    }


    function buscarXRFC() {
      rfcCliente = $('#busq_rfc').val();
      //alert(nombreCliente);
      $.post("../funciones/busquedaRFC.php", {
          id: rfcCliente
        },
        function(respuesta) {
          if (respuesta[0] == 0) {
            var resp = respuesta.split('|');
            notificaBad(resp[1]);
          } else {

            $("#resultadosBusq").html(respuesta);

          }
        });



    }
  </script>