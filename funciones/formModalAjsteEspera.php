<?php
//print_r($_POST);
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];

$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$color=(isset($_POST['color']) AND $_POST['color'] != '') ? $_POST['color'] : '' ;
$sqlAlerta="
SELECT
ajst.id AS folio,
ajst.descripcion, 
CASE ajst.estatus
WHEN '2' THEN
    \"<div class='alert alert-primary' role='alert'>El Ajuste se encuentra en espera de Autorización. Intenta más 	tarde.</div>\"
WHEN '3' THEN
    \"<div class='alert alert-success' role='alert'>El Ajuste ha sido Autorizado, puedes realizar los cambios.</div>\"
WHEN '4' THEN
    \"<div class='alert alert-danger' role='alert'>El Ajuste ha sido Cancelado, notifica que has sido enterado.</div>\"
END AS estado,
ajst.estatus
FROM
ajustes ajst
WHERE ajst.id='$id'";
$res=mysqli_query($link,$sqlAlerta) or die('<tr><td colspan="5" aling="center">Error de Consulta.'.$sql.'</td></tr>');
$array_estatus=mysqli_fetch_array($res);
$alerta=$array_estatus["estado"];
$estado_solicitud=$array_estatus["estatus"];
$modal_completo="";
$descripcion=$array_estatus["descripcion"];
$cabecera_modal="
<div class=\"modal fade\" id=\"verestatusSolicitud\" role=\"dialog\" aria-labelledby=\"verPDFLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog \">
        <div class=\"modal-content\">
        <form id='form_ajustar' action='' method='post'>
            <div class=\"modal-header bg-$color\" style=\"color:#fff;\" id=\"verestatusHeader\">
                <h4 class=\"modal-title\" id=\"verestatusTitle\">Ajuste con Folio: $id</h4>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
            </div>";
$body_modal="<div class=\"modal-body\" id=\"verestatusBody\">";
$botones_modal="<div class=\"modal-footer\"><button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Salir</button>";
$detalles=
"<p><b>Folio de Ajuste: </b>$id</p><p><b>Descripción: </b>$descripcion</p>";

switch ($estado_solicitud) {
    //SOLICITUD CANCELADA
    case '4':
        $modal_completo.=$cabecera_modal.$body_modal.$detalles.$alerta."</div><div class=\"modal-footer\"><button type=\"button\" class=\"btn btn-success\" data-dismiss=\"modal\" onclick=\"notificarCancelacion($id);\">Notificado</button>";
    break;
    case '2':
    require_once('cargaAjstModal.php');
    $modal_completo.=$cabecera_modal.$body_modal.$detalles.$alerta.$estTabla.$botones_modal."</div></div>";
        break;
    case '3':
    require_once('cargaAjstModal.php');
    $boton_ajustar="<button type='button' onclick=\"ajustar();\" class=\"btn btn-success\">Ajustar</button>";

    $modal_completo.=$cabecera_modal.$body_modal.$detalles.$alerta.$estTabla.$botones_modal.$boton_ajustar."</div></form></div>";
    break;
}
echo $modal_completo;
?>
