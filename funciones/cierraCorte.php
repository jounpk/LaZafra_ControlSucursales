<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCorte = (!empty($_POST['idCorte'])) ? $_POST['idCorte'] : 0 ;
$m1 = (!empty($_POST['moneda1'])) ? $_POST['moneda1'] : 0 ;
$m2 = (!empty($_POST['moneda2'])) ? $_POST['moneda2'] : 0 ;
$m5 = (!empty($_POST['moneda5'])) ? $_POST['moneda5'] : 0 ;
$m10 = (!empty($_POST['moneda10'])) ? $_POST['moneda10'] : 0 ;
$m20 = (!empty($_POST['moneda20'])) ? $_POST['moneda20'] : 0 ;
$m100 = (!empty($_POST['moneda100'])) ? $_POST['moneda100'] : 0 ;
$b20 = (!empty($_POST['billete20'])) ? $_POST['billete20'] : 0 ;
$b50 = (!empty($_POST['billete50'])) ? $_POST['billete50'] : 0 ;
$b100 = (!empty($_POST['billete100'])) ? $_POST['billete100'] : 0 ;
$b200 = (!empty($_POST['billete200'])) ? $_POST['billete200'] : 0 ;
$b500 = (!empty($_POST['billete500'])) ? $_POST['billete500'] : 0 ;
$b1000 = (!empty($_POST['billete1000'])) ? $_POST['billete1000'] : 0 ;
$cambio = (!empty($_POST['cambio'])) ? $_POST['cambio'] : 0 ;
$montoTotal = (!empty($_POST['montoTotal'])) ? $_POST['montoTotal'] : 0 ;
$sqlCon = "SELECT estatus FROM cortes WHERE id = '$idCorte'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los cortes, notifica a tu Administrador.'));
$c = mysqli_fetch_array($resCon);
$estatus = $c['estatus'];
if ($estatus > 1) {
  errorBD('El corte ya fue cerrado, no se puede hacer cambios a un corte cerrado');
}
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$idCorte: '.$idCorte;
echo '<br>$m1: '.$m1;
echo '<br>$m2: '.$m2;
echo '<br>$m5: '.$m5;
echo '<br>$m10: '.$m10;
echo '<br>$m20: '.$m20;
echo '<br>$m100: '.$m100;
echo '<br>$b20: '.$b20;
echo '<br>$b50: '.$b50;
echo '<br>$b100: '.$b100;
echo '<br>$b200: '.$b200;
echo '<br>$b500: '.$b500;
echo '<br>$b1000: '.$b1000;
echo '<br>$cambio: '.$cambio;
echo '<br>$montoTotal: '.$montoTotal;
echo '<br><br> ############################################# <br>';
#exit(0);
#*/
$conBilletes = "SELECT * FROM desglocesefectivo WHERE idCorte = '$idCorte'";
$resConBilletes = mysqli_query($link,$conBilletes) or die(errorBD('Problemas al verificar el detallado de billetes, notifica a tu Administrador.'));
$cant = mysqli_num_rows($resConBilletes);
if ($cant > 0) {
  $dbillete = mysqli_fetch_array($resConBilletes);
  $idDetBilletes = $dbillete['id'];
$sqlDetBilletes = "UPDATE desglocesefectivo SET cambio = '$cambio',montoFinal = '$montoTotal',m1 = '$m1',m2 = '$m2',m5 = '$m5',m10 = '$m10',m20 = '$m20',m100 = '$m100',b20 = '$b20',
                    b50 = '$b50',b100 = '$b100',b200 = '$b200',b500 = '$b500',b1000 = '$b1000' WHERE id = '$idDetBilletes'";
} else {
  $sqlDetBilletes = "INSERT INTO desglocesefectivo(idCorte, cambio,montoFinal,m1,m2,m5,m10,m20,m100,b20,b50,b100,b200,b500,b1000)
                      VALUES('$idCorte','$cambio','$montoTotal','$m1','$m2','$m5','$m10','$m20','$m100','$b20','$b50','$b100','$b200','$b500','$b1000')";
}

#echo '<br>$sqlDetBilletes: '.$sqlDetBilletes;
$resDetBilletes = mysqli_query($link,$sqlDetBilletes) or die(errorBD('Problemas al capturar el detallado de billetes, notifica a tu Administrador.'));

$sqlCierraCorte = "UPDATE cortes SET estatus = '2', fechaCierre = NOW() WHERE id = '$idCorte'";
#echo '<br>$sqlCierraCorte: '.$sqlCierraCorte;
$resCierraCorte = mysqli_query($link,$sqlCierraCorte) or die(errorBD('Problemas al realizar el cierre de corte, notifica a tu Administrador.'));

$_SESSION['LZFticketCorte'] = $idCorte;
echo '<script>
        location.href=" ../imprimeTicketCorte.php?idCorte='.$idCorte.'";
      </script> ';
    #  echo '<br>Se abrió?';

$_SESSION['LZFmsjSuccessInicioCaja'] = 'Cierre realizado correctamente';
#header('location: ../cierreDeCorte.php');
exit(0);

function errorBD($error){
#  echo '<br>Sucedió un error: '.$error;
  $_SESSION['LZFmsjInicioCaja'] = $error;
#  header('location: ../cierreDeCorte.php');
}
 ?>
