<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');

$cantCad = COUNT($cad);
$nameLk = $cad[$cantCad - 1];
session_start();

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
    <link rel="icon" type="../image/png" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
    <title><?= $info->nombrePag; ?></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <style>
        .btn-circle-tablita {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            margin-left: 5px;
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
                                <h4 class="m-b-0 text-white">Detallado de Convenios Por Proveedor</h4>
                            </div>
                            <div class="card-body">


                                <?php
                                $leftsitos = '';


                                if (isset($_POST['buscaEmp']) and $_POST['buscaEmp'] >= 1) {
                                    $leftsitos = "LEFT JOIN compras cp ON cp.idProveedor = prov.id
                                    LEFT JOIN detcompras dtcp ON dtcp.idCompra=cp.id
                                    LEFT JOIN productos prod ON prod.id=dtcp.idProducto
                                    LEFT JOIN sucursales suc ON suc.id=cp.idSucursal
	                                INNER JOIN empresas emp ON emp.id=prov.idEmpresa";
                                    $buscaEmp = $_POST['buscaEmp'];
                                    $filtroEmp = "AND emp.id=" . $_POST['buscaEmp'];
                                } else {
                                    $filtroEmp = 'AND 1=1';
                                    $buscaEmp = '';
                                    $leftsitos = '';
                                }
                                if (isset($_POST['buscaMarca']) and $_POST['buscaMarca'] >= 1) {
                                    $leftsitos = "LEFT JOIN compras cp ON cp.idProveedor = prov.id
                                    LEFT JOIN detcompras dtcp ON dtcp.idCompra=cp.id
                                    LEFT JOIN productos prod ON prod.id=dtcp.idProducto
                                    LEFT JOIN sucursales suc ON suc.id=cp.idSucursal
	                                INNER JOIN empresas emp ON emp.id=prov.idEmpresa";
                                    $buscaMrka = $_POST['buscaMarca'];
                                    $filtroMrka = "AND  prod.idCatMarca=" . $_POST['buscaMarca'];
                                } else {
                                    $filtroMrka = 'AND 1=1';
                                    $buscaMrka = '';
                                }

                                if (isset($_POST['buscaDpto']) and $_POST['buscaDpto'] >= 1) {
                                    $leftsitos = "LEFT JOIN compras cp ON cp.idProveedor = prov.id
                                    LEFT JOIN detcompras dtcp ON dtcp.idCompra=cp.id
                                    LEFT JOIN productos prod ON prod.id=dtcp.idProducto
                                    LEFT JOIN sucursales suc ON suc.id=cp.idSucursal
	                                INNER JOIN empresas emp ON emp.id=prov.idEmpresa";
                                    $buscaDpto = $_POST['buscaDpto'];
                                    $filtroDpto = "AND prod.idDepartamento=" . $_POST['buscaDpto'];
                                } else {
                                    $filtroDpto = 'AND 1=1';
                                    $buscaDpto = '';
                                }


                                $sql = "SELECT * FROM empresas WHERE estatus=1 ORDER BY nombre";
                                $resEmp = mysqli_query($link, $sql) or die("Problemas al enlistar Sucursales.");

                                $listaEmp = '';
                                while ($datos = mysqli_fetch_array($resEmp)) {
                                    $activeEmp = ($datos['id'] == $buscaEmp) ? 'selected' : '';
                                    $listaEmp .= '<option value="' . $datos['id'] . '" ' . $activeEmp . '>' . $datos['nombre'] . '</option>';
                                }



                                $sql = "SELECT id, nombre FROM catmarcas WHERE estatus='1'";
                                $resMrka = mysqli_query($link, $sql) or die("Problemas al enlistar Marcas.");

                                $listaMrka = '';
                                while ($datos = mysqli_fetch_array($resMrka)) {
                                    $activeMrka = ($datos['id'] == $buscaMrka) ? 'selected' : '';
                                    $listaMrka .= '<option value="' . $datos['id'] . '" ' . $activeMrka . '>' . $datos['nombre'] . '</option>';
                                }


                                $sql = "SELECT id, nombre FROM departamentos  WHERE estatus='1'";
                                $resDpto = mysqli_query($link, $sql) or die("Problemas al enlistar Departamentos.");

                                $listaDpto = '';
                                while ($datos = mysqli_fetch_array($resDpto)) {
                                    $activeDpto = ($datos['id'] == $buscaDpto) ? 'selected' : '';
                                    $listaDpto .= '<option value="' . $datos['id'] . '" ' . $activeDpto . '>' . $datos['nombre'] . '</option>';
                                }


                                ?>
                                <div class="text-right">
                                    <button class="btn btn-outline-<?= $pyme; ?> btn-rounded" data-target="#modalPrecio" data-toggle="modal"> <i class="fas fa-plus"></i> Agregar Documento</button>
                                </div>
                                <div class="row">

                                    <form method="post" action="conveniosPorProveedor.php">

                                        <div class="col-6">

                                        </div>
                                        <div class="col-6">

                                        </div>
                                </div>


                                <!--/span-->
                                <div class="">
                                    <div class="row">

                                        <div class="col-md-10 mt-2 mx-4">
                                            <h4><i class="fas fa-filter "></i> Filtrado</h4>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="rangeBa1" class="control-label col-form-label">Empresa</label>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="finVig1"><i class="mdi mdi-store"></i></span>
                                                            </div>
                                                            <select class="select2 form-control custom-select" name="buscaEmp" id="buscaEmp" onchange="" style="width: 80%;">

                                                                <option value=""> Todas las Empresas</option>
                                                                <?= $listaEmp ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="rangeBa1" class="control-label col-form-label">Marca</label>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="finVig1"><i class="fas fa-boxes"></i></span>
                                                            </div>
                                                            <select class="select2 form-control custom-select" name="buscaMarca" id="buscaMarca" onchange="" style="width: 80%;">

                                                                <option value=""> Todas las Marcas</option>
                                                                <?= $listaMrka ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="rangeBa1" class="control-label col-form-label">Departamentos</label>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="finVig1"><i class="fas fa-bookmark"></i></span>
                                                            </div>
                                                            <select class="select2 form-control custom-select" name="buscaDpto" id="buscaDpto" onchange="" style="width: 80%;">

                                                                <option value=""> Todas los Departamentos</option>
                                                                <?= $listaDpto ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-md-1 pt-4">
                                            <input type="submit" id="buscarConexion" class="btn btn-success mt-5" value="Buscar"></input>


                                        </div>

                                    </div>
                                </div>
                                </form>






                                <div class="table-responsive">
                                    <table class="table product-overview" id="tabla_convenios">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Nº de Proveedor</th>
                                                <th>Proveedor</th>
                                                <th>Monto de Crédito</th>
                                                <th>Crédito</th>
                                                <th>Fecha Reg.</th>
                                                <th>User Reg.</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!--end .table-responsive -->
                            </div>
                            <!--end .col -->
                        </div>
                        <!--end .row -->
                        <!-- END DATATABLE 1 -->
                    </div>
                    <!--end .card-body -->
                </div>
                <!--end .card -->
                <!-- END ACTION -->
                <div id="modalPrecio" class="modal fade show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" enctype="multipart/form-data">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;">
                                <h4 class="modal-title" id="lblEditMetodo">Agregar Nuevo Documento</h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>


                            </div>
                            <form action="" method="post" id="formPrecio">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="nombre" class="control-label col-form-label">Nombre del Proveedor</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="nombre"><i class=" fas fa-user"></i></span>
                                                </div>
                                                <select class="select2 form-control custom-select" name="idProveedor" id="idProveedor" onchange="" style="width: 91%;" required>

                                                    <option value=""> Asigna Al Proveedor</option>
                                                    <?php
                                                    $sql = "SELECT  prov.id,  prov.nombre FROM proveedores prov WHERE estatus='1' ORDER BY prov.nombre";
                                                    $resProv = mysqli_query($link, $sql) or die("Problemas al enlistar Proveedores.");

                                                    $listaProv = '';
                                                    while ($datos = mysqli_fetch_array($resProv)) {
                                                        $activeProv = ($datos['id'] == $buscaProv) ? 'selected' : '';
                                                        echo '<option value="' . $datos['id'] . '" ' . $activeProv . '>' . $datos['id'] . '-' . $datos['nombre'] . '</option>';
                                                    }
                                                    ?>


                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="precios" class="control-label col-form-label">Documento</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="nombre"><i class="fas fa-file-pdf"></i></span>

                                                </div>
                                                <input type="file" accept="application/pdf" class="form-control" id="precios" name="preciosDocto" required>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="precios" class="control-label col-form-label">Clasificación Del Documento</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="nombre"><i class="fas fa-tags"></i></span>

                                                </div>
                                                <select class="form-control" id="tipo" name="tipo" required>
                                                    <option value="">Selecciona la Clasificación</option>

                                                    <option value="1">Convenio</option>
                                                    <option value="2">Lista de Precio</option>

                                                
                                                
                                                </select>    




                                            </div>

                                        </div>

                                    </div>



                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="anotaciones" class="control-label col-form-label">Anotaciones Extras</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="anotaciones"><i class="fas fa-pencil-alt"></i></span>
                                                </div>
                                                <textarea class="form-control" id="anotaciones" aria-describedby="anotaciones" name="anotaciones"></textarea>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <div id="bloquear-btn1" style="display:none;">
                                        <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                        </button>
                                        <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                        </button>
                                        <button class="btn btn-<?= $pyme ?>" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                    <div id="desbloquear-btn1">
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" id="registrarGastobtn" class="btn btn-success waves-effect waves-light">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="verIMG" tabindex="-1" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-<?= $pyme ?>" style="color:#fff;" id="verIMGContent">

                                <h4 class="modal-title" id="verIMGTitle"> </h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>

                            </div>
                            <div class="modal-body" id="verIMGBody">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn bg-<?= $pyme ?>" data-dismiss="modal">Salir</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- /.modal -->
            </div>



            <footer class="footer text-center">
                Powered by
                <b class="text-info">RVSETyS</b>.
            </footer>

        </div>

    </div>


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
    <script src="../assets/tablasZafra/datatable_convenios.js"></script>


    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                language: 'es',
                format: 'dd-mm-yyyy',
            });
            <?php
            #include('../funciones/basicFuctions.php');
            #alertMsj($nameLk);

            if (isset($_SESSION['LZmsjInfoConvenios'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoConvenios'] . "');";
                unset($_SESSION['LZmsjInfoConvenios']);
            }
            if (isset($_SESSION['LZmsjSuccessConvenios'])) {
                echo "notificaSuc('" . $_SESSION['LZmsjSuccessConvenios'] . "');";
                unset($_SESSION['LZmsjSuccessConvenios']);
            }
            ?>
        }); // Cierre de document ready


        function limpiaCadena(dat, id) {
            //alert(id);
            dat = getCadenaLimpia(dat);
            $("#" + id).val(dat);
        }


        $("#formPrecio").submit(function(event) {
            event.preventDefault();
            var formElement = document.getElementById("formPrecio");
            var formPrecio = new FormData(formElement);
            bloqueoBtn("bloquear-btn1", 1);

            $.ajax({
                type: 'POST',
                url: "../funciones/registraNuevaListaPrecios.php",
                data: formPrecio,
                processData: false,
                contentType: false,

                success: function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {

                        location.reload();

                    } else {
                        bloqueoBtn("bloquear-btn1", 2);
                        notificaBad(resp[1]);
                    }
                }
            });
        });

        function ejecutandoCarga(identif) {
            var selector = 'DIV' + identif;
            var finicio = $('#fStart').val();
            var ffin = $('#fEnd').val();

            $.post("../funciones/cargaContenidoConvenio.php", {
                    ident: identif
                },
                function(respuesta) {
                    $("#" + selector).html(respuesta);
                });

        }

        function verIMG(name, link) {

            $("#verIMGTitle").html('<b>' + name + '</b>');
            //  $("#verIMGBody").html('<img class="img-thumbnail responsive" src="../' + link + '" width="100%"  type="application/pdf">');
            $("#verIMGBody").html('<embed src="../' + link + '" height="500px" width="100%" type="application/pdf">');
            $("#verIMG").modal("show");
        }
        <?php

        $sql =
            "SELECT DISTINCT 
  prov.id,
  prov.nombre,
  IF (prov.limiteCredito IS NULL,'$0.0', CONCAT('$',FORMAT(prov.limiteCredito, 2)) )AS monto,
CASE
      prov.tipoLimite 
      WHEN '1' THEN
      CONCAT( FORMAT(prov.cantidadLimite,0), ' ', 'Día(s)' ) 
      WHEN '2' THEN
      CONCAT( FORMAT(prov.cantidadLimite,0), ' ', 'Semana(s)' ) 
      WHEN '3' THEN
      CONCAT( FORMAT(prov.cantidadLimite,0), ' ', 'Meses(s)' ) 
      WHEN '4' THEN
      CONCAT( FORMAT(prov.cantidadLimite,0), ' ', 'Año(s)' ) 
      WHEN '' THEN
      CONCAT( 'Sin cantidad de Crédito Asignado' ) 
  END AS credito, 
  DATE_FORMAT(prov.fechaReg,'%d-%m-%Y') AS fecha,
  CONCAT(seg.nombre,' ',seg.appat,' ',seg.apmat) AS usuario
  FROM proveedores prov
  INNER JOIN segusuarios seg ON seg.id=prov.idUserReg
  $leftsitos
  WHERE prov.estatus='1' $filtroMrka $filtroDpto $filtroEmp 
  ";
        $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta de Proveedores </option>' . $sql);
        $arreglo['data'] = array();
        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($res);
        echo 'var jsonData= ' . $var . ';';
        echo 'var pyme = "' . $pyme . '";';
        ?>
    </script>

</body>

</html>