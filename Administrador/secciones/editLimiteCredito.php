<?php
$idCliente = $_SESSION['idCliente'];
unset($_SESSION['idCliente']);
$sql = "SELECT credito,limiteCredito FROM clientes WHERE id = '$idCliente' LIMIT 1";
$res = mysqli_query($link,$sql) or die('Porblemas al consultar el crédito del cliente, notifica a tu Administrador.');
$dat = mysqli_fetch_array($res);
$credito = $dat['credito'];
$limiteCredito = $dat['limiteCredito'];
echo '<div class="row">
    <div class="col-md-6" id="divEdCredito">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-question"></i></span>
        </div>
            <select class="form-control" id="eCredito" name="eCredito" onchange="selCredito(this.value,2);">';

              if ($credito == 1) {
                $sel1 = 'selected';
                $sel2 = '';
                $ver = '';
              } elseif ($credito == 2) {
                $sel1 = '';
                $sel2 = 'selected';
                $ver = 'style="display:none;"';
              } else {
                $sel1 = '';
                $sel2 = '';
                $ver = 'style="display:none;"';
              }
        echo '<option value="">Selecciona una Opción</option>
              <option value="1" '.$sel1.'>Sí</option>
              <option value="2" '.$sel2.'>No</option>';
      echo '</select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6" id="divEdLimite" '.$ver.'>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-cash-usd"></i></span>
        </div>
            <input type="number" min="0" placeholder="Ingresa el Monto" class="form-control" value="'.$limiteCredito.'" id="eLimite" name="eLimite">
        </div>
    </div>
    <!--/span-->
</div>';

 ?>
