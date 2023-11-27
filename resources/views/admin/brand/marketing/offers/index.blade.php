@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.tabs')
        <div class="row">
            <div class="card-panel">
                <h5 class="card-title header">{{__('marketing.OffersList')}}</h5>
                <p>
                    <a class="btn btn-small waves-effect waves-light"
                       href="{{route('admin.company.brand.marketing.offers.create', [
                        'company' => $company,
                        'brand'   => $brand,
                    ])}}">
                        <span>{{__('marketing.New')}}</span>

                    </a>
                </p>
                @include('admin.catalog.table')
            </div>
        </div>
    </div>
@endsection
