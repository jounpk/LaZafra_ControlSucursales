$(document).ready(function() {
    var dt = $('#table-traspaso').DataTable({
        "dom": 'T<"clear">lfrtip',
        "data": datsJson.data,
        "columns": [{
                "class": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<div class="text-center"><i class="fas fa-plus-circle text-' + pyme + '"></i> </div>',
            },
            {
                "data": "id" //tocket
            },
            {
                "data": "sucSalida" //sucursal Salida
            },
            {
                "data": "usuarioEnvio" //usuario Envia
            },
           {
                "data": "fechaEnvio" //fechaEnvio
            },
           {
                "data": "sucEntrada"   //Sucursal Entrada
            },
             {
                "data": null,
                render: function(data, type, row) {
                   // estatus = data.estatusRec;
                    //estatus_rec='';
                    if(data.estatusRec=='3'){
                        estatus_rec='<div class="text-center"><i class=\"fas fa-check text-center  text-success\"></i></div>';
                    }else{
                        estatus_rec='<div class="text-center"><i class=\"fas fa-clock textcenter  text-danger\"></i></div> ';
                    }
                    return estatus_rec;
                }
            },
           {
                "data": "usuarioRec"
            },
            {
                "data": "fechaRec"
            },
           {
                "data": null,
                render: function(data, type, row) {
                    estatus = data.estatusBodeguero;
                    estatus_Bodeguero='';
                    if(estatus=='2'){
                        estatus_Bodeguero='<div class="text-center"><i class=\"fas fa-check text-center  text-success\"></i></div>';
                    }else{
                        estatus_Bodeguero='<div class="text-center"><i class=\"fas fa-clock textcenter  text-danger\"></i></div> ';
                    }
                    return estatus_Bodeguero;
                }},
               {
                    "data": null,
                    render: function(data, type, row) {
                       boton=' <button type="button" class="btn bg-success btn-circle muestraSombra" onClick="muestraTicket('+data.id+')"><i class="fas fa-print text-white"></i></button>';
                        return boton;
                    }},


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