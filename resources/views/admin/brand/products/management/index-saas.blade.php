@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.product-tabs')
        <div class="row">
            <div class="card-panel">
                <h5 class="card-title header">{{__('products.ProductManagement')}}</h5>

                <div id="item-management-list"></div>
            </div>
        </div>
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
