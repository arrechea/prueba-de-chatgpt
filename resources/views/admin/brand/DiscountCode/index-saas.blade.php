@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.marketing-tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h5 class="header">{{__('discounts.DiscountCodes')}}</h5>
                <p>
                    <a class="btn btn-small waves-effect waves-light"
                       href="{{route('admin.company.brand.discount-code.create', [
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
