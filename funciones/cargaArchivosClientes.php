<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$ident = (isset($_POST['ident'])) ? trim($_POST['ident']) : '';
$debug = 0;

//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

if ($ident == '') {
  errorBD('No se reconoce el Cliente, actualiza e intenta otra vez, si persiste notifica a tu Administrador', '');
} ?>

<div class="accordion" id="accordionExample">


  <?php
  $sql = "SELECT * FROM doctoclientes WHERE idCliente='$ident'";

  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Cargar Archivos, notifica a tu Administrador', mysqli_error($link)));

    //$canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    // echo '<br>Cant de Registros Cargados: '.$canInsert;
  } else {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Cargar Archivos, notifica a tu Administrador', mysqli_error($link)));
    //$canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------
  if (mysqli_num_rows($resultXquery) <= 0) {
    echo '<div class="col-md-12 text-danger">No Hay Documentos Relacionados.</div>';
  } else {
    $iteracion = 0;

    while ($com = mysqli_fetch_array($resultXquery)) {
      $archivo = '';
      $show = '';
      $iteracion++;
      $ident = "Evidencia_" . $iteracion;
      $redireccion = "Ir_" . $iteracion;
      $tipo = $com['tipo'];

      $archivo = '<embed src="../' . $com['docto'] . '" type="application/pdf" width="100%" height="600"  ></embed>';


      //----------------devBug------------------------------
      if ($debug == 1) {
        echo ($archivo);
        print_r($com);
      } //-------------Finaliza devBug------------------------------
      $collapsed = '';
      if ($iteracion == 1) {
        $show = 'show';
        $collapsed = '';
      }

      echo '  
      <div class="card">
        <div class="card-header" id="heading' . $iteracion . '">
          <h2 class="mb-0">
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $iteracion . '" aria-expanded="true" aria-controls="collapse' . $iteracion . '">
                    ' . $tipo . '
            </button>
          </h2>
        </div>
        <div id="collapse' . $iteracion . '" class="collapse" aria-labelledby="heading' . $iteracion . '" data-parent="#accordionExample">
          <div class="card-body">
              ' . $archivo . '
          </div>
        </div>
      </div>
        
     
        
          ';
    }
  }








  ?>



</div>
</div>



<?php
function errorBD($error, $sql_error)
{
  if ($GLOBALS['debug'] == 1) {
    echo '<br><br>Det Error: ' . $error;
    echo '<br><br>Error Report: ' . $sql_error;
  } else {
    echo '0|' . $error;
  }
  exit(0);
}

?>