@extends('admin.layout.master')

@section('content')
    <div class="main-container">
        @include('admin.company.users.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                @include('admin.company.user_categories.form')
            </div>
        </div>
    </div>
@endsection
