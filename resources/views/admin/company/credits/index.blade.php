@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.credits.tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h5 class="header">{{__('credits.Credits')}}</h5>
                @include('admin.catalog.table')
            </div>
        </div>
    </div>
@endsection
