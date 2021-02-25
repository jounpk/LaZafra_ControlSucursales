<?php
session_start();
?>
<!DOCTYPE html>
<html dir="ltr" lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plataforma de Punto de Venta.">
    <meta name="author" content="RV SETyS">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>LA ZAFRA - MX</title>
    <!-- Custom CSS -->
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box capaPrincipal" style="opacity:.6;">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="images/logo-icon.png" alt="logo" /></span>
                        <h5 class="font-medium m-b-20"></h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal m-t-20" id="loginform" action="login.php" method="post">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="Usuario" name="usuario" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" class="form-control form-control-lg" placeholder="Password" name="password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox">

                                            <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> Olvido su password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info" type="submit">Iniciar Sesión</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                        <div class="social">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        Powered By <a href="https://rvsetys.com" class="text-info m-l-5"><b>RV SETyS</b></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="recoverform" class="capaPrincipal">
                    <div class="logo">
                        <span class="db"><img src="images/logo-icon.png" alt="logo" /></span>
                        <h5 class="font-medium m-b-20"><br>Recuperar tu Password</h5>
                        <span>Comunicate con tu Administrador del Sistema!</span>
                    </div>
                    <div class="row m-t-20">
                        <!-- Form -->
                        <div class="col-12">

                            <!-- pwd -->
                            <div class="row m-t-20">
                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-danger" id="to-login" type="submit" name="action">Regresar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!--Custom JavaScript -->
    <script src="assets/libs/toastr/build/toastr.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $(document).ready(function(){
      <?php
      if (isset($_SESSION['LZFacceso']) AND $_SESSION['LZFacceso'] != '') {
        echo  "notificaBad('".$_SESSION['LZFacceso']."');";
        unset($_SESSION['LZFacceso']);
      }
      ?>
    });

    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-login').on("click", function() {
        $("#loginform").fadeIn();
        $("#recoverform").slideUp();
    });
    // ==============================================================
    // Oculta Capa Principal
    // ==============================================================
    $(".capaPrincipal").mouseenter(function(e){
      $(".capaPrincipal").css("opacity", "1");
    });
    $(".capaPrincipal").mouseleave(function(e){
      $(".capaPrincipal").css("opacity", "0.7");
    });
    // Success Type
    function notificaBad(mensaje){
      toastr.error(mensaje);
    }

    </script>
</body>

</html>
