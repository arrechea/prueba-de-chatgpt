@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.export.tabs')
        <div class="row">
            {{--@dd(\App\Librerias\Metrics\ExportMetrics\LibExportMetrics::allUsers()->first())--}}
            <div class="card-panel radius--forms"><h5 class="header">{{__('metrics.export')}}</h5>
                @include('admin.brand.metrics.export.formexportMembership')
            </div>
        </div>
    </div>
@endsection
