$(document).ready(function() {
    var dt = $('#tabla_ajustessol').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": datoSolicitudes.data,
        "columns": [{
                "class": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-' + pyme + '"></i> </div>',
            },
            {
                "data": "id"
            },
            {
                "data": "fechaAplica"
            },
            {
                "data": "descripcion"
            },
            {
                "data": "sucursal"
            },
            {
                "data": "usuario"
            },
            {
                "data": "usuarioDecide"
            },
            {
                "data": "usuarioEmitio"
            },

            {
                "data": "button"
            }

        ],


        "order": [
            [2, 'desc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#tabla_ajustessol tbody').on('click', 'td.details-control', function() {
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
        //alert(d.idPyme + " La varGlob ");
        return '<div id="DIV' + d.id + '" class="col-lg-12 text-center">' +
            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
            '</div>';
    };

    var cargaContenido = function(d) {
        // alert(d.id + " Probado ");
        ejecutandoCarga(d.id);
    };
}); // Cierre de document ready