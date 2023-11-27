@if ( \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::SETTINGS_EDIT))
    <?php
    $checked = old('cloudflare[enabled]', $settings->get('cloudflare.enabled') !== '');
    ?>
    <form action="{{route('admin.settings.save')}}" class="row" method="post" autocomplete="off"
          enctype="multipart/form-data">
        {{csrf_field()}}
        <h5 class="">{{__('settings.Cloudflare')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">

                <div class="input-field">
                    <input type="checkbox" id="cloudflare[enabled]"
                           name="cloudflare[enabled]" {!! $checked ? 'checked' : '' !!}>
                    <label for="cloudflare[enabled]">{{__('settings.Enabled')}}</label>
                </div>
                <br>

                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[base-uri]" name="cloudflare[base-uri]"
                               value="{{old('cloudflare')['base-uri'] ?? ($settings->get('cloudflare.base-uri') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[base-uri]">{{__('settings.AuthUrl')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[x-auth-key]" name="cloudflare[x-auth-key]"
                               value="{{old('cloudflare')['x-auth-key'] ?? ($settings->get('cloudflare.x-auth-key') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[x-auth-key]">{{__('settings.AuthKey')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[x-auth-email]" name="cloudflare[x-auth-email]"
                               value="{{old('cloudflare')['x-auth-email'] ?? ($settings->get('cloudflare.x-auth-email') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[x-auth-email]">{{__('settings.AuthEmail')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[zone-id]" name="cloudflare[zone-id]"
                               value="{{old('cloudflare')['zone-id'] ?? ($settings->get('cloudflare.zone-id') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[zone-id]">{{__('settings.AuthZoneId')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[ip]" name="cloudflare[ip]"
                               value="{{old('cloudflare')['ip'] ?? ($settings->get('cloudflare.ip') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[ip]">{{__('settings.AuthIp')}}</label>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">{{__('settings.Cloudflare')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">

                <div class="input-field">
                    <input type="checkbox" id="gympass[active]"
                           name="gympass[active]" {!! $checked ? 'checked' : '' !!}>
                    <label for="gympass[active]">{{__('settings.Enabled')}}</label>
                </div>
                <br>

                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[base-uri]" name="cloudflare[base-uri]"
                               value="{{old('cloudflare')['base-uri'] ?? ($settings->get('cloudflare.base-uri') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[base-uri]">{{__('settings.AuthUrl')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[x-auth-key]" name="cloudflare[x-auth-key]"
                               value="{{old('cloudflare')['x-auth-key'] ?? ($settings->get('cloudflare.x-auth-key') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[x-auth-key]">{{__('settings.AuthKey')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[x-auth-email]" name="cloudflare[x-auth-email]"
                               value="{{old('cloudflare')['x-auth-email'] ?? ($settings->get('cloudflare.x-auth-email') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[x-auth-email]">{{__('settings.AuthEmail')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[zone-id]" name="cloudflare[zone-id]"
                               value="{{old('cloudflare')['zone-id'] ?? ($settings->get('cloudflare.zone-id') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[zone-id]">{{__('settings.AuthZoneId')}}</label>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="cloudflare[ip]" name="cloudflare[ip]"
                               value="{{old('cloudflare')['ip'] ?? ($settings->get('cloudflare.ip') ?? '')}}"
                               class="cloudflare-settings">
                        <label for="cloudflare[ip]">{{__('settings.AuthIp')}}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <h5 class="">{{__('settings.Pictures')}}</h5>
            <div class="card-panel panelcombos">
                <div class="col s12 m6 gafafit-settings-image">
                    <div class="row">
                        <div class="file-field input-field">
                            <div class="">
                                <div class="uploadPhoto">
                                    <img src="{{$settings->get('pic')}}" height="180px"
                                         alt=""
                                         class="responsive-img uploadPhoto--image"/><br>
                                    <h5 class="header"><i
                                            class="material-icons small">add_a_photo</i> {{__('settings.Logo')}}</h5>
                                    <input type='file' class="uploadPhoto--input" name="pic"/>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col s12 m6 gafafit-settings-image">
                    <div class="row">
                        <div class="file-field input-field">
                            <div class="">
                                <div class="uploadPhoto">
                                    <img src="{{$settings->get('icon')}}" height="75px"
                                         alt=""
                                         class="responsive-img uploadPhoto--image"/> <br>
                                    <h5 class="header"><i
                                            class="material-icons small">add_a_photo</i>{{__('settings.Icon')}}</h5>
                                    <input type='file' class="uploadPhoto--input" name="icon"/>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <br>
            </div>
        </div>

        <button type="submit" class="waves-effect waves-light btn right" style="top: 15px;"><i
                class="material-icons right small">save</i>{{__('settings.Save')}}
        </button>
    </form>
@endif
