@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        <div class="row">
            <div class="card-panel">
                <h5 class="card-title header">{{__('company.Companies')}}</h5>

                @include('admin.catalog.table')
            </div>
        </div>
    </div>
@endsection
