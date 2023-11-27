<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($LocationToEdit))
        <input type="hidden" name="id" value="{{$LocationToEdit->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m8">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('brand.Data')}} {{__('brand.General')}}</h5>
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

                @if(isset($LocationToEdit))
                    <div class="col s12 m12 l4"></div>
                @else
                    <div class="col s12 m12 l4">
                        <div class="input-field">
                            <label for="service" class="active"> {{__('brand.Services')}} </label>
                            <select id="service" name="services_id">
                                <option value="">--</option>
                                @foreach ($services as $service) {
                                <option value="{{$service->id}}"
                                        @if( old('services_id', (isset($LocationToEdit) ? $LocationToEdit->services_id : '')) == $service->id) selected="selected"@endif>{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="number" class="input" name="order" id="order"
                               value="{{old('order', (isset($LocationToEdit) ? $LocationToEdit->order: ''))}}">
                        <label for="order">{{__('location.order')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" class="input" name="whatsapp" id="whatsapp"
                               value="{{old('whatsapp', (isset($LocationToEdit) ? $LocationToEdit->whatsapp: ''))}}">
                        <label for="phone">{{__('brand.WhatsApp')}}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('brand.address')}}</h5>
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
                        <label for="number">{{__('brand.Number')}} {{__('brand.Exterior')}}</label>
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
                {{--<div class="col s12 m12 l3">--}}
                {{--<div class="input-field">--}}
                {{--<input type="text" class="input" name="district" id="district"--}}
                {{--value="{{old('district', (isset($LocationToEdit) ? $LocationToEdit->district: ''))}}">--}}
                {{--<label for="district">{{__('brand.District')}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col s12 m12 l3">--}}
                {{--<div class="input-field">--}}
                {{--<input type="text" class="input" name="state" id="state"--}}
                {{--value="{{old('state', (isset($LocationToEdit) ? $LocationToEdit->state: ''))}}">--}}
                {{--<label for="state">{{__('brand.State')}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col s12 m12 l4">--}}
                {{--<label for="country"> {{__('brand.Country')}}--}}
                {{--<select id="country" class="select2 select"--}}
                {{--data-name="countries">--}}
                {{--<option value="">--</option>--}}
                {{--@foreach($countries as $country)--}}
                {{--<option value="{{$country->id}}"--}}
                {{--@if (old('countries_id', (isset($LocationToEdit) ? $LocationToEdit->countries_id : '')) == $country->id) selected="selected"@endif>{{__($country->name)}}</option>--}}
                {{--@endforeach--}}
                {{--</select>--}}
                {{--</label>--}}
                {{--<input type="hidden" name="countries_id" id="countries_id"--}}
                {{--value="{{old('countries_id',(isset($LocationToEdit->country) ? $LocationToEdit->country->id : ''))}}"/>--}}
                {{--<input type="hidden" name="countries_name" id="countries_name"--}}
                {{--value="{{old('countries_name',(isset($LocationToEdit->country) ? $LocationToEdit->country->name : ''))}}"/>--}}
                {{--</div>--}}
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

        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('location.WForze')}}</h5>
                <div class="col s12 m12 l12">
                    <div class="switch">
                        <label>
                            {{__('location.NoForze')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('waiver_forze','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($LocationToEdit) && $LocationToEdit->isForze() ? 'checked' : '' !!}
                                   @endif
                                   name="waiver_forze">
                            <span class="lever"></span>
                            {{__('location.Forze')}}
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l0">
                    <div class="input-field">
                        <label for="waiver_text">{{__('location.TForze')}}</label>
                        <textarea style="width: 95%;" name="waiver_text" id="waiver_text"
                                  class="materialize-textarea">{{old('waiver_text',($LocationToEdit->waiver_text ?? ''))}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="header">{{__('location.scheduling')}}</h5>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label for="since">{{__('location.since')}}</label>
                        <input type="text" class="calendar-date pck-pink" name="since" id="since"
                               value="{{old('since',(isset($LocationToEdit) ? $LocationToEdit->since: ''))}}">
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label for="until">{{__('location.until')}}</label>
                        <input type="text" class="calendar-date pck-pink" name="until" id="until"
                               value="{{old('until', (isset($LocationToEdit) ? $LocationToEdit->until: ''))}}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="header">{{__('location.date-start')}} de {{__('location.calendar')}}</h5>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label for="date_start">{{__('location.date-start')}}</label>
                        <input type="text" class="calendar-date pck-pink" name="date_start" id="date_start"
                               value="{{old('date_start', (isset($LocationToEdit) ? $LocationToEdit->date_start: ''))}}">
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="calendar_days" id="calendar_days"
                               value="{{old('calendar_days', (isset($LocationToEdit) ? $LocationToEdit->calendar_days:''))}}" required>
                        <label for="calendar_days">{{__('location.calendar_days')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="start-end-timepicker">
                    <div class="input-field col s12 m12 l5">
                        <input type="text" class="clock-location time-start" name="start_time" id="start_time"
                               autocomplete="off"
                               value="{{old('start_time', (isset($LocationToEdit) ? $LocationToEdit->start_time: ''))}}">
                        <label for="start_time">{{__('location.start-time')}}</label>
                    </div>

                    <div class="input-field col s12 m12 l5">
                        <input type="text" class="clock-location time-end" name="end_time" id="end_time"
                               autocomplete="off"
                               value="{{old('end_time', (isset($LocationToEdit) ? $LocationToEdit->end_time: ''))}}">
                        <label for="end_time">{{__('location.end-time')}}</label>
                    </div>
                </div>
            </div>
        </div>

        {{--<div class="row">--}}
        {{--<h5 class="header">{{__('location.Contact')}}</h5>--}}
        {{--</div>--}}
    </div>

    <div class="col s12 m4">
        <div class="col s12 m12 card-panel panelcombos">
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
        {{--todo: ----------FASE 2 ------- foto de listado y foto movil--}}

        <div class="row">
            <div class="col s12 m12 l12">
                <div class="switch">
                    <label>
                        {{__('brand.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($LocationToEdit) && $LocationToEdit->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('brand.Active')}}
                    </label>
                </div>
            </div>
            @if(isset($LocationToEdit) && !$LocationToEdit->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('location.ActiveWarning')}}</p>
                </div>
            @endif
        </div>


        <div class="row">
            <div class="col s12 m12 l7 edit-buttons">
                <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                        class="material-icons right small">save</i>{{__('brand.Save')}}</button>
            </div>
        </div>

        @if (isset($LocationToEdit))
            <div class="row">
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::LOCATION_DELETE,$brand))
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
    <div id="eliminar_L" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.brand.locations.delete', ['company'=>$company,'brand' => $brand, 'LocationToEdit' => $LocationToEdit->id])}}">
        <div class="modal-content"></div>
    </div>
@endif

@section('jsPostApp')
    @parent

    <script src="<?php echo e(asset('plugins/pickers/jquery-timepicker/jquery.timepicker.js')); ?>"></script>
    <script src="{{asset('js/Range/time_range.js')}}"></script>
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="<?php echo e(asset('plugins/pickers/jquery-timepicker/jquery.timepicker.css')); ?>">

@endsection


