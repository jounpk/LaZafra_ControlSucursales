<?php
session_start();

define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$idProv = (isset($_POST['idProv']) and $_POST['idProv'] >= 1) ? $_POST['idProv'] : '';
$sql = "SELECT * FROM proveedores WHERE id = '$idProv'";
//echo $sql;
$result = mysqli_query($link, $sql) or die('<span class="text-danger"><center>Problemas al consultar los Datos. Notifica a tu Administrador.</center></span>' . $sql);
$dat = mysqli_fetch_array($result);
$idEmp = $dat['idEmpresa'];
$prov = $dat['nombre'];
$rfc = $dat['rfc'];
$tel = $dat['telEmpresa'];
$correo = $dat['correoPagos'];
$credito = $dat['credito'];
$limiteCred = number_format($dat['limiteCredito'],2,'.',',');
$cantLimite = $dat['cantidadLimite'];
$tipoLimite = $dat['tipoLimite'];
$stylo = ($credito != 1) ? 'style="display: none;"' : '' ;
$sel = ($credito == 1) ? 'selected' : '' ;
$s = $s1 = $s2 = $s3 = $s4 = '';
switch ($tipoLimite) {
	case '1':   $s1 = 'selected';   break;
	case '2':   $s2 = 'selected';   break;
	case '3':   $s3 = 'selected';   break;
	case '4':   $s4 = 'selected';   break;

	default:    $s  = 'selected';   break;
}
?>
<form role="form" method="post" action="../funciones/editaProveedor.php">
		<div class="input-group">
				<div class="input-group-prepend">
						<span class="input-group-text" id="idEmpresa1"><i class="far fa-building"></i></span>
				</div>
				<select class="form-control" id="idEmpresa" name="idEmpresa" required>
					<option value="">Selecciona una Empresa</option>
					<?php
						$sqlEmp = "SELECT * FROM empresas WHERE estatus = '1'";
						$resEmp = mysqli_query($link,$sqlEmp) or die('Problemas al consultar la empresas, notifica a tu Administrador.');
						while ($e = mysqli_fetch_array($resEmp)) {
							$activo = ($idEmp == $e['id']) ? 'selected' : '' ;
							echo '<option value="'.$e['id'].'" '.$activo.'>'.$e['nombre'].'</option>';
						}
					 ?>
				</select>
		</div>
		<br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editNombre1"><i class="mdi mdi-account"></i></span>
	      </div>
	      <input type="text" class="form-control" id="editNombre" placeholder="Ingresa el Nombre" aria-describedby="nombre" name="editNombre" oninput="limpiaCadena(this.value,'editNombre');" value="<?=$prov;?>" required>
	  </div>
	  <br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editRFC1"><i class="mdi mdi-account-key"></i></span>
	      </div>
	      <input type="text" class="form-control" id="editRFC" placeholder="Ingresa su RFC" aria-describedby="rfc" min="12" maxlength="13" onkeyup="cambiaMayusculas(this.value,'editRFC');" value="<?=$rfc;?>" name="editRFC" required>
	  </div>
	  <br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editTelefono1"><i class="mdi mdi-phone-plus"></i></span>
	      </div>
	      <input type="text" class="form-control phone-inputmask" id="editTelefono" placeholder="Ingresa su teléfono" aria-describedby="Telefono" value="<?=$tel;?>" name="editTelefono" required>
	  </div>
		<br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editCorreo1"><i class="mdi mdi-email-outline"></i></span>
	      </div>
	      <input type="email" class="form-control email-inputmask" placeholder="Ingresa el Correo de la Empresa" id="editCorreo" aria-describedby="Correo" value="<?=$correo;?>" name="editCorreo">
	  </div>
		<br>
	  <div class="input-group">
	      <div class="input-group-prepend">
	          <span class="input-group-text" id="editCorreo1">CR</span>
	      </div>
	      <select class="form-control" name="edCredito" id ="edCredito" onchange="visualizaCampos(this.value,2);">
					<option value="0">No brinda Crédito</option>
					<option value="1" <?=$sel;?>>Si brinda Crédito</option>
				</select>
	  </div>
		<br>
		<div id="divCreditos2" <?=$stylo;?>>
		<div class="input-group">
				<div class="input-group-prepend">
						<span class="input-group-text" id="editCorreo1"><i class="far fa-calendar-plus"></i></span>
				</div>
				<select class="form-control campoCredito2" name="tipoLimite" id ="tipoLimite">
					<option value="" <?=$s;?>>Selecciona el periodo</option>
					<option value="1" <?=$s1;?>>Días</option>
					<option value="2" <?=$s2;?>>Semanas</option>
					<option value="3" <?=$s3;?>>Meses</option>
					<option value="4" <?=$s4;?>>Años</option>
				</select>
		</div>
		<br>
			<div class="input-group">
		      <div class="input-group-prepend">
		          <span class="input-group-text" id="editCorreo1"><i class="fas fa-clock"></i></span>
		      </div>
		      <input type="number" min="1" max="99" class="form-control campoCredito2" value="<?=$cantLimite;?>" id="cantLimite" name="cantLimite" placeholder="Ingresa cuántos días, semanas, meses o años">
		  </div>
			<br>
			<div class="input-group">
		      <div class="input-group-prepend">
		          <span class="input-group-text" id="editCorreo1"><i class="far fa-money-bill-alt"></i></span>
		      </div>
		      <input type="text" class="form-control campoCredito2" value="<?=$limiteCred;?>" onkeypress="mascaraMonto(this,Monto)" id="limiteCred" name="limiteCred" placeholder="Ingresa el monto de crédito que brinda">
		  </div>
		</div>
		<br>
    <div class="modal-footer">
        <input type="hidden" name="ident" id="ident" value="<?=$idProv;?>">
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Editar</button>
    </div>
	</form>
	<script src="../dist/js/pages/forms/mask/mask.init.js"></script>
