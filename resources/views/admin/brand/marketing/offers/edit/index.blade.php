@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.tabs')
        <div class="row">
            <div class="card-panel">
                @include('admin.brand.marketing.offers.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        window.Offer = {
            lang: {
                buy_get: "{{__('marketing.BuyGetGet')}}",
                buy_buy: "{{__('marketing.BuyGetBuy')}}",
                code: "{{__('marketing.Code')}}",
                discount_number: " {{__('marketing.DiscountNumber')}}",
                discount_type: "{{__('marketing.DiscountType')}}",
                price: "{{__('marketing.Price')}}",
                percent: "{{__('marketing.Percentage')}}",
                credits: "{{__('marketing.Credits')}}",
                credits_number: "{{__('marketing.CreditsNumber')}}"
            }
        };
        console.log(window.Offer);
    </script>
    <script src="{{asset('js/marketing/offers.js')}}"></script>
@endsection
