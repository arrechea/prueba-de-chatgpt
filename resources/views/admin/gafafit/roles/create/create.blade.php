@extends('admin.layout.master')

@section('content')
    <div class="main-container">
        @include('admin.gafafit.administrators.components.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('roles.title-create')}}</h5>
                @include('admin.gafafit.roles.form')
            </div>
        </div>
    </div>
@endsection
