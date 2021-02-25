$(document).ready(function () {
    var dt = $('#tabla_ajustessol').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": dataJson.data,
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-' + pyme + '"></i> </div>',
        },
        {
            "data": "sucursales"
        },
        {
            "data": "cliente"
        },
        {
            "data": "montoTotal"
        },
        {
            "data": "usuario"
        },
        {
            "data": "fecha"
        },
       
        {
            "data": null,
            render: function (data, type, row) {
                return "<center><button id=btnSave"+data.id+" onclick='estatusSolic("+data.id+",3,"+data.idSucursal+");' type='button' class='btn btn-success btn-circle btn-circle-tablita' title='Aprobar'><i class=' fas fa-check'></i></button><button type='button' class='btn btn-danger btn-circle-tablita btn-circle' onclick='estatusSolic("+data.id+",4, "+data.idSucursal+");' title='Cancelar'><i class='fa fa-times'></i></button><span id='res"+data.id+"'></span></center> ";
                /* "<center><button id=btnSave\",ajs.id, \" onclick='estatusSolic(\", ajs.id, \", 3);' type='button' class='btn btn-success btn-circle btn-circle-tablita'
	                title='Aprobar'><i class=' fas fa-check'></i></button><button type='button' class='btn btn-danger btn-circle-tablita btn-circle' onclick='estatusSolic(\",
                   ajs.id, \", 5);' title='Cancelar'><i class='fa fa-times'></i></button><span id='res\", ajs.id,\"'></span></center> \"	*/
                //return '<div class="text-center">'+data.id+'<i id="itable" class="text-pyme fas fa-plus-circle"></i> </div>';
            }
        }

        ],


        "order": [
            [5, 'desc']
        ]
    });


    //Add event listener for opening and closing details
    var o = this;
    $('#tabla_ajustessol tbody').on('click', 'td.details-control', function () {
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