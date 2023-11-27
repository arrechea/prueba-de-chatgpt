@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header">{{__('metrics.reservations-metrics')}}</h5>

                <div class="card">
                    <div class="card-content">
                        @include('charts.chart',$chart)
                        <div id="compare-percentage-chart"
                             hidden>
                            @include('charts.chart',$compare_occupation_chart)
                        </div>


                    </div>

                    <div class="card-content">
                        @include('charts.chart',$chart_totals)
                        <div id="compare-totals-chart"
                             hidden>
                            @include('charts.chart',$compare_totals_chart)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    <link rel="stylesheet" href="{{asset('css/admin/charts.css')}}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'table']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            $(document).ready(function () {
                setTimeout(function () {
                    drawChartlocationOccupation();
                    drawChartlocationTotals();
                }, 1000);
            })
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#compare-switch').on('change', function () {
                if ($(this).prop('checked')) {
                    $('#compare-start').prop('disabled', false);
                    $('#compare-end').prop('disabled', false);
                    $('#compare-percentage-chart').show();
                    $('#compare-totals-chart').show();
                    drawChartcompareLocationOccupation();
                    drawChartcompareLocationTotals();
                } else {
                    $('#compare-start').prop('disabled', true);
                    $('#compare-end').prop('disabled', true);
                    $('#compare-percentage-chart').hide();
                    $('#compare-totals-chart').hide();
                }
            });

            // $('#hour-picker').lolliclock()
        })
    </script>
@endsection
