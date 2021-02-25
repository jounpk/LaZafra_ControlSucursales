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
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
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
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
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
                <?php
                $sqlConCot = "SELECT *,DATE_FORMAT(fechaReg,'%d-%m-%Y') AS fechaRegistro FROM cotizaciones WHERE estatus = '1' AND idSucursal = '$idSucursal' AND idUserReg = '$idUser' LIMIT 1";
                $resConCot = mysqli_query($link,$sqlConCot) or die('Problemas al consultar las cotizaciones, notifica a tu Administrador.');
                $cantCot = mysqli_num_rows($resConCot);


                  if ($cantCot < 1) {
                 ?>
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <div class="card border-<?=$pyme;?>">
                        <div class="card-header bg-<?=$pyme;?>">
                          <h4 class="card-title text-white">Selecciona un Cliente</h4>
                        </div>
                        <div class="card-body">
                          <div class="col-md-12 text-center">
                            <a class="text-danger"><b>NOTA:</b></a> Si el cliente <u class="text-danger">no está registrado en el sistema</u>, selecciona la pestaña de "<u class="text-info">No registrados</u>" para poder agregarlo a la Cotización
                          </div>
                          <ul class="nav nav-pills m-t-30 m-b-30">
                               <li class=" nav-item"> <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Registrados</a> </li>
                               <li class="nav-item"> <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">No registrados</a> </li>
                           </ul>
                           <div class="tab-content br-n pn">
                               <div id="navpills-1" class="tab-pane active">
                                   <div class="row">
                                       <div class="col-md-10">
                                         <form role="form" autocomplete="off" method="post" action="../funciones/capturaClienteEnCotizacion.php">
                                         <?php
                                       #  /*
                                               $sql="SELECT id, nombre, apodo FROM clientes WHERE estatus = '1' $filtroCliente";
                                             #  echo $sql;
                                               $res=mysqli_query($link,$sql) or die ("Problemas al consultar los Códigos de Barra, notifica a tu Administrador.");;
                                               $cont = 0;
                                               while($rows=mysqli_fetch_array($res)){

                                                 if ($cont == 0) {
                                                   #<select class="select2 form-control custom-select select2-hidden-accessible" style="width: 100%; height:100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                   echo '<select class="select2 form-control custom-select" id="cliente1" name="clienteRegistrado" style="width: 100%; height:100%;" required>';
                                                   echo '<option value="">Ingresa el Nombre del cliente</option>';
                                                 }
                                                 echo '<option value="'.$rows['id'].'">'.$rows['nombre'].' ('.$rows['apodo'].')</option>';
                                                 $cont++;

                                           }
                                           echo '</select>';
                                         #  */
                                         ?>
                                       </div>
                                       <div class="col-md-2">
                                         <button type="submit" class="btn btn-info">Agregar</button>
                                       </div>
                                     </form>
                                   </div>
                               </div>
                               <div id="navpills-2" class="tab-pane">
                                   <div class="row">
                                       <div class="col-md-10">
                                         <form role="form" autocomplete="off" method="post" action="../funciones/capturaClienteEnCotizacion.php">
                                           <input type="text" id="cliente2" name="clienteNoRegistrado" class="form-control" placeholder="Ingresa el nombre del Cliente" required>
                                       </div>
                                       <div class="col-md-2">
                                         <button type="submit" class="btn btn-info">Agregar</button>
                                       </div>
                                     </form>
                                   </div>
                               </div>

                           </div>
                        </div>
                    </div>
                  </div>
                 </div>

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

                 $filtroFecha = " AND v.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal'";

                 if (isset($_POST['estado']) && $_POST['estado'] > 1) {
                   $estado = $_POST['estado'];
                   $filtroEstado = " AND c.estatus = '$estado'";
                 } else {
                   $estado = '';
                   $filtroEstado = ' AND c.estatus > 1';
                 }
                 $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = '';
                 switch ($estado) {
                   case '2':
                     $sel2 = 'selected';
                     break;
                   case '3':
                     $sel3 = 'selected';
                     break;
                   case '4':
                     $sel4 = 'selected';
                     break;
                   case '5':
                     $sel5 = 'selected';
                     break;

                   default:
                     $sel1 = 'selected';
                     break;
                 }
                  ?>

                 <div class="row">
                   <div class="col-md-12 col-lg-12">
                     <div class="card border-<?=$pyme;?>">
                         <div class="card-header bg-<?=$pyme;?>">
                           <h4 class="card-title text-white">Historial de cotizaciones</h4>
                         </div>
                         <div class="card-body" id="idCard">
                           <div class="row">
                             <div class="col-md-2"></div>
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
                             <div class="col-md-5">
                               <div class="col-md-12">
                                   <div class="row">

                                   <div class="col-md-12">
                                     <h5 class="m-t-30 text-<?=$pyme;?>">Selecciona un Estatus:</h5>
                                   </div>
                                   <div class="col-md-5">
                                   </div>
                                 </div>
                               </div>
                               <div class="row">
                               <div class="col-md-8">
                                 <select class="select2 form-control custom-select" name="estado" id="estado" style="width: 95%; height:100%;">
                                   <option value="" <?=$sel1;?>>Selecciona el estatus</option>
                                   <option value="2" <?=$sel2;?>>Por Autorizar</option>
                                   <option value="3" <?=$sel3;?>>Autorizado</option>
                                   <option value="4" <?=$sel4;?>>Rechazados</option>
                                   <option value="5" <?=$sel5;?>>Cancelado por el Usuario</option>
                                 </select>
                               </div>
                               <div class="col-md-4">
                                 <button type="submit" class="btn btn-<?=$pyme;?>">Buscar</button>
                               </div>
                             </form>
                             </div>
                           </div>

                           <div class="col-md-1"></div>
                           <div class="col-md-12">
                             <br>
                             <br>
                             <?php
                              $sqlHistorial = "SELECT c.*, CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomUsu, scs.nombre AS nomSucursal, cli.correo AS mailCliente,
                                                cl.nombre AS name_cliente
                                                FROM cotizaciones c
                                                INNER JOIN sucursales scs ON c.idSucursal = scs.id
                                                LEFT JOIN clientes cl ON c.idCliente=cl.id

                                                INNER JOIN segusuarios u ON c.idUserReg = u.id
                                                LEFT JOIN clientes cli ON c.cliente = cli.nombre
                                                WHERE c.fechaReg BETWEEN '$fechaInicial' AND '$fechaFinal' AND c.idSucursal = '$idSucursal' $filtroEstado ORDER BY c.fechaReg DESC";
                              #echo '$sqlHistorial: '.$sqlHistorial;
                              $resHistorial = mysqli_query($link,$sqlHistorial) or die('Problemas al consultar las cotizaciones, notifica a tu Administrador.');
                              ?>
                             <div class="table-responsive">
                               <table class="table product-overview" id="zero_config">
                                 <thead class="text-dark">
                                     <tr>
                                         <th class="text-center">#</th>
                                         <th class="text-center">Folio</th>
                                         <th>Sucursal</th>
                                         <th>Atendió</th>
                                         <th class="text-center">Cliente</th>
                                         <th class="text-center">Total</th>
                                         <th class="text-center">Fecha</th>
                                         <th class="text-center">Estado</th>
                                         <th class="text-center">Enviado por Correo</th>
                                         <th class="text-center">Acciones</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                   <?php
                                   $no = 0;
                                      while ($d = mysqli_fetch_array($resHistorial)) {
                                        if($d['cliente']!=''){
                                          $cliente=$d['cliente'];
                                         }else if($d['name_cliente']!=''){
                                           $cliente=$d['name_cliente'];
                                         }
                                        ++$no;
                                        switch ($d['estatus']) {
                                          case '2':
                                            $estado = 'Pendiente';
                                            $color = '';
                                            $btnAccion = '
                                                          <a href="../funciones/imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-success btn-square" title="Imprimir Ticket" disabled><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-danger btn-square" title="Imprimir PDF" disabled><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;
                                          case '3':
                                            $estado = 'Autorizado';
                                            $color = 'class="table-success"';
                                            $btnAccion = '
                                                          <button type="button" onClick="enviaCotizacion('.$d['id'].');" class="btn btn-outline-info btn-sm btn-square muestraSombra" title="Enviar por correo al Cliente"><i class="fas fa-envelope"></i></button>
                                                          <a href="../funciones/imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-success btn-square muestraSombra" title="Imprimir Ticket"><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-danger btn-square muestraSombra" title="Imprimir PDF"><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;
                                          case '4':
                                            $estado = 'Rechazado';
                                            $color = 'class="table-danger"';
                                            $btnAccion = '
                                                          <a href="../funciones/imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-success btn-square" title="Imprimir Ticket" disabled><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-danger btn-square" title="Imprimir PDF" disabled><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;

                                          default:
                                            $estado = 'Cancelado';
                                            $color = 'class="table-warning"';
                                            $btnAccion = '
                                                          <a href="../funciones/imprimeTicketCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-success btn-square" title="Imprimir Ticket" disabled><i class="fas fa-file-alt"></i></a>
                                                          <a href="../imprimePdfCotizacion.php?idCotizacion='.$d['id'].'" target="_blank" class="btn btn-sm btn-outline-danger btn-square" title="Imprimir PDF" disabled><i class="fas fa-file-pdf"></i></a>
                                                          ';
                                            break;
                                        }
                                        if ($d['enviado'] == 1) {
                                          $btnEnviado = '<a class="text-warning" title="Pendiente"><i class="fas fa-exclamation-triangle"</a>';
                                        } else {
                                          $btnEnviado = '<a class="text-success" title="Enviado"><i class="fas fa-check"</a>';
                                        }
                                        echo '
                                            <tr '.$color.'>
                                                <td class="text-center">'.$no.'</td>
                                                <td class="text-center">'.$d['folio'].'</td>
                                                <td>'.$d['nomSucursal'].'</td>
                                                <td>'.$d['nomUsu'].'</td>
                                                <td>'.$cliente.'</td>
                                                <td class="text-center">$ '.number_format($d['montoTotal'],2,'.',',').'</td>
                                                <td class="text-center">'.$d['fechaReg'].'</td>
                                                <td class="text-center">'.$estado.'</td>
                                                <td class="text-center">'.$btnEnviado.'</td>
                                                <td class="text-center">'.$btnAccion.'</td>
                                            </tr>
                                        ';
                                      }
                                    ?>

                                 </tbody>
                               </table>
                            </div>
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
               </div>
                 <?php
               } else {
                 $dat = mysqli_fetch_array($resConCot);
                 $idCot = $dat['id'];
                  ?>
                 <div class="row">
                   <div class="col-md-8 col-lg-8">
                     <div class="card border-<?=$pyme;?>">
                         <div class="card-header bg-<?=$pyme;?>">
                           <h4 class="card-title text-white">Productos</h4>
                         </div>
                         <div class="card-body">
                           <div class="row">
                             <div class="col-md-12">
                               <h5><b>Listado de Productos</b></h5>
                             </div>
                             <div class="col-md-12">
                               <?php
                             #  /*
                                     $sql="SELECT id,descripcion,prioridad FROM productos WHERE estatus = 1 ORDER BY descripcion ASC";
                                     //echo $sql;
                                     $res=mysqli_query($link,$sql);
                                     $cont = 0;
                                     while($rows=mysqli_fetch_array($res))
                                     {
                                       switch ($rows['prioridad']) {
                                         case 1:
                                         #  $punto = '<span class="label label-success label-rounded">(Alta)</span>';
                                         $punto = 'success';
                                           break;
                                         case 2:
                                         #  $punto = '<span class="label label-warning label-rounded">(Media)</span>';
                                         $punto = 'warning';
                                           break;


                                         default:
                                         #  $punto = '<span class="label label-default label-rounded">(Baja)</span>';
                                         $punto = 'default';
                                           break;
                                       }
                                       if ($cont == 0) {
                                         echo '<select class="select2 form-control custom-select template-with-knot-icons" id="producto" onChange="agregaProdEnCoti('.$idCot.',this.value)" style="width: 100%; height:100%;">';
                                         echo '<option value="">Ingresa el Nombre del Producto</option>';
                                       }
                                       echo '<option value="'.$rows['id'].'" data-flag="'.$punto.'">'.$rows['descripcion'].'</option>';
                                       $cont++;

                                 }
                                 echo '</select>';
                               #  */
                               $bloqueaBtnPago = ($idVenta > 0) ? '' : 'disabled' ;
                               ?>
                               <br>
                               <br>
                             </div>
                         </div>
                       </div>
                     </div>
                   </div>
                   <div class="col-md-4 col-lg-4">
                       <div class="card border-<?=$pyme;?>">
                           <div class="card-header bg-<?=$pyme;?>">
                             <h4 class="card-title text-white">Datos de la Cotización</h4>
                           </div>
                           <div class="card-body">
                             <div class="row">
                               <div class="col-md-4">
                                 <h5><b>Cliente:</b></h5>
                                 <h5><b>Registro:</b></h5>
                                 <h5><b>Folio:</b></h5>
                               </div>
                               <div class="col-md-8">
                                 <h4 class="text-info"><b><?=$dat['cliente'];?></b></h4>
                                 <h4 class="text-info"><b><?=$dat['fechaRegistro'];?></b></h4>
                                 <h4 class="text-success"><b><?=$dat['folio'];?></b></h4>
                               </div>
                             </div>

                           </div>
                       </div>
                   </div>
                   <div class="col-md-8 col-lg-8">
                     <div class="card">
                         <div class="card-body">
                           <div class="table-responsive">
                             <table class="table product-overview">
                               <thead>
                                 <tr>
                                   <th width="25px">#</th>
                                   <th>Descripción</th>
                                   <th class="text-center">Referencia</th>
                                   <th class="text-center">Precio</th>
                                   <th class="text-center">Cantidad</th>
                                   <th  class="text-center">Subtotal</th>
                                   <th  class="text-center">Acciones</th>
                                 </tr>
                               </thead>
                               <tbody>
                                 <?php
                                 $sqlConProd = "SELECT dc.*, p.costo,p.descripcion, s.cantActual, s.id AS idStock,p.id AS idProd
                              									FROM cotizaciones c
                              									INNER JOIN detcotizaciones dc ON c.id = dc.idCotizacion
                              									INNER JOIN productos p ON dc.idProducto = p.id
                              									LEFT JOIN stocks s ON dc.idProducto = s.idProducto AND s.idSucursal = c.idSucursal
                              									WHERE c.id = '$idCot'
                              									ORDER BY dc.id DESC";
                                 $resConProd = mysqli_query($link,$sqlConProd) or die('Problemas al consultar el detallado de la cotización, notifica a tu Administrador.');
                                 $cont = $total = 0;
                                 while ($pd = mysqli_fetch_array($resConProd)) {
                                   ++$cont;
                                   $idStock = $pd['idStock'];
                                   $idProd = $pd['idProd'];
                                   $buscar = ($pd['idStock'] > 0) ? "s.id = $idStock" : "p.id = $idProd";
                                   $min = $pd['costo'];
                                   $sqlPrecios = "SELECT DISTINCT(IF(scs.aplicaExtra = 1 AND a.tipoPrecio = 1,IF(scs.tipoExtra = 1,(a.precio + scs.cantExtra),(a.precio *(1 + (scs.cantExtra / 100)))),a.precio)) AS precioProd
                           											FROM stocks s
                           											INNER JOIN sucursales scs ON s.idSucursal = scs.id
                           											INNER JOIN productos p ON s.idProducto = p.id
                           											INNER JOIN (
                           											SELECT '1' AS tipoPrecio, precio, idProducto FROM preciosbase
                           											UNION
                           											SELECT '2' AS tipoPrecio, precio, idProducto FROM excepcionesprecio WHERE idSucursal = '$idSucursal'
                           											) a ON a.idProducto = p.id
                           											WHERE $buscar
                           											ORDER BY precioProd DESC";
                           				$resPrecios = mysqli_query($link,$sqlPrecios) or die('Problemas al consultar los precios, notifica a tu Administrador');
                                  $precioPd = 0;
                                  $referencia = '';
                                  $referencia .= '<select id="precios" class="form-control custom-select" onChange="cambiaPrecioProdCot('.$pd['id'].',this.value,'.$min.');">';
                    				      $referencia .= '<optgroup label="Precios">';
                           				while ($pr = mysqli_fetch_array($resPrecios)) {

                           						$precioPd = $pr['precioProd'];
                           					$p1 = ($precioPd == $pd['precioVenta']) ? 'selected' : '' ;
                           					if ($precioPd > 0) {
                           						$referencia .= '<option value="'.$precioPd.'" '.$p1.' class="text-right">$ '.number_format($precioPd,2,'.',',').'</option>';
                           					}

                           			}
                                $referencia .= '</optgroup>
                        						<optgroup label="Costo">';
                        				$referencia .= '<option value="'.$pd['costo'].'" class="text-right" disabled>$ '.number_format($pd['costo'],2,'.',',').'</option>
                        						</optgroup>
                        					</select>';
                                  $min2 = $min + 2;
                        					$precioProd = '<input type="number" min="'.$min2.'" class="text-right" value="'.$pd['precioVenta'].'" id="cant'.$pd['idVenta'].'" onChange="cambiaPrecioProdCot('.$pd['id'].',this.value,'.$min.');">';
                        					$cantidad = '<input type="number" min="0" max="'.$pd['cantActual'].'" class="form-control text-right" value="'.$pd['cantidad'].'" id="cant'.$pd['idVenta'].'" onChange="cambiaCantProdCot('.$pd['id'].',this.value);">';
                        					$subtotal = $pd['precioVenta'] * $pd['cantidad'];
                        					$acciones = '<div class="btn btn-outline-danger" onClick="eliminaProductoEnCoti('.$pd['id'].');"><i class="fas fa-trash"></i></div>';

                                  if ($pd['precioVenta'] <= $min) {
                                    $color = 'class="table-danger"';
                                  } else {
                                    $color = '';
                                  }
                                   echo '
                                      <tr '.$color.'>
                                        <td>'.$cont.'</td>
                                        <td>'.$pd['descripcion'].'</td>
                                        <td>'.$referencia.'</td>
                                        <td>'.$precioProd.'</td>
                                        <td>'.$cantidad.'</td>
                                        <td class="text-right">$'.number_format($subtotal,2,'.',',').'</td>
                                        <td>'.$acciones.'</td>
                                      </tr>
                                   ';
                                   $total += $subtotal;
                                 }
                                  ?>

                               </tbody>
                               <tfoot>
                                    <tr>
                                      <th rowspan="1" colspan="1"></th>
                                      <th rowspan="1" colspan="1"></th>
                                      <th rowspan="1" colspan="1"></th>
                                      <th rowspan="1" colspan="1"></th>
                                      <th rowspan="1" colspan="1" class="text-danger text-right"><h4><b>Total</b></h4></th>
                                      <th rowspan="1" colspan="2" class="text-info text-right"><h5><b>$ <?=number_format($total,2,'.',',');?></b></h5></th>
                                    </tr>
                                </tfoot>
                             </table>
                           </div>
                         </div>
                       </div>
                   </div>
                   <div class="col-md-4 col-lg-4">
                     <div class="card border-<?=$pyme;?>" id="divCorreos">
                         <div class="card-header bg-<?=$pyme;?>">
                           <h4 class="card-title text-white">Correos</h4>
                         </div>
                         <div class="card-body">
                           <?php
                              $sqlConMail = "SELECT * FROM detcotcorreos WHERE idCotizacion = '$idCot'";
                              $resConMail = mysqli_query($link,$sqlConMail) or die('Problemas al consultar los correos, notifica a tu Administrador.');
                              $detCon = mysqli_num_rows($resConMail);
                              $correos = '';
                              if ($detCon < 1) {
                                $correos = '<h4>No hay correos disponibles, <b class="text-danger">ingresa un correo.</b></h4>';
                              } else {
                                $correos .= '<ul>';
                                while ($mail = mysqli_fetch_array($resConMail)) {
                                  $correos .= '<li id="liCorreo-'.$mail['id'].'"><a href="JavaScript:void(0);" onClick="eliminaCorreo('.$mail['id'].','.$idCot.')" class="text-danger"><i class="fas fa-trash"></i></a> &nbsp;&nbsp;&nbsp;&nbsp;'.$mail['correo'].'</li>';
                                }
                                $correos .= '</ul>';
                              }
                            ?>
                           <div class="row" id="correos">
                               <?=$correos;?>
                           </div>
                           <br>
                           <div class="row">
                             <div class="col-md-9">
                                 <input type="text" class="form-control email-inputmask" id="newCorreo" placeholder="Ingresa el Correo" aria-describedby="Correo" name="newCorreo">
                             </div>
                             <div class="col-md-2">
                               <button type="button" class="btn btn-info" onclick="agregaCorreo(<?=$idCot;?>);">Agregar</button>
                             </div>
                           </div>
                           <br>
                           <br>
                           <div class="row">
                             <div class="col-md-12">
                               <div class="modal-footer">
                                 <form role="form" method="post" action="../funciones/cierraCotizacion.php">
                                   <input type="hidden" name="idCot" value="<?=$idCot;?>">
                                   <input type="hidden" name="total" value="<?=$total;?>">
                                   <input type="hidden" name="tipo" value="2">
                                     <button type="submit" class="btn btn-danger btn-outline" id="btnCancelaVenta" title="Cerrar Cotización en 0">Cancelar</button>
                                </form>

                                 <form role="form" method="post" action="../funciones/cierraCotizacion.php">
                                   <input type="hidden" name="idCot" value="<?=$idCot;?>">
                                   <input type="hidden" name="total" value="<?=$total;?>">
                                   <input type="hidden" name="tipo" value="1">
                                   <?php
                                   if ($cont > 0) {
                                     echo '<button type="submit" class="btn btn-success btn-outline" id="btnCierraVenta">Cerrar Cotización</button>';
                                   } else {
                                     echo '<button type="button" class="btn btn-success btn-outline" id="btnCierraVenta" disabled>Cerrar Cotización</button>';
                                   }
                                    ?>
                                </form>
                               </div>
                             </div>
                           </div>
                         </div>
                     </div>
                   </div>

                  </div>
                  <?php
                }

                   ?>


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
    <!-- dataTable js -->
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min-ESP.js"></script>
    <script src="../dist/js/pages/datatable/datatable-api.init.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/scripts/basicFuctions.js"></script>
    <script src="../assets/scripts/notificaciones.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../dist/js/pages/forms/mask/mask.init.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="../assets/libs/block-ui/jquery.blockUI.js"></script>
    <script src="../assets/extra-libs/block-ui/block-ui.js"></script>
    <script src="../assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/libs/sweetalert2/sweet-alert.init.js"></script>
    <script src="../assets/libs/toastr/build/toastr.min.js"></script>

    <script>
    $(document).ready(function(){
      <?php
      #include('../funciones/basicFuctions.php');
      #alertMsj($nameLk);

      if (isset( $_SESSION['LZFmsjAdminCotizaciones'])) {
				echo "notificaBad('".$_SESSION['LZFmsjAdminCotizaciones']."');";
				unset($_SESSION['LZFmsjAdminCotizaciones']);
			}
			if (isset( $_SESSION['LZFmsjSuccessAdminCotizaciones'])) {
				echo "notificaSuc('".$_SESSION['LZFmsjSuccessAdminCotizaciones']."');";
        unset($_SESSION['LZFmsjSuccessAdminCotizaciones']);
			}
      ?>
    });

    function agregaProdEnCoti(idCot,idprod){
      if (idCot > 0 && idprod > 0) {
        $('<form action="../funciones/agregaProdEnCoti.php" method="POST"><input type="hidden" name="idCot" value="'+idCot+'"><input type="hidden" name="idprod" value="'+idprod+'"></form>').appendTo('body').submit();
      } else {
        notificaBad('No se reconoció el producto o la cotización, actualiza e inténtalo nuevamente, si persiste notifica a tu Administrador.');
      }
    }

    function cambiaPrecioProdCot(id, precio,costo){
      //alert('Cambio Precio'+precio);
      $('<form action="../funciones/editaDetalleVentaPrecioCoti.php" method="POST"><input type="hidden" name="costo" value="'+costo+'"><input type="hidden" name="id" value="'+id+'"><input type="hidden" name="precio" value="'+precio+'"></form>').appendTo('body').submit();
    }

    function cambiaCantProdCot(id, cant){
      if(id > 0){
        $('<form action="../funciones/editaDetalleVentaCoti.php" method="POST"><input type="hidden" name="id" value="'+id+'"><input type="hidden" name="cant" value="'+cant+'"></form>').appendTo('body').submit();
      } else {
        notificaBad('No se reconoció la cotización, actualiza e inténtalo nuevamente, si persiste notifica a tu Administrador.');
      }
    }

    function eliminaProductoEnCoti(idDetCoti){
      $('<form action="../funciones/eliminaProductoEnCoti.php" method="POST"><input type="hidden" name="idDetCoti" value="'+idDetCoti+'"></form>').appendTo('body').submit();
    }

    function agregaCorreo(idCot){
      var correo = $("#newCorreo").val();
      if (correo != '') {
        //bloqueaCard('divCorreos',1);
          $.post("../funciones/agregaCorreoEnCotizacion.php",
        {correo:correo, idCot:idCot},
        function(respuesta){
            var resp = respuesta.split('|');
            if (resp[0] == 1) {
             // bloqueaCard('divCorreos',2);
              notificaSuc(resp[1]);
              $("#newCorreo").val('');
              $("#correos").html(resp[2]);
              $("#newCorreo").attr(disabled, true);

            } else {
              notificaBad(resp[1]);
            }
        });
      } else {
        notificaBad('No se reconoció el correo ingresado, verifica e inténtalo nuevamente.');
      }
    }

    function eliminaCorreo(id,idCot){
      if (id > 0) {
       // bloqueaCard('divCorreos',1);
        $.post("../funciones/eliminaCorreoCot.php",
      {ident:id, idCot:idCot},
    function(respuesta){
      var resp = respuesta.split('|');
      if (resp[0] == 1) {
        $("#liCorreo-"+id).remove();
        notificaSuc(resp[1]);
       // bloqueaCard('divCorreos',2);
       $("#newCorreo").attr(disabled, true);

      } else {
        notificaBad(resp[1]);
      }
    });
      } else {
        notificaBad('No se reconoció el correo a eliminar, verifica e inténtalo nuevamente.');
      }
    }

    function enviaCotizacion(idCotizacion){
      var pagina = 'Administrador/cotizaciones.php' ;
      var alerta = 'AdminCotizaciones';
      $('<form action="../funciones/creaCotizacion.php" method="POST"><input type="hidden" name="idCotizacion" value="'+idCotizacion+'"><input type="hidden" name="pagina" value="'+pagina+'"><input type="hidden" name="alerta" value="'+alerta+'"></form>').appendTo('body').submit();
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
