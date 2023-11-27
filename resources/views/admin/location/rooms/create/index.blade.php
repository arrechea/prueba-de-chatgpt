@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
               <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.locations.rooms.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                  <i class="far fa-angle-left"></i>
                  <span>Atrás</span>
               </a>
                <div class="BuqSaas-c-sectionTitle">
                    <h2>{{__('rooms.RoomData')}}</h2>
                </div>
            </div>
            <div class="BuqSaas-l-form__body">
                @include('admin.location.rooms.form')
            </div>
        </div>
        @else
            <div class="row">
                <a class="waves-effect waves-light btn ml-30"
                href="{{route('admin.company.brand.locations.rooms.index',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
                    <i class="material-icons small">arrow_left</i>{{__('common.back_to_list')}}</a>
            </div>
            <div class="row">
                <div class="card-panel form-panel radius--forms">
                    @include('admin.location.rooms.form')
                </div>
            </div>
        @endif
    </div>
@endsection
