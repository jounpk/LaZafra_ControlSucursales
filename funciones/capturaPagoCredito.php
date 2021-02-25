<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$idCliente = (!empty($_POST['idCliente'])) ? $_POST['idCliente'] : 0 ;
$formaPago = (!empty($_POST['formaPago'])) ? $_POST['formaPago'] : 0 ;
$pago = (!empty($_POST['pago'])) ? str_replace(",","",$_POST['pago']) : 0 ;
$claveBanco = (!empty($_POST['claveBanco'])) ? $_POST['claveBanco'] : 0 ;
$folio = (isset($_POST['folio']) && $_POST['folio'] != '') ? $_POST['folio'] : '' ;
$credencialIne = (isset($_POST['credencialIne']) && $_POST['credencialIne'] != '') ? $_POST['credencialIne'] : '' ;
$comision = (isset($_POST['comision']) && $_POST['comision'] != '') ?str_replace(",","", $_POST['comision']) : '' ;
$porcentajeComision = (isset($_POST['porcentajeComision']) && $_POST['porcentajeComision'] != '') ? $_POST['porcentajeComision'] : '' ;
$idents = (isset($_POST['idents']) && $_POST['idents'] != '') ? $_POST['idents'] : '' ;
$idSucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];

$idPagos = '';

$debug = 0;
if ($debug == 1) {
  echo '<br>########################################################################<br>';
  echo '<br>$_POST:<br>';
  print_r($_POST);
  echo '<br>';
  echo '<br>$formaPago: '.$formaPago;
  echo '<br>$idCliente: '.$idCliente;
  echo '<br>$pago: '.$pago;
  echo '<br>$claveBanco: '.$claveBanco;
  echo '<br>$folio: '.$folio;
  echo '<br>$comision: '.$comision;
  echo '<br>$porcentajeComision: '.$porcentajeComision;
  echo '<br>$idents: '.$idents;
  echo '<br>$idSucursal: '.$idSucursal;
  echo '<br>$userReg: '.$userReg;
  echo '<br>';
  echo '<br>########################################################################<br>';
}
#      echo '<br>------------ se verifica que se haya un cliente ------------<br>';
  if ($idCliente < 1) {
    errorBD('No se reconoció el cliente, intenta de nuevo, si persiste notifica a tu Administrador.');
  }
#    echo '<br>------------ se verifica que el pago ingresado sea mayor a 1 ------------<br>';
  if ($pago < 1) {
    errorBD('El pago debe ser mayor a $0');
  }
#    echo '<br>------------ se verifica que la forma de pago sea mayor de 1 ------------<br>';
if ($formaPago < 1) {
  errorBD('No se reconoció la forma de pago, intenta de nuevo, si persiste notifica a tu Administrador.');
}
#    echo '<br>------------ si la forma de pago es mayor a 1 (diferente a efectivo) ------------<br>';
if ($formaPago > 1) {
#    echo '<br>------------ se verifica que haya una clave de banco ------------<br>';
    if ($claveBanco < 1) {
      errorBD('No se reconoció el banco, intenta de nuevo, si persiste notifica a tu Administrador.');
    }
#      echo '<br>------------ se verifica que el folio no venga vacío ------------<br>';
    if ($folio == '') {
      errorBD('No se reconoció el folio, intenta de nuevo, si persiste notifica a tu Administrador.');
    }
#    echo '<br>------------ si el folio no viene vacío se verifica si no fue ingresado en otra sucursal en las ventas ------------<br>';
    $sqlConFolios = "SELECT CONCAT(usu.nombre,' ',usu.appat) AS nomUsuario, scs.nameFact AS nomSucursal, vnt.fechaReg
                FROM pagosventas pvnt
                INNER JOIN ventas vnt ON pvnt.idVenta = vnt.id
                INNER JOIN segusuarios usu ON vnt.idUserReg = usu.id
                INNER JOIN sucursales scs ON vnt.idSucursal = scs.id
                WHERE pvnt.folio = '$folio' AND pvnt.idFormaPago = '$formaPago' AND pvnt.idBanco = '$claveBanco' AND vnt.estatus < '3'";
    if ($debug == 1) {
      echo '<br>$sqlConFolios: '.$sqlConFolios;
    }
    $resConFolios = mysqli_query($link,$sqlConFolios) or die(errorBD('Problemas al consultar los folios de pago, notifica a tu Administrador. '));#.mysqli_error($link)));
    $cant = mysqli_num_rows($resConFolios);
    if ($debug == 1) {
      echo '<br>------------ en caso de que si exista uno se informa ------------<br>';
    }
    if ($cant > 0 AND $formaPago == 2 || $formaPago == 3) {
      $dat = mysqli_fetch_array($resConFolios);
      errorBD('El folio '.$folio.' ya fue capturado en sucursal '.$dat['nomSucursal'].' por el usuario: '.$dat['nomUsuario'].' el día '.$dat['fecha']);
    }
#    echo '<br>------------ se realiza la misma acción en los pagos de créditos ------------<br>';
    $sqlConFolios2 = "SELECT CONCAT(usu.nombre,' ',usu.appat) AS nomUsuario, scs.nameFact AS nomSucursal, vnt.fechaReg
                FROM pagoscreditos pvnt
                INNER JOIN ventas vnt ON pvnt.idVenta = vnt.id
                INNER JOIN segusuarios usu ON vnt.idUserReg = usu.id
                INNER JOIN sucursales scs ON vnt.idSucursal = scs.id
                WHERE pvnt.folio = '$folio' AND pvnt.idFormaPago = '$formaPago' AND pvnt.idBanco = '$claveBanco' AND vnt.estatus < '3";

    if ($debug == 1) {
      echo '<br>$sqlConFolios2: '.$sqlConFolios2;
    }

    $resConFolios = mysqli_query($link,$sqlConFolios) or die(errorBD('Problemas al consultar los folios de pago, notifica a tu Administrador. '));#.mysqli_error($link)));
    $cant2 = mysqli_num_rows($resConFolios);
#    echo '<br>------------ en caso de que si exista uno se informa ------------<br>';
    if ($cant2 > 0 AND $formaPago == 2 || $formaPago == 3) {
      $dat2 = mysqli_fetch_array($resConFolios);
      errorBD('El folio '.$folio.' ya fue capturado en sucursal '.$dat2['nomSucursal'].' por el usuario: '.$dat2['nomUsuario'].' el día '.$dat2['fecha']);
    }
}
  $monto1 = $deuda = $totalDeuda = 0;
#      echo '<br>------------ en caso de que hayan seleccionado creditos se realiza lo siguiente ------------<br>';
  if ($idents != '') {
#    echo '<br>------------ comienza sí hay ids de créditos seleccionados ------------<br>';
#        echo '<br>------------ se comienza con la transacción ------------<br>';
    $sqlTransIn = "BEGIN";
    $resTrasnIn = mysqli_query($link,$sqlTransIn) or die('Problemas al iniciar la transacción, notifica a tu Administrador.');
#    echo '<br>$sqlTransIn: '.$sqlTransIn;
#        echo '<br>------------ se obtienen los ids enviados ------------<br>';
    $ids = explode(',',$idents);
    $cantId = count($ids);
      for ($i=0; $i < $cantId; $i++) {
#            echo '<br>------------ se consulta si el crédito está activo para realizar el desconteo ------------<br>';
        $sqlConCred1 = "SELECT COUNT(id) AS cant FROM creditos WHERE id = '$ids[$i]' AND estatus = '1'";
        if ($debug == 1) {
          echo '<br>$sqlConCred1: '.$sqlConCred1;
        }
        $resConCred1 = mysqli_query($link,$sqlConCred1) or die(cancelaTrans($link,'Problemas al consultar el estatus de los créditos, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
        $cr = mysqli_num_rows($resConCred1);
        if ($cr > 0) {
#              echo '<br>------------ se consulta el monto deudor del credito ------------<br>';
        $sqlConCred = "SELECT montoDeudor,totalDeuda FROM creditos WHERE id = '$ids[$i]'";
        if ($debug == 1) {
          echo '<br>$sqlConCred: '.$sqlConCred;
        }
        $resConCred = mysqli_query($link,$sqlConCred) or die(cancelaTrans($link,'Problemas al consultar los montos de los créditos, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
        $mCrd = mysqli_fetch_array($resConCred);
        $deuda = $mCrd['montoDeudor'];
        $totalDeuda = $mCrd['totalDeuda'];
#    echo '<br>------------ se verifica que el pago sea mayor al adeudo ------------<br>';
        if ($deuda > $pago) {
#          echo '<br>------------ comienza pago menor a deuda ------------<br>';
          if ($pago > 0) {
            $monto1 = $deuda - $pago;
            if ($debug == 1) {
              echo '<br>$pago: '.$pago;
              echo '<br>$idCredito: '.$ids[$i];
              echo '<br>$deuda: '.$deuda;
              echo '<br>$totalDeuda: '.$totalDeuda;
              echo '<br>$monto1: '.$monto1;
              echo '<br>------------ Se ingresa el pago en pagoscreditos ------------<br>';
            }
            $sqlInsert = "INSERT INTO pagoscreditos(idCredito,comision,idFormaPago,idSucursal,monto,fechaReg,idUserReg,residual,idBanco,folio,adeudo,idCorte,credencialIne)
                                      VALUES('$ids[$i]', '$comision', '$formaPago', '$idSucursal', '$pago', NOW(), '$userReg', '$monto1','$claveBanco', '$folio', '$deuda','0','$credencialIne')";
            if ($debug == 1) {
               echo '<br>$sqlInsert: '.$sqlInsert;
             }
            $resInsert = mysqli_query($link,$sqlInsert) or die(cancelaTrans($link,'Problemas al agregar el pago del crédito, intenta de nuevo, si pesiste notifica a tu Administrador. '));#.mysqli_error($link)));
            $newIdPago = mysqli_insert_id($link);
            $idPagos .= $newIdPago.',';
            $sqlUpdate = "UPDATE creditos SET montoDeudor = (montoDeudor - '$pago') WHERE id = '$ids[$i]'";
            if ($debug == 1) {
              echo '<br>------------ Se resta el pago de creditos ------------<br>';
              echo '<br>$sqlUpdate: '.$sqlUpdate;
            }
            $resUpdate = mysqli_query($link,$sqlUpdate) or die(cancelaTrans($link, 'Problemas al actualizar los créditos, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
            $pago = 0;
          } # fin de if ($pago > 0)
          # fin de if ($deuda > $pago)
#    echo '<br>------------ termina pago es menor a deuda ------------<br>';
        } else {
          $monto1 = $pago - $deuda ;
          if ($debug == 1) {
            echo '<br>$pago: '.$pago;
            echo '<br>$idCredito: '.$ids[$i];
            echo '<br>$deuda: '.$deuda;
            echo '<br>$totalDeuda: '.$totalDeuda;
            echo '<br>$monto1: '.$monto1;
            echo '<br>------------ comienza pago mayor a deuda ------------<br>';
          }
          $sqlInsert = "INSERT INTO pagoscreditos(idCredito, comision,idFormaPago,idSucursal,monto,fechaReg,idUserReg,residual,idBanco,folio,adeudo,idCorte,credencialIne)
                        VALUES('$ids[$i]', '$comision', '$formaPago', '$idSucursal', '$deuda', NOW(), '$userReg', '0','$claveBanco', '$folio', '$deuda','0','$credencialIne')";
          if ($debug == 1) {
            echo '<br>------------ se resta el pago de creditos ------------<br>';
            echo '<br>$sqlInsert: '.$sqlInsert;
          }
          $resInsert = mysqli_query($link,$sqlInsert) or die(cancelaTrans($link, 'Problemas al ingresar el pago, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
          $newIdPago = mysqli_insert_id($link);
          $idPagos .= $newIdPago.',';
          $sqlUpdate = "UPDATE creditos SET montoDeudor = 0, estatus = 2 WHERE id = '$ids[$i]'";
          if ($debug == 1) {
            echo '<br>$sqlUpdate: '.$sqlUpdate;
          }
          $resUpdate = mysqli_query($link,$sqlUpdate) or die(cancelaTrans($link, 'Problemas al agregar el pago, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
          $pago = $monto1;
        } # fin de else
      } # fin de if ($cr > 0) {
      }  #fin de for ($i=0; $i < $cantId; $i++)
  #    echo '<br>------------ termina movimientos ------------<br>';
      if ($pago > 0) {
        $mensaje = ', hay sobrante de $'.$pago;
      } else {
        $mensaje = '.';
      }
  #    echo '<br>------------ FIN de la Transacción ------------<br>';
      if ($debug == 1) {
        $sqlTransIn = "ROLLBACK";
        echo '<br>$sqlTransIn: '.$sqlTransIn;
      } else {
        $sqlTransIn = "COMMIT";
      }
      $resTrasnIn = mysqli_query($link,$sqlTransIn) or die(errorBD('Problemas al iniciar la transacción, notifica a tu Administrador. '));#.mysqli_error($link)));
      if ($debug == 1) {
            echo '<br>------------ termina sí hay ids de créditos seleccionados ------------<br>';
            echo '<br>------------ manda a ticket ------------<br>';
      } else {
        $idPagos = trim($idPagos, ",");
        $_SESSION['LZFmsjSuccessCreditos'] = 'Pagos capturados correctamente'.$mensaje;
        echo '<script>
                location.href="../funciones/ticketLanzaPagoCredito.php?idCliente='.$idCliente.'&idPagos='.$idPagos.'";
              </script> ';
#              echo '<br>Se abrió?';
              exit(0);


        header('location: ../creditos.php');
        exit(0);
      }
      # fin de if ($idents != '')
  } else{
#    echo '<br>------------ comienza sí no hay ids de créditos seleccionados ------------<br>';
#    echo '<br>------------ comienza la transacción ------------<br>';
    $sqlTransIn = "BEGIN";
    $resTrasnIn = mysqli_query($link,$sqlTransIn) or die(errorBD('Problemas al iniciar la transacción, notifica a tu Administrador.'));
    if ($debug == 1) {
      echo '<br>$sqlTransIn: '.$sqlTransIn;
      echo '<br>------------ comienza la realización del pago, se evalua que el pago sea mayor a $0 ------------<br>';
    }
        $idCredito = 1;
    while ($pago > 0 && $idCredito >= 1) {

      $sql = "SELECT cd.* FROM creditos cd INNER JOIN ventas v ON cd.idVenta = v.id
              WHERE cd.idCliente = '$idCliente' AND cd.estatus = '1' ORDER BY v.fechaReg ASC LIMIT 1";
      if ($debug == 1) {
        echo '<br>------------ consulta el crédito más antiguo de ese cliente ------------<br>';
        echo '<br>$sql: '.$sql;
      }
      $res = mysqli_query($link,$sql) or die(cancelaTrans($link, 'Problemas al consultar los créditos, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
      $d = mysqli_num_rows($res);

#      echo '<br>------------ verifica que haya ------------<br>';
      if ($d < 1) {
        cancelaTrans($link, 'No se encontró ningún crédito pendiente. ');
      }
      if ($d > 0) {
        $d = mysqli_fetch_array($res);
        $idCredito = $d['id'];
        $deuda = $d['montoDeudor'];
        $totalDeuda = $d['totalDeuda'];
        if ($pago < $deuda) {
#          echo '<br>------------ comienza sí el pago es menor que la deuda ------------<br>';
          $monto1 = $deuda - $pago;
/*
          echo '<br>$pago: '.$pago;
          echo '<br>$idCredito: '.$idCredito;
          echo '<br>$deuda: '.$deuda;
          echo '<br>$totalDeuda: '.$totalDeuda;
          echo '<br>$monto1: '.$monto1;
          echo '<br>------------ se agrega el monto en pagos ------------<br>';
#*/
          $sqlInsert = "INSERT INTO pagoscreditos(idCredito, comision,idFormaPago,idSucursal,monto,fechaReg,idUserReg,residual,idBanco,folio,adeudo,idCorte,credencialIne)
                        VALUES('$idCredito', '$comision', '$formaPago', '$idSucursal', '$pago', NOW(), '$userReg', '$monto1','$claveBanco', '$folio', '$deuda','0','$credencialIne')";
                      #  echo '<br>$sqlInsert: '.$sqlInsert;
          $resInsert = mysqli_query($link,$sqlInsert) or die(cancelaTrans($link, 'Problemas al ingresar el pago, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
          $newIdPago = mysqli_insert_id($link);
          $idPagos .= $newIdPago.',';
#          echo '<br>------------ se resta el pago al crédito ------------<br>';
          $sqlUpdate = "UPDATE creditos SET montoDeudor = (montoDeudor - '$pago') WHERE id = '$idCredito'";
#          echo '<br>$sqlUpdate: '.$sqlUpdate;
          $resUpdate = mysqli_query($link,$sqlUpdate) or die(cancelaTrans($link, 'Problemas al restar el pago de los créditos, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
#          echo '<br>------------ termina si el pago es menor que la deuda ------------<br>';
          $pago = 0;
        } else {
#          echo '<br>------------ comienza si el pago es mayor que la deuda ------------<br>';
          $monto1 = $pago - $deuda ;
          if ($debug == 1) {
            echo '<br>$pago: '.$pago;
            echo '<br>$idCredito: '.$idCredito;
            echo '<br>$deuda: '.$deuda;
            echo '<br>$totalDeuda: '.$totalDeuda;
            echo '<br>$monto1: '.$monto1;
            echo '<br>------------ se agrega el monto en pagos ------------<br>';
          }
          $sqlInsert = "INSERT INTO pagoscreditos(idCredito, comision,idFormaPago,idSucursal,monto,fechaReg,idUserReg,residual,idBanco,folio,adeudo,idCorte,credencialIne)
                        VALUES('$idCredito', '$comision', '$formaPago', '$idSucursal', '$deuda', NOW(), '$userReg', '0','$claveBanco', '$folio', '$deuda','0','$credencialIne')";
          if ($debug == 1) {
            echo '<br>$sqlInsert: '.$sqlInsert;
          }
          $resInsert = mysqli_query($link,$sqlInsert) or die(cancelaTrans($link, 'Problemas al ingresar el pago, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
          $newIdPago = mysqli_insert_id($link);
          $idPagos .= $newIdPago.',';
          if ($debug == 1) {
            echo '<br>------------ se resta el pago al crédito ------------<br>';
          }
          $sqlUpdate = "UPDATE creditos SET montoDeudor = 0, estatus = 2 WHERE id = '$idCredito'";
          if ($debug == 1) {
            echo '<br>$sqlUpdate: '.$sqlUpdate;
          }
          $resUpdate = mysqli_query($link,$sqlUpdate) or die(cancelaTrans($link, 'Problemas al restar el pago de los créditos, intenta de nuevo, si persiste notifica a tu Administrador. '));#.mysqli_error($link)));
          $pago = $monto1;
          if ($debug == 1) {
            echo '<br>------------ termina si el pago es mayor que la deuda ------------<br>';
          }
        }
      }
    }
    if ($debug == 1) {
      echo '<br>------------ FIN de la Transacción ------------<br>';
      $sqlTransIn = "ROLLBACK";
    } else {
      $sqlTransIn = "COMMIT";
    }
    $resTrasnIn = mysqli_query($link,$sqlTransIn) or die('Problemas al iniciar la transacción, notifica a tu Administrador.');
    if ($debug == 1) {
      echo '<br>$sqlTransIn: '.$sqlTransIn;
      echo '<br>------------ Manda a ticket ------------<br>';
      echo '<br>------------ termina movimientos ------------<br>';
    } else {
      $idPagos = trim($idPagos, ",");
      $_SESSION['LZFmsjSuccessCreditos'] = 'Pagos capturados correctamente'.$mensaje;
      echo '<script>
              location.href="../funciones/ticketLanzaPagoCredito.php?idCliente='.$idCliente.'&idPagos='.$idPagos.'";
            </script> ';
#              echo '<br>Se abrió?';
            exit(0);
      header('location: ../creditos.php');
      exit(0);
    }
  }


function cancelaTrans($link,$desc){
  if ($GLOBALS['debug'] == 1) {
    echo '<br> Entró en función de cancelaTrans()';
    echo '<br>$error: '.$desc;
  } else {
      $sqlBack = "ROLLBACK";
      $resBack = mysqli_query($link,$sqlBack) or die('No se pudo realizar los pagos. notifica a tu Administrador.');

      $_SESSION['LZFmsjCreditos'] = $desc;
     header('location: ../creditos.php');
      exit(0);
    }
}

function errorBD($error){
  if ($GLOBALS['debug'] == 1) {
    echo '<br> Entró en función de error';
    echo '<br>$error: '.$error;
  } else {
    $_SESSION['LZFmsjCreditos'] = $error;
    header('location: ../creditos.php');
    exit(0);
  }
}
 ?>
