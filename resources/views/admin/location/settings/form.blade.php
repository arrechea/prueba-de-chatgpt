<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($LocationToEdit))
        <input type="hidden" name="id" value="{{$LocationToEdit->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <input type="hidden" name="locations_id" value="{{$location->id}}">
    <div class="col s12 m8">
        <h5 class="">{{__('location.GeneralInformation')}}</h5>
        <div class="card-panel panelcombos ">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="name" id="name"
                               value="{{old('name',(isset($LocationToEdit) ? $LocationToEdit->name: ''))}}" required>
                        <label for="name">{{__('brand.Name')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" class="input" name="phone" id="phone"
                               value="{{old('phone', (isset($LocationToEdit) ? $LocationToEdit->phone: ''))}}">
                        <label for="phone">{{__('brand.Phone')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="email" id="email"
                               value="{{old('email', (isset($LocationToEdit) ? $LocationToEdit->email: ''))}}" required>
                        <label for="email">{{__('brand.Email')}}</label>
                    </div>
                </div>
            </div>
        </div>
        <h5 class="">{{__('brand.address')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="street" id="street"
                               value="{{old('street', (isset($LocationToEdit) ? $LocationToEdit->street: ''))}}">
                        <label for="street">{{__('brand.Street')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" class="input" name="number" id="number"
                               value="{{old('number', (isset($LocationToEdit) ? $LocationToEdit->number: ''))}}">
                        <label for="number">{{__('location.ExteriorNumber')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="suburb" id="suburb"
                               value="{{old('suburb', (isset($LocationToEdit) ? $LocationToEdit->suburb: ''))}}">
                        <label for="suburb">{{__('brand.Suburb')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" class="input" name="postcode" id="postcode"
                               value="{{old('postcode', (isset($LocationToEdit) ? $LocationToEdit-> postcode: ''))}}">
                        <label for="postcode">{{__('brand.Postcode')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="places-selectors"></div>
            </div>

            <div class="row">
                <div class="col s12 m12 l10">
                    <div class="input-field">
                        <input type="text" class="input" name="gmaps" id="gmaps"
                               value="{{old('gmaps',(isset($LocationToEdit) ? $LocationToEdit->gmaps: ''))}}">
                        <label for="gmaps">{{__('brand.url')}} de {{__('brand.Gmaps')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" class="input" name="latitude" id="latitude"
                               value="{{old('latitude', (isset($LocationToEdit) ? $LocationToEdit->latitude: ''))}}">
                        <label for="latitude">{{__('location.latitude')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" class="input" name="longitude" id="longitude"
                               value="{{old('longitude', (isset($LocationToEdit) ? $LocationToEdit->longitude: ''))}}">
                        <label for="longitude">{{__('location.longitude')}}</label>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">{{__('location.WForze')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l10">
                    <div class="switch">
                        <label>
                            {{__('location.NoForze')}}
                            <input type="checkbox"
                                   @if(!!old())
                                       {!! old('waiver_forze','')==='on' ? 'checked' : '' !!}
                                   @else
                                       {!! isset($LocationToEdit) && $LocationToEdit->isForze() ? 'checked' : '' !!}
                                   @endif
                                   name="waiver_forze">
                            <span class="lever"></span>
                            {{__('location.Forze')}}
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l10">
                    <div class="{{$isSaas ? 'input-field text-area' : ''}}">
                        <label for="waiver_text" class="active">{{__('location.TForze')}}</label>
                        <textarea style="width: 95%;" name="waiver_text" id="waiver_text"
                                  class="materialize-textarea">{{old('waiver_text',($LocationToEdit->waiver_text ?? ''))}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">{{__('location.scheduling')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="start-end-datepicker">
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <label for="since">{{__('location.since')}}</label>
                            <input type="text" class="time-start calendar-date pck-pink" name="since" id="since"
                                   value="{{old('since',(isset($LocationToEdit) ? $LocationToEdit->since: ''))}}">
                        </div>
                    </div>

                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <label for="until">{{__('location.until')}}</label>
                            <input type="text" class="time-end calendar-date pck-pink" name="until" id="until"
                                   value="{{old('until', (isset($LocationToEdit) ? $LocationToEdit->until: ''))}}">
                        </div>
                        <p class="start-end-datepicker-alert input-field"
                           style="color: var(--alert-color);font-size: x-small;margin-top:0"
                           hidden>{{__('timepicker.until-date-alert')}}</p>
                    </div>
                </div>

            </div>
        </div>

        <h5 class="">{{__('location.CalendarStartDate')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label for="date_start">{{__('location.date-start')}}</label>
                        <input type="text" class="calendar-date pck-pink" name="date_start" id="date_start"
                               value="{{old('date_start', (isset($LocationToEdit) ? $LocationToEdit->date_start: ''))}}">
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="calendar_days" id="calendar_days" required
                               value="{{old('calendar_days', (isset($LocationToEdit) ? $LocationToEdit->calendar_days:''))}}">
                        <label for="calendar_days">{{__('location.calendar_days')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="start-end-timepicker">
                    <div class="input-field col s12 m12 l5">
                        <input type="text" class=" time-start" name="start_time" id="start_time"
                               value="{{old('start_time', (isset($LocationToEdit) ? $LocationToEdit->start_time: ''))}}">
                        <label for="start_time">{{__('location.start-time')}}</label>
                    </div>

                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <input type="text" class=" time-end" name="end_time" id="end_time"
                                   value="{{old('end_time', (isset($LocationToEdit) ? $LocationToEdit->end_time: ''))}}">
                            <label for="end_time">{{__('location.end-time')}}</label>
                        </div>
                        <p class="start-end-timepicker-alert"
                           style="color: var(--alert-color);font-size: x-small;margin-top:0"
                           hidden>{{__('timepicker.closing-time-alert')}}</p>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
        {{--<h5 class="header">{{__('brand.Contact')}}</h5>--}}
        {{--</div>--}}

        @php
            $edit_permission=\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_SETTINGS_EDIT, $location);
        @endphp

        @if($company->isGympassActive() &&
            \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_LOCATION_VIEW, $location))
            <h5 class="">{{__('gympass.locationGympassTitle')}}</h5>
            <div class="card-panel panelcombos">
                <div class="row">

                    <div class="col s12 m12 l6">
                        <div class="switch">
                            <label>
                                {{__('gafacompany.Inactive')}}
                                <input type="checkbox"
                                       @if(!$edit_permission) disabled="disabled" @endif
                                       @if(!!old())
                                           {!! old('extra_fields.gympass.active','')==='on' ? 'checked' : '' !!}
                                       @else
                                           {!! isset($location) && $location->isMarkedGympassActive() ? 'checked' : '' !!}
                                       @endif
                                       name="gympass_active">
                                <span class="lever"></span>
                                {{__('gafacompany.Active')}}
                            </label>
                        </div>
                    </div>

                    <div class="col s12 m12 l6">
                        <div class="switch">
                            <label>
                                {{__('gympass.development')}}
                                <input type="checkbox"
                                       @if(!$edit_permission) disabled="disabled" @endif
                                       @if(!!old())
                                           {!! old('extra_fields.gympass.production','')==='on' ? 'checked' : '' !!}
                                       @else
                                           {!! isset($location) && $location->isGympassProduction() ? 'checked' : '' !!}
                                       @endif
                                       name="gympass_production">
                                <span class="lever"></span>
                                {{__('gympass.production')}}
                            </label>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <input type="number"
                                   id="extra_fields.gympass.gym_id"
                                   class="input"
                                   name="extra_fields[gympass][gym_id]"
                                   @if(!$edit_permission) disabled="disabled" @endif
                                   value="{{ isset($location) ? $location->getDotValue('extra_fields.gympass.gym_id') : '' }}">
                            <label for="extra_fields.gympass.gym_id">{{__('gympass.gymId')}}</label>
                        </div>
                    </div>

                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <select
                                id="extra_fields.gympass.checkin_type"
                                class="input"
                                name="extra_fields[gympass][checkin_type]"
                                @if(!$edit_permission) disabled="disabled" @endif
                            >
                                <option value="">--</option>
                                @foreach(\App\Librerias\Gympass\Helpers\GympassHelpers::getCheckinTypes() as $type)
                                    <option value="{{$type}}"
                                            @if(isset($location) && $location->getDotValue('extra_fields.gympass.checkin_type')===$type) selected @endif>{{__("gympass.checkinType_$type")}}</option>
                                @endforeach
                            </select>
                            <label for="extra_fields.gympass.checkin_type">{{__('gympass.checkinType')}}</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m12 l8">
                        <div class="input-field">
                            <label for="hours_before_opening">{{__('gympass.locationDaysBeforeWindow')}}</label>
                            <input type="number" class="input" name="extra_fields[gympass][days_before_opening]"
                                   id="days_before_opening"
                                   value="{{old('days_before_opening', (isset($LocationToEdit) ? $LocationToEdit->getDotValue('extra_fields.gympass.days_before_opening'): ''))}}">
                        </div>
                    </div>
                </div>

                @if(isset($LocationToEdit))

                    <div class="row">
                        <div class="col s12 m12 l6">
                            <div class="switch">
                                <label>
                                    {{__('gympass.unapproved')}}
                                    <input type="checkbox"
                                           name="gympass_approved"
                                           @if(!$edit_permission) disabled="disabled" @endif
                                    @if(!!old())
                                        {!! old('extra_fields.gympass.approved','')==='on' ? 'checked' : '' !!}
                                        @else
                                        {!! isset($location) && $location->isGympassApproved() ? 'checked' : '' !!}
                                        @endif
                                    >
                                    <span class="lever"></span>
                                    {{__('gympass.approved')}}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col s12 m12 l12">
                            <label for="gympass_resend_email"
                                   class="label">
                                <input type="checkbox"
                                       style="position: relative;"
                                       @if(!$edit_permission) disabled="disabled" @endif
                                       name="gympass_resend_email"> {{__('gympass.resendApprovalEmail')}}
                            </label>
                        </div>
                    </div>

                @endif

            </div>
        @endif
    </div>

    <div class="col s12 m4">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$LocationToEdit->pic??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('brand.studiologo')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="pic"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
        {{--<div class="col s12 m12 l12">--}}
        {{--<div class="switch">--}}
        {{--<label>--}}
        {{--{{__('brand.Inactive')}}--}}
        {{--<input type="checkbox"--}}
        {{--{{isset($LocationToEdit) && $LocationToEdit->isActive() ? 'checked="checked"' : ''}} name="status">--}}
        {{--<span class="lever"></span>--}}
        {{--{{__('brand.Active')}}--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}


        <div class="row">
            <div class="col s12 m12 l7 edit-buttons">
                <button type="submit"
                        class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                    @if($isSaas)
                        <div>
                            <i class="fal fa-save"></i>
                            <span>{{__('brand.Save')}}</span>
                        </div>
                    @else
                        <i class="material-icons right small">save</i>
                        {{__('brand.Save')}}
                    @endif
                </button>
            </div>
        </div>

        @if (isset($LocationToEdit))
            <div class="row">
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::LOCATION_DELETE))
                        <a class="waves-effect waves-light btn btnguardar" href="#eliminar_L"
                           style="background-color: grey"><i
                                class="material-icons right small">clear</i>{{__('brand.Delete')}}</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</form>
@if (isset($LocationToEdit))
    <div id="eliminar_L" class="modal modal modal-fixed-footer modaldelete" data-method="get"
         data-href="{{route('admin.company.brand.locations.settings.delete', ['company'=>$company,'brand' => $brand,'location'=> $location, 'LocationToEdit' => $LocationToEdit->id])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <a class="s12 modal-action modal-close waves-effect waves-green btn btndelete edit-button model-delete-button"
               id="settings-delete-button" data-name="settings">
                <i class="material-icons small">done</i>
                {{__('brand.Delete')}}
            </a>
        </div>
    </div>
@endif


@section('jsPostApp')
    @parent
    <script src="{{asset('js/Range/time_range.js')}}"></script>
    <script src="{{asset('js/Range/date_range.js')}}"></script>

    <script src="<?php echo e(asset('plugins/pickers/jquery-timepicker/jquery.timepicker.js')); ?>"></script>
    <script src="{{asset('js/Range/time_range.js')}}"></script>
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="<?php echo e(asset('plugins/pickers/jquery-timepicker/jquery.timepicker.css')); ?>">

@endsection




