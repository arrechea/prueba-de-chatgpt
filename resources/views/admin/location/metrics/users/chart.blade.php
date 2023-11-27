@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.location.metrics.reservations.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header card-title">{{__('metrics.users-metrics')}}</h5>

                @foreach($catalogs as $catalog)
                    <h5>{{$catalog['title']}}</h5>
                    <div class="card-panel panelcombos">
                        <div class="row">
                            <?php
                            $catalogo = $catalog['catalogo'];
                            $ajaxDatatable = $catalog['url'];
                            ?>

                            @include('admin.catalog.table')
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection
