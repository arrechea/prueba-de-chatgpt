@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.marketing-tabs')
        <br>
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('marketing.GiftCards')}}</h5>
                @include('admin.catalog.table')
            </div>
        </div>
    </div>
@endsection
