<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$idCorte = (isset($_POST['idCorte']) and $_POST['idCorte'] >= 1) ? $_POST['idCorte'] : '';
$pyme = $_SESSION['LZFpyme'];
$idNivel = $_SESSION['LZFidNivel'];

$sql = "SELECT d.*, s.nombre
FROM cortes c
INNER JOIN depositos d ON c.id = d.idCorte
INNER JOIN sucursales s ON c.idSucursal = s.id
WHERE c.id = '$idCorte'
ORDER BY d.fechaReg DESC
LIMIT 1";
//echo $sql;
$result = mysqli_query($link, $sql) or die('<span class="text-danger"><center>Problemas al consultar los Datos. Notifica a tu Administrador.</center></span>' . $sql);
$dat = mysqli_fetch_array($result);
$idDeposito = $dat['id'];
$nomSucursal = $dat['nombre'];
$total = $dat['total'];
$estatus = $dat['estatus'];
if ($dat['estatus'] == '0' || $dat['estatus'] == '3') {
  if ($idNivel == '3' || $idNivel == '4' || $idNivel == '7') {
    ?>
    <div class="modal-header bg-<?=$pyme;?>">
      <h4 class="modal-title text-white" id="lblDetallaCorte"><?=$nomSucursal;?>, Corte <?=$idCorte;?>, Monto: $<?=number_format($total,2,'.',',');?></h4>
      <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body text-center">
      <br><h4>Sin documentos</h4><br>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <?php
  } else {
  $fechaAct=date('Y-m-d');
$required = ($total > 0) ? 'required' : '' ;
?>

  <div class="modal-header bg-<?=$pyme;?>">
    <h4 class="modal-title text-white" id="lblDetallaCorte"><?=$nomSucursal;?>, Corte <?=$idCorte;?>, Monto: $<?=number_format($total,2,'.',',');?></h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
  </div>
    <form class="form-horizontal" role="form" method="post" onsubmit="return checkSubmit();" action="../funciones/capturaDeposito.php" enctype="multipart/form-data">
      <div class="modal-body">

        <label class="control-label">Fecha</label>
        <div class="input-group mb-3">
            <input type="date" class="form-control" name="fecha[]" id="fechaDeposito0" value="<?=$fechaAct;?>" required>
        </div>

        <label class="control-label">Banco</label>
        <div class="input-group mb-3">
            <select class="form-control" id="calveBanco0" name="idBanco[]" onchange="muestraCuentas(this.value,0)" required>
              <option value="">Selecciona un Banco</option>
              <?php
                $sqlBnk = "SELECT c.id,c.nombreCorto
                          FROM catbancos c
                          INNER JOIN cuentasbancarias cb ON c.id = cb.idClaveBanco GROUP BY c.id";
                $resBnk = mysqli_query($link,$sqlBnk) or die('Problemas al consultar los bancos, notifica a tu Administrador.');
                while ($bnk = mysqli_fetch_array($resBnk)) {
                  echo '<option value="'.$bnk['id'].'">'.$bnk['nombreCorto'].'</option>';
                }
               ?>
            </select>
        </div>
        <div class="input-group mb-3" id="muestraNoCuenta0">

        </div>
        <label class="control-label">Folio del Ticket de Depósito</label>
        <div class="input-group mb-3">
            <input type="text" id="folioTicket0" class="form-control" name="folioTicket[]" required>
        </div>

        <label class="control-label">Monto</label>
        <div class="input-group mb-3">
            <input type="text" step="any" min="0.01" id="deposito0" name="deposito[]" onkeyup="mascaraMonto(this,Monto)" class="form-control" <?=$required;?>>
        </div>

        <label class="control-label">Foto</label>
        <div class="input-group mb-3">
            <input type="file" id="foto0" accept="image/jpeg" class="form-control" name="foto0">
        </div>
        <div id="divAgregaCampos">

        </div>
<!-- ########################################################################################## -->
        <label class="control-label">¿Deseas agregar otro depósito para éste corte?&nbsp;&nbsp;</label><br>
        <div class="input-group mb-3">
            <div class="btn btn-info" onclick="agregaCampos();" id="btnAgrega"><input type="hidden" id="valores" value=""><i class="fas fa-plus"></i> Agregar Campos</div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="idDeposito" value="<?=$idDeposito;?>">
          <input type="hidden" name="idCorte" value="<?=$idCorte;?>">
          <input type="hidden" name="vista" value="">
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success waves-effect waves-light">Registrar</button>
        </div>
      </div>
    </form>


<?php
}
} else {


  $sqlConsulta = "SELECT d.id AS idDeposito,dd.docto,dd.folio,dd.monto,bnk.nombreCorto,dd.fechaDeposito,cb.noCuenta,CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomRecolector
                  FROM depositos d
                  INNER JOIN segusuarios u ON d.idUserReg = u.id
                  LEFT JOIN detdepositos dd ON d.id = dd.idDepositoRecoleccion AND dd.estatus IN(1,2)
                  LEFT JOIN catbancos bnk ON dd.idClaveBanco = bnk.id
                  LEFT JOIN cuentasbancarias cb ON dd.idCuentaBancaria = cb.id
                  WHERE d.id = $idDeposito";
  $resConsulta = mysqli_query($link,$sqlConsulta) or die('No se pudo realizar la verificación de su factura, por favor Notifique a su Administrador'.mysqli_error($link));
  $filas = mysqli_num_rows($resConsulta);
  switch ($estatus) {
    case '1':
      $icono = 'fas fa-check';
      $status = 'Aceptada';
      $color = 'success';
      break;

    case '2':
      $icono = 'far fa-clock';
      $status = 'Esperando Aceptación';
      $color = 'warning';
      break;

    case '3':
      $icono = 'fas fa-times';
      $status = 'Rechazada';
      $color = 'danger';
      break;

    default:
      $icono = 'fas fa-exclamation-triangle';
      $status = 'Notifica al Admin.';
      $color = 'danger';
      break;
    }
    $btnAutoriza = ($idNivel == '3' || $idNivel == '4' || $idNivel == '7') ? '<button type="button" onClick="autorizaDeposito('.$idDeposito.',3);" class="btn btn-danger">Rechazar</button><button type="button" onClick="autorizaDeposito('.$idDeposito.',1);" class="btn btn-success">Autorizar</button>' : '';
    $nptMotivo = ($idNivel == '3' || $idNivel == '4' || $idNivel == '7') ? '<hr><div class="row"><label class="control-label text-info">Motivo:</label><input type="text" class="form-control" id="motivo" placeholder="Ingresa un motivo en caso de Rechazo"></div><br>' : '';
?>
    <div class="modal-header bg-<?=$pyme;?>">
      <h4 class="modal-title text-white" id="lblDetallaCorte"><?=$nomSucursal;?>, Corte <?=$idCorte;?>, Monto: $<?=number_format($total,2,'.',',');?></h4>
      <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
<?php
if ($filas < 2) {
  $row = mysqli_fetch_array($resConsulta);
  echo '<div class="row">
          <center>
            <img class="img-fluid" src="../'.$row['docto'].'">
          </center>
        </div>
        <div class="row">
          <div class="col-md-9">
            <br><label class="control-label text-success">Fecha de Depósito: &nbsp;&nbsp;</label><label class="control-label">'.$row['fechaDeposito'].'</label>
            <br><label class="control-label text-success">Banco: &nbsp;&nbsp;</label><label class="control-label">'.$row['nombreCorto'].'</label>
            <br><label class="control-label text-success">Cuenta: &nbsp;&nbsp;</label><label class="control-label">'.$row['noCuenta'].'</label>
            <br><label class="control-label text-success">Folio del Ticket: &nbsp;&nbsp;</label><label class="control-label">'.$row['folio'].'</label>
            <br><label class="control-label text-success">Monto: &nbsp;&nbsp;</label><label class="control-label">$ '.number_format($row['monto'],2,'.',',').'</label>
            <br><label class="control-label text-success">Recolector: &nbsp;&nbsp;</label><label class="control-label">'.$row['nomRecolector'].'</label>
          </div>
          <div class="col-md-3 text-center">
            <br><br>
            <b class="text-'.$color.'">'.$status.'</b>
            <br>
            <a class="btn btn-circle btn-'.$color.'"><i class="'.$icono.' text-white"></i></a>
          </div>
        </div>
        '.$nptMotivo.'
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
          '.$btnAutoriza.'
        </div>';
} else {
  $contador = 0;
   echo '<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">';
           mysqli_data_seek($resConsulta,0);

while ($dato = mysqli_fetch_array($resConsulta)) {
  $active = ($contador == 0) ? 'active' : '' ;

      echo '
            <div class="carousel-item '.$active.'">
                <div class="row">
                  <center>
                    <img class="img-fluid" src="../'.$dato['docto'].'">
                  </center>
                </div>
                <div class="row">
                  <div class="col-md-9">
                    <br><label class="control-label text-success">Fecha de Depósito: &nbsp;&nbsp;</label><label class="control-label">'.$dato['fechaDeposito'].'</label>
                    <br><label class="control-label text-success">Banco: &nbsp;&nbsp;</label><label class="control-label">'.$dato['nombreCorto'].'</label>
                    <br><label class="control-label text-success">Cuenta: &nbsp;&nbsp;</label><label class="control-label">'.$dato['noCuenta'].'</label>
                    <br><label class="control-label text-success">Folio del Ticket: &nbsp;&nbsp;</label><label class="control-label">'.$dato['folio'].'</label>
                    <br><label class="control-label text-success">Monto: &nbsp;&nbsp;</label><label class="control-label">$ '.number_format($dato['monto'],2,'.',',').'</label>
                    <br><label class="control-label text-success">Recolector: &nbsp;&nbsp;</label><label class="control-label">'.$dato['nomRecolector'].'</label>
                  </div>
                  <div class="col-md-3 text-center">
                    <br><br>
                    <b class="text-'.$color.'">'.$status.'</b>
                    <br>
                    <a class="btn btn-circle btn-'.$color.'"><i class="'.$icono.' text-white"></i></a>
                  </div>
                </div>
            </div>';

            $contador++;

  }
     echo ' </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
        </div>
        '.$nptMotivo.'
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
          '.$btnAutoriza.'
        </div>';
}
 ?>

    </div>

<?php
}
?>
<script>
function muestraCuentas(id,no){
  $.post("../funciones/muestraDatosBnk.php",
{idBnk:id},
function(respuesta){
var resp = respuesta.split('|');
if (resp[0] == 1) {
  $("#muestraNoCuenta"+no).html(resp[1]);
} else {
  $("#muestraNoCuenta"+no).html('');
}
});
}

var statSend = false;
function checkSubmit() {
    if (!statSend) {
        statSend = true;
        return true;
    } else {
        alert("Se está enviendo la información, por favor espere...");
        return false;
    }
}

    function mascaraMonto(o,f){
      v_obj=o;
      v_fun=f;
      setTimeout("execmascaraMonto()",1);
    }
    function execmascaraMonto(){
      v_obj.value=v_fun(v_obj.value);
    }
    function Monto(v){
      v=v.replace(/([^0-9\.]+)/g,'');                                              // Acepta solo números y el punto.
      v=v.replace(/^[\.]/,'');                                                     // Quita punto al inicio.
      v=v.replace(/[\.][\.]/g,'');                                                 // Elimina dos puntos juntos.
      v=v.replace(/\.(\d)(\d)(\d)/g,'.$1$2');                                      // Si encuentra el patrón .123 lo cambia por .12.
      v=v.replace(/\.(\d{1,2})\./g,'.$1');                                         // Si encuentra el patrón .1. o .12. lo cambia por .1 o .12.
      v = v.toString().split('').reverse().join('').replace(/(\d{3})/g,'$1,');     // Pone la cadena al revés Si encuentra el patrón 123 lo cambia por 123,.
      v = v.split('').reverse().join('').replace(/^[\,]/,'');                      // Si inicia con una coma la reemplaza por nada.
      return v;                                                                    // si le colocamos "v+d" en lugar de "v" nos da decimales ilimitados
    }
</script>
