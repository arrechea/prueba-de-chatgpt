@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.location.metrics.reservations.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header card-title">{{__('metrics.sales-metrics')}}</h5>

                <div class="card-panel panelcombos">
                    <label class="">{{__('metrics.sales-metrics.time')}}</label>
                    @include('charts.chart',$chart)
                </div>

                @foreach($catalogos as $catalog)
                    <?php
                    $catalogo = $catalog['catalogo'];
                    $ajaxDatatable = $catalog['ajaxDatatable'];
                    ?>
                    <h5 class="">{{$catalog['title']}}</h5>
                    <div class="card-panel panelcombos">
                        @include('admin.catalog.table')
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@section('jsPostApp')
    @parent
    <link rel="stylesheet" href="{{asset('css/admin/charts.css')}}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart', 'table'],
            language: '{{\Illuminate\Support\Facades\Lang::getLocale()}}'
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            $(document).ready(function () {
                setTimeout(function () {
                    drawChartsalesLocation()
                }, 1000);
            })
        }

    </script>


@endsection
