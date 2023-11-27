@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
         <div class="BuqSaas-l-form">
               <div class="BuqSaas-l-form__header">
                  <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.locations.maps-position.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                     <i class="far fa-angle-left"></i>
                     <span>Atrás</span>
                  </a>
                  <div class="BuqSaas-c-sectionTitle">
                     <h2>{{__('maps.Position')}}</h2>
                  </div>
               </div>
               <div class="BuqSaas-l-form__body">
                  @include('admin.location.rooms.positions.form')
               </div>
         </div>
        @else
            <div class="row">
               <div class="card-panel radius--forms" style="padding: 18px;">
                  @include('admin.location.rooms.positions.form')
               </div>
            </div>
        @endif
    </div>
@endsection
