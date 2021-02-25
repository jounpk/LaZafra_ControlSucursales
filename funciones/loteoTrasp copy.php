<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
//INICIA TRANSACCION
mysqli_autocommit($link, FALSE);
mysqli_begin_transaction($link);
//ID DEL TRASPASO A EFECTUAR A MODIFICAR
$ident = (isset($_POST['ident']) and $_POST['ident'] != '') ? $_POST['ident'] : '';
//OBTENER LOS DETALLES DE TRASPASO Y EL ID DEL PRODUCTO
$sql = "SELECT
det.id AS idDetalle,
det.cantEnvio,
det.idProducto
FROM
traspasos tr
INNER JOIN dettraspasos det ON det.idTraspaso=tr.id
WHERE
tr.id = '$ident'";
//print_r ( "Consulta Traspasos-->".$sql);
$res = mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Traspasos, notifica a tu Administrador'));
$cant = mysqli_num_rows($res);
//SI COMPARAMOS Y HAY DETALLES DE TRASPASOS
if ($cant > 0) {
    
//PARA LOTEO DE TRASPASOS  X EL USUARIO
//RECORRER CADA DETALLE DE TRASPASOS EN BUSCA DE UN CHECKBOX
   while ($dat = mysqli_fetch_array($res)) {
    //CONSTRUIMOS EL NAME DEL CHECKBOX
    $check_valid = 'chck_auto_' . $dat['idDetalle'];
    $iddettrasp = $dat["idDetalle"];
    $cantidad = $dat["cantEnvio"];
    $producto = $dat["idProducto"];
    if (isset($_POST[$check_valid]) and $_POST[$check_valid] == '1') {
       
        //HAY UN CHECK ACTIVO
    $sqllotes = "SELECT
       lote.id,
       lote.cant
    FROM
       lotestocks lote
       WHERE lote.idProducto='$producto'
       AND lote.idSucursal='$sucursal'"; //BUSCA QUE TODOS LOS LOTES DEL PRODUCTO
     $reslote = mysqli_query($link, $sqllotes) or die(errorCarga('Problemas al consultar ajustes, notifica a tu Administrador'));
     while ($datlote = mysqli_fetch_array($reslote)) {
        
     $cantlotes = "cantlote-" . $datlote['id']; //NOMBRE DE LOS INPUTS
        //SI EXISTE EL INPUT DE CANTIDAD LOTE SE PROCEDE A LAS QUERYS 
        if (isset($_POST[$cantlotes]) and $_POST[$cantlotes] != '0') {
            $idloteseleccionado = $datlote['id'];
            $cantidad = $_POST[$cantlotes];
            $sql = "INSERT INTO dettrasplote (idDetTraspaso, idLoteSalida, cantidad) VALUES('$iddettrasp', '$idloteseleccionado', '$cantidad')";
           // print_r ( $sql;
            $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar Traspasos, notifica a tu Administrador'));
            $sql = "UPDATE lotestocks SET cant=cant-$cantidad WHERE id=$idloteseleccionado";
          ///  print_r ( $sql;
            $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador'));
         
        }//CIERRRA IF DE VALIDACION DE LOS INPUTS*/

     }//CIERRA WHILE DE LOS LOTES

    }//EN CASO DE QUE FUERA EL LOTEO MANUAL 
    else{
        $poracompletar = $cantidad;
        //PARA TRASPASOS DE MANERA AUTOMATICA
        /*$sql = "SELECT lote.id, lote.cant
        FROM lotestocks lote 
        WHERE lote.idProducto='$producto' AND ((lote.caducidad IS NULL AND lote.idSucursal='$sucursal' AND lote.cant>0) OR ( lote.caducidad=(
        SELECT MIN(lote.caducidad) AS caducidad
        FROM lotestocks lote WHERE lote.idSucursal = '$sucursal' AND lote.idProducto='$producto' AND lote.cant>0
         )))
        ";   */
        $sql="SELECT
        lote.id,
        lote.cant 
    FROM
        lotestocks lote 
    WHERE
        lote.idProducto = '$producto' 
                AND lote.idSucursal = '$sucursal' 
                AND lote.cant > 0 
    ORDER BY lote.caducidad ASC"; 
    // print_r ( "<br>Checa Lote: ". $sql);            AND lote.estatus='1'
    
     $resAcom = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar Traspasos, notifica a tu Administrador'));
     $iteracion=0;
     if (mysqli_num_rows($resAcom) > 0) { //se checa que haya lotes
        while ($datlote = mysqli_fetch_array($resAcom)) {
              $iteracion++;  
            //SE ACTUALIZA EL LOTE
           // $lote = mysqli_fetch_array($resAcom);
            $cantloteactual = $datlote['cant'];
           // print_r ( "1|".$cantloteactual;
          
            if ($cantloteactual >= $cantidad AND $poracompletar>0) { //Lote con capacidad para descontar
              //  print_r("Se metio primero_ciclo: ".$iteracion);
                $idloteseleccionado = $datlote["id"];
                $sql = "INSERT INTO dettrasplote (idDetTraspaso, idLoteSalida, cantidad) VALUES('$iddettrasp', '$idloteseleccionado', '$cantidad')";
                $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar Traspasos, notifica a tu Administrador'));
                $sql = "UPDATE lotestocks SET cant=cant-$cantidad WHERE id=$idloteseleccionado";
               // print_r ( "<br> El lote seleccionado es: ".$idloteseleccionado);
                $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador' ));
                $poracompletar = 0;
            }//cierre de lote con capacidad para descontar
                else if  ($poracompletar>0){//lote que no surte el decremento
                   // print_r("Se metio dos_ciclo: ".$iteracion);
                      // errorCarga("Upps! No te alcanza");
                      $poracompletar = $cantidad;
                    // print_r ("<br>Upps! No te alcanza".$poracompletar);
                      $sqlAcom = 'SELECT lote.id, lote.cant
                  FROM lotestocks lote 
                  WHERE lote.idProducto="' . $producto . '" AND lote.idSucursal="' . $sucursal . '" ORDER BY caducidad ASC
                  ';

                  $resAcom = mysqli_query($link, $sqlAcom) or die(errorCarga('Problemas al consultar Traspasos, notifica a tu Administrador'));
                  if (mysqli_num_rows($resAcom) > 0) {//AL MENOS EXISTE UN LOTE 
                      while ($datlote = mysqli_fetch_array($resAcom)) {//recorrido de los lotes
                            //SE ACTUALIZA EL LOTE
                        $cantloteactual = $datlote['cant'];
                        $idloteseleccionado = $datlote["id"];
                        if ($cantloteactual < $poracompletar &&  $poracompletar != 0) {
                            $sql = "INSERT INTO dettrasplote (idDetTraspaso, idLoteSalida, cantidad) VALUES('$iddettrasp', '$idloteseleccionado', '$cantidad')";
                            $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar Traspasos, notifica a tu Administrador'));

                            
                            $poracompletar = $poracompletar - $cantidad;
                           $sql = "UPDATE lotestocks SET cant=cant-$cantloteactual WHERE id=$idloteseleccionado";
                           $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador'));
                            
                        }
                    else if ($cantloteactual>=$poracompletar &&  $poracompletar != 0) {//EL LOTE SOBREPASA

                        $sql = "INSERT INTO dettrasplote (idDetTraspaso, idLoteSalida, cantidad) VALUES('$iddettrasp', '$idloteseleccionado', '$poracompletar')";
                        $resCon = mysqli_query($link, $sql) or die(errorCarga('Problemas al consultar Traspasos, notifica a tu Administrador'));
                        $poracompletar = 0;
                     
                        $sql = "UPDATE lotestocks SET cant=cant-$poracompletar WHERE id=$idloteseleccionado";
                         $rescantlote = mysqli_query($link, $sql) or die(errorCarga('Problemas al descontar en el lote, notifica a tu Administrador'));
                     
                    }//EL LOTE SOBREPASA
                      }//recorrido de los lotes  
                    
                    }else {
                        errorCarga("Los lotes registrados no surten el movimiento");
                    }



                }//cierre del del lote que no surte el decremento
        }//cierra while
            }//CIERRE DE LA COMPROBACION
        

        else {
                //SINO ENCUENTRA LOTES
        errorCarga('No se encuentra ningun dato sobre lotes, notifica a tu Administrador');
            }


    }//EN CASO DE QUE HAYA LOTES 
    
    

    }//CIERRA WHILE
    $sql="CALL SP_enviaTraspaso('$ident','$userReg','$sucursal', NOW())";
    $res=mysqli_query($link,$sql) or die(errorBD($sql));
    $dat = mysqli_fetch_array($res);
    
    $estatus = $dat['estatus'];
    $msj = $dat['mensaje'];
    if ($estatus == 1){
    
        echo  ('1|'.$msj);
    
      }else {
            errorCarga($msj);
        }
   

   }//CUANDO SI HAY DET DE TRASPASOS

//AFECTAR STOCKS
/*$sql="CALL SP_enviaTraspaso('$ident','$userReg','$sucursal', NOW())";
$res=mysqli_query($link,$sql) or die(errorBD($sql));
$dat = mysqli_fetch_array($res);

$estatus = $dat['estatus'];
$msj = $dat['mensaje'];
if ($estatus != 1){
   
      errorCarga($msj);
  }*/



else{
  
  errorCarga('Lo sentimos los datos no se pueden validar, notifica a tu Administrador.');

}

//  AL FINAL DEL PROCESO MANDAMOS RESULTADOS
if (mysqli_commit($link)) {
 
}
 else {
    errorCarga('Error al cargar los datos, notifica a tu Administrador.');
}

function errorCarga($error)
{
    $link = $GLOBALS["link"];
    echo '0|' . $error;
    mysqli_rollback($link);
    exit(0);
}
?>