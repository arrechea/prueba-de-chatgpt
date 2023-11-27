@extends('admin.layout.master')
@section('content')
    <div class="main-container list">
        <section class="list__title">
            @include('admin.company.Brands.tabs')
        </section>
        <div class="list__container">
            <section class="list__content is-fullWidth is-threeColumns">
                    <div class="list__header">
                        <div class="template__title has-icon is-dashboard">
                            <h2><i class="material-icons">local_offer</i> <span>{{__('company.Brands')}}</span></h2>
                        </div>
                        @include('admin.catalog.table-list')
            </section>
        </div>
    </div>
@endsection
