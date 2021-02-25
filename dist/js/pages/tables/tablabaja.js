$(window).on('load', function() {
    // Row Toggler
    // -----------------------------------------------------------------
    $('#demo-foo-row-toggler').footable();

    // Accordion
    // -----------------------------------------------------------------
    $('#demo-foo-accordion').footable().on('footable_row_expanded', function(e) {
        $('#demo-foo-accordion tbody tr.footable-detail-show').not(e.row).each(function() {
            $('#demo-foo-accordion').data('footable').toggleDetail(this);
        });
    });

    // Accordion
    // -----------------------------------------------------------------
    $('#demo-foo-accordion2').footable().on('footable_row_expanded', function(e) {
        $('#demo-foo-accordion2 tbody tr.footable-detail-show').not(e.row).each(function() {
            $('#demo-foo-accordion').data('footable').toggleDetail(this);
        });
    });

    // Pagination & Filtering
    // -----------------------------------------------------------------
    $('[data-page-size]').on('click', function(e) {
        e.preventDefault();
        var newSize = $(this).data('pageSize');
        FooTable.get('#demo-foo-pagination').pageSize(newSize);
    });
    $('#demo-foo-pagination').footable();

    $('#demo-foo-addrow').footable();

    var addrow = $('#demo-foo-addrow2');
    addrow.footable().on('click', '.delete-row-btn', function() {

        //get the footable object
        var footable = addrow.data('footable');

        //get the row we are wanting to delete
        var row = $(this).parents('tr:first');

        //delete the row
        footable.removeRow(row);
    });



});