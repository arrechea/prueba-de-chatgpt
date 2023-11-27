{{--@extends('admin.layout.master')--}}
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
            <div class="BuqSaas-l-list">
                <div class="BuqSaas-l-list__header">
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>{{__('gympass.checkinTitle')}}</h2>
                    </div>
                    <div class="BuqSaas-c-nav is-list">
                        <div class="BuqSaas-c-nav__filter">
                        </div>
                    </div>
                </div>
                <div class="BuqSaas-l-list__body is-users">
                    @include('admin.catalog.table')
                </div>
            </div>
        @else
            {{--            @include('admin.location.users.tabs')--}}
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('gympass.checkinTitle')}}</h5>
                    @include('admin.catalog.table')
                </div>
            </div>
        @endif
    </div>
@endsection

