<?php
echo '<div class="row">
    <div class="col-md-6" id="divNewCredito">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-question"></i></span>
        </div>
            <select class="form-control" id="newCredito" name="newCredito" onchange="selCredito(this.value,1);">
                <option value="">¿Tendrá Crédito?</option>
                <option value="1">Sí</option>
                <option value="2">No</option>
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6" id="divNewLimite" style="display:none;">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-cash-usd"></i></span>
          </div>
            <input type="number" min="0" placeholder="Ingresa el Monto" class="form-control" id="newLimite" name="newLimite">
        </div>
    </div>
    <!--/span-->
</div>';
 ?>
