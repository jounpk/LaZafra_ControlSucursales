<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$color = (isset($_POST['color']) AND $_POST['color'] != '') ? $_POST['color'] : '' ;

if ($id < 1) {
    errorBD('No se reconoció el pago, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  $sql = "SELECT
  doctoComprobante AS link,
  extComprobante,
  descripcion
FROM
  gastos 
  
WHERE
  id = '$id'";
  $res = mysqli_query($link, $sql) or die('Problemas al listar la imagen, notifica a tu Administrador');
  $array=mysqli_fetch_array($res);
  $imagen=$array['link'];
  $ext=$array['extComprobante'];
  $desc=$array['descripcion'];
  switch ($ext) {
      case 'pdf':
        $contenido='<embed src="'.$imagen.'" type="application/pdf" width="100%" height="600"  ></embed>';
          break;
      
      default:
         $contenido='<img class="img-thumbnail responsive" src="'.$imagen.'" width="100%"  >' ;
          break;
  }
  $imagen_modal='<div class="modal fade" id="verIMG" role="dialog" aria-labelledby="verPDFLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header bg-'.$color.'" style="color:#fff;" id="verIMGContent">

              <h4 class="modal-title" id="verIMGTitle">'.$desc.' </h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

          </div>
          <div class="modal-body" id="verIMGBody">
                '.$contenido.'
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
          </div>
      </div>
  </div>
</div>';
 echo $imagen_modal;
  ?>
