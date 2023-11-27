@extends('admin.layout.master')
@section('content')
    <div class="main-container">
      @if(Auth::user()->isA('gafa-saas'))
         <div class="BuqSaas-l-map">
            <div class="BuqSaas-l-map__header">
               <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.locations.room-maps.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                  <i class="far fa-angle-left"></i>
                  <span>Atrás</span>
               </a>
               <div class="BuqSaas-c-sectionTitle">
                  <h2>{{__('maps.Create-maps')}}</strong></h2>
               </div>
            </div>
            <div class="BuqSaas-l-map__body is-locationRooms">
               <div id="AppGenerateMap">
                  <div class="AppGenerateMap--lang"
                       style="display: none">{{$langFile}}</div>
                  <div class="AppGenerateMap--location_positions"
                       style="display: none">{{$locationPositions}}</div>
                  <div class="AppGenerateMap--urlForm"
                       style="display: none">{{$urlForm}}</div>
                  @if(isset($initialMap))
                      <div class="AppGenerateMap--initial_map"
                           style="display: none">{{$initialMap}}</div>
                  @endif
                  @if(isset($map))
                      <div class="AppGenerateMap--image_background"
                           style="display: none">{{$map->image_background}}</div>
                  @endif
               </div>
            </div>
         </div>
      @else
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('maps.Edit-maps')}}</h5>
                <div id="AppGenerateMap">
                    <div class="AppGenerateMap--lang"
                         style="display: none">{{$langFile}}</div>
                    <div class="AppGenerateMap--location_positions"
                         style="display: none">{{$locationPositions}}</div>
                    <div class="AppGenerateMap--urlForm"
                         style="display: none">{{$urlForm}}</div>
                    @if(isset($initialMap))
                        <div class="AppGenerateMap--initial_map"
                             style="display: none">{{$initialMap}}</div>
                    @endif
                    @if(isset($map))
                        <div class="AppGenerateMap--image_background"
                             style="display: none">{{$map->image_background}}</div>
                    @endif
                </div>
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
