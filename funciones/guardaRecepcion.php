<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$id = (isset($_POST['ident']) and $_POST['ident'] != '') ? $_POST['ident'] : '';
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
if ($id == '' or $id == 0) {
  errorBD("Problemas en el ingreso de la Traspaso. Inténtalo de Nuevo.", '0');
}
$debug = '0';
$sql = "SELECT t.estatus AS estatusTras, dtlt.idLoteSalida, dtlt.cantidad,
dt.idProducto,lote.*, 
IF(loteRec.id IS NULL, 'CREAR', loteRec.id) AS loteRecibe,
IF(stk.id IS NULL, 'CREAR', stk.id) AS stockRecibe
FROM traspasos t 
INNER JOIN dettraspasos dt ON t.id =dt.idTraspaso
INNER JOIN dettrasplote dtlt ON dt.id=dtlt.idDetTraspaso
LEFT JOIN lotestocks lote ON dtlt.idLoteSalida=lote.id
LEFT JOIN lotestocks loteRec ON t.idSucEntrada=loteRec.idSucursal AND lote.lote=loteRec.lote
LEFT JOIN stocks stk ON dt.idProducto=stk.idProducto AND stk.idSucursal = '$sucursal'
WHERE t.id='$id';";

//----------------devBug------------------------------
if ($debug == 1) {
  $verLote = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Lotes Enviados, notifica a tu Administrador', 0));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
  echo '<br>Cant Encontrada</br>: '.mysqli_num_rows($verLote);
} else {
  $verLote = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Lotes Enviados, notifica a tu Administrador', 0));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

if (mysqli_num_rows($verLote)>0) {
  $QueryIns = '';
  $QueryStocks='';
  while ($datos = mysqli_fetch_array($verLote)) {

    if ($datos['estatusTras'] != '2') {
      errorBD("La Recepción Se Encuentra En Otro Estado. Inténtalo de Nuevo.", '0');
  }





    if ($datos['loteRecibe'] == 'CREAR') {
      $producto = $datos['idProducto'];
      $stock = $datos['stockRecibe'];
      $nombreLote=$datos['lote'];
      $caducidad=$datos['caducidad'];
      if ($stock == 'CREAR') {
        $sql = "INSERT INTO stocks(idSucursal, idProducto, idUserReg, fechaReg, cantActual) VALUES ('$sucursal', '$producto', '$userReg', NOW(), '0')";
        //----------------devBug------------------------------
        if ($debug == 1) {
          mysqli_query($link, $sql) or die(errorBD('Problemas al Crear Stock, notifica a tu Administrador' . mysqli_error($link), 1));
          $canInsert = mysqli_affected_rows($link);
          echo '<br>SQL: ' . $sql . '<br>';
          echo '<br>Cant de Registros Cargados: ' . $canInsert;
        } else {
          mysqli_query($link, $sql) or die(errorBD('Problemas al Crear Stock, notifica a tu Administrador', 1));
          $canInsert = mysqli_affected_rows($link);
        } //-------------Finaliza devBug------------------------------
        $stock=mysqli_insert_id($link);
        if ($canInsert == 0) {
          errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1);
        }
      }
      $QueryIns.= "('$sucursal','$producto', '$stock', '$nombreLote', '$caducidad','0', '1'),";
    }
  }
  if($QueryIns!=''){
    $QueryIns=substr($QueryIns,0, strlen($QueryIns)-1);
    $sql = "INSERT INTO lotestocks(idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES $QueryIns";
     //----------------devBug------------------------------
     if ($debug == 1) {
      echo '<br>SQL: ' . $sql . '<br>';

      mysqli_query($link, $sql) or die(errorBD('Problemas al Crear Nuevos Lotes, notifica a tu Administrador' . mysqli_error($link), 1));
      $canInsert = mysqli_affected_rows($link);
      echo '<br>Cant de Registros Cargados: ' . $canInsert;
    } else {
      mysqli_query($link, $sql) or die(errorBD('Problemas al Crear Nuevos Lotes, notifica a tu Administrador', 1));
      $canInsert = mysqli_affected_rows($link);
    } //-------------Finaliza devBug------------------------------


   


  }
  $sql = "UPDATE
  traspasos tr 
   INNER JOIN dettraspasos dt ON tr.id=dt.idTraspaso
   INNER JOIN stocks stk ON tr.idSucEntrada =stk.idSucursal AND dt.idProducto=stk.idProducto
   INNER JOIN dettrasplote dtlt ON dt.id=dtlt.idDetTraspaso
   INNER JOIN lotestocks lote ON dtlt.idLoteSalida=lote.id
   INNER JOIN lotestocks loteEnt ON tr.idSucEntrada=loteEnt.idSucursal AND dt.idProducto=loteEnt.idProducto AND lote.lote=loteEnt.lote
   SET
   tr.estatus='3', 
   dt.cantFinalRec=stk.cantActual+dt.cantEnvio,
   stk.cantActual= stk.cantActual+dt.cantEnvio,
   dtlt.cantLoteFinalRec=loteEnt.cant+dtlt.cantidad,
   loteEnt.cant=loteEnt.cant+dtlt.cantidad,
   dtlt.idLoteEntrada=loteEnt.id,
   dt.cantRecepcion=dt.cantEnvio,
   tr.idUserRecepcion='$userReg',
   tr.fechaRecepcion=NOW()
   WHERE dt.idTraspaso = '$id'";
  //----------------devBug------------------------------
  if ($debug == 1) {
   mysqli_query($link, $sql) or die(errorBD('Problemas al Sumar La Cantidad en Los Lotes, notifica a tu Administrador' . mysqli_error($link), 1));
   $canInsert = mysqli_affected_rows($link);
   echo '<br>SQL: ' . $sql . '<br>';
   echo '<br>Cant de Registros Cargados: ' . $canInsert;
 } else {
   mysqli_query($link, $sql) or die(errorBD('Problemas al Sumar La Cantidad en Los Lotes, notifica a tu Administrador', 1));
   $canInsert = mysqli_affected_rows($link);
 } //-------------Finaliza devBug------------------------------
 if ($canInsert == 0) {
  errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1);
}

  mysqli_commit($link);
  echo '1|Traspaso Se Ha Recibido Correctamente';
  

} else {
  errorBD('No hay Detallado de Lotes Enviados', 0);
}
























function errorBD($error, $NecesitaRollBack)
{
  $link = $GLOBALS["link"];
  echo '0|' . $error;
  if ($NecesitaRollBack == '1') {
    mysqli_rollback($link);
  }
  if ($GLOBALS['debug'] == 1) {
    echo '<br><br>Det Error: ' . $error;
    echo '<br><br>Error Report: ' . mysqli_error($link);
  } else {
    echo '0|' . $error;
  }
  exit(0);
}
