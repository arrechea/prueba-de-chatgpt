@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.marketing.product-tabs')
        <div class="list__container">
            <div class="row">
                <div class="card-panel radius--forms">
                    <div class="row">
                        <a class="waves-effect waves-light btn right mr-30"
                           href="{{route('admin.company.brand.marketing.membership.create', [
                        'company' => $company,
                        'brand'   => $brand,
                    ])}}">
                            <i class="material-icons small">add</i>{{__('common.add')}}</a>
                    </div>
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div>
@endsection
