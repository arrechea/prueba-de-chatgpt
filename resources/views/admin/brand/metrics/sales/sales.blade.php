@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.tabs')
        <div class="row">

            <div class="card-panel radius--forms">
                <h5 class="header">{{__('metrics.sales-metrics')}}</h5>
                <div class="card">
                    <div class="card-content">
                        <h5 class="card-tittle">{{__('metrics.sales-metrics.time')}}</h5>
                        <div class="card-tittle"></div>
                        @include('charts.chart',$chart)
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        @foreach($catalogos as $catalog)
                            <?php
                            $catalogo = $catalog['catalogo'];
                            $ajaxDatatable = $catalog['ajaxDatatable'];
                            ?>
                            <h5 class="card-tittle">{{$catalog['title']}}</h5>
                            @include('admin.catalog.table')
                        @endforeach
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
