<?php
session_start();
$_SESSION['LZFident'] = 'DlpGLtFCQD6rjvlikE3oGo9';
$_SESSION['LZFidNivel'] = 'LZU34ZxoR9+9O2wZIPFRJVL';
$_SESSION['LZFidSuc'] = 'RJVLIYui5sivbuUAT9N8V7Y';

define('INCLUDE_CHECK',1);
require_once('include/connect.php');

$devBug=0;
if ($devBug != 1){
	error_reporting(0);
} else{
	error_reporting(1);
	echo 'Contenido de POST:</br>';
	print_r($_POST);
	echo '</br></br>';
}

if(isset($_POST['usuario']) and isset($_POST['password']) and $_POST['usuario']!="" and $_POST['password']!=""){
	$usuario = $_POST['usuario'];
	$passwd  = md5($_POST['password']);
	$sql="SELECT * FROM segusuarios WHERE usuario='$usuario' AND pass='$passwd'";
	//----------------devBug	COD: LG001 ------------------------------
	if ($devBug==1) {
		$res= mysqli_query($link,$sql) or die ("Error de consulta existeUsuario: ".mysqli_error($link).'<br>SQL: '.$sql);
		echo $sql.'<br>';
	} else {
		$res= mysqli_query($link,$sql) or die (returnBad('Intentalo de nuevo o Notifica a tu Administrador... <br>Error: <b>LGN001</b>'));
	}
	//-------------Finaliza devBug------------------------------

	$numRes=mysqli_num_rows($res);

	if($numRes== 1){
			$var=mysqli_fetch_array($res);
			if($var['estatus']!=1){
				returnBad('Este Usuario se encuentra <b>Deshabilitado</b>.');
			} else {
				$userId = $var['id'];
				$sql="SELECT usr.id, usr.nombre, usr.appat, usr.apmat, lvl.id AS 'idNivel', lvl.nombre AS 'nameNivel', ars.link AS 'arLink',
								mnu.link AS 'mnuLink', sbmn.link AS 'sbmnLink', scs.id AS 'idSuc', scs.estatus AS 'estatSuc',	scs.nombre AS 'nameSuc', mp.nameCto AS pyme
							FROM segusuarios usr
							INNER JOIN sucursales scs ON usr.idSucursal = scs.id
							INNER JOIN empresas mp ON scs.idEmpresa = mp.id
							INNER JOIN segniveles lvl ON usr.idNivel = lvl.id
							INNER JOIN segdetnivel dtlvl ON lvl.id = dtlvl.idNivel
							INNER JOIN segareas ars ON dtlvl.idArea =  ars.id AND ars.estatus = '1'
							INNER JOIN segmenus mnu ON dtlvl.idMenu = mnu.id AND mnu.estatus ='1'
							LEFT JOIN segsubmenus sbmn ON dtlvl.idSubMenu = sbmn.id AND sbmn.estatus = '1'
							WHERE usr.id = '$userId' AND usr.estatus = '1'
							ORDER BY ars.orden, mnu.orden, sbmn.orden ASC
							LIMIT 1";
				//----------------devBug------------------------------
				if ($devBug==1) {
					$res1= mysqli_query($link,$sql) or die ("Error de consulta Nivel de Usuario: ".mysqli_error($link).'<br>SQL: '.$sql);
					echo $sql.'<br>';
				} else {
					$res1= mysqli_query($link,$sql) or die (returnBad('Intentalo de nuevo o Notifica a tu Administrador... <br>Error: <b>LGN002</b>'));
				}
				//-------------Finaliza devBug------------------------------
				$cantPermisos = mysqli_num_rows($res1);

				unset($_SESSION['LZFident']);
				unset($_SESSION['LZFidNivel']);
				unset($_SESSION['LZFidSuc']);

				if($cantPermisos < 1)	{
					returnBad('Su usuario no tiene Permisos Asignados.');

				} else {
					$dat = mysqli_fetch_array($res1);
					if($dat['estatSuc'] != 1)	{
						returnBad('Su Sucursal ya no esta Activa.');
					}

					$_SESSION['LZFident'] = $dat['id'];
					$_SESSION['LZFidNivel'] = $dat['idNivel'];
					$_SESSION['LZFnombreNivel'] = $dat['nameNivel'];
					$_SESSION['LZFnombreUser'] = trim($dat['nombre']).' '.trim($dat['appat']).' '.trim($dat['apmat']);
					$_SESSION['LZFnombreUserCto'] = trim($dat['nombre']).' '.trim($dat['appat']);
					$_SESSION['LZFidSuc'] = $dat['idSuc'];
					$_SESSION['LZFnombreSuc'] = $dat['nameSuc'];
					$_SESSION['LZFpyme'] = $dat['pyme'];

					$link = '';
					if (strlen($dat['arLink']) > 4 ) {
						$link = $dat['arLink'];
					}
		/*			if (strlen($dat['mnuLink']) > 4 ) {
						$link = $dat['mnuLink'];
					}
					if (strlen($dat['sbmnLink']) > 4 ) {
						$link = $dat['sbmnLink'];
					}
					*/
					if ($devBug == 1) {
						echo '<br><br> Link de SubMenu: '.$dat['sbmnLink'];
						echo '<br> Link de Menu: '.$dat['mnuLink'];
						echo '<br> Link de Area: '.$dat['arLink'];
					}

					echo '<br><br>Contenido de SESSION: ';
			    var_dump($_SESSION);
					echo '</br></br>';

					if ($link == '') {
						if ($devBug == 1) {
							echo 'El link esta Vacio.';
						} else {
							returnBad('Intentalo de nuevo o Notifica a tu Administrador... <br>Error: <b>LGN003</b>');
						}
					}

					if ($devBug == 1) {
						echo '<br><br> Link Final: '.$link;
					} else {
						header('location: '.$link);
					}
					// ----------------   AQUI FINALIZA EL PROCESO   --------------------------------
				}
			}
		} else {
			returnBad('El Usuario o el Password que ingreso son incorrectos, por favor intente de nuevo.');
		}
} else{
	returnBad('Debes llenar todos los campos.');
}

function returnBad($msj){
	if ($GLOBALS['devBug'] == 1) {
		echo '<br><hr><br>MSJ ERROR: '.$msj.'<br><br><hr><br>';
	} else {
		$_SESSION['LZFacceso']=$msj;
		header('location: index.php');
	}
	exit(0);
}
?>
