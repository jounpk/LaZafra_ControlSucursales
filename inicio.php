<?php
require_once 'seg.php';
$info = new Seguridad();
$info->Acceso('inicio.php');

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title><?=$info->nombrePag;?></title>

		<!-- BEGIN META -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<!-- END META -->

		<!-- BEGIN STYLESHEETS -->
		<link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/bootstrap.css?1422792965" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/materialadmin.css?1425466319" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/font-awesome.min.css?1422529194" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/material-design-iconic-font.min.css?1421434286" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/select2/select2.css?1424887856" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/multi-select/multi-select.css?1424887857" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/bootstrap-datepicker/datepicker3.css?1424887858" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/jquery-ui/jquery-ui-theme.css?1423393666" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/bootstrap-tagsinput/bootstrap-tagsinput.css?1424887862" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/typeahead/typeahead.css?1424887863" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/DataTables/jquery.dataTables.css?1423553989" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/DataTables/extensions/dataTables.colVis.css?1423553990" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/DataTables/extensions/dataTables.tableTools.css?1423553990" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/summernote/summernote.css?1425218701" />
		<link type="text/css" rel="stylesheet" href="assets/css/theme-Leagold/libs/toastr/toastr.css?1425466569" />

    <link rel="shortcut icon" href="favicon.ico">
		<!-- END STYLESHEETS -->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="assets/js/libs/utils/html5shiv.js?1403934957"></script>
		<script type="text/javascript" src="assets/js/libs/utils/respond.min.js?1403934956"></script>
		<![endif]-->
	</head>
	<body class="menubar-hoverable header-fixed me">

		<!-- BEGIN HEADER-->
		<header id="header" >
			<div class="headerbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand" >
							<div class="brand-holder">
								<a href="inicio.php">
									<img src="assets/img/texto100.png">
								</a>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="headerbar-right">
					<ul class="header-nav header-nav-profile">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
								<img src="assets/img/avatar1.jpg?1403934956" alt="" />
								<span class="profile-info">
									<?=$info->nombreUser;?>
									<small><?=$info->nombreNivel;?></small>
								</span>
							</a>
							<ul class="dropdown-menu animation-dock">
	              <!--<li><a href="html/pages/calendar.html"><i class="fa fa-fw fa-globe"></i> Calendario</a></li>
								<li class="divider"></li>-->
								<?=$info->generaMenuUsuario();?>
							</ul><!--end .dropdown-menu -->
						</li><!--end .dropdown -->
					</ul><!--end .header-nav-profile -->
				</div><!--end #header-navbar-collapse -->
			</div>
		</header>
		<!-- END HEADER-->

		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN OFFCANVAS LEFT -->
			<div class="offcanvas">
			</div><!--end .offcanvas-->
			<!-- END OFFCANVAS LEFT -->

			<!-- BEGIN CONTENT-->
			<div id="content">
				<section>
					<div class="section-body">

						<!-- BEGIN INTRO -->
						<div class="row">
							<div class="col-lg-12">
								<h1 class="text-primary"><?=$info->nombrePag;?></h1>
							</div><!--end .col -->
							<div class="col-lg-8">
								<article class="margin-bottom-xxl">
									<p class="lead"><?=$info->detailPag;?></p>
								</article>
							</div><!--end .col -->
						</div><!--end .row -->
						<!-- END INTRO -->
						<?php
						if($info->Seccion(1)){

						?>
						<!-- BEGIN ACTION -->
						<div class="card">
							<div class="card-head">
								<header><i class=""> En espera de la Imagen Principal...</header>
								<div class="tools">
								</div>
							</div>
						</div>
						<!-- END ACTION -->

						<?php
						}
						?>
					</div><!--end .section-body -->

				</section>
			</div><!--end #content-->
			<!-- END CONTENT -->


			<!-- BEGIN MENUBAR-->
			<div id="menubar" class="menubar-inverse ">

				<div class="menubar-scroll-panel">

					<!-- BEGIN MAIN MENU -->
					<ul id="main-menu" class="gui-controls">

						<!-- MENU LATERAL -->
						<?=$info->generaMenuLateral();?>

					</ul><!--end .main-menu -->
					<!-- END MAIN MENU -->

				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->


			</div>	
		</div><!--end #base-->
		<!-- END BASE -->

		<!-- BEGIN JAVASCRIPT -->
		<script src="assets/js/libs/jquery/jquery-1.11.2.min.js"></script>
		<script src="assets/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
		<script src="assets/js/libs/jquery-ui/jquery-ui.min.js"></script>
		<script src="assets/js/libs/bootstrap/bootstrap.min.js"></script>
		<script src="assets/js/libs/spin.js/spin.min.js"></script>
		<script src="assets/js/libs/autosize/jquery.autosize.min.js"></script>
		<script src="assets/js/libs/select2/select2.min.js"></script>
		<script src="assets/js/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
		<script src="assets/js/libs/summernote/summernote.min.js"></script>
		<script src="assets/js/libs/multi-select/jquery.multi-select.js"></script>
		<script src="assets/js/libs/inputmask/jquery.inputmask.bundle.min.js"></script>
		<script src="assets/js/libs/moment/moment.min.js"></script>
		<script src="assets/js/libs/DataTables/jquery.dataTables.min.js"></script>
		<script src="assets/js/libs/DataTables/extensions/ColVis/js/dataTables.colVis.min.js"></script>
		<script src="assets/js/libs/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>
		<script src="assets/js/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
		<script src="assets/js/libs/d3/d3.min.js"></script>
		<script src="assets/js/libs/d3/d3.v3.js"></script>
		<script src="assets/js/libs/rickshaw/rickshaw.min.js"></script>
		<script src="assets/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>
		<script src="assets/js/libs/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="assets/js/libs/jquery-validation/dist/additional-methods.min.js"></script>
		<script src="assets/js/core/source/App.js"></script>
		<script src="assets/js/core/source/AppNavigation.js"></script>
		<script src="assets/js/core/source/AppOffcanvas.js"></script>
		<script src="assets/js/core/source/AppCard.js"></script>
		<script src="assets/js/core/source/AppForm.js"></script>
		<script src="assets/js/core/source/AppNavSearch.js"></script>
		<script src="assets/js/core/source/AppVendor.js"></script>
		<script src="assets/js/core/demo/Demo.js"></script>
		<script src="assets/js/core/demo/DemoTableDynamic.js"></script>
		<script>
		function btnGenerado(ident){
			$("#pBtn"+ident).html('<img src="assets/img/loading.gif" class="img-responsive" style="margin-bottom:-1em" alt="Trabajando..."/>');
			//alert('ID: '+ident);
			$.post("funciones/btnEstatusGenerado.php",
      	{ identif:ident },
    			function(respuesta){
      			$("#pBtn"+ident).html(respuesta);
						$("#pBtn"+ident).attr("data-original-title","En Proceso: <?=$info->nombreUser;?>");
   				});
		}

		function btnProceso(ident){
			$("#pBtn"+ident).html('<img src="assets/img/loading.gif" class="img-responsive" style="margin-bottom:-1em" alt="Trabajando..."/>');
			//alert('ID: '+ident);
			$.post("funciones/btnEstatusProceso.php",
				{ identif:ident },
					function(respuesta){
						$("#pBtn"+ident).html(respuesta);
						$("#pBtn"+ident).attr("data-original-title","Atendido: <?=$info->nombreUser;?>");
					});
		}

		function btnGeneraTicket(ident){
			$("#btnTicket"+ident).html('<img src="assets/img/loading.gif" class="img-responsive" style="margin-bottom:-1em" alt="Trabajando..."/>');
			//alert('ID: '+ident);
			$.post("funciones/generaTicket.php",
      	{ identif:ident },
    			function(respuesta){
      			$("#btnTicket"+ident).html(respuesta);
   				});
		}
		</script>
		<!-- END JAVASCRIPT -->

	</body>
</html>
