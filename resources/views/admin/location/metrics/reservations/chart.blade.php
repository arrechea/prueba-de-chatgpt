@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.location.metrics.reservations.tabs')
        <div class="card-panel radius--forms">
            <h5 class="header card-title">{{__('metrics.reservations-metrics')}}</h5>
            <h5 class="">{{__('metrics.reservations.byLocation')}}</h5>
            <div class="card-panel panelcombos">
                @include('charts.chart',$chart)
            </div>
            <h5 class="">{{__('metrics.reservations.byRoom')}}</h5>
            <div class="card-panel panelcombos">
                @include('admin.catalog.table')
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
                    drawChartreservationsLocation();
                }, 1000);
            })
        }
    </script>
@endsection
