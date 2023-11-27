@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.Administrator.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('administrators.Administrators')}}</h5>

                <div class="card-panel panelcombos">
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div>
@endsection
