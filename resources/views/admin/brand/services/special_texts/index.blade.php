@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.services.tabs')
        <div class="row">
            <div class="card-panel" id="service-edit-panel">
                @include('admin.brand.services.special_texts.form')
            </div>
        </div>
    </div>
@endsection
