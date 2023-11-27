@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-list">
            <div class="BuqSaas-l-list__header">
                <div class="BuqSaas-c-sectionTitle">
                    <h2>Gift Cards de: <strong>{{($brand->name)}}</strong></h2>
                </div>
                <div class="BuqSaas-c-nav is-list">
                    <div class="BuqSaas-c-nav__filter">
                    </div>
                    <div class="BuqSaas-c-nav__add">
                    </div>
                </div>
            </div>
            <div class="BuqSaas-l-list__body is-marketingGiftCards">
                @include('admin.catalog.table')
            </div>
        </div>
        @else
        @include('admin.brand.marketing.tabs')
        <br>
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('marketing.GiftCards')}}</h5>
                    @include('admin.catalog.table')
                </div>
            </div>
        @endif
    </div>
@endsection
