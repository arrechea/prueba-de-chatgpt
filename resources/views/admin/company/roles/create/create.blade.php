@extends('admin.layout.master')

@section('content')
    <div class="main-container">
        @include('admin.company.Administrator.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('roles.title-create')}}</h5>

                @include('admin.company.roles.form')
            </div>
        </div>
    </div>
@endsection
