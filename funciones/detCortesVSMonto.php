<?php
function detalladoDeCortes($funcionIdCorte, $link)
{
?>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
            <div class="table-responsive">
              <table class="table table-bordered footer_callback" data-toggle-column="first" id="">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Descripción</th>
                    <th>Monto Captado</th>
                    <th class="text-center">Monto en Cortes</th>
                    <th class="text-center">Monto Facturado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  #/*
                  $sqlVnt = "SELECT s_fpgo.nombre, totalVtas, totalCte, totalFact FROM sat_formapago s_fpgo 
                  LEFT  JOIN (SELECT
                    s_fp.id,
                    s_fp.nombre,
                    SUM(pgo.monto) AS totalVtas
                  FROM
                  ventas vta
                  INNER JOIN cortes cte  ON vta.idCorte=cte.id AND cte.id='$funcionIdCorte'
                  INNER JOIN pagosventas pgo ON pgo.idVenta = vta.id
                  LEFT JOIN sat_formapago s_fp ON s_fp.id=pgo.idFormaPago 
                  WHERE vta.estatus='2'
                  GROUP BY s_fp.id) totalesVentas ON totalesVentas.id=s_fpgo.id
                  LEFT JOIN
                  (SELECT
                    s_fp.id, 
                    s_fp.nombre,
                    SUM(dtcte.monto) AS totalCte
                  FROM
                  cortes cte
                  INNER JOIN detcortes dtcte ON dtcte.idCorte = cte.id AND cte.id='$funcionIdCorte'
                  LEFT JOIN sat_formapago s_fp ON s_fp.id=dtcte.idFormaPago 
                  WHERE dtcte.tipo='1'
                  GROUP BY s_fp.id) totalesCorte  ON totalesCorte.id=s_fpgo.id
                  LEFT JOIN 
                  (SELECT 
                   s_fp.id,
                    s_fp.nombre,
                    SUM(fact.monto) AS totalFact
                  FROM ventas vta
                  INNER JOIN vtasfact vf ON vf.idVenta=vta.id
                  INNER JOIN facturasgeneradas fg ON fg.id=vf.idFactgen
                  INNER JOIN facturas fact ON fact.uuid=fg.uuidFact
                  LEFT JOIN sat_formapago s_fp ON s_fp.clave=fact.formaPago 
                  WHERE vta.idCorte='$funcionIdCorte' AND fact.metodoPago='PUE'
                  GROUP BY s_fp.id) totalesFact ON totalesFact.id=s_fpgo.id
                  GROUP BY  s_fpgo.id
                  
                  
                  ";
                  $resVnt = mysqli_query($link, $sqlVnt) or die('Problemas al consultar los créditos pendientes, notifica a tu Administrador.');
                  $cont = $idventa = 0;
                  $estado = $tipoPagos = $color = $ids = '';
                  $cant = mysqli_num_rows($resVnt);
                  $contador=1;
                  $sumVentas=0;
                  $sumCortes=0;
                  $sumFact=0;
                  if ($cant > 0) {
                    while ($vnt = mysqli_fetch_array($resVnt)) {
                          $sumCortes+=$vnt['totalCte'];
                          $sumVentas+=$vnt['totalVtas'];
                          $sumFact+=$vnt['totalFact'];
                      echo '<tr ' . $color . '>
                                <td class="text-center">' . $contador. '</td>
                                <td class="text-center">' . $vnt['nombre'] . '</td>
                                <td class="text-center">$ ' . number_format($vnt['totalVtas'], 2, '.', ',') . '</td>
                                <td>$ ' . number_format($vnt['totalCte'], 2, '.', ',') . '</td>
                                <td class="text-right">$ ' . number_format($vnt['totalFact'], 2, '.', ',') . '</td>
                               
                              </tr>';
                              $contador ++;
                    }
                  }


            
                  $sql="SELECT 
                  '0' AS id,
                 'Pago Parcial Diferido' AS nombre,
                 SUM(fact.monto) AS totalPPD
                FROM ventas vta
                INNER JOIN vtasfact vf ON vf.idVenta=vta.id
                INNER JOIN facturasgeneradas fg ON fg.id=vf.idFactgen
                INNER JOIN facturas fact ON fact.uuid=fg.uuidFact
                WHERE vta.idCorte='$funcionIdCorte' AND fact.formaPago='99' AND   fact.metodoPago='PPD'
                GROUP BY fact.metodoPago";
                $resPPD = mysqli_query($link, $sql) or die('Problemas al consultar los PPD Facturados, notifica a tu Administrador.');
                $arrayDePPD = mysqli_fetch_array($resPPD);
                $nombre ='Pago Parcial Diferido' ;
                echo '<tr  class="table-warning">
                <td class="text-center"></td>
                <td class="text-center">' . $nombre . '</td>
                <td class="text-center">$ 0.0</td>
                <td>$ 0.0</td>
                <td class="text-right">$ ' . number_format($arrayDePPD['totalPPD'], 2, '.', ',') . '</td>
               
              </tr>';
              $sumFact+=$arrayDePPD['totalPPD'];







                  echo '<tfoot>
                  <tr>
                      <th colspan="2" style="text-align:right">Total:</th>
                      <th class="text-center">$'.number_format($sumVentas, 2, '.', ',').'</th>
                      <th class="text-center">$'.number_format($sumCortes, 2, '.', ',').'</th>
                      <th class="text-center">$'.number_format($sumFact, 2, '.', ',').'</th>
                     
                      
                  </tr>
              </tfoot>';
                  #*/
                  $ids = trim($ids, ',');
                  ?>

                </tbody>
              </table>
            </div>
          </div>
          <br>
          <div class="row">
            <?php
            $totTotal = $total = $totGastos = $totDev = 0;
            #/*
            if ($cant > 0) {
              $sqlTotales = "SELECT SUM(CASE pv.idFormaPago WHEN '1' THEN pv.monto ELSE 0 END) AS Efectivo,
                                SUM(CASE pv.idFormaPago WHEN '2' THEN pv.monto ELSE 0 END) AS Cheques,
                                SUM(CASE pv.idFormaPago WHEN '3' THEN pv.monto ELSE 0 END) AS Transferencias,
                                SUM(CASE pv.idFormaPago WHEN '4' THEN pv.monto ELSE 0 END) AS TarjetaD,
                                SUM(CASE pv.idFormaPago WHEN '5' THEN pv.monto ELSE 0 END) AS TarjetaC,
                                SUM(CASE pv.idFormaPago WHEN '6' THEN pv.monto ELSE 0 END) AS Boletas,
                                SUM(CASE pv.idFormaPago WHEN '7' THEN pv.monto ELSE 0 END) AS Creditos,
																IF(dv.cantCancel>0,SUM(dv.precioVenta * dv.cantCancel),0) AS Devoluciones,
                                IF(b.Gastos > 0, b.Gastos, 0) AS Gastos, IF(a.efectivoCred > 0, a.efectivoCred,0) AS efectivoCredito
                                FROM ventas v
                                INNER JOIN pagosventas pv ON v.id = pv.idVenta
																LEFT JOIN (
																	SELECT idCorte, SUM(monto) AS efectivoCred FROM pagoscreditos WHERE idCorte = '$funcionIdCorte' AND idFormaPago = '1'
																	) a ON a.idCorte = v.idCorte
                                LEFT JOIN detventas dv ON v.id = dv.id
                                LEFT JOIN (
                                  SELECT SUM(g.monto) AS Gastos, g.idSucursal
                                  FROM gastos g LEFT JOIN cortes c ON g.idCorte = c.id
                                  WHERE g.idSucursal = '1' AND g.idCorte >= 0 AND IF(g.idCorte > 0, c.estatus = 1, 1=1)
                                ) b ON v.idSucursal = b.idSucursal
                                WHERE v.idCorte = '$funcionIdCorte'";

              $resTotales = mysqli_query($link, $sqlTotales) or die('Problemas al consultar los totales del día, notifica a tu Administrador.');
              #echo '<br>$sqlTotales: '.$sqlTotales;
              $tot = mysqli_fetch_array($resTotales);
              $tarjetas = $tot['TarjetaD'] + $tot['TarjetaC'];
              #/*
            /*  if ($tot['Efectivo'] > 0) {
                echo '<div class="col-md-3">
                          <div class="card card-hover">
                            <div class="box bg-cyan text-center">
                              <b><h4 class="font-light text-white">$ ' . number_format($tot['Efectivo'], 2, '.', ',') . '</h4></b>
                              <h6 class="text-white">Total Efectivo</h6>
                            </div>
                          </div>
                        </div>';
              }
              if ($tot['efectivoCredito'] > 0) {
                echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-warning text-center">
                                <b><h4 class="font-light text-white">$ ' . number_format($tot['efectivoCredito'], 2, '.', ',') . '</h4></b>
                                <h6 class="text-white">Pago de Créditos en Efectivo</h6>
                              </div>
                            </div>
                          </div>';
              }
              if ($tot['Cheques'] > 0) {
                echo '<div class="col-md-3">
                          <div class="card card-hover">
                            <div class="box bg-purple text-center">
                              <b><h4 class="font-light text-white">$ ' . number_format($tot['Cheques'], 2, '.', ',') . '</h4></b>
                              <h6 class="text-white">Total Cheques</h6>
                            </div>
                          </div>
                        </div>';
              }
              if ($tot['Transferencias'] > 0) {
                echo '<div class="col-md-3">
                          <div class="card card-hover">
                            <div class="box bg-inverse text-center">
                              <b><h4 class="font-light text-white">$ ' . number_format($tot['Transferencias'], 2, '.', ',') . '</h4></b>
                              <h6 class="text-white">Total Transferencias</h6>
                            </div>
                          </div>
                        </div>';
              }
              if ($tarjetas > 0) {
                echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-info text-center">
                                <b><h4 class="font-light text-white">$ ' . number_format($tarjetas, 2, '.', ',') . '</h4></b>
                                <h6 class="text-white">Total Tarjetas</h6>
                              </div>
                            </div>
                          </div>';
              }
              if ($tot['Boletas'] > 0) {
                echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-orange text-center">
                                <b><h4 class="font-light text-white">$ ' . number_format($tot['Boletas'], 2, '.', ',') . '</h4></b>
                                <h6 class="text-white">Total Boletas</h6>
                              </div>
                            </div>
                          </div>';
              }
              if ($tot['Creditos'] > 0) {
                echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-primary text-center">
                                <b><h4 class="font-light text-white">$ ' . number_format($tot['Creditos'], 2, '.', ',') . '</h4></b>
                                <h6 class="text-white">Total Creditos</h6>
                              </div>
                            </div>
                          </div>';
              }
              if ($tot['Devoluciones'] > 0) {
                echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-warning text-center">
                                <b><h4 class="font-light text-white">$ ' . number_format($tot['Devoluciones'], 2, '.', ',') . '</h4></b>
                                <h6 class="text-white">Total Devoluciones</h6>
                              </div>
                            </div>
                          </div>';
              }
              if ($tot['Gastos'] > 0) {
                echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-danger text-center">
                                <b><h4 class="font-light text-white">$ ' . number_format($tot['Gastos'], 2, '.', ',') . '</h4></b>
                                <h6 class="text-white">Total Gastos</h6>
                              </div>
                            </div>
                          </div>';
              }

              $total = $tot['Efectivo'] + $tot['Cheques'] + $tot['Transferencias'] + $tarjetas + $tot['Boletas'] + $tot['Creditos'] - $tot['Devoluciones'] - $tot['Gastos'];
               */

              #*/

            }
            #  echo '<br><br>$ids: '.$ids;
            ?>
            <!---<div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                  <div class="card card-hover">
                    <div class="box bg-success text-center">
                      <h4 class="font-light text-white">$ <?= number_format($total, 2, '.', ','); ?></h4>
                      <h6 class="text-white">Total Ventas</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>--->
          </div>

        </div>
      </div>
    </div>
  </div>
<?php
}
?>