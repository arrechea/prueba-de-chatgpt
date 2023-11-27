@extends('admin.layout.master')
@section('content')
    {{-- <div class="main-container">
        @include('admin.gafafit.administrators.components.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('gafafit.Administrators')}}</h5>

                <div class="card-panel panelcombos">
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div> --}}

    <div class="main-container list">
        <section class="list__title">
            @include('admin.gafafit.administrators.components.tabs')
        </section>
        <div class="list__container">
            <section class="list__content is-fullWidth">
                <div class="list__header">
                    <div class="template__title has-icon is-dashboard">
                        <h2><i class="material-icons">sort</i> <span>{{__('gafafit.Administrators')}}</span></h2>
                    </div>
                        @include('admin.catalog.table-list')
            </section>
        </div>
    </div>
@endsection
