@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.gafafit.users.tabs')
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.gafafit.users.form')
            </div>
        </div>
    </div>
@endsection
