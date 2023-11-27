@extends('admin.layout.master')

@section('content')
    <div class="main-container">
        @include('admin.company.users.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <div class="card-panel panelcombos">
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div>
@endsection
