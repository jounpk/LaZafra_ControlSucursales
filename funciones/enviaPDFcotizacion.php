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
$idCotizacion = (!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0 ;
$pagina = (isset($_REQUEST['pagina']) && $_REQUEST['pagina'] != '') ? $_REQUEST['pagina'] : '' ;
$alerta = (isset($_REQUEST['alerta']) && $_REQUEST['alerta'] != '') ? $_REQUEST['alerta'] : '' ;
$idUser = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];

#/*
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
#*/

if (!ctype_digit($idCotizacion)) {
      $idCotizacion = 0;
    }
    if ($idCotizacion > 0) {
      $sql = "SELECT c.id,c.folio,c.cliente,dc.correo
              FROM cotizaciones c
              INNER JOIN detcotcorreos dc ON c.id = dc.idCotizacion
              WHERE c.id = '$idCotizacion'";
      $res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los correos, notifica a tu Administrador.'.$linea));
      $cant = mysqli_num_rows($res);
      $linea .= '24,';
      if ($cant < 1) {
        errorBD('No se encontró ningún correo al cual mandar la cotización.'.$linea);
      }


      //===================================  LANZANDO CORREO ===========================================================

      $mail = new PHPMailer();                              		// Passing `true` enables exceptions
      $mail->setLanguage('es', 'PHPMailer/language/');
      try {
          //Server settings
          $mail->SMTPDebug = 0;                                 // Enable verbose debug output
          $mail->isSMTP();                                      // Set mailer to use SMTP
          $mail->Host = 'smtp.hostinger.mx';                       // Specify main and backup SMTP servers
          $mail->SMTPAuth = true;                               // Enable SMTP authentication
          $mail->Username = 'abi.said@rvsetys.com';               // SMTP username
          $mail->Password = 'V3n3n0117';                       // SMTP password
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 587;                                  // TCP port to connect to
    #      	echo 'Correos enviados a: ';
           //Recipients
          $mail->setFrom('abi.said@rvsetys.com', 'Por Favor no responda este correo.');        // Es el remitente
          while ($m = mysqli_fetch_array($res)) {
            $mail->addAddress($m['correo']);     // Add a recipient  Destinatario 1
            echo '<br>'.$m['correo'];
            $folio = $m['folio'];
          }
          $mail->addAddress('abi.said@rvsetys.com');     // Add a recipient  Destinatario 1

          #$mail->addBCC('arq.said@gmail.com');

          $mail->addReplyTo('abi.said@rvsetys.com', 'No Responder');

          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'Cotización';
          $mail->Body  = ' <h2>Cotización con Folio: '.$folio.'</h2> <br>';
          $mail->Body .= ' <p><b>Cotización emitida por '.$_SESSION['LZFnombreUser'].' en '.$_SESSION['LZFnombreSuc'].'.</b><br>';
          $mail->Body .= ' <p><b>NOTA:</b><br>
                             Cualquier duda o aclaración contactese con la sucursal donde expidió su Cotización.<br>';
          $mail->CharSet = 'UTF-8';

          $mail->addAttachment('../doctos/Cotizacion/cotizacion.pdf');
          $mail->send();

        }
        catch (Exception $e) {
          errorBD('No se pudo enviar la cotización.');
        }
        #*/
    } else {
      errorBD('No se reconoció la cotización, inténtalo de nuevo, si persiste notifica a tu Administrador.'.$linea);
      exit(0);
    }
    $sqlUp = "UPDATE cotizaciones SET enviado = 2 WHERE id = '$idCotizacion' LIMIT 1";
    $resUp = mysqli_query($link,$sqlUp) or die('Problemas al actualizar el envío de la cotización, notifica a tu Administrador.');

    $_SESSION['LZFmsjSuccess'.$alerta] = 'Se ha enviado el correo correctamente.';
    header('location: ../'.$pagina);
/*
    echo '<br>Archivo enviado';
    echo '<br>LZFmsjSuccess'.$alerta;
    echo '<br>header(\'location: ../'.$pagina.')';;
    */
    exit(0);

    function errorBD($error){
      #echo '<br>$error: '.$error;
      $_SESSION['LZFmsj'.$alerta] = $error;
      header('location: ../'.$pagina);
      exit(0);
    }
 ?>
