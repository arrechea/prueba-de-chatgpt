@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.location.reservations.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header">{{__('brand.Reservations')}}</h5>

            </div>
        </div>
    </div>
@endsection
