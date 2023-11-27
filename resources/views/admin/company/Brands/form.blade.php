<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" class="row" method="post" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    @if(isset($brandToEdit))
        <input type="hidden" name="id" value="{{$brandToEdit->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <h5 class="">{{__('company.GeneralData')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="name" class="input" name="name"
                               value="{{old('name', ($brandToEdit->name?? ''))}}" required>
                        <label for="name">{{__('company.Name')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <label class="active" for="currency"> {{__('company.Currency')}} </label>
                        <select id="currency" name="currencies_id" style="width: 100%" required>
                            <option value="">$</option>
                            @foreach($currencies as $currency)
                                <option value="{{$currency->id}}"
                                        @if (old('currencies_id', (isset($brandToEdit) ? $brandToEdit->currencies_id : '')) == $currency->id) selected="selected"@endif>{{__($currency->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" name="email" class="input" id="email"
                               value="{{old('email', (isset($brandToEdit) ? $brandToEdit->email : ''))}}" required>
                        <label for="email">{{__('company.E-mail')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <label class="active" for="language"> {{__('company.Language')}}  </label>
                        <select id="language" name="language_id" style="width: 100%" required>
                            <option value="">--</option>
                            @foreach($languages as $language){
                            <option value="{{$language->id}}"
                                    @if(old('language_id', (isset($brandToEdit) ? $brandToEdit->language_id : '')) == $language->id) selected="selected"@endif>{{__($language->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="job" id="job"
                               value="{{old('job', (isset($brandToEdit) ? $brandToEdit->job : ''))}}">
                        <label for="job">{{__('company.job')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="number" class="clock-location" name="cancelation_dead_line"
                               id="cancelation_dead_line"
                               value="{{old('cancelation_dead_line', (isset($brandToEdit->cancelation_dead_line) ? $brandToEdit->cancelation_dead_line: ''))}}">
                        <label for="cancelation_dead_line">{{__('company.Cancel')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="copyright" id="copyright"
                               value="{{old('copyright', (isset($brandToEdit) ? $brandToEdit->copyright : ''))}}">
                        <label for="copyright">{{__('company.copyrightText')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="switch">
                        <label>
                            {{__('company.12hrs')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('time_format','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($brandToEdit) && $brandToEdit->is24hrsFormat() ? 'checked' : '' !!}
                                   @endif
                                   name="time_format">
                            <span class="lever"></span>

                            {{__('company.24hrs')}}
                        </label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <label for="time_zone" class="active"> {{__('location.timezone')}} </label>
                        <select id="time_zone" name="time_zone">
                            <option value="">--</option>
                            @foreach (\App\Librerias\Time\LibTime::getTimeZones() as $timezone) {
                            <option value="{{$timezone}}"
                                    @if( old('time_zone', (isset($brandToEdit) ? $brandToEdit->time_zone : '')) === $timezone) selected="selected"@endif>{{$timezone}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="input-field">
                        <input type="text" class="input" name="terms_conditions_link" id="terms_conditions_link"
                               value="{{old('terms_conditions_link', (isset($brandToEdit) ? $brandToEdit->terms_conditions_link : ''))}}"
                               placeholder="{{__('brand.terms_conditions_link.placeholder')}}">
                        <label for="terms_conditions_link">{{__('brand.terms_conditions_link')}}</label>
                    </div>
                </div>
            </div>


            <div class="row">
               <div class="{{$isSaas ? 'input-field text-area' : ''}} col s12 m10">
                  <label for="description" class="active">{{__('company.Description')}}</label>
                  <textarea type="text" id="description" class="materialize-textarea" name="description"
                  >{{old('description',(isset($brandToEdit) ? $brandToEdit->description : ''))}}</textarea>
               </div>
            </div>
        </div>


        <h5 class="">{{__('company.MatrixAddress')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" id="street" name="street"
                               value="{{old('street', isset($brandToEdit) ? $brandToEdit->street : '')}}">
                        <label for="street">{{__('company.Street')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" id="external_number" class="input" name="external_number"
                               value="{{old('external_number', (isset($brandToEdit) ? $brandToEdit->external_number : ''))}}">
                        <label for="external_number">{{__('company.ExteriorNumber')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="suburb" class="input" name="suburb"
                               value="{{old('suburb',(isset($brandToEdit) ? $brandToEdit->suburb : ''))}}">
                        <label for="suburb">{{__('company.Suburb')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" id="postcode" name="postcode" class="input"
                               value="{{old('postcode', (isset($brandToEdit) ? $brandToEdit->postcode : ''))}}">
                        <label for="postcode">{{__('company.Postcode')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                {{--<div class="col s12 m12 l3">--}}
                {{--<div class="input-field">--}}
                {{--<input type="text" id="district" name="district" class="input"--}}
                {{--value="{{old('district', (isset($brandToEdit) ? $brandToEdit->district : ''))}}">--}}
                {{--<label for="district">{{__('company.District')}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m12 l3">--}}
                {{--<div class="input-field">--}}
                {{--<input type="text" class="input" id="state" name="state"--}}
                {{--value="{{old('state', (isset($brandToEdit) ? $brandToEdit->state : ''))}}">--}}
                {{--<label for="state">{{__('company.State')}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m12 l4">--}}
                {{--<label for="country"> {{__('company.Country')}}--}}
                {{--<select id="country" class="select2 select" data-name="countries" style="width: 100%">--}}
                {{--<option value="">--</option>--}}
                {{--@foreach ($countries as $country) {--}}
                {{--<option value="{{$country->id}}"--}}
                {{--@if( old('countries_id', (isset($brandToEdit) ? $brandToEdit->countries_id : '')) == $country->id) selected="selected"@endif>{{$country->name}}</option>--}}
                {{--@endforeach--}}
                {{--</select>--}}
                {{--</label>--}}
                {{--<input type="hidden" name="countries_id" id="countries_id"--}}
                {{--value="{{old('countries_id',(isset($brandToEdit->country) ? $brandToEdit->country->id : ''))}}"/>--}}
                {{--<input type="hidden" name="countries_name" id="countries_name"--}}
                {{--value="{{old('countries_name',(isset($brandToEdit->country) ? $brandToEdit->country->name : ''))}}"/>--}}
                {{--</div>--}}
                <div id="places-selectors"></div>
            </div>
        </div>

        <h5 class="">{{__('company.socialNetworks')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.fbUrl')}}" type="text" class="input" name="facebook"
                               id="facebook"
                               value="{{old('facebook',(isset($brandToEdit) ? $brandToEdit->facebook : ''))}}">
                        <label for="facebook">{{__('company.facebook')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.ytUrl')}}" type="text" class="input" name="youtube" id="youtube"
                               value="{{old('youtube',(isset($brandToEdit) ? $brandToEdit->youtube : ''))}}">
                        <label for="youtube">{{__('company.youtube')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.instaUrl')}}" type="text" class="input" name="instagram"
                               id="instagram"
                               value="{{old('instagram',(isset($brandToEdit) ? $brandToEdit->instagram : ''))}}">
                        <label for="instagram">{{__('company.instagram')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.twitUrl')}}" type="text" class="input" name="twitter"
                               id="twitter"
                               value="{{old('twitter',(isset($brandToEdit) ? $brandToEdit->twitter : ''))}}">
                        <label for="twitter">{{__('company.twitter')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.pgUrl')}}" type="text" class="input" name="google_plus"
                               id="google_plus"
                               value="{{old('google_plus',(isset($brandToEdit) ? $brandToEdit->google_plus : ''))}}">
                        <label for="google_plus">{{__('company.google_plus')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.snapUrl')}}" type="text" class="input" name="snapchat"
                               id="snapchat"
                               value="{{old('snapchat',(isset($brandToEdit) ? $brandToEdit->snapchat : ''))}}">
                        <label for="snapchat">{{__('company.snapchat')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.spotUrl')}}" type="text" class="input" name="spotify"
                               id="spotify"
                               value="{{old('spotify',(isset($brandToEdit) ? $brandToEdit->spotify : ''))}}">
                        <label for="spotify">{{__('company.spotify')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.tumUrl')}}" type="text" class="input" name="tumblr" id="tumblr"
                               value="{{old('tumblr',(isset($brandToEdit) ? $brandToEdit->tumblr : ''))}}">
                        <label for="tumblr">{{__('company.tumblr')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.linkUrl')}}" type="text" class="input" name="linkedin"
                               id="linkedin"
                               value="{{old('linkedin',(isset($brandToEdit) ? $brandToEdit->linkedin : ''))}}">
                        <label for="linkedin">{{__('company.linkedin')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input placeholder="{{__('redes.pintUrl')}}" type="text" class="input" name="pinterest"
                               id="pinterest"
                               value="{{old('pinterest',(isset($brandToEdit) ? $brandToEdit->pinterest : ''))}}">
                        <label for="pinterest">{{__('company.pinterest')}}</label>
                    </div>
                </div>
            </div>

        </div>

        <h5 class="">{{__('company.WForze')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l0">
                    <div class="switch">
                        <label>
                            {{__('company.Off')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('waiver_forze','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($brandToEdit) && $brandToEdit->isForze() ? 'checked' : '' !!}
                                   @endif
                                   name="waiver_forze">
                            <span class="lever"></span>
                            {{__('company.On')}}
                        </label>
                    </div>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="col s12 m12 l0">
                    <div class="{{$isSaas ? 'input-field text-area' : ''}}">
                        <label for="waiver_text"  class="active">{{__('company.TForze')}}</label>
                        <textarea style="width: 95%;" name="waiver_text" id="waiver_text"
                                  class="materialize-textarea">{{old('waiver_text',($brandToEdit->waiver_text ?? ''))}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">GAFA PAY</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                @if(isset($brandToEdit) && ($brandToEdit->gafapay_client_id != null || $brandToEdit->gafapay_client_id !== ""))
                    <div class="col s12 m6 l12">
                        <div class="switch">
                            <label>
                                Generate new key
                                <input type="checkbox"
                                       name="regeneratesecret">
                                <span class="lever"></span>
                            </label>
                        </div>
                    </div>
                @endif
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="name_from" class="input" name="gafapay_secret" readonly
                               value="{{old('gafapay_client_secret',(isset($brandToEdit) ? $brandToEdit->gafapay_client_secret : ''))}}">
                        <label for="name_from">Client Secret</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <input type="text" id="mail_from" class="input" name="gafapay_id" readonly
                               value="{{old('gafapay_client_id',(isset($brandToEdit) ? $brandToEdit->gafapay_client_id : ''))}}">
                        <label for="mail_from">Client ID</label>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($brandToEdit))
            <h5 class="">{{__('company.PaymentMethods')}}</h5>
            <div class="card-panel panelcombos">
                <div class="row">
                    <div class="col s12 ">
                        @foreach($payment_methods as $method)
                            @if($method->getPaymentSlug()==='srpago')
                            @continue
                            @endif
                            <div class="payment_method_checks">
                                <div class="card grey lighten-5">
                                    <div class="card-content">
                                        {!! $method->ActivationCheckboxes($brandToEdit) !!}
                                        {!! $method->ConfigurationInputs($brandToEdit) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <br>
            </div>
        @endif

        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::NOTIFICATION_EMAIL_VIEW))
            <h5 class="">{{__('company.EmailConfiguration')}}</h5>
            <div class="card-panel panelcombos">
                <div class="row">
                    <div class="col s12 m12 l6">
                        <div class="input-field">
                            <input type="text" id="name_from" class="input" name="name_from"
                                   value="{{old('name_from', (isset($brandToEdit) ? $brandToEdit->name_from : ''))}}">
                            <label for="name_from">{{__('company.NameFrom')}}</label>
                        </div>
                    </div>
                    <div class="col s12 m12 l3">
                        <div class="input-field">
                            <input type="text" id="mail_from" class="input" name="mail_from"
                                   value="{{old('mail_from', (isset($brandToEdit) ? $brandToEdit->mail_from : ''))}}">
                            <label for="mail_from">{{__('company.MailFrom')}}</label>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <h5 class="">{{__('company.simultaneousReservations')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="input-field">
                        <input type="number"
                               min="1"
                               step="1"
                               id="simultaneous_reservations"
                               class="input"
                               name="simultaneous_reservations"
                               value="{{old('simultaneous_reservations', (isset($brandToEdit) ? ($brandToEdit->simultaneous_reservations??1) : 1))}}"
                        >
                        <label
                            for="simultaneous_reservations">{{__('company.simultaneousReservations.label')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l12">
                    <label>{{__('company.enableGuestInfo')}}</label>
                    <div class="switch">
                        <label>
                            {{__('company.disabled')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('extra_fields.require_guest_info','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($brandToEdit) && array_get($brandToEdit->extra_fields,'require_guest_info')==='on' ? 'checked' : '' !!}
                                   @endif
                                   name="extra_fields[require_guest_info]">
                            <span class="lever"></span>
                            {{__('company.enabled')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">CSS</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="input-field">
                        <select style="width: 95%;" name="map_css" id="map_css">
                            <option
                                value="reservation-template.css"
                                {!! ($brandToEdit->map_css??'')==='reservation-template.css' ? 'selected' : '' !!}
                            >
                                Zuda
                            </option>
                            <option
                                value="reservation.css"
                                {!! ($brandToEdit->map_css??'')==='reservation.css' ? 'selected' : '' !!}
                            >
                                Original
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <h5 class="">{{__('company.Waitlist')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m6 l6">
                    <div class="switch">
                        <label>
                            {{__('company.InactiveF')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('waitlist','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($brandToEdit) && $brandToEdit->usesWaitlist() ? 'checked' : '' !!}
                                   @endif
                                   name="waitlist">
                            <span class="lever"></span>
                            {{__('company.ActiveF')}}
                        </label>
                    </div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="{{$isSaas ? 'input-field text-area' : ''}}">
                       <label for="explanation_waitlist" class="active">{{__('waitlist.ExplanationText')}}</label>
                       <textarea style="width: 95%;" name="explanation_waitlist" id="explanation_waitlist" class="materialize-textarea">{{old('explanation_waitlist',(isset($brandToEdit) ?$brandToEdit->explanation_waitlist : ''))}}</textarea>
                    </div>
                </div>

                <div class="col s12 m6 l6">
                    <div class="input-field">
                        <select style="width: 95%;" name="max_waitlist" id="max_waitlist">
                            <option
                                value="25"{!! isset($brandToEdit) && $brandToEdit->max_waitlist===25 ? 'selected' : '' !!}>
                                25%
                            </option>
                            <option
                                value="50"{!! isset($brandToEdit) && $brandToEdit->max_waitlist===50 ? 'selected' : '' !!}>
                                50%
                            </option>
                            <option
                                value="75"{!! isset($brandToEdit) && $brandToEdit->max_waitlist===75 ? 'selected' : '' !!}>
                                75%
                            </option>
                            <option
                                value="100"{!! isset($brandToEdit) && $brandToEdit->max_waitlist===100 ? 'selected' : '' !!}>
                                100%
                            </option>
                        </select>
                        <label for="max_waitlist" class="active">{{__('waitlist.MaxWaitlist')}}</label>
                    </div>
                </div>
            </div>
        </div>

        {{--        Visualización de clases sin disponibilidad--}}
{{--        <div class="col s12 m12 card-panel panelcombos">--}}
{{--            <div class="row">--}}
{{--                <h5 class="header">{{__('brand.Visualizations')}}</h5>--}}
{{--                <div class="col s12 m6 l6">--}}
{{--                    <div class="input-field">--}}
{{--                        <select style="width: 95%;" name="no_availability_display"--}}
{{--                                id="no_availability_display">--}}
{{--                            <option--}}
{{--                                value="default"{!! $brandToEdit->no_availability_display==='default' ? 'selected' : '' !!}>{{__('brand.visualization_default')}}</option>--}}
{{--                            <option--}}
{{--                                value="disable"{!! $brandToEdit->no_availability_display==='disable' ? 'selected' : '' !!}>{{__('brand.visualization_disable')}}</option>--}}
{{--                        </select>--}}
{{--                        <label--}}
{{--                            for="no_availability_display">{{__('brand.NoAvailabilityVisualization')}}</label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div style="min-height: 150px;"></div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div id="special-text-form"></div>
    </div>

    <div class="col s12 m4">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="file-field input-field">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{isset($brandToEdit) ? $brandToEdit->pic:''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('company.BrandLogo')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="pic"/>
                        </div>
                    </div>
                </div>

                <div class="file-field input-field">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{isset($brandToEdit) ?$brandToEdit->banner:''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('company.Banner')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="banner"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="switch">
                <label>
                    {{__('company.Inactive')}}
                    <input type="checkbox"
                           @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                           @else {!! isset($brandToEdit) && $brandToEdit->isActive() ? 'checked' : '' !!}
                           @endif
                           name="status">
                    <span class="lever"></span>
                    {{__('company.Active')}}
                </label>
            </div>
            @if(isset($brandToEdit) && !$brandToEdit->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('brand.ActiveWarning')}}</p>
                </div>
            @endif
        </div>


        <div class="row">
            <div class="col s12 m12 l7 edit-buttons">
                <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                    @if($isSaas)
                        <div>
                            <i class="fal fa-save"></i>
                            <span>{{__('company.Save')}}</span>
                        </div>
                    @else
                        <i class="material-icons right small">save</i>
                        {{__('company.Save')}}
                    @endif
                </button>
            </div>
        </div>

        @if (isset($brandToEdit))
            <div class="row">
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::BRANDS_DELETE,$company))
                        <a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}" href="#eliminar_b" style="{{$isSaas ? '' : 'background-color: grey'}}">
                           @if($isSaas)
                              <i class="far fa-times"></i>
                              <span>{{__('company.Delete')}}</span>
                           @else
                              <i class="material-icons right small">clear</i>
                              {{__('company.Delete')}}
                           @endif
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="clear"></div>

</form>
@if (isset($brandToEdit))
    <div id="eliminar_b" class="modal modal-fixed-footer modaldelete" data-method="get"
         data-href="{{route('admin.company.brands.delete', ['company'=>$company,'brandToEdit' => $brandToEdit->id])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <button type="submit"
                    class="s12 modal-action modal-close waves-effect waves-green btn edit-button model-delete-button"
                    data-name="brand">
                <i class="material-icons small mat">done</i>
                {{__('gafacompany.Delete')}}
            </button>
        </div>
    </div>
@endif



@section('jsPostApp')
    @parent
    <script>
        $(document).ready(function () {
            $('.emailgafa select').material_select();
            $('.select2').select2();
        });
        (function () {
            var inputTimeZone = $('#time_zone');
            if (inputTimeZone.val() === '') {
                inputTimeZone.val(window.guest_timezone);
            }
        })()
    </script>
@endsection
