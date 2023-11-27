@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('gafafit.Payments')}}</h5>

                @include('admin.gafafit.payment_types.form')
            </div>
        </div>
    </div>
@endsection
