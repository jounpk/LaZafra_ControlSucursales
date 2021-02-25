<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../../include/connect.php');

//$cantNotit = (isset($_POST['cantNotit'])) ? $_POST['cantNotit'] : 0 ;
$cantNotit = (isset($_SESSION['cantNotit'])) ? $_SESSION['cantNotit'] : 0 ;
$cantNotirt = (isset($_SESSION['cantNotirt'])) ? $_SESSION['cantNotirt'] : 0 ;
$idSucursal = $_SESSION['LZFidSuc'];
$idNivel = $_SESSION['LZFidNivel'];
$idArea = $_SESSION['LZFarea'];
$idUser = $_SESSION['LZFident'];

//$cantNotit=$_SESSION[cantNotit];
$audio=0;
$cont='';
$notif=0;
$cantNotificaciones = 0;
// Comienza declaración de notificaciones


//###################  comienza declaración de variables en nulo  ####################
$traspasos = $Depositos = $Ajustes = $Cots = $Vtas = $Pagos = $Recolecciones = $RecoleccionesRechazadas = $NoDepositado24 = '';
//###################  Termina declaración de variables en nulo  ####################

//###################  Comienza declaración de urls de cada notificación  ####################
switch ($idArea) {
  case '1':
    $urlSol = 'Administrador/traspasos.php';
    $urlTrasp = 'Administrador/traspasos.php';
    $urlCotizaciones = 'Corporativo/corpCotizaciones.php';
    $urlVtaEsp = 'Corporativo/autorizaVentaEspecial.php';
    $urlAjustes = 'Corporativo/solicitudEntradasSalidas.php';
    $urlDepositos = 'Corporativo/historialDepositos.php';
    $urlPagos = 'Corporativo/pagosXsucursal.php';
    break;

  case '2':
    $urlSol = 'traspasos.php';
    $urlTrasp = 'traspasos.php';
    $urlCotizaciones = '../Corporativo/corpCotizaciones.php';
    $urlVtaEsp = '../Corporativo/autorizaVentaEspecial.php';
    $urlAjustes = '../Corporativo/solicitudEntradasSalidas.php';
    $urlDepositos = '../Corporativo/historialDepositos.php';
    $urlPagos = '../Corporativo/pagosXsucursal.php';
    break;

  case '3':
    $urlSol = '../Administrador/traspasos.php';
    $urlTrasp = '../Administrador/traspasos.php';
    $urlCotizaciones = 'corpCotizaciones.php';
    $urlVtaEsp = 'autorizaVentaEspecial.php';
    $urlAjustes = 'solicitudEntradasSalidas.php';
    $urlDepositos = 'historialDepositos.php';
    $urlPagos = 'pagosXsucursal.php';
    break;

  case '4':
    $urlSol = '../Administrador/traspasos.php';
    $urlTrasp = '../Administrador/traspasos.php';
    $urlCotizaciones = '../Corporativo/corpCotizaciones.php';
    $urlVtaEsp = '../Corporativo/autorizaVentaEspecial.php';
    $urlAjustes = '../Corporativo/solicitudEntradasSalidas.php';
    $urlDepositos = '../Corporativo/historialDepositos.php';
    $urlPagos = '../Corporativo/pagosXsucursal.php';
    break;

    case '6':
      $urlRecoleccion = 'recoleccion.php';
      break;

  default:
  $urlSol = '';
  $urlTrasp = '';
  $urlCotizaciones = '';
  $urlVtaEsp = '';
  $urlAjustes = '';
  $urlDepositos = '';
  $urlPagos = '';
  $urlRecoleccion = '';
  break;
}
//###################  Termina declaración de urls de cada notificación  ####################

//###################  Comieza Estructura de los mensajes  ####################

if ($idNivel != 5 && $idNivel != 6) {
  // ########################## Inicia solicitudes de Traspasos ########################## //
$sqlSol = "SELECT IF(COUNT(t.id) > 0,COUNT(t.id),0) AS cant
FROM traspasos t
LEFT JOIN solicitudestrasp st ON t.idSolicitud = st.id
WHERE t.estatus = 1 AND t.idSucSalida = '$idSucursal' AND t.idUserEnvio = 0 AND IF(t.idSolicitud > 0,st.estatus = 2, 1=1)";
$resSol = mysqli_query($link,$sqlSol) or die('Problemas al consultar las solitudes de traspasos, notifica a tu Administrador.');
$sol = mysqli_fetch_array($resSol);
$cantSol = $sol['cant'];
if ($cantSol > 0) {
$traspasos .= '<a href="'.$urlSol.'" class="message-item">
    <span class="btn btn-warning btn-circle">
        <i class="mdi mdi-calendar-multiple-check"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Solicitudes </h5>
        <span class="mail-desc">'.$cantSol.' Solicitud(es) de Producto</span>
    </div>
</a>';
$notif = 1;
$audio = 1;
++$cantNotificaciones;
// ########################## Termina solicitudes de Traspasos ########################## //
}

// ########################## Inicia Llegada de Traspaso ########################## //
$sqlTrasp = "SELECT IF(COUNT(t.id) > 0,COUNT(t.id),0) AS cant
FROM traspasos t
WHERE t.estatus = 2 AND t.idSucEntrada = '$idSucursal'";
$resTrasp = mysqli_query($link,$sqlTrasp) or die('Problemas al consultar los traspasos, notifica a tu Administrador.');
$traspaso = mysqli_fetch_array($resTrasp);
$cantTrasp = $traspaso['cant'];
if ($cantTrasp > 0) {
$traspasos .= '<a href="'.$urlSol.'" class="message-item">
  <span class="btn btn-danger btn-circle">
      <i class="fas fa-people-carry"></i>
  </span>
  <div class="mail-contnet">
      <h5 class="message-title">Recepciones</h5>
      <span class="mail-desc">'.$cantTrasp.' Traspaso(s) por aceptar</span>
  </div>
</a>';
$notif = 1;
$audio = 1;
++$cantNotificaciones;
// ########################## Termina Llegada de Traspaso ########################## //
}

}

if ($idNivel == 3 || $idNivel == 4 || $idNivel == 7) {
  // ########################## Inicia Autorización de Venta Especial ########################## //
  $sqlVtaEsp = "SELECT IF(COUNT(v.id) > 0,COUNT(v.id),0) AS cant
FROM ventas v
WHERE v.ventaEspecial = 1 AND v.idUserAut = 0 AND v.estatus = 1";
  $resVtaEsp = mysqli_query($link,$sqlVtaEsp) or die('Problemas al consultar las autorizaciones de Venta Especial, notifica a tu Administrador.');
  $Vta = mysqli_fetch_array($resVtaEsp);
  $cantVtaEsp = $Vta['cant'];
  if ($cantVtaEsp > 0) {
  $Vtas .= '<a href="'.$urlVtaEsp.'" class="message-item">
    <span class="btn btn-purple btn-circle">
        <i class="fas fa-shopping-cart"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Venta Especial</h5>
        <span class="mail-desc">'.$cantVtaEsp.' Autorizacione(s) pendientes</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Autorización de Venta Especial ########################## //
  }
}

if ($idNivel == 3 || $idNivel == 4 || $idNivel == 7) {
  // ########################## Inicia Alerta de Pagos a 1 semana ########################## //
  $sqlNotPagos = "SELECT IF(COUNT(id) > 0,COUNT(id),0) AS cant
FROM pagos
WHERE TIMESTAMPDIFF(DAY,fechaVencimiento,NOW()) BETWEEN 0 AND 7 AND pagado = 0";
  $resNotPagos = mysqli_query($link,$sqlNotPagos) or die('Problemas al consultar las autorizaciones de Venta Especial, notifica a tu Administrador.');
  $Pga = mysqli_fetch_array($resNotPagos);
  $cantNotPagos = $Pga['cant'];
  #$cantNotPagos = 2;
  if ($cantNotPagos > 0) {
  $Pagos .= '<a href="'.$urlPagos.'" class="message-item">
    <span class="btn btn-warning btn-circle">
        <i class="fas fa-hand-holding-usd"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Pagos de Sucursales</h5>
        <span class="mail-desc">'.$cantNotPagos.' Pago(s) pendientes</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Alerta de Pagos a 1 semana ########################## //
  }
}

#/*
if ($idNivel == 3 || $idNivel == 4 || $idNivel == 7) {
  // ########################## Inicia Alerta de Recolecciones no depositadas en 24 hrs ########################## //
  $sqlNoDeposit24 = "SELECT usu.id AS idUsuario, CONCAT(usu.nombre, ' ',usu.appat, ' ', usu.apmat) AS nomUsuario, COUNT(d.id) AS cant, MIN(d.fechaReg) AS fechaMinima, MAX(d.fechaReg) AS fechaMax
                  FROM depositos d
                  INNER JOIN segusuarios usu ON d.idUserReg = usu.id
                  WHERE TIMESTAMPDIFF(HOUR,d.fechaReg,NOW()) >= 24 AND d.estatus = 0
                  GROUP BY usu.id
                  ORDER BY usu.nombre ASC,d.fechaReg";
  $resNoDeposit24 = mysqli_query($link,$sqlNoDeposit24) or die('Problemas al consultar los cortes por depositar, notifica a tu Administrador.');
  $Dep24 = mysqli_fetch_array($resNoDeposit24);
  $cantNoDeposit24 = $Dep24['cant'];
  #$cantNoDeposit24 = 2;
  if ($cantNoDeposit24 > 0) {
  $NoDepositado24 .= '<a href="'.$urlDepositos.'" class="message-item">
    <span class="btn btn-danger btn-circle">
        <i class="fas fa-piggy-bank"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">No Depositados en 24 hrs</h5>
        <span class="mail-desc">'.$cantNoDeposit24.' Cortes(s) pendientes</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Alerta de Recolecciones no depositadas en 24 hrs ########################## //
  }
}
#*/

if ($idNivel == 6) {
  // ########################## Inicia Alerta de Recolecciones ########################## //
  $sqlNotRecoleccion = "SELECT IF(COUNT(id) > 0,COUNT(id), 0) AS cant
FROM cortes
WHERE estatus = 2 AND DATEDIFF(now(),fechaCierre) > 0 AND idRecoleccion = 0";
  $resNotRecoleccion = mysqli_query($link,$sqlNotRecoleccion) or die('Problemas al consultar las autorizaciones de Venta Especial, notifica a tu Administrador.');
  $Reco2 = mysqli_fetch_array($resNotRecoleccion);
  $cantNotRecoleccion = $Reco2['cant'];
  #$cantNotRecoleccion = 2;
  if ($cantNotRecoleccion > 0) {
  $Recolecciones .= '<a href="'.$urlRecoleccion.'" class="message-item">
    <span class="btn btn-warning btn-circle">
        <i class="fas fa-hand-holding-usd"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Recoleccion de Cortes</h5>
        <span class="mail-desc">'.$cantNotRecoleccion.' Cortes(s) por Recolectar</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Alerta de Recolecciones ########################## //
  }
}

if ($idNivel == 6) {
  // ########################## Inicia Alerta de Recolecciones Rechazadas ########################## //
  $sqlNotRecoRechazado = "SELECT IF(COUNT(id) > 0,COUNT(id), 0) AS cant
                          FROM depositos
                          WHERE estatus = 3 AND idUserReg = '$idUser'";
  $resNotRecoRechazado = mysqli_query($link,$sqlNotRecoRechazado) or die('Problemas al consultar las autorizaciones de Venta Especial, notifica a tu Administrador.');
  $Reco = mysqli_fetch_array($resNotRecoRechazado);
  $cantNotRecoRechazado = $Reco['cant'];
  #$cantNotRecoRechazado = 2;
  if ($cantNotRecoRechazado > 0) {
  $RecoleccionesRechazadas .= '<a href="'.$urlRecoleccion.'" class="message-item">
    <span class="btn btn-danger btn-circle">
        <i class="fas fa-times"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Recoleccion de Cortes</h5>
        <span class="mail-desc">'.$cantNotRecoRechazado.' Cortes(s) por Recolectar</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Alerta de Recolecciones Rechazadas ########################## //
  }
}

if ($idNivel == 3 || $idNivel == 4 || $idNivel == 7) {
  // ########################## Inicia Autorización de Cotizaciones ########################## //
  $sqlCot = "SELECT IF(COUNT(c.id) > 0,COUNT(c.id),0) AS cant
FROM cotizaciones c
WHERE c.estatus = 2";
  $resCot = mysqli_query($link,$sqlCot) or die('Problemas al consultar las autorizaciones de Cotizaciones, notifica a tu Administrador.');
  $Cot = mysqli_fetch_array($resCot);
  $cantCot = $Cot['cant'];
  if ($cantCot > 0) {
  $Cots .= '<a href="'.$urlCotizaciones.'" class="message-item">
    <span class="btn btn-primary btn-circle">
        <i class="mdi mdi-file-check"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Cotizaciones</h5>
        <span class="mail-desc">'.$cantCot.' Autorizacione(s) pendientes</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Autorización de Cotizaciones ########################## //
  }
}

if ($idNivel == 3 || $idNivel == 4 || $idNivel == 7) {
  // ########################## Inicia Autorización de Ajustes ########################## //
  $sqlAjuste = "SELECT IF(COUNT(id) > 0,COUNT(id),0) AS cant
FROM ajustes
WHERE estatus = '2'";
  $resAjuste = mysqli_query($link,$sqlAjuste) or die('Problemas al consultar las autorizaciones de ajustes, notifica a tu Administrador.');
  $Ajuste = mysqli_fetch_array($resAjuste);
  $cantAjuste = $Ajuste['cant'];
  if ($cantAjuste > 0) {
  $Ajustes .= '<a href="'.$urlAjustes.'" class="message-item">
    <span class="btn btn-success btn-circle">
        <i class="ti-exchange-vertical"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Ajustes de entrada y Salida</h5>
        <span class="mail-desc">'.$cantAjuste.' Autorizacione(s) pendientes</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Autorización de Ajustes ########################## //
  }
}

if ($idNivel == 3 || $idNivel == 4 || $idNivel == 7) {
  // ########################## Inicia Autorización de Depósitos ########################## //
  $sqlDeposito = "SELECT IF(COUNT(id) > 0,COUNT(id),0) AS cant
FROM depositos
WHERE estatus = '2'";
  $resDeposito = mysqli_query($link,$sqlDeposito) or die('Problemas al consultar los depósitos, notifica a tu Administrador.');
  $Deposito = mysqli_fetch_array($resDeposito);
  $cantDeposito = $Deposito['cant'];
  if ($cantDeposito > 0) {
  $Depositos .= '<a href="'.$urlDepositos.'" class="message-item">
    <span class="btn btn-orange btn-circle text-white">
        <i class="fas fa-ticket-alt"></i>
    </span>
    <div class="mail-contnet">
        <h5 class="message-title">Depósitos</h5>
        <span class="mail-desc">'.$cantDeposito.' Autorizacione(s) pendientes</span>
    </div>
  </a>';
  $notif = 1;
  $audio = 1;
  ++$cantNotificaciones;
  // ########################## Termina Autorización de Depósitos ########################## //
  }
}
// Termina declaración de Notificaciones

//###################  Termina Estructura de los mensajes  ####################

// Comienza estructura de la notificaciones
if ($notif==1) {
// Se carga la información en la variable $cont
  $cont .='<a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mdi mdi-bell-outline font-22"></i>
                <span class="badge badge-pill badge-info noti">'.$cantNotificaciones.'</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                <span class="with-arrow">
                    <span class="bg-primary"></span>
                </span>
                <ul class="list-style-none">
                    <li>
                        <div class="drop-title bg-primary text-white">
                            <h4 class="m-b-0 m-t-5">'.$cantNotificaciones.' Nuevas</h4>
                            <span class="font-light">Notificaciones</span>
                        </div>
                    </li>
                    <li>
                        <div class="message-center notifications">
                        '.$traspasos.'
                        '.$Vtas.'
                        '.$Cots.'
                        '.$Ajustes.'
                        '.$Depositos.'
                        '.$Pagos.'
                        '.$Recolecciones.'
                        '.$RecoleccionesRechazadas.'
                        '.$NoDepositado24.'
                    </li>
                </ul>
            </div>';

  //Se iguala a cero las cantidades de notificaciones;
  $_SESSION['cantNotit']=0;
  echo $audio.'|'.$cont;
} else {

} // Cierre de else


if($_SESSION['LZFidNivel']==1)
{
} // Cierre de if($_SESSION['nivel']==1)
 ?>
