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

    <link rel="stylesheet" href="../assets/extra-libs/taskboard/css/lobilist.css">
    <link rel="stylesheet" href="../assets/extra-libs/taskboard/css/jquery-ui.min.css">
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
            $sucursal = $_SESSION['LZFidSuc'];

            $userReg = $_SESSION['LZFident'];
            $sql = "SELECT * FROM ajustes WHERE idUserReg='$userReg' AND idSucursal='$sucursal' AND estatus='1'";
            // echo $sql;
            $res = mysqli_query($link, $sql) or die("Error de Consulta:" . $sql);
            $var = mysqli_fetch_array($res, MYSQLI_ASSOC);
            $act = mysqli_num_rows($res);
            $idAjuste = (isset($var['id']) and $var['id'] != '') ? $var['id'] : '0';

            //$idAjuste = (isset($var['id'])? $var['id']:0 ;
            // echo "El ID de mi Ajuste es: " . $idAjuste;
            $tipo = $var['tipo'];
            if ($tipo != 0) {
                $disabledBtn = "disabled";
            } else {
                $disabledBtn = "";
            }
            echo '<input type="hidden" value="' . $idAjuste . '" id="identAjs">';
            ?>
            <?php
            $sql = "SELECT productos.* FROM productos INNER JOIN stocks ON stocks.idProducto=productos.id AND
            stocks.idSucursal='$sucursal'
             WHERE estatus=1 ORDER BY descripcion";
            $resProd = mysqli_query($link, $sql) or die("Problemas al enlistar Productos.");

            $listaProd = '';
            while ($datos = mysqli_fetch_array($resProd)) {
                $listaProd .= '<option value="' . $datos['id'] . '">' . $datos['descripcion'] . '</option>';
            }
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
                    <div class="col-md-12">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Registro de Entradas y Salidas</h4>
                            </div>

                            <div class="card-body">


                                <div class="row mx-3">

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="inputEmail3" class="control-label col-form-label">Producto</label>
                                            <div class="input-group">
                                               
                                                <select class="select2 form-control custom-select" name="prod" id="producto"  style="width: 93%;">

                                                    <option value=""> Agrega más productos</option>
                                                    <?= $listaProd ?>

                                                </select>
                                            </div>
                                        </div>
                                        <!--Cierra form-->
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputEmail3" class="control-label col-form-label">Tipo de Movimiento</label>
                                        <div class="input-group">
                                            
                                            <select class="form-control custom-select" name="movimiento" id="movimiento" onchange="regProducto(<?= $idAjuste ?>, this.value);" style="width: 93%;">

                                                <option value=""> Selecciona el Tipo de Movimiento</option>
                                                <option value="1">Entrada</option>
                                                <option value="2">Salida</option>

                                            </select>
                                        </div>





                                    </div>
                                    <!--Cierra col-->

                                </div>
                                <!--Cierra ROW-->
                                <?php $altura = ($idAjuste == 0) ? "height: 50px;" : "height: 1000px;"; ?>
                                <div class="row mx-3" style='<?= $altura; ?>'>
                                    <div class="col-md-12">
                                        <div class="row" id="todo-lists-basic-demo"></div>
                                    </div>
                                    <!--Cierra col-->
                                </div>
                                <!--Cierra ROW-->
                                <div class="row  p-3">
                                    <div class="col-md-12">
                                        <form id="formAjustes" method="post">
                                            <input type="hidden" name="ident" id="identAjusteFinal" value="<?= $idAjuste; ?>">
                                            <label for="notas" class="control-label col-form-label">Notas Adicionales</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="finVig1"><i class=" fas fa-pencil-alt"></i></span>
                                                </div>
                                                <textarea class="form-control" id="notas" name="descripcion" rows="3" required></textarea>
                                            </div>
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
                                            <br>
                                            <div id="desbloquear-btn1">
                                                <div class="text-right">
                                                    <button type="button" onclick="cancelarEdicion();" class="btn btn-danger ">Cancelar</button>

                                                    <button type="submit" class="btn btn-success ">Generar Ajuste</button>

                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                            </div>
                            <!--Cierra card-body-->
                        </div>
                        <!--Cierra card-->
                    </div>
                    <!--Cierra col-12-->
                </div>
                <!--Cierra ROW-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-<?= $pyme; ?>">
                            <div class="card-header bg-<?= $pyme; ?>">
                                <h4 class="m-b-0 text-white">Estado de Solicitudes</h4>
                            </div>
                            <div class="card-body">
                                <div class="text-right">
                                    <a class="btn btn-circle bg-<?= $pyme ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Historial" style=" box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff" href="historialAjs.php">
                                        <i class="fas fa-tasks"></i></a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table product-overview " id="tabla_ajustes">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Ticket</th>
                                                <th class="text-center">Sucursal</th>
                                                <th class="text-center">Usuario</th>
                                                <th class="text-center">Descripción</th>
                                                <th class="text-center">Fecha Solicitud</th>
                                                <th class="text-center">Acción</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

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


    </div>
    <div id="espacio_modal"></div>
    <!-- /.modal -->
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
    <script src="../assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved.js"></script>
    <script src="../assets/extra-libs/taskboard/js/jquery-ui.min.js"></script>
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
    <script src="../assets/extra-libs/taskboard/js/lobilist.js"></script>
    <script src="../assets/extra-libs/taskboard/js/lobibox.min.js"></script>
    <script src="../assets/extra-libs/taskboard/js/entrada_salidas.js"></script>
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
    <script src="../assets/tablasZafra/datatable_configAjste.js"></script>
    <script>
        <?php
        //AJUSTE SOLICITUDES
        $sqlSolicitud = "SELECT ajs.id,
        suc.nombre AS sucursal,
        CONCAT( usrio.nombre, ' ', usrio.appat, ' ', usrio.apmat ) AS usuarioSolicita,
        ajs.descripcion,
        DATE_FORMAT( ajs.fechaReg, '%d/%m/%Y %H:%i:%s' ) AS fechaSolicitud,
        CASE ajs.estatus
	WHEN 2 THEN
		CONCAT(\"<center><button type='button' onclick='verestado(\",ajs.id,\");' class='btn ink-reaction btn-icon-toggle btn-circle btn-circle-tablita btn-primary'><i class='fas fa-spinner'></i></button></center>\")
	WHEN 3 THEN
		CONCAT(\"<center><button type='button' onclick='verestado(\",ajs.id,\");' class='btn ink-reaction btn-icon-toggle btn-circle btn-circle-tablita btn-success'><i class='fas fa-check'></i></button></center>\")
	WHEN 4 THEN
		CONCAT(\"<center><button type='button' onclick='verestado(\",ajs.id,\");' class='btn ink-reaction btn-icon-toggle btn-circle btn-circle-tablita btn-danger'><i class='fas fa-times'></i></button></center>\")
END AS button
        FROM
        ajustes ajs
        INNER JOIN sucursales suc ON ajs.idSucursal = suc.id AND ajs.idSucursal='$sucursal'
        INNER JOIN segusuarios usrio ON ajs.idUserReg = usrio.id WHERE ajs.estatus!='5' AND ajs.estatus!='1' AND ajs.idUserAplica='0'";

        $res = mysqli_query($link, $sqlSolicitud) or die('<option value="">Error de Consulta </option>');
        $arreglo['data'] = array();

        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($res);
        echo 'var datsJsonSolicitud= ' . $var . ';';

        //AJUSTES DE ENTRADA
        $sqlEntrada = "SELECT
        det.id AS id,
        CONCAT(\"<div class='row'><button class='btn btn-circle bg-success' onclick='cambiartipo(\",det.id,\",)' style=' box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff'><i class='fas fa-share'></i></button><div class='col-md-6'>\",prod.descripcion,\"</div>\") AS title,
        CONCAT(\"<div class='col-md-3'><input value='\",det.cantidad,\"' type='number' id='cantEnvio\",det.id, \"' class='form-control' min='1' onchange='cambiaAjuste( \",det.id, \", this.value);'></div></div>\"   ) AS description
    FROM
        ajustes aj
        LEFT JOIN detajustes det ON det.idAjuste = aj.id
        INNER JOIN productos prod ON det.idProducto = prod.id
    WHERE
        det.idAjuste = '$idAjuste'
        AND aj.estatus = 1
        AND aj.idUserReg = '$userReg'
        AND det.tipo = '1'
    ORDER BY
        det.id ASC";
        $total = 0;
        $it = 1;

        $res = mysqli_query($link, $sqlEntrada) or die('<option value="">Error de Consulta </option>');
        $arreglo['data'] = array();

        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($resPro);
        echo 'var datsJsonEntrada= ' . $var . ';';


        //AJUSTES DE SALIDA
        $sqlEntrada = "SELECT
        det.id AS id,
        CONCAT(\"<div class='row'> <button class='btn btn-circle bg-danger' onclick='cambiartipo(\",det.id,\",)' style=' box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff'><i class='fas fa-reply'></i></button><div class='col-md-6'>\",prod.descripcion,\"</div>\") AS title,
        CONCAT(\"<div class='col-md-3'><input value='\",det.cantidad,\"' type='number' id='cantEnvio\",det.id, \"' class='form-control' min='1' onchange='cambiaAjuste( \",det.id, \", this.value);'></div></div>\"   ) AS description
    FROM
        ajustes aj
        LEFT JOIN detajustes det ON det.idAjuste = aj.id
        INNER JOIN productos prod ON det.idProducto = prod.id
    WHERE
        det.idAjuste = '$idAjuste'
        AND aj.estatus = 1
        AND aj.idUserReg = '$userReg'
        AND det.tipo = '2'
    ORDER BY
        det.id ASC";
        $total = 0;
        $it = 1;

        $res = mysqli_query($link, $sqlEntrada) or die('<option value="">Error de Consulta </option>');
        $arreglo['data'] = array();

        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($resPro);
        echo 'var datsJsonSalida= ' . $var . ';';
        echo 'var pyme = "' . $pyme . '";';
        ?>
        //console.log(datsJsonEntrada.data);
        $(document).ready(function() {

            $('#tablatrasp').DataTable({});
            //QUITAR ENVIO DE FORMULARIOS
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }


            <?php


            if (isset($_SESSION['LZmsjInfoAjuste'])) {
                echo "notificaBad('" . $_SESSION['LZmsjInfoAjuste'] . "');";
                unset($_SESSION['LZmsjInfoAjuste']);
            }
            if (isset($_SESSION['LZmsjSuccessAjuste'])) {

                echo "
               notificaSuc('" . $_SESSION['LZmsjSuccessAjuste'] . "');";
                unset($_SESSION['LZmsjSuccessAjuste']);
            }
            ?>
        }); // Cierre de document ready

        function iniciaAjuste() {
            tipo = $('#tipoAjuste option:selected').val();
            location.href = "../funciones/altaAjuste.php?tipo=" + tipo;
        }

        function regProducto(idAjuste, movimiento) {
            // alert(prod);
            var producto=$("#producto").val();
            if (producto != "" && movimiento!="") {
                location.href = "../funciones/detalleAjuste.php?idAjuste=" + idAjuste + "&producto=" + producto+"&movimiento="+movimiento;
            }
        };

  

        function cargalotes(ident, cantidad, idDetAjuste) {
            var debug = 0;
            if (debug == 1) {
                console.log("IDENT: " + ident);
                console.log("CANTIDAD: " + cantidad);
                console.log("ID DET Ajuste: " + idDetAjuste);

            }
            if ($('#loteo_manual-' + ident).is(':checked')) {

                $.post("../funciones/cargaLoteoManualAjustes.php", {
                        ident: ident,
                        cantidadTotal: cantidad,
                        idDetAjuste: idDetAjuste
                    },
                    function(respuesta) {
                        $(".loteo-" + ident).remove();
                        $("#tr" + ident).after(respuesta);
                    });
            } else {
                $.post("../funciones/eliminaDetLotesAjuste.php", {

                        idDetAjuste: idDetAjuste
                    },
                    function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {
                            /*  notificaSuc(resp[1]);*/
                            $(".loteo-" + ident).remove();
                        } else {
                            notificaBad(resp[1]);
                        }
                    });


            }
        }

        function cancelarEdicion() {
            ident = $('#identAjusteFinal').val();

            if (ident != "") {
                $.post("../funciones/cancelaEdicionAjuste.php", {
                        ident: ident,
                    },
                    function(respuesta) {
                        var resp = respuesta.split('|');
                        if (resp[0] == 1) {
                            notificaSuc(resp[1]);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else if (resp[0] == 0) {
                            notificaBad(resp[1]);
                        }
                    });
                // location.href = "../funciones/cancelaEdicionTraspasos.php?ident=" + ident;
            }
        };

        function ejecutandoCarga(identif) {
            var selector = 'DIV' + identif;
            var finicio = $('#fStart').val();
            var ffin = $('#fEnd').val();

            $.post("../funciones/cargaContenidoAjste.php", {
                    ident: identif
                },
                function(respuesta) {
                    $("#" + selector).html(respuesta);
                });

        }

        function limpiaCadena(dat, id) {
            //alert(id);
            dat = getCadenaLimpia(dat);
            $("#" + id).val(dat);
        }

        function eliminaProducto(id) {
            location.href = "../funciones/borraDetAjuste.php?id=" + id;
        };

        function cambiaCant(id, cant) {
            location.href = "../funciones/editaCompraCant.php?id=" + id + "&cant=" + cant;
        };


        $("#formAjustes").submit(function(event) {
            event.preventDefault();
            id = $("#identAjusteFinal").val();
            val_descripcion = $("#notas").val();

            $.post("../funciones/guardaAjusteFinal.php", {
                    ident: id,
                    descripcion: val_descripcion
                },
                function(respuesta) {

                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {

                        bloqueoBtn("bloquear-btn1", 1);

                        notificaSuc(resp[1]);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        //  alert(respuesta);
                        bloqueoBtn("bloquear-btn1", 2);
                        notificaBad(resp[1]);
                    }
                });


        });


        function ajustar(idAjuste) {
            














            $.post("../funciones/ajustarSolicitud.php",{idAjuste:idAjuste},
                function(respuesta) {
                   
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        var id = $('#id_ajuste').val();
                        

                        notificaSuc(resp[1]);
                        setTimeout(function() {
                            $('<form action="../funciones/ticketLanzaAjuste.php" method="POST"><input type="hidden" name="idAjuste" value="' + id + '"></form>').appendTo('body').submit();

                            //location.href = "reimprimeTickets.php?idVenta=" + id + "&tipoTicket=lanzaAjuste";

                        }, 1000);
                    } else {
                        //  alert(respuesta);
                        // bloqueoBtn("bloquear-btn2", 2);
                        notificaBad(resp[1]);
                    }
                });



        }

        function cambiartipo(id) {

            $.post("../funciones/cambiaEstatusAjuste.php", {
                    ident: id,
                },
                function(respuesta) {

                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        location.reload();
                        // notificaSuc(resp[1]);
                        /* setTimeout(function() {
                             location.reload();
                         }, 1000);*/
                    } else {
                        //notificaBad(resp[1]);
                    }
                });
        }


        function verestado(ident) {

            $.post("../funciones/formModalAjste.php", {
                    ident: ident,
                    color: '<?= $pyme ?>'
                },
                function(respuesta) {
              
                    $("#espacio_modal").html(respuesta);
                    $('#verestatusSolicitud').modal('show');
                   
                });

        }

        function notificarCancelacion(ident) {
            $.post("../funciones/notificaCancelAjuste.php", {
                    ident: ident,
                },
                function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        notificaSuc(resp[1]);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (resp[0] == 0) {
                        notificaBad(resp[1]);
                    }
                });

        }
        function revisarCant(idProducto, idLote, valor) {
            $("#area_resultado_" + idLote).html('<i class="fa-spin fas fa-spinner"></i>');

            var debug = 0;

            clase = ".cant_loteo_" + idProducto;
            var sumaTotales = 0;
            var cantMaxXLote = $("#lote_" + idLote).data("maxima");
            var cantTotalEnvio = $("#lote_" + idLote).data("total");
            var valorAgregado = valor;
            var idDetAjuste = $("#lote_" + idLote).data("detajuste");
            var idLote = idLote;

            if (debug == 1) {
                console.log("CANTIDAD MAXIMA A LLEGAR AL LOTE: " + cantMaxXLote);
                console.log("CANTIDAD TOTAL: " + cantTotalEnvio);

            }

            $(clase).each(function() {
                sumaTotales += parseFloat($(this).val());
            });

            if (debug == 1) {
                console.log("CANTIDAD QUE SUMA LOS LOTES: " + sumaTotales);
            }

            /* COMPARACION DE VALIDACION */
            if (cantMaxXLote < valorAgregado) {
                $("#lote_" + idLote).val(0);
                notificaBad("Verifica las Asignaciones de tus Lotes");
                $("#lote_" + idLote).focus();
                $("#area_resultado_" + idLote).html('');
                dispararLotes(idLote, 0, idDetAjuste, 0);


            } else {
                if (cantTotalEnvio < sumaTotales) {

                    $("#lote_" + idLote).val(0);
                    notificaBad("La suma de los Lotes excede la cantidad a Enviar");
                    $("#lote_" + idLote).focus();
                    $("#area_resultado_" + idLote).html('');
                    dispararLotes(idLote, 0, idDetAjuste, 0);



                } else {
                    if (debug == 1) {
                        console.log("ID LOTE : " + idLote);
                        console.log("CANTIDAD : " + valorAgregado);
                        console.log("ID DET TRASP : " + idDetAjuste);
                    }

                    dispararLotes(idLote, valorAgregado, idDetAjuste, 1);
                }

            }
        }


        function dispararLotes(idLote, valorAgregado, idDetAjuste, operacion) {
            $.post("../funciones/registraDetLotesAjuste.php", {
                    idLote: idLote,
                    cantidad: valorAgregado,
                    idDetAjuste: idDetAjuste
                },
                function(respuesta) {
                    var resp = respuesta.split('|');
                    if (resp[0] == 1) {
                        if (operacion == 1) {
                            notificaSuc(resp[1]);
                        }
                        $("#area_resultado_" + idLote).html('<i class="fas fa-check text-success"></i>')
                    } else {
                        notificaBad(resp[1]);
                        $("#area_resultado_" + idLote).html('<i class="fas fa-times text-danger"></i>')

                    }
                });
        }
























        function cambiaAjuste(id, cant) {
            location.href = "../funciones/editaAjusteCant.php?id=" + id + "&cant=" + cant;
        };

        function listaDeptos() {
            //  var mensaje = 'Mensaje';
            $.post("../funciones/listarDeptos.php", {},
                function(respuesta) {
                    $("#validation").html(respuesta);
                });
        }
        var selectproductos = `
        <select class="select2 form-control custom-select producto" name="prod"  onchange="regProducto(<?= $idAjuste ?>, this.value);" style="width: 100%;">
        <option value=""> Agrega más productos</option>
         <?= $listaProd ?>
        </select>`;
    </script>

</body>

</html>