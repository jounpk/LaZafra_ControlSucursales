<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];
$idCorte=(!empty($_POST['idCorte'])) ? $_POST['idCorte'] : 0;
if ($idCorte < 1) {
  errorBD('No se reconoció el Corte, vuelve a intentarlo, si persiste notifica a tu Administrador');
}
$sql =
"SELECT
/*totalesVentas.ventas,
totalesVentas.idFormaPago,
totalesVentas.totales AS montoVentas,
totalesCortes.totales AS montoCortes,
totalesFact.totales AS montoFact*/
totalesVentas.ventas,
st_fp.nombre,
totalesVentas.idFormaPago,
IF(totalesVentas.totales IS NULL,'$0.0',CONCAT('$',FORMAT(totalesVentas.totales,2))) AS montoVentas,
IF(totalesCortes.totales IS NULL,'$0.0',CONCAT('$',FORMAT(totalesCortes.totales,2))) AS montoCortes,
IF(totalesFact.totales IS NULL,'$0.0',CONCAT('$',FORMAT(totalesFact.totales,2))) AS montoFact
   FROM
   (
   SELECT GROUP_CONCAT(vtas.id) AS ventas,
   pgosvta.idFormaPago,
   SUM(pgosvta.monto) AS totales
   FROM ventas vtas 
   INNER JOIN pagosventas pgosvta ON pgosvta.idVenta = vtas.id
   WHERE vtas.idCorte='$idCorte'
   GROUP BY pgosvta.idFormaPago /*Ventas*/) totalesVentas


   LEFT JOIN (
   SELECT dtcte.idFormaPago,
   SUM(dtcte.monto) AS totales
   FROM cortes cte
   INNER JOIN detcortes dtcte ON dtcte.idCorte=cte.id
   WHERE dtcte.idCorte='$idCorte'
   GROUP BY dtcte.idFormaPago) totalesCortes ON totalesCortes.idFormaPago=totalesVentas.idFormaPago


   LEFT JOIN (
       SELECT st_fp.id AS idFormaPago,
       fact.monto AS totales
       FROM ventas vtas
       INNER JOIN vtasfact vf ON vtas.id=vf.idVenta AND vtas.idCorte='$idCorte'
       INNER JOIN facturasgeneradas fg ON fg.id =vf.idFactgen
       INNER JOIN facturas fact ON fact.uuid=fg.uuidFact
       INNER JOIN sat_formapago st_fp ON st_fp.clave=fact.formaPago
   
   ) totalesFact ON totalesFact.idFormaPago=totalesVentas.idFormaPago
   INNER JOIN sat_formaPago st_fp ON st_fp.id=totalesVentas.idFormaPago
   GROUP BY totalesVentas.idFormaPago";

/*cte.estatus='2' */
        $res = mysqli_query($link, $sql) or die(errorBD('<option value="">Error de Consulta de Datos de Corte </option>' ));
        $arreglo['data'] = array();
        while ($datos = mysqli_fetch_array($res)) {
            $arreglo['data'][] = $datos;
        }
        $var = json_encode($arreglo);
        mysqli_free_result($res);
        echo $var;
      
        function errorBD($error)
        {
          echo $error;
          exit(0);
        }
?>