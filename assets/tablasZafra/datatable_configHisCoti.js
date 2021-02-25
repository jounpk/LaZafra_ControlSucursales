
$(document).ready(function () {
    var dt = $('#table-cotiHis').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": datsJson.data,
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-' + pyme + '"></i> </div>',
        },
        {
            "data": "folio" //tocket
        },
        {
            "data": "cliente" //sucursal Salida
        },
        {
            "data": "montoTotal" //usuario Envia
        },
        {
            "data": "usuario" //fechaEnvio
        },//'<span class="label ' . $datos['etiquetita'] . ' label-rounded">' . $datos['etiquetitaText'] . '</span>';

        {
            "data": null,
            render: function (data, type, row) {
                resultado = '';
                if (data.estatus == '4') {
                    resultado = '<span class="label badge-dark label-rounded">Rechazada</span>';

                } else if (data.estatus == '5') {
                    resultado = '<span class="label label-info label-rounded">Utilizada</span>';

                } else if (data.estatus == '2' || data.estatus == '1') {
                    resultado = '<span class="badge badge-light">Pendiente</span>';
                }
                else {
                    resultado = '<span class="label ' + data.etiquetita + ' label-rounded">' + data.etiquetitaText + '</span>';
                }
                return resultado;
            }
        },
        {
            "data": "fecha" //usuario Envia
        },
        {
            "data": null,
            render: function (data, type, row) {

                if (data.estatus == '2') {
                    return "<small>Por autorizar</small>";
                }
                else if (data.estatus == '1') {
                    return "<small>En Captura</small>";
                }
                else if (data.estatus != '2') {
                    return ' <a href="../funciones/imprimeTicketCotizacion.php?idCotizacion=' + data.id + '" target="_blank" class="btn btn-sm btn-outline-success btn-square" title="Imprimir Ticket" disabled><i class="fas fa-file-alt"></i></a><a href="../imprimePdfCotizacion.php?idCotizacion=' + data.id + '" target="_blank" class="btn btn-sm btn-outline-danger btn-square" title="Imprimir PDF" disabled><i class="fas fa-file-pdf"></i></a>';
                }
            }
        }


        ],

        "buttons": [
            'excel',
            'print'
        ],
        "order": [
            [6, 'desc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#table-cotiHis tbody').on('click', 'td.details-control', function () {
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