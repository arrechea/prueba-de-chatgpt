<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
@if(!$urlForm || isset($initialMap) || !empty($initialMap))
    <form class="row" autocomplete="off" enctype="multipart/form-data" action="{{$urlEdit}}" method="post">
        @endif
        @include('admin.common.alertas')
        {{csrf_field()}}
        <input hidden name="brands_id" value="{{$brand->id}}">
        <input hidden name="companies_id" value="{{$company->id}}">
        <input hidden name="locations_id" value="{{$location->id}}">
        @if (isset($maps))
            <input type="hidden" name="id" value="{{$maps->id}}">
        @endif

        <div class="card-panel panelcombos">
            <h5 class="card-title header">{{__('maps.Edit-maps')}}</h5>
            @if(isset($initialMap) && !empty($initialMap))
                  @if($isSaas)
                     <div class="BuqSaas-l-map">
                        <div class="BuqSaas-l-map__body">
                           <div id="AppGenerateMap">
                              <div class="AppGenerateMap--lang"
                                    style="display: none">{{$langFile}}</div>
                              <div class="AppGenerateMap--location_positions"
                                    style="display: none">{{$locationPositions}}</div>
                              <div class="AppGenerateMap--urlForm"
                                    style="display: none">{{$urlForm}}</div>
                              <div class="AppGenerateMap--initial_map"
                                    style="display: none">{{json_encode($initialMap)}}</div>
                              <div class="AppGenerateMap--initial_name">{{$maps->name ?? ''}}</div>
                              <div class="AppGenerateMap--initial_active">{{$maps->status==='active'}}</div>
                           </div>
                        </div>
                     </div>
                  @else
                     <div id="AppGenerateMap">
                        <div class="AppGenerateMap--lang"
                              style="display: none">{{$langFile}}</div>
                        <div class="AppGenerateMap--location_positions"
                              style="display: none">{{$locationPositions}}</div>
                        <div class="AppGenerateMap--urlForm"
                              style="display: none">{{$urlForm}}</div>
                        <div class="AppGenerateMap--initial_map"
                              style="display: none">{{json_encode($initialMap)}}</div>
                        <div class="AppGenerateMap--initial_name">{{$maps->name ?? ''}}</div>
                        <div class="AppGenerateMap--initial_active">{{$maps->status==='active'}}</div>
                     </div>
                  @endif
            @else
                <div class="mapWithReservations col s12">
                    <h6>{{__('rooms.editmapalert')}}</h6>
                </div>
                <br>
                <div class="col s12 m8">
                    <div class="input-field col s12 {{$isSaas ? 'm12' : 'm10'}}">
                        <input type="text" class="input" name="name" id="name"
                               value="{{old('name', ($maps->name ?? ''))}}" required>
                        <label for="name">{{__('maps.name')}}</label>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="row">
                        <div class="file-field input-field">
                            <div class="">
                                <div class="uploadPhoto">
                                    <img src="{{$maps->image_background ??''}}" width="180px" alt=""
                                         class="responsive-img uploadPhoto--image"/> <br>
                                    <h5 class="header"><i
                                                class="material-icons small">add_a_photo</i> {{__('maps.bakcgroundImage')}}
                                    </h5>
                                    <input type='file' class="uploadPhoto--input" name="image_background"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switch">
                        <label>
                            {{__('company.Inactive')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($maps) && $maps->isActive() ? 'checked' : '' !!}
                                   @endif
                                   name="status">
                            <span class="lever"></span>
                            {{__('company.Active')}}
                        </label>
                    </div>
                    @if(isset($maps) && !$maps->isActive())
                        <div class="">
                            <p style="color: var(--alert-color)">{{__('maps.ActiveWarningMap')}}</p>
                        </div>
                    @endif

                    <div class="col s6 {{$isSaas ? 'm12' : 'm6'}} edit-buttons input-field">
                        <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                           @if($isSaas)
                              <div>
                                    <i class="fal fa-save"></i>
                                    <span>{{__('rooms.Save')}}</span>
                              </div>
                           @else
                              <i class="material-icons right small">save</i>
                              {{__('rooms.Save')}}
                           @endif
                        </button>
                    </div>

                </div>
            @endif
        </div>
        @if(!$urlForm || isset($initialMap) || !empty($initialMap))
    </form>
@endif



