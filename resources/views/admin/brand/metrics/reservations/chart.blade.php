@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header">{{__('metrics.reservations-metrics')}}</h5>
                <div class="card">
                    <div class="card-content">
                        <h5 class="header">{{__('metrics.reservations.byLocation')}}</h5>
                        @include('charts.chart',$chart)
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h5 class="header">{{__('metrics.reservations.TotalsByLocation')}}</h5>
                        <?php
                        $catalogo = $catalogTotals;
                        $ajaxDatatable = $ajaxDatatableTotals;
                        ?>
                        @include('admin.catalog.table')

                        <h5 class="header">{{__('metrics.reservations.TotalsByRoom')}}</h5>
                        <?php
                        $catalogo = $catalogRooms;
                        $ajaxDatatable = $ajaxDatatableRooms;
                        ?>
                        @include('admin.catalog.table')
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
                    drawChartreservationsLocation();
                }, 1000);
            })
        }
    </script>
@endsection
