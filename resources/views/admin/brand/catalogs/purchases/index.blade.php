@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.catalogs.tabs')
        <div class="row">
            <div class="card-panel">
                <h5 class="card-title header">{{__('purchases.List')}}</h5>
                @include('admin.catalog.table')
            </div>
        </div>
    </div>
@endsection
