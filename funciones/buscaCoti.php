<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$debug = 0;
$pyme = $_SESSION['LZFpyme'];
$idSucursal = $_SESSION['LZFidSuc'];
$folio = $_POST['folio'];
//----------------devBug------------------------------
if ($debug == 1) {
    print_r($_POST);
    echo '<br><br>';
} else {
    error_reporting(0);
}  //-------------Finaliza devBug------------------------------
?>
<?php
$sql = "SELECT
	ct.idSucursal,
	suc.nombre AS sucursales,
	ct.id AS idCotizacion ,
    ct.estatus,
    IF
	( ct.folio IS NULL, '********', ct.folio ) AS folio,
    IF
	( ct.tipo = 2, CONCAT( ct.nameCliente, ' <span><b>(Público en General)</b></span>' ), cl.nombre ) AS cliente,
	ct.nameCliente,
    IF
	( ct.montoTotal IS NULL, '$0.0', CONCAT( '$ ', FORMAT( ct.montoTotal, 2 )) ) AS montoTotal,
	CONCAT( usr.nombre, ' ', usr.appat, ' ', usr.apmat ) AS usuario,
	DATE_FORMAT( ct.fechaAut, '%d-%m-%Y %H:%i:%s' ) AS fecha,
    IF
	( DATE_ADD( ct.fechaAut, INTERVAL ct.cantPeriodo DAY )>= NOW() AND ct.estatus=3, ' <h1 class=\"text-success\">Activa</h1>', '<h1 class=\"text-danger\">Expirada</h1>' ) AS etiquetitaText,
    IF
	( DATE_ADD( ct.fechaAut, INTERVAL ct.cantPeriodo DAY )>= NOW(), '1', '2' ) AS Vigencia,
    DATE_FORMAT(DATE_ADD( ct.fechaAut, INTERVAL ct.cantPeriodo DAY ), '%d-%m-%Y') AS fechaExp,

	ct.estatus 
    FROM
	cotizaciones ct
	LEFT JOIN clientes cl ON ct.idCliente = cl.id
	INNER JOIN sucursales suc ON ct.idSucursal = suc.id
	INNER JOIN segusuarios usr ON ct.idUserReg = usr.id 
    WHERE  ct.idSucursal='$idSucursal' AND
	folio ='$folio'";
//----------------devBug------------------------------
if ($debug == 1) {
    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador.');
    echo '<br>Listado de Ventas producto: ' .    $sql  . '<br>';
} else {
    $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador.');
} //-------------Finaliza devBug------------------------------
if ($debug == '1') {
    echo "NUMERO DE COTIZACIONES" . mysqli_num_rows($resXQuery);
}
if (mysqli_num_rows($resXQuery) >= 1) {

    while ($datos = mysqli_fetch_array($resXQuery)) {
        $clientes = $datos['cliente'];
        $fechaExp = $datos['fechaExp'];
        $etiquetitaText = $datos['etiquetitaText'];
        $idCotizacion = $datos['idCotizacion'];
        $Vigencia = $datos['Vigencia'];
        $estatus = $datos['estatus'];
    }
?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <b>Cliente: <?= $clientes ?></b>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Fecha de Expiración: <?= $fechaExp ?></h4>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="text-danger">
                                <h1><?= $etiquetitaText ?></h1>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a  class="btn btn-danger" href="imprimePdfCotizacion.php?idCotizacion=<?=$idCotizacion?>" target="_blank">Ver PDF</a>
                            <a  href="funciones/imprimeTicketCotizacion.php?idCotizacion=<?=$idCotizacion?>" target="_blank" class="btn btn-success">Ver Ticket</a>
                        </div>
                    </div>
                    <br>
                    <table class="table product-overview" id="zero_config">
                        <thead>
                            <tr>
                                <th class="text-center">Cantidad</th>
                                <th>Producto</th>
                                <th>Cant Actual</th>
                                <th>Tipo de Precio</th>
                                <th>Precio</th>
                                <th>Subtotal</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT dc.*, pr.descripcion, IF(dc.asignaPrecio='1','P. Base','Pers.') AS tipoPago,
                                FORMAT(dc.precioCoti,2) AS precio, dc.subtotal,
                                stk.cantActual

                                FROM cotizaciones c
                                INNER JOIN detcotizaciones dc ON c.id=dc.idCotizacion
                                INNER JOIN productos pr ON dc.idProducto=pr.id
                                INNER JOIN stocks stk ON pr.id=stk.idProducto AND stk.idSucursal='$idSucursal'

                                WHERE c.id='$idCotizacion' AND c.idSucursal='$idSucursal'";
                            //----------------devBug------------------------------
                            if ($debug == 1) {
                                $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador.');
                                echo '<br>Listado de Ventas producto: ' .    $sql  . '<br>';
                            } else {
                                $resXQuery = mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador.');
                            } //-------------Finaliza devBug------------------------------
                            while ($datos = mysqli_fetch_array($resXQuery)) {
                                $colorsito=$datos['cantActual']<$datos['cantidad'] ?'table-danger': '';
                                echo '<tr class="'.$colorsito.'"><td>' . $datos['cantidad'] . '</td>';
                                echo '<td>' . $datos['descripcion'] . '</td>';
                                echo '<td>' . $datos['cantActual'] . '</td>';
                                echo '<td>' . $datos['tipoPago'] . '</td>';
                                echo '<td>$ ' . $datos['precio'] . '</td>';
                                echo '<td>$ ' . $datos['subtotal'] . '</td></tr>';
                            }


                            ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <?php
        if ($Vigencia=="1" AND $estatus=='3'){
            echo '<button type="button" class="btn btn-info" onclick="cargaCotizacion('.$idCotizacion.');"> Ocupar</button>';
        }
        ?>
    </div>

<?php
} else {
    echo "<div class='text-danger'>No hay datos de la Cotización</div>";
}
?>