<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once('../include/connect.php');


function hacerComplemento($ids_PagoCredito, $datosClientes, $link)
{
  //---------------------------INICIA PROCESO TRANSACCIONAL----------------------------//
  mysqli_autocommit($link, FALSE);
  mysqli_begin_transaction($link);
  $userReg = $_SESSION['LZFident'];

 
    $datosFacturables = $datosClientes; //uid, apiKey, secretKey, rfcRe, correo
    $fechaPago = strftime("%Y-%m-%dT%H:%I:%S"); //fechaDePAgo
    //--------------------------Buscar API KEY-----------------------------------------//
    $sql='SELECT
    dp.apiKey,
    dp.secretKey 
  FROM
    datospacs dp
    INNER JOIN datosfisc df ON dp.idEmpresa =df.idEmpresa AND df.uid="'.$datosFacturables['uid'].'"';
    $resultXApi = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error Al consultar API KEY, notifica a tu administrador", 'idsErroneos' => mysqli_error($link))));
    $arrayApi=mysqli_fetch_array($resultXApi);
    $apiKey=$arrayApi["apiKey"];
    $secretKey=$arrayApi["secretKey"];
    //---------------------------DATOS DE LOS MONTOS PAGADOS----------------------------//
    $ArrayIdsPagoCredito = array();
    $ArrayIdsPagoCredito = array_merge($ArrayIdsPagoCredito, $ids_PagoCredito);
    $busqPagoCredito = implode(',', $ArrayIdsPagoCredito);
    $sql = "SELECT
    pc.adeudo AS monto,
    pc.monto AS ImpPagado,
    pc.residual AS ImpSaldoInsoluto,
    s_fp.clave,
    fg.uuidFact,
    suc.idSerieComPgo,
    suc.serieComPgo,
    fact.folio,
    fact.serie,
    dc.numParcialidad,
    emp.rfc AS rfcEm,
    emp.nombre AS  empresa,
    suc.nombre AS sucursal,
    vta.id AS idVenta
    
  FROM
    pagoscreditos pc
    INNER JOIN sat_formapago s_fp ON s_fp.id = pc.idFormaPago
    INNER JOIN creditos cr ON cr.id = pc.idCredito 
    INNER JOIN vtasfact vf ON vf.idVenta=cr.idVenta
    INNER JOIN facturasgeneradas fg ON fg.id=vf.idFactgen
    INNER JOIN facturas fact ON fact.uuid=fg.uuidFact AND fact.estatus!='3'
    INNER JOIN ventas vta ON vta.id=cr.idVenta
    INNER JOIN sucursales suc ON suc.id=vta.idSucursal
    INNER JOIN empresas emp ON emp.id=suc.idEmpresa
    LEFT JOIN detcomplementos dc ON dc.uuidFact=fg.uuidFact
  WHERE
  pc.id IN ('$busqPagoCredito')";
    $resultXPgos = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Recuperar de Pagos Para Complementos, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
    $count = 1;
    $complementos = array();
    $detComplementos = array();
    $idSerieComPgo = '';
    $empresa = "";
    $sucursal = "";
    $rfcEm = "";
    $busqVentas = "";
    if (mysqli_num_rows($resultXPgos) == 0) {
      mysqli_rollback($link);
      $resp = array('estatus' => '0', 'mensaje' => 'No hay Factura vinculada a la venta', 'idsErroneos' => json_encode(array('Factura Primero la Venta.')));
      return $resp;
    } else {
      while ($dat = mysqli_fetch_array($resultXPgos)) {
        $idSerieComPgo = $dat["idSerieComPgo"];
        $empresa = $dat["empresa"];
        $sucursal = $dat["sucursal"];
        $rfcEm = $dat["rfcEm"];
        $busqVentas = $dat["idVenta"];
        if ($count == 1) {
          $importeAnt = $dat["monto"];
        }
        $NumParcialidad = $dat["numParcialidad"] + 1;

        $relacionado = array(
          "IdDocumento" => $dat["uuidFact"],
          "ImpSaldoAnt" => $importeAnt, //falta
          "ImpPagado" => $dat["ImpPagado"],
          "ImpSaldoInsoluto" => $dat["ImpSaldoInsoluto"],   //datosFact - monto
          "MetodoDePagoDR" => "PUE",
          "MonedaDR" => "MXN",
          "TipoCambioDR" => 0,
          "NumParcialidad" => $NumParcialidad,      //NO VA
          "Folio" => $dat["folio"],        //datosFact
          "Serie" => $dat["serie"]      //datosFact

        );
        array_push($detComplementos, $relacionado);
        $complemento = array(
          "typeComplement" => "pagos", //CONSTANTE
          "FechaPago" => $fechaPago, //ya
          "FormaDePagoP" => $dat["clave"], //YA
          "MonedaP" => "MXN",  //YA
          "Monto" => $dat["monto"],  //YA
          "relacionados" => [
            $relacionado
          ]

        );
        array_push($complementos, $complemento);
        $count++;
        $importeAnt = $dat["ImpSaldoInsoluto"];
      }
    }
    //print_r($complementos);
    //------------------------------------INTERACCION CON LA API--------------------------------------------------
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v3/cfdi33/complemento/pagos/create");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      "Receptor" => [
        "UID" => $datosFacturables["uid"]
      ],
      "TipoDocumento" => "pago",
      "UsoCFDI" => "P01",
      "Redondeo" => "2",
      "FormaPago" => "99",
      "MetodoPago" => "PUE",
      "Moneda" => "XXX",
      "Serie" => $idSerieComPgo,
      "Conceptos" => [[
        "ClaveProdServ" => "84111506", //CONSTANTE
        "Cantidad" => "1",  //CONSTANTE
        "ClaveUnidad" => "ACT", //CONSTANTE
        "Descripcion" => "Pago", //CONSTANTE
        "ValorUnitario" => "0", //CONSTANTE
        "Complemento" =>
        $complementos

      ]],
    ]));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
      "F-Api-Key: " . $apiKey,
      "F-Secret-Key: " . $secretKey
    ));

    $response = curl_exec($ch);
    // print_r($response);
    curl_close($ch);
    $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array
    $estatusApi = $JSON_FACTURA_PHP->response; //guardo el estatus*/

    //-----------------------------------------------MANEJO DE RESULTADOS-----------------------------------------------------
    switch ($estatusApi) {
      case 'success':
        //----------------------------------------------Datos Arrojados ---------------------------------------------------------
        $UUID = $JSON_FACTURA_PHP->UUID;
        $mensaje = $JSON_FACTURA_PHP->message;
        $uid = $JSON_FACTURA_PHP->uid;
        $fechaTimbrado = $JSON_FACTURA_PHP->SAT->FechaTimbrado;
        $noCertSat = $JSON_FACTURA_PHP->SAT->NoCertificadoSAT;
        $selloCfd = $JSON_FACTURA_PHP->SAT->SelloSAT;
        $selloSat = $JSON_FACTURA_PHP->SAT->SelloCFD;
        $serie = $JSON_FACTURA_PHP->INV->Serie;
        $folio = $JSON_FACTURA_PHP->INV->Folio;
        $rfcRe = $datosFacturables["rfcRe"];
        $portalCarga = '1';
        $monto = '0';
        $formaPago = "99";
        $metodoPago = "PPD";
        $tipo = 'P';
        //-------------------------------------------------REGISTRO EN COMPLEMENTOS ---------------------------------------
        $sqlInsert = "INSERT INTO complementos (uuid,uid, mensaje, rfcEm, rfcRe, monto, portalCarga, fechaTimbrado, 
   noCertSat, selloSat, sellocfd, serie, folio, fechaReg, estatus, idUserReg, tipo, formaPago, metodoPago) VALUES ('$UUID','$uid','$mensaje', '$rfcEm','$rfcRe','$monto','$portalCarga', '$fechaTimbrado',
   '$noCertSat','$selloSat','$selloCfd', '$serie','$folio',NOW(),'1','$userReg','$tipo', '$formaPago', '$metodoPago')";
        $resultXComp = mysqli_query($link, $sqlInsert) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Guardar Datos del Complemento, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
        $idComponente = mysqli_insert_id($link);
        ///print_r($detComplementos);
        //-------------------------------------------------REGISTRO EN  DETCOMPLEMNTOS ----------------------------------
        for ($i = 0; $i < count($detComplementos); $i++) {
          $idCredito =  $ArrayIdsPagoCredito[$i];
          $metpago = $detComplementos[$i]["MetodoDePagoDR"];
          $moneda = $detComplementos[$i]["MonedaDR"];
          $NumParcialidad = $detComplementos[$i]["NumParcialidad"];
          $folioDet = $detComplementos[$i]["Folio"];
          $saldoAnt = $detComplementos[$i]["ImpSaldoAnt"];
          $insoluto = $detComplementos[$i]["ImpSaldoInsoluto"];
          $UUIDDocto = $detComplementos[$i]["IdDocumento"];
          $importe = $detComplementos[$i]["ImpPagado"];
          $sqlInsComp = "INSERT INTO detcomplementos (idComplemento,idPagoCredito, metodoPago, saldoAnterior, saldoInsoluto, uuidFact, moneda, numParcialidad, importePagado, folio) 
    VALUES('$idComponente','$idCredito', '$metpago','$saldoAnt','$insoluto','$UUIDDocto', '$moneda', '$NumParcialidad','$importe', '$folioDet')";
          //print_r($sqlInsComp);
          $resultXDetComp = mysqli_query($link, $sqlInsComp) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Guardar Detalles del Complemento, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
        }
        //-------------------------------------------------GUARDAR XML Y PDF -----------------------------------------
        $anioCoriente = date("Y");
        $numMes = date("m");
        $nameSuc = $sucursal;
        $carpeta = 'FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
        $carpetaOrig = '../FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
        if (!file_exists($carpetaOrig)) {
          mkdir($carpetaOrig, 0755, true);
        }
        $nameFile = $carpetaOrig . $UUID;
        $nameFileSave = $carpeta . $UUID;
        //   ------------------------------------   DESCARGA DE PDF   ---------------------------------
        $ch = curl_init();
        $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/pdf';
        curl_setopt($ch, CURLOPT_URL, "$ruta");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
          "F-API-KEY: " . $apiKey,
          "F-SECRET-KEY: " . $secretKey
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        file_put_contents($nameFile . '.PDF', $response);

        //   ------------------------------------   DESCARGA DE XML   ---------------------------------
        $ch = curl_init();
        $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/xml';
        curl_setopt($ch, CURLOPT_URL, "$ruta");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
          "F-API-KEY: " . $apiKey,
          "F-SECRET-KEY: " . $secretKey
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        file_put_contents($nameFile . '.XML', $response);

        //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
        $filePDF = (file_exists($nameFile . '.PDF')) ? $nameFileSave . '.PDF' : '';
        $fileXML = (file_exists($nameFile . '.XML')) ? $nameFileSave . '.XML' : '';

        #                    echo '<br>* link de PDF: '.$filePDF.'<br>* link de XML: '.$fileXML;

        $sqlUpdateFiles = "UPDATE complementos SET doctoPDF = '$filePDF', doctoXML = '$fileXML' WHERE uuid = '$UUID' ";
        #                    echo '<br>Consulta actualizar doctos: '.$sqlUpdateFiles;
        mysqli_query($link, $sqlUpdateFiles) or die('0|E8.- Tuvimos Problemas al actualizar los Archivos:' . mysqli_error($link) . '<br> ' . $sqlUpdateFiles);

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
          $mail->Username = 'lazafra@lazafra.com.mx';      // SMTP username
          $mail->Password = '@gr0f3rt1l1z@nt3s.L@z@fr@';                     // SMTP password
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 587;                                    // TCP port to connect to
          # echo 'Correos enviados a: ';
          //Recipients
          $mail->setFrom('lazafra@lazafra.com.mx', 'Por Favor no responda este correo.');
          //PRINT_R("este es el correo-->".$correo);
          $mail->addAddress($datosFacturables["correo"]);     // Add a recipient
          // $mail->addBCC('contabilidad@lazafra.com.mx');

          //$mail->addReplyTo('karen.dominguez@rvsetys.com', 'No Responder');

          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'Nuevo Complemento de Pago creado con Folio: ' . $serie . '' . $folio . ' de la Venta: ' . $busqVentas;
          $mail->Body  = ' <h2>Venta con Folio: ' . $busqVentas . '</h2> <br>';
          $mail->Body .= ' <p><b>Complemento de Pago emitido por ' . $empresa . ' en la Sucursal ' . $nameSuc . '.</b><br>';
          $mail->Body .= ' <p>Folio Fiscal: <b>' . $UUID . '</b></p>';
          $mail->Body .= ' <p><b>NOTA:</b><br>
                       Cualquier duda o aclaración, contactese con la sucursal donde realizó su compra.<br>';
          $mensajeFactura = "";
          // $mail->Body  ='la mamada';
          $mail->CharSet = 'UTF-8';
          $archivo = 'Factura_' . $serie . '-' . $folio;

          $mail->addAttachment('../' . $filePDF, $archivo . '.PDF');
          $mail->addAttachment('../' . $fileXML, $archivo . '.XML');

          $mail->send();
          $mensajeFactura .= ' y se envió copia a la sucursal';
        } catch (Exception $e) {
          echo '0|E8b.- ' . $mensajeFactura . ', pero tuvimos Problemas al Mandar el correo a la Sucursal, Notifica a tu Administrador';
        }
        //======================================   FINALIZA CORREO   =====================================================
        $resp = array('estatus' => '1', 'mensaje' => 'Complemento de Pago Emitido correctamente', 'result' => '');
        if (mysqli_commit($link)) {
          //PRINT_R($respFormaPago);
          return $resp;
        } else {
          return array('estatus' => '3', 'mensaje' => "Error al procesar operaciones, notifica a tu Administrador.", 'idsErroneos' => mysqli_error($link));
        }
        break;
      case 'error':
        mysqli_rollback($link);
        $resp = array('estatus' => '0', 'mensaje' => 'Error Al Facturar.', 'idsErroneos' => json_encode($JSON_FACTURA_PHP->message));
        return $resp;
        break;
    }
    //return $resp;



  }




//CARCASA PARA Facturar
function facturar($ids_Ventas, $datosClientes, $link, $portalCarga, $verificarBoleta)
{
 // PRINT_R($datosClientes);
  //PRINT_R('Ids Ventas'.$ids_Ventas);
  // $ids_Ventas=[8,9,19];
  $datosFacturables = array(); //uid, uso de cfdi, metodo de pago, forma de pago
  $idEmpresa = '';
  //PRINT_R($ids_Ventas);
  //---------------------------INICIA PROCESO TRANSACCIONAL----------------------------//
  mysqli_autocommit($link, FALSE);
  mysqli_begin_transaction($link);
  $rfc = $datosClientes['rfc'];
  $resp = validaVentas($ids_Ventas, $link);

  

  
  // PRINT_R("El id es:-->".$idEmpresa);
  if ($resp["estatus"] == '3' || $resp["estatus"] == '0') {
    mysqli_rollback($link);
    return $resp;
  } else if ($resp["estatus"] == '1') {
    $respFactAnt = validaFactAnt($ids_Ventas, $link);
   // print_r("valida Fact:");
   // print_r($respFactAnt);
    $idEmpresa = $resp["result"]['idEmpresa'];
    $datosFacturables = array_merge($datosFacturables, $resp["result"]);
    if ($respFactAnt["estatus"] == '3' || $respFactAnt["estatus"] == '0') {
      mysqli_rollback($link);
      return $respFactAnt;
    } else if ($respFactAnt["estatus"] == '1') {

      $respRegistro = registraCliente($datosClientes, $link, $portalCarga, $idEmpresa);
     //PRINT_R("Datos del Registro Cliente");
     //print_r($respRegistro);
    //PRINT_R("Datos del Cliente");
    //PRINT_R($datosClientes);
      if ($respRegistro["estatus"] == '3' || $respRegistro["estatus"] == '0') {
        mysqli_rollback($link);
        return $respRegistro;
      } else if ($respRegistro["estatus"] == '1') {
       //print_r($respRegistro["result"]);
        $datosFacturables = array_merge($datosFacturables, $respRegistro["result"]);
        $datosFacturables = array_merge($datosFacturables, array("usoCFDI" => $datosClientes["usoCFDI"]));

        $respFormaPago = obtenerDatosFact($ids_Ventas, $rfc, $link, $verificarBoleta);
        //print_r("obtener Datos:".$$respFormaPago);
        if ($respFormaPago["estatus"] == '3' || $respFormaPago["estatus"] == '0') {
          mysqli_rollback($link);
          return $respFormaPago;
        } else if ($respFormaPago["estatus"] == '1') {
          $datosFacturables = array_merge($datosFacturables, $respFormaPago["result"]);  //metodo y forma de pago
         //PRINT_R("Datos Para la Factura ".$datosFacturables);
          $respFacturaFinal = facturarApi($ids_Ventas, $datosFacturables, $link);
         //PRINT_R("Datos de la factura".$respFacturaFinal);
          if ($respFacturaFinal["estatus"] == '3' ||  $respFacturaFinal["estatus"] == '0') {
            mysqli_rollback($link);
      
            return $respFacturaFinal;
          } else if ($respFacturaFinal["estatus"] == '1') {
            if (mysqli_commit($link)) {
              return $respFacturaFinal;
            } else {
              return array('estatus' => '3', 'mensaje' => "Error al procesar operaciones, notifica a tu Administrador.", 'idsErroneos' => mysqli_error($link));
            }
          }
        }
      }
    }
  }
}
function complementosFact($datosFactura, $ids_Ventas, $link)
{
  $userReg = $_SESSION['LZFident'];
  $ArrayIdsVentas = array();
  $ArrayIdsVentas = array_merge($ArrayIdsVentas, $ids_Ventas);
  $busqVentas = implode(',', $ArrayIdsVentas);
  //------------------------------------DATOS DEL COMPLEMENTO DE  PAGO--------------------------------------------------
  $tipoDocto = "pago";
  $usoCFDI = "P01";
  $redondeo = "2";
  $formaPago = "";
  $metodoPago = "";
  $moneda = "MXN";
  $idSerieComPgo = $datosFactura["idSerieComPgo"];
  //------------------------------------DATOS DE LOS DATOS DE FACTURA--------------------------------------------------
  $UID = $datosFactura["UID"];
  $UUID = $datosFactura["UUID"];

  $serieFact = $datosFactura["serie"];
  $folioFact = $datosFactura["folio"];
  $montoAnt = $datosFactura["monto"]; //bien
  $montoResta = $datosFactura["monto"];
  $secretKey = $datosFactura["secretKey"];
  $apiKey = $datosFactura["apiKey"];
  //------------------------------------DATOS DE LOS METODOS DE PAGO--------------------------------------------------
  $sql = "SELECT DISTINCT
      COUNT( vtas.id ) AS totalVtas,
      pgosvta.idFormaPago,
      metpgos.clave AS FormaDePagoP,
      DATE_FORMAT(NOW(),'%Y-%m-%dT%H:%m:%i') AS fechaPago,
      pgosvta.monto
    FROM
      ventas vtas
      INNER JOIN pagosventas pgosvta ON vtas.id = pgosvta.idVenta
      INNER JOIN sat_formapago metpgos ON metpgos.id = pgosvta.idFormaPago  
    WHERE
      vtas.id IN ( $busqVentas ) AND pgosvta.idFormaPago != '7'
    GROUP BY
      pgosvta.idFormaPago";

  $resultXCom = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Recuperar de Ventas por Facturar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
  $complementos = array();
  $detComplementos = array();
  if (mysqli_num_rows($resultXCom) == 0) {
    $resp = array('estatus' => '2', 'mensaje' => 'No hay Complementos de Pago', 'result' => '');
    return $resp;
  } else {
    $saldoAnterior = $datosFactura["monto"];
    while ($dat = mysqli_fetch_array($resultXCom)) {
      // $montoAnt=$montoResta;
      $saldoPendiente = $saldoAnterior - $dat["monto"];

      $relacionado = array(
        "IdDocumento" => $UUID,
        "ImpSaldoAnt" => $saldoAnterior, //falta
        "ImpPagado" => $dat["monto"],
        "ImpSaldoInsoluto" => $saldoPendiente,    //datosFact - monto
        "MetodoDePagoDR" => "PUE",
        "MonedaDR" => "MXN",
        "TipoCambioDR" => 0,
        "NumParcialidad" => "1",      //NO VA
        "Folio" => $folioFact,        //datosFact
        "Serie" => $serieFact       //datosFact

      );
      $saldoAnterior = $saldoPendiente;
      array_push($detComplementos, $relacionado);
      $complemento["relacionados"] = array();
      $complemento = array(
        "typeComplement" => "pagos", //CONSTANTE
        "FechaPago" => $dat["fechaPago"], //ya
        "FormaDePagoP" => $dat["FormaDePagoP"], //YA
        "MonedaP" => "MXN",  //YA
        "Monto" => $dat["monto"],  //YA
        "relacionados" => [
          /*   [
        "IdDocumento" => "4940915c-e467-4fda-83da-255a40c18b4f",
        "ImpSaldoAnt" => $montoAnt, //falta
        "ImpPagado" => $dat["monto"],
        "ImpSaldoInsoluto" => $montoResta,    //datosFact - monto
        "MetodoDePagoDR" => "PUE",
        "MonedaDR" => "MXN",
        "TipoCambioDR"=>0,
        "NumParcialidad" => "1",      //NO VA
        "Folio" => $folioFact,        //datosFact
        "Serie" => $serieFact       //datosFact
        ]*/
          $relacionado
        ]

      );

      //array_push( $complemento["relacionados"], $relacionado);

      array_push($complementos, $complemento);
    }
    //------------------------------------INTERACCION CON LA API--------------------------------------------------
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v3/cfdi33/complemento/pagos/create");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      "Receptor" => [
        "UID" => $UID
      ],
      "TipoDocumento" => "pago",
      "UsoCFDI" => "P01",
      "Redondeo" => "2",
      "FormaPago" => "99",
      "MetodoPago" => "PUE",
      "Moneda" => "XXX",
      "Serie" => $idSerieComPgo,
      "Conceptos" => [[
        "ClaveProdServ" => "84111506", //CONSTANTE
        "Cantidad" => "1",  //CONSTANTE
        "ClaveUnidad" => "ACT", //CONSTANTE
        "Descripcion" => "Pago", //CONSTANTE
        "ValorUnitario" => "0", //CONSTANTE
        "Complemento" =>
        $complementos

      ]],
    ]));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
      "F-Api-Key: " . $apiKey,
      "F-Secret-Key: " . $secretKey
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    //PRINT_R($response);
    $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array
    $estatusApi = $JSON_FACTURA_PHP->response; //guardo el estatus*/

    //-----------------------------------------------MANEJO DE RESULTADOS-----------------------------------------------------
    switch ($estatusApi) {
      case 'success':
        //----------------------------------------------Datos Arrojados ---------------------------------------------------------
        $UUID = $JSON_FACTURA_PHP->UUID;
        $mensaje = $JSON_FACTURA_PHP->message;
        $uid = $JSON_FACTURA_PHP->uid;
        $fechaTimbrado = $JSON_FACTURA_PHP->SAT->FechaTimbrado;
        $noCertSat = $JSON_FACTURA_PHP->SAT->NoCertificadoSAT;
        $selloCfd = $JSON_FACTURA_PHP->SAT->SelloSAT;
        $selloSat = $JSON_FACTURA_PHP->SAT->SelloCFD;
        $serie = $JSON_FACTURA_PHP->INV->Serie;
        $folio = $JSON_FACTURA_PHP->INV->Folio;
        $rfcRe = $datosFactura["rfcRe"];
        $rfcEm = $datosFactura["rfcEm"];
        $portalCarga = '1';
        $monto = '0';
        $formaPago = "99";
        $metodoPago = "PPD";
        $tipo = 'P';
        //-------------------------------------------------REGISTRO EN COMPLEMENTOS ---------------------------------------
        $sql = "INSERT INTO complementos (uuid,uid, mensaje, rfcEm, rfcRe, monto, portalCarga, fechaTimbrado, 
   noCertSat, selloSat, sellocfd, serie, folio, fechaReg, estatus, idUserReg, tipo, formaPago, metodoPago) VALUES ('$UUID','$uid','$mensaje', '$rfcEm','$rfcRe','$monto','$portalCarga', '$fechaTimbrado',
   '$noCertSat','$selloSat','$selloCfd', '$serie','$folio',NOW(),'1','$userReg','$tipo', '$formaPago', '$metodoPago')";
        $resultXComp = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Guardar Datos del Complemento, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
        $idComponente = mysqli_insert_id($link);
        // print_r($detComplementos);
        //-------------------------------------------------REGISTRO EN  DETCOMPLEMNTOS ----------------------------------
        for ($i = 0; $i < count($detComplementos); $i++) {
          $metpago = $detComplementos[$i]["MetodoDePagoDR"];
          $moneda = $detComplementos[$i]["MonedaDR"];
          $NumParcialidad = $detComplementos[$i]["NumParcialidad"];
          $folioDet = $detComplementos[$i]["Folio"];
          $saldoAnt = $detComplementos[$i]["ImpSaldoAnt"];
          $insoluto = $detComplementos[$i]["ImpSaldoInsoluto"];
          $UUIDDocto = $detComplementos[$i]["IdDocumento"];
          $importe = $detComplementos[$i]["ImpPagado"];
          $sql = "INSERT INTO detcomplementos (idComplemento, metodoPago, saldoAnterior, saldoInsoluto, uuidFact, moneda, numParcialidad, importePagado, folio) 
    VALUES('$idComponente', '$metpago','$saldoAnt','$insoluto','$UUIDDocto', '$moneda', '$NumParcialidad','$importe', '$folioDet')";
          $resultXDetComp = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Guardar Detalles del Complemento, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
        }
        //-------------------------------------------------GUARDAR XML Y PDF -----------------------------------------
        $anioCoriente = date("Y");
        $numMes = date("m");
        $empresa = $datosFactura['empresa'];
        $nameSuc = $datosFactura['sucursal'];
        $carpeta = 'FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
        $carpetaOrig = '../FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
        if (!file_exists($carpetaOrig)) {
          mkdir($carpetaOrig, 0755, true);
        }
        $nameFile = $carpetaOrig . $UUID;
        $nameFileSave = $carpeta . $UUID;
        //   ------------------------------------   DESCARGA DE PDF   ---------------------------------
        $ch = curl_init();
        $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/pdf';
        curl_setopt($ch, CURLOPT_URL, "$ruta");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
          "F-API-KEY: " . $datosFactura['apiKey'],
          "F-SECRET-KEY: " . $datosFactura['secretKey']
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        file_put_contents($nameFile . '.PDF', $response);

        //   ------------------------------------   DESCARGA DE XML   ---------------------------------
        $ch = curl_init();
        $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/xml';
        curl_setopt($ch, CURLOPT_URL, "$ruta");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
          "F-API-KEY: " . $datosFactura['apiKey'],
          "F-SECRET-KEY: " . $datosFactura['secretKey']
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        file_put_contents($nameFile . '.XML', $response);

        //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
        $filePDF = (file_exists($nameFile . '.PDF')) ? $nameFileSave . '.PDF' : '';
        $fileXML = (file_exists($nameFile . '.XML')) ? $nameFileSave . '.XML' : '';

        #                    echo '<br>* link de PDF: '.$filePDF.'<br>* link de XML: '.$fileXML;

        $sqlUpdateFiles = "UPDATE complementos SET doctoPDF = '$filePDF', doctoXML = '$fileXML' WHERE uuid = '$UUID' ";
        #                    echo '<br>Consulta actualizar doctos: '.$sqlUpdateFiles;
        mysqli_query($link, $sqlUpdateFiles) or die('0|E8.- Tuvimos Problemas al actualizar los Archivos:' . mysqli_error($link) . '<br> ' . $sqlUpdateFiles);
        $resp = array('estatus' => '1', 'mensaje' => 'Se anexo Componente correctamente', 'result' => [$filePDF, $fileXML]);
        return $resp;
        break;


      case 'error':
        $resp = array('estatus' => '0', 'mensaje' => 'Error Al Facturar.', 'idsErroneos' => json_encode($JSON_FACTURA_PHP->message));
        return $resp;

        break;
    }
  }
}
function facturarApi($ids_Ventas, $datosFacturables, $link)
{
  //PRINT_R($datosFacturables);
  $tipoMoneda = "MXN";
  $userReg = $_SESSION['LZFident'];
  //------------------------------------LISTA DE PRODUCTOS A FACTURAR--------------------------------------------------
  $ArrayIdsVentas = array();
  $ArrayIdsVentas = array_merge($ArrayIdsVentas, $ids_Ventas);
  $busqVentas = implode(',', $ArrayIdsVentas);
  $sql = "SELECT
  clvprod.codigo AS claveprod,
  clvuni.id AS claveUnidad,
  dtvta.cantidad,
  clvuni.nombre claveUniNombre,
  dtvta.precioVenta,
  prod.descripcion AS producto,
 (dtvta.cantidad*dtvta.precioVenta) AS subtotal

FROM
  ventas vta INNER JOIN detventas dtvta ON dtvta.idVenta = vta.id
  INNER JOIN productos prod ON dtvta.idProducto=prod.id
  INNER JOIN sat_claveproducto clvprod ON prod.idClaveProducto =clvprod.codigo
  INNER JOIN sat_claveunidad clvuni ON clvuni.id=prod.idClavUniProducto
  WHERE vta.id IN($busqVentas)";


  $resultXprod = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Producto de Ventas por Facturar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
  $conceptos = array();
  $monto = 0;
  while ($dat = mysqli_fetch_array($resultXprod)) {
    array_push($conceptos, array(
      'ClaveProdServ' => $dat['claveprod'],
      'Cantidad' => $dat['cantidad'],
      'ClaveUnidad' => $dat['claveUnidad'],
      'Unidad' => $dat['claveUniNombre'],
      'ValorUnitario' => $dat['precioVenta'],
      'Descripcion' => $dat['producto'],
    ));
    $monto = $monto + $dat['subtotal'];
  }

  //-----------------------------------------------INTERACCION CON LA API-----------------------------------------------------
  $ch = curl_init();
  //print_r($datosFacturables);
  $fields = [
    "Receptor" => ["UID" => $datosFacturables['uid']],
    "TipoDocumento" => "factura",
    "UsoCFDI" => $datosFacturables['usoCFDI'],
    "Redondeo" => 2,
    "Conceptos" => $conceptos,
    "FormaPago" => $datosFacturables['formaPago'],
    "MetodoPago" => $datosFacturables['metodosPago'],
    "Moneda" => $tipoMoneda,
    "CondicionesDePago" => $datosFacturables['descMetPgo'],
    "Serie" => $datosFacturables['idSerieFact'],
    //"Serie" => 11739,
    //"EnviarCorreo" => 'false',
    "Comentarios" => "Folio Ventas: " . $busqVentas
  ];
  //print_r($fields);
  $jsonfield = json_encode($fields);
  //print_r($jsonfield);
  curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v3/cfdi33/create");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonfield);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
    "F-API-KEY: " . $datosFacturables['apiKey'],
    "F-SECRET-KEY: " . $datosFacturables['secretKey']
  ));

  $response = curl_exec($ch);
  //print_r("Respuesta: ".$response);
  curl_close($ch);
  $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array
  $estatusApi = $JSON_FACTURA_PHP->response; //guardo el estatus*/
  //print_r ("Estatus: ".$response);

  //-----------------------------------------------MANEJO DE RESULTADOS-----------------------------------------------------
  switch ($estatusApi) {
    case 'success':
      $uid = $JSON_FACTURA_PHP->uid;
      $UUID = $JSON_FACTURA_PHP->SAT->UUID;
      $mensaje = $JSON_FACTURA_PHP->message;
      $uid = $JSON_FACTURA_PHP->uid;
      $fechaTimbrado = $JSON_FACTURA_PHP->SAT->FechaTimbrado;
      $noCertSat = $JSON_FACTURA_PHP->SAT->NoCertificadoSAT;
      $selloSat = $JSON_FACTURA_PHP->SAT->SelloSAT;
      $selloCfd = $JSON_FACTURA_PHP->SAT->SelloCFD;
      $serie = $JSON_FACTURA_PHP->INV->Serie;
      $folio = $JSON_FACTURA_PHP->INV->Folio;
      $formaPago = $datosFacturables["formaPago"];
      $metodoPago = trim($datosFacturables["metodosPago"]);
      $rfcRe = $datosFacturables["rfcRe"];
      $rfcEm = $datosFacturables["rfcEmp"];
      $idEmpresa = $datosFacturables["idEmpresa"];
      $correo = $datosFacturables["correo"];
      $uidDatosFisc = $datosFacturables['uid'];
      $complemento = $datosFacturables['complemento'];
      //$idDatosF=$datosFacturables["idDatosAct"];
      //------------------------------------------REGISTRO EN LA TABLA FACTURAS ---------------------------------------------------------
      $sql = "INSERT INTO facturas (uuid,uid, mensaje,  rfcEm, rfcRe, monto, portalCarga, fechaTimbrado, noCertSat, selloSat, selloCfd, serie, folio, fechaReg,
    estatus, idUserReg, tipo, formaPago, metodoPago) VALUES ('$UUID','$uid', '$mensaje','$rfcEm','$rfcRe','$monto','1','$fechaTimbrado','$noCertSat','$selloSat',' $selloCfd', '$serie', '$folio',
    NOW(),'1', $userReg, '1', '$formaPago','$metodoPago')";
      $resultXFact = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al guardar factura, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      $facturaIng = mysqli_insert_id($link);
      //--------------------------------------------REGISTRO EN LA TABLA FACTURASGENERADAS ---------------------------------------------------------
      $sql = "INSERT INTO facturasgeneradas(uuidFact, uidDatosFisc, portalCarga, idUserReg, fechaReg, idEmpresa) VALUES ('$UUID', '$uidDatosFisc','1', '$userReg', NOW(),'$idEmpresa')";
      $resultXFactGen = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al guardar factura generada, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      $facturaGen = mysqli_insert_id($link);
      //--------------------------------------------REGISTRO EN LA TABLA FACTVTAS ---------------------------------------------------------

      for ($i = 0; $i < count($ids_Ventas); $i++) {
        $idVenta = $ids_Ventas[$i];
        $sql = "INSERT INTO vtasfact(idVenta, idFactgen) VALUES ('$idVenta', '$facturaGen')";
        $resultXFactVta = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al guardar factura en Ventas, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      }
      //--------------------------------------------COMPLEMENTO DE PAGO---------------------------------------------------------
      if ($complemento == '1') {

        $datosFactura = array(
          "UUID" => $UUID, "UID" => $datosFacturables['uid'], "serie" => $serie, "folio" => $folio, "monto" => $monto, "secretKey" => $datosFacturables['secretKey'], "apiKey" => $datosFacturables['apiKey'], 'idSerieComPgo' => $datosFacturables["idSerieComPgo"],
          "rfcRe" => $rfcRe, "rfcEm" => $rfcEm, "empresa" => $datosFacturables['empresa'], "sucursal" => $datosFacturables['sucursal']
        );
        $responseComp = complementosFact($datosFactura, $ids_Ventas, $link);
        $estatusComp = $responseComp["estatus"];
        $fileXMLComp = '';
        $filePDFComp = '';
        $archivoComp = "";
        switch ($estatusComp) {
          case '1':
            // print_r("rutaPDF-->".$responseComp["result"][1]);
            $fileXMLComp = $responseComp["result"][1];
            $filePDFComp = $responseComp["result"][0];
            $archivoComp = 'ComplPgo_' . $serie . '-' . $folio;

            break;
          case '0':
            $fileXMLComp = '';
            $filePDFComp = '';
            $archivoComp = "";
            $resp = array('estatus' => '0', 'mensaje' => 'Factura Emitida, Error Al Crear Complemento de Pago.', 'idsErroneos' => $responseComp["idsErroneos"]);
            return $resp;
            break;
          default:
            $fileXMLComp = '';
            $filePDFComp = '';
            $archivoComp = "";
            break;
        }
      }
      // =========================================   Descargar PDF y XML =========================================================
      $anioCoriente = date("Y");
      $numMes = date("m");
      $empresa = $datosFacturables['empresa'];
      $nameSuc = $datosFacturables['sucursal'];
      $carpeta = 'FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
      $carpetaOrig = '../FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
      if (!file_exists($carpetaOrig)) {
        mkdir($carpetaOrig, 0755, true);
      }
      $nameFile = $carpetaOrig . $UUID;
      $nameFileSave = $carpeta . $UUID;
      //   ------------------------------------   DESCARGA DE PDF   ---------------------------------
      $ch = curl_init();
      $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/pdf';
      curl_setopt($ch, CURLOPT_URL, "$ruta");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
        "F-API-KEY: " . $datosFacturables['apiKey'],
        "F-SECRET-KEY: " . $datosFacturables['secretKey']
      ));
      $response = curl_exec($ch);
      curl_close($ch);
      file_put_contents($nameFile . '.PDF', $response);

      //   ------------------------------------   DESCARGA DE XML   ---------------------------------
      $ch = curl_init();
      $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/xml';
      curl_setopt($ch, CURLOPT_URL, "$ruta");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
        "F-API-KEY: " . $datosFacturables['apiKey'],
        "F-SECRET-KEY: " . $datosFacturables['secretKey']
      ));
      $response = curl_exec($ch);
      curl_close($ch);
      file_put_contents($nameFile . '.XML', $response);

      //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
      $filePDF = (file_exists($nameFile . '.PDF')) ? $nameFileSave . '.PDF' : '';
      $fileXML = (file_exists($nameFile . '.XML')) ? $nameFileSave . '.XML' : '';

      #                    echo '<br>* link de PDF: '.$filePDF.'<br>* link de XML: '.$fileXML;

      $sqlUpdateFiles = "UPDATE facturas SET doctoPDF = '$filePDF', doctoXML = '$fileXML' WHERE uuid = '$UUID' ";
      #                    echo '<br>Consulta actualizar doctos: '.$sqlUpdateFiles;
      mysqli_query($link, $sqlUpdateFiles) or die('0|E8.- Tuvimos Problemas al actualizar los Archivos:' . mysqli_error($link) . '<br> ' . $sqlUpdateFiles);
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
        $mail->Username = 'lazafra@lazafra.com.mx';      // SMTP username
        $mail->Password = '@gr0f3rt1l1z@nt3s.L@z@fr@';                     // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        # echo 'Correos enviados a: ';
        //Recipients
        $mail->setFrom('lazafra@lazafra.com.mx', 'Por Favor no responda este correo.');
        //PRINT_R("este es el correo-->".$correo);
        $mail->addAddress($correo);     // Add a recipient
        // $mail->addBCC('contabilidad@lazafra.com.mx');

        //$mail->addReplyTo('karen.dominguez@rvsetys.com', 'No Responder');

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Nueva Factura creada con Folio: ' . $serie . '' . $folio . ' de la Venta: ' . $busqVentas;
        $mail->Body  = ' <h2>Venta con Folio: ' . $busqVentas . '</h2> <br>';
        $mail->Body .= ' <p><b>Factura emitida por ' . $empresa . ' en la Sucursal ' . $nameSuc . '.</b><br>';
        $mail->Body .= ' <p>Folio Fiscal: <b>' . $UUID . '</b></p>';
        $mail->Body .= ' <p><b>NOTA:</b><br>
                       Cualquier duda o aclaración, contactese con la sucursal donde realizó su compra.<br>';
        $mensajeFactura = "";
        // $mail->Body  ='la mamada';
        $mail->CharSet = 'UTF-8';
        $archivo = 'Factura_' . $serie . '-' . $folio;
        //----------------------------------------------AGREGAR COMPLEMENTO DE PAGO-------------------------------------
        $filePDFComp = (isset($filePDFComp)) ? $filePDFComp : '';
        if ($filePDFComp != '' and $fileXMLComp != '') {
          $mail->addAttachment('../' . $filePDFComp, $archivoComp . '.PDF');
          $mail->addAttachment('../' . $fileXMLComp, $archivoComp . '.XML');
        }
        $mail->addAttachment('../' . $filePDF, $archivo . '.PDF');
        $mail->addAttachment('../' . $fileXML, $archivo . '.XML');

        $mail->send();
        $mensajeFactura .= ' y se envió copia a la sucursal';
      } catch (Exception $e) {
        echo '0|E8b.- ' . $mensajeFactura . ', pero tuvimos Problemas al Mandar el correo a la Sucursal, Notifica a tu Administrador';
      }
      //======================================   FINALIZA CORREO   =====================================================
      $resp = array('estatus' => '1', 'mensaje' => 'Facturación correcta', 'result' => '');
      return $resp;

      break;
    case 'error':

      $resp = array('estatus' => '0', 'mensaje' => 'Error Al Facturar.', 'idsErroneos' => array("message" => $JSON_FACTURA_PHP->message));
      return $resp;
      break;
    default:
      $resp = array('estatus' => '0', 'mensaje' => 'Error Inesperado al Guardar Factura', 'idsErroneos' => "");

      break;
  }
}
//---------------------------------------------FACTURAR DESDE API UNA BOLETA --------------------------------------------------
function facturarApiBol($ids_Ventas, $datosFacturables, $link)
{
  //PRINT_R($datosFacturables);
  $tipoMoneda = "MXN";
  $userReg = $_SESSION['LZFident'];
  //------------------------------------LISTA DE PRODUCTOS A FACTURAR--------------------------------------------------
  $ArrayIdsVentas = array();
  $ArrayIdsVentas = array_merge($ArrayIdsVentas, $ids_Ventas);
  $busqVentas = implode(',', $ArrayIdsVentas);
  $sql = "SELECT
  clvprod.codigo AS claveprod,
  clvuni.id AS claveUnidad,
  dtvta.cantidad,
  clvuni.nombre claveUniNombre,
  dtvta.precioVenta,
  prod.descripcion AS producto,
 (dtvta.cantidad*dtvta.precioVenta) AS subtotal

FROM
  ventas vta INNER JOIN detventas dtvta ON dtvta.idVenta = vta.id
  INNER JOIN productos prod ON dtvta.idProducto=prod.id
  INNER JOIN sat_claveproducto clvprod ON prod.idClaveProducto =clvprod.codigo
  INNER JOIN sat_claveunidad clvuni ON clvuni.id=prod.idClavUniProducto
  WHERE vta.id IN($busqVentas)";


  $resultXprod = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Producto de Ventas por Facturar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
  $conceptos = array();
  $monto = 0;
  while ($dat = mysqli_fetch_array($resultXprod)) {
    array_push($conceptos, array(
      'ClaveProdServ' => $dat['claveprod'],
      'Cantidad' => $dat['cantidad'],
      'ClaveUnidad' => $dat['claveUnidad'],
      'Unidad' => $dat['claveUniNombre'],
      'ValorUnitario' => $dat['precioVenta'],
      'Descripcion' => $dat['producto'],
    ));
    $monto = $monto + $dat['subtotal'];
  }

  //-----------------------------------------------INTERACCION CON LA API-----------------------------------------------------
  $ch = curl_init();
  //print_r($datosFacturables);
  $fields = [
    "Receptor" => ["UID" => $datosFacturables['uid']],
    "TipoDocumento" => "factura",
    "UsoCFDI" => $datosFacturables['usoCFDI'],
    "Redondeo" => 2,
    "Conceptos" => $conceptos,
    "FormaPago" => $datosFacturables['formaPago'],
    "MetodoPago" => $datosFacturables['metodosPago'],
    "Moneda" => $tipoMoneda,
    "CondicionesDePago" => $datosFacturables['descMetPgo'],
    "Serie" => $datosFacturables['idSerieFact'],
    //"Serie" => 11739,
    //"EnviarCorreo" => 'false',
    "Comentarios" => "Folio Ventas: " . $busqVentas
  ];
  //print_r($fields);
  $jsonfield = json_encode($fields);
  //print_r($jsonfield);
  curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v3/cfdi33/create");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonfield);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
    "F-API-KEY: " . $datosFacturables['apiKey'],
    "F-SECRET-KEY: " . $datosFacturables['secretKey']
  ));

  $response = curl_exec($ch);
 // print_r("Respuesta".$response);
  curl_close($ch);
  $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array
  $estatusApi = $JSON_FACTURA_PHP->response; //guardo el estatus*/
  //print_r ("Estatus: ".$response);

  //-----------------------------------------------MANEJO DE RESULTADOS-----------------------------------------------------
  switch ($estatusApi) {
    case 'success':
      $uid = $JSON_FACTURA_PHP->uid;
      $UUID = $JSON_FACTURA_PHP->SAT->UUID;
      $mensaje = $JSON_FACTURA_PHP->message;
      $uid = $JSON_FACTURA_PHP->uid;
      $fechaTimbrado = $JSON_FACTURA_PHP->SAT->FechaTimbrado;
      $noCertSat = $JSON_FACTURA_PHP->SAT->NoCertificadoSAT;
      $selloSat = $JSON_FACTURA_PHP->SAT->SelloSAT;
      $selloCfd = $JSON_FACTURA_PHP->SAT->SelloCFD;
      $serie = $JSON_FACTURA_PHP->INV->Serie;
      $folio = $JSON_FACTURA_PHP->INV->Folio;
      $formaPago = $datosFacturables["formaPago"];
      $metodoPago = trim($datosFacturables["metodosPago"]);
      $rfcRe = $datosFacturables["rfcRe"];
      $rfcEm = $datosFacturables["rfcEmp"];
      $idEmpresa = $datosFacturables["idEmpresa"];
      $correo = $datosFacturables["correo"];
      $uidDatosFisc = $datosFacturables['uid'];
      $complemento = $datosFacturables['complemento'];
      //$idDatosF=$datosFacturables["idDatosAct"];
      //------------------------------------------REGISTRO EN LA TABLA FACTURAS ---------------------------------------------------------
      $sql = "INSERT INTO facturas (uuid,uid, mensaje,  rfcEm, rfcRe, monto, portalCarga, fechaTimbrado, noCertSat, selloSat, selloCfd, serie, folio, fechaReg,
    estatus, idUserReg, tipo, formaPago, metodoPago) VALUES ('$UUID','$uid', '$mensaje','$rfcEm','$rfcRe','$monto','1','$fechaTimbrado','$noCertSat','$selloSat',' $selloCfd', '$serie', '$folio',
    NOW(),'1', $userReg, '1', '$formaPago','$metodoPago')";
      $resultXFact = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al guardar factura, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      $facturaIng = mysqli_insert_id($link);
      //--------------------------------------------REGISTRO EN LA TABLA FACTURASGENERADAS ---------------------------------------------------------
      $sql = "INSERT INTO facturasgeneradas(uuidFact, uidDatosFisc, portalCarga, idUserReg, fechaReg, idEmpresa) VALUES ('$UUID', '$uidDatosFisc','1', '$userReg', NOW(),'$idEmpresa')";
      $resultXFactGen = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al guardar factura generada, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      $facturaGen = mysqli_insert_id($link);
      //--------------------------------------------REGISTRO EN LA TABLA FACTVTAS ---------------------------------------------------------

      for ($i = 0; $i < count($ids_Ventas); $i++) {
        $idVenta = $ids_Ventas[$i];
        $sql = "INSERT INTO vtasfact(idVenta, idFactgen) VALUES ('$idVenta', '$facturaGen')";
        $resultXFactVta = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al guardar factura en Ventas, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      }
      //--------------------------------------------COMPLEMENTO DE PAGO---------------------------------------------------------
      if ($complemento == '1') {

        $datosFactura = array(
          "UUID" => $UUID, "UID" => $datosFacturables['uid'], "serie" => $serie, "folio" => $folio, "monto" => $monto, "secretKey" => $datosFacturables['secretKey'], "apiKey" => $datosFacturables['apiKey'], 'idSerieComPgo' => $datosFacturables["idSerieComPgo"],
          "rfcRe" => $rfcRe, "rfcEm" => $rfcEm, "empresa" => $datosFacturables['empresa'], "sucursal" => $datosFacturables['sucursal']
        );
        $responseComp = complementosFact($datosFactura, $ids_Ventas, $link);
        $estatusComp = $responseComp["estatus"];
        $fileXMLComp = '';
        $filePDFComp = '';
        $archivoComp = "";
        switch ($estatusComp) {
          case '1':
            // print_r("rutaPDF-->".$responseComp["result"][1]);
            $fileXMLComp = $responseComp["result"][1];
            $filePDFComp = $responseComp["result"][0];
            $archivoComp = 'ComplPgo_' . $serie . '-' . $folio;

            break;
          case '0':
            $fileXMLComp = '';
            $filePDFComp = '';
            $archivoComp = "";
            $resp = array('estatus' => '0', 'mensaje' => 'Factura Emitida, Error Al Crear Complemento de Pago.', 'idsErroneos' => $responseComp["idsErroneos"]);
            return $resp;
            break;
          default:
            $fileXMLComp = '';
            $filePDFComp = '';
            $archivoComp = "";
            break;
        }
      }
      // =========================================   Descargar PDF y XML =========================================================
      $anioCoriente = date("Y");
      $numMes = date("m");
      $empresa = $datosFacturables['empresa'];
      $nameSuc = $datosFacturables['sucursal'];
      $carpeta = 'FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
      $carpetaOrig = '../FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/';
      if (!file_exists($carpetaOrig)) {
        mkdir($carpetaOrig, 0755, true);
      }
      $nameFile = $carpetaOrig . $UUID;
      $nameFileSave = $carpeta . $UUID;
      //   ------------------------------------   DESCARGA DE PDF   ---------------------------------
      $ch = curl_init();
      $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/pdf';
      curl_setopt($ch, CURLOPT_URL, "$ruta");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
        "F-API-KEY: " . $datosFacturables['apiKey'],
        "F-SECRET-KEY: " . $datosFacturables['secretKey']
      ));
      $response = curl_exec($ch);
      curl_close($ch);
      file_put_contents($nameFile . '.PDF', $response);

      //   ------------------------------------   DESCARGA DE XML   ---------------------------------
      $ch = curl_init();
      $ruta = "https://facturaonline.com.mx/api/v3/cfdi33/" . $uid . '/xml';
      curl_setopt($ch, CURLOPT_URL, "$ruta");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
        "F-API-KEY: " . $datosFacturables['apiKey'],
        "F-SECRET-KEY: " . $datosFacturables['secretKey']
      ));
      $response = curl_exec($ch);
      curl_close($ch);
      file_put_contents($nameFile . '.XML', $response);

      //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
      $filePDF = (file_exists($nameFile . '.PDF')) ? $nameFileSave . '.PDF' : '';
      $fileXML = (file_exists($nameFile . '.XML')) ? $nameFileSave . '.XML' : '';

      #                    echo '<br>* link de PDF: '.$filePDF.'<br>* link de XML: '.$fileXML;

      $sqlUpdateFiles = "UPDATE facturas SET doctoPDF = '$filePDF', doctoXML = '$fileXML' WHERE uuid = '$UUID' ";
      #                    echo '<br>Consulta actualizar doctos: '.$sqlUpdateFiles;
      mysqli_query($link, $sqlUpdateFiles) or die('0|E8.- Tuvimos Problemas al actualizar los Archivos:' . mysqli_error($link) . '<br> ' . $sqlUpdateFiles);
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
        $mail->Username = 'lazafra@lazafra.com.mx';      // SMTP username
        $mail->Password = '@gr0f3rt1l1z@nt3s.L@z@fr@';                     // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        # echo 'Correos enviados a: ';
        //Recipients
        $mail->setFrom('lazafra@lazafra.com.mx', 'Por Favor no responda este correo.');
        //PRINT_R("este es el correo-->".$correo);
        $mail->addAddress($correo);     // Add a recipient
        // $mail->addBCC('contabilidad@lazafra.com.mx');

        //$mail->addReplyTo('karen.dominguez@rvsetys.com', 'No Responder');

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Nueva Factura creada con Folio: ' . $serie . '' . $folio . ' de la Venta: ' . $busqVentas;
        $mail->Body  = ' <h2>Venta con Folio: ' . $busqVentas . '</h2> <br>';
        $mail->Body .= ' <p><b>Factura emitida por ' . $empresa . ' en la Sucursal ' . $nameSuc . '.</b><br>';
        $mail->Body .= ' <p>Folio Fiscal: <b>' . $UUID . '</b></p>';
        $mail->Body .= ' <p><b>NOTA:</b><br>
                       Cualquier duda o aclaración, contactese con la sucursal donde realizó su compra.<br>';
        $mensajeFactura = "";
        // $mail->Body  ='la mamada';
        $mail->CharSet = 'UTF-8';
        $archivo = 'Factura_' . $serie . '-' . $folio;
        //----------------------------------------------AGREGAR COMPLEMENTO DE PAGO-------------------------------------
        $filePDFComp = (isset($filePDFComp)) ? $filePDFComp : '';
        if ($filePDFComp != '' and $fileXMLComp != '') {
          $mail->addAttachment('../' . $filePDFComp, $archivoComp . '.PDF');
          $mail->addAttachment('../' . $fileXMLComp, $archivoComp . '.XML');
        }
        $mail->addAttachment('../' . $filePDF, $archivo . '.PDF');
        $mail->addAttachment('../' . $fileXML, $archivo . '.XML');

        $mail->send();
        $mensajeFactura .= ' y se envió copia a la sucursal';
      } catch (Exception $e) {
        echo '0|E8b.- ' . $mensajeFactura . ', pero tuvimos Problemas al Mandar el correo a la Sucursal, Notifica a tu Administrador';
      }
      //======================================   FINALIZA CORREO   =====================================================
      $resp = array('estatus' => '1', 'mensaje' => 'Facturación correcta', 'result' => '');
      return $resp;

      break;
    case 'error':

      $resp = array('estatus' => '0', 'mensaje' => 'Error Al Facturar.', 'idsErroneos' => array("message" => $JSON_FACTURA_PHP->message));
      return $resp;
      break;
    default:
      $resp = array('estatus' => '0', 'mensaje' => 'Error Inesperado al Guardar Factura', 'idsErroneos' => "");

      break;
  }
}




























//---------------------------------------------OBTENER DATOS DE FACTURACION----------------------------------------------------
function obtenerDatosFact($ids_Ventas, $rfc, $link, $verificarBoleta)
{
  //$ids_Ventas=[8];
  if ($rfc == 'XAXX010101000') {
    $complemento='0';
    $metpags= "PUE";
    $descripcionMetPgo = "Pago en parcialidades o diferido";

    $formPago = "99";
    $resp = array('estatus' => '1', 'mensaje' => 'Verificación de datos de Facturación correcta', 'result' => array("metodosPago" => $metpags, "formaPago" => $formPago, "descMetPgo" => $descripcionMetPgo, "complemento" => $complemento));
    return $resp;
  } else {
    $ArrayIdsVentas = array();
    $ArrayIdsVentas = array_merge($ArrayIdsVentas, $ids_Ventas);
    $busqVentas = implode(',', $ArrayIdsVentas);
    $sql = "SELECT DISTINCT COUNT(vtas.id) AS totalVtas, pgosvta.idFormaPago, metpgos.clave  FROM ventas vtas
    INNER JOIN pagosventas pgosvta ON vtas.id= pgosvta.idVenta
    INNER JOIN sat_formapago metpgos ON metpgos.id= pgosvta.idFormaPago
    WHERE vtas.id IN ($busqVentas) GROUP BY pgosvta.idFormaPago";
    //print_r($sql);
    $resultXpagos = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Buscar el Metodo de Pago, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
    $ventaXBoleta = "<div class='text-danger'>Total de Ventas Pagadas Con Boleta:</div>";
    $contarxBoleta = 0;
    $erroresVentas = array();
    $total_ventas = mysqli_num_rows($resultXpagos);
    $formaUnica = 0;
    $formaAnterior = "";
    $complemento = 0;
    if ($total_ventas == 1) {
      $xpags = mysqli_fetch_array($resultXpagos);
      if ($xpags['idFormaPago'] == '7') {
        $complemento = 1;
        $metpags = "PPD";
        $formPago = "99";
        $descripcionMetPgo = "Pago en parcialidades o diferido";
        $formaUnica = 1;
      }
      //----------------------pago de boletas---------------------------------
      else if ($xpags["idFormaPago"] == "6" and $verificarBoleta == '1') {
        $ventaXBoleta .=  $xpags["totalVtas"];
        $contarxBoleta++;
      } else if ($xpags['idFormaPago'] == '7') {
        $complemento = 1;
        $metpags = "PPD";
        $formPago = "99";
        $descripcionMetPgo = "Pago en parcialidades o diferido";
      } else {
        $metpags = "PUE";
        $formPago = $xpags['clave'];
        $descripcionMetPgo = "Pago en una sola exhibición";
        $formaUnica = 1;
      }
    } else {
      while ($dat = mysqli_fetch_array($resultXpagos)) {

        if ($complemento == 0) {
          //----------------------pago de boletas---------------------------------
          if ($dat["idFormaPago"] == "6" and $verificarBoleta == '1') {
            $ventaXBoleta .= $dat["totalVtas"];
            $contarxBoleta++;
          } else {
            if ($dat['idFormaPago'] == '7') {
              $complemento = 1;
              $metpags = "PPD";
              $formPago = "99";
              $descripcionMetPgo = "Pago en parcialidades o diferido";
            }
          }
        }
      } //cierra While

    }

    if ($contarxBoleta > 0) {
      array_push($erroresVentas, $ventaXBoleta);
      $resp = array('estatus' => '0', 'mensaje' => 'Ventas Pagadas con Boletas NO se pueden facturar.', 'idsErroneos' => json_encode($erroresVentas));
      return $resp;
    } else {
      if ($formaUnica == 1) {

        $resp = array('estatus' => '1', 'mensaje' => 'Verificación de datos de Facturación correcta', 'result' => array("metodosPago" => $metpags, "formaPago" => $formPago, "descMetPgo" => $descripcionMetPgo, "complemento" => $complemento));
        return $resp;
      } else {
        if ($complemento == 1) {
          $resp = array('estatus' => '1', 'mensaje' => 'Verificación de datos de Facturación correcta', 'result' => array("metodosPago" => $metpags, "formaPago" => $formPago, "descMetPgo" => $descripcionMetPgo, "complemento" => $complemento));
          return $resp;
        } else {
          $metpags = "PUE";
          $formPago = "99";
          $descripcionMetPgo = "Pago en una sola exhibición";
          $resp = array('estatus' => '1', 'mensaje' => 'Verificación de datos de Facturación correcta', 'result' => array("metodosPago" => $metpags, "formaPago" => $formPago, "descMetPgo" => $descripcionMetPgo, "complemento" => $complemento));
          return $resp;
        }
      }
    }
  }
}

//-----------------------------------------FUNCION REGISTRA CLIENTE--------------------------------------------
function registraCliente($datosClientes, $link, $portalCarga, $idEmpresaSeleccionada)
{
  $userReg = $_SESSION['LZFident'];
  $arrayResult = array();
  switch ($datosClientes["operacion"]) {
    case '1':
      //Iniciar ciclo con las tres empresas
      $sql = "SELECT
       dp.id,
       apiKey,
       secretKey,
       dp.idEmpresa
       FROM
       datospacs dp
       INNER JOIN empresas emp ON emp.id = dp.idEmpresa
       WHERE dp.pac='Factura.com' AND emp.estatus='1'";
      $resultKey = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Registrar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));

      while ($dat = mysqli_fetch_array($resultKey)) {
        $rfcCliente = $datosClientes["rfc"];
        $razonCliente = $datosClientes["razonSocial"];
        $calle = $datosClientes["calle"];
        $noExt = $datosClientes["noExt"];
        $noInt = $datosClientes["noInt"];
        $colonia = $datosClientes["colonia"];
        $codpostal = $datosClientes["codpostal"];
        $municipio = $datosClientes["municipio"];
        $estado = $datosClientes["estado"];
        $correo = $datosClientes["correo"];
        $correo2 = $datosClientes["correo2"];
        $correo3 = $datosClientes["correo3"];
        $nombre = $datosClientes["nombre"];
        $apellido = $datosClientes["apellido"];
        $telefono = $datosClientes["telefono"];
        $cfdi = $datosClientes["usoCFDI"];
        $idEmpresa = $dat["idEmpresa"];
        $idCliente = $datosClientes["idCliente"] == '' ? '0' : $datosClientes["idCliente"];
        //API
        $ch = curl_init();

        $fields = [
          "nombre" => $nombre,
          "apellidos" => $apellido,
          "email" => $correo,
          "email2" => $correo2,
          "email3" => $correo3,
          "telefono" => $telefono,
          "razons" => $razonCliente,
          "rfc" => $rfcCliente,
          "calle" => $calle,
          "numero_exterior" => $noExt,
          "numero_interior" => $noInt,
          "codpos" => $codpostal,
          "colonia" => $colonia,
          "estado" => $estado,
          "ciudad" => $municipio
        ];

        $jsonfield = json_encode($fields);

        curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v1/clients/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonfield);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
          "F-Api-Key: " . $dat["apiKey"],
          "F-Secret-Key: " . $dat["secretKey"]
        ));

        $response = curl_exec($ch);
        //  PRINT_R("Nuevos Datos-->".$response);  
        curl_close($ch);

        $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array
        $estatusApi = $JSON_FACTURA_PHP->status; //guardo el estatus*/
        $uid = $JSON_FACTURA_PHP->Data->UID;


        switch ($estatusApi) {
          case 'success':

            // PROCEDEMOS A GUARDAR A LA BASE DE DATOS
            $sql = "INSERT INTO datosfisc (uid, rfc, razonSocial,usoCfdi, calle, noExt, noInt, colonia, codigoPostal, municipio, estado, correo, correo2, correo3,
          nombre, apellidos, fechaReg, tel, idEmpresa, estatus, idUserReg,portalCarga, idCliente) VALUES ('$uid', '$rfcCliente','$razonCliente','$cfdi', '$calle', '$noExt', '$noInt','$colonia', '$codpostal', '$municipio', '$estado', '$correo', '$correo2', '$correo3', '$nombre',
          '$apellido', NOW(), '$telefono', '$idEmpresa', '1', '$userReg', '$portalCarga', '$idCliente' )";
            $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Registrar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
            $idActual = mysqli_insert_id($link);
            if ($idEmpresa == $idEmpresaSeleccionada) {
              $arrayResult = array("uid" => $uid, 'apiKey' => $dat["apiKey"], 'secretKey' => $dat["secretKey"], 'rfcRe' => $rfcCliente, 'correo' => $correo, 'idDatosAct' => $idActual);
            }
            break;
          case 'error':
            // Fin del proceso en caso de error al guardar
            $resp = array('estatus' => '0', 'mensaje' => 'Error al Guardar los Nuevos Datos Fiscales', 'idsErroneos' => json_encode(array('Error en los datos')));
            return $resp;
            break;
          default:
            $resp = array('estatus' => '0', 'mensaje' => 'Error Inesperado al Guardar los Nuevos Datos Fiscales', 'idsErroneos' => json_encode(array($JSON_FACTURA_PHP->messsage)));
            return $resp;
        }
      } //Cierre de While
      $resp = array('estatus' => '1', 'mensaje' => 'Los Datos Fiscales Se Han Guardado', 'result' => $arrayResult);
      return $resp;
      break;
      //---------------------------------------------MODIFICACION DE DATOS FISCALES------------------------------------------
    case '2':
      //Iniciar ciclo con las tres empresas
      $id = $datosClientes["idCliente"];
      $sql = "SELECT
      dp.id,
      apiKey,
      secretKey,
      dp.idEmpresa,
      dtsfisc.uid 
    FROM
      datospacs dp
      INNER JOIN empresas emp ON emp.id = dp.idEmpresa 
      INNER JOIN datosfisc dtsfisc ON dtsfisc.idEmpresa=dp.idEmpresa 
    WHERE
      dp.pac = 'Factura.com' 
      AND emp.estatus = '1' 
      AND dtsfisc.rfc=(SELECT rfc FROM datosfisc WHERE id='$id')";

      $resultKey = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Registrar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      while ($dat = mysqli_fetch_array($resultKey)) {
        $rfcCliente = $datosClientes["rfc"];
        $razonCliente = $datosClientes["razonSocial"];
        $calle = $datosClientes["calle"];
        $noExt = $datosClientes["noExt"];
        $noInt = $datosClientes["noInt"];
        $colonia = $datosClientes["colonia"];
        $codpostal = $datosClientes["codpostal"];
        $municipio = $datosClientes["municipio"];
        $estado = $datosClientes["estado"];
        $correo = $datosClientes["correo"];
        $correo2 = $datosClientes["correo2"];
        $correo3 = $datosClientes["correo3"];
        $nombre = $datosClientes["nombre"];
        $apellido = $datosClientes["apellido"];
        $telefono = $datosClientes["telefono"];
        $cfdi = $datosClientes["usoCFDI"];
        $idEmpresa = $dat["idEmpresa"];
        $uid = $dat["uid"];

        //API
        $ch = curl_init();
        $fields = [
          "nombre" => $nombre,
          "apellidos" => $apellido,
          "email" => $correo,
          "email2" => $correo2,
          "email3" => $correo3,
          "telefono" => $telefono,
          "razons" => $razonCliente,
          "rfc" => $rfcCliente,
          "calle" => $calle,
          "numero_exterior" => $noExt,
          "numero_interior" => $noInt,
          "codpos" => $codpostal,
          "colonia" => $colonia,
          "estado" => $estado,
          "ciudad" => $municipio
        ];

        $jsonfield = json_encode($fields);
        curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v1/clients/$uid/update");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonfield);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
          "F-Api-Key: " . $dat["apiKey"],
          "F-Secret-Key: " . $dat["secretKey"]
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array

        $estatusApi = $JSON_FACTURA_PHP->status; //guardo el estatus*/


        switch ($estatusApi) {
          case 'success':
            if ($idEmpresa == $idEmpresaSeleccionada) {

              $arrayResult = array("uid" => $uid, 'apiKey' => $dat["apiKey"], 'secretKey' => $dat["secretKey"], 'rfcRe' => $rfcCliente, 'correo' => $correo, 'idDatosAct' => $id);
            }

            $sql = "UPDATE  datosfisc SET rfc='$rfcCliente', razonSocial='$razonCliente',usoCfdi='$cfdi', calle='$calle', noExt='$noExt', noInt='$noInt', colonia='$colonia', codigoPostal='$codpostal', municipio='$municipio', estado='$estado', correo='$correo', correo2='$correo2', correo3='$correo3',
          nombre='$nombre', apellidos='$apellido', fechaReg=NOW(), tel='$telefono', estatus='1', idUserReg='$userReg',portalCarga='$portalCarga' WHERE rfc=(SELECT rfc FROM datosfisc WHERE id='$id')";
            $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Registrar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
            break;
          case 'error':
            // Fin del proceso en caso de error al guardar
            $resp = array('estatus' => '0', 'mensaje' => 'Error al Modificar los Nuevos Datos Fiscales', 'idsErroneos' => json_encode(array('Error en los datos')));
            return $resp;
            break;
        }
      } //Cierre de While
      $resp = array('estatus' => '1', 'mensaje' => 'Los Datos Fiscales Se Han Modificado', 'result' => $arrayResult);
      return $resp;

      break;
    case '3':
      //------------------------------------OBTENER EL UID DE LA EMPRESA POR LA QUE SE VA A FACTURAR, SU API KEY, Y EL SECRET KEY----------------------------

      $idCliente = $datosClientes["idCliente"];
      $rfcCliente = $datosClientes["rfc"];
      $razonCliente = $datosClientes["razonSocial"];
      $calle = $datosClientes["calle"];
      $noExt = $datosClientes["noExt"];
      $noInt = $datosClientes["noInt"];
      $colonia = $datosClientes["colonia"];
      $codpostal = $datosClientes["codpostal"];
      $municipio = $datosClientes["municipio"];
      $estado = $datosClientes["estado"];
      $correo = $datosClientes["correo"];
      $correo2 = $datosClientes["correo2"];
      $correo3 = $datosClientes["correo3"];
      $nombre = $datosClientes["nombre"];
      $apellido = $datosClientes["apellido"];
      $telefono = $datosClientes["telefono"];
      $cfdi = $datosClientes["usoCFDI"];
      $sql = "SELECT dtspac.apiKey, dtspac.secretKey, dtfisc.uid, dtfisc.id FROM datosfisc dtfisc
        INNER JOIN datospacs dtspac ON dtspac.idEmpresa=dtfisc.idEmpresa
        WHERE dtfisc.idEmpresa='$idEmpresaSeleccionada' AND dtfisc.estatus='1' AND dtfisc.rfc= (SELECT rfc FROM datosfisc WHERE id='$idCliente')";
      $resultXBusq = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Recuperar datos del Cliente Seleccionado, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
      $num_registros = mysqli_num_rows($resultXBusq);
  //  print_r($sql);
      if ($num_registros == 0) {
        //-----Caso 1: Que no haya cliente para facturar esa venta previamente registrado------------------------ 
        $sql = "SELECT dtspac.apiKey, dtspac.secretKey, dtspac.idEmpresa,dtfisc.id  FROM datospacs dtspac 
          LEFT JOIN datosfisc dtfisc ON dtspac.idEmpresa=dtfisc.idEmpresa AND dtfisc.rfc= (SELECT rfc FROM datosfisc WHERE id='$idCliente')
          WHERE dtfisc.idEmpresa IS NULL OR dtfisc.estatus='0'";
      // print_r($sql);
        $resultXDtsFlt = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Recuperar datos de Actualizacion de datos fiscales del Cliente Seleccionado, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
        while ($dat = mysqli_fetch_array($resultXDtsFlt)) {
          $idEmpresa = $dat['idEmpresa'];
          //API
          $ch = curl_init();
          //print_r($ch);
          $fields = [
            "nombre" => $nombre,
            "apellidos" => $apellido,
            "email" => $correo,
            "email2" => $correo2,
            "email3" => $correo3,
            "telefono" => $telefono,
            "razons" => $razonCliente,
            "rfc" => $rfcCliente,
            "calle" => $calle,
            "numero_exterior" => $noExt,
            "numero_interior" => $noInt,
            "codpos" => $codpostal,
            "colonia" => $colonia,
            "estado" => $estado,
            "ciudad" => $municipio
          ];

          $jsonfield = json_encode($fields);

          curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v1/clients/create");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_POST, TRUE);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonfield);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
            "F-Api-Key: " . $dat["apiKey"],
            "F-Secret-Key: " . $dat["secretKey"]
          ));
          $response = curl_exec($ch);
          //  PRINT_R("Respuesta--->".$response);
          curl_close($ch);
          $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array
          $estatusApi = $JSON_FACTURA_PHP->status; //guardo el estatus*/
          $idDtsFisc = $dat["id"];
          switch ($estatusApi) {
            case 'success':
              $uid = $JSON_FACTURA_PHP->Data->UID;
              if ($idEmpresa == $idEmpresaSeleccionada) {
               // print_r($idEmpresaSeleccionada.'Empresa Seleccionada');

                $arrayResult = array("uid" => $uid, 'apiKey' => $dat["apiKey"], 'secretKey' => $dat["secretKey"], 'rfcRe' => $rfcCliente, 'correo' => $correo, 'idDatosAct' => $idDtsFisc);
               // print_r($arrayResult);
              }
              // PROCEDEMOS A GUARDAR A LA BASE DE DATOS
              $sql = "INSERT INTO datosfisc (uid, rfc, razonSocial,usoCfdi, calle, noExt, noInt, colonia, codigoPostal, municipio, estado, correo, correo2, correo3,
            nombre, apellidos, fechaReg, tel, idEmpresa, estatus, idUserReg,portalCarga) VALUES ('$uid', '$rfcCliente','$razonCliente','$cfdi', '$calle', '$noExt', '$noInt','$colonia', '$codpostal', '$municipio', '$estado', '$correo', '$correo2', '$correo3', '$nombre',
            '$apellido', NOW(), '$telefono', '$idEmpresa', '1', '$userReg', '$portalCarga' )";
              $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => "Error al Registrar, notifica a tu Administrador", 'idsErroneos' => mysqli_error($link))));
              break;
            case 'error':
              // Fin del proceso en caso de error al guardar
              $resp = array('estatus' => '0', 'mensaje' => 'Error al Guardar los Nuevos Datos Fiscales', 'idsErroneos' => json_encode(array('Error en los datos')));
              return $resp;
              break;
            default:
              $resp = array('estatus' => '0', 'mensaje' => 'Error Inesperado en Datos Fiscales', 'idsErroneos' => json_encode(array($JSON_FACTURA_PHP->messsage)));
              return $resp;
          }
        }
        $resp = array('estatus' => '1', 'mensaje' => 'Los Datos Fiscales Se Han Actualizado e Identificado', 'result' => $arrayResult);
        return $resp;
      } else {
        //-----Caso 2: Que si haya cliente para facturar esa venta previamente registrado------------------------ 
        //  print_r($result);
        $datResult = mysqli_fetch_array($resultXBusq);
        // print_r("Este es-->".$datResult);
        $arrayResult = array("uid" => $datResult['uid'], 'apiKey' => $datResult["apiKey"], 'secretKey' => $datResult["secretKey"], 'rfcRe' => $rfcCliente, 'correo' => $correo);
        $resp = array('estatus' => '1', 'mensaje' => 'Los Datos Fiscales Se Han Identificado', 'result' => $arrayResult);
        return $resp;
      }



      break;
  } //cierre Switch principal

}

//---------------------------------FUNCION VALIDA VENTAS ---------------------------------------------------
function validaVentas($ids_Ventas, $link)
{
  //vaciar ids en un array
  // $ids_Ventas=[8, 19, 40];
  $userReg = $_SESSION['LZFident'];
  //PRINT_R($userReg);
  $ArrayIdsVentas = array();
  $ArrayIdsVentas = array_merge($ArrayIdsVentas, $ids_Ventas);
  $resp = array(); //array para retornar
  //VALIDAR QUE LAS VENTAS PERTENEZCAN A LA VENTA DE LA SUCURSAL QUE VA Facturar
  $busqVentas = implode(',', $ArrayIdsVentas);
  //PRINT_R($busqVentas);

  //VALIDAR QUE EXISTA LA VENTA Y ESTE CERRADA
  $idsDefectuosos = array();
  $idAnt = '';

  $sql = "SELECT
vnt.id,
vnt.estatus,
suc.id AS sucursal,
suc.nombre
FROM
ventas vnt
INNER JOIN sucursales suc ON suc.id =vnt.idSucursal
INNER JOIN empresas emp ON emp.id =suc.idEmpresa
WHERE vnt.id IN ($busqVentas)";

  $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al consultar la venta, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
  $antEmpresa = "";
  $nameEmpresa = "";
  $idVentaUno = '';
  $vuelta = 1;
  $nameAnt = '';
  $ventasNoCanceladas = "<div class='text-danger'>Ventas Canceladas:</div>";
  $cuentaVentas = 0;
  while ($dat = mysqli_fetch_array($result)) {
    //analizar ventas Canceladas
    if ($dat["estatus"] != 2) {
      $ventasNoCanceladas .= " #" . $dat['id'];
      $cuentaVentas++;
    }

    //ver si la empresa es la misma
    if ($vuelta == 1) {
      $antEmpresa = $dat["sucursal"];
      $idVentaUno = $dat["id"];
      $nameAnt = $dat["nombre"];
      $vuelta++;
    } else if ($antEmpresa != $dat["sucursal"] and $vuelta > 1) {
      if ($vuelta == 2) {
        $nameEmpresa = "Venta de la Empresa " . $nameAnt . ": #" . $idVentaUno . "<br>";
        array_push($idsDefectuosos, $nameEmpresa);
        $vuelta++;
      }
      $nameEmpresa = "Venta de la Empresa " . $dat["nombre"] . ": #" . $dat["id"] . "<br>";
      array_push($idsDefectuosos, $nameEmpresa);
      $antEmpresa = $dat["sucursal"];
      $vuelta++;
    }
  }

  $sql = "SELECT COUNT(prdVnt.id) productosVenta, COUNT(prd.id) productosBien FROM ventas vnt
INNER JOIN detventas dtvnt ON dtvnt.idVenta=vnt.id AND idVenta IN($busqVentas)
INNER JOIN productos prdVnt ON dtvnt.idProducto = prdVnt.id
INNER JOIN productos prd ON dtvnt.idProducto = prd.id AND prd.idClaveProducto IS NOT NULL AND
prd.idClavUniProducto IS NOT NULL";

  $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al consultar Productos de Venta, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
  $arrayResultados = mysqli_fetch_array($result);
  $productosVendidos = $arrayResultados['productosVenta'];
  $productosBien = $arrayResultados['productosBien'];
  $estatusClave = 1;
  $clavesMal = '';
  //0: Mal 1:Bien
  //$productosBien=12;
  //$productosVendidos=15;
  if ($productosVendidos != $productosBien) {
    $estatusClave = 0;
    $clavesMal = "<br><b>Verifica que los productos contengan clave y unidad de producto</b>";
  }
  if (count($idsDefectuosos) > 0 || $cuentaVentas > 0 || $estatusClave == 0) {
    if ($cuentaVentas == 0) {
      $ventasNoCanceladas = "";
    }
    array_push($idsDefectuosos, $ventasNoCanceladas);
    array_push($idsDefectuosos, $clavesMal);
    $resp = array('estatus' => '0', 'mensaje' => 'Verifica los ticket(s) de venta(s), pertenezcan a la misma empresa.', 'idsErroneos' => json_encode($idsDefectuosos));
    return $resp;
  } else {
    $sql = "SELECT DISTINCT
    suc.idEmpresa,
    suc.serieFact,
    suc.idSerieFact,
    suc.idSerieComPgo,
    suc.serieComPgo,
    suc.id AS idSuc,
    suc.nombre AS sucursal, 
    emp.nombre AS empresa,
    emp.rfc AS rfcEmp
  FROM
    ventas vtas
    INNER JOIN sucursales suc ON suc.id = vtas.idSucursal
    INNER JOIN empresas emp ON emp.id =suc.idEmpresa
  WHERE vtas.id IN ($busqVentas)";
    $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al consultar Productos de Venta, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
    $array_Empresa = mysqli_fetch_array($result);
    $arrayResult = array("serieFact" => $array_Empresa['serieFact'], "idSerieFact" => $array_Empresa['idSerieFact'], "serieComPgo" => $array_Empresa['serieComPgo'], "idSerieComPgo" => $array_Empresa['idSerieComPgo'], "idEmpresa" => $array_Empresa['idEmpresa'], "idSucursal" => $array_Empresa['idSuc'], "sucursal" => $array_Empresa['sucursal'], "empresa" => $array_Empresa["empresa"], "rfcEmp" => $array_Empresa["rfcEmp"]);
    $resp = array('estatus' => '1', 'mensaje' => 'Verificación de los ticket(s) de venta(s) correcta.', 'result' => $arrayResult);
    return $resp;
  }
} //funcion

//-----------------------------------------VALIDA FACTURACION ANTERIOR -----------------------------------
function validaFactAnt($ids_Ventas, $link)
{
  $userReg = $_SESSION['LZFident'];
  //vaciar ids en un array
  //  $ids_Ventas=[1,6,34,8];
  $ArrayIdsVentas = array();
  $ArrayIdsVentas = array_merge($ArrayIdsVentas, $ids_Ventas);
  $resp = array(); //array para retornar

  $busqVentas = implode(',', $ArrayIdsVentas);
  //VALIDAR QUE EXISTA LA VENTA Y ESTE CERRADA
  $idsDefectuosos = array();
  $idAnt = '';
  for ($i = 0; $i <= count($ArrayIdsVentas) - 1; $i++) {
    $idVenta = $ArrayIdsVentas[$i];
    $sql = "SELECT
  COUNT( factgencancel.id ) AS factCanceladas,
  COUNT( factgen.id ) AS totalFact
FROM
  vtasfact vf
  INNER JOIN facturasgeneradas factgen ON factgen.id = vf.idFactgen
  LEFT JOIN facturasgeneradas factgencancel ON factgencancel.id = vf.idFactgen
  AND factgencancel.idCancelada != ''
  WHERE vf.idVenta='$idVenta'";

    $result = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al consultar la Facturas de la venta, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
    $array_resultado = mysqli_fetch_array($result);
    $factCanceladas = $array_resultado['factCanceladas'];
    $totalFact = $array_resultado['totalFact'];
    //Se verifica que el total de cancelaciones sea igual al total de las facturas emitidas
    if ($totalFact > $factCanceladas) {
      array_push($idsDefectuosos, "#" . $idVenta);
    }
  }
  if (count($idsDefectuosos) > 0) {
    $resp = array('estatus' => '0', 'mensaje' => 'Verifica que las ventas no tengan facturas ACTIVAS.', 'idsErroneos' => json_encode($idsDefectuosos));
    return $resp;
  } else {
    $resp = array('estatus' => '1', 'mensaje' => 'Verificación de los ticket(s) de venta(s) correcta', 'idsErroneos' => '');
    return $resp;
  } //no permitidas
}





function cancelarFactura($uids_Ventas, $link, $necesitaCompr)
{
  $userReg = $_SESSION['LZFident'];
  $ArrayUIDsVentas = array();
  $ArrayUIDsVentas = array_merge($ArrayUIDsVentas, $uids_Ventas);

  $busqVentas = implode(',', $ArrayUIDsVentas);
  //-----------------------------------------------------Busqueda del UUID de la factura ---------------------------
  $sql = "SELECT 
    fact.uuid,
    fact.uid,
    dtp.secretKey,
    dtp.apiKey,
    factgen.id AS factgen,
    emp.nombre AS empresa,
    suc.nombre AS sucursal,
    vf.idVenta AS idVenta,
    vf.id AS idVF
    FROM ventas vta
    INNER JOIN sucursales suc ON suc.id=vta.idSucursal
    INNER JOIN empresas emp ON suc.idEmpresa =emp.id
    INNER JOIN datospacs dtp ON dtp.idEmpresa=emp.id
    INNER JOIN vtasfact vf ON vf.idVenta = vta.id
    INNER JOIN facturasgeneradas factgen ON factgen.id=vf.idFactgen
    INNER JOIN facturas fact ON fact.uuid= factgen.uuidFact
    WHERE fact.uid IN ('$busqVentas')";
  $resultXBusq = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al consultar la venta para cancelar CFDI, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
  while ($datXdtsCancela = mysqli_fetch_array($resultXBusq)) {
    $apiKey = $datXdtsCancela["apiKey"];
    $secretKey = $datXdtsCancela["secretKey"];
    $cfdi_uuid = $datXdtsCancela["uuid"];
    $cfdi_uid = $datXdtsCancela["uid"];
    $empresa = $datXdtsCancela['empresa'];
    $nameSuc = $datXdtsCancela['sucursal'];
    //------------------------------------------------Interaccion con la API---------------------------
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://facturaonline.com.mx/api/v3/cfdi33/$cfdi_uid/cancel");
    //print_r( "https://facturaonline.com.mx/api/v3/cfdi33/$cfdi_uid/cancel");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
      "F-Api-Key: " . $apiKey,
      "F-Secret-Key: " . $secretKey
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    $JSON_FACTURA_PHP = json_decode($response); //creo respuesta de API a un Array

    $estatusApi = $JSON_FACTURA_PHP->respuestaapi->response; //guardo el estatus*/  
    switch ($estatusApi) {
      case "success":
        $uuidFact = $datXdtsCancela["uuid"];
        $idVenta = $datXdtsCancela["idVenta"];
        $idVF = $datXdtsCancela["idVF"];
        $acuse = $JSON_FACTURA_PHP->respuestaapi->acuse;
        $anioCoriente = date("Y");
        $numMes = date("m");



        //-----------------------------------GUARDAR EN LA BASE DE DATOS---------------------------
        $sql = "INSERT INTO cancelafact(uuidFact, idVenta,  fechaReg, idUserReg) VALUES('$uuidFact', '$idVenta', NOW(), '$userReg')";
        $resultXInsert = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al guardar la cancelacion de CFDI, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
        $idCancelada = mysqli_insert_id($link);
        //-----------------------------------ACTUALIZAR DATOS DE FACT GENERADAS--------------------
        $sql = "UPDATE facturasgeneradas SET idCancelada='$idCancelada' WHERE id='$idVF'";
        $resultXUpdate = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al actualizar la cancelacion de CFDI, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
        $estatus = ($necesitaCompr == '1') ? '2' : '3';
        $sql = "UPDATE facturas SET estatus='$estatus' WHERE uuid='$uuidFact'";
        $resultXUpdateStatus = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al actualizar el estatus de CFDI, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
        //----------------------------------------GUARDA XML-------------------------------
        $carpeta = 'FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/' . "Cancelaciones";
        $carpetaOrig = '../FacturasEmitidas/' . $empresa . '/' . $nameSuc . '/' . $anioCoriente . '/' . $numMes . '/' . "Cancelaciones";
        if (!file_exists($carpetaOrig)) {
          mkdir($carpetaOrig, 0755, true);
        }
        $nameFile = $carpetaOrig . "/cancel-" . $uuidFact;
        $nameFileSave = $carpeta . "/cancel-" . $uuidFact;
        file_put_contents($nameFile . '.XML', $JSON_FACTURA_PHP->respuestaapi->acuse);
        //   ------------------------------------   COMPROBACION DE ARCHIVOS   ---------------------------------
        $fileXML = (file_exists($nameFile . '.XML')) ? $nameFileSave . '.XML' : '';
        if ($necesitaCompr == '0') {
          $sql = "UPDATE cancelafact SET acuseXML='$fileXML' WHERE id='$idCancelada'";
          $resultXUpdateDoctos = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al actualizar el XML de CFDI, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
        } else {
          $sql = "UPDATE cancelafact SET acuseCancelacion='$fileXML' WHERE id='$idCancelada'";
          $resultXUpdateDoctos = mysqli_query($link, $sql) or die(json_encode(array('estatus' => '3', 'mensaje' => 'Error al actualizar el XML de CFDI, notifica a tu Administrador', 'idsErroneos' => mysqli_error($link))));
        }


        break;
      case "error":
        $resp = array('estatus' => '0', 'mensaje' => 'Cancelacion de la factura erronea, verifica los detalles.', 'idsErroneos' => json_encode($JSON_FACTURA_PHP->message));
        return $resp;
        break;
    }
    $resp = array('estatus' => '1', 'mensaje' => 'Cancelación correcta', 'result' => '');
    return $resp;
  }
}
