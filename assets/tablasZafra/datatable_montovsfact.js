var jsonData;
var dt;
  function obtenerData(idCorte) {

  $.post("../funciones/obtenerData.php", {
            idCorte: idCorte
        },
        function(respuesta) {
            //$("#prueba").html(respuesta);
            console.log(dt);
            jsonData = JSON.parse(respuesta);
           // dt.destroy();
            if (dt=="undefined"){
                iniciarDataTable(idCorte);

            }else{
               // dt.destroy();
                iniciarDataTable(idCorte);

            }

        });
}
function iniciarDataTable(id){
    dt = $('#tabla_detalles-'+id).DataTable({
        'rowCallback': function(row, data, index) {


        },
        "destroy":true,
        "dom": 'T<"clear">lfrtip',

        "data": jsonData.data, //{}
        "columns": [{
                "class": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-pyme itable"></i> </div>',
            },
          
            {
                "data": "nombre"
            }, 
            {
                "data": "montoVentas"
            }, 
            {
                "data": "montoCortes"
            }, 
            {
                "data": "montoFact"
            }
            


        ],

        "buttons": [
            'excel',
            'print'
        ],
        "order": [
            [0, 'asc']
        ]
    });

    $(".itable").click(function() {
        //alert('Haciendo Change');
        $(this).toggleClass("fa-plus-circle");
        $(this).toggleClass("fa-minus-circle");
    });

    //Add event listener for opening and closing details
    var o = this;
    $('#tabla_detalles-'+id+' tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = dt.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(lanzaLoading(row.data())).show();
            tr.addClass('shown');
            row.child(cargaContenido(row.data()));
            //alert("hola");
        }
    });


    var lanzaLoading = function(d) {
        //alert(id + " La varGlob ");
        return '<div id="DIV' + d.idFormaPago + '_'+id+'" class="col-lg-12">' +
            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
            '</div>';
    };

    var cargaContenido = function(d) {
       // alert(id + " La varGlob ");
        ejecutandoCarga(d.ventas, d.idFormaPago, id);
    };

}

function ejecutandoCarga(idsVentas, idFormaPago, id) {
    var selector = 'DIV' +  idFormaPago + '_'+id ;
    var finicio = $('#fStart').val();
    var ffin = $('#fEnd').val();

    $.post("../funciones/cargaContenidoMontoVSFact.php", {
            idsVentas:idsVentas,
            idFormaPago:idFormaPago

        },
        function(respuesta) {
           // alert(id);
            $("#" + selector).html(respuesta);
        });

}
