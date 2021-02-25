<?php

function stockTraspasos($link, $idTraspaso, $idSucursalEnvia, $idUsuario)
{
    $debugLocal = 1;
    if ($idTraspaso > 0) {
        $sql = "SELECT COUNT(usr.nombre) AS usuarioPermiso
        FROM traspasos tps
        INNER JOIN sucursales scs ON tps.idSucSalida = scs.id AND tps.idSucSalida = '$idSucursalEnvia'
        INNER JOIN segusuarios usr ON scs.id = usr.idSucursal AND usr.estatus = 1
        WHERE tps.id = '$idTraspaso' AND usr.id = '$idUsuario';
        ";
        $resultXTraspasos = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el Usuario que Autorizó, notifica a tu Administrador', mysqli_error($link)));

        //----------------devBug------------------------------
        if ($debugLocal == 1) {

            $canInsert = mysqli_affected_rows($link);
            echo '<br>SQL: ' . $sql . '<br>';
            echo '<br>Cant de Registros Cargados: ' . $canInsert;
        } else {

            $canInsert = mysqli_affected_rows($link);
        } //-------------Finaliza devBug------------------------------
        $arrayTraspasos = mysqli_fetch_array($resultXTraspasos);
        $userPermiso = $arrayTraspasos['usuarioPermiso'];
        if ($userPermiso > 0) {

            $sql = "SELECT COUNT(*) AS faltaInStock FROM stocks stk
		INNER JOIN (
		SELECT 
			tps.idSucSalida, dtp.idProducto, dtp.cantEnvio
		FROM traspasos tps 
			INNER JOIN dettraspasos dtp ON tps.id = dtp.idTraspaso
		WHERE tps.id = $idTraspaso) AS prod ON stk.idSucursal = prod.idSucSalida AND stk.idProducto = prod.idProducto WHERE stk.cantActual < prod.cantEnvio;";
            $resultVerStock = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el total Stock, notifica a tu Administrador', mysqli_error($link)));

            //----------------devBug------------------------------
            if ($debugLocal == 1) {
                $canInsert = mysqli_affected_rows($link);
                echo '<br>SQL: ' . $sql . '<br>';
                echo '<br>Cant de Registros Cargados: ' . $canInsert;
            } else {
                $canInsert = mysqli_affected_rows($link);
            } //-------------Finaliza devBug------------------------------   
            $arrayStock = mysqli_fetch_array($resultVerStock);
            $faltaInStock = $arrayStock['faltaInStock'];
            if ($faltaInStock == 0) {
                $sql = "	UPDATE traspasos tps
			INNER JOIN dettraspasos dtp ON tps.id =  dtp.idTraspaso
			INNER JOIN stocks stk ON tps.idSucSalida = stk.idSucursal AND dtp.idProducto = stk.idProducto
		SET 
			dtp.cantFinalEnv = stk.cantActual - dtp.cantEnvio,
			stk.cantActual =  stk.cantActual - dtp.cantEnvio,
			stk.fechaReg=NOW()
		WHERE
            tps.id = $idTraspaso;";
                //----------------devBug------------------------------
                if ($debugLocal == 1) {
                    mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el total Stock, notifica a tu Administrador', mysqli_error($link)));
                    $canInsert = mysqli_affected_rows($link);
                    echo '<br>SQL: ' . $sql . '<br>';
                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                } else {
                    mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el total Stock, notifica a tu Administrador', mysqli_error($link)));
                    $canInsert = mysqli_affected_rows($link);
                } //-------------Finaliza devBug------------------------------   

                $sql = "	UPDATE traspasos tp
		SET 
			tp.idUserEnvio = $idUsuario,
			tp.fechaEnvio = NOW(),
			tp.estatus = 2
		WHERE 
            tp.id = $idTraspaso AND tp.estatus = 1;";
                //----------------devBug------------------------------
                if ($debugLocal == 1) {
                    mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el total Stock, notifica a tu Administrador', mysqli_error($link)));
                    $canInsert = mysqli_affected_rows($link);
                    echo '<br>SQL: ' . $sql . '<br>';
                    echo '<br>Cant de Registros Cargados: ' . $canInsert;
                } else {
                    mysqli_query($link, $sql) or die(errorBD('Problemas al Ver el total Stock, notifica a tu Administrador', mysqli_error($link)));
                    $canInsert = mysqli_affected_rows($link);
                } //-------------Finaliza devBug------------------------------   





                return '1|Traspaso Correcto';
            } else {

                return '0|Falta Productos Para Solventar';
            }
        } else {

            return '0|Usuario No autorizado Para Manejar el Stock';
        }
    } else {

        return '0|Verifica la informacion seleccionada';
    }
}
