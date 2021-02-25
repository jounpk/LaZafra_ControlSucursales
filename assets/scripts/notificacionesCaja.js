$(document).ready(function() {
conn();
setInterval("conn()", 20000);
})
function conn() {
  //$("#notificaciones").load("funciones/Notificaciones/alertas.php");
  //alert($('#cantNotit').val());
  $.post("funciones/Notificaciones/alertas.php",
     { },
  function(respuesta){
    //alert(respuesta);
    //$("#demo").html(respuesta);
    var subCadenas = respuesta.split('|');
    //alert(subCadenas[0]);
    //alert('Sub1'+subCadenas[1]);

    $("#notificaciones").html(subCadenas[1]);

   if(subCadenas[0]==1 || subCadenas[0]=='1'){
      //alert('suena');
      document.getElementById('player').play();
      playing = true;
    }
  });
}
