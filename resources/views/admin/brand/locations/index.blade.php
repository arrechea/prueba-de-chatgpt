@extends('admin.layout.master')
@section('content')
    <div class="main-container list">
        <section class="list__title">
            @include('admin.brand.locations.tabs')
        </section>
        <div class="list__container">
            <section class="list__content is-fullWidth">
                <div class="list__header">
                    <div class="template__title has-icon is-dashboard">
                        <h2><i class="material-icons">sort</i> <span>{{__('brand.Studios')}}</span></h2>
                    </div>
                        @include('admin.catalog.table-list')
            </section>
        </div>
    </div>
@endsection
