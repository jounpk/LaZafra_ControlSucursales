<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
$idCompra = (isset($_POST['idCompra']) && $_POST['idCompra'] != '') ? $_POST['idCompra'] : 0 ;
$nombreEntrega = (isset($_POST['nombreEntrega']) && $_POST['nombreEntrega'] != '') ? trim($_POST['nombreEntrega']) : '' ;
$tipo = (isset($_POST['tipo']) && $_POST['tipo'] != '') ? $_POST['tipo'] : 0 ;
$vista = (isset($_POST['vista']) && $_POST['vista'] != '') ? $_POST['vista'] : 1 ;
$mensaje = '';
$debug = 0;

if ($debug == 1) {
  echo '<br> ##################################################### <br>';
  echo '<br>$_POST:<br>';
  print_r($_POST);
  echo '<br>$idCompra:'.$idCompra;
  echo '<br>$nombreEntrega:'.$nombreEntrega;
  echo '<br>$tipo:'.$tipo;
  echo '<br>$idSucursal:'.$idSucursal;
  echo '<br>$idUser:'.$idUser;
  echo '<br> ##################################################### <br>';
#  exit(0);
}


$SqlCon ="SELECT * FROM recepciones WHERE idCompra = '$idCompra' AND idUserReg = '$idUser' AND idSucursal = '$idSucursal' AND estatus = '1' ORDER BY fechaReg DESC LIMIT 1";
$resCon = mysqli_query($link,$SqlCon) or die(errorBD2('Problemas al consultar las recepciones, notifica a tu Administrador.'));
$r = mysqli_fetch_array($resCon);
$idRecepcion = (int)$r['id'];
$estatus = (int)$r['estatus'];

if ($debug == 1) {
  echo '<br>$idRecepcion:'.$idRecepcion;
  echo '<br>$estatus:'.$estatus;
}
#exit(0);
if ($estatus == 1 && $idRecepcion > 0) {
$sqlInit = "START TRANSACTION";
#echo '<br>$sqlInit:'.$sqlInit;
$resInit = mysqli_query($link,$sqlInit) or die(errorBD('Problemas al iniciar, notifica a tu Administrador.',$link));

if ($tipo == 2) {


      $sqlCaptura = "SELECT * FROM detrecepciones WHERE idRecepcion = '$idRecepcion'";
      if ($debug == 1) {
        echo '<br>$sqlCaptura: '.$sqlCaptura;
      }
      $resCaptura = mysqli_query($link,$sqlCaptura) or die(errorBD('Problemas al obtener el listado de productos a registrar, notifica a tu Administrador.',$link));

    while ($lst = mysqli_fetch_array($resCaptura)) {
      $idDetRecepcion = $lst['id'];
      $idDetLote = $lst['idLote'];
      $idDetCompra = $lst['idDetCompra'];
      $idProducto = $lst['idProducto'];
      if ($idProducto > 0) {
        $sqlCon = "SELECT * FROM stocks WHERE idProducto = $idProducto AND idSucursal = '$idSucursal'";
        $resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar tu stock, notifica a tu Administrador.',$link));
        $numRows = mysqli_num_rows($resCon);
        if ($numRows > 0) {
          if ($debug == 1) {
            echo 'si el id del producto es mayor a 0 (está en stock) lo suma en lotes.';
          }
          $datoRes = mysqli_fetch_array($resCon);
          $resIdStock = $datoRes['id'];

          $sqlUpLt = "UPDATE lotestocks lts INNER JOIN detrecepciones dtr ON lts.id = dtr.idLote SET lts.idStock = '$resIdStock' WHERE dtr.id = '$idDetRecepcion'";
          if ($debug == 1) {
            echo '<br>$sqlUpLt:'.$sqlUpLt;
          }
          $rseUpLt = mysqli_query($link,$sqlUpLt) or die(errorBD('Problemas al vincular el stock con el lote, notifica a tu Administrador.',$link));

          $sqlUp2 = "UPDATE detrecepciones dtr
          INNER JOIN lotestocks lts ON dtr.idLote = lts.id
          INNER JOIN stocks s ON lts.idStock = s.id
          SET dtr.cantFinal = (s.cantActual + dtr.cantidad),lts.cant = (lts.cant + dtr.cantidad), s.cantActual = (s.cantActual + dtr.cantidad),dtr.estatus = '2'
          WHERE dtr.id = '$idDetRecepcion'";

        } else {
          if ($debug == 1) {
              echo 'Si el id del producto no se encuentra en stock, lo crea en el sotck antes de sumarlo';
            }
          $sqlUp3 = "INSERT INTO stocks(idSucursal,idProducto,cantMinima,cantActual,fechaReg,idUserReg,anaquel,cantFinal,cantMaxima,notificaMin)
                                VALUES('$idSucursal','$idProducto','1','0',  NOW(),   '$idUser',  '0',    '0',     '2',       '1')";
          if ($debug == 1) {
              echo '<br>$sqlUp3: '.$sqlUp3;
            }
          $resUp3 = mysqli_query($link,$sqlUp3) or die(errorBD('Problemas al crear el stock, notifica a tu Administrador.',$link));
          $newIdStock = mysqli_insert_id($link);

          $sqlUpLt = "UPDATE lotestocks lts INNER JOIN detrecepciones dtr ON lts.id = dtr.idLote SET lts.idStock = '$newIdStock' WHERE dtr.id = '$idDetRecepcion'";
          if ($debug == 1) {
            echo '<br>$sqlUpLt:'.$sqlUpLt;
          }
          $rseUpLt = mysqli_query($link,$sqlUpLt) or die(errorBD('Problemas al vincular el stock con el lote, notifica a tu Administrador.',$link));

          $sqlUp2 = "UPDATE detrecepciones dtr
          INNER JOIN lotestocks lts ON dtr.idLote = lts.id
          INNER JOIN stocks s ON lts.idStock = s.id
          SET dtr.cantFinal = (s.cantActual + dtr.cantidad),lts.cant = (lts.cant + dtr.cantidad), s.cantActual = (s.cantActual + dtr.cantidad),dtr.estatus = '2'
          WHERE dtr.id = '$idDetRecepcion'";
        }
        $mensaje = 'Se ha capturado la recepción de la mercancia.';
      } else {

        if ($debug == 1) {
          echo 'si el id del producto es 0 significa que es un producto que no se encuentra en stock (por ejemplo: unos lapiceros o libretas)';
        }
        $sqlUp2 = "UPDATE detrecepciones dtr
                  LEFT JOIN (SELECT IF(SUM(drp.cantidad) > 0,SUM(drp.cantidad),0) AS cantRecibida, drp.idDetCompra
                  FROM recepciones r
                  INNER JOIN detrecepciones drp ON r.id = drp.idRecepcion
                  WHERE r.idCompra = 2 AND drp.estatus = '2' AND drp.idDetCompra = '$idDetCompra') d ON dtr.idDetCompra = d.idDetCompra
                  SET dtr.cantFinal = 0, dtr.estatus = '2' WHERE dtr.id = '$idDetRecepcion'";
      }
      if ($debug == 1) {
        echo '<br>$sqlUp2:'.$sqlUp2;
      }
      $resUp2 = mysqli_query($link,$sqlUp2) or die(errorBD('Problemas al editar el stock(2), notifica a tu Administrador.',$link));
    }

    $sqlUp = "UPDATE recepciones SET estatus = '2', entrega = '$nombreEntrega' WHERE id = '$idRecepcion'";
    if ($debug == 1) {
      echo '<br>$sqlUp:'.$sqlUp;
    }
    $resUp = mysqli_query($link,$sqlUp) or die(errorBD('Problemas al actualizar la recepción, notifica a tu Administrador.',$link));

    $sqlUp2 = "UPDATE detrecepciones SET estatus = '3' WHERE idRecepcion = '$idRecepcion' AND estatus = '1'";
    if ($debug == 1) {
      echo '<br>$sqlUp2:'.$sqlUp2;
    }
    $resUp2 = mysqli_query($link,$sqlUp2) or die(errorBD('Problemas al actualizar la recepción(2), notifica a tu Administrador.',$link));

    } else {


      $sqlConRe = "SELECT * FROM detrecepciones WHERE idRecepcion = '$idRecepcion'";
      $resConRe =  mysqli_query($link,$sqlConRe) or die(errorBD('Problemas al verificar el detallado de recepción, notifica a tu Administrador.',$link));
      $cantDetRe = mysqli_num_rows($resConRe);

      if ($cantDetRe > 1) {
          $sqlCons = "SELECT lts.id
                      FROM detrecepciones dtr
                      LEFT JOIN lotestocks lts ON dtr.idLote = lts.id
                      WHERE lts.cant = 0 AND dtr.idRecepcion != '$idRecepcion' AND dtr.estatus = '1'";
          $resCons = mysqli_query($link,$sqlCons) or die(errorBD('Problemas al consultar para eliminar lotes, notifica a tu Administrador.',$link));
          $cantLt = mysqli_num_rows($resCons);
          if ($cantLt > 0) {
            $sqlDel = "UPDATE detrecepciones SET estatus = '3' WHERE idRecepcion = '$idRecepcion'";
          } else {
            $sqlDel2 = "DELETE lts FROM lotestocks lts INNER JOIN detrecepciones dtr ON lts.id = dtr.idLote WHERE lts.cant = 0 AND dtr.idRecepcion = '$idRecepcion'";
            if ($debug == 1) {
              echo '<br>$sqlDel2:'.$sqlDel2;
            }
            $resDel2 = mysqli_query($link,$sqlDel2) or die(errorBD('Problemas al eliminar los lotes, notifica a tu Administrador.',$link));
            $sqlDel = "DELETE FROM detrecepciones WHERE idRecepcion = '$idRecepcion'";
          }


          if ($debug == 1) {
            echo '<br>$sqlDel:'.$sqlDel;
          }
          $resDel = mysqli_query($link,$sqlDel) or die(errorBD('Problemas al eliminar las recepciones, notifica a tu Administrador.',$link));
        }

        $sqlUp = "UPDATE recepciones SET estatus = '3' WHERE id = '$idRecepcion'";
        if ($debug == 1) {
          echo '<br>$sqlUp:'.$sqlUp;
        }
        $resUp = mysqli_query($link,$sqlUp) or die(errorBD('Problemas al iniciar, notifica a tu Administrador.',$link));

      $mensaje = 'Se ha cancelado la recepción.';
    }

    if ($debug == 1) {
      $sqlInit2 = "ROLLBACK";
      echo '<br>$sqlInit2:'.$sqlInit2;
    } else {
      $sqlInit2 = "COMMIT";

    }
    $resInit2 = mysqli_query($link,$sqlInit2) or die(errorBD('Problemas al Cargar los productos, notifica a tu Administrador.',$link));

    if ($debug == 1) {

    } else {
      if ($vista == 2) {
        if ($debug == 1) {
          echo "Todo ok, manda a ADMINISTRADOR adminRecibeProductos.php";
          echo '<br>Con mensaje: '.$mensaje;
        } else {
          $_SESSION['LZFmsjSuccessRecibeProductoAdmin'] = $mensaje;
          header('location: ../funciones/ticketLanzaRecepcion.php?idRecepcion='.$idRecepcion);
          exit(0);
        }
      } else {
        if ($debug == 1) {
          echo "Todo ok, manda a BODEGA recibeProductos.php";
          echo '<br>Con mensaje: '.$mensaje;
        } else {
          $_SESSION['LZFmsjSuccessRecibeProductoBodega'] = $mensaje;
          header('location: ../funciones/ticketLanzaRecepcion.php?idRecepcion='.$idRecepcion);
          exit(0);
        }
      }
    }

} elseif ($estatus != 1 && $idRecepcion > 0) {
  errorBD2('No se realizó ningún cambio debido a que la recepción no estaba abierta.');
} else {

  if ($vista == 2) {
    if ($debug == 1) {
      echo "Todo ok, manda a ADMINISTRADOR adminRecibeProductos.php";
    } else {
      header('location: ../Administrador/adminRecibeProductos.php');
      exit(0);
    }
  } else {
    if ($debug == 1) {
      echo "Todo ok, manda a BODEGA recibeProductos.php";
    } else {
      header('location: ../Bodega/recibeProductos.php');
      exit(0);
    }
  }

}

function errorBD($error,$link){
  if ($GLOBALS['vista'] = 2) {
    if ($GLOBALS['debug'] == 1) {
        $sqlInit3 = "ROLLBACK";
        $resInit3 = mysqli_query($link,$sqlInit3) or die('Problemas al cancelar, notifica a tu Administrador.');

      echo "Entró a errorBD: '.$error.', manda a ADMINISTRADOR adminRecibeProductos.php";
      exit(0);
    } else {
      $sqlInit3 = "ROLLBACK";
      $resInit3 = mysqli_query($link,$sqlInit3) or die('Problemas al cancelar, notifica a tu Administrador.');

      $_SESSION['LZFmsjRecibeProductoAdmin'] = $error;
      header('location: ../Administrador/adminRecibeProductos.php');
      exit(0);
    }
  } else {
    if ($GLOBALS['debug'] == 1) {
      $sqlInit3 = "ROLLBACK";
      $resInit3 = mysqli_query($link,$sqlInit3) or die('Problemas al cancelar, notifica a tu Administrador.');
      echo "Entró a errorBD: '.$error.', manda a BODEGA recibeProductos.php";
      exit(0);
    } else {
      $sqlInit3 = "ROLLBACK";
      $resInit3 = mysqli_query($link,$sqlInit3) or die('Problemas al cancelar, notifica a tu Administrador.');

      $_SESSION['LZFmsjRecibeProductoBodega'] = $error;
      header('location: ../Bodega/recibeProductos.php');
      exit(0);
    }
  }
}

function errorBD2($error){
  if ($GLOBALS['vista'] == 2) {
    if ($GLOBALS['debug'] == 1) {
      echo "Entró a errorBD2: '.$error.', manda a BODEGA recibeProductos.php";
      exit(0);
    } else {
      $_SESSION['LZFmsjRecibeProductoAdmin'] = $error;
      header('location: ../Administrador/adminRecibeProductos.php');
      exit(0);
    }
  } else {
    if ($GLOBALS['debug'] == 1) {
      echo "Entró a errorBD2: '.$error.', manda a BODEGA recibeProductos.php";
      exit(0);
    } else {
      $_SESSION['LZFmsjRecibeProductoBodega'] = $error;
      header('location: ../Bodega/recibeProductos.php');
      exit(0);
    }
  }
}

#*/
 ?>
