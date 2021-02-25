<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$producto = '';
$idStock = '';
$nombreLote = '';
mysqli_autocommit($link, FALSE);
mysqli_begin_transaction($link);
$debug = 0;
$HayEntradas = 0;
$ident = (isset($_POST['idAjuste']) and $_POST['idAjuste'] != '') ? $_POST['idAjuste'] : '';

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
    dtajs.id AS idDetAjuste,
    dtajs.idProducto, 
    prod.descripcion AS nombreProducto,
    dtajs.tipo AS tipoAjs,
    IF(dtlt.cantEnLotes IS NULL, '0', dtlt.cantEnLotes) AS TotalDeLotes,
    IF(dtlt.numLote IS NULL, '0', dtlt.numLote) AS NumLote,
    dtajs.cantidad AS cantProd,
    ajs.estatus
    FROM
    ajustes ajs
    INNER JOIN detajustes dtajs ON ajs.id=dtajs.idAjuste
    LEFT JOIN (SELECT idDetAjuste, COUNT(id) AS numLote,SUM(cantidad) AS cantEnLotes FROM detajustelote GROUP BY idDetAjuste ) dtlt 
    ON dtajs.id=dtlt.idDetAjuste
    INNER JOIN productos prod ON dtajs.idProducto=prod.id 
    WHERE ajs.id='$ident' ORDER BY dtajs.tipo DESC";
//----------------devBug------------------------------
if ($debug == 1) {
    $resultXAjuste = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Ajustes, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
    $resultXAjuste = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Ajustes, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

$cantLotesRegistrados = mysqli_num_rows($resultXAjuste);
$productosErroneos = '';
$HaySalidas = 0;
if ($cantLotesRegistrados > 0) {
    while ($DataAjustes = mysqli_fetch_array($resultXAjuste)) {
        if ($DataAjustes['estatus'] != '3') {
            errorBD("El Ajuste Se Encuentra En Otro Estado. Inténtalo de Nuevo.", '0');
        }

        $tipoAjs = $DataAjustes["tipoAjs"];
        $idDetAjs = $DataAjustes["idDetAjuste"];
        $idProducto = $DataAjustes["idProducto"];
        $NumLote = $DataAjustes['NumLote'];
        $TotalDeLotes = $DataAjustes['TotalDeLotes'];
        $cantProd = $DataAjustes['cantProd'];
        $nombreProd = $DataAjustes['nombreProducto'];



        if ($tipoAjs == '2') {

            $HaySalidas++;
            if ($NumLote > 0) {
                //INICIA PROCESO MANUAL
                if ($cantProd != $TotalDeLotes) {
                    errorBD("Verifica Las Cantidades Seleccionadas de Los Lotes.", '0');
                }
            } else {
                //INICIA PROCESO AUTOMATICO
                if ($NumLote == 0) {

                    //----------------devBug------------------------------

                    if ($debug == '1') {
                        print_r("Producto A Descontar: " . $idProducto);
                        print_r("Cantidad A Enviar: " . $cantProd);
                    } //-------------Finaliza devBug------------------------------

                    $sql = "SELECT
                    lote.id,
                    lote.cant,
                    lote.caducidad 
                    FROM
                    lotestocks lote
                    WHERE lote.idProducto='$idProducto'
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
                                    $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) 
                                    VALUES('$idDetAjs','$idLote','$cantPendiente')";

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
            } //Acaba Automatico

        } //Ajuste Salida
        else if ($tipoAjs == '1') {
            $HayEntradas++;
            if ($NumLote > 0) {
                //INICIA PROCESO MANUAL
                if ($cantProd != $TotalDeLotes) {
                    errorBD("Verifica Las Cantidades Seleccionadas de Los Lotes.", '0');
                }
            } else {
                //----------------devBug------------------------------
                if ($debug == 1) {

                    print_r("Num Lotes-> " . $NumLote);
                } //-------------Finaliza devBug------------------------------

                //INICIA PROCESO AUTOMATICO
                if ($NumLote == 0) {
                    //----------------devBug------------------------------
                    if ($debug == 1) {

                        print_r($HaySalidas . " de Salidas");
                    } //-------------Finaliza devBug------------------------------


                    if ($HaySalidas > 0) {
                        //----------------devBug------------------------------
                        if ($debug == 1) {

                            print_r("Hay Salidas");
                        } //-------------Finaliza devBug------------------------------


                        $sql = "SELECT
                        loteSal.id AS idSal,
                        loteSal.caducidad AS cadSal,
                        loteSal.lote AS loteSal ,
                        iF(loteEnt.id IS NULL, 'CREAR',loteEnt.id) AS lotEnt,
                        loteEnt.caducidad AS cadEnt,
                        loteEnt.lote AS loteEnt
                        FROM
                        ajustes ajs
                        INNER JOIN detajustes dtaj ON ajs.id = dtaj.idAjuste 
                        INNER JOIN detajustelote dtajslte ON dtaj.id = dtajslte.idDetAjuste
                        INNER JOIN lotestocks loteSal ON dtajslte.idLote = loteSal.id AND dtaj.tipo='2'
                        LEFT JOIN lotestocks loteEnt ON dtajslte.idLote = loteEnt.id AND dtaj.tipo='1' AND loteSal.caducidad= loteEnt.caducidad
                        WHERE
                        loteSal.caducidad != '0000-00-00' AND loteSal.caducidad IS NOT NULL AND ajs.id='$ident'
                        ORDER BY
                        loteSal.caducidad ASC LIMIT 1";

                        //----------------devBug------------------------------
                        if ($debug == 1) {
                            $resultXLoteIns = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Lote Con Menor Caducidad, notifica a tu Administrador' . mysqli_error($link), '0'));
                            $canInsert = mysqli_affected_rows($link);
                            echo '<br>SQL: ' . $sql . '<br>';
                            echo '<br>Cant de Registros Cargados: ' . $canInsert;
                        } else {
                            $resultXLoteIns = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Lote Con Menor Caducidad, notifica a tu Administrador' . mysqli_error($link), '0'));
                            $canInsert = mysqli_affected_rows($link);
                        } //-------------Finaliza devBug------------------------------
                        if (mysqli_num_rows($resultXLoteIns) > 0) {

                            $DatosDeLotesInsertar = mysqli_fetch_array($resultXLoteIns);
                            $loteEnt = $DatosDeLotesInsertar['lotEnt'];
                            $caducidadEnt = $DatosDeLotesInsertar['cadEnt'];
                            $caducidadSal = $DatosDeLotesInsertar['cadSal'];
                            if ($loteEnt == 'CREAR') {
                                $sql = "SELECT
                                stk.id AS idStock,
                                IF
                                ( lote.id IS NULL, 'CREAR', lote.id ) AS loteGral 
                                FROM
                                stocks stk
                                LEFT JOIN lotestocks lote ON stk.id = lote.idStock 	AND 
                                lote.caducidad = '$caducidadSal' 
                                
                                WHERE
                                stk.idSucursal = '$sucursal' 
                                AND stk.idProducto = '$idProducto' 
                                ";


                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    $resultXLoteGral = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    $resultXLoteGral = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                                if (mysqli_num_rows($resultXLoteGral) > 0) {
                                    $arrayStockInfo = mysqli_fetch_array($resultXLoteGral);
                                    $idStock = $arrayStockInfo['idStock'];
                                    $loteGral = $arrayStockInfo['loteGral'];
                                    $caducidadNueva = str_replace("-", "", $caducidadSal);
                                    $nombreLote = "lts_" . $idProducto . "_" . $caducidadNueva;

                                    if ($debug == '1') {
                                        echo '<br>' . $idStock . '</br>';
                                        echo '<br>' . $loteGral . '</br>';
                                    }
                                    if ($loteGral == 'CREAR') {
                                        $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES ('$sucursal','$idProducto','$idStock','$nombreLote', '$caducidadSal', '0', '1')";
                                        //----------------devBug------------------------------
                                        if ($debug == 1) {
                                            mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                            $canInsert = mysqli_affected_rows($link);
                                            echo '<br>SQL: ' . $sql . '<br>';
                                            echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                        } else {
                                            mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador', 1));
                                            $canInsert = mysqli_affected_rows($link);
                                        } //-------------Finaliza devBug------------------------------
                                        $loteGral = mysqli_insert_id($link);

                                        $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                        //----------------devBug------------------------------
                                        if ($debug == 1) {
                                            mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                            $canInsert = mysqli_affected_rows($link);
                                            echo '<br>SQL: ' . $sql . '<br>';
                                            echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                        } else {
                                            mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                            $canInsert = mysqli_affected_rows($link);
                                        } //-------------Finaliza devBug------------------------------

                                    } else {

                                        $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                        //----------------devBug------------------------------
                                        if ($debug == 1) {
                                            mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                            $canInsert = mysqli_affected_rows($link);
                                            echo '<br>SQL: ' . $sql . '<br>';
                                            echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                        } else {
                                            mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                            $canInsert = mysqli_affected_rows($link);
                                        } //-------------Finaliza devBug------------------------------



                                    }
                                } else {

                                    $sql = "INSERT INTO stocks (idSucursal, idProducto, fechaReg, idUserReg)  VALUES('$sucursal','$idProducto',NOW(),'$userReg')";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Nuevo Stock, notifica a tu Administrador' . $sql, 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Nuevo Stock, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------
                                    $idStock = mysqli_insert_id($link);


                                    $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES ('$sucursal','$idProducto','$idStock','$nombreLote', '0000-00-00','0', '1')";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------
                                    $loteGral = mysqli_insert_id($link);


                                    $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------
                                }
                            } else {
                                if ($caducidadSal == $caducidadEnt) {

                                    $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteEnt') ";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lote de Entrada Con Caducidad  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lote de Entrada Con Caducidad  de Producto, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------

                                }
                            }
                        } else {

                            $sql = "SELECT
                            stk.id AS idStock,
                            IF(lote.id IS NULL, 'CREAR', lote.id) AS loteGral
                            FROM
                            stocks stk
                            LEFT JOIN lotestocks lote ON stk.id = lote.idStock 
                            AND (
                                lote.caducidad = '0000-00-00' 
                            OR lote.caducidad IS NULL)
                            WHERE
                            stk.idSucursal = '$sucursal' AND stk.idProducto='$idProducto'
                            ";


                            //----------------devBug------------------------------
                            if ($debug == 1) {
                                $resultXLoteGral = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                                $canInsert = mysqli_affected_rows($link);
                                echo '<br>SQL: ' . $sql . '<br>';
                                echo '<br>Cant de Registros Cargados: ' . $canInsert;
                            } else {
                                $resultXLoteGral = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                                $canInsert = mysqli_affected_rows($link);
                            } //-------------Finaliza devBug------------------------------
                            if (mysqli_num_rows($resultXLoteGral) > 0) {
                                $arrayStockInfo = mysqli_fetch_array($resultXLoteGral);
                                $idStock = $arrayStockInfo['idStock'];
                                $loteGral = $arrayStockInfo['loteGral'];
                                $nombreLote = "lts_" . $idProducto . "_gral";

                                if ($debug == '1') {
                                    echo '<br>' . $idStock . '</br>';
                                    echo '<br>' . $loteGral . '</br>';
                                }
                                if ($loteGral == 'CREAR') {
                                    $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES ('$sucursal','$idProducto','$idStock','$nombreLote', '0000-00-00', '0', '1')";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------
                                    $loteGral = mysqli_insert_id($link);

                                    $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------

                                } else {

                                    $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                    //----------------devBug------------------------------
                                    if ($debug == 1) {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                        $canInsert = mysqli_affected_rows($link);
                                        echo '<br>SQL: ' . $sql . '<br>';
                                        echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                    } else {
                                        mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                        $canInsert = mysqli_affected_rows($link);
                                    } //-------------Finaliza devBug------------------------------



                                }
                            } else {

                                $sql = "INSERT INTO stocks (idSucursal, idProducto, fechaReg, idUserReg)  VALUES('$sucursal','$idProducto',NOW(),'$userReg')";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Nuevo Stock, notifica a tu Administrador' . $sql, 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Nuevo Stock, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                                $idStock = mysqli_insert_id($link);


                                $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES ('$sucursal','$idProducto','$idStock','$nombreLote', '0000-00-00', '0', '1')";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                                $loteGral = mysqli_insert_id($link);


                                $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                            }
                        }
                    } else {
                        $sql = "SELECT
                        stk.id AS idStock,
                        IF(lote.id IS NULL, 'CREAR', lote.id) AS loteGral
                        FROM
                        stocks stk
                        LEFT JOIN lotestocks lote ON stk.id = lote.idStock     AND (
                            lote.caducidad = '0000-00-00' 
                        OR lote.caducidad IS NULL)
                        WHERE
                        stk.idSucursal = '$sucursal' AND stk.idProducto='$idProducto'
                      ";

                        //----------------devBug------------------------------
                        if ($debug == 1) {
                            $resultXLoteGral = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                            $canInsert = mysqli_affected_rows($link);
                            echo '<br>SQL: ' . $sql . '<br>';
                            echo '<br>Cant de Registros Cargados: ' . $canInsert;
                        } else {
                            $resultXLoteGral = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Detalle de los Lotes por Descontar, notifica a tu Administrador' . mysqli_error($link), '0'));
                            $canInsert = mysqli_affected_rows($link);
                        } //-------------Finaliza devBug------------------------------

                        if (mysqli_num_rows($resultXLoteGral) > 0) {
                            $arrayStockInfo = mysqli_fetch_array($resultXLoteGral);
                            $idStock = $arrayStockInfo['idStock'];
                            $loteGral = $arrayStockInfo['loteGral'];
                            $nombreLote = "lts_" . $idProducto . "_gral";

                            if ($debug == '1') {
                                echo '<br>' . $idStock . '</br>';
                                echo '<br>' . $loteGral . '</br>';
                            }
                            if ($loteGral == 'CREAR') {
                                $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES ('$sucursal','$idProducto','$idStock','$nombreLote', '0000-00-00', '0', '1')";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------
                                $loteGral = mysqli_insert_id($link);

                                $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------

                            } else {

                                $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                                //----------------devBug------------------------------
                                if ($debug == 1) {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                    $canInsert = mysqli_affected_rows($link);
                                    echo '<br>SQL: ' . $sql . '<br>';
                                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                                } else {
                                    mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                    $canInsert = mysqli_affected_rows($link);
                                } //-------------Finaliza devBug------------------------------


                            }
                        } else {

                            $sql = "INSERT INTO stocks (idSucursal, idProducto, fechaReg, idUserReg)  VALUES('$sucursal','$idProducto',NOW(),'$userReg')";
                            //----------------devBug------------------------------
                            if ($debug == 1) {
                                mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Nuevo Stock, notifica a tu Administrador' . mysqli_error($link), 1));
                                $canInsert = mysqli_affected_rows($link);
                                echo '<br>SQL: ' . $sql . '<br>';
                                echo '<br>Cant de Registros Cargados: ' . $canInsert;
                            } else {
                                mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Nuevo Stock, notifica a tu Administrador', 1));
                                $canInsert = mysqli_affected_rows($link);
                            } //-------------Finaliza devBug------------------------------
                            $idStock = mysqli_insert_id($link);


                            $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, lote, caducidad, cant, estatus) VALUES ('$sucursal','$idProducto','$idStock','$nombreLote', '0000-00-00', '0', '1')";
                            //----------------devBug------------------------------
                            if ($debug == 1) {
                                mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                $canInsert = mysqli_affected_rows($link);
                                echo '<br>SQL: ' . $sql . '<br>';
                                echo '<br>Cant de Registros Cargados: ' . $canInsert;
                            } else {
                                mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Lotes General de Producto, notifica a tu Administrador', 1));
                                $canInsert = mysqli_affected_rows($link);
                            } //-------------Finaliza devBug------------------------------
                            $loteGral = mysqli_insert_id($link);


                            $sql = "INSERT INTO detajustelote (idDetAjuste, cantidad, idLote) VALUES ('$idDetAjs','$cantProd','$loteGral') ";
                            //----------------devBug------------------------------
                            if ($debug == 1) {
                                mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador' . mysqli_error($link), 1));
                                $canInsert = mysqli_affected_rows($link);
                                echo '<br>SQL: ' . $sql . '<br>';
                                echo '<br>Cant de Registros Cargados: ' . $canInsert;
                            } else {
                                mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar los Detallado de los Lotes  de Producto, notifica a tu Administrador', 1));
                                $canInsert = mysqli_affected_rows($link);
                            } //-------------Finaliza devBug------------------------------


                        }
                    }
                }
            }
        }
    }
    if ($HayEntradas > 0) {
            $sql = "UPDATE 
        ajustes ajs
        INNER JOIN detajustes da ON ajs.id = da.idAjuste 
        
        INNER JOIN stocks stk ON ajs.idSucursal = stk.idSucursal 
        AND da.idProducto = stk.idProducto
        INNER JOIN detajustelote dalt ON da.id = dalt.idDetAjuste
        INNER JOIN lotestocks lote ON dalt.idLote = lote.id 
        AND da.idProducto = lote.idProducto 
        SET ajs.estatus = '4',
        da.cantFinal = stk.cantActual + da.cantidad,
        stk.cantActual = stk.cantActual + da.cantidad,
        dalt.cantLoteFinal = lote.cant + dalt.cantidad,
        lote.cant = lote.cant + dalt.cantidad,
        ajs.idUserAplica= '$userReg',
        ajs.fechaAplica = NOW() 
        WHERE
        ajs.id = '$ident' AND da.tipo = '1'";
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
    }
    if ($HaySalidas > 0) {
            $sql = "UPDATE 
        ajustes ajs
        INNER JOIN detajustes da ON ajs.id = da.idAjuste 
        INNER JOIN stocks stk ON ajs.idSucursal = stk.idSucursal 
        AND da.idProducto = stk.idProducto
        INNER JOIN detajustelote dalt ON da.id = dalt.idDetAjuste
        INNER JOIN lotestocks lote ON dalt.idLote = lote.id
        AND da.idProducto = lote.idProducto 
        SET ajs.estatus = '4',
        da.cantFinal = stk.cantActual - da.cantidad,
        stk.cantActual = stk.cantActual - da.cantidad,
        dalt.cantLoteFinal = lote.cant - dalt.cantidad,
        lote.cant = lote.cant - dalt.cantidad,
        ajs.idUserAplica= '$userReg',
        ajs.fechaAplica = NOW() 
        WHERE
        ajs.id = '$ident' AND da.tipo='2' ";
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
    }
















    if (mysqli_commit($link)) {
        echo '1|Ajuste Se Ha Realizado Correctamente';
    }
} else {
    errorBD("No hay Registro de Detalles de Ajustes. Inténtalo de Nuevo.", '0');
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
