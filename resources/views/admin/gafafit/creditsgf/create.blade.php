@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.gafafit.creditsgf.tabs')
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.gafafit.creditsgf.form')
            </div>
        </div>
    </div>
@endsection
