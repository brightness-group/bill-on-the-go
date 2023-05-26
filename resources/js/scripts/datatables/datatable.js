/*=========================================================================================
    File Name: datatables-basic.js
    Description: Basic Datatable
    ----------------------------------------------------------------------------------------
    Item Name: Frest HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(document).ready(function() {

    /****************************************
    *       js of zero configuration        *
    ****************************************/
    var $locale = document.getElementsByTagName("html")[0].getAttribute("lang");
    if ($locale === "en") {
        $('.zero-configuration').DataTable();
    } else {
        $('.zero-configuration').DataTable({
            "language": {
                "sEmptyTable": "Keine Daten in der Tabelle vorhanden",
                "sInfo": "_START_ bis _END_ von _TOTAL_ Einträgen",
                "sInfoEmpty": "Keine Daten vorhanden",
                "sInfoFiltered": "(gefiltert von _MAX_ Einträgen)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ Einträge anzeigen",
                "sLoadingRecords": "Wird geladen ..",
                "sProcessing": "Bitte warten ..",
                "sSearch": "Suchen",
                "sZeroRecords":	"Keine Einträge vorhanden",
                "oPaginate": {
                    "sFirst": "Erste",
                    "sPrevious": "Zurück",
                    "sNext": "Nächste",
                    "sLast": "Letzte",
                },
                "oAria": {
                    "sSortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                    "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren",
                },
                "select": {
                    "rows": {
                        "0": "",
                        "1": "1 Zeile ausgewählt",
                        "_": "%d Zeilen ausgewählt",
                    }
                },
                "buttons": {
                    "print": "Drucken",
                    "colvis": "Spalten",
                    "copy": "Kopieren",
                    "copyTitle": "In Zwischenablage kopieren",
                    "copyKeys": "Taste <i>ctrl</i> oder <i>⌘</i> + <i>C</i> um Tabelle<br>in Zwischenspeicher zu kopieren.<br><br>Um abzubrechen die Nachricht anklicken oder Escape drücken.",
                    "copySuccess": {
                        "1": "1 Zeile kopiert",
                        "_": "%d Zeilen kopiert",
                    },
                    "pageLength": {
                        "-1": "Zeige alle Zeilen",
                        "_": "Zeige %d Zeilen",
                    }
                }
            }
        });
    }

    /********************************************
     *        js of Order by the grouping        *
     ********************************************/

    var groupingTable = $('.row-grouping').DataTable({
        "columnDefs": [{
            "visible": false,
            "targets": 2
        }],
        "order": [
            [2, 'asc']
        ],
        "displayLength": 10,
        "drawCallback": function(settings) {
            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var last = null;

            api.column(2, {
                page: 'current'
            }).data().each(function(group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    $('.row-grouping tbody').on('click', 'tr.group', function() {
        var currentOrder = groupingTable.order()[0];
        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            groupingTable.order([2, 'desc']).draw();
        }
        else {
            groupingTable.order([2, 'asc']).draw();
        }
    });

    /*************************************
    *       js of complex headers        *
    *************************************/

    $('.complex-headers').DataTable();


    /*****************************
    *       js of Add Row        *
    ******************************/

    var t = $('.add-rows').DataTable();
    var counter = 2;

    $('#addRow').on( 'click', function () {
        t.row.add( [
            counter +'.1',
            counter +'.2',
            counter +'.3',
            counter +'.4',
            counter +'.5'
        ] ).draw( false );

        counter++;
    });


    /**************************************************************
    * js of Tab for COLUMN SELECTORS WITH EXPORT AND PRINT OPTIONS *
    ***************************************************************/

    $('.dataex-html5-selectors').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                text: 'JSON',
                action: function ( e, dt, button, config ) {
                    var data = dt.buttons.exportData();

                    $.fn.dataTable.fileSave(
                        new Blob( [ JSON.stringify( data ) ] ),
                        'Export.json'
                    );
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });

    /**************************************************
    *       js of scroll horizontal & vertical        *
    **************************************************/

    $('.scroll-horizontal-vertical').DataTable( {
        "scrollY": 200,
        "scrollX": true
    });
});
