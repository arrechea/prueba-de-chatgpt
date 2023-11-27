@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header">{{__('metrics.users-metrics')}}</h5>

                @foreach($catalogs as $catalog)
                    <div class="card">
                        <div class="row">
                            <div class="col s12">
                                <div class="card-content" style="background: #e0f2f1">
                                    <div class="card-tittle"><h5 style="color: #e91e63">{{$catalog['title']}}</h5></div>
                                    <?php
                                    $catalogo = $catalog['catalogo'];
                                    $ajaxDatatable = $catalog['url'];
                                    ?>

                                    @include('admin.catalog.table')
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
