<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$linea = '';
$idCotizacion = (!empty($_REQUEST['idCotizacion'])) ? $_REQUEST['idCotizacion'] : 0 ;
$_SESSION['idCotizacion'] = $idCotizacion;

  if(!ctype_digit($idCotizacion)) {
      $idCotizacion = 0;
  }

  if($idCotizacion > 0) {
     $sql = "SELECT c.id,c.folio,dc.correo,dc.id
            FROM cotizaciones c
            INNER JOIN detcotcorreos dc ON c.id = dc.idCotizacion
            WHERE c.id = '$idCotizacion'";
     $res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los correos, notifica a tu Administrador.'.$linea));
     $cant = mysqli_num_rows($res);
     $linea .= '24,';
 
        while ($eq = $res->fetch_assoc()) {
          echo '1|
                 <tr class="text-center">
                    <td>'.$eq['correo'].'
                    <td>
                    <button id="'.$eq['id'].'" onclick="deleteEmail('.$eq['id'].')" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    </button>
                    </td>
                   </tr>
                ';

      }
  
  if($cant < 1) {
      errorBD('No se encontró ningún correo'.$linea);
  }
 }

function errorBD($error){
    echo '0|'.$error;
    exit(0);
   }

?>
