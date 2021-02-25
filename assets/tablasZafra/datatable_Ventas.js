$(document).ready(function () {
    var dt = $('#tabla_venta').DataTable({
        "dom": 'T<"clear">lfrtip',
        "ajax": "funciones/productosEnVenta.php", //aquí obtiene el archivo con la información
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "defaultContent": ''
        },

        {
            "data": "descripcion"
        },
        {
            "data": "cantActual"
        },
        {
            "data": "precio"
        },
        {
            "data": "cantidad"
        },
        {
            "data": "subtotal",
            "className": 'text-center'
        },
        {
            "data": "acciones",
            "className": 'text-center'
        }

        ],


        "order": [
            [1, 'desc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#tabla_venta tbody').on('click', 'td.details-control', function () {
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
