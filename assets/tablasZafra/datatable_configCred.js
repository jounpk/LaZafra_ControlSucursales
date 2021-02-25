$(document).ready(function () {

    var dt = $('#tabla_creditos').DataTable({
        'rowCallback': function(row, data, index) {
            if (parseInt(data.montoDeudor) == 0) {
                $(row).css('color', 'green');
               
            }
            else if (parseInt(data.totalDeuda)>= parseInt(data.montoDeudor) && data.estatus!='3') {
                $(row).css('color', 'red');
            }
            if(data.estatus=='3'){
                $(row).css('background', '#EEEBEA');
            }

        },
        "dom": 'T<"clear">lfrtip',
        "data": datsJson.data,
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-pyme i_table"></i> </div>',
        },
        {
            "data": "nombre"
        },

        {
            "data": "idVenta"
        },
        {
            "data": "sg_tDeudor"
        },
        {
            "data": "sg_mDeudor"
        },

        {
            "data": "prestamista"
        },
        {
            "data": "fecha"
        },

        {
            "data": null,
            render: function (data, type, row) {
                estatus = data.estatus;
                icon_estatus="";
                icon_estatus=estatus=='1'?"<i class=' text-center fas fa-exclamation-triangle text-danger'></i>":icon_estatus;
                icon_estatus=estatus=='2'?"<i class=' text-center  fas fa-check text-success'></i>":icon_estatus;
                icon_estatus=estatus=='3'?"<i class=' text-center  fas fa-times text-dark'></i>":icon_estatus;

                return icon_estatus;
            }
        },
        {
            "data": null,
            render: function (data, type, row) {
                venta = data.idVenta;
                return `<button type="button" class="btn btn-info btn-circle muestraSombra" 
                title="Imprimir Ticket" onClick="imprimeTicketVenta('${venta}');"><i class="fas fa-print"></i></button>
                `;
            }
        }
        ],

        "buttons": [
            'excel',
            'print'
        ],
        "order": [
            [7, 'asc']
        ]
   
    });
    $(".i_table").click(function () {
        //alert('Haciendo Change');
        $(this).toggleClass("fa-plus-circle");
        $(this).toggleClass("fa-minus-circle");
    });
    //Add event listener for opening and closing details
    var o = this;
    $('#tabla_creditos tbody').on('click', 'td.details-control', function () {
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
        return '<div id="DIV' + d.id + '" class="col-lg-12">' +
            '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
            '</div>';
    };

    var cargaContenido = function (d) {
        ejecutandoCarga(d.id);
    };
});
