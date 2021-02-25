<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
require_once('StockTrasp.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
//-------------------------------------------INICIA TRANSACCION----------------------------------------------------------//
$cantEnLote = '';
$cantEnvio = '';
$cantSeleccionada = '';
$ident = (isset($_POST['ident']) and $_POST['ident'] != '') ? $_POST['ident'] : '';
$debug = 0;
if ($ident == '') {
    errorBD("Problemas Al Recibir los Datos. Inténtalo de Nuevo.", '');
}
//----------------devBug------------------------------
if ($debug == 1) {
    print_r($_POST);
    echo '<br><br>';
} else {
    error_reporting(0);
}  //-------------Finaliza devBug------------------------------

$sql = "SELECT
tr.estatus,
dt.id AS idDetTraspaso,
dt.idProducto, 
prod.descripcion AS nombreProducto,
IF(dtlt.cantEnLotes IS NULL, '0', dtlt.cantEnLotes) AS TotalDeLotes,
IF(dtlt.numLote IS NULL, '0', dtlt.numLote) AS NumLote,
dt.cantEnvio AS cantProd
FROM
traspasos tr
INNER JOIN dettraspasos dt ON tr.id=dt.idTraspaso
LEFT JOIN (SELECT idDetTraspaso, COUNT(id) AS numLote,SUM(cantidad) AS cantEnLotes FROM dettrasplote GROUP BY idDetTraspaso ) dtlt 
ON dt.id=dtlt.idDetTraspaso
INNER JOIN productos prod ON dt.idProducto=prod.id 
WHERE tr.id='$ident';";
//----------------devBug------------------------------
if ($debug == 1) {
    $resultXTraspasos = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Traspasos, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
    $resultXTraspasos = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Traspasos, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$cantLotesRegistrados = mysqli_num_rows($resultXTraspasos);
$productosErroneos = '';
if ($cantLotesRegistrados > 0) {

    while ($data = mysqli_fetch_array($resultXTraspasos)) {
        if ($data['estatus'] != '1') {
            errorBD("El Traspaso Se Encuentra En Otro Estado. Inténtalo de Nuevo.", '0');
        }
        $NumLote = $data['NumLote'];
        $TotalDeLotes = $data['TotalDeLotes'];
        $cantProd = $data['cantProd'];
        $nombreProd = $data['nombreProducto'];
        $idDetTraspaso = $data['idDetTraspaso'];
        $producto = $data['idProducto'];
        //----------------devBug------------------------------
        if ($debug == 1) {
            print_r('Id Producto: ' . $producto);
        } //-------------Finaliza devBug------------------------------
        mysqli_autocommit($link, FALSE);
        mysqli_begin_transaction($link);

        if ($NumLote > 0) {
            //INICIA PROCESO MANUAL
            if ($cantProd == $TotalDeLotes) {

                /*EL LOTE ES IGUAL AL PRODUCTO*/
            } else {
                errorBD("Verifica Las Cantidades Seleccionadas de Los Lotes.", '0');
            }
        } else {
            //INICIA PROCESO AUTOMATICO
            if ($NumLote == 0) {

                //----------------devBug------------------------------

                if ($debug == '1') {
                    print_r("Producto A Descontar: " . $producto);
                    print_r("Cantidad A Enviar: " . $cantEnvio);
                } //-------------Finaliza devBug------------------------------

                $sql = "SELECT
                lote.id,
                lote.cant,
                lote.caducidad 
                FROM
                lotestocks lote
                WHERE lote.idProducto='$producto'
                AND lote.idSucursal='$sucursal'
                AND lote.estatus='1'
                AND lote.cant>0
                ORDER BY lote.caducidad ASC";
                //----------------devBug------------------------------
                if ($debug == 1) {
                    $resultXLotes = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                    $canInsert = mysqli_affected_rows($link);
                    echo '<br>SQL: ' . $sql . '<br>';
                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                } else {
                    $resultXLotes = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                    $canInsert = mysqli_affected_rows($link);
                } //-------------Finaliza devBug------------------------------
                if (mysqli_num_rows($resultXLotes) > 0) {
                    $cantPendiente = $cantProd;
                    //----------------devBug------------------------------
                    if ($debug == 1) {
                        print_r("<br> Cantidad Pendiente: " . $cantPendiente);
                    } //-------------Finaliza devBug------------------------------

                    while ($dataXLote = mysqli_fetch_array($resultXLotes)) {
                        $cantEnLote = $dataXLote['cant'];
                        $idLote = $dataXLote['id'];
                        if ($cantPendiente > 0) {
                            if ($cantEnLote >= $cantPendiente) {
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    print_r("<br> Cantidad Pendiente Ciclo Manual: " . $cantPendiente);
                                } //-------------Finaliza devBug------------------------------
                                $sql = "INSERT INTO dettrasplote (idDetTraspaso, idLoteSalida, cantidad) 
                            VALUES('$idDetTraspaso','$idLote','$cantPendiente')";

                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                                $cantPendiente = 0;
                            } else {
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    print_r("<br> Cantidad Pendiente Menor: " . $cantPendiente);
                                    print_r("<br> Cantidad Pendiente Lote: " . $cantEnLote);
                                } //-------------Finaliza devBug------------------------------
                                $sql = "INSERT INTO dettrasplote (idDetTraspaso, idLoteSalida, cantidad) 
                                VALUES('$idDetTraspaso','$idLote','$cantEnLote')";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes por Descontar, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                                $cantPendiente -= $canEnLote;
                            }
                        }
                    }
                    if ($cantPendiente > 0) {
                        $productosErroneos .= '<b>' . $nombreProd . '<b><br>';
                    }
                } else {
                    errorBD("No Hay Lotes Destinados Al Producto.", '0');
                }
            }
        }
    }
    if ($productosErroneos != '') {
        errorBD("Los lotes de estos productos no alcanzan a surtir: <br>" . $productosErroneos, '1');
    }

    $sql = "UPDATE
    traspasos tr 
    INNER JOIN dettraspasos dt ON tr.id=dt.idTraspaso
    INNER JOIN stocks stk ON tr.idSucSalida =stk.idSucursal AND dt.idProducto=stk.idProducto
    INNER JOIN dettrasplote dtlt ON dt.id=dtlt.idDetTraspaso
    INNER JOIN lotestocks lote ON dtlt.idLoteSalida=lote.id
    SET
    tr.estatus='2', 
    dt.cantFinalEnv=stk.cantActual-dt.cantEnvio,
    stk.cantActual= stk.cantActual-dt.cantEnvio,
    dtlt.cantLoteFinalEnv=lote.cant-dtlt.cantidad,
    lote.cant=lote.cant-dtlt.cantidad,
    tr.idUserEnvio='$userReg',
    tr.fechaEnvio=NOW()
    WHERE dt.idTraspaso = '$ident'";
    //----------------devBug------------------------------
    if ($debug == 1) {
        mysqli_query($link, $sql) or die(errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador' . mysqli_error($link), 1));
        $canInsert = mysqli_affected_rows($link);
        echo '<br>SQL: ' . $sql . '<br>';
        echo '<br>Cant de Registros Cargados: ' . $canInsert;
    } else {
        mysqli_query($link, $sql) or die(errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1));
        $canInsert = mysqli_affected_rows($link);
    } //-------------Finaliza devBug------------------------------
    if ($canInsert == 0) {
        errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1);
    }
    $sql = "SELECT  
    dt.idProducto,
    SUM(stk.cantActual) AS totalStock
    FROM traspasos tr
    INNER JOIN dettraspasos dt ON tr.id=dt.idTraspaso
    INNER JOIN stocks stk ON tr.idSucSalida =stk.idSucursal AND dt.idProducto=stk.idProducto
    WHERE tr.id='$ident' AND stk.cantActual<'0'
    GROUP BY stk.id";
    //----------------devBug------------------------------
    if ($debug == 1) {
        $validStock = mysqli_query($link, $sql) or die(errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1));
        $canInsert = mysqli_affected_rows($link);
        echo '<br>SQL: ' . $sql . '<br>';
        echo '<br>Cant de Registros Cargados: ' . $canInsert;
    } else {
        $validStock = mysqli_query($link, $sql) or die(errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1));
        $canInsert = mysqli_affected_rows($link);
    } //-------------Finaliza devBug------------------------------
    if (mysqli_num_rows($validStock) > 0) {
        errorBD('No tienes la Cantidad de Productos Suficientes.', 1);
    } else {


        $sql = "SELECT  
        dt.idProducto,
        SUM(lote.cant) AS totalLote
        FROM traspasos tr
        INNER JOIN dettraspasos dt ON tr.id=dt.idTraspaso
        INNER JOIN dettrasplote dtlt ON dt.id=dtlt.idDetTraspaso
        INNER JOIN lotestocks lote ON dtlt.idLoteSalida=lote.id
        WHERE tr.id='$ident' AND lote.cant<'0'
        GROUP BY lote.id";
        //----------------devBug------------------------------
        if ($debug == 1) {
            $validLote = mysqli_query($link, $sql) or die(errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1));
            $canInsert = mysqli_affected_rows($link);
            echo '<br>SQL: ' . $sql . '<br>';
            echo '<br>Cant de Registros Cargados: ' . $canInsert;
        } else {
            $validLote = mysqli_query($link, $sql) or die(errorBD('Problemas al Efectuar la Operación Final, notifica a tu Administrador', 1));
            $canInsert = mysqli_affected_rows($link);
        } //-------------Finaliza devBug------------------------------
        if (mysqli_num_rows($validLote) > 0) {
            errorBD('No tienes la Cantidad Suficiente de Los Lotes.', 1);
        }
    }
} else {
    errorBD("No hay Registro de Detalles de Traspasos. Inténtalo de Nuevo.", '0');
}


if (mysqli_num_rows($validLote) > 0) {
    errorBD('No tienes la Cantidad Suficiente de Los Lotes.', 1);
}

$sql = "DELETE dtl
FROM
	dettrasplote dtl
INNER JOIN dettraspasos dt ON dtl.idDetTraspaso=dt.id
INNER JOIN traspasos t ON dt.idTraspaso=t.id
WHERE t.id='$ident' AND dtl.cantidad<=0";
//----------------devBug------------------------------
if ($debug == 1) {
    $validLote = mysqli_query($link, $sql) or die(errorBD('Problemas al Ajustar Lotes la Operación Final, notifica a tu Administrador', 1));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
    $validLote = mysqli_query($link, $sql) or die(errorBD('Problemas al Ajustar Lotes la Operación Final, notifica a tu Administrador', 1));
    $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

if(mysqli_commit($link)){
    echo '1|Traspaso Se Ha Enviado Correctamente';

}








function errorBD($error, $NecesitaRollBack)
{
    $link = $GLOBALS["link"];
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
