$(document).ready(function() {

    var dt = $('#tabla_nofact').DataTable({
        'rowCallback': function(row, data, index) {


        },
        "dom": 'T<"clear">lfrtip',

        "data": jsonData.data, //{}
        "columns": [{
                "class": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-pyme itable"></i> </div>',
            },
                     
           {
                "data": "idVenta"
            }, 
            {
                "data": "sucursal"
            }, 
            {
                "data": "cliente"
            }, 
            {
                "data": "subTotal"
            }, 
            {
                "data": "total"
            }, 
            {
                "data": "usuario"
            }, 
            {
                "data": "fecha"
            }, 
            {
                "data":"idVenta",
                render: function ( data, type, row ) {
                    if ( type === 'display' ) {
                        return '<input type="checkbox" name="facturar[]" class="ParaFacturar" value="'+data+'">';
                    }else{
                        return data;
                    }
                  
                },
                className: "dt-body-center"
            }
            


        ],
        rowCallback: function ( row, data ) {
            // Set the checked state of the checkbox in the table
            $('input.ParaFacturar', row).prop( 'checked', data.active == 1 );
        },
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
    $('#tabla_nofact tbody').on('click', 'td.details-control', function() {
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
        return '<div id="DIV' + d.idVenta + '" class="col-lg-12">' +
            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
            '</div>';
    };

    var cargaContenido = function(d) {
        ejecutandoCarga(d.idVenta);
    };
});