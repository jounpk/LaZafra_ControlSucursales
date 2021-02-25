<?php
session_start();
define('INCLUDE_CHECK',1);
$devBug=0;
if ($devBug != 1){
	error_reporting(0);
} else{
		echo 'Contenido de POST: ';
		var_dump($_POST);
    echo '<br>Contenido de SESSION: ';
    var_dump($_SESSION);
		echo '</br></br>';
}

class Seguridad
{
    public $pagina;
		public $nombrePag;
    public $detailPag;
    public $ident;
    public $idNivel;
    public $nombreNivel;
		public $nombreUser;
    public $nombreUserCto;
		public $idDetNivel;
		public $idMenu;
		public $idSubMenu;
		public $idSucursal;
		public $nombreSuc;
		public $area;
    public $linkArea;
		public $subArea;
    public $iconColor;

    #-----------------------  SEGURIDAD  ------------------------------
    public function Acceso($pag)
    {
    	//Validamos que existan las variables
      if(isset($_SESSION['LZFident']) AND $_SESSION['LZFident'] >= 1 AND isset($_SESSION['LZFidNivel']) AND $_SESSION['LZFidNivel'] AND isset($_SESSION['LZFnombreNivel']) AND isset($_SESSION['LZFnombreUser'])>= 1 ){
				require('include/connect.php');

        $this->ident=$_SESSION['LZFident'];
        $this->idNivel=$_SESSION['LZFidNivel'];
        $this->nombreNivel=$_SESSION['LZFnombreNivel'];
        $this->pagina=$pag;
				$this->nombreUser=$_SESSION['LZFnombreUser'];
        $this->nombreUserCto=$_SESSION['LZFnombreUserCto'];
        $idLevel = $_SESSION['LZFidNivel'];
				$idSuc = $_SESSION['LZFidSuc'];
				$ident = $_SESSION['LZFident'];

      } else{
        problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG001</b>');
      }

			$sql = "SELECT ars.link, ars.error AS linkError, IFNULL(sbmns.nombre,mns.nombre) AS nameFile, IFNULL(sbmns.descripcion,mns.descripcion) AS descFile, IFNULL(sbmns.estatus,mns.estatus) AS estatusFile,
										ars.id AS idArea, mns.id AS idMenu, sbmns.id AS idSubMenu
							FROM segareas ars
							INNER JOIN segmenus mns ON ars.id = mns.idArea
							LEFT JOIN segsubmenus sbmns ON mns.id = sbmns.idSegMenu
							WHERE  (mns.link ='$pag' OR sbmns.link = '$pag')
							ORDER BY ars.orden, mns.orden, sbmns.orden ASC
							LIMIT 1";
			//----------------devBug------------------------------
			if ($GLOBALS['devBug'] == 1) {
				$respLnk = mysqli_query($link,$sql) or die ("<br><br>Error al consultar el Archivo en los Registros: ".mysqli_error($link).'<br>SQL: '.$sql);
				echo 'Validación de archivo en Registros: '.$sql.'<br>';
			} else {
				$respLnk = mysqli_query($link,$sql) or die (problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG002</b>'));
			}
			//-------------Finaliza devBug------------------------------
			$cantLnk = mysqli_num_rows($respLnk);

			if ($cantLnk == 1) {
				$lnk = mysqli_fetch_array($respLnk);
				$lnkError = $lnk['linkError'];
				$lnkNameFi = $lnk['nameFile'];
				$lnkDescFi = $lnk['descFile'];
				$lnkStatFi = $lnk['estatusFile'];
				$idAr = $lnk['idArea'];
				$idMn = $lnk['idMenu'];
				$idSm = $lnk['idSubMenu'];

				$this->nombrePag = $lnkNameFi;
				$this->detailPag = $lnkDescFi;
				$this->linkArea = $lnk['link'];
				$this->idMenu = $lnk['idMenu'];
				$this->idSubMenu = $lnk['idSubMenu'];
				$this->area = $idAr;
				$this->subArea = $idMn;
				$_SESSION['LZFarea'] = $idAr;
				if ($lnkStatFi != '1') {
					negado($lnkError, 'La liga a la que deceas Acceder esta Deshabilitada.');
				}
				$busq = ($idSm == '' OR $idSm == NULL) ? "dtlvl.idSubMenu IS NULL OR dtlvl.idSubMenu = ''" : "dtlvl.idSubMenu = '$idSm'" ;

				$sql = "SELECT CONCAT(usr.nombre, ' ', usr.appat, ' ', usr.apmat) AS fullName, dtlvl.id AS idDetLevel, scs.nombre AS nameSuc
								FROM segusuarios usr
								INNER JOIN sucursales scs ON usr.idSucursal = scs.id AND scs.estatus = '1'
								INNER JOIN segniveles lvl ON usr.idNivel = lvl.id ANd lvl.estatus = '1'
								INNER JOIN segdetnivel dtlvl ON usr.idNivel = dtlvl.idNivel
								WHERE usr.id = '$ident' AND usr.idNivel = '$idLevel' AND usr.idSucursal = '$idSuc' AND usr.estatus = '1'
									AND dtlvl.idArea = '$idAr' AND dtlvl.idMenu = '$idMn' AND ($busq)";
				//----------------devBug------------------------------
				if ($GLOBALS['devBug'] == 1) {
					$authLnk = mysqli_query($link,$sql) or die ("<br><br>Error al validar el Usuario con el Detalle del Nivel: ".mysqli_error($link).'<br>SQL: '.$sql);
					echo 'Validación de Usuario con su Detalle de Nivel: '.$sql.'<br>';
				} else {
					$authLnk = mysqli_query($link,$sql) or die (problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG004</b>'));
				}
				//-------------Finaliza devBug------------------------------
				$cantAuth = mysqli_num_rows($authLnk);

				if ($cantAuth == 1) {
					$datAuth = mysqli_fetch_array($authLnk);
					$this->idDetNivel = $datAuth['idDetLevel'];
					$this->idSucursal = $idSuc;
					$this->nombreSuc = $datAuth['nameSuc'];

					//----------------devBug------------------------------
					if ($GLOBALS['devBug'] == 1) {
						echo '<br><br><hr><br>Se ha validado Correctamente al Usuario: <b>'.$datAuth['fullName'].'</b> y se le identificó el idDetNivel en <b>'.$datAuth['idDetLevel'].'</b>.<br><hr><br><br>';
					}//-------------Finaliza devBug------------------------------

				} elseif ($cantAuth > 1) {
					problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG005</b>');
				}	else {
					negado($lnkError, 'No tienes Acceso al link que intentaste ingresar.');
				}

			} else {
				problemas('El area donde deseas ingresar tiene problemas. Notifica a tu Administrador... <br>Error: <b>SEG003</b>');
			}
    }  #--FIN SEGURIDAD--

		#-----------------------  CUSTOMIZACIÓN  ------------------------------
    public function customizer()
    {
			$devBug=1;
			require('include/connect.php');

      $idUser = $this->ident;
			$idSuc = $this->idSucursal;

			$sql = "SELECT mps.iconoColor, mps.iconoWhite, mps.color
							FROM segusuarios usr
							INNER JOIN sucursales scs ON usr.idSucursal = scs.id AND scs.estatus = '1'
							INNER JOIN empresas mps ON scs.idEmpresa = mps.id
							WHERE usr.id = '$idUser' AND usr.idSucursal = '$idSuc'";
			//----------------devBug------------------------------
			if ($GLOBALS['devBug'] == 1) {
				$pym = mysqli_query($link,$sql) or die ("<br><br>Error al identificar la Empresa y Sucursal del Usuario: ".mysqli_error($link).'<br>SQL: '.$sql);
				echo 'Validación de la Empresa y Sucursal del Usuario: '.$sql.'<br>';
			} else {
				$pym = mysqli_query($link,$sql) or die (problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG006</b>'));
			}
			//-------------Finaliza devBug------------------------------
			$cantPym = mysqli_num_rows($pym);

			if ($cantPym == 1) {
				$linkLog = ($this->linkArea == 'home.php' ) ? 'home.php' : 'index.php' ;
				$pyme = mysqli_fetch_array($pym);
				$iconColor = $pyme['iconoColor'];
				$iconWhite = $pyme['iconoWhite'];
				$this->iconColor = $pyme['color'];

				return '
					<div class="navbar-header">
							<!-- This is for the sidebar toggle which is visible on mobile only -->
							<a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
									<i class="ti-menu ti-close"></i>
							</a>
							<!-- Logo -->
								<div class="navbar-brand">
										<a href="'.$linkLog.'" class="logo">
												<span class="logo-text">
														<img src="'.$iconColor.'" alt="Inicio" class="dark-logo" />
														<img src="'.$iconWhite.'" alt="Inicio" class="light-logo" />
												</span>
										</a>
										<a class="sidebartoggler d-none d-md-block" href="javascript:void(0)" data-sidebartype="mini-sidebar">
												<i class="mdi mdi-toggle-switch mdi-toggle-switch-off font-20"></i>
										</a>
								</div>
							<!-- End Logo -->

							<!-- Toggle which is visible on mobile only -->
							<a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent"
									aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
									<i class="ti-more"></i>
							</a>
					</div>
				';
			} else {
				problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG007</b>');
			}

    }  #--FIN SEGURIDAD--

    #-----------------------  MENU DE AREAS DISPONIBLES  ------------------------------
    public function generaMenuUsuario()
    {
			$devBug=1;
			require('include/connect.php');

      $level=$this->idNivel;
      $area =$this->area;
      $sql="SELECT
              DISTINCT(dtnvl.idArea) AS identArea, ars.*
          FROM segdetnivel dtnvl
          INNER JOIN segareas ars ON dtnvl.idArea = ars.id
          WHERE
          dtnvl.idNivel='$level'
          ORDER BY ars.orden DESC";
			//----------------devBug------------------------------
			if ($GLOBALS['devBug'] == 1) {
				$ars = mysqli_query($link,$sql) or die ("<br><br>Error al Consultar las Áreas a las que puede Acceder el Usuario: ".mysqli_error($link).'<br>SQL: '.$sql);
				echo 'Validación de la Empresa y Sucursal del Usuario: '.$sql.'<br>';
			} else {
				$ars = mysqli_query($link,$sql) or die (problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG008</b>'));
			}
			//-------------Finaliza devBug------------------------------
			$cantArs = mysqli_num_rows($ars);
			if ($cantArs >= 1) {
				$arsLinks = '';
				while ($dat=mysqli_fetch_array($ars)){
	          $estatus = ($dat['identArea'] == $area) ? 'active' : '' ;
	          $arsLinks .= '
									<a class="dropdown-item '.$estatus.'" href="'.$dat['link'].'">
										<i class="'.$dat['icono'].' m-r-5 m-l-5"></i> '.$dat['nombre'].'</a>';
	      }

				//----   Perfil no Funcional -----
				$perfil = '
				<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="javascript:void(0)">
						<i class="ti-user m-r-5 m-l-5"></i> Mi Perfil</a>';
				$perfil = '';

				return '
				<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img src="assets/images/users/2.jpg" alt="user" class="rounded-circle" width="40">
								<span class="m-l-5 font-medium d-none d-sm-inline-block">'.$this->nombreUser.' <i class="mdi mdi-chevron-down"></i></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
								<span class="with-arrow">
										<span class="bg-primary"></span>
								</span>
								<div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
										<div class="">
												<img src="assets/images/users/2.jpg" alt="user" class="rounded-circle" width="60">
										</div>
										<div class="m-l-10">
												<h4 class="m-b-0">'.$this->nombreUserCto.' </h4>
												<p class=" m-b-0">'.$this->nombreNivel.' </p>
										</div>
								</div>
								<div class="profile-dis scrollable">
										'.$arsLinks.'
										'.$perfil.'
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="logout.php">
												<i class="fa fa-power-off m-r-5 m-l-5"></i>Cerrar Sesi&oacute;n</a>
								</div>
						</div>
				</li>';
			} else {
				problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG007</b>');
			}


			//========================================
      echo '<li class="dropdown-header">Secciones</li>';
      while ($dat=mysqli_fetch_array($res)){
          $estatus = ($dat['id'] == $area) ? 'class="active"' : '' ;
					$atras = '';
          echo '<li '.$estatus.'><a href="'.$atras.$dat['link'].'"><i class="'.$dat['icono'].'"></i> '.$dat['nombre'].'</a></li>';
      }
      echo '<li class="divider"></li>
			<li><a href="logout.php"><i class="fa fa-fw fa-power-off text-danger"></i> Cerrar Sesi&oacute;n</a></li>';
			//=========================================

    }

    #-----------------------  MENU LATERAL  ------------------------------
    public function generaMenuLateral()
    {
      require('include/connect.php');

      $pagina =$this->pagina;
      $level=$this->idNivel;
			$area =$this->area;
			$idMenu =$this->idMenu;
      $idSubMenu =$this->idSubMenu;

      $sql="SELECT dtnvl.idMenu, dtnvl.idSubMenu,
							mn.nombre AS menu, mn.descripcion AS menuDesc, mn.icono AS menuIco, mn.link AS menuLink,
							sbmn.nombre AS sbmn, sbmn.descripcion AS sbmnDesc, sbmn.icono AS subMenuIco, sbmn.link AS sbmnLink

            FROM
                segdetnivel dtnvl
						INNER JOIN segmenus mn ON dtnvl.idMenu = mn.id AND mn.estatus = 1
						LEFT JOIN segsubmenus sbmn ON dtnvl.idSubMenu = sbmn.id AND sbmn.estatus = 1
            WHERE
                dtnvl.idNivel = '$level' AND dtnvl.idArea='$area'
            ORDER BY mn.orden, sbmn.orden ASC";
						//echo 'Menu de Usuario: '.$sql.'<br>';
      //----------------devBug------------------------------
      if ($GLOBALS['devBug'] == 1) {
	      $res= mysqli_query($link,$sql) or die ("Error de consultar Menu Lateral: ".mysqli_error($link).'<br>SQL: '.$sql);
	      echo 'Menu de Usuario: '.$sql.'<br>';
      } else {
      	$res= mysqli_query($link,$sql) or die (problemas('Se ha Bloqueado por Seguridad, por favor inténtalo de nuevo o notifica a tu Administrador... <br>Error: <b>SEG010</b>'));
      }
      //-------------Finaliza devBug------------------------------

			$nivel1 = 0;
			$abierto1 = 0;
			$menus = '';
			$nomMenu = '';
			$nomSubMenu = '';
			$menuAnt = 0;
			$menuAct = 0;

			while ($dat=mysqli_fetch_array($res)){
				$mnAct = ($idMenu == $dat['idMenu']) ? 'selected' : '' ;
				$mnA = ($idMenu == $dat['idMenu']) ? 'active' : '' ;
				$sbMnAct = ($idSubMenu == $dat['idSubMenu']) ? 'selected' : '' ;
				$sbMnA = ($idSubMenu == $dat['idSubMenu']) ? 'active' : '' ;

				$menuAct = $dat['idMenu'];
				$nomMenu = $dat['menu'];
				$nomSubMenu = $dat['sbmn'];


				$direccion = $dat['menuLink'];
				$iconMenu = $dat['menuIco'];

				$direccion2 = $dat['sbmnLink'];
				$iconMenu2 = $dat['subMenuIco'];

			if ($nomMenu != '') {
					if($abierto1 == 1 && $menuAct != $menuAnt) {	// evalua si esta abierta la etiqueta <ul> para cerrarla y haya otro catálogo contiguo
						$menus .= '</ul>';			// Cierra la lista
						$abierto1 = 0;				// iguala a 0 la variable, si hay otra lista despues vuelve a comenzar el ciclo del listado
					}
				if ($dat['idSubMenu'] >= 1 && $abierto1 == 0) {		// Se evalua que comienece el submenu y que no haya sido abierto uno antes a traves del $abierto1
							$menus .= '<li class="sidebar-item" '.$mnAct.'>
													<a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false" '.$mnA.'>
														<i class="'.$iconMenu.'" style="color: #'.$this->iconColor.';"></i>
														<span class="hide-menu"> '.$nomMenu.'</span>
													</a>
													<ul aria-expanded="false" class="collapse first-level">
														<li class="sidebar-item" '.$sbMnAct.'>
															<a class="sidebar-link waves-effect waves-dark sidebar-link" href="'.$direccion2.'" aria-expanded="false" '.$sbMnA.'>
																<i class="mdi mdi-menu-right"></i><i class="'.$iconMenu2.'" style="color: #'.$this->iconColor.';"></i>
																<span class="hide-menu"> '.$nomSubMenu.'</span>
															</a>
														</li>';
													$abierto1 = 1;		//Se abre la lista del submenu e informa que ya hay una sublista abierta para la siguiente iteracion
												} elseif ($dat['idSubMenu'] >= 1 && $abierto1 == 1) {		// se evalua sí ya hay una sublista abierta e ingresa otro elemento a esa sublista

														$menus .=	'<li class="sidebar-item" '.$sbMnAct.'>
																					<a class="sidebar-link waves-effect waves-dark sidebar-link" href="'.$direccion2.'" aria-expanded="false" '.$sbMnA.'>
																						<i class="mdi mdi-menu-right"></i><i class="'.$iconMenu2.'" style="color: #'.$this->iconColor.';"></i>
																						<span class="hide-menu"> '.$nomSubMenu.'</span>
																					</a>
																				</li>';

												} else {		// en caso de que no haya un submenu pasa al siguiente elemento de la lista un nivel anterior (del nivel 2 sube al nivel 1)

														if($dat['idSubMenu'] == '' && $abierto1 == 1) {	// evalua si esta abierta la etiqueta <ul> para cerrarla
														$menus .= '</ul>';			// Cierra la lista
															$abierto1 = 0;				// iguala a 0 la variable, si hay otra lista despues vuelve a comenzar el ciclo del listado
														}

													$menus .= '<li class="sidebar-item" '.$mnAct.'>
																			<a class="sidebar-link waves-effect waves-dark sidebar-link" href="'.$direccion.'" aria-expanded="false" '.$mnA.'>
																				<i class="'.$iconMenu.'" style="color: #'.$this->iconColor.';"></i>
																				<span class="hide-menu"> '.$nomMenu.'</span>
																			</a>';
												}


						if ($abierto1 == 0) {
							$menus .= '</li>';
						}
					}
					$menuAnt = $menuAct;
			}


			return '
			<aside class="left-sidebar">
					<div class="scroll-sidebar">
							<nav class="sidebar-nav">
									<ul id="sidebarnav">
									'.$menus.'
									</ul>
							</nav>
					</div>
					<input type="hidden" id="varSesion" value="'.$_SESSION['LZFarea'].'">
			</aside>';
    }
}

function problemas($msj){
	if ($GLOBALS['devBug'] == 1) {
		echo '<br><hr><br>MSJ ERROR: '.$msj.'<br><br><hr><br>';
	} else {
		$_SESSION['LZFacceso']=$msj;
		header('location: index.php');
	}
	exit(0);
}

function negado($link, $msj){
	if ($GLOBALS['devBug'] == 1) {
		echo '<br><hr><br>*** MSJ ACCESO NEGADO: '.$msj.'<br><br><hr><br>';
	} else {
		$_SESSION['LZFmsjGralPlatform'] = $msj;
		header('location: '.$link);
	}
	exit(0);
}
?>
