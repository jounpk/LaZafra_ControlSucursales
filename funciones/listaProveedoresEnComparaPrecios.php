<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$idprvs = (isset($_POST['listaProv']) && $_POST['listaProv'] != '') ? $_POST['listaProv'] : '' ;
$idMks = (isset($_POST['marcas']) && $_POST['marcas'] != '') ? $_POST['marcas'] : '' ;
$arregloProv = explode(",",$idprvs);
#echo '<br>$_POST: ';
#print_r($_POST);
#echo '<br>$idprvs: '.$idprvs;
#echo '<br>$idMks: '.$idMks;
if ($idMks == '') {
    echo '<option value="">Selecciona Primero una Marca</option>';
} else {

    $sqlProv = "SELECT DISTINCT(pv.id) AS idProveedor, pv.nombre
                FROM catmarcas cm
                INNER JOIN productos p ON cm.id = p.idCatMarca
                INNER JOIN detcompras dc ON p.id = dc.idProducto
                INNER JOIN compras c ON dc.idCompra = c.id
                LEFT JOIN proveedores pv ON c.idProveedor = pv.id
                WHERE cm.id IN($idMks) AND c.estatus = '2'
                ORDER BY pv.nombre";
    #echo $sqlProv;
    $resProv = mysqli_query($link,$sqlProv) or die('Problemas al consultar los Proveedores, notifica a tu Administrador.');
    #var_dump($arregloProv);
#/*
    while ($pr = mysqli_fetch_array($resProv)) {
      if ($pr['nombre'] != '') {
        $act2 = (in_array($pr['idProveedor'], $arregloProv)) ? 'selected' : '' ;
        echo '<option value="'.$pr['idProveedor'].'" id="idPrv-'.$pr['idProveedor'].'" '.$act2.'>'.$pr['nombre'].'</option>';
      }
    }
  }
#    */
 ?>
