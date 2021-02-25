<?php
function detalladoDeCortes($funcionIdCorte,$link){
  $debug=0;
  ?>
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div id="validation" class="m-t-40 jsgrid" style="position: relative; height: auto; width: 100%;">
              <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable" id="zero_config2">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Ticket</th>
                      <th class="text-center">Fecha y Hora</th>
                      <th>Cliente</th>
                      <th class="text-center">Total</th>
                      <th class="text-center">Estado</th>
                      <th class="text-center">Cajero</th>
                      <th class="text-center">Desgloce</th>
                      <th class="text-center">FACT</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                  #/*
                      $sql = "SELECT v.idSucursal,v.id AS idVenta, v.fechaReg, cli.nombre AS nomCliente, v.total,cd.estatus AS estadoCredito, v.estatus AS estatusVenta,
                                CONCAT(u.nombre,' ',u.appat,' ',u.apmat) AS nomCajero, d.id AS devolucion
                                FROM ventas v
                                INNER JOIN segusuarios u ON v.idUserReg = u.id
                                LEFT JOIN devoluciones d ON v.id = d.idVenta
                                LEFT JOIN cortes c ON v.idCorte = c.id
                                LEFT JOIN clientes cli ON v.idCliente = cli.id
                                LEFT JOIN creditos cd ON v.id = cd.idVenta
                                WHERE v.idCorte = $funcionIdCorte
                                ORDER BY v.fechaReg ASC";
                      echo '$sqlVnt: '.$sql;

                    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Extraer Corte, notifica a tu Administrador', mysqli_error($link)));

                      $cont = $idventa = 0;
                      $estado = $tipoPagos = $color = $ids = '';
                      $cant = mysqli_num_rows($resultXquery);
                    if ($cant > 0) {
                      while ($vnt = mysqli_fetch_array($resultXquery)) {
                        $idSucursal = $vnt['idSucursal'];
                        $idventa = $vnt['idVenta'];
                        if ($vnt['devolucion'] > 0) {
                          $dev = '(Dev # '.$vnt['devolucion'].')';
                        } else {
                          $dev = '';
                        }

                        ++$cont;
                        switch ($vnt['estatusVenta']) {
                          case '1':
                            $estado = 'Abierta';
                            $color = '';
                            break;
                          case '2':
                            $estado = 'Pagada';
                            $color = '';
                            break;
                          case '3':
                            $estado = 'Cancelada';
                            $color = 'class="table-danger"';
                            break;
                          default:
                            $estado = 'Cancelada en 0';
                            $color = 'class="table-danger"';
                            break;
                        }
                        if ($vnt['estatusVenta'] == 2 AND $vnt['estadoCredito'] == 1) {
                          $estado = 'Crédito';
                          $color = 'class="table-warning"';
                        }
                        $sqlConFact = "SELECT IF(ISNULL(vf.id),0,COUNT(vf.id)) AS facturada, IF(ISNULL(fg.idCancelada),0,COUNT(vf.id)) AS facturaCancelada
                                        FROM vtasfact vf
                                        INNER JOIN facturasgeneradas fg ON vf.idFactgen = fg.id
                                        WHERE vf.idVenta = '$idventa'
                                        GROUP BY vf.idVenta LIMIT 1";
                        $resConFact = mysqli_query($link,$sqlConFact) or die('Problemas al consultar las facturas, notifica a tu Administrador.');
                        $dat = mysqli_fetch_array($resConFact);

                        if ($dat['facturaCancelada'] < $dat['facturada']) {
                          $btnFacturar = '<button type="button" class="btn btn-success btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta('.$idventa.')" id="btn-'.$idventa.'"><i class="fas fa-download"></i></button></button>';
                        } elseif ($dat['facturaCancelada'] > $dat['facturada']) {
                          $btnFacturar = '<button type="button" class="btn btn-warning btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta('.$idventa.')" id="btn-'.$idventa.'"><i class="fas fa-copy"></i></button></button>';
                        } else {
                          $btnFacturar = '<button type="button" class="btn btn-info btn-circle muestraSombra" title="Facturar Venta" data-toggle="modal" data-target="#modalFacturaVenta" onClick="modalFacturaVenta('.$idventa.')" id="btn-'.$idventa.'"><i class="fas fa-copy"></i></button></button>';
                        }
                          $ids .= $idventa.',';
                        echo '<tr '.$color.'>
                                <td class="text-center">'.$cont.' '.$dev.'</td>
                                <td class="text-center">'.$idventa.'</td>
                                <td class="text-center">'.$vnt['fechaReg'].'</td>
                                <td>'.$vnt['nomCliente'].'</td>
                                <td class="text-right">$ '.number_format($vnt['total'],2,'.',',').'</td>
                                <td class="text-center">'.$estado.'</td>
                                <td class="text-center">'.$vnt['nomCajero'].'</td>
                                <td class="text-center">
                                  <button type="button" class="btn btn-info btn-circle muestraSombra" title="Mostrar Desgloce" data-toggle="modal" data-target="#modalDesgloceVenta" onClick="muestraDesgloce('.$idventa.')"><i class="fas fa-bars"></i></button></button>
                                </td>
                                <td class="text-center">
                                  '.$btnFacturar.'
                                </td>
                              </tr>';

                      }
                    }
                      #*/
                      $ids = trim($ids,',');
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
                                  WHERE g.idSucursal = '$idSucursal' AND g.idCorte >= 0 AND IF(g.idCorte > 0, c.estatus = 1, 1=1)
                                ) b ON v.idSucursal = b.idSucursal
                                WHERE v.idCorte = '$funcionIdCorte'";

                $resTotales = mysqli_query($link,$sqlTotales) or die('Problemas al consultar los totales del día, notifica a tu Administrador.');
                #echo '<br>$sqlTotales: '.$sqlTotales;
                $tot = mysqli_fetch_array($resTotales);
              $tarjetas = $tot['TarjetaD'] + $tot['TarjetaC'];
              #/*
              if ($tot['Efectivo'] > 0) {
                  echo '<div class="col-md-3">
                          <div class="card card-hover">
                            <div class="box bg-cyan text-center">
                              <b><h4 class="font-light text-white">$ '.number_format($tot['Efectivo'],2,'.',',').'</h4></b>
                              <h6 class="text-white">Total Efectivo</h6>
                            </div>
                          </div>
                        </div>';
                      }
                if ($tot['efectivoCredito'] > 0) {
                    echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-warning text-center">
                                <b><h4 class="font-light text-white">$ '.number_format($tot['efectivoCredito'],2,'.',',').'</h4></b>
                                <h6 class="text-white">Pago de Créditos en Efectivo</h6>
                              </div>
                            </div>
                          </div>';
                        }
              if ($tot['Cheques'] > 0) {
                  echo '<div class="col-md-3">
                          <div class="card card-hover">
                            <div class="box bg-purple text-center">
                              <b><h4 class="font-light text-white">$ '.number_format($tot['Cheques'],2,'.',',').'</h4></b>
                              <h6 class="text-white">Total Cheques</h6>
                            </div>
                          </div>
                        </div>';
                      }
              if ($tot['Transferencias'] > 0) {
                  echo '<div class="col-md-3">
                          <div class="card card-hover">
                            <div class="box bg-inverse text-center">
                              <b><h4 class="font-light text-white">$ '.number_format($tot['Transferencias'],2,'.',',').'</h4></b>
                              <h6 class="text-white">Total Transferencias</h6>
                            </div>
                          </div>
                        </div>';
                      }
              if ($tarjetas > 0) {
                    echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-info text-center">
                                <b><h4 class="font-light text-white">$ '.number_format($tarjetas,2,'.',',').'</h4></b>
                                <h6 class="text-white">Total Tarjetas</h6>
                              </div>
                            </div>
                          </div>';
                        }
                if ($tot['Boletas'] > 0) {
                    echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-orange text-center">
                                <b><h4 class="font-light text-white">$ '.number_format($tot['Boletas'],2,'.',',').'</h4></b>
                                <h6 class="text-white">Total Boletas</h6>
                              </div>
                            </div>
                          </div>';
                        }
                if ($tot['Creditos'] > 0) {
                    echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-primary text-center">
                                <b><h4 class="font-light text-white">$ '.number_format($tot['Creditos'],2,'.',',').'</h4></b>
                                <h6 class="text-white">Total Creditos</h6>
                              </div>
                            </div>
                          </div>';
                        }
                if ($tot['Devoluciones'] > 0) {
                    echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-warning text-center">
                                <b><h4 class="font-light text-white">$ '.number_format($tot['Devoluciones'],2,'.',',').'</h4></b>
                                <h6 class="text-white">Total Devoluciones</h6>
                              </div>
                            </div>
                          </div>';
                        }
                if ($tot['Gastos'] > 0) {
                    echo '<div class="col-md-3">
                            <div class="card card-hover">
                              <div class="box bg-danger text-center">
                                <b><h4 class="font-light text-white">$ '.number_format($tot['Gastos'],2,'.',',').'</h4></b>
                                <h6 class="text-white">Total Gastos</h6>
                              </div>
                            </div>
                          </div>';
                        }

                        $total = $tot['Efectivo'] + $tot['Cheques'] + $tot['Transferencias'] + $tarjetas + $tot['Boletas'] + $tot['Creditos'] - $tot['Devoluciones'] - $tot['Gastos'];


                    #*/

                  }
              #  echo '<br><br>$ids: '.$ids;
               ?>
               <div class="col-md-12">
                 <div class="row">
                   <div class="col-md-4">
                   </div>
                    <div class="col-md-4">
                      <div class="card card-hover">
                        <div class="box bg-success text-center">
                          <h4 class="font-light text-white">$ <?=number_format($total,2,'.',',');?></h4>
                          <h6 class="text-white">Total Ventas</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>
<?php
}
 ?>
