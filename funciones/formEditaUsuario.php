<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idUsuario'])) ? $_POST['idUsuario'] : 0 ;
$vista = (!empty($_POST['vista'])) ? $_POST['vista'] : 0 ;
$idSucursal = $_SESSION['LZFidSuc'];
$idNivelUsu = $_SESSION['LZFidNivel'];

if ($id == 0) {
  errorBD('No se reconoció el usuario, actualiza e inténtalo de nuevo, si persiste notifica a tu Administrador.');
}

$sqlCon = "SELECT * FROM segusuarios WHERE id = '$id' LIMIT 1";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consutlar los usuarios, notifica a tu Administrador.');
$usu = mysqli_fetch_array($resCon);
$nombre = $usu['nombre'];
$ApPaterno = $usu['appat'];
$ApMaterno = $usu['apmat'];
$direccion = $usu['direccion'];
$usuario = $usu['usuario'];
$nivel = $usu['idNivel'];
$sucursal = $usu['idSucursal'];


echo '<form role="form" method="post" action="../funciones/editaUsuario.php" id="formEditUsuario">
        <div class="text-center"><label class="control-label col-form-label"><b class="text-danger">Nota: </b>Si no deseas cambiar la contraseña déjala en blanco ('.$idNivelUsu.')</label></div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editNombre1"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" id="editNombre" placeholder="Ingresa el Nombre" aria-describedby="nombre" name="editNombre" value="'.$nombre.'" required>
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editApPat1">A.P.</span>
            </div>
            <input type="text" class="form-control" id="editApPat" placeholder="Ingresa el Apellido Paterno" aria-describedby="apPaterno" name="editApPat" value="'.$ApPaterno.'" required>
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editApMat1">A.M.</span>
            </div>
            <input type="text" class="form-control" id="editApMat" placeholder="Ingresa el Apellido Materno" aria-describedby="apMaterno" name="editApMat" value="'.$ApMaterno.'" required>
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editDireccion1"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" id="editDireccion" placeholder="Ingresa su Dirección" aria-describedby="direcion" name="editDireccion" value="'.$direccion.'" required>
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editNivel1"><i class="fas fa-key"></i></span>
            </div>
            <select class="form-control" id="editNivel" name="editNivel" required>
              <option value="">Selecciona un Nivel de Acceso</option>';
              switch ($idNivelUsu) {
                case '3':
                  $sqlLvl = "SELECT * FROM segniveles WHERE id != '4' AND id != '7'  ORDER BY orden ASC";
                  break;

                case '4':
                  $sqlLvl = "SELECT * FROM segniveles WHERE id != '7'  ORDER BY orden ASC";
                  break;

                default:
                  $sqlLvl = "SELECT * FROM segniveles WHERE id < '3'  ORDER BY orden ASC";
                  break;
              }


                  $resLvl = mysqli_query($link,$sqlLvl) or die('Problemas al consultar los niveles, notifica a tu Administrador');
                  while ($lvl = mysqli_fetch_array($resLvl)) {
                    $activo = ($nivel == $lvl['id']) ? 'selected' : '' ;
                    $disabled = ($lvl['estatus'] == 1) ? '' : 'disabled' ;
                    echo '<option value="'.$lvl['id'].'"  '.$activo.' '.$disabled.'>'.$lvl['nombre'].'</option>';
                  }

    echo '</select>
        </div>
        <br>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editSucursal1"><i class="fas fa-building"></i></span>
            </div>
            <select class="form-control" id="editSucursal" name="editSucursal" required>
              <option value="">Selecciona la Sucursal en donde estará</option>';
              if ($idNivelUsu < 3) {
                $filtroSuc = "WHERE id = '$idSucursal'";
              } else {
                $filtroSuc = '';
              }

                  $sqlSuc = "SELECT * FROM sucursales $filtroSuc ORDER BY nameFact ASC";
                  $resSuc = mysqli_query($link,$sqlSuc) or die('Problemas al consultar los niveles, notifica a tu Administrador');
                  while ($suc = mysqli_fetch_array($resSuc)) {
                    $activo2 = ($sucursal == $suc['id']) ? 'selected' : '' ;
                    $disabled = ($suc['estatus'] == 1) ? '' : 'disabled' ;
                    echo '<option value="'.$suc['id'].'" '.$activo2.' '.$disabled.'>'.$suc['nameFact'].'</option>';
                  }

    echo '</select>
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editUsuario1"><i class="fas fa-user-circle"></i></span>
            </div>
            <input type="text" class="form-control" id="editUsuario" placeholder="Ingresa el Nombre con el que Iniciará Sesión" aria-describedby="usuario" name="editUsuario" value="'.$usuario.'" required>
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editContraseña1"><i class="mdi mdi-key"></i></span>
            </div>
            <input type="password" class="form-control" id="editContraseña" placeholder="Ingresa la Contraseña" aria-describedby="contraseña" name="editContraseña">
        </div>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="editContraseña21"><i class="mdi mdi-key-variant"></i></span>
            </div>
            <input type="password" class="form-control" id="editContraseña2" placeholder="Repite la Contraseña" aria-describedby="contraseña2" name="editContraseña2">
        </div>
        <br>
            <div class="modal-footer">
              <input type="hidden" id="ident" name="ident" value="'.$id.'">
              <input type="hidden" id="vista" name="vista" value="'.$vista.'">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success waves-effect waves-light">Editar</button>
            </div>
        </form>';


 ?>
