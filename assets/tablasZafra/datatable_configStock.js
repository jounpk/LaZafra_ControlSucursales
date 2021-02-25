$(document).ready(function () {

    var dt = $('#table-stock').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": datsJson.data,
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-' + pyme + '"></i> </div>',
        },
        {
            "data": "seguimientoStock"
        },
        {
            "data": "descripcion"
        },
        {
            "data": "marca"
        },
        {
            "data": "precio"
        },
        {
            "data": "dpto"
        },
        {
            "data": "stock"
        },
        {
            "data": "fechaEdi"
        }


        ],
        responsive: false,
				dom: 'B<"clear">lfrtip',
				fixedColumns: true,
				fixedHeader: true,
				scrollCollapse: true,
				bSort: true,
				autoWidth: true,
				scrollCollapse: true,
				lengthMenu: [
					[5, 25, 50, -1],
					[5, 25, 50, "Todo"]
				],
				info: true,

        "buttons": [{
            extend: 'excelHtml5',
            className: 'text-white btn bg-'+pyme,
            text: "Excel",
            exportOptions: {
                columns: ":not(.no-exportar)"
            }
        },
        {
            extend: 'csvHtml5',
            className: 'text-white btn bg-'+pyme,
            text: "Csv",
            exportOptions: {
                columns: ":not(.no-exportar)"
            }
        },
        {
            extend: 'pdfHtml5',
            className: 'text-white btn bg-'+pyme,
            text: "Pdf",
            exportOptions: {
                columns: ":not(.no-exportar)"
            },

        },
        {
            extend: 'copy',
            className: 'text-white btn bg-'+pyme,
            text: "Copiar",
            exportOptions: {
                columns: ":not(.no-exportar)"
            }
        }
        ],
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "decimal": ",",
            "thousands": "."
        },
        "order": [
            [1, 'asc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#table-stock tbody').on('click', 'td.details-control', function () {
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