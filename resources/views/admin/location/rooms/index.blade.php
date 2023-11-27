@extends('admin.layout.master')
@section('content')
    <div class="main-container">

        @if(Auth::user()->isA('gafa-saas'))
            <div class="BuqSaas-l-list">
                <div class="BuqSaas-l-list__header">
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>Salones de: <strong>{{($location->name)}}</strong></h2>
                    </div>
                    <div class="BuqSaas-c-nav is-list">
                        <div class="BuqSaas-c-nav__filter is-filter">
                            @include('admin.location.rooms.tabs-saas')
                        </div>
                        <div class="BuqSaas-c-nav__add">
                                <a  class="BuqSaas-e-button is-add"
                                    href="{{route('admin.company.brand.locations.rooms.create',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
                                    <i class="material-icons small">add</i>{{__('common.add')}}
                                </a>
                        </div>
                    </div>
                </div>
                <div class="BuqSaas-l-list__body is-locationRooms">
                    @include('admin.catalog.table')
                </div>
            </div>
        @else

            @include('admin.location.rooms.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('rooms.RoomList')}}</h5>
                    @include('admin.catalog.table')
                </div>
            </div>
        @endif
    </div>
@endsection
