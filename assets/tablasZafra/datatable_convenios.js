$(document).ready(function() {

    var dt = $('#tabla_convenios').DataTable({
        'rowCallback': function(row, data, index) {


        },
        "dom": 'T<"clear">lfrtip',

        "data": jsonData.data, //{}
        "columns": [{
                "class": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-pyme" id="itable"></i> </div>',
            },
            {
                "data": "id"
            },

            {
                "data": "nombre"
            },
            {
                "data": "monto"
            },
            {
                "data": "credito"
            },
            {
                "data": "fecha"
            },
            {
                "data": "usuario"
            }
           


        ],

        "buttons": [
            'excel',
            'print'
        ],
        "order": [
            [2, 'asc']
        ]
    });

    $("#itable").click(function() {
        //alert('Haciendo Change');
        $(this).toggleClass("fa-plus-circle");
        $(this).toggleClass("fa-minus-circle");
    });

    //Add event listener for opening and closing details
    var o = this;
    $('#tabla_convenios tbody').on('click', 'td.details-control', function() {
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
        return '<div id="DIV' + d.id + '" class="col-lg-12">' +
            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
            '</div>';
    };

    var cargaContenido = function(d) {
        ejecutandoCarga(d.id);
    };
});