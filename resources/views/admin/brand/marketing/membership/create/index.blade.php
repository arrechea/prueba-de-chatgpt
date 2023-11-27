@extends('admin.layout.master')
@section('content')
    <div class="main-container">

        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
               <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.marketing.combos.index', ['company'=>$company,'brand'=>$brand])}}">
                  <i class="far fa-angle-left"></i>
                  <span>Atrás</span>
               </a>
               <div class="BuqSaas-c-sectionTitle">
                  <h2>{{__('marketing.Create')}} {{__('marketing.Membership')}}</h2>
               </div>
            </div>
            <div class="BuqSaas-l-form__body">
                  @include('admin.brand.marketing.membership.form')
            </div>
        </div>
        @else
            @include('admin.brand.marketing.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('marketing.Create')}} {{__('marketing.Membership')}}</h5>
                    @include('admin.brand.marketing.membership.form')
                </div>
            </div>
        @endif
    </div>
@endsection

@section('jsPostApp')
    @parent()
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('js/Range/date_range.js')}}"></script>
    <script src="{{asset('js/subscriptions/configuration.js')}}"></script>
@endsection
