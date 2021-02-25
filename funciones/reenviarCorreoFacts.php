<?php
session_start();
define('INCLUDE_CHECK',1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once('../include/connect.php');
$busqVentas = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$correo = (!empty($_POST['email'])) ? $_POST['email'] : 0 ;
$serie = (!empty($_POST['serie'])) ? $_POST['serie'] : 0 ;
$folio = (!empty($_POST['folio'])) ? $_POST['folio'] : 0 ;
$empresa = (!empty($_POST['empresa'])) ? $_POST['empresa'] : 0 ;
$nameSuc = (!empty($_POST['sucursal'])) ? $_POST['sucursal'] : 0 ;
$UUID = (!empty($_POST['uuid'])) ? $_POST['uuid'] : 0 ;
$filePDF = (!empty($_POST['doctoPDF'])) ? $_POST['doctoPDF'] : 0 ;
$fileXML = (!empty($_POST['doctoXML'])) ? $_POST['doctoXML'] : 0 ;

if($busqVentas=='' || $correo=='' || $serie==''|| $folio==''|| $empresa==''|| $nameSuc==''||$UUID==''||$filePDF==''||$fileXML==''){
    errorBD("Faltan Datos Necesarios, verifica e intentalo de nuevo");
}

//===================================  LANZANDO CORREO ===========================================================

$mail = new PHPMailer();  
                            // Passing `true` enables exceptions
$mail->setLanguage('es', 'PHPMailer/language/');
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.mx';    
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'karen.dominguez@rvsetys.com';      // SMTP username
    $mail->Password = 'K4r3n.d0m1ngu3z';                     // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
  # echo 'Correos enviados a: ';
    //Recipients
    $mail->setFrom('karen.dominguez@rvsetys.com', 'Por Favor no responda este correo.');
    //PRINT_R("este es el correo-->".$correo);
    $mail->addAddress($correo);     // Add a recipient
   // $mail->addBCC('contabilidad@lazafra.com.mx');

    //$mail->addReplyTo('karen.dominguez@rvsetys.com', 'No Responder');

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Reenvío Factura creada con Folio: '.$serie.''.$folio.' de la Venta: '.$busqVentas;
    $mail->Body  = ' <h2>Venta con Folio: '.$busqVentas.'</h2> <br>';
    $mail->Body .= ' <p><b>Factura emitida por '.$empresa.' en la Sucursal '.$nameSuc.'.</b><br>';
    $mail->Body .= ' <p>Folio Fiscal: <b>'.$UUID.'</b></p>';
    $mail->Body .= ' <p><b>NOTA:</b><br>
                       Cualquier duda o aclaración, contactese con la sucursal donde realizó su compra.<br>';
    $mensajeFactura="";
   // $mail->Body  ='la mamada';
    $mail->CharSet = 'UTF-8';
    $archivo = 'Factura_'.$serie.'-'.$folio;

    $mail->addAttachment('../'.$filePDF, $archivo.'.PDF');
     $mail->addAttachment('../'.$fileXML, $archivo.'.XML');

    $mail->send();
    $mensajeFactura .= ' y se envió copia a la sucursal';
    echo "1|Envío Correcto de la factura a ".$correo;
}
catch (Exception $e) {
  echo '0|E8b.- '.$mensajeFactura.', pero tuvimos Problemas al Mandar el correo a la Sucursal, Notifica a tu Administrador';
}
//======================================   FINALIZA CORREO   =====================================================


?>