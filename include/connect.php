<?php
if(!defined('INCLUDE_CHECK')) die('No se puede leer este archivo');

if (isset($_SESSION['LZFident']) AND ($_SESSION['LZFident'] >= 1 OR $_SESSION['LZFident'] == 'DlpGLtFCQD6rjvlikE3oGo9') AND
    isset($_SESSION['LZFidNivel']) AND ($_SESSION['LZFidNivel'] >= 1 OR $_SESSION['LZFidNivel'] == 'LZU34ZxoR9+9O2wZIPFRJVL') AND
    isset($_SESSION['LZFidSuc']) AND ($_SESSION['LZFidSuc'] >= 1 OR $_SESSION['LZFidSuc'] == 'RJVLIYui5sivbuUAT9N8V7Y')) {
  date_default_timezone_set('Mexico/General');

  /* Database config */
/*
  $db_host		= 'localhost:3308';
  $db_user		= 'root';
  $db_pass		= '310187';
  $db_database	= 'puntoventa';
#*/

  $db_host		= 'localhost';
  $db_user		= 'root';
  $db_pass		= '';
  $db_database	= 'puntoventa';

 /*$db_host		= 'localhost';
  $db_user		= 'u619350364_controlSuc';
  $db_pass		= 'C0ntr0ld3@cc3s0..';
  $db_database	= 'u619350364_controlSuc';*/


  $link = mysqli_connect($db_host,$db_user,$db_pass,$db_database) or die('No se pudo realizar la conexion');
  mysqli_select_db($link,$db_database);
  mysqli_query($link, "SET names UTF8");
  mysqli_query($link, "SET time_zone = '-06:00'");

} else {
  header('location: logout.php');
  #echo 'Valio Mauser tu conexion..';
}
?>
