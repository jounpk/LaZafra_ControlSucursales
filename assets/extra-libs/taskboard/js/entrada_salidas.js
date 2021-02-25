$(function() {
    /**
     * Created by Zura on 4/5/2016.
     */
    $(function() {
        Lobibox.notify.DEFAULTS = $.extend({}, Lobibox.notify.DEFAULTS, {
            size: 'mini',
            // delay: false,
            position: 'right top'
        });

        //Basic example
        $('#todo-lists-basic-demo').lobiList({
            // Default action buttons for all lists
            controls: [],
            lists: [{
                    id: '1',
                    title: '<i class=" fas fa-arrow-down"></i> Ajuste de Entrada',
                    defaultStyle: 'lobilist-success',
                    items: datsJsonEntrada.data

                }, {
                    id: '2',
                    title: '<i class="fas fa-arrow-up"></i> Ajuste de Salida',
                    defaultStyle: 'lobilist-danger',
                    items: datsJsonSalida.data

                },

            ]
        });

        // Event handling
        (function() {
            var list;

            $('#todo-lists-initialize-btn').click(function() {
                list = $('#todo-lists-demo-events')
                    .lobiList({
                        init: function() {
                            Lobibox.notify('default', {
                                msg: 'init'
                            });
                        },
                        beforeDestroy: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeDestroy'
                            });
                        },
                        afterDestroy: function() {
                            Lobibox.notify('default', {
                                msg: 'afterDestroy'
                            });
                        },
                        beforeListAdd: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeListAdd'
                            });
                        },
                        afterListAdd: function() {
                            Lobibox.notify('default', {
                                msg: 'afterListAdd'
                            });
                        },
                        beforeListRemove: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeListRemove'
                            });
                        },
                        afterListRemove: function() {
                            Lobibox.notify('default', {
                                msg: 'afterListRemove'
                            });
                        },
                        beforeItemAdd: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeItemAdd'
                            });
                        },
                        afterItemAdd: function() {
                            console.log(arguments);

                            Lobibox.notify('default', {
                                msg: 'afterItemAdd'
                            });
                        },
                        beforeItemUpdate: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeItemUpdate'
                            });
                        },
                        afterItemUpdate: function() {
                            console.log(arguments);
                            Lobibox.notify('default', {
                                msg: 'afterItemUpdate'
                            });
                        },
                        beforeItemDelete: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeItemDelete'
                            });
                        },
                        afterItemDelete: function() {
                            Lobibox.notify('default', {
                                msg: 'afterItemDelete'
                            });
                        },
                      /*  beforeListDrop: function() {
                            Lobibox.notify('default', {
                                msg: 'beforeListDrop'
                            });
                        },*/
                        afterListReorder: function() {

                            Lobibox.notify('default', {
                                msg: 'afterListReorder'
                            });
                        },
                      /*  beforeItemDrop: function() {

                            Lobibox.notify('default', {
                                msg: 'beforeItemDrop'
                            });

                        },*/
                        afterItemReorder: function() {


                            Lobibox.notify('default', {
                                msg: 'afterItemReorder'
                            });

                        },
                        afterMarkAsDone: function() {
                            Lobibox.notify('default', {
                                msg: 'afterMarkAsDone'
                            });
                        },
                        afterMarkAsUndone: function() {
                            Lobibox.notify('default', {
                                msg: 'afterMarkAsUndone'
                            });
                        },



                    })
                    .data('lobiList');
            });

            $('#todo-lists-destroy-btn').click(function() {
                list.destroy();
            });
        })();
        // Custom controls
        $('#todo-lists-demo-controls').lobiList({

        });
        // Disabled drag & drop
        /*$('#todo-lists-demo-sorting').lobiList({
            sortable: false,
            lists: [{
                    title: 'Todo',
                    defaultStyle: 'lobilist-info',
                    controls: ['edit', 'styleChange'],
                    items: [{
                        title: 'Floor cool cinders',
                        description: 'Thunder fulfilled travellers folly, wading, lake.',
                        dueDate: '2015-01-31'
                    }]
                },
                {
                    title: 'Controls disabled',
                    controls: false,
                    items: [{
                        title: 'Composed trays',
                        description: 'Hoary rattle exulting suspendisse elit paradises craft wistful. Bayonets allures prefer traits wrongs flushed. Tent wily matched bold polite slab coinage celerities gales beams.'
                    }]
                }
            ]
        });*/



        $('.datepicker').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        $('.lobilist').perfectScrollbar();
        $('.lobilist-wrapper').addClass('col-md-6 m-0 col-xs-12  col-sm-6');


       /* $('.lobilist').droppable({
           // drop: function(ev, ui) { cambiartipo(ui.draggable[0].dataset.id); }
        });*/
       // $('.drag-handler').addClass("bg-dark");


    });
});