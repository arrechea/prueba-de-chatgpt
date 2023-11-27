@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-list">
            <div class="BuqSaas-l-list__header">
                <div class="BuqSaas-c-sectionTitle">
                    <h2>Descuentos de: <strong>{{($brand->name)}}</strong></h2>
                </div>
                <div class="BuqSaas-c-nav is-list">
                    <div class="BuqSaas-c-nav__filter">
                    </div>
                    <div class="BuqSaas-c-nav__add">
                        <a
                            class="BuqSaas-e-button is-add"
                            href="{{route('admin.company.brand.discount-code.create', [
                                'company' => $company,
                                'brand'   => $brand,
                            ])}}">
                            <i class="material-icons small">add</i>{{__('common.add')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="BuqSaas-l-list__body is-marketingDiscounts">
                @include('admin.catalog.table')
            </div>
        </div>
        @else
            @include('admin.brand.marketing.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="header">{{__('discounts.DiscountCodes')}}</h5>
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
        @endif
    </div>
@endsection
