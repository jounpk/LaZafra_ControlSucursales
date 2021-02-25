<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident = (isset($_POST['idBnk'])) ? $_POST['idBnk'] : '' ;
$mensaje = '';


$sql = "SELECT e.nombre, cb.id, bnk.nombreCorto,cb.noCuenta,cb.claBe
FROM cuentasbancarias cb
INNER JOIN catbancos bnk ON cb.idClaveBanco = bnk.id
INNER JOIN empresas e ON cb.idEmpresa = e.id
WHERE cb.estatus = 1 AND bnk.id = '$ident'
";
$consulta = mysqli_query($link,$sql) or die('0|Problemas al consultar las cuentas'.mysqli_error($link));
$cantidad = mysqli_num_rows($consulta);

if ($cantidad >= 1) {
  $no = 1;
  $mensaje = '<select name="cuentabanco[]" id="cuentabanco" class="form-control" required>';
  $mensaje .= '<option value="">Selecciona una Cuenta</option>';
while ($fila = mysqli_fetch_array($consulta)) {
  $check = ($no == 1) ? 'checked' : '' ;
  $mensaje .=	'<option value="'.$fila['id'].'">Cuenta: '.$fila['noCuenta'].' ('.$fila['nombre'].')</option>';
  $no++;
  }
  $mensaje .= '</select>';
  echo '1|'.$mensaje;
} else {
  echo '0|'.$mensaje;
}

 ?>
