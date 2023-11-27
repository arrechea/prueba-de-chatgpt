@extends('admin.layout.master')
@section('content')
     {{--<div class="main-container">--}}
        {{--@include('admin.gafafit.companies.tabs')--}}
        {{--<div class="row">--}}
            {{--<div class="card-panel radius--forms">--}}
                {{--<h5 class="card-title header">{{__('gafacompany.Companies')}}</h5>--}}

                {{--<div class="card-panel panelcombos">--}}
                    {{--@include('admin.catalog.table')--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="main-container list">
        <section class="list__title">
            @include('admin.gafafit.creditsgf.tabs')
        </section>
        <div class="list__container">
            <section class="list__content is-fullWidth">
                <div class="list__header">
                    <div class="template__title has-icon is-dashboard">
                        <h2><i class="material-icons">sort</i> <span>{{__('gafaCredits.Credits')}}</span></h2>
                    </div>
                    @include('admin.catalog.table-list')
                </div>
            </section>
        </div>
    </div>
@endsection
