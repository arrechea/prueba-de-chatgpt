@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.location.metrics.reservations.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header card-title">{{__('metrics.staff-metrics')}}</h5>
                <div class="card-panel panelcombos">
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div>
@endsection
