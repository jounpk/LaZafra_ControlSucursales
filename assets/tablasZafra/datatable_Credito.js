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
        "data": jsonData.data,
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
            "data": "format_totalDeuda"
        },
        {
            "data": "format_totalResta"
        },
        {
            "data": "credBrindados"
        },

        {
            "data": null,
            render: function(data, type, row){
              //return '<div class="text-center">'+data.id+'<i id="itable" class="text-pyme fas fa-plus-circle"></i> </div>';
              return '<div class="col-12 text-right">'+data.format_limCredito+'<br><div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: '+data.porcentaje+'%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div></div>';
            }
        }
        ],

        "buttons": [
            'excel',
            'print'
        ],
        "order": [
            [2, 'desc']
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
