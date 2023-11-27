@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header">{{__('metrics.staff-metrics')}}</h5>
                <div class="card">
                    <div class="card-content">
                        <div class="card-tittle"></div>
                        @include('admin.catalog.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
