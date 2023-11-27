<h5 class="header card-title">{{__('gafacompany.TitleEdit')}}</h5>
<form method="post" action="{{$urlForm}}" class="row" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($compToEdit))
        <input type="hidden" name="id" value="{{$compToEdit->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <h5 class="">{{__('gafacompany.General')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" id="name" class="input" name="name"
                               value="{{old('name',(isset($compToEdit) ? $compToEdit->name : ''))}}" required>
                        <label for="name">{{__('gafacompany.Name')}}</label>
                        <span class="material-icons">chess_pawn</span>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field">
                        <input type="text" id="email" class="input" name="email"
                               value="{{old('email', (isset($compToEdit) ? $compToEdit->email : ''))}}">
                        <label for="email">{{__('gafacompany.Email')}}</label>
                    </div>
                    {{-- seleccionador de paquete para fase 2 --}}
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <select class="col s12" name="language_id" id="language_id">
                            @foreach(($languages ?? []) as $language)
                                <option
                                        value="{{$language->id}}" {!! old('language_id',$language->id)===$compToEdit->language_id ? 'selected' : '' !!}>{{__($language->name)}}</option>
                            @endforeach
                        </select>
                        <label for="language_id">{{__('common.Language')}}</label>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">{{__('gafacompany.Address')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="address" class="input" name="address"
                               value="{{old('address',(isset($compToEdit) ? $compToEdit->address : ''))}}">
                        <label for="address">{{__('gafacompany.Street')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="external_number" class="input" name="external_number"
                               value="{{old('external_number',(isset($compToEdit) ? $compToEdit->external_number : ''))}}">
                        <label for="external_number">{{__('gafacompany.ExteriorNumber')}}</label>
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
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" id="postal_code" class="input" name="postal_code"
                               value="{{old('postal_code',(isset($compToEdit) ? $compToEdit->postal_code : ''))}}">
                        <label for="postal_code"> {{__('gafacompany.Postcode')}}</label>
                    </div>
                </div>
            </div>
            <div id="places-selectors" class="col s12 m12 l11"></div>
        </div>

        <h5 class="">{{__('gafacompany.Client')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div id="client-secret-key"></div>
        </div>

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

        <h5>{{__('gafacompany.WebhooksConfiguration')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                @if(count($compToEdit->webhooks)>0)
                    @foreach(($compToEdit->webhooks ?? []) as $webhook_def)
                        <div class="col s12 m12 l12">
                            <div class="input-field">
                                <input type="text" id="webhook_{{ $loop->index }}" class="input" name="webhook[]"
                                       value="{{old('webhook.'.$loop->index, (isset($webhook_def->webhook) ? $webhook_def->webhook : ''))}}">
                                <label for="webhook_{{ $loop->index }}">{{__('company.Webhook')}}</label>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <input type="text" id="webhook_0" class="input" name="webhook[]"
                                   value="{{ old('webhook.0') }}">
                            <label for="webhook_0">{{__('company.Webhook')}}</label>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{--<div class="col s12 m12 card-panel panelcombos">--}}
        {{--<h5 class="header">{{__('gafacompany.Colors')}}</h5>--}}
        {{--<div class="row">--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" class="color txt--color" id="black" name="black" value="#000000"/>--}}
        {{--<label class="colors--label" for="black">{{__('colors.black')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#323232" class="color txt--color" id="main" name="main"/>--}}
        {{--<label class="colors--label" for="main">{{__('colors.main')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#626262" class="color txt--color" id="secondary" name="secondary"/>--}}
        {{--<label class="colors--label" for="secondary">{{__('colors.secondary')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#929292" class="color txt--color" id="secondary2" name="secondary2"/>--}}
        {{--<label class="colors--label" for="secondary2">{{__('colors.secondary2')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#C2C2C2" class="color txt--color" id="secondary3" name="secondary3"/>--}}
        {{--<label class="colors--label" for="secondary3">{{__('colors.secondary3')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#C2C2C2" class="color txt--color" id="light" name="light"/>--}}
        {{--<label class="colors--label" for="light">{{__('colors.light')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#323232" class="color txt--color" id="menutop" name="menutop"/>--}}
        {{--<label class="colors--label" for="menutop">{{__('colors.menutop')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#323232" class="color txt--color" id="menuleft" name="menuleft"/>--}}
        {{--<label class="colors--label" for="menuleft">{{__('colors.menuleft')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#323232" class="color txt--color" id="menuleft_secondary"--}}
        {{--name="menuleft_secondary"/>--}}
        {{--<label class="colors--label" for="menuleft_secondary">{{__('colors.menuSecondary')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#626262" class="color txt--color" id="menuleft_selected"--}}
        {{--name="menuleft_selected"/>--}}
        {{--<label class="colors--label" for="menuleft_selected">{{__('colors.menuSelected')}}</label>--}}
        {{--</div>--}}
        {{--<div class="input-field col s12 m4 l4">--}}
        {{--<input type="text" value="#FF0000" class="color txt--color" id="alert" name="alert"/>--}}
        {{--<label class="colors--label" for="alert">{{__('colors.alert')}}</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>

    <div class="col s12 m4">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$compToEdit->pic??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                            class="material-icons small">add_a_photo</i> {{__('company.ProfilePicture')}}
                                </h5>
                                <input type='file' class="uploadPhoto--input" name="pic"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            <div class="col s12 m12 l7 edit-buttons">
                <a href="{{route('admin.company.settings.colors.create',[ 'company' => $company])}}"
                   class="waves-effect waves-light btn btnguardar"><i
                            class="material-icons right small">brush</i>{{__('gafacompany.Colors')}}</a>
            </div>
            @if (isset($compToEdit))
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_DELETE,$compToEdit))
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
         data-href="{{route('admin.company.settings.delete',['company'=>$company])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer" href="#">
                <i class="material-icons small">clear</i>
                Cancelar
            </a>
            <button
                    class="modal-action modal-close waves-effect waves-green btn btndelete edit-button model-delete-button"
                    data-name="settings">
                <i class="material-icons small">done</i>
                {{__('gafacompany.Delete')}}
            </button>
        </div>
    </div>
@endif
