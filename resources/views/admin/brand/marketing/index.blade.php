@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('marketing.OffersList')}}</h5>

            </div>
        </div>
    </div>
@endsection
