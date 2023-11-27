<div id="ChartDashboard_{{$id}}" class="Chart--Dashboard ChartDashboard__{{$clase}}"
     data-other-filters="{{$other_filters}}">
    <div id="ChartDashboard--filters--{{$id}}">{!! $filters !!}</div>
    <div id="{{$id}}" class="grafica_metrica {{$clase}}">
        <div style="font-size:8px;padding:20px;">Creando gráfica....</div>
    </div>
</div>

<script>
    var data_{{$id}} = null;
    var chart_{{$id}} = null;
    var tipo_chart__{{$id}} = null;
    var options_{{$id}} = {
//        fontSize: 8,
        animation: {
            duration: 500,
            easing: 'inAndOut',
            startup: true
        },
        'crosshair': {trigger: 'both', orientation: 'vertical', opacity: 0.5, color: 'blue'},
//        pointSize: 2,
//        backgroundColor: 'whitesmoke',
        legend: {textStyle: {fontSize: 10}, position: 'top'},
        vAxis: {textPosition: 'in'},
//        hAxis: {title: '', textStyle: {fontSize: 16}},
//        lineWidth: 3,
//        colors:['#e91e63'],

        'title': '{!!$titulo!!}',
        titleTextStyle: {bold: false},
        'height': '{!!$alto!!}',
        'is3D': true,
        selectionMode: 'multiple',
        tooltip: {trigger: 'selection'},
        aggregationTarget: 'product',
        chartArea: {left: 10, right: 10},
//        tooltip: {textStyle: {fontSize: 10}}
        {!!$options!!}
    };
    var ajaxUrl_{{$id}} = "{{$ajaxUrl}}";


    window.addEventListener("resize", function () {
        //Este es el sistema responsive
        if (chart_{{$id}})
            chart_{{$id}}.draw(data_{{$id}}, options_{{$id}});
    });

    /**
     * Obtiene info ajax actualizada
     */
    function {{"obtainChartInfo$id"}}() {
        var jsonData = null;
        if (ajaxUrl_{{$id}}) {
            let other_filters = $("#ChartDashboard_{{$id}}").data('other-filters');
            let filter_id = other_filters !== null && other_filters !== '' ? other_filters : 'ChartDashboard--filters--{{$id}}';
            var info = $('#' + filter_id);

            jsonData = $.ajax({
                url: ajaxUrl_{{$id}},
                dataType: "json",
                async: false,
                data: info.find(':input').serializeArray(),
            }).responseText;
        }

        // Create the data table.
        data_{{$id}} = new google.visualization.DataTable(jsonData);
        {!!$colums!!}
        {!!$rows!!}
    }

    /**
     * Imprime los chart
     */
    function {{"drawChart$id"}}(tipoChart, preventReloadData) {
        if (!preventReloadData) {
            {{"obtainChartInfo$id"}}();
        }


        // Instantiate and draw our chart, passing in some options.
        var chart_div = document.getElementById('{{$id}}');

        //Buscamos cual es el tipoChart que deseamos mostrar
        if (!tipoChart) {
            //Comprobaremos que no estemos dentro de un resize
            var botonActivo = $(chart_div).find('.Chart--Botonera__activo');
            if (botonActivo.length) {
                tipoChart = botonActivo.data('chart');
            } else {
                tipoChart = "{{$tipo}}";
            }
        }

        if (!chart_{{$id}}) {
            //Update info by filters
            let other_filters = $("#ChartDashboard_{{$id}}").data('other-filters');
            let filter_id = other_filters !== null && other_filters !== '' ? other_filters : 'ChartDashboard--filters--{{$id}}';
            $("#" + filter_id).find('[type="submit"]').on('click', function () {
                {{"obtainChartInfo$id"}}();
                chart_{{$id}}.draw(data_{{$id}}, options_{{$id}});
            });
        }

        //Si aun no tenemos un tipochart global para el id o es distinto necesitamos crear un nuevo chart
        if (tipo_chart__{{$id}}=== null || tipo_chart__{{$id}} !== tipoChart) {

            chart_{{$id}} = new google.{{$visualization}}[tipoChart](chart_div);
            tipo_chart__{{$id}} = tipoChart;

            //Crear Botonera
            google.visualization.events.addListener(chart_{{$id}}, 'ready', function () {
                crearBotonera{{$id}}(chart_div, tipoChart);
                $(chart_div).data('typechart', tipoChart);
                {{--$('body').one('click', '[data-action="minifyMenu"],[data-action="toggleMenu"]', function () {--}}
                {{--setTimeout(function () {--}}
                {{--chart_{{$id}}.draw(data_{{$id}}, options_{{$id}});--}}
                {{--}, 1);--}}
                {{--});--}}
            });
        }

        chart_{{$id}}.draw(data_{{$id}}, options_{{$id}});
    };

    /**
     * Adjunta los manejadores del chart
     */
    function {{"crearBotonera$id"}}(chart_div, tipoChart) {
        var chartsExtra = {!! $extra_charts !!};
        if (Array.isArray(chartsExtra)) {
            var caja = $('<div class="Chart--Botonera"></hola>');//creamos botonera
            if (chartsExtra.length) {
                if (chartsExtra.length) {
                    chartsExtra.forEach(function (chart, indice) {
                        var selected = tipoChart ?
                            (chart === tipoChart ? 'Chart--Botonera__activo' : '') : (indice === 0 ? 'Chart--Botonera__activo' : '');
                        caja.append('<span class="Chart--icon--' + chart + ' ' + selected + '" data-chart="' + chart + '" title="' + chart + '"></span>');
                    });
                }
                var $chart_div = $(chart_div);
                $chart_div.append(caja);//incrustamos Botonera
                $chart_div.find('[data-chart]').one('click', function () {
                    drawChart{{$id}}($(this).data('chart'), true);
                })
            }
        }
    }
</script>
