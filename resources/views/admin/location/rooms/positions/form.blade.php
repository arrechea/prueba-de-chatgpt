<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" class="row" method="post" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    <input hidden name="brands_id" value="{{$brand->id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
    <input hidden name="locations_id" value="{{$location->id}}">
    @if(isset($position))
        <input type="hidden" name="id" value="{{$position->id}}">
    @endif
    {{csrf_field()}}
    <div class="card-panel panelcombos">
        <h5 class="header card-title">{{__('maps.Position')}}</h5>
        <div class="col s12 m8 l8">
               <h5 class="">{{__('maps.GeneralData')}}</h5>
               <div class="row">
                  <div class="input-field col s12 m6">
                     <input type="text" id="name" class="input" name="name"
                              value="{{old('name',($position->name ?? ''))}}" required>
                     <label for="name">{{__('maps.name')}}</label>
                  </div>
               </div>

               <div class="row">
                  <h5 class="information-header">{{__('maps.mapSize')}}</h5>
                  <a class="information-tooltip" data-position="right"
                     data-tooltip2
                     data-tooltip2-message="{{__('maps.sizeMessage')}}" style="padding: 0; margin-top: 13px; "
                  ><i class="material-icons" style="font-size: 15px !important;">info</i></a>
               </div>

               <div class="row">
                  <div class="input-field col s12 m5">
                     <input type="text" id="width" class="input" name="width" required
                              value="{{old('width',($position->width ?? ''))}}">
                     <label for="width">{{__('maps.width')}}</label>
                  </div>
                  <div class="input-field col s12 m5">
                     <input type="text" id="height" class="input" name="height" required
                              value="{{old('height',($position->height ?? ''))}}">
                     <label for="height">{{__('maps.height')}}</label>
                  </div>
               </div>


               <div class="row">
                  <h5 class="information-header" style="display: flex">{{__('maps.mapstype')}}<span
                           class="red-asterisk">*</span></h5>
                  <a class="information-tooltip"
                     data-tooltip2 data-tooltip2-message="{{__('maps.typeMessage')}}"
                     style="padding: 0; margin-top: 13px; "
                  ><i class="material-icons small" style="font-size: 15px !important;">info</i> </a>
               </div>

               <div class="row">
                  <div class="col s12 m10">
                     <p>
                        <input type="radio" id="private" name="type" class="with-gap"
                                 value="private" {{isset($position)&&$position->type==='private' ? 'checked="checked"':''}}>
                        <label for="private">{{__('maps.privatePosition')}}</label>
                     </p>
                     <p>
                        <input type="radio" id="public" name="type" class="with-gap"
                                 value="public" {{isset($position)&&$position->type==='public' ? 'checked="checked"':''}}>
                        <label for="public">{{__('maps.publicPosition')}}</label>
                     </p>
                      <p>
                          <input type="radio" id="coach" name="type" class="with-gap"
                                 value="coach" {{isset($position)&&$position->type==='coach' ? 'checked="checked"':''}}>
                          <label for="coach">{{__('maps.coachPosition')}}</label>
                      </p>
                  </div>
               </div>
        </div>
    </div>

    <div class="col s12 m4 l4">
        <div class="">
            <div class="row">
                <div class="file-field input-field" style="display: inline-block">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{$position->image ??''}}" width="50px" height="50px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header "><i
                                    class="material-icons small">add_a_photo</i> {{__('maps.baseImage')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="image"/>
                        </div>
                    </div>
                </div>

                <a data-tooltip-bottom
                   data-tooltipBottom-message="{{__('maps.baseMessage')}}"
                ><i class="material-icons small" style="margin-bottom: 41px; font-size: 15px !important;">info</i>
                </a>
            </div>
            <div class="row">
                <div class="file-field input-field" style="display: inline-block">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{$position->image_selected ??''}}" width="50px" height="50px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header "><i
                                    class="material-icons small">add_a_photo</i> {{__('maps.imgSelected')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="image_selected"/>
                        </div>
                    </div>
                </div>
                <a data-tooltip-bottom
                   data-tooltipBottom-message="{{__('maps.selectedMessage')}}"
                ><i class="material-icons small" style="margin-bottom: 41px; font-size: 15px !important;">info</i>
                </a>
            </div>
            <div class="row">
                <div class="file-field input-field" style="display: inline-block">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{$position->image_disabled ??''}}" width="50px" height="50px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header "><i
                                    class="material-icons small">add_a_photo</i> {{__('maps.imgDisabled')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="image_disabled"/>
                        </div>
                    </div>
                </div>
                <a data-tooltip-bottom data-tooltipBottom-message="{{__('maps.disabledMessage')}}"
                ><i class="material-icons small" style="margin-bottom: 41px; font-size: 15px !important;">info</i>
                </a>
            </div>

        </div>
      <div class="row">
         <div class="switch">
            <label>
               {{__('maps.Inactive')}}
               <input type="checkbox"
                        @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                        @else {!! isset($position) && $position->isActive() ? 'checked' : '' !!}
                        @endif
                        name="status">
               <span class="lever"></span>
               {{__('maps.Active')}}
            </label>
         </div>
         @if(isset($position) && !$position->isActive())
               <div class="">
                  <p style="color: var(--alert-color)">{{__('maps.ActiveWarning')}}</p>
               </div>
         @endif
      </div>

      <div class="row">
         <div class="col s7 m7 edit-buttons input-field">
               <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                  @if($isSaas)
                     <div>
                           <i class="fal fa-save"></i>
                           <span>{{__('maps.Save')}}</span>
                     </div>
                  @else
                     <i class="material-icons right small">save</i>
                     {{__('maps.Save')}}
                  @endif
               </button>
         </div>
      </div>
   </div>
</form>

