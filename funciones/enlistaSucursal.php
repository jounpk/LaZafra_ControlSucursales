<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$ident = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$sqlSucursal = "SELECT
suc.nombre,
stk.id 
FROM
sucursales suc
INNER JOIN stocks stk ON stk.idSucursal = suc.id
LEFT JOIN excepcionesprecio pr_exp ON pr_exp.idStock = stk.id 
WHERE
stk.idProducto =".$ident;
        $resSucursal = mysqli_query($link,$sqlSucursal) or die('Problemas al consultar las Sucursales, notifica a tu Administrador');
        $listaSuc="";
        if(mysqli_num_rows($resSucursal)==0){
            echo '<option value="">No hay Sucursales con Stock</option>';
        }
        while ($suc = mysqli_fetch_array($resSucursal)) {
            $listaSuc.='<option value="'.$suc['id'].'">'.$suc['nombre'].'</option>';
        }




echo $listaSuc;
