@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.metrics.export.tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h5 class="header">{{__('metrics.export')}}</h5>
                @include('admin.brand.metrics.export.form')
            </div>
        </div>
    </div>
@endsection
