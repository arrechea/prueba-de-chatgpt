<h5 class="header">{{__('gafacompany.RecordCompanyData')}}</h5>
<form method="post" action="{{$urlForm}}" class="row" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($compToEdit))
        <input type="hidden" name="id" value="{{$compToEdit->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <h5 class="">{{__('gafacompany.GeneralData')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="name" class="input" name="name"
                               value="{{old('name',(isset($compToEdit) ? $compToEdit->name : ''))}}" required>
                        <label for="name">{{__('gafacompany.Name')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="email" class="input" name="email"
                               value="{{old('email', (isset($compToEdit) ? $compToEdit->email : ''))}}">
                        <label for="email">{{__('gafacompany.Email')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <select class="col s12" name="language_id" id="language_id">
                            @foreach(($languages ?? []) as $language)
                                <option
                                    value="{{$language->id}}"
                                    @if(old('language_id', (isset($compToEdit) ? $compToEdit->language_id : '')) == $language->id) selected="selected"@endif>{{__($language->name)}}</option>
                            @endforeach
                        </select>
                        <label for="language_id">{{__('common.Language')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="copyright" class="input" name="copyright"
                               value="{{old('copyright', (isset($compToEdit) ? $compToEdit->copyright : ''))}}">
                        <label for="copyright">{{__('company.copyrightText')}}</label>
                    </div>
                </div>
            </div>
        </div>
        <h5 class="">{{__('gafacompany.Address')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="address" class="input" name="address"
                               value="{{old('address',(isset($compToEdit) ? $compToEdit->address : ''))}}">
                        <label for="address">{{__('gafacompany.Street')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <input type="text" id="external_number" class="input" name="external_number"
                               value="{{old('external_number',(isset($compToEdit) ? $compToEdit->external_number : ''))}}">
                        <label
                            for="external_number"> {{__('gafacompany.ExteriorNumber')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="municipality" class="input" name="municipality"
                               value="{{old('municipality',(isset($compToEdit) ? $compToEdit->municipality : ''))}}">
                        <label for="municipality"> {{__('gafacompany.Suburb')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <input type="text" id="postal_code" class="input" name="postal_code"
                               value="{{old('postal_code',(isset($compToEdit) ? $compToEdit->postal_code : ''))}}">
                        <label for="postal_code"> {{__('gafacompany.Postcode')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                {{--<div class="col s12 m12 l3">--}}
                {{--<div class="input-field">--}}
                {{--<input type="text" id="city" class="input" name="city"--}}
                {{--value="{{old('city',(isset($compToEdit) ? $compToEdit->city : ''))}}">--}}
                {{--<label for="city"> {{__('gafacompany.District')}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m12 l3">--}}
                {{--<div class="input-field ">--}}
                {{--<input type="text" id="state" class="input" name="state"--}}
                {{--value="{{old('state',(isset($compToEdit) ? $compToEdit->state : ''))}}">--}}
                {{--<label for="state">{{__('gafacompany.State')}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m12 l3">--}}
                {{--<label for="country">--}}
                {{--{{__('administrators.Country')}}--}}
                {{--<select class="select2 select" id="country"--}}
                {{--style="width: 100%;"--}}
                {{--data-url="{{route('admin.administrator.countries')}}" data-name="countries">--}}
                {{--@if(!empty(old('countries_id','')))--}}
                {{--<option value="{{old('countries_id')}}"--}}
                {{--selected="selected">{{old('countries_name')}}</option>--}}
                {{--@elseif(isset($compToEdit->country))--}}
                {{--<option value="{{$compToEdit->country->id}}"--}}
                {{--selected="selected">{{$compToEdit->country->name}}</option>--}}
                {{--@endif--}}
                {{--</select>--}}
                {{--</label>--}}
                {{--<input type="hidden" name="countries_id" id="countries_id"--}}
                {{--value="{{old('countries_id',(isset($compToEdit) ? $compToEdit->countries_id : ''))}}">--}}
                {{--<input type="hidden" name="countries_name" id="countries_name"--}}
                {{--value="{{old('countries_name',(isset($compToEdit) ? ($compToEdit->country!==null ? $compToEdit->country->name : '') : ''))}}">--}}
                {{--</div>--}}
                <div id="places-selectors" class="col s12 m12 l11"></div>
            </div>
        </div>

        <h5>{{__('gafacompany.Contact')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <label class="col s12 m12 l6">
                    <select class="select select2" style="width: 100%" data-name="admins"
                            data-url="{{route('admin.companyEdit.admins')}}">
                        @if(old('admins_id'))
                            <option value="{{old('admins_id')}}">{{old('admins_name')}}</option>
                        @elseif(isset($compToEdit) && $compToEdit->contact!==null)
                            <option value="{{$compToEdit->admins_id}}">{{$compToEdit->contact->email}}</option>
                        @endif
                    </select>
                </label>
                <input type="hidden" id="admins_id" name="admins_id"
                       value="{{old('admins_id',(isset($compToEdit) ? $compToEdit->admins_id : ''))}}">
                <input type="hidden" name="admins_name" id="admins_name"
                       value="{{old('admins_name',(isset($compToEdit) ? ($compToEdit->contact!==null ? $compToEdit->contact->name : '') : ''))}}">
            </div>
        </div>

        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::NOTIFICATION_EMAIL_VIEW))
            <h5 class="">{{__('company.EmailConfiguration')}}</h5>
            <div class="card-panel panelcombos">
                <div class="row">
                    <div class="col s12 m12 l6">
                        <div class="input-field">
                            <input type="text" id="name_from" class="input" name="name_from"
                                   value="{{old('name_from', (isset($compToEdit) ? $compToEdit->name_from : ''))}}">
                            <label for="name_from">{{__('company.NameFrom')}}</label>
                        </div>
                    </div>
                    <div class="col s12 m12 l3">
                        <div class="input-field">
                            <input type="text" id="mail_from" class="input" name="mail_from"
                                   value="{{old('mail_from', (isset($compToEdit) ? $compToEdit->mail_from : ''))}}">
                            <label for="mail_from">{{__('company.MailFrom')}}</label>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <h5>{{__('gafacompany.MailchimpConfiguration')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="mailchimp_apikey" class="input" name="mailchimp_apikey"
                               value="{{old('mailchimp_apikey', (isset($compToEdit) ? $compToEdit->mailchimp_apikey : ''))}}">
                        <label for="mailchimp_apikey">{{__('company.ApiKey')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <input type="text" id="mailchimp_list_id" class="input" name="mailchimp_list_id"
                               value="{{old('mailchimp_list_id', (isset($compToEdit) ? $compToEdit->mailchimp_list_id : ''))}}">
                        <label for="mailchimp_list_id">{{__('company.ListId')}}</label>
                    </div>
                </div>
            </div>
        </div>

        @php
            $edit_permission=\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_SETTINGS_EDIT);
        @endphp
        @if( \App\Librerias\Gympass\Helpers\GympassHelpers::isGympassActive() &&
            \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_SETTINGS_VIEW))

            <h5>{{__('gympass.gympassSettings')}}</h5>
            <div class="card-panel panelcombos" style="margin-bottom: 150px">
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
                                           {!! isset($compToEdit) && $compToEdit->isMarkedGympassActive() ? 'checked' : '' !!}
                                       @endif
                                       name="gympass_active">
                                <span class="lever"></span>
                                {{__('gafacompany.Active')}}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="col s12 m4">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="file-field input-field">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{old('pic',$compToEdit->pic??'')}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('gafacompany.CompanyPhoto')}}
                            </h5>
                            <input type='file' class="uploadPhoto--input" name="pic"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="switch">
                <label>
                    {{__('gafacompany.Inactive')}}
                    <input type="checkbox"
                           @if(!!old())
                               {!! old('status','')==='on' ? 'checked' : '' !!}
                           @else
                               {!! isset($compToEdit) && $compToEdit->isActive() ? 'checked' : '' !!}
                           @endif
                           name="status">
                    <span class="lever"></span>
                    {{__('gafacompany.Active')}}
                </label>
            </div>
            @if(isset($compToEdit) && !$compToEdit->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('gafacompany.ActiveWarning')}}</p>
                </div>
            @endif
        </div>

        <div class="row">
            @if (isset($compToEdit))
                {{--<div class="col s4 m12">--}}
                {{--<button type="submit" class="waves-effect waves-light btn deep-purple lighten-4"--}}
                {{--style=" top: 16px;">{{__('gafacompany.Accounts')}}</button>--}}
                {{--</div>--}}
            @endif
            <div class="col s12 m12 l7 edit-buttons">
                <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                        class="material-icons right small">save</i>{{__('gafacompany.Save')}}</button>
            </div>
        </div>
        <div class="row">
            @if (isset($compToEdit))
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_DELETE))
                        <a class="waves-effect waves-light btn btnguardar" href="#eliminar_c"
                           style="background-color: grey"><i
                                class="material-icons right small">clear</i>{{__('gafacompany.Delete')}}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</form>
@if (isset($compToEdit))
    <div id="eliminar_c" class="modal modal-fixed-footer modaldelete" data-method="get"
         data-href="{{route('admin.companyEdit.delete', ['companyToEdit' => $compToEdit->id,])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <button type="submit" data-name="company"
                    class="s12 modal-action modal-close waves-effect waves-green btn btndelete edit-button model-delete-button">
                <i class="material-icons small">done</i>
                {{__('gafacompany.Delete')}}
            </button>
        </div>
    </div>
@endif




