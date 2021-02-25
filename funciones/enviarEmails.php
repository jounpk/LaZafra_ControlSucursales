<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$linea = '';
$idCoti = $_SESSION['idCotizacion'];
//$idCoti = (!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0 ;

/*
echo '<br><br>###########################################<br><br>';
echo '<br>$_REQUEST:';
print_r($_REQUEST);
echo '<br>$idCotizacion:'.$idCotizacion;
echo '<br>$pagina:'.$pagina;
echo '<br>$alerta:'.$alerta;
echo '<br>$idUser:'.$idUser;
echo '<br>$idSucursal:'.$idSucursal;
echo '<br><br>###########################################<br><br>';
#exit(0);
*/

  if(!ctype_digit($idCoti)) {
      $idCoti = 0;
  }
  if($idCoti > 0) {
      $sql = "SELECT c.id,c.folio,dc.correo,suc.nombre, DATE_FORMAT(DATE_ADD(c.fechaAut,INTERVAL c.cantPeriodo DAY),'%d-%m-%Y') AS fechaExp,emp.nombre AS empresa
              FROM cotizaciones c
              INNER JOIN detcotcorreos dc ON c.id = dc.idCotizacion
              INNER JOIN sucursales suc ON c.idSucursal = suc.id 
              INNER JOIN empresas emp ON suc.idEmpresa=emp.id
              WHERE c.id = '$idCoti'";

      $res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los correos, notifica a tu Administrador.'.$linea));
      $cant = mysqli_num_rows($res);
      $linea .= '24,';

      if($cant < 1) {
         errorBD('No se encontró ningún correo al cual mandar la cotización.'.$linea);
      }


      //===================================  LANZANDO CORREO ===========================================================

$mail = new PHPMailer();
      // Passing `true` enables exceptions
$mail->setLanguage('es', 'PHPMailer/language/');

  try{
    
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.mx';
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'lazafra@lazafra.com.mx';      // SMTP username
    $mail->Password = '@gr0f3rt1l1z@nt3s.L@z@fr@';                     // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
        # echo 'Correos enviados a: ';
        //Recipients
    $mail->setFrom('lazafra@lazafra.com.mx', 'Por Favor no responda este correo.');
        //PRINT_R("este es el correo-->".$correo);

      while($m = mysqli_fetch_array($res)) {
            $mail->addAddress($m['correo']);     // Add a recipient  Destinatario 1
            echo '<br>'.$m['correo'];
            $folioCotizacion = $m['folio'];
            $nomSucursal=$m["nombre"];
            $fechaExp=$m["fechaExp"];
            $empresa=$m["empresa"];
      }
          
      $mail->isHTML(true);    
      $mail->Subject="Cotización emitida por: " .$empresa;
      $mail->Body .="".$empresa. ' Agradece el interes mostrado';
      $mail->Body .= ' <p><b>Folio Cotización: ' .$folioCotizacion.'</b><br>
          Le recordamos que los precios están sujetos a cambios<br>';
      $mail->Body.='<p>Nombre de la sucursal: ' .$nomSucursal.'</p>';
      $mail->Body.='<p>Fecha de expiración: ' .$fechaExp.'</p>';
      $mail->Body.='<p><b>NOTA:</b><br>
             Le recordamos que los precios están sujetos a cambios.</br>';
      $mail->CharSet = 'UTF-8';
      $mail->addAttachment('../doctos/Cotizacion/cotizacion.pdf');

      $mail->Send();

      }catch(Exception $e) {
        errorBD('No se pudo enviar la cotización.');
      }
    } else {
       errorBD('No se reconoció la cotización, inténtalo de nuevo, si persiste notifica a tu Administrador.'.$linea);
       exit(0);
    }

    function errorBD($error){
      #echo '<br>$error: '.$error;
      $_SESSION['LZFmsj'.$alerta] = $error;
      header('location: ../'.$pagina);
      exit(0);
    }
    
 ?>
