@extends('admin.layout.master')
@section('content')
    <div class="main-container">
      @if(Auth::user()->isA('gafa-saas'))
         <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
               <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.locations.room-maps.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                  <i class="far fa-angle-left"></i>
                  <span>Atrás</span>
               </a>
               <div class="BuqSaas-c-sectionTitle">
                     <h2>{{__('maps.Edit-maps')}}</h2>
               </div>
            </div>
            <div class="BuqSaas-l-form__body is-locationRooms">
               @include('admin.location.rooms.maps.edit.editForm')
            </div>
         </div>
      @else
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.location.rooms.maps.edit.editForm')
            </div>
        </div>
      @endif
    </div>
@endsection

@section('css')
    @parent
    <link rel="stylesheet" type="text/css" charset="UTF-8"
          href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css"/>
    <link rel="stylesheet" href="{{mixGafaFit('css/admin/mapGenerator.css')}}">
@endsection

@section('jsPostApp')
    @parent
    <script src="{{mixGafaFit('/js/admin/react/maps/generate-map/build.js')}}"></script>
@endsection
