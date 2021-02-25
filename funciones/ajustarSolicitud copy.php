<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
//INICIA TRANSACCION
mysqli_autocommit($link, FALSE);
mysqli_begin_transaction($link);
//ID DEL AJUSTE A MODIFICAR
$ident = (isset($_POST['id_ajuste']) and $_POST['id_ajuste'] != '') ? $_POST['id_ajuste'] : '';
$nuevoLote = "";
$nombre_lote = '';

//OBTENER LOS DETALLES DE AJUSTE Y EL ID DEL PRODUCTO
$sql = "SELECT
det.id AS idDetalle,
det.tipo,
det.cantidad,
prod.id AS idProducto
FROM
ajustes ajs
INNER JOIN detajustes det ON det.idAjuste=ajs.id
INNER JOIN productos prod ON prod.id=det.idProducto
WHERE
ajs.id = '$ident'";
//print_r($sql);
$res = mysqli_query($link, $sql) or die(errorBD('Problemas al consultar ajustes, notifica a tu Administrador'));
$cant = mysqli_num_rows($res);
//SI COMPARAMOS Y HAY DETALLES DE AJUSTE
if ($cant > 0) {
    //RECORRER CADA DETALLE DE AJUSTE EN BUSCA DE UN CHECKBOX
    while ($dat = mysqli_fetch_array($res)) {
        //CONSTRUIMOS EL NAME DEL CHECKBOX
        $check_valid = 'chck_auto_' . $dat['idDetalle'];
        $iddetajuste = $dat["idDetalle"];
        $cantidad = $dat["cantidad"];
        $producto = $dat["idProducto"];
        $tipo_Ajuste = $dat['tipo'];
        //VALIDAMOS QUE EXISTA 
        if (isset($_POST[$check_valid]) and $_POST[$check_valid] == '1') {
            //echo "YA ENTRO EN CHECK VALIDADO";
            $sqllotes = "SELECT
       lote.id,
       lote.cant
    FROM
       lotestocks lote
       WHERE lote.idProducto='$producto'
       AND lote.idSucursal='$sucursal'";
            $reslote = mysqli_query($link, $sqllotes) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
            while ($datlote = mysqli_fetch_array($reslote)) {
                $cantlotes = "cantlote-" . $datlote['id'];
                //SI EXISTE EL INPUT DE CANTIDAD LOTE SE PROCEDE A LAS QUERYS 
                if (isset($_POST[$cantlotes]) and $_POST[$cantlotes] != '0') {
                    $idloteseleccionado = $datlote['id'];
                    $cantidad = $_POST[$cantlotes];
                    $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantidad')";
                    $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                    //AFECTAR LA TABLA LOTES PARA HACER EL DESCUENTO O SUMA
                    if ($tipo_Ajuste == "1") {
                        $sql = "UPDATE lotestocks SET cant=cant+$cantidad WHERE id=$idloteseleccionado";
                        //echo $sql;
                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));

                        $sql = "CALL SP_ejecutaAjusteEntrada($iddetajuste, $userReg, $sucursal, $ident)";
                        $resProce = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                    } else if ($tipo_Ajuste == "2") {
                        $sql = "UPDATE lotestocks SET cant=cant-$cantidad WHERE id=$idloteseleccionado";
                        // echo $sql;
                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));

                        //Verificar que haya el producto suficiente
                        $sqlstock = "SELECT cantActual
                    FROM stocks INNER JOIN detajustes dtajs ON
                    dtajs.idProducto= '$producto' WHERE dtajs.id='$iddetajuste'";
                        $resStock = mysqli_query($link, $sqlstock) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                        $stock_actual = mysqli_fetch_array($resStock)['cantActual'];

                        if ($stock_actual >= $cantidad) {
                            $sql = "CALL SP_ejecutaAjusteSalida($iddetajuste, $userReg, $sucursal, $ident)";
                            $resProce = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                        } else {
                            errorCarga('Lo sentimos revisa tus cantidades.');
                        }
                    }
                }
            }
        } else {


            //PARA AJUSTES DE ENTRADA AUTO
            if ($tipo_Ajuste == 1) {
                //1. BUSCAR ENTRE SUS INGREDIENTES DE LA MEZCLA EL PRODUCTO MAS CADUCO A VENCER SI ES DE TIPO 1 Y DESPUES SU LOTE
           
                $sql = 'SELECT
            lote.id 
            FROM
            lotestocks lote 
            WHERE
            lote.idProducto = "' . $dat["idProducto"] . '" 
            AND 
             lote.idSucursal = "' . $sucursal . '" 
            AND 
            lote.caducidad =(
            SELECT
              MIN( lote.caducidad ) AS caducidad 
            FROM
            detajustes det
            INNER JOIN lotestocks lote ON lote.idProducto = det.idProducto 
            AND det.idAjuste = "1" 
            AND det.tipo = "2" 
            WHERE
            lote.idSucursal = "' . $sucursal . '" 
            LIMIT 1 
            )';  //obtiene el lote aunque sea el gral
                //  echo $sql;  
                $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                if (mysqli_num_rows($resCon) > 0) {
                    //SE ACTUALIZA EL LOTE
                    $idloteseleccionado = mysqli_fetch_array($resCon)["id"];

                    $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantidad')";
                    $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                    if ($tipo_Ajuste == "1") {
                        $sql = "UPDATE lotestocks SET cant=cant+$cantidad WHERE id=$idloteseleccionado";
                        //echo $sql;
                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                    } else if ($tipo_Ajuste == "2") {
                        $sql = "UPDATE lotestocks SET cant=cant-$cantidad WHERE id=$idloteseleccionado";
                        // echo $sql;
                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                    }
                } else {

                    //-----------------------------------VERIFICAR SI EXISTE NUM 2--------------------------------------------//
                    $sqlvrfikar = "SELECT dtajs.id FROM detajustes dtajs WHERE dtajs.idAjuste='$ident' AND dtajs.tipo='2'";
                    $resCon = mysqli_query($link, $sqlvrfikar) or die(errorCarga('Problemas al consultar ajustes de tipo 2, notifica a tu Administrador'));
                    if (mysqli_num_rows($resCon) <= 0) {
                        $producto = $dat["idProducto"];
                        $sqlVerLote = "SELECT
                            lote.id 
                        FROM
                            lotestocks lote 
                        WHERE
                            lote.idProducto = '$producto'
                            AND lote.idSucursal = '$sucursal' 
                            AND lote.estatus = '1'";
                       // print_r($sqlVerLote);
                        $resConExiste = mysqli_query($link, $sqlVerLote) or die(errorCarga('Problemas al consultar ajustes de tipo 2, notifica a tu Administrador'));
                        if (mysqli_num_rows($resConExiste) <= 0) {

                                $caducidad = '0000-00-00';
                                $caducidadnombre = 'gral';
                        
                            //INSERTAR Y LUEGO AÑADIR EL LOTE
                            $nombrelote = "lts_" . $producto . "_" . $caducidadnombre;
                            //  print_r("<br>El nombre del lote es: ".$nombrelote);
                            //echo $nombrelote;
                            $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, cant,lote,caducidad, estatus) VALUES ($sucursal,$producto,(SELECT id FROM stocks WHERE idProducto='$producto' AND idSucursal='$sucursal') ,$cantidad,'$nombrelote', '$caducidad', '1')";
                            $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . $sql));
                            $idloteseleccionado = mysqli_insert_id($link);
                            // echo "This is new lote:".$nuevoLote;
                            $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantidad')";
                            $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                        } else {

                            $idloteseleccionado = mysqli_fetch_array($resConExiste)['id'];

                            $sql = "UPDATE lotestocks SET cant=cant+$cantidad WHERE id=$idloteseleccionado";
                            $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al sumar en el lote, notifica a tu Administrador'));
                            $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantidad')";
                            $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                        }
                    } else {



                        $sql = 'SELECT DATE_FORMAT(MIN( lote.caducidad ),"%Y%m%d") AS caducidadnombre, MIN( lote.caducidad) AS caducidad
                FROM detajustes det 
                INNER JOIN lotestocks lote ON lote.idProducto=det.idProducto AND det.idAjuste="' . $ident . '" AND det.tipo="2"
                WHERE lote.idSucursal = "' . $sucursal . '" AND (lote.caducidad!="0000-00-00" OR lote.caducidad!=NULL) LIMIT 1';
                        //print_r('Consulta para ver la caducidad -->'.$sql);
                        $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                        $array = mysqli_fetch_array($resCon);
                        //print_r($array);
                        if ($array["caducidad"] != '' and $array["caducidad"] != '') {


                            $caducidad = $array["caducidad"];
                            $caducidadnombre = $array["caducidadnombre"];
                        } else {
                            $caducidad = '0000-00-00';
                            $caducidadnombre = 'gral';
                        }
                        //INSERTAR Y LUEGO AÑADIR EL LOTE
                        $nombrelote = "lts_" . $producto . "_" . $caducidadnombre;
                        //  print_r("<br>El nombre del lote es: ".$nombrelote);
                        //echo $nombrelote;
                        $sql = "INSERT INTO lotestocks (idSucursal, idProducto, idStock, cant,lote,caducidad, estatus) VALUES ($sucursal,$producto,(SELECT id FROM stocks WHERE idProducto='$producto' AND idSucursal='$sucursal') ,$cantidad,'$nombrelote', '$caducidad', '1')";
                        $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . $sql));
                        $idloteseleccionado = mysqli_insert_id($link);
                        // echo "This is new lote:".$nuevoLote;
                        $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantidad')";
                        $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                    }
                }
            } else {
                //PARA AJUSTES DE SALIDA AUTO
        $sql = "SELECT
        lote.id,
        lote.cant 
    FROM
        lotestocks lote 
    WHERE
        lote.idProducto = '" . $dat["idProducto"] . " ' 
        AND lote.idSucursal = '$sucursal' 
        AND lote.cant > 0  
     AND 			lote.caducidad =(
                SELECT
                    MIN( lote.caducidad ) AS caducidad 
                FROM
                    lotestocks lote 
                WHERE
                    lote.idSucursal = '$sucursal' 
                    AND lote.idProducto = '" . $dat["idProducto"] . "' 
                AND lote.cant > 0 
        )";
                $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                if (mysqli_num_rows($resCon) > 0) {
                    //SE ACTUALIZA EL LOTE
                    $infolote = mysqli_fetch_array($resCon);
                    $cantloteactual = $infolote['cant'];
                    $poracompletar = $cantidad;
                    if ($cantloteactual >= $cantidad and $poracompletar > 0) {

                        $idloteseleccionado = $infolote["id"];
                        $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantidad')";
                        $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                        // errorCarga("Upps! Es mayor");
                        //AFECTAR LA TABLA LOTES PARA HACER EL DESCUENTO O SUMA
                        if ($tipo_Ajuste == "1") {
                            $sql = "UPDATE lotestocks SET cant=cant+$cantidad WHERE id=$idloteseleccionado";
                            //echo $sql;
                            $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                        } else if ($tipo_Ajuste == "2") {
                            $sql = "UPDATE lotestocks SET cant=cant-$cantidad WHERE id=$idloteseleccionado";
                            // echo $sql;
                            $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                        }
                        $poracompletar = 0;
                    } else {
                        // errorCarga("Upps! No te alcanza");

                        // errorCarga("Upps! No te alcanza".$poracompletar);

                        $sqlAcom = 'SELECT lote.id, lote.cant
                    FROM lotestocks lote 
                    WHERE lote.idProducto="' . $producto . '" AND lote.idSucursal="' . $sucursal . '" ORDER BY caducidad ASC
                    ';
                        $resAcom = mysqli_query($link, $sqlAcom) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
                        if (mysqli_num_rows($resAcom) > 0) {
                            while ($datlote = mysqli_fetch_array($resAcom)) {
                                //SE ACTUALIZA EL LOTE
                                $cantloteactual = $datlote['cant'];
                                $idloteseleccionado = $datlote["id"];
                                if ($cantloteactual <= $poracompletar &&  $poracompletar != 0) {
                                    $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantloteactual')";
                                    $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                                    $poracompletar = $poracompletar - $cantloteactual;
                                    if ($tipo_Ajuste == "1") {
                                        $sql = "UPDATE lotestocks SET cant=cant+$cantidad WHERE id=$idloteseleccionado";
                                        //echo $sql;
                                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                                    } else if ($tipo_Ajuste == "2") {
                                        $sql = "UPDATE lotestocks SET cant=cant-$cantloteactual WHERE id=$idloteseleccionado";
                                        // echo $sql;
                                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                                    }
                                } else if ($cantloteactual > $poracompletar &&  $poracompletar != 0) {
                                    $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$poracompletar')";
                                    $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
                                    $poracompletar = 0;
                                    if ($tipo_Ajuste == "1") {
                                        $sql = "UPDATE lotestocks SET cant=cant+$cantidad WHERE id=$idloteseleccionado";
                                        //echo $sql;
                                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                                    } else if ($tipo_Ajuste == "2") {
                                        $sql = "UPDATE lotestocks SET cant=cant-$poracompletar WHERE id=$idloteseleccionado";
                                        // echo $sql;
                                        $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' . $sql));
                                    }
                                }

                                // $sql = "INSERT INTO detajustelote (idDetAjuste, idLote, cantidad) VALUES('$iddetajuste', '$idloteseleccionado', '$cantloteactual')";
                                // $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'.mysqli_error($link)));

                            }
                        } else {
                            errorCarga("Los lotes registrados no surten el movimiento");
                        }
                    }
                } else {
                    //SINO ENCUENTRA LOTES

                    errorCarga('No se encuentra ningun dato sobre lotes, notifica a tu Administrador');
                }
            }
        }

        //CUANDO SE HACE EL CAMBIO EN EL LOTEO SE DEBE DE REALIZAR LA FUNCION DE DESCONTEO EN EL STOCK
        if ($tipo_Ajuste == "1") {
            // echo ("ya entro");
            $sql = "CALL SP_ejecutaAjusteEntrada($iddetajuste, $userReg, $sucursal, $ident)";
            $resProce = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
        } else if ($tipo_Ajuste == "2") {
            // echo ("ya entro");
            //Verificar que haya el producto suficiente
            $sqlstock = "SELECT cantActual
            FROM stocks INNER JOIN detajustes dtajs  WHERE 
            dtajs.id='$iddetajuste' AND stocks.idProducto= '$producto'  AND stocks.idSucursal='$sucursal'";
            $resStock = mysqli_query($link, $sqlstock) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));

            $datos = mysqli_fetch_array($resStock);
            $stock_actual = $datos['cantActual'];
            if ($stock_actual >= $cantidad) {
                $sql = "CALL SP_ejecutaAjusteSalida($iddetajuste, $userReg, $sucursal, $ident)";
                $resProce = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
            } else {
                // errorCarga('Lo sentimos .'.$cantidad.'|'.$stock_actual.'|'.$sqlstock);
                errorCarga('Lo sentimos revisa tus cantidades.');
            }
        }
    } //while
    $sqlaplica =
        "UPDATE ajustes ajs
 SET 
     ajs.idUserAplica = '$userReg',
     ajs.fechaAplica = NOW()
 WHERE 
     ajs.id = $ident AND ajs.estatus = 3;";
    $resaplica = mysqli_query($link, $sqlaplica) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador' . mysqli_error($link)));
    // header('location:../Administrador/reimprimeTickets.php?idVenta='.$ident.'&tipoTicket=lanzaAjuste');
    echo "1|El ajuste se ha guardado con éxito";
} else {
    errorCarga('Lo sentimos los datos no se pueden validar, notifica a tu Administrador.');
}

//  AL FINAL DEL PROCESO MANDAMOS RESULTADOS
if (mysqli_commit($link)) {
} else {
    errorCarga('Error al cargar los datos, notifica a tu Administrador.');
}

function errorCarga($error)
{
    $link = $GLOBALS["link"];
    echo '0|' . $error;
    mysqli_rollback($link);
    exit(0);
}
