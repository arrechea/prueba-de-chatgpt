@extends('admin.layout.master')
@section('content')
   <div class="main-container">
      @if(Auth::user()->isA('gafa-saas'))
         <div class="BuqSaas-l-products">
            <div class="BuqSaas-l-products__header">
               <div class="BuqSaas-c-sectionTitle">
                  <h2>{{__('products.ProductManagement')}}</h2>
               </div>
            </div>
            <div class="BuqSaas-l-products__body">
               <div id="item-management-list"></div>
            </div>
      </div>
      @else
         @include('admin.brand.marketing.tabs')
         <div class="row">
            <div class="card-panel">
               <h5 class="card-title header">{{__('products.ProductManagement')}}</h5>

               <div id="item-management-list"></div>
            </div>
         </div>
      @endif
    </div>
@endsection

@section('jsPostApp')
    @parent()
    @include('admin.common.items-list')
@endsection

@section('css')
    @parent()
    <link href="{{asset('css/admin/item-list.css')}}" rel="stylesheet">
@endsection
