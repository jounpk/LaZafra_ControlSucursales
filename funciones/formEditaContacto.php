<?php
session_start();

define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$idContacto = (isset($_POST['idContacto']) and $_POST['idContacto'] >= 1) ? $_POST['idContacto'] : '';
$sql = "SELECT * FROM contactosprov WHERE id = '$idContacto'";
//echo $sql;
$result = mysqli_query($link, $sql) or die('<span class="text-danger"><center>Problemas al consultar los Datos. Notifica a tu Administrador.</center></span>' . $sql);
$dat = mysqli_fetch_array($result);
$id = $dat['id'];
$nombre = $dat['nombre'];
$telefono = $dat['telefono'];
$correoSucursal = $dat['correoSucursal'];

?>
<form role="form" method="post" action="../funciones/editaContacto.php">
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editNombre1"><i class="mdi mdi-account"></i></span>
	      </div>
	      <input type="text" class="form-control" id="editNombre" placeholder="Ingresa el Nombre" aria-describedby="nombre" name="editNombre" oninput="limpiaCadena(this.value,'editNombre');" value="<?=$nombre;?>" required>
	  </div>
	  <br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editTelefono"><i class="mdi mdi-phone-plus"></i></span>
	      </div>
	      <input type="text" class="form-control phone-inputmask" id="editTelefono" placeholder="telefono" aria-describedby="telefono"   value="<?=$telefono;?>" name="editTelefono" required>
	  </div>
	  <br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editCorreoSucursal"><i class="mdi mdi-email-outline"></i></span>
	      </div>
	      <input type="text" class="form-control " id="editCorreoSucursal" placeholder="Ingresa correo" aria-describedby="correo" value="<?=$correoSucursal;?>" name="editCorreoSucursal" required>
	  </div>
		<br>
	  
		
    <div class="modal-footer">
        <input type="hidden" name="ident" id="ident" value="<?=$idContacto;?>">
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Editar</button>
    </div>
	</form>
	<script src="../dist/js/pages/forms/mask/mask.init.js"></script>
