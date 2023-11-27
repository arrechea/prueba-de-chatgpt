@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
            <div class="BuqSaas-l-form">
                <div class="BuqSaas-l-form__header">
                     <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.discount-code.index', ['company'=>$company,'brand'=>$brand])}}">
                        <i class="far fa-angle-left"></i>
                        <span>Atrás</span>
                     </a>
                     <div class="BuqSaas-c-sectionTitle">
                           <h2>{{__('discounts.Create')}}</h2>
                     </div>
                </div>
                <div class="BuqSaas-l-form__body">
                    @include('admin.brand.DiscountCode.form')
                </div>
            </div>
        @else
            <div class="row">
                @include('admin.brand.marketing.tabs')
                <div class="row">
                    <div class="card-panel radius--forms"><h5 class="header">{{__('discounts.Create')}}</h5>
                    @include('admin.brand.DiscountCode.form')
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
