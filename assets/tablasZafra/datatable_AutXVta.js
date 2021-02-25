$(document).ready(function () {
    var dt = $('#tablaAutXVta').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": dataJson.data,
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-' + pyme + '"></i> </div>',
        },
        {
            "data": "nomSucursal"
        },
        {
            "data": "nomUsuario"
        },
        {
            "data": "motivoVtaEsp"
        },
        {
            "data": "nomCliente"
        },
        {
            "data": "fecha"
        },
       
        {
            "data": null,
            render: function (data, type, row) {
               
                return  `<button type="button" class="btn btn-success btn-circle muestraSombra" title="Aceptar Solicitud" onClick="aceptaVentaEsp(${data.id},1);"><i class="fas fa-check"></i></button>
                <button type="button" class="btn btn-danger btn-circle muestraSombra" title="Rechazar Solicitud" onClick="aceptaVentaEsp(${data.id},2);"><i class="fas fa-times"></i></button>`;
             
            }
        }

        ],


        "order": [
            [5, 'desc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#tablaAutXVta tbody').on('click', 'td.details-control', function () {
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


    var lanzaLoading = function (d) {
        //alert(d.idPyme + " La varGlob ");
        return '<div id="DIV' + d.id + '" class="col-lg-12 text-center">' +
            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
            '</div>';
    };

    var cargaContenido = function (d) {
        // alert(d.id + " Probado ");
        ejecutandoCarga(d.id);
    };
}); // Cierre de document ready