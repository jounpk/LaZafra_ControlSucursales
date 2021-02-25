
function notificaSuc(cont){
  toastr.success(cont, 'Excelente!', {
    "progressBar": true,
    "CerrarButton": true
  });
}

function notificaBad(cont){
  toastr.error(cont, 'Lo Sentimos!', {
    "progressBar": true,
    "closeButton": true
  });
}

function bloqueoBtn(seccion,no){
  if (seccion == 0) {
    seccion == '';
  }
    if (no==1) {
      $("#bloquear-btn"+seccion).show();
      $("#desbloquear-btn"+seccion).hide();
    }else {
      $("#bloquear-btn"+seccion).hide();
      $("#desbloquear-btn"+seccion).show();
    }

  }
