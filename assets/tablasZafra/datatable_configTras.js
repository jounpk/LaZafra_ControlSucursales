$(document).ready(function() {
    var dt = $('#table-traspaso').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": datsJson.data,
        "columns": [{
                "class": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-'+pyme+'"></i> </div>',
            },
            {
                "data": "idinput"
            },
            {
                "data": "fechaSolicitud"
            },
            {
                "data": "sucEntrada"
            },
            {
                "data": "usuarioSolicita"
            },
            {
                "data": "button"
            },


        ],

        "buttons": [
            'excel',
            'print'
        ],
        "order": [
            [1, 'asc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#table-traspaso tbody').on('click', 'td.details-control', function() {
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