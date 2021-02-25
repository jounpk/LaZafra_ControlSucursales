<?php
require_once 'seg.php';
$info = new Seguridad();
$cad = explode('/', $_SERVER["REQUEST_URI"]);
require_once('../include/connect.php');
$hoy = date('d-m-Y');
$mes = date('m-Y');
$fechaAct = date('Y-m-d');
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
    <link rel="icon" type="../image/icon" sizes="16x16" href="../assets/images/<?= $pyme; ?>.ico">
    <title><?= $info->nombrePag; ?></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/libs/select2/dist/css/select2.min.css">

    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <!--<link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
-->
    <link href="../assets/libs/footable/css/footable.bootstrap.min.css" rel="stylesheet">


    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <style>
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="font-22 mdi mdi-email-outline"></i>

                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                                <span class="with-arrow">
                                    <span class="bg-danger"></span>
                                </span>
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title text-white bg-danger">
                                            <h4 class="m-b-0 m-t-5">5 New</h4>
                                            <span class="font-light">Messages</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center message-body">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img">
                                                    <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle">
                                                    <span class="profile-status online pull-right"></span>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Pavan kumar</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link text-dark" href="javascript:void(0);">
                                            <b>See all e-Mails</b>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->


                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown border-right">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell-outline font-22"></i>
                                <span class="badge badge-pill badge-info noti">3</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                <span class="with-arrow">
                                    <span class="bg-primary"></span>
                                </span>
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title bg-primary text-white">
                                            <h4 class="m-b-0 m-t-5">4 New</h4>
                                            <span class="font-light">Notifications</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center notifications">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-danger btn-circle">
                                                    <i class="fa fa-link"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Luanch Admin</h5>
                                                    <span class="mail-desc">Just see the my new admin!</span>
                                                    <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-success btn-circle">
                                                    <i class="ti-calendar"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Event today</h5>
                                                    <span class="mail-desc">Just a reminder that you have event</span>
                                                    <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-info btn-circle">
                                                    <i class="ti-settings"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Settings</h5>
                                                    <span class="mail-desc">You can customize this template as you want</span>
                                                    <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-primary btn-circle">
                                                    <i class="ti-user"></i>
                                                </span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">Pavan kumar</h5>
                                                    <span class="mail-desc">Just see the my admin!</span>
                                                    <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center m-b-5 text-dark" href="javascript:void(0);">
                                            <strong>Check all notifications</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
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

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?= $info->generaMenuLateral(); ?>
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            <?php
            $sql = "SELECT c.*, p.nombre AS proveedor
            FROM compras c
            LEFT JOIN proveedores p ON c.idProveedor=p.id
            WHERE c.idUserReg=".$_SESSION['LZFident']." and c.idSucursal=".$_SESSION['LZFidSuc']." and c.estatus=1";
            $res = mysqli_query($link, $sql) or die("Error de Consulta:" . $sql);
            //echo $sql;
            $var = mysqli_fetch_array($res, MYSQLI_ASSOC);
            $act = mysqli_num_rows($res);
            $idCompra = $var['id'];
            $proveedor = $var['idProveedor'];
            //$proveedorName = $var['proveedor'];
            // $formaPago = $var['formaPago'];
            $nota = $var['nota'];
            $estatus = $var['estatus'];
            $letrasDesc = strlen($nota);
            $descripcion = ($letrasDesc>0) ? $nota : ' ';
            if ($idCompra == "") {
                $idCompra = 0;

               
            }
            if ($idCompra != 0) {
                $disabledPro = "";
            } else {
                $disabledPro = "disabled";
            }
            if ($idCompra != 0) {
               
                $disabledBtn = "disabled";
            } else {
                $disabledBtn = "";
            }
            echo '<input type="hidden" value="' . $idCompra . '" id="identCompra">';


            $sql = "SELECT * FROM proveedores WHERE estatus=1 ORDER BY nombre";
            $resProv = mysqli_query($link, $sql) or die("Problemas al enlistar Proveedores.");

            $listaProv = '';
            while ($datos = mysqli_fetch_array($resProv)) {
                $activeProv = ($datos['id'] == $proveedor) ? 'selected' : '';
                $listaProv .= '<option value="' . $datos['id'] . '" ' . $activeProv . '>' . $datos['nombre'] . '</option>';
            }
            ?>

            <?php
            $sql = "SELECT * FROM productos WHERE estatus=1 ORDER BY descripcion";
            $resProd = mysqli_query($link, $sql) or die("Problemas al enlistar Productos.");

            $listaProd = '';
            while ($datos = mysqli_fetch_array($resProd)) {
                $activeProd = ($datos['id'] == $buscaProv) ? 'selected' : '';
                $listaProd .= '<option value="' . $datos['id'] . '" ' . $activeProd . '><b>' . $datos['codBarra'] . '</b>   -' . $datos['descripcion'] . '</option>';
            }
            $tablaTotales="";
            ?>

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
                    <div class="col-md-4">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Registro del proveedor</h4>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label col-form-label">Proveedor</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <select class="select2 form-control custom-select" name="compraProv" id="compraProv" onchange="" style="width: 80%;" <?= $disabledBtn ?>>

                                            <option value=""> Todos los Proveedores</option>
                                            <?= $listaProv ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3">
                                        <button title="Agregar Proveedor" id="agregarProv" class="btn btn-circle bg-<?= $pyme; ?>" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" onclick="iniciaCompra();" type="button" <?= $disabledBtn ?>><i class="fas  fas fa-check"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="control-label col-form-label">Cantidad</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="number" class="form-control" min="1" name="rcant" value="1" id="rcant" placeholder="" aria-describedby="basic-addon7" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">

                                        <div class="form-group">
                                            <label for="inputEmail3" class="control-label col-form-label">Producto</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="finVig1"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <select class="select2 form-control custom-select" name="compraProd" id="compraProd" onchange="regProducto(<?= $idCompra ?>, this.value);" style="width: 80%;" <?= $disabledPro ?>>

                                                    <option value=""> Todos los Productos</option>
                                                    <?= $listaProd ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>
                        <!--end .table-responsive -->
                    </div>

                    <div class="col-md-8">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Productos a Comprar</h4>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table product-overview ">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Descripción</th>
                                                <th>Costo Unitario</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-center">Importe</th>
                                                <th class="text-center">Eliminar</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT det.*,prod.descripcion as producto
                                        FROM detcompras det
                                        INNER JOIN productos prod ON det.idProducto=prod.id
                                        WHERE det.idCompra='$idCompra'
                                        ORDER BY id DESC";
                                            //echo $sql;
                                            $total = 0;
                                            $it = 1;
                                            $res = mysqli_query($link, $sql) or die('<option value="">Error de Consulta </option>' . $sql);
                                            while ($datos = mysqli_fetch_array($res)) {
                                                $importe = $datos['costoUnitario'] * $datos['cantidad'];
                                                $subtotal = $subtotal + $importe;
                                                $iva = $subtotal * 0.16;
                                                $total = $subtotal + $iva;
                                                $tablaTotales="
                                                <tr>
                                                <td></td>
                                                <td colspan='3' align='' class='text-$pyme'>
                                                    <h5>SUBTOTAL</h5>
                                                </td>
                                                <td colspan='2'><b>
                                                        <h5>$ ".number_format($subtotal, 2, '.', ',')."</h5>
                                                    </b></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan='3' align='' class='text-$pyme'>
                                                    <h5>IVA</h5>
                                                </td>
                                                <td colspan='2'><b>
                                                        <h5>$ ".number_format($iva, 2, '.', ',')."</h5>
                                                    </b></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan='3' align='' class='text-$pyme'>
                                                    <h3>Total</h3>
                                                </td>
                                                <td colspan='2'><b>
                                                        <h3>$ ".number_format($total, 2, '.', ',')."</h3>
                                                    </b></td>
                                            </tr>
                                                ";
                                                echo '<tr id="tr' . $datos['id'] . '">
                                                <td>' . $it . '</td>
                                              <td>' . $datos['producto'] . '</td>
                                              <td> <div class="input-group px-3 mb-3">
                                              <div class="input-group-prepend">
                                                  <span class="input-group-text" id="basic-addon7">$</span>
                                              </div>
                                              <input value="' . $datos['costoUnitario'] . '" type="number" id="precio' . $datos['id'] . '" class="form-control" onchange="cambiaPrecio(' . $datos['id'] . ',this.value)";></div></td>
                                              <td><input id="producto' . $datos['id'] . '" type="number" class="form-control" onchange="cambiaCant(' . $datos['id'] . ',this.value);" value="' . $datos['cantidad'] . '"></td>
                                              <td>$' . number_format($importe, 2, '.', ',') . '</td>
                                              <td><center class="text-success"  data-toggle="tooltip" data-placement="top"
                                              title="" data-original-title="Eliminar" >
                                              <button class="btn-circle btn-danger" style="color:#fff, box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62);"
                                              onClick="eliminaProducto(' . $datos['id'] . ');"><i class="fas  fas fa-trash"></i></button></center></td>
                                          </tr>';
                                                $it++;
                                            }
                                            ?>
                                            <tr>
                                                <?=$tablaTotales;?>
                                        
                                        </tbody>
                                    </table>

                                </div>
                                <hr>
                                <form action="../funciones/generaCompra.php" method="post">

                                    <input type="hidden" name="ident" id="ident" value="<?= $idCompra; ?>">
                                    <input type="hidden" name="subtotal" id="subtotal" value="<?= $subtotal; ?>">
                                    <input type="hidden" name="total" id="total" value="<?= $total; ?>">
                                    <input type="hidden" name="iva" id="iva" value="<?= $iva; ?>">


                                    <label for="rnotas" class="control-label col-form-label px-3">Notas de la Compra</label>

                                    <div class="input-group px-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon7">$</span>
                                        </div>
                                        <input value="<?= $descripcion; ?>" type="text" class="form-control" name="nota" id="nota" >
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success ">Finalizar Compra</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
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
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>
    <!--This page JavaScript -->
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/moment/moment.js"></script>
    <script src="../assets/libs/footable/js/footable.min.js"></script>
    <script src="../assets/libs/footable/js/footable.min.js"></script>
    <script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker-ES.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#demo-foo-row-toggler').footable({
                "toggleColumn": "last",

            });



            <?php
            #include('../funciones/basicFuctions.php');
            #alertMsj($nameLk);

            if (isset($_SESSION['LZmsjInfoCompra'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoCompra'] . "');";
                unset($_SESSION['LZmsjInfoCompra']);
            }
            if (isset($_SESSION['LZmsjSuccessCompra'])) {

                echo "
               // $('#compraProv').val('" . $_SESSION['LZmsjSuccessCompra'] . "').trigger('change');
                //$('#compraProv').attr('disabled',true);
              //  $('#compraProd').attr('disabled',false);
                $('#compraProd').focus();
            

                notificaSuc('" . $_SESSION['LZmsjSuccessCompra'] . "');";
                unset($_SESSION['LZmsjSuccessCompra']);
            }
            ?>
        }); // Cierre de document ready

        function iniciaCompra() {
            idProveedor = $('#compraProv option:selected').val();
            //  $('<form action="configuraProducto.php" method="POST"><input type="hidden" name="ident" value="'+ident+'"></form>').appendTo('body').submit();

            location.href = "../funciones/altaCompra.php?ident=" + idProveedor;
        }

        function regProducto(idCompra, prod) {
            cant = $('#rcant').val();
            if ($("#producto").val() != "") {
                location.href = "../funciones/detalleCompra.php?idCompra=" + idCompra + "&idProveedor=<?= $proveedor; ?>&producto=" + prod + "&cant=" + cant;
            }
        };

        function limpiaCadena(dat, id) {
            //alert(id);
            dat = getCadenaLimpia(dat);
            $("#" + id).val(dat);
        }

        function eliminaProducto(id) {
            location.href = "../funciones/borraDetCompra.php?id=" + id;
        };

        function cambiaCant(id, cant) {
            location.href = "../funciones/editaCompraCant.php?id=" + id + "&cant=" + cant;
        };

        function cambiaPrecio(id, precio) {
            location.href = "../funciones/editaCompraPrecio.php?id=" + id + "&costo=" + precio;
        };

        function listaDeptos() {
            //  var mensaje = 'Mensaje';
            $.post("../funciones/listarDeptos.php", {},
                function(respuesta) {
                    $("#validation").html(respuesta);
                });
        }
    </script>

</body>

</html>