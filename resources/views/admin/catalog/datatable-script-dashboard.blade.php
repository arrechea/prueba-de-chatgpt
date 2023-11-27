<script>
    (function ($) {
        $(document).ready(function () {
            var table = $('#datatable_{{$micro}}');
            var columnasSinEdicion = [];
            var columnas = table.find('>thead>tr>th');
            columnas.each(function (i, e) {
                if ($(e).is('.notOrdenable')) {
                    columnasSinEdicion.push(i)
                }
            });

            let other_filters = table.data('other-filters');
            let filters = $(other_filters !== '' ? ('#' + other_filters) : '#datatable_custom_filters_{{$micro}}');
            let order = JSON.parse("{{json_encode($catalogo->GetDefaultOrderArrayForDataTables())}}".replace(/&quot;/g, '"'));

            var tableData = table
                .on('preXhr.dt', function (e, settings, data) {
                    var info = filters;
                    data.filters = info.find(':input').serializeArray();
                })
                .DataTable({
                    "serverSide": true,
                    "columnDefs": [
                        {
                            "orderable": false, "targets": columnasSinEdicion
                        }
                    ],
                    searching: false,
                    "bLengthChange": false,
                    "language": {
                        "decimal": "{{__('datatable.decimal')}}",
                        "emptyTable": "{{__('datatable.emptyTable')}}",
                        "info": "{{__('datatable.info')}}",
                        "infoEmpty": "{{__('datatable.infoEmpty')}}",
                        "infoFiltered": "{{__('datatable.infoFiltered')}}",
                        "infoPostFix": "{{__('datatable.infoPostFix')}}",
                        "thousands": "{{__('datatable.thousands')}}",
                        "lengthMenu": "{{__('datatable.lengthMenu')}}",
                        "loadingRecords": "{{__('datatable.loadingRecords')}}",
                        "processing": "{{__('datatable.processing')}}",
                        "search": "{{__('datatable.search')}}",
                        "zeroRecords": "{{__('datatable.zeroRecords')}}",
                        "paginate": {
                            "first": "{{__('datatable.paginate.first')}}",
                            "last": "{{__('datatable.paginate.last')}}",
                            "next": "{{__('datatable.paginate.next')}}",
                            "previous": "{{__('datatable.paginate.previous')}}"
                        },
                        "aria": {
                            "sortAscending": "{{__('datatable.aria.sortAscending')}}",
                            "sortDescending": "{{__('datatable.aria.sortDescending')}}"
                        }
                    },
                    "drawCallback": function (dat) {
                        var modals = table.find('.modal');
                        InitModals(modals);

                        if (dat.json.data.length < 3){
                            var addData = '<tr class="add"> <td> <a href="'+ addDataRoute + '"> <i class="material-icons">add</i> <p>Agregar nueva entrada</p> </a> </td> </tr>';
                            $('.dataTable tbody').append(addData);
                        }

                        let foot = table.find('tfoot');
                        foot.empty();
                        foot.append(dat.json.footer);
                        window.datatable_{{$micro}} = tableData;
                    },
                    "order": order
                });

            var updatingTimeOut = null;
            filters.find('input,select').on('change keyup', function () {
                if (updatingTimeOut) {
                    clearTimeout(updatingTimeOut);
                }
                updatingTimeOut = setTimeout(function () {
                    tableData.draw();
                }, 300);
            });
            window.dataTable = {
                "datatable_{{$micro}}--redraw": function () {
                    tableData.draw();
                }
            }
        });

    })(jQuery);
</script>
